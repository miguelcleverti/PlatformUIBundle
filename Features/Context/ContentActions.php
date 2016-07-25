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
        $this->selectFromUniversalDiscovery("eZ Platform/$destiny");
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
        $confirmButton = $this->findWithWait('.ez-confirmbox-confirm');
        $confirmButton->click();
    }

    /**
     * Cancel content removal.
     * @When I cancel the removal
     */
    public function cancelRemoval()
    {
        $cancelButton = $this->findWithWait('.ez-confirmbox-cancel');
        $cancelButton->click();
    }

    /**
     * @Then I am asked to confirm if I am sure that I want to send the content to trash
     */
    public function iSeeConfirmationBox()
    {
        $element = $this->findWithWait('.ez-confirmbox-title');
        if (!$element) {
            throw new \Exception('Confirmation box not found');
        }
    }

    /**
     * @Then I see a confirmation button
     */
    public function iSeeConfirmation()
    {
        $element = $this->findWithWait('.ez-confirmbox-confirm');
        if (!$element) {
            throw new \Exception('Confirmation button not found');
        }
    }

    /**
     * @Then I see a cancel button
     */
    public function iSeeCancel()
    {
        $element = $this->findWithWait('.ez-confirmbox-cancel');
        if (!$element) {
            throw new \Exception('Cancel button not found');
        }
    }
}
