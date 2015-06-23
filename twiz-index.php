<?php
/*
Plugin Name: The Welcomizer
Version: 2.8.1
Plugin URI: http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer
Description: This plugin allows you to quickly animate your blog.
Author: S&#233;bastien Laframboise
Author URI: http://www.sebastien-laframboise.com
License: GPL2
*/

/*  Copyright 2015  Sébastien Laframboise  (email:sebastien.laframboise@gmail.com)

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
    
    // Copy this line into wp-config.php to force and bypass the 'Display plugin environment variables' setting.
    // define('TWIZ_FORCE_VARDUMP', true); 
    //
    if ( !defined('TWIZ_FORCE_VARDUMP') ){
        
        define('TWIZ_FORCE_VARDUMP', false);
    }
    
    // Copy this line into wp-config.php to log activation error 'wp-content/uploads/the-welcomizer-activation-error.log'
    // define('TWIZ_LOG_ACTIVATION', true); 
    //
    if ( !defined('TWIZ_LOG_ACTIVATION') ){
        
        define('TWIZ_LOG_ACTIVATION', false);
    }
    
    // Copy this line into wp-config.php to log deactivation error 'wp-content/uploads/the-welcomizer-deactivation-error.log'
    // define('TWIZ_LOG_DEACTIVATION', true); 
    //
    if ( !defined('TWIZ_LOG_DEACTIVATION') ){
        
        define('TWIZ_LOG_DEACTIVATION', false);
    }    
    
    // for multisite
    if ( is_multisite() ){
        
        if(!function_exists('wp_get_current_user'))
        require_once(ABSPATH . 'wp-includes/pluggable.php'); 
        wp_cookie_constants();
    }
    
    /***********************
    * --- The Twiz Class ---
    ***********************/
             
    require_once(dirname(__FILE__).'/includes/twiz.class.php');  
    require_once(dirname(__FILE__).'/includes/twiz.installation.class.php');
    require_once(dirname(__FILE__).'/includes/twiz.importexport.class.php');
    require_once(dirname(__FILE__).'/includes/twiz.library.class.php');
    require_once(dirname(__FILE__).'/includes/twiz.output.class.php');
    require_once(dirname(__FILE__).'/includes/twiz.view.class.php');
    require_once(dirname(__FILE__).'/twiz-ajax.php');

    /******************
    * --- Functions ---
    *******************/
    
    // Create the necessary for the installation
    if(!function_exists('twizInstall')){
    function twizInstall( $network_activation = ''){ 
    
        global $wpdb;
        
        if( is_multisite() ){ // v3.2 version supported (no params $network_activation)
        
            $network=isset($_SERVER['SCRIPT_NAME'])?$_SERVER['SCRIPT_NAME']:"";
            $activate=isset($_GET['action'])?$_GET['action']:"";
            $isNetwork=($network=='/wp-admin/network/plugins.php')?true:false;
            $isActivation=($activate=='deactivate')?false:true;
            
            if($isNetwork and $isActivation){
                
                $network_activation = '1';
            }
        }
        
        if( $network_activation == '1' ){

            $TwizInstallation = new TwizInstallation();
            $blog_array = $TwizInstallation->getAllBlogIds();

            foreach ( $blog_array as $key => $blog){

                switch_to_blog($blog['id']);
                $code = update_option('twiz_network_activated', '1'); // Switch On the networkfeature for all
                $ok = $TwizInstallation->install( $network_activation );               
                $result = activate_plugin('the-welcomizer/twiz-index.php');                    
                restore_current_blog();
            }
        
        }else{

                $TwizInstallation = new TwizInstallation();
                $ok = $TwizInstallation->install( $network_activation );                    
        }            
        
        // LOG ACTIVATION ERRORS
        if( TWIZ_LOG_ACTIVATION == true ){        
        
            file_put_contents(ABSPATH. 'wp-content/uploads/the-welcomizer-activation-error.log', ob_get_contents());
        }
    }}
    
 
    // uninstall the plugin, drop table etc... 
    if(!function_exists('twizUninstall')){
    function twizUninstall( $network_deactivation = '' ){

        if( is_multisite() ){ // v3.2 version supported (no params $network_activation)
        
            $network=isset($_SERVER['SCRIPT_NAME'])?$_SERVER['SCRIPT_NAME']:"";
            $activate=isset($_GET['action'])?$_GET['action']:"";
            $isNetwork=($network=='/wp-admin/network/plugins.php')?true:false;
            $isActivation=($activate=='deactivate')?true:false;
            
            if($isNetwork and $isActivation){
                
                $network_deactivation = '1';
            }
        }
        
        $is_last_network = '';
    
        if( $network_deactivation  == '1' ){
            
            $TwizInstallation  = new TwizInstallation();
            $blog_array = $TwizInstallation->getAllBlogIds();
            
            $count_blog = count($blog_array);
            
            $i = 1;

            foreach ( $blog_array as $key => $blog){
            
                if( $count_blog == $i ){ $is_last_network = '1'; } // erase network settings last.
                
                switch_to_blog($blog['id']);
                $result = deactivate_plugins('the-welcomizer/twiz-index.php', true); 
                $ok = $TwizInstallation->uninstall( $network_deactivation, $is_last_network );
                restore_current_blog();
                $i++;
            }

        }else{

            $TwizInstallation  = new TwizInstallation();
            $ok = $TwizInstallation->uninstall( $network_deactivation );
        }
        
        // LOG DEACTIVATION ERRORS
        if( TWIZ_LOG_DEACTIVATION == true ){
        
            file_put_contents(ABSPATH. 'wp-content/uploads/the-welcomizer-deactivation-error.log', ob_get_contents());
        }
    }}
    // Database version check 
    if(!function_exists('twizUpdateDbCheck')){     
    function twizUpdateDbCheck( $network_activation = '' ){
    
        $TwizInstallation  = new TwizInstallation();
        
        if( ( !is_multisite() ) or ( $TwizInstallation->override_network_settings ==  '1') ){

            $dbversion = get_option('twiz_db_version');
            
        }else{

            $dbversion = get_site_option('twiz_db_version');
        }        

        if( $dbversion  != $TwizInstallation->dbVersion ){
        
            $ok = $TwizInstallation->install( $network_activation );
        }
    }}
    // Admin page
    if(!function_exists('twizDisplayMainPage')){     
    function twizDisplayMainPage(){     
    
        $myTwiz = new Twiz();
        
        echo($myTwiz->twizIt());
    }}
    // GenerateOutput code
    if(!function_exists('twizGenerateOutput')){
    function twizGenerateOutput(){
    
        $myTwizOutput  = new TwizOutput();
        
        echo($myTwizOutput->generateOutput());
    }}
    // do shortcode
    if(!function_exists('twizReplaceShortCode')){
    function twizReplaceShortCode( $shortcode = '' ){
    
        $myTwizOutput  = new TwizOutput($shortcode['id']);
        
        return( $myTwizOutput->generateOutput() );
    }}
    if(!function_exists('twizReplaceShortCodeDir')){
    function twizReplaceShortCodeDir( $shortcode = '' ){
    
         $upload_dir = wp_upload_dir();
         
         return $upload_dir['baseurl'];
    }}
    if(!function_exists('twizCleanFeedShortcodeID')){
    function twizCleanFeedShortcodeID( $content = '' ){

        return preg_replace('/\[twiz (.*)\]/i', '', $content);
    }}
    if(!function_exists('twizRemoveFeedShortcode')){
    function twizRemoveFeedShortcode(){
    
        add_shortcode('twiz_wp_upload_dir','twizReplaceShortCodeDir');
        add_filter('the_content', 'twizCleanFeedShortcodeID');
    }}
    if(!function_exists('twizInit')){
    function twizInit(){
    
        $myTwiz = new Twiz();
               
        $css_transform_included = false;

        if (( !is_admin() ) and ( $myTwiz->global_status == '1' ) 
        and (!preg_match("/\/wp-admin/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]))
        and (!preg_match("/\/wp-login/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]))
        ){
            $myTwizLibrary  = new TwizLibrary();
            if( ( !is_multisite() ) or ( $myTwiz->override_network_settings == '1' ) ){

                $cookie_option = get_option('twiz_cookie_js_status');
                
            }else{
                
                $cookie_option = get_site_option('twiz_cookie_js_status');
            }             
            
            if( $myTwiz->admin_option[Twiz::KEY_OUTPUT] !=  '' ){
       
                add_shortcode('twiz','twizReplaceShortCode');
                add_shortcode('twiz_wp_upload_dir','twizReplaceShortCodeDir');
                
                add_filter('widget_text', 'do_shortcode', 11);
                
                // add the Frontend generated code
                add_filter($myTwiz->admin_option[Twiz::KEY_OUTPUT], 'twizGenerateOutput');
            }
            
            if( $myTwiz->admin_option[Twiz::KEY_REGISTER_JQUERY] ==  '1' ){

                wp_deregister_script( 'jquery' );
                wp_register_script( 'jquery', includes_url().'js/jquery/jquery.js');
                wp_enqueue_script( 'jquery' );
            }
  
            if( $cookie_option == true ){
            
                wp_deregister_script( 'the-welcomizer-jquery-cookie' );
                wp_register_script( 'the-welcomizer-jquery-cookie',plugin_dir_url( __FILE__ ) .'includes/jquery/jquery-cookie/jquery.cookie.js');
                wp_enqueue_script( 'the-welcomizer-jquery-cookie' );
            }
                 
            if( $myTwiz->admin_option[Twiz::KEY_REGISTER_JQUERY_ROTATE3DI] ==  '1' ){
            
                wp_deregister_script( 'the-welcomizer-css-transform' );
                wp_register_script( 'the-welcomizer-css-transform', plugin_dir_url( __FILE__ ) .'includes/jquery/css-transform/jquery-css-transform.js');
                wp_enqueue_script( 'the-welcomizer-css-transform' );
                
                wp_deregister_script( 'the-welcomizer-rotate3di' );
                wp_register_script( 'the-welcomizer-rotate3di', plugin_dir_url( __FILE__ ) .'includes/jquery/rotate3di/rotate3Di.js');
                wp_enqueue_script( 'the-welcomizer-rotate3di' );
                
                $css_transform_included = true;
            }
            
            if( $myTwiz->admin_option[Twiz::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] ==  '1' ){   
                
                if( $css_transform_included == false ){
                
                    wp_deregister_script( 'the-welcomizer-css-transform' );
                    wp_register_script( 'the-welcomizer-css-transform', plugin_dir_url( __FILE__ ) .'includes/jquery/css-transform/jquery-css-transform.js');
                    wp_enqueue_script( 'the-welcomizer-css-transform' );
                } 
                
                wp_deregister_script( 'the-welcomizer-jquery-animate-css-rotate-scale' );
                wp_register_script( 'the-welcomizer-jquery-animate-css-rotate-scale', plugin_dir_url( __FILE__ ) .'includes/jquery/jquery-animate-css-rotate-scale/jquery-animate-css-rotate-scale.js');
                wp_enqueue_script( 'the-welcomizer-jquery-animate-css-rotate-scale' );

            }

            if( $myTwiz->admin_option[Twiz::KEY_REGISTER_JQUERY_TRANSFORM] ==  '1' ){
     
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

            if( $myTwiz->admin_option[Twiz::KEY_REGISTER_JQUERY_TRANSIT] ==  '1' ){

                wp_deregister_script( 'the-welcomizer-transit' );
                wp_register_script( 'the-welcomizer-transit', plugin_dir_url( __FILE__ ) .'includes/jquery/transit/jquery.transit.min.js');
                wp_enqueue_script( 'the-welcomizer-transit' );
            }            
            
            if( $myTwiz->admin_option[Twiz::KEY_REGISTER_JQUERY_EASING] ==  '1' ){

                wp_deregister_script( 'the-welcomizer-jquery-easing' );
                wp_register_script( 'the-welcomizer-jquery-easing', plugin_dir_url( __FILE__ ) .'includes/jquery/jquery-easing/jquery.easing-1.3.pack.js');
                wp_enqueue_script( 'the-welcomizer-jquery-easing' );
            }
            
            $siteurl = get_site_url().'/';

            if($myTwiz->admin_option[Twiz::KEY_SORT_LIB_DIR] == 'reversed' ){
            
               $library_dir_array = array_reverse($myTwizLibrary->array_library_dir, true);
               
            }else{
            
               $library_dir_array = $myTwizLibrary->array_library_dir;
            }
 
            foreach( $library_dir_array as $directory ){
            
                foreach( $myTwizLibrary->array_library as $key => $value ){
                
                    if( ( $value[Twiz::F_STATUS] == "1" ) 
                    and ( $value[Twiz::KEY_DIRECTORY] == $directory )
                     ){ //and ( $myTwiz->privacy_question_answered == true )
                    
                        $file = $directory.$value[Twiz::KEY_FILENAME];
                        
                        if( @file_exists( ABSPATH.$file ) ){
                        
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
    }}
    if(!function_exists('twizAdminEnqueueScripts')){
    function twizAdminEnqueueScripts( $hook ){

        if ( $hook != 'appearance_page_the-welcomizer' ){ return; }       
         
        $myTwiz  = new Twiz();
       
        if( $myTwiz->privacy_question_answered == true ){
        
            // Drag&Drop
            wp_enqueue_script('twiz-jquery.ui.core.min', plugin_dir_url( __FILE__ ) .'includes/jquery/ui/core.min.js');
            wp_enqueue_script('twiz-jquery.ui.widget.min', plugin_dir_url( __FILE__ ) .'includes/jquery/ui/widget.min.js');
            wp_enqueue_script('twiz-jquery.ui.mouse.min', plugin_dir_url( __FILE__ ) .'includes/jquery/ui/mouse.min.js');
            wp_enqueue_script('twiz-jquery.ui.draggable.min', plugin_dir_url( __FILE__ ) .'includes/jquery/ui/draggable.min.js');
            wp_enqueue_script('twiz-jquery.ui.droppable.min', plugin_dir_url( __FILE__ ) .'includes/jquery/ui/droppable.min.js');
                    
            // Fileuploader
            wp_enqueue_script( 'twiz-file-uploader', plugin_dir_url( __FILE__ ) . 'includes/import/client/fileuploader.js', array( 'jquery' ) );            
            wp_enqueue_style('twiz-css-b', plugins_url('includes/import/client/fileuploader.css', __FILE__ ));
            
            // Admin Ajax script
            wp_enqueue_script( 'twiz-ajax-request', plugin_dir_url( __FILE__ ) . 'twiz-ajax.js.php', array( 'jquery' ) );
        }
        
        // Enqueue default stylesheet
        wp_enqueue_style('twiz-'.$myTwiz->cssVersion.'-a-'.Twiz::DEFAULT_SKIN, plugins_url(Twiz::SKIN_PATH.Twiz::DEFAULT_SKIN.'/twiz-style.css', __FILE__ ));
         // Enqueue genericons stylesheet
        wp_enqueue_style('twiz-genericons-'.$myTwiz->cssVersion, plugins_url('includes/icons/style.css', __FILE__ ));
 
        // Current skin
        wp_enqueue_style('twiz-'.$myTwiz->cssVersion.'-a', plugins_url($myTwiz->skin[$myTwiz->user_id].'/twiz-style.css', __FILE__ ));
    }}
    if(!function_exists('twizAdminTinymceScript')){
    function twizAdminTinymceScript( $plugin_array ){

        // Tinymce shortcode Script
        $plugin_array['thewelcomizer'] = plugin_dir_url( __FILE__ ) . 'includes/jquery/tinymce-shortcode/twiz-tinymce-shortcode.js.php';
        
        return $plugin_array;
    }}
    if(!function_exists('twizPromotePluginImageLink')){
    function twizPromotePluginImageLink( ){
    
        $myTwiz  = new Twiz();
        $imagelink = $myTwiz->getPromotePluginImageLink();
        
        echo ( $imagelink );
    }}
    if(!function_exists('twizAddPluginActionLink')){
    function twizAddPluginActionLink( $links = array() ){	

        $new_link = '<a href="'.admin_url().'themes.php?page=the-welcomizer">'.__('Settings').'</a>';
        array_unshift($links, $new_link);

        return $links;
    }}
    // Add a menu link under theme menu.
    if(!function_exists('twizAddLinkAdminMenu')){ 
    function twizAddLinkAdminMenu(){
        
        $myTwiz = new Twiz();
        
        // Set the multi-language file, english is the standard.
        load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );
        
        add_theme_page(__('The Welcomizer', 'the-welcomizer'), __('The Welcomizer', 'the-welcomizer'), $myTwiz->admin_option[Twiz::KEY_MIN_ROLE_LEVEL], 'the-welcomizer', 'twizDisplayMainPage');
    }}
    // Add a menu link to admin bar.
    if(!function_exists('twizAddLinkAdminToolbarMenu')){     
    function twizAddLinkAdminToolbarMenu( $admin_bar ){
    
        $myTwiz = new Twiz();

        if( current_user_can( $myTwiz->admin_option[Twiz::KEY_MIN_ROLE_LEVEL] ) ){
            
            // Set the multi-language file, english is the standard.
            load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );
        
            $admin_bar->add_menu( array('id'    => 'the-welcomizer'
                                       ,'title' => __('The Welcomizer', 'the-welcomizer')
                                       ,'href'  => admin_url().'themes.php?page=the-welcomizer'
                                       ));
        }
    }}
    /****************
    * --- Actions ---
    *****************/

    
    // register installation hooks and action
    register_activation_hook( __FILE__,  'twizInstall');
    register_deactivation_hook( __FILE__,  'twizUninstall');

    if( ( is_admin() ) 
    and (!preg_match("/\/wp-admin\/customize.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])) 
    and (!preg_match("/\/wp-admin\/plugins.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])) 
    and (!preg_match("/\/wp-admin\/plugin-install.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])) 
    and (!preg_match("/\/wp-admin\/update.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])) 
    and ((preg_match("/\/wp-admin\/themes.php\?page=the-welcomizer/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]))
     or (preg_match("/\/wp-admin\/admin-ajax.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]))
     or (preg_match("/\/wp-content\/plugins\/the-welcomizer\/includes\/import\/server\/php.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]))
    )){
        $twiz_wp_param_page =( !isset($_GET['page']) ) ? '' : $_GET['page'];
        
        if(!isset($_POST['page'])) $_POST['page'] = '';

        $TWIZ_POST_action = ( !isset($_POST['action']) ) ? '' : $_POST['action'];
        $TWIZ_POST_twiz_action = ( !isset($_POST['twiz_action']) ) ? '' : $_POST['twiz_action'];
        
        // Do WP Ajax
        if( ( $TWIZ_POST_action == 'twiz_ajax_callback' ) and ( $TWIZ_POST_twiz_action != '' ) ){
        
            // Set the multi-language file, english is the standard.
            load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );

            // Ajax callback
            add_action('wp_ajax_my_action', 'twiz_ajax_callback');
            do_action('wp_ajax_my_action', $_POST['action']);
            
        }else if( ( $twiz_wp_param_page == 'the-welcomizer') 
        and (preg_match("/\/wp-admin\/themes.php\?page=the-welcomizer/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] ) ) ){

            // Set the multi-language file, english is the standard.
            load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );
        
            // (for the admin dashboard) + hook
            add_action('admin_enqueue_scripts', 'twizAdminEnqueueScripts');
        }
    }
    
    if( ( is_admin() ) 
    and ((preg_match("/\/wp-admin\/post.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])) 
    or (preg_match("/\/wp-admin\/post-new.php/i", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])) ) ){
    
        // (for the tinymce shortcode) -> [twiz_wp_upload_dir] -> url to image.
		add_filter('mce_external_plugins',  'twizAdminTinymceScript');    
                
    }
    
    // dbversion check 
    add_action('plugins_loaded', 'twizUpdateDbCheck');
    
    if( is_admin() ){
                
        // Add the menu links
        add_action('admin_menu', 'twizAddLinkAdminMenu');
        add_action('admin_bar_menu', 'twizAddLinkAdminToolbarMenu', 100);
        
        // Add Action link 
        add_filter( 'plugin_action_links_'. plugin_basename(__FILE__), 'twizAddPluginActionLink', 10, 1);
        
    }else{

        // feed  
        add_action('atom_head', 'twizRemoveFeedShortcode');
        add_action('rdf_header', 'twizRemoveFeedShortcode');
        add_action('rss_head', 'twizRemoveFeedShortcode');
        add_action('rss2_head', 'twizRemoveFeedShortcode');     
        
        // (for the frontend)
        add_action('wp_enqueue_scripts', 'twizInit');
        add_action('wp_footer', 'twizPromotePluginImageLink');
    }
?>