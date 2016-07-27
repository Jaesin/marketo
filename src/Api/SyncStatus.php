<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class SyncStatus {

  /**
   * @var int
   */
  public $leadId;

  /**
   * @var string
   *     NOTE: status should follow the following restrictions
   *     You can have one of the following value
   *     CREATED
   *     UPDATED
   *     UNCHANGED
   *     FAILED
   */
  public $status;

  /**
   * @var string
   */
  public $error;

}
