<?php

namespace App;

use App\base\Mongo;
use Illuminate\Support\Str;



class Game {


    static function addGameUrlsToGames($games) {
        foreach($games as $game) {
            $game->url = config('app.game_url').'/q/'.$game->game_id."/";
        }
        return $games;
    }

    static function list( array $searchArray, int $limit = 100) {
        $mongo = new Mongo('db', 'games');
        $games = $mongo->connection->find($searchArray, [ 'limit' => $limit ]);
        $games = iterator_to_array($games);
        $games = Game::addGameUrlsToGames($games);
        return $games;
    }

    static function create($user, $quiz) {

        $mongo = new Mongo('db', 'games');
        $insertData = [
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
        foreach($questions as $question) {
            $insertData['questions'][] = $question->toArray();
        }

        $game = $mongo->connection->findOne(['quiz_id' => $quiz->id, 'game_started' => 0 ]);
        if($game) {
            return $mongo->connection->updateOne(['quiz_id' => $quiz->id, 'game_started' => 0], ['$set' => $insertData]);
        } else {
            return $mongo->connection->insertOne($insertData);
        }

    }
}
