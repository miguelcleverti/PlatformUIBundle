<?php

/**
 * File containing the ContentType Functions for context class PlatformUI.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */
namespace EzSystems\PlatformUIBundle\Features\Context;

use Behat\Mink\WebAssert;
use Behat\Gherkin\Node\TableNode;

class ContentType extends PlatformUI
{

    /**
     * @Given i click in the :name Content type group
     * @Then I should see a :name ContentType group
     * @Then I should see a :name ContentType
     */
    public function iClickContentTypeGroup($name)
    {
        $this->clickElementByText($name, 'a');
    }

    /**
     * @When I edit the :group Content type group
     */
    public function iEditContentTypeGroup($group)
    {
        $groupElement = $this->getElementByText($group, '.ez-selection-table tbody tr', 'a');
        if ($groupElement) {
            $this->clickElementByText('Edit', 'a', null, $groupElement);
        } else {
            throw new \Exception("Content type group $group not found");
        }
    }

    /**
     * @Then I see the following ContentType fields:
     * @Then I see the following ContentType group fields:
     */
    public function iSeeContentTypeFields(TableNode $fields)
    {
        $fields = $fields->getRow(0);
        foreach ($fields as $field) {
            $element = $this->getElementByText($field, '.ez-selection-table th');
            if (!$element) {
                throw new \Exception("Content type field $field not found");
            }
        }
    }

    /**
     * @Given I add a field type :field with:
     */
    public function iAddFieldType($type, TableNode $fields)
    {
        $elements = $this->findAllWithWait('.pure-control-group');
        $element = $this->getElementContainsText('Field type selection', '.pure-control-group');
        $select = $element->find('css', 'select');
        $select->selectOption($type);
        $this->clickElementByText('Add field definition', '.ez-button', null, $element);
        $this->waitWhileLoading();
        $internalName = $this->getFieldTypeManager()->getFieldTypeInternalIdentifier($type);
        $formElement = $this->findWithWait(".field-type-$internalName");
        foreach ($fields as $field) {
            $formElement->fillField($field['Field'], $field['Value']);
        }
    }
}
