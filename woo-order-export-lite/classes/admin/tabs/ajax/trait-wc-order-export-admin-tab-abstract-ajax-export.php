<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

trait WC_Order_Export_Admin_Tab_Abstract_Ajax_Export {
	use WC_Order_Export_Ajax_Helpers;


	public function ajax_preview() {
        $this->check_nonce();

		$settings = WC_Order_Export_Manage::use_ready_or_prepare_settings( $_POST ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
		// use unsaved settings

		$id = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Missing
		do_action( 'woe_start_preview_job', $id, $settings );

		WC_Order_Export_Engine::kill_buffers();

		ob_start(); // we need html for preview , even empty!

		$total = WC_Order_Export_Engine::build_file( $settings, 'estimate_preview', 'file', 0, 0, 'test');

		$limit = isset($_POST['limit']) ? sanitize_text_field(wp_unslash($_POST['limit'])) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Missing
		WC_Order_Export_Engine::build_file( $settings, 'preview', 'browser', 0, $limit );

		$html = ob_get_contents();
		ob_end_clean();

		echo json_encode( array( 'total' => $total, 'html' => $html ) );
	}

	public function ajax_estimate() {

        $this->check_nonce();

		$settings = WC_Order_Export_Manage::use_ready_or_prepare_settings( $_POST ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
		// use unsaved settings

		$total = WC_Order_Export_Engine::build_file( $settings, 'estimate', 'file', 0, 0, 'test' );

		echo json_encode( array( 'total' => $total ) );
	}

	public function ajax_export_start() {
        $this->check_nonce();
		$this->start_prevent_object_cache();
		$settings = WC_Order_Export_Manage::use_ready_or_prepare_settings( $_POST ); //phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( $settings['format'] === 'XLS' && ! function_exists( "mb_strtolower" ) ) {
			die( esc_html__( 'Please, install/enable PHP mbstring extension!', 'woo-order-export-lite' ) );
		}

		$filename = WC_Order_Export_Engine::get_filename( "orders" );
		if ( ! $filename ) {
			die( esc_html__( 'Can\'t create temporary file', 'woo-order-export-lite' ) );
		}
		//no free space or other file system errors?
		try {
			file_put_contents( $filename, '' );
			$id = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Missing
			do_action( 'woe_start_export_job', $id, $settings );
			$result = WC_Order_Export_Engine::build_file( $settings, 'start_estimate', 'file', 0, 0, $filename );
		} catch ( Exception $e ) {
			die( esc_html($e->getMessage()) );
		}
		// file created
		$file_id = current_time( 'timestamp' );
		set_transient( $this->tempfile_prefix . $file_id, $filename, 5 * MINUTE_IN_SECONDS );
		$this->stop_prevent_object_cache();
		echo json_encode( array(
			'total' => $result['total'],
			'file_id' => $file_id,
			'max_line_items' => $result['max_line_items'],
			'max_coupons' => $result['max_coupons'],
		 ) );
	}


	public function ajax_export_part() {
        $this->check_nonce();

		$settings = WC_Order_Export_Manage::use_ready_or_prepare_settings( $_POST ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
		$main_settings = WC_Order_Export_Main_Settings::get_settings();

		$settings['max_line_items'] = isset($_POST['max_line_items']) ? sanitize_text_field(wp_unslash($_POST['max_line_items'])) : 10; //phpcs:ignore WordPress.Security.NonceVerification.Missing
		$settings['max_coupons'] = isset($_POST['max_coupons']) ? sanitize_text_field(wp_unslash($_POST['max_coupons'])) : 10; //phpcs:ignore WordPress.Security.NonceVerification.Missing

		$start = isset($_POST['start']) ? intval(sanitize_text_field(wp_unslash($_POST['start']))) : 0; //phpcs:ignore WordPress.Security.NonceVerification.Missing
		WC_Order_Export_Engine::build_file( $settings, 'partial', 'file', $start,
			$main_settings['ajax_orders_per_step'],
			$this->get_temp_file_name() );

		echo json_encode( array( 'start' => $start + $main_settings['ajax_orders_per_step'] ) );
	}

	public function ajax_plain_export() {

        $this->check_nonce();
		// use unsaved settings
		$settings = WC_Order_Export_Manage::use_ready_or_prepare_settings( $_POST ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
		$id = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Missing
		do_action( 'woe_start_export_job', $id, $settings );

		// custom export worked for plain
		if ( apply_filters( 'woe_plain_export_custom_func', false, $id, $settings ) ) {
			return;
		}

		if ( $settings['format'] === 'XLS' && ! function_exists( "mb_strtolower" ) ) {
			die( esc_html__( 'Please, install/enable PHP mbstring extension!', 'woo-order-export-lite' ) );
		}

		$file = WC_Order_Export_Engine::build_file_full( $settings );
		//$order_id = WC_Order_Export_Engine::$orders_for_export;
		if ( $file !== false ) {
			$file_id = current_time( 'timestamp' );
			$this->start_prevent_object_cache();
			set_transient( $this->tempfile_prefix . $file_id, $file, 5 * MINUTE_IN_SECONDS );
			$this->stop_prevent_object_cache();

			WC_Order_Export_Manage::set_correct_file_ext( $settings );

			$_GET['format']  = $settings['format'];
			$_GET['file_id'] = $_REQUEST['file_id'] = $file_id;
			$filename = WC_Order_Export_Engine::make_filename( $settings['export_filename'] );
			$this->start_prevent_object_cache();
			set_transient( $this->tempfile_prefix . 'download_filename', $filename, 5 * MINUTE_IN_SECONDS );
			$this->stop_prevent_object_cache();

			$this->set_filename($filename);
			$this->set_tmp_filename($file);
			$this->ajax_export_download();
		} else {
			esc_html_e( 'Nothing to export. Please, adjust your filters', 'woo-order-export-lite' );
		}
	}


	public function ajax_export_download() {
        $this->check_nonce();

		$this->start_prevent_object_cache();
		$format   = isset($_GET['format']) ? basename( sanitize_text_field(wp_unslash($_GET['format'])) ) : 'xls'; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filename = $this->get_temp_file_name();
		$file_id   = isset($_GET['file_id']) ? basename( sanitize_text_field(wp_unslash($_GET['file_id'])) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		delete_transient( $this->tempfile_prefix . $file_id );

		$download_name = $this->filename ? $this->filename : get_transient( $this->tempfile_prefix . 'download_filename' );
		$this->send_headers( $format, $download_name );
		$this->send_contents_delete_file( $filename );
		$this->stop_prevent_object_cache();
	}

	public function ajax_export_finish() {
        $this->check_nonce();
		$settings = WC_Order_Export_Manage::use_ready_or_prepare_settings( $_POST ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
		WC_Order_Export_Engine::build_file( $settings, 'finish', 'file', 0, 0, $this->get_temp_file_name() );

		$filename = WC_Order_Export_Engine::make_filename( $settings['export_filename'] );
		$this->start_prevent_object_cache();
		set_transient( $this->tempfile_prefix . 'download_filename', $filename, 5 * MINUTE_IN_SECONDS );
		$this->stop_prevent_object_cache();
		echo json_encode( array( 'done' => true ) );
	}


	public function ajax_cancel_export() {
        $this->check_nonce();
		$this->delete_temp_file();
		echo json_encode( array() );
	}
}
