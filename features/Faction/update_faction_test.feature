Feature: Update Faction
  As a client
  I want to update a faction

  Background:
    Given the following factions exist:
        | id | name | description |
        | f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a | Faction Name 1 | Faction Description 2 |

  @adminToken
  Scenario: AC-1a: Update faction with valid auth token
    When I send a PUT api request to "/api/v1/factions/f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a" with body:
    """
    {
      "name": "Faction Name 1 Updated",
      "description": "Faction Description 2 Updated"
    }
    """
    Then the response status code should be 200


  @adminToken
  Scenario: AC-1b: Update faction with valid auth token but not exist faction
    When I send a PUT api request to "/api/v1/factions/9c38a6c9-8066-4f27-bcb3-9bc686c8b393" with body:
    """
    {
      "name": "Faction Name 1 Updated",
      "description": "Faction Description 2 Updated"
    }
    """
    Then the response status code should be 404
    And the JSON nodes should be equal to:
      | status | 404                                                                      |
      | title  | FACTION_NOT_FOUND                                                        |
      | detail | Faction with identifier 9c38a6c9-8066-4f27-bcb3-9bc686c8b393 not found   |
      | type   | http://google.com/ur-to-errors-doc/faction_not_found                     |
      | code   | faction_not_found                                                        |
    

  Scenario: AC-1d: Update faction with valid auth token
    When I send a PUT api request to "/api/v1/factions/f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a" with body:
    """
    {
      "name": "Faction Name 1 Updated"
    }
    """
    Then the response status code should be 401