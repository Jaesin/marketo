<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 7/21/16
 * Time: 5:13 PM
 */

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class LeadRecord {

  /**
   * @var int
   */
  public $Id;

  /**
   * @var string
   */
  public $Email;

  /**
   * @var string
   */
  public $ForeignSysPersonId;

  /**
   * @var string
   *     NOTE: ForeignSysType should follow the following restrictions
   *     You can have one of the following value
   *     CUSTOM
   *     SFDC
   *     NETSUITE
   */
  public $ForeignSysType;

  /**
   * @var (object)ArrayOfAttribute
   */
  public $leadAttributeList;

}
