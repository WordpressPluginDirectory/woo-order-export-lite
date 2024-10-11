<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$pro_link = '<a href="https://algolplus.com/plugins/downloads/advanced-order-export-for-woocommerce-pro/?currency=USD" target=_blank>' . __( 'Pro version', 'woo-order-export-lite' ) . '</a>';
?>
<div class="tabs-content">
<br><br>
<?php _e( "Export single order automatically when it's paid or created",'woo-order-export-lite' )?>.
<a href="https://docs.algolplus.com/algol_order_export/interface-of-the-status-change-jobs-tab/" target=_blank>
<?php _e( 'More details','woo-order-export-lite' )?></a>
<hr>
<?php echo sprintf( __( 'Buy %s to get access to this section', 'woo-order-export-lite' ), $pro_link ) ?>
</div>
