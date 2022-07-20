## Installation

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

