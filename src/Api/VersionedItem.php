<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class VersionedItem {

  /**
   * @var integer
   */
  public $id;

  /**
   * @var string
   */
  public $name;

  /**
   * @var string
   */
  public $type;

  /**
   * @var string
   */
  public $description;

  /**
   * @var dateTime
   */
  public $timestamp;

}

