<?php
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

    require_once(dirname(__FILE__).'/twiz.menu.class.php'); 
    

class TwizImportExport extends Twiz{
   
                                       
    function __construct(){
    
        parent::__construct();
    }
  
    function import( $sectionid = parent::DEFAULT_SECTION_HOME ){
    
        $filearray = $this->getFileDirectory(array(parent::EXT_TWZ, parent::EXT_TWIZ, parent::EXT_XML), WP_CONTENT_DIR.parent::IMPORT_PATH);
        
        foreach( $filearray as $filename ){
            
            if( $code = $this->importData($filename, $sectionid) ){
 
                return true;
            }
        }
        
        return true;
    }
    
    function importData( $filename = '', $sectionid = parent::DEFAULT_SECTION_HOME ){
 
        /* full file path */
        $file = WP_CONTENT_DIR.parent::IMPORT_PATH.$filename;
        $rows = '';
        if ( @file_exists($file) ) {
        
            if( $twz = @simplexml_load_file($file) ){

               /* flip array mapping value to match */
               $reverse_array_twz_mapping = array_flip($this->array_twz_mapping);
               
                /* loop xml entities */              
                foreach( $twz->children() as $twzrow ) { 
                
                    $row = array();
                    $row[parent::F_SECTION_ID] = $sectionid; 
                    
                    foreach( $twzrow->children() as $twzfield ) {
                    
                        $fieldname = '';
                        $fieldvalue = '';
                        
                        $fieldname = strtr($twzfield->getName(), $reverse_array_twz_mapping);

                        if( $fieldname != "" ) {                        

                            /* get the real name of the field */
                            $fieldvalue = $twzfield;

                            /* build record array */
                            $row[$fieldname] = $fieldvalue;
                        }
                    }
                    $rows[] = $row;
                }
                if(count($rows > 0 )){
                    /* insert row  */
                    if( !$code = $this->importInsert($rows, $sectionid) ){
                        
                        return false;
                    }
                }
                /* imported */     
                return  true;
            }
        }
        
        return false;
    }
    
