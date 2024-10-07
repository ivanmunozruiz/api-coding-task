Feature: Create Faction
  As a client
  I want to create a faction

  @adminToken
  Scenario: AC-1a: Create faction with valid auth token
    When I send a POST api request to "/api/v1/factions" with body:
    """
    {
      "id": "f1d1b2b3-4b5c-6d7e-8f9a-0b1c2d3e4f5a",
      "name": "Faction Name 1",
      "description": "Faction Description 2"
    }
    """
    Then the response status code should be 201
  Scenario: AC-1b: Try to create faction with an invalid auth token
    When I send a POST api request to "/api/v1/factions" with body:
    """
    {
      "name": "Faction Name",
      "description": "Faction Description"
    }
    """
    Then the response status code should be 401
    And the JSON nodes should be equal to:
      | status | 401                                                      |
      | title  | UNKNOWN_ERROR                                            |
      | detail | Full authentication is required to access this resource. |
      | type   | http://google.com/ur-to-errors-doc/http                  |
      | code   | http                                                     |
  @adminToken
  Scenario: AC-1c: Try to create faction with an invalid body
    When I send a POST api request to "/api/v1/factions" with body:
    """
    {
      "name": "Faction Name"
    }
    """
    Then the response status code should be 422
    And the JSON nodes should be equal to:
      | status | 422                                                      |
      | title  | BAD_REQUEST                                              |
      | detail | id is required                                       |
      | type   | http://google.com/ur-to-errors-doc/invalid_argument      |
      | code   | invalid_argument                                         |
