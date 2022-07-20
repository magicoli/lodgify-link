<?php defined( 'LOLI_VERSION' ) || die;

function loli_is_project_product($product_id) {
  // return true; // let's handle this later
	return (wc_get_product( $product_id )->get_meta( '_linkproject' ) == 'yes');
}

/**
 * [LOLI description]
 */
class LOLI {

  /*
  * Bootstraps the class and hooks required actions & filters.
  */
  public static function init() {
		// Add project option to product edit page
    add_filter( 'product_type_options', __CLASS__ . '::add_product_type_options');
    add_action( 'save_post_product', __CLASS__ . '::save_product_type_options', 10, 3);

		// Add project field to product page
    add_action( 'woocommerce_before_add_to_cart_button', __CLASS__ . '::display_custom_field');

		// Update product name in cart
    add_filter( 'woocommerce_add_to_cart_validation', __CLASS__ . '::validate_custom_field', 10, 3 );
    add_filter( 'woocommerce_add_cart_item_data', __CLASS__ . '::add_custom_field_item_data', 10, 4 );
    add_filter( 'woocommerce_cart_item_name', __CLASS__ . '::cart_item_name', 1, 3 );

		// add_action( 'woocommerce_before_calculate_totals', __CLASS__ . '::before_calculate_totals', 10, 1 );
    add_action( 'woocommerce_checkout_create_order_line_item', __CLASS__ . '::add_custom_data_to_order', 10, 4 );

    // add_filter( 'wc_add_to_cart_message', __CLASS__ . '::add_to_cart_message', 10, 2 );
    // add_filter( 'woocommerce_get_price_html', __CLASS__ . '::get_price_html', 10, 2 );
    //
    add_action( 'plugins_loaded', __CLASS__ . '::load_plugin_textdomain' );
    //
    add_filter( 'woocommerce_product_add_to_cart_text', __CLASS__ . '::add_to_card_button', 10, 2);
    add_filter( 'woocommerce_product_single_add_to_cart_text', __CLASS__ . '::single_add_to_card_button', 10, 2);
  }

