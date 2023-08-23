<?php
/**
 * Plugin Name: CMB2 WooCommerce HPOS Orders
 * Plugin URI: https://github.com/CMB2/cmb2-woocommerce-hpos-orders
 * Description: Adds the ability to add custom fields to the new WooCommerce HPOS orders page.
 * Version: 1.0.0
 * Author: CMB2
 * Author URI: http://cmb2.io
 * License: GPLv2+
 */

/**
 * CMB2_Woo_HPOS_Orders loader
 *
 * Handles checking for and smartly loading the newest version of this library.
 *
 * @category  WordPressLibrary
 * @package   CMB2_Woo_HPOS_Orders
 * @author    CMB2 <info@cmb2.io>
 * @copyright 2016 CMB2 <info@cmb2.io>
 * @license   GPL-2.0+
 * @version   1.0.0
 * @link      https://github.com/CMB2/cmb2-woocommerce-hpos-orders
 * @since     1.2.3
 */

/**
 * Copyright (c) 2016 CMB2 (email : info@cmb2.io)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Loader versioning: http://jtsternberg.github.io/wp-lib-loader/
 */

if ( ! class_exists( 'CMB2_Woo_HPOS_Orders_127', false ) ) {

	/**
	 * Versioned loader class-name
	 *
	 * This ensures each version is loaded/checked.
	 *
	 * @category WordPressLibrary
	 * @package  CMB2_Woo_HPOS_Orders
	 * @author   CMB2 <info@cmb2.io>
	 * @license  GPL-2.0+
	 * @version  1.0.0
	 * @link     https://github.com/CMB2/cmb2-woocommerce-hpos-orders
	 * @since    1.0.0
	 */
	class CMB2_Woo_HPOS_Orders_127 {

		/**
		 * CMB2_Woo_HPOS_Orders version number
		 * @var   string
		 * @since 1.2.3
		 */
		const VERSION = '1.0.0';

		/**
		 * Current version hook priority.
		 * Will decrement with each release
		 *
		 * @var   int
		 * @since 1.2.3
		 */
		const PRIORITY = 9999;

		/**
		 * Starts the version checking process.
		 * Creates CMB2_WOO_HPOS_ORDERS_LOADED definition for early detection by
		 * other scripts.
		 *
		 * Hooks CMB2_Woo_HPOS_Orders inclusion to the cmb2_woo_hpos_orders_load hook
		 * on a high priority which decrements (increasing the priority) with
		 * each version release.
		 *
		 * @since 1.2.3
		 */
		public function __construct() {
			if ( ! defined( 'CMB2_WOO_HPOS_ORDERS_LOADED' ) ) {
				/**
				 * A constant you can use to check if CMB2_Woo_HPOS_Orders is loaded
				 * for your plugins/themes with CMB2_Woo_HPOS_Orders dependency.
				 *
				 * Can also be used to determine the priority of the hook
				 * in use for the currently loaded version.
				 */
				define( 'CMB2_WOO_HPOS_ORDERS_LOADED', self::PRIORITY );
			}

			// Use the hook system to ensure only the newest version is loaded.
			add_action( 'cmb2_woo_hpos_orders_load', array( $this, 'include_lib' ), self::PRIORITY );

			// Use the hook system to ensure only the newest version is loaded.
			add_action( 'after_setup_theme', array( $this, 'do_hook' ) );
		}

		/**
		 * Fires the cmb2_woo_hpos_orders_load action hook
		 * (from the after_setup_theme hook).
		 *
		 * @since 1.2.3
		 */
		public function do_hook() {
			// Then fire our hook.
			do_action( 'cmb2_woo_hpos_orders_load' );
		}

		/**
		 * A final check if CMB2_Woo_HPOS_Orders exists before kicking off
		 * our CMB2_Woo_HPOS_Orders loading.
		 *
		 * CMB2_WOO_HPOS_ORDERS_VERSION and CMB2_WOO_HPOS_ORDERS_DIR constants are
		 * set at this point.
		 *
		 * @since  1.2.3
		 */
		public function include_lib() {
			if ( class_exists( 'CMB2_Woo_HPOS_Orders', false ) ) {
				return;
			}

			if ( ! defined( 'CMB2_WOO_HPOS_ORDERS_VERSION' ) ) {
				/**
				 * Defines the currently loaded version of CMB2_Woo_HPOS_Orders.
				 */
				define( 'CMB2_WOO_HPOS_ORDERS_VERSION', self::VERSION );
			}

			if ( ! defined( 'CMB2_WOO_HPOS_ORDERS_DIR' ) ) {
				/**
				 * Defines the directory of the currently loaded version of CMB2_Woo_HPOS_Orders.
				 */
				define( 'CMB2_WOO_HPOS_ORDERS_DIR', dirname( __FILE__ ) . '/' );
			}

			// Include and initiate CMB2_Woo_HPOS_Orders.
			require_once CMB2_WOO_HPOS_ORDERS_DIR . 'init.php';
		}

	}

	// Kick it off.
	new CMB2_Woo_HPOS_Orders_127;
}
