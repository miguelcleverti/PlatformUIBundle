<?php

/**
 * File containing the Common Functions for context class PlatformUI.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */
namespace EzSystems\PlatformUIBundle\Features\Context;

use Behat\Gherkin\Node\TableNode;

class Role extends PlatformUI
{
    /**
     * @var EzSystems\PlatformUIBundle\Features\Context\SubContext\DashboardContext
     */
    protected $dashboardContext;

    /**
     * @var EzSystems\PlatformUIBundle\Features\Context\SubContext\BrowserContext
     */
    protected $browserContext;

    /**
     * @Given I am on the Roles page
     */
    public function onRolesPage()
    {
        $this->getSession()->visit(
            $this->locatePath(
                self::PLATFORM_URI . '#/admin/pjax%2Frole'
            )
        );
    }

    /**
     * @Given I am on the RolesUI
     */
    public function onRolesUI()
    {
        $this->dashboardContext->clickNavigationZone('Admin Panel');
        $this->dashboardContext->clickNavigationItem('Roles');
    }

    /**
     * @Given I create a new role
     */
    public function createRole()
    {
        $this->clickElementByText('Create a role', ['ez-button']);
        $this->waitWhileLoading();
    }

    /**
     * @When I click on the Roles
     */
    public function clickRoles()
    {
        $this->clickElementByText('Roles', ['ez-navigation-item']);
        $this->waitWhileLoading();
    }

    /**
     * @When I edit the :name role name
     */
    public function iEditRole($name)
    {
        $xpath = "//*[contains(concat(' ', normalize-space(@class), ' '), ' ez-role ') and descendant-or-self::*//text()='$name']//*[contains(concat(' ', normalize-space(@class), ' '), ' ez-button ') and descendant-or-self::*//text()='Edit role name']";
        $element = $this->findElementXpath($xpath, $name);
        $element->click();
    }

    /**
     * @When I am viewing the :role role's details
     */
    public function roleDetailsView($role)
    {
        $this->onRolesUI();
        $this->clickElementByText($role, ['ez-role-name']);
    }

    /**
     * @Then I see the Roles page
     */
    public function iSeeRolePage()
    {
        $this->browserContext->iSeeTitle('Roles');
    }

    /**
     * @When I delete the :name role
     */
    public function deleteRole($name)
    {
        $this->clickElementByText($name, ['ez-role-name']);
        $this->clickElementByText('Delete', ['ez-button']);
    }

    /**
     * @Then I should see that there are no policies set up for this role
     */
    public function noPoliciesForThisRole()
    {
        $this->getElementByText('There are no policies set up for this role.');
    }

    /**
     * @Then I should see that this role has no Role Assigments
     */
    public function noAssigmentsForThisRole()
    {
        $this->getElementByText('This role is not assigned to any users or user groups.');
    }

    /**
     * @Then the Role is successfully published
     */
    public function roleWasPublished()
    {
        $this->dashboardContext->iSeeNotification('The role was published.');
    }

    /**
     * @Then the Role :name is not published
     * @Then the Role :name is successfully deleted
     */
    public function roleWasNotPublished($name)
    {
        $found = true;
        try {
            $element = $this->getElementByText($name, ['ez-role-name']);
        } catch (\Exception $e) {
            $found = false;
        }
        if ($found) {
            throw new \Exception("Role '$name' was found in the page");
        }
    }

    /**
     * @Then I see a message saying that the name :name already exists
     */
    public function nameAlreadyExists($name)
    {
        $this->dashboardContext->iSeeNotification('Form did not validate. Please review errors below.');
        $element = $this->getElementByText(
            'Identifier "' . $name . '" already exists. Role identifier must be unique.'
        );
    }

    /**
     * @Then I should see a label for the Role Assigments
     */
    public function roleAssigmentLabel()
    {
        $this->browserContext->iSeeTitle('Invalid argument: The role name must be unique.');
    }

    /**
     * @Then I see a message asking for the field :label to be filled
     */
    public function labelMustBeFilled($label)
    {
        //Empty for now, while the notification system is not working for the empty fields
    }

    /**
     * @Then I see the following roles with an :button button:
     */
    public function iSeeRolesList(TableNode $roles, $button)
    {
        foreach ($roles as $role) {
            $name = $role['Name'];
                $foundName = $this->getElementByText($name, ['ez-role-name']);
                $foundButton = $this->getElementByText($button, ['ez-button']);
        }
    }

    /**
     * @Then I should see a/an :label button
     */
    public function iSeeButton($label)
    {
        $element = $this->getElementByText($label, ['ez-button']);
    }

    /**
     * @Then I see the following limitations fields:
     * @Then I should see a group with the Role Assigments:
     */
    public function iSeeLimitationFields(TableNode $limitations)
    {
        $limitations = $limitations->getRow(0);
        foreach ($limitations as $limitation) {
            $element = $this->getElementByText($limitation);
        }
    }
}
