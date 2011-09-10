<?php
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

class TwizAdmin extends Twiz{
	
    /* variable declaration */
    private $array_admin;

    /* Output array */
    private $array_output = array(parent::OUTPUT_HEADER    
                                 ,parent::OUTPUT_FOOTER  
                                 ); 
    
    function __construct(){
    
        parent::__construct();
        
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
    $("#twiz_delete_menu").fadeOut("slow");
    $("#twiz_library_upload").fadeOut("slow");    
    $("#twiz_import").fadeOut("slow");    
    $("#twiz_export").fadeOut("slow"); 
	$("#twiz_delete_menu_everywhere").fadeOut("slow");
    $("#twiz_add_sections").fadeOut("slow"); 
    $("#twiz_right_panel").fadeOut("slow");
});
//]]>
</script>';


        $html = '<table class="twiz-table-form" cellspacing="0" cellpadding="0">';      
        
        $html .= '<tr><td class="twiz-admin-form-td-left">'.__('Output code hooked to', 'the-welcomizer').': ';
        $html .= '<div class="twiz-float-right">'.$this->getHTMLOutputList().'</td><td class="twiz-form-td-right"></td></tr>';
        
        $html .= '<tr><td colspan="2"><hr></td></tr>';
        
        $html .= '<tr><td class="twiz-td-save" colspan="2"><img src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_admin_save_img" name="twiz_admin_save_img" class="twiz-loading-gif twiz-loading-gif-save"> <input type="button" name="twiz_admin_save" id="twiz_admin_save" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /></td></tr>';
        
        $html.= '</table>'.$jquery;
                 
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
    
    function loadAdmin(){

        $this->array_admin = get_option('twiz_admin');

        // Add new settings right here and below.
    
        /* Output setting */ 
        if( !isset($this->array_admin[parent::KEY_OUTPUT]) ) $this->array_admin[parent::KEY_OUTPUT] = '';
        
        if( $this->array_admin[parent::KEY_OUTPUT] == '' ) {
        
            $this->array_admin[parent::KEY_OUTPUT] = parent::OUTPUT_HEADER;
            $code = update_option('twiz_admin', $this->array_admin); 
            $this->array_admin = get_option('twiz_admin');
        }
        /* ****** */
      
    }
    
    function saveAdmin( $setting = '' ){
    
        if( $setting == '' ){ return false;}
    
        // Add new settings right here.
        $this->array_admin[parent::KEY_OUTPUT] = $setting[parent::KEY_OUTPUT];
        
        $code = update_option('twiz_admin', $this->array_admin); 

        return $code;
    }    
}
?>