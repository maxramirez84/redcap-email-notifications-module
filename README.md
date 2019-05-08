# REDCap Email Notifications External Module

[![License: MIT](https://img.shields.io/github/license/mashape/apistatus.svg)](https://opensource.org/licenses/MIT)

> [External Modules](https://github.com/vanderbilt/redcap-external-modules) are individual packages of software that can
be downloaded and installed by a [_REDCap_](https://www.project-redcap.org/) administrator. Modules can extend 
_REDCap_'s current functionality, and can also provide customizations and enhancements for _REDCap_'s existing behavior 
and appearance at the system level or project level.

The _Email Notification EM_ allows project administrators to schedule email notifications (every minute, hourly, daily,
weekly or monthly) to one or more recipients **summarizing** record creation through the **_REDCap API_**, i.e. 
**_REDCap Mobile App_**. 

Since _REDCap 9.0.0 version_, the [_Email Alert EM_](https://github.com/vanderbilt-redcap/email-alerts-module) is 
included as core functionality within the _Alerts & Notifications Application_. This application allows users to 
construct alerts and send customized email notifications. These notifications may be sent to one or more recipients and 
can be triggered or scheduled when a form/survey is saved and/or based on conditional logic whenever data is saved or 
imported. However, **these notifications are record-based, i.e. one notification when one record is saved 
(_one-to-one_)**. This is adequate when records are not created in a very frequent basis, e.g. a Clinical Trial. 
However, for a Household Survey context, in which many records are created everyday, these kind of notifications may be 
overwhelming.

Thus, the _Email Notification EM_ sends every minute, hour, day, week or month a summary of the new records coming from 
the field, i.e. from the _REDCap Mobile App_. This summary includes the number of records created, who created them and 
in which project.

  