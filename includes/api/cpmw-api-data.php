<?php
if (!defined('ABSPATH')) {
    exit();
}
if (!class_exists('CPMW_API_DATA')) {
    class CPMW_API_DATA
    {

        /**
         * CRYPTOCOMPARE_TRANSIENT used for fiat conversion API transient time.
         */
        const CRYPTOCOMPARE_TRANSIENT = 10 * MINUTE_IN_SECONDS;

        /**
         * OPENEXCHANGERATE_TRANSIENT used for fiat conversion API  transient time.
         */

        const OPENEXCHANGERATE_TRANSIENT = 120 * MINUTE_IN_SECONDS;

        /**
         * BINANCE_TRANSIENT used for fiat conversion API  transient time.
         */
        const BINANCE_TRANSIENT = 10 * MINUTE_IN_SECONDS;

        /**
         * CMC_API_ENDPOINT
         *
         * Holds the URL of the coins data API.
         *
         * @access public
         *
         */
        const CRYPTOCOMPARE_API = 'https://min-api.cryptocompare.com/data/price?fsym=';

        /**
         * COINGECKO_API_ENDPOINT
         *
         * Holds the URL of the coingecko API.
         *
         * @access public
         *
         */
        const BINANCE_API_COM = 'https://api.binance.com/api/v3/ticker/24hr?symbol=';
        const BINANCE_API_US = 'https://api.binance.us/api/v3/ticker/24hr?symbol=';

        /**
         * OPENEXCHANGERATE_API_ENDPOINT
         *
         * Holds the URL of the openexchangerates API.
         *
         * @access public
         *
         */

        const OPENEXCHANGERATE_API_ENDPOINT = 'https://openexchangerates.org/api/latest.json?app_id=';

        public function __construct()
        {
            // self::CMC_API_ENDPOINT = 'https://apiv3.coinexchangeprice.com/v3/';
        }

        public static function cpmw_crypto_compare_api($fiat, $crypto_token)
        {
            $settings_obj = get_option('cpmw_settings');
            $api = !empty($settings_obj['crypto_compare_key']) ? $settings_obj['crypto_compare_key'] : "";
            $transient = get_transient("cpmw_currency" . $crypto_token);
            if (empty($transient) || $transient === "") {
                $response = wp_remote_post(self::CRYPTOCOMPARE_API . $fiat . '&tsyms=' . $crypto_token . '&api_key=' . $api . '', array('timeout' => 120, 'sslverify' => true));
                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                    return $error_message;
                }
                $body = wp_remote_retrieve_body($response);
                $data_body = json_decode($body);
                set_transient("cpmw_currency" . $crypto_token, $data_body, self::CRYPTOCOMPARE_TRANSIENT);
                return $data_body;
            } else {
                return $transient;
            }
        }

        
        

    }
}
