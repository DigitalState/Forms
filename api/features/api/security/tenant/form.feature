@api @security @tenant @form
Feature: Deny access to forms belonging to other tenants

  Background:
    Given I am authenticated as the "system@system.ds" user from the tenant "b6ac25fe-3cd6-4100-a054-6bba2fc9ef18"

  Scenario: Browse forms from your own tenant
    When I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/forms"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the response should be in JSON
    And the JSON should be valid according to this schema:
    """
    {
      "type": "array",
      "items": {
        "type": "object",
        "properties": {
          "tenant": {
            "type": "string",
            "pattern": "b6ac25fe-3cd6-4100-a054-6bba2fc9ef18"
          }
        }
      },
      "required": [
        "tenant"
      ]
    }
    """

  Scenario: Read a form from an other tenant
    When I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/forms/08474f2e-78a5-4029-9eef-9c83e6e5e1e7"
    Then the response status code should be 404
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON

  Scenario: Edit a form from an other tenant
    When I add "Accept" header equal to "application/json"
    And I add "Content-Type" header equal to "application/json"
    And I send a "PUT" request to "/forms/08474f2e-78a5-4029-9eef-9c83e6e5e1e7" with body:
    """
    {}
    """
    Then the response status code should be 404
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON

  Scenario: Delete a form from another tenant
    When I add "Accept" header equal to "application/json"
    And I send a "DELETE" request to "/forms/08474f2e-78a5-4029-9eef-9c83e6e5e1e7"
    Then the response status code should be 404
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
