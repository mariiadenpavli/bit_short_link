<?php

namespace Drupal\bit_short_link;

use Drupal\Core\Entity\EntityViewBuilder;

/**
 * Render controller for short link messages.
 */
class ShortLinkViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildComponents(array &$build, array $entities, array $displays, $view_mode) {
    parent::buildComponents($build, $entities, $displays, $view_mode);
    foreach ($entities as $id => $entity) {
      $build[$id]['short_url'] = [
        '#title' => t('Short Link'),
        '#type' => 'item',
        '#markup' => $entity->getAbsoluteShortUrl(),
      ];
    }
    return $build;
  }

}
