<?php
/*
Plugin Name: The Welcomizer
Version: 1.3.2.4
Plugin URI: http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer
Description: Currently activated on the Homepage, this plugin allows you to easily add 'Smart' moves and jQuery effects to virtually any HTML element that has an attribute ID.(e.g. div id="sidebar"). Enjoy!
Author: Sebastien Laframboise
Author URI: http://www.sebastien-laframboise.com
License: GPL2
*/

/*  Copyright 2011  Sebastien Laframboise  (email:wordpress@sebastien-laframboise.com)

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

    /********************
    * --- The Classes ---
    *********************/
    
    require_once(dirname(__FILE__).'/includes/twiz-shd.class.php'); 
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
    
    /****************
    * --- Actions ---
    *****************/
    
    /* register installation hooks */
    register_activation_hook( __FILE__,  'twizInstall' );
    register_deactivation_hook( __FILE__,  'twizUninstall' );    

    /* Set the multi-language file, english is the standard. */
    load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' ); 
    
    /* Enqueue style in admin */
    if(is_admin()){
        wp_enqueue_style('twiz-style', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'includes/twiz-style.css');
    }
    
    /* Add the menu link */
    add_action('admin_menu', 'twizMenu');
    
    /* add the Frontend generated code */    
    add_filter('wp_footer', 'twizFrontEnd');
?>