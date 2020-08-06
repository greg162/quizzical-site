 # Quizzical - The Site

This is the site part of Quizzical. It's a PHP / MySQL app that uses Laravel as a Framework. 

Note: This part of the site is only used to create the games, the games are played using a seperate application that can be seen here:

https://github.com/greg162/quizzical-app

Custom Laravel .env variables required:

  * `GAME_URL` - The base URL(including protocol) of the game server.
  * `MONGO_URI` -  Your Mongo connection string. Ensure the database is included.
  * `MONGO_URI_OPTIONS` - Your mongo URI connection options (if required. Leave blank if not needed).
  * `MONGO_DRIVER_OPTIONS`- Your mongo driver options (if required. Leave blank if needed).

 ## Bugs

  * Quit button removes the GAME ID.
  * Create unit tests to ensure that users disconnecting then rejoining does not crash the server.

 ## Features Still to implement

  * Image or audio question type.
  * Ability to highlight an answer.
  * Ability to assign half a point.

 # Design Changes required

  * Create a logo
  * Choose a font