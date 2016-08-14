UPGRADE FROM 1.9 to 1.10
=======================

####OroCRMSalesBundle:
- The 'status' field in `OroCRM\Bundle\SalesBundle\Entity\Opportunity` is changed to enum type
- Constructor for `OroCRM\Bundle\SalesBundle\Provider\ForecastOfOpportunities` changed. New arguments: `DateHelper $dateHelper`, `OwnerHelper $ownerHelper`
- Class `OroCRM\Bundle\SalesBundle\Provider\OpportunityByStatusProvider` moved to `OroCRM\Bundle\SalesBundle\Dashboard\Provider\OpportunityByStatusProvider`. New argument: `OwnerHelper $ownerHelper`
- Deprecated method `OroCRM\Bundle\SalesBundle\Entity\Repository\LeadRepository::getOpportunitiesByLeadSource`
  in favor of `OroCRM\Bundle\SalesBundle\Entity\Repository\OpportunityRepository::getOpportunitiesCountGroupByLeadSource`
- The 'status' field in OroCRM\Bundle\SalesBundle\Entity\Lead is changed to enum type
- The OroCRM\Bundle\SalesBundle\Entity\LeadStatus is deprecated due to enum type usage
- The 'address' field in OroCRM\Bundle\SalesBundle\Entity\Lead is deprecated and will be removed in the next release. Addresses field is used instead now to store a collection of LeadAddress entities.
 If any custom fields were added to the oro_address table, they should be added to LeadAddress entity and migrated to the orocrm_lead_address table.
- B2bCustomer entity extended with 2 collection fields (emails, phones)
- Constructor for `OroCRM\Bundle\SalesBundle\Form\Type\OpportunityType` changed. New argument: `OpportunityRelationsBuilder $relationsBuilder`.

#### OroCRMCallBundle:
- The `duration` field in `OroCRM\Bundle\CallBundle\Entity\Call` is changed to `duration` DB type 
from `DateTime` which accepts a (int) duration in seconds.
Updating Call `duration` field (API POST/PUT, Forms) now accepts strings with formats '*HH*:*MM*:*SS*', '*HH*h *MM*m *SS*s' or `(int)` seconds
Retrieving Call `duration` field (API GET) now returns `(int)` seconds instead of 'HH:MM:SS' formatted string
- OroCRMCallBundle moved to a separate package
- OroCRMCallBridgeBundle was added to integrate OroCRMCallBundle into CRM

####OroCRMTaskBundle:
- OroCRMTaskBundle moved to a separate package
- OroCRMTaskBridgeBundle was added to integrate OroCRMTaskBundle into CRM

####OroCRMTaskBundle:
- OroCRMCallBundle moved to a separate package
- OroCRMCallBridgeBundle was added to integrate OroCRMCallBundle into CRM

#### OroCRMChannelBundle:
- Constructor for `OroCRM\Bundle\ChannelBundle\Provider\Lifetime\AverageLifetimeWidgetProvider` was changed. New argument: `DateFilterProcessor $filterProcessor`
- Constructor for `OroCRM\Bundle\ChannelBundle\Provider\ChannelsByEntitiesProvider` was changed. New argument: `AclHelper $aclHelper`
- The constructor of the `OroCRM\Bundle\SalesBundle\Provider\ForecastOfOpportunities` class was changed.
    Before: `__construct(RegistryInterface $doctrine, NumberFormatter $numberFormatter, DateTimeFormatter $dateTimeFormatter, AclHelper $aclHelper, TranslatorInterface $translator, DateHelper $dateHelper, OwnerHelper $ownerHelper)`.
    After: `__construct(NumberFormatter $numberFormatter, DateTimeFormatter $dateTimeFormatter, TranslatorInterface $translator, DateHelper $dateHelper, OwnerHelper $ownerHelper, ForecastProvider $provider, FilterDateRangeConverter $filterDateRangeConverter)`.
- The method `getForecastOfOpportunitiesValues` of `OroCRM\Bundle\SalesBundle\Provider\ForecastOfOpportunities` class was changed. Argument $getterName was changed to $dataKey.
