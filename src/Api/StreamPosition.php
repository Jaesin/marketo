<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class StreamPosition {

  /**
   * @var dateTime
   */
  public $latestCreatedAt;

  /**
   * @var dateTime
   */
  public $oldestCreatedAt;

  /**
   * @var dateTime
   */
  public $activityCreatedAt;

  /**
   * @var string
   */
  public $offset;

}
