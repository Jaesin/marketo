<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 7/21/16
 * Time: 5:24 PM
 */

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ArrayOfLeadRecord {

  /**
   * @var array[0, unbounded] of (object)LeadRecord
   */
  public $leadRecord;

}
