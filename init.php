<?php
/**
 * Class CMB2_Woo_HPOS_Orders
 */
class CMB2_Woo_HPOS_Orders {

	/**
	 * Current version number
	 */
	const VERSION = CMB2_WOO_HPOS_ORDERS_VERSION;

	/**
	 * The object type we are performing the hookup for
	 *
	 * @var   string
	 * @since {{next}}
	 */
	const OBJECT_TYPE = 'woocommerce_page_wc-orders';

	/**
	 * @var CMB2_Woo_HPOS_Orders
	 */
	protected static $single_instance = null;

	/**
	 * CMB2_Woo_Orders_Hookup instance if woocommerce_page_wc-orders page metabox.
	 *
	 * @var   CMB2_Woo_Orders_Hookup|null
	 * @since {{next}}
	 */
	protected $wooorders_hookup = null;

	/**
	 * Creates or returns an instance of this class.
	 * @since  0.1.0
	 * @return CMB2_Woo_HPOS_Orders A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Initialize the plugin by hooking into CMB2
	 */
	protected function __construct() {
		add_action( 'cmb2_init_hooks', array( $this, 'init_hooks' ) );
		add_filter( 'cmb2_set_object_id', array( $this, 'set_order_object_id' ), 10, 2 );
		add_filter( 'cmb2_set_box_object_type', array( $this, 'set_box_object_type' ), 10, 2 );
	}

	/**
	 * Hook in and setup our hookup object.
	 *
	 * @since {{next}}
	 *
	 * @param CMB2 $hookup The CMB2_Hookup object.
	 *
	 * @return CMB2_Woo_HPOS_Orders
	 */
	public function init_hooks( $hookup ) {
		require_once __DIR__ . '/includes/CMB2_Woo_Orders_Hookup.php';

		$this->wooorders_hookup = new CMB2_Woo_Orders_Hookup( $hookup->cmb );
		$this->wooorders_hookup->hooks();

		return $this;
	}

	/**
	 * Hook in and update the object id if on the orders page.
	 *
	 * @since {{next}}
	 *
	 * @param int  $object_id The object ID.
	 * @param CMB2 $cmb       The CMB2 object.
	 *
	 * @return int
	 */
	public function set_order_object_id( $object_id, $cmb ) {
		// Try to get our object ID from the global space.
		if ( self::OBJECT_TYPE === $cmb->object_type() ) {
			$object_id = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : $object_id;
		}

		return $object_id;
	}

	/**
	 * Hook in and update the box object type if on the orders page.
	 *
	 * @since {{next}}
	 *
	 * @param string $mb_object_type The metabox object type.
	 * @param string $found_type     The found object type.
	 *
	 * @return string
	 */
	public function set_box_object_type( $mb_object_type, $found_type ) {
		if ( self::OBJECT_TYPE === $found_type ) {
			$mb_object_type = $found_type;
		}

		return $mb_object_type;
	}

	/**
	 * Magic getter for our object.
	 *
	 * @param string $field Property to return.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		return $this->{$field};
	}
}
CMB2_Woo_HPOS_Orders::get_instance();
