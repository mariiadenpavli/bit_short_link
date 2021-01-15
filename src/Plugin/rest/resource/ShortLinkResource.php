<?php

namespace Drupal\bit_short_link\Plugin\rest\resource;

use Drupal\Core\Database\Database;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a resource for short url entry.
 *
 * @RestResource(
 *   id = "bit_short_link",
 *   label = @Translation("Short url info"),
 *   uri_paths = {
 *     "canonical" = "/api/view/{short_url}"
 *   }
 * )
 */
class ShortLinkResource extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * Returns a short url entry for the specified ID.
   *
   * @param string $short_url
   *   The ID of the short url entry.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the entry.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   *   Thrown when the entry was not found.
   * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
   *   Thrown when no entry was provided.
   */
  public function get($short_url) {
    if ($short_url) {
      $record = Database::getConnection()->query("SELECT * FROM {bit_short_link} WHERE short_url = :uri", [':uri' => $short_url])->fetchAssoc();
      if (!empty($record)) {
        $build = [
          '#cache' => [
            'max-age' => 0,
          ],
        ];
        return (new ResourceResponse(['data' => $record], 200))->addCacheableDependency($build);
      }
      throw new NotFoundHttpException("Short url with URL '$short_url' was not found");
    }
    throw new BadRequestHttpException('No short url entry was provided');
  }

}
