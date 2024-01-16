<?php
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Cpmw_metamask_list extends WP_List_Table
{


    function get_columns()
{
    $columns = array(    
        
         'id'=>'#',
         'order_id'=>__("Order Id", "cpmw"),
         'transaction_id'=>__("Transaction Id", "cpmw") ,         
         'sender'=> __("Sender", "cpmw"),
         'chain_name'=>__("Chain Name", "cpmw") ,
         'selected_currency'=> __("Payment Currency", "cpmw"),
         'crypto_price'=>__("Payment Price", "cpmw"),
         'order_price'=>__("Order Price", "cpmw"),
         'status'=> __("Status", "cpmw"),
         'last_updated'=>__("Date", "cpmw")
    );
    return $columns;
}

function prepare_items()
{
           
        global $wpdb, $_wp_column_headers;
    //    echo '<h1>Coins List</h1><form method="post">';
            $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $query = 'SELECT * FROM ' . $wpdb->base_prefix .'cpmw_transaction';
       /*  $this->cmc_process_bulk_action();
        $this->cmc_perform_row_actions(); */
       // delete_option('cpmw-coins-search');

        // search keyword
       
        $user_search_keyword = isset($_REQUEST['s']) ? wp_unslash(trim($_REQUEST['s'])) : '';
        $processing=isset($_REQUEST['cpmw_processing']) ? wp_unslash(trim($_REQUEST['cpmw_processing'])) : '';
        $canceled = isset($_REQUEST['cpmw_canceled']) ? wp_unslash(trim($_REQUEST['cpmw_canceled'])) : '';
        $completed = isset($_REQUEST['cpmw_completed']) ? wp_unslash(trim($_REQUEST['cpmw_completed'])) : '';
        $onhold = isset($_REQUEST['cpmw_on_hold']) ? wp_unslash(trim($_REQUEST['cpmw_on_hold'])) : '';


     /*      if( !empty($user_search_keyword) ){
            
            update_option('cpmw-coins-search', $user_search_keyword );
        }else if( false != get_option('cpmw-coins-search', false) && empty($user_search_keyword) ){
            $user_search_keyword = get_option('cpmw-coins-search', '');
        } */	 

	 	 if (isset($user_search_keyword) && !empty($user_search_keyword) ) {
            $query .= ' where ( order_id LIKE "%' . $user_search_keyword . '%" OR chain_name LIKE "%' . $user_search_keyword . '%" OR selected_currency LIKE "%' . $user_search_keyword . '%") ';
        }   
        elseif (isset($processing) && !empty($processing) ) {
           $query .= ' where ( status LIKE "%' . $processing . '%" ) ';

        }
         elseif (isset($canceled) && !empty($canceled) ) {
           $query .= ' where ( status LIKE "%' . $canceled . '%" ) ';

        }
         elseif (isset($completed) && !empty($completed) ) {
           $query .= ' where ( status LIKE "%' . $completed . '%" ) ';

        }
         elseif (isset($onhold ) && !empty($onhold ) ) {
           $query .= ' where ( status LIKE "%' . $onhold  . '%" ) ';

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
