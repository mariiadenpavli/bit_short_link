<?php

namespace Drupal\bit_short_link;

/**
 * Class ShortLinkService.
 */
class ShortLinkService implements ShortLinkServiceInterface {

  public $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

  /**
   *
   */
  public function idToUrl($id): string {
    $short_url = '';
    while ($id > 0) {
      $short_url .= $this->characters[$id % 62];
      $id = intval($id / 62);
    }

    return strrev($short_url);
  }

}
