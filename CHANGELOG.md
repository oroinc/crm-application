CHANGELOG for 1.7.0
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.7.0 versions.
* 1.7.0 (2015-04-28)
 * Tracking of email conversations and threads
 * Email signatures
 * Email attachments
 * Email contexts
 * Immediate availability of Magento data after initial synchronization
 * Automatic accounts discovery on Magento customers sync
 * Create and Edit Magento customers from OroCRM
 * Import of Magento newsletter subscribers
 * Connection between web events and CRM data
 * Connect guest/anonymous web events to customer after authentication
 * Abandoned shopping cart campaigns
 * New widgets for eCommerce dashboard
 * Dropped support of Magento 1.6 due to API limitations.

CHANGELOG for 1.6.0
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.6.0 versions.
* 1.6.0 (2015-01-19)
 * Availability of email campaign results for filtering in reports & segments.
Now email campaign results, such as opens, clicks, bounces, etc., are available for filter queries in reporting and customer segmentation. This also includes campaign statistics received via MailChimp integration

CHANGELOG for 1.5.0
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.5.0 versions.
* 1.5.0 (2014-12-18)
 * RFM analytic for Magento channels.
RFM is a popular set of metrics used to analyze customer value and to determine the best customers, especially for retail and e-commerce. The 1.5.0 release of OroCRM adds the ability to configure RFM metrics for Magento channels.
The RFM score consists of three metrics:
 - Recency, that evaluates the number of days that passed since the last purchase. The more recent is the purchase, the better.
 - Frequency, that evaluates the number of orders placed by the customer in the last 365 days. The more frequently the customer buys, the better.
 - Monetary value, that evaluates the total amount of orders placed by the customer in the last 365 days. The more money customer spends, the better.
To construct these metrics, the entire range of values is divided into a small number of categories, or "buckets." The number of buckets usually lies in range of 3 to 10, and scores for R, F, and M range accordingly—from 1 (the best score) to the maximum number of buckets (the worst score). You can change the number of buckets and move their boundaries in order to adjust the scores to characteristic values of your business.
After the metric is applied, every customer gets a three-number set of RFM scores. R1 F1 M1 identifies the best customers, and the higher the scores are, the worse these customers perform in a particular field.
RFM scores are displayed on the Magento customer view page and on the customer section of the Account view. You may also re-use these scores in reporting and segmentation.

CHANGELOG for 1.4.0
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.4.0 versions.
* 1.4.0 (2014-10-15)
 * The re-introduction of Channels.
We started the implementation of a new vision for the Channels in 1.3 version and now we bring Channels back, although under a new definition.
The general idea behind channels may be explained as follows: a channel in OroCRM represents an outside source customer and sales data, where "customer" and "sales" must be understood in the broadest sense possible. Depending on the nature of the outside source, the channel may or may not require a data integration.
This new definition leads to multiple noticeable changes across the system.
 * Accounts.
Account entity now performs as the "umbrella" entity for all customer identities across multiple channels, displaying all their data in a single view.
 * Integration management.
Albeit the Integrations grid still displays all integrations that exist in the system, you now may create only "non-customer" standalone integrations, such as Zendesk integration. The "customer" integrations, such as Magento integration, may be created only in scope of a channel and cannot exist without it.
 * Channel management UI.
The UI for channel creation now allows the user to specify channel type. By default there are three channel types: Magento, B2B, and Custom; more channel types may be created by developers.
Each channel type characterizes the following:
Whether a channel requires an integration. If the answer is yes (cf. Magento), the integration should be configured along the creation of the channel.
Which entity will serve as the Customer Identity. This entity cannot be changed by the user.
Which entities will be enabled in the system along with the channel.
A specific set of entities comes by default (e.g. Sales Process, Lead, and Opportunity for B2B channel), but the user may remove or add entities if necessary.
 * B2B functionality.
B2B functionality, such as Leads or Opportunities will no longer be available by default—in order to work with them the user should create at least one B2B channel first. As a result it is now possible to configure your instance of OroCRM to be fully B2C-oriented and work only with entities that make sense in eCommerce context—with no mandatory Leads and Opportunities at all.
In order to comply with the new concept of Customer Identity, the new entity named B2B Customer was added to the system. It replaces Account in most cases of our default Sales Process workflows.
 * Lifetime sales value.
