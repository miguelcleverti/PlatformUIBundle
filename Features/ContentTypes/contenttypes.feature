Feature: ContentType feature

    Background:
        Given I am logged in as an 'Administrator' in PlatformUI

    ##
    # ContentType Groups
    ##
    @javascript
    Scenario: Create ContentType form fields
        Given I am on the ContentTypes page
        When I should see "Content type groups" title
        Then I see the following ContentType group fields:
            | Name | ID |
        And I should see a "Create a Content type group" button

    @javascript
    Scenario: View create ContentType group form fields
        Given I am on the Content types page
        When I click at the "Create a Content type group" link
        Then I should see "Creating a new Content type group" title
        And I should see a "Name" input field
        And I should see a "Cancel" button
        And I should see a "Save" button

    @javascript
    Scenario: Create a new ContentType group
        Given I am on the Content types page
        And I click at the "Create a Content type group" link
        When I fill in "Name" with "NewUserGroup"
        And I click at "Save" button
        Then I should see a "NewUserGroup" ContentType group

    @javascript
    Scenario: Edit existing ContentType group
        Given there is a Content Type Group with identifier "NewUserGroup"
        And I am on the "Content types" page
        When I edit the "NewUserGroup" Content type group
        And I fill in "Name" with "EditedUserGroup"
        And I click at "Save" button
        Then I should see a "EditedUserGroup" ContentType group

    @javascript
    Scenario: Content type group using an existing content type group name
        Given there is a Content Type Group with identifier "ExistingNameGroup"
        And I am on the Content types page
        When I click at the "Create a Content type group" link
        And I fill in "Name" with "ExistingNameGroup"
        Then I should see an error

        #It is not possible to update a content type group using an existing content type group name
        #Delete  content type group that has no content type
        #It is not possible to delete content type groups that have defined content type

        #It is not possible to create a content type using an existing content type name
        #It is not possible to update a content type using an existing content type name
        #Delete  content type that has no created content
        #It is not possible to delete content type that has created contents

    ##
    # ContentTypes
    ##
    @javascript
    Scenario: Create ContentType form fields
        Given I am on the Content types page
        When I click in the "Content" Content type group
        Then I should see "Content type group" title
        And I see the following ContentType fields:
            | ID | Name | Identifier | Modification date |
        And I should see a "Create a content type" button

    @javascript @now
    Scenario: Create ContentType form fields
        Given I am on the "Content types" page
        And I click in the "Content" Content type group
        When I click at "Create a content type" button
        And I fill form with:
            | Field      | Value |
            | Name       | Test  |
            | Identifier | test  |
        And I add a field type "Authors" with:
            | Field      | Value  |
            | Name       | Author |
            | Identifier | author |
        And I click at "OK" button
        Then    I should see "Test" ContentType in "Content" type group
