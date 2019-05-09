# REDCap Email Notifications External Module

[![License: MIT](https://img.shields.io/github/license/mashape/apistatus.svg)](https://opensource.org/licenses/MIT)
[![Release: v1.0.0](https://img.shields.io/github/release/maxramirez84/redcap-email-notifications-module.svg)](https://github.com/maxramirez84/redcap-email-notifications-module/releases/tag/v1.0.0)

This README file has been written following the recommendations given at [Make a README](https://www.makeareadme.com/).

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

## Installation

This External Module is not part of the [_REDCap Repo_](https://redcap.vanderbilt.edu/consortium/modules/index.php) yet.
Therefore, you will need to copy and paste manually the email_notifications_vX.X.X folder to the REDCap _modules_ 
directory, usually at:

```bash
cp -R email_notifications_vX.X.X /var/www/html/redcap/modules/
chown www-data:www-data -R /var/www/html/redcap/modules/email_notifications_vX.X.X 
```

Once you have copied the folder, login _REDCap_ with an _Admin_ account and navigate to the _Control Center_. Then, 
click on the _External Modules_ link (within the _Technical / Developer Tools_ section) and after on the 
_Enable a module_ button. A popup will be open from where you will be able to enable the module.

After that, the EM will appear under the _Modules Currently Available on this System_ section. Click on the _Configure_
button and complete the _Sender email address_ field with a valid email address. This will be the email address used in
the _FROM_, i.e. from whom the email will appear to be sent. This will also be the "reply-to" address as it appears to 
the recipient.

## Usage

Login REDCap and access to any project in which you have the _Project Setup/Design_ privilege. Under the _Applications_
section, click on the _External Modules_ link. After that, click on the _Enable a module_ button. Look for the 
_Email Notifications vX.X.X_ module and click on its _Enable_ button (on the right). 

The module is now enabled in the project. It should appear under the _Currently Enabled Modules_ section within the 
project. The next step is to configure it. Therefore, click on the _Configure_ button close to the 
_Email Notifications - vX.X.X_ module. A popup will be displayed allowing us to define as many notification recipients
as we want. Each of the recipients will have its own notification frequency. To add more than one recipient, click on
the _+_ button. 

## Support

For reporting any problem and/or any feature request, go to the 
[Issues](https://github.com/maxramirez84/redcap-email-notifications-module/issues) tracker on GitHub. For any other 
question, write an email to [maximo.ramirez@isglobal.org](mailto:maximo.ramirez@isglobal.org).

## Roadmap

1. **Send notifications in a concrete date and time**: daily, weekly or monthly notifications are triggered when REDCap
considers that interval has passed by and not at the end of the day, week or month. Next step is to provide the user
with the capacity of defining when she wants to send the notifications. E.g. notify me **how many** records were created 
during the **last week** on **Fridays at 18:00**.

## Contributing

As far as this is a one-person repository, the branching model used is the simplest function model. I.e. two long-living
branches, master and develop. Each release to production comes out from the master branch and it has a tag with a named
version. The master branch is protected and it requires pull request reviews before merging. Therefore, all commits must 
be made to the develop or any bug-fix branches (which are not protected) and then submitted via a pull request that will 
have to be approved.

![Simple branch strategy for small teams or individuals](https://cdn-images-1.medium.com/max/1600/1*_W8zvBeP6cKLFUjO5wzn3Q.png)

This way the team can work on new features on a separate branch (develop), while the master is intact until the next 
release. The main advantage of this approach is that, if you have bugs to fix (you will always have), you will be able 
to solve the bug without having to stash your changes or speed up the features you are developing. So in these 
situations, we create a short lived branch for the fix (a bug-fix branche), merge on the master and deploy to production 
with a new tag, and propagate the fix to develop. When the new features are ready, they would already have the fixes, 
and merging with the master branch would not be a problem [[Grazi Bonizi, 2018](https://medium.com/@grazibonizi/the-best-branching-model-to-work-with-git-4008a8098e6a)].

Version numbers follow the recommendations from [Semantic Versioning 2.0.0](https://semver.org/). 

## Authors and acknowledgment

This EM is entirely coded by [Máximo Ramírez from ISGlobal](https://www.isglobal.org/en/person?p_p_id=viewpersona_WAR_intranetportlet&p_p_lifecycle=0&p_p_col_id=column-3&p_p_col_count=1&_viewpersona_WAR_intranetportlet_struts_action=%2Fview%2FpersonaView&_viewpersona_WAR_intranetportlet_personaId=9401&_viewpersona_WAR_intranetportlet_typeOfPeople=staff).

Chris Kadolph is acknowledged by his [contribution](https://community.projectredcap.org/questions/46367/project-specific-external-module-cron-jobs.html) 
for setting project specific scope in REDCap EMs. 

## License

[MIT License](https://opensource.org/licenses/MIT)

Copyright (c) 2019 Máximo Ramírez Robles

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated 
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the 
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit 
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the 
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE 
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR 
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR 
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.