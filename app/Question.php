<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $fillable = ['type', 'question', 'answer_1', 'answer_2', 'answer_3', 'answer_4', 'answer_5', 'answer_6', 'correct_answer'];


    static function validateQuestion($questionData, $questionNo) {
        $errors = "";

        if(empty($questionData['question'])) { $errors .= "you must enter a question for question $questionNo\n"; }
        
        //Validate the question type
        if(empty($questionData['questionType']))                                      { $errors .= "You must select a question type for question #$questionNo\n"; }
        elseif(!in_array($questionData['questionType'], ['text', 'multiple-choice'])) { $errors .= "You have not selected a valid question type for question #$questionNo\n"; }
        elseif($questionData['questionType'] === 'multiple-choice') {
            if(empty($questionData['answer_1']))       { $errors .= "You must something into answer 1 for question  #$questionNo\n"; }
            if(empty($questionData['answer_1']))       { $errors .= "You must something into answer 2 for question  #$questionNo\n"; }
            if(empty($questionData['correct_answer'])) { $errors .= "You must select a correct answer for question  #$questionNo\n"; }
        }

        return $errors;
    }
}
