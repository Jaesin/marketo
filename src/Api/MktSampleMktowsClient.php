<?php

namespace Drupal\marketo\Api;

/**
 * Client for Marketo SOAP API.
 */
class MktSampleMktowsClient {

  // Change this vale to true to enable debug output.
  const DEBUG = FALSE;

  const CLIENT_TZ = 'America/Los_Angeles';

  const MKTOWS_USER_ID = 'bigcorp2_458073844B29ACAFC64AC0';
  const MKTOWS_SECRET_KEY = '425794457179585644BB2299AACCBB01CC66229C2B35';

  const MKTOWS_NAMESPACE = 'http://www.marketo.com/mktows/';

  protected $accessKey;
  protected $secretKey;

  /**
   * @var Client
   */
  protected $soapClient;

  public function __construct($accessKey, $secretKey, $soapEndPoint) {
    $this->accessKey = $accessKey;
    $this->secretKey = $secretKey;

    $options = array("connection_timeout" => 20, "location" => $soapEndPoint);
    if (self::DEBUG) {
      $options["trace"] = true;
    }

    $wsdlUri = $soapEndPoint . '?WSDL';

    $this->soapClient = new \SoapClient($wsdlUri, $options);
  }

  private function _getAuthenticationHeader() {
    $dtzObj = new \DateTimeZone(self::CLIENT_TZ);
    $dtObj = new \DateTime('now', $dtzObj);
    $timestamp = $dtObj->format(DATE_W3C);
    //$timestamp = '2009-01-27T15:53';

    $encryptString = $timestamp . $this->accessKey;

    $signature = hash_hmac('sha1', $encryptString, $this->secretKey);
    //echo "encrypt:   $encryptString\n";
    //echo "key:       {this->secretKey}\n";
    //echo "signature: $signature\n";

    $attrs = new stdClass();
    $attrs->mktowsUserId  = $this->accessKey;
    $attrs->requestSignature = $signature;
    $attrs->requestTimestamp = $timestamp;

    $soapHdr = new SoapHeader(self::MKTOWS_NAMESPACE, 'AuthenticationHeader', $attrs);
    return $soapHdr;
  }

