<?php
/**
 * Handles hooking CMB2 HPOS order fields into WooCommerce orders.
 *
 * @since  {{next}}
 *
 * @category  WordPress_Plugin
 * @package   CMB2
 * @author    CMB2 team
 * @license   GPL-2.0+
 * @link      https://cmb2.io
 */
class CMB2_Woo_Orders_Hookup extends CMB2_Hookup {

	/**
	 * The object type we are performing the hookup for
	 *
	 * @var   string
	 * @since {{next}}
	 */
	protected $object_type = CMB2_Woo_HPOS_Orders::OBJECT_TYPE;

	/**
	 * Constructor
	 *
	 * @since {{next}}
	 * @param CMB2   $cmb        The CMB2 object to hookup.
	 */
	public function __construct( CMB2 $cmb ) {
		$this->cmb = $cmb;
	}

	/**
	 * Hook in and add metaboxes, override meta values, and save meta values, etc.
	 *
	 * @since {{next}}
	 * @return void
	 */
	public function hooks() {
		add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
		add_filter( 'cmb2_should_pre_enqueue', array( $this, 'maybe_pre_enqueue' ), 10, 2 );
		add_action( 'woocommerce_process_shop_order_meta', array( $this, 'save_order' ), 10, 2 );
		add_filter( 'cmb2_override_meta_value', array( $this, 'get_order_meta' ), 10, 3 );
		add_filter( 'cmb2_override_meta_save', array( $this, 'update_order_meta' ), 10, 2 );
		add_filter( 'cmb2_override_meta_remove', array( $this, 'remove_order_meta' ), 10, 2 );
	}

	/**
	 * If the current page is the object type, pre-enqueue the CMB2 scripts/styles.
	 *
	 * @since {{next}}
	 * @param bool   $should_pre_enqueue Whether to pre-enqueue.
	 * @param string $hook               The current page hook.
	 * @return bool
	 */
	public function maybe_pre_enqueue( $should_pre_enqueue, $hook ) {
		if ( $this->object_type === $hook ) {
			$should_pre_enqueue = true;
		}

		return $should_pre_enqueue;
	}

	/**
	 * Save the order meta.
	 *
	 * @since {{next}}
	 *
	 * @param int      $order_id The order ID.
	 * @param WC_Order $order    The order object.
	 *
	 * @return void
	 */
	public function save_order( $order_id, $order ) {
		// Cache the order object.
		$this->get_order( $order );

		// check permissions.
		if ( $this->can_save( $this->object_type ) ) {
			$this->cmb->save_fields( $order_id, $this->object_type, wp_unslash( $_POST ) );
		}
	}

	/**
	 * Get the order meta.
	 *
	 * @since {{next}}
	 *
	 * @param mixed  $data      The meta value.
	 * @param int    $object_id The object ID.
	 * @param string $args      The field args.
	 *
	 * @return mixed
	 */
	public function get_order_meta( $data, $object_id, $args ) {
		$order = $this->get_order( $object_id );
		if ( ! $order->is() ) {
			return $data;
		}

		return $order->get_meta( $args['field_id'], true, 'edit' );
	}

	/**
	 * Update the order meta.
	 *
	 * @since {{next}}
	 *
	 * @param bool|array $override Whether to override the meta value.
	 * @param array      $args     The field args.
	 *
	 * @return bool
	 */
	public function update_order_meta( $override, $args ) {
		$order = $this->get_order( $args['id'] );
		if ( ! $order->is() ) {
			return $override;
		}

		$order->update_meta_data( $args['field_id'], $args['value'], false );

		add_action( "cmb2_save_{$this->object_type}_fields_{$this->cmb->cmb_id}", array( $this, 'save_order_meta_changes' ), 10, 2 );

		return true;
	}

	/**
	 * Remove the order meta.
	 *
	 * @since {{next}}
	 *
	 * @param bool|array $override Whether to override the meta value.
	 * @param array      $args     The field args.
	 *
	 * @return bool
	 */
	public function remove_order_meta( $override, $args ) {
		$order = $this->get_order( $args['id'] );
		if ( ! $order->is() ) {
			return $override;
		}

		$order->delete_meta_data( $args['field_id'], false );

		add_action( "cmb2_save_{$this->object_type}_fields_{$this->cmb->cmb_id}", array( $this, 'save_order_meta_changes' ), 10, 2 );

		return true;
	}

	/**
	 * Trigger the save of the order meta.
	 *
	 * @since {{next}}
	 *
	 * @param int   $object_id The object ID.
	 * @param array $updated   The updated fields.
	 *
	 * @return void
	 */
	public function save_order_meta_changes( $object_id, $updated ) {
		if ( empty( $updated ) ) {
			return;
		}

		$order = $this->get_order( $object_id );
		if ( ! $order->is() ) {
			return;
		}

		$order->save();
	}

	/**
	 * Get the order object and load the CMB2_Woo_Order class if needed.
	 *
	 * @since {{next}}
	 *
	 * @param int|WC_Order $id The order ID or object.
	 *
	 * @return CMB2_Woo_Order
	 */
	public function get_order( $id ) {
		if ( ! class_exists( 'CMB2_Woo_Order', false ) ) {
			require_once __DIR__ . '/CMB2_Woo_Order.php';
		}

		return CMB2_Woo_Order::get( $id );

	}
	/**
	 * Magic getter for our object.
	 *
	 * @since {{next}}
	 *
	 * @param string $field Property to retrieve.
	 *
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'object_type':
			case 'cmb':
				return $this->{$field};
			default:
				throw new Exception( sprintf( 'Invalid %1$s property: %2$s', __CLASS__, $field ) );
		}
	}
}
