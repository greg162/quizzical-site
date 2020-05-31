<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Quiz;
use App\Question;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user        = Auth::user();
        $userQuizzes = Quiz::where('user_id', $user->id)->paginate(10);
        return view('quizzes.list', ['userQuizzes' => $userQuizzes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = Auth::user();
        return view('quizzes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //Get the user
        $user = Auth::user();

        //Carry out the basic validation on the quiz
        $validator = Validator::make($request->all(), Quiz::createValidationRules() );

        //If validation fails, return the errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage = "";
            foreach ($errors->all() as $error) { $errorMessage .= $error."\n"; }
            return response()->json(['errors' => $errorMessage ],202);
        } else {
            //Validate each question
            $errorMessage = "";
            if(!empty($request->questions)) {
                foreach( $request->questions as $key => $question ) {

                    //Carry out question validation
                    $questionNo = $key + 1;
                    $errorMessage .= Question::validateQuestion($question, $questionNo);
                }
                if($errorMessage) { return response()->json(['errors' => $errorMessage ],202); }

                //Validation complete! Save the answers to the DB.
                $quiz = new Quiz;
                $quiz->name        = $request->title;
                if(!empty($request->password)) {
                    $quiz->password = Quiz::encryptQuizPassword($request->password);
                }
                $quiz->description = $request->description ?? '';
                $quiz->user_id     = $user->id;
                $quiz->save();
                foreach($request->questions as $key => $requestQuestion) {
                    $question = new Question;
                    $question->question       = $requestQuestion['question'];
                    $question->answer_1       = $requestQuestion['answer_1'] ?? '';
                    $question->answer_2       = $requestQuestion['answer_2'] ?? '';
                    $question->answer_3       = $requestQuestion['answer_3'] ?? '';
                    $question->answer_4       = $requestQuestion['answer_4'] ?? '';
                    $question->answer_5       = $requestQuestion['answer_5'] ?? '';
                    $question->answer_6       = $requestQuestion['answer_6'] ?? '';
                    $question->type           = $requestQuestion['questionType'] ?? '';
                    $question->correct_answer = $requestQuestion['correct_answer'] ?? '';
                    $question->quiz_id = $quiz->id;
                    $question->user_id = $user->id;
                    $question->save();
                }
                return ['success' => "Questionnaire successfully created!\n", 'errors' => '' ];
            } else {
                return response()->json(['errors' => 'You must add at least one question.' ],202);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user      = Auth::user();
        $quiz      = Quiz::where('id', $id)->where('user_id', $user->id)->first();
        if(!$quiz) { abort(404); }
        $questions = Question::where('quiz_id', $quiz->id)->get();
        foreach($questions as $question) {
            $question->questionType = $question->type;
        }
        return response()->json(['quiz' => $quiz->toArray(), 'questions' => $questions->toArray() ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user      = Auth::user();
        $quiz      = Quiz::where('id', $id)->where('user_id', $user->id)->count();
        if(!$quiz) { abort(404); }
        return view('quizzes.edit', ['id' => $id]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //Get the user
        $user = Auth::user();

        $quiz      = Quiz::where('id', $id)->where('user_id', $user->id)->first();
        if(!$quiz) { abort(404); }
        $questions = Question::where('quiz_id', $quiz->id)->get();
        $questions = $questions->keyBy('id');


        //Carry out the basic validation on the quiz
        $validator = Validator::make($request->all(), Quiz::updateValidationRules() );

        //If validation fails, return the errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage = "";
            foreach ($errors->all() as $error) { $errorMessage .= $error."\n"; }
            return response()->json(['errors' => $errorMessage ],202);
        } else {
            //Validate each question
            $errorMessage = "";
            if(!empty($request->questions)) {
                foreach( $request->questions as $key => $question ) {

                    //Carry out question validation
                    $questionNo = $key + 1;
                    $errorMessage .= Question::validateQuestion($question, $questionNo);
                }
                if($errorMessage) { return response()->json(['errors' => $errorMessage ],202); }

                //Hash the password

                //Validation complete! Save the answers to the DB.
                $quiz->name        = $request->title;
                $quiz->description = $request->description ?? '';
                if(!empty($request->password)) {
                    $quiz->password = Quiz::encryptQuizPassword($request->password);
                }

                $quiz->save();
                $ids = [];
                foreach($request->questions as $key => $requestQuestion) {
                    
                    //Step 1) Check if the questions already exists, if it does not, create it.
                    if( !empty($requestQuestion['id']) && $questions->contains('id', $requestQuestion['id']) ) {
                        $question = $questions->get(intval($requestQuestion['id']));
                    } else {
                        $question = new Question;
                    }

                    //Step 2) Add request data to the question
                    $question->question       = $requestQuestion['question'];
                    $question->answer_1       = $requestQuestion['answer_1'] ?? '';
                    $question->answer_2       = $requestQuestion['answer_2'] ?? '';
                    $question->answer_3       = $requestQuestion['answer_3'] ?? '';
                    $question->answer_4       = $requestQuestion['answer_4'] ?? '';
                    $question->answer_5       = $requestQuestion['answer_5'] ?? '';
                    $question->answer_6       = $requestQuestion['answer_6'] ?? '';
                    $question->type           = $requestQuestion['questionType'] ?? '';
                    $question->correct_answer = $requestQuestion['correct_answer'] ?? '';
                    
                    //Step 3) If this is a new question add the quiz and user ID
                    if(empty($question->id)) {
                        $question->quiz_id = $quiz->id;
                        $question->user_id = $user->id;
                    }
                    $question->save();
                    $ids[] = $question->id;
                }

                //Remove questions that no longer exist.
                Question::where('quiz_id', $quiz->id)->whereNotIn('id', $ids)->delete();

                return ['success' => "Questionnaire successfully updated!\n", 'errors' => '' ];
            } else {
                return response()->json(['errors' => 'You must add at least one question.' ],202);
            }
        }
    }


    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function start($id)
    {
        //
        $user      = Auth::user();
        $quiz      = Quiz::where('id', $id)->where('user_id', $user->id)->first();
        if(!$quiz) { abort(404); }

        //Connect to Mongo
        $mongoConnectionString = env('MONGO_CONNECTION');
        $client                = new \MongoDB\Client($mongoConnectionString);
        $gamesDatabase         = $client->db->games;



        $insertData = [
            'uuid'            => Str::uuid(),
            'game_id'         => Str::random(10),
            'id'              => $quiz->id,
            'name'            => $quiz->name,
            'description'     => $quiz->description,
            'password'        => $quiz->password,
            'game_started'    => 0,
            'game_completed'  => 0,
            'game_start_time' => null,
            'questions'       => [],
        ];
        $questions  = Question::where('quiz_id', $quiz->id)->get();
        foreach($questions as $question) {
            $insertData['questions'][] = $question->toArray();
        }

        $game = $gamesDatabase->findOne(['id' => $quiz->id, 'game_started' => 0 ]);
        if($game) {
            $gamesDatabase->updateOne(['id' => $quiz->id], ['$set' => $insertData]);
        } else {
            $game = $gamesDatabase->insertOne($insertData);
        }


        return redirect()->route('quiz.list');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user      = Auth::user();
        $quiz      = Quiz::where('id', $id)->where('user_id', $user->id)->first();
        if(!$quiz) { abort(404); }
        $quiz->delete();
        session()->flash('success', " successfully deleted!");
        return redirect()->route('quiz.list');
    }
}
