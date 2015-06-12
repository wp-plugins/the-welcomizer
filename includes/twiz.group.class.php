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
    
class TwizGroup extends Twiz{
  
    function __construct(){
    
        parent::__construct();
    }
    
    function getHtmlFormGroup( $id = '', $section_id = '', $action = parent::ACTION_NEW ){

        $twiz_group_name = '';
        $twiz_group_start_delay = '';
        $twiz_status = '';
        $lbl_action = '';
        $twiz_export_id = '';
        
        switch( $action ){
        
            case parent::ACTION_NEW:
                   
                    $lbl_action = __('Add New', 'the-welcomizer');
                    $twiz_status = ' checked="checked"';
                    $twiz_export_id = $this->getUniqid(); 

                break;
                
            case parent::ACTION_EDIT:
                    
                    if(!$data = $this->getRow($id)){return '';}
                    
                    $lbl_action = __('Edit', 'the-welcomizer');
                    $twiz_group_name = $data[parent::F_LAYER_ID];
                    $twiz_group_start_delay = $data[parent::F_START_DELAY];
                    $twiz_export_id = $data[parent::F_EXPORT_ID];
                    $twiz_status = ( $data[parent::F_STATUS] == '1' ) ? ' checked="checked"' : '';  
                    
                break;
                
            case parent::ACTION_COPY:
             
                    if(!$data = $this->getRow($id)){return '';}
                    
                    $lbl_action = __('Copy', 'the-welcomizer');
                    $twiz_group_name = $data[parent::F_LAYER_ID];
                    $twiz_group_start_delay = $data[parent::F_START_DELAY];
                    $twiz_export_id = $data[parent::F_EXPORT_ID];
                    $twiz_status = ( $data[parent::F_STATUS] == '1' ) ? ' checked="checked"' : '';  
            
                break;
            
        }   
    
            $jsscript = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
 twiz_view_id = null;';
 
        $jsscript .= '
$("[name^=twiz_listmenu]").css("display", "none");
';

        $jsscript .= '});
 //]]>
</script>';

        $html = '<div class="twiz-box-menu"><div class="twiz-text-right twiz-float-right">'.__('Action', 'the-welcomizer').'<div class="twiz-green">'.$lbl_action.'</div></div>'.__('Status', 'the-welcomizer').': <input type="checkbox" id="twiz_group_'.parent::F_STATUS.'" name="twiz_group_'.parent::F_STATUS.'" '.$twiz_status.'/>';

        $html .='<br>'.__('Group name', 'the-welcomizer').': ';
        $html .= '<input type="text" id="twiz_group_name" name="twiz_group_name"  value="'.$twiz_group_name.'" maxlength="27" class="twiz-input twiz-input-focus"/>';

        // cancel and save button
        $html .= '<div class="twiz-clear"></div><div class="twiz-text-right"><span id="twiz_group_save_img_box" name="twiz_group_save_img_box" class="twiz-loading-gif-save"></span><a id="twiz_group_cancel">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_group_save" id="twiz_group_save" class="button-primary" value="'.__('Save', 'the-welcomizer').'" /><input type="hidden" value="'.$action.'" id="twiz_sub_action" name="twiz_sub_action"/><input type="hidden" value="'.$id.'" id="twiz_group_id" name="twiz_group_id"/><input type="hidden" name="twiz_group_'.parent::F_EXPORT_ID.'" id="twiz_group_'.parent::F_EXPORT_ID.'" value="'.$twiz_export_id.'"/></div>';
        
        $html .= '</div>'.$jsscript;
        
        return $html;
    }
    
    private function getGroupList( $groupid = '' , $old_exportid = '' ){
    
        global $wpdb;
            
        $sql = "SELECT * FROM ".$this->table." WHERE ".parent::F_ID." = '".$groupid."' OR ".parent::F_PARENT_ID." = '".$old_exportid."'";   
                       
        $rows = $wpdb->get_results($sql, ARRAY_A);
        
        return $rows;
    }
    