This feature provides the means to record historical sales for every channel type. The exact definition of what constitutes sales is subject to channel type: for Magento channels lifetime sales are counted as order subtotal (excluding cancelled orders), and for B2B channels it is counted as total value of won opportunities. The common metric allows you to quickly compare sales across channels in the account view, where both per-channel and account total values are displayed.
 * Marketing lists.
Marketing lists serve as the basis for marketing activities, such as email campaigns (see below). They represent a target auditory of the activity—that is, people, who will be contacted when the activity takes place. Marketing lists have little value by themselves; they exist in scope of some marketing campaign and its activities.
Essentially, marketing list is a segment of entities that contain some contact information, such as email or phone number or physical address. Lists are build based on some rules using Oro filtering tool. Similarly to segments, marketing lists can be static or dynamic; the rules are the same. The user can build marketing lists of contacts, Magento customers, leads, etc.
In addition to filtering rules, the user can manually tweak contents of the marketing list by removing items ("subscribers") from it. Removed subscribers will no longer appear in the list even if they fit the conditions. It is possible to move them back in the list, too.
Every subscriber can also unsubscribe from the list. In this case, he will remain in the list, but will no longer receive email campaigns that are sent to this list. Note that subscription status is managed on per-list basis; the same contact might be subscribed to one list and unsubscribed from another.
 * Email campaigns.
Email campaign is a first example of marketing activity implemented in OroCRM. The big picture is following: Every marketing campaign might contain multiple marketing activities, e.g. an email newsletter, a context ad campaign, a targeted phone advertisement. All these activities serve the common goal of the "big" marketing campaign.
In its current implementation, email campaign is a one-time dispatch of an email to a list of subscribers. Hence, the campaign consists of three basic parts:
Recipients—represented by a Marketing list.
Email itself—the user may choose a template, or create a campaign email from scratch.
Sending rules—for now, only one-time dispatch is available.
Email campaign might be tied to a marketing campaign, but it might exist on its own as well.
 * Ecommerce dashboard
In addition to default dashboard we have added a special Ecommerce-targeted board with three widgets:
<ul><li>Average order amount</li>
   <li>New web customers</li>
   <li>Average customer lifetime sales</li></ul>
Every widget displays historical trend for the particular value over the past 12 months. You can also add them to any other dashboard using the Add Widget button.

CHANGELOG for 1.4.0-RC1
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.4.0-RC1 versions.
* 1.4.0-RC1 (2014-09-30)
 * The re-introduction of Channels.
We started the implementation of a new vision for the Channels in 1.3 version and now we bring Channels back, although under a new definition.
The general idea behind channels may be explained as follows: a channel in OroCRM represents an outside source customer and sales data, where "customer" and "sales" must be understood in the broadest sense possible. Depending on the nature of the outside source, the channel may or may not require a data integration.
This new definition leads to multiple noticeable changes across the system.
 * Accounts.
Account entity now performs as the "umbrella" entity for all customer identities across multiple channels, displaying all their data in a single view.
 * Integration management.
Albeit the Integrations grid still displays all integrations that exist in the system, you now may create only "non-customer" standalone integrations, such as Zendesk integration. The "customer" integrations, such as Magento integration, may be created only in scope of a channel and cannot exist without it.
 * Channel management UI.
The UI for channel creation now allows the user to specify channel type. By default there are three channel types: Magento, B2B, and Custom; more channel types may be created by developers.
Each channel type characterizes the following:
Whether a channel requires an integration. If the answer is yes (cf. Magento), the integration should be configured along the creation of the channel.
Which entity will serve as the Customer Identity. This entity cannot be changed by the user.
Which entities will be enabled in the system along with the channel.
A specific set of entities comes by default (e.g. Sales Process, Lead, and Opportunity for B2B channel), but the user may remove or add entities if necessary.
 * B2B functionality.
