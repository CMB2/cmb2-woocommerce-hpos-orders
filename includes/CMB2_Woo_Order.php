<?php
/**
 * CMB2 WooCommerce Order class.
 *
 * @since  {{next}}
 *
 * @category  WordPress_Plugin
 * @package   CMB2
 * @author    CMB2 team
 * @license   GPL-2.0+
 * @link      https://cmb2.io
 *
 */
class CMB2_Woo_Order {

	/**
	 * Holds instances of this order object.
	 *
	 * @since {{next}}
	 *
	 * @var CMB2_Woo_Order[]
	 */
	protected static $instances = array();

	/**
	 * Holds the ID of the order.
	 *
	 * @since {{next}}
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Holds the order object.
	 *
	 * @since {{next}}
	 *
	 * @var WC_Order
	 */
	protected $order;

	/**
	 * Get instance of the CMB2_Woo_Order and cache it.
	 *
	 * @since  {{next}}
	 *
	 * @param  string  $id    The order ID.
	 * @param  boolean $cached Whether to use the cached instance or not.
	 *
	 * @return self
	 */
	public static function get( $id = '', $cached = true ) {
		if ( empty( $id ) ) {
			return new self( $id );
		}

		if ( $id instanceof WC_Order || $id instanceof WC_Abstract_Order ) {

			$order = $id;
			$id    = (int) $order->get_id();
			if ( ! isset( self::$instances[ $id ] ) ) {
				new self( $id );
			}

			self::$instances[ $id ]->set_order( $order );

		} elseif ( ! empty( $id->ID ) ) {
			$id = (int) $id->ID;
		} else {
			$id = (int) $id;
		}

		$me = isset( self::$instances[ $id ] ) ? self::$instances[ $id ] : null;
		if ( ! $cached || ! $me ) {
			$me = new self( $id );
			$me->fetch_and_set_order();
		}

		return $me;
	}

	/**
	 * Class constructor.
	 *
	 * @since {{next}}
	 *
	 * @param string $id The order ID.
	 */
	protected function __construct( $id = '' ) {

		// If no data has been passed, don't setup anything. Maybe we are in test or create mode?
		if ( empty( $id ) ) {
			return;
		}

		// Prepare properties.
		$this->id = $id;

		self::$instances[ $id ] = $this;
	}

	/**
	 * Fetches the order object and sets it.
	 *
	 * @since {{next}}
	 *
	 * @return self
	 */
	protected function fetch_and_set_order() {
		$this->set_order( wc_get_order( $this->id ) );

		return $this;
	}

	/**
	 * Sets the order object.
	 *
	 * @since {{next}}
	 *
	 * @param WC_Order $order The order object.
	 *
	 * @return self
	 */
	protected function set_order( $order ) {
		$this->order = $order;

		return $this;
	}

	/**
	 * Checks if the order exists/is valid.
	 *
	 * @since {{next}}
	 *
	 * @return boolean
	 */
	public function is() {
		if ( empty( $this->order ) ) {
			return false;
		}
		$id = $this->order->get_id();

		return ! empty( $id );
	}

	/**
	 * Gets order meta, use HPOS API when possible.
	 *
	 * @since {{next}}
	 *
	 * @param  string $key Meta Key.
	 * @param  bool   $single return first found meta with key, or all with $key.
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return mixed
	 */
	public function get_meta( $key = '', $single = true, $context = 'edit' ) {
		return ! empty( $this->order ) && method_exists( $this->order, 'get_meta' )
			? $this->order->get_meta( $key, $single, $context )
			: get_post_meta( $this->id, $key, $single );
	}

	/**
	 * Updates order meta, use HPOS API when possible.
	 *
	 * If using HPOS, can pass $save = false to not save the order (for bulk updates).
	 *
	 * @since {{next}}
	 *
	 * @param string $key   The meta key.
	 * @param mixed  $value The meta value.
	 * @param bool   $save  Whether to save the order after meta update (if using HPOS).
	 *
	 * @return boolean
	 */
	public function update_meta_data( $key, $value, $save = true ) {
		if ( ! empty( $this->order ) && method_exists( $this->order, 'update_meta_data' ) ) {
			$this->order->update_meta_data( $key, $value );
			return $save ? $this->order->save() : false;
		}

		return update_post_meta( $this->id, $key, $value );
	}

	public function delete_meta_data( $key, $save = true ) {
		if ( ! empty( $this->order ) && method_exists( $this->order, 'delete_meta_data' ) ) {
			$this->order->delete_meta_data( $key );
			return $save ? $this->order->save() : false;
		}

		return delete_post_meta( $this->id, $key );
	}

	/**
	 * Proxy calls to the order object.
	 *
	 * @since {{next}}
	 *
	 * @param string $method The method name.
	 * @param array  $args   The method arguments.
	 *
	 * @return mixed
	 */
	public function __call( $method, $args ) {
		return $this->order
			? call_user_func_array( array( $this->order, $method ), $args )
			: null;
	}
}
