<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 7/21/16
 * Time: 5:22 PM
 */

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ArrayOfBase64Binary {

  // You need to set only one from the following two vars

  /**
   * @var array[0, unbounded] of Plain Binary
   */
  public $base64Binary;

  /**
   * @var array[0, unbounded] of base64Binary
   */
  public $base64Binary_encoded;


}
