<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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
					$user_ids    = join( ",", $user_ids );
					$where_users = "WHERE user_id IN ($user_ids)";
				} else {
					$where_users = '';
				}
				$user_fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} $where_users" );
				$order_fields      = self::get_order_custom_fields();
			} else {
				$user_fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->prefix}wc_orders INNER JOIN {$wpdb->usermeta} ON {$wpdb->prefix}wc_orders.customer_id = {$wpdb->usermeta}.user_id WHERE type = '" . self::$object_type . "' {$sql_in_orders}" );
				$order_fields      = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->prefix}wc_orders INNER JOIN {$wpdb->prefix}wc_orders_meta ON {$wpdb->prefix}wc_orders.ID = {$wpdb->prefix}wc_orders.order_id WHERE type = '" . self::$object_type . "' {$sql_in_orders}" );
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

		$order_ids   = $wpdb->get_col( "SELECT ID FROM {$wpdb->prefix}wc_orders WHERE type = '" . self::$object_type . "' ORDER BY ID DESC LIMIT " . self::HUGE_SHOP_ORDERS );
		if( empty($order_ids) )
			return array();
		$order_ids   = join( ",", $order_ids );

		if( self::is_HPOS_orders_field($key) ) {
			$field = substr($key,1) ;// ignore leading _
			$values = $wpdb->get_col( "SELECT DISTINCT $field FROM {$wpdb->prefix}wc_orders WHERE id IN ($order_ids)" );
		}elseif( $hpos_addr = self::parse_HPOS_order_address_field($key) ) {
			$values = $wpdb->get_col( "SELECT DISTINCT $hpos_addr[field] FROM {$wpdb->prefix}wc_order_addresses WHERE order_id IN ($order_ids) AND address_type = '$hpos_addr[address_type]'" );
		} else {
			$values = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT meta_value FROM {$wpdb->prefix}wc_orders_meta WHERE meta_key = %s  AND order_id IN ($order_ids)", $key ) );
		}
		sort( $values );
		return apply_filters( 'woe_get_order_custom_fields_values', $values, $key);
	}

	public static function get_order_meta_values( $type, $key ) {
		global $wpdb;

		$key = strtolower($key);

		if( !in_array($key, self::$table_order_address_fields) )
			return array();


		$order_ids   = $wpdb->get_col( "SELECT ID FROM {$wpdb->prefix}wc_orders WHERE type = '" . self::$object_type . "' ORDER BY ID DESC LIMIT " . self::HUGE_SHOP_ORDERS );
		if( empty($order_ids) )
			return array();

		$order_ids   = join( ",", $order_ids );

		$query   = $wpdb->prepare( "SELECT DISTINCT $key FROM {$wpdb->prefix}wc_order_addresses WHERE address_type = %s AND order_id IN($order_ids)",
			array( trim($type,"_") ) );
		$results = $wpdb->get_col( $query );
		$data    = array_filter( $results );
		sort( $data );
		return $data;
	}

	public static function get_item_meta_keys() {
		global $wpdb;

		$names = $wpdb->get_results( "SELECT distinct order_item_type,meta_key  FROM  {$wpdb->prefix}woocommerce_order_items AS items
			INNER JOIN (SELECT ID AS order_id FROM {$wpdb->prefix}wc_orders WHERE type='shop_order' ORDER BY ID DESC LIMIT " . self::HUGE_SHOP_ORDERS . " ) AS orders ON orders.order_id = items.order_id
			JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS meta ON meta.order_item_id = items.order_item_id
			ORDER BY order_item_type,meta_key" );

		$keys = array();
		foreach ( $names as $n ) {
			$keys[ $n->order_item_type ][ $n->meta_key ] = $n->meta_key;
		}

		return $keys;
	}
}
