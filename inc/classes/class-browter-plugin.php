<?php

/**
 * Bootstraps the plugin
 * 
 * @package Browter
 */

namespace BROWTER_PLUGIN\Inc;

use BROWTER_PLUGIN\Inc\Traits\Singleton;

class BROWTER_PLUGIN
{
    use Singleton;

    protected function __construct()
    {
        //load classes
        $this->setup_hooks();
    }

    protected function setup_hooks()
    {
        /**
         * Actions
         */
        add_action('plugins_loaded', [$this, 'browter_wcpay_init'], 11);

        /**
         * Hooks
         */
        register_activation_hook(__FILE__, [$this, 'stripe_woocommerce_activate']);
        // register_uninstall_hook(__FILE__, [$this, 'stripe_woocommerce_uninstall']);

        /**
         * Filters
         */

        add_filter('woocommerce_payment_gateways', [$this, 'add_browter_wcpay_gateway']);
    }

    public function browter_wcpay_init()
    {
        if (class_exists('WC_Payment_Gateway')) {
            require_once BROWTER_WCP_PLUGIN_DIR_PATH . '/inc/classes/class-wcpay.php';
        }
    }

    function stripe_woocommerce_activate()
    {
        // Activation logic goes here
    }

    static function stripe_woocommerce_uninstall()
    {
        // Uninstallation logic goes here
    }

    public function add_browter_wcpay_gateway($gateways)
    {
        $gateways[] = 'BROWTER_PLUGIN\Inc\Browter_WC_Pay';
        return $gateways;
    }
}
