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

