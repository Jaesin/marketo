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
class ParamsListOperation {

  /**
   * @var string
   *     NOTE: listOperation should follow the following restrictions
   *     You can have one of the following value
   *     ADDTOLIST
   *     ISMEMBEROFLIST
   *     REMOVEFROMLIST
   */
  public $listOperation;

  /**
   * @var (object)ListKey
   */
  public $listKey;

  /**
   * @var (object)ArrayOfLeadKey
   */
  public $listMemberList;

  /**
   * @var boolean
   */
  public $strict;

}