B2B functionality, such as Leads or Opportunities will no longer be available by default—in order to work with them the user should create at least one B2B channel first. As a result it is now possible to configure your instance of OroCRM to be fully B2C-oriented and work only with entities that make sense in eCommerce context—with no mandatory Leads and Opportunities at all.
In order to comply with the new concept of Customer Identity, the new entity named B2B Customer was added to the system. It replaces Account in most cases of our default Sales Process workflows.
 * Lifetime sales value.
This feature provides the means to record historical sales for every channel type. The exact definition of what constitutes sales is subject to channel type: for Magento channels lifetime sales are counted as order subtotal (excluding cancelled orders), and for B2B channels it is counted as total value of won opportunities. The common metric allows you to quickly compare sales across channels in the account view, where both per-channel and account total values are displayed.
 * Marketing lists.
Marketing lists serve as the basis for marketing activities, such as email campaigns (see below). They represent a target auditory of the activity—that is, people, who will be contacted when the activity takes place. Marketing lists have little value by themselves; they exist in scope of some marketing campaign and its activities.
Essentially, marketing list is a segment of entities that contain some contact information, such as email or phone number or physical address. Lists are build based on some rules using Oro filtering tool. Similarly to segments, marketing lists can be static or dynamic; the rules are the same. The user can build marketing lists of contacts, Magento customers, leads, etc.
In addition to filtering rules, the user can manually tweak contents of the marketing list by removing items ("subscribers") from it. Removed subscribers will no longer appear in the list even if they fit the conditions. It is possible to move them back in the list, too.
Every subscriber can also unsubscribe from the list. In this case, he will remain in the list, but will no longer receive email campaigns that are sent to this list. Note that subscription status is managed on per-list basis; the same contact might be subscribed to one list and unsubscribed from another.
 * Email campaigns.
Email campaign is a first example of marketing activity implemented in OroCRM. The big picture is following: Every marketing campaign might contain multiple marketing activities, e.g. an email newsletter, a context ad campaign, a targeted phone advertisement. All these activities serve the common goal of the "big" marketing campaign.
In its current implementation, email campaign is a one-time dispatch of an email to a list of subscribers. Hence, the campaign consists of three basic parts:
Recipients—represented by a Marketing list.
Email itself—the user may choose a template, or create a campaign email from scratch.
Sending rules—for now, only one-time dispatch is available.
Email campaign might be tied to a marketing campaign, but it might exist on its own as well.
 * Ecommerce dashboard
In addition to default dashboard we have added a special Ecommerce-targeted board with three widgets:
<ul><li>Average order amount</li>
   <li>New web customers</li>
   <li>Average customer lifetime sales</li></ul>
Every widget displays historical trend for the particular value over the past 12 months. You can also add them to any other dashboard using the Add Widget button.

CHANGELOG for 1.3.2
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.3.2 versions.

* 1.3.2 (2014-09-22)
 * Stored XSS Vulnerability fixes
    * added "|json_encode|raw" for values outputted in JS objects
    * removed "|raw" from outputs of path in url attributes
    * added "e('html_attr')|raw" when outputting html attributes
    * removed mentions of "flexible entity" and unused code
    * added validator for css field of embedded form, now if user will enter html tags in this field he will get an error message
    * added stiptags filter for css of embedded forms
    * changed translation message oro.entity_config.records_count.label to contain placeholder of records count and use UI.link macros in template instead of slicing str
    * changed method of validation of emails on the client, old validation was working very slowly with some values like '"><img src=d onerror=confirm(/provensec/);>', n
    * removed "trans|raw" where it's not required
    * minor changes in templates to improve readability
    * added Email validator for Lead
    * fixed XSS vulnerability in Leads, Case Comments, Notes, Embedded forms, Emails, Business Units, Breadcrumbs
    * fixed escaping of page title

CHANGELOG for 1.3.1
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.3.1 versions.

* 1.3.1 (2014-08-14)
 * Magento Synchronization stabilization improvements 
 * Fixed issue: Incorrect row count on grids.
 * Fixed issue: Reports and Segments crash when "Is empty" filter is added.
 * Fixed issue: Recent Emails dashboard widget is broken.
 * Fixed issue: Accounts cannot be linked to Contacts from Edit Contact page.

CHANGELOG for 1.3.0
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.3.0 versions.

