<?php

/**
 * File containing the Copy Functions for context class PlatformUI.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */
namespace EzSystems\PlatformUIBundle\Features\Context;

class ContentActions extends PlatformUI
{
    /**
     * @Then I am notified that :name has been copied under :destiny
     */
    public function iSeeCopiedNotification($name, $destiny)
    {
        $message = "'$name' has been successfully copied under '$destiny'";
        $this->iSeeNotification($message);
    }

    /**
     * @When I move :name into the :destiny folder
     */
    public function moveInto($name, $destiny)
    {
        $this->onFullView($name);
        $this->clickActionBar('Move');
        $this->selectFromUniversalDiscovery("Home/$destiny");
        $this->confirmSelection();
        $destinyName = explode('/', $destiny);
        $destinyName = end($destinyName);
        $this->iSeeMovedNotification($name, $destinyName);
        $this->mapContentPath($name, "$destiny/$name");
    }

    /**
     * @Then :name is moved
     */
    public function contentIsMoved($name)
    {
        $path = $this->getContentPath($name);
        $this->goToContentWithPath($path);
    }

    /**
     * @Then I am notified that :name has been moved under :destiny
     */
    public function iSeeMovedNotification($name, $destiny)
    {
        $message = "'$name' has been successfully moved under '$destiny'";
        $this->iSeeNotification($message);
    }

    /**
     * @When I remove :name
     */
    public function removeContent($name)
    {
        $this->onFullView($name);
        $this->waitWhileLoading();
        $this->clickActionBar('Send to Trash');
    }

    /**
     * Confirm content removal.
     * @When I confirm the removal
     */
    public function confirmRemoval()
    {
        $this->clickElementByText('Confirm', '.ez-confirmbox-confirm');
    }

    /**
     * Cancel content removal.
     * @When I cancel the removal
     */
    public function cancelRemoval()
    {
        $this->clickElementByText('Cancel', '.ez-confirmbox-cancel');
    }

    /**
     * @Then I am asked to confirm if I am sure that I want to send the content to trash
     */
    public function iSeeConfirmationBox()
    {
        $element = $this->getElementByText(
            'Are you sure you want to send this content to trash?',
            '.ez-confirmbox-title'
        );
        if (!$element) {
            throw new \Exception('Confirmation box not found');
        }
    }

    /**
     * @Then I see a confirmation button
     */
    public function iSeeConfirmation()
    {
        $element = $this->getElementByText('Confirm', '.ez-confirmbox-confirm');
        if (!$element) {
            throw new \Exception('Confirmation button not found');
        }
    }

    /**
     * @Then I see a cancel button
     */
    public function iSeeCancel()
    {
        $element = $this->getElementByText('Cancel', '.ez-confirmbox-cancel');
        if (!$element) {
            throw new \Exception('Cancel button not found');
        }
    }

    /**
     * @When I select :chosenPath location checkbox
     */
    public function selectLocationCheckBox($chosenPath)
    {
        $table = $this->getSession()->getPage()->findAll('css', '.ez-locations-list-table tr');
        $chosenPath = str_replace('/', ' ', $chosenPath);
        foreach ($table as $row) {
            $path = $row->find('css', '.ez-breadcrumbs-list');
            if ($path && ($path->getText() == $chosenPath)) {
                $button = $row->find('css', ".ez-location-checkbox");
                $button->check();
            }
        }
    }

    /**
     * @When I change the :chosenPath location visibility
     */
    public function changeLocationVisibility($chosenPath)
    {
        $table = $this->getSession()->getPage()->findAll('css', '.ez-locations-list-table tr');
        $chosenPath = str_replace('/', ' ', $chosenPath);
        $foundButton = false;
        foreach ($table as $row) {
            $path = $row->find('css', '.ez-breadcrumbs-list');
            if ($path) {
                $path = $path->getText();
                if ($path == $chosenPath) {
                    $button = $row->find('css', ".ez-locations-hidden-button");
                    $button->click();
                    $foundButton = true;
                }
            }
        }

        if (!$foundButton) {
            throw new \Exception("Location with path '$chosenPath' not found");
        }
    }

    /**
     * @Then the :chosenPath visibility is :visibility
     */
    public function verifyLocationVisibility($chosenPath, $visibility)
    {
        $table = $this->getSession()->getPage()->findAll('css', '.ez-locations-list-table tr');
        $chosenPath = str_replace('/', ' ', $chosenPath);
        foreach ($table as $row) {
            $path = $row->find('css', '.ez-breadcrumbs-list');
            if ($path) {
                $path = $path->getText();
                if ($path == $chosenPath) {
                    $this->sleep();
                    $this->sleep();
                    $pageVisibility = $row->find('css', ".ez-table-data-visibility")->getText();
                    if ($pageVisibility != $visibility) {
                        throw new \Exception("Visibility was not expected '$visibility'");
                    }
                }
            }
        }
    }

    /**
     * @Then the child content visibility is :visibility
     */
    public function verifySubContentVisibility($visibility)
    {
        $rowList = $this->getSession()->getPage()->findAll('css', '.ez-subitemlist-table tbody tr');
        foreach ($rowList as $row) {
            $rowVisibility = $row->getText();
            if (!strpos($rowVisibility, $visibility)) {
                throw new \Exception("Child Visibility was not '$visibility'");
            }
        }
    }


    /**
     * @Given I change the content :content main location to the path :newPath
     */
    public function changeContentMainLocation($content, $newPath)
    {
        $table = $this->getSession()->getPage()->findAll('css', '.ez-locations-list-table tr');
        $newPath = str_replace('/', ' ', $newPath);
        foreach ($table as $t) {
            $path = $t->find('css', '.ez-breadcrumbs-list');
            if ($path) {
                if ($path->getText() == $newPath) {
                    $radioButton = $t->find('css', ".ez-main-location-radio");
                    $radioButton->click();
                    $this->waitWhileLoading();
                    $this->clickElementByText('Confirm', 'button');
                    break;
                }
            }
        }
    }

    /**
     * @Then I verify that the main location of the content :content is :newPath
     */
    public function verifyContentMainLocation($content, $newPath)
    {
        $table = $this->getSession()->getPage()->findAll('css', '.ez-locations-list-table tr');
        $newPath = str_replace('/', ' ', $newPath);
        foreach ($table as $t) {
            $path = $t->find('css', '.ez-breadcrumbs-list');
            if ($path) {
                if ($path->getText() == $newPath) {
                    $radioButton = $t->find('css', ".ez-main-location-radio");
                    if (!$radioButton->isChecked()) {
                        throw new \Exception("Content '$content' doesn't have the correct main location!");
                    }
                    break;
                }
            }
        }
    }
}
