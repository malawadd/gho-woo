<?php
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Cpmw_metamask_list extends WP_List_Table
{

    public function get_columns()
    {
        $columns = array(
            'order_id' => __("Order Id", "cpmwp"),
            'transaction_id' => __("Transaction ID", "cpmwp"),
            'sender' => __("Sender", "cpmwp"),
            'chain_name' => __("Network", "cpmwp"),
            'selected_currency' => __("Coin", "cpmwp"),
            'crypto_price' => __(" Crypto Price", "cpmwp"),
            'order_price' => __("Fiat Price", "cpmwp"),
            'status' => __("Payment Confirmation", "cpmwp"),
            'order_status' => __("Order Status", "cpmwp"),
            'last_updated' => __("Date", "cpmw"),
        );
        return $columns;
    }

   

    

    

}
