<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use HTMLPurifier, HTMLPurifier_Config;
use App\User;
class Question extends Model
{
    //
    protected $fillable = ['type', 'question', 'answer_1', 'answer_2', 'answer_3', 'answer_4', 'answer_5', 'answer_6', 'correct_answer'];

    /*
        This function is used to check that a question is valid
        $questionData = The raw json data from the request that contains the details of the question.
        $questionNo   = The question number. If the question was the third in the list of questions, the number would be 3.
        $errors       = A string containing the errors associated with the question.
    */
    static function validateQuestion($questionData, $questionNo, User $user) {
        $errors = "";

        if(empty($questionData['question'])) { $errors .= "you must enter a question for question $questionNo\n"; }
        
        //Validate the question type
        if(empty($questionData['questionType']))                                      { $errors .= "You must select a question type for question #$questionNo\n"; }
        elseif(!in_array($questionData['questionType'], [
            'text',
            'multiple-choice',
            'embed',
            'upload',
            'divider',
        ])) { $errors .= "You have not selected a valid question type for question #$questionNo\n"; }
        elseif($questionData['questionType'] === 'multiple-choice') {
            if(empty($questionData['answer_1']))       { $errors .= "You must something into answer 1 for question  #$questionNo\n"; }
            if(empty($questionData['answer_2']))       { $errors .= "You must something into answer 2 for question  #$questionNo\n"; }
            if(empty($questionData['correct_answer'])) { $errors .= "You must select a correct answer for question  #$questionNo\n"; }
        }elseif($questionData['questionType'] === 'text') { //No extra validation is required for a text question
        }elseif($questionData['questionType'] === 'divider') { // A Divider does not have a question type
        }elseif($questionData['questionType'] === 'embed') {
            if(empty($questionData['answer_1']))       { $errors .= "You must enter some embed code for  #$questionNo\n"; }
            if(empty($questionData['correct_answer'])) { $errors .= "You must select a correct answer for question  #$questionNo\n"; }
        } elseif($questionData['questionType'] === 'upload') {
            if( !empty($questionData['upload']['uuid']) && Upload::where('uuid', $questionData['upload']['uuid'])->where('user_id', $user->id)->count() <= 0 ) {
                $errors .= "You must upload something for question #$questionNo\n"; 
            } elseif(!empty($questionData['id']) && !Upload::where('table_id', $questionData['id'])->where('user_id', $user->id)->count()) {
                $errors .= "You must upload something for question #$questionNo\n"; 
            } elseif(empty($questionData['id']) && empty($questionData['uuid'])) {
                $errors .= "No upload ID was found!\n";
            }
        } else {
            $errors .= "We could not find the question type that you uploaded for question #$questionNo. Please try again later.\n";
        }

        return $errors;
    }

    /*
        This function uses the HTML purifier to ensure that an iframe entered by a user does not contain any malicious HTML. This is done by only allowing certain iframe sources and removing any unrequired tags.
     */
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
