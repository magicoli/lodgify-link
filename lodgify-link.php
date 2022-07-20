<?php defined( 'ABSPATH' ) || die;
/**
 * Plugin Name:     Lodgify Link for WooCommerce
 * Plugin URI:      https://magiiic.com/wordpress/plugins/lodgify-link/
 * Description:     Synchronize accomodation bookings with Lodifgy
 * Author:          Magiiic
 * Author URI:      https://magiiic.com/
 * Text Domain:     lodgify-link
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Product_Addons_Autofill
 *
 * Icon1x: https://github.com/magicoli/lodgify-link/raw/master/assets/icon-128x128.png
 * Icon2x: https://github.com/magicoli/lodgify-link/raw/master/assets/icon-256x256.png
 * BannerHigh: https://github.com/magicoli/lodgify-link/raw/master/assets/banner-1544x500.jpg
 * BannerLow: https://github.com/magicoli/lodgify-link/raw/master/assets/banner-772x250.jpg
 */

// Your code starts here.
if(!defined('LOLI_VERSION')) {
  define('LOLI_VERSION', '1.0');
  define('LOLI_PLUGIN', plugin_basename(__FILE__));
  define('LOLI_SLUG', dirname(LOLI_PLUGIN));
  // define('LOLI_PLUGIN_NAME', 'Lodgify Link for WooCommerce');

  require(plugin_dir_path(__FILE__) . 'includes/classes.php');
  // if(is_admin()) require(plugin_dir_path(__FILE__) . 'admin/wc-admin-classes.php');

  if(file_exists(plugin_dir_path( __FILE__ ) . 'lib/package-updater.php'))
  include_once plugin_dir_path( __FILE__ ) . 'lib/package-updater.php';
}
