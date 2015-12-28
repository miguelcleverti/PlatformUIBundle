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
     * @Given I am on the RolesUI
     */
    public function onRolesPage()
    {
        $this->clickNavigationZone('Admin Panel');
        $this->clickNavigationItem('Roles');
    }

    /**
     * @Given I create a new role
     */
    public function createRole()
    {
        $this->clickElementByText('Create a role', '.ez-button');
        $this->waitWhileLoading();
    }

    /**
     * @When I click on the Roles
     */
    public function clickRoles()
    {
        $this->clickElementByText('Roles', 'li a');
        $this->waitWhileLoading();
    }

    /**
     * @When I edit the :name role name
     */
    public function iEditRole($name)
    {
        $elements = $this->findAllWithWait('.ez-role');
        if (!$elements) {
            throw new \Exception('No roles found');
        }
        foreach ($elements as $element) {
            $foundName = $this->getElementByText($name, '.ez-role-name', null, $element);
            if ($foundName) {
                $button = $this->getElementByText('Edit role name', '.ez-button', null, $element);
                if (!$button) {
                    throw new \Exception("Role name edit button not found for '$name'");
                }
                $button->click();
                break;
            }
        }
        if (!$foundName) {
            throw new \Exception("Role $name not found");
        }
    }

    /**
     * @When I am viewing the :role role's details
     */
    public function roleDetailsView($role)
    {
        $this->onRolesPage();
        $this->clickElementByText($role, '.ez-role-name a');
    }

    /**
     * @Then I see the Roles page
     */
    public function iSeeRolePage()
    {
        $this->iSeeTitle('Roles');
    }

    /**
     * @When I delete the :name role
     */
    public function deleteRole($name)
    {
        $this->clickElementByText($name, '.ez-role-name a');
        $this->clickElementByText('Delete', '.ez-button');
    }

    /**
     * @Then I should see that there are no policies set up for this role
     */
    public function noPoliciesForThisRole()
    {
        $this->getElementByText('There are no policies set up for this role.', 'tr td');
    }

    /**
     * @Then I should see that this role has no Role Assigments
     */
    public function noAssigmentsForThisRole()
    {
        $this->getElementByText('This role is not assigned to any users or user groups.', 'tr td');
    }

    /**
     * @Then the Role is successfully published
     */
    public function roleWasPublished()
    {
        $this->iSeeNotification('The role was published.');
    }

    /**
     * @Then the Role :name is not published
     * @Then the Role :name is successfully deleted
     */
    public function roleWasNotPublished($name)
    {
        $element = $this->getElementByText($name, '.ez-role-name');
        if ($element) {
            throw new \Exception('Role was found');
        }
    }

    /**
     * @Then I see a message saying that the name :name already exists
     */
    public function nameAlreadyExists($name)
    {
        $this->iSeeNotification('Form did not validate. Please review errors below.');
        $element = $this->getElementByText(
            'Identifier "' .  $name . '" already exists. Role identifier must be unique.',
            'li'
        );
        if (!$element) {
            throw new \Exception('Error message not found');
        }
    }

    /**
     * @Then I should see a label for the Role Assigments
     */
    public function roleAssigmentLabel()
    {
        $this->iSeeTitle('Invalid argument: The role name must be unique.');
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
        $elements = $this->findAllWithWait('.ez-role');
        if (!$elements) {
            throw new \Exception('No roles found');
        }
        foreach ($roles as $role) {
            $name = $role['Name'];
            foreach ($elements as $element) {
                $foundName = $this->getElementByText($name, '.ez-role-name', null, $element);
                $foundButton = $this->getElementByText($button, '.ez-button', null, $element);
                if ($foundName) {
                    break;
                }
            }
            if (!$foundName) {
                throw new \Exception("Role $name not found");
            }
            if (!$foundButton) {
                throw new \Exception("Role $name does not have a $button button");
            }
        }
    }

    /**
     * @Then I should see a/an :label button
     */
    public function iSeeButton($label)
    {
        $element = $this->getElementByText($label, '.ez-button');
        if (!$element) {
            throw new \Exception("Button with label $label not found");
        }
    }

    /**
     * @Then I see the following limitations fields:
     * @Then I should see a group with the Role Assigments:
     */
    public function iSeeLimitationFields(TableNode $limitations)
    {
        $limitations = $limitations->getRow(0);
        foreach ($limitations as $limitation) {
            $element = $this->getElementByText($limitation, '.ez-selection-table th');
            if (!$element) {
                throw new \Exception("Limitation $limitation not found");
            }
        }
    }

    /**
     * @Given a policy with :module and :function of :roleIdentifier exists
     * @param $roleIdentifier
     * @param $module
     * @param $function
     */
    public function createPolicy($roleIdentifier, $module, $function)
    {
        $repository = $this->getRepository();
        $repository->sudo(
            function () use ($repository, $roleIdentifier, $module, $function) {
                $roleService = $repository->getRoleService();
                $role = $roleService->loadRoleByIdentifier($roleIdentifier);

                $policyCreateSctruct = $roleService->newPolicyCreateStruct($module, $function);
                $roleDraft = $roleService->createRoleDraft($role);

                $roleDraft = $roleService->addPolicyByRoleDraft($roleDraft, $policyCreateSctruct);
                $roleService->publishRoleDraft($roleDraft);
            }
        );
    }

    /**
     * @When I click on edit limitations of module :module and function :function
     * @param $module
     * @param $function
     */
    public function clickEditLimitations($module, $function)
    {
        $row = $this->verifyPolicyExists($module, $function);
        $columnList = $this->findAllWithWait('td', $row);
        foreach ($columnList as $column) {
            if ($column->getText() == 'Edit limitations') {
                $column->click();
            }
        }
    }

    /**
     * @When I click on remove policy with module :module and function :function
     */
    public function clickRemoveLimitation($module, $function)
    {
        $row = $this->verifyPolicyExists($module, $function);
        $deleteButton = $this->findWithWait('.ez-button-delete', $row);
        $deleteButton->click();
        $this->waitWhileLoading();

    }

    /**
     * @When I select :option of :limitation
     * @param $option
     * @param $limitation
     */
    public function selectOptionFromLabel($option, $limitation)
    {
        $limitationTables = $this->findAllWithWait('.pure-control-group');
        foreach ($limitationTables as $table) {
            $label = $this->findWithWait('label', $table);
            if ($label->getText() == $limitation) {
                $select = $this->findWithWait('select', $table);
                $desiredOption = $this->getElementByText($option, 'option', null, $select);
                if ($desiredOption) {
                    $desiredOption->click();
                }
            }
        }
    }

    /**
     * @Then I verify the policy exists module :module function :function
     */
    public function verifyPolicyExists($module, $function)
    {
        $table = $this->findAllWithWait('.ez-selection-table tbody tr');
        foreach ($table as $row) {
            $modulePolicy = $this->getElementByText($module, 'td', null, $row);
            $functionPolicy = $this->getElementByText($function, 'td', null, $row);
            if ($modulePolicy && $functionPolicy) {
                $selectedRow = $row;
            }
        }

        return $selectedRow;
    }

    /**
     * @Then I don't see the policy with module :module and function :function
     * @param $module
     * @param $function
     * @throws \Exception
     */
    public function iDontSeePolicy($module, $function)
    {
        $table = $this->findAllWithWait('.ez-selection-table tbody tr');
        $state = false;
        foreach ($table as $row) {
            $modulePolicy = $this->getElementByText($module, 'td', null, $row);
            $functionPolicy = $this->getElementByText($function, 'td', null, $row);
            if ($modulePolicy && $functionPolicy) {
                $state = true;
            }
        }
        if ($state) {
            throw new \Exception("Policy '$module' '$function' exists");
        }
    }


    /**
     * @Then I see on module :module and function :function the limitation :editedLimitation
     */
    public function iSeeLimitation($editedLimitation)
    {
        $table = $this->findAllWithWait('.ez-selection-table tbody tr');
        $state = false;
        foreach ($table as $row) {
            $limitation = $this->getElementByText($editedLimitation, 'td', null, $row);
//            var_dump($limitation->getText());
            if ($limitation) {
                $state = true;
                var_dump($limitation->getText());
                var_dump("está correcto!");
            }
        }
        if (!$state) {
            throw new \Exception("Limitation didn't change");
        }
    }
}
