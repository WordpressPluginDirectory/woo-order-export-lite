<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var WC_Order_Export_Admin $WC_Order_Export WC_Order_Export_Admin instance
 * @var string                $mode            ( now | profiles | cron | order-action )
 * @var integer               $id              job id
 * @var string                $ajaxurl
 * @var array                 $show
 *
 */
$settings                 = WC_Order_Export_Manage::get( $mode, $id );
$settings                 = apply_filters( 'woe_settings_page_prepare', $settings );
$order_custom_meta_fields = WC_Order_Export_Data_Extractor_UI::get_all_order_custom_meta_fields();
$readonly_php             = WC_Order_Export_Admin::user_can_add_custom_php() ? '' : 'readonly';
$options                  = WC_Order_Export_Main_Settings::get_settings();

$pdf_format_available_options = array(
	'orientation' => array(
		'P' => esc_html__( 'Portrait', 'woo-order-export-lite' ),
		'L' => esc_html__( 'Landscape', 'woo-order-export-lite' ),
	),
	'page_size'   => array(
		'A3'     => 'A3',
		'A4'     => 'A4',
		'A5'     => 'A5',
		'letter' => esc_html__( 'Letter', 'woo-order-export-lite' ),
		'legal'  => esc_html__( 'Legal', 'woo-order-export-lite' ),
	),
);

function print_formats_field( $type, $segment = "", $selected = "", $custom_key = "" ) {
	if ( ! $type && $type !== 'meta' && $type !== 'field' && $type !== 'calculated' ) {
		return ;
	}
	$margin_left = 'meta' == $type ? '1px' : '4px';
	// colname_custom_field
	$id = $custom_key ? $custom_key : ($segment ? 'format_custom_' . $type . '_' . $segment : 'format_custom_' . $type);

	echo '<label for="' . esc_attr($id) . '">' .
		esc_html__( 'Field format', 'woo-order-export-lite' ) . ':' .
		'</label>' .
		'<select id="' . esc_attr($id) . '" style="max-width: 221px; margin-left: ' . esc_attr($margin_left) . '">' .
		'<option value="" >' . esc_html__( '-', 'woo-order-export-lite' ) . '</option>';

	foreach ( WC_Order_Export_Data_Extractor_UI::get_format_fields() as $format_id => $format_label ) {
		echo "<option value='".esc_attr($format_id)."' ".($selected === $format_id ? 'selected="selected"' : '').">".esc_html($format_label)."</option>";
	};
	echo '</select>';
}

function remove_time_from_date( $datetime ) {
	if ( ! $datetime ) {
		return "";
	}

	$timestamp = strtotime( $datetime );
	if ( ! $timestamp ) {
		return "";
	}

	$date = gmdate( 'Y-m-d', $timestamp );

	return $date ? $date : "";
}


?>

<?php
//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- optional parameter
$woe_order_post_type = isset($settings['post_type']) ? $settings['post_type'] : (isset($_GET['woe_post_type']) ? sanitize_text_field(wp_unslash($_GET['woe_post_type'])) : 'shop_order'); ?>

<script>
	var woe_order_post_type = '<?php echo esc_js( $woe_order_post_type ) ?>';
	var mode = '<?php echo esc_js( $mode ) ?>';
	var job_id = '<?php echo esc_js( $id ) ?>';
	var output_format = '<?php echo esc_js( $settings['format'] ) ?>';
	var selected_order_fields = <?php echo json_encode( $settings['order_fields'] ) ?>;
	var selected_order_products_fields = <?php echo json_encode( $settings['order_product_fields'] ) ?>;
	var selected_order_coupons_fields = <?php echo json_encode( $settings['order_coupon_fields'] ) ?>;
	var duplicated_fields_settings = <?php echo json_encode( $settings['duplicated_fields_settings'] ) ?>;
	var all_fields = <?php echo json_encode( WC_Order_Export_Manage::make_all_fields( $settings['format'] ) ); ?>;
	var order_custom_meta_fields = <?php echo json_encode( $order_custom_meta_fields ) ?>;
	var order_products_custom_meta_fields = <?php echo json_encode( WC_Order_Export_Data_Extractor_UI::get_product_custom_fields() ) ?>;
	var order_order_item_custom_meta_fields = <?php echo json_encode( WC_Order_Export_Data_Extractor_UI::get_product_itemmeta() ) ?>;
	var order_coupons_custom_meta_fields = <?php echo json_encode( WC_Order_Export_Data_Extractor_UI::get_all_coupon_custom_meta_fields() ) ?>;
	var order_segments = <?php echo json_encode( WC_Order_Export_Data_Extractor_UI::get_unselected_fields_segments() ) ?>;
	var field_formats = <?php echo json_encode( WC_Order_Export_Data_Extractor_UI::get_format_fields() ) ?>;
	var summary_mode_by_products = <?php echo esc_js( $settings['summary_report_by_products'] ) ?>;
	var summary_mode_by_customers = <?php echo esc_js( $settings['summary_report_by_customers'] ) ?>;

	jQuery( document ).ready( function ( $ ) {
		$( 'input.color_pick' ).wpColorPicker();
	} );
</script>