    private function importInsert( $rows = array(), $newsectionid = parent::DEFAULT_SECTION_HOME  ){
        
        global $wpdb;
        
        $newrows = '';
        $tempdata = '';
        $updatesql = '';
        
        foreach( $rows as $data ){
        
            usleep(100000);
            
            $exportid = uniqid();
            
            $data[parent::F_EXPORT_ID] = ($data[parent::F_EXPORT_ID]=='')? $exportid : $data[parent::F_EXPORT_ID];
            
            $exportidExists = $this->ExportidExists( $data[parent::F_EXPORT_ID] );
            
            if( !isset($data[parent::F_EXPORT_ID.'_old']) ) $data[parent::F_EXPORT_ID.'_old'] = '';
            
            if( $exportidExists == true ){
                
                // New values
                $data[parent::F_EXPORT_ID.'_old'] = $data[parent::F_EXPORT_ID];
                $data[parent::F_EXPORT_ID] = $exportid;
            }
           
            $newrows['key_'.$data[parent::F_EXPORT_ID]] = $data;
            $tempdata['key_'.$data[parent::F_EXPORT_ID]] = $data;
        }
        
        $newrows = ( !is_array($newrows) ) ? array() : $newrows;
         
        foreach( $newrows as $data ){ 
                      
            foreach( $newrows as $exportid => $newdata ){
            
                if($data[parent::F_EXPORT_ID.'_old'] !=''){ 

                    $newdata[parent::F_JAVASCRIPT] = str_replace($data[parent::F_EXPORT_ID.'_old'], $data[parent::F_EXPORT_ID], $newdata[parent::F_JAVASCRIPT]);
                    $newdata[parent::F_EXTRA_JS_A] = str_replace($data[parent::F_EXPORT_ID.'_old'], $data[parent::F_EXPORT_ID], $newdata[parent::F_EXTRA_JS_A]);
                    $newdata[parent::F_EXTRA_JS_B] = str_replace($data[parent::F_EXPORT_ID.'_old'], $data[parent::F_EXPORT_ID], $newdata[parent::F_EXTRA_JS_B]); 
 
                    $tempdata[$exportid] = $newdata;
                }
                $newrows = $tempdata;
            }
        }

        $rows = ( !is_array($tempdata) ) ? array() : $tempdata;
       
        foreach( $rows as $data ){
        
  
            /* Fields added after */
            if( !isset($data[parent::F_ON_EVENT]) ) $data[parent::F_ON_EVENT] = '';
            if( !isset($data[parent::F_LOCK_EVENT]) ) $data[parent::F_LOCK_EVENT] = '';
            if( !isset($data[parent::F_LOCK_EVENT_TYPE]) ) $data[parent::F_LOCK_EVENT_TYPE] = '';
            if( !isset($data[parent::F_JAVASCRIPT]) ) $data[parent::F_JAVASCRIPT] = '';
            if( !isset($data[parent::F_CSS]) ) $data[parent::F_CSS] = '';
            if( !isset($data[parent::F_ZINDEX]) ) $data[parent::F_ZINDEX] = '';
            if( !isset($data[parent::F_TYPE]) ) $data[parent::F_TYPE] = '';
            if( !isset($data[parent::F_OUTPUT]) ) $data[parent::F_OUTPUT] = '';
            if( !isset($data[parent::F_OUTPUT_POS]) ) $data[parent::F_OUTPUT_POS] = '';
            if( !isset($data[parent::F_PARENT_ID]) ) $data[parent::F_PARENT_ID] = '';
            if( !isset($data[parent::F_EXPORT_ID]) ) $data[parent::F_EXPORT_ID] = '';
            if( !isset($data[parent::F_SECTION_ID]) ) $data[parent::F_SECTION_ID] = '';
            if( !isset($data[parent::F_START_ELEMENT_TYPE]) ) $data[parent::F_START_ELEMENT_TYPE] = '';
            if( !isset($data[parent::F_START_ELEMENT]) ) $data[parent::F_START_ELEMENT] = '';
            if( !isset($data[parent::F_START_TOP_POS_FORMAT]) ) $data[parent::F_START_TOP_POS_FORMAT] = '';
            if( !isset($data[parent::F_START_LEFT_POS_FORMAT]) ) $data[parent::F_START_LEFT_POS_FORMAT] = '';
            if( !isset($data[parent::F_MOVE_ELEMENT_TYPE_A]) ) $data[parent::F_MOVE_ELEMENT_TYPE_A] = '';
            if( !isset($data[parent::F_MOVE_ELEMENT_A]) ) $data[parent::F_MOVE_ELEMENT_A] = '';               
            if( !isset($data[parent::F_MOVE_TOP_POS_FORMAT_A]) ) $data[parent::F_MOVE_TOP_POS_FORMAT_A] = '';
            if( !isset($data[parent::F_MOVE_LEFT_POS_FORMAT_A]) ) $data[parent::F_MOVE_LEFT_POS_FORMAT_A] = '';
            if( !isset($data[parent::F_MOVE_ELEMENT_TYPE_B]) ) $data[parent::F_MOVE_ELEMENT_TYPE_B] = '';
            if( !isset($data[parent::F_MOVE_ELEMENT_B]) ) $data[parent::F_MOVE_ELEMENT_B] = '';            
            if( !isset($data[parent::F_MOVE_TOP_POS_FORMAT_B]) ) $data[parent::F_MOVE_TOP_POS_FORMAT_B] = '';
            if( !isset($data[parent::F_MOVE_LEFT_POS_FORMAT_B]) ) $data[parent::F_MOVE_LEFT_POS_FORMAT_B] = '';
            if( !isset($data[parent::F_EASING_A]) ) $data[parent::F_EASING_A] = '';
            if( !isset($data[parent::F_EASING_B]) ) $data[parent::F_EASING_B] = '';
            
            $twiz_start_element_type = esc_attr(trim($data[parent::F_START_ELEMENT_TYPE]));
            $twiz_start_element = esc_attr(trim($data[parent::F_START_ELEMENT]));        
            $twiz_move_element_type_a = esc_attr(trim($data[parent::F_MOVE_ELEMENT_TYPE_A]));
            $twiz_move_element_a = esc_attr(trim($data[parent::F_MOVE_ELEMENT_A]));        
            $twiz_move_element_type_b = esc_attr(trim($data[parent::F_MOVE_ELEMENT_TYPE_B]));
            $twiz_move_element_b = esc_attr(trim($data[parent::F_MOVE_ELEMENT_B]));
        
            $twiz_move_top_pos_a  = esc_attr(trim($data[parent::F_MOVE_TOP_POS_A]));
            $twiz_move_left_pos_a = esc_attr(trim($data[parent::F_MOVE_LEFT_POS_A]));
            $twiz_move_top_pos_b  = esc_attr(trim($data[parent::F_MOVE_TOP_POS_B]));
            $twiz_move_left_pos_b = esc_attr(trim($data[parent::F_MOVE_LEFT_POS_B]));
            $twiz_start_top_pos   = esc_attr(trim($data[parent::F_START_TOP_POS]));
            $twiz_start_left_pos  = esc_attr(trim($data[parent::F_START_LEFT_POS]));
            
            $twiz_move_top_pos_a  = ($twiz_move_top_pos_a=='') ? 'NULL' : $twiz_move_top_pos_a;
            $twiz_move_left_pos_a = ($twiz_move_left_pos_a=='') ? 'NULL' : $twiz_move_left_pos_a;
            $twiz_move_top_pos_b  = ($twiz_move_top_pos_b=='') ? 'NULL' : $twiz_move_top_pos_b;
            $twiz_move_left_pos_b = ($twiz_move_left_pos_b=='') ? 'NULL' : $twiz_move_left_pos_b;
            $twiz_start_top_pos   = ($twiz_start_top_pos=='') ? 'NULL' : $twiz_start_top_pos;
            $twiz_start_left_pos  = ($twiz_start_left_pos=='') ? 'NULL' : $twiz_start_left_pos;
          
            $twiz_javascript = str_replace("\\", "\\\\" , $data[parent::F_JAVASCRIPT]);
            $twiz_css = str_replace("\\", "\\\\" , $data[parent::F_CSS]);
            $twiz_extra_js_a = str_replace("\\", "\\\\" , $data[parent::F_EXTRA_JS_A]);
            $twiz_extra_js_b = str_replace("\\", "\\\\" , $data[parent::F_EXTRA_JS_B]);
            
            $twiz_status = esc_attr(trim($data[parent::F_STATUS]));
            $twiz_lock_event = esc_attr(trim($data[parent::F_LOCK_EVENT]));
            $twiz_lock_event_type = esc_attr(trim($data[parent::F_LOCK_EVENT_TYPE]));
            $twiz_start_delay = esc_attr(trim($data[parent::F_START_DELAY]));
            $twiz_duration = esc_attr(trim($data[parent::F_DURATION]));
            
            $twiz_status = ( $twiz_status == '' ) ? '0' : $twiz_status;
            $twiz_lock_event = ( ( $twiz_lock_event == '' ) and ( ( $data[parent::F_ON_EVENT] !='') and ( $data[parent::F_ON_EVENT] !='Manually') ) ) ? '1' : $twiz_lock_event; // old format locked by default
            $twiz_lock_event = ( $twiz_lock_event == '' ) ? '0' : $twiz_lock_event;
            $twiz_lock_event_type = ( $twiz_lock_event_type == '' ) ? 'auto' : $twiz_lock_event_type;
            $twiz_start_delay = ( $twiz_start_delay == '' ) ? '0' : $twiz_start_delay;
            $twiz_duration = ( $twiz_duration == '' ) ? '0' : $twiz_duration;
            
            $group = '';
    
            // replace section id
            $twiz_javascript = str_replace("$(document).twiz_group_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_group_".$newsectionid."_", $data[parent::F_JAVASCRIPT]);
            $twiz_extra_js_a = str_replace("$(document).twiz_group_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_group_".$newsectionid."_", $data[parent::F_EXTRA_JS_A]);
            $twiz_extra_js_b = str_replace("$(document).twiz_group_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_group_".$newsectionid."_", $data[parent::F_EXTRA_JS_B]);  
            
            // replace section id part2
            $twiz_javascript = str_replace("$(document).twiz_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $twiz_javascript);
            $twiz_extra_js_a = str_replace("$(document).twiz_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $twiz_extra_js_a);
            $twiz_extra_js_b = str_replace("$(document).twiz_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $twiz_extra_js_b);
            
           // replace section id part3
            $twiz_javascript = str_replace("twiz_event_".$data[parent::F_SECTION_ID]."_", "twiz_event_".$newsectionid."_", $twiz_javascript);
            $twiz_extra_js_a = str_replace("twiz_event_".$data[parent::F_SECTION_ID]."_", "twiz_event_".$newsectionid."_", $twiz_extra_js_a);
            $twiz_extra_js_b = str_replace("twiz_event_".$data[parent::F_SECTION_ID]."_", "twiz_event_".$newsectionid."_", $twiz_extra_js_b);
            
            // default output pos for older export files. b r default, because no backward v to check
            $data[parent::F_OUTPUT] = ($data[parent::F_OUTPUT] == '')? 'b' : $data[parent::F_OUTPUT];
            $data[parent::F_OUTPUT_POS] = ($data[parent::F_OUTPUT_POS] == '') ? 'r' : $data[parent::F_OUTPUT_POS];
            $data[parent::F_START_TOP_POS_FORMAT] = ($data[parent::F_START_TOP_POS_FORMAT] == '') ? parent::FORMAT_PIXEL : $data[parent::F_START_TOP_POS_FORMAT];
            $data[parent::F_START_LEFT_POS_FORMAT] = ($data[parent::F_START_LEFT_POS_FORMAT] == '') ? parent::FORMAT_PIXEL : $data[parent::F_START_LEFT_POS_FORMAT];
            $data[parent::F_MOVE_TOP_POS_FORMAT_A] = ($data[parent::F_MOVE_TOP_POS_FORMAT_A] == '') ? parent::FORMAT_PIXEL : $data[parent::F_MOVE_TOP_POS_FORMAT_A];
            $data[parent::F_MOVE_LEFT_POS_FORMAT_A] = ($data[parent::F_MOVE_LEFT_POS_FORMAT_A] == '') ? parent::FORMAT_PIXEL : $data[parent::F_MOVE_LEFT_POS_FORMAT_A];
            $data[parent::F_MOVE_TOP_POS_FORMAT_B] = ($data[parent::F_MOVE_TOP_POS_FORMAT_B] == '') ? parent::FORMAT_PIXEL : $data[parent::F_MOVE_TOP_POS_FORMAT_B];
            $data[parent::F_MOVE_LEFT_POS_FORMAT_B] = ($data[parent::F_MOVE_LEFT_POS_FORMAT_B] == '') ? parent::FORMAT_PIXEL : $data[parent::F_MOVE_LEFT_POS_FORMAT_B];
            
            $data[parent::F_EASING_A] = ($data[parent::F_EASING_A] == '')? 'swing' : $data[parent::F_EASING_A];
            $data[parent::F_EASING_B] = ($data[parent::F_EASING_B] == '')? 'swing' : $data[parent::F_EASING_B];
  
                    
            $sql = "INSERT INTO ".$this->table." 
                 (".parent::F_PARENT_ID."
                 ,".parent::F_EXPORT_ID."
                 ,".parent::F_SECTION_ID."
                 ,".parent::F_STATUS."
                 ,".parent::F_TYPE."
                 ,".parent::F_LAYER_ID."
                 ,".parent::F_ON_EVENT."
                 ,".parent::F_LOCK_EVENT."
                 ,".parent::F_LOCK_EVENT_TYPE."
                 ,".parent::F_START_DELAY."
                 ,".parent::F_DURATION."
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
                 ,".parent::F_ROW_LOCKED."       
                 )VALUES('".esc_attr(trim($data[parent::F_PARENT_ID]))."'
                 ,'".esc_attr(trim($data[parent::F_EXPORT_ID]))."'
                 ,'".$newsectionid."'
                 ,'".$twiz_status."'
                 ,'".esc_attr(trim($data[parent::F_TYPE]))."'
                 ,'".esc_attr(trim($data[parent::F_LAYER_ID]))."'
                 ,'".esc_attr(trim($data[parent::F_ON_EVENT]))."'             
                 ,'".$twiz_lock_event."'             
                 ,'".$twiz_lock_event_type."'             
                 ,'".$twiz_start_delay."'
                 ,'".$twiz_duration."'
                 ,'".esc_attr(trim($data[parent::F_OUTPUT]))."'
                 ,'".esc_attr(trim($data[parent::F_OUTPUT_POS]))."'
                 ,'".esc_attr($twiz_javascript)."'   
                 ,'".esc_attr($twiz_css)."'   
                 ,'".$twiz_start_element_type."'
                 ,'".$twiz_start_element."'                 
                 ,'".esc_attr(trim($data[parent::F_START_TOP_POS_SIGN]))."'    
                 ,".$twiz_start_top_pos."
                 ,'".esc_attr(trim($data[parent::F_START_TOP_POS_FORMAT]))."'   
                 ,'".esc_attr(trim($data[parent::F_START_LEFT_POS_SIGN]))."'    
                 ,".$twiz_start_left_pos."
                 ,'".esc_attr(trim($data[parent::F_START_LEFT_POS_FORMAT]))."'    
                 ,'".esc_attr(trim($data[parent::F_POSITION]))."'
                 ,'".esc_attr(trim($data[parent::F_ZINDEX]))."'                      
                 ,'".esc_attr(trim($data[parent::F_EASING_A]))."'  
                 ,'".esc_attr(trim($data[parent::F_EASING_B]))."'    
                 ,'".$twiz_move_element_type_a."'
                 ,'".$twiz_move_element_a."'                  
                 ,'".esc_attr(trim($data[parent::F_MOVE_TOP_POS_SIGN_A]))."'    
                 ,".$twiz_move_top_pos_a."
                 ,'".esc_attr(trim($data[parent::F_MOVE_TOP_POS_FORMAT_A]))."'    
                 ,'".esc_attr(trim($data[parent::F_MOVE_LEFT_POS_SIGN_A]))."'    
                 ,".$twiz_move_left_pos_a."
                 ,'".esc_attr(trim($data[parent::F_MOVE_LEFT_POS_FORMAT_A]))."'    
                 ,'".$twiz_move_element_type_b."'
                 ,'".$twiz_move_element_b."'                  
                 ,'".esc_attr(trim($data[parent::F_MOVE_TOP_POS_SIGN_B]))."'                     
                 ,".$twiz_move_top_pos_b."
                 ,'".esc_attr(trim($data[parent::F_MOVE_TOP_POS_FORMAT_B]))."'                     
                 ,'".esc_attr(trim($data[parent::F_MOVE_LEFT_POS_SIGN_B]))."'    
                 ,".$twiz_move_left_pos_b."
                 ,'".esc_attr(trim($data[parent::F_MOVE_LEFT_POS_FORMAT_B]))."'    
                 ,'".esc_attr(trim($data[parent::F_OPTIONS_A]))."'                             
                 ,'".esc_attr(trim($data[parent::F_OPTIONS_B]))."'
                 ,'".esc_attr($twiz_extra_js_a)."'                             
                 ,'".esc_attr($twiz_extra_js_b)."'                 
                 ,'3'                
                 );"; // Lock
                
                $code = $wpdb->query($sql);
                
                $exportidExists = $this->ExportidExists( $data[parent::F_EXPORT_ID.'_old'] );

                if( $exportidExists == true ){

                    // Update new parent_id
                    $updatesql[] = "UPDATE ".$this->table . " SET
                    ". parent::F_PARENT_ID . " = '".$data[parent::F_EXPORT_ID] ."' 
                    WHERE ". parent::F_PARENT_ID . " = '".$data[parent::F_EXPORT_ID.'_old']."' 
                    AND ". parent::F_ROW_LOCKED . " = '3';";
                }                
            }
                     
            // Update new parent_id
            $updatesql = ( !is_array($updatesql) ) ? array() : $updatesql;
            foreach( $updatesql as $sql ){ 
                $code = $wpdb->query($sql);
            }
           
            $code = $this->UnlockRows(3);
 
            return true;
    }
    
    function export( $section_id = '', $id = '' ){
  
        $sectionname = '';
        $error = '';
        
        if( $id != '' ) {
        
           $where = " WHERE ".parent::F_ID." = '".$id."'";
           $id = "_".$id;
        }else{
        
            $where = ($section_id != '') ? " WHERE ".parent::F_SECTION_ID." = '".$section_id."'" : " WHERE ".parent::F_SECTION_ID." = '".$this->DEFAULT_SECTION[$this->userid]."'";
        }
     
        $listarray = $this->getListArray( $where ); 

        $filedata = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        
        $filedata .= '<TWIZ>'."\n";

        foreach( $listarray as $value ){

              if ( $sectionname == '' ) {
              
                  $myTwizMenu  = new TwizMenu(); 
                  $sectionname = sanitize_title_with_dashes($myTwizMenu->getSectionName($value[parent::F_SECTION_ID]));
              }
              
              $filedata .= '<ROW>'."\n";
              
              $count_array = count($this->array_fields);
                    
              /* loop fields array */
              foreach( $this->array_fields as $key ){
              
                  if( $key != parent::F_ID ){
             
                     $filedata .= '<'.$this->array_twz_mapping[$key].'>'.$value[$key].'</'.$this->array_twz_mapping[$key].'>'."\n";
                  }
              }
                
              $filedata .= '</ROW>'."\n";
              $id = ($id == '') ? '' : $id.'_'.sanitize_title_with_dashes($value[parent::F_LAYER_ID]);
        }

        $filedata .= '</TWIZ>'."\n";
        
        $sectionname = ($sectionname == '') ? $sectionname = $section_id.$id : $sectionname.$id;
        $sectionname =  str_replace(parent::DEFAULT_SECTION_ALL_ARTICLES, 'allposts', $sectionname);
       
        $filename = urldecode($sectionname).".".parent::EXT_TWIZ;
        $filepath = parent::IMPORT_PATH.parent::EXPORT_PATH;
        $filepathdir = WP_CONTENT_DIR.$filepath;
        $filefullpathdir = $filepathdir.$filename;
        $filefullpathurl = WP_CONTENT_URL.$filepath.$filename;
 
        if (is_writable($filepathdir)) {

            if (!$handle = fopen($filefullpathdir, 'w')) {
                $error =  __("Cannot open file", 'the-welcomizer').' ('.$filename.')';
                exit;
            }

            if (fwrite($handle, $filedata) === FALSE) {
                $error = __("Cannot write to file", 'the-welcomizer').' ('.$filename.')';
                exit;
            }

            fclose($handle);

        } else {
           $error =  __("You must first create this directory", 'the-welcomizer').':<br>'.$this->export_path_message;
        }
        
        $html = ($error!='')? '<div class="twiz-red">' . $error .'</div>' : ' <a href="'.$filefullpathurl.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'"><img name="twiz_img_download_export" id="twiz_img_download_export" src="'.$this->pluginUrl.'/images/twiz-download.png" /></a><a href="'.$filefullpathurl.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'">'.__('Download file', 'the-welcomizer').'<br>'. $filename .'</a>' ;
        
        return $html;
    }
}
?>