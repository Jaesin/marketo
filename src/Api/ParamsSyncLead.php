<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 7/21/16
 * Time: 5:35 PM
 */

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ParamsSyncLead {

  /**
   * @var (object)LeadRecord
   */
  public $leadRecord;

  /**
   * @var boolean
   */
  public $returnLead;

  /**
   * @var string
   */
  public $marketoCookie;

}
