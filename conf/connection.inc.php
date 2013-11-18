<?php
ini_set('date.timezone', 'America/Chicago'); // Sets the default timezone used by all date/time functions in a script

define('DB_HOST', '%(domain)s');
define('DB_USER', '%(db_user)s');
define('DB_PASSWORD', '%(db_password)s');
define('DB_NAME', '%(db_name)s');

// Determine whether we're working on a local server
// OR on the real server:
if (get_cfg_var('IS_LIVE') == 1) {
  define('IS_LIVE', true);
} else {
  define('IS_LIVE', false);
}
?>