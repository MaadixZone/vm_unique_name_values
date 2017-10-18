<?php

namespace Drupal\vm_unique_name_values\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

// @todo inject service entity query
// use Drupal\Core\Entity\Query\QueryFactory;
/**
 * Validates the MachineName constraint.
 */
class MachineNameConstraintValidator extends ConstraintValidator {
  /**
   * Drupal\Core\Entity\Query\QueryFactory definition.
   *
   * @var Drupal\Core\Entity\Query\QueryFactory
   */
  // @todo Inject service entity query
  /*protected $entityQuery;

  public function __construct(QueryFactory $entityQuery) {
  $this->entityQuery = $entityQuery;
  }

  public static function create(ContainerInterface $container) {
  return new static(
  $container->get('entity.query')
  );
  }
   */

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    // ddl($items);
    $item = $items->first();
    if (!isset($item)) {
      return NULL;
    }
    /** @var \Drupal\Core\Entity\EntityInterface $entity */
    $entity = $items->getEntity();

    if ($item->value) {
      // Verify That is not in reserved names:
      $reserved_names = ["reserved", "pirate_as_me"];
      // @todo validate against configuration list entered by form,
      // or against an external api.
      if (in_array($item->value, $reserved_names)) {
        $this->context->addViolation($constraint->reservedMessage);
      }

      // @todo inject service
      // $entityQuery = $this->container->get('entity.query');
      // Verify that a non-existent name is entered.
      // $query = $entityQuery->get('commerce_order_item');
      $query = \Drupal::entityQuery('commerce_order_item');
      $query->condition('machine_name_vm', $item->value);
      if (!empty($item->getEntity()->id())) {
        $query->condition('order_item_id', $item->getEntity()->id(), '!=');
      }
      $query->condition('order_id.entity.state.value', ['validation', 'completed'], 'IN');
      $existent_count = $query->count()->execute();
      if ($existent_count > 0) {
        $this->context->addViolation($constraint->existsMessage);
      }
    }

  }

}
