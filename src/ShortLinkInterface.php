<?php

namespace Drupal\bit_short_link;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining a bit_short_link entity.
 */
interface ShortLinkInterface extends ContentEntityInterface {

  /**
   * Gets the source absolute_link of the short_url.
   *
   * @return string
   *   The source absolute_link.
   */
  public function getAbsoluteLink();

  /**
   * Sets the source absolute_link of the short_url.
   *
   * @param string $absolute_link
   *   The source absolute_link.
   *
   * @return $this
   */
  public function setAbsoluteLink($absolute_link);

  /**
   * Gets the short_url for this absolute_link.
   *
   * @return string
   *   The short_url for this absolute_link.
   */
  public function getShortUrl();

  /**
   * Sets the short_url for this absolute_link.
   *
   * @param string $short_url
   *   The absolute_link short_url.
   *
   * @return $this
   */
  public function setShortUrl($short_url);

}
