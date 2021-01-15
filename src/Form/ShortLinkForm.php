<?php

namespace Drupal\bit_short_link\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form handler for the short link edit forms.
 *
 * @internal
 */
class ShortLinkForm extends ContentEntityForm {
  /**
   * Drupal\bit_short_link\ShortLinkServiceInterface definition.
   *
   * @var \Drupal\bit_short_link\ShortLinkServiceInterface
   */
  protected $shortener;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->shortener = $container->get('bit_short_link.shortener');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = t('Create a short link');

    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->save();
    if ($entity->id()) {
      $entity->setShortUrl($this->shortener->idToUrl($entity->id()));
      $entity->save();
      $form_state->setRedirect(
        'entity.bit_short_link.canonical',
        ['bit_short_link' => $entity->id()]
      );
    }
  }

}
