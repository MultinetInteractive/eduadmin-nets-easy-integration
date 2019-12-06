<?php
defined( 'ABSPATH' ) || die( 'This plugin must be run within the scope of WordPress.' );

/*
 * Plugin Name: EduAdmin Booking - Dibs Easy integration-plugin
 * Plugin URI:  https://www.eduadmin.se
 * Description: Plugin to EduAdmin Booking to enable the Dibs Easy-integration
 * Version:	$PLUGINVERSION$
 * GitHub Plugin Uri: https://github.com/MultinetInteractive/eduadmin-dibs-easy-integration
 * Requires at least: $PLUGINATLEAST$
 * Tested up to: $PLUGINTESTEDTO$
 * Author: Chris Gårdenberg, MultiNet Interactive AB
 * Author URI: https://www.multinet.com
 * License: GPL3
 * Text Domain: eduadmin-dibs-easy-integration
 */
/*
	EduAdmin Booking plugin
	Copyright (C) 2015-2019 Chris Gårdenberg, MultiNet Interactive AB
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

add_action( 'admin_init', function () {
	if ( is_admin() && current_user_can( 'activate_plugins' ) && ( ! is_plugin_active( 'eduadmin-booking/eduadmin.php' ) && ! is_plugin_active( 'eduadmin/eduadmin.php' ) ) ) {
		add_action( 'admin_notices', function () {
			?>
            <div class="error">
                <p><?php esc_html_e( 'This plugin requires the EduAdmin-WordPress-plugin to be installed and activated.', 'eduadmin-dibs-easy-integration' ); ?></p>
            </div>
			<?php
		} );
		deactivate_plugins( plugin_basename( __FILE__ ) );
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
} );

if ( ! class_exists( 'EDU_DibsEasy_Loader' ) ) {
	final class EDU_DibsEasy_Loader {
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'init' ) );
		}

		public function init() {
			if ( class_exists( 'EDU_Integration' ) ) {
				require_once __DIR__ . '/class-eduadmin-dibs-easy-integration.php';

				add_filter( 'edu_integrations', array( $this, 'add_integration' ) );
			}
		}

		public function add_integration( $integrations ) {
			$integrations[] = "EDU_DibsEasy";

			return $integrations;
		}
	}

	$edu_dibseasy_loader = new EDU_DibsEasy_Loader();
}
