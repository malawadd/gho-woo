<?php
if (!defined('ABSPATH')) {
    exit;
}

class WC_cpmw_Gateway extends WC_Payment_Gateway
{
    use CPMW_HELPER;

    public function __construct()
    {
        $optionss = get_option('cpmw_settings');
        // Initialize payment gateway properties
        $this->id = 'cpmw';
        $this->icon = CPMW_URL . '/assets/images/metamask.png';
        $this->has_fields = true;
        $this->method_title = __('MetaMask Pay', 'cpmw');
        $this->method_description = __('GhoWoo For WooCommerce', 'cpmw');

        // Initialize form fields and settings
        $this->init_form_fields();
        $this->init_settings();
        $this->enabled = $this->get_option('enabled');
        $this->title = !empty($this->get_option('title')) ? $this->get_option('title') : 'MetaMask Pay';
        $this->description = $this->get_option('custom_description');     
        $this->order_button_text =(isset($optionss['place_order_button']) && !empty($optionss['place_order_button'])) ? $optionss['place_order_button']: __('Pay With Crypto Wallets', 'cpmwp');
        // Add action hooks
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_receipt_' . $this->id, array($this, 'pay_order_page'));
        //add_action('woocommerce_order_status_changed', array($this, 'check_payment_gateway_status'), 10, 3);

        // Display admin notice if currency is not supported
        if (!$this->is_valid_for_use()) {
            $this->enabled = 'no';
            add_action('admin_notices', function() {
                ?>
                <style>div#message.updated {
                    display: none;
                }</style>
                <div class="notice notice-error is-dismissible">
                    <p><?php _e('Current WooCommerce store currency is not supported by GhoWoo For WooCommerce', 'cpmw'); ?></p>
                </div>
                <?php
            });
        }

        // Set supported features
        $this->supports = array(
            'products',
            'subscriptions',
        );
    }
    public function check_payment_gateway_status($order_id, $old_status, $new_status)
    {
        $payment_gateway_id = $this->id; // Replace with your payment gateway ID


        $order = wc_get_order($order_id);
        $payment_method = $order->get_payment_method();

        // Check if the payment gateway matches and the order status changed to the required status
        if ($payment_method === $payment_gateway_id) {
            $db=new CPMW_database();
            $db->update_fields_value($order_id,'status',$new_status);
            // Do something here, like sending an email notification
        }
    }

    public function is_valid_for_use()
    {
        if (in_array(get_woocommerce_currency(), apply_filters('cpmw_supported_currencies', $this->cpmw_supported_currency()))) {
            return true;
        }
        return false;
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => 'Enable/Disable',
                'label' => 'Enable MetaMask Pay',
                'type' => 'checkbox',
                'description' => '',
                'default' => 'yes',
            ),
            'title' => array(
                'title' => __('Title', 'cpmw'),
                'type' => 'text',
                'description' => __('This controls the title for the payment method the customer sees during checkout.', 'cpmw'),
                'default' => __('Pay With Cryptocurrency','cpmw'),
                'desc_tip' => false,
            ),
            'custom_description' => array(
                'title' => 'Description',
                'type' => 'text',
                'description' => 'Add custom description for checkout payment page',                
            ),
        );
    }



   

 
}
