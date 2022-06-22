<?php

namespace UniversityOfAdelaide\OpenShift;

use Exception;

/**
 * OpenShift client exception.
 */
class ClientException extends Exception {

  /** @var string */
  private string $body;

  public function __construct(
    $message,
    $code,
    Exception $previous = NULL,
    string $body = ''
  ) {
    parent::__construct($message, $code, $previous);
    $this->body = $body;
  }

  /**
   * Get the associated response body.
   *
   * @return string|null
   *   Returns the response body if set, null otherwise.
   */
  public function getBody(): ?string {
    return $this->body;
  }

  /**
   * Check if a response body is set.
   *
   * @return bool
   *   True if the body is set.
   */
  public function hasBody(): bool {
    return $this->body !== NULL;
  }

}
