<?php
/**
 * Plugin Name: AM Plugin
 * Description: Plugin crafted for Awesome Motive.
 * Version: @##VERSION##@
 * Requires at least: 4.2
 * Requires PHP: 7.3
 * Author: akshitsethi
 * Text Domain: am-plugin
 * Domain Path: i18n
 * Author URI: https://akshitsethi.com
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package AwesomeMotive\AMPlugin
 */

namespace AwesomeMotive\AMPlugin;

// Stop execution if the file is called directly.
defined( 'ABSPATH' ) || exit;

// Composer autoloder file.
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Plugin class where all the action happens.
 *
 * @category    Plugins
 * @package     AwesomeMotive\AMPlugin
 */
class AMPlugin {

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		if ( class_exists( 'WP_CLI' ) ) {
			\WP_CLI::add_command( 'amplugin', __NAMESPACE__ . '\Admin\CLI' );
		}
	}

	/**
	 * Initialize plugin when all the plugins have been loaded.
	 *
	 * @return void
	 */
	public function init() : void {
		new Config();
		new Admin();
		new Front();

		load_plugin_textdomain( Config::SLUG, false, Config::$plugin_path . 'i18n/' );
	}

	/**
	 * Runs when the plugin is activated.
	 *
	 * @return void
	 */
	public function activate() : void {
		// Stuff like populating plugin options or DB schema happens here.
	}

}

// Initialize plugin.
$am_plugin = new AMPlugin();

/**
 * To be run on plugin activation.
 */
register_activation_hook( __FILE__, array( $am_plugin, 'activate' ) );
