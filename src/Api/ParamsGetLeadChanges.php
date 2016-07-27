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
class ParamsGetLeadChanges {

  /**
   * @var (object)StreamPosition
   */
  public $startPosition;

  /**
   * @var (object)ActivityTypeFilter
   */
  public $activityFilter;

  /**
   * @var int
   */
  public $batchSize;

}
