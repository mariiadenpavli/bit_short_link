<?php

namespace Drupal\bit_short_link\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks if an entity field has a unique value.
 *
 * @Constraint(
 *   id = "UniqueShortLinkValue",
 *   label = @Translation("Unique Value constraint", context = "Validation"),
 * )
 */
class UniqueShortLinkValueConstraint extends Constraint {

  public $message = 'The %absolute_link is already in use.';

  public $differentCapitalizationMessage = 'The %absolute_link could not be added because it is already in use with different capitalization: %stored_absolute_link.';

  /**
   * {@inheritdoc}
   */
  public function validatedBy() {
    return '\Drupal\bit_short_link\Plugin\Validation\Constraint\UniqueShortLinkValueConstraintValidator';
  }

}
