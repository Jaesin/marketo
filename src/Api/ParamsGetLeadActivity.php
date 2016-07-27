<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 7/21/16
 * Time: 5:28 PM
 */

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ParamsGetLeadActivity {

  /**
   * @var (object)LeadKey
   */
  public $leadKey;

  /**
   * @var (object)ActivityTypeFilter
   */
  public $activityFilter;

  /**
   * @var (object)StreamPosition
   */
  public $startPosition;

  /**
   * @var int
   */
  public $batchSize;

}
