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

    public function prepare_items()
    {

        global $wpdb, $_wp_column_headers;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $query = 'SELECT * FROM ' . $wpdb->base_prefix . 'cpmw_transaction';
        $user_search_keyword = isset($_REQUEST['s']) ? wp_unslash(trim($_REQUEST['s'])) : '';
        $status= isset($_REQUEST['payment_status']) ? wp_unslash(trim($_REQUEST['payment_status'])) : '';
        if (isset($user_search_keyword) && !empty($user_search_keyword)) {
            $query .= ' where ( order_id LIKE "%' . $user_search_keyword . '%" OR chain_name LIKE "%' . $user_search_keyword . '%" OR selected_currency LIKE "%' . $user_search_keyword . '%" OR transaction_id LIKE "%' . $user_search_keyword . '%") ';
        } elseif (isset($status) && !empty($status)) {
            $query .= ' where ( status LIKE "' . $status . '" ) ';

        }
        // Ordering parameters
        $orderby = !empty($_REQUEST["orderby"]) ? esc_sql($_REQUEST["orderby"]) : 'last_updated';
        $order = !empty($_REQUEST["order"]) ? esc_sql($_REQUEST["order"]) : 'DESC';
        if (!empty($orderby) & !empty($order)) {
            $query .= ' ORDER BY ' . $orderby . ' ' . $order;
        }

        // Pagination parameters
        $totalitems = $wpdb->query($query);
        $perpage = 10;
        if (!is_numeric($perpage) || empty($perpage)) {
            $perpage = 10;
        }

        $paged = !empty($_REQUEST["paged"]) ? esc_sql($_REQUEST["paged"]) : false;

        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }
        $totalpages = ceil($totalitems / $perpage);

        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query .= ' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        // Register the pagination & build link
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        )
        );

        // Get feedback data from database
        $this->items = $wpdb->get_results($query);

    }

    

    

}
