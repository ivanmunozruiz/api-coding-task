Feature: Read Faction
  As a client
  I want to read a faction

  Background:
    Given the following factions exist:
        | id | name | description |
        | f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a | Faction Name 1 | Faction Description 2 |

  @adminToken
  Scenario: AC-1a: Read faction with valid auth token
    When I send a GET api request to "/api/v1/factions/f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id               | f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a |
      | faction_name     | Faction Name 1                       |
      | description      | Faction Description 2                |


  @adminToken
  Scenario: AC-1b: Read faction without valid auth token
    When I send a GET api request to "/api/v1/factions/bc8d74fc-4b68-4877-8a0d-9b5b26b0dff9"
    Then the response status code should be 404
    And the JSON nodes should be equal to:
      | status | 404                                                                      |
      | title  | FACTION_NOT_FOUND                                                        |
      | detail | Faction with identifier bc8d74fc-4b68-4877-8a0d-9b5b26b0dff9 not found   |
      | type   | http://google.com/ur-to-errors-doc/faction_not_found                     |
      | code   | faction_not_found                                                        |


  Scenario: AC-1c: Read faction with valid auth token
    When I send a GET api request to "/api/v1/factions/f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a"
    Then the response status code should be 401