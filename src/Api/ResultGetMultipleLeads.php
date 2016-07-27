<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ResultGetMultipleLeads {

  /**
   * @var int
   */
  public $returnCount;

  /**
   * @var int
   */
  public $remainingCount;

  /**
   * @var string
   */
  public $newStreamPosition;

  /**
   * @var (object)ArrayOfLeadRecord
   */
  public $leadRecordList;

}
