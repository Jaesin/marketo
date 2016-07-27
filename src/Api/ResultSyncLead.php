<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ResultSyncLead {

  /**
   * @var int
   */
  public $leadId;

  /**
   * @var string
   *     NOTE: syncStatus should follow the following restrictions
   *     You can have one of the following value
   *     CREATED
   *     UPDATED
   *     FAILED
   */
  public $syncStatus;

  /**
   * @var (object)LeadRecord
   */
  public $leadRecord;

}
