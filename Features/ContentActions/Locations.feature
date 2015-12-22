Feature: Content Locations

    Background:
        Given I am logged in as an 'Administrator' in PlatformUI

    @javascript
    Scenario: Add a location to a content and verify
        Given a "Folder" folder exists
        And I am on 'Folder' full view
        And I click on the tab "Locations"
        When I click on "Add location" button
        And I select the "Media/Images" folder in the Universal Discovery Widget
        And I click on "Choose this content" button
        And I click on "Confirm selection" button
        Then I click on the navigation item "Media library"
        And I see "Images/Folder" in the content tree

    @javascript
    Scenario: Remove a location of a content and verify
        Given a "Folder" folder exists
        And a "Location" folder exists
        And I add the "Location" content as location to the content "Folder"
        And I am on 'Folder' full view
        And I click on the tab "Locations"
        When I select "eZ Platform/Location/Folder" location checkbox
        And I click on "Remove selected" button
        And I confirm the removal
        Then I don't see "Location/Folder" in the content tree

    @javascript
    Scenario: Change location visibility of a content and verify the change
        Given a "Folder" folder exists
        And a "Location" folder exists
        And I add the "Location" content as location to the content "Folder"
        And I am on "Folder" full view
        And I click on the tab "Locations"
        When I change the "eZ Platform/Location/Folder" location visibility
        Then the "eZ Platform/Location/Folder" visibility is "Hidden"

    @javascript
    Scenario: Change location of a parent content and verify the change
        Given a "FolderA/FolderB" folder exists
        And I am on "FolderA" full view
        And I click on the tab "Locations"
        When I change the "eZ Platform/FolderA" location visibility
        Then the child content visibility is "Hidden by superior"

    @javascript
    Scenario: I want to change the main location of a content
        Given a "LocationTestFolder" folder exists
        And an "LocationTestArticle" article exists
        And I am on "LocationTestArticle" full view
        And I click on the tab "Locations"
        When I add the "LocationTestFolder" content as location to the content "LocationTestArticle"
        And I change the content "LocationTestArticle" main location to the path "eZ Platform/LocationTestFolder/LocationTestArticle"
        Then I verify that the main location of the content "LocationTestArticle" is "eZ Platform/LocationTestFolder/LocationTestArticle"

