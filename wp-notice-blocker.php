<?php
/*
Plugin Name: Notice Blocker
Plugin URI: https://hasinur.me/wp-notice-blocker/
Description: A WordPress admin notice blocker plugin
Version: 1.0
Author: Hasinur Rahman
Author URI: https://hasinur.me/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-notice-blocker
Domain Path: /languages
*/

/**
 * Copyright (c) YEAR Hasinur Rahman (email: hasinurrahman3@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base_Plugin class
 *
 * @class Base_Plugin The class that holds the entire Base_Plugin plugin
 */
class WP_Notice_Blocker {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * Constructor for the WP_Admin_Notice_Blocker class
	 *
	 * Sets up all the appropriate hooks and actions
	 * within our plugin.
	 *
	 * @uses register_activation_hook()
	 * @uses register_deactivation_hook()
	 * @uses is_admin()
	 * @uses add_action()
	 */
	public function __construct() {

		$this->define_constants();

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Define the constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'WP_NB_VERSION', $this->version );
		define( 'WP_NB_FILE', __FILE__ );
		define( 'WP_NB_PATH', dirname( WP_NB_FILE ) );
		define( 'WP_NB_INCLUDES', WP_NB_PATH . '/includes' );
		define( 'WP_NB_URL', plugins_url( '', WP_NB_FILE ) );
		define( 'WP_NB_ASSETS', WP_NB_URL . '/assets' );
	}

	/**
	 * Initializes the Base_Plugin() class
	 *
	 * Checks for an existing Base_Plugin() instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new WP_Notice_Blocker();
		}

		return $instance;
	}

	/**
	 * Placeholder for activation function
	 *
	 * Nothing being called here yet.
	 */
	public function activate() {

		update_option( 'wp_notice_blocker_version', WP_NB_VERSION );
	}

	/**
	 * Placeholder for deactivation function
	 *
	 * Nothing being called here yet.
	 */
	public function deactivate() {

	}

	/**
	 * Include the required files
	 *
	 * @return void
	 */
	public function includes() {

	}

	/**
	 * Initialize the hooks
	 *
	 * @return void
	 */
	public function init_hooks() {
		// Localize our plugin
		add_action( 'init', array( $this, 'localization_setup' ) );

		// Block Notice
		add_action('in_admin_header', [ $this, 'block_notice' ], 1000);

		// Loads frontend scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @uses load_plugin_textdomain()
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'wp-notice-blocker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Enqueue admin scripts
	 *
	 * Allows plugin assets to be loaded.
	 *
	 * @uses wp_enqueue_script()
	 * @uses wp_localize_script()
	 * @uses wp_enqueue_style
	 */
	public function enqueue_scripts() {

		/**
		 * All styles goes here
		 */
		wp_enqueue_style( 'wp-notice-blocker-styles', plugins_url( 'assets/css/style.css', __FILE__ ), false, date( 'Ymd' ) );

		/**
		 * All scripts goes here
		 */
		wp_enqueue_script( 'wp-notice-blocker-scripts', plugins_url( 'assets/js/script.js', __FILE__ ), array( 'jquery'
		), false, true );


		/**
		 * Example for setting up text strings from Javascript files for localization
		 *
		 * Uncomment line below and replace with proper localization variables.
		 */
		// $translation_array = array( 'some_string' => __( 'Some string to translate', 'wp-notice-blocker' ),
		// 'a_value' =>
//'10' );
		// wp_localize_script( 'base-plugin-scripts', 'wp-notice-blocker', $translation_array ) );

	}

	/**
	 * Block all admin notice
	 *
	 * @return void
	 */
	public function block_notice() {
		remove_all_actions('admin_notices');
		remove_all_actions('all_admin_notices');
	}

} // WP_Plugin_Notice

$wp_notice_blocker = WP_Notice_Blocker::init();