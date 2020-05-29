<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    //
    protected $table = 'quizzes';
    protected $fillable = ['name', 'description'];



    static function createValidationRules() {
        return [
            'title'       => 'required|max:255',
            'password'    => 'required|min:6|max:30|regex:/\d/i',
            'description' => 'max:1000',
            'questions'   => 'array|required',
        ];
    }

    static function updateValidationRules() {
        return [
            'title'       => 'required|max:255',
            'password'    => 'min:6|max:30|regex:/\d/i',
            'description' => 'max:1000',
            'questions'   => 'array|required',
        ];
    }


    static function encryptQuizPassword($password) {
        $options = [
            'cost' => 10,
        ];
        return $password = password_hash($password, PASSWORD_BCRYPT, $options);
    }
}
