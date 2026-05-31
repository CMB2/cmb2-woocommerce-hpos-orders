CMB2 WooCommerce HPOS Orders
==================

Custom field for [CMB2](https://github.com/CMB2/CMB2).

Adds the ability to add custom fields to the new WooCommerce HPOS orders page.

## Installation

Follow the example in [`example-field-setup.php`](https://github.com/CMB2/cmb2-woocommerce-hpos-orders/blob/master/example-field-setup.php) for a demonstration. The example assumes you have both CMB2 and this extension in your mu-plugins directory. If you're using CMB2 installed as a plugin, you can remove the `require_once` line.

## Usage
You can retrieve the meta data using the following:

```php
$test_text_value = wc_get_order( $order_id )->get_meta( 'yourprefix_woo_orders_text', true, 'edit' );
```

## Changelog

### 1.0.1
* Bugfix: Gate the `cmb2_override_meta_*` filters to the orders object type so they no longer run for unrelated CMB2 fields (posts, pages, users, etc.), which caused a fatal `Call to undefined function wc_get_order()` when WooCommerce was inactive.
* Guard `wc_get_order()` with `function_exists()` since this loads as an mu-plugin regardless of whether WooCommerce is active.

### 1.0.0
* Release