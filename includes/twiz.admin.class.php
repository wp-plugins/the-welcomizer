<?php
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

class TwizAdmin extends Twiz{
   
    // Output array 
    private $array_output = array(parent::OUTPUT_HEADER    
                                 ,parent::OUTPUT_FOOTER  
                                 );
    
    // role equivalence array 
    private $array_role_conversion; // see __construct()
    private $array_admin_only;

    // Number of posts to display 
    private $array_number_posts;
    
    // Mutisite message
    private $MULTISITE_SETTING_MESSAGE;
                                       
    function __construct(){
    
        parent::__construct();

        // http://codex.wordpress.org/Roles_and_Capabilities
        $this->array_role_conversion = array('manage_options'       => __('Administrator', 'the-welcomizer') 
                                            ,'moderate_comments'    => __('Editor', 'the-welcomizer')
                                            ,'edit_published_posts' => __('Author', 'the-welcomizer')
                                            ,'edit_posts'           => __('Contributor', 'the-welcomizer') 
                                            ,'read'                 => __('Subscriber', 'the-welcomizer')
                                            );       

        $this->array_admin_only = array('manage_options' => __('Administrator', 'the-welcomizer') );
        
        if( $this->override_network_settings == '1' ) {
            
            $this->MULTISITE_SETTING_MESSAGE = '<span class="twiz-red">'.__('Settings and Sections aren\'t shared through the network.', 'the-welcomizer').'</span>';
            
        }else{
            
            $this->MULTISITE_SETTING_MESSAGE = '<span class="twiz-green">'.__('Settings and Sections are shared through the network.', 'the-welcomizer').'</span>';
            
        }
        // Number of posts to display 
        $this->array_number_posts = array ('1'   => '1'
                                          ,'25'   => '25'
                                          ,'50'   => '50'
                                          ,'75'   => '75'
                                          ,'100'  => '100'
                                          ,'125'  => '125'
                                          ,'250'  => '250'
                                          ,'500'  => '500'
                                          ,'750'  => '750'
                                          ,'1000' => '1000'
                                          ,'-1'   => __('All', 'the-welcomizer')
                                          );
        
        $this->loadAdmin();
    }

    function getHtmlAdmin(){
    
        $html = $this->getHtmlAdminForm();
        
        return $html;
    }
    
