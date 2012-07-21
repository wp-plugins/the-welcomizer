<?php
/*  Copyright 2012  Sébastien Laframboise  (email:wordpress@sebastien-laframboise.com)

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
    
    /* variable declaration */
    private $array_admin;

    /* Output array */
    private $array_output = array(parent::OUTPUT_HEADER    
                                 ,parent::OUTPUT_FOOTER  
                                 ); 
    
    /* role equivalence array */
    private $array_role_conversion = array ("administrator" => 'activate_plugins'
                                           ,"editor"        => 'moderate_comments'
                                           ,"author"        => 'edit_published_posts'
                                           ,"contributor"   => 'edit_posts'
                                           ,"subscriber"    => 'read'
                                           );
    
    /* Number of posts to display */
    private $array_number_posts;
                                       
    function __construct(){
    
        parent::__construct();
        
        /* Number of posts to display */
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
    
        $html = '<div id="twiz_admin_master">';
        $html .= $this->getHtmlAdminForm();
        $html .= '</div>';
        
        return $html;
    }
    
    private function getHtmlAdminForm(){
        
        /* hide element */
        $jquery = '<script>
//<![CDATA[
jQuery(document).ready(function($) {
    $("#twiz_new").fadeOut("slow");
    $("#twiz_add_menu").fadeOut("slow");
    $("#twiz_library_upload").fadeOut("slow");    
    $("#twiz_import").fadeOut("slow");    
    $("#twiz_export").fadeOut("slow"); 
    $("#twiz_add_sections").fadeOut("slow"); 
    $("#twiz_right_panel").fadeOut("slow");
});
//]]>
</script>';

        $html = '<table class="twiz-table-form" cellspacing="0" cellpadding="0">';      
        
        // default jquery registration
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Register jQuery default library', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryRegister().'</div></td><td class="twiz-form-td-right"><span name="twiz_admin_save_img_box" class="twiz-loading-gif-save"></span><input type="button" name="twiz_admin_save" id="twiz_admin_save_top" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /></td></tr>';
        
        $html .= '<tr><td colspan="2"><hr></td></tr>';
        
        $html .= '<tr><td colspan="2"><strong>'.__('Built-in jQuery packages', 'the-welcomizer').'</strong></td></tr>';
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
                      
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('rotate3Di', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryRotate3Di().'</div></td><td class="twiz-form-td-right twiz-float-left"><a href="https://github.com/zachstronaut/rotate3Di" target="_blank">'.__('More info', 'the-welcomizer').'</a> <label for="twiz_register_jquery_rotate3di">'.__('(ignored by IE < 9)', 'the-welcomizer').'</label></td></tr>';
        
        $html .= '<tr><td></td><td><strong>'.__('And/Or', 'the-welcomizer').'</strong></td></tr>';
                
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('jquery-animate-css-rotate-scale', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryanimatecssrotatescale().'</div></td><td class="twiz-form-td-right twiz-float-left"><a href="https://github.com/zachstronaut/jquery-animate-css-rotate-scale" target="_blank">'.__('More info', 'the-welcomizer').'</a> <label for="twiz_register_jquery_animatecssrotatescale">'.__('(ignored by IE < 9)', 'the-welcomizer').'</label></td></tr>';
        
        $html .= '<tr><td></td><td><strong>'.__('Or', 'the-welcomizer').'</strong></td></tr>';
        
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('transform', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQuerytransform().'</div></td><td class="twiz-form-td-right twiz-float-left"><a href="https://github.com/heygrady/transform/" target="_blank">'.__('More info', 'the-welcomizer').'</a></td></tr>';
        
        $html .= '<tr><td></td><td><strong>'.__('Or', 'the-welcomizer').'</strong></td></tr>';
        // transition
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('jQuery Transit', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryTransit().'</div></td><td class="twiz-form-td-right twiz-float-left"><a href="https://github.com/rstacruz/jquery.transit" target="_blank">'.__('More info', 'the-welcomizer').'</a> <label for="twiz_register_jquery_transit">'.__('(different easing)', 'the-welcomizer').'</label></td></tr>';
        
        $html .= '<tr><td colspan="2"><hr></td></tr>';
        $html .= '<tr><td colspan="2"><strong>'.__('Output code settings', 'the-welcomizer').'</strong></td></tr>';
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
        // Protected
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Disable \'ajax, post, and cookie\'', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLOutputProtected().'</div></td><td class="twiz-form-td-right twiz-float-left twiz-green"><label for="twiz_output_protected">'.__('(recommended)', 'the-welcomizer').'</label></td></tr>'; 
        // Output compress
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Compress Output code', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLOutputCompression().'</div></td><td class="twiz-form-td-right twiz-float-left twiz-green"><label for="twiz_output_compression">'.__('(recommended)', 'the-welcomizer').'</label></td></tr>';
        
        // Output code hook
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Output code hooked to', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLOutputList().'</div></td><td class="twiz-form-td-left"></td></tr>';
        
        $html .= '<tr><td colspan="2"><hr></td></tr>';
        $html .= '<tr><td colspan="2"><strong>'.__('Menu settings', 'the-welcomizer').'</strong></td></tr>';
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
        // Number of posts displayed in lists
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Maximum number of posts in lists', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLNumberPostsInLists().'</div></td><td class="twiz-form-td-right"></td></tr>';
        
        $html .= '<tr><td colspan="2"><hr></td></tr>';
        $html .= '<tr><td colspan="2"><strong>'.__('Edition settings', 'the-welcomizer').'</strong></td></tr>';
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>';        
        // extra easing
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Display extra easing in lists', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLjQueryExtraEasing().'</div></td><td class="twiz-form-td-right twiz-float-left"><label for="twiz_extra_easing"><a href="http://jqueryui.com/demos/effect/easing.html" target="_blank">'. __('(requires jQuery easing)', 'the-welcomizer').'</a></label></td></tr>';
                   
        // Starting position by default on add new
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Starting position by default', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLStartingPositionList().'</div></td><td class="twiz-form-td-right"></td></tr>';
          
        $html .= '<tr><td colspan="2"><hr></td></tr>';
        $html .= '<tr><td colspan="2"><strong>'.__('Access level settings', 'the-welcomizer').'</strong></td></tr>';
        
        $html .= '<tr><td colspan="2">&nbsp;</td></tr>'; 
        
        // Min role level
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Minimum Role to access this plugin', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLMinRoleLevel().'</div></td><td class="twiz-form-td-right"></td></tr>';
        
        // Min role library
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Minimum Role to access the Library', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLMinRoleLibrary().'</div></td><td class="twiz-form-td-right"></td></tr>';
        
        // Min role admin
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Minimum Role to access the Admin', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLMinRoleAdmin().'</div></td><td class="twiz-form-td-right"></td></tr>';
        
        $html .= '<tr><td colspan="2"><hr></td></tr>';
        
        // Deactivation
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Delete all when disabling the plugin', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLDeleteAll().'</div></td><td class="twiz-form-td-right twiz-float-left twiz-red"><label for="twiz_delete_all">'.__('(not recommended)', 'the-welcomizer').'</label></td></tr>';
        
        $html .= '<tr><td colspan="2"><hr></td></tr>';

        
        $html .= '<tr><td class="twiz-td-save" colspan="2"><span name="twiz_admin_save_img_box" class="twiz-loading-gif-save"></span><input type="button" name="twiz_admin_save" id="twiz_admin_save" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /></td></tr>';
        
        $html.= '</table>'.$jquery;
                 
        return $html;
    }
    
    private function getHTMLjQueryRegister(){
    
        $twiz_register_jquery = ($this->array_admin[parent::KEY_REGISTER_JQUERY] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_register_jquery" name="twiz_register_jquery"'.$twiz_register_jquery.'>';
                 
        return $html;
    }
    
    private function getHTMLjQueryTransit(){
    
        $twiz_register_jquery_transit = ($this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSIT] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_register_jquery_transit" name="twiz_register_jquery_transit"'.$twiz_register_jquery_transit.'>';
                 
        return $html;
    }

    private function getHTMLjQuerytransform(){
    
        $twiz_register_jquery_transform = ($this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSFORM] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_register_jquery_transform" name="twiz_register_jquery_transform"'.$twiz_register_jquery_transform.'>';
                 
        return $html;
    }
   
    private function getHTMLjQueryRotate3Di(){
    
        $twiz_register_jquery_rotate3di = ($this->array_admin[parent::KEY_REGISTER_JQUERY_ROTATE3DI] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_register_jquery_rotate3di" name="twiz_register_jquery_rotate3di"'.$twiz_register_jquery_rotate3di.'>';
                 
        return $html;
    }

    private function getHTMLjQueryanimatecssrotatescale(){
    
        $twiz_register_jquery_animatecssrotatescale = ($this->array_admin[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_register_jquery_animatecssrotatescale" name="twiz_register_jquery_animatecssrotatescale"'.$twiz_register_jquery_animatecssrotatescale.'>';
                 
        return $html;
    }    
    
    private function getHTMLjQueryExtraEasing(){
    
        $twiz_extra_easing = ($this->array_admin[parent::KEY_EXTRA_EASING] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_extra_easing" name="twiz_extra_easing"'.$twiz_extra_easing.'>';
                 
        return $html;
    }
    
    private function getHTMLDeleteAll(){
    
        $twiz_delete_all = ($this->array_admin[parent::KEY_DELETE_ALL] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_delete_all" name="twiz_delete_all"'.$twiz_delete_all.'>';
                 
        return $html;
    }
   
    private function getHTMLNumberPostsInLists(){
        
        $html  = '<select name="twiz_number_posts" id="twiz_number_posts">';
        
        foreach( $this->array_number_posts as $key => $value ) {

            $selected = ($value == $this->array_admin[parent::KEY_NUMBER_POSTS]) ? ' selected="selected"' : '';
            
            $html .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
        }
    
        $html .= '</select>';
        
        return $html;
    }
    
        private function getHTMLStartingPositionList(){
        
        $html  = '<select name="twiz_starting_position" id="twiz_starting_position">';

        foreach( $this->array_position as $value ) {

            $selected = ($value == $this->array_admin[parent::KEY_STARTING_POSITION]) ? ' selected="selected"' : '';
            
            $html .= '<option value="'.$value.'"'.$selected.'>'.__($value, 'the-welcomizer').'</option>';
        }
    
        $html .= '</select>';
        
        return $html;
    }
    
    private function getHTMLMinRoleLevel(){
    
        global $wp_roles;
        
        $roles = apply_filters('wp_role_listing', $wp_roles);
        
        $html  = '<select name="twiz_min_rolelevel" id="twiz_min_rolelevel">';
        
        foreach( $roles->role_names as $key => $rolename ) {
        
            if( $key != 'subscriber' ){            
            
                $strkey =  strtr($key, $this->array_role_conversion);
            
                $selected = ($strkey == $this->array_admin[parent::KEY_MIN_ROLE_LEVEL]) ? ' selected="selected"' : '';
                
                $html .= '<option value="'.$strkey.'"'.$selected.'>'._x($rolename, 'User role').'</option>';
            }
        }
    
        $html .= '</select>';
        
        return $html;
    }

    private function getHTMLMinRoleAdmin(){
    
        global $wp_roles;
        
        $roles = apply_filters('wp_role_listing', $wp_roles);
        
        $html  = '<select name="twiz_min_roleadmin" id="twiz_min_roleadmin">';
        
        foreach( $roles->role_names as $key => $rolename ) {
           
           if( $key != 'subscriber' ){
           
                $strkey =  strtr($key, $this->array_role_conversion);
            
                $selected = ($strkey == $this->array_admin[parent::KEY_MIN_ROLE_ADMIN]) ? ' selected="selected"' : '';
                
                $html .= '<option value="'.$strkey.'"'.$selected.'>'._x($rolename, 'User role').'</option>';
            }
            
        }
    
        $html .= '</select>';
        
        return $html;
    }
    
    private function getHTMLMinRoleLibrary(){
    
        global $wp_roles;
        
        $roles = apply_filters('wp_role_listing', $wp_roles);
        
        $html  = '<select name="twiz_min_rolelibrary" id="twiz_min_rolelibrary">';
        
        foreach( $roles->role_names as $key => $rolename ) {
        
            if( $key != 'subscriber' ){
           
                $strkey =  strtr($key, $this->array_role_conversion);
            
                $selected = ($strkey == $this->array_admin[parent::KEY_MIN_ROLE_LIBRARY]) ? ' selected="selected"' : '';
                
                $html .= '<option value="'.$strkey.'"'.$selected.'>'._x($rolename, 'User role').'</option>';
            }
        }
    
        $html .= '</select>';
        
        return $html;
    }
    
    private function getHTMLOutputCompression(){
    
        $twiz_output_compression = ($this->array_admin[parent::KEY_OUTPUT_COMPRESSION] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_output_compression" name="twiz_output_compression"'.$twiz_output_compression.'>';
                 
        return $html;
    }
    
    private function getHTMLOutputList(){
    
        $select = '<select class="twiz-slc-output" name="twiz_slc_output" id="twiz_slc_output">';
         
        foreach ($this->array_output as $value){

            $selected = ($value == $this->array_admin[parent::KEY_OUTPUT]) ? ' selected="selected"' : '';
            
            $select .= '<option value="'.$value.'"'.$selected.'>'.$value.' </option>';
            
        }
            
        $select .= '</select>';
            
        return $select;
    }
    
    private function getHTMLOutputProtected(){
    
        $twiz_output_protected = ($this->array_admin[parent::KEY_OUTPUT_PROTECTED] == '1') ? ' checked="checked"' : '';
    
        $html = '<input type="checkbox" id="twiz_output_protected" name="twiz_output_protected"'.$twiz_output_protected.'>';
                 
        return $html;
    }
    
    function loadAdmin(){

        $this->array_admin = get_option('twiz_admin');

        // Add new settings right here and below...
    
        // Register jQuery
        if( !isset($this->array_admin[parent::KEY_REGISTER_JQUERY]) ) $this->array_admin[parent::KEY_REGISTER_JQUERY] = '';
        if( $this->array_admin[parent::KEY_REGISTER_JQUERY] == '' ) {
        
            $this->array_admin[parent::KEY_REGISTER_JQUERY] = '1'; // Activated by default
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
 
        // Register jQuery transition
        if( !isset($this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSIT]) ) $this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSIT] = '';
        if( $this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSIT] == '' ) {
        
            $this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSIT] = '0'; // Activated by default
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
        
        // Register jQuery transform
        if( !isset($this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSFORM]) ) $this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSFORM] = '';
        if( $this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSFORM] == '' ) {
        
            $this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSFORM] = '0'; // Activated by default
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }

        // Register jQuery rotate3Di
        if( !isset($this->array_admin[parent::KEY_REGISTER_JQUERY_ROTATE3DI]) ) $this->array_admin[parent::KEY_REGISTER_JQUERY_ROTATE3DI] = '';
        if( $this->array_admin[parent::KEY_REGISTER_JQUERY_ROTATE3DI] == '' ) {
        
            $this->array_admin[parent::KEY_REGISTER_JQUERY_ROTATE3DI] = '0'; // Activated by default
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }

        // Register jQuery animate-css-rotate-scale
        if( !isset($this->array_admin[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE]) ) $this->array_admin[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] = '';
        if( $this->array_admin[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] == '' ) {
        
            $this->array_admin[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] = '0'; // Activated by default
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }        
        
        // Output compresssion
        if( !isset($this->array_admin[parent::KEY_OUTPUT_COMPRESSION]) ) $this->array_admin[parent::KEY_OUTPUT_COMPRESSION] = '';
        if( $this->array_admin[parent::KEY_OUTPUT_COMPRESSION] == '' ) {
        
            $this->array_admin[parent::KEY_OUTPUT_COMPRESSION] = '1';
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }

        // Output setting 
        if( !isset($this->array_admin[parent::KEY_OUTPUT]) ) $this->array_admin[parent::KEY_OUTPUT] = '';
        if( $this->array_admin[parent::KEY_OUTPUT] == '' ) {
        
            $this->array_admin[parent::KEY_OUTPUT] = parent::OUTPUT_HEADER;
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
        
        // Extra Easing
        if( !isset($this->array_admin[parent::KEY_EXTRA_EASING]) ) $this->array_admin[parent::KEY_EXTRA_EASING] = '';
        if( $this->array_admin[parent::KEY_EXTRA_EASING] == '' ) {
        
            $this->array_admin[parent::KEY_EXTRA_EASING] = '0'; 
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
        
        // Number of posts displayed in lists
        if( !isset($this->array_admin[parent::KEY_NUMBER_POSTS]) ) $this->array_admin[parent::KEY_NUMBER_POSTS] = '';
        if( $this->array_admin[parent::KEY_NUMBER_POSTS] == '' ) {
        
            $this->array_admin[parent::KEY_NUMBER_POSTS] = parent::DEFAULT_NUMBER_POSTS;
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
        
        // Starting position by default on add new
        if( !isset($this->array_admin[parent::KEY_STARTING_POSITION]) ) $this->array_admin[parent::KEY_STARTING_POSITION] = 'nothing';
        if( $this->array_admin[parent::KEY_STARTING_POSITION] == 'nothing' ) {
        
            $this->array_admin[parent::KEY_STARTING_POSITION] = parent::DEFAULT_STARTING_POSITION;
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
        
        // Min role Level
        if( !isset($this->array_admin[parent::KEY_MIN_ROLE_LEVEL]) ) $this->array_admin[parent::KEY_MIN_ROLE_LEVEL] = '';
        if( $this->array_admin[parent::KEY_MIN_ROLE_LEVEL] == '' ) {
        
            $this->array_admin[parent::KEY_MIN_ROLE_LEVEL] = parent::DEFAULT_MIN_ROLE_LEVEL;
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }

        // Min role Library
        if( !isset($this->array_admin[parent::KEY_MIN_ROLE_LIBRARY]) ) $this->array_admin[parent::KEY_MIN_ROLE_LIBRARY] = '';
        if( $this->array_admin[parent::KEY_MIN_ROLE_LIBRARY] == '' ) {
        
            $this->array_admin[parent::KEY_MIN_ROLE_LIBRARY] = parent::DEFAULT_MIN_ROLE_LEVEL;
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
        
        // Min role Admin
        if( !isset($this->array_admin[parent::KEY_MIN_ROLE_ADMIN]) ) $this->array_admin[parent::KEY_MIN_ROLE_ADMIN] = '';
        if( $this->array_admin[parent::KEY_MIN_ROLE_ADMIN] == '' ) {
        
            $this->array_admin[parent::KEY_MIN_ROLE_ADMIN] = parent::DEFAULT_MIN_ROLE_LEVEL;
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
        
        // Output Protected
        if( !isset($this->array_admin[parent::KEY_OUTPUT_PROTECTED]) ) $this->array_admin[parent::KEY_OUTPUT_PROTECTED] = '';
        if( $this->array_admin[parent::KEY_OUTPUT_PROTECTED] == '' ) {
        
            $this->array_admin[parent::KEY_OUTPUT_PROTECTED] =  '1';
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
        
        // Delete All
        if( !isset($this->array_admin[parent::KEY_DELETE_ALL]) ) $this->array_admin[parent::KEY_DELETE_ALL] = '';
        if( $this->array_admin[parent::KEY_DELETE_ALL] == '' ) {
        
            $this->array_admin[parent::KEY_DELETE_ALL] = '0';
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
        
        // Next option
    }
    
    function saveAdmin( $setting = '' ){
    
        $code = false;
        
        if( $setting == '' ){ return $code;}
        
        // Add new settings right here and above...

        // Register jQuery
        $register_jquery = ($setting[parent::KEY_REGISTER_JQUERY] == 'true') ? '1' : '0';
        $this->array_admin[parent::KEY_REGISTER_JQUERY] = $register_jquery ;

        // Register jQuery transition
        $twiz_register_jquery_transit = ($setting[parent::KEY_REGISTER_JQUERY_TRANSIT] == 'true') ? '1' : '0';
        $this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSIT] = $twiz_register_jquery_transit ;
        
        // Register jQuery transform
        $twiz_register_jquery_transit = ($setting[parent::KEY_REGISTER_JQUERY_TRANSFORM] == 'true') ? '1' : '0';
        $this->array_admin[parent::KEY_REGISTER_JQUERY_TRANSFORM] = $twiz_register_jquery_transit ;

        // Register jQuery rotate3Di
        $twiz_register_jquery_transit = ($setting[parent::KEY_REGISTER_JQUERY_ROTATE3DI] == 'true') ? '1' : '0';
        $this->array_admin[parent::KEY_REGISTER_JQUERY_ROTATE3DI] = $twiz_register_jquery_transit ;

        // Register jQuery animate-css-rotate-scale
        $twiz_register_jquery_transit = ($setting[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] == 'true') ? '1' : '0';
        $this->array_admin[parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] = $twiz_register_jquery_transit ;
        
        // Extra Easing
        $extra_easing = ($setting[parent::KEY_EXTRA_EASING] == 'true') ? '1' : '0';
        $this->array_admin[parent::KEY_EXTRA_EASING] = $extra_easing ;
        
        // Number of posts displayed in lists
        $this->array_admin[parent::KEY_NUMBER_POSTS] = $setting[parent::KEY_NUMBER_POSTS];
        
        // Starting position by default on add new
        $this->array_admin[parent::KEY_STARTING_POSITION] = $setting[parent::KEY_STARTING_POSITION];
        
        // Output compresssion
        $output_compression = ($setting[parent::KEY_OUTPUT_COMPRESSION] == 'true') ? '1' : '0';
        $this->array_admin[parent::KEY_OUTPUT_COMPRESSION] = $output_compression ;
        
        // Output setting
        $this->array_admin[parent::KEY_OUTPUT] = $setting[parent::KEY_OUTPUT];
        
        // Min role Level
        $this->array_admin[parent::KEY_MIN_ROLE_LEVEL] = $setting[parent::KEY_MIN_ROLE_LEVEL];
        
        // Min role Library
        $this->array_admin[parent::KEY_MIN_ROLE_LIBRARY] = $setting[parent::KEY_MIN_ROLE_LIBRARY];
        
        // Min role Admin
        $this->array_admin[parent::KEY_MIN_ROLE_ADMIN] = $setting[parent::KEY_MIN_ROLE_ADMIN];
        
        // Output Protected
        $output_protected = ($setting[parent::KEY_OUTPUT_PROTECTED] == 'true') ? '1' : '0';        
        $this->array_admin[parent::KEY_OUTPUT_PROTECTED] = $output_protected;
        
        // Delete All
        $delete_all = ($setting[parent::KEY_DELETE_ALL] == 'true') ? '1' : '0';
        $this->array_admin[parent::KEY_DELETE_ALL] = $delete_all ;
        
        // Update array
        $code = update_option('twiz_admin', $this->array_admin); 

        return $code;
    }    
}
?>