<?php
/**
 * Configuration class for the plugin.
 *
 * @package AwesomeMotive\AMPlugin
 */

namespace AwesomeMotive\AMPlugin;

/**
 * Set configuration options.
 */
class Config {

	/**
	 * URL to plugin root folder.
	 *
	 * @var string
	 */
	public static $plugin_url;

	/**
	 * Path to plugin root folder.
	 *
	 * @var string
	 */
	public static $plugin_path;

	/**
	 * Plugin slug to be used for nonce, scripts enqueue, etc.
	 *
	 * @var string
	 */
	const SLUG = 'am-plugin';

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '@##VERSION##@';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		self::$plugin_url  = plugin_dir_url( dirname( __FILE__ ) );
		self::$plugin_path = plugin_dir_path( dirname( __FILE__ ) );
	}

	/**
	 * Get plugin name.
	 *
	 * @return string
	 */
	public static function get_plugin_name() : string {
		return esc_html__( 'AM Plugin', 'am-plugin' );
	}

}
