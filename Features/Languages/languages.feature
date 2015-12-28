Feature: Perform languages admin related operations

    Background:
        Given I am logged in as an 'Administrator' in PlatformUI

    @javascript
    Scenario: Verify the existence of Languages page and it's content
        When I go to the Languages page
        Then I see the Languages page
        And I see the following limitations fields:
            | Language name  | Language code | Language ID | Enabled |
        And I see the "Create a new language" link

    @javascript
    Scenario: Create a new language
        Given I go to the Languages page
        And I click on the "Create a new language" link
        When I fill in "Name" with "NewLanguageTest"
        And I fill in "Language code" with "NLT"
        And I check "Enabled" checkbox
        And I click on the "Save" button
        And I click on the navigation item "Languages"
        Then I should see the newly created language with:
            | Language name   | Language code | Enabled |
            | NewLanguageTest | NLT           | true    |

    @javascript
    Scenario: Edit an existing language
        Given a Language with name "NewLanguageTest" and code "testcode" exists
        And I go to the Languages page
        When I edit the language with name "NewLanguageTest"
        And I fill in "Name" with "LanguageEditTest"
        Then I verify that the "Language code" text field is disabled
        And I check "Enabled" checkbox
        And I click on the "Save" button
        And I click on the navigation item "Languages"
        And I should see the newly edited language with:
            | Language name    | Language code | Enabled |
            | LanguageEditTest | NLT           | true    |

    @javascript
    Scenario: Delete an existing language
        Given a Language with name "DeleteLanguageTest" and code "testcode" exists
        Given I go to the Languages page
        When I delete the language with name "DeleteLanguageTest"
        Then I shouldn't see the previously created language with:
            | Language name    | Language code | Enabled |
            | DeleteLanguageTest | NLT           | true    |
