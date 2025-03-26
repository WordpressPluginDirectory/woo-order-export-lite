<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

trait WC_Order_Export_Admin_Tab_Abstract_Ajax {
	use WC_Order_Export_Admin_Tab_Abstract_Ajax_Filters;
	use WC_Order_Export_Admin_Tab_Abstract_Ajax_Export;

	public function ajax_save_settings() {

        $this->check_nonce();
		$settings = WC_Order_Export_Manage::make_new_settings( $_POST ); //phpcs:ignore WordPress.Security.NonceVerification.Missing

		/*
		array_walk_recursive($settings, function(&$_value, $_key) {
		    if ($_key !== 'custom_php_code'  AND $_key !== 'email_body') {
			$_value = esc_attr($_value);
		    }
		});
		*/
                $error = '';
                try {
					$mode = isset($_POST['mode']) ? sanitize_text_field(wp_unslash($_POST['mode'])) : '';   //phpcs:ignore WordPress.Security.NonceVerification.Missing
					$post_id = isset($_POST['id']) ? (int)sanitize_text_field(wp_unslash($_POST['id'])) : 0;//phpcs:ignore WordPress.Security.NonceVerification.Missing
                    $id = WC_Order_Export_Manage::save_export_settings( $mode, $post_id, $settings );
                } catch (Exception $ex) {
                    $error = $ex->getMessage();
                }

		echo json_encode( $error ? array('error' => $error) : array( 'id' => $id ) );
	}

	public function ajax_reset_profile() {
        $this->check_nonce();
		$mode = isset($_POST['mode']) ? sanitize_text_field(wp_unslash($_POST['mode'])) : '';   //phpcs:ignore WordPress.Security.NonceVerification.Missing
		$post_id = isset($_POST['id']) ? (int)sanitize_text_field(wp_unslash($_POST['id'])) : 0;//phpcs:ignore WordPress.Security.NonceVerification.Missing
		$id = WC_Order_Export_Manage::save_export_settings( $mode, $post_id, array() );
		wp_send_json_success();
	}

}