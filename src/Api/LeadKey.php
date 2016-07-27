<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class LeadKey {

  /**
   * @var string
   *     NOTE: keyType should follow the following restrictions
   *     You can have one of the following value
   *     IDNUM
   *     COOKIE
   *     EMAIL
   *     LEADOWNEREMAIL
   *     SFDCACCOUNTID
   *     SFDCCONTACTID
   *     SFDCLEADID
   *     SFDCLEADOWNERID
   *     SFDCOPPTYID
   */
  public $keyType;

  /**
   * @var string
   */
  public $keyValue;

}

