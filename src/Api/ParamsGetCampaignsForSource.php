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
class ParamsGetCampaignsForSource {

  /**
   * @var string
   *     NOTE: source should follow the following restrictions
   *     You can have one of the following value
   *     MKTOWS
   *     SALES
   */
  public $source;

  /**
   * @var string
   */
  public $name;

  /**
   * @var boolean
   */
  public $exactName;

}
