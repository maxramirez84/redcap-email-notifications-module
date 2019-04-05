<?php
/**
 * EmailNotificationsExternalModule
 *
 * REDCap External Module for managing email notifications when new records are
 * created through the API, i.e. REDCap Mobile App.
 *
 * php version 7.2
 *
 * @category External_Module
 * @package  REDCap
 * @author   Maximo Ramirez Robles <maximo.ramirez@isglobal.org>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/maxramirez84/redcap-email-notifications-module
 * @since    04/04/2019
 */

namespace ISGlobal\EmailNotificationsExternalModule;

use Exception;
use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use Plugin;
use Project;
use RCView;
use REDCap;

/**
 * Class EmailNotificationsExternalModule
 *
 * @category External_Module
 * @package  ISGlobal\EmailNotificationsExternalModule
 * @author   Maximo Ramirez Robles <maximo.ramirez@isglobal.org>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/maxramirez84/redcap-email-notifications-module/blob/master/EmailNotificationsExternalModule.php
 */
class EmailNotificationsExternalModule extends AbstractExternalModule
{
    const REDCAP_LOG_EVENT_TABLE = "redcap_log_event";
    const REDCAP_USER_INFORMATION_TABLE = "redcap_user_information";
    const CREATE_RECORD_API_DESCRIPTION = "Create record (API)";

    private $_enabled_projects = [];

    /**
     * EmailNotificationsExternalModule constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setEnabledProjects();
    }

    /**
     * Find all projects in which the EM is enabled and store them in the
     * corresponding property.
     *
     * @return void
     */
    public function setEnabledProjects()
    {
        $enabled_projects = ExternalModules::getEnabledProjects($this->PREFIX);

        while ($project = db_fetch_assoc($enabled_projects)) {
            $this->_enabled_projects[$project["project_id"]] = $project;
        }
    }

    /**
     * Parse INI language file, stored in the module folder inside /redcap/modules
     * directory, based on the project language. If language file not found,
     * English.ini loaded by default.
     *
     * @param string $language   Language name to be loaded
     * @param bool   $show_error Whether to show errors or not
     *
     * @return array Array of language strings
     */
    public function callLanguageFile($language = 'English', $show_error = true)
    {
        global $lang;

        // DEBUG
        Plugin::log("Project language = $language");

        // Get path of language file
        $dir = dirname(__FILE__);
        // English and other language files are kept in the EM folder
        $language_file = $dir . DS . "$language.ini";

        // DEBUG
        // Plugin::log("Language file path = $language_file");

        // Parse ini file into an array
        $this_lang = parse_ini_file($language_file);

        // If fails, parse English.ini by default
        if ($show_error && (!$this_lang || !is_dir($dir))) {
            $language_file = $dir . DS . "English.ini";
            $this_lang = parse_ini_file($language_file);

            if (!$this_lang) {
                exit(
                    $lang['config_functions_63'] .
                    "<br>" .
                    RCView::escape($language_file)
                );
            }
        }

        // DEBUG
        Plugin::log("$language_file loaded");

        // Return array of language text
        return $this_lang;
    }

    /**
     * Function called by the Cron equally named to check and notify if new records
     * were created through the API in the specified time interval.
     *
     * For setting project specific scope (by chris.kadolph):
     * https://community.projectredcap.org/questions/46367/project-specific-external-module-cron-jobs.html
     *
     * @return void
     * @throws Exception
     */
    public function minuteNotifications()
    {
        // DEBUG
        // REDCap::logEvent("System-level REDCap::logEvent from cron method.");

        // Check if mandatory system settings were defined. If not, exit
        $sender = $this->getSystemSetting("sender");

        // DEBUG
        Plugin::log("System Settings[sender]: $sender");

        if ($sender == null) {
            return;
        }

        // Set project specific scope
        global $Project;

        if (count($this->_enabled_projects) > 0) {
            foreach ($this->_enabled_projects as $project_id => $project) {
                // Set project specific scope
                try {
                    $Project = new Project($project_id);
                } catch (Exception $e) {
                    REDCap::logEvent(
                        "Caught exception in " . $this->PREFIX . ": " .
                        $e->getMessage()
                    );
                }
                define("PROJECT_ID", $project_id);
                $_GET['pid'] = $project_id;

                // EM internationalization from project language value
                $project_language = $Project->project['project_language'];
                $project_lang = $this->callLanguageFile($project_language);

                // DEBUG
                // REDCap::logEvent(
                //     "Project-level (project $project_id) ".
                //     "REDCap::logEvent from cron method."
                // );

                // Check if recipients were configured. If not, exit
                $recipients = $this->getSubSettings("recipients");

                // DEBUG
                // Plugin::log("Project Settings[recipients]:", $recipients);

                if (sizeof($recipients) == 0) {
                    return;
                }

                // Send email notification if new records were created
                // Check if new records were created through the API during the
                // last minute
                $query = "SELECT * FROM %s " .
                    "WHERE project_id = %d " .
                    "AND description LIKE '%%%s%%' " .
                    "AND ts >= (NOW() - INTERVAL 1 MINUTE)";

                $sql = sprintf(
                    $query,
                    self::REDCAP_LOG_EVENT_TABLE,
                    $project_id,
                    self::CREATE_RECORD_API_DESCRIPTION
                );

                // DEBUG
                Plugin::log("Checking in DB if new records arrived", $sql);

                $result_records = $this->query($sql);
                if ($result_records->num_rows > 0) {
                    // DEBUG
                    Plugin::log("New records created during the last minute!");

                    // Send email notification to users with 'minute' frequency
                    // configured in project settings
                    foreach ($recipients as $key => $recipient) {
                        if ($recipient['frequency'] == "minute") {
                            // DEBUG
                            Plugin::log(
                                "Sending email notification to " . $recipient['user']
                            );

                            // Get user email
                            $query = "SELECT * FROM %s WHERE username = '%s'";
                            $sql = sprintf(
                                $query,
                                self::REDCAP_USER_INFORMATION_TABLE,
                                $recipient['user']
                            );

                            // DEBUG
                            Plugin::log("Checking in DB user information", $sql);

                            $result_user = $this->query($sql);
                            $user = $result_user->fetch_assoc();

                            // DEBUG
                            Plugin::log("Result:", $user);

                            REDCap::email(
                                $user['user_email'],
                                $sender,
                                $project_lang['em_email_notifications_01'],
                                "Is this test working?"
                            );
                        }
                    }
                }
            }
        }
    }
}