  static function load_plugin_textdomain() {
		load_plugin_textdomain(
			'lodgify-link',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

  static function add_to_card_button( $text, $product ) {
    if($product->get_meta( '_linkproject' ) == 'yes') $text = __('Support project', 'lodgify-link');
  	return $text;
  }

  static function single_add_to_card_button( $text, $product ) {
    if($product->get_meta( '_linkproject' ) == 'yes') $text = __('Support project', 'lodgify-link');
  	return $text;
  }

  static function add_to_cart_message( $message, $product_id ) {
      // make filter magic happen here...
      if(!empty($_POST['loli_project_name'])) $message = $_POST['loli_project_name'] . ": $message";
      return $message;
  }

  static function add_product_type_options($product_type_options) {
    $product_type_options['linkproject'] = array(
      "id"            => "_linkproject",
      "wrapper_class" => "show_if_simple show_if_variable",
      "label"         => __('Lodgify Link', 'lodgify-link'),
      "description"   => __('Check to add a project field to product page.', 'lodgify-link'),
      "default"       => "no",
    );
    return $product_type_options;
  }

  public static function save_product_type_options($post_ID, $product, $update) {
    update_post_meta($product->ID, "_linkproject", isset($_POST["_linkproject"]) ? "yes" : "no");
  }

  /**
  * Display custom field on the front end
  * @since 1.0.0
  */
  static function display_custom_field() {
    global $post;
		if(!loli_is_project_product( wc_get_product( $post->ID ) )) return;

    $value = (isset($_REQUEST['project'])) ? esc_attr($_REQUEST['project']) : NULL;

    // $value = isset( $_POST['loli_project_name'] ) ? sanitize_text_field( $_POST['loli_project_name'] ) : '';
    printf(
      '<div class="loli-field loli-field-project-name">
        <label for="loli_project_name" class="required">%s%s</label>
        <p class="form-row form-row-wide">
          <input type="%s" class="input-text" name="loli_project_name" value="%s" placeholder="%s" required>
        </p>
        %s
      </div>',
      __('Booking', 'lodgify-link'),
      (empty($value)) ? ' <abbr class="required" title="required">*</abbr>' : ': <span class=project_name>' . $value . '</span>',
      (empty($value)) ? 'text' : 'hidden',
      $value,
      __("Enter a project name", 'lodgify-link'),
      (empty($value)) ? __('Specify the project related to this purchase.', 'lodgify-link') : '',
    );
  }

  static function validate_custom_field( $passed, $product_id, $quantity ) {
    if($passed && loli_is_project_product( $product_id )) {
      if(!empty($_POST['loli_project_name']))
      $project = sanitize_text_field($_POST['loli_project_name']);
      else if(!empty($_REQUEST['project']))
      $project = sanitize_text_field($_REQUEST['project']);
      else
      $project = NULL;

      if( empty( $project ) ) {
        $product_title = wc_get_product( $product_id )->get_title();

        wc_add_notice( sprintf(
          __('"%s" could not be added to the cart. Please provide a project name.', 'lodgify-link'),
          sprintf('<a href="%s">%s</a>', get_permalink($product_id), $product_title),
        ), 'error' );
        return false;
      }
    }
    return $passed;
  }

  /**
  * Add the text field as item data to the cart object
  * @since 1.0.0
  * @param Array $cart_item_data Cart item meta data.
  * @param Integer $product_id Product ID.
  * @param Integer $variation_id Variation ID.
  * @param Boolean $quantity Quantity
  */
  static function add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
    if(!empty($_POST['loli_project_name']))
    $cart_item_data['loli_project_name'] = sanitize_text_field($_POST['loli_project_name']);
    else if(!empty($_REQUEST['project']))
    $cart_item_data['loli_project_name'] = sanitize_text_field($_REQUEST['project']);

    return $cart_item_data;
  }

  /**
  * Update the price in the cart
  * @since 1.0.0
  */
  function before_calculate_totals( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
      return;
    }
    // Iterate through each cart item
    foreach( $cart->get_cart() as $cart_key => $cart_item ) {
      $cached = wp_cache_get('loli_cart_item_processed_' . $cart_key, 'lodgify-link');
      if(!$cached) {
        if( is_numeric( $cart_item['project_price'] &! $cart_item['project_price_added']) ) {
          $price = (float)$cart_item['data']->get_price( 'edit' );
          $total = $price + $cart_item['project_price'];
          error_log('adding ' . $cart_item['project_price']
          . "\n" . 'initial price ' . $price
          . "\n" . 'adjusted price ' . $total);
          // $cart_item['data']->adjust_price( $cart_item['project_price'] );
          $cart_item['data']->set_price( ( $total ) );
          $cart_item['project_price_added'] = true;
        }
        wp_cache_set('loli_cart_item_processed_' . $cart_key, true, 'lodgify-link');
      }
    }
  }

  /**
  * Display the custom field value in the cart
  * @since 1.0.0
  */
  static function cart_item_name( $name, $cart_item, $cart_item_key ) {
    error_log(__FUNCTION__ . ' ' . print_r($cart_item, true));

    if( isset( $cart_item['loli_project_name'] ) ) {
      $name = sprintf(
      '%s <span class=loli-project-name>"%s"</span>',
      $name,
      esc_html( $cart_item['loli_project_name'] ),
      );
    }
    return $name;
  }

  /**
  * Add custom field to order object
  */
  function add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
    foreach( $item as $cart_item_key=>$values ) {
      if( isset( $values['loli_project_name'] ) ) {
        $item->add_meta_data( __( 'Booking', 'lodgify-link' ), $values['loli_project_name'], true );
      }
    }
  }

  function get_price_html( $price_html, $product ) {
    if($product->get_meta( '_linkproject' ) == 'yes') {
      $price = max($product->get_price(), get_option('loli_project_minimum_price', 0));
      if( $price == 0 ) {
        $price_html = apply_filters( 'woocommerce_empty_price_html', '', $product );
      } else {
        if ( $product->is_on_sale() && $product->get_price() >= $price ) {
          $price = wc_format_sale_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ),
          wc_get_price_to_display( $product ) ) . $product->get_price_suffix();
        } else {
          $price = wc_price( $price ) . $product->get_price_suffix();
        }
        $price_html = sprintf('<span class="from">%s </span>', __('From', 'lodgify-link')) . $price;
      }
    }
    return $price_html;
  }
}

LOLI::init();