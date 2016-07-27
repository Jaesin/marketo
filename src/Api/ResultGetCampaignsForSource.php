<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ResultGetCampaignsForSource {

  /**
   * @var int
   */
  public $returnCount;

  /**
   * @var (object)ArrayOfCampaignRecord
   */
  public $campaignRecordList;

}
