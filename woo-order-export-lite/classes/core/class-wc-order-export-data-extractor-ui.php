<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching

class WC_Order_Export_Data_Extractor_UI extends WC_Order_Export_Data_Extractor {
	use WOE_Core_Extractor_UI;

	static $object_type = 'shop_order';

	// ADD custom fields for export
	public static function get_all_order_custom_meta_fields( $sql_order_ids = '' ) {
		global $wpdb;

		$transient_key = 'woe_get_all_order_custom_meta_fields_results_' . md5( json_encode( $sql_order_ids ) ); // complex key
		$fields        = get_transient( $transient_key );
		if ( $fields === false ) {
			$sql_in_orders = '';
			if ( $sql_order_ids ) {
				$sql_in_orders = " AND ID IN ($sql_order_ids) ";
			}

			// must show all
			if ( ! $sql_in_orders ) {
				//rewrite for huge # of users
				$total_users = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->users}" );
				if ( $total_users >= self::HUGE_SHOP_CUSTOMERS ) {
					$user_ids    = $wpdb->get_col( "SELECT  ID FROM {$wpdb->users} ORDER BY ID DESC LIMIT 1000" ); // take last 1000
					$list_placeholders = implode(',', array_fill(0, count($user_ids), '%d'));
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- Ignored for allowing interpolation in the IN statement.
					$where_users = $wpdb->prepare("WHERE user_id IN ($list_placeholders)",$user_ids);
				} else {
					$where_users = '';
				}
				$user_fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} $where_users" );//phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$order_fields      = self::get_order_custom_fields();
			} else {
				$user_fields = $wpdb->get_col( $wpdb->prepare("SELECT DISTINCT meta_key FROM {$wpdb->posts} INNER JOIN {$wpdb->usermeta} ON {$wpdb->posts}.post_author = {$wpdb->usermeta}.user_id WHERE post_type = %s {$sql_in_orders}",self::$object_type) );//phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$order_fields      = $wpdb->get_col( $wpdb->prepare("SELECT DISTINCT meta_key FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE post_type = %s {$sql_in_orders}",self::$object_type) );//phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			}

			foreach ( $user_fields as $k => $v ) {
				$user_fields[ $k ] = 'USER_' . $v;
			}

			$user_fields = array_unique( $user_fields );
			$order_fields = array_unique( $order_fields );
			sort( $user_fields );
			sort( $order_fields );

			$fields = array(
				'user' => $user_fields,
				'order' => $order_fields,
			);
			//debug set_transient( $transient_key, $fields, 60 ); //valid for a 1 min
		}

		return apply_filters( 'woe_get_all_order_custom_meta_fields', $fields );
	}


	public static function get_order_custom_fields_values( $key ) {
		global $wpdb;

		$order_ids   = $wpdb->get_col( $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_type = %s ORDER BY ID DESC LIMIT %d", self::$object_type , self::HUGE_SHOP_ORDERS) );
		if( empty($order_ids) )
			return array();

		$list_placeholders = implode(',', array_fill(0, count($order_ids), '%d'));
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- Ignored for allowing interpolation in the IN statement.
		$values = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s  AND post_id IN ($list_placeholders)", array_merge([$key],$order_ids) ) );
		sort( $values );

		return apply_filters( 'woe_get_order_custom_fields_values', $values, $key);
	}

	public static function get_order_meta_values( $type, $key ) {
		global $wpdb;

		$order_ids   = $wpdb->get_col(  $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_type = %s ORDER BY ID DESC LIMIT %d", self::$object_type,self::HUGE_SHOP_ORDERS) );
		if( empty($order_ids) )
			return array();

		$list_placeholders = implode(',', array_fill(0, count($order_ids), '%d'));
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- Ignored for allowing interpolation in the IN statement.
		$results = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s AND post_id IN($list_placeholders)",	array_merge( [$type . strtolower( $key )],$order_ids ) ) );
		$data    = array_filter( $results );
		sort( $data );

		return $data;
	}

	public static function get_item_meta_keys() {
		global $wpdb;

		$names = $wpdb->get_results( $wpdb->prepare("SELECT distinct order_item_type,meta_key  FROM  {$wpdb->prefix}woocommerce_order_items AS items
			INNER JOIN (SELECT ID AS order_id FROM {$wpdb->prefix}posts WHERE post_type='shop_order' ORDER BY ID DESC LIMIT %d ) AS orders ON orders.order_id = items.order_id
			JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS meta ON meta.order_item_id = items.order_item_id
			ORDER BY order_item_type,meta_key", self::HUGE_SHOP_ORDERS) );

		$keys = array();
		foreach ( $names as $n ) {
			$keys[ $n->order_item_type ][ $n->meta_key ] = $n->meta_key;
		}

		return $keys;
	}
}
