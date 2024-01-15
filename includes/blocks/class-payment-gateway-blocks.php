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


       
    }
}
