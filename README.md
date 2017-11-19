# About The Game

In short, the game shows on the screen some information and let you choose your next steps. However, the overall idea far more exciting. 

Basically, you walk around different rooms, kill enemies, collect weapons, find clues, and finally, in order to win the game you have to find the exit.

The game can be played from the command line as well as on the web. Well, the UI on the web is not implemented (I have left it for the full-stack developers) but RESTful API is live.

The game consist of the following entities:

- **Room** - room is room, nothing else to say here :)
- **Challenge** - This is how certain rooms are protected. If a room is protected with a challenge you cannot enter the room without having a proper solution.
- **Solution** - It allows you to enter rooms protected by challenges. Normally, it goes 1 solution per challenge, and 1 challenge per room. Not all rooms are protected with challenges. In fact, most of the rooms are free to access.
- **Guard** - This is with who players fight in the game. Conceptually, guards protect rooms. It's another level of protection for rooms. Players should kill guards in order to enter rooms. There are rooms without guards. Normally, most of the rooms in the game without guards.
- **Health** - It gives players power when fighting with guards. Initially, players get 50% of health. While walking throughout the rooms, players might come across the health artifacts which give an additional health percentage to them.
- **Weapon** - It gives additional power to players to kill guards. 
- **Hint** - It can be found in certain rooms. It gives you a clue about how to win the game. You should pay attention on what it says. Generally, when picking a weapon, health, or solution in the game, it gives you some sort of suggestion/help about how you can utilize the picked artifact to win the game. That said, you should read the description of collected artifacts to win.



## How to install

First, you need to run `composer update` to download all the needed packages. 

Next, you should provide details for the database to use. This can be done in the `parameters.yml` file. 

Once, you are done with that, you can install the game by running the command `php bin/console game:install`. Finally, you can now run the game with this command `php bin/console game:play {username} {password}`. 

**Note:** For the command line, you don't have to be prior registered in order to play the game. All you need is to provide your username and password and the system will detect whether you are already registered or not. If not, it will register you first, if yes, it will log you in.



## Basic components & services

The game consist of the following components:

- **Scenario** - This is what you see on the screen. It basically describes you the current situation and let you choose one of the provided choices to move to the next scenario. As a rule of thumb, scenarios never perform actions. They only show information and provide a list of choices.

- **Choice** - It describes your next step. It describes an action to perform based on what you read in the current scenario. In contrast to scenarios choices can perform actions and decide which scenarios go next. 
- **Player** - It represents a user playing the game.

- **Lifecycle Service** It is used to manage the workflow of the game. Choices heavily rely on this service to perform actions.
- **Player Service** - It helps to register, login/logout players.


## RESTful API

The game can be played via HTTP requests. This can be leveraged when building the front-end part of the game.

**Important:** All the endpoints are prefixed with `/api` so this piece will be omitted in the endpoints below. Additionally, after a player
logs in and gets the token from the server, it should be always provided with each request in the header like this `Game-Token: {secret}`
 
### Player

##### Creates a new player

##### `POST /players`

- Payload
            
            {
                "username": "test",
                "password": "1234"
            }
            
- Response

            {
                "username": "test",
                "health": 50,
                "artifacts": [
                    {
                        "name": "weapon",
                        "power": 100,
                        "description": "A super gun to kill a super bad guy."
                    },
                    {
                        "name": "solution",
                        "description": "Some key for some door."
                    }
                ]
            }
            
##### Gets a current player

##### `GET /players/current`       

- Response

            {
                "username": "test",
                "health": 50,
                "artifacts": [
                    {
                        "name": "weapon",
                        "power": 100,
                        "description": "A super gun to kill a super bad guy."
                    },
                    {
                        "name": "solution",
                        "description": "Some key for some door."
                    }
                ]
            }
            
##### Logs in a player

##### `POST /players/current/login`

- Payload
            
            {
                "username": "test",
                "password": "1234"
            }
            
- Response

            {
                "secret": "xxxaaabbb222"
            }
            
**Note:** It will return the `403` code in case of wrong credentials


##### Logs out a player

##### `POST /players/current/logout`

### Game

##### Starts a game

##### `POST /games`

- Response

            {
                "description": "You are in the room \"Scary Holes\". You can do the following things here.",
                
                "choices": [
                    {
                        "identifier": "quit",
                        "title": "Quit"
                    },
                    {
                        "identifier": "room-10",
                        "title": "Enter room \"Gun Shop\""
                    }
                ]
            }
            
##### Get a current scenario
 
##### `GET /games/current/scenarios/current`

- Response

            {
                "description": "You are in the room \"Scary Holes\". You can do the following things here.",
                
                "choices": [
                    {
                        "identifier": "quit",
                        "title": "Quit"
                    },
                    {
                        "identifier": "room-10",
                        "title": "Enter room \"Gun Shop\""
                    }
                ]
            }
            
##### Choose a choice

##### `POST /games/current/scenarios/current/choices`

- Payload

            {
                "identifier": "fight"
            }
            
- Response

            {
                "description": "Game over!",
                
                "choices": [
                    {
                        "identifier": "quit",
                        "title": "Quit"
                    },
                    {
                        "identifier": "reply",
                        "title": "Play again"
                    }
                ]
            }

## Tests

RESTful API is fully covered with Behat tests. In fact, most of the functionality is covered with the integration tests. However, some parts of the system cannot be covered with the integration tests. Therefore, those parts of the system are covered with unit tests. With that, the overall functionality should be nearly fully covered. At least, I want to believe so ;-)

