<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Upload extends Model
{
    //
    
    static function createValidationRules() {
        return [
            'uuid' => 'uuid',
            'id'   => 'integer',
            'file' => 'required|file|image|max:5000',
        ];
    }

    static function deleteValidationRules() {
        return [
            'uuid' => 'uuid',
            'id'   => 'integer',
        ];
    }

    function generateFromRequest(Request $request, $tableName, $tableId, $user) {
        $extension    = $request->file->extension();
        $uuid         = (!empty($request->uuid))? $request->uuid : Str::uuid() ;
        $fileName     = $uuid.'.'.$extension;
        $filePath     = 'quiz-images/'.$fileName;

        $tempFilePath = storage_path().'/app/'.$request->file->storeAs('temp_question_images', $fileName, 'local');
        // create an image manager instance with favored driver
        $manager = new ImageManager(array('driver' => 'GD'));
        $image   = $manager->make($tempFilePath);
        $w = $image->width();
        $h = $image->height();
        if($h > 1000 || $w > 1000) {
            if($w > $h) {
                $image->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $image->resize(null, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }
        $image->save();
        Storage::disk('public')->put($filePath, fopen($tempFilePath, 'r+'));
        $fileUrl = Storage::disk('public')->url($filePath);
        $this->uuid           = $uuid;
        $this->user_id        = ($user->id)? $user->id: 0 ;
        $this->file_name      = $fileName;
        $this->file_path      = $filePath;
        $this->file_url       = $fileUrl;
        $this->storage_engine = 'public';
        $this->table_name     = $tableName;
        $this->table_id       = $tableId;
        $this->file_size      = $image->filesize();
    }

    static function assignUploads($tableName, $user, $uuid, $tableId) {
        Upload::where('uuid', $uuid)->where('table_name', $tableName)->where('table_id', 0)->where('user_id', $user->id)->update(['table_id' => $tableId]);
    }

    function deleteFile() {
        Storage::disk($this->storage_engine)->delete($this->file_path);
    }

}
