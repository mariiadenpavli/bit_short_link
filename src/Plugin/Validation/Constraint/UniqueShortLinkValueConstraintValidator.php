<?php

namespace Drupal\bit_short_link\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates that a field is unique for the given entity type.
 */
class UniqueShortLinkValueConstraintValidator extends ConstraintValidator implements ContainerInjectionInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Creates a new UniqueShortLinkValueConstraintValidator instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function validate($entity, Constraint $constraint) {
    /** @var \Drupal\bit_short_link\ShortLinkInterface $entity */
    $absolute_link = $entity->getAbsoluteLink();

    $storage = $this->entityTypeManager->getStorage('bit_short_link');
    $query = $storage->getQuery()
      ->accessCheck(FALSE)
      ->condition('absolute_link', $absolute_link, '=');

    if (!$entity->isNew()) {
      $query->condition('id', $entity->id(), '<>');
    }

    if ($result = $query->range(0, 1)->execute()) {
      $existing_absolute_link_id = reset($result);
      $existing_absolute_link = $storage->load($existing_absolute_link_id);

      if ($existing_absolute_link->getAbsoluteLink() !== $absolute_link) {
        $this->context->buildViolation($constraint->differentCapitalizationMessage, [
          '%absolute_link' => $absolute_link,
          '%stored_absolute_link' => $existing_absolute_link->getAbsoluteLink(),
        ])->addViolation();
      }
      else {
        $this->context->buildViolation($constraint->message, [
          '%absolute_link' => $absolute_link,
        ])->addViolation();
      }
    }
  }

}
