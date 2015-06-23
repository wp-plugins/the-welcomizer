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

require_once(dirname(__FILE__).'/twiz.menu.class.php');
    

class TwizImportExport extends Twiz{

    public $import_dir_abspath;
    private $export_dir_label;
    private $export_dir_url;
    private $export_dir_abspath;
    private $backup_dir_url;
    private $backup_dir_abspath;
                                       
    function __construct(){
    
        parent::__construct();

        if( !is_multisite() ){
        
            $wp_content_url =  WP_CONTENT_URL;
            
        }else{
        
            $wp_content_url =  network_site_url( '/' ).'wp-content'; 
        }
        
        $this->import_dir_abspath =  WP_CONTENT_DIR . parent::IMPORT_PATH;
        $this->export_dir_label = str_replace(ABSPATH,"", WP_CONTENT_DIR . parent::IMPORT_PATH . parent::EXPORT_PATH);
        $this->export_dir_url = $wp_content_url . parent::IMPORT_PATH . parent::EXPORT_PATH;
        $this->export_dir_abspath =  WP_CONTENT_DIR . parent::IMPORT_PATH . parent::EXPORT_PATH;
        $this->backup_dir_url = $wp_content_url . parent::IMPORT_PATH . parent::EXPORT_PATH . parent::BACKUP_PATH;
        $this->backup_dir_abspath =  WP_CONTENT_DIR . parent::IMPORT_PATH . parent::EXPORT_PATH . parent::BACKUP_PATH;
    }
    
