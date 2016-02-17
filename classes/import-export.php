<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEA_FacetWP_Export_Import {

	public function __construct(){

		if ( ! class_exists( 'FacetWP' ) ) {
			return false;
		}

		add_action( 'admin_menu', array( $this, 'submenu_page' ) );
		add_action( 'admin_init', array( $this, 'export_settings' ) );
		add_action( 'admin_init', array( $this, 'import_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

	}

	public function submenu_page() {
		add_submenu_page(
			'tools.php',
			'FacetWP ' . __( 'Import' ) . ' ' . __( 'Export' ),
			'FacetWP ' . __( 'Import' ) . ' ' . __( 'Export' ),
			'manage_options',
			'facetwp-export-import',
			array( $this, 'callback' ) );

	}


	/**
	 * @return bool|void
	 * @author Julien Maury
	 */
	public function admin_enqueue_scripts( $hook_suffix ){

		if ( 'tools_page_facetwp-export-import' !== $hook_suffix ) {
			return false;
		}

		wp_register_script( 'bea-facetwp-ie', BEA_FACETWPIE_URL . 'js/admin.js', array(), BEA_FACETWPIE_VERSION, true );
		wp_enqueue_script( 'bea-facetwp-ie' );
	}


	/**
	 * @return bool
	 * @author Julien Maury
	 */
	public function callback() {

		// Export feature
		$export   = array();
		$settings = FacetWP::instance()->helper->settings;

		foreach ( $settings['facets'] as $facet ) {
			$export[ 'facet-' . $facet['name'] ] = 'Facet - ' . $facet['label'];
		}

		foreach ( $settings['templates'] as $template ) {
			$export[ 'template-' . $template['name'] ] = 'Template - ' . $template['label'];
		}

		require BEA_FACETWPIE_DIR . 'views/settings.php';
	}

	/**
	 * Export your settings
	 * @author Julien Maury
	 * @return bool|void
	 */
	public function export_settings() {

		if ( empty( $_POST['export_facetwp'] ) || empty( $_POST['action'] ) || 'export_facetwp_settings' !== $_POST['action'] ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['export_facetwp_nonce'], 'export_facetwp_nonce' ) ) {
			return;
		}

		$items = $_POST['export_facetwp'];

		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				if ( 'facet' == substr( $item, 0, 5 ) ) {
					$item_name          = substr( $item, 6 );
					$output['facets'][] = FacetWP::instance()->helper->get_facet_by_name( $item_name );
				} elseif ( 'template' == substr( $item, 0, 8 ) ) {
					$item_name             = substr( $item, 9 );
					$output['templates'][] = FacetWP::instance()->helper->get_template_by_name( $item_name );
				}
			}
		}

		ignore_user_abort( true );
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=facetwp-settings-export-' . strtotime( 'now' ) . '.json' );
		header( 'Expires: 0' );
		echo json_encode( $output );
		exit;

	}

	/**
	 * Import settings from file .json
	 * @return bool|void
	 * @author Julien Maury
	 */
	public function import_settings() {
		if ( empty( $_POST['action'] ) || 'import_facetwp_settings' !== $_POST['action'] ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['import_facetwp_nonce'], 'import_facetwp_nonce' ) ) {
			return;
		}

		$extension = end( explode( '.', $_FILES['import_file']['name'] ) );
		if ( 'json' !== $extension ) {
			wp_die( __( 'Please upload a valid .json file' ) );
		}
		$import_file = $_FILES['import_file']['tmp_name'];
		if ( empty( $import_file ) ) {
			wp_die( __( 'Please upload a file to import' ) );
		}

		$settings    = FacetWP::instance()->helper->settings;
		$import_code = (array) json_decode( file_get_contents( $import_file ), true);
		$status = array(
			'imported' => array(),
			'skipped' => array(),
		);

		$overwrite   = !empty(  $_POST['import_facetwp_overwrite'] ) ? (int) $_POST['import_facetwp_overwrite'] : 0;
		foreach ( $import_code as $object_type => $object_items ) {
			foreach ( $object_items as $object_item ) {
				$is_match = false;
				foreach ( $settings[ $object_type ] as $key => $settings_item ) {
					if ( $object_item['name'] == $settings_item['name'] ) {
						if ( $overwrite ) {
							$settings[ $object_type ][ $key ] = $object_item;
							$status['imported'][]             = $object_item['label'];
						} else {
							$status['skipped'][] = $object_item['label'];
						}
						$is_match = true;

						break;
					}
				}

				if ( ! $is_match ) {
					$settings[ $object_type ][] = $object_item;
					$status['imported'][]       = $object_item['label'];
				}
			}
		}

		if ( ! empty( $settings ) ) {
			update_option( 'facetwp_settings', json_encode( $settings ) );
		}
		wp_safe_redirect( add_query_arg( 'page', 'facetwp-export-import', admin_url( 'tools.php' ) ) );
		exit;
	}
}