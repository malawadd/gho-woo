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


    // Get network on selected coin base
   
    // Canel or fail Order
    

    // On successfull payment handle order status & save transaction in database
   
    // validate and save transation info inside transaction table and order
   

}

CpmwRestApi::getInstance();
