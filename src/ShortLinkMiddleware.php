<?php

namespace Drupal\bit_short_link;

use Drupal\Core\Database\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Short link middleware.
 */
class ShortLinkMiddleware implements HttpKernelInterface {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The kernel.
   *
   * @var \Symfony\Component\HttpKernel\HttpKernelInterface
   */
  protected $httpKernel;

  /**
   * Constructs the FirstMiddleware object.
   *
   * @param \Symfony\Component\HttpKernel\HttpKernelInterface $http_kernel
   *   The decorated kernel.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(HttpKernelInterface $http_kernel, Connection $database) {
    $this->httpKernel = $http_kernel;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = TRUE) {
    $uri = ltrim($request->getRequestUri(), '/');
    $link_record = $this->getRedirect($uri);
    if (!empty($link_record['absolute_link'])) {
      return new RedirectResponse($link_record['absolute_link']);
    }

    return $this->httpKernel->handle($request, $type, $catch);
  }

  /**
   * {@inheritdoc}
   */
  public function getRedirect($uri) {
    return $this->database->query("SELECT * FROM {bit_short_link} WHERE short_url = :uri", [':uri' => $uri])->fetchAssoc();
  }

}
