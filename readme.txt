=== Lodgify Link for WooCommerce ===
Contributors: magicoli69
Donate link: https://magiiic.com/support/Lodgify+Link
Tags: woocommerce, projects, product, donation
Requires at least: 4.5
Tested up to: 6.0.1
Requires PHP: 5.6
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Synchronize WooCommerce booking orders with Lodifgy

== Description ==

Currently, the plugin simply provides booking id and user-defined amount fields to WooCommerce products, to allow keeping track of payments related to externally managed bookings.

The final goal will be to use this booking id to fetch booking data from Lodgify and display a comprehensive detail of the reservation in WooCommerce order (accommodation, name of guest, booking dates, deposit and total amount...)

== Installation ==

* Install as usual (download and unzip in wp-content/plugins/ folder, then activate).
* Create a "Payment" product, activate "Lodgify Link" option and set product price to 0 (zero).
* You would probably also activate "Virtual" option.

From there, you can give you customer one of these links:

* Product URL
  - example.org/my/product
  (customer will fill amount and reference)
* Product URL with prefilled parameters, e.g.
  - example.org/my/product/?booking_id=12345&amount=100
  (customer can adjust the amount, then proceed)
* Add to cart link to send customer directly to cart or checkout page, e.g.
  - example.org/cart/?add-to-cart=123&booking_id=12345&amount=100
  - example.org/checkout/?add-to-cart=123&booking_id=12345&amount=100
  (the value of add-to-cart is the WooCommerce product id)

== Frequently Asked Questions ==

= Any question? =

42

== Changelog ==

= 1.0.2 =
* updated readme

= 1.0.1 =
* fix plugin icon

= 1.0 =
* added Lodgify Link option to products
* added custom amount and booking id fields to product pages

= 0.1.0 =
* Initial commit
