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
 
class TwizMenu extends Twiz{
        
    /* variable declaration */
    private $pages;
    private $allposts;
    private $categories;
    private $array_output;
    private $array_sections;
    private $array_hardsections; 
    private $array_admin_option;
    private $array_multi_sections;
    private $array_section_conversion;
    
    /* key menu constants */
    const KEY_STATUS = 'status';     
    
    /* output type constants */
    const TYPE_DEFAULT      = 'default';      
    const TYPE_UNIQUE       = 'unique';      
    const TYPE_MULTIPLE     = 'multiple';      
    const TYPE_CUSTOM_LOGIC = 'custom_logic';      
    const TYPE_CUSTOM_LOGIC_SHORT = 'logic';    
    const TYPE_SHORT_CODE   = 'shortcode';    
    
    
    /* Section name menu max lenght... */
    const MAX_LENGHT_SECTION_NAME = '47';     
                         
    function __construct(){
    
        parent::__construct();
        
        $this->array_admin_option = get_option('twiz_admin');
                 
        $this->array_section_conversion = array (parent::DEFAULT_SECTION_HOME           => __('Home', 'the-welcomizer')
                                                ,parent::DEFAULT_SECTION_EVERYWHERE     => __('Everywhere', 'the-welcomizer')
                                                ,parent::DEFAULT_SECTION_ALL_CATEGORIES => __('All Categories', 'the-welcomizer')
                                                ,parent::DEFAULT_SECTION_ALL_PAGES      => __('All Pages', 'the-welcomizer')
                                                ,parent::DEFAULT_SECTION_ALL_ARTICLES   => __('All Posts', 'the-welcomizer')
                                                );
                                                
        $this->array_output = array (self::TYPE_DEFAULT      => __('Default', 'the-welcomizer')
                                    ,self::TYPE_UNIQUE       => __('Unique', 'the-welcomizer')
                                    ,self::TYPE_MULTIPLE     => __('Multiple', 'the-welcomizer')
                                    ,self::TYPE_CUSTOM_LOGIC => __('Custom logic', 'the-welcomizer')
                                    ,self::TYPE_CUSTOM_LOGIC_SHORT => __('Logic', 'the-welcomizer')
                                    ,self::TYPE_SHORT_CODE   => __('Short code', 'the-welcomizer')
                                    );                  
        
        $this->categories = get_categories( array('orderby' => 'name', 
                                                  'order'   => 'ASC') );
        $this->pages = $this->get_wp('page', 'order by post_title'); 
        $this->allposts = $this->get_wp('post', 'order by post_date desc'); 

        $this->loadSections();
    }

    private function get_wp( $type = '', $order = '' ){

        global $wpdb;
        
        $sql = "SELECT ID, post_title, post_date  
                FROM ".$wpdb->posts."
                WHERE post_type='".$type."' 
                AND post_status NOT IN ('trash', 'auto-draft') ".$order;
                
        $resultarray = $wpdb->get_results($sql, ARRAY_A);
        
        return $resultarray;
    }

    protected function getMaxKeyArrayMultiSections(){
        
        $id = '';
        $i = 0;
 
        foreach( $this->array_multi_sections as $key => $value ){
  
            list($type, $number) = preg_split('/_/', $key);
            $id[] = $number;
            $i++;
        }

        if( !is_array($id) ){
        
            $id = array($i);
        }

        $max = max($id);
        
        return $max;
    }
    
    function saveSectionMenu( $section_status, $section_json_id = '', $section_name = '', $current_section_id = '', $output_choice = '', $custom_logic = '', $short_code = '', $cookie_name = '', $cookie_option_1 = '', $cookie_option_2 = '' ,$cookie_with = ''){
    
        global $wpdb;
   
        $html = '';
        $new_section_id = '';
        $section_status = ($section_status=='true') ? parent::STATUS_ACTIVE : parent::STATUS_INACTIVE;    
            
        if( $section_json_id == '' ){return '';}
        if( $section_json_id == '' ){return '';}
        if( $output_choice == '' ){return '';}
        if( $section_name == '' ){return '';}
        
        $section_name = ($section_name == '') ? __('Give the section a name', 'the-welcomizer') : $section_name;
        
        $array_section_id = json_decode( html_entity_decode($section_json_id) );

        
        switch($output_choice){
            
            case 'twiz_single_output':
            
                $section = array(parent::F_STATUS   => $section_status 
                                ,parent::KEY_TITLE  => $section_name
                                ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => $cookie_name 
                                                            ,parent::KEY_COOKIE_OPTION_1 => $cookie_option_1                 
                                                            ,parent::KEY_COOKIE_OPTION_2 => $cookie_option_2 
                                                            ,parent::KEY_COOKIE_WITH     => $cookie_with  
                                                            )                               
                                );

                if((!in_array($current_section_id, $this->array_hardsections)) 
                and ($current_section_id != "")){
           
                    if( !isset($this->array_multi_sections[$current_section_id]) ){}else{ unset($this->array_multi_sections[$current_section_id]); }
                    if( !isset($this->array_sections[$current_section_id]) ){}else{ unset($this->array_sections[$current_section_id]);}
                    
                    // Replace all section_id.
                    $updatesql = "UPDATE ".$this->table . " SET
                    ". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", '_".$current_section_id."', '_".$array_section_id[0] ."') 
                    ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", '_".$current_section_id."', '_".$array_section_id[0] ."') 
                    ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", '_".$current_section_id."', '_".$array_section_id[0] ."') 
                    WHERE ". parent::F_SECTION_ID ." = '".$current_section_id."'";
                    $code = $wpdb->query($updatesql);
                
                    // update the section
                    $sql = "UPDATE ".$this->table." 
                    SET ".parent::F_SECTION_ID." = '". $array_section_id[0] ."'               
                    WHERE ".parent::F_SECTION_ID." = '". $current_section_id ."';";
                        
                    $code = $wpdb->query($sql);
                }

                if( !isset($this->array_sections[$array_section_id[0]]) ) $this->array_sections[$array_section_id[0]] = '';              
                
                $this->array_sections[$array_section_id[0]] = $section;
                
                $code = update_option('twiz_sections', $this->array_sections);
                $code = $this->UpdateJSCookieStatus($array_section_id[0], $cookie_option_1, $cookie_with);
                
                return $array_section_id[0];
                
            
                break;
                
            case 'twiz_multiple_output':
            
