<?php
/**
 * File containing the Language context class for PlatformUI.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

namespace EzSystems\PlatformUIBundle\Features\Context;

use EzSystems\PlatformUIBundle\Features\Context\PlatformUI;
use Behat\Gherkin\Node\TableNode;

class Languages extends PlatformUI
{

    /**
     * @Given I see the Languages page
     */
    public function iSeeTheLanguagesPage()
    {
        $this->sleep();
        $this->iSeeTitle('Languages');
    }

    /**
     * @Then I see the :link link
     */
    public function iSeeLink($link)
    {
        $this->getElementContainsText($link, 'a');
    }

    /**
     * @Then I should see the newly created/edited language with:
     */
    public function iSeeTheLanguage(TableNode $fields)
    {
        foreach ($fields as $field) {
            $languageName = $field['Language name'];
            $languageCode = $field['Language code'];
            $isEnabled = $field['Enabled'];
        }
        $isEnabled = filter_var($isEnabled, FILTER_VALIDATE_BOOLEAN);

        $tableRow = $this->getElementContainsText($languageName, '.ez-selection-table-row');
        $tableCols = $this->findAllWithWait('td', $tableRow);

        if ($languageName != $tableCols[0]->getText() ||
           $languageCode != $tableCols[1]->getText() ||
           $isEnabled != ($tableCols[3]->getAttribute('checked') == null)) {
            throw new \Exception("Language '$languageName' couldn't be created or edited");
        }
    }

    /**
     * @Given I edit the language with name :languageName
     */
    public function iEditTheLanguage($languageName)
    {
        $tableRow = $this->getElementContainsText($languageName, '.ez-selection-table-row');
        if (!$tableRow) {
            throw new \Exception("Couldn't find '$languageName'!");
        }
        $editButton = $tableRow->find('css', '.ez-button');
        $editButton->click();
    }

    /**
     * @Given I delete the language with name :languageName
     */
    public function iDeleteTheLanguage($languageName)
    {
        $tableRow = $this->getElementContainsText($languageName, '.ez-selection-table-row');
        if (!$tableRow) {
            throw new \Exception("Couldn't find '$languageName'!");
        }
        $deleteButton = $tableRow->find('css', '#ezrepoforms_language_delete_delete');
        $deleteButton->click();
    }

    /**
     * @Then I shouldn't see the previously created language with:
     */
    public function iDontSeeTheLanguage(TableNode $fields)
    {
        foreach ($fields as $field) {
            $languageName = $field['Language name'];
            $languageCode = $field['Language code'];
            $isEnabled = $field['Enabled'];
        }
        $isEnabled = $isEnabled == 'true';

        $tableRow = $this->getElementContainsText($languageName, '.ez-selection-table-row');
        if (!$tableRow) {
            // language was successfully deleted
            return;
        }
        $tableCols = $tableRow->findAll('css', 'td');

        if ($languageName == $tableCols[0]->getText() &&
           $languageCode == $tableCols[1]->getText() &&
           $isEnabled == ($tableCols[3]->getAttribute('checked') == null)) {
            throw new \Exception("Language '$languageName' couldn't be deleted");
        }
    }

    /**
     * @Then I verify that the :textfield text field is disabled
     */
    public function iVerifyTextFieldDisabled($textField)
    {
        $container = $this->getElementContainsText($textField, '.pure-control-group');
        $textField = $container->find('css', 'input');
        if (!$textField->hasAttribute('disabled')) {
            throw \Exception("Text field '$textField' is not disabled");
        }
    }
}
