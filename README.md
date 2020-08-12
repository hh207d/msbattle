# msbattle

a simple battleships-game-api with symfony

## requirements


## installation
* clone
* composer install
* symfony serve

## playing

please use included postman project, to see available calls

steps to play

* login with existing user (with [`POST` to `authentication_token`]) and the appropriate json

      {
          "email": "ttt@a.a",
          "password": "sehrgeheim"
      }


* add game (with [`POST` to `api/games`]) 

      {  
          "sizeX": 5,
          "sizeY": 5,
          "state": "STATE_STARTED"
      }

* make placements for the ships (with [`POST` to `api/placements`]) and the appropriate json

      {
          "xcoord": 0,
          "ycoord": 0,
          "orientation": "ORIENTATION_HORIZONTAL",
          "game": "api/games/28",
          "ship": "api/ships/170"
      }

* make turns (with [`POST` to `api/turns`]) and the appropriate json

      {
          "xcoord": 0,
          "ycoord": 2,
          "game": "api/games/28"    
      }

* (win the game); status of game can be seen with the [`GET` to `api/games/{id}`]

* a list of all games can be seen with the [`GET` to `api/games`]

## what is (known) missing

* no strategies of COMP-opponent
* no security for routes, the user shouldn't reach
* no tests
* gui
* login url `authentication_token` not intuitive

## what else is missing

* ???