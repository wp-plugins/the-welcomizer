<?php
/*
Plugin Name: The Welcomizer
Version: 1.3.4.8
Plugin URI: http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer
Description: Welcomize your visitors also on categories and pages. This plugin allows you to add 'Smart' moves and jQuery effects to virtually any HTML element that has an attribute ID. The Welcomizer has Spirit!
Author: S&#233;bastien Laframboise
Author URI: http://www.sebastien-laframboise.com
License: GPL2
*/

/*  Copyright 2011  Sébastien Laframboise  (email:wordpress@sebastien-laframboise.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

    /***********************
    * --- The Twiz Class ---
    ***********************/
    
    require_once(dirname(__FILE__).'/includes/twiz.class.php'); 
    
    /******************
    * --- Functions ---
    *******************/
    
    /* Create the necessary for the installation */
    function twizInstall() {
        
        $myTwiz  = new Twiz();
        $myTwiz->install();
    }
    
    /* uninstall the plugin, drop table etc... */
    function twizUninstall() {
    
        $myTwiz  = new Twiz();
        $myTwiz->uninstall();
    }

    /* Add a menu link under theme menu. */
    function twizMenu() {
    
        add_theme_page(__('The Welcomizer', 'the-welcomizer'), __('The Welcomizer', 'the-welcomizer'), 6, 'the-welcomizer', 'twizMainPage');
    }
        
    /* Admin page */
    function twizMainPage() {     
    
        $myTwiz = new Twiz();
        echo($myTwiz->twizIt());
    }
    
    /* Frontend code */
    function twizFrontEnd() {
    
        $myTwiz  = new Twiz();
        echo($myTwiz->getFrontEnd());
    }

    function twizEnqueueJS(){
     
        if (( !is_admin() ) and ( get_option('twiz_global_status') == '1' )) {
        
            $myTwiz  = new Twiz();
            
            $library = get_option('twiz_library');
            
            if( !is_array($library )){ $library = array();}
             
            foreach( $library as $key => $value ){
            
                if( $value[Twiz::F_STATUS] == "1" ){
                
                    $file = Twiz::IMPORT_PATH . $value[Twiz::KEY_FILENAME];
                    
                    if( file_exists( WP_CONTENT_DIR.$file ) ) {
                    
                        /* Enqueue js files, maybe css in the future */
                        wp_deregister_script( 'the-welcomizer'.$key );
                        wp_register_script( 'the-welcomizer'.$key, WP_CONTENT_URL.$file);
                        wp_enqueue_script( 'the-welcomizer'.$key );
                    }
                }
            }
        }
    }
    
    /****************
    * --- Actions ---
    *****************/

    /* register installation hooks */
    register_activation_hook( __FILE__,  'twizInstall' );
    register_deactivation_hook( __FILE__,  'twizUninstall' );    

    /* Set the multi-language file, english is the standard. */
    load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' ); 
    
    /* Enqueue style in admin */
    if( is_admin() ){
    
        wp_enqueue_style('twiz-style-a', plugins_url('includes/twiz-style.css', __FILE__ ));
        wp_enqueue_style('twiz-style-b', plugins_url('includes/import/client/fileuploader.css', __FILE__ ));
    }
    
    /* Add init action */
    add_action('init', 'twizEnqueueJS');
    
    /* Add the menu link */
    add_action('admin_menu', 'twizMenu');
    
    /* add the Frontend generated code */    
    add_filter('wp_footer', 'twizFrontEnd');
?>