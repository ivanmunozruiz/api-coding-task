Feature: Delete Faction
  As a client
  I want to delete a faction

  Background:
    Given the following factions exist:
        | id | name | description |
        | f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a | Faction Name 1 | Faction Description 2 |

  @adminToken
  Scenario: AC-1a: Delete faction with valid auth token
    When I send a DELETE api request to "/api/v1/factions/f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a" with body:
    """
    """
    Then the response status code should be 204

  @adminToken
  Scenario: AC-1b: Delete faction with valid auth token but not exist faction
    When I send a DELETE api request to "/api/v1/factions/9c38a6c9-8066-4f27-bcb3-9bc686c8b393" with body:
    """
    """
    Then the response status code should be 404
    And the JSON nodes should be equal to:
      | status | 404                                                                      |
      | title  | FACTION_NOT_FOUND                                                        |
      | detail | Faction with identifier 9c38a6c9-8066-4f27-bcb3-9bc686c8b393 not found   |
      | type   | http://google.com/ur-to-errors-doc/faction_not_found                     |
      | code   | faction_not_found                                                        |


  Scenario: AC-1c: Delete faction with valid auth token
    When I send a DELETE api request to "/api/v1/factions/f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a" with body:
    """
    """
    Then the response status code should be 401