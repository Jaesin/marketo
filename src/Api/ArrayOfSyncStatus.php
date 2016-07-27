<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 7/21/16
 * Time: 5:25 PM
 */

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ArrayOfSyncStatus {

  /**
   * @var array[0, unbounded] of (object)SyncStatus
   */
  public $syncStatus;

}
