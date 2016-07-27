<?php

namespace Drupal\marketo\Api;

/**
 * Corresponds to the data type defined in WSDL.
 */
class ArrayOfActivityType {

  /**
   * @var array[0, unbounded] of string
   *     NOTE: activityType should follow the following restrictions
   *     You can have one of the following value
   *     VisitWebpage
   *     FillOutForm
   *     ClickLink
   *     RegisterForEvent
   *     AttendEvent
   *     SendEmail
   *     EmailDelivered
   *     EmailBounced
   *     UnsubscribeEmail
   *     OpenEmail
   *     ClickEmail
   *     NewLead
   *     ChangeDataValue
   *     LeadAssigned
   *     NewSFDCOpprtnty
   *     Wait
   *     RunSubflow
   *     RemoveFromFlow
   *     PushLeadToSales
   *     CreateTask
   *     ConvertLead
   *     ChangeScore
   *     ChangeOwner
   *     AddToList
   *     RemoveFromList
   *     SFDCActivity
   *     EmailBouncedSoft
   *     PushLeadUpdatesToSales
   *     DeleteLeadFromSales
   *     SFDCActivityUpdated
   *     SFDCMergeLeads
   *     MergeLeads
   *     ResolveConflicts
   *     AssocWithOpprtntyInSales
   *     DissocFromOpprtntyInSales
   *     UpdateOpprtntyInSales
   *     DeleteLead
   *     SendAlert
   *     SendSalesEmail
   *     OpenSalesEmail
   *     ClickSalesEmail
   *     AddtoSFDCCampaign
   *     RemoveFromSFDCCampaign
   *     ChangeStatusInSFDCCampaign
   *     ReceiveSalesEmail
   *     InterestingMoment
   *     RequestCampaign
   *     SalesEmailBounced
   */
  public $activityType;

}
