Feature: Allows to register new players, log them in/out and view their profiles

    Scenario: Registers a new player, logs him in, views his profile, and logs him out

        Given I have the following data:
            |username|password|
            |test    |1234    |

        Then I post the data to "/players"

        Then I see the following data:
            |username|health|
            |test    |50    |


        Given I have the following data:
            |username|password|
            |test    |1234    |

        Then I post the data to "/players"

        Then I see the "400" code with the following data:
            |message|
            |The "test" player already exists|

        Given I have the following data:
            |username|password|
            |||

        Then I post the data to "/players"

        Then I see the "422" code with the following data:
            |errors.username|errors.password|
            |This value should not be blank.|This value should not be blank.|

        Given I have the following data:
            |username|password|
            |unknown    |1234    |

        Then I post the data to "/players/current/login"

        Then I see the "403" code with the following data:
            |message|
            |The provided credentials are not valid|

        Given I have the following data:
            |username|password|
            |test    |1234    |

        Then I post the data to "/players/current/login"

        Then I see the following data:
            |secret  |
            |{string}|

        Then I remember the value of the "secret" field

        Given I have the following data in the header:
            |Game-Token|
            |{secret} |

        Then I get the content from "/players/current"

        Then I see the following data:
            |username|health|
            |test    |50    |

        Given I have the following data in the header:
            |Game-Token|
            |{secret} |

        Then I post to "/players/current/logout"
        Then I see no content

        Given I have the following data in the header:
            |Game-Token|
            |{secret} |

        Then I get the content from "/players/current"

        Then I see the "403" code with the following data:
            |message|
            |Access denied to the resource|
