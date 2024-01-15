<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * cpmw Payments Blocks integration
 *
 * @since 1.0.3
 */
final class WC_cpmw_Gateway_Blocks_Support extends AbstractPaymentMethodType
{
    use CPMW_HELPER;

    /**
     * The gateway instance.
     *
     * @var WC_cpmw_Gateway
     */
    private $gateway;

    /**
     * Payment method name/id/slug.
     *
     * @var string
     */
    protected $name = 'cpmw';

    /**
     * Initializes the payment method type.
     */
    public function initialize()
    {
        $this->settings = get_option('woocommerce_cpmw_settings', []);
        $gateways = WC()->payment_gateways->payment_gateways();
        $this->gateway = $gateways[$this->name];
    }

    /**
     * Returns if this payment method should be active. If false, the scripts will not be enqueued.
     *
     * @return boolean
     */
    public function is_active()
    {

        return $this->gateway->is_available();
    }

    /**
     * Returns an array of scripts/handles to be registered for this payment method.
     *
     * @return array
     */
    public function get_payment_method_script_handles()
    {
        $filePaths = glob(CPMW_PATH . '/assets/pay-with-metamask/build/block' . '/*.php');
        $fileName = pathinfo($filePaths[0], PATHINFO_FILENAME);
        $jsbuildUrl = str_replace('.asset', '', $fileName);
        $script_path = 'assets/pay-with-metamask/build/block/' . $jsbuildUrl . '.js';
        $script_asset_path = CPMW_PATH . 'assets/pay-with-metamask/build/block/' . $jsbuildUrl . '.asset.php';
        $script_asset = file_exists($script_asset_path)
        ? require $script_asset_path
        : array(
            'dependencies' => array(),
            'version' => CPMW_VERSION,
        );
        $script_url = CPMW_URL . $script_path;

        wp_register_script(
            'wc-cpmw-payments-blocks',
            $script_url,
            $script_asset['dependencies'],
            $script_asset['version'],
            true
        );
        wp_enqueue_style('cpmw-checkout', CPMW_URL . 'assets/css/checkout.css', null, CPMW_VERSION);
        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations('wc-cpmw-payments-blocks', 'woocommerce-gateway-cpmw', CPMW_PATH . 'languages/');
        }

        return ['wc-cpmw-payments-blocks'];
    }

    /**
     * Returns an array of key=>value pairs of data made available to the payment methods script.
     *
     * @return array
     */
    public function get_payment_method_data()
    {
 // Get plugin options
$options = get_option('cpmw_settings');

// Enqueue necessary styles
wp_enqueue_style('cpmw_checkout', CPMW_URL . 'assets/css/checkout.css', array(), CPMW_VERSION);

// Get user wallet settings
$user_wallet = $options['user_wallet'];

// Get currency options
$bnb_currency = $options['bnb_select_currency'];
$eth_currency = $options['eth_select_currency'];

// Get currency conversion API options
$compare_key = $options['crypto_compare_key'];
$openex_key = $options['openexchangerates_key'];
$select_currecny = $options['currency_conversion_api'];
$const_msg = $this->cpmw_const_messages();

// Get supported network names
$network_name = $this->cpmw_supported_networks();

// Get selected network
$get_network = $options["Chain_network"];

// Get constant messages



// Determine crypto currency based on network
$crypto_currency = ($get_network == '0x1' || $get_network == '0x5' || $get_network == '0xaa36a7') ?
$options["eth_select_currency"] : $options["bnb_select_currency"];
$select_currency_lbl = (isset($options['select_a_currency']) && !empty($options['select_a_currency'])) ? $options['select_a_currency'] : __('Please Select a Currency', 'cpmwp');
// Get type and total price
$type = $options['currency_conversion_api'];
$logo_url =CPMW_URL . 'assets/images/metamask.png';
$total_price =  isset(WC()->cart->subtotal)?WC()->cart->subtotal:null;
$enabledCurrency = array();
$error = '';
if (is_array($crypto_currency)) {
    foreach ($crypto_currency as $key => $value) {
        // Get coin logo image URL
        $image_url = $this->cpmw_get_coin_logo($value);
        // Perform price conversion
        $in_crypto = $this->cpmw_price_conversion($total_price, $value, $type);       
        if (isset($in_crypto['restricted'])) {
            $error = $in_crypto['restricted'];
            break; // Exit the loop if the API is restricted.
        }
        if(isset($in_crypto['error'])) {
            $error = $in_crypto['error'];
            break; // Exit the loop if the API is restricted.
        }
        $enabledCurrency[$value] = array('symbol' => $value, 'price' => $in_crypto, 'url' => $image_url);
    }}

        return [
            'title' =>!empty($this->get_setting('title')) ? $this->get_setting('title') : __('Pay With Cryptocurrency', 'cpmw'),
            'description' => $this->get_setting('custom_description'),
            'supports' => array_filter($this->gateway->supports, [$this->gateway, 'supports']),
            'total_price' => $total_price,
            'error'=>$error,
            'api_type' => $type,
            'logo_url' => $logo_url,
            'decimalchainId' => isset($get_network) ? hexdec($get_network) : false,
            'active_network' => isset($get_network) ? $get_network : false,
            'nonce' => wp_create_nonce('wp_rest'),
            'restUrl' => get_rest_url() . 'pay-with-metamask/v1/',
            'currency_lbl' => $select_currency_lbl,
            'const_msg' => $const_msg,
            'networkName' => $network_name[$get_network],
            'enabledCurrency' => $enabledCurrency,
            'order_button_text' => (isset($options['place_order_button']) && !empty($options['place_order_button'])) ? $options['place_order_button'] : __('Pay With Crypto Wallets', 'cpmw'),

        ];
    }
}
