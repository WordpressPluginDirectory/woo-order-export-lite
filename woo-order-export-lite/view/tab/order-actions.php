<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="tabs-content">
<br><br>
<?php esc_html_e( "Export single order automatically when it's paid or created",'woo-order-export-lite' )?>.
<a href="https://docs.algolplus.com/advanced-order-export/pro-version-algol-order-export/status-change-jobs/" target=_blank>
<?php esc_html_e( 'More details','woo-order-export-lite' )?></a>
<hr>
<?php
/* translators: purchase Pro link  */
echo sprintf( esc_html__( 'Buy %s to get access to this section', 'woo-order-export-lite' ),
	sprintf( '<a href="https://algolplus.com/plugins/downloads/advanced-order-export-for-woocommerce-pro/?utm_source=plugin&utm_medium=banner&utm_campaign=2026" target=_blank>%s</a>', esc_html__( 'Pro version', 'woo-order-export-lite' ) )
	);
?>
</div>
