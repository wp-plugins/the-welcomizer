<?php
/*
Plugin Name: The Welcomizer
Version: 2.0.1
Plugin URI: http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer
Description: This plugin allows you to animate your blog using jQuery effects. (100% AJAX) + .js/.css Includer.
Author: S&#233;bastien Laframboise
Author URI: http://www.sebastien-laframboise.com
License: GPL2
*/

/*  Copyright 2013  Sébastien Laframboise  (email:wordpress@sebastien-laframboise.com)

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
    require_once(dirname(__FILE__).'/includes/twiz.installation.class.php');
    require_once(dirname(__FILE__).'/includes/twiz.importexport.class.php');
    require_once(dirname(__FILE__).'/includes/twiz.library.class.php');
    require_once(dirname(__FILE__).'/includes/twiz.output.class.php');
    require_once(dirname(__FILE__).'/twiz-ajax.php');

    /******************
    * --- Functions ---
    *******************/
  
    
    // Create the necessary for the installation
    function twizInstall() {
        
        $TwizInstallation  = new TwizInstallation();
        $ok = $TwizInstallation->proceed();
    }
    
    // uninstall the plugin, drop table etc... 
    function twizUninstall() {
    
        $array_admin = get_option('twiz_admin');
        
        // Check admin option
        if( $array_admin[Twiz::KEY_DELETE_ALL] == '1' ) {
        
            $TwizInstallation  = new TwizInstallation();
            $ok = $TwizInstallation->uninstall();
        }
    }

    // Database version check 
    function twizUpdateDbCheck() {
    
        $TwizInstallation  = new TwizInstallation();
       
        $dbversion = get_option('twiz_db_version');

        if( $dbversion  != $TwizInstallation->dbVersion ){
        
            $ok = $TwizInstallation->proceed();
        }
    }
    
    // Add a menu link under theme menu.
    function twizAddLinkAdminMenu() {
    
        $array_admin = get_option('twiz_admin'); // get min access level

        add_theme_page(__('The Welcomizer', 'the-welcomizer'), __('The Welcomizer', 'the-welcomizer'), $array_admin[Twiz::KEY_MIN_ROLE_LEVEL], 'the-welcomizer', 'twizDisplayMainPage');
    }
        
    // Admin page
    function twizDisplayMainPage() {     
    
        $myTwiz = new Twiz();
        echo($myTwiz->twizIt());
    }
    
    // GenerateOutput code
    function twizGenerateOutput() {
    
        $myTwizOutput  = new TwizOutput();
        echo($myTwizOutput->generateOutput());
    }
    
    // do short code
    function twizReplaceShortCode( $shortcode = '' ){
    
        $myTwizOutput  = new TwizOutput($shortcode['id']);
        
        return( $myTwizOutput->generateOutput() );
    }
    
    function twizReplaceShortCodeDir( $shortcode = '' ){
    
         $upload_dir = wp_upload_dir();
         return $upload_dir['baseurl'];
    }    
    
    function twizCleanFeedShortcode( $content = '' ){

        return preg_replace('/\[twiz (.*)\]/i', '', $content);
    }
    
    function twizRemoveFeedShortcode(){
    
        add_shortcode('twiz_wp_upload_dir','twizReplaceShortCodeDir');
        add_filter('the_content', 'twizCleanFeedShortcode');
    }
    
    function twizInit(){
        
        $gstatus = get_option('twiz_global_status');
        $css_transform_included = false;
        
        if (( !is_admin() ) and ( $gstatus == '1' ) 
        and (!preg_match("/wp-admin/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]))
        and (!preg_match("/wp-login/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]))
        ) {
            
            $myTwizLibrary  = new TwizLibrary();
            
            $cookie_option = get_option('twiz_cookie_js_status');
            
            if( $myTwizLibrary->admin_option[Twiz::KEY_OUTPUT] !=  '' ){

                add_shortcode('twiz','twizReplaceShortCode');
                add_shortcode('twiz_wp_upload_dir','twizReplaceShortCodeDir');
                
                add_filter('widget_text', 'do_shortcode', 11);
                
                // add the Frontend generated code
                add_filter($myTwizLibrary->admin_option[Twiz::KEY_OUTPUT], 'twizGenerateOutput');
            }
            
            if( $myTwizLibrary->admin_option[Twiz::KEY_REGISTER_JQUERY] ==  '1' ){

                wp_deregister_script( 'jquery' );
                wp_register_script( 'jquery', includes_url().'js/jquery/jquery.js');
                wp_enqueue_script( 'jquery' );
            }
  
            if( $cookie_option == true ){
            
                wp_deregister_script( 'the-welcomizer-jquery-cookie' );
                wp_register_script( 'the-welcomizer-jquery-cookie',plugin_dir_url( __FILE__ ) .'includes/jquery/jquery-cookie/jquery.cookie.js');
                wp_enqueue_script( 'the-welcomizer-jquery-cookie' );
            }
                 
            if( $myTwizLibrary->admin_option[Twiz::KEY_REGISTER_JQUERY_ROTATE3DI] ==  '1' ){
            
                wp_deregister_script( 'the-welcomizer-css-transform' );
                wp_register_script( 'the-welcomizer-css-transform', plugin_dir_url( __FILE__ ) .'includes/jquery/css-transform/jquery-css-transform.js');
                wp_enqueue_script( 'the-welcomizer-css-transform' );
                
                wp_deregister_script( 'the-welcomizer-rotate3di' );
                wp_register_script( 'the-welcomizer-rotate3di', plugin_dir_url( __FILE__ ) .'includes/jquery/rotate3di/rotate3Di.js');
                wp_enqueue_script( 'the-welcomizer-rotate3di' );
                
                $css_transform_included = true;
            }
            
            if( $myTwizLibrary->admin_option[Twiz::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] ==  '1' ){   
                
                if( $css_transform_included == false ){
                
                    wp_deregister_script( 'the-welcomizer-css-transform' );
                    wp_register_script( 'the-welcomizer-css-transform', plugin_dir_url( __FILE__ ) .'includes/jquery/css-transform/jquery-css-transform.js');
                    wp_enqueue_script( 'the-welcomizer-css-transform' );
                } 
                
                wp_deregister_script( 'the-welcomizer-jquery-animate-css-rotate-scale' );
                wp_register_script( 'the-welcomizer-jquery-animate-css-rotate-scale', plugin_dir_url( __FILE__ ) .'includes/jquery/jquery-animate-css-rotate-scale/jquery-animate-css-rotate-scale.js');
                wp_enqueue_script( 'the-welcomizer-jquery-animate-css-rotate-scale' );

            }

            if( $myTwizLibrary->admin_option[Twiz::KEY_REGISTER_JQUERY_TRANSFORM] ==  '1' ){
     
                wp_deregister_script( 'the-welcomizer-transform-1' );
                wp_deregister_script( 'the-welcomizer-transform-2' );
                wp_deregister_script( 'the-welcomizer-transform-3' );
                wp_deregister_script( 'the-welcomizer-transform-4' );
                wp_deregister_script( 'the-welcomizer-transform-5' );
                wp_deregister_script( 'the-welcomizer-transform-6' );
                wp_deregister_script( 'the-welcomizer-transform-7' );
                wp_register_script( 'the-welcomizer-transform-1', plugin_dir_url( __FILE__ ) .'includes/jquery/transform/jquery.matrix.js');
                wp_register_script( 'the-welcomizer-transform-2', plugin_dir_url( __FILE__ ) .'includes/jquery/transform/jquery.transform.js');
                wp_register_script( 'the-welcomizer-transform-3', plugin_dir_url( __FILE__ ) .'includes/jquery/transform/jquery.matrix.calculations.js');
                wp_register_script( 'the-welcomizer-transform-4', plugin_dir_url( __FILE__ ) .'includes/jquery/transform/jquery.angle.js');
                wp_register_script( 'the-welcomizer-transform-5', plugin_dir_url( __FILE__ ) .'includes/jquery/transform/jquery.transform.animate.js');
                wp_register_script( 'the-welcomizer-transform-6', plugin_dir_url( __FILE__ ) .'includes/jquery/transform/jquery.matrix.functions.js');
                wp_register_script( 'the-welcomizer-transform-7', plugin_dir_url( __FILE__ ) .'includes/jquery/transform/jquery.transform.attributes.js');
                wp_enqueue_script( 'the-welcomizer-transform-1' );
                wp_enqueue_script( 'the-welcomizer-transform-2' );
                wp_enqueue_script( 'the-welcomizer-transform-3' );
                wp_enqueue_script( 'the-welcomizer-transform-4' );
                wp_enqueue_script( 'the-welcomizer-transform-5' );
                wp_enqueue_script( 'the-welcomizer-transform-6' );
                wp_enqueue_script( 'the-welcomizer-transform-7' );
            }

            if( $myTwizLibrary->admin_option[Twiz::KEY_REGISTER_JQUERY_TRANSIT] ==  '1' ){

                wp_deregister_script( 'the-welcomizer-transit' );
                wp_register_script( 'the-welcomizer-transit', plugin_dir_url( __FILE__ ) .'includes/jquery/transit/jquery.transit.min.js');
                wp_enqueue_script( 'the-welcomizer-transit' );
            }            
            
            if( $myTwizLibrary->admin_option[Twiz::KEY_REGISTER_JQUERY_EASING] ==  '1' ){

                wp_deregister_script( 'the-welcomizer-jquery-easing' );
                wp_register_script( 'the-welcomizer-jquery-easing', plugin_dir_url( __FILE__ ) .'includes/jquery/jquery-easing/jquery.easing-1.3.pack.js');
                wp_enqueue_script( 'the-welcomizer-jquery-easing' );
            }
            
            $siteurl = get_site_url().'/';

            if($myTwizLibrary->admin_option[Twiz::KEY_SORT_LIB_DIR] == 'reversed' ){
            
               $library_dir_array = array_reverse($myTwizLibrary->array_library_dir, true);
               
            }else{
            
               $library_dir_array = $myTwizLibrary->array_library_dir;
            }
 
            foreach( $library_dir_array as $directory ){
            
                foreach( $myTwizLibrary->array_library as $key => $value ){
                
                    if( ( $value[Twiz::F_STATUS] == "1" ) 
                    and ( $value[Twiz::KEY_DIRECTORY] == $directory )){
                    
                        $file = $directory.$value[Twiz::KEY_FILENAME];
                        
                        if( @file_exists( ABSPATH.$file ) ) {
                        
                            $fileinfo = pathinfo($siteurl.$file);
                            
                            switch($fileinfo['extension']){
                            
                                // Enqueue js files
                                case Twiz::EXT_JS: 
                                
                                    wp_deregister_script( 'the-welcomizer'.$key );
                                    wp_register_script( 'the-welcomizer'.$key, $siteurl.$file);
                                    wp_enqueue_script( 'the-welcomizer'.$key );
                                
                                    break;
                                    
                                // Enqueue css files
                                case Twiz::EXT_CSS:
                                
                                    wp_enqueue_style('twiz-css-'.str_replace('.'.Twiz::EXT_CSS, '', $value[Twiz::KEY_FILENAME] ), $siteurl.$file , __FILE__ );
                                    
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }
    
    function twizAdminEnqueueScripts(){
               
        $myTwiz  = new Twiz();
        
        // Drag&Drop
        wp_enqueue_script('twiz-jquery.ui.core.min', plugin_dir_url( __FILE__ ) .'includes/jquery/ui/jquery.ui.core.min.js');
        wp_enqueue_script('twiz-jquery.ui.widget.min', plugin_dir_url( __FILE__ ) .'includes/jquery/ui/jquery.ui.widget.min.js');
        wp_enqueue_script('twiz-jquery.ui.mouse.min', plugin_dir_url( __FILE__ ) .'includes/jquery/ui/jquery.ui.mouse.min.js');
        wp_enqueue_script('twiz-jquery.ui.draggable.min', plugin_dir_url( __FILE__ ) .'includes/jquery/ui/jquery.ui.draggable.min.js');
        wp_enqueue_script('twiz-jquery.ui.droppable.min', plugin_dir_url( __FILE__ ) .'includes/jquery/ui/jquery.ui.droppable.min.js');
                
        // Enqueue default stylesheet
        wp_enqueue_style('twiz-'.$myTwiz->cssVersion.'-a-'.Twiz::DEFAULT_SKIN, plugins_url(Twiz::SKIN_PATH.Twiz::DEFAULT_SKIN.'/twiz-style.css', __FILE__ ));
 
        // Current skin
        wp_enqueue_style('twiz-'.$myTwiz->cssVersion.'-a', plugins_url($myTwiz->skin[$myTwiz->userid].'/twiz-style.css', __FILE__ ));
        
        wp_enqueue_style('twiz-css-b', plugins_url('includes/import/client/fileuploader.css', __FILE__ ));

        // Admin Ajax script
        wp_enqueue_script( 'twiz-ajax-request', plugin_dir_url( __FILE__ ) . 'twiz-ajax.js.php', array( 'jquery' ) );
        
        // Fileuploader
        wp_enqueue_script( 'twiz-file-uploader', plugin_dir_url( __FILE__ ) . 'includes/import/client/fileuploader.js', array( 'jquery' ) );
    }
    
    /****************
    * --- Actions ---
    *****************/

    // register installation hooks and action
    register_activation_hook( __FILE__,  'twizInstall' );
    register_deactivation_hook( __FILE__,  'twizUninstall' );
     
    if( ( is_admin() ) 
    and (!preg_match("/plugins.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])) 
    and (!preg_match("/plugin-install.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])) 
    and (!preg_match("/update.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])) 
    and ((preg_match("/the-welcomizer/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]))
    or (preg_match("/admin-ajax.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]))
    or (preg_match("/php.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]))
    )){

        if(!isset($_POST['page'])) $_POST['page'] = '';

        $_POST_action = (!isset($_POST['action'])) ? '' : $_POST['action'];
        $_POST_twiz_action = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'];
        
        // Do WP Ajax
        if( ( $_POST_action == 'twiz_ajax_callback' ) and ( $_POST_twiz_action != '' ) ){
        
            // Set the multi-language file, english is the standard.
            load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );

            // Ajax callback
            add_action('wp_ajax_my_action', 'twiz_ajax_callback');
            do_action('wp_ajax_my_action', $_POST['action']);
            
        }else{
        
            if(preg_match("/the-welcomizer/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])){
                
                // Set the multi-language file, english is the standard.
                load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );
            
                // (for the admin dashboard)
                add_action('admin_enqueue_scripts', 'twizAdminEnqueueScripts');
            }
        }
    }
    
    // dbversion check 
    add_action('plugins_loaded', 'twizUpdateDbCheck');
    
    // feed  
    add_action('atom_head', 'twizRemoveFeedShortcode');
    add_action('rdf_header', 'twizRemoveFeedShortcode');
    add_action('rss_head', 'twizRemoveFeedShortcode');
    add_action('rss2_head', 'twizRemoveFeedShortcode');     
    
    // (for the frontend)
    add_action('wp_enqueue_scripts', 'twizInit');
    
    // Add the menu link
    add_action('admin_menu', 'twizAddLinkAdminMenu');
?>