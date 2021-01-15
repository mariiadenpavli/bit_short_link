<?php

namespace Drupal\bit_short_link;

/**
 * Interface ShortLinkServiceInterface.
 */
interface ShortLinkServiceInterface {

  /**
   * Short Link.
   *
   * @param int $id
   *   The generated id.
   *
   * @return $this
   *   The class instance that this method is called on.
   */
  public function idToUrl(int $id): string;

}