                // update multi selection and unique or multi hard section.
                $section = array(parent::F_STATUS   => $section_status 
                                ,parent::KEY_TITLE  => $section_name
                                ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => $cookie_name 
                                                            ,parent::KEY_COOKIE_OPTION_1 => $cookie_option_1                 
                                                            ,parent::KEY_COOKIE_OPTION_2 => $cookie_option_2 
                                                            ,parent::KEY_COOKIE_WITH     => $cookie_with  
                                                            )                                        
                                );
               

                if( !isset($this->array_multi_sections[$current_section_id]) ){}else{ unset($this->array_multi_sections[$current_section_id]); }
                if( !isset($this->array_sections[$current_section_id]) ){}else{ unset($this->array_sections[$current_section_id]);}
                
                $newprefix = "ms_".($this->getMaxKeyArrayMultiSections() + 1);
                
                // add type replacement
                if((preg_match("/cl_/i", $current_section_id))
                or (preg_match("/sc_/i", $current_section_id))
                or (preg_match("/c_/i", $current_section_id))
                or (preg_match("/p_/i", $current_section_id))
                or (preg_match("/a_/i", $current_section_id))){
             
                    $new_section_id = $newprefix;
                }
               
                // Update or insert
                if($current_section_id != "") {  // update

                    $section_id = ($new_section_id != "" ) ? $new_section_id : $current_section_id;
                    
                }else{ // insert

                    $section_id = $newprefix;
                }
                
                
                if((!in_array($current_section_id, $this->array_default_section))
                or (in_array($$array_section_id[0], $this->array_default_section))
                and ($current_section_id != "")){
           
                    // Replace all section_id.
                    $updatesql = "UPDATE ".$this->table . " SET
                     ". parent::F_JAVASCRIPT . " = replace(". self::F_JAVASCRIPT . ", '_".$current_section_id."', '_".$section_id."') 
                    ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", '_".$current_section_id."', '_".$section_id."') 
                    ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", '_".$current_section_id."', '_".$section_id."') 
                    WHERE ".parent::F_SECTION_ID ." = '".$current_section_id."'";
                    $code = $wpdb->query($updatesql);
                    
                    // update the sectionid
                    $sql = "UPDATE ".$this->table." 
                          SET ".parent::F_SECTION_ID." = '". $section_id ."'               
                          WHERE ".parent::F_SECTION_ID." = '". $current_section_id ."';";
                    $code = $wpdb->query($sql);
                }
                
                if( !isset($this->array_multi_sections[$section_id]) ) $this->array_multi_sections[$section_id] = '';
                if( !isset($this->array_sections[$section_id]) ) $this->array_sections[$section_id] = '';   
                              
                $this->array_multi_sections[$section_id] = $array_section_id;
                $this->array_sections[$section_id] = $section;

                $code = update_option('twiz_multi_sections', $this->array_multi_sections);
                $code = update_option('twiz_sections', $this->array_sections);
                $code = $this->UpdateJSCookieStatus($section_id, $cookie_option_1, $cookie_with);
                
                return $section_id;
                    
                break;
                
            case 'twiz_logic_output':
            
                $section = array(parent::F_STATUS   => $section_status
                                ,parent::KEY_TITLE  => $section_name
                                ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => $cookie_name 
                                                            ,parent::KEY_COOKIE_OPTION_1 => $cookie_option_1                 
                                                            ,parent::KEY_COOKIE_OPTION_2 => $cookie_option_2 
                                                            ,parent::KEY_COOKIE_WITH     => $cookie_with  
                                                            )                                      
                                );
                                         
                if( !isset($this->array_multi_sections[$current_section_id]) ){}else{ unset($this->array_multi_sections[$current_section_id]); }
                if( !isset($this->array_sections[$current_section_id]) ){}else{ unset($this->array_sections[$current_section_id]);}
                    
                $newprefix = "cl_".($this->getMaxKeyArrayMultiSections() + 1);
                
                // add type replacement
                if((preg_match("/ms_/i", $current_section_id))
                or (preg_match("/sc_/i", $current_section_id))
                or (preg_match("/c_/i", $current_section_id))
                or (preg_match("/p_/i", $current_section_id))
                or (preg_match("/a_/i", $current_section_id))){
             
                    $new_section_id = $newprefix;
                }
               
                // Update or insert
                if($current_section_id != "") {  // update

                    $section_id = ($new_section_id != "" ) ? $new_section_id : $current_section_id;
                    
                }else{ // insert

                    $section_id = $newprefix;
                }
 
                if((!in_array($current_section_id, $this->array_default_section))
                or (in_array($$array_section_id[0], $this->array_default_section))
                and ($current_section_id != "")){
           
                    // Replace all section_id.
                    $updatesql = "UPDATE ".$this->table . " SET
                     ". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", '_".$current_section_id."', '_".$section_id."') 
                    ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", '_".$current_section_id."', '_".$section_id."') 
                    ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", '_".$current_section_id."', '_".$section_id."') 
                    WHERE ". parent::F_SECTION_ID ." = '".$current_section_id."'";
                    $code = $wpdb->query($updatesql);   
                    
                    // update the section
                    $sql = "UPDATE ".$this->table." 
                          SET ".parent::F_SECTION_ID." = '". $section_id ."'               
                          WHERE ".parent::F_SECTION_ID." = '". $current_section_id ."';";
                    $code = $wpdb->query($sql);
                }
            
                if( !isset($this->array_multi_sections[$section_id]) ) $this->array_multi_sections[$section_id] = '';
                if( !isset($this->array_sections[$section_id]) ) $this->array_sections[$section_id] = '';   
                               
                $this->array_multi_sections[$section_id] = $custom_logic;
                $this->array_sections[$section_id] = $section;

                $code = update_option('twiz_multi_sections', $this->array_multi_sections);
                $code = update_option('twiz_sections', $this->array_sections);
                $code = $this->UpdateJSCookieStatus($section_id, $cookie_option_1, $cookie_with);
                
                return $section_id;
                
            break;
            
            case 'twiz_shortcode_output':
            
                $section = array(parent::F_STATUS   => $section_status 
                                ,parent::KEY_TITLE  => $section_name
                                ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => $cookie_name 
                                                            ,parent::KEY_COOKIE_OPTION_1 => $cookie_option_1                 
                                                            ,parent::KEY_COOKIE_OPTION_2 => $cookie_option_2 
                                                            ,parent::KEY_COOKIE_WITH     => $cookie_with  
                                                            )                                        
                                );
                                         
                if( !isset($this->array_multi_sections[$current_section_id]) ){}else{ unset($this->array_multi_sections[$current_section_id]); }
                if( !isset($this->array_sections[$current_section_id]) ){}else{ unset($this->array_sections[$current_section_id]);}
                    
                $newprefix = "sc_".($this->getMaxKeyArrayMultiSections() + 1);
                
                // add type replacement
                if((preg_match("/ms_/i", $current_section_id))
                or (preg_match("/cl_/i", $current_section_id))
                or (preg_match("/c_/i", $current_section_id))
                or (preg_match("/p_/i", $current_section_id))
                or (preg_match("/a_/i", $current_section_id))){
             
                    $new_section_id = $newprefix;
                }
               
                // Update or insert
                if($current_section_id != "") {  // update

                    $section_id = ($new_section_id != "" ) ? $new_section_id : $current_section_id;
                    
                }else{ // insert

                    $section_id = $newprefix;
                }
 
                if((!in_array($current_section_id, $this->array_default_section))
                or (in_array($$array_section_id[0], $this->array_default_section))
                and ($current_section_id != "")){
           
                    // Replace all section_id.
                    $updatesql = "UPDATE ".$this->table . " SET
                     ". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", '_".$current_section_id."', '_".$section_id."') 
                    ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", '_".$current_section_id."', '_".$section_id."') 
                    ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", '_".$current_section_id."', '_".$section_id."') 
                    WHERE ". parent::F_SECTION_ID ." = '".$current_section_id."'";
                    $code = $wpdb->query($updatesql);   
                    
                    // update the section
                    $sql = "UPDATE ".$this->table." 
                          SET ".parent::F_SECTION_ID." = '". $section_id ."'               
                          WHERE ".parent::F_SECTION_ID." = '". $current_section_id ."';";
                    $code = $wpdb->query($sql);
                }
            
                if( !isset($this->array_multi_sections[$section_id]) ) $this->array_multi_sections[$section_id] = '';
                if( !isset($this->array_sections[$section_id]) ) $this->array_sections[$section_id] = '';   
                               
                $this->array_multi_sections[$section_id] = $short_code;
                $this->array_sections[$section_id] = $section;

                $code = update_option('twiz_multi_sections', $this->array_multi_sections);
                $code = update_option('twiz_sections', $this->array_sections);
                $code = $this->UpdateJSCookieStatus($section_id, $cookie_option_1, $cookie_with);
                
                return $section_id;
                
            break;         
            
            case 'twiz_default':
            
               $section_id = $current_section_id;
            
               $section = array(parent::F_STATUS   => $section_status 
                               ,parent::KEY_TITLE  => $section_name
                               ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => $cookie_name 
                                                           ,parent::KEY_COOKIE_OPTION_1 => $cookie_option_1                 
                                                           ,parent::KEY_COOKIE_OPTION_2 => $cookie_option_2 
                                                           ,parent::KEY_COOKIE_WITH     => $cookie_with  
                                                           )                                      
                                );    
              $this->array_hardsections[$section_id] = $section;                                  
              $code = update_option('twiz_hardsections', $this->array_hardsections);
              $code = $this->UpdateJSCookieStatus($section_id, $cookie_option_1, $cookie_with);
 
              return $section_id;              
            
              break;
        }
    }    
    
    private function UpdateJSCookieStatus( $section_id = '', $cookie_option_1 = '', $cookie_with = '' ){
    
        if( ($cookie_option_1 != '')
        and(($cookie_with == 'js') or ($cookie_with == 'all')) ){
        
            $code = update_option('twiz_cookie_js_status', true);  
            
        }else{
        
            $hasOtherSectionCookies = $this->hasOtherSectionCookies( $section_id );
            
            if($hasOtherSectionCookies == false){
            
                $code = update_option('twiz_cookie_js_status', false);  
            }
        }
        
        return true;
    }
    
    private function hasOtherSectionCookies( $section_id = '' ) {

        foreach ( $this->array_hardsections as $key => $value ){
         
            if( ($section_id != $key) 
            and ($value[parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] != '') 
            and ($value[parent::F_STATUS] == parent::STATUS_ACTIVE) ){
                return true;
            }
        }
        
        foreach ( $this->array_sections as $key => $value ){
        
            if( ($section_id != $key) 
            and ($value[parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] != '') 
            and ($value[parent::F_STATUS] == parent::STATUS_ACTIVE) ){
                return true;
            }
        }
        
        return false;
    }
    
    function deleteSectionMenu( $section_id = '' ){
    
        global $wpdb;
        
        if( $section_id == '' ){return false;}
         
        $sql = "DELETE from ".$this->table." where ".parent::F_SECTION_ID." = '".$section_id."';";
        $code = $wpdb->query($sql);
        
        // Hard sections are not deleted
        if( !array_key_exists($section_id, $this->array_default_section) ){
            
            $sections = $this->array_sections;
               
            foreach( $sections as $key => $value ){
        
                if( $key == $section_id ){
  
                    unset($this->array_sections[$key]);
                }
            }
            
            $sections = $this->array_multi_sections;
               
            foreach( $sections as $key => $value ){
        
                if( $key == $section_id ){
  
                    unset($this->array_multi_sections[$key]);
                }
            }            
            
           $code = update_option('twiz_sections', $this->array_sections);
           $code = update_option('twiz_multi_sections', $this->array_multi_sections);
        }

        return true;
    }     
    
    private function getHtmlSingleSection( $section_id = '' ){
    
        global $wpdb;
 
        $html = '';
        $select_cat = '';
        $select_page = '';
        $select_post = '';
        $separator_page = '';
        $separator_post = '';
        $selected_post_first = '';
        $count_array_sections = 0;
        $array_sections = array();
        
        $sections = $this->array_sections; // check single to exclude unique key
        if($section_id!=""){

            if(in_array($section_id, $this->array_default_section)){
            
                $section_id = 'default_' . $section_id;
            }
        
            list($type, $id ) = preg_split('/_/', $section_id);
                 
            // get the section array single or multi
            switch($type){
            
                case 'ms': // is custom multi-sections
                
                    $array_sections = $this->array_multi_sections[$section_id];
                
                    break;
                    
                case 'cl': // is custom logic
                
                    $array_sections =  array();
                
                    break;
                    
                default:
                
                $array_sections = array($section_id);
            }       
            
            $count_array_sections = count($array_sections);
        }
        
       
        $select = '<select name="twiz_slc_sections" id="twiz_slc_sections">';
        $select .= '<option value="">'.__('Choose the output', 'the-welcomizer').'</option>';
         
              
        foreach( $this->categories as $value ){
        
            $separator_cat = '<option value=""disabled="disabled">------------------------------------------------------</option>';
            
            if(in_array('c_'.$value->cat_ID, $array_sections) 
            and (($count_array_sections==1) and ($type!= "ms"))){
                $selected = ' selected="selected"'; 
                $select_cat .= '<option value="c_'.$value->cat_ID.'"'.$selected .'>'.$value->cat_name.'</option>';
            }else{
                if( ((in_array('c_'.$value->cat_ID, $array_sections)and ($type!= "ms")))
                or ( !array_key_exists('c_'.$value->cat_ID, $sections)) ){
                     $selected = '';
                     $select_cat .= '<option value="c_'.$value->cat_ID.'"'.$selected .'>'.$value->cat_name.'</option>';
                }
            }
        }
        
        foreach( $this->pages as $value ){
        
            $separator_page = '<option value=""disabled="disabled">------------------------------------------------------</option>';
            
            if(in_array('p_'.$value['ID'], $array_sections) 
            and (($count_array_sections==1) and ($type!= "ms"))){
                $selected = ' selected="selected"'; 
                $select_page .= '<option value="p_'.$value['ID'].'"'.$selected .'>'.$value['post_title'].'</option>';
            }else{
                if( ((in_array('p_'.$value['ID'], $array_sections ) and ($type!= "ms")))
                or (!array_key_exists('p_'.$value['ID'], $sections)) ){
                     $selected = '';
                     $select_page .= '<option value="p_'.$value['ID'].'"'.$selected .'>'.$value['post_title'].'</option>';
                }
            }
        }
    
        foreach( $this->allposts as $value ){
     
            if(in_array('a_'.$value['ID'], $array_sections) 
            and (($count_array_sections==1) and ($type!= "ms"))){

                $selected_post_first .=  '<option value="a_'.$value['ID'].'" selected="selected">'. mysql2date('Y-m-d', $value['post_date']). ' : '.$value['post_title'].'</option>';
            }
        }
            
        $i = 1;
        foreach( $this->allposts as $value ){
        
            if($i > $this->array_admin_option[parent::KEY_NUMBER_POSTS]){
                break;   
            }
            $separator_post = '<option value="" disabled="disabled">------------------------------------------------------------------------------------------------------------</option>';

            if( ((in_array('a_'.$value['ID'], $array_sections))and ($type!= "ms"))
            or(!array_key_exists('a_'.$value['ID'], $sections)) ){

                $select_post .= '<option value="a_'.$value['ID'].'">'. mysql2date('Y-m-d', $value['post_date']). ' : '.$value['post_title'].'</option>';            
            }
            $i++;
        }
        
        /* close select */
        $html =  $select.$separator_cat.$select_cat.$separator_page.$select_page.$separator_post.$selected_post_first.$select_post.'</select>';
        
        return $html;
    }
    
    private function GetHtmlMultiSection( $section_id = '', $array_sections = array() ){
    
        $html = '';
        $disabled = '';
        $select_cat = '';
        $select_page = '';
        $select_post = '';
        $separator_cat = '';
        $separator_page = '';
        $separator_post = '';
        $disabled_hard = false;
        $select_hardsection = '';
        $selected_post_first = '';
        $disabled_hard_output = '';

        $select = '<select name="twiz_slc_multi_sections" id="twiz_slc_multi_sections" multiple="multiple" size="20">';
      
        // get hard sections
        foreach( $this->array_section_conversion as $key => $value ){
    
            $selected_hard = (in_array($key, $array_sections)) ? ' selected="selected"' : '';
            $select_hardsection .= '<option value="'.$key.'"'. $selected_hard . '>'.$value.'</option>';
        }

        foreach( $this->categories as $value ){
     
            $separator_cat = '<option value="" disabled="disabled">------------------------------------------------------</option>';
            $selected = (in_array('c_'.$value->cat_ID, $array_sections)) ? ' selected="selected"' : '';
            $select_cat .= '<option value="c_'.$value->cat_ID.'"'. $selected .'>'.$value->cat_name.'</option>';
        }
  
        foreach( $this->pages as $value ){

            $separator_page = '<option value="" disabled="disabled">------------------------------------------------------</option>';
            $selected = (in_array('p_'.$value['ID'], $array_sections)) ? ' selected="selected"' : '';
            $select_page .= '<option value="p_'.$value['ID'].'"'. $selected .'>'.$value['post_title'].'</option>';
        }
    
        foreach( $this->allposts as $value ){
            
            if(in_array('a_'.$value['ID'], $array_sections)) { 

                $selected_post_first .= '<option value="a_'.$value['ID'].'"  selected="selected">'. mysql2date('Y-m-d', $value['post_date']). ' : '.$value['post_title'].'</option>';
            }
        }
            
        $i = 1;
        foreach( $this->allposts as $value ){
        
            if($i > $this->array_admin_option[parent::KEY_NUMBER_POSTS]){
                break;   
            }
            
            $separator_post = '<option value="" disabled="disabled">------------------------------------------------------------------------------------------------------------</option>';
            
            if(!in_array('a_'.$value['ID'], $array_sections)) { 
            
                $select_post .= '<option value="a_'.$value['ID'].'">'. mysql2date('Y-m-d', $value['post_date']). ' : '.$value['post_title'].'</option>';
            }
            $i++;
        }
        
        // close select
        $html =  $select.$select_hardsection.$separator_cat.$select_cat.$separator_page.$select_page.$separator_post.$selected_post_first.$select_post.'</select>';
        
        return $html;
    }
    
    function GetHtmlMultiSectionBoxes( $section_id = '', $action = '' ){
    
        global $wpdb;
 
        $html = '';
        $type = '';
        $choices = '';
        $jsscript = '';
        $jsscript_in = '';
        $default_message = '';
        $twiz_custom_logic ='';
        $twiz_shortcode = '';
        $twiz_shortcode_sample = '';
        $array_sections = array();
        $twiz_cookie_name = '';

        if(in_array($section_id, $this->array_default_section)){

            $section_id = 'default_' . $section_id;
        }
        
        if( $section_id != "" ){
         
            list($type, $id ) = preg_split('/_/', $section_id);
            
            // get the section array single or multi
            switch($type){
            
                case 'sc': // is short code
                
                    $twiz_shortcode = $this->array_multi_sections[$section_id];
                    $jsscript_in = '$(".twiz-custom-message").html("");
$("#twiz_output_choice_0").attr("checked", "checked");                    
$(".twiz-block-ouput").hide();
$("#twiz_shortcode_output").show();';
                    break;                    
                    
                case 'ms': // is custom multi-sections
                
                    $array_sections = $this->array_multi_sections[$section_id];
                    $jsscript_in = '$(".twiz-custom-message").html("");
$(".twiz-block-ouput").hide();
$("#twiz_output_choice_2").attr("checked", "checked");
$("#twiz_multiple_output").show();';
                    
                    break;
                    
                case 'cl': // is custom logic
                
                    $twiz_custom_logic = $this->array_multi_sections[$section_id];
                    $jsscript_in = '$(".twiz-custom-message").html("");
$("#twiz_output_choice_3").attr("checked", "checked");                    
$(".twiz-block-ouput").hide();
$("#twiz_logic_output").show();';
                    break;
                    
                case 'default': // is default section
                
                    $section_id = str_replace('default_','',$section_id);
                    
                    $array_sections = array($section_id);
                    
                    $jsscript_in = '$(".twiz-custom-message").html("");
$("#twiz_output_choice_0").attr("checked", "checked");                    
$(".twiz-block-ouput").hide();
$("#twiz_logic_output").hide();
$("#twiz_shortcode_output").hide();';
                    break;
                    
                default:
                
                    $array_sections = array($section_id);
                    $jsscript_in = '$(".twiz-custom-message").html("");
$("#twiz_output_choice_1").attr("checked", "checked");                    
$(".twiz-block-ouput").hide();
$("#twiz_single_output").show();';                    
            }
            
        }else{
            
                $jsscript_in = '$(".twiz-custom-message").html("");
$("#twiz_output_choice_0").attr("checked", "checked");                    
$(".twiz-block-ouput").hide();
$("#twiz_shortcode_output").show();';
}        
        if( !isset($twiz_slc_cookie_option['onlyonce']) ) $twiz_slc_cookie_option['onlyonce'] = '';
        if( !isset($twiz_slc_cookie_option['onlytwice']) ) $twiz_slc_cookie_option['onlytwice'] = '';
        if( !isset($twiz_slc_cookie_option['onlythrice']) ) $twiz_slc_cookie_option['onlythrice'] = '';
        if( !isset($twiz_slc_cookie_option['pervisit']) ) $twiz_slc_cookie_option['pervisit'] = '';
        if( !isset($twiz_slc_cookie_option['perhour']) ) $twiz_slc_cookie_option['perhour'] = '';
        if( !isset($twiz_slc_cookie_option['perday']) ) $twiz_slc_cookie_option['perday'] = '';
        if( !isset($twiz_slc_cookie_option['permonth']) ) $twiz_slc_cookie_option['permonth'] = '';
        if( !isset($twiz_slc_cookie_option['perweek']) ) $twiz_slc_cookie_option['perweek'] = '';
        if( !isset($twiz_slc_cookie_option['peryear']) ) $twiz_slc_cookie_option['peryear'] = '';
        if( !isset($twiz_slc_cookie_with['all']) ) $twiz_slc_cookie_with['all'] = '';
        if( !isset($twiz_slc_cookie_with['js']) ) $twiz_slc_cookie_with['js'] = '';
        if( !isset($twiz_slc_cookie_with['php']) ) $twiz_slc_cookie_with['php'] = '';
        
        if($section_id != ''){
        
            if(in_array($section_id, $this->array_default_section)){
            
                $sections = $this->array_hardsections;
                
            }else{
            
                $sections = $this->array_sections;
            }
            
            $twiz_slc_cookie_option['onlyonce'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] == 'onlyonce') ? ' selected="selected"' : '';
            $twiz_slc_cookie_option['onlytwice'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] == 'onlytwice') ? ' selected="selected"' : '';
            $twiz_slc_cookie_option['onlythrice'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] == 'onlythrice') ? ' selected="selected"' : '';
            $twiz_slc_cookie_option['pervisit'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2] == 'pervisit') ? ' selected="selected"' : '';
            $twiz_slc_cookie_option['perhour'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2] == 'perhour') ? ' selected="selected"' : '';
            $twiz_slc_cookie_option['perday'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2] == 'perday') ? ' selected="selected"' : '';
            $twiz_slc_cookie_option['perweek'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2] == 'perweek') ? ' selected="selected"' : '';
            $twiz_slc_cookie_option['permonth'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2] == 'permonth') ? ' selected="selected"' : '';
            $twiz_slc_cookie_option['peryear'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2] == 'peryear') ? ' selected="selected"' : '';
            
            $twiz_slc_cookie_with['all'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH] == 'all') ? ' selected="selected"' : '';
            $twiz_slc_cookie_with['js'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH] == 'js') ? ' selected="selected"' : '';
            $twiz_slc_cookie_with['php'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH] == 'php') ? ' selected="selected"' : '';
            $twiz_cookie_name = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME];
            
            $twiz_section_status = ( $sections[$section_id][parent::F_STATUS] == parent::STATUS_ACTIVE ) ? ' checked="checked"' : '';
            
            if($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] != '') {

                $jsscript_in .= '
$("#twiz_div_cookie_with").show();
$("#twiz_div_cookie_name").show();
$("#twiz_div_cookie_option_2").show();';
            }
            
        }else{
            
            $twiz_section_status = ' checked="checked"';
        
        }        
  
        $addsection = '';
        
        // a checker explode array sectionid si old etc.
        $twiz_section_name = $this->getSectionName($section_id);
        
        // Remove red from deleted
        $twiz_section_name = str_replace('<span class="twiz-status-red">', '', $twiz_section_name );
        $twiz_section_name = str_replace('</span>', '', $twiz_section_name );
        
        $twiz_shortcode_sample = ( $twiz_shortcode != '' ) ? '[twiz id="'.$twiz_shortcode.'"]' : '[twiz id="'. __('Example').'"]';    
                
        $jsscript = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) { ';
 
        $jsscript .= '
$("#twiz_section_name").focus();';
        
        $jsscript .= $jsscript_in;

        $jsscript .= '});
 //]]>
</script>';

        // radio menu choice
        $choices = '<div id="twiz_output_section">'.__('Output type', 'the-welcomizer').': ';

        if(in_array($section_id, $this->array_default_section)){
        
            $choices .= '<em>'.__('Default', 'the-welcomizer').'</em><input type="radio" id="twiz_output_choice_4" name="twiz_output_choice" class="twiz-output-choice twiz-display-none" value="twiz_default" checked="checked"/>';
            
        }else{        
        
            $choices .= '<input type="radio" id="twiz_output_choice_0" name="twiz_output_choice" class="twiz-output-choice" value="twiz_shortcode_output"/> <label for="twiz_output_choice_0">'.__('Short code', 'the-welcomizer').'</label> ';
            $choices .= '<input type="radio" id="twiz_output_choice_1" name="twiz_output_choice" class="twiz-output-choice" value="twiz_single_output"/> <label for="twiz_output_choice_1">'.__('Unique', 'the-welcomizer').'</label> ';
            $choices .= '<input type="radio" id="twiz_output_choice_2" name="twiz_output_choice" class="twiz-output-choice"  value="twiz_multiple_output"/> <label for="twiz_output_choice_2">'.__('Multiple', 'the-welcomizer').'</label> ';
            $choices .= '<input type="radio" id="twiz_output_choice_3" name="twiz_output_choice" class="twiz-output-choice"  value="twiz_logic_output"/> <label for="twiz_output_choice_3">'.__('Custom logic', 'the-welcomizer').'</label>';
        }
        
        $choices .= '</div>';
        
        // main box section name
        $html = '<div id="twiz_multi_menu">'.$default_message.'<div id="twiz_multi_action">'.__('Action', 'the-welcomizer').'<div class="twiz-green">'.__($action, 'the-welcomizer').'</div></div>'.__('Status', 'the-welcomizer').': <input type="checkbox" id="twiz_section_'.self::F_STATUS.'" name="twiz_section_'.self::F_STATUS.'" '.$twiz_section_status.'/><br>'.__('Section name', 'the-welcomizer').': ';

        
        if(in_array($section_id, $this->array_default_section)){
        
            $html .= ''.$twiz_section_name.'<input type="text" id="twiz_section_name" name="twiz_section_name"  value="'.$twiz_section_name.'" maxlength="255" class="twiz-display-none"/><br>';
            
        }else{  
        
            $html .=  '<input type="text" id="twiz_section_name" name="twiz_section_name"  value="'.$twiz_section_name.'" maxlength="255"/>';
        }
 
        // cookie option1
        $html .= '<div id="twiz_div_cookie_option_1" class="twiz-float-left">'.__('Cookie options', 'the-welcomizer').': <select id="twiz_slc_cookie_option_1">
        <option value="">'.__('Disabled', 'the-welcomizer').'</option>
        <option value="onlyonce"'.$twiz_slc_cookie_option['onlyonce'].'>'.__('Only once', 'the-welcomizer').'</option>
        <option value="onlytwice"'.$twiz_slc_cookie_option['onlytwice'].'>'.__('Only twice', 'the-welcomizer').'</option>
        <option value="onlythrice"'.$twiz_slc_cookie_option['onlythrice'].'>'.__('Only thrice', 'the-welcomizer').'</option>
        </select></div>';
        
        // cookie option2 
        $html .= '<div id="twiz_div_cookie_option_2" class="twiz-float-left twiz-display-none"><select id="twiz_slc_cookie_option_2">
        <option value="pervisit"'.$twiz_slc_cookie_option['pervisit'].'>'.__('per visit', 'the-welcomizer').'</option>
        <option value="perhour"'.$twiz_slc_cookie_option['perhour'].'>'.__('per hour', 'the-welcomizer').'</option>
        <option value="perday"'.$twiz_slc_cookie_option['perday'].'>'.__('per day', 'the-welcomizer').'</option>
        <option value="perweek"'.$twiz_slc_cookie_option['perweek'].'>'.__('per week', 'the-welcomizer').'</option>
        <option value="permonth"'.$twiz_slc_cookie_option['permonth'].'>'.__('per month', 'the-welcomizer').'</option>
        <option value="peryear"'.$twiz_slc_cookie_option['peryear'].'>'.__('per year', 'the-welcomizer').'</option>
        </select></div>';
        
        // cookie with
        $html .= '<div id="twiz_div_cookie_with" class="twiz-float-left twiz-display-none"><select id="twiz_slc_cookie_with">
        <option value="js"'.$twiz_slc_cookie_with['js'].'>'.__('with', 'the-welcomizer').' JS</option>
        <option value="php"'.$twiz_slc_cookie_with['php'].'>'.__('with', 'the-welcomizer').' PHP</option>
        <option value="all"'.$twiz_slc_cookie_with['all'].'>'.__('with', 'the-welcomizer').' JS &amp; PHP</option>
        </select></div>';
        
        // cookie name
        $html .= '<div class="twiz-clear"></div><div id="twiz_div_cookie_name" class="twiz-float-left twiz-display-none">'.__('Cookie name', 'the-welcomizer').': <input type="text" id="twiz_cookie_name" name="twiz_cookie_name" value="'.$twiz_cookie_name.'" maxlength="255"/> </div>';
        
         // cancel and save button
        $html .= '<div class="twiz-clear"></div><div class="twiz-text-right"><span id="twiz_menu_save_img_box" name="twiz_menu_save_img_box" class="twiz-loading-gif-save"></span><a name="twiz_section_cancel" id="twiz_section_cancel">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save_section" id="twiz_save_section" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /><input type="hidden" value="'.$section_id.'" id="twiz_section_id" name="twiz_section_id"/></div>';
        
        $html .= $choices;
        
         // Shortcode section box
        $html .= '<div id="twiz_shortcode_output" class="twiz-block-ouput">'.$this->array_output[self::TYPE_SHORT_CODE].': <div class="twiz-float-right twiz-green">'.__('Copy and paste this into a post, page or text widget.', 'the-welcomizer').'</div><div class="twiz-float-right"><input id="twiz_shortcode_sample" value="'.htmlentities($twiz_shortcode_sample).'"/></div><div class="twiz-float-left"><input type="text" id="twiz_shortcode" name="twiz_shortcode" value="'.$twiz_shortcode.'" maxlength="255"/> <strong>&gt;&gt;</strong></div></div>';
        
        // single section box
        $html .= '<div id="twiz_single_output" class="twiz-block-ouput">'.$this->array_output[self::TYPE_UNIQUE].': <div class="twiz-float-right twiz-text-right twiz-green">'.__('Select to overwrite the section name.', 'the-welcomizer').'</div><br><div id="twiz_custom_message_1" class="twiz-red twiz-custom-message"></div>'.$this->getHtmlSingleSection($section_id).'</div>';
           
        // multiple section box
        $html .= '<div id="twiz_multiple_output" class="twiz-block-ouput">'.$this->array_output[self::TYPE_MULTIPLE].':<div class="twiz-float-right twiz-text-right twiz-green">'.__('DoubleClick to overwrite the section name.', 'the-welcomizer').'<br>'.__('Press CTRL to select multiple output choices.', 'the-welcomizer').'</div><br><div id="twiz_custom_message_2" class="twiz-red twiz-custom-message"></div>'.$this->GetHtmlMultiSection($section_id, $array_sections).'</div>';

         // Custom Logic section box
        $html .= '<div id="twiz_logic_output" class="twiz-block-ouput">'.$this->array_output[self::TYPE_CUSTOM_LOGIC].': <br><div id="twiz_custom_message_3" class="twiz-red twiz-custom-message"></div><input type="text" id="twiz_custom_logic" name="twiz_custom_logic" value="'.$twiz_custom_logic.'"/>e.g.<br>is_page(\'32\') || is_category(\'55\') || is_post(\'345\')<br>!is_page(\'32\') && !is_category(\'55\') && !is_post(\'345\')<br><br><a href="http://codex.wordpress.org/Conditional_Tags#Conditional_Tags_Index" target="_blank">'.__('Conditional Tags on WordPress.org', 'the-welcomizer').'</a></div>';
        
        $html .= $jsscript;
        return $html;
    }

    function getHtmlMenu( $selected_id = '' ){
    
        // retrieve stored sections
        $sections = $this->array_sections;
        $hardsections = $this->array_hardsections;
       
        $menu = '';

        $statusimg = '<div id="twiz_status_menu_home" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_HOME, $hardsections[parent::DEFAULT_SECTION_HOME][parent::F_STATUS], 'menu' ).'</div>';
       
        // default home section 
        $menu .=  $statusimg . '<div id="twiz_menu_home" class="twiz-menu twiz-menu-selected twiz-display-none">'.__('Home').'</div>';
               
        // generate the section menu
        foreach( $sections as $key => $value ){
       
             if( $key != parent::DEFAULT_SECTION_HOME ){
                       
                $menu .= $this->getHtmlSectionMenu($key, $value[parent::KEY_TITLE], $value[parent::F_STATUS], $selected_id);
            }
        }

      
        // default everywhere section 
        $statusimg = '<div id="twiz_status_menu_'.parent::DEFAULT_SECTION_EVERYWHERE.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_EVERYWHERE, $hardsections[parent::DEFAULT_SECTION_EVERYWHERE][parent::F_STATUS], 'menu' ).'</div>';
          
        $menu .= $statusimg. '<div id="twiz_menu_'.parent::DEFAULT_SECTION_EVERYWHERE.'" class="twiz-menu twiz-display-none">'.__('Everywhere', 'the-welcomizer').'</div>';
        
        $statusimg = '<div id="twiz_status_menu_'.parent::DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_CATEGORIES, $hardsections[parent::DEFAULT_SECTION_ALL_CATEGORIES][parent::F_STATUS], 'menu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_menu_'.parent::DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-menu twiz-display-none">'.__('All Categories', 'the-welcomizer').'</div>';
        
        $statusimg = '<div id="twiz_status_menu_'.parent::DEFAULT_SECTION_ALL_PAGES.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_PAGES, $hardsections[parent::DEFAULT_SECTION_ALL_PAGES][parent::F_STATUS], 'menu' ).'</div>';

        $menu .= $statusimg . '<div id="twiz_menu_'.parent::DEFAULT_SECTION_ALL_PAGES.'" class="twiz-menu twiz-display-none">'.__('All Pages', 'the-welcomizer').'</div>';
        
        $statusimg = '<div id="twiz_status_menu_'.parent::DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_ARTICLES, $hardsections[parent::DEFAULT_SECTION_ALL_ARTICLES][parent::F_STATUS], 'menu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_menu_'.parent::DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-menu twiz-display-none">'.__('All Posts', 'the-welcomizer').'</div>';

        $menu .= '<div id="twiz_library_menu" class="twiz-menu twiz-display-none">'.__('Library', 'the-welcomizer').'</div>';

        $menu .= '<div id="twiz_admin_menu" class="twiz-menu twiz-display-none">'.__('Admin', 'the-welcomizer').'</div>';
        $menu .= '<div id="twiz_edit_menu"></div>';
        $menu .= '<div id="twiz_delete_menu"></div>';

        return $menu;
    }
    
    function getHtmlVerticalMenu( $selected_id = '' ){
    
        // retrieve stored sections
        $sections = $this->array_sections;
        $hardsections = $this->array_hardsections;
        
        $menu = '';
        
        $output_type_default = '<div class="twiz-output-label">'.$this->array_output[self::TYPE_DEFAULT].'</div>';
         
        $statusimg = '<div id="twiz_status_vmenu_home" class="twiz-status-menu">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_HOME, $hardsections[parent::DEFAULT_SECTION_HOME][parent::F_STATUS], 'vmenu' ).'</div>';
       
        // default home section
        $menu .=  $statusimg . '<div id="twiz_vmenu_home" class="twiz-menu">'.__('Home'). $output_type_default .'</div>';
       
        // generate the section menu
        foreach( $sections as $key => $value ){
       
            if( $key != parent::DEFAULT_SECTION_HOME ){
            
                $menu .= $this->getHtmlSectionvMenu($key, $value[parent::KEY_TITLE], $value[parent::F_STATUS], $selected_id);
            }
        }

        // default everywhere section
        $statusimg = '<div id="twiz_status_vmenu_'.parent::DEFAULT_SECTION_EVERYWHERE.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_EVERYWHERE, $hardsections[parent::DEFAULT_SECTION_EVERYWHERE][parent::F_STATUS], 'vmenu' ).'</div>';
          
        $menu .= $statusimg. '<div id="twiz_vmenu_'.parent::DEFAULT_SECTION_EVERYWHERE.'" class="twiz-menu">'.__('Everywhere', 'the-welcomizer').$output_type_default.'</div>';
        
        $statusimg = '<div id="twiz_status_vmenu_'.parent::DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_CATEGORIES, $hardsections[parent::DEFAULT_SECTION_ALL_CATEGORIES][parent::F_STATUS], 'vmenu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_vmenu_'.parent::DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-menu">'.__('All Categories', 'the-welcomizer').$output_type_default.'</div>';
        
        $statusimg = '<div id="twiz_status_vmenu_'.parent::DEFAULT_SECTION_ALL_PAGES.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_PAGES, $hardsections[parent::DEFAULT_SECTION_ALL_PAGES][parent::F_STATUS], 'vmenu' ).'</div>';

        $menu .= $statusimg . '<div id="twiz_vmenu_'.parent::DEFAULT_SECTION_ALL_PAGES.'" class="twiz-menu">'.__('All Pages', 'the-welcomizer'). $output_type_default .'</div>';
        
        $statusimg = '<div id="twiz_status_vmenu_'.parent::DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_ARTICLES, $hardsections[parent::DEFAULT_SECTION_ALL_ARTICLES][parent::F_STATUS], 'vmenu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_vmenu_'.parent::DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-menu ">'.__('All Posts', 'the-welcomizer'). $output_type_default.'</div>';

        return $menu;
    }
    
    private function getHtmlSectionMenu( $section_id = '', $section_name = '', $status = parent::STATUS_ACTIVE, $selected_id = ''){

       if( $section_id == '' ){return '';}
       if( $section_name == '' ){return '';}
       
       $selected = ($selected_id == $section_id ) ? ' twiz-menu-selected' : '';
       
       $statusimg = '<div id="twiz_status_menu_'.$section_id.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( $section_id, $status, 'menu' ).'</div>';
       $section_name = (strlen($section_name)>49)? mb_substr($section_name, 0, self::MAX_LENGHT_SECTION_NAME,'UTF-8').'...': $section_name;
       $html = $statusimg.'<div id="twiz_menu_'.$section_id.'" class="twiz-menu twiz-display-none'.$selected.'">'.$section_name.'</div>';
            
       return $html;
    }
    
    private function getHtmlTypeLabel( $section_id = '' ){
    
        if( $section_id == '' ){return '';}
    
        list( $type, $id ) = preg_split('/_/', $section_id);
        
        switch($type){
         
            case 'sc': // is short code
            
                return '<div class="twiz-output-label">'.$this->array_output[self::TYPE_SHORT_CODE].'</div>';
            
                break;
                
            case 'ms': // is custom multi-sections
            
                return '<div class="twiz-output-label">'.$this->array_output[self::TYPE_MULTIPLE].'</div>';
            
                break;
                
            case 'cl': // is custom logic
            
                return '<div class="twiz-output-label">'.$this->array_output[self::TYPE_CUSTOM_LOGIC_SHORT].'</div>';
            
                break;

            default:
            
                return '<div class="twiz-output-label">'.$this->array_output[self::TYPE_UNIQUE].'</div>';
                
            break;
        }
    }
    
    private function getHtmlSectionvMenu( $section_id = '', $section_name = '', $status = parent::STATUS_ACTIVE, $selected_id = ''){
    
       if( $section_id == '' ){return '';}
       if( $section_name == '' ){return '';}
       
       $selected = ($selected_id == $section_id ) ? ' twiz-menu-selected' : '';
       
       $statusimg = '<div id="twiz_status_vmenu_'.$section_id.'" class="twiz-status-menu twiz-display-block">'.$this->getHtmlImgStatus( $section_id, $status, 'vmenu' ).'</div>';
       $section_name = (strlen($section_name)>49)? mb_substr($section_name, 0, self::MAX_LENGHT_SECTION_NAME,'UTF-8').'...': $section_name;
       $html = $statusimg.'<div id="twiz_vmenu_'.$section_id.'" class="twiz-menu twiz-display-block'.$selected.'">'.$section_name.$this->getHtmlTypeLabel($section_id).'</div>';
            
       return $html;
    }
    
    function getSectionName( $key = ''){
    
        if( $key  == '' ){return '';}
        
        if(!preg_match("/_/i", $key)){
       
            if(!isset( $this->array_sections[$key])){
            
                $name = strtr($key, $this->array_section_conversion);
                
            }else{
            
                $name = $this->array_sections[$key][parent::KEY_TITLE];
            }
        
            return $name;
        }
        
        list( $type, $id ) = preg_split('/_/', $key);
                
        $name = '';
        $wpname = '';
        $newid = '';
        
        switch($type){
        
            case 'sc': // is short code
            
                $name = $this->array_sections[$key][parent::KEY_TITLE];
            
                break;
                
            case 'ms': // is custom multi-sections
            
                $name = $this->array_sections[$key][parent::KEY_TITLE];
            
                break;
                
            case 'cl': // is custom logic
            
                $name = $this->array_sections[$key][parent::KEY_TITLE];
            
                break;

            case 'c': // is category
            
                $wpname = get_cat_name($id);
                
                $name = $this->array_sections[$key][parent::KEY_TITLE];
                
                // User deleted category 
                if ($wpname==""){
                
                    $name = '<span class="twiz-status-red">'.$name.'</span>';
                }
                break;
                
            case 'p': // is page
            
                $page = get_page($id);
                
                if(is_object($page)){
                
                    $wpname = $page->post_title;
                }
                
                $name = $this->array_sections[$key][parent::KEY_TITLE];
                
                // User deleted page 
                if ( $wpname == "" ){
                
                    $name = '<span class="twiz-status-red">'.$name.'</span>';
                }
                break;
                
            case 'a': // is post
                   
                $post = get_post( $id );
                
                if(is_object($post)){
                
                    $wpname = mysql2date('Y-m-d', $post->post_date).' : '.$post->post_title;
                }
                
                $name = $this->array_sections[$key][parent::KEY_TITLE];
                
                // User deleted post 
                if ( $wpname == "" ){

                    $name = '<span class="twiz-status-red">'.$name.'</span>';
                }
                break;   
        }
        
        return $name;
    }
    
    private function loadSections(){
    
        $key_title = array();
        $this->array_sections = get_option('twiz_sections');
        $this->array_hardsections = get_option('twiz_hardsections');
        $this->array_multi_sections = get_option('twiz_multi_sections');
        
        $sections = $this->array_sections;
        
        if( !is_array($this->array_multi_sections) ){
            $this->array_multi_sections = array();
        }
            
        if( !is_array($this->array_sections) ){
        
            $this->array_sections = array();
            
        }else{
        
            // Reformat array if necessary
            foreach( $sections as $key => $value ) {

                if( !is_array($sections[$key]) ){ // reformat array for the first time
                    
                    if( !isset($this->array_sections[$value]) ) $this->array_sections[$value] = ''; 
                    
                    $section = array(parent::F_STATUS   => parent::STATUS_ACTIVE
                                    ,parent::KEY_TITLE  => $this->getSectionName($key)
                                    ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => '' 
                                                                ,parent::KEY_COOKIE_OPTION_1 => ''
                                                                ,parent::KEY_COOKIE_OPTION_2 => ''
                                                                ,parent::KEY_COOKIE_WITH     => '' 
                                                                )   
                                    ); // new array
                   
                    $this->array_sections[$value] = $section; // new value
                    
                    unset($this->array_sections[$key]); // delete old format
                    
                }else{ 
            
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE]) ) $this->array_sections[$key][parent::KEY_COOKIE] = ''; 
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME]) ) $this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME] = ''; 
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH]) ) $this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH] = ''; 
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1]) ) $this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] = ''; 
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2]) ) $this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2] = ''; 
                 
                    if( !isset($this->array_sections[$key][parent::F_SECTION_ID]) ){ // Always update the title, this array has been cleaned already
                        
                        if( !isset($this->array_sections[$key][parent::KEY_TITLE]) ) $this->array_sections[$key][parent::KEY_TITLE] = ''; // Register the key

                         
                        $this->array_sections[$key][parent::KEY_TITLE] = $this->getSectionName($key); // Fresh title
                      
                    }else{ // it has already been formated once v1.3.8.6(broken), we need to format it again for those users.
                                             
                        $newkey = $this->array_sections[$key][parent::F_SECTION_ID]; // New key from array
                        $samestatus = $this->array_sections[$key][parent::F_STATUS]; // Same status
                        
                        if( !isset($this->array_sections[$newkey]) ) $this->array_sections[$newkey] = ''; 
                        
                        $section = array( parent::F_STATUS   => $samestatus
                                         ,parent::KEY_TITLE  => $this->getSectionName($newkey)
                                         ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => '' 
                                                                     ,parent::KEY_COOKIE_OPTION_1 => ''
                                                                     ,parent::KEY_COOKIE_OPTION_2 => ''
                                                                     ,parent::KEY_COOKIE_WITH     => '' 
                                                                     )                                         
                                         ); // new array
                        
                        $this->array_sections[$newkey] = $section; // new value
                        
                        unset($this->array_sections[$key]); // delete old format
                    }
                }
            }
            
            $code = update_option('twiz_sections', $this->array_sections); 
        }
   
          // Sort by title
        foreach ($this->array_sections as $key => $value) {
        
            $key_title[$key] = $value[parent::KEY_TITLE];
        }
        
        $key_title = array_map('strtolower', $key_title);
        
        array_multisort($key_title, SORT_ASC, SORT_STRING, $this->array_sections);
        
        // Register hard menu
        foreach( $this->array_default_section as $key ) {

            if( !isset($this->array_hardsections[$key][parent::F_SECTION_ID]) ){}else{
                unset($this->array_hardsections[$key]);  // delete old format
            }
            
            if( !isset($this->array_hardsections[$key]) ){
                
                $this->array_hardsections[$key] = '';
            
                if( $key == parent::DEFAULT_SECTION_HOME ){
                
                    $section = array(parent::F_STATUS   => parent::STATUS_ACTIVE
                                    ,parent::KEY_TITLE  => $this->getSectionName($key)
                                    ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => '' 
                                                                ,parent::KEY_COOKIE_OPTION_1 => ''
                                                                ,parent::KEY_COOKIE_OPTION_2 => ''
                                                                ,parent::KEY_COOKIE_WITH     => '' 
                                                                )     
                                    );
                }else{
                
                    $section = array(parent::F_STATUS   => parent::STATUS_INACTIVE                                     
                                    ,parent::KEY_TITLE  => $this->getSectionName($key)
                                    ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => '' 
                                                                ,parent::KEY_COOKIE_OPTION_1 => ''
                                                                ,parent::KEY_COOKIE_OPTION_2 => ''
                                                                ,parent::KEY_COOKIE_WITH     => '' 
                                                                )     
                                    );
                }

                $this->array_hardsections[$key] = $section; // Activated by default
                
            }else{
            
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE]) ) $this->array_hardsections[$key][parent::KEY_COOKIE] = ''; 
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME]) ) $this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME] = ''; 
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1]) ) $this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] = ''; 
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2]) ) $this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2] = ''; 
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH]) ) $this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH] = '';             
                
            }
        }
        
        $code = update_option('twiz_hardsections', $this->array_hardsections); 

    }  
    
    function switchMenuStatus( $id = '' ){ 
    
       $htmlstatus = '';
       $newstatus = '';
       $sections = $this->array_sections;
       $hardsections = $this->array_hardsections;
       
       $cleanid = str_replace('vmenu_','',$id);
       $cleanid = str_replace('menu_','',$cleanid);

        foreach( $sections as $key => $value ){
   
            if( $key == $cleanid ){
                
                $newstatus = ($value[parent::F_STATUS] == parent::STATUS_INACTIVE) ? parent::STATUS_ACTIVE : parent::STATUS_INACTIVE; // swicth the status value
                $ddcleanid = str_replace('_'.$cleanid,'',$id);
            
                $htmlstatus = ($newstatus == parent::STATUS_ACTIVE) ? $this->getHtmlImgStatus($cleanid, parent::STATUS_ACTIVE, $ddcleanid ) : $this->getHtmlImgStatus($cleanid , parent::STATUS_INACTIVE, $ddcleanid );
                
                $this->array_sections[$key][parent::F_STATUS] = $newstatus;

                $code = update_option('twiz_sections', $this->array_sections);
        
            }
        }

        foreach( $hardsections as $key => $value ){
   
            if( $key == $cleanid ){
                
                $newstatus = ($value[parent::F_STATUS] == parent::STATUS_INACTIVE) ? parent::STATUS_ACTIVE : parent::STATUS_INACTIVE; // swicth the status value
                $ddcleanid = str_replace('_'.$cleanid,'',$id);
            
                $htmlstatus = ($newstatus == parent::STATUS_ACTIVE) ? $this->getHtmlImgStatus($cleanid, parent::STATUS_ACTIVE, $ddcleanid ) : $this->getHtmlImgStatus($cleanid , parent::STATUS_INACTIVE, $ddcleanid );
                                  
                $this->array_hardsections[$key][parent::F_STATUS] = $newstatus;

                $code = update_option('twiz_hardsections', $this->array_hardsections);
            }
        }
        
        return $htmlstatus;
    }
}
?>