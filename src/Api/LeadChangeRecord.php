<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class LeadChangeRecord {

  /**
   * @var int
   */
  public $id;

  /**
   * @var dateTime
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
  public $mktPersonId;

}