* 1.3.0 (2014-07-23)
 * Website event tracking
 * Marketing campaigns
 * Campaign code tracking
 * Cases
 * Processes within Magento integration
 * Activities (Notes, Emails, Attachments)
 * Data import in CSV format
 * Zendesk integration
 * Other changes and improvements

CHANGELOG for 1.2.0
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.2.0 versions.

* 1.2.0 (2014-05-28)
 * Two-side customer data synchronization with Magento
 * Improvements to Customer view
 * Improvements to Magento data view
 * Fixed issue Broken widgets in merged Account view
 * Fixed Community requests

CHANGELOG for 1.2.0-rc1
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.2.0 RC1 versions.

* 1.2.0 RC1 (2014-05-12)
 * Improvements to Customer view
 * Improvements to display of Magento data
 * Fixed issue Broken widgets in merged Account view

CHANGELOG for 1.0.0
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.0.0 versions.

* 1.0.0 (2014-04-01)
 * Tasks
 * Improved UI for launch of the Sales Process workflow

CHANGELOG for 1.0.0-rc2
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.0.0-rc2 versions.

* 1.0.0-rc2 (2014-02-25)
 * Refactored Flexible Workflows
 * Embedded forms
 * Account merging

CHANGELOG for 1.0.0-rc1
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.0.0-rc1 versions.

* 1.0.0-rc1 (2014-01-30)
 * Improved Reports
 * Improved Workflow
 * Improved Dashboard
 * Magento import performance improvements
 * Other improvements in channnels, contacts

CHANGELOG for 1.0.0-beta6
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.0.0-beta6 versions.

* 1.0.0-beta6 (2013-12-30)
 * Magento data import: Customers, Shopping carts and Orders
 * B2C Sales Flow
 * Call view window
 * Basic dashboards

CHANGELOG for 1.0.0-beta5
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.0.0-beta5 versions.

* 1.0.0-beta5 (2013-12-05)
 * Reports creation wizard (Table reports)
 * B2B Sales Flow adjustments
 * Call entity
 * Add weather layer in the map on contact view page

CHANGELOG for 1.0.0-beta4
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.0.0-beta4 versions.

* 1.0.0-beta4 (2013-11-21)
 * Workflow transitions
 * Make all entities as Extended
 * End support for Internet Explorer 9 

CHANGELOG for 1.0.0-beta3
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.0.0-beta3 versions.

* 1.0.0-beta3 (2013-11-11)
  * Oro Platform Beta 3 dependency changes

CHANGELOG for 1.0.0-beta2
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.0.0-beta2 versions.

* 1.0.0-beta2 (2013-10-28)
  * Oro Platform Beta 2 dependency changes

CHANGELOG for 1.0.0-beta1
===================
This changelog references the relevant changes (new features, changes and bugs) done in 1.0.0-beta1 versions.

* 1.0.0-beta1 (2013-09-30)
  * CRM Entities reports
  * Contacts Import/Export
  * Account association with contacts
  * Custom entities and fields in usage

CHANGELOG for 1.0.0-alpha6
===================

* 1.0.0-alpha6 (2013-09-12)
  * Leads and Opportunities
  * Flexible Workflow Engine (FWE)

CHANGELOG for 1.0.0-alpha5
===================

* 1.0.0-alpha5 (2013-08-29)
 * Contacts Improvements
   * added ability to manage addresses from contact view page with Google Maps API support
   * added support of multiple Emails and Phones for Contact

CHANGELOG for 1.0.0-alpha4
===================

* 1.0.0-alpha4 (2013-07-31)
 * Address Types Management. Added ability to set different type for addresses in Contact address book

CHANGELOG for 1.0.0-alpha3
===================

This changelog references the relevant changes (new features, changes and bugs) done in 1.0.0-alpha3 versions.

* 1.0.0-alpha3 (2013-06-27)
 * Placeholders
 * Developer toolbar works with AJAX navigation requests
 * Configuring hidden columns in a Grid
 * Auto-complete form type
 * Added Address Book
 * Many-to-many relation between Contacts and Accounts
 * Added ability to sort Contacts and Accounts by Phone and Email in a Grid
 * Localized countries and regions
 * Enhanced data change log with ability to save changes for collections
 * Removed dependency on lib ICU

