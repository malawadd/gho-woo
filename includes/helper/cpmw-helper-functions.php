<?php
if (!defined('ABSPATH')) {
    exit();
}

trait CPMW_HELPER
{
    public function __construct()
    {

    }
    // Generate a dynamic secret key for hash_hmac
    protected function cpmw_get_secret_key()
    {
        if (get_option('cpmwp_secret_key') == false) {
            update_option('cpmwp_secret_key', wp_generate_password(4, true, true));
        }
        return get_option('cpmwp_secret_key');
    }

    //Price conversion API start

    protected function cpmw_price_conversion($total, $crypto, $type)
    {
        global $woocommerce;
        $currency = get_woocommerce_currency();
        $settings_obj = get_option('cpmw_settings');

        $api = !empty($settings_obj['crypto_compare_key']) ? $settings_obj['crypto_compare_key'] : '';

        if ($type == "cryptocompare") {
            if (empty($api)) {
                return "no_key";
            }

            $current_price = CPMW_API_DATA::cpmw_crypto_compare_api($currency, $crypto);
            $current_price_array = (array) $current_price;

            return isset($current_price_array[$crypto]) ? $this->cpmw_format_number(($current_price_array[$crypto]) * $total) : null;
        } else {
            $price_list = CPMW_API_DATA::cpmw_openexchangerates_api();

            if (isset($price_list->error)&& $currency != 'USD') {
                return array('error' => $price_list->description);
            }

            $price_array =  ($currency != 'USD') ? (array) $price_list->rates : '';
            $current_rate = ($currency != 'USD') ? $price_array[$currency] : 1;
          
            if ($crypto == "USDT"||$crypto == "BUSD") {
                $current_price_USDT = CPMW_API_DATA::cpmw_crypto_compare_api($currency, $crypto);
                $current_price_array_USDT = (array) $current_price_USDT;

                return isset($current_price_array_USDT[$crypto]) ? $this->cpmw_format_number(($current_price_array_USDT[$crypto]) * $total) : null;
            }  elseif ($crypto == "GHO") { // Check if the crypto is GHO
                $fixedRate = 0.980754; // The fixed rate for GHO
                $in_crypto_GHO = $total / $fixedRate; // Calculate the total in GHO
                return $this->cpmw_format_number($in_crypto_GHO); // Return the formatted amount
            }else  {
                $binance_price = CPMW_API_DATA::cpmw_binance_price_api('' . $crypto . 'USDT');
                
                if (isset($binance_price->lastPrice)) {
                    $lastprice = $binance_price->lastPrice;                  
                    return !empty($current_rate) ? $this->cpmw_format_number(($total / $current_rate) / $lastprice) : null;
                } elseif (current_user_can('manage_options')) {
                    return isset($binance_price->msg) ? array('restricted' => __("Binance API Is Restricted In Your region, Please Switch With CryptoCompare API.", "cpmw")) : 'error';
                }
            }
        }
    }

    protected function cpmw_format_number($n)
    {
        if (is_numeric($n)) {
            if ($n >= 25) {
                return $formatted = number_format($n, 2, '.', ',');
            } else if ($n >= 0.50 && $n < 25) {
                return $formatted = number_format($n, 3, '.', ',');
            } else if ($n >= 0.01 && $n < 0.50) {
                return $formatted = number_format($n, 4, '.', ',');
            } else if ($n >= 0.001 && $n < 0.01) {
                return $formatted = number_format($n, 5, '.', ',');
            } else if ($n >= 0.0001 && $n < 0.001) {
                return $formatted = number_format($n, 6, '.', ',');
            } else {
                return $formatted = number_format($n, 8, '.', ',');
            }
        }
    }

//Price conversion API end here

    protected function cpmw_supported_currency()
    {
        $oe_currency = array("AED", "AFN", "ALL", "AMD", "ANG", "AOA", "ARS", "AUD", "AWG", "AZN", "BAM", "BBD", "BDT", "BGN", "BHD", "BIF", "BMD", "BND", "BOB", "BRL", "BSD", "BTC", "BTN", "BWP", "BYN", "BZD", "CAD", "CDF", "CHF", "CLF", "CLP", "CNH", "CNY", "COP", "CRC", "CUC", "CUP", "CVE", "CZK", "DJF", "DKK", "DOP", "DZD", "EGP", "ERN", "ETB", "EUR", "FJD", "FKP", "GBP", "GEL", "GGP", "GHS", "GIP", "GMD", "GNF", "GTQ", "GYD", "HKD", "HNL", "HRK", "HTG", "HUF", "IDR", "ILS", "IMP", "INR", "IQD", "IRR", "ISK", "JEP", "JMD", "JOD", "JPY", "KES", "KGS", "KHR", "KMF", "KPW", "KRW", "KWD", "KYD", "KZT", "LAK", "LBP", "LKR", "LRD", "LSL", "LYD", "MAD", "MDL", "MGA", "MKD", "MMK", "MNT", "MOP", "MRO", "MRU", "MUR", "MVR", "MWK", "MXN", "MYR", "MZN", "NAD", "NGN", "NIO", "NOK", "NPR", "NZD", "OMR", "PAB", "PEN", "PGK", "PHP", "PKR", "PLN", "PYG", "QAR", "RON", "RSD", "RUB", "RWF", "SAR", "SBD", "SCR", "SDG", "SEK", "SGD", "SHP", "SLL", "SOS", "SRD", "SSP", "STD", "STN", "SVC", "SYP", "SZL", "THB", "TJS", "TMT", "TND", "TOP", "TRY", "TTD", "TWD", "TZS", "UAH", "UGX", "USD", "UYU", "UZS", "VES", "VND", "VUV", "WST", "XAF", "XAG", "XAU", "XCD", "XDR", "XOF", "XPD", "XPF", "XPT", "YER", "ZAR", "ZMW", "ZWL");
        return $oe_currency;
    }

//Add custom tokens for networks
   
   



  

    

}
