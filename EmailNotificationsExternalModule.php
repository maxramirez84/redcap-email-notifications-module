<?php
/**
 * Created by PhpStorm.
 * User: maramirez
 * Date: 10/12/18
 * Time: 16:38
 */

namespace ISGlobal\EmailNotificationsExternalModule;


class EmailNotificationsExternalModule extends \ExternalModules\AbstractExternalModule
{
    /**
     * Function called by the Cron equally named to check and notify if new records were created through the API.
     */
    function hourly_notifications()
    {

    }
}