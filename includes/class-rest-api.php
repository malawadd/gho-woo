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
    
    // Get network on selected coin base


    // Get network on selected coin base
   
    // Canel or fail Order
    

    // On successfull payment handle order status & save transaction in database
   
    // validate and save transation info inside transaction table and order
   

}

CpmwRestApi::getInstance();
