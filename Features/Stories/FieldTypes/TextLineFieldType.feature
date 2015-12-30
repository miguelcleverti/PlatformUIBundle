
Feature: Test the validations done on fields from PlatformUI - text line fieldtype
    In order to validate the text line fieldtype
    As an Editor user
    I need to be able to create and update content with text line fieldtypes

    Background:
       Given I am logged in as an 'Administrator' in PlatformUI

    ##
    # Validate the existence of expected fields from a field type when creating a content
    ##
    @javascript
    Scenario: A Content of a Content Type that has a text line fieldtype must have a text field
        Given a Content Type with a "text line" Field exists
        When I create a content of this type
        Then I should see a "text line" field

    @javascript
    Scenario: When editing a Content, the label of a text line field must have the same name than field type from the respective Content Type
        Given a Content Type with a "text line" with field definition name "Test text" exists
        When I create a content of this type
        Then I should see a "Test text" label related with the "text line" field

    @javascript
    Scenario: The label of a required text line field of a Content must be marked as required
        Given a Content Type with a required "text line" with field definition name "Required" exists
        When I create a content of this type
        Then the "Required" field should be marked as required

    ##
    # Creating Content using a Content Type that has a text line Field Type
    ##
    @javascript
    Scenario: Creating a text line Field works
        Given a Content Type with a "text line" Field exists
        When I create a content of this type
        And I set "Test text" as the Field Value
        And I publish the content
        Then the Content is successfully published

    @javascript
    Scenario: Creating a text line Field with an empty value works
        Given a Content Type with a "text line" Field exists
        When I create a content of this type
        And I set an empty value as the Field Value
        And I publish the content
        Then the Content is successfully published

    @javascript
    Scenario: Creating a required text line Field fails validation when using an empty value
        Given a Content Type with a required "text line" Field exists
        When I create a content of this type
        And I set an empty value as the Field Value
        And I publish the content
        Then Publishing fails with validation error message "This field is required"

    @javascript
    Scenario: Creating a valid text line Field works when using a value within limited character scope
        Given a Content Type with a "text line" Field exists with Properties:
            | Validator                | Value |
            | minimum length validator | 2     |
            | maximum length validator | 4     |
        When I create a content of this Type
        And I set "LOL" as the Field Value
        And I publish the content
        Then the Content is successfully published

    @javascript
    Scenario: Creating an invalid text line Field fails validation when using a value smaller than minimum character limit allowed
        Given a Content Type with a "text line" Field exists with Properties:
            | Validator                | Value |
            | minimum length validator | 2     |
            | maximum length validator | 4     |
        When I create a content of this Type
        And I set "X" as the Field Value
        And I publish the content
        Then Publishing fails with validation error message "The value should have at least 2 characters"

    @javascript
    Scenario: Creating an invalid text line Field fails validation when using a value bigger than maximum character limit allowed
        Given a Content Type with a "text line" Field exists with Properties:
            | Validator                | Value |
            | minimum length validator | 2     |
            | maximum length validator | 4     |
        When I create a content of this Type
        And I set "Hipopotomonstrosesquipedaliofobia" as the Field Value
        And I publish the content
        Then Publishing fails with validation error message "The value should have at most 4 characters"

    ##
    # Update Content using a Content Type that has a text line Field Type
    ##
    @javascript
    Scenario: Updating a text line field using a text line Field works
        Given a Content Type with a "text line" Field exists
        And a Content of this type exists
        When I edit this content
        And I set "Test text update" as the Field Value
        And I publish the content
        Then the Content is successfully published

    @javascript
    Scenario: Updating a text line Field with an empty value works
        Given a Content Type with a "text line" Field exists
        When I create a content of this type
        And I set an empty value as the Field Value
        And I publish the content
        Then the Content is successfully published

    @javascript
    Scenario: Updating a valid text line Field works when using a value within limited character scope
        Given a Content Type with a "text line" Field exists with Properties:
            | Validator                | Value |
            | minimum length validator | 2     |
            | maximum length validator | 4     |
        When I edit this content
        And I set "TES" as the Field Value
        And I publish the content
        Then the Content is successfully published

    @javascript
    Scenario: Updating a text line Field fails validation when using a value smaller than minimum character limit allowed
        Given a Content Type with a "text line" Field exists with Properties:
            | Validator                | Value |
            | minimum length validator | 2     |
            | maximum length validator | 4     |
        When I edit this content
        And I set "X" as the Field Value
        And I publish the content
        Then Publishing fails with validation error message "The value should have at least 2 characters"

    @javascript
    Scenario: Updating a text line Field fails validation when using a value bigger than maximum character limit allowed
        Given a Content Type with a "text line" Field exists with Properties:
            | Validator                | Value |
            | minimum length validator | 2     |
            | maximum length validator | 4     |
        When I edit this content
        And I set "Hipopotomonstrosesquipedaliofobia" as the Field Value
        And I publish the content
        Then Publishing fails with validation error message "The value should have at most 4 characters"

    @javascript
    Scenario: Updating a required text line Field fails validation when using an empty value
        Given a Content Type with a required "text line" Field exists
        And a Content of this type exists
        When I edit this content
        And I set an empty value as the Field Value
        And I publish the content
        Then Publishing fails with validation error message "This field is required"

    ##
    # Viewing content that has a text line fieldtype
    ##
    @javascript
    Scenario: Viewing a Content that has a text line fieldtype should show the expected value when the value is positive
        Given a Content Type with a "text line" Field exists
        And a Content of this type exists with "text line" Field Value set to "Test text"
        When I view this Content
        Then I should see a field with value "Test text"

    @javascript
    Scenario: Viewing a Content that has a text line fieldtype should return "This field is empty" when the value is empty
        Given a Content Type with a "text line" Field exists
        And a Content of this type exists with "text line" Field Value set to empty
        When I view this Content
        Then I should see a field with value "This field is empty"