    function copyGroup( $groupid = '', $section_id = '' ){

        global $wpdb;
        
        if( $groupid == '' ){return false;}

        $old_exportid = $this->getValue($groupid, parent::F_EXPORT_ID);
        $new_export_id = $this->getUniqid();        
          
        $group_rows = $this->getGroupList($groupid , $old_exportid);
        
        $twiz_group_name = esc_attr(trim($_POST['twiz_group_name']));       
        $twiz_group_status = esc_attr(trim($_POST['twiz_group_'.parent::F_STATUS]));
        $twiz_group_status = ($twiz_group_status=='true') ? 1 : 0;
        
        foreach($group_rows as $value){
            
            $twiz_start_top_pos   = ( $value[parent::F_START_TOP_POS] == '' ) ? 'NULL' : $value[parent::F_START_TOP_POS];
            $twiz_start_left_pos  = ( $value[parent::F_START_LEFT_POS] == '' ) ? 'NULL' :$value[parent::F_START_LEFT_POS];
            $twiz_move_top_pos_a = ( $value[parent::F_MOVE_TOP_POS_A] == '' ) ? 'NULL' : $value[parent::F_MOVE_TOP_POS_A];
            $twiz_move_left_pos_a = ( $value[parent::F_MOVE_LEFT_POS_A] == '' ) ? 'NULL' : $value[parent::F_MOVE_LEFT_POS_A];
            $twiz_move_top_pos_b  = ( $value[parent::F_MOVE_TOP_POS_B] == '' ) ? 'NULL' : $value[parent::F_MOVE_TOP_POS_B];
            $twiz_move_left_pos_b = ( $value[parent::F_MOVE_LEFT_POS_B] == '' ) ? 'NULL' : $value[parent::F_MOVE_LEFT_POS_B];
            
            $group_data[parent::F_PARENT_ID] = ($value[parent::F_PARENT_ID] == $old_exportid) ? $new_export_id : '';
            $group_data[parent::F_EXPORT_ID] = ($value[parent::F_EXPORT_ID] == $old_exportid) ? $new_export_id : $this->getUniqid();
            $group_data[parent::F_BLOG_ID] = $value[parent::F_BLOG_ID];
            $group_data[parent::F_SECTION_ID] = $value[parent::F_SECTION_ID];
            $group_data[parent::F_STATUS] = ($value[parent::F_TYPE] == parent::ELEMENT_TYPE_GROUP) ?  $twiz_group_status : $value[parent::F_STATUS] ; // From The Group Form or field.
            $group_data[parent::F_TYPE] = $value[parent::F_TYPE];
            $group_data[parent::F_LAYER_ID] = ($value[parent::F_TYPE] == parent::ELEMENT_TYPE_GROUP) ?  $twiz_group_name : $value[parent::F_LAYER_ID] ; // From The Group Form or field.
            $group_data[parent::F_ON_EVENT] = $value[parent::F_ON_EVENT];
            $group_data[parent::F_LOCK_EVENT] = $value[parent::F_LOCK_EVENT];
            $group_data[parent::F_LOCK_EVENT_TYPE] = $value[parent::F_LOCK_EVENT_TYPE];
            $group_data[parent::F_START_DELAY] = $value[parent::F_START_DELAY];
            $group_data[parent::F_DURATION] = $value[parent::F_DURATION];
            $group_data[parent::F_DURATION_B] = $value[parent::F_DURATION_B];
            $group_data[parent::F_OUTPUT] = $value[parent::F_OUTPUT];
            $group_data[parent::F_OUTPUT_POS] = $value[parent::F_OUTPUT_POS];
            $group_data[parent::F_JAVASCRIPT] = $value[parent::F_JAVASCRIPT];
            $group_data[parent::F_CSS] = $value[parent::F_CSS];
            $group_data[parent::F_START_ELEMENT_TYPE] = $value[parent::F_START_ELEMENT_TYPE];
            $group_data[parent::F_START_ELEMENT] = $value[parent::F_START_ELEMENT];
            $group_data[parent::F_START_TOP_POS_SIGN] = $value[parent::F_START_TOP_POS_SIGN];
            $group_data[parent::F_START_TOP_POS] = $twiz_start_top_pos;
            $group_data[parent::F_START_TOP_POS_FORMAT] = $value[parent::F_START_TOP_POS_FORMAT];
            $group_data[parent::F_START_LEFT_POS_SIGN] = $value[parent::F_START_LEFT_POS_SIGN];
            $group_data[parent::F_START_LEFT_POS] = $twiz_start_left_pos;
            $group_data[parent::F_START_LEFT_POS_FORMAT] = $value[parent::F_START_LEFT_POS_FORMAT];
            $group_data[parent::F_POSITION] = $value[parent::F_POSITION];
            $group_data[parent::F_ZINDEX] = $value[parent::F_ZINDEX];
            $group_data[parent::F_EASING_A] = $value[parent::F_EASING_A];
            $group_data[parent::F_EASING_B] = $value[parent::F_EASING_B];
            $group_data[parent::F_MOVE_ELEMENT_TYPE_A] = $value[parent::F_MOVE_ELEMENT_TYPE_A];
            $group_data[parent::F_MOVE_ELEMENT_A] = $value[parent::F_MOVE_ELEMENT_A];
            $group_data[parent::F_MOVE_TOP_POS_SIGN_A] = $value[parent::F_MOVE_TOP_POS_SIGN_A];
            $group_data[parent::F_MOVE_TOP_POS_A] = $twiz_move_top_pos_a;
            $group_data[parent::F_MOVE_TOP_POS_FORMAT_A] = $value[parent::F_MOVE_TOP_POS_FORMAT_A];
            $group_data[parent::F_MOVE_LEFT_POS_SIGN_A] = $value[parent::F_MOVE_LEFT_POS_SIGN_A];
            $group_data[parent::F_MOVE_LEFT_POS_A] = $twiz_move_left_pos_a;
            $group_data[parent::F_MOVE_LEFT_POS_FORMAT_A] = $value[parent::F_MOVE_LEFT_POS_FORMAT_A];
            $group_data[parent::F_MOVE_ELEMENT_TYPE_B] = $value[parent::F_MOVE_ELEMENT_TYPE_B];
            $group_data[parent::F_MOVE_ELEMENT_B] = $value[parent::F_MOVE_ELEMENT_B];
            $group_data[parent::F_MOVE_TOP_POS_SIGN_B] = $value[parent::F_MOVE_TOP_POS_SIGN_B];
            $group_data[parent::F_MOVE_TOP_POS_B] = $twiz_move_top_pos_b;
            $group_data[parent::F_MOVE_TOP_POS_FORMAT_B] = $value[parent::F_MOVE_TOP_POS_FORMAT_B];
            $group_data[parent::F_MOVE_LEFT_POS_SIGN_B] = $value[parent::F_MOVE_LEFT_POS_SIGN_B];
            $group_data[parent::F_MOVE_LEFT_POS_B] = $twiz_move_left_pos_b;
            $group_data[parent::F_MOVE_LEFT_POS_FORMAT_B] = $value[parent::F_MOVE_LEFT_POS_FORMAT_B];
            $group_data[parent::F_OPTIONS_A] = $value[parent::F_OPTIONS_A];
            $group_data[parent::F_OPTIONS_B] = $value[parent::F_OPTIONS_B];
            $group_data[parent::F_EXTRA_JS_A] = $value[parent::F_EXTRA_JS_A];
            $group_data[parent::F_EXTRA_JS_B] = $value[parent::F_EXTRA_JS_B];
            $group_data[parent::F_GROUP_ORDER] = $value[parent::F_GROUP_ORDER];
        
        
        $sql = "INSERT INTO ".$this->table." 
              (".parent::F_PARENT_ID."
              ,".parent::F_EXPORT_ID."
              ,".parent::F_BLOG_ID."
              ,".parent::F_SECTION_ID."
              ,".parent::F_STATUS."
              ,".parent::F_TYPE."
              ,".parent::F_LAYER_ID."
              ,".parent::F_ON_EVENT."                  
              ,".parent::F_LOCK_EVENT."                  
              ,".parent::F_LOCK_EVENT_TYPE."                  
              ,".parent::F_START_DELAY."
              ,".parent::F_DURATION."
              ,".parent::F_DURATION_B."
              ,".parent::F_OUTPUT."
              ,".parent::F_OUTPUT_POS."
              ,".parent::F_JAVASCRIPT."
              ,".parent::F_CSS."
              ,".parent::F_START_ELEMENT_TYPE."
              ,".parent::F_START_ELEMENT."
              ,".parent::F_START_TOP_POS_SIGN."
              ,".parent::F_START_TOP_POS."
              ,".parent::F_START_TOP_POS_FORMAT."
              ,".parent::F_START_LEFT_POS_SIGN."
              ,".parent::F_START_LEFT_POS."    
              ,".parent::F_START_LEFT_POS_FORMAT."    
              ,".parent::F_POSITION."    
              ,".parent::F_ZINDEX."    
              ,".parent::F_EASING_A." 
              ,".parent::F_EASING_B." 
              ,".parent::F_MOVE_ELEMENT_TYPE_A."
              ,".parent::F_MOVE_ELEMENT_A."                   
              ,".parent::F_MOVE_TOP_POS_SIGN_A."
              ,".parent::F_MOVE_TOP_POS_A."
              ,".parent::F_MOVE_TOP_POS_FORMAT_A."
              ,".parent::F_MOVE_LEFT_POS_SIGN_A."
              ,".parent::F_MOVE_LEFT_POS_A."
              ,".parent::F_MOVE_LEFT_POS_FORMAT_A."
              ,".parent::F_MOVE_ELEMENT_TYPE_B."
              ,".parent::F_MOVE_ELEMENT_B."                  
              ,".parent::F_MOVE_TOP_POS_SIGN_B."
              ,".parent::F_MOVE_TOP_POS_B."
              ,".parent::F_MOVE_TOP_POS_FORMAT_B."
              ,".parent::F_MOVE_LEFT_POS_SIGN_B."
              ,".parent::F_MOVE_LEFT_POS_B."
              ,".parent::F_MOVE_LEFT_POS_FORMAT_B."
              ,".parent::F_OPTIONS_A."
              ,".parent::F_OPTIONS_B."
              ,".parent::F_EXTRA_JS_A."
              ,".parent::F_EXTRA_JS_B."    
              ,".parent::F_GROUP_ORDER."    
              )VALUES('".$group_data[parent::F_PARENT_ID]."' 
              ,'".$group_data[parent::F_EXPORT_ID]."' 
              ,'".$group_data[parent::F_BLOG_ID]."' 
              ,'".$group_data[parent::F_SECTION_ID]."'
              ,'".$group_data[parent::F_STATUS]."' 
              ,'".$group_data[parent::F_TYPE]."' 
              ,'".$group_data[parent::F_LAYER_ID]."' 
              ,'".$group_data[parent::F_ON_EVENT]."' 
              ,'".$group_data[parent::F_LOCK_EVENT]."'
              ,'".$group_data[parent::F_LOCK_EVENT_TYPE]."' 
              ,'".$group_data[parent::F_START_DELAY]."' 
              ,'".$group_data[parent::F_DURATION]."' 
              ,'".$group_data[parent::F_DURATION_B]."' 
              ,'".$group_data[parent::F_OUTPUT]."' 
              ,'".$group_data[parent::F_OUTPUT_POS]."' 
              ,'".$group_data[parent::F_JAVASCRIPT]."' 
              ,'".$group_data[parent::F_CSS]."'
              ,'".$group_data[parent::F_START_ELEMENT_TYPE]."'
              ,'".$group_data[parent::F_START_ELEMENT]."'
              ,'".$group_data[parent::F_START_TOP_POS_SIGN]."'
               ,".$group_data[parent::F_START_TOP_POS]."
              ,'".$group_data[parent::F_START_TOP_POS_FORMAT]."'
              ,'".$group_data[parent::F_START_LEFT_POS_SIGN]."'
               ,".$group_data[parent::F_START_LEFT_POS]."
              ,'".$group_data[parent::F_START_LEFT_POS_FORMAT]."'
              ,'".$group_data[parent::F_POSITION]."'
              ,'".$group_data[parent::F_ZINDEX]."' 
              ,'".$group_data[parent::F_EASING_A]."'
              ,'".$group_data[parent::F_EASING_B]."'
              ,'".$group_data[parent::F_MOVE_ELEMENT_TYPE_A]."'
              ,'".$group_data[parent::F_MOVE_ELEMENT_A]."'
              ,'".$group_data[parent::F_MOVE_TOP_POS_SIGN_A]."'
               ,".$group_data[parent::F_MOVE_TOP_POS_A]."
              ,'".$group_data[parent::F_MOVE_TOP_POS_FORMAT_A]."'
              ,'".$group_data[parent::F_MOVE_LEFT_POS_SIGN_A]."'
               ,".$group_data[parent::F_MOVE_LEFT_POS_A]."
              ,'".$group_data[parent::F_MOVE_LEFT_POS_FORMAT_A]."'
              ,'".$group_data[parent::F_MOVE_ELEMENT_TYPE_B]."'
              ,'".$group_data[parent::F_MOVE_ELEMENT_B]."'
              ,'".$group_data[parent::F_MOVE_TOP_POS_SIGN_B]."'
               ,".$group_data[parent::F_MOVE_TOP_POS_B]."
              ,'".$group_data[parent::F_MOVE_TOP_POS_FORMAT_B]."'
              ,'".$group_data[parent::F_MOVE_LEFT_POS_SIGN_B]."'
               ,".$group_data[parent::F_MOVE_LEFT_POS_B]."
              ,'".$group_data[parent::F_MOVE_LEFT_POS_FORMAT_B]."'
              ,'".$group_data[parent::F_OPTIONS_A]."'
              ,'".$group_data[parent::F_OPTIONS_B]."'
              ,'".$group_data[parent::F_EXTRA_JS_A]."'
              ,'".$group_data[parent::F_EXTRA_JS_B]."'
              ,'".$group_data[parent::F_GROUP_ORDER]."'
              );";    
              
              $code = $wpdb->query($sql);
        }
        
             
        $code = $this->cleanCopiedGroup( $groupid, $old_exportid, $new_export_id);      
        
        $new_id = $this->getId(parent::F_EXPORT_ID, $new_export_id);
        
        $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_GROUP][$new_export_id] = 1;
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $code = update_option('twiz_toggle', $this->toggle_option);
            
        }else{
        
            $code = update_site_option('twiz_toggle', $this->toggle_option);
        }            
        
        
        $ok = $this->reInitializeGroupOrder( $section_id );

        return $new_id;
    }
    
    private function cleanCopiedGroup( $groupid = '', $old_exportid = '', $new_export_id = '' ){
        
        global $wpdb;
        
        $oldarrayid =  array();

        // = = = = = = = = = = = = = = = = =    
        //  Replace new rows only exportid =
        // = = = = = = = = = = = = = = = = =
        
        // get old rows infos.
        $old_list_value = $this->getListArray("WHERE ".parent::F_PARENT_ID." = '".$old_exportid."'");

        // get news rows infos.     
        $new_list_value = $this->getListArray("WHERE ".parent::F_PARENT_ID." = '".$new_export_id."'");
            
        // loop all rows 
        foreach ( $new_list_value as $key => $new_value ){ // loop for each new rows ID
             
             // loop all rows 
             foreach ( $new_list_value as $value ){ // reloop to replace each ID within the upper loop
                
                // Replace all current exportid. all functions and activations vars included
                $updatesql = "UPDATE ".$this->table . " SET
                 ". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", '_".$old_list_value[$key][parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($old_list_value[$key][parent::F_LAYER_ID]))."_".$old_list_value[$key][parent::F_EXPORT_ID]."'
                 , '_".$new_value[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($new_value[parent::F_LAYER_ID]))."_".$new_value[parent::F_EXPORT_ID]."') 
                 
                ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", '_".$old_list_value[$key][parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($old_list_value[$key][parent::F_LAYER_ID]))."_".$old_list_value[$key][parent::F_EXPORT_ID]."'
                , '_".$new_value[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($new_value[parent::F_LAYER_ID]))."_".$new_value[parent::F_EXPORT_ID]."') 

                ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", '_".$old_list_value[$key][parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($old_list_value[$key][parent::F_LAYER_ID]))."_".$old_list_value[$key][parent::F_EXPORT_ID]."'
                , '_".$new_value[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($new_value[parent::F_LAYER_ID]))."_".$new_value[parent::F_EXPORT_ID]."') 
                WHERE ".parent::F_ID." = ".$value[parent::F_ID]."";

                //print $updatesql."\n";

                $code = $wpdb->query($updatesql);
            }                
        }
        
        // = = = = = = = = = = = = = = = = =   
        // Replace new Group only exportid =
        // = = = = = = = = = = = = = = = = =
        
        // get old group infos.
        $old_group = $this->getListArray("WHERE ".parent::F_ID." = '".$groupid."' ");
        $old_group = $old_group[0]; 
        
        // get new group infos.
        $new_group = $this->getListArray("WHERE ".parent::F_EXPORT_ID." = '".$new_export_id."'"); 
        $new_group = $new_group[0]; 
        
        // loop all rows 
        foreach ( $new_list_value as $key => $value ){

            // Replace all current exportid. all functions and activations vars included
            $updatesql = "UPDATE ".$this->table . " SET
             ". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", '_".$old_group[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($old_group[parent::F_LAYER_ID]))."_".$old_group[parent::F_EXPORT_ID]."'
             ,'_".$new_group[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($new_group[parent::F_LAYER_ID]))."_".$new_export_id."') 
            ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", '_".$old_group[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($old_group[parent::F_LAYER_ID]))."_".$old_group[parent::F_EXPORT_ID]."'
            , '_".$new_group[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($new_group[parent::F_LAYER_ID]))."_".$new_export_id."') 
            ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", '_".$old_group[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($old_group[parent::F_LAYER_ID]))."_".$old_group[parent::F_EXPORT_ID]."'
            , '_".$new_group[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($new_group[parent::F_LAYER_ID]))."_".$new_export_id."') 
            WHERE ".parent::F_ID." = ".$value[parent::F_ID]."";

            $code = $wpdb->query($updatesql);                
        }

        return true;
    }
    
    function getHTMLGroupList( $section_id = '' ){
    
        $where = " WHERE ".parent::F_TYPE."='".parent::ELEMENT_TYPE_GROUP."' and ".parent::F_SECTION_ID." = '".$section_id."'";
        $listarray = $this->getListArray( $where );
        
        // Animations
        $html = '<select class="twiz-slc-group" id="twiz_slc_group" name="twiz_slc_group">';
        $html .= '<option value="">'.__('(Optional)', 'the-welcomizer').'</option>';

        foreach ( $listarray as $value ){

            $html .= '<option value="'.$value[parent::F_EXPORT_ID].'">'.$value[parent::F_ID].' - '.$value[parent::F_LAYER_ID].'</option>';
        }
        
        $html .= '</select>';

        return $html;       
    }
    
    function deleteGroup( $id = '', $section_id = '' ){
    
        global $wpdb;
        
        if( $id == '' ){return false;}
        
        $exportid = $this->getValue($id, parent::F_EXPORT_ID);
         
        $clean = $this->cleanTwizFunction( $id );
        
        $sql = "DELETE FROM ".$this->table." WHERE ".parent::F_ID." = '".$id."' OR ".parent::F_PARENT_ID." = '".$exportid."';";
        $code = $wpdb->query($sql);
        
        unset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_GROUP][$exportid]);
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $code = update_option('twiz_toggle', $this->toggle_option);
            
        }else{
        
            $code = update_site_option('twiz_toggle', $this->toggle_option);
        }    
    
        $ok = $this->reInitializeGroupOrder( $section_id );
            
        return true;
    }
    
    function initializeGroupOrder( $section_id = '' ){

        $where = " WHERE ".parent::F_TYPE."='".parent::ELEMENT_TYPE_GROUP."' and ".parent::F_SECTION_ID." = '".$section_id."'";
        $listarray = $this->getListArray( $where, '' ); // get all the data

        $i = 1;
        foreach( $listarray as $key => $value ){ // initialize   
         
            if( $value[parent::F_GROUP_ORDER] == '0' ){
            
                $code = $this->saveGroupValue( $value[parent::F_EXPORT_ID], parent::F_GROUP_ORDER, $i); 
            }
            $i++;
        }
        
        $i = ($i == 1) ? 1 : $i - 1; // 1 is min
        
        return $i;
    }
    
    function reInitializeGroupOrder( $section_id = '' ){

        $where = " WHERE ".parent::F_TYPE."='".parent::ELEMENT_TYPE_GROUP."' and ".parent::F_SECTION_ID." = '".$section_id."'";
        $listarray = $this->getListArray( $where, '' ); // get all the data

        $i = 1;
        foreach( $listarray as $key => $value ){ // initialize   
            
            $code = $this->saveGroupValue( $value[parent::F_EXPORT_ID], parent::F_GROUP_ORDER, $i); 
            
            $i++;
        }

        return true;
    }    
    
    function updateGroupOrder( $id = '', $order = '', $section_id = ''){
           
        $ibase = 1;
           
        $origorder = $this->getValue($id, parent::F_GROUP_ORDER);
        $neworder = $origorder;
        $exportid = $this->getValue($id, parent::F_EXPORT_ID);
        
        $maxkeyorder = $this->initializeGroupOrder( $section_id );

        // get fresh array
        $where = " WHERE ".parent::F_TYPE."='".parent::ELEMENT_TYPE_GROUP."' and ".parent::F_SECTION_ID." = '".$section_id."'";
        $listarray = $this->getListArray( $where, '' ); // get all the data
        
        $i = 1;

        foreach( $listarray as $key => $value ){ // update / set order

            if( $value[parent::F_ID] == $id ){
            
                $ibase = $i;  
                $neworder = $i;
                
                switch($order){

                    case parent::LB_ORDER_UP:

                        $neworder = $neworder - 1;
                        break;
                        
                    case parent::LB_ORDER_DOWN:
                        
                        $neworder = $neworder + 1;
                        break;
                }
                
                $neworder = ( $neworder < 1 ) ? 1 : $neworder;

                if( $maxkeyorder > 1 ){
                
                   $neworder = ( $neworder > $maxkeyorder ) ? $maxkeyorder : $neworder;
                   
                }else if ($maxkeyorder == 1 ){
                
                   $neworder = 1;
                }
            }
            $i++;
         }
         
         $i = 1;
         
         foreach( $listarray as $key => $value ){ // update / set order    
         
            if( $value[parent::F_GROUP_ORDER] == $neworder ){
            
                $code = $this->saveGroupValue( $value[parent::F_EXPORT_ID], parent::F_GROUP_ORDER, $ibase); 
                
            }else{
            
                $code = $this->saveGroupValue( $value[parent::F_EXPORT_ID], parent::F_GROUP_ORDER, $i); 
            }
            $i++;
        }

        foreach( $listarray as $key => $value ){ // switch order

            if( $value[parent::F_ID] == $id ){
            
                $code = $this->saveGroupValue( $value[parent::F_EXPORT_ID], parent::F_GROUP_ORDER, $neworder);    
            }             
        }

        return true;
    }
    
    function saveGroupValue( $exportid = '', $column = '', $value = '' ){ 
        
        global $wpdb;
            
        if( $exportid == '' ){return false;}
        if( $column == '' ){return false;}
    
        $sql = "UPDATE ".$this->table." 
                SET ".$column." = '".$value."'                 
                WHERE ".parent::F_EXPORT_ID." = '".$exportid."'
                OR ".parent::F_PARENT_ID." = '".$exportid."';";
        $code = $wpdb->query($sql);
                   
        return $exportid;
    }
    
    
    function saveGroup( $id = '', $section_id = ''){
    
        if ($id == '') {       
        
           $ok = $this->initializeGroupOrder( $section_id );
           $group_order = 0;
            
        }else{
        
           $group_order = $this->getValue($id, parent::F_GROUP_ORDER);
        }

        // mapping                 
        $_POST['twiz_'.parent::F_EXPORT_ID] = esc_attr(trim($_POST['twiz_group_'.parent::F_EXPORT_ID]));
        $_POST['twiz_'.parent::F_BLOG_ID] = json_encode( $this->getSectionBlogId( $section_id ) );
        $_POST['twiz_'.parent::F_STATUS] = esc_attr(trim($_POST['twiz_group_'.parent::F_STATUS]));
        $_POST['twiz_'.parent::F_LAYER_ID] = esc_attr(trim($_POST['twiz_group_name']));
        $_POST['twiz_'.parent::F_TYPE] = parent::ELEMENT_TYPE_GROUP;
        $_POST['twiz_'.parent::F_ON_EVENT] = parent::EV_MANUAL;
        $_POST['twiz_'.parent::F_GROUP_ORDER] = $group_order;
    
        $exportid = $_POST['twiz_'.parent::F_EXPORT_ID];
        
        // Set Toggle On
        $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_GROUP][$exportid] = 1;
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $code = update_option('twiz_toggle', $this->toggle_option);
            
        }else{
        
            $code = update_site_option('twiz_toggle', $this->toggle_option);
        }    

        $arr = $this->save( $id );
        
        if ($id == '') {       
       
           $ok = $this->reInitializeGroupOrder( $section_id );
        }        
    
        return $arr;
    }    
}?>