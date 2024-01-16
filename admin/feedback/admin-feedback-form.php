<?php
namespace CPMW\feedback;

class Cpmw_feedback{

	private $plugin_url = CPMW_URL;
	private $plugin_version = CPMW_VERSION;
	private $plugin_name = 'GhoWoo For WooCommerce';
	private $plugin_slug = 'cpmw';
	private $feedback_url = '';

    /*
    |-----------------------------------------------------------------|
    |   Use this constructor to fire all actions and filters          |
    |-----------------------------------------------------------------|
    */
    public function __construct(){
        add_action('admin_enqueue_scripts', array( $this, 'enqueue_feedback_scripts') );

        add_action('admin_head', array( $this, 'show_deactivate_feedback_popup') );
        add_action('wp_ajax_'.$this->plugin_slug.'_submit_deactivation_response', array($this, 'submit_deactivation_response' ));
    }

    /*
    |-----------------------------------------------------------------|
    |   Enqueue all scripts and styles to required page only          |
    |-----------------------------------------------------------------|
    */
    function enqueue_feedback_scripts(){
        $screen = get_current_screen();
        if( isset( $screen ) && $screen->id == 'plugins' ){
            wp_enqueue_script(__NAMESPACE__.'feedback-script', $this->plugin_url .'admin/feedback/js/admin-feedback.js',array('jquery'),$this->plugin_version );
            wp_enqueue_style('cool-plugins-feedback-style', $this->plugin_url .'admin/feedback/css/admin-feedback.css',null,$this->plugin_version );
        }
    }

  
    

    
}
new Cpmw_feedback;