<?php
/*  Copyright 2014  Sébastien Laframboise  (email:wordpress@sebastien-laframboise.com)

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

    private $import_dir_abspath;
    private $export_dir;
    private $export_dir_url;
    private $export_dir_abspath;
                                       
    function __construct(){
    
        parent::__construct();
        
        $this->import_dir_abspath =  WP_CONTENT_DIR . parent::IMPORT_PATH;
        $this->export_dir = str_replace(ABSPATH,"", WP_CONTENT_DIR . parent::IMPORT_PATH . parent::EXPORT_PATH);
        $this->export_dir_url =  WP_CONTENT_URL . parent::IMPORT_PATH . parent::EXPORT_PATH;
        $this->export_dir_abspath =  WP_CONTENT_DIR . parent::IMPORT_PATH . parent::EXPORT_PATH ;
    }
    
    private function containsGroup( $file = '' ){

        if ( @file_exists($file) ){
        
            if( $twz = @simplexml_load_file($file) ){

               // flip array mapping value to match 
               $reverse_array_twz_mapping = array_flip($this->array_twz_mapping);
               
                // loop xml entities               
                foreach( $twz->children() as $twzrow ){ 

                    foreach( $twzrow->children() as $twzfield ){
                    
                        $fieldname = '';
                        
                        // get the real name of the field 
                        $fieldname = strtr( $twzfield->getName(), $reverse_array_twz_mapping );
                        
                        $fieldvalue = $twzfield;
                        
                        if(( $fieldname == parent::F_TYPE ) and ( $fieldvalue == parent::ELEMENT_TYPE_GROUP)){                        

                            return true;
                        }
                    }
                }
            }
        }
        
        return false;
    }
    
    function import( $sectionid = parent::DEFAULT_SECTION_HOME , $groupid = '' ){
    
        $exportid = $this->getValue( $groupid, parent::F_EXPORT_ID ); // to import under a group
        $parentid = $exportid;
        $grouporder = $this->getValue($groupid, parent::F_GROUP_ORDER); // to import under a group

        $filearray = $this->getFileDirectory(array(parent::EXT_TWZ, parent::EXT_TWIZ, parent::EXT_XML), $this->import_dir_abspath);
        
        foreach( $filearray as $filename ){
            
            if( $groupid != '' ){
            
                $containsGroup = $this->containsGroup( $this->import_dir_abspath . $filename );
                
                if( $containsGroup ){
                
                    return false;
                }
            }
            
            if( $code = $this->importData( $this->import_dir_abspath . $filename, $sectionid, $parentid, $grouporder) ){
 
                return true;
            }
        }
        
        return true;
    }
    
    function importFromTheServer( $id = '', $sectionid = '', $groupid = '', $action = '' ){
    
        $error = '';
        $htmlresponse = '';
        $exportid = $this->getValue( $groupid, parent::F_EXPORT_ID ); // to import under a group
        $parentid = $exportid;
        $grouporder = $this->getValue($groupid, parent::F_GROUP_ORDER); // to import under a group
        
        $filename =  $this->getFileNamebyID( $id );
        
        if( $groupid != '' ){
        
            $containsGroup = $this->containsGroup(  $this->export_dir_abspath . $filename );
            
            if( $containsGroup ){
            
                $error = __('Group found in file. Groups can not be imported into another group, the import was cancelled.', 'the-welcomizer');
                $htmlresponse = $this->getHtmlList($sectionid, '', '', '' , $action);
            }
        }  
            
        if( $error == '' ){
        
            if( $code = $this->importData($this->export_dir_abspath.$filename, $sectionid, $parentid, $grouporder)){

                $htmlresponse = $this->getHtmlList($sectionid, '', '', $parentid , $action);
                
            }else{
            
                $error = __('An error occured, please try again.', 'the-welcomizer');
                $htmlresponse = $this->getHtmlList($sectionid, '', '', '' , $action);
            }
        }

        $jsonlistarray = json_encode( array('filename' => $filename, 'html'=> $htmlresponse, 'error' => $error )); 
            
        return $jsonlistarray;
    }
    
    private function getFileNamebyID( $id = '' ){
        
        if( $id == ''){ return ''; }
        
        // get all export files
        $export_file_array = $this->getFileDirectory( array(parent::EXT_TWZ, parent::EXT_TWIZ, parent::EXT_XML), $this->export_dir_abspath );
        
        // Loop files
        foreach( $export_file_array as $filename ){
            
            if ( md5($filename) == $id ){
            
                return $filename;
            }
        }
        
        return '';
    }
    
    function importData( $filepath = '', $sectionid = parent::DEFAULT_SECTION_HOME, $parentid = '', $grouporder = 0 ){
        
        $rows = '';
        
        if ( @file_exists($filepath) ){
        
            if( $twz = @simplexml_load_file($filepath) ){

               // flip array mapping value to match 
               $reverse_array_twz_mapping = array_flip($this->array_twz_mapping);
               
                // loop xml entities               
                foreach( $twz->children() as $twzrow ){ 
                
                    $row = array();
                    $row[parent::F_SECTION_ID] = $sectionid;
                    
                    foreach( $twzrow->children() as $twzfield ){
                    
                        $fieldname = '';
                        $fieldvalue = '';
                        
                        // get the real name of the field 
                        $fieldname = strtr( $twzfield->getName(), $reverse_array_twz_mapping );

                        if( $fieldname != "" ){                        

                            $fieldvalue = $twzfield;

                            // build record array 
                            $row[$fieldname] = $fieldvalue;
                        }
                    }
                    $rows[] = $row;
                }
                if(count($rows > 0 )){
                    // insert row  
                    if( !$code = $this->importInsert( $rows, $sectionid, $parentid, $grouporder ) ){
                        
                        return false;
                    }
                }
                // imported      
                return true;
            }
        }
        
        return false;
    }
    
    private function importInsert( $rows = array(), $newsectionid = parent::DEFAULT_SECTION_HOME, $parentid = '', $grouporder = 0 ){
        
        global $wpdb;
        
        $newrows = '';
        $tempdata = '';
        $updatesql = '';
        
        $rows = (!is_array($rows))? array() : $rows;
        
        foreach( $rows as $data ){
            
            if( !isset($data[parent::F_EXPORT_ID]) ) $data[parent::F_EXPORT_ID] = '';
            
            usleep(500);
            
            $exportid = uniqid();
            
            $data[parent::F_EXPORT_ID] = ($data[parent::F_EXPORT_ID]=='')? $exportid : $data[parent::F_EXPORT_ID];
            
            $exportidExists = $this->exportIdExists( $data[parent::F_EXPORT_ID] );
            
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
         
        // Replace export id 
        foreach( $newrows as $data ){ 
                        
            if( !isset($data[parent::F_EXPORT_ID]) ) $data[parent::F_EXPORT_ID] = '';     
            
            foreach( $newrows as $exportid => $newdata ){
            
                if( !isset($newdata[parent::F_JAVASCRIPT]) ) $newdata[parent::F_JAVASCRIPT] = '';     
                if( !isset($newdata[parent::F_EXTRA_JS_A]) ) $newdata[parent::F_EXTRA_JS_A] = '';     
                if( !isset($newdata[parent::F_EXTRA_JS_B]) ) $newdata[parent::F_EXTRA_JS_B] = '';     
                
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
        
            // Fields 
            if( !isset($data[parent::F_SECTION_ID]) ) $data[parent::F_SECTION_ID] = '';
            if( !isset($data[parent::F_EXPORT_ID]) ) $data[parent::F_EXPORT_ID] = '';
            if( !isset($data[parent::F_PARENT_ID]) ) $data[parent::F_PARENT_ID] = '';
            if( !isset($data[parent::F_STATUS]) ) $data[parent::F_STATUS] = '';
            if( !isset($data[parent::F_LAYER_ID]) ) $data[parent::F_LAYER_ID] = '';
            if( !isset($data[parent::F_TYPE]) ) $data[parent::F_TYPE] = '';
            if( !isset($data[parent::F_ON_EVENT]) ) $data[parent::F_ON_EVENT] = '';
            if( !isset($data[parent::F_LOCK_EVENT]) ) $data[parent::F_LOCK_EVENT] = '';
            if( !isset($data[parent::F_LOCK_EVENT_TYPE]) ) $data[parent::F_LOCK_EVENT_TYPE] = '';
            if( !isset($data[parent::F_START_DELAY]) ) $data[parent::F_START_DELAY] = '';
            if( !isset($data[parent::F_DURATION]) ) $data[parent::F_DURATION] = '';
            if( !isset($data[parent::F_DURATION_B]) ) $data[parent::F_DURATION_B] = '';
            if( !isset($data[parent::F_OUTPUT_POS]) ) $data[parent::F_OUTPUT_POS] = '';
            if( !isset($data[parent::F_START_ELEMENT_TYPE]) ) $data[parent::F_START_ELEMENT_TYPE] = '';
            if( !isset($data[parent::F_START_ELEMENT]) ) $data[parent::F_START_ELEMENT] = '';
            if( !isset($data[parent::F_START_TOP_POS_SIGN]) ) $data[parent::F_START_TOP_POS_SIGN] = '';
            if( !isset($data[parent::F_START_TOP_POS]) ) $data[parent::F_START_TOP_POS] = '';
            if( !isset($data[parent::F_START_TOP_POS_FORMAT]) ) $data[parent::F_START_TOP_POS_FORMAT] = '';
            if( !isset($data[parent::F_START_LEFT_POS_SIGN]) ) $data[parent::F_START_LEFT_POS_SIGN] = '';
            if( !isset($data[parent::F_START_LEFT_POS]) ) $data[parent::F_START_LEFT_POS] = '';
            if( !isset($data[parent::F_START_LEFT_POS_FORMAT]) ) $data[parent::F_START_LEFT_POS_FORMAT] = '';
            if( !isset($data[parent::F_POSITION]) ) $data[parent::F_POSITION] = '';
            if( !isset($data[parent::F_ZINDEX]) ) $data[parent::F_ZINDEX] = '';
            if( !isset($data[parent::F_OUTPUT]) ) $data[parent::F_OUTPUT] = '';
            if( !isset($data[parent::F_JAVASCRIPT]) ) $data[parent::F_JAVASCRIPT] = '';
            if( !isset($data[parent::F_CSS]) ) $data[parent::F_CSS] = '';
            if( !isset($data[parent::F_EASING_A]) ) $data[parent::F_EASING_A] = '';
            if( !isset($data[parent::F_MOVE_ELEMENT_TYPE_A]) ) $data[parent::F_MOVE_ELEMENT_TYPE_A] = '';
            if( !isset($data[parent::F_MOVE_ELEMENT_A]) ) $data[parent::F_MOVE_ELEMENT_A] = '';
            if( !isset($data[parent::F_MOVE_TOP_POS_SIGN_A]) ) $data[parent::F_MOVE_TOP_POS_SIGN_A] = '';
            if( !isset($data[parent::F_MOVE_TOP_POS_A]) ) $data[parent::F_MOVE_TOP_POS_A] = '';
            if( !isset($data[parent::F_MOVE_TOP_POS_FORMAT_A]) ) $data[parent::F_MOVE_TOP_POS_FORMAT_A] = '';
            if( !isset($data[parent::F_MOVE_LEFT_POS_SIGN_A]) ) $data[parent::F_MOVE_LEFT_POS_SIGN_A] = '';
            if( !isset($data[parent::F_MOVE_LEFT_POS_A]) ) $data[parent::F_MOVE_LEFT_POS_A] = '';
            if( !isset($data[parent::F_MOVE_LEFT_POS_FORMAT_A]) ) $data[parent::F_MOVE_LEFT_POS_FORMAT_A] = '';
            if( !isset($data[parent::F_OPTIONS_A]) ) $data[parent::F_OPTIONS_A] = '';
            if( !isset($data[parent::F_EXTRA_JS_A]) ) $data[parent::F_EXTRA_JS_A] = '';
            if( !isset($data[parent::F_EASING_B]) ) $data[parent::F_EASING_B] = '';
            if( !isset($data[parent::F_MOVE_ELEMENT_TYPE_B]) ) $data[parent::F_MOVE_ELEMENT_TYPE_B] = '';
            if( !isset($data[parent::F_MOVE_ELEMENT_B]) ) $data[parent::F_MOVE_ELEMENT_B] = '';
            if( !isset($data[parent::F_MOVE_TOP_POS_SIGN_B]) ) $data[parent::F_MOVE_TOP_POS_SIGN_B] = '';
            if( !isset($data[parent::F_MOVE_TOP_POS_B]) ) $data[parent::F_MOVE_TOP_POS_B] = '';
            if( !isset($data[parent::F_MOVE_TOP_POS_FORMAT_B]) ) $data[parent::F_MOVE_TOP_POS_FORMAT_B] = '';
            if( !isset($data[parent::F_MOVE_LEFT_POS_SIGN_B]) ) $data[parent::F_MOVE_LEFT_POS_SIGN_B] = '';
            if( !isset($data[parent::F_MOVE_LEFT_POS_B]) ) $data[parent::F_MOVE_LEFT_POS_B] = '';
            if( !isset($data[parent::F_MOVE_LEFT_POS_FORMAT_B]) ) $data[parent::F_MOVE_LEFT_POS_FORMAT_B] = '';
            if( !isset($data[parent::F_OPTIONS_B]) ) $data[parent::F_OPTIONS_B] = '';
            if( !isset($data[parent::F_EXTRA_JS_B]) ) $data[parent::F_EXTRA_JS_B] = '';
            if( !isset($data[parent::F_GROUP_ORDER]) ) $data[parent::F_GROUP_ORDER] = '';
            

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
            $twiz_duration_b = esc_attr(trim($data[parent::F_DURATION_B]));
            $twiz_group_order = esc_attr(trim($data[parent::F_GROUP_ORDER]));
            
            $twiz_status = ( $twiz_status == '' ) ? '0' : $twiz_status;
            $twiz_lock_event = ( ( $twiz_lock_event == '' ) and ( ( $data[parent::F_ON_EVENT] !='') and ( $data[parent::F_ON_EVENT] !='Manually') ) ) ? '1' : $twiz_lock_event; // old format locked by default
            $twiz_lock_event = ( $twiz_lock_event == '' ) ? '0' : $twiz_lock_event;
            $twiz_lock_event_type = ( $twiz_lock_event_type == '' ) ? 'auto' : $twiz_lock_event_type;
            $twiz_start_delay = ( $twiz_start_delay == '' ) ? '0' : $twiz_start_delay;
            $twiz_duration = ( $twiz_duration == '' ) ? '0' : $twiz_duration;
            $twiz_group_order = ( $twiz_group_order == '' ) ? '0' : $twiz_group_order;
            
            $twiz_parent_id = ( $parentid == "" ) ? esc_attr(trim($data[parent::F_PARENT_ID])) : $parentid; // to import under a group
            $twiz_group_order = ( $grouporder != "" ) ? $grouporder : $twiz_group_order; // to import under a group
    
            // replace section id group function
            $twiz_javascript = str_replace("$(document).twiz_group_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_group_".$newsectionid."_", $data[parent::F_JAVASCRIPT]);
            $twiz_extra_js_a = str_replace("$(document).twiz_group_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_group_".$newsectionid."_", $data[parent::F_EXTRA_JS_A]);
            $twiz_extra_js_b = str_replace("$(document).twiz_group_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_group_".$newsectionid."_", $data[parent::F_EXTRA_JS_B]);
            
            // replace section id function
            $twiz_javascript = str_replace("$(document).twiz_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $twiz_javascript);
            $twiz_extra_js_a = str_replace("$(document).twiz_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $twiz_extra_js_a);
            $twiz_extra_js_b = str_replace("$(document).twiz_".$data[parent::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $twiz_extra_js_b);
            
            // replace section id event
            $twiz_javascript = str_replace("twiz_event_".$data[parent::F_SECTION_ID]."_", "twiz_event_".$newsectionid."_", $twiz_javascript);
            $twiz_extra_js_a = str_replace("twiz_event_".$data[parent::F_SECTION_ID]."_", "twiz_event_".$newsectionid."_", $twiz_extra_js_a);
            $twiz_extra_js_b = str_replace("twiz_event_".$data[parent::F_SECTION_ID]."_", "twiz_event_".$newsectionid."_", $twiz_extra_js_b);            
            
            // replace section id repeat
            $twiz_javascript = str_replace("twiz_repeat_".$data[parent::F_SECTION_ID]."_", "twiz_repeat_".$newsectionid."_", $twiz_javascript);
            $twiz_extra_js_a = str_replace("twiz_repeat_".$data[parent::F_SECTION_ID]."_", "twiz_repeat_".$newsectionid."_", $twiz_extra_js_a);
            $twiz_extra_js_b = str_replace("twiz_repeat_".$data[parent::F_SECTION_ID]."_", "twiz_repeat_".$newsectionid."_", $twiz_extra_js_b);
            
            // replace section id locked
            $twiz_javascript = str_replace("twiz_locked_".$data[parent::F_SECTION_ID]."_", "twiz_locked_".$newsectionid."_", $twiz_javascript);
            $twiz_extra_js_a = str_replace("twiz_locked_".$data[parent::F_SECTION_ID]."_", "twiz_locked_".$newsectionid."_", $twiz_extra_js_a);
            $twiz_extra_js_b = str_replace("twiz_locked_".$data[parent::F_SECTION_ID]."_", "twiz_locked_".$newsectionid."_", $twiz_extra_js_b);
            
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
                 ,".parent::F_ROW_LOCKED."       
                 )VALUES('".$twiz_parent_id."'
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
                 ,'".$twiz_duration_b."'
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
                 ,'".$twiz_group_order."'
                 ,'3'                
                 );"; // Lock
  
                $code = $wpdb->query($sql);
                
                $exportidExists = $this->exportIdExists( $data[parent::F_EXPORT_ID.'_old'] );

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
           
            $code = $this->unlockRows(3);
 
            return true;
    }
    
    private function unlockRows( $value = '' ){
    
        global $wpdb;
        
        $unlockrow = "UPDATE ".$this->table . " SET ". self::F_ROW_LOCKED. " = 0 
                      WHERE ". self::F_ROW_LOCKED . " = ".$value."";
                      
        $code = $wpdb->query($unlockrow);
        
        return $code;
    }
    
    function export( $section_id = '', $id = '', $groupid = ''){
  
        $error = '';
        $filedata = '';
        $myTwizMenu  = new TwizMenu();
        $sectionname = sanitize_title_with_dashes($myTwizMenu->getSectionName($section_id));
        
        if( $id != '' ){
        
           $where = " WHERE ".parent::F_ID." = '".$id."'";
           $id = "_".$id;
           
        }else{
        
        
            $where = ($section_id != '') ? " WHERE ".parent::F_SECTION_ID." = '".$section_id."'" : " WHERE ".parent::F_SECTION_ID." = '".$this->DEFAULT_SECTION[$this->userid]."'";
            
            if( $groupid != '' ){
            
                $exportid = $this->getValue( $groupid, parent::F_EXPORT_ID ); // to import under a group
                $parentid = $exportid;
            
                $where .= " AND ".parent::F_PARENT_ID." = '".$parentid."'" ;
                
                $sectionname .= '_group_'.$groupid.'_'.sanitize_title_with_dashes( $this->getValue( $groupid, parent::F_LAYER_ID ) );
            }
            
        }

      
        $listarray = $this->getListArray( $where );

        $filedataBegin = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        
        $filedataBegin .= '<TWIZ>'."\n";

        foreach( $listarray as $value ){

              $filedata .= '<ROW>'."\n";
              
              $count_array = count($this->array_fields);
                    
              // loop fields array 
              foreach( $this->array_fields as $key ){
              
                  if( $key != parent::F_ID ){
                  
                     if(( $groupid != '' ) and (($key == parent::F_PARENT_ID) or ($key == parent::F_GROUP_ORDER) ) ){
             
                        $filedata .= '<'.$this->array_twz_mapping[$key].'></'.$this->array_twz_mapping[$key].'>'."\n";
                     
                     }else{
                     
                        $filedata .= '<'.$this->array_twz_mapping[$key].'>'.$value[$key].'</'.$this->array_twz_mapping[$key].'>'."\n";
                     }
                  }
              }
                
              $filedata .= '</ROW>'."\n";
              $id = ($id == '') ? '' : $id.'_'.sanitize_title_with_dashes($value[parent::F_LAYER_ID]);
        }
        
        if( $filedata != '' ){
        
            $filedata = $filedataBegin.$filedata .'</TWIZ>'."\n";
            
            $sectionname = ($sectionname == '') ? $section_id.$id : $sectionname.$id;
            
            $sectionname =  str_replace( parent::DEFAULT_SECTION_ALL_ARTICLES, 'allposts', $sectionname );
           
            $filename = urldecode($sectionname).'-'.date('Ymd-His').'.'.parent::EXT_TWIZ;
            $filefullpathdir = $this->export_dir_abspath.$filename;
            $filefullpathurl = $this->export_dir_url .$filename;
     
            if (is_writable($this->export_dir_abspath)){

                if (!$handle = fopen($filefullpathdir, 'w')){
                    $error =  __("Cannot open file", 'the-welcomizer').' ('.$filename.')';
                    exit;
                }

                if (fwrite($handle, $filedata) === FALSE){
                    $error = __("Cannot write to file", 'the-welcomizer').' ('.$filename.')';
                    exit;
                }

                fclose($handle);

            } else {
            
               $error =  __("You must first create those directories<br>and make them writable", 'the-welcomizer').':<br>'.$this->export_path_message;
            }
            
        }else{
        
            $error = __("Nothing to export.", 'the-welcomizer');
        }
        
        $html = ($error!='')? '<div class="twiz-red">' . $error .'</div>' : ' <div id="twiz_img_download_export">'.__('Download file', 'the-welcomizer').'</div> <a href="'.$filefullpathurl.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'">'. $filename .'</a>' ;
        
        return $html;
    }
    
    private function exportIdExists( $exportid = '' ){ 
    
        global $wpdb;
        
        if($exportid==''){return false;}
    
        $sql = "SELECT ".self::F_EXPORT_ID." FROM ".$this->table." WHERE ".self::F_EXPORT_ID." = '".$exportid."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        if($row[self::F_EXPORT_ID]!=''){

            return true;
        }
  
        return false;
    }
    
    function getHTMLExportFileList( $sectionid = '', $filter = '' ){
    
        if( $sectionid == '' ){ return ''; }
    
        $rowcolor = '';
        $myTwizMenu = new TwizMenu();
        $sectionname = $myTwizMenu->getSectionName( $sectionid );
        $twiz_export_filter = get_option('twiz_export_filter');
        
        // set twiz_export_filter array
        if(!isset($twiz_export_filter[$this->userid][$sectionid])) $twiz_export_filter[$this->userid][$sectionid] = '';
         
        // set filter and label
        $filter = ( $twiz_export_filter[$this->userid][$sectionid] == '' ) ? 'twiz_export_filter_none' : $filter;
        $filter = ( $filter == '' ) ? $twiz_export_filter[$this->userid][$sectionid] : $filter ;
        $lbl_filter = ( $filter == 'twiz_export_filter_none' ) ? __('none', 'the-welcomizer') : $filter;        
        $twiz_export_filter[$this->userid][$sectionid] = $filter;
        $filter = ( $filter == 'twiz_export_filter_none' ) ? '' : $filter; 
        $code = update_option('twiz_export_filter',$twiz_export_filter);       
            
        // get all export files
        $export_file_array = $this->getFileDirectory( array(parent::EXT_TWZ, parent::EXT_TWIZ, parent::EXT_XML), $this->export_dir_abspath );
        
        sort($export_file_array);
        
        // set toggle
        if(!isset($this->toggle_option[$this->userid][parent::KEY_TOGGLE_EXPORT])) $this->toggle_option[$this->userid][parent::KEY_TOGGLE_EXPORT] = '';
        if(!isset($this->toggle_option[$this->userid][parent::KEY_TOGGLE_EXPORT]['twizexp0'])) $this->toggle_option[$this->userid][parent::KEY_TOGGLE_EXPORT]['twizexp0'] = '';
        
        // Set Toggle On
        $this->toggle_option[$this->userid][parent::KEY_TOGGLE_EXPORT]['twizexp0'] = '1'; 
         
        if( $this->toggle_option[$this->userid][parent::KEY_TOGGLE_EXPORT]['twizexp0'] == '1' ){
       
            $hide = '';
            $toggleimg = 'twiz-minus';
            $boldclass = ' twiz-bold';
            
        }else{

            $hide = ' twiz-display-none';
            $toggleimg = 'twiz-plus';
            $boldclass = '';
        }

        $html = '<table class="twiz-table-list" cellspacing="0">';

        $html .= '
<tr class="twiz-table-list-tr-h"><td class="twiz-td-v-line"></td><td class="twiz-table-list-td-h twiz-text-left">'.__('Filename', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-ifs-filter twiz-text-left"><span class="twiz-float-left">'.__('Filter', 'the-welcomizer').':&nbsp;</span><div id="twiz_ajax_td_val_ifs_filter" title="'.__('Edit', 'the-welcomizer').'"><a id="twiz_ajax_td_a_ifs_filter">'.$lbl_filter.'</a></div><div id="twiz_ajax_td_loading_ifs_filter"></div><div id="twiz_ajax_td_edit_ifs_filter" class="twiz-display-none"><input class="twiz-input-focus" type="text" name="twiz_input_ifs_filter" id="twiz_input_ifs_filter" value="'.$filter.'" maxlength="100"/></div></td><td class="twiz-table-list-td-h twiz-td-ifs-action twiz-text-right">'.__('Action', 'the-welcomizer').'</td></tr>';
         
        $html.= '
<tr class="twiz-row-color-1"><td><div class="twiz-relative"><div id="twiz_export_img_twizexp0" class="twiz-toggle-export twiz-toggle-img '.$toggleimg.'"></div></div></td>

<td class="twiz-table-list-td" colspan="3"><a id="twiz_export_a_e_twizexp0" class="twiz-toggle-export'.$boldclass.'">'.$this->export_dir.'</a> <div class="twiz-blue twiz-float-right">'.__('Click filename to import', 'the-welcomizer').'</div></td></tr>';
        
        $rowid = 1;
        
        // Loop files
        foreach( $export_file_array as $filename ){
            
            $fileid = md5($filename);
            
            if(preg_match("/".$filter."/i", $filename)){
            
                $rowcolor = ( $rowcolor == 'twiz-row-color-2' ) ? 'twiz-row-color-1' : 'twiz-row-color-2';
                
                $html .= '
    <tr class="twiz-list-tr twizexp0 '.$rowcolor.$hide.'"><td class="twiz-td-v-line twiz-row-color-3">&nbsp;'.$rowid.'</td><td class="twiz-table-list-td" colspan="2">&nbsp;<a id="twiz_import_from_server_'.$fileid.'" title="'.__('Import', 'the-welcomizer').'" class="twiz-import-from-server">'.$filename.'</a></td> <td class="twiz-table-list-td twiz-text-right"><div id="twiz_delete_'.$fileid.'" title="'.__('Delete', 'the-welcomizer').'" class="twiz-delete-export twiz-delete-img"></div></td>';
                
                $rowid++;
            }
        }
        
        if( $rowid == 1 ){

            $html .= '
<tr class="twizexp0'.$hide.' twiz-row-color-2"><td></td>
<td class="twiz-table-list-td twiz-text-center twiz-blue" colspan="4">'. __('No results found.', 'the-welcomizer').'</td>   
</tr>';
        }
            
        $html .= '</table>';

        return $html;
    }
    
    function deleteExportFile( $id = '', $sectionid = '', $filter = '' ){ 
    
        $filename =  $this->export_dir_abspath . $this->getFileNamebyID( $id );
        
        if($filename != ''){
        
            if(@file_exists( $filename )){
             
                unlink( $filename );
            }
        }
       
        $htmlresponse = $this->getHTMLExportFileList( $sectionid, $filter );
        
        return $htmlresponse;
    }
}
?>