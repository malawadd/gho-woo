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
            
           

        ),
    ));
    

   
   

endif;
