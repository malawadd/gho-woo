<?PHP

class CpmwRestApi
{
    use CPMW_HELPER;
    public static $instanceApi;
    const Rest_End_Point = 'pay-with-metamask/v1';
    public static function getInstance()
    {
        if (!isset(self::$instanceApi)) {
            self::$instanceApi = new self();
        }
        return self::$instanceApi;
    }

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'registerRestApi'));
    }
    //Register all required rest roots
    public function registerRestApi()
    {
        $routes = [
            'verify-transaction' => 'verify_transaction_handler',
            'save-transaction' => 'save_transaction_handler',
            'selected-network' => 'get_selected_network',
            'cancel-order' => 'set_order_failed',
            'update-price' => 'update_price',
        ];

        foreach ($routes as $route => $callback) {
            register_rest_route(self::Rest_End_Point, $route, [
                'methods' => 'POST',
                'callback' => [$this, $callback],
                'permission_callback' => '__return_true',
            ]);
        }

    }
    // Get network on selected coin base
    public function update_price($request)
    {
        $data = $request->get_json_params();
        // Verify the nonce
        $nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : (isset($_SERVER['HTTP_X_WP_NONCE']) ? $_SERVER['HTTP_X_WP_NONCE'] : '');

        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            wp_send_json_error('Nonce verification failed');

        }
        $options = get_option('cpmw_settings');
        $type = $options['currency_conversion_api'];
        // Get selected network
        $get_network = $options["Chain_network"];
        $crypto_currency = ($get_network == '0x1' || $get_network == '0x5' || $get_network == '0xaa36a7') ?
        $options["eth_select_currency"] : $options["bnb_select_currency"];
        $total_price = !empty($data['total_amount']) ? sanitize_text_field($data['total_amount']) : '';
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
                if (isset($in_crypto['error'])) {
                    $error = $in_crypto['error'];
                    break; // Exit the loop if the API is restricted.
                }
                $enabledCurrency[$value] = array('symbol' => $value, 'price' => $in_crypto, 'url' => $image_url);
            }}
        return new WP_REST_Response($enabledCurrency);

    }

    // Get network on selected coin base
    public function get_selected_network($request)
    {
        $data = $request->get_json_params();
        // Verify the nonce
        $nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : (isset($_SERVER['HTTP_X_WP_NONCE']) ? $_SERVER['HTTP_X_WP_NONCE'] : '');

        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            wp_send_json_error('Nonce verification failed');

        }
        $symbol = !empty($data['symbol']) ? sanitize_text_field($data['symbol']) : '';

        $network_array = $this->cpmwp_get_active_networks_for_currency($symbol);
        return new WP_REST_Response($network_array);

    }
    // Canel or fail Order
    public static function set_order_failed($request)
    {
        $data = $request->get_json_params();
        // Verify the nonce
        $order_id = (int) sanitize_text_field($data['order_id']);
        $nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : (isset($_SERVER['HTTP_X_WP_NONCE']) ? $_SERVER['HTTP_X_WP_NONCE'] : '');

        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            wp_send_json_error('Nonce verification failed');

        }
        $canceled = sanitize_text_field($data['canceled']);
        $message = __('Payment has been failed due to user rejection', 'cpmw');

        $order = new WC_Order($order_id);
        $order->update_status('wc-failed', $message);
        $checkout_page = wc_get_checkout_url();

        $order->save_meta_data();
        return new WP_REST_Response(array('error' => $message, 'url' => $canceled ? $checkout_page : ''), 400);

    }

    // On successfull payment handle order status & save transaction in database
   
    // validate and save transation info inside transaction table and order
   

}

CpmwRestApi::getInstance();