<form method="post" id="export_job_settings">
	<?php if ( $mode !== WC_Order_Export_Manage::EXPORT_NOW ): ?>
        <div style="width: 100%;">&nbsp;</div>
	<?php endif; ?>

	<input type="hidden" name="settings[post_type]"
               value="<?php echo esc_attr( $woe_order_post_type ) ?>">

	<?php if ($woe_order_post_type && $woe_order_post_type !== 'shop_order'): ?>
	    <div id="my-export-post-type" class="my-block" style="width: 100%; max-width: 993px;">
		<div class="wc-oe-header" style="display: inline-block">
		    <?php esc_html_e( 'Order Type', 'woo-order-export-lite' ) ?>:
		</div>
		<div style="display: inline-block">
		    <?php
			switch($woe_order_post_type) {
			    case 'shop_subscription':
				esc_html_e( 'Order Subscription', 'woo-order-export-lite' );
				break;

			    case 'shop_order_refund':
				esc_html_e( 'Order Refund', 'woo-order-export-lite' );
				break;
			}
		    ?>
		</div>
	    </div>
	    <br>
	<?php endif; ?>

    <div id="export-block-left" class="export-block-left-div">
		<?php do_action( 'woe_settings_form_view_top', $settings ); ?>
        <input type="hidden" name="settings[version]"
               value="<?php echo esc_attr(isset( $settings['version'] ) ? $settings['version'] : '2.0') ?>">


		<?php if ( $show['date_filter'] ) : ?>
            <div id="my-export-date-field" class="my-block">
                <div class="wc-oe-header">
					<?php esc_html_e( 'Filter orders by', 'woo-order-export-lite' ) ?>:
                </div>
                <label>
                    <input type="radio" name="settings[export_rule_field]"
                           class="width-100" <?php echo ( ! isset( $settings['export_rule_field'] ) || ( $settings['export_rule_field'] == 'date' ) ) ? 'checked' : '' ?>
                           value="date">
					<?php esc_html_e( 'Order Date', 'woo-order-export-lite' ) ?>
                </label>
                &#09;&#09;
                <label>
                    <input type="radio" name="settings[export_rule_field]"
                           class="width-100" <?php echo ( isset( $settings['export_rule_field'] ) && ( $settings['export_rule_field'] == 'modified' ) ) ? 'checked' : '' ?>
                           value="modified">
					<?php esc_html_e( 'Modification Date', 'woo-order-export-lite' ) ?>
                </label>
                 <?php if ( $woe_order_post_type && $woe_order_post_type !== 'shop_order_refund' ) { ?>
                &#09;&#09;
                <label title="<?php esc_html_e( 'You will export only paid orders', 'woo-order-export-lite' ) ?>" >
                    <input type="radio" name="settings[export_rule_field]"
                           class="width-100" <?php echo ( isset( $settings['export_rule_field'] ) && ( $settings['export_rule_field'] == 'date_paid' ) ) ? 'checked' : '' ?>
                           value="date_paid">
					<?php esc_html_e( 'Paid Date', 'woo-order-export-lite' ) ?>
                </label>
                &#09;&#09;
                <label title="<?php esc_html_e( 'You will export only completed orders', 'woo-order-export-lite' ) ?>" >
                    <input type="radio" name="settings[export_rule_field]"
                           class="width-100" <?php echo ( isset( $settings['export_rule_field'] ) && ( $settings['export_rule_field'] == 'date_completed' ) ) ? 'checked' : '' ?>
                           value="date_completed">
					<?php esc_html_e( 'Completed Date', 'woo-order-export-lite' ) ?>
                </label>
                 <?php }/*hide Paid/Completed if export Refunds*/ ?>
            </div>
            <br>
            <div id="my-date-filter" class="my-block"
                 title="<?php esc_html_e( 'This date range should not be saved in the scheduled task',
				     'woo-order-export-lite' ) ?>">
                <div style="display: inline;">
                    <span class="wc-oe-header"><?php esc_html_e( 'Date range', 'woo-order-export-lite' ) ?></span>
                    <input type=text class='date' name="settings[from_date]" id="from_date"
                           value='<?php echo esc_attr(! empty($options['show_date_time_picker_for_date_range']) ? $settings['from_date']: remove_time_from_date($settings['from_date'])) ?>'>
					<?php esc_html_e( 'to', 'woo-order-export-lite' ) ?>
                    <input type=text class='date' name="settings[to_date]" id="to_date"
                           value='<?php echo esc_attr(! empty($options['show_date_time_picker_for_date_range']) ? $settings['to_date']: remove_time_from_date($settings['to_date']))?>'>
                </div>
                <br>
						<br>
                <div style="display: inline;">
                    <span class="wc-oe-header"><?php esc_html_e( 'Orders range', 'woo-order-export-lite' ) ?></span>
                    <input class='width-15' type=text name="settings[from_order_id]" id="from_order_id" value='<?php echo esc_attr( $settings['from_order_id'] ) ?>'>
					<?php esc_html_e( 'to', 'woo-order-export-lite' ) ?>
                    <input class='width-15' type=text name="settings[to_order_id]" id="to_order_id" value='<?php echo  esc_attr( $settings['to_order_id'] ) ?>'>
                <button id="my-quick-export-btn" class="button-primary"><?php esc_html_e( 'Express export',
						'woo-order-export-lite' ) ?></button>
						<?php do_action( "woe_settings_below_orders_range", $settings ); ?>
                </div>

						<br>
                <div id="summary_report_by_products" style="display:block"><input type="hidden"
                                                                                         name="settings[summary_report_by_products]"
                                                                                         value="0"/><label><input
                                type="checkbox" id=summary_report_by_products_checkbox
                                name="settings[summary_report_by_products]"
                                value="1" <?php checked( $settings['summary_report_by_products'] ) ?> /> <?php esc_html_e( "Summary Report By Products",
							'woo-order-export-lite' ) ?></label>
                </div>
                <div id="summary_report_by_customers" style="display:block"><input type="hidden"
                                                                                         name="settings[summary_report_by_customers]"
                                                                                         value="0"/><label><input
                                type="checkbox" id=summary_report_by_customers_checkbox
                                name="settings[summary_report_by_customers]"
                                value="1" <?php checked( $settings['summary_report_by_customers'] ) ?> /> <?php esc_html_e( "Summary Report By Customers",
							'woo-order-export-lite' ) ?></label>
                </div>
            </div>
            <br>
		<?php endif; ?>

        <div id="my-export-file" class="my-block">
            <div class="wc-oe-header">
				<?php esc_html_e( 'Export filename', 'woo-order-export-lite' ) ?> :
				<a style="float:right;font-weight:normal" target="_blank" href="https://docs.algolplus.com/algol_order_export/free-version-algol_order_export/export-now/export-filename/">
                <?php esc_html_e('supported tags', 'woo-order-export-lite' ) ?></a>
            </div>
            <label id="export_filename" class="width-100">
                <input type="text" name="settings[export_filename]" class="width-100"
                       value="<?php echo esc_attr( isset( $settings['export_filename'] ) ? $settings['export_filename'] : 'orders-%y-%m-%d-%h-%i-%s.xlsx' ) ?>">
            </label>
        </div>
        <br>


        <div id="my-format" class="my-block">
            <span class="wc-oe-header"><?php esc_html_e( 'Format', 'woo-order-export-lite' ) ?></span><br>
            <p class="line-height__3 mb-0">
				<?php foreach ( WC_Order_Export_Admin::$formats as $format ) { ?>
                    <label class="button-secondary">
                        <input type=radio name="settings[format]" class="output_format" value="<?php echo esc_attr($format) ?>"
							<?php if ( $format == $settings['format'] ) {
								echo 'checked';
							} ?> ><?php echo esc_html( $format ); ?>
                        <span class="ui-icon ui-icon-triangle-1-s my-icon-triangle"></span>
                    </label>
				<?php } ?>
            </p>

            <div id='XLS_options' style='display:none'><strong><?php esc_html_e( 'XLS options',
						'woo-order-export-lite' ) ?></strong><br>
				<?php if ( ! function_exists( "mb_strtolower" ) ): ?>
                    <div style="color:red"><?php esc_html_e( 'Please, install/enable PHP mbstring extension!', 'woo-order-export-lite' ) ?></div>
				<?php endif ?>
                <input type=hidden name="settings[format_xls_use_xls_format]" value=0>
                <input type=hidden name="settings[format_xls_display_column_names]" value=0>
                <input type=hidden name="settings[format_xls_auto_width]" value=0>
                <input type=hidden name="settings[format_xls_auto_height]" value=0>
                <input type=hidden name="settings[format_xls_direction_rtl]" value=0>
                <input type=hidden name="settings[format_xls_force_general_format]" value=0>
                <input type=hidden name="settings[format_xls_remove_emojis]" value=0>
                <input type=checkbox name="settings[format_xls_use_xls_format]"
                       value=1 <?php if ( @$settings['format_xls_use_xls_format'] ) {
					echo 'checked';
				} ?> id="format_xls_use_xls_format"> <?php esc_html_e( 'Export as .xls (Binary File Format)',
					'woo-order-export-lite' ) ?><br>
                <input type=checkbox checked disabled><?php esc_html_e( 'Use sheet name', 'woo-order-export-lite' ) ?></b>
                <input type=text name="settings[format_xls_sheet_name]"
                       value='<?php echo esc_attr($settings['format_xls_sheet_name']) ?>' size=10><br>
                <input type=checkbox name="settings[format_xls_display_column_names]"
                       value=1 <?php if ( @$settings['format_xls_display_column_names'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Output column titles as first line', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_xls_auto_width]"
                       value=1 <?php if ( @$settings['format_xls_auto_width'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Auto column width', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_xls_auto_height]"
                       value=1 <?php if ( @$settings['format_xls_auto_height'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Auto row height', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_xls_direction_rtl]"
                       value=1 <?php if ( @$settings['format_xls_direction_rtl'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Right-to-Left direction', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_xls_force_general_format]"
                       value=1 <?php if ( @$settings['format_xls_force_general_format'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Force general format for all cells', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_xls_remove_emojis]"
                       value=1 <?php if ( @$settings['format_xls_remove_emojis'] ) {
                    echo 'checked';
                } ?> > <?php esc_html_e( 'Remove emojis', 'woo-order-export-lite' ) ?><br>

	            <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Images width', 'woo-order-export-lite' ) ?>
		            <br>
		            <input type="number" name="settings[format_xls_row_images_width]"
		                   value='<?php echo esc_attr($settings['format_xls_row_images_width']) ?>' min="0">
	            </div>

	            <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Images height', 'woo-order-export-lite' ) ?>
		            <br>
		            <input type="number" name="settings[format_xls_row_images_height]"
		                   value='<?php echo esc_attr($settings['format_xls_row_images_height']) ?>' min="0">
	            </div>
            </div>
            <div id='CSV_options' style='display:none'><strong><?php esc_html_e( 'CSV options',
						'woo-order-export-lite' ) ?></strong><br>
                <input type=hidden name="settings[format_csv_add_utf8_bom]" value=0>
                <input type=hidden name="settings[format_csv_display_column_names]" value=0>
                <input type=hidden name="settings[format_csv_force_quotes]" value=0>
                <input type=hidden name="settings[format_csv_delete_linebreaks]" value=0>
                <input type=hidden name="settings[format_csv_remove_linebreaks]" value=0>
                <input type=hidden name="settings[format_csv_item_rows_start_from_new_line]" value=0>
                <input type=checkbox name="settings[format_csv_add_utf8_bom]"
                       value=1 <?php if ( @$settings['format_csv_add_utf8_bom'] ) {
					echo 'checked';
				} ?> id="woe_format_disabler"> <?php esc_html_e( 'Output UTF-8 BOM', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_csv_display_column_names]"
                       value=1 <?php if ( @$settings['format_csv_display_column_names'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Output column titles as first line', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_csv_force_quotes]"
                       value=1 <?php if ( @$settings['format_csv_force_quotes'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Force enclosure for all values', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_csv_delete_linebreaks]"
                       value=1 <?php if ( @$settings['format_csv_delete_linebreaks'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Convert line breaks to literals', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_csv_remove_linebreaks]"
                       value=1 <?php if ( @$settings['format_csv_remove_linebreaks'] ) {
                    echo 'checked';
                } ?> > <?php esc_html_e( 'Remove line breaks', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_csv_item_rows_start_from_new_line]"
                       value=1 <?php if ( @$settings['format_csv_item_rows_start_from_new_line'] ) {
					echo 'checked';
                } ?> > <?php esc_html_e( 'Product rows start with a new line', 'woo-order-export-lite' ) ?><br>
                <div class="line-height__3">
                    <?php esc_html_e( 'Enclosure', 'woo-order-export-lite' ) ?> <input type=text
                                                                                name="settings[format_csv_enclosure]"
                                                                                value='<?php echo esc_attr($settings['format_csv_enclosure']) ?>'
                                                                                size=1>
                    <?php esc_html_e( 'Field Delimiter', 'woo-order-export-lite' ) ?> <input type=text
                                                                                        name="settings[format_csv_delimiter]"
                                                                                        value='<?php echo esc_attr($settings['format_csv_delimiter']) ?>'
                                                                                        size=1>
                    <?php esc_html_e( 'Line Break', 'woo-order-export-lite' ) ?><input type=text
                                                                                name="settings[format_csv_linebreak]"
                                                                                value='<?php echo esc_attr($settings['format_csv_linebreak']) ?>'
                                                                                size=4><br>
                    <?php if ( function_exists( 'iconv' ) ): ?>
                        <?php esc_html_e( 'Character encoding', 'woo-order-export-lite' ) ?><input type=text
                                                                                            name="settings[format_csv_encoding]"
                                                                                            value="<?php echo esc_attr($settings['format_csv_encoding']) ?>"
											    id="woe_format_disabled"
										    >
                        <br>
                    <?php endif ?>
                </div>
            </div>
            <div id='XML_options' style='display:none'><strong><?php esc_html_e( 'XML options',
						'woo-order-export-lite' ) ?></strong><br><br>
				<?php if ( ! class_exists( "XMLWriter" ) ): ?>
                    <div style="color:red"><?php esc_html_e( 'Please, install/enable PHP XML extension!', 'woo-order-export-lite' ) ?></div>
				<?php endif ?>
                <input type=hidden name="settings[format_xml_self_closing_tags]" value=0>
                <input type=hidden name="settings[format_xml_preview_format]" value=0>
                <span class="xml-title"><?php esc_html_e( 'Prepend XML', 'woo-order-export-lite' ) ?></span><input type=text
                                                                                                              name="settings[format_xml_prepend_raw_xml]"
                                                                                                              value='<?php echo esc_attr($settings['format_xml_prepend_raw_xml']) ?>'><br><br>
                <span class="xml-title"><?php esc_html_e( 'Root tag', 'woo-order-export-lite' ) ?></span><input type=text
                                                                                                           name="settings[format_xml_root_tag]"
                                                                                                           value='<?php echo esc_attr($settings['format_xml_root_tag']) ?>'><br><br>
                <span class="xml-title"><?php esc_html_e( 'Order tag', 'woo-order-export-lite' ) ?></span><input type=text
                                                                                                            name="settings[format_xml_order_tag]"
                                                                                                            value='<?php echo esc_attr($settings['format_xml_order_tag']) ?>'><br><br>
                <span class="xml-title"><?php esc_html_e( 'Product tag', 'woo-order-export-lite' ) ?></span><input type=text
                                                                                                              name="settings[format_xml_product_tag]"
                                                                                                              value='<?php echo esc_attr($settings['format_xml_product_tag']) ?>'><br><br>
                <span class="xml-title"><?php esc_html_e( 'Coupon tag', 'woo-order-export-lite' ) ?></span><input type=text
                                                                                                             name="settings[format_xml_coupon_tag]"
                                                                                                             value='<?php echo esc_attr($settings['format_xml_coupon_tag']) ?>'><br><br>
                <span class="xml-title"><?php esc_html_e( 'Append XML', 'woo-order-export-lite' ) ?></span><input type=text
                                                                                                             name="settings[format_xml_append_raw_xml]"
                                                                                                             value='<?php echo esc_attr($settings['format_xml_append_raw_xml']) ?>'><br><br>
                <span class="xml-title"><?php esc_html_e( 'Self closing tags', 'woo-order-export-lite' ) ?></span><input
                        type=checkbox name="settings[format_xml_self_closing_tags]"
                        value=1 <?php if ( @$settings['format_xml_self_closing_tags'] ) {
					echo 'checked';
                } ?> ><br><br>
                <span class="xml-title"><?php esc_html_e( 'Format output', 'woo-order-export-lite' ) ?></span><input
                        type=checkbox name="settings[format_xml_preview_format]"
                        value=1 <?php if ( @$settings['format_xml_preview_format'] ) {
					echo 'checked';
                } ?> ><br><br>
            </div>
            <div id='JSON_options' style='display:none'><strong><?php esc_html_e( 'JSON options',
						'woo-order-export-lite' ) ?></strong><br>
                <span class="xml-title"><?php esc_html_e( 'Start tag', 'woo-order-export-lite' ) ?></span><input type=text
                                                                                                            name="settings[format_json_start_tag]"
                                                                                                            value='<?php echo esc_attr(@$settings['format_json_start_tag']) ?>'><br>
                <span class="xml-title"><?php esc_html_e( 'End tag', 'woo-order-export-lite' ) ?></span><input class="mt-sm" type=text
                                                                                                          name="settings[format_json_end_tag]"
                                                                                                          value='<?php echo esc_attr(@$settings['format_json_end_tag']) ?>'><br>
                <label><input type=checkbox name="settings[format_json_unescaped_slashes]" value=1 <?php if(@$settings['format_json_unescaped_slashes']){
                        echo 'checked';
                    }?>><?php esc_html_e("Don't escape /",'woo-order-export-lite')?></label><br>
                <label><input type=checkbox
                    name="settings[format_json_numeric_check]"
                    value=1 <?php if ( @$settings['format_json_numeric_check'] ) {
                        echo 'checked';
                    }?>><?php esc_html_e("Encode numeric strings as numbers",'woo-order-export-lite')?></label><br>
                <label><input type=checkbox
                              name="settings[format_json_encode_unicode]"
                              value=1 <?php if ( @$settings['format_json_encode_unicode'] ) {
                                  echo 'checked';
                              }?>><?php esc_html_e("Don't encode unicode chars",'woo-order-export-lite')?></label>
            </div>
            <div id='TSV_options' style='display:none'><strong><?php esc_html_e( 'TSV options',
						'woo-order-export-lite' ) ?></strong><br>
                <input type=hidden name="settings[format_tsv_add_utf8_bom]" value=0>
                <input type=hidden name="settings[format_tsv_display_column_names]" value=0>
                <input type=hidden name="settings[format_tsv_item_rows_start_from_new_line]" value=0>
                <input type=checkbox name="settings[format_tsv_add_utf8_bom]"
                       value=1 <?php if ( @$settings['format_tsv_add_utf8_bom'] ) {
					echo 'checked';
				} ?> id="woe_format_tsv_disabler" > <?php esc_html_e( 'Output UTF-8 BOM', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_tsv_display_column_names]"
                       value=1 <?php if ( @$settings['format_tsv_display_column_names'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Output column titles as first line', 'woo-order-export-lite' ) ?><br>
                <input type=checkbox name="settings[format_tsv_item_rows_start_from_new_line]"
                       value=1 <?php if ( @$settings['format_tsv_item_rows_start_from_new_line'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Product rows start with a new line', 'woo-order-export-lite' ) ?><br>
				<?php esc_html_e( 'Line Break', 'woo-order-export-lite' ) ?><input type=text
                                                                              name="settings[format_tsv_linebreak]"
                                                                              value='<?php echo esc_attr($settings['format_tsv_linebreak']) ?>'
                                                                              size=4><br>
				<?php if ( function_exists( 'iconv' ) ): ?>
					<?php esc_html_e( 'Character encoding', 'woo-order-export-lite' ) ?><input type=text
                                                                                          name="settings[format_tsv_encoding]"
                                                                                          value="<?php echo esc_attr($settings['format_tsv_encoding']) ?>"
                                                                                          id="woe_format_tsv_disabled" >
                    <br>
				<?php endif ?>
            </div>

            <div id='PDF_options' style='display:none'><strong><?php esc_html_e( 'PDF options',
						'woo-order-export-lite' ) ?></strong><br>
                <input type=hidden name="settings[format_pdf_display_column_names]" value=0>
                <input type=checkbox name="settings[format_pdf_display_column_names]"
                       value=1 <?php if ( @$settings['format_pdf_display_column_names'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Output column titles as first line', 'woo-order-export-lite' ) ?>

                (
                <input type=hidden name="settings[format_pdf_repeat_header]" value=0>
                <input type=checkbox name="settings[format_pdf_repeat_header]"
                       value=1 <?php if ( @$settings['format_pdf_repeat_header'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'repeat at each page', 'woo-order-export-lite' ) ?>)<br>
<!--
                <input type=hidden name="settings[format_pdf_direction_rtl]" value=0>
                <input type=checkbox name="settings[format_pdf_direction_rtl]"
                       value=1 <?php if ( @$settings['format_pdf_direction_rtl'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Right-to-Left direction', 'woo-order-export-lite' ) ?><br>
-->
                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Orientation', 'woo-order-export-lite' ) ?><br>
                    <select name="settings[format_pdf_orientation]">
						<?php foreach ( $pdf_format_available_options['orientation'] as $orientation => $label ): ?>
                            <option value="<?php echo esc_attr($orientation); ?>" <?php selected( $orientation , $settings['format_pdf_orientation'] ); ?> ><?php echo esc_html($label); ?></option>
						<?php endforeach; ?>
                    </select>
                </div>

                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Page size', 'woo-order-export-lite' ) ?><br>
                    <select name="settings[format_pdf_page_size]">
						<?php foreach ( $pdf_format_available_options['page_size'] as $size => $label ): ?>
                            <option value="<?php echo esc_attr($size); ?>" <?php selected( $size, $settings['format_pdf_page_size'] ); ?> ><?php echo esc_html($label); ?></option>
						<?php endforeach; ?>
                    </select>
                </div>

                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Font size', 'woo-order-export-lite' ) ?><br>
                    <input type=number name="settings[format_pdf_font_size]"
                           value='<?php echo esc_attr($settings['format_pdf_font_size']) ?>' min=1 size=3><br>
                </div>

                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Page numbers', 'woo-order-export-lite' );

					$align_types = array(
						'L' => esc_html__( 'Left align', 'woo-order-export-lite' ),
						'C' => esc_html__( 'Center align', 'woo-order-export-lite' ),
						'R' => esc_html__( 'Right align', 'woo-order-export-lite' ),
					);

					?><br>
                    <select name="settings[format_pdf_pagination]">
						<?php foreach ( array_merge( $align_types, array( 'disable' => esc_html__( 'No page numbers', 'woo-order-export-lite' ) ) ) as $align => $label ): ?>
                            <option value="<?php echo esc_attr($align); ?>" <?php selected( $align , $settings['format_pdf_pagination'] ); ?> ><?php echo esc_html($label); ?></option>
						<?php endforeach; ?>
                    </select>
                </div>


                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Page header text', 'woo-order-export-lite' ) ?><br>
                    <input type=text name="settings[format_pdf_header_text]"
                           value='<?php echo esc_attr($settings['format_pdf_header_text']) ?>'>
                </div>
                <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Columns width', 'woo-order-export-lite' ) ?>
                    <input title="<?php esc_html_e( 'comma separated list', 'woo-order-export-lite' ) ?>" type=text name="settings[format_pdf_cols_width]" value='<?php echo esc_attr($settings['format_pdf_cols_width']) ?>'>
                </div>

                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Page footer text', 'woo-order-export-lite' ) ?><br>
                    <input type=text name="settings[format_pdf_footer_text]"
                           value='<?php echo esc_attr($settings['format_pdf_footer_text']) ?>'>
                </div>
                <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Columns horizontal align', 'woo-order-export-lite' ) ?>
                    <input title="<?php esc_html_e( 'L,C or R. Comma separated list', 'woo-order-export-lite' ) ?>" type=text name="settings[format_pdf_cols_align]" value='<?php echo esc_attr($settings['format_pdf_cols_align']) ?>'>
                </div>

                <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Fit table to page width', 'woo-order-export-lite' ) ?><br>
                    <input type="radio" name="settings[format_pdf_fit_page_width]" value=1 <?php checked( @$settings['format_pdf_fit_page_width'] ); ?> ><?php esc_html_e( 'Yes', 'woo-order-export-lite' ); ?>
                    <input type="radio" name="settings[format_pdf_fit_page_width]" value=0 <?php checked( !@$settings['format_pdf_fit_page_width'] ); ?> ><?php esc_html_e( 'No', 'woo-order-export-lite' ); ?>
                </div>

	            <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Columns vertical align', 'woo-order-export-lite' ) ?>
		            <input title="<?php esc_html_e( 'T,C or B. Comma separated list', 'woo-order-export-lite' ) ?>" type=text name="settings[format_pdf_cols_vertical_align]" value='<?php echo esc_attr($settings['format_pdf_cols_vertical_align']) ?>'>
	            </div>


                <hr>
                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Table header text color', 'woo-order-export-lite' ) ?>
                    <input type=text class="color_pick" name="settings[format_pdf_table_header_text_color]"
                           value='<?php echo esc_attr($settings['format_pdf_table_header_text_color']) ?>'>
                </div>
                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Table header background color', 'woo-order-export-lite' ) ?>
                    <input type=text class="color_pick" name="settings[format_pdf_table_header_background_color]"
                           value='<?php echo esc_attr($settings['format_pdf_table_header_background_color']) ?>'>
                </div>

                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Table row text color', 'woo-order-export-lite' ) ?><br>
                    <input type=text class="color_pick" name="settings[format_pdf_table_row_text_color]"
                           value='<?php echo esc_attr($settings['format_pdf_table_row_text_color']) ?>'>
                </div>
                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Table row background color', 'woo-order-export-lite' ) ?>
                    <input type=text class="color_pick" name="settings[format_pdf_table_row_background_color]"
                           value='<?php echo esc_attr($settings['format_pdf_table_row_background_color']) ?>'>
                </div>

                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Page header text color', 'woo-order-export-lite' ) ?>
                    <input type=text class="color_pick" name="settings[format_pdf_page_header_text_color]"
                           value='<?php echo esc_attr($settings['format_pdf_page_header_text_color']) ?>'>
                </div>
                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Page footer text color', 'woo-order-export-lite' ) ?>
                    <input type=text class="color_pick" name="settings[format_pdf_page_footer_text_color]"
                           value='<?php echo esc_attr($settings['format_pdf_page_footer_text_color']) ?>'>
                </div>

                <hr>

                <div class="pdf_two_col_block">
                    <input type="button" class="button button-primary image-upload-button"
                           value="<?php esc_html_e( 'Select logo', 'woo-order-export-lite' ) ?>">
                    <input type="hidden" class="source_id" name="settings[format_pdf_logo_source_id]"
                           value='<?php echo esc_attr($settings['format_pdf_logo_source_id']) ?>'>
                    <input type="hidden" class="source_url" name="settings[format_pdf_logo_source]"
                           value='<?php echo esc_attr($settings['format_pdf_logo_source']) ?>'>
                    <br>
					<?php
					$source = $settings['format_pdf_logo_source'] ? $settings['format_pdf_logo_source'] : '';
                    if(!$source)
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo wc_placeholder_img('thumbnail', array("class" => 'hidden') );//internal woocommerce function
                    else
                        echo wp_get_attachment_image( $settings['format_pdf_logo_source_id'], 'thumbnail');
					?>
                    <br>
                    <input type="button"
                           class="button button-warning image-clear-button <?php echo ! $source ? 'hidden' : ''; ?>"
                           value="<?php esc_html_e( 'Remove logo', 'woo-order-export-lite' ) ?>">
                </div>
                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Logo align', 'woo-order-export-lite' ) ?>
                    <select name="settings[format_pdf_logo_align]">
						<?php foreach ( $align_types as $align => $label ): ?>
                            <option value="<?php echo esc_attr($align); ?>" <?php  selected( $align, $settings['format_pdf_logo_align'] ); ?> ><?php echo esc_html($label); ?></option>
						<?php endforeach; ?>
                    </select>
                </div>
                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Logo height', 'woo-order-export-lite' ) ?>
                    <br>
                    <input type="number" name="settings[format_pdf_logo_height]"
                           value='<?php echo esc_attr($settings['format_pdf_logo_height']) ?>' min="0">
                </div>
                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Logo width', 'woo-order-export-lite' ) ?>
                    ( <?php esc_html_e( '0 - auto scale', 'woo-order-export-lite' ) ?> )
                    <br>
                    <input type="number" name="settings[format_pdf_logo_width]"
                           value='<?php echo esc_attr($settings['format_pdf_logo_width']) ?>' min="0">
                </div>

	            <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Images width', 'woo-order-export-lite' ) ?>
		            <br>
		            <input type="number" name="settings[format_pdf_row_images_width]"
		                   value='<?php echo esc_attr($settings['format_pdf_row_images_width']) ?>' min="0">
	            </div>

	            <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Images height', 'woo-order-export-lite' ) ?>
		            <br>
		            <input type="number" name="settings[format_pdf_row_images_height]"
		                   value='<?php echo esc_attr($settings['format_pdf_row_images_height']) ?>' min="0">
	            </div>

                <div class="pdf_two_col_block">
                    <input type=hidden name="settings[format_pdf_row_dont_page_break_order_lines]" value="0">
                    <input type=checkbox name="settings[format_pdf_row_dont_page_break_order_lines]"
                           value="1" <?php if ( @$settings['format_pdf_row_dont_page_break_order_lines'] ) {
		                echo 'checked';
	                } ?> > <?php esc_html_e( 'Don\'t put page break between order lines', 'woo-order-export-lite' ) ?>
                </div>

                <div class="pdf_two_col_block" style="margin-top:10px">
                    <input type=hidden name="settings[format_pdf_row_images_add_link]" value="0">
                    <input type=checkbox name="settings[format_pdf_row_images_add_link]"
                           value="1" <?php if ( @$settings['format_pdf_row_images_add_link'] ) {
			            echo 'checked';
		            } ?> > <?php esc_html_e( 'Add links to images', 'woo-order-export-lite' ) ?>
                </div>

                </div>

            <div id='HTML_options' style='display:none'><strong><?php esc_html_e( 'Html options',
						'woo-order-export-lite' ) ?></strong><br>
                <input type=hidden name="settings[format_html_display_column_names]" value=0>
                <input type=checkbox name="settings[format_html_display_column_names]"
                       value=1 <?php if ( @$settings['format_html_display_column_names'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'Output column titles as first line', 'woo-order-export-lite' ) ?>
		(
                <input type=hidden name="settings[format_html_repeat_header_last_line]" value=0>
                <input type=checkbox name="settings[format_html_repeat_header_last_line]"
                       value=1 <?php if ( $settings['format_html_repeat_header_last_line'] ) {
					echo 'checked';
				} ?> > <?php esc_html_e( 'repeat header as last line', 'woo-order-export-lite' ) ?>)
		<br>

                <div class="pdf_two_col_block">
					<?php esc_html_e( 'Font size', 'woo-order-export-lite' ) ?><br>
                    <input type=number name="settings[format_html_font_size]"
                           value='<?php echo esc_attr(@$settings['format_html_font_size']) ?>' min=1 size=3><br>
                </div>

                <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Columns align', 'woo-order-export-lite' ) ?>
                    <input title="<?php esc_html_e( 'comma separated list', 'woo-order-export-lite' ) ?>" type=text name="settings[format_html_cols_align]" value='<?php echo esc_attr($settings['format_html_cols_align']) ?>'>
                </div>

                <div class="pdf_two_col_block">
		    <?php esc_html_e( 'Header text', 'woo-order-export-lite' ) ?><br>
		    <textarea type=text name="settings[format_html_header_text]"><?php echo esc_textarea($settings['format_html_header_text']) ?></textarea>
                </div>

                <div class="pdf_two_col_block">
		    <?php esc_html_e( 'Footer text', 'woo-order-export-lite' ) ?><br>
		    <textarea type=text name="settings[format_html_footer_text]"><?php echo esc_textarea($settings['format_html_footer_text']) ?></textarea>
                </div>

                <div class="pdf_two_col_block">
		    <?php esc_html_e( 'Table header text color', 'woo-order-export-lite' ) ?>
                    <input type=text class="color_pick" name="settings[format_html_table_header_text_color]"
                           value='<?php echo esc_attr($settings['format_html_table_header_text_color']) ?>'>
                </div>
                <div class="pdf_two_col_block">
		    <?php esc_html_e( 'Table header background color', 'woo-order-export-lite' ) ?>
                    <input type=text class="color_pick" name="settings[format_html_table_header_background_color]"
                           value='<?php echo esc_attr($settings['format_html_table_header_background_color']) ?>'>
                </div>

                <div class="pdf_two_col_block">
		    <?php esc_html_e( 'Table row text color', 'woo-order-export-lite' ) ?><br>
                    <input type=text class="color_pick" name="settings[format_html_table_row_text_color]"
                           value='<?php echo esc_attr($settings['format_html_table_row_text_color']) ?>'>
                </div>
                <div class="pdf_two_col_block">
		    <?php esc_html_e( 'Table row background color', 'woo-order-export-lite' ) ?>
                    <input type=text class="color_pick" name="settings[format_html_table_row_background_color]"
                           value='<?php echo esc_attr($settings['format_html_table_row_background_color']) ?>'>
                </div>

                <div class="pdf_two_col_block">
		    <?php esc_html_e( 'Header text color', 'woo-order-export-lite' ) ?><br>
                    <input type=text class="color_pick" name="settings[format_html_header_text_color]"
                           value='<?php echo esc_attr($settings['format_html_header_text_color']) ?>'>
                </div>
                <div class="pdf_two_col_block">
		    <?php esc_html_e( 'Footer text color', 'woo-order-export-lite' ) ?><br>
                    <input type=text class="color_pick" name="settings[format_html_footer_text_color]"
                           value='<?php echo esc_attr($settings['format_html_footer_text_color']) ?>'>
                </div>

	            <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Images width', 'woo-order-export-lite' ) ?>
		            <br>
		            <input type="number" name="settings[format_html_row_images_width]"
		                   value='<?php echo esc_attr($settings['format_html_row_images_width']) ?>' min="0">
	            </div>

	            <div class="pdf_two_col_block">
		            <?php esc_html_e( 'Images height', 'woo-order-export-lite' ) ?>
		            <br>
		            <input type="number" name="settings[format_html_row_images_height]"
		                   value='<?php echo esc_attr($settings['format_html_row_images_height']) ?>' min="0">
	            </div>

                <div class="pdf_two_col_block">
                    <input type=hidden name="settings[format_html_images_add_link]" value="0">
                    <input type=checkbox name="settings[format_html_images_add_link]"
                           value="1" <?php if ( @$settings['format_html_images_add_link'] ) {
		                echo 'checked';
	                } ?> > <?php esc_html_e( 'Add links to images', 'woo-order-export-lite' ) ?>
                </div>

                <div class="pdf_two_col_block">
                </div>

		<br/>
		<div>

		</div>
		    <div>
                <?php esc_html_e( 'Custom css', 'woo-order-export-lite' ) ?><br>
			<textarea style="width: 100%" type=text name="settings[format_html_custom_css]" rows=5><?php echo esc_textarea($settings['format_html_custom_css']) ?></textarea>
			<div><i><?php esc_html_e( "This option cancels UI settings(above) and don't applied to Preview", 'woo-order-export-lite' ) ?></i></div>
		    </div>
                </div>


            <hr>
            <div id="my-date-time-format" class="">
                <div id="date_format_block">
                    <span class="wc-oe-header"><?php esc_html_e( 'Date', 'woo-order-export-lite' ) ?></span>
					<?php
					$date_format = array(
						'',
						'F j, Y',
						'Y-m-d',
						'm/d/Y',
						'd/m/Y',
					);
					$date_format = apply_filters( 'woe_date_format', $date_format );
					?>
                    <select>
						<?php foreach ( $date_format as $format ): ?>
                            <option value="<?php echo esc_attr($format) ?>" <?php selected( @$settings['date_format'],
								$format ) ?> ><?php echo ! empty( $format ) ? esc_html(current_time( $format )) : esc_html__( '-',
									'woo-order-export-lite' ) ?></option>
						<?php endforeach; ?>
                        <option value="custom" <?php selected( in_array( @$settings['date_format'], $date_format ),
							false ) ?> ><?php echo esc_html__( 'custom', 'woo-order-export-lite' ) ?></option>
                    </select>
                    <div id="custom_date_format_block" style="<?php echo in_array( @$settings['date_format'],
						$date_format ) ? 'display: none' : '' ?>">
                        <input type="text" name="settings[date_format]" value="<?php echo esc_attr($settings['date_format']) ?>">
                    </div>
                </div>

                <div id="time_format_block">
                    <span class="wc-oe-header"><?php esc_html_e( 'Time', 'woo-order-export-lite' ) ?></span>
					<?php
					$time_format = array(
						'',
						'g:i a',
						'g:i A',
						'H:i',
					);
					$time_format = apply_filters( 'woe_time_format', $time_format );
					?>
                    <select>
						<?php foreach ( $time_format as $format ): ?>
                            <option value="<?php echo esc_attr($format) ?>" <?php selected( @$settings['time_format'],
								$format ) ?> ><?php echo ! empty( $format ) ? esc_html(current_time( $format )) : esc_html__( '-',
									'woo-order-export-lite' ) ?></option>
						<?php endforeach; ?>
                        <option value="custom" <?php selected( in_array( @$settings['time_format'], $time_format ),
							false ) ?> ><?php echo esc_html__( 'custom', 'woo-order-export-lite' ) ?></option>
                    </select>
                    <div id="custom_time_format_block" style="<?php echo in_array( @$settings['time_format'],
						$time_format ) ? 'display: none' : '' ?>">
                        <input type="text" name="settings[time_format]" value="<?php echo esc_attr($settings['time_format']) ?>">
                    </div>
                </div>
            </div>

            </div>
        <br/>
        <div id="my-sort" class="my-block line-height__3">
			<?php
			$sort = array(
				'order_id'      => esc_html__( 'Order ID', 'woo-order-export-lite' ),
				'post_date'     => esc_html__( 'Order Date', 'woo-order-export-lite' ),
				'post_modified' => esc_html__( 'Modification Date', 'woo-order-export-lite' ),
				'post_status'   => esc_html__( 'Order status', 'woo-order-export-lite' ),
			);
                        foreach ( $settings['order_fields'] as $field ) {
                                if ($field['key'] !== 'products' && $field['key'] !== 'coupons' && !in_array(strtolower($field['label']), array_map('strtolower', $sort))) {
                                    $sort['setup_field_'. (isset($field['format']) ? $field['format'] : '') . '_' . $field['key']] = $field['label'];
                                }
			}
			foreach ( WC_Order_Export_Data_Extractor_UI::get_order_custom_fields() as $field ) {
				$sort[$field] = $field;
			}

            esc_html_e( 'Sort orders by ', 'woo-order-export-lite' );
            ?>
            <select name="settings[sort]">
				<?php foreach ( $sort as $value => $text ): ?>
                    <option value='<?php echo esc_attr($value) ?>' <?php  selected( @$settings['sort'],
						$value ) ?> ><?php echo esc_attr($text); ?></option>
				<?php endforeach; ?>
            </select>
			<?php
            esc_html_e( ' in ', 'woo-order-export-lite' );
            ?>
            <select name="settings[sort_direction]">
                <option value='DESC' <?php selected( @$settings['sort_direction'],
					'DESC' ) ?> ><?php esc_html_e( 'Descending', 'woo-order-export-lite' ) ?></option>
                <option value='ASC' <?php selected( @$settings['sort_direction'],
					'ASC' ) ?> ><?php esc_html_e( 'Ascending', 'woo-order-export-lite' ) ?></option>
            </select>
			<?php
            esc_html_e( ' order', 'woo-order-export-lite' );
			?>
        </div>
        <br>

        <?php if ( ! isset( $woe_order_post_type ) || $woe_order_post_type != 'shop_subscription' ) { ?>
		    <?php if ( $mode === WC_Order_Export_Manage::EXPORT_SCHEDULE || $mode === WC_Order_Export_Manage::EXPORT_PROFILE ) { ?>
        <div id="my-change-status" class="my-block">
                    <label for="change_order_status_to"><?php esc_html_e( 'Change order status to',
							'woo-order-export-lite' ) ?></label>
                    <select id="change_order_status_to" name="settings[change_order_status_to]">
                        <option value="" <?php if ( empty( $settings['change_order_status_to'] ) ) {
							echo 'selected';
						} ?>><?php esc_html_e( "- don't modify -", 'woo-order-export-lite' ) ?></option>
						<?php foreach ( apply_filters( 'woe_settings_order_statuses', wc_get_order_statuses() ) as $i => $status ) { ?>
                            <option value="<?php echo esc_attr($i) ?>" <?php if ( $i === $settings['change_order_status_to'] ) {
								echo 'selected';
							} ?>><?php echo esc_html($status) ?></option>
						<?php } ?>
                    </select>
        </div>
        <br>
            <?php } ?>
        <?php } ?>

        <div class="my-block">
			<span class="my-hide-next "><?php esc_html_e( 'Misc settings', 'woo-order-export-lite' ) ?>
                <span class="ui-icon ui-icon-triangle-1-s my-icon-triangle"></span></span>
            <div id="my-misc" class="hide">
                <div>
                    <input type="hidden" name="settings[format_number_fields]" value="0"/>
                    <label><input type="checkbox" name="settings[format_number_fields]"
                                  value="1" <?php checked( $settings['format_number_fields'] ) ?>/><?php esc_html_e( 'Format numbers (use WC decimal separator)',
							'woo-order-export-lite' ) ?></label>
                </div>
                <div>
                    <input type="hidden" name="settings[export_all_comments]" value="0"/>
                    <label><input type="checkbox" name="settings[export_all_comments]"
                                  value="1" <?php checked( $settings['export_all_comments'] ) ?>/><?php esc_html_e( 'Export all order notes',
							'woo-order-export-lite' ) ?></label>
                </div>
                <div>
                    <input type="hidden" name="settings[export_refund_notes]" value="0"/>
                    <label><input type="checkbox" name="settings[export_refund_notes]"
                                  value="1" <?php checked( $settings['export_refund_notes'] ) ?>/><?php esc_html_e( 'Export refund notes as Customer Note',
							'woo-order-export-lite' ) ?></label>
                </div>
                <div>
                    <input type="hidden" name="settings[strip_tags_product_fields]" value="0"/>
                    <label><input type="checkbox" name="settings[strip_tags_product_fields]"
                                  value="1" <?php checked( $settings['strip_tags_product_fields'] ) ?>/><?php esc_html_e( 'Strip tags from Product Description/Variation',
							'woo-order-export-lite' ) ?></label>
                </div>
                <div>
                    <input type="hidden" name="settings[strip_html_tags]" value="0"/>
                    <label><input type="checkbox" name="settings[strip_html_tags]"
                                  value="1" <?php checked( $settings['strip_html_tags'] ) ?>/><?php esc_html_e( 'Strip tags from all fields',
                            'woo-order-export-lite' ) ?></label>
                </div>
                <div>
                    <input type="hidden" name="settings[round_item_tax_rate]" value="0"/>
                    <label><input type="checkbox" name="settings[round_item_tax_rate]"
                                  value="1" <?php checked( $settings['round_item_tax_rate'] ) ?>/><?php esc_html_e( 'Item Tax Rate as an integer',
							'woo-order-export-lite' ) ?></label>
                </div>
                <div>
                    <input type="hidden" name="settings[cleanup_phone]" value="0"/>
                    <label><input type="checkbox" name="settings[cleanup_phone]"
                                  value="1" <?php checked( $settings['cleanup_phone'] ) ?>/><?php esc_html_e( 'Cleanup phone (export only digits)',
							'woo-order-export-lite' ) ?></label>
                </div>
                <div>
                    <input type="hidden" name="settings[billing_details_for_shipping]" value="0"/>
                    <label><input type="checkbox" name="settings[billing_details_for_shipping]"
                                  value="1" <?php checked( $settings['billing_details_for_shipping'] ) ?>/><?php esc_html_e( 'Shipping fields use billing details (if shipping address is empty)',
							'woo-order-export-lite' ) ?></label>
                </div>
                <div>
                    <input type="hidden" name="settings[convert_serialized_values]" value="0"/>
                    <label><input type="checkbox" name="settings[convert_serialized_values]"
                                  value="1" <?php checked( $settings['convert_serialized_values'] ) ?>/><?php esc_html_e( 'Try to convert serialized values',
							'woo-order-export-lite' ) ?></label>
                </div>
                <div>
                    <input type="hidden" name="settings[enable_debug]" value="0"/>
                    <label><input type="checkbox" name="settings[enable_debug]"
                                  value="1" <?php checked( $settings['enable_debug'] ) ?>/><?php esc_html_e( 'Enable debug output',
							'woo-order-export-lite' ) ?></label>
                </div>
                <div>
                    <input type="hidden" name="settings[custom_php]" value="0"/>
                    <label><input type="checkbox" name="settings[custom_php]"
                                  value="1" <?php checked( $settings['custom_php'] ) ?>/><?php esc_html_e( 'Custom PHP code to modify output',
							'woo-order-export-lite' ) ?></label>
                    <div id="custom_php_code_textarea" <?php echo $settings['custom_php'] ? '' : 'style="display: none"' ?>>
						<?php if ( $readonly_php == 'readonly' ): ?>
                            <strong>
								<?php esc_html_e( 'Please check permissions for your role. You must have capability “edit_themes” to use this box.',
									'woo-order-export-lite' ); ?>
                            </strong>
							<?php echo sprintf( '<a href="%s" target=_blank>%s</a>',
							               "https://algolplus.freshdesk.com/support/solutions/articles/25000018208-grey-textarea-for-custom-code-in-section-misc-settings-",
							               esc_html__( 'Read how to fix it','woo-order-export-lite' )
							               ); ?>

						<?php endif; ?>
                        <textarea placeholder="<?php esc_html_e( 'Use only unnamed functions!', 'woo-order-export-lite' ) ?>"
                                  name="settings[custom_php_code]" <?php echo esc_attr($readonly_php) ?> class="width-100"
                                  rows="10"><?php echo esc_textarea($settings['custom_php_code']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="export-block-right" class="export-block-right-div">
		<?php do_action( 'woe_settings_form_view_destinations', $settings ); ?>
        <div class="my-block">
            <?php if ( $woe_order_post_type && $woe_order_post_type === 'shop_subscription' ) {
                include_once WOE_PRO_PLUGIN_BASEPATH . '/view/filter-by-subscription.php';
            } else {
                ?>
                <span class="my-hide-next "><?php esc_html_e( 'Filter by order', 'woo-order-export-lite' ); ?>
                <span class="ui-icon ui-icon-triangle-1-s my-icon-triangle"></span></span>
                <div id="my-order" class="hide">
                    <div><input type="hidden" name="settings[skip_suborders]" value="0"/><label><input type="checkbox"
                                                                                                        name="settings[skip_suborders]"
                                                                                                        value="1" <?php checked( $settings['skip_suborders'] ) ?> /> <?php esc_html_e( "Don't export child orders",
                                'woo-order-export-lite' ) ?></label></div>
                    <div>
                        <input type="hidden" name="settings[export_refunds]" value="0"/>
	                    <?php if ( $woe_order_post_type ) {
		                    if ( $woe_order_post_type !== 'shop_order_refund'
                                 || ( $mode !== WC_Order_Export_Manage::EXPORT_SCHEDULE && $mode !== WC_Order_Export_Manage::EXPORT_PROFILE)
                            ) {
			                    ?>
                                <label>
                                    <input type="checkbox" name="settings[export_refunds]"
                                           value="1" <?php checked( $settings['export_refunds'] ) ?> />
				                    <?php esc_html_e( "Export refunds", 'woo-order-export-lite' ) ?>
				                    <i><?php esc_html_e( "(only date filters applied)", 'woo-order-export-lite' ) ?></i>
                                </label>
		                    <?php }
	                    } ?>
                    </div>
                    <?php do_action("woe_ui_form_filter_by_order", $settings);?>
                    <div><input type="hidden" name="settings[mark_exported_orders]" value="0"/><label><input type="checkbox"
                                                                                                                name="settings[mark_exported_orders]"
                                                                                                                value="1" <?php checked( $settings['mark_exported_orders'] ) ?> /> <?php esc_html_e( "Mark exported orders",
                                'woo-order-export-lite' ) ?></label></div>
                    <div><input type="hidden" name="settings[export_unmarked_orders]" value="0"/><label><input
                                    type="checkbox" name="settings[export_unmarked_orders]"
                                    value="1" <?php checked( $settings['export_unmarked_orders'] ) ?> /> <?php esc_html_e( "Export unmarked orders only",
                                'woo-order-export-lite' ) ?></label></div>
                    <span class="wc-oe-header"><?php esc_html_e( 'Order statuses', 'woo-order-export-lite' ); ?></span>
                    <select id="statuses" class="select2-i18n" name="settings[statuses][]" multiple="multiple"
                            style="width: 100%; max-width: 25%;">
                        <?php foreach (
                            apply_filters( 'woe_settings_order_statuses', wc_get_order_statuses() ) as $i => $status
                        ) { ?>
                            <option value="<?php echo esc_attr($i) ?>" <?php if ( in_array( $i, $settings['statuses'] ) ) {
                                echo 'selected';
                            } ?>><?php echo esc_html($status) ?></option>
                        <?php } ?>
                    </select>
                    <div>
                        <div class="custom-fields__wrapper">
                            <div>
                                <span class="wc-oe-header"><?php esc_html_e( 'Custom fields', 'woo-order-export-lite' ) ?></span>
                            </div>
                            <div class="custom-fields__condotion-wrapper custom-fields__condotion-wrapper_position">
                                <select id="custom_fields" class="select2-i18n" data-select2-i18n-width="150" style="width: auto;">
                                    <?php foreach ( WC_Order_Export_Data_Extractor_UI::get_order_custom_fields() as $cf_name ) { ?>
                                        <option><?php echo esc_attr( $cf_name); ?></option>
                                    <?php } ?>
                                </select>

                                <select id="custom_fields_compare" class="select_compare">
                                    <option>=</option>
                                    <option>&lt;&gt;</option>
                                    <option>LIKE</option>
                                    <option>NOT LIKE</option>
                                    <option>&gt;</option>
                                    <option>&gt;=</option>
                                    <option>&lt;</option>
                                    <option>&lt;=</option>
                                    <option>NOT SET</option>
                                    <option>IS SET</option>
                                </select>

                                <input type="text" id="text_custom_fields" disabled class="like-input" style="display: none;">
                                <button id="add_custom_fields" class="button-secondary"><span
                                            class="dashicons dashicons-plus-alt"></span></button>
                            </div>
                        </div>
                        <select id="custom_fields_check" class="select2-i18n" multiple name="settings[order_custom_fields][]"
                                style="width: 100%; max-width: 25%;">
                            <?php
                            if ( $settings['order_custom_fields'] ) {
                                foreach ( $settings['order_custom_fields'] as $prod ) {
                                    ?>
                                    <option selected value="<?php echo esc_attr($prod); ?>"> <?php echo esc_html($prod); ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>
        </div>

        <br>

        <div class="my-block">
            <div id=select2_warning
                 style='display:none;color:red;font-size: 120%;'><?php esc_html_e( "The filters won't work correctly.<br>Another plugin(or theme) has loaded outdated Select2.js",
					'woo-order-export-lite' ) ?></div>
            <span class="my-hide-next "><?php esc_html_e( 'Filter by product', 'woo-order-export-lite' ) ?>
                <span class="ui-icon ui-icon-triangle-1-s my-icon-triangle"></span></span>
	    <div id="my-products" class="hide">
                <div><input type="hidden" name="settings[all_products_from_order]" value="0"/><label><input
                                type="checkbox" name="settings[all_products_from_order]"
                                value="1" <?php checked( $settings['all_products_from_order'] ) ?> /> <?php esc_html_e( 'Export all products from the order',
							'woo-order-export-lite' ) ?></label></div>
                <div><input type="hidden" name="settings[skip_refunded_items]" value="0"/><label><input type="checkbox"
                                                                                                        name="settings[skip_refunded_items]"
                                                                                                        value="1" <?php checked( $settings['skip_refunded_items'] ) ?> /> <?php esc_html_e( 'Skip fully refunded items',
							'woo-order-export-lite' ) ?></label></div>
                <span class="wc-oe-header"><?php esc_html_e( 'Product categories', 'woo-order-export-lite' ) ?></span>
                <select id="product_categories" class="select2-i18n" data-select2-i18n-ajax-method="get_categories"
                        name="settings[product_categories][]" multiple="multiple"
                        style="width: 100%; max-width: 25%;">
					<?php
					if ( $settings['product_categories'] ) {
						foreach ( $settings['product_categories'] as $cat ) {
							$cat_term = get_term( $cat, 'product_cat' );
							if ( $cat_term ) {
								?>
                                <option selected
                                        value="<?php echo esc_attr($cat_term->term_id) ?>"> <?php echo esc_html($cat_term->name); ?></option>
								<?php
							}
							?>
						<?php }
					} ?>
                </select>
                <span class="wc-oe-header"><?php esc_html_e( 'Vendors/creators', 'woo-order-export-lite' ) ?></span>
                <select id="product_vendors" class="select2-i18n" data-select2-i18n-ajax-method="get_vendors"
                        name="settings[product_vendors][]" multiple="multiple"
                        style="width: 100%; max-width: 25%;">
					<?php
					if ( $settings['product_vendors'] ) {
						foreach ( $settings['product_vendors'] as $user_id ) {
							$user = get_user_by( 'id', $user_id );
							?>
                            <option selected value="<?php echo esc_attr($user_id) ?>"> <?php echo esc_html($user->display_name); ?></option>
						<?php }
					} ?>
                </select>

				<?php do_action( "woe_settings_filter_by_product_after_vendors", $settings ); ?>

                <span class="wc-oe-header"><?php esc_html_e( 'Products', 'woo-order-export-lite' ) ?></span>

                <select id="products" class="select2-i18n" data-select2-i18n-ajax-method="get_products"
                        name="settings[products][]" multiple="multiple"
                        style="width: 100%; max-width: 25%;">
					<?php
					if ( $settings['products'] ) {
						foreach ( $settings['products'] as $prod ) {
							$p = get_the_title( $prod );
							?>
                            <option selected value="<?php echo esc_attr($prod) ?>"> <?php echo esc_html($p); ?></option>
						<?php }
					} ?>
                </select>

                <span class="wc-oe-header"><?php esc_html_e( 'Product SKU', 'woo-order-export-lite' ) ?></span>
                <br>
                <textarea id="product_sku" name="settings[product_sku]" rows="4" class="width-100" style="resize: none;"><?php echo esc_textarea($settings['product_sku']) ?></textarea>
                <br>
                <div class="custom-fields__wrapper">
                    <div>
                        <span class="wc-oe-header"><?php esc_html_e( 'Product taxonomies', 'woo-order-export-lite' ) ?></span>
                    </div>
                    <div class="custom-fields__condotion-wrapper custom-fields__condotion-wrapper_position">
                        <select id="taxonomies" class="select2-i18n" data-select2-i18n-width="150" style="width: auto;">
                            <?php foreach ( WC_Order_Export_Data_Extractor_UI::get_product_taxonomies() as $attr_id => $attr_name ) { ?>
                                <option><?php echo esc_html($attr_name); ?></option>
                            <?php } ?>
                        </select>

                        <select id="taxonomies_compare" class="select_compare">
                            <option>=</option>
                            <option>&lt;&gt;</option>
                            <option>NOT SET</option>
                            <option>IS SET</option>
                        </select>

                        <input type="text" id="text_taxonomies" disabled style="display: none;">

                        <button id="add_taxonomies" class="button-secondary"><span class="dashicons dashicons-plus-alt"></span>
                        </button>
                    </div>
                    <select id="taxonomies_check" class="select2-i18n" data-select2-i18n-default="1" multiple
                            name="settings[product_taxonomies][]"
                            style="width: 100%; max-width: 25%;">
                        <?php
                        if ( $settings['product_taxonomies'] ) {
                            foreach ( $settings['product_taxonomies'] as $prod ) {
                                ?>
                                <option selected value="<?php echo esc_attr($prod); ?>"> <?php echo esc_html($prod); ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                <div class="custom-fields__wrapper">
                    <div>
                        <span class="wc-oe-header"><?php esc_html_e( 'Product custom fields', 'woo-order-export-lite' ) ?></span>
                    </div>
                    <div class="custom-fields__condotion-wrapper custom-fields__condotion-wrapper_position">
                        <select id="product_custom_fields" class="select2-i18n" data-select2-i18n-width="150"
                                style="width: auto;">
                            <?php foreach ( WC_Order_Export_Data_Extractor_UI::get_product_custom_fields() as $cf_name ) { ?>
                                <option><?php echo esc_attr($cf_name); ?></option>
                            <?php } ?>
                        </select>

                        <select id="product_custom_fields_compare" class="select_compare">
                            <option>=</option>
                            <option>&lt;&gt;</option>
                            <option>LIKE</option>
                            <option>NOT LIKE</option>
                            <option>&gt;</option>
                            <option>&gt;=</option>
                            <option>&lt;</option>
                            <option>&lt;=</option>
                            <option>NOT SET</option>
                            <option>IS SET</option>
                        </select>

                        <input type="text" id="text_product_custom_fields" disabled class="like-input" style="display: none;">

                        <button id="add_product_custom_fields" class="button-secondary"><span
                                    class="dashicons dashicons-plus-alt"></span></button>
                    </div>
                    <select id="product_custom_fields_check" class="select2-i18n" data-select2-i18n-default="1" multiple
                            name="settings[product_custom_fields][]"
                            style="width: 100%; max-width: 25%;">
                        <?php
                        if ( $settings['product_custom_fields'] ) {
                            foreach ( $settings['product_custom_fields'] as $prod ) {
                                ?>
                                <option selected value="<?php echo esc_attr($prod); ?>"> <?php echo esc_html($prod); ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                <div class="custom-fields__wrapper">
                    <div>
                        <span class="wc-oe-header"><?php esc_html_e( 'Variable product attributes',
                            'woo-order-export-lite' ) ?></span>
                    </div>
                    <div class="custom-fields__condotion-wrapper custom-fields__condotion-wrapper_position">
                        <select id="attributes" class="select2-i18n" data-select2-i18n-width="150" style="width: auto;">
                            <?php foreach ( WC_Order_Export_Data_Extractor_UI::get_product_attributes() as $attr_id => $attr_name ) { ?>
                                <option><?php echo esc_html($attr_name); ?></option>
                            <?php } ?>
                        </select>

                        <select id="attributes_compare" class="select_compare">
                            <option>=</option>
                            <option>&lt;&gt;</option>
                            <option>LIKE</option>
                        </select>

                        <input type="text" id="text_attributes" disabled class="like-input" style="display: none;">

                        <button id="add_attributes" class="button-secondary"><span class="dashicons dashicons-plus-alt"></span>
                        </button>
                    </div>
                    <select id="attributes_check" class="select2-i18n" data-select2-i18n-default="1" multiple
                            name="settings[product_attributes][]"
                            style="width: 100%; max-width: 25%;">
                        <?php
                        if ( $settings['product_attributes'] ) {
                            foreach ( $settings['product_attributes'] as $prod ) {
                                ?>
                                <option selected value="<?php echo esc_attr($prod); ?>"> <?php echo esc_html($prod); ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                <div class="custom-fields__wrapper">
                    <div>
                        <span class="wc-oe-header"><?php esc_html_e( 'Item meta data', 'woo-order-export-lite' ) ?></span>
                    </div>
                    <div class="custom-fields__condotion-wrapper custom-fields__condotion-wrapper_position">
                        <select id="itemmeta" class="select2-i18n" data-select2-i18n-width="220" style="width: auto;">
                            <?php foreach ( WC_Order_Export_Data_Extractor_UI::get_product_itemmeta() as $attr_name ) { ?>
                                <option data-base64="<?php echo esc_attr(base64_encode( $attr_name )); ?>"><?php echo esc_html($attr_name); ?></option>
                            <?php } ?>
                        </select>

                        <select id="itemmeta_compare" class="select_compare">
                            <option>=</option>
                            <option>&lt;&gt;</option>
                            <option>LIKE</option>
                            <option>&gt;</option>
                            <option>&gt;=</option>
                            <option>&lt;</option>
                            <option>&lt;=</option>
                        </select>

                        <input type="text" id="text_itemmeta" disabled class="like-input" style="display: none;">

                        <button id="add_itemmeta" class="button-secondary"><span class="dashicons dashicons-plus-alt"></span>
                        </button>
                    </div>
                    <select id="itemmeta_check" class="select2-i18n" data-select2-i18n-default="1" multiple
                            name="settings[product_itemmeta][]"
                            style="width: 100%; max-width: 25%;">
                        <?php
                        if ( $settings['product_itemmeta'] ) {
                            foreach ( $settings['product_itemmeta'] as $prod ) {
                                ?>
                                <option selected value="<?php echo esc_attr($prod); ?>"> <?php echo esc_html($prod); ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
		<span class="wc-oe-header"><?php esc_html_e( 'Exclude products', 'woo-order-export-lite' ) ?></span>

                <select id="exclude_products" class="select2-i18n" data-select2-i18n-ajax-method="get_products"
                        name="settings[exclude_products][]" multiple="multiple"
                        style="width: 100%; max-width: 25%;">
					<?php
					if ( $settings['exclude_products'] ) {
						foreach ( $settings['exclude_products'] as $prod ) {
							$p = get_the_title( $prod );
							?>
                            <option selected value="<?php echo esc_attr($prod) ?>"> <?php echo esc_html($p); ?></option>
						<?php }
					} ?>
                </select>

            </div>
        </div>

        <br>

        <div class="my-block">
			<span class="my-hide-next "><?php esc_html_e( 'Filter by customer', 'woo-order-export-lite' ) ?>
                <span class="ui-icon ui-icon-triangle-1-s my-icon-triangle"></span></span>
	    <div id="my-users" class="hide">

                <span class="wc-oe-header"><?php esc_html_e( 'Usernames', 'woo-order-export-lite' ) ?></span>
                <select id="user_names" class="select2-i18n" data-select2-i18n-ajax-method="get_users"
                        name="settings[user_names][]" multiple="multiple"
                        style="width: 100%; max-width: 25%;">
					<?php
					if ( $settings['user_names'] ) {
						foreach ( $settings['user_names'] as $user_id ) {
							$user = get_user_by( 'id', $user_id );
							?>
                            <option selected value="<?php echo esc_attr($user_id) ?>"> <?php echo esc_html($user->display_name); ?></option>
						<?php }
					} ?>
                </select>

                <span class="wc-oe-header"><?php esc_html_e( 'User roles', 'woo-order-export-lite' ) ?></span>
                <select id="user_roles" class="select2-i18n" name="settings[user_roles][]" multiple="multiple"
                        style="width: 100%; max-width: 25%;">
					<?php
					global $wp_roles;
					foreach ( $wp_roles->role_names as $k => $v ) { ?>
                        <option value="<?php echo esc_attr($k) ?>" <?php selected( in_array( $k, $settings['user_roles'] )) ?>> <?php echo esc_html($v) ?></option>
					<?php } ?>
                </select>
                <div class="custom-fields__wrapper">
                    <div>
                        <span class="wc-oe-header"><?php esc_html_e( 'Custom fields', 'woo-order-export-lite' ) ?></span>
                    </div>
                    <div class="custom-fields__condotion-wrapper custom-fields__condotion-wrapper_position">
                        <select id="user_custom_fields" class="select2-i18n" data-select2-i18n-width="150" style="width: auto;">
                            <?php foreach ( WC_Order_Export_Data_Extractor_UI::get_user_custom_fields() as $cf_name ) { ?>
                                <option><?php echo esc_html($cf_name); ?></option>
                            <?php } ?>
                        </select>
                        <select id="user_custom_fields_compare" class="select_compare">
                            <option>=</option>
                            <option>&lt;&gt;</option>
                            <option>LIKE</option>
                            <option>NOT LIKE</option>
                            <option>&gt;</option>
                            <option>&gt;=</option>
                            <option>&lt;</option>
                            <option>&lt;=</option>
                            <option>NOT SET</option>
                            <option>IS SET</option>
                        </select>

                        <input type="text" id="text_user_custom_fields" disabled class="like-input" style="display: none;">

                        <button id="add_user_custom_fields" class="button-secondary"><span
                                    class="dashicons dashicons-plus-alt"></span></button>
                    </div>
                    <select id="user_custom_fields_check" class="select2-i18n" multiple
                            name="settings[user_custom_fields][]"
                            style="width: 100%; max-width: 25%;">
                        <?php
                        if ( ! empty( $settings['user_custom_fields'] ) ) {
                            foreach ( $settings['user_custom_fields'] as $value ) {
                                ?>
                                <option selected value="<?php echo esc_attr($value); ?>"> <?php echo esc_html($value); ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>
        </div>

        <br>

        <div class="my-block">
			<span class="my-hide-next "><?php esc_html_e( 'Filter by coupon', 'woo-order-export-lite' ) ?>
                <span class="ui-icon ui-icon-triangle-1-s my-icon-triangle"></span></span>
            <div id="my-coupons" class="hide">
                <div>
                    <input type="hidden" name="settings[any_coupon_used]" value="0"/>
                    <label><input type="checkbox" name="settings[any_coupon_used]"
                                  value="1" <?php checked( $settings['any_coupon_used'] ) ?>/><?php esc_html_e( 'Any coupon used',
							'woo-order-export-lite' ) ?></label>
                </div>
                <span class="wc-oe-header"><?php esc_html_e( 'Coupons', 'woo-order-export-lite' ) ?></span>
                <select id="coupons" class="select2-i18n" data-select2-i18n-ajax-method="get_coupons"
                        name="settings[coupons][]" multiple="multiple"
                        style="width: 100%; max-width: 25%;">
					<?php
					if ( $settings['coupons'] ) {
						foreach ( $settings['coupons'] as $coupon ) {
							?>
                            <option selected value="<?php echo esc_attr($coupon); ?>"> <?php echo esc_html($coupon); ?></option>
						<?php }
					} ?>
                </select>
            </div>
        </div>

        <br>

        <div class="my-block">
			<span class="my-hide-next "><?php esc_html_e( 'Filter by billing', 'woo-order-export-lite' ) ?>
                <span class="ui-icon ui-icon-triangle-1-s my-icon-triangle"></span></span>
	    <div id="my-billing" class="hide">
                <div class="custom-fields__wrapper">
                    <div>
                        <span class="wc-oe-header"><?php esc_html_e( 'Billing locations', 'woo-order-export-lite' ) ?></span>
                    </div>
                    <div class="custom-fields__condotion-wrapper custom-fields__condotion-wrapper_position">
                        <select id="billing_locations" class="select2-i18n" data-select2-i18n-width="150">
                            <option value="City"><?php esc_html_e( 'City', 'woo-order-export-lite' );?></option>
                            <option value="State"><?php esc_html_e( 'State', 'woo-order-export-lite' );?></option>
                            <option value="Postcode"><?php esc_html_e( 'Postcode', 'woo-order-export-lite' );?></option>
                            <option value="Country"><?php esc_html_e( 'Country', 'woo-order-export-lite' );?></option>
                        </select>
                        <select id="billing_compare" class="select_compare">
                            <option>=</option>
                            <option>&lt;&gt;</option>
                        </select>
                        <button id="add_billing_locations" class="button-secondary"><span
                                    class="dashicons dashicons-plus-alt"></span></button>
                    </div>
                </div>
                <select id="billing_locations_check" class="select2-i18n" data-select2-i18n-default="1" multiple
                        name="settings[billing_locations][]"
                        style="width: 100%; max-width: 25%;">
					<?php
					if ( $settings['billing_locations'] ) {
						foreach ( $settings['billing_locations'] as $location ) {
							?>
                            <option selected value="<?php echo esc_attr($location); ?>"> <?php echo esc_html($location); ?></option>
						<?php }
					} ?>
                </select>

                <span class="wc-oe-header"><?php esc_html_e( 'Payment methods', 'woo-order-export-lite' ) ?></span>
                <select id="payment_methods" class="select2-i18n" name="settings[payment_methods][]" multiple="multiple"
                        style="width: 100%; max-width: 25%;">
					<?php foreach ( WC()->payment_gateways->payment_gateways() as $gateway ) { ?>
                        <option value="<?php echo esc_attr($gateway->id) ?>" <?php if ( in_array( $gateway->id,
							$settings['payment_methods'] ) ) {
							echo 'selected';
						} ?>><?php echo esc_html($gateway->get_title()) ?></option>
					<?php } ?>
                </select>
            </div>
        </div>

        <br>

        <div class="my-block">
			<span class="my-hide-next "><?php esc_html_e( 'Filter by shipping', 'woo-order-export-lite' ) ?>
                <span class="ui-icon ui-icon-triangle-1-s my-icon-triangle"></span></span>
            <div id="my-shipping" class="hide">
                <div class="custom-fields__wrapper">
                    <div>
                        <span class="wc-oe-header"><?php esc_html_e( 'Shipping locations', 'woo-order-export-lite' ) ?></span>
                    </div>
                    <div class="custom-fields__condotion-wrapper custom-fields__condotion-wrapper_position">
                        <select id="shipping_locations" class="select2-i18n" data-select2-i18n-width="150">
                            <option value="City"><?php esc_html_e( 'City', 'woo-order-export-lite' );?></option>
                            <option value="State"><?php esc_html_e( 'State', 'woo-order-export-lite' );?></option>
                            <option value="Postcode"><?php esc_html_e( 'Postcode', 'woo-order-export-lite' );?></option>
                            <option value="Country"><?php esc_html_e( 'Country', 'woo-order-export-lite' );?></option>
                        </select>
                        <select id="shipping_compare" class="select_compare">
                            <option>=</option>
                            <option>&lt;&gt;</option>
                        </select>

                        <button id="add_shipping_locations" class="button-secondary"><span
                                    class="dashicons dashicons-plus-alt"></span></button>
                    </div>
                    <select id="shipping_locations_check" class="select2-i18n" data-select2-i18n-default="1" multiple
                            name="settings[shipping_locations][]"
                            style="width: 100%; max-width: 25%;">
                        <?php
                        if ( $settings['shipping_locations'] ) {
                            foreach ( $settings['shipping_locations'] as $location ) {
                                ?>
                                <option selected value="<?php echo esc_attr($location); ?>"> <?php echo esc_html($location); ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                <span class="wc-oe-header"><?php esc_html_e( 'Shipping methods', 'woo-order-export-lite' ) ?></span>
                <select id="shipping_methods" class="select2-i18n" name="settings[shipping_methods][]"
                        multiple="multiple"
                        style="width: 100%; max-width: 25%;">
					<?php foreach ( WC_Order_Export_Data_Extractor_UI::get_shipping_methods() as $i => $title ) { ?>
                        <option value="<?php echo esc_attr($i) ?>" <?php if ( in_array( $i, $settings['shipping_methods'] ) ) {
							echo 'selected';
						} ?>><?php echo esc_html($title) ?></option>
					<?php } ?>
                </select>
            </div>
        </div>

        <br>

        <div class="my-block">
			<span class="my-hide-next "><?php esc_html_e( 'Filter by item and metadata', 'woo-order-export-lite' ) ?>
                <span class="ui-icon ui-icon-triangle-1-s my-icon-triangle"></span></span>
	    <div id="my-items-meta" class="hide">
                <div><input type="hidden" name="settings[export_matched_items]" value="0"/><label><input
                                type="checkbox" name="settings[export_matched_items]"
                                value="1" <?php checked( $settings['export_matched_items'] ) ?> /> <?php esc_html_e( 'Export only matched product items',
                            'woo-order-export-lite' ) ?></label></div>
                <div><input type="hidden" name="settings[exclude_free_items]" value="0"/><label><input
                                type="checkbox" name="settings[exclude_free_items]"
                                value="1" <?php
                        checked( $settings['exclude_free_items'] ) ?> /> <?php
                        esc_html_e( 'Exclude free items',
                            'woo-order-export-lite' ) ?></label></div>
                <div class="custom-fields__wrapper">
                    <div>
                        <span class="wc-oe-header"><?php esc_html_e( 'Item names', 'woo-order-export-lite' ) ?></span>
                    </div>
                    <div class="custom-fields__condotion-wrapper custom-fields__condotion-wrapper_position">
                        <select id="item_names" class="select2-i18n" data-select2-i18n-width="150">
                            <option>coupon</option>
                            <option>fee</option>
                            <option>line_item</option>
                            <option>shipping</option>
                            <option>tax</option>
                        </select>
                        <select id="item_name_compare" class="select_compare">
                            <option>=</option>
                            <option>&lt;&gt;</option>
                            <option>LIKE</option>
                            <option>NOT LIKE</option>
                        </select>
                        <input type="text" id="text_order_item_name" disabled class="like-input" style="display: none;">
                        <button id="add_item_names" class="button-secondary"><span class="dashicons dashicons-plus-alt"></span>
                        </button>
                    </div>
                </div>
                <select id="item_names_check" class="select2-i18n" data-select2-i18n-default="1" multiple
                        name="settings[item_names][]"
                        style="width: 100%; max-width: 25%;">
					<?php
					if ( $settings['item_names'] ) {
						foreach ( $settings['item_names'] as $name ) {
							?>
                            <option selected value="<?php echo esc_attr($name); ?>"> <?php echo esc_html($name); ?></option>
						<?php }
					} ?>
                </select>
                <div class="custom-fields__wrapper">
                    <div>
                        <span class="wc-oe-header"><?php esc_html_e( 'Item metadata', 'woo-order-export-lite' ) ?></span>
                    </div>
                    <div class="custom-fields__condotion-wrapper custom-fields__condotion-wrapper_position">
                        <select id="item_metadata" class="select2-i18n" data-select2-i18n-width="150">
                            <?php foreach ( WC_Order_Export_Data_Extractor_UI::get_item_meta_keys() as $type => $meta_keys ) { ?>
                                <optgroup label="<?php echo esc_attr( ucwords( $type ) ); ?>">
                                    <?php foreach ( $meta_keys as $item_meta_key ) { ?>
                                        <option value="<?php echo esc_attr($type . ":" . $item_meta_key); ?>"><?php echo esc_html($item_meta_key); ?></option>
                                    <?php } ?>
                                </optgroup>
                            <?php } ?>
                        </select>
                        <select id="item_metadata_compare" class="select_compare">
                            <option>=</option>
                            <option>&lt;&gt;</option>
                            <option>LIKE</option>
                            <option>NOT SET</option>
                            <option>IS SET</option>
							<option>&gt;</option>
							<option>&gt;=</option>
							<option>&lt;</option>
							<option>&lt;=</option>

                        </select>
                        <input type="text" id="text_order_itemmetadata" disabled class="like-input" style="display: none;">
                        <button id="add_item_metadata" class="button-secondary"><span
                                    class="dashicons dashicons-plus-alt"></span></button>
                    </div>
                    <select id="item_metadata_check" class="select2-i18n" data-select2-i18n-default="1" multiple
                            name="settings[item_metadata][]"
                            style="width: 100%; max-width: 25%;">
                        <?php
                        if ( $settings['item_metadata'] ) {
                            foreach ( $settings['item_metadata'] as $meta ) {
                                ?>
                                <option selected value="<?php echo esc_attr($meta); ?>"> <?php echo esc_html($meta); ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="weo_clearfix"></div>
    <br>
    <div class="my-block">
		<span id='adjust-fields-btn' class="my-hide-next "><?php esc_html_e( 'Set up fields to export',
				'woo-order-export-lite' ) ?>
            <span class="ui-icon ui-icon-triangle-1-s my-icon-triangle"></span></span>
	    <div id="manage_fields" class="hide">
            <div style="display: grid; grid-template-columns: 10fr 1fr 10fr;">
                <div class="clear"></div>
                <div></div>
                <div>
                    <br class="clear"/>
                </div>
                <div id='fields' style='display:none;'>

                    <div class="fields-control-block"></div>
                    <div class="summary-row-title">
                        <div style="margin-bottom: 10px">
                            <input type="hidden" name="settings[display_summary_row]" value="0">
                            <input type="checkbox" name="settings[display_summary_row]"
                                   id="display_summary_row_checkbox" value="1"
				                <?php checked( $settings['display_summary_row'] ?? '', '1' ); ?>>
			                <?php esc_html_e( 'Display summary row (you must mark fields below)', 'woo-order-export-lite' ); ?>
                        </div>
                        <div id="title_for_summary_row_block">
			                <?php esc_html_e( 'Title for summary row', 'woo-order-export-lite' ); ?>
                            <input name="settings[summary_row_title]"
                                   value="<?php echo esc_attr( $settings['summary_row_title'] ?? esc_html__( 'Total', 'woo-order-export-lite' ) ); ?>">
                        </div>
                        <hr>
                    </div>
                    <div class="fields-control">
                        <div style="display: inline-block; float: left">
                            <label style="font-size: medium;">
								<?php esc_html_e( 'Drag rows to reorder exported fields', 'woo-order-export-lite' ) ?>
                            </label>
                        </div>
                        <div style="display: inline-block; float: right; margin-bottom: 15px">
                            <a id="clear_selected_fields" class="button"
                               style="background-color: #bb77ae; color: white;: ">
								<?php esc_html_e( 'Remove all fields', 'woo-order-export-lite' ) ?>
                            </a>
                        </div>
                    </div>
                    <div>
                        <br class="clear"/>
                    </div>
                    <ul id="order_fields"></ul>
                </div>
                <div></div>
                <div id='unselected_fields'>
                    <?php $common_hints = WC_Order_Export_Data_Extractor_UI::get_common_hints() ?>
                    <div id="woe_common_tips" class="woe_common_tips">
                        <?php foreach ( $common_hints as $common_hint ) { ?>
                            <span><?php echo esc_html($common_hint); ?></span>
                        <?php } ?>
                    </div>
                    <ul class="subsubsub" style="float: none">
                        <?php $segments = WC_Order_Export_Data_Extractor_UI::get_unselected_fields_segments(); ?>
                        <?php $segment_hints = WC_Order_Export_Data_Extractor_UI::get_segment_hints(); ?>
						<?php foreach ( $segments as $id => $segment_title ): ?>
			<li class="block-segment-choice" data-segment="<?php echo esc_attr($id); ?>">
                                <a class="segment_choice"
                                   data-segment="<?php echo esc_attr($id); ?>" href="#segment=<?php echo esc_attr($id); ?>">
									<?php echo esc_html($segment_title); ?>
                                </a>
				    <span class="divider"><?php echo( end( $segments ) == $segment_title ? '' : ' | ' ); ?></span>
                            </li>
						<?php endforeach; ?>
                    </ul>
                    <div class="tab-controls">
                        <div class="tab-actions-buttons default-actions">
                            <span class="tab-actions-buttons__title">
                                <strong><?php esc_html_e( 'Actions', 'woo-order-export-lite' ) ?>:</strong>
                            </span>
                            <button class='button-secondary add-meta'>
								<?php esc_html_e( 'Add field', 'woo-order-export-lite' ) ?>
                            </button>
                            <button class='button-secondary add-custom'>
								<?php esc_html_e( 'Add static field', 'woo-order-export-lite' ) ?>
                            </button>
                            <button class='button-secondary add-calculated'>
								<?php esc_html_e( 'Add calculated field', 'woo-order-export-lite' ) ?>
                            </button>
                            <div class='add_form_warning' id='notice_drag_fields' style='display:none'><?php esc_html_e( "Drag a field to list of exported fields", 'woo-order-export-lite' )?></div>
                        </div>
                        <div class="tab-actions-buttons other_items-actions-buttons">
                            <span class="tab-actions-buttons__title">
                                <strong><?php esc_html_e( 'Actions', 'woo-order-export-lite' ) ?>:</strong>
                            </span>
                            <button class='button-secondary add-fee'>
				<?php esc_html_e( 'Add fee', 'woo-order-export-lite' ) ?>
                            </button>
                            <button class='button-secondary add-shipping'>
				<?php esc_html_e( 'Add shipping', 'woo-order-export-lite' ) ?>
                            </button>
                            <button class='button-secondary add-tax'>
				<?php esc_html_e( 'Add tax', 'woo-order-export-lite' ) ?>
                            </button>
                        </div>
                        <div class="tab-actions-forms">
                            <div class='div_meta segment-form all-segments'>
								<div class='add_form_tip'><?php esc_html_e( "The plugin fetches meta keys from the existing orders. So you should create fake order if you've added new field just now.", 'woo-order-export-lite' )?></div>
                                <label for="select_custom_meta_order">
                                    <?php esc_html_e( 'Meta key', 'woo-order-export-lite' ) ?>:
                                </label><br/>
                                <div>
                                    <select class="select2-i18n set-up__selects" id='select_custom_meta_order'>
                                        <?php
                                        foreach ( $order_custom_meta_fields['order'] as $meta_id => $meta_name ) {
                                            echo "<option value='" . esc_attr($meta_name) . "' > " . esc_attr($meta_name) . "</option>";
                                        };
                                        ?>
                                    </select>
                                </div>
                                <div id="custom_meta_order_mode" style="margin-bottom: 10px;">
                                    <input class="set-up__selects mt-sm" type='text' id='text_custom_meta_order'
                                           placeholder="<?php esc_html_e( 'or type meta key here',
                                               'woo-order-export-lite' ) ?>"/><br>
                                </div>
                                <div style="margin-bottom: 8px;">
                                    <input id="custom_meta_order_mode_used" type="checkbox"
                                           name="custom_meta_order_mode" value="used"> <?php esc_html_e( 'Hide unused fields',
                                        'woo-order-export-lite' ) ?>
                                </div>
                                <hr>
                                <div style="margin-top: 20px;"><label for="colname_custom_meta"><?php esc_html_e( 'Column name',
											'woo-order-export-lite' ) ?>:</label><input type='text'
                                                                                           id='colname_custom_meta'/>
                                </div>
                                <div style="margin-top: 20px;">
									<?php print_formats_field( 'meta' ); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_custom_meta' class='button-secondary'><?php esc_html_e( 'Confirm',
											'woo-order-export-lite' ) ?></button>
                                    <button class='button-secondary button-cancel'><?php esc_html_e( 'Cancel',
											'woo-order-export-lite' ) ?></button>
                                </div>
                            </div>
                            <div class='div_custom segment-form all-segments'>
                                <div>
                                    <label for="colname_custom_field"><?php esc_html_e( 'Column name',
											'woo-order-export-lite' ) ?>:</label>
                                    <input class="set-up__selects_sm" type='text' id='colname_custom_field'/>
                                </div>
                                <div>
                                    <label for="value_custom_field"><?php esc_html_e( 'Value', 'woo-order-export-lite' ) ?>
                                        :</label>
                                    <input class="set-up__selects_sm" type='text' id='value_custom_field'/>
                                </div>
                                <div>
									<?php print_formats_field( 'field' ); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_custom_field' class='button-secondary'><?php esc_html_e( 'Confirm',
											'woo-order-export-lite' ) ?></button>
                                    <button class='button-secondary button-cancel'><?php esc_html_e( 'Cancel',
											'woo-order-export-lite' ) ?></button>
                                </div>
                            </div>
                            <div class='div_meta segment-form user-segment user-add-field'>
                                <label for="select_custom_meta_user">
			                        <?php esc_html_e( 'Meta key', 'woo-order-export-lite' ) ?>:
                                </label><br/>
                                <div>
                                    <select class="select2-i18n set-up__selects" id='select_custom_meta_user'>
                                        <?php
                                        foreach ( $order_custom_meta_fields['user'] as $meta_id => $meta_name ) {
                                            echo "<option value='" . esc_html($meta_name) . "' >". esc_html($meta_name)."</option>";
                                        };
                                        ?>
                                    </select>
                                </div>
                                <div id="custom_meta_user_mode" style="margin-bottom: 10px;">
                                    <input class="set-up__selects mt-sm" type='text' id='text_custom_meta_user'
                                           placeholder="<?php esc_html_e( 'or type meta key here',
				                               'woo-order-export-lite' ) ?>"/><br>
                                </div>
                                <div style="margin-bottom: 8px;">
                                    <input id="custom_meta_user_mode_used" type="checkbox"
                                           name="custom_meta_order_mode" value="used"> <?php esc_html_e( 'Hide unused fields',
				                        'woo-order-export-lite' ) ?>
                                </div>
                                <hr>
                                <div style="margin-top: 20px;"><label for="colname_custom_meta_user"><?php esc_html_e( 'Column name',
					                        'woo-order-export-lite' ) ?>:</label><input type='text'
                                                                                           id='colname_custom_meta_user'/>
                                </div>
                                <div style="margin-top: 20px;">
			                        <?php print_formats_field( 'meta', 'user' ); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_custom_meta_users' class='button-secondary'><?php esc_html_e( 'Confirm',
					                        'woo-order-export-lite' ) ?></button>
                                    <button class='button-secondary button-cancel'><?php esc_html_e( 'Cancel',
					                        'woo-order-export-lite' ) ?></button>
                                </div>
                            </div>
                            <div class='div_meta products-segment segment-form products-add-field'>
                                <label for="select_custom_meta_products">
                                    <?php esc_html_e( 'Product fields', 'woo-order-export-lite' ) ?>:
                                </label>
                                <div>
                                    <select class="select2-i18n set-up__selects" id='select_custom_meta_products'>

                                    </select>
                                </div>

                                <input class="set-up__selects mt-sm" type='text'
                                                            id='text_custom_meta_products'
                                                            placeholder="<?php esc_html_e( 'or type meta key here',
									                            'woo-order-export-lite' ) ?>"/>
                                <div id="custom_meta_products_mode">
                                    <label><input id="custom_meta_products_mode_used" type="checkbox"
                                                  name="custom_meta_products_mode"
                                                  value="used"> <?php esc_html_e( 'Hide unused fields',
											'woo-order-export-lite' ) ?></label>
                                </div>
                                <div style="width: 80%; text-align: center;"><?php esc_html_e( 'OR',
										'woo-order-export-lite' ) ?></div>
                                <label><?php esc_html_e( 'Taxonomy', 'woo-order-export-lite' ) ?>:</label><select class="set-up__selects"
                                        id='select_custom_taxonomies_products'>
                                    <option></option>
									<?php
									foreach ( WC_Order_Export_Data_Extractor_UI::get_product_taxonomies() as $tax_id => $tax_name ) {
										echo "<option value='__".esc_attr($tax_name)."' >__".esc_html($tax_name)."</option>";
									};
									?>
                                </select>
                                <hr>
                                <div style="margin-top: 15px;"></div>
                                <label><?php esc_html_e( 'Column name', 'woo-order-export-lite' ) ?>:</label><input
                                        type='text' id='colname_custom_meta_products'/>
                                <div style="margin-top: 15px;"></div>
								<?php print_formats_field( 'meta', 'products' ); ?>
                                <div style="text-align: right;">
                                    <button id='button_custom_meta_products'
                                            class='button-secondary'><?php esc_html_e( 'Confirm',
											'woo-order-export-lite' ) ?></button>
                                    <button class='button-secondary button-cancel'><?php esc_html_e( 'Cancel',
											'woo-order-export-lite' ) ?></button>
                                </div>
                            </div>
			                <div class='div_custom products-segment segment-form products-add-static-field'>
                                <div>
                                    <label for="colname_custom_field_products"><?php esc_html_e( 'Column name',
											'woo-order-export-lite' ) ?>:</label>
                                    <input type='text' id='colname_custom_field_products' class="set-up__selects_sm"/>
                                </div>
                                <div>
                                    <label for="value_custom_field_products"><?php esc_html_e( 'Value',
											'woo-order-export-lite' ) ?>:</label>
                                    <input type='text' id='value_custom_field_products' class="set-up__selects_sm"/>
                                </div>
                                <div>
									<?php print_formats_field( 'field', 'products' ); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_custom_field_products'
                                            class='button-secondary'><?php esc_html_e( 'Confirm',
											'woo-order-export-lite' ) ?></button>
                                    <button class='button-secondary button-cancel'><?php esc_html_e( 'Cancel',
											'woo-order-export-lite' ) ?></button>
                                </div>
                            </div>
                            <div class='div_meta product_items-segment segment-form products-add-field'>
								<div class='add_form_tip'><?php esc_html_e( "The plugin fetches meta keys from the existing orders. So you should create fake order if you've added new field just now.", 'woo-order-export-lite' )?></div>

                                <label for="select_custom_meta_order_items">
                                    <?php esc_html_e( 'Order item fields', 'woo-order-export-lite' ) ?>:
                                </label>
                                <div>
                                    <select class="select2-i18n set-up__selects" id='select_custom_meta_order_items'>

                                    </select>
                                </div>
                                <input style="width: 53.5%; margin-top: .5rem; margin-bottom: 10px;" type='text'
                                                            id='text_custom_meta_order_items'
                                                            placeholder="<?php esc_html_e( 'or type meta key here',
									                            'woo-order-export-lite' ) ?>"/>
                                <div id="custom_meta_product_items_mode">
                                    <label><input id="custom_meta_product_items_mode_used" type="checkbox"
                                                  name="custom_meta_product_items_mode"
                                                  value="used"> <?php esc_html_e( 'Hide unused fields',
											'woo-order-export-lite' ) ?></label>
                                </div>
                                <hr>
                                <div style="margin-top: 15px;"></div>
                                <label><?php esc_html_e( 'Column name', 'woo-order-export-lite' ) ?>:</label><input
                                        type='text' id='colname_custom_meta_product_items'/>
                                <div style="margin-top: 15px;"></div>
								<?php print_formats_field( 'meta', 'product_items' ); ?>
                                <div style="text-align: right;">
                                    <button id='button_custom_meta_product_items'
                                            class='button-secondary'><?php esc_html_e( 'Confirm',
											'woo-order-export-lite' ) ?></button>
                                    <button class='button-secondary button-cancel'><?php esc_html_e( 'Cancel',
											'woo-order-export-lite' ) ?></button>
                                </div>
                            </div>
                            <div class='div_custom product_items-segment segment-form products-add-static-field'>
                                <div>
                                    <label for="colname_custom_field_product_items"><?php esc_html_e( 'Column name',
											'woo-order-export-lite' ) ?>:</label>
                                    <input type='text' class="set-up__selects_sm" id='colname_custom_field_product_items'/>
                                </div>
                                <div>
                                    <label for="value_custom_field_product_items"><?php esc_html_e( 'Value',
											'woo-order-export-lite' ) ?>:</label>
                                    <input type='text' class="set-up__selects_sm" id='value_custom_field_product_items'/>
                                </div>
                                <div>
									<?php print_formats_field( 'field', 'product_items' ); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_custom_field_product_items'
                                            class='button-secondary'><?php esc_html_e( 'Confirm',
											'woo-order-export-lite' ) ?></button>
                                    <button class='button-secondary button-cancel'><?php esc_html_e( 'Cancel',
											'woo-order-export-lite' ) ?></button>
                                </div>
                            </div>
                            <div class='div_meta coupons-segment segment-form coupons-add-field'>
                                <label><?php esc_html_e( 'Meta key', 'woo-order-export-lite' ) ?>:</label>
                                <div id="custom_meta_coupons_mode" style="display: none;">
                                    <input id="custom_meta_coupons_mode" type="checkbox"
                                           name="custom_meta_coupons_mode" value="used"> <?php esc_html_e( 'Hide unused fields',
										'woo-order-export-lite' ) ?>
                                </div>
                                <br>
                                <select class="set-up__selects" id='select_custom_meta_coupons'></select>
                                <input class="set-up__selects mb-2 mt-sm" type='text' id='text_custom_meta_coupons'
                                       placeholder="<?php esc_html_e( 'or type meta key here',
									       'woo-order-export-lite' ) ?>"/><br/>
                                <hr>
                                <label><?php esc_html_e( 'Column name', 'woo-order-export-lite' ) ?>:</label><input
                                        type='text' id='colname_custom_meta_coupons'/></label>
                                <div style="margin-top: 20px;">
									<?php print_formats_field( 'meta', 'coupons' ); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_custom_meta_coupons'
                                            class='button-secondary'><?php esc_html_e( 'Confirm',
											'woo-order-export-lite' ) ?></button>
                                    <button class='button-secondary button-cancel'><?php esc_html_e( 'Cancel',
											'woo-order-export-lite' ) ?></button>
                                </div>
                            </div>
                            <div class='div_custom coupons-segment segment-form coupons-add-static-field'>
                                <div>
                                    <label for="colname_custom_field_coupons"><?php esc_html_e( 'Column name',
											'woo-order-export-lite' ) ?>:</label>
                                    <input class="set-up__selects_sm" type='text' id='colname_custom_field_coupons'/>
                                </div>
                                <div>
                                    <label for="value_custom_field_coupons"><?php esc_html_e( 'Value',
											'woo-order-export-lite' ) ?>:</label>
                                    <input class="set-up__selects_sm" type='text' id='value_custom_field_coupons'/>
                                </div>
                                <div>
									<?php print_formats_field( 'field', 'coupons' ); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_custom_field_coupons' class='button-secondary'>
										<?php esc_html_e( 'Confirm', 'woo-order-export-lite' ) ?>
                                    </button>
                                    <button class='button-secondary button-cancel'>
										<?php esc_html_e( 'Cancel', 'woo-order-export-lite' ) ?>
                                    </button>
                                </div>
                            </div>
                            <div class='div_custom other-items-segment segment-form other-items-add-fee-form'>
                                <label>
                                    <?php esc_html_e( 'Fee name', 'woo-order-export-lite' ) ?>:
                                </label>
                                <br/>
                                <select id='select_fee_items'></select>
                                <br/>
                                <br/>
                                <label><?php esc_html_e( 'Column name', 'woo-order-export-lite' ) ?>:</label>
                                <input type='text' id='colname_fee_item_other_items'/>
                                <div style="margin-top: 20px;">
				                    <?php print_formats_field( 'field', 'other_items', 'money',  'format_fee_item_other_items'); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_other_items_add_fee_field' class='button-secondary'>
					                    <?php esc_html_e( 'Confirm', 'woo-order-export-lite' ) ?>
                                    </button>
                                    <button class='button-secondary button-cancel'>
					                    <?php esc_html_e( 'Cancel', 'woo-order-export-lite' ) ?>
                                    </button>
                                </div>
                            </div>
                            <div class='div_custom other-items-segment segment-form other-items-add-shipping-form'>
                                <label>
                                    <?php esc_html_e( 'Shipping name', 'woo-order-export-lite' ) ?>:
                                </label>
                                <br/>
                                <select id='select_shipping_items'></select>
                                <br/>
                                <br/>
                                <label><?php esc_html_e( 'Column name', 'woo-order-export-lite' ) ?>:</label>
                                <input type='text' id='colname_shipping_item_other_items'/>
                                <div style="margin-top: 20px;">
				                    <?php print_formats_field( 'field', 'other_items', 'money',  'format_shipping_item_other_items'); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_other_items_add_shipping_field' class='button-secondary'>
					                    <?php esc_html_e( 'Confirm', 'woo-order-export-lite' ) ?>
                                    </button>
                                    <button class='button-secondary button-cancel'>
					                    <?php esc_html_e( 'Cancel', 'woo-order-export-lite' ) ?>
                                    </button>
                                </div>
                            </div>
                            <div class='div_custom other-items-segment segment-form other-items-add-tax-form'>
                                <label>
                                    <?php esc_html_e( 'Tax name', 'woo-order-export-lite' ) ?>:
                                </label>
                                <br/>
                                <select id='select_tax_items'></select>
                                    <br/>
                                    <br/>
                                    <label><?php esc_html_e( 'Column name', 'woo-order-export-lite' ) ?>:</label>
                                    <input type='text' id='colname_tax_item_other_items'/>
                                <div style="margin-top: 20px;">
				                    <?php print_formats_field( 'field', 'other_items', 'money',  'format_tax_item_other_items'); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_other_items_add_tax_field' class='button-secondary'>
					                    <?php esc_html_e( 'Confirm', 'woo-order-export-lite' ) ?>
                                    </button>
                                    <button class='button-secondary button-cancel'>
					                    <?php esc_html_e( 'Cancel', 'woo-order-export-lite' ) ?>
                                    </button>
                                </div>
                            </div>
                            <div class="div_calculated segment-form all-segments">
                                <div style="padding-bottom: 0.4rem">
                                    <a class='add_form_tip' href="https://docs.algolplus.com/algol_order_export/developers-algol_order_export/common/add-calculated-field-for-order/" target="_blank">
                                        <?php esc_html_e( "You should add code to section \"Misc Settings\". Read the guide", 'woo-order-export-lite' )?>
                                    </a>
                                </div>
                                <div>
                                    <label for="metakey_custom_calculated">
                                        <?php esc_html_e('Meta key', 'woo-order-export-lite') ?>:
                                    </label>
                                    <input type="text" id="metakey_custom_calculated"/>
                                </div>
                                <div>
                                    <label for="label_custom_calculated">
                                        <?php esc_html_e('Label', 'woo-order-export-lite') ?>:
                                    </label>
                                    <input type="text" id="label_custom_calculated"/>
                                </div>
                                <div>
                                    <?php print_formats_field('calculated'); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_custom_calculated' class='button-secondary'>
					                    <?php esc_html_e( 'Confirm', 'woo-order-export-lite' ) ?>
                                    </button>
                                    <button class='button-secondary button-cancel'>
					                    <?php esc_html_e( 'Cancel', 'woo-order-export-lite' ) ?>
                                    </button>
                                </div>
                            </div>
                            <div class="div_calculated segment-form products-segment">
                                <div style="padding-bottom: 0.4rem">
                                    <a class='add_form_tip' href="https://docs.algolplus.com/algol_order_export/developers-algol_order_export/common/add-calculated-field-for-product/" target="_blank">
                                        <?php esc_html_e( "You should add code to section \"Misc Settings\". Read the guide", 'woo-order-export-lite' )?>
                                    </a>
                                </div>
                                <div>
                                    <label for="metakey_custom_calculated_products">
                                        <?php esc_html_e('Meta key', 'woo-order-export-lite') ?>:
                                    </label>
                                    <input type="text" id="metakey_custom_calculated_products"/>
                                </div>
                                <div>
                                    <label for="label_custom_calculated_products">
                                        <?php esc_html_e('Label', 'woo-order-export-lite') ?>:
                                    </label>
                                    <input type="text" id="label_custom_calculated_products"/>
                                </div>
                                <div>
                                    <?php print_formats_field('calculated', 'products'); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_custom_calculated_products' class='button-secondary'>
					                    <?php esc_html_e( 'Confirm', 'woo-order-export-lite' ) ?>
                                    </button>
                                    <button class='button-secondary button-cancel'>
					                    <?php esc_html_e( 'Cancel', 'woo-order-export-lite' ) ?>
                                    </button>
                                </div>
                            </div>
                            <div class="div_calculated segment-form product_items-segment">
                                <div style="padding-bottom: 0.4rem">
                                    <a class='add_form_tip' href="https://docs.algolplus.com/algol_order_export/developers-algol_order_export/common/add-calculated-field-for-product/" target="_blank">
                                        <?php esc_html_e( "You should add code to section \"Misc Settings\". Read the guide", 'woo-order-export-lite' )?>
                                    </a>
                                </div>
                                <div>
                                    <label for="metakey_custom_calculated_product_items">
                                        <?php esc_html_e('Meta key', 'woo-order-export-lite') ?>:
                                    </label>
                                    <input type="text" id="metakey_custom_calculated_product_items"/>
                                </div>
                                <div>
                                    <label for="label_custom_calculated_product_items">
                                        <?php esc_html_e('Label', 'woo-order-export-lite') ?>:
                                    </label>
                                    <input type="text" id="label_custom_calculated_product_items"/>
                                </div>
                                <div>
                                    <?php print_formats_field('calculated', 'product_items'); ?>
                                </div>
                                <div style="text-align: right;">
                                    <button id='button_custom_calculated_product_items' class='button-secondary'>
					                    <?php esc_html_e( 'Confirm', 'woo-order-export-lite' ) ?>
                                    </button>
                                    <button class='button-secondary button-cancel'>
					                    <?php esc_html_e( 'Cancel', 'woo-order-export-lite' ) ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="woe_hints_block">
                        <div class="summary-products-mode-tip">
                            <?php esc_html_e( 'Turn off mode Summary report to export order fields', 'woo-order-export-lite' ) ?>
                        </div>
                        <?php foreach ( $segment_hints as $key => $hint ): ?>
                                <div class="woe_segment_tips" id="woe_tips_<?php echo esc_attr($key) ?>">
                                    <?php echo esc_html($hint); ?>
                                </div>
                        <?php endforeach; ?>
                    </div>
                    <div id="unselected_fields_list"></div>


                    <!--                    <div class="section settings-segment" id="order_segment">-->
                    <!--                        <h1>ORDER</h1>-->
                    <!--                    </div>-->
                    <!--                    <div class="section settings-segment" id="products_segment">-->
                    <!--                        <h1>PRODUCT</h1>-->
                    <!--                    </div>-->
                    <!--                    <div class="section settings-segment" id="coupons_segment">-->
                    <!--                        <h1>COUPON</h1>-->
                    <!--                    </div>-->

                </div>
            </div>
            <div id="modal_content" style="display: none;"></div>
        </div>

    </div>
	<?php do_action( "woe_settings_above_buttons", $settings ); ?>
    <div id=JS_error_onload
         style='color:red;font-size: 120%;'><?php
		/* translators: error message if external js broken our UI loader */
         echo sprintf( esc_html__( "If you see this message after page load, user interface won't work correctly!<br>There is a JS error (<a target=blank href='%s'>read here</a> how to view it). Probably, it's a conflict with another plugin or active theme.",
			'woo-order-export-lite' ),
			"https://wordpress.org/support/article/using-your-browser-to-diagnose-javascript-errors/#step-3-diagnosis" ); ?></div>
    <p class="submit">
        <input type="submit" id='preview-btn' class="button-secondary preview-btn"
               data-limit="<?php echo( $mode === WC_Order_Export_Manage::EXPORT_ORDER_ACTION ? 1 : 5 ); ?>"
               value="<?php esc_html_e( 'Preview', 'woo-order-export-lite' ) ?>"
               title="<?php esc_html_e( 'Might be different from actual export!', 'woo-order-export-lite' ) ?>"/>
		<?php if ( $mode == WC_Order_Export_Manage::EXPORT_NOW ): ?>
            <input type="submit" id='save-only-btn' class="button-primary"
                   value="<?php esc_html_e( 'Save settings', 'woo-order-export-lite' ) ?>"/>
		<?php else: ?>
            <input type="submit" id='save-btn' class="button-primary"
                   value="<?php esc_html_e( 'Save & Exit', 'woo-order-export-lite' ) ?>"/>
            <input type="submit" id='save-only-btn' class="button-secondary"
                   value="<?php esc_html_e( 'Save settings', 'woo-order-export-lite' ) ?>"/>
		<?php endif; ?>

		<?php if ( $show['export_button'] ) { ?>
            <input type="submit" id='export-btn' class="button-secondary"
                   value="<?php esc_html_e( 'Export', 'woo-order-export-lite' ) ?>"/>
		<?php } ?>
		<?php if ( $show['export_button_plain'] ) { ?>
            <input type="submit" id='export-wo-pb-btn' class="button-secondary"
                   value="<?php esc_html_e( 'Export [w/o progressbar]', 'woo-order-export-lite' ) ?>"
                   title="<?php esc_html_e( 'It might not work for huge datasets!', 'woo-order-export-lite' ) ?>"/>
		<?php } ?>

		<?php do_action( 'woe_settings_form_view_save_as_profile', $settings ) ?>

		<?php if ( $mode === WC_Order_Export_Manage::EXPORT_NOW ): ?>
            <input type="submit" id='reset-profile' class="button-secondary"
                   value="<?php esc_html_e( 'Reset settings', 'woo-order-export-lite' ) ?>"/>
		<?php endif; ?>

        <span id="preview_actions" class="hide">
			<strong id="output_preview_total"><?php
			/* translators: estimation when button Preview pressed  */
			echo sprintf( esc_html__( 'Export total: %s orders',
					'woo-order-export-lite' ), '<span></span>' ) ?></strong>
			<?php esc_html_e( 'Preview size', 'woo-order-export-lite' ); ?>
			<?php foreach ( array( 5, 10, 25, 50 ) as $n ): ?>
                <button class="button-secondary preview-btn" data-limit="<?php echo esc_attr($n); ?>"><?php echo esc_html($n); ?></button>
			<?php endforeach ?>
		</span>
    </p>
    <div id=Settings_updated
         style='display:none;color:green;font-size: 120%;'><?php esc_html_e( "Settings were successfully updated!",
			'woo-order-export-lite' ) ?></div>
    <div id=Settings_error
         style='display:none;color:red;font-size: 120%;'></div>

	<?php if ( $show['export_button'] OR $show['export_button_plain'] ) { ?>
        <div id="progress_div" style="display: none;">
            <h1 class="title-cancel"><?php esc_html_e( "Press 'Esc' to cancel the export", 'woo-order-export-lite' ) ?></h1>
            <h1 class="title-download"><a target=_blank><?php esc_html_e( "Click here to download",
						'woo-order-export-lite' ) ?></a></h1>
            <div id="progressBar">
                <div></div>
            </div>
            <h3 class="title-gen-file"><?php esc_html_e( "Generating file...", 'woo-order-export-lite' ) ?></h3>
        </div>
        <div id="background"></div>
	<?php } ?>

</form>
<textarea rows=10 id='output_preview' style="overflow: auto;" wrap='off'></textarea>
<div id='output_preview_csv' style="overflow: auto;width:100%"></div>

<iframe id='export_new_window_frame' width=0 height=0 style='display:none'></iframe>

<form id='export_wo_pb_form' method='post' target='export_wo_pb_window'>
    <input name="action" type="hidden" value="order_exporter">
    <input name="method" type="hidden" value="plain_export">
    <input name="tab" type="hidden" value="<?php echo esc_attr($active_tab) ?>">
	<?php wp_nonce_field( 'woe_nonce', 'woe_nonce' ); ?>
    <input name="mode" type="hidden" value="<?php echo esc_attr($mode) ?>">
    <input name="id" type="hidden" value="<?php echo esc_attr($id) ?>">
    <input name="json" type="hidden">
    <input name="woe_order_post_type" type="hidden" value="<?php echo esc_attr( $woe_order_post_type ) ?>">
</form>