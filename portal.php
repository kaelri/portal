<?php

/*
Plugin Name: Portal
Plugin URI: https://github.com/kaelri/portal
Version: 0.1
Author: Michael Engard
Author URI: https://www.kaelri.com
Description: A set of tools to enable cleaner and simpler communication between your client-side JavaScript communicate with the WordPress REST API.
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: portal
*/

require_once plugin_dir_path ( __FILE__ ) . '/classes/portal.php';

Portal::setup( __FILE__ );
