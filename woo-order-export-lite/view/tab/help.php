<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="weo_clearfix"></div>
<div id="woe-admin" class="container-fluid wpcontent">
    <br>
    <p>
     <?php
     /* translators: links to documentation website  */
     echo sprintf( esc_html__( "Please, review %s at first.",'woo-order-export-lite' ), 
		sprintf('<a href="https://docs.algolplus.com/category/algol_order_export/" target=_blank>%s</a>', esc_html__( 'user guide','woo-order-export-lite' ) )
     ); ?>
     <br>
     <br>
     <?php
     /* translators: links to documentation website  */
     echo sprintf( esc_html__( 'Need help? Create ticket in %s .', 'woo-order-export-lite' ), 
     sprintf('<a href="https://algolplus.freshdesk.com" target=_blank>%s</a>', esc_html__( 'helpdesk system', 'woo-order-export-lite' ) )
     ); ?>
     <br>
     <br>
		<?php
     /* translators: links to documentation website  */
		echo sprintf( esc_html__( "Don't forget to attach your %s or some screenshots. It will significantly reduce reply time :)",
			'woo-order-export-lite' ), 
			sprintf('<a href="%1$s" target=_blank>%2$s</a>',
				esc_url( admin_url( 'admin.php?page=wc-order-export&tab=tools' ) ),
				esc_html__( 'settings',	'woo-order-export-lite' ) 
			)		
			); ?>
	</p>
    <br>
    <p><?php
     /* translators: links to documentation website  */
    echo sprintf( esc_html__( 'Look at %1$s for popular plugins or check %2$s to study how to extend the plugin.',
			'woo-order-export-lite' ), 
			sprintf('<a href="https://docs.algolplus.com/category/algol_order_export/developers-algol_order_export/codes-for-plugins-developers-algol_order_export/" target=_blank>%s</a>', esc_html__( 'code snippets', 'woo-order-export-lite' )),
			sprintf('<a href="https://docs.algolplus.com/category/algol_order_export/developers-algol_order_export/code-samples-developers-algol_order_export/" target=_blank>%s</a>',esc_html__( 'this page',	'woo-order-export-lite' ))
			); ?>
	</p>
</div>