<?php

/**
 * Plugin name: BEA Facetwp Import Export
 * Author : Beapi
 * Author URI : http://beapi.fr
 * Version: 0.1
 * Description: Allows to grab json export as json file and not copy/paste code
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BEA_FACETWPIE_VERSION', '0.1' );
define( 'BEA_FACETWPIE_DIR', plugin_dir_path( __FILE__ ) );
define( 'BEA_FACETWPIE_URL', plugin_dir_url( __FILE__ ) );

if ( ! file_exists( BEA_FACETWPIE_DIR . 'vendor/autoload.php' ) ) {
	return false;
}

require BEA_FACETWPIE_DIR . 'vendor/autoload.php';

add_action( 'plugins_loaded', 'bea_facetwp_import_export' );
function bea_facetwp_import_export() {

	if ( is_admin() ) {
		add_action( 'admin_init', array( 'BEA_FacetWP_Checking', 'admin_init' ) );
		new BEA_FacetWP_Export_Import();
	}
}