Feature: Allows to play a game

    Scenario: Goes through rooms, kills enemies, collects artifacts, solve challenges and etc.

        Given I have the player with following data:
            |username|password|
            |test    |1234    |

        # start playing

        Then I get the content from "/players/current"

        Then I see the following data:
            |username|health|
            |test|50|


        Then I get the content from "/games/current/scenarios/current"

        Then I see no content

        Then I post to "/games"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%the room "Reception"%|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|
            |Enter room "Horrible Place"|room-2|Enter room "Block A"|room-3|

        Then I get the content from "/games/current/scenarios/current"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%the room "Reception"%|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|
            |Enter room "Horrible Place"|room-2|Enter room "Block A"|room-3|

        Given I have the following data:
            |identifier|
            |room-2    |

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%in the room "Horrible Place"%|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|choices.4.title|choices.4.identifier|
            |Go back|back|Enter room "Hall"|room-4|Collect artifacts|collect|

        Given I have the following data:
            |identifier|
            |collect   |

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |Solution: Some key for some door.|Quit|quit|Play again|replay|Continue|continue|

        Then I get the content from "/players/current"

        Then I see the following data:
            |artifacts.0.name|artifacts.0.description|
            |Solution|Some key for some door.|

        Given I have the following data:
            |identifier|
            |continue  |

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%in the room "Horrible Place"%|Quit|quit|Play again|replay|

        Given I have the following data:
            |identifier|
            |room-4|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |%in the room "Hall"%|Quit|quit|Play again|replay|Go back|back|

        Then I see the following data as well:
            |choices.3.title|choices.3.identifier|choices.4.title|choices.4.identifier|choices.5.title|choices.5.identifier|
            |Enter room "Bank of Universe"|room-5|Enter room "Right Way"|room-6|Enter room "Pharmacy"|room-7|

        Given I have the following data:
            |identifier|
            |room-5|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%need a key to open this door%|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|
            |Go back|back|I have what's needed|solve|

        Given I have the following data:
            |identifier|
            |solve|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%in the room "Bank of Universe"%|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|
            |Go back|back|Collect artifacts|collect|


        Then I get the content from "/players/current"

        Then I see the following data:
            |artifacts|
            |[]|

        Given I have the following data:
            |identifier|
            |collect|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |Solution: $1 000 000 to buy a good gun.|Quit|quit|Play again|replay|Continue|continue|

        Given I have the following data:
            |identifier|
            |continue|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%in the room "Bank of Universe"%|

        Given I have the following data:
            |identifier|
            |back|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%in the room "Hall"%|

        Given I have the following data:
            |identifier|
            |room-7|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%in the room "Pharmacy"%|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|
            |Go back|back|Collect artifacts|collect|

        Given I have the following data:
            |identifier|
            |collect|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |Health [+50]: This will help you to kill the guard.|Quit|quit|Play again|replay|Continue|continue|

        Then I get the content from "/players/current"

        Then I see the following data:
            |health|
            |100|

        Given I have the following data:
            |identifier|
            |continue|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%the room "Pharmacy"%|

        Given I have the following data:
            |identifier|
            |back|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%in the room "Hall"%|

        Given I have the following data:
            |identifier|
            |room-6|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%in the room "Right Way"%|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|
            |Go back|back|Enter room "Fight Club"|room-8|

        Given I have the following data:
            |identifier|
            |room-8|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%You faced a guard "Big Dude" in this room. His power is "90". Your power is "100".%|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|
            |Go back|back|Fight!|fight|

        Given I have the following data:
            |identifier|
            |fight|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |%in the room "Fight Club"%|Quit|quit|Play again|replay|Go back|back|

        Then I see the following data as well:
            |choices.3.title|choices.3.identifier|choices.4.title|choices.4.identifier|
            |Enter room "Wonderful Place"|room-9|Enter room "Bridge"|room-11|

        Then I get the content from "/players/current"

        Then I see the following data:
            |health|
            |10|

        Given I have the following data:
            |identifier|
            |room-9|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |%in the room "Wonderful Place"%|Quit|quit|Play again|replay|Go back|back|

        Then I see the following data as well:
            |choices.3.title|choices.3.identifier|choices.4.title|choices.4.identifier|
            |Enter room "Outside World"|room-10|Collect artifacts|collect|

        Given I have the following data:
            |identifier|
            |collect|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |Hint: The result of this `2 x 2 = ?` will help you in the future.|Quit|quit|Play again|replay|Continue|continue|

        Given I have the following data:
            |identifier|
            |continue|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%in the room "Wonderful Place"%|

        Given I have the following data:
            |identifier|
            |back|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%in the room "Fight Club"%|

        Given I have the following data:
            |identifier|
            |room-11|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |%in the room "Bridge"%|Quit|quit|Play again|replay|Go back|back|

        Then I see the following data as well:
            |choices.3.title|choices.3.identifier|choices.4.title|choices.4.identifier|
            |Enter room "Gun Shop"|room-12|Enter room "Scary Holes"|room-13|

        Given I have the following data:
            |identifier|
            |room-12|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |Do you have enough money to buy a serious gun?|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|
            |Go back|back|I have what's needed|solve|

        Given I have the following data:
            |identifier|
            |solve|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%in the room "Gun Shop"%|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|
            |Go back|back|Collect artifacts|collect|

        Given I have the following data:
            |identifier|
            |collect|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |Weapon [100]: A super gun to kill a super bad guy.|Quit|quit|Play again|replay|Continue|continue|

        Given I have the following data:
            |identifier|
            |continue|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%in the room "Gun Shop"%|

        Given I have the following data:
            |identifier|
            |back|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%in the room "Bridge"%|

        Given I have the following data:
            |identifier|
            |room-13|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |%in the room "Scary Holes"%|Quit|quit|Play again|replay|Go back|back|

        Then I see the following data as well:
            |choices.7.title|choices.7.identifier|choices.13.title|choices.13.identifier|
            |Enter room "Hole #5"|room-18|Collect artifacts|collect|

        Given I have the following data:
            |identifier|
            |collect|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |Health [+50]: This should make you feel much better.|Quit|quit|Play again|replay|Continue|continue|

        Then I get the content from "/players/current"

        Then I see the following data:
            |health|artifacts.0.name|artifacts.0.description|artifacts.1.name|artifacts.1.description|
            |60|Hint|The result of this `2 x 2 = ?` will help you in the future.|Weapon|A super gun to kill a super bad guy.|

        Given I have the following data:
            |identifier|
            |continue|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%in the room "Scary Holes"%|

        Given I have the following data:
            |identifier|
            |room-17|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%You faced a guard "Killer #1" in this room. His power is "140". Your power is "160".%|Quit|quit|Play again|replay|

        Then I see the following data as well:
            |choices.2.title|choices.2.identifier|choices.3.title|choices.3.identifier|
            |Go back|back|Fight!|fight|

        Given I have the following data:
            |identifier|
            |fight|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |%Congratulations! You have reached the final room%|Quit|quit|Play again|replay|Go back|back|


        Given I have the following data:
            |identifier|
            |quit|
        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see no content

        Then I get the content from "/games/current/scenarios/current"

        Then I see no content

        Then I post to "/games"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|choices.2.title|choices.2.identifier|
            |%Congratulations! You have reached the final room%|Quit|quit|Play again|replay|Go back|back|

        Given I have the following data:
            |identifier|
            |replay|

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |%the room "Reception"%|Quit|quit|Play again|replay|

    Scenario: Goes through rooms 'till gets killed.

        Given I have the player with following data:
            |username|password|
            |test    |1234    |

        Then I post to "/games"

        Given I have the following data:
            |identifier|
            |room-2    |

        Then I post the data to "/games/current/scenarios/current/choices"

        Given I have the following data:
            |identifier|
            |room-4    |

        Then I post the data to "/games/current/scenarios/current/choices"

        Given I have the following data:
            |identifier|
            |room-6    |

        Then I post the data to "/games/current/scenarios/current/choices"

        Given I have the following data:
            |identifier|
            |room-8   |

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%You faced a guard "Big Dude" in this room. His power is "90". Your power is "50"%|

        Given I have the following data:
            |identifier|
            |fight    |

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |Game over!|Quit|quit|Play again|replay|

        Then I get the content from "/games/current/scenarios/current"

        Then I see the following data:
            |description|choices.0.title|choices.0.identifier|choices.1.title|choices.1.identifier|
            |Game over!|Quit|quit|Play again|replay|

        Given I have the following data:
            |identifier|
            |replay|

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%in the room "Reception"%|


    Scenario: Goes to the room, collects an artifact, then revisit the same room and tries to collect artifacts again

        Given I have the player with following data:
            |username|password|
            |test    |1234    |

        Then I post to "/games"

        Given I have the following data:
            |identifier|
            |room-2|

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |choices.4.identifier|
            |collect             |

        Given I have the following data:
            |identifier|
            |collect|

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |choices.2.identifier|
            |continue|

        Given I have the following data:
            |identifier|
            |continue|

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the following data:
            |description|
            |%in the room "Horrible Place"%|

        Given I have the following data:
            |identifier|
            |collect|

        Then I post the data to "/games/current/scenarios/current/choices"

        Then I see the "400" code with the following data:
            |message|
            |The player has chosen nonexistent choice|






























