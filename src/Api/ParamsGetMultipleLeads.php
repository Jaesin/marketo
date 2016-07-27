<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 7/21/16
 * Time: 5:29 PM
 */

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ParamsGetMultipleLeads {

  /**
   * @var dateTime
   */
  public $lastUpdatedAt;

  /**
   * @var string
   */
  public $streamPosition;

  /**
   * @var int
   */
  public $batchSize;

}
