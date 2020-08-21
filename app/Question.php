<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use HTMLPurifier, HTMLPurifier_Config;

class Question extends Model
{
    //
    protected $fillable = ['type', 'question', 'answer_1', 'answer_2', 'answer_3', 'answer_4', 'answer_5', 'answer_6', 'correct_answer'];

    static function validateQuestion($questionData, $questionNo) {
        $errors = "";

        if(empty($questionData['question'])) { $errors .= "you must enter a question for question $questionNo\n"; }
        
        //Validate the question type
        if(empty($questionData['questionType']))                                      { $errors .= "You must select a question type for question #$questionNo\n"; }
        elseif(!in_array($questionData['questionType'], [
            'text',
            'multiple-choice',
            'embed',
            'upload',
        ])) { $errors .= "You have not selected a valid question type for question #$questionNo\n"; }
        elseif($questionData['questionType'] === 'multiple-choice') {
            if(empty($questionData['answer_1']))       { $errors .= "You must something into answer 1 for question  #$questionNo\n"; }
            if(empty($questionData['answer_2']))       { $errors .= "You must something into answer 2 for question  #$questionNo\n"; }
            if(empty($questionData['correct_answer'])) { $errors .= "You must select a correct answer for question  #$questionNo\n"; }
        }elseif($questionData['questionType'] === 'text') {

        }elseif($questionData['questionType'] === 'embed') {
            if(empty($questionData['answer_1']))       { $errors .= "You must enter some embed code for  #$questionNo\n"; }
            if(empty($questionData['correct_answer'])) { $errors .= "You must select a correct answer for question  #$questionNo\n"; }
        } elseif($questionData['questionType'] === 'upload') {
            if( !empty($questionData['uuid']) && !Upload::where('uuid', $questionData['uuid'])->count() ) {
                $errors .= "You must upload something for question #$questionNo\n"; 
            } elseif(!empty($questionData['id']) && !Upload::where('table_id', $questionData['id'])->count()) {
                $errors .= "You must upload something for question #$questionNo\n"; 
            } elseif(empty($questionData['id']) && empty($questionData['uuid'])) {
                $errors .= "No upload ID was found!\n";
            }
        } else {
            $errors .= "We could not find the question type that you uploaded for question #$questionNo. Please try again later.\n";
        }

        return $errors;
    }

    function cleanQuestionData() {
        $config = HTMLPurifier_Config::createDefault();
        //allow iframes from trusted sources
        $config->set('HTML.SafeIframe', true);
        $config->set('HTML.Allowed', 'iframe[style|src|height|width|class]');
        $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/||giphy\.com/embed/)%'); //allow YouTube and Vimeo and giphy
        $purifier = new HTMLPurifier($config);
        if($this->type == 'embed') {
            $this->answer_2 = $purifier->purify($this->answer_2);
        }
    }
}
