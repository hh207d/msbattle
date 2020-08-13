# msbattle

a simple battleships-game-api with symfony

## requirements


## installation

* clone

* // go to project folder, then:
* composer install
* php bin/console doctrine:schema:create
* php bin/console doctrine:fixtures:load
* openssl genrsa -out config/jwt/private.pem -aes256 4096 // passphrase: 9272edf7c56cc77d1fcce1de5b134a06
* openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem // passphrase: 9272edf7c56cc77d1fcce1de5b134a06
* symfony serve

## playing

please use included postman project, to see available calls

steps to play

* login with existing user (with [`POST` to `authentication_token`]) and the appropriate json

      {
          "email": "spieler@spiel.de",
          "password": "sehrgeheim"
      }
  (Postman should now set the bearer token automatically in the next requests; if not, please take it from the response of the above login request)


* add game (with [`POST` to `api/games`]) 

      {}

  optionally, the size can be set while adding a game:

      {  
          "sizeX": 5,
          "sizeY": 5,
      }

* make placements for the ships (with [`POST` to `api/placements`]) and the appropriate json

      {
          "xcoord": 0,
          "ycoord": 0,
          "orientation": "ORIENTATION_HORIZONTAL",
          "game": "api/games/28",
          "ship": "api/ships/170"
      }
  (when all ships are placed, the game will indicate it through the `gameInPlacementMode` value set to `false`)

* make turns (with [`POST` to `api/turns`]) and the appropriate json

      {
          "xcoord": 0,
          "ycoord": 2,
          "game": "api/games/28"    
      }

* status of game can be seen with the [`GET` to `api/games/{id}`]

* a list of all games can be seen with the [`GET` to `api/games`]

* a little index page can be reached via browser: http://localhost:8000/