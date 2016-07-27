<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ActivityRecord {

  /**
   * @var int
   */
  public $id;

  /**
   * @var \DateTime
   */
  public $activityDateTime;

  /**
   * @var string
   */
  public $activityType;

  /**
   * @var string
   */
  public $mktgAssetName;

  /**
   * @var (object)ArrayOfAttribute
   */
  public $activityAttributes;

  /**
   * @var string
   */
  public $campaign;

  /**
   * @var string
   */
  public $personName;

  /**
   * @var string
   */
  public $mktPersonId;

  /**
   * @var string
   */
  public $foreignSysId;

  /**
   * @var string
   */
  public $orgName;

  /**
   * @var string
   */
  public $foreignSysOrgId;

}