    function containsGroup( $file = '' ){

        if ( @file_exists($file) ){
        
            if( $twz = @simplexml_load_file($file) ){

               // flip array mapping value to match 
               $reverse_array_twz_mapping = array_flip($this->array_twz_mapping);
               
                // loop xml entities               
                foreach( $twz->children() as $twzrow ){ 
                
                    if($twzrow->getName() == 'ROW'){
                    
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
        }
        
        return false;
    }
    
    function isEmptySection( $file = '' ){

        $isEmptySection = true;

        if ( @file_exists($file) ){
        
            if( $twz = @simplexml_load_file($file) ){

               // flip array mapping value to match 
               $reverse_array_twz_mapping = array_flip($this->array_twz_mapping);
               
                // loop xml entities               
                foreach( $twz->children() as $twzrow ){ 
                
                    if($twzrow->getName() == 'SECTION'){
                    
                        foreach( $twzrow->children() as $twzfield ){
                        
                            $isEmptySection = false;
                        }
                    }
                }
            }
        }
        
        return $isEmptySection;
    }
    
    function import( $sectionid = '' , $groupid = '' ){
    
        $exportid = $this->getValue( $groupid, parent::F_EXPORT_ID ); // to import under a group
        $parentid = $exportid;
        $grouporder = $this->getValue($groupid, parent::F_GROUP_ORDER); // to import under a group

        $filearray = $this->getFileDirectory(array(parent::EXT_TWZ, parent::EXT_TWIZ, parent::EXT_XML), $this->import_dir_abspath);
        
        foreach( $filearray as $filename ){
            
            if( $sectionarray = $this->importData( $this->import_dir_abspath . $filename, $sectionid, $parentid, $grouporder) ){
 
                return $sectionarray;
                
            }else{
            
                return false;
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
        
        if( $groupid != '' ){ // import under a group
        
            $containsGroup = $this->containsGroup(  $this->export_dir_abspath . $filename );
            
            if( $containsGroup ){
            
                $error = __('Group found in file. Groups can not be imported into another group, the import was cancelled.', 'the-welcomizer');
                $htmlresponse = $this->getHtmlList($sectionid, '', '', '' , $action);
            }
        }  
        
        if( $sectionid == '' ){ // Add new section
        
            $isEmptySection = $this->isEmptySection(  $this->export_dir_abspath . $filename );
            
            if( $isEmptySection ){
            
                $error = __('No section tag found in file. Section can not be created, the import was cancelled.', 'the-welcomizer');
                $htmlresponse = $this->getHtmlList($sectionid, '', '', '' , $action);
            }
        }              
        
        if( $error == '' ){
        
            if( $imported_section = $this->importData($this->export_dir_abspath.$filename, $sectionid, $parentid, $grouporder) ){
            
                $htmlresponse = $this->getHtmlList($imported_section['section_id'], '', '', $parentid , $action);
                
            }else{
            
                $error = __('An error occured, please try again.', 'the-welcomizer');
                $htmlresponse = $this->getHtmlList($sectionid, '', '', '' , $action);
            }
        }
        
        if( !isset( $imported_section['section_id'] ) ){ $imported_section['section_id'] = ''; }

        $jsonlistarray = json_encode( array('sectionid' => $sectionid, 'newsectionid' => $imported_section['section_id'], 'filename' => $filename, 'html'=> $htmlresponse, 'error' => $error ) ); 
            
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
    
    function importData( $filepath = '', $sectionid = '', $parentid = '', $grouporder = 0 ){
        
        $rows = '';
        $section = '';
        $site = '';
        $isnewsection = '';
        $siteurl = '';
        
        if ( @file_exists($filepath) ){
            
            if( $twz = @simplexml_load_file($filepath) ){

               // flip array mapping value to match 
               $reverse_array_twz_mapping = array_flip($this->array_twz_mapping);
               $reverse_array_twz_section_mapping = array_flip($this->array_twz_section_mapping);
               
                // loop xml entities               
                foreach( $twz->children() as $twzrow ){ 

                    switch( $twzrow->getName() ){
                    
                        case 'URL':
                        
                            $siteurl = trim($twzrow);
                            
                            break;
                            
                        case 'SECTION':
                        
                            if( $sectionid == '' ){ // Add new section

                                $isnewsection = 1;
                            
                                foreach( $twzrow->children() as $twzfield ){
                                
                                    $fieldname = '';
                                    $fieldvalue = '';
                                    
                                    // get the real name of the field 
                                    $fieldname = strtr( $twzfield->getName(), $reverse_array_twz_section_mapping );

                                    switch( $fieldname ){
                                        
                                        case parent::F_BLOG_ID: 
                                        
                                            if( ( $siteurl == get_site_url() ) 
                                            or ( ( $this->override_network_settings != '1' ) 
                                            and( ( preg_match("[".$siteurl."]i", get_site_url())) 
                                            or( preg_match("[".get_site_url()."]i", $siteurl) ) ) )
                                            ){
                                            
                                                $section[$fieldname] = esc_attr(trim($twzfield));
                                                
                                            }else{ // from another website
                                            
                                                $section[$fieldname] = json_encode(array($this->BLOG_ID)); 
                                            }
                                            
                                            break;
                                            
                                        case parent::KEY_TITLE: 
                                            
                                            $section[$fieldname] = esc_attr(trim($twzfield)); 
                                            $section[$fieldname] = __($section[$fieldname], 'the-welcomizer'); // translates hardsections
                                            
                                            break;
                                            
                                        case parent::KEY_SHORTCODE: 
                                            
                                            $section[$fieldname] = esc_attr(trim($twzfield)); 
                                            
                                            if( $section[$fieldname] == "" ){ // use the title as shortcode
                                            
                                                $section[$fieldname] = $section[parent::KEY_TITLE];
                                            }
                                            
                                            break;

                                        case parent::KEY_MULTI_SECTIONS: 
                                            
                                            if( ( $siteurl == get_site_url() ) 
                                            or ( ( $this->override_network_settings != '1' ) 
                                            and( ( preg_match("[".$siteurl."]i", get_site_url())) 
                                            or( preg_match("[".get_site_url()."]i", $siteurl) ) ) )
                                            ){
                                            
                                                // rebuild array quotes.
                                                $twzfield = str_replace(',', '","', esc_attr(trim($twzfield)));
                                                $twzfield = str_replace('[', '["', $twzfield);
                                                $twzfield = str_replace(']', '"]', $twzfield);
                                                $section[$fieldname] = json_decode($twzfield); 
                                                
                                            }else{ // from another website
                                            
                                                $section[$fieldname] = array();
                                            }
                                            
                                            break;
                                            
                                        default:          
                                        
                                            if( $fieldname != "" ){  
                                            
                                                $section[$fieldname] = esc_attr(trim($twzfield)); 
                                            }
                                            
                                            break;
                                    }
                                }
                                 
                                if( ( $siteurl == get_site_url() ) 
                                or ( ( $this->override_network_settings != '1' ) 
                                and( ( preg_match("[".$siteurl."]i", get_site_url())) 
                                or( preg_match("[".get_site_url()."]i", $siteurl) ) ) )
                                ){}else{// from another website
                                
                                    if( true == $this->matchDefaultSection( $section[parent::F_SECTION_ID] )  ){
                                    
                                        $type = 'default';
                                        
                                    }else{
                                    
                                        list($type, $id ) = preg_split('/_/', $section[parent::F_SECTION_ID]);
                                    }
                                    
                                    if( $type != 'cl' ){// all type custom logic excluded
                                    
                                        $section[parent::F_SECTION_ID.'_orig'] = 'sc_1'; // convert default section into shortcode
                                        $section[parent::F_SECTION_ID] = '';
                                        $section[parent::KEY_MULTI_SECTIONS] = array();
                                    }
                                }
                                
                                if(!is_array($section)){$section = array();}

                                if( count( $section > 0 ) ){
                        
                                    $myTwizMenu  = new TwizMenu();

                                    if( ( !isset( $myTwizMenu->array_hardsections[$section[parent::F_SECTION_ID]] ) ) 
                                    and ( !isset( $myTwizMenu->array_sections[$section[parent::F_SECTION_ID]] ) ) ){

                                        $section[parent::F_SECTION_ID.'_orig'] = $section[parent::F_SECTION_ID]; // _orig is used later to get the type
                                        
                                        // is default section
                                        if( true == $this->matchDefaultSection( $section[parent::F_SECTION_ID] ) ){
                                        
                                            $section[parent::F_SECTION_ID.'_orig'] = 'sc_1'; // convert default section into shortcode
                                            $section[parent::F_SECTION_ID] = '';  // Empty for add new 
                                        }
                                        
                                        $sectionid = $myTwizMenu->saveSectionMenu( $section[parent::KEY_MULTI_SECTIONS], $section );
                                       // print_r($section);
                                    }else{// this section already exists, make new. reset sectionid
                                    
                                        $section[parent::F_SECTION_ID.'_orig'] = $section[parent::F_SECTION_ID]; 
                                    
                                        // is default section
                                        if( true == $this->matchDefaultSection( $section[parent::F_SECTION_ID] ) ){
                                        
                                            $section[parent::F_SECTION_ID.'_orig'] = 'sc_1'; // convert default section into shortcode
                                            $section[parent::F_SECTION_ID] = '';  // Empty for add new
 
                                        }else{
                                        
                                            $section[parent::F_SECTION_ID] = '';
                                        }
                                        
                                        $sectionid = $myTwizMenu->saveSectionMenu( $section[parent::KEY_MULTI_SECTIONS], $section );
                                    }
                                }
                            }
  
                            break;
                            
                        case 'ROW':

                            $row = array();
                            $row[parent::F_SECTION_ID] = $sectionid;
                            
                            foreach( $twzrow->children() as $twzfield ){
                            
                                $fieldname = '';
                                $fieldvalue = '';
                                
                                // get the real name of the field 
                                $fieldname = strtr( $twzfield->getName(), $reverse_array_twz_mapping );
            
                                if($fieldname != ""){
                                
                                    switch( $fieldname ){
                                    
                                        case parent::F_BLOG_ID:
                                            
                                            $row[$fieldname] = esc_attr(trim($twzfield));
                                        
                                            break;
                                            
                                        default:                                       
                                        
                                            $row[$fieldname] = esc_attr(trim($twzfield)); 
                                                
                                            break;
                                    }
                                }
                            }
                            // building record array 
                            $rows[] = $row;
                            
                            break;
                    }
                }
                
                if(!is_array($rows)){$rows = array();}

                if( count( $rows > 0 ) ){
                    
                    // insert rows
                    if( !$code = $this->importInsert( $rows, $sectionid, $parentid, $grouporder, $siteurl ) ){
                        
                        return false;
                    }
                }
                
                // imported      
                return array('section_id' => $sectionid, 'isnewsection' => $isnewsection);
            }
        }
        
        return false;
    }
    
    private function importInsert( $rows = array(), $newsectionid = '', $parentid = '', $grouporder = 0, $siteurl = '' ){
        
        global $wpdb;
        
        $newrows = '';
        $tempdata = '';
        $updatesql = '';

        $rows = (!is_array($rows))? array() : $rows;
        foreach( $rows as $data ){
            
            if( !isset($data[parent::F_EXPORT_ID]) ) $data[parent::F_EXPORT_ID] = '';
            
            usleep(10);
            
            $exportid = $this->getUniqid();
            
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
            if( !isset($data[parent::F_PARENT_ID]) ) $data[parent::F_PARENT_ID] = '';
            if( !isset($data[parent::F_EXPORT_ID]) ) $data[parent::F_EXPORT_ID] = '';
            if( !isset($data[parent::F_BLOG_ID]) ) $data[parent::F_BLOG_ID] = '';
            if( !isset($data[parent::F_SECTION_ID]) ) $data[parent::F_SECTION_ID] = '';
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
        
            $twiz_move_top_pos_a  = $data[parent::F_MOVE_TOP_POS_A];
            $twiz_move_left_pos_a = $data[parent::F_MOVE_LEFT_POS_A];
            $twiz_move_top_pos_b  = $data[parent::F_MOVE_TOP_POS_B];
            $twiz_move_left_pos_b = $data[parent::F_MOVE_LEFT_POS_B];
            $twiz_start_top_pos   = $data[parent::F_START_TOP_POS];
            $twiz_start_left_pos  = $data[parent::F_START_LEFT_POS];
            
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
            
            $twiz_status = $data[parent::F_STATUS];
            $twiz_lock_event = $data[parent::F_LOCK_EVENT];
            $twiz_lock_event_type = $data[parent::F_LOCK_EVENT_TYPE];
            $twiz_start_delay = $data[parent::F_START_DELAY];
            $twiz_duration = $data[parent::F_DURATION];
            $twiz_group_order = $data[parent::F_GROUP_ORDER];
            $twiz_blog_id = $data[parent::F_BLOG_ID];
            
            if( $siteurl != get_site_url() ){ // from another website or older versions
            
                $twiz_blog_id = json_encode( array($this->BLOG_ID) ); // set current blog id.
                
            }else{
            
                $twiz_blog_id = ( $twiz_blog_id == '' ) ? json_encode( array($this->BLOG_ID) ) : $twiz_blog_id;
            }
            $search = array("\"","'");
            $twiz_blog_id =  str_replace($search , "", $twiz_blog_id);
            
            $twiz_status = ( $twiz_status == '' ) ? '0' : $twiz_status;
            $twiz_lock_event = ( ( $twiz_lock_event == '' ) and ( ( $data[parent::F_ON_EVENT] !='') and ( $data[parent::F_ON_EVENT] !='Manually') ) ) ? '1' : $twiz_lock_event; // old format locked by default
            $twiz_lock_event = ( $twiz_lock_event == '' ) ? '0' : $twiz_lock_event;
            $twiz_lock_event_type = ( $twiz_lock_event_type == '' ) ? 'auto' : $twiz_lock_event_type;
            $twiz_start_delay = ( $twiz_start_delay == '' ) ? '0' : $twiz_start_delay;
            $twiz_duration = ( $twiz_duration == '' ) ? '0' : $twiz_duration;
            $twiz_group_order = ( $twiz_group_order == '' ) ? '0' : $twiz_group_order;
            
            $twiz_parent_id = ( $parentid == "" ) ? $data[parent::F_PARENT_ID] : $parentid; // to import under a group
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
                 ,".parent::F_ROW_LOCKED."       
                 )VALUES('".$twiz_parent_id."'
                 ,'".$data[parent::F_EXPORT_ID]."'
                 ,'".$twiz_blog_id."'
                 ,'".$newsectionid."'
                 ,'".$twiz_status."'
                 ,'".$data[parent::F_TYPE]."'
                 ,'".$data[parent::F_LAYER_ID]."'
                 ,'".$data[parent::F_ON_EVENT]."'             
                 ,'".$twiz_lock_event."'             
                 ,'".$twiz_lock_event_type."'             
                 ,'".$twiz_start_delay."'
                 ,'".$twiz_duration."'
                 ,'".$data[parent::F_DURATION_B]."'
                 ,'".$data[parent::F_OUTPUT]."'
                 ,'".$data[parent::F_OUTPUT_POS]."'
                 ,'".$twiz_javascript."'   
                 ,'".$twiz_css."'   
                 ,'".$data[parent::F_START_ELEMENT_TYPE]."'
                 ,'".$data[parent::F_START_ELEMENT]."'                 
                 ,'".$data[parent::F_START_TOP_POS_SIGN]."'    
                 ,".$twiz_start_top_pos."
                 ,'".$data[parent::F_START_TOP_POS_FORMAT]."'   
                 ,'".$data[parent::F_START_LEFT_POS_SIGN]."'    
                 ,".$twiz_start_left_pos."
                 ,'".$data[parent::F_START_LEFT_POS_FORMAT]."'    
                 ,'".$data[parent::F_POSITION]."'
                 ,'".$data[parent::F_ZINDEX]."'                      
                 ,'".$data[parent::F_EASING_A]."'  
                 ,'".$data[parent::F_EASING_B]."'    
                 ,'".$data[parent::F_MOVE_ELEMENT_TYPE_A]."'
                 ,'".$data[parent::F_MOVE_ELEMENT_A]."'                  
                 ,'".$data[parent::F_MOVE_TOP_POS_SIGN_A]."'    
                 ,".$twiz_move_top_pos_a."
                 ,'".$data[parent::F_MOVE_TOP_POS_FORMAT_A]."'    
                 ,'".$data[parent::F_MOVE_LEFT_POS_SIGN_A]."'    
                 ,".$twiz_move_left_pos_a."
                 ,'".$data[parent::F_MOVE_LEFT_POS_FORMAT_A]."'    
                 ,'".$data[parent::F_MOVE_ELEMENT_TYPE_B]."'
                 ,'".$data[parent::F_MOVE_ELEMENT_B]."'                  
                 ,'".$data[parent::F_MOVE_TOP_POS_SIGN_B]."'                     
                 ,".$twiz_move_top_pos_b."
                 ,'".$data[parent::F_MOVE_TOP_POS_FORMAT_B]."'                     
                 ,'".$data[parent::F_MOVE_LEFT_POS_SIGN_B]."'    
                 ,".$twiz_move_left_pos_b."
                 ,'".$data[parent::F_MOVE_LEFT_POS_FORMAT_B]."'    
                 ,'".$data[parent::F_OPTIONS_A]."'                             
                 ,'".$data[parent::F_OPTIONS_B]."'
                 ,'".$twiz_extra_js_a."'                             
                 ,'".$twiz_extra_js_b."'                 
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
    
    function exportAll(){
    
        $blogid_string = '';
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $sections       = get_option('twiz_sections');
            $hardsections   = get_option('twiz_hardsections');
            
        }else{
        
            $sections       = get_site_option('twiz_sections');
            $hardsections   = get_site_option('twiz_hardsections');
        }    
        

        if(!is_array($sections)){ $sections = array();}
        
        $all_links = '';
        $zipfile = '';
        
        if (is_writable($this->backup_dir_abspath)){
        
            if( $this->override_network_settings == '1' ){

                $blogid_string = $this->BLOG_ID.'-';
            }
            
            $zipfilename = $blogid_string.'twiz-'.date_i18n('Ymd-His').'.zip';
            
            $the_zip = new ZipArchive();
            $status = $the_zip->open($this->backup_dir_abspath.$zipfilename, ZipArchive::CREATE  | ZipArchive::OVERWRITE);
            
        }else{
        
            $status = true;
        }
        
        if ( $status === true ) {
        
            foreach($sections as $key => $value){

                $link_array = $this->export($key, '', '', true);
                $all_links .= $link_array['htmllink'];
                
                if( @file_exists($link_array['filename_abspath']) 
                and (is_writable($this->backup_dir_abspath)) ){
                
                    $the_zip->addFile($link_array['filename_abspath'], $link_array['filename']);
                }
            }

            // default sections
            foreach($hardsections as $key => $value){
            
                $link_array = $this->export($key, '', '', true);
                $all_links .= $link_array['htmllink'];
                
                if( @file_exists($link_array['filename_abspath']) 
                and (is_writable($this->backup_dir_abspath)) ){
                
                    $the_zip->addFile($link_array['filename_abspath'], $link_array['filename']);
                }
            }       
            if (is_writable($this->backup_dir_abspath)){
                // close the zip
                $the_zip->close();
            }
            if (( is_writable($this->backup_dir_abspath)) 
            and( @file_exists($this->backup_dir_abspath.$zipfilename))){
            
                $zipfile= '<p>'.__('Backup file', 'the-welcomizer').': <a href="'.$this->backup_dir_url.$zipfilename.'" class="twiz-bold">'.$zipfilename.'</a></p>';
            }
            
            $all_links = $zipfile.'<ul><li>'.$all_links.'</li></ul>';
        }
        
        return  $all_links;
    }
    
    function export( $section_id = '', $id = '', $groupid = '', $onlylink = false){
  
        $error = '';
        $filedata = '';
        $type = '';
        $blogid_string = '';
        $myTwizMenu  = new TwizMenu();
        
        $sectionname = sanitize_title_with_dashes($myTwizMenu->getSectionName($section_id));
        //die($sectionname );
        if( $id != '' ){
        
           $where = " WHERE ".parent::F_ID." = '".$id."'";
           $id = "_".$id;
           
        }else{
        
        
            $where = ($section_id != '') ? " WHERE ".parent::F_SECTION_ID." = '".$section_id."'" : " WHERE ".parent::F_SECTION_ID." = '".$this->DEFAULT_SECTION[$this->user_id]."'";
            
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
        $filedataBegin .= '<URL>'.get_site_url().'</URL>'."\n";
        
        if(in_array($section_id, $this->array_default_section)){ // export default section as shortcode
            
            $sections = $myTwizMenu->array_hardsections;
            $type = 'default';
                
        }else{
        
            $sections = $myTwizMenu->array_sections;
            list( $type, $unused ) = preg_split('/_/', $section_id);
        }
        if( !isset( $myTwizMenu->array_multi_sections[$section_id] ) ){
            
            $multi_sections = array();
            
        }else{
        
            $multi_sections = $myTwizMenu->array_multi_sections[$section_id];
        }
        $search = array("\"","'");
        $twiz_blog_id =  str_replace($search , "", json_encode( $sections[$section_id][parent::F_BLOG_ID] ));
        $multi_sections = str_replace($search , "", json_encode( $multi_sections ) );
        
        // Section infos
        $filedataBegin .= '<SECTION>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::F_SECTION_ID].'>'.$section_id.'</'.$this->array_twz_section_mapping[parent::F_SECTION_ID].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::F_STATUS].'>'.$sections[$section_id][parent::F_STATUS].'</'.$this->array_twz_section_mapping[parent::F_STATUS].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_VISIBILITY].'>'.$sections[$section_id][parent::KEY_VISIBILITY].'</'.$this->array_twz_section_mapping[parent::KEY_VISIBILITY].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::F_BLOG_ID].'>'.$twiz_blog_id.'</'.$this->array_twz_section_mapping[parent::F_BLOG_ID].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_TITLE].'>'.$sections[$section_id][parent::KEY_TITLE].'</'.$this->array_twz_section_mapping[parent::KEY_TITLE].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_SHORTCODE].'>'.$sections[$section_id][parent::KEY_SHORTCODE].'</'.$this->array_twz_section_mapping[parent::KEY_SHORTCODE].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_SHORTCODE_HTML].'>'.$sections[$section_id][parent::KEY_SHORTCODE_HTML].'</'.$this->array_twz_section_mapping[parent::KEY_SHORTCODE_HTML].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_MULTI_SECTIONS].'>'.$multi_sections.'</'.$this->array_twz_section_mapping[parent::KEY_MULTI_SECTIONS].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_CUSTOM_LOGIC].'>'.$sections[$section_id][parent::KEY_CUSTOM_LOGIC].'</'.$this->array_twz_section_mapping[parent::KEY_CUSTOM_LOGIC].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_COOKIE_CONDITION].'>'.$sections[$section_id][parent::KEY_COOKIE_CONDITION].'</'.$this->array_twz_section_mapping[parent::KEY_COOKIE_CONDITION].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_COOKIE_NAME].'>'.$sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME].'</'.$this->array_twz_section_mapping[parent::KEY_COOKIE_NAME].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_COOKIE_OPTION_1].'>'.$sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1].'</'.$this->array_twz_section_mapping[parent::KEY_COOKIE_OPTION_1].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_COOKIE_OPTION_2].'>'.$sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2].'</'.$this->array_twz_section_mapping[parent::KEY_COOKIE_OPTION_2].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_COOKIE_WITH].'>'.$sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH].'</'.$this->array_twz_section_mapping[parent::KEY_COOKIE_WITH].'>'."\n";
        $filedataBegin .= '<'.$this->array_twz_section_mapping[parent::KEY_COOKIE_SCOPE].'>'.$sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_SCOPE].'</'.$this->array_twz_section_mapping[parent::KEY_COOKIE_SCOPE].'>'."\n";
        $filedataBegin .= '</SECTION>'."\n";

        
        foreach( $listarray as $value ){

            $filedata .= '<ROW>'."\n";
            // loop fields array 
            foreach( $this->array_twz_mapping as $key => $notused){
              
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
        
        if(( $filedata != '' ) 
        or(!in_array($section_id, $this->array_default_section))){
        
            $filedata = $filedataBegin.$filedata .'</TWIZ>'."\n";
            
            $sectionname = ($sectionname == '') ? $section_id.$id : $sectionname.$id;
            
            $sectionname =  str_replace( $this->DEFAULT_SECTION_ALL_ARTICLES, 'allposts', $sectionname );
            
            if( $this->override_network_settings == '1' ){

                $blogid_string = $this->BLOG_ID.'-';
            }

            $filename = $blogid_string.$sectionname.'-'.date_i18n('Ymd-His').'.'.parent::EXT_TWIZ;

            $filename_abspath = $this->export_dir_abspath.$filename;
            $filename_url = $this->export_dir_url.urlencode($filename);
     
            if (is_writable($this->export_dir_abspath)){

                if (!$handle = fopen($filename_abspath, 'w')){
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
        
        if( $onlylink == true ){
        
            $html = ($error!='')? array('htmllink' => '<li class="twiz-red">' . $error .'</li>', 'filename_abspath' => '', 'filename' => '') : array('htmllink' => '<li><a href="'.$filename_url.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'">'. urldecode($filename) .'</a></li>', 'filename_abspath' => $filename_abspath, 'filename' => $filename);
            
        }else{

            $html = ($error!='')? '<div class="twiz-red">' . $error .'</div>' : ' <div id="twiz_img_download_export">'.__('Download file', 'the-welcomizer').'</div> <a href="'.$filename_url.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'">'. urldecode($filename) .'</a>';
            
        }

        return $html;
    }

    function getHTMLExportFileList( $sectionid = '', $filter = '' ){
    
        if( $sectionid == '' ){ $sectionid = __('Add New', 'the-welcomizer'); }
    
        $rowcolor = '';
        $myTwizMenu = new TwizMenu();
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $twiz_export_filter = get_option('twiz_export_filter');     
            
        }else{
        
            $twiz_export_filter = get_site_option('twiz_export_filter');
        } 

        // set twiz_export_filter array
        if(!isset($twiz_export_filter[$this->user_id][$sectionid])) $twiz_export_filter[$this->user_id][$sectionid] = '';
         
        // set filter and label
        $filter = ( $twiz_export_filter[$this->user_id][$sectionid] == '' ) ? 'twiz_export_filter_none' : $filter;
        $filter = ( $filter == '' ) ? $twiz_export_filter[$this->user_id][$sectionid] : $filter ;
        $lbl_filter = ( $filter == 'twiz_export_filter_none' ) ? __('none', 'the-welcomizer') : $filter;        
        $twiz_export_filter[$this->user_id][$sectionid] = $filter;
        $filter = ( $filter == 'twiz_export_filter_none' ) ? '' : $filter; 
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $code = update_option('twiz_export_filter',$twiz_export_filter);    
            
        }else{
        
            $code = update_site_option('twiz_export_filter',$twiz_export_filter);
        }         
            
        // get all export files
        $export_file_array = $this->getFileDirectory( array(parent::EXT_TWZ, parent::EXT_TWIZ, parent::EXT_XML), $this->export_dir_abspath );
        
        sort($export_file_array);
        
        // set toggle
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_EXPORT])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_EXPORT] = '';
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_EXPORT]['twizexp0'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_EXPORT]['twizexp0'] = '';
        
        // Set Toggle On
        $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_EXPORT]['twizexp0'] = '1'; 
         
        if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_EXPORT]['twizexp0'] == '1' ){
       
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
<tr class="twiz-table-list-tr-h"><td class="twiz-td-v-line"></td><td class="twiz-table-list-td-h twiz-text-left">'.__('Filename', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-ifs-filter twiz-text-left"><span class="twiz-float-left">'.__('Filter', 'the-welcomizer').':&nbsp;</span><div id="twiz_ajax_td_val_ifs_filter" title="'.__('Edit', 'the-welcomizer').'"><a id="twiz_ajax_td_a_ifs_filter">'.$lbl_filter.'</a></div><div id="twiz_ajax_td_loading_ifs_filter"></div><div id="twiz_ajax_td_edit_ifs_filter" class="twiz-display-none"><input class="twiz-input-focus" type="text" name="twiz_input_ifs_filter" id="twiz_input_ifs_filter" value="'.$filter.'" maxlength="100"/></div></td><td class="twiz-table-list-td-h twiz-td-ifs-action twiz-text-right twiz-td-lib-action">'.__('Action', 'the-welcomizer').'</td></tr>';
         
        $html.= '
<tr class="twiz-row-color-1"><td><div class="twiz-relative"><div id="twiz_export_img_twizexp0" class="twiz-toggle-export twiz-toggle-img '.$toggleimg.'"></div></div></td>

<td class="twiz-table-list-td" colspan="3"><a id="twiz_export_a_e_twizexp0" class="twiz-toggle-export'.$boldclass.'">'.$this->export_dir_label.'</a> <div class="twiz-blue twiz-float-right">'.__('Click filename to import', 'the-welcomizer').'</div></td></tr>';
        
        $rowid = 1;
        
        // Loop files
        foreach( $export_file_array as $filename ){
            
            $fileid = md5($filename);
            
            if(preg_match("/".$filter."/i", $filename)){
            
                $rowcolor = ( $rowcolor == 'twiz-row-color-2' ) ? 'twiz-row-color-1' : 'twiz-row-color-2';
                
                $html .= '
    <tr class="twiz-list-tr twizexp0 '.$rowcolor.$hide.'" id="twiz_list_tr_'.$fileid.'"><td class="twiz-td-v-line twiz-row-color-3">&nbsp;'.$rowid.'</td><td class="twiz-table-list-td" colspan="2">&nbsp;<a id="twiz_import_from_server_'.$fileid.'" title="'.__('Import this file', 'the-welcomizer').'" class="twiz-import-from-server">'.$filename.'</a></td> <td class="twiz-table-list-td twiz-text-right"><a class="twiz-arrow-lib-s twiz-arrow-lib" href="'.$this->export_dir_url.$filename.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" id="twiz_download_'.$fileid.'" target="_blank"></a> <div id="twiz_delete_'.$fileid.'" title="'.__('Delete', 'the-welcomizer').'" class="twiz-delete-export twiz-delete-img"></div></td>';
                
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
             
                @unlink( $filename );
            }
        }
       
        $htmlresponse = $this->getHTMLExportFileList( $sectionid, $filter );
        
        return $htmlresponse;
    }
}
?>