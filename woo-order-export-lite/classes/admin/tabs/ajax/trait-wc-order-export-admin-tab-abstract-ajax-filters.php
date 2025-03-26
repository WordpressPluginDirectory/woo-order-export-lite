<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

trait WC_Order_Export_Admin_Tab_Abstract_Ajax_Filters {

    private function check_nonce() {
        $nonce = isset($_GET['woe_nonce']) ? sanitize_text_field(wp_unslash($_GET['woe_nonce'])) :
            (isset($_POST['woe_nonce']) ? sanitize_text_field(wp_unslash($_POST['woe_nonce'])) : '');

        if ( empty($nonce) || ! wp_verify_nonce($nonce, 'woe_nonce') ) {
            wp_send_json_error( array( 'message' => 'Nonce verification failed' ) );
            exit;
        }
    }

    /**
	 * Select2 method
	 */
	public function ajax_get_products() {

        $this->check_nonce();
		$main_settings = WC_Order_Export_Main_Settings::get_settings();

		$limit = $main_settings['show_all_items_in_filters'] ? null : $main_settings['autocomplete_products_max'];
		$q = isset($_REQUEST['q']) ? sanitize_text_field(wp_unslash($_REQUEST['q'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo json_encode( apply_filters( "woe_ajax_get_products", WC_Order_Export_Data_Extractor_UI::get_products_like( $q, $limit ),  $q, $limit ) );
	}

	/**
	 * Select2 method
	 */
	public function ajax_get_users() {
		$this->check_nonce();
		$q = isset($_REQUEST['q']) ? sanitize_text_field(wp_unslash($_REQUEST['q'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_users_like( $q ) );
	}

	/**
	 * Select2 method
	 */
	public function ajax_get_coupons() {
		$this->check_nonce();
		$q = isset($_REQUEST['q']) ? sanitize_text_field(wp_unslash($_REQUEST['q'])) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_coupons_like( $q ) );
	}

	/**
	 * Select2 method
	 */
	public function ajax_get_categories() {
		$this->check_nonce();
		$main_settings = WC_Order_Export_Main_Settings::get_settings();
		$limit         = $main_settings['show_all_items_in_filters'] ? null : 10;
		$q = isset($_REQUEST['q']) ? sanitize_text_field(wp_unslash($_REQUEST['q'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_categories_like( $q, $limit ) );
	}

	/**
	 * Select2 method
	 */
	public function ajax_get_vendors() {
		$this->check_nonce();
		$this->ajax_get_users();
	}

	public function ajax_get_used_custom_order_meta() {
        $this->check_nonce();
		$settings = WC_Order_Export_Manage::make_new_settings( $_POST );//phpcs:ignore WordPress.Security.NonceVerification.Missing
		$sql      = WC_Order_Export_Data_Extractor::sql_get_order_ids( $settings );
		$ret      = WC_Order_Export_Data_Extractor_UI::get_all_order_custom_meta_fields( $sql );
		echo json_encode( $ret );
	}

	public function ajax_get_used_custom_products_meta() {

        $this->check_nonce();
		$settings = WC_Order_Export_Manage::make_new_settings( $_POST );//phpcs:ignore WordPress.Security.NonceVerification.Missing
		$sql      = WC_Order_Export_Data_Extractor::sql_get_order_ids( $settings );
		$ret      = WC_Order_Export_Data_Extractor_UI::get_product_custom_meta_fields_for_orders( $sql );
		echo json_encode( $ret );
	}

	public function ajax_get_used_custom_order_items_meta() {

        $this->check_nonce();
		$settings = WC_Order_Export_Manage::make_new_settings( $_POST );//phpcs:ignore WordPress.Security.NonceVerification.Missing
		$sql      = WC_Order_Export_Data_Extractor::sql_get_order_ids( $settings );
		$ret      = WC_Order_Export_Data_Extractor_UI::get_order_item_custom_meta_fields_for_orders( $sql );
		echo json_encode( $ret );
	}

	public function ajax_get_used_custom_coupons_meta() {
        $this->check_nonce();
		$ret = array();
		echo json_encode( $ret );
	}

	public function ajax_get_order_custom_fields_values() {
        $this->check_nonce();
		$cf_name = isset($_POST['cf_name']) ? sanitize_text_field(wp_unslash($_POST['cf_name'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_order_custom_fields_values( $cf_name ) );
	}

	public function ajax_get_user_custom_fields_values() {
        $this->check_nonce();
		$cf_name = isset($_POST['cf_name']) ? sanitize_text_field(wp_unslash($_POST['cf_name'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_user_custom_fields_values( $cf_name ) );
	}

	public function ajax_get_product_custom_fields_values() {
        $this->check_nonce();
		$cf_name = isset($_POST['cf_name']) ? sanitize_text_field(wp_unslash($_POST['cf_name'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_product_custom_fields_values( $cf_name ) );
	}

	public function ajax_get_products_taxonomies_values() {
        $this->check_nonce();
		$tax = isset($_POST['tax']) ? sanitize_text_field(wp_unslash($_POST['tax'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_products_taxonomies_values( $tax ) );
	}

	public function ajax_get_products_attributes_values() {
        $this->check_nonce();
		$attr = isset($_POST['attr']) ? sanitize_text_field(wp_unslash($_POST['attr'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_products_attributes_values( $attr ) );
	}

	public function ajax_get_products_itemmeta_values() {
        $this->check_nonce();
		$item = isset($_POST['item']) ? sanitize_text_field(wp_unslash($_POST['item'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_products_itemmeta_values( $item ) );
	}

	public function ajax_get_order_shipping_values() {
        $this->check_nonce();
		$item = isset($_POST['item']) ? sanitize_text_field(wp_unslash($_POST['item'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_order_meta_values( '_shipping_', $item ) );
	}

	public function ajax_get_order_billing_values() {
        $this->check_nonce();
		$item = isset($_POST['item']) ? sanitize_text_field(wp_unslash($_POST['item'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_order_meta_values( '_billing_', $item ) );
	}

	public function ajax_get_order_item_names() {
        $this->check_nonce();
		$item_type = isset($_POST['item_type']) ? sanitize_text_field(wp_unslash($_POST['item_type'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_order_item_names( $item_type ) );
	}

	public function ajax_get_order_item_meta_key_values() {
        $this->check_nonce();
		$meta_key = isset($_POST['meta_key']) ? sanitize_text_field(wp_unslash($_POST['meta_key'])) : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_order_item_meta_key_values( $meta_key ) );
	}

	public function ajax_get_used_order_fee_items() {

		$ret = WC_Order_Export_Data_Extractor::get_order_fee_items();

		$ret = array_map(function ($v) { return 'FEE_' . $v; }, $ret);

		echo json_encode( $ret );
	}

	public function ajax_get_used_order_shipping_items() {

		$ret = WC_Order_Export_Data_Extractor::get_order_shipping_items();

		$ret = array_map(function ($v) { return 'SHIPPING_' . $v; }, $ret);

		echo json_encode( $ret );
	}

	public function ajax_get_used_order_tax_items() {

		$ret = WC_Order_Export_Data_Extractor::get_order_tax_items();

		$ret = array_map(function ($v) { return 'TAX_' . $v; }, $ret);

		echo json_encode( $ret );
	}

}