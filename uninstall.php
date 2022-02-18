<?php
/**
 * File which gets called on plugin uninstall. Since the plugin does not do any
 * sort of setup, nothing is done over here.
 * 
 * Ideally, this file is used to cleanup plugin stuff from the database, etc.
 *
 * @package AwesomeMotive\AMPlugin
 */

namespace AwesomeMotive\AMPlugin;

// Prevent unauthorized access.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
