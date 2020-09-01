<?php

namespace App;

use App\base\Mongo;
use Illuminate\Support\Str;
use App\Upload;

class Game {


    static function addGameUrlsToGames($games) {
        foreach($games as $game) {
            $game->url = config('app.game_url').'/q/'.$game->game_id."/";
        }
        return $games;
    }

    static function list( array $searchArray, int $limit = 5) {
        $mongo = new Mongo('db', 'games');
        $games = $mongo->connection->find($searchArray, [ 
            'limit' => $limit,
            'sort' => ['created_date' => -1],
        ]);
        $games = iterator_to_array($games);
        $games = Game::addGameUrlsToGames($games);
        return $games;
    }

    static function create($user, $quiz) {

        $mongo = new Mongo('db', 'games');
        $date =  new \MongoDB\BSON\UTCDateTime(time() * 1000 );
        $insertData = [
            'created_date'      => $date,
            'uuid'              => Str::uuid(),
            'game_id'           => Str::random(10),
            'user_id'           => $user->id,
            'quiz_id'           => $quiz->id,
            'name'              => $quiz->name,
            'description'       => $quiz->description,
            'password'          => $quiz->password,
            'game_started'      => 0,
            'game_completed'    => 0,
            'game_start_time'   => null,
            'questions'         => [],
            //'players'           => [],
            'admin_socket_id'   => "",
            'have_admin_player' => 0,
            //'current_question'  => [],

        ];
        $questions  = Question::where('quiz_id', $quiz->id)->get();
        foreach($questions as $key => $question) {
            $insertData['questions'][$key] = $question->toArray();
            if($question->type == 'upload') {
                $upload = Upload::where('table_id', $question->id)->where('table_name', 'questions')->first(); //This is not very effiecent, I should look into making this a single call later
                if(!empty($upload->id)) {
                    $insertData['questions'][$key]['answer_2'] = $upload->file_url;
                }
            }
        }

        $game = $mongo->connection->findOne(['quiz_id' => $quiz->id, 'game_started' => 0 ]);
        if($game) {
            return $mongo->connection->updateOne(['quiz_id' => $quiz->id, 'game_started' => 0], ['$set' => $insertData]);
        } else {
            return $mongo->connection->insertOne($insertData);
        }

    }
}
