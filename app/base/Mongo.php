<?php 
namespace app\base;

use MongoDB\Client;

class Mongo {


    function __construct($db, $set) {
      $mongoConnectionString = env('MONGO_URI');
      $client                =  new \MongoDB\Client($mongoConnectionString);
      $this->connection      = $client->{$db}->{$set};
    }

}