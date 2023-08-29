<?php
/*
Plugin Name: Woocommerce Payment gateway One
Plugin URI: https://browter.com/
Description: A plugin that integrates a payment gateway with WooCommerce
Version: 1.0.0
Author: Imran Hosein Khan Joy
Author URI: https://greatkhanjoy.browter.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: browter-wcpay
*/

if (!defined('BROWTER_WCP_PLUGIN_DIR_PATH')) {
    define('BROWTER_WCP_PLUGIN_DIR_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
}

if (!defined('BROWTER_WCP_PLUGIN_DIR_URI')) {
    define('BROWTER_WCP_PLUGIN_DIR_URI', untrailingslashit(plugin_dir_url(__FILE__)));
}

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return;
}


require_once BROWTER_WCP_PLUGIN_DIR_PATH . '/inc/helpers/autoloader.php';

function browter_get_theme_instance()
{
    \BROWTER_PLUGIN\Inc\BROWTER_PLUGIN::get_instance();
}

browter_get_theme_instance();
