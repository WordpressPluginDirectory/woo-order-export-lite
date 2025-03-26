<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Order_Export_Admin_Tab_Tools extends WC_Order_Export_Admin_Tab_Abstract {
	const KEY = 'tools';

	public function __construct() {
		$this->title = __( 'Tools', 'woo-order-export-lite' );
	}

	public function render() {
		$this->render_template( 'tab/tools' );
	}

	public function ajax_save_tools() {
        $nonce = isset($_GET['woe_nonce']) ? sanitize_text_field(wp_unslash($_GET['woe_nonce'])) :
            (isset($_POST['woe_nonce']) ? sanitize_text_field(wp_unslash($_POST['woe_nonce'])) : '');

        if ( empty($nonce) || ! wp_verify_nonce($nonce, 'woe_nonce') ) {
            wp_send_json_error( array( 'message' => 'Nonce verification failed' ) );
            exit;
        }
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$tools_import = isset($_POST['tools-import']) ?$_POST['tools-import'] : '';
		$data = json_decode( $tools_import, true );

		if ( $data ) {
			WC_Order_Export_Manage::import_settings( $data );
		}
	}
}