  public function getLead($keyType, $keyValue) {
    $retLead = NULL;

    $leadKey = new LeadKey();
    $leadKey->keyType = $keyType;
    $leadKey->keyValue = $keyValue;

    $params = new ParamsGetLead();
    $params->leadKey = $leadKey;

    $options = array();

    $authHdr = $this->_getAuthenticationHeader();

    try {
      $success = $this->soapClient->__soapCall('getLead', array($params), $options, $authHdr);

      if (self::DEBUG) {
        $req = $this->soapClient->__getLastRequest();
        echo "RAW request:\n$req\n";
        $resp = $this->soapClient->__getLastResponse();
        echo "RAW response:\n$resp\n";
      }

      if (isset($success->result)) {
        if ($success->result->count > 1) {
          // Is this okay?  If not, raise exception.
        }
        if (isset($success->result->leadRecordList->leadRecord)) {
          $leadRecList = $success->result->leadRecordList->leadRecord;
          if (!is_array($leadRecList)) {
            $leadRecList = array($leadRecList);
            $count = count($leadRecList);
            if ($count > 0) {
              $retLead = $leadRecList[$count - 1];
            }
          }
        }
      }
    }
    catch (SoapFault $ex) {
      $ok = FALSE;
      $errCode = 1;
      $faultCode = NULL;
      if (!empty($ex->detail->serviceException->code)) {
        $errCode = $ex->detail->serviceException->code;
      }
      if (!empty($ex->faultCode)) {
        $faultCode = $ex->faultCode;
      }
      switch ($errCode) {

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
      $req = $this->soapClient->__getLastRequest();
      echo "Error occurred for request: $msg\n$req\n";
      var_dump($ex);
      exit(1);
    }

    return $retLead;
  }

  public function syncLead($key, $attrs) {
    // Build array of Attribute objects.
    $attrArray = array();
    foreach ($attrs as $attrName => $attrValue) {
      $a = new Attribute();
      $a->attrName = $attrName;
      $a->attrValue = $attrValue;
      $attrArray[] = $a;
    }
    $aryOfAttr = new ArrayOfAttribute();
    $aryOfAttr->attribute = $attrArray;

    // Build LeadRecord.
    $leadRec = new LeadRecord();
    $leadRec->leadAttributeList = $aryOfAttr;

    // Set the unique lead key.
    if (is_numeric($key)) {
      // Marketo system ID.
      $leadRec->Id = $key;
    }
    else {
      // @todo Add email format validation - should be SMTP email address.
      $leadRec->Email = $key;
    }

    // Build API params.
    $params = new ParamsSyncLead();
    $params->leadRecord = $leadRec;

    // Don't return the full lead record - just the ID.
    $params->returnLead = FALSE;
    // Add the marketo tracking cookie if it exists.
    if (isset($_COOKIE['_mkto_trk'])) {
      $params->marketoCookie = $_COOKIE['_mkto_trk'];
    }

    $options = array();

    $authHdr = $this->_getAuthenticationHeader();
    try {
      $success = $this->soapClient->__soapCall('syncLead', array($params), $options, $authHdr);
      if (self::DEBUG) {
        $req = $this->soapClient->__getLastRequest();
        echo "RAW request:\n$req\n";
        $resp = $this->soapClient->__getLastResponse();
        echo "RAW response:\n$resp\n";
      }
    }
    catch (\SoapFault $ex) {
      watchdog("marketo", "Marketo SOAP error:" . print_r($ex->detail, TRUE), NULL, WATCHDOG_WARNING);

      $ok = FALSE;
      $errCode = 1;
      $faultCode = NULL;
      if (!empty($ex->detail->serviceException->code)) {
        $errCode = $ex->detail->serviceException->code;
      }
      if (!empty($ex->faultCode)) {
        $faultCode = $ex->faultCode;
      }
      switch ($errCode) {

        case MktWsError::ERR_LEAD_SYNC_FAILED:
          // Retry once and handle error if retry fails.
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
      $req = $this->soapClient->__getLastRequest();
      echo "Error occurred for request: $msg\n$req\n";
      var_dump($ex);
      exit(1);
    }
    // @todo when no api keys are set, this doesn't get set previously.
    return $success;
  }

  public function getCampaignsForSource() {
    $retList = NULL;

    $params = new ParamsGetCampaignsForSource();
    // We want campaigns configured for access through the SOAP API.
    $params->source = 'MKTOWS';

    $options = NULL;

    $authHdr = $this->_getAuthenticationHeader();

    try {
      $success = $this->soapClient->__soapCall('getCampaignsForSource', array($params), $options, $authHdr);

      if (self::DEBUG) {
        $req = $this->soapClient->__getLastRequest();
        echo "RAW request:\n$req\n";
        $resp = $this->soapClient->__getLastResponse();
        echo "RAW response:\n$resp\n";
      }

      if (isset($success->result->returnCount) && $success->result->returnCount > 0) {
        if (isset($success->result->campaignRecordList->campaignRecord)) {
          $retList = array();
          // campaignRecordList is ArrayOfCampaignRecord from WSDL.
          $campRecList = $success->result->campaignRecordList->campaignRecord;
          // Force to array when one 1 item is returned (quirk of PHP SOAP)
          if (!is_array($campRecList)) {
            $campRecList = array($campRecList);
          }
          // $campRec is CampaignRecord from WSDL.
          /** @var \stdClass $campRec */
          foreach ($campRecList as $campRec) {
            $retList[$campRec->name] = $campRec->id;
          }
        }
      }
    }
    catch (\SoapFault $ex) {
      if (self::DEBUG) {
        $req = $this->soapClient->__getLastRequest();
        echo "RAW request:\n$req\n";
        $resp = $this->soapClient->__getLastResponse();
        echo "RAW response:\n$resp\n";
      }
      $ok = FALSE;
      $errCode = !empty($ex->detail->serviceException->code) ? $ex->detail->serviceException->code : 1;
      $faultCode = !empty($ex->faultCode) ? $ex->faultCode : NULL;
      switch ($errCode) {

        case MktWsError::ERR_CAMP_NOT_FOUND:
          // Handle error for campaign not found.
          break;

        default:
          // Handle other errors.
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
    catch (Exception $ex) {
      $msg = $ex->getMessage();
      $req = $this->soapClient->__getLastRequest();
      echo "Error occurred for request: $msg\n$req\n";
      var_dump($ex);
      exit(1);
    }

    return $retList;
  }

  public function requestCampaign($campId, $leadEmail) {
    $retStat = FALSE;

    $leadKey = new LeadKey();
    $leadKey->keyType = 'IDNUM';
    $leadKey->keyValue = $leadEmail;

    $leadList = new ArrayOfLeadKey();
    $leadList->leadKey = array($leadKey);

    $params = new ParamsRequestCampaign();
    $params->campaignId = $campId;
    $params->leadList = $leadList;
    $params->source = 'MKTOWS';

    $authHdr = $this->_getAuthenticationHeader();

    try {
      $options = NULL;
      $success = $this->soapClient->__soapCall('requestCampaign', array($params), $options, $authHdr);

      if (self::DEBUG) {
        $req = $this->soapClient->__getLastRequest();
        echo "RAW request:\n$req\n";
        $resp = $this->soapClient->__getLastResponse();
        echo "RAW response:\n$resp\n";
      }

      if (isset($success->result->success)) {
        $retStat = $success->result->success;
      }
    }
    catch (\SoapFault $ex) {
      $ok = FALSE;
      $errCode = !empty($ex->detail->serviceException->code) ? $ex->detail->serviceException->code : 1;
      $faultCode = !empty($ex->faultCode) ? $ex->faultCode : NULL;
      switch ($errCode) {

        case MktWsError::ERR_LEAD_NOT_FOUND:
          // Handle error for campaign not found.
          break;

        default:
          // Handle other errors.
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
      $req = $this->soapClient->__getLastRequest();
      echo "Error occurred for request: $msg\n$req\n";
      var_dump($ex);
      exit(1);
    }

    return $retStat;
  }

  /**
   * Enter description here...
   *
   * @param string $leadEmail       Lead email
   * @param string $listName        Name of static list
   * @param string $sinceTimestamp  Some valid PHP time string like 2009-12-25 01:00:00
   * @param int    $lastId          ID of last activity
   */
  public function wasLeadAddedToListSince($leadId, $listName, $sinceTimestamp, $lastId) {
    $wasAdded = FALSE;
    $actRec = NULL;

    $leadKey = new LeadKey();
    $leadKey->keyType = 'IDNUM';
    $leadKey->keyValue = $leadId;
    $params = new ParamsGetLeadActivity();
    $params->leadKey = $leadKey;

    $actTypes = array();
    $actTypes[] = 'AddToList';
    $actArray = new ArrayOfActivityType();
    $actArray->activityType = $actTypes;
    $filter = new ActivityTypeFilter();
    $filter->includeTypes = $actArray;
    $params->activityFilter = $filter;

    $startPos = new StreamPosition();
    // Use the correct time zone!
    $dtzObj = new \DateTimeZone(self::CLIENT_TZ);
    $dtObj = new \DateTime($sinceTimestamp, $dtzObj);
    $startPos->oldestCreatedAt = $dtObj->format(DATE_W3C);
    $params->startPosition = $startPos;

    $params->batchSize = 100;

    $doPage = TRUE;
    while ($doPage) {
      $authHdr = $this->_getAuthenticationHeader();

      try {
        $options = NULL;
        $success = $this->soapClient->__soapCall('getLeadActivity', array($params), $options, $authHdr);

        if (self::DEBUG) {
          $req = $this->soapClient->__getLastRequest();
          echo "RAW request:\n$req\n";
          $resp = $this->soapClient->__getLastResponse();
          echo "RAW response:\n$resp\n";
        }

        if (isset($success->leadActivityList)) {
          // leadActivityList is LeadActivityList in WSDL.
          $result = $success->leadActivityList;
          if ($result->returnCount > 0) {
            // actRecList is ArrayOfActivityRecord from WSDL.
            $actRecList = $result->activityRecordList;
            // Force to array when one 1 item is returned (quirk of PHP SOAP)
            if (!is_array($actRecList)) {
              $actRecList = array($actRecList);
            }
            foreach ($actRecList as $actRec) {
              if ($actRec->id > $lastId && $actRec->mktgAssetName == $listName) {
                $wasAdded = TRUE;
                break 2;
              }
            }
            $newStartPos = $success->leadActivityList->newStartPosition;
            $params->startPosition = $newStartPos;
          }
          else {
            $doPage = FALSE;
          }
        }
      }
      catch (\SoapFault $ex) {
        $ok = FALSE;
        $errCode = !empty($ex->detail->serviceException->code) ? $ex->detail->serviceException->code : 1;
        $faultCode = !empty($ex->faultCode) ? $ex->faultCode : NULL;
        switch ($errCode) {

          case MktWsError::ERR_LEAD_NOT_FOUND:
            // Handle error for lead not found.
            break;

          default:
            // Handle other errors.
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
        break;
      }
      catch (\Exception $ex) {
        $msg = $ex->getMessage();
        $req = $this->soapClient->__getLastRequest();
        echo "Error occurred for request: $msg\n$req\n";
        var_dump($ex);
        exit(1);
      }
    }

    return array($wasAdded, $actRec);
  }

}
