<?php

/**
 * WcPay Payment Gateway
 * 
 * @package Browter
 */

namespace BROWTER_PLUGIN\Inc;


class Browter_WC_Pay extends \WC_Payment_Gateway
{
    public function __construct()
    {
        $this->id = 'browter_wcpay';
        $this->icon = apply_filters('woocommerce_offline_icon', BROWTER_WCP_PLUGIN_DIR_URI . '/assets/img/icon.png');
        $this->has_fields = false;
        $this->method_title = __('Browter WCPay', 'browter-wcpay');
        $this->method_description = __('Allows payments via Browter WCPay', 'browter-wcpay');
        $this->method_instructions = __('Pay via Browter WCPay', 'browter-wcpay');

        // Define user set variables
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->instructions = $this->get_option('instructions');

        // Load the settings
        $this->init_form_fields();
        $this->init_settings();

        // Add a hook to save the settings
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        // Payment listener/API hook

        add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
    }

    public function init_form_fields()
    {
        $this->form_fields = apply_filters('browter_wcpay_fields', [
            'enabled' => [
                'title' => __('Enable/Disable', 'browter-wcpay'),
                'type' => 'checkbox',
                'label' => __('Enable Browter WCPay', 'browter-wcpay'),
                'default' => 'yes',
            ],
            'title' => [
                'title' => __('Title', 'browter-wcpay'),
                'type' => 'text',
                'description' => __('Add a new title for the Browter WC Pay.', 'browter-wcpay'),
                'default' => __('Browter WCPay', 'browter-wcpay'),
                'desc_tip' => true,
            ],
            'description' => [
                'title' => __('Description', 'browter-wcpay'),
                'type' => 'textarea',
                'description' => __('Add a new description for the Browter WC Pay.', 'browter-wcpay'),
                'default' => __('Pay via Browter WCPay', 'browter-wcpay'),
                'desc_tip' => true,
            ],
            'instructions' => [
                'title' => __('Instructions', 'browter-wcpay'),
                'type' => 'textarea',
                'description' => __('Add instructions for the customer.', 'browter-wcpay'),
                'default' => __('Pay via Browter WCPay', 'browter-wcpay'),
                'desc_tip' => true,
            ],
        ]);
    }

    public function process_payment($order_id)
    {
        $order = wc_get_order($order_id);

        // Mark as on-hold (we're awaiting the payment)
        $order->update_status('pending-payment', __('Awaiting Browter WCPay payment', 'browter-wcpay'));

        // Payment with API
        $this->clear_payment_with_api($order);

        // Reduce stock levels
        $order->reduce_order_stock();

        // Remove cart
        WC()->cart->empty_cart();

        // Return thankyou redirect
        return [
            'result' => 'success',
            'redirect' => $this->get_return_url($order),
        ];
    }

    public function clear_payment_with_api($order)
    {
        // Payment with API
    }

    public function thankyou_page()
    {
        if ($this->instructions) {
            echo wpautop(wptexturize($this->instructions));
        }
    }
}
