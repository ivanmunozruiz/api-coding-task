Feature: Greeting API

  Scenario: Get greeting message
    When I send a GET request to "/api/greeting"
    Then the response status code should be 404