    private function getHtmlAdminForm(){
        
        // hide element 
        $jquery = '<script>
//<![CDATA[
jQuery(document).ready(function($) {
    $("#twiz_add_menu").fadeOut("fast");
    $("#twiz_library_upload").fadeOut("fast");
    $("#twiz_import").fadeOut("fast");
    $("#twiz_export").fadeOut("fast");
    $("#twiz_right_panel").fadeOut("fast");
});
//]]>
</script>';

        $html = '<table class="twiz-table-form" cellspacing="0" cellpadding="0">';

        if( ( $this->network_activated == '1' ) and( is_super_admin() ) ){

            // Override network settings message
            $html .= '<tr class="twiz-row-color-1"><td class="twiz-admin-form-td-left" colspan="2">'.$this->MULTISITE_SETTING_MESSAGE.'</td></tr>';  
            
            $html .= '<tr class="twiz-row-color-2"><td class="twiz-admin-form-td-left">'.__('Override network settings for this site', 'the-welcomizer').': ';
            $html .= '<div class="twiz-float-right">'.$this->getHTMLOverRideNetworkSettings().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left">'.$this->getHTMLRestoreUpdateSettings().'</td></tr>';
            
            $html .= '<tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr>';
        }
        
        // default jquery registration
        $html .= '<tr><td colspan="2" class="twiz-admin-form-td-left"><b>'.__('Basic Setting', 'the-welcomizer').'</b></td></tr>';
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Include the jQuery default library on the front-end', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryRegister().'</div></td><td class="twiz-form-td-right"><span id="twiz_admin_save_img_box_1" class="twiz-loading-gif-save"></span> <a id="twiz_cancel_1">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_admin_save" id="twiz_admin_save_1" class="button-primary" value="'.__('Save', 'the-welcomizer').'" /></td></tr>';
        
        
        $html .= '<tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr>';
        
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin0'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin0'] = '';
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin1'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin1'] = '';
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin2'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin2'] = '';
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin3'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin3'] = '';
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin4'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin4'] = '';
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin5'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin5'] = '';
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin6'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin6'] = '';
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin7'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin7'] = '';
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin8'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin8'] = '';
        // Put new toggle section here..
        
        if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin0'] == '1' ){
        
            $hide = '';
            $toggleimg = 'twiz-minus';
            $boldclass = ' twiz-bold';
            
        }else{

            $hide = ' twiz-display-none';
            $toggleimg = 'twiz-plus';
            $boldclass = '';
        }
 
        $html .= '<tr><td colspan="2"><div class="twiz-relative"><div id="twiz_admin_img_twizadmin0" class="twiz-toggle-admin twiz-toggle-img-admin '.$toggleimg.'"></div></div><a id="twiz_admin_e_a_twizadmin0" class="twiz-toggle-admin'.$boldclass.'">'.__('Built-in jQuery Packages', 'the-welcomizer').'</strong></td></tr>';
        
        $html .= '<tr class="twizadmin0'.$hide.'"><td colspan="2">&nbsp;</td></tr>';

        // jquery.easing
        $html .= '<tr class="twizadmin0'.$hide.'"><td class="twiz-admin-form-td-left">'.__('jQuery Easing', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryEasing().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><a href="http://gsgd.co.uk/sandbox/jquery/easing/" target="_blank">'.__('More info', 'the-welcomizer').'</a></td></tr>';
        
         // extra easing
        $html .= '<tr class="twizadmin0'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Display extra easing in lists', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryExtraEasing().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><label for="twiz_extra_easing"><a href="http://jqueryui.com/resources/demos/effect/easing.html" target="_blank">'.__('More info', 'the-welcomizer').'</a></label></td></tr>';
        
        $html .= '<tr class="twizadmin0'.$hide.'"><td></td> <td><hr class="twiz-hr twiz-corner-all"></td></tr>';
        
        $html .= '<tr class="twizadmin0'.$hide.'"><td class="twiz-admin-form-td-left">'.__('rotate3Di', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryRotate3Di().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><a href="https://github.com/zachstronaut/rotate3Di" target="_blank">'.__('More info', 'the-welcomizer').'</a> <label for="twiz_register_jquery_rotate3di">'.__('(ignored by IE < 9)', 'the-welcomizer').'</label></td></tr>';
        
        $html .= '<tr class="twizadmin0'.$hide.'"><td></td><td><b>'.__('And/Or', 'the-welcomizer').'</b></td></tr>';
                
        $html .= '<tr class="twizadmin0'.$hide.'"><td class="twiz-admin-form-td-left">'.__('jquery-animate-css-rotate-scale', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryanimatecssrotatescale().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><a href="https://github.com/zachstronaut/jquery-animate-css-rotate-scale" target="_blank">'.__('More info', 'the-welcomizer').'</a> <label for="twiz_register_jquery_animatecssrotatescale">'.__('(ignored by IE < 9)', 'the-welcomizer').'</label></td></tr>';
        
        $html .= '<tr class="twizadmin0'.$hide.'"><td></td><td><b>'.__('Or', 'the-welcomizer').'</b></td></tr>';
        
        // transition
        $html .= '<tr class="twizadmin0'.$hide.'"><td class="twiz-admin-form-td-left">'.__('jQuery Transit', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryTransit().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><a href="https://github.com/rstacruz/jquery.transit" target="_blank">'.__('More info', 'the-welcomizer').'</a> <label for="twiz_register_jquery_transit"></label></td></tr>';
        
        $html .= '<tr class="twizadmin0'.$hide.'"><td></td><td><b>'.__('Or', 'the-welcomizer').'</b></td></tr>';
        
        $html .= '<tr class="twizadmin0'.$hide.'"><td class="twiz-admin-form-td-left">'.__('transform', 'the-welcomizer').' - <span class="twiz-green twiz-form-small">'.__('(no longer maintained)', 'the-welcomizer').'</span>: ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQuerytransform().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><a href="https://github.com/heygrady/transform/" target="_blank">'.__('More info', 'the-welcomizer').'</a></td></tr>';
        

        if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin1'] == '1' ){
        
            $hide = '';
            $toggleimg = 'twiz-minus';
            $boldclass = ' twiz-bold';
            
        }else{

            $hide = ' twiz-display-none';
            $toggleimg = 'twiz-plus';
            $boldclass = '';
        }
 
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="2"><div class="twiz-relative"><div id="twiz_admin_img_twizadmin1" class="twiz-toggle-admin twiz-toggle-img-admin '.$toggleimg.'"></div></div><a id="twiz_admin_e_a_twizadmin1" class="twiz-toggle-admin'.$boldclass.'">'.__('Output Settings', 'the-welcomizer').'</a></td></tr>';
        
        $html .= '<tr class="twizadmin1'.$hide.'"><td colspan="2">&nbsp;</td></tr>';
        
        // the_content filter
        $html .= '<tr class="twizadmin1'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Replace all WP shortcodes within the HTML', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLContentFilter().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left twiz-green"><label for="twiz_'.parent::KEY_THE_CONTENT_FILTER.'"></label></td></tr>';
                
        // Protected
        $html .= '<tr class="twizadmin1'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Disable \'ajax, post, and cookie\'', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLOutputProtected().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left twiz-green"><label for="twiz_'.parent::KEY_OUTPUT_PROTECTED.'">'.__('(recommended)', 'the-welcomizer').'</label></td></tr>';
        
        // Output compress
        $html .= '<tr class="twizadmin1'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Compress Output code', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLOutputCompression().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left twiz-green"><label for="twiz_'.parent::KEY_OUTPUT_COMPRESSION.'">'.__('(recommended)', 'the-welcomizer').'</label></td></tr>';
        
        // Output code hook
        $html .= '<tr class="twizadmin1'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Output code hooked to', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLOutputList().'</div></td><td class="twiz-form-td-left"></td></tr>';


        if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin2'] == '1' ){
        
            $hide = '';
            $toggleimg = 'twiz-minus';
            $boldclass = ' twiz-bold';
            
        }else{

            $hide = ' twiz-display-none';
            $toggleimg = 'twiz-plus';
            $boldclass = '';
        }
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="2"><div class="twiz-relative"><div id="twiz_admin_img_twizadmin2" class="twiz-toggle-admin twiz-toggle-img-admin '.$toggleimg.'"></div></div><a id="twiz_admin_e_a_twizadmin2" class="twiz-toggle-admin'.$boldclass.'">'.__('Menu Setting', 'the-welcomizer').'</a></td></tr>';
        
        $html .= '<tr class="twizadmin2'.$hide.'"><td colspan="2">&nbsp;</td></tr>';
        // Number of posts displayed in lists
        $html .= '<tr class="twizadmin2'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Maximum number of posts in lists', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLNumberPostsInLists().'</div></td><td class="twiz-form-td-right"></td></tr>';
        
        if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin3'] == '1' ){
        
            $hide = '';
            $toggleimg = 'twiz-minus';
            $boldclass = ' twiz-bold';
            
        }else{

            $hide = ' twiz-display-none';
            $toggleimg = 'twiz-plus';
            $boldclass = '';
        }
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="2"><div class="twiz-relative"><div id="twiz_admin_img_twizadmin3" class="twiz-toggle-admin twiz-toggle-img-admin '.$toggleimg.'"></div></div><a id="twiz_admin_e_a_twizadmin3" class="twiz-toggle-admin'.$boldclass.'">'.__('Library Setting', 'the-welcomizer').'</a></td></tr>';
        
        $html .= '<tr class="twizadmin3'.$hide.'"><td colspan="2">&nbsp;</td></tr>';
        
        // Sort order for directories
        $html .= '<tr class="twizadmin3'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Sort order for directories', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLSortOrderDirectories().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left"></td></tr>';
        
        
        if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin4'] == '1' ){
        
            $hide = '';
            $toggleimg = 'twiz-minus';
            $boldclass = ' twiz-bold';
            
        }else{

            $hide = ' twiz-display-none';
            $toggleimg = 'twiz-plus';
            $boldclass = '';
        }
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="2"><div class="twiz-relative"><div id="twiz_admin_img_twizadmin4" class="twiz-toggle-admin twiz-toggle-img-admin '.$toggleimg.'"></div></div><a id="twiz_admin_e_a_twizadmin4" class="twiz-toggle-admin'.$boldclass.'">'.__('Edition Settings', 'the-welcomizer').'</a></td></tr>';
        
        $html .= '<tr class="twizadmin4'.$hide.'"><td colspan="2">&nbsp;</td></tr>';
        
        // Starting position by default on add new
        $html .= '<tr class="twizadmin4'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Starting position by default', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLStartingPositionList().'</div></td><td class="twiz-form-td-right"></td></tr>';
          
        // Positioning method
        $html .= '<tr class="twizadmin4'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Positioning method', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLPositioningMethod().'</div></td><td class="twiz-form-td-right"></td></tr>';
                    
        if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin5'] == '1' ){
        
            $hide = '';
            $toggleimg = 'twiz-minus';
            $boldclass = ' twiz-bold';
            
        }else{

            $hide = ' twiz-display-none';
            $toggleimg = 'twiz-plus';
            $boldclass = '';
        }
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="2"><div class="twiz-relative"><div id="twiz_admin_img_twizadmin5" class="twiz-toggle-admin twiz-toggle-img-admin '.$toggleimg.'"></div></div><a id="twiz_admin_e_a_twizadmin5" class="twiz-toggle-admin'.$boldclass.'">'.__('Access Level Settings', 'the-welcomizer').'</a></td></tr>';
        
        $html .= '<tr class="twizadmin5'.$hide.'"><td colspan="2">&nbsp;</td></tr>';
        
        // Min role level
        $html .= '<tr class="twizadmin5'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Minimum Role to access this plugin', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLMinRoleLevel().'</div></td><td class="twiz-form-td-right"></td></tr>';
        
        // Min role library
        $html .= '<tr class="twizadmin5'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Minimum Role to access the Library', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLMinRoleLibrary().'</div></td><td class="twiz-form-td-right"></td></tr>';
        
        // Min role admin
        $html .= '<tr class="twizadmin5'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Minimum Role to access the Admin', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLMinRoleAdmin().'</div></td><td class="twiz-form-td-right"></td></tr>';
        
        if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin7'] == '1' ){
        
            $hide = '';
            $toggleimg = 'twiz-minus';
            $boldclass = ' twiz-bold';
            
        }else{

            $hide = ' twiz-display-none';
            $toggleimg = 'twiz-plus';
            $boldclass = '';
        }
        
        
        $class_div_promote = '';
        $class_label_promote = '';
        
        // Display promote position list or show label instead
        if( $this->admin_option[parent::KEY_PROMOTE_PLUGIN] == '1' ){ 

            $class_label_promote = ' class="twiz-display-none"';
            
        }else{

            $class_div_promote = ' class="twiz-display-none"';
        }
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="2"><div class="twiz-relative"><div id="twiz_admin_img_twizadmin7" class="twiz-toggle-admin twiz-toggle-img-admin '.$toggleimg.'"></div></div><a id="twiz_admin_e_a_twizadmin7" class="twiz-toggle-admin'.$boldclass.'">'.__('Privacy Setting', 'the-welcomizer').'</a></td></tr>';
        
        $html .= '<tr class="twizadmin7'.$hide.'"><td colspan="2">&nbsp;</td></tr>';

        // Privacy settings
        $html .= '<tr class="twizadmin7'.$hide.'"><td class="twiz-admin-form-td-left">'.__('Remove facebook like button', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLFBlike().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><label for="twiz_'.parent::KEY_FB_LIKE.'"></label></td></tr>';
        
        if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_ADMIN]['twizadmin6'] == '1' ){
        
            $hide = '';
            $toggleimg = 'twiz-minus';
            $boldclass = ' twiz-bold';
            
        }else{

            $hide = ' twiz-display-none';
            $toggleimg = 'twiz-plus';
            $boldclass = '';
        }
        
        
        $class_div_promote = '';
        $class_label_promote = '';
        
        // Display promote position list or show label instead
        if( $this->admin_option[parent::KEY_PROMOTE_PLUGIN] == '1' ){ 

            $class_label_promote = ' class="twiz-display-none"';
            
        }else{

            $class_div_promote = ' class="twiz-display-none"';
        }
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="2"><div class="twiz-relative"><div id="twiz_admin_img_twizadmin6" class="twiz-toggle-admin twiz-toggle-img-admin '.$toggleimg.'"></div></div><a id="twiz_admin_e_a_twizadmin6" class="twiz-toggle-admin'.$boldclass.'">'.__('Removal Settings', 'the-welcomizer').'</a></td></tr>';

        // Deactivation
        $html .= '<tr class="twizadmin6'.$hide.'"><td class="twiz-admin-form-td-left twiz-red">'.__('Delete all settings when disabling the plugin', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLDeleteAll().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left twiz-green"><label for="twiz_delete_all">'.__('(for uninstall)', 'the-welcomizer').'</label></td></tr>';
      
        // Remove created directories
        $html .= '<tr class="twizadmin6'.$hide.'"><td class="twiz-admin-form-td-left twiz-red">'.__('Delete created directories when disabling the plugin', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLRemoveCreatedDirectories().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left twiz-green"><label for="twiz_remove_created_directories">'.__('(for uninstall)', 'the-welcomizer').'</label></td></tr>';
        
        $created_dir = '';
        
        if( @file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH) ){
         
            $created_dir = WP_CONTENT_DIR. '<b>'. parent::IMPORT_PATH .'</b>';
        }
        if( @file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH) ){
         
            $created_dir = WP_CONTENT_DIR. '<b>'.parent::IMPORT_PATH. parent::EXPORT_PATH.'</b>';
        }       
        if( @file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH. parent::BACKUP_PATH) ){
         
            $created_dir = WP_CONTENT_DIR. '<b>'.parent::IMPORT_PATH. parent::EXPORT_PATH. parent::BACKUP_PATH.'</b>';
        }
                
        $html .= '<tr class="twizadmin6'.$hide.'"><td class="twiz-admin-form-td-left twiz-form-small twiz-blue" colspan="2">'.$created_dir.'</td></tr>';

        $html .= '<tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr>';

        // Display vardump
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Display plugin environment variables', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLDisplayVardump().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left"></td></tr>'; 
        
        // Promote this plugin
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Promote this plugin, add a link on this website', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLPromote().'</div></td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><label id="twiz_label_promote_plugin" for="twiz_promote_plugin"'.$class_label_promote.' class="twiz_promote_plugin">'.__('(at the bottom of web pages)', 'the-welcomizer').'</label><div id="twiz_div_promote_position"'.$class_div_promote.'>'.$this->getHTMLPromotePosition().'</div></td></tr>'; 
        
        $html .= '<tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr>';
                
        $html .= '<tr><td class="twiz-td-save" colspan="2"><span id="twiz_admin_save_img_box_2" class="twiz-loading-gif-save"></span> <a id="twiz_cancel_2">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_admin_save" id="twiz_admin_save_2" class="button-primary" value="'.__('Save', 'the-welcomizer').'" /></td></tr>';
        
        $html.= '</table>'.$jquery;

        return $html;
    }
    
    private function getHTMLOverRideNetworkSettings(){
    
        $twiz_override_network_settings = ($this->override_network_settings == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_override_network_settings" name="twiz_override_network_settings"'.$twiz_override_network_settings.'/>';
                 
        return $html;
    }
    
    private function getHTMLjQueryRegister(){
    
        $twiz_register_jquery = ($this->admin_option[parent::KEY_REGISTER_JQUERY] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_REGISTER_JQUERY.'" name="twiz_'.parent::KEY_REGISTER_JQUERY.'"'.$twiz_register_jquery.'/>';
                 
        return $html;
    }
    
    private function getHTMLjQueryTransit(){
    
        $twiz_register_jquery_transit = ($this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSIT] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_REGISTER_JQUERY_TRANSIT.'" name="twiz_'.parent::KEY_REGISTER_JQUERY_TRANSIT.'"'.$twiz_register_jquery_transit.'/>';
                 
        return $html;
    }
    
    private function getHTMLjQuerytransform(){
    
        $twiz_register_jquery_transform = ($this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSFORM] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_REGISTER_JQUERY_TRANSFORM.'" name="twiz_'.parent::KEY_REGISTER_JQUERY_TRANSFORM.'"'.$twiz_register_jquery_transform.'/>';
                 
        return $html;
    }
   
    private function getHTMLjQueryRotate3Di(){
    
        $twiz_register_jquery_rotate3di = ($this->admin_option[parent::KEY_REGISTER_JQUERY_ROTATE3DI] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_REGISTER_JQUERY_ROTATE3DI.'" name="twiz_'.parent::KEY_REGISTER_JQUERY_ROTATE3DI.'"'.$twiz_register_jquery_rotate3di.'/>';
                 
        return $html;
    }

    private function getHTMLjQueryanimatecssrotatescale(){
    
        $twiz_register_jquery_animatecssrotatescale = ($this->admin_option[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE.'" name="twiz_'.parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE.'"'.$twiz_register_jquery_animatecssrotatescale.'/>';
                 
        return $html;
    }    

    private function getHTMLjQueryEasing(){
    
        $twiz_register_jquery_easing = ($this->admin_option[parent::KEY_REGISTER_JQUERY_EASING] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_REGISTER_JQUERY_EASING.'" name="twiz_'.parent::KEY_REGISTER_JQUERY_EASING.'"'.$twiz_register_jquery_easing.'/>';
                 
        return $html;
    }
    
    private function getHTMLjQueryExtraEasing(){
    
        $twiz_extra_easing = ($this->admin_option[parent::KEY_EXTRA_EASING] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_EXTRA_EASING.'" name="twiz_'.parent::KEY_EXTRA_EASING.'"'.$twiz_extra_easing.'/>';
                 
        return $html;
    }
    
    private function getHTMLDeleteAll(){
    
        $twiz_delete_all = ($this->admin_option[parent::KEY_DELETE_ALL] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_DELETE_ALL.'" name="twiz_'.parent::KEY_DELETE_ALL.'"'.$twiz_delete_all.'/>';
                 
        return $html;
    }   
    
    private function getHTMLRemoveCreatedDirectories(){
    
        $twiz_remove_created_directories = ($this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_REMOVE_CREATED_DIRECTORIES.'" name="twiz_'.parent::KEY_REMOVE_CREATED_DIRECTORIES.'"'.$twiz_remove_created_directories.'/>';
                 
        return $html;
    }

    private function getHTMLFBlike(){
    
        $twiz_footer_ads = ($this->admin_option[parent::KEY_FB_LIKE] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_FB_LIKE.'" name="twiz_'.parent::KEY_FB_LIKE.'"'.$twiz_footer_ads.'/>';
                 
        return $html;
    }
    
    
    private function getHTMLPromote(){
    
        $twiz_promote_plugin = ($this->admin_option[parent::KEY_PROMOTE_PLUGIN] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_PROMOTE_PLUGIN.'" name="twiz_'.parent::KEY_PROMOTE_PLUGIN.'"'.$twiz_promote_plugin.' class="twiz-promote-plugin"/>';
                 
        return $html;
    }    

    private function getHTMLPromotePosition(){
        
        $select  = '<select name="twiz_'.parent::KEY_PROMOTE_POSITION.'" id="twiz_'.parent::KEY_PROMOTE_POSITION.'">';
        
        
        $bottom_left = (parent::POS_BOTTOM_LEFT == $this->admin_option[parent::KEY_PROMOTE_POSITION]) ? ' selected="selected"' : '';
        $bottom_right = (parent::POS_BOTTOM_RIGHT == $this->admin_option[parent::KEY_PROMOTE_POSITION]) ? ' selected="selected"' : '';
            
        $select .= '<option value="'.parent::POS_BOTTOM_LEFT.'"'.$bottom_left.'>'.__('At the bottom left', 'the-welcomizer').'</option>';
        $select .= '<option value="'.parent::POS_BOTTOM_RIGHT.'"'.$bottom_right.'>'.__('At the bottom right', 'the-welcomizer').'</option>';
    
        $select .= '</select>';
        
        return $select;
    }

    private function getHTMLDisplayVardump(){
    
        $twiz_display_var_dump = ($this->admin_option[parent::KEY_DISPLAY_VAR_DUMP] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_DISPLAY_VAR_DUMP.'" name="twiz_'.parent::KEY_DISPLAY_VAR_DUMP.'"'.$twiz_display_var_dump.'/>';
                 
        return $html;
    }    
    
    private function getHTMLNumberPostsInLists(){
        
        $html  = '<select name="twiz_'.parent::KEY_NUMBER_POSTS.'" id="twiz_'.parent::KEY_NUMBER_POSTS.'">';
        
        foreach( $this->array_number_posts as $key => $value ) {

            $selected = ($value == $this->admin_option[parent::KEY_NUMBER_POSTS]) ? ' selected="selected"' : '';
            
            $html .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
        }
    
        $html .= '</select>';
        
        return $html;
    }
        
    
    private function getHTMLSortOrderDirectories(){
    
        $select = '<select class="twiz-slc-output" name="twiz_'.parent::KEY_SORT_LIB_DIR.'" id="twiz_'.parent::KEY_SORT_LIB_DIR.'">';
         
        $original = ('original' == $this->admin_option[parent::KEY_SORT_LIB_DIR]) ? ' selected="selected"' : '';
        $reversed = ('reversed' == $this->admin_option[parent::KEY_SORT_LIB_DIR]) ? ' selected="selected"' : '';
            
        $select .= '<option value="original"'.$original.'>'.__('Original', 'the-welcomizer').'</option>';
        $select .= '<option value="reversed"'.$reversed.'>'.__('Reversed', 'the-welcomizer').'</option>';

        $select .= '</select>';
            
        return $select;
    }
        
    private function getHTMLStartingPositionList(){
        
        $html  = '<select name="twiz_'.parent::KEY_STARTING_POSITION.'" id="twiz_'.parent::KEY_STARTING_POSITION.'">';

        foreach( $this->array_position as $value ) {

            $selected = ($value == $this->admin_option[parent::KEY_STARTING_POSITION]) ? ' selected="selected"' : '';
            
            $html .= '<option value="'.$value.'"'.$selected.'>'.__($value, 'the-welcomizer').'</option>';
        }
    
        $html .= '</select>';
        
        return $html;
    }        
    
    private function getHTMLPositioningMethod(){
        
        $html  = '<select name="twiz_'.parent::KEY_POSITIONING_METHOD.'" id="twiz_'.parent::KEY_POSITIONING_METHOD.'">';

        foreach( $this->array_positioning_method as $value ) {

            $selected = ($value == $this->admin_option[parent::KEY_POSITIONING_METHOD]) ? ' selected="selected"' : '';
            
            $html .= '<option value="'.$value.'"'.$selected.'>'.$value.'</option>';
        }
    
        $html .= '</select>';
        
        return $html;
    }
    
    private function getHTMLSelectOptions( $option_key = '', $admin_only = true){

        $html = '';
        
        if($admin_only ==  true ){
           
           $temp_array = array_intersect_assoc( $this->array_admin_only, $this->array_role_conversion );
           $this->array_role_conversion = $temp_array;
        }

        if( ( ( current_user_can('manage_network') ) or ( current_user_can('activate_plugins') ) 
        and ( is_multisite() ) ) ){
        
            $selected = ( 'manage_network' == $this->admin_option[$option_key] ) ? ' selected="selected"' : '';
            $html .= '<option value="manage_network"'.$selected.'>'.__('Super Admin', 'the-welcomizer').'</option>';
        }
         
        foreach( $this->array_role_conversion as $wprole => $role_label ) {
            
            if( $wprole != 'read' ){
            
                $selected = ($wprole == $this->admin_option[$option_key]) ? ' selected="selected"' : '';
                $html .= '<option value="'.$wprole.'"'.$selected.'>'.__($role_label, 'the-welcomizer').'</option>'; 
            }
        }    

        return $html;
    }
    
    private function getHTMLMinRoleLevel(){
    
        $html  = '<select name="twiz_'.parent::KEY_MIN_ROLE_LEVEL.'" id="twiz_'.parent::KEY_MIN_ROLE_LEVEL.'">';
        
        $html .= $this->getHTMLSelectOptions(parent::KEY_MIN_ROLE_LEVEL, false);
    
        $html .= '</select>';
        
        return $html;
    }

    private function getHTMLMinRoleAdmin(){
    
        $html  = '<select name="twiz_'.parent::KEY_MIN_ROLE_ADMIN.'" id="twiz_'.parent::KEY_MIN_ROLE_ADMIN.'">';
        
        $html .= $this->getHTMLSelectOptions(parent::KEY_MIN_ROLE_ADMIN, true);
    
        $html .= '</select>';
        
        return $html;
    }
    
    private function getHTMLMinRoleLibrary(){
        
        $html  = '<select name="twiz_'.parent::KEY_MIN_ROLE_LIBRARY.'" id="twiz_'.parent::KEY_MIN_ROLE_LIBRARY.'">';
        
        $html .= $this->getHTMLSelectOptions(parent::KEY_MIN_ROLE_LIBRARY, true);
    
        $html .= '</select>';
        
        return $html;
    }
    
    private function getHTMLOutputCompression(){
    
        $twiz_output_compression = ($this->admin_option[parent::KEY_OUTPUT_COMPRESSION] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_OUTPUT_COMPRESSION.'" name="twiz_'.parent::KEY_OUTPUT_COMPRESSION.'"'.$twiz_output_compression.'/>';
                 
        return $html;
    }
    
    private function getHTMLOutputList(){
    
        $select = '<select class="twiz-slc-output" name="twiz_'.parent::KEY_OUTPUT.'" id="twiz_'.parent::KEY_OUTPUT.'">';
         
        foreach ($this->array_output as $value){

            $selected = ($value == $this->admin_option[parent::KEY_OUTPUT]) ? ' selected="selected"' : '';
            
            $select .= '<option value="'.$value.'"'.$selected.'>'.$value.' </option>';
            
        }
            
        $select .= '</select>';
            
        return $select;
    }   
    
    private function getHTMLRestoreUpdateSettings(){

        $html = '';
        
     //  if( $this->override_network_settings == '1' ){
       
          $html = '<div id="twiz_restore_network_options" class="twiz-display-none">  <span class="twiz-display-none" id="twiz_span_restore"><input type="radio" id="twiz_restore" name="twiz_restore_settings" value="restore"/> <label for="twiz_restore" class="twiz-green twiz-form-small">'.__('Restore from network', 'the-welcomizer').'</label></span>';
 
          $html .= ' <span class="twiz-display-none" id="twiz_span_update"><input type="radio" id="twiz_update" name="twiz_restore_settings" value="update" checked="checked"/> <label for="twiz_update" class="twiz-green twiz-form-small">'.__('Update this site', 'the-welcomizer').'</label></span></div>';
     //   }
                 
        return $html;
    }
    
    private function getHTMLContentFilter(){
    
        $twiz_the_content_filter = ($this->admin_option[parent::KEY_THE_CONTENT_FILTER] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_THE_CONTENT_FILTER.'" name="twiz_'.parent::KEY_THE_CONTENT_FILTER.'"'.$twiz_the_content_filter.'/>';
                 
        return $html;
    }
    
    private function getHTMLOutputProtected(){
    
        $twiz_output_protected = ($this->admin_option[parent::KEY_OUTPUT_PROTECTED] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_'.parent::KEY_OUTPUT_PROTECTED.'" name="twiz_'.parent::KEY_OUTPUT_PROTECTED.'"'.$twiz_output_protected.'/>';
                 
        return $html;
    }
    
    function loadAdmin(){

        // Override Network Settings
        if( !isset($this->override_network_settings) ) $this->override_network_settings = '';
        if( $this->override_network_settings == '' ){
        
            $this->override_network_settings = '0'; 
            $code = update_option('twiz_override_network_settings', $this->override_network_settings);
        }
        
        // Register jQuery
        if( !isset($this->admin_option[parent::KEY_REGISTER_JQUERY]) ) $this->admin_option[parent::KEY_REGISTER_JQUERY] = '';
        if( $this->admin_option[parent::KEY_REGISTER_JQUERY] == '' ){
        
            $this->admin_option[parent::KEY_REGISTER_JQUERY] = '0';
            
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
        
        // Register jQuery transition
        if( !isset($this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSIT]) ) $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSIT] = '';
        if( $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSIT] == '' ){
        
            $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSIT] = '0';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
        
        // Register jQuery transform
        if( !isset($this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSFORM]) ) $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSFORM] = '';
        if( $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSFORM] == '' ){
        
            $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSFORM] = '0'; 
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }

        // Register jQuery rotate3Di
        if( !isset($this->admin_option[parent::KEY_REGISTER_JQUERY_ROTATE3DI]) ) $this->admin_option[parent::KEY_REGISTER_JQUERY_ROTATE3DI] = '';
        if( $this->admin_option[parent::KEY_REGISTER_JQUERY_ROTATE3DI] == '' ){
        
            $this->admin_option[parent::KEY_REGISTER_JQUERY_ROTATE3DI] = '0'; 
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }

        // Register jQuery animate-css-rotate-scale
        if( !isset($this->admin_option[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE]) ) $this->admin_option[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] = '';
        if( $this->admin_option[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] == '' ){
        
            $this->admin_option[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] = '0'; 
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }        

        // Register jQuery easing
        if( !isset($this->admin_option[parent::KEY_REGISTER_JQUERY_EASING]) ) $this->admin_option[parent::KEY_REGISTER_JQUERY_EASING] = '';
        if( $this->admin_option[parent::KEY_REGISTER_JQUERY_EASING] == '' ){
        
            $this->admin_option[parent::KEY_REGISTER_JQUERY_EASING] = '0'; // deactivated by default
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }     
        
        // Output compresssion
        if( !isset($this->admin_option[parent::KEY_OUTPUT_COMPRESSION]) ) $this->admin_option[parent::KEY_OUTPUT_COMPRESSION] = '';
        if( $this->admin_option[parent::KEY_OUTPUT_COMPRESSION] == '' ){
        
            $this->admin_option[parent::KEY_OUTPUT_COMPRESSION] = '1';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }

        // Output setting 
        if( !isset($this->admin_option[parent::KEY_OUTPUT]) ) $this->admin_option[parent::KEY_OUTPUT] = '';
        if( $this->admin_option[parent::KEY_OUTPUT] == '' ){
        
            $this->admin_option[parent::KEY_OUTPUT] = parent::OUTPUT_HEADER;
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
        
        // Extra Easing
        if( !isset($this->admin_option[parent::KEY_EXTRA_EASING]) ) $this->admin_option[parent::KEY_EXTRA_EASING] = '';
        if( $this->admin_option[parent::KEY_EXTRA_EASING] == '' ){
        
            $this->admin_option[parent::KEY_EXTRA_EASING] = '0';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
        
        // Number of posts displayed in lists
        if( !isset($this->admin_option[parent::KEY_NUMBER_POSTS]) ) $this->admin_option[parent::KEY_NUMBER_POSTS] = '';
        if( $this->admin_option[parent::KEY_NUMBER_POSTS] == '' ){
        
            $this->admin_option[parent::KEY_NUMBER_POSTS] = parent::DEFAULT_NUMBER_POSTS;
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
                
        // Sort order for directories
        if( !isset($this->admin_option[parent::KEY_SORT_LIB_DIR]) ) $this->admin_option[parent::KEY_SORT_LIB_DIR] = '';
        if( $this->admin_option[parent::KEY_SORT_LIB_DIR] == '' ){
        
            $this->admin_option[parent::KEY_SORT_LIB_DIR] = 'reversed';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
        
        // Starting position by default on add new
        if( !isset($this->admin_option[parent::KEY_STARTING_POSITION]) ) $this->admin_option[parent::KEY_STARTING_POSITION] = 'nothing';
        if( $this->admin_option[parent::KEY_STARTING_POSITION] == 'nothing' ) {
        
            $this->admin_option[parent::KEY_STARTING_POSITION] = parent::DEFAULT_STARTING_POSITION;
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }        
        
        // Positioning method
        if( !isset($this->admin_option[parent::KEY_POSITIONING_METHOD]) ) $this->admin_option[parent::KEY_POSITIONING_METHOD] = '';
        if( $this->admin_option[parent::KEY_POSITIONING_METHOD] == '' ){
        
            $this->admin_option[parent::KEY_POSITIONING_METHOD] =  parent::DEFAULT_POSITIONING_METHOD;
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }        
        $ok = $this->setPositioningMethod();
        
        // Min role Level
        if( !isset($this->admin_option[parent::KEY_MIN_ROLE_LEVEL]) ) $this->admin_option[parent::KEY_MIN_ROLE_LEVEL] = '';
        if( $this->admin_option[parent::KEY_MIN_ROLE_LEVEL] == '' ){
        
            $this->admin_option[parent::KEY_MIN_ROLE_LEVEL] = $this->DEFAULT_MIN_ROLE_LEVEL;
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }

        // Min role Library
        if( !isset($this->admin_option[parent::KEY_MIN_ROLE_LIBRARY]) ) $this->admin_option[parent::KEY_MIN_ROLE_LIBRARY] = '';
        if( $this->admin_option[parent::KEY_MIN_ROLE_LIBRARY] == '' ){
        
            $this->admin_option[parent::KEY_MIN_ROLE_LIBRARY] = $this->DEFAULT_MIN_ROLE_LEVEL;
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
        
        // Min role Admin
        if( !isset($this->admin_option[parent::KEY_MIN_ROLE_ADMIN]) ) $this->admin_option[parent::KEY_MIN_ROLE_ADMIN] = '';
        if( $this->admin_option[parent::KEY_MIN_ROLE_ADMIN] == '' ){
        
            $this->admin_option[parent::KEY_MIN_ROLE_ADMIN] = $this->DEFAULT_MIN_ROLE_LEVEL;
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
        
        // the_content filter
        if( !isset($this->admin_option[parent::KEY_THE_CONTENT_FILTER]) ) $this->admin_option[parent::KEY_THE_CONTENT_FILTER] = '';
        if( $this->admin_option[parent::KEY_THE_CONTENT_FILTER] == '' ){
        
            $this->admin_option[parent::KEY_THE_CONTENT_FILTER] =  '0';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
        
        // Output Protected
        if( !isset($this->admin_option[parent::KEY_OUTPUT_PROTECTED]) ) $this->admin_option[parent::KEY_OUTPUT_PROTECTED] = '';
        if( $this->admin_option[parent::KEY_OUTPUT_PROTECTED] == '' ){
        
            $this->admin_option[parent::KEY_OUTPUT_PROTECTED] =  '1';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }              

        // FB like
        if( !isset($this->admin_option[parent::KEY_FB_LIKE]) ) $this->admin_option[parent::KEY_FB_LIKE] = '';
        if( $this->admin_option[parent::KEY_FB_LIKE] == '' ){
        
            $this->admin_option[parent::KEY_FB_LIKE] = '0';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }     
                
        // Delete All
        if( !isset($this->admin_option[parent::KEY_DELETE_ALL]) ) $this->admin_option[parent::KEY_DELETE_ALL] = '';
        if( $this->admin_option[parent::KEY_DELETE_ALL] == '' ){
        
            $this->admin_option[parent::KEY_DELETE_ALL] = '0';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
                  
        // Remove Created Directories
        if( !isset($this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES]) ) $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] = '';
        if( $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] == '' ){
        
            $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] = '0';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }
        
        // Promote this plugin
        if( !isset($this->admin_option[parent::KEY_PROMOTE_PLUGIN]) ) $this->admin_option[parent::KEY_PROMOTE_PLUGIN] = '';
        if( $this->admin_option[parent::KEY_PROMOTE_PLUGIN] == '' ){
        
            $this->admin_option[parent::KEY_PROMOTE_PLUGIN] = '0';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }   
        
        // Promote position
        if( !isset($this->admin_option[parent::KEY_PROMOTE_POSITION]) ) $this->admin_option[parent::KEY_PROMOTE_POSITION] = '';
        if( $this->admin_option[parent::KEY_PROMOTE_POSITION] == '' ){
        
            $this->admin_option[parent::KEY_PROMOTE_POSITION] = parent::DEFAULT_PROMOTE_POSITION;
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }           
        
        // Display vardump
        if( !isset($this->admin_option[parent::KEY_DISPLAY_VAR_DUMP]) ) $this->admin_option[parent::KEY_DISPLAY_VAR_DUMP] = '';
        if( $this->admin_option[parent::KEY_DISPLAY_VAR_DUMP] == '' ){
        
            $this->admin_option[parent::KEY_DISPLAY_VAR_DUMP] = '0';
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_option('twiz_admin');
                
            }else{
            
                $code = update_site_option('twiz_admin', $this->admin_option);
                $this->admin_option = get_site_option('twiz_admin');            
            }
        }            
        
        // Put next admin option here..
    }
    
    function SavePrivacyQuestion( $twiz_jquery = '', $twiz_fblike = '', $twiz_delete_all = '', $twiz_remove_created_directories = '' ){
        
        $jquery = '<script>
//<![CDATA[
jQuery(document).ready(function($) {
    location.reload();
});
//]]>
</script>';
        
        if( $twiz_fblike == '' ) {
        
            return $jquery;
        }

        // Register jQuery on the front end
        $twiz_jquery = ($twiz_jquery == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_REGISTER_JQUERY] = $twiz_jquery ;  

        // FB like
        $twiz_fblike = ($twiz_fblike == 'true') ? '0' : '1';
        $this->admin_option[parent::KEY_FB_LIKE] = $twiz_fblike;
        
        $twiz_delete_all = ($twiz_delete_all == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_DELETE_ALL] = $twiz_delete_all ;  
        
        $twiz_remove_created_directories = ($twiz_remove_created_directories == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] = $twiz_remove_created_directories ;        
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $code = update_option('twiz_admin', $this->admin_option);
            $ok = $this->setConfigurationSettings( $this->network_activated, true );       
            
        }else{
            
            $code = update_site_option('twiz_admin', $this->admin_option);
            $ok = $this->setConfigurationSettings( $this->network_activated, true );      
        }              
        
       return $jquery;
    }
     
    function saveAdmin(){
    
        $restore_settings = '';
        $ImgHScrollStatus = '';
        $ImgGlobalstatus = '';
        
        $setting[parent::KEY_REGISTER_JQUERY] = esc_attr(trim($_POST['twiz_'.parent::KEY_REGISTER_JQUERY]));
        $setting[parent::KEY_REGISTER_JQUERY_TRANSIT] = esc_attr(trim($_POST['twiz_'.parent::KEY_REGISTER_JQUERY_TRANSIT]));
        $setting[parent::KEY_REGISTER_JQUERY_TRANSFORM] = esc_attr(trim($_POST['twiz_'.parent::KEY_REGISTER_JQUERY_TRANSFORM]));
        $setting[parent::KEY_REGISTER_JQUERY_ROTATE3DI] = esc_attr(trim($_POST['twiz_'.parent::KEY_REGISTER_JQUERY_ROTATE3DI]));
        $setting[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] = esc_attr(trim($_POST['twiz_'.parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE]));
        $setting[parent::KEY_REGISTER_JQUERY_EASING] = esc_attr(trim($_POST['twiz_'.parent::KEY_REGISTER_JQUERY_EASING]));
        $setting[parent::KEY_OUTPUT_COMPRESSION] = esc_attr(trim($_POST['twiz_'.parent::KEY_OUTPUT_COMPRESSION]));
        $setting[parent::KEY_OUTPUT] = esc_attr(trim($_POST['twiz_'.parent::KEY_OUTPUT]));
        $setting[parent::KEY_THE_CONTENT_FILTER] = esc_attr(trim($_POST['twiz_'.parent::KEY_THE_CONTENT_FILTER]));
        $setting[parent::KEY_OUTPUT_PROTECTED] = esc_attr(trim($_POST['twiz_'.parent::KEY_OUTPUT_PROTECTED]));
        $setting[parent::KEY_EXTRA_EASING] = esc_attr(trim($_POST['twiz_'.parent::KEY_EXTRA_EASING]));
        $setting[parent::KEY_NUMBER_POSTS] = esc_attr(trim($_POST['twiz_'.parent::KEY_NUMBER_POSTS]));
        $setting[parent::KEY_SORT_LIB_DIR] = esc_attr(trim($_POST['twiz_'.parent::KEY_SORT_LIB_DIR]));
        $setting[parent::KEY_STARTING_POSITION] = esc_attr(trim($_POST['twiz_'.parent::KEY_STARTING_POSITION]));
        $setting[parent::KEY_POSITIONING_METHOD] = esc_attr(trim($_POST['twiz_'.parent::KEY_POSITIONING_METHOD]));
        $setting[parent::KEY_MIN_ROLE_LEVEL] = esc_attr(trim($_POST['twiz_'.parent::KEY_MIN_ROLE_LEVEL]));
        $setting[parent::KEY_MIN_ROLE_ADMIN] = esc_attr(trim($_POST['twiz_'.parent::KEY_MIN_ROLE_ADMIN]));
        $setting[parent::KEY_MIN_ROLE_LIBRARY] = esc_attr(trim($_POST['twiz_'.parent::KEY_MIN_ROLE_LIBRARY]));
        $setting[parent::KEY_FB_LIKE] = esc_attr(trim($_POST['twiz_'.parent::KEY_FB_LIKE]));
        $setting[parent::KEY_DELETE_ALL] = esc_attr(trim($_POST['twiz_'.parent::KEY_DELETE_ALL]));
        $setting[parent::KEY_REMOVE_CREATED_DIRECTORIES] = esc_attr(trim($_POST['twiz_'.parent::KEY_REMOVE_CREATED_DIRECTORIES]));
        $setting[parent::KEY_PROMOTE_PLUGIN] = esc_attr(trim($_POST['twiz_'.parent::KEY_PROMOTE_PLUGIN]));
        $setting[parent::KEY_PROMOTE_POSITION] = esc_attr(trim($_POST['twiz_'.parent::KEY_PROMOTE_POSITION]));
        $setting[parent::KEY_DISPLAY_VAR_DUMP] = esc_attr(trim($_POST['twiz_'.parent::KEY_DISPLAY_VAR_DUMP]));
        // Put next admin option here..

        if( ( $this->network_activated == '1' ) and( is_super_admin() ) ){
        
            $_POST['twiz_override_network_settings'] = (!isset($_POST['twiz_override_network_settings'])) ? '' : $_POST['twiz_override_network_settings'];
            $_POST['twiz_restore_settings'] = (!isset($_POST['twiz_restore_settings'])) ? '' : $_POST['twiz_restore_settings'];

            $override_network_settings = esc_attr(trim($_POST['twiz_override_network_settings']));
            $restore_settings = esc_attr(trim($_POST['twiz_restore_settings']));

            // Override Network Settings
            $this->override_network_settings = ($override_network_settings == 'true') ? '1' : '0';
        }  
        
        // Register jQuery
        $register_jquery = ($setting[parent::KEY_REGISTER_JQUERY] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_REGISTER_JQUERY] = $register_jquery ;

        // Register jQuery transition
        $twiz_register_jquery_transit = ($setting[parent::KEY_REGISTER_JQUERY_TRANSIT] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSIT] = $twiz_register_jquery_transit ;
        
        // Register jQuery transform
        $twiz_register_jquery_transform = ($setting[parent::KEY_REGISTER_JQUERY_TRANSFORM] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSFORM] = $twiz_register_jquery_transform ;

        // Register jQuery rotate3Di
        $twiz_register_jquery_rotate3Di = ($setting[parent::KEY_REGISTER_JQUERY_ROTATE3DI] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_REGISTER_JQUERY_ROTATE3DI] = $twiz_register_jquery_rotate3Di ;

        // Register jQuery animate-css-rotate-scale
        $twiz_register_jquery_animate_css_rotate_scale = ($setting[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] = $twiz_register_jquery_animate_css_rotate_scale ;
        
        // Register jQuery easing
        $twiz_register_jquery_easing = ($setting[parent::KEY_REGISTER_JQUERY_EASING] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_REGISTER_JQUERY_EASING] = $twiz_register_jquery_easing ;
        
        // Output compresssion
        $output_compression = ($setting[parent::KEY_OUTPUT_COMPRESSION] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_OUTPUT_COMPRESSION] = $output_compression ;
        
        // Output setting
        $this->admin_option[parent::KEY_OUTPUT] = $setting[parent::KEY_OUTPUT];
        
        // The_content filter
        $the_content_filter = ($setting[parent::KEY_THE_CONTENT_FILTER] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_THE_CONTENT_FILTER] = $the_content_filter;        
        
        // Output Protected
        $output_protected = ($setting[parent::KEY_OUTPUT_PROTECTED] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_OUTPUT_PROTECTED] = $output_protected;
        
        // Extra Easing
        $extra_easing = ($setting[parent::KEY_EXTRA_EASING] == 'true') ? '1' : '0';
        $extra_easing = ($twiz_register_jquery_easing == '1') ? '1' : $extra_easing;
        $extra_easing = ($twiz_register_jquery_transit == '1') ? '1' : $extra_easing;
        $this->admin_option[parent::KEY_EXTRA_EASING] = $extra_easing ;
        
        // Number of posts displayed in lists
        $this->admin_option[parent::KEY_NUMBER_POSTS] = $setting[parent::KEY_NUMBER_POSTS];
        
        // Sort order for directories
        $this->admin_option[parent::KEY_SORT_LIB_DIR] = $setting[parent::KEY_SORT_LIB_DIR];
        
        // Starting position by default on add new
        $this->admin_option[parent::KEY_STARTING_POSITION] = $setting[parent::KEY_STARTING_POSITION];
        
        // Positioning method
        $this->admin_option[parent::KEY_POSITIONING_METHOD] = $setting[parent::KEY_POSITIONING_METHOD];
        
        // Min role Level
        if(current_user_can($setting[parent::KEY_MIN_ROLE_LEVEL])){
            $this->admin_option[parent::KEY_MIN_ROLE_LEVEL] = $setting[parent::KEY_MIN_ROLE_LEVEL];
        }
        
        // Min role Library
        if(current_user_can($setting[parent::KEY_MIN_ROLE_LIBRARY])){        
            $this->admin_option[parent::KEY_MIN_ROLE_LIBRARY] = $setting[parent::KEY_MIN_ROLE_LIBRARY];
        }
        
        // Min role Admin
        if(current_user_can($setting[parent::KEY_MIN_ROLE_ADMIN])){                
            $this->admin_option[parent::KEY_MIN_ROLE_ADMIN] = $setting[parent::KEY_MIN_ROLE_ADMIN];
        }

        // FB like
        $fb_like_before = $this->admin_option[parent::KEY_FB_LIKE];
        $fb_like = ($setting[parent::KEY_FB_LIKE] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_FB_LIKE] = $fb_like ;
        
        // Delete All
        $delete_all = ($setting[parent::KEY_DELETE_ALL] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_DELETE_ALL] = $delete_all ;
        
        // Remove created directories
        $remove_created_directories = ($setting[parent::KEY_REMOVE_CREATED_DIRECTORIES] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] = $remove_created_directories ;        
        
        // Promote this plugin
        $promote_plugin = ($setting[parent::KEY_PROMOTE_PLUGIN] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_PROMOTE_PLUGIN] = $promote_plugin ;         
        // Promote position
        $this->admin_option[parent::KEY_PROMOTE_POSITION] = $setting[parent::KEY_PROMOTE_POSITION];       

        // Display vardump
        $display_vardump = ($setting[parent::KEY_DISPLAY_VAR_DUMP] == 'true') ? '1' : '0';
        $this->admin_option[parent::KEY_DISPLAY_VAR_DUMP] = $display_vardump ;               
        
        // Put next admin option here..
        
        // saving admin options
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
            // update admin settings
            $code = update_option('twiz_admin', $this->admin_option); 
                
            if( ( $this->override_network_settings == '1' )and( $this->network_activated == '1' ) ){
            
                $site_dbversion = get_site_option('twiz_db_version');
                $site_twiz_global_status = get_site_option('twiz_global_status');
                $site_twiz_cookie_js_status = get_site_option('twiz_cookie_js_status');
                $site_twiz_network_activated = get_site_option('twiz_network_activated');

                $blog_dbversion = get_option('twiz_db_version');
                $blog_twiz_global_status = get_option('twiz_global_status');
                $blog_twiz_cookie_js_status = get_option('twiz_cookie_js_status');
            //  $blog_twiz_setting_menu = get_option('twiz_setting_menu');

                $blog_twiz_toggle = get_option('twiz_toggle');
                $blog_twiz_order_by = get_option('twiz_order_by');
                $blog_twiz_skin = get_option('twiz_skin');
                $blog_twiz_bullet = get_option('twiz_bullet');
                $blog_twiz_setting_menu = get_option('twiz_setting_menu');
                $blog_twiz_hscroll_status = get_option('twiz_hscroll_status');
                    
                $blog_network_activated = get_option('twiz_network_activated');
                $blog_twiz_privacy_question_answered = get_option('twiz_privacy_question_answered');
                
                if($blog_dbversion == ''){ $code = update_option('twiz_db_version',$site_dbversion); }
                if($blog_twiz_global_status == ''){ $code = update_option('twiz_global_status', $site_twiz_global_status); }
                if($blog_twiz_cookie_js_status == ''){ $code = update_option('twiz_cookie_js_status', $site_twiz_cookie_js_status); }
            
                if($blog_twiz_toggle == ''){ $code = update_option('twiz_toggle', array()); }
                if($blog_twiz_order_by == ''){ $code = update_option('twiz_order_by', array()); }
                if($blog_twiz_skin == ''){ $code = update_option('twiz_skin', array()); }
                if($blog_twiz_bullet == ''){ $code = update_option('twiz_bullet', array()); }
                if($blog_twiz_setting_menu == ''){ $code = update_option('twiz_setting_menu', array()); }
                if($blog_twiz_hscroll_status == ''){ $code = update_option('twiz_hscroll_status', array()); }

            //  if($blog_twiz_setting_menu == ''){ 
                    
                if( !isset($this->DEFAULT_SECTION[$this->user_id] ) ) $this->DEFAULT_SECTION[$this->user_id] = '';
                $this->DEFAULT_SECTION[$this->user_id] = self::DEFAULT_SECTION_HOME.'_'.$this->BLOG_ID; 
                
                $code = update_option('twiz_setting_menu', $this->DEFAULT_SECTION);
                
            //  }

                if($blog_network_activated == ''){ $code = update_option('twiz_network_activated', $site_twiz_network_activated); }
                if($blog_twiz_privacy_question_answered == ''){ $code = update_option('twiz_privacy_question_answered', true); }
                $code = update_option('twiz_override_network_settings', $this->override_network_settings);   
                
                $this->setOptions();
                $this->setUserSettings();
                $ImgHScrollStatus = $this->getImgHScrollStatus().'<div class="twiz-arrow twiz-arrow-e twiz-hscroll-arrow"></div>';
                $ImgGlobalstatus = $this->getImgGlobalstatus();

            }
            
        }else{ // only is_multisite()
        
            $is_network_activated = get_site_option('twiz_network_activated');
            $is_network_activated = ( $is_network_activated == '' )? '0' : $is_network_activated;
            
            switch( $restore_settings ){
            
                case 'update': 
                
                    if( ( $this->override_network_settings != '1' )and( $this->network_activated == '1' ) ){

                    // update settings
                    $code = update_site_option('twiz_admin', $this->admin_option);                     
                    $code = update_site_option('twiz_setting_menu', $this->DEFAULT_SECTION);
  
                    }else{ // blog activated on network
                    
                        // update settings
                        $code = update_option('twiz_admin', $this->admin_option);                     
                        $code = update_option('twiz_setting_menu', $this->DEFAULT_SECTION);                    
                    }
                    
                    break;
            
                case 'restore':
  
                    $code = update_option('twiz_override_network_settings', '0'); 

                    $site_dbversion = get_site_option('twiz_db_version');
                    $site_twiz_global_status = get_site_option('twiz_global_status');
                    $site_twiz_cookie_js_status = get_site_option('twiz_cookie_js_status');
                    $site_privacy_question_answered = get_site_option('twiz_privacy_question_answered');
                    $site_twiz_network_activated = get_site_option('twiz_network_activated');
                    
                    $site_twiz_toggle = get_site_option('twiz_toggle');
                    $site_twiz_order_by = get_site_option('twiz_order_by');
                    $site_twiz_skin = get_site_option('twiz_skin');
                    $site_twiz_bullet = get_site_option('twiz_bullet');
                    $site_twiz_setting_menu = get_site_option('twiz_setting_menu');
                    $site_twiz_hscroll_status = get_site_option('twiz_hscroll_status');
                    

                    if($site_dbversion == ''){ $code = update_site_option('twiz_db_version', $this->dbVersion); }
                    if($site_twiz_global_status == ''){ $code = update_site_option('twiz_global_status', '1'); }
                    if($site_twiz_cookie_js_status == ''){ $code = update_site_option('twiz_cookie_js_status', false); }
                    if($site_privacy_question_answered == ''){ $code = update_site_option('twiz_privacy_question_answered', true); }
                    if($site_twiz_network_activated == ''){ $code = update_site_option('twiz_network_activated', '1'); }
                    
                    if($site_twiz_toggle == ''){ $code = update_site_option('twiz_toggle', array()); }
                    if($site_twiz_order_by == ''){ $code = update_site_option('twiz_order_by', array()); }
                    if($site_twiz_skin == ''){ $code = update_site_option('twiz_skin', array()); }
                    if($site_twiz_bullet == ''){ $code = update_site_option('twiz_bullet', array()); }
                    if($site_twiz_setting_menu == ''){ $code = update_site_option('twiz_setting_menu', array()); }
                    if($site_twiz_hscroll_status == ''){ $code = update_site_option('twiz_hscroll_status', array()); }

                    if( !isset($this->DEFAULT_SECTION[$this->user_id] ) ) $this->DEFAULT_SECTION[$this->user_id] = '';
                    $this->DEFAULT_SECTION[$this->user_id] = self::DEFAULT_SECTION_HOME; 
                    $code = update_site_option('twiz_setting_menu', $this->DEFAULT_SECTION);
                                    
                    $this->setOptions();
                    $this->setUserSettings();
                    $ImgHScrollStatus = $this->getImgHScrollStatus().'<div class="twiz-arrow twiz-arrow-e twiz-hscroll-arrow"></div>';
                    $ImgGlobalstatus = $this->getImgGlobalstatus();
                    
                    break;
                }
            
            if( $is_network_activated == '1' ){
            
                $code = update_site_option('twiz_network_activated', $is_network_activated);
            }
            
            // update settings for this site
            $code = update_option('twiz_override_network_settings', $this->override_network_settings);
            $code = update_option('twiz_network_activated', $is_network_activated);                    
        }
            
        $relike = '';
        $rebind = '';
        $htmladsresponse = '';
        $htmllikeresponse = '';
        $debug = '';
        
        // fb like
        if( ( $fb_like == '1' ) 
        and ( $fb_like_before == '0' ) ){    
    
            $relike = 'remove';
        }
        
        if( ( $fb_like == '0' ) 
        and ( $fb_like_before == '1' ) ){
        
           $htmllikeresponse = parent::IFRAME_FB_LIKE; ;
           $relike = 'relike';
        }        

        // Debug alert()
        $debug =  ''; // put something to debug.

        if( TWIZ_FORCE_VARDUMP == true ){
        
            $display_vardump = '1';
        }
             
        $json = json_encode( array('debug' => $debug, parent::KEY_DISPLAY_VAR_DUMP => $display_vardump, 'override_network_settings' => $this->override_network_settings,'relike' => $relike,'htmllike' =>  $htmllikeresponse, 'skin' => $this->skin[$this->user_id], 'section_id' => $this->DEFAULT_SECTION[$this->user_id],'ImgHScrollStatus' => $ImgHScrollStatus,'ImgGlobalstatus' => $ImgGlobalstatus));

        return $json;
    }
}
?>