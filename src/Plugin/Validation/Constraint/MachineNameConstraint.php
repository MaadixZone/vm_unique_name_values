<?php

namespace Drupal\vm_unique_name_values\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that the node is assigned only a "leaf" term in the forum taxonomy.
 *
 * @Constraint(
 *   id = "MachineName",
 *   label = @Translation("Machine name", context = "Validation"),
 * )
 */
class MachineNameConstraint extends Constraint {

  public $enterMachineName = 'Enter a machine name.';
  public $existsMessage = 'The machine name exists, please select another one.';
  public $reservedMessage = 'The machine name is reserved, please select another one.';

}
