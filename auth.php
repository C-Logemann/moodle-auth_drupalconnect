<?php

/**
 * @author Carsten Logemann (paratio.com e.K.)
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package auth_drupalconnect
 *
 * Authentication Plugin: drupalconnect
 *
 * Authentication is managed via REST interface of a drupal platform.
 *
 * 2012-02-28  File created.
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/authlib.php');

/**
 * Plugin for no authentication.
 */
class auth_plugin_drupalconnect extends auth_plugin_base {

    /**
     * Constructor.
     */
    function auth_plugin_drupalconnect() {
        $this->authtype = 'drupalconnect';
        $this->config = get_config('auth/drupalconnect');
    }

    /**
     * Returns true if the username and password work or don't exist and false
     * if the user exists and the password is wrong.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    function user_login ($username, $password) {
        global $CFG, $DB;

        require_once('drupal.inc');
        return drupalconnect_check($username, $password);

        //$user = $DB->get_record('user', array('username'=>$username, 'mnethostid'=>$CFG->mnet_localhost_id));
/*
        if ($user = $DB->get_record('user', array('username'=>$username, 'mnethostid'=>$CFG->mnet_localhost_id))) {
            return validate_internal_user_password($user, $password);
        }
*/

        return false;
    }

    /**
     * Updates the user's password.
     *
     * called when the user password is updated.
     *
     * @param  object  $user        User table object
     * @param  string  $newpassword Plaintext password
     * @return boolean result
     *

    function user_update_password($user, $newpassword) {
        $user = get_complete_user_data('id', $user->id);
        return update_internal_user_password($user, $newpassword);
    }
 */

    function prevent_local_passwords() {
        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return false;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url() {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return false;
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param array $page An object containing all the data for this page.
     */
    function config_form($config, $err, $user_fields) {
        include "config.html";
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     */
    function process_config($config) {
      global $CFG;
      if (!isset ($config->nodeid)) {
            $config->nodeid = '';
        }

        // save settings
        set_config('nodeid',    $config->nodeid,    'auth/drupalconnect');
        set_config('serviceurl',    $config->serviceurl,    'auth/drupalconnect');

        if (isset($config->secureswitch) && $config->secureswitch == 'on'){
            set_config('secureswitch',    $config->secureswitch,    'auth/drupalconnect');

        } else {
            if (isset($this->config->secureswitch) and $this->config->secureswitch == 'on'){
                set_config('secureswitch',    'off',    'auth/drupalconnect');
            }
            $config->secureswitch = 'off';
        }
        return true;
    }

}
