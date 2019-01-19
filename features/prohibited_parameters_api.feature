Feature: Prohibited parameter combinations
    In order to disallow customer to select incompatible parameter combinations
    As an API consumer
    I need to be able to see available options given current selection

    Background:
        Given there is parameter "Size" with possible values "S", "M", "L" and "XL"
        And there is parameter "Color" with possible values "Red", "Green" and "Blue"
        And there is parameter "Plug Type" with possible values "US", "UK" and "Schuko"
        And there is a restriction that "Size" "XL" cannot go together with "Color" "Red"
        And there is a restriction that "Color" "Green" cannot go together with "Plug Type" "UK"
        And there is a restriction that "Size" "S" cannot go together with "Plug Type" "Schuko"

    Scenario: API returns all available options when no selection is made yet
        When I query the API for parameter options without current selection
        Then I should receive parameter "Size" with choices "S", "M", "L" and "XL"
        And I should receive parameter "Color" with choices "Red", "Green" and "Blue"
        And I should receive parameter "Plug Type" with choices "US", "UK" and "Schuko"

    Scenario: API excludes the prohibited combination when one parameter is selected
        When I query the API for parameter options with current selection "Size" being "XL"
        Then I should receive parameter "Size" with choices "S", "M", "L" and "XL"
        And I should receive parameter "Color" with choices "Green" and "Blue"
        And I should receive parameter "Plug Type" with choices "US", "UK" and "Schuko"

    Scenario: API excludes all prohibited combinations when multiple parameters are selected
        When I query the API for parameter options with current selection "Size" being "S" and "Color" being "Green"
        Then I should receive parameter "Size" with choices "S", "M", "L" and "XL"
        And I should receive parameter "Color" with choices "Red", "Green" and "Blue"
        And I should receive parameter "Plug Type" with choices "US"

    Scenario: API responds with an error status code when a non-existent parameter value is selected
        When I query the API for parameter options with current selection "Size" being "XS"
        Then I should receive receive a status code 404
        And I should receive an error message

    Scenario: API responds with an error status code when an invalid combination is already selected
        When I query the API for parameter options with current selection "Size" being "XL" and "Color" being "Red"
        Then I should receive receive a status code 400
        And I should receive an error message
