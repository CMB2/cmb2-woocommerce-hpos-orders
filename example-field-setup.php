<?php
/*
 * Example setup for adding metaboxes/fields to WooCommerce HPOS Orders.
 */

/**
 * Get the bootstrap! If using as a plugin, REMOVE THIS!
 */
require_once WPMU_PLUGIN_DIR . '/cmb2/init.php';
require_once WPMU_PLUGIN_DIR . '/cmb2-woocommerce-hpos-orders/cmb2-woocommerce-hpos-orders.php';

/**
 * Hook in and register a metabox to handle WooCommerce order fields.
 */
function yourprefix_register_woocommerce_orders_metabox() {
	$cmb_woo_orders = new_cmb2_box( array(
		'id'           => 'yourprefix_woocommerce_orders_page',
		'title'        => esc_html__( 'Order Stuff', 'cmb2' ),
		'desc'         => __FILE__,
		'object_types' => array( 'woocommerce_page_wc-orders' ),
	) );

	$cmb_woo_orders->add_field( array(
		'name'    => '👋',
		'id'      => 'yourprefix_woo_orders_title',
		'type'    => 'title',
	) );

	$cmb_woo_orders->add_field( array(
		'name'    => esc_html__( 'Some other color', 'cmb2' ),
		'desc'    => esc_html__( 'field description (optional)', 'cmb2' ),
		'id'      => 'yourprefix_woo_orders_other_color',
		'type'    => 'colorpicker',
		'default' => '#ffffff',
	) );

	$cmb_woo_orders->add_field( array(
		'name'     => __( 'Test Text', 'cmb2' ),
		'desc'     => __( 'field description (optional)', 'cmb2' ),
		'id'       => 'yourprefix_woo_orders_text',
		'type'     => 'text',
	) );
}
add_action( 'cmb2_admin_init', 'yourprefix_register_woocommerce_orders_metabox' );
