<?php

namespace Drupal\marketo;

use Drupal\marketo\Api\LeadKey;
use Drupal\marketo\Api\ParamsGetLead;
use Drupal\marketo\Api\MktWsError;
use Drupal\Core\Config\ConfigFactory;

/**
 * Class SoapClient.
 *
 * @package Drupal\marketo
 */
class SoapClient implements ClientInterface {

  /**
   * Enable debug output.
   *
   * @var bool
   */
  public $debug = FALSE;

  /**
   * Marketo user id.
   *
   * @var string
   */
  protected $userId;

  /**
   * Marketo secret key.
   *
   * @var string
   */
  protected $secretKey;

  /**
   * Marketo endpoint url.
   *
   * @var string
   */
  protected $endpoint;

  const MKTOWS_NAMESPACE = 'http://www.marketo.com/mktows/';

  /**
   * Drupal\Core\Config\ConfigFactory definition.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * SOAP Client.
   *
   * @var \SoapClient
   */
  protected $client;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactory $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Get SOAP client object.
   */
  protected function client() {
    if (empty($this->client)) {
      $options = array("connection_timeout" => 20, "location" => $this->endpoint);
      if ($this->debug) {
        $options["trace"] = TRUE;
      }
      $this->client = new \SoapClient($this->endpoint . '?WSDL', $options);
    }
    return $this->client;
  }

  /**
   * Gets auth header for SOAP requests.
   *
   * @return \SoapHeader
   *   Header object to add to request.
   */
  private function getAuthenticationHeader() {

    $dtzObj = new \DateTimeZone(date_default_timezone_get());
    $dtObj = new \DateTime('now', $dtzObj);
    /** @var string $timestamp */
    $timestamp = $dtObj->format(DATE_W3C);

    $encryptString = $timestamp . $this->secretKey;

    $signature = hash_hmac('sha1', $encryptString, $this->userId);

    $attrs = (object) [
      'mktowsUserId' => $this->userId,
      'requestSignature' => $signature,
      'requestTimestamp' => $timestamp,
    ];

    $soapHdr = new \SoapHeader(self::MKTOWS_NAMESPACE, 'AuthenticationHeader', $attrs);
    return $soapHdr;
  }

  /**
   * {@inheritdoc}
   */
  public function getLead($keyType, $keyValue) {
    $retLead = NULL;

    $leadKey = new LeadKey();
    $leadKey->keyType = $keyType;
    $leadKey->keyValue = $keyValue;

    $params = new ParamsGetLead();
    $params->leadKey = $leadKey;

    $options = array();

    $authHdr = $this->getAuthenticationHeader();

    try {
      $success = $this->client()
        ->__soapCall('getLead', array($params), $options, $authHdr);

      if ($this->debug) {
        $req = $this->client()->__getLastRequest();
        echo "RAW request:\n$req\n";
        $resp = $this->client()->__getLastResponse();
        echo "RAW response:\n$resp\n";
      }

      if (isset($success->result)) {
        if ($success->result->count > 1) {
          // Is this okay?  If not, raise exception.
        }
        if (isset($success->result->leadRecordList->leadRecord)) {
          $leadRecList = $success->result->leadRecordList->leadRecord;
          if (!is_array($leadRecList)) {
            /** @var array $leadRecList */
            $leadRecList = array($leadRecList);
            /** @var integer $count */
            $count = count($leadRecList);
            if ($count > 0) {
              $retLead = $leadRecList[$count - 1];
            }
          }
        }
      }
    }
    catch (\SoapFault $ex) {
      $ok = FALSE;
      $faultCode = NULL;
      if (!empty($ex->faultCode)) {
        $faultCode = $ex->faultCode;
      }
      switch (_marketo_soapfault_get_error_code($ex)) {

        case MktWsError::ERR_LEAD_NOT_FOUND:
          $ok = TRUE;
          break;

        default:

      }
      if (!$ok) {
        if ($faultCode != NULL) {
          if (strpos($faultCode, 'Client')) {
            // This is a client error.  Check the other codes and handle.
          }
          elseif (strpos($faultCode, 'Server')) {
            // This is a server error.  Call Marketo support with details.
          }
          else {
            // W3C spec has changed :)
            // But seriously, Call Marketo support with details.
          }
        }
        else {
          // Not a good place to be.
        }
      }
    }
    catch (\Exception $ex) {
      $msg = $ex->getMessage();
      $req = $this->client()->__getLastRequest();
      echo "Error occurred for request: $msg\n$req\n";
      var_dump($ex);
      exit(1);
    }

    return $retLead;
  }

}
