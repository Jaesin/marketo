<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ListKey {

  /**
   * @var string
   *     NOTE: keyType should follow the following restrictions
   *     You can have one of the following value
   *     MKTOLISTNAME
   *     MKTOSALESUSERID
   *     SFDCLEADOWNERID
   */
  public $keyType;

  /**
   * @var string
   */
  public $keyValue;

}
