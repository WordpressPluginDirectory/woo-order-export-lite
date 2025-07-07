<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Order_Export_Order_Coupon_Fields {
	var $item;
	var $coupon_meta;
	var $static_vals;
	var $coupon_object = false;

	public function __construct($item, $labels, $static_vals) {
		global $wpdb;

		$this->coupon_meta     = array();
		$get_coupon_meta = array_intersect( $labels->get_keys(), array( 'code', 'discount_amount', 'discount_amount_tax', 'excerpt' ) ) ;

		if ( $get_coupon_meta ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$recs = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value,meta_key FROM {$wpdb->postmeta} AS meta
				JOIN {$wpdb->posts} AS posts ON posts.ID = meta.post_id
				WHERE posts.post_title=%s", $item->get_name() ) );
			foreach ( $recs as $rec ) {
				$this->coupon_meta[ $rec->meta_key ] = $rec->meta_value;
			}

			try {
				$this->coupon_object = new WC_Coupon( $item->get_name() );
				foreach ( $this->coupon_object->get_meta_data() as $meta) {
					$this->coupon_meta[ $meta->key ] = $meta->value;
				};
			} catch (Exception $e) {
				// Invalid coupon. deleted ?
			}
		}
		$this->item = $item;
		$this->static_vals = $static_vals;
	}

	public function get($field) {
		if ( method_exists( $this->item, "get_$field" ) ) {
			return $this->item->{"get_$field"}();
		} elseif ( $field == 'code' ) {
			return $this->item->get_name();
		} elseif ( $field == 'discount_amount_plus_tax' ) {
			return (float)$this->item->get_discount() + (float)$this->item->get_discount_tax();
		} elseif ( $field == 'discount_amount' ) {
			return $this->item->get_discount();
		} elseif ( $field == 'discount_amount_tax' ) {
			return $this->item->get_discount_tax();
		} elseif ( $field == 'excerpt' ) {
			return $this->coupon_object ? $this->coupon_object->get_description(): '';
		} elseif ( isset( $this->coupon_meta[ $field ] ) ) {
			return $this->coupon_meta[ $field ];
		} elseif ( isset( $this->static_vals[ $field ] ) ) {
			return $this->static_vals[ $field ];
		} else {
			return '';
		}
	}

	public function get_coupon_meta() {
		return $this->coupon_meta;
	}
}
