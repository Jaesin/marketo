<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class AuthenticationHeaderInfo {

  /**
   * @var string
   */
  public $mktowsUserId;

  /**
   * @var string
   */
  public $requestSignature;

  /**
   * @var string
   */
  public $requestTimestamp;

  /**
   * @var string
   */
  public $audit;

  /**
   * @var int
   */
  public $mode;

}
