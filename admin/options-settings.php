<?php defined('ABSPATH') || exit;

if (class_exists('CSF')):

    $prefix = "cpmw_settings";

    CSF::createOptions($prefix, array(
        'framework_title' => esc_html__('Settings', 'cpmw'),
        'menu_title' => false,
        'menu_slug' => "cpmw-metamask-settings",
        'menu_capability' => 'manage_options',
        'menu_type' => 'submenu',
        'menu_parent' => 'woocommerce',
        'menu_position' => 103,
        'menu_hidden' => true,
        'nav' => 'inline',
        'show_bar_menu' => false,
        'show_sub_menu' => false,
        'show_reset_section' => false,
        'show_reset_all' => false,
        'theme' => 'light',

    ));

    CSF::createSection($prefix, array(

        'id' => 'general_options',
        'title' => esc_html__('General Options', 'cpmw'),
        'icon' => 'fa fa-cog',
        'fields' => array(

            array(
                'id' => 'user_wallet',
                'title' => __('Payment Address <span style="color:red">(Required)</span>', 'cpmw'),
                'type' => 'text',
                'placeholder' => '0x1dCXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
                'validate' => 'csf_validate_required',
                'help' => esc_html__('Enter your default wallet address to receive crypto payments.', 'cpmw'),
                'desc' => 'Enter your default wallet address to receive crypto payments.<br>
											',
            ),
            array(
                'id' => 'currency_conversion_api',
                'title' => esc_html__('Crypto Price API', 'cpmw'),
                'type' => 'select',
                'options' => array(
                    'cryptocompare' => __('CryptoCompare', 'cpmw'),
                    'openexchangerates' => __('Binance', 'cpmw'),
                ),
                'default' => 'openexchangerates',
                'desc' => 'It will convert product price from fiat currency to cryptocurrency in real time. Match your token symbol with CryptoCompare or Binance listed tokens for accurate pricing.<br>
											',
            ),
            array(
                'id' => 'crypto_compare_key',
                'title' => __('CryptoCompare API Key <span style="color:red">(Required)</span>', 'cpmw'),
                'type' => 'text',
                'dependency' => array('currency_conversion_api', '==', 'cryptocompare'),
                'desc' => 'Check -<a href=" https://paywithcryptocurrency.net/get-cryptocompare-free-api-key/" target="_blank">How to retrieve CryptoCompare free API key?</a>',
            ),          
            array(
                'id' => 'openexchangerates_key',
                'title' => __('Openexchangerates API Key', 'cpmw'),
                'type' => 'text',   
                'dependency' => array('currency_conversion_api', '==', 'openexchangerates'),       
                'desc' => 'Please provide the API key if you are utilizing a store currency other than USD. Check -<a href="https://paywithcryptocurrency.net/get-openexchangerates-free-api-key/" target="_blank">How to retrieve openexchangerates free api key?</a>',

            ),
            array(
                'id' => 'Chain_network',
                'title' => esc_html__('Select Network/Chain', 'cpmw'),
                'type' => 'select',
                'options' => array(
                    '0x1' => __('Ethereum Mainnet (ERC20)', 'cpmw'),
                    '0xaa36a7' => __('Ethereum Sepolia (Testnet)', 'cpmw'),
                    
                    
                ),
                'desc' => '',
                'default' => '0xaa36a7',

            ),
            array(
                'id' => 'eth_select_currency',
                'title' => __('Select Crypto Currency <span style="color:red">(Required )</span>', 'cpmw'),
                'type' => 'select',
                'validate' => 'csf_validate_required',
                'placeholder' => 'Select Crypto currency',
                'options' => array(
                    'ETH' => __('Ethereum', 'cpmw'),
                    'GHO' => __('GHO', 'cpmw'),
                ),
                'chosen' => true,
                'multiple' => true,
                'settings' => array('width' => '50%'),
                'dependency' => array('Chain_network', 'any', '0x1,0x5,0xaa36a7'),
                'desc' => '',
                'default' => 'GHO',

            ),
            array(
                'id' => 'bnb_select_currency',
                'title' => __('Select Crypto Currency <span style="color:red">(Required )</span>', 'cpmw'),
                'type' => 'select',
                'placeholder' => 'Select Crypto Currency',
                'validate' => 'csf_validate_required',
                'options' => array(
                    'BNB' => __('Binance Coin', 'cpmw'),
                    'BUSD' => __('BUSD', 'cpmw'),
                ),
                'chosen' => true,
                'multiple' => true,
                'settings' => array('width' => '50%'),
                'dependency' => array('Chain_network', 'any', '0x38,0x61'),
                'desc' => '',
                'default' => 'BNB',
            ),
            array(
                'id' => 'enable_refund',
                'title' => esc_html__('Enable Refund', 'cpmw'),
                'type' => 'switcher',
                'text_on' => 'Enable',
                'text_off' => 'Disable',
                'text_width' => 80,
                'desc' => '',
                'help' => esc_html__('Enable refund option', 'cpmw'),
                'default' => true,
            ),

            array(
                'id' => 'payment_status',
                'title' => esc_html__('Payment Success: Order Status', 'cpmw'),
                'type' => 'select',
                'options' => apply_filters(
                    'cpmwp_settings_order_statuses',
                    array(
                        'default' => __('Woocommerce Default Status', 'cpmw'),
                        'on-hold' => __('On Hold', 'cpmw'),
                        'processing' => __('Processing', 'cpmw'),
                        'completed' => __('Completed', 'cpmw'),
                    )
                ),
                'desc' => __('Order status upon successful cryptocurrency payment.', 'cpmw'),
                'default' => 'default',
            ),

            array(
                'id' => 'redirect_page',
                'title' => esc_html__('Payment Success: Redirect Page', 'cpmw'),
                'type' => 'text',
                'placeholder' => 'https://ethglobal.com/events/lfgho',
                'desc' => 'Enter custom url to redirect or leave blank to update order status on same page.',
            ),
            array(
                'id' => 'dynamic_messages',
                'title' => esc_html__('Customize Text Display', 'cpmw'),
                'type' => 'select',
                'options' => array(
                    'confirm_msg' => __('Payment Confirmation (Popup)', 'cpmw'),
                    'payment_process_msg' => __('Payment Processing (Popup)', 'cpmw'),
                    'rejected_message' => __('Payment Rejected (Popup)', 'cpmw'),
                    'payment_msg' => __('Payment Completed (Popup)', 'cpmw'),
                    'place_order_button' => __('Place Order Button (Checkout page)', 'cpmw'),
                    'select_a_currency' => __('Select Coin (Checkout page)', 'cpmw'),
                ),

                'desc' => __('Customize the text displayed by the plugin on the frontend.', 'cpmw'),
                'default' => 'place_order_button',
            ),
            array(
                'id' => 'confirm_msg',
                'title' => esc_html__('Payment Confirmation (Popup)', 'cpmw'),
                'type' => 'text',
                'dependency' => array('dynamic_messages', '==', 'confirm_msg'),
                'desc' => 'You can change it to your preferred text or leave it blank to keep the default text.',
                'placeholder' => __('Confirm Payment Inside Your Wallet!', 'cpmw'),
            ),
            array(
                'id' => 'payment_process_msg',
                'title' => esc_html__('Payment Processing (Popup)', 'cpmw'),
                'type' => 'text',
                'dependency' => array('dynamic_messages', '==', 'payment_process_msg'),
                'desc' => 'Custom message to show  while processing payment via blockchain.',
                'placeholder' => __('Payment in process.', 'cpmw'),
            ),
            array(
                'id' => 'rejected_message',
                'title' => esc_html__('Payment Rejected (Popup)', 'cpmw'),
                'type' => 'text',
                'dependency' => array('dynamic_messages', '==', 'rejected_message'),
                'desc' => 'Custom message to show  if you rejected payment via metamask.',
                'placeholder' => __('Transaction rejected. ', 'cpmw'),
            ),
            array(
                'id' => 'payment_msg',
                'title' => esc_html__('Payment Completed (Popup)', 'cpmw'),
                'type' => 'text',
                'dependency' => array('dynamic_messages', '==', 'payment_msg'),
                'placeholder' => __('Payment completed successfully.', 'cpmw'),
                'desc' => 'Custom message to show  if  payment confirm  by blockchain.',

            ),
            array(
                'id' => 'place_order_button',
                'title' => esc_html__('Place Order Button (Checkout page)', 'cpmw'),
                'type' => 'text',
                'dependency' => array('dynamic_messages', '==', 'place_order_button'),
                'placeholder' => __('Pay With gho-woo', 'cpmw'),
                'desc' => 'Please specify a name for the "Place Order" button on the checkout page.',

            ),
            array(
                'id' => 'select_a_currency',
                'title' => esc_html__('Select Coin (Checkout page)', 'cpmw'),
                'type' => 'text',
                'dependency' => array('dynamic_messages', '==', 'select_a_currency'),
                'placeholder' => __('Please Select a Currency', 'cpmw'),
                'desc' => 'Please provide a name for the label that selects the currency on the checkout page.',

            ),
            array(
                'id' => 'enable_debug_log',
                'title' => esc_html__('Debug mode ', 'cpmw'),
                'type' => 'switcher',
                'text_on' => 'Enable',
                'text_off' => 'Disable',
                'text_width' => 80,
                'desc' => 'When enabled, payment error logs will be saved to WooCommerce > Status > <a href="' . esc_url(get_admin_url(null, "admin.php?page=wc-status&tab=logs")) . '">Logs.</a>',
                'help' => esc_html__('Enable debug mode', 'cpmwp'),
                'default' => true,
            ),

        ),
    ));
    

    CSF::createSection($prefix, array(
        'title' => 'Free Test Tokens',
        'icon' => 'fas fa-rocket',
        'fields' => array(
            array(
                'type' => 'heading',
                'content' => 'Get Free Test Tokens to Test Payment via Metamask on Test Networks/Chains.',
            ),
            array(
                'type' => 'subheading',
                'content' => ' ETH Test Token For Sepolia Network: <a href="https://sepoliafaucet.com/" target="_blank">https://sepoliafaucet.com</a>',
            ),
            array(
                'type' => 'subheading',
                'content' => ' GHO Test Token For Sepolia Network: <a href="https://gho.aave.com/" target="_blank">https://gho.aave.com</a>',
            ),
          

        ),

    ));
   

endif;
