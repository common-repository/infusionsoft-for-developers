<?php
/*
Plugin Name: 	Infusionsoft Developers Plugin
Plugin URI: 	http://wordpress.org/plugins/infusionsoft-for-developers/
Description: 	This plugin is primarily designed for developers adding Infusionsoft API hooks for use in WP. It only provides a basic feature set for the average WordPress user.
Version: 		0.2
Author: 		Infusionsoft
Author URI: 	http://infusionsoft.com
License: 		GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2014 Infusionsoft (email : info@infusionsoft.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

global $infusionsoft;

require_once plugin_dir_path( __FILE__ ) . 'infusionsoft.php';
require_once plugin_dir_path( __FILE__ ) . 'infusionsoft-examples.php';
require_once plugin_dir_path( __FILE__ ) . 'infusionsoft-gravityforms.php';
require_once plugin_dir_path( __FILE__ ) . 'infusionsoft-settings.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

// Load main Infusionsoft API
$settings = (array) get_option( 'infusionsoft_settings' );
if ( isset( $settings['subdomain'] ) && isset( $settings['api_key'] ) && isset( $settings['gf_integration'] ) ) {
	$infusionsoft = new Infusionsoft( $settings['subdomain'], $settings['api_key'] );

	// Make sure Infusionsoft connected
	if ( is_wp_error( $infusionsoft->error ) ) {
		$error = $infusionsoft->error->get_error_message();
		add_action( 'admin_notices', create_function( '$error', 'echo "<div class=\"error\"><p><strong>Infusionsoft Error:</strong> ' . $error . '</p></div>";' ) );
	}

}

class Infusionsoft_WP {
	/**
	 * Calls all actions and hooks used by the plugin
	 */
	public function __construct() {
		$settings = (array) get_option( 'infusionsoft_settings' );

		// Load Gravity Forms integration if enabled
		if ( isset( $settings['gf_integration'] ) && $settings['gf_integration'] && ! is_plugin_active( 'infusionsoft/infusionsoft.php' ) ) {
			$infusionsoft_gravityforms = new Infusionsoft_GravityForms;
		}
	}
}

// Start the plugin
$infusionsoft_wp = new Infusionsoft_WP;