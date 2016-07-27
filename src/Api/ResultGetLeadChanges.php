<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ResultGetLeadChanges {

  /**
   * @var int
   */
  public $returnCount;

  /**
   * @var int
   */
  public $remainingCount;

  /**
   * @var (object)StreamPosition
   */
  public $newStartPosition;

  /**
   * @var (object)ArrayOfLeadChangeRecord
   */
  public $leadChangeRecordList;

}
