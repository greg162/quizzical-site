<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

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
        $fileName     = $request->uuid.'.'.$extension;
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
        $filePath = 'quiz-images/'.$fileName;
        Storage::disk('Wasabi')->put($filePath, fopen($tempFilePath, 'r+'));
        $fileUrl = Storage::disk('Wasabi')->url('quiz-images/'.$fileName);
        $this->uuid           = $request->uuid;
        $this->user_id        = ($user->id)? $user->id: 0 ;
        $this->file_name      = $fileName;
        $this->file_path      = $filePath;
        $this->file_url       = $fileUrl;
        $this->storage_engine = 'Wasabi';
        $this->table_name     = $tableName;
        $this->table_id       = $tableId;
    }

    function deleteFile() {
        Storage::disk('Wasabi')->delete($this->file_path);
    }

}
