<?php
/*
Plugin Name: The Welcomizer
Version: 1.3.5.9
Plugin URI: http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer
Description: This plugin allows you to animate your blog using jQuery effects. (100% AJAX) + .js/.css Includer.
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
    require_once(dirname(__FILE__).'/includes/twiz.library.class.php');     
    
    /******************
    * --- Functions ---
    *******************/
    
    /* Create the necessary for the installation */
    function twizInstall() {
        
        $myTwiz  = new Twiz();
        $ok = $myTwiz->install();
    }
    
    /* uninstall the plugin, drop table etc... */
    function twizUninstall() {
    
        $myTwiz  = new Twiz();
        $ok = $myTwiz->uninstall();
    }

    /* Database version check  */
    function twizUpdateDbCheck() {
    
        $myTwiz  = new Twiz();
       
        $dbversion = get_option('twiz_db_version');

        if( $dbversion  != $myTwiz->dbVersion ){
        
            $ok = $myTwiz->install();
        }
    }
    
    /* Add a menu link under theme menu. */
    function twizAddLinkAdminMenu() {
    
        add_theme_page(__('The Welcomizer', 'the-welcomizer'), __('The Welcomizer', 'the-welcomizer'), 6, 'the-welcomizer', 'twizDisplayMainPage');
    }
        
    /* Admin page */
    function twizDisplayMainPage() {     
    
        $myTwiz = new Twiz();
        echo($myTwiz->twizIt());
    }
    
    /* Frontend code */
    function twizDisplayFrontEnd() {
    
        $myTwiz  = new Twiz();
        echo($myTwiz->getFrontEnd());
    }

    function twizEnqueueLibrary(){
    
        $gstatus = get_option('twiz_global_status');
     
        if (( !is_admin() ) and ( $gstatus == '1' )) {
        
            $myTwizLibrary  = new TwizLibrary();
             
            foreach( $myTwizLibrary->array_library as $key => $value ){
            
                if( $value[Twiz::F_STATUS] == "1" ){
                
                    $file = Twiz::IMPORT_PATH . $value[Twiz::KEY_FILENAME];
                    
                    if( @file_exists( WP_CONTENT_DIR.$file ) ) {
                    
                        $fileinfo = pathinfo(WP_CONTENT_URL.$file);
                        
                        switch($fileinfo['extension']){
                        
                             /* Enqueue js files */
                            case Twiz::EXT_JS: 
                            
                                wp_deregister_script( 'the-welcomizer'.$key );
                                wp_register_script( 'the-welcomizer'.$key, WP_CONTENT_URL.$file);
                                wp_enqueue_script( 'the-welcomizer'.$key );
                            
                                break;
                                
                            /* Enqueue css files */
                            case Twiz::EXT_CSS:
                            
                                wp_enqueue_style('twiz-css-'.str_replace('.'.Twiz::EXT_CSS, '', $value[Twiz::KEY_FILENAME] ), WP_CONTENT_URL.$file , __FILE__ );
                                
                                break;
                        }
                    }
                }
            }
        }
    }
    
    /****************
    * --- Actions ---
    *****************/

    /* register installation hooks and action */
    register_activation_hook( __FILE__,  'twizInstall' );
    register_deactivation_hook( __FILE__,  'twizUninstall' );    

    /* Set the multi-language file, english is the standard. */
    load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' ); 
    
    /* Enqueue style in admin */
    if( is_admin() ){
    
        wp_enqueue_style('twiz-css-a', plugins_url('includes/twiz-style.css', __FILE__ ));
        wp_enqueue_style('twiz-css-b', plugins_url('includes/import/client/fileuploader.css', __FILE__ ));
    }
    
    /* dbversion check */
    add_action('plugins_loaded', 'twizUpdateDbCheck');
    
    /* Add init action */
    add_action('init', 'twizEnqueueLibrary');
    
    /* Add the menu link */
    add_action('admin_menu', 'twizAddLinkAdminMenu');
    
    /* add the Frontend generated code */    
    add_filter('wp_footer', 'twizDisplayFrontEnd');
?>