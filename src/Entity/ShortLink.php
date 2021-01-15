<?php

namespace Drupal\bit_short_link\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\bit_short_link\ShortLinkInterface;
use Drupal\Core\Url;

/**
 * Defines the bit_short_link entity class.
 *
 * @ContentEntityType(
 *   id = "bit_short_link",
 *   label = @Translation("Short link"),
 *   label_collection = @Translation("Short links"),
 *   label_singular = @Translation("Short link"),
 *   label_plural = @Translation("Short links"),
 *   label_count = @PluralTranslation(
 *     singular = "@count Short link",
 *     plural = "@count Short links"
 *   ),
 *   handlers = {
 *     "view_builder" = "Drupal\bit_short_link\ShortLinkViewBuilder",
 *     "form" = {
 *       "default" = "Drupal\bit_short_link\Form\ShortLinkForm",
 *     },
 *    "access" = "Drupal\bit_short_link\ShortLinkAccessControlHandler",
 *   },
 *   base_table = "bit_short_link",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 *   links = {
 *     "canonical" = "/shortlink/{bit_short_link}",
 *   },
 *   constraints = {
 *     "UniqueShortLinkValue" = {}
 *   }
 * )
 */
class ShortLink extends ContentEntityBase implements ShortLinkInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['absolute_link'] = BaseFieldDefinition::create('string_long')
      ->setLabel(new TranslatableMarkup('Absolute link'))
      ->setDescription(new TranslatableMarkup('Enter absolute link to create a short link'))
      ->setRequired(TRUE)
      ->addPropertyConstraints('value', [
        'Regex' => [
          'pattern' => "
            /^                                                      # Start at the beginning of the text
            (?:ftp|https?|feed):\/\/                                # Look for ftp, http, https or feed schemes
            (?:                                                     # Userinfo (optional) which is typically
              (?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*      # a username or a username and password
              (?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@          # combination
            )?
            (?:
              (?:[a-z0-9\-\.]|%[0-9a-f]{2})+                        # A domain name or a IPv4 address
              |(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\])         # or a well formed IPv6 address
            )
            (?::[0-9]+)?                                            # Server port number (optional)
            (?:[\/|\?]
              (?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})   # The path and query (optional)
            *)?
          $/xi",
          'message' => new TranslatableMarkup('Unable to shorten that link. It is not a valid url.'),
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['short_url'] = BaseFieldDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Short URL'))
      ->setDescription(new TranslatableMarkup('An short URL used with this absolute link.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getAbsoluteLink() {
    return $this->get('absolute_link')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAbsoluteLink($absolute_link) {
    $this->set('absolute_link', $absolute_link);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getShortUrl() {
    return $this->get('short_url')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setShortUrl($short_url) {
    $this->set('short_url', $short_url);
    return $this;
  }

  /**
   *
   */
  public function getAbsoluteShortUrl() {
    return Url::fromUserInput('/' . $this->getShortUrl(), ['absolute' => TRUE])->toString();
  }

}
