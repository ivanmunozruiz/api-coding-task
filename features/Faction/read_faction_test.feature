Feature: Read Faction
  As a client
  I want to read a faction

  Background:
    Given the following factions exist:
        | id | name | description |
        | f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a | Faction Name 1 | Faction Description 2 |

  @adminToken
  Scenario: AC-1b: Read faction with valid auth token
    When I send a GET api request to "/api/v1/factions/f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id               | f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a |
      | faction_name     | Faction Name 1                       |
      | description      | Faction Description 2                |