<?php

namespace Drupal\marketo;

/**
 * Interface SoapClientInterface.
 *
 * @package Drupal\marketo
 */
interface ClientInterface {

  /**
   * Get lead.
   *
   * @param string $keyType
   *   Field you wish to query the lead by.
   * @param string $keyValue
   *   Value you wish to query the lead by.
   *
   * @return \stdClass|NULL
   *   Lead object, or NULL on error.
   *
   * @see http://developers.marketo.com/documentation/soap/getlead/
   * @see \Drupal\marketo\Api\MktSampleMktowsClient::getLead
   */
  public function getLead($keyType, $keyValue);

}
