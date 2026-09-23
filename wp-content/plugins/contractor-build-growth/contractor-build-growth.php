<?php
/**
 * Plugin Name: Contractor Build Growth System
 * Description: Content architecture, lead capture, attribution, tracking hooks, schema, redirects, and starter content for Contractor Build.
 * Version: 1.0.0
 * Requires PHP: 8.1
 * Text Domain: contractor-build-growth
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CBG_VERSION', '1.0.0' );
define( 'CBG_FILE', __FILE__ );
define( 'CBG_DIR', plugin_dir_path( __FILE__ ) );

require_once CBG_DIR . 'includes/content-types.php';
require_once CBG_DIR . 'includes/forms.php';
require_once CBG_DIR . 'includes/seo.php';
require_once CBG_DIR . 'includes/settings.php';
require_once CBG_DIR . 'includes/setup.php';

register_activation_hook( __FILE__, 'cbg_activate' );
function cbg_activate() {
	cbg_register_content_types();
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
