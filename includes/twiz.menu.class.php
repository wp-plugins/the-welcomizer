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

class TwizMenu extends Twiz{
        
    // variable declaration 
    private $pages;
    private $allposts;
    private $categories;
    private $array_output;
    public  $array_sections;
    public  $array_hardsections;
    public  $array_multi_sections;
    private $array_hardsections_translation;
    
    // output type constants 
    const TYPE_DEFAULT      = 'default';
    const TYPE_UNIQUE       = 'unique';
    const TYPE_MULTIPLE     = 'multiple';
    const TYPE_CUSTOM_LOGIC = 'custom_logic';
    const TYPE_CUSTOM_LOGIC_SHORT = 'logic';
    const TYPE_SHORT_CODE   = 'shortcode';
       
    
    // Section name menu max lenght... 
    const MAX_LENGHT_NAME_MENU = '37';
    const MAX_LENGHT_NAME_VMENU = '52';
    
    function __construct(){
    
        parent::__construct();

        $this->array_hardsections_translation = array ($this->DEFAULT_SECTION_HOME           => __('Home', 'the-welcomizer')
                                                ,$this->DEFAULT_SECTION_EVERYWHERE     => __('Everywhere', 'the-welcomizer')
                                                ,$this->DEFAULT_SECTION_ALL_CATEGORIES => __('All Categories', 'the-welcomizer')
                                                ,$this->DEFAULT_SECTION_ALL_PAGES      => __('All Pages', 'the-welcomizer')
                                                ,$this->DEFAULT_SECTION_ALL_ARTICLES   => __('All Posts', 'the-welcomizer')
                                                );
                                                
        $this->array_output = array (self::TYPE_DEFAULT      => __('Default', 'the-welcomizer')
                                    ,self::TYPE_UNIQUE       => __('Unique', 'the-welcomizer')
                                    ,self::TYPE_MULTIPLE     => __('Multiple', 'the-welcomizer')
                                    ,self::TYPE_CUSTOM_LOGIC => __('Custom logic', 'the-welcomizer')
                                    ,self::TYPE_CUSTOM_LOGIC_SHORT => __('Logic', 'the-welcomizer')
                                    ,self::TYPE_SHORT_CODE   => __('Shortcode', 'the-welcomizer')
                                    );
                                    
        // This query below replace the comment line below, since it returns invalid taxonomy for yet unknown reasons.
        // If the plugin is activated indivually(single or multi) it's ok.
        // If the plugin is activated with another plugin as the plugin management, it's ok. 
        // The installation procedure here seems to be the same. No idea why.
        // It does not work with network activation.
        
        //$this->categories = get_categories( array('orderby' => 'name', 'order' => 'ASC') );
        global $wpdb;
        $sql = "SELECT t.term_id as cat_ID, t.name as cat_name FROM ".$wpdb->terms." t INNER JOIN ".$wpdb->term_taxonomy." tt on tt.term_id = t.term_id where tt.taxonomy = 'category' order by name";
        $resultarray = $wpdb->get_results($sql, ARRAY_A);
        $this->categories = $resultarray;
       
        $this->pages = $this->get_wp('page', 'order by post_title');
        $this->allposts = $this->get_wp('post', 'order by post_date desc');

        $this->loadSections();
    }
    
    function getVarDump( $twiz_action = '' ){
                 global $wpdb;
        $output = '';
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $bullet = get_option('twiz_bullet');
        
        }else{

            $bullet = get_site_option('twiz_bullet');
        }     
        
        if( !isset( $this->admin_option[parent::KEY_DISPLAY_VAR_DUMP] ) ){

            $KEY_DISPLAY_VAR_DUMP = ''; 
            
        }else{
        
            $KEY_DISPLAY_VAR_DUMP = $this->admin_option[parent::KEY_DISPLAY_VAR_DUMP];
        }
        
        if( ( $KEY_DISPLAY_VAR_DUMP == true ) or ( TWIZ_FORCE_VARDUMP ==  true ) ){

            if ( ! function_exists( 'is_plugin_active_for_network' ) ){ require_once( ABSPATH . '/wp-admin/includes/plugin.php' ); }

            $output .= '<pre>';
            $output .= '<div class="twiz-float-right"><span class="twiz-bold">Time</span>: <span class="twiz-green">'.date_i18n('H:i:s').'</span></div>';
            
            $output .= '<br><span class="twiz-bold twiz-blue">$this->version</span> = <span class="">'.$this->version.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->cssVersion</span> = <span class="">'.$this->cssVersion.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->dbVersion</span> = <span class="">'.$this->dbVersion.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->table</span> = <span class="">'.$this->table.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->pluginName</span> = <span class="">'.$this->pluginName.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->import_path_message</span> = <span class="">'.$this->import_path_message.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->export_path_message</span> = <span class="">'.$this->export_path_message.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->pluginDir</span> = <span class="">'.$this->pluginDir.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->pluginUrl</span> = <span class="">'.$this->pluginUrl.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->uploadDir</span> = <span class="">'.print_r($this->uploadDir, true).'</span>';
            
            $output .= '<hr class="twiz-hr twiz-corner-all">';
            
            $output .= '<span class="twiz-bold twiz-blue">$this->BLOG_ID</span> = <span class="">'.$this->BLOG_ID.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->user_id</span> = <span class="">'.$this->user_id.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->global_status</span> = <span class="">'.$this->global_status.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->network_activated</span> = <span class="">'.$this->network_activated.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->override_network_settings</span> = <span class="">'.$this->override_network_settings.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->privacy_question_answered</span> = <span class="">'.$this->privacy_question_answered .'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">$this->DEFAULT_MIN_ROLE_LEVEL</span> = <span class="">'.$this->DEFAULT_MIN_ROLE_LEVEL.'</span>';
            $output .= '<br><span class="twiz-bold twiz-blue">is_plugin_active_for_network()</span> = <span class="">'. is_plugin_active_for_network( 'the-welcomizer/twiz-index.php' ).'</span>';

            $output .= '<hr class="twiz-hr twiz-corner-all">';

            $output .= '<br><span class="twiz-bold twiz-blue">$this->hscroll_status</span> = <span class="">'.print_r($this->hscroll_status, true).'</span>';

            $output .= '<hr class="twiz-hr twiz-corner-all">';
            
            $output .= '<span class="twiz-bold twiz-blue">$this->DEFAULT_SECTION</span> = <span class="">'.print_r($this->DEFAULT_SECTION, true).'</span>';

            $output .= '<hr class="twiz-hr twiz-corner-all">';
            
            $output .= '<span class="twiz-bold twiz-blue">$this->skin</span> = <span class="">'.print_r($this->skin, true).'</span>';

            $output .= '<hr class="twiz-hr twiz-corner-all">';
            
            $output .= '<span class="twiz-bold twiz-blue">$this->twiz_bullet</span> = <span class="">'.print_r($bullet, true).'</span>';                        
            
            $output .= '<hr class="twiz-hr twiz-corner-all">';
             
            $output .= '<span class="twiz-bold twiz-blue">$this->admin_option</span> = <span class="">'.print_r($this->admin_option, true).'</span>';
            
            $output .= '<hr class="twiz-hr twiz-corner-all">';
                        
            $output .= '<span class="twiz-bold twiz-blue">$this->toggle_option</span> = <span class="">'.print_r($this->toggle_option, true).'</span>';
            
            $output .= '<hr class="twiz-hr twiz-corner-all">';
                        
            $output .= '<span class="twiz-bold twiz-blue">$this->array_sections</span> = <span class="">'.print_r($this->array_sections, true).'</span>';
            
            $output .= '<hr class="twiz-hr twiz-corner-all">';
                        
            $output .= '<span class="twiz-bold twiz-blue">$this->array_hardsections</span> = <span class="">'.print_r($this->array_hardsections, true).'</span>';
            
            $output .= '<hr class="twiz-hr twiz-corner-all">';
                        
            $output .= '<span class="twiz-bold twiz-blue">$this->array_multi_sections</span> = <span class="">'.print_r($this->array_multi_sections, true).'</span>';
            
            $output .= '</pre>';
        }
        return $output;
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

    protected function getMaxKeyArraySections(){
        
        $id = '';
        $i = 0;
 
        foreach( $this->array_sections as $key => $value ){
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
    
    private function updateRowsBlogId( $section_id = '', $blogid_array = ''){
    
        global $wpdb;
        
        if( $this->override_network_settings != '1' ) {
        
            $search = array("\"","'");
            $blogid = str_replace($search, "", json_encode( $blogid_array ) );
            
            // Replace all blogid for this section.
            $updatesql = "UPDATE ".$this->table . " SET ". parent::F_BLOG_ID . " = '".$blogid."'
            WHERE ". parent::F_SECTION_ID ." = '".$section_id."'";
            $code = $wpdb->query($updatesql);
        
        }
        
        return true;
    }
    
    function saveSectionMenu( $section_json_id = '', $section_import = array() ){
    
        global $wpdb;
   
        $html = '';
        $new_section_id = '';
        $current_section_id = '';
        
        if( empty($section_import) ){ // from section form (Edit - Add New)
  
            if(!isset($_POST['twiz_section_name'])) $_POST['twiz_section_name'] = '';
            if(!isset($_POST['twiz_output_choice']))  $_POST['twiz_output_choice'] = '';
            if(!isset($_POST['twiz_custom_logic'])) $_POST['twiz_custom_logic'] = '';
            if(!isset($_POST['twiz_shortcode'])) $_POST['twiz_shortcode'] = '';
            if(!isset($_POST['twiz_shortcode_html'])) $_POST['twiz_shortcode_html'] = '';
        
            $section_status = esc_attr(trim($_POST['twiz_section_status']));
            $current_section_id = esc_attr(trim($_POST['twiz_current_section_id']));
            $visibility = esc_attr(trim($_POST['twiz_visibility']));
            
            if( !isset( $_POST['twiz_'.parent::F_BLOG_ID] ) ) { // ACTION_SAVE_SECTION -> twiz.ajax.class.php
            
                $blogid_array = $this->getSectionBlogId( $current_section_id );

            }else{

                // safe json                                
                $search = array("\"","'");
                $blogidpost =  str_replace($search , "", $_POST['twiz_'.parent::F_BLOG_ID]);
                $blogidpost = str_replace(',', '","', esc_attr(trim($blogidpost)));
                $blogidpost = str_replace('[', '["', $blogidpost);
                $blogidpost = str_replace(']', '"]', $blogidpost);

                $blogid_array = json_decode($blogidpost);             
                $blogid_array = ( in_array(parent::ALL_SITES, $blogid_array) )? array(parent::ALL_SITES) : $blogid_array; // Keep only all sites
            }

            $section_name = esc_attr(trim($_POST['twiz_section_name']));
            $output_choice = esc_attr(trim($_POST['twiz_output_choice']));
            $custom_logic = esc_attr(trim($_POST['twiz_custom_logic']));
            $shortcode = esc_attr(trim($_POST['twiz_shortcode']));
            $cookie_condition = esc_attr(trim($_POST['twiz_cookie_condition']));
            $cookie_name = esc_attr(trim($_POST['twiz_cookie_name']));
            $cookie_option_1 = esc_attr(trim($_POST['twiz_cookie_option_1']));
            $cookie_option_2 = esc_attr(trim($_POST['twiz_cookie_option_2']));
            $cookie_with = esc_attr(trim($_POST['twiz_cookie_with']));
            $cookie_scope = esc_attr(trim($_POST['twiz_cookie_scope']));
            $shortcode_html = esc_attr(trim($_POST['twiz_shortcode_html']));
        
            $section_status = ( $section_status == 'true' )? parent::STATUS_ACTIVE : parent::STATUS_INACTIVE;
            $blogid_array = ( $blogid_array == '' )?  $this->getSectionBlogId( $current_section_id ) : $blogid_array;
            
            // safe json                                
            $search = array("\"","'");
            $section_json_id =  str_replace($search , "", $section_json_id);
            $section_json_id = str_replace(',', '","', esc_attr(trim($section_json_id)));
            $section_json_id = str_replace('[', '["', $section_json_id);
            $section_json_id = str_replace(']', '"]', $section_json_id);
                
            $section_id_array = json_decode($section_json_id);
            
        }else{ // from import file under section form(Add New)
        
            $current_section_id = $section_import[parent::F_SECTION_ID]; // can be empty for add new, twiz.importexport.class.php importData()

            $section_status = $section_import[parent::F_STATUS];
            $visibility = $section_import[parent::KEY_VISIBILITY];
            $section_name = $section_import[parent::KEY_TITLE];
            $output_choice = $this->getOutputChoiceValue($section_import[parent::F_SECTION_ID.'_orig']);
            $custom_logic = $section_import[parent::KEY_CUSTOM_LOGIC];
            $shortcode = $section_import[parent::KEY_SHORTCODE];
            $cookie_condition = $section_import[parent::KEY_COOKIE_CONDITION];
            $cookie_name = $section_import[parent::KEY_COOKIE_NAME];
            $cookie_option_1 = $section_import[parent::KEY_COOKIE_OPTION_1];
            $cookie_option_2 = $section_import[parent::KEY_COOKIE_OPTION_2];
            $cookie_with = $section_import[parent::KEY_COOKIE_WITH];
            $cookie_scope = $section_import[parent::KEY_COOKIE_SCOPE];
            $shortcode_html = $section_import[parent::KEY_SHORTCODE_HTML];
            
            $blogid_array =  json_decode( $section_import[parent::F_BLOG_ID] );

            if( ( $output_choice == 'twiz_multiple_output')
            and (($section_import[parent::KEY_MULTI_SECTIONS][0] == '' ) ) ){
            
                $section_id_array = array($section_import[parent::F_SECTION_ID.'_orig']); // used for converted p, a, c types, to have a selection.
                
            }else{
             
                $section_id_array =  $section_import[parent::KEY_MULTI_SECTIONS];
            }
        }

        if( $output_choice == '' ){return '';}
        if( $section_name == '' ){return '';}
        
        $section_name = ($section_name == '') ? __('Give the section a name', 'the-welcomizer') : $section_name;
                        
        switch($output_choice){
            
            case 'twiz_single_output':
            
                $section = array(parent::F_STATUS   => $section_status 
                                ,parent::KEY_VISIBILITY => $visibility
                                ,parent::F_BLOG_ID => $blogid_array
                                ,parent::KEY_TITLE  => $section_name
                                ,parent::KEY_SHORTCODE => $shortcode                               
                                ,parent::KEY_SHORTCODE_HTML => $shortcode_html                               
                                ,parent::KEY_CUSTOM_LOGIC => $custom_logic    
                                ,parent::KEY_COOKIE_CONDITION  => $cookie_condition
                                ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => $cookie_name 
                                                            ,parent::KEY_COOKIE_OPTION_1 => $cookie_option_1                 
                                                            ,parent::KEY_COOKIE_OPTION_2 => $cookie_option_2 
                                                            ,parent::KEY_COOKIE_WITH     => $cookie_with  
                                                            ,parent::KEY_COOKIE_SCOPE => $cookie_scope  
                                                            )
                                );
                                
                if( $current_section_id != "" ){
                
                    if( !isset($this->array_multi_sections[$current_section_id]) ){}else{ unset($this->array_multi_sections[$current_section_id]); }
                    if( !isset($this->array_sections[$current_section_id]) ){}else{ unset($this->array_sections[$current_section_id]);}
                    
                }else{
                
                    if( $this->override_network_settings == '1' ){

                        $section_id_array[0] = $section_id_array[0].'_'.$this->BLOG_ID;
                            
                    }             
                }
                
                if((!in_array($current_section_id, $this->array_hardsections)) 
                and ($current_section_id != "") and ($current_section_id != $section_id_array[0] )){
                    
                    // Replace all section_id.
                    $updatesql = "UPDATE ".$this->table . " SET
                    ". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", '_".$current_section_id."', '_".$section_id_array[0] ."') 
                    ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", '_".$current_section_id."', '_".$section_id_array[0] ."') 
                    ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", '_".$current_section_id."', '_".$section_id_array[0] ."') 
                    WHERE ". parent::F_SECTION_ID ." = '".$current_section_id."'";
                    $code = $wpdb->query($updatesql);
                
                    // update the section
                    $sql = "UPDATE ".$this->table." 
                    SET ".parent::F_SECTION_ID." = '". $section_id_array[0] ."'               
                    WHERE ".parent::F_SECTION_ID." = '". $current_section_id ."';";
                        
                    $code = $wpdb->query($sql);
                }

                if( !isset($this->array_sections[$section_id_array[0]]) ) $this->array_sections[$section_id_array[0]] = '';
                
                $this->array_sections[$section_id_array[0]] = $section;
                
                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
                   
                    $code = update_option('twiz_multi_sections', $this->array_multi_sections);
                    $code = update_option('twiz_sections', $this->array_sections);
                    
                }else{

                    $code = update_site_option('twiz_multi_sections', $this->array_multi_sections);
                    $code = update_site_option('twiz_sections', $this->array_sections);
                }                 
  
                $code = $this->cleanCookieCondition($section_id_array[0], $cookie_option_1);
                $code = $this->updateJSCookieStatus($section_id_array[0], $cookie_option_1, $cookie_with);
                $code = $this->updateRowsBlogId($section_id_array[0], $blogid_array);
                        
                return $section_id_array[0];

                break;
                
            case 'twiz_multiple_output':
            
                // update multi selection and unique or multi hard section.
                $section = array(parent::F_STATUS   => $section_status 
                                ,parent::KEY_VISIBILITY => $visibility
                                ,parent::F_BLOG_ID => $blogid_array
                                ,parent::KEY_TITLE  => $section_name
                                ,parent::KEY_SHORTCODE => $shortcode                               
                                ,parent::KEY_SHORTCODE_HTML => $shortcode_html                               
                                ,parent::KEY_CUSTOM_LOGIC => $custom_logic    
                                ,parent::KEY_COOKIE_CONDITION  => $cookie_condition                                
                                ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => $cookie_name 
                                                            ,parent::KEY_COOKIE_OPTION_1 => $cookie_option_1                 
                                                            ,parent::KEY_COOKIE_OPTION_2 => $cookie_option_2 
                                                            ,parent::KEY_COOKIE_WITH     => $cookie_with  
                                                            ,parent::KEY_COOKIE_SCOPE => $cookie_scope
                                                            )  
                                );
 
                if( !isset($this->array_multi_sections[$current_section_id]) ){}else{ unset($this->array_multi_sections[$current_section_id]); }
                if( !isset($this->array_sections[$current_section_id]) ){}else{ unset($this->array_sections[$current_section_id]);}
                
                $newprefix = "ms_".($this->getMaxKeyArraySections() + 1);
                
                // add type replacement
                if((preg_match("/cl_/i", $current_section_id))
                or (preg_match("/sc_/i", $current_section_id))
                or ( (preg_match("/c_/i", $current_section_id)) and (!preg_match("/sc_/i", $current_section_id)))
                or (preg_match("/p_/i", $current_section_id))
                or (preg_match("/a_/i", $current_section_id))){
             
                    if( $this->override_network_settings == '1' ){

                        $new_section_id = $newprefix.'_'.$this->BLOG_ID;
                            
                    }else{
                    
                        $new_section_id = $newprefix;
                    }
                }
               
                // Update or insert
                if($current_section_id != "") {  // update

                    $section_id = ($new_section_id != "" ) ? $new_section_id : $current_section_id;
                    
                }else{ // insert
                
                    if( $this->override_network_settings == '1' ){

                        $section_id = $newprefix.'_'.$this->BLOG_ID;
                            
                    }else{
                    
                        $section_id = $newprefix;
                    }
                }
                
                if(($current_section_id != "")
                and ($current_section_id != $section_id )){
           
                    // Replace all section_id.
                    $updatesql = "UPDATE ".$this->table . " SET
                     ". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", '_".$current_section_id."', '_".$section_id."') 
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
                              
                $this->array_multi_sections[$section_id] = $section_id_array;
                $this->array_sections[$section_id] = $section;

                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
                   
                    $code = update_option('twiz_multi_sections', $this->array_multi_sections);
                    $code = update_option('twiz_sections', $this->array_sections);
                    
                }else{

                    $code = update_site_option('twiz_multi_sections', $this->array_multi_sections);
                    $code = update_site_option('twiz_sections', $this->array_sections);
                }       
                $code = $this->cleanCookieCondition($section_id, $cookie_option_1);
                $code = $this->updateJSCookieStatus($section_id, $cookie_option_1, $cookie_with);
                $code = $this->updateRowsBlogId($section_id, $blogid_array);
                
                return $section_id;
                    
                break;
                
            case 'twiz_logic_output':
            
                $section = array(parent::F_STATUS   => $section_status
                                ,parent::KEY_VISIBILITY => $visibility
                                ,parent::F_BLOG_ID => $blogid_array
                                ,parent::KEY_TITLE  => $section_name
                                ,parent::KEY_SHORTCODE => $shortcode                               
                                ,parent::KEY_SHORTCODE_HTML => $shortcode_html                               
                                ,parent::KEY_CUSTOM_LOGIC => $custom_logic                             
                                ,parent::KEY_COOKIE_CONDITION  => $cookie_condition                                
                                ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => $cookie_name 
                                                            ,parent::KEY_COOKIE_OPTION_1 => $cookie_option_1                 
                                                            ,parent::KEY_COOKIE_OPTION_2 => $cookie_option_2 
                                                            ,parent::KEY_COOKIE_WITH     => $cookie_with  
                                                            ,parent::KEY_COOKIE_SCOPE => $cookie_scope  
                                                            )                                      
                                );
                                         
                if( !isset($this->array_multi_sections[$current_section_id]) ){}else{ unset($this->array_multi_sections[$current_section_id]); }
                if( !isset($this->array_sections[$current_section_id]) ){}else{ unset($this->array_sections[$current_section_id]);}
                    
                $newprefix = "cl_".($this->getMaxKeyArraySections() + 1);
                
                // add type replacement
                if((preg_match("/ms_/i", $current_section_id))
                or (preg_match("/sc_/i", $current_section_id))
                or ( (preg_match("/c_/i", $current_section_id)) and (!preg_match("/sc_/i", $current_section_id)))
                or (preg_match("/p_/i", $current_section_id))
                or (preg_match("/a_/i", $current_section_id))){
             
                    if( $this->override_network_settings == '1' ){

                        $new_section_id = $newprefix.'_'.$this->BLOG_ID;
                            
                    }else{
                    
                        $new_section_id = $newprefix;
                    }
                }
               
                // Update or insert
                if($current_section_id != "") {  // update

                    $section_id = ($new_section_id != "" ) ? $new_section_id : $current_section_id;
                    
                }else{ // insert

                    if( $this->override_network_settings == '1' ){

                        $section_id = $newprefix.'_'.$this->BLOG_ID;
                            
                    }else{
                    
                        $section_id = $newprefix;
                    }
                }
 
                if(($current_section_id != "")
                and ($current_section_id != $section_id )){
           
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
            
                if( !isset($this->array_sections[$section_id]) ) $this->array_sections[$section_id] = '';
                               
                $this->array_sections[$section_id] = $section;
                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
                   
                    $code = update_option('twiz_multi_sections', $this->array_multi_sections);
                    $code = update_option('twiz_sections', $this->array_sections);
                    
                }else{

                    $code = update_site_option('twiz_multi_sections', $this->array_multi_sections);
                    $code = update_site_option('twiz_sections', $this->array_sections);
                }       
                $code = $this->cleanCookieCondition($section_id, $cookie_option_1);
                $code = $this->updateJSCookieStatus($section_id, $cookie_option_1, $cookie_with);
                $code = $this->updateRowsBlogId($section_id, $blogid_array);
                
                return $section_id;
                
            break;
            
            case 'twiz_shortcode_output':
            
                $section = array(parent::F_STATUS   => $section_status 
                                ,parent::KEY_VISIBILITY => $visibility
                                ,parent::F_BLOG_ID => $blogid_array
                                ,parent::KEY_TITLE  => $section_name
                                ,parent::KEY_SHORTCODE => $shortcode                               
                                ,parent::KEY_SHORTCODE_HTML => $shortcode_html                               
                                ,parent::KEY_CUSTOM_LOGIC => $custom_logic                                
                                ,parent::KEY_COOKIE_CONDITION  => $cookie_condition                                
                                ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => $cookie_name 
                                                            ,parent::KEY_COOKIE_OPTION_1 => $cookie_option_1                 
                                                            ,parent::KEY_COOKIE_OPTION_2 => $cookie_option_2 
                                                            ,parent::KEY_COOKIE_WITH     => $cookie_with  
                                                            ,parent::KEY_COOKIE_SCOPE => $cookie_scope 
                                                            )                                        
                                );
                                         
                if( !isset($this->array_multi_sections[$current_section_id]) ){}else{ unset($this->array_multi_sections[$current_section_id]); }
                if( !isset($this->array_sections[$current_section_id]) ){}else{ unset($this->array_sections[$current_section_id]);}
                    
                $newprefix = "sc_".($this->getMaxKeyArraySections() + 1);
           
                // add type replacement
                if((preg_match("/ms_/i", $current_section_id))
                or (preg_match("/cl_/i", $current_section_id))
                or ( (preg_match("/c_/i", $current_section_id)) and (!preg_match("/sc_/i", $current_section_id)))
                or (preg_match("/p_/i", $current_section_id))
                or (preg_match("/a_/i", $current_section_id))){
             
                    if( $this->override_network_settings == '1' ){

                        $new_section_id = $newprefix.'_'.$this->BLOG_ID;
                            
                    }else{
                    
                        $new_section_id = $newprefix;
                    }
                }
                
                // Update or insert
                if($current_section_id != "") {  // update

                    $section_id = ($new_section_id != "" ) ? $new_section_id : $current_section_id;
                    
                }else{ // insert

                    if( $this->override_network_settings == '1' ){

                        $section_id = $newprefix.'_'.$this->BLOG_ID;
                            
                    }else{
                    
                        $section_id = $newprefix;
                    }
                }
 
                if(($current_section_id != "")
                and ($current_section_id != $section_id )){
           
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
            
                if( !isset($this->array_sections[$section_id]) ) $this->array_sections[$section_id] = '';

                $this->array_sections[$section_id] = $section;

                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
                   
                    $code = update_option('twiz_multi_sections', $this->array_multi_sections);
                    $code = update_option('twiz_sections', $this->array_sections);
                    
                }else{

                    $code = update_site_option('twiz_multi_sections', $this->array_multi_sections);
                    $code = update_site_option('twiz_sections', $this->array_sections);
                }       
                $code = $this->cleanCookieCondition($section_id, $cookie_option_1);
                $code = $this->updateJSCookieStatus($section_id, $cookie_option_1, $cookie_with);
                $code = $this->updateRowsBlogId($section_id, $blogid_array);
                 
                return $section_id;
                
            break;
            
            case 'twiz_default':
            
               $section_id = $current_section_id;
            
               $section = array(parent::F_STATUS   => $section_status 
                               ,parent::KEY_VISIBILITY => $visibility                               
                               ,parent::F_BLOG_ID => $blogid_array                              
                               ,parent::KEY_TITLE  => $section_name
                               ,parent::KEY_SHORTCODE => $shortcode                               
                               ,parent::KEY_SHORTCODE_HTML => $shortcode_html                               
                               ,parent::KEY_CUSTOM_LOGIC => $custom_logic                               
                               ,parent::KEY_COOKIE_CONDITION  => $cookie_condition                               
                               ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => $cookie_name 
                                                           ,parent::KEY_COOKIE_OPTION_1 => $cookie_option_1                 
                                                           ,parent::KEY_COOKIE_OPTION_2 => $cookie_option_2 
                                                           ,parent::KEY_COOKIE_WITH     => $cookie_with  
                                                           ,parent::KEY_COOKIE_SCOPE => $cookie_scope  
                                                           )                                      
                                );
              $this->array_hardsections[$section_id] = $section;
              if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                  $code = update_option('twiz_hardsections', $this->array_hardsections);
                    
              }else{

                  $code = update_site_option('twiz_hardsections', $this->array_hardsections);
              }               
              $code = $this->cleanCookieCondition($section_id, $cookie_option_1);
              $code = $this->updateJSCookieStatus($section_id, $cookie_option_1, $cookie_with);
              $code = $this->updateRowsBlogId($section_id, $blogid_array);
              
              return $section_id;
            
              break;
        }
    }    
  
    private function cleanCookieCondition( $section_id = '', $cookie_option_1 = ''){
    
        if( $cookie_option_1 == '' ){
        
            $hardsections = $this->array_hardsections;
            $sections = $this->array_sections;


            foreach ( $hardsections as $key => $value ){
               
               if( $hardsections[$key][parent::KEY_COOKIE_CONDITION] == $section_id ) {
               
                    $this->array_hardsections[$key][parent::KEY_COOKIE_CONDITION] = '';
               }
            }            

            foreach ( $sections as $key => $value ){
                    
               if( $sections[$key][parent::KEY_COOKIE_CONDITION]  == $section_id ) {
               
                    $this->array_sections[$key][parent::KEY_COOKIE_CONDITION] = '';
               }
            }
            // update options
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                $code = update_option('twiz_sections', $this->array_sections);
                $code = update_option('twiz_hardsections', $this->array_hardsections);
            
            }else{
            
                $code = update_site_option('twiz_sections', $this->array_sections);
                $code = update_site_option('twiz_hardsections', $this->array_hardsections);
            }         
        }
        
        return true;
    }
    
    private function updateJSCookieStatus( $section_id = '', $cookie_option_1 = '', $cookie_with = '' ){
    
        if( ($cookie_option_1 != '')
        and(($cookie_with == 'js') or ($cookie_with == 'all')) ){
        
            // update options
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                $code = update_option('twiz_cookie_js_status', true);
                
            }else{

                $code = update_site_option('twiz_cookie_js_status', true);
            }         
            
        }else{
        
            $hasOtherSectionCookies = $this->hasOtherSectionCookies( $section_id );
            
            if($hasOtherSectionCookies == false){
            
                // update options
                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                    $code = update_option('twiz_cookie_js_status', false);
                    
                }else{

                    $code = update_site_option('twiz_cookie_js_status', false);
                }  
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
    
    private function deleteToggleGroup( $section_id = '' ){
    
        global $wpdb;
        
        if( $section_id == '' ){return false;}
        
        $where = " WHERE ".parent::F_SECTION_ID." = '".$section_id."' AND ".parent::F_TYPE." = '".parent::ELEMENT_TYPE_GROUP."'";
        $listarray = $this->getListArray( $where );
        
        foreach( $listarray as $key => $value ){
        
            unset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_GROUP][$value[parent::F_EXPORT_ID]]);
        }  
        
        // update options
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $code = update_option('twiz_toggle', $this->toggle_option);
            
        }else{

            $code = update_site_option('twiz_toggle', $this->toggle_option);
        }         
        
        return true;
    }
    
    function deleteSectionMenu( $section_id = '' ){
    
        global $wpdb;
        
        if( $section_id == '' ){return false;}
        
        $ok = $this->deleteToggleGroup( $section_id );
        $ok = $this->unsetExportFilter( $section_id );
         
        $sql = "DELETE from ".$this->table." where ".parent::F_SECTION_ID." = '".$section_id."';";
        $code = $wpdb->query($sql);
        
        // Hard sections are not deleted
        if( !in_array($section_id, $this->array_default_section) ){
            
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
            
            // update options
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
                
                $code = update_option('twiz_sections', $this->array_sections);
                $code = update_option('twiz_multi_sections', $this->array_multi_sections);
                
            }else{
            
                $code = update_site_option('twiz_sections', $this->array_sections);
                $code = update_site_option('twiz_multi_sections', $this->array_multi_sections);
            }             


        }else{
        
            return $section_id; // Stay here
        }

        return $this->DEFAULT_SECTION_HOME;
    }     
    
    private function unsetExportFilter( $section_id = '' ){
    
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $twiz_export_filter = get_option('twiz_export_filter');
            
        }else{
        
            $twiz_export_filter = get_site_option('twiz_export_filter');
        }     
    
        if(!isset($twiz_export_filter[$this->user_id][$section_id])){}else{
        
            unset($twiz_export_filter[$this->user_id][$section_id]);     
        }
        
        // update options
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $code = update_option('twiz_export_filter', $twiz_export_filter);  
            
        }else{

            $code = update_site_option('twiz_export_filter', $twiz_export_filter);  
        }         
        
        return true;
    }
    
    function emptySectionMenu( $section_id = '' ){
    
        global $wpdb;
        
        if( $section_id == '' ){return false;}
         
        $sql = "DELETE from ".$this->table." where ".parent::F_SECTION_ID." = '".$section_id."';";
        $code = $wpdb->query($sql);

        return $section_id;
    }     

    private function getHtmlSingleSection( $section_id = '' ){

        $html = '';
        $select_cat = '';
        $select_page = '';
        $select_post = '';
        $separator_page = '';
        $separator_post = '';
        $separator_cat = '';
        $selected_post_first = '';
        $count_array_sections = 0;
        $array_sections = array();
        
        $sections = $this->array_sections; // check single to exclude unique key
        
        if(( $section_id != "" )
        and( $section_id != __('Add New', 'the-welcomizer') ) ){

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
        
            if( $this->override_network_settings == '1' ){

                $value['cat_ID'] = $value['cat_ID'].'_'.$this->BLOG_ID;
            }  
                    
            $separator_cat = '<option value=""disabled="disabled">------------------------------------------------------</option>';
            
            if(in_array('c_'.$value['cat_ID'], $array_sections) 
            and (($count_array_sections==1) and ($type!= "ms"))){
            
                $selected = ' selected="selected"';
                $select_cat .= '<option value="c_'.$value['cat_ID'].'"'.$selected .'>'.$value['cat_name'].'</option>';
                
            }else{
            
                if( ((in_array('c_'.$value['cat_ID'], $array_sections)and ($type!= "ms")))
                or ( !array_key_exists('c_'.$value['cat_ID'], $sections)) ){
                
                     $selected = '';
                     $select_cat .= '<option value="c_'.$value['cat_ID'].'"'.$selected .'>'.$value['cat_name'].'</option>';
                }
            }
        }
        
        foreach( $this->pages as $value ){
        
            if( $this->override_network_settings == '1' ){

                $value['ID'] = $value['ID'].'_'.$this->BLOG_ID;
            }  
            
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
     
            if( $this->override_network_settings == '1' ){

                $value['ID'] = $value['ID'].'_'.$this->BLOG_ID;
            }  
            
            if(in_array('a_'.$value['ID'], $array_sections) 
            and (($count_array_sections==1) and ($type!= "ms"))){

                $selected_post_first .=  '<option value="a_'.$value['ID'].'" selected="selected">'. mysql2date('Y-m-d', $value['post_date']). ' : '.$value['post_title'].'</option>';
            }
        }
            
        $i = 1;
        foreach( $this->allposts as $value ){
        
            if($i > $this->admin_option[parent::KEY_NUMBER_POSTS]){
            
                break;
            }
            
            if( $this->override_network_settings == '1' ){

                $value['ID'] = $value['ID'].'_'.$this->BLOG_ID;
            }  
            
            $separator_post = '<option value="" disabled="disabled">------------------------------------------------------------------------------------------------------------</option>';

            if( ((in_array('a_'.$value['ID'], $array_sections))and ($type!= "ms"))
            or(!array_key_exists('a_'.$value['ID'], $sections)) ){

                $select_post .= '<option value="a_'.$value['ID'].'">'. mysql2date('Y-m-d', $value['post_date']). ' : '.$value['post_title'].'</option>';
            }
            
            $i++;
        }
        
        // close select 
        $html =  $select.$separator_cat.$select_cat.$separator_page.$select_page.$separator_post.$selected_post_first.$select_post.'</select>';
        
        return $html;
    }
    
    private function getHtmlMultiSection( $section_id = '', $array_sections = array() ){
    
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
        foreach( $this->array_hardsections_translation as $key => $value ){
    
            $selected_hard = (in_array($key, $array_sections)) ? ' selected="selected"' : '';
            $select_hardsection .= '<option value="'.$key.'"'. $selected_hard . '>'.$value.'</option>';
        }

        foreach( $this->categories as $value ){
     
            $separator_cat = '<option value="" disabled="disabled">------------------------------------------------------</option>';
            $selected = (in_array('c_'.$value['cat_ID'], $array_sections)) ? ' selected="selected"' : '';
            $select_cat .= '<option value="c_'.$value['cat_ID'].'"'. $selected .'>'.$value['cat_name'].'</option>';
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
        
            if($i > $this->admin_option[parent::KEY_NUMBER_POSTS]){
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
    
    private function getHtmlCookieConditionList(  $section_id = '' ){

        $selected = '';
        $hardsections = $this->array_hardsections;
        $sections = $this->array_sections;
         
        $select = '<select id="twiz_slc_cookie_condition">';
        $select .= '<option value="">'.__('Disabled', 'the-welcomizer').'</option>';

        foreach ( $hardsections as $key => $value ){
           
                if( !isset( $sections[$section_id][parent::KEY_COOKIE_CONDITION] ) ){ $selected = ''; }else{
                    $selected = ($sections[$section_id][parent::KEY_COOKIE_CONDITION]  ==  $key) ? ' selected="selected"' : '';
                }
                if( !isset( $hardsections[$section_id][parent::KEY_COOKIE_CONDITION] ) ){ }else{
                    $selected = ($hardsections[$section_id][parent::KEY_COOKIE_CONDITION]  ==  $key) ? ' selected="selected"' : '';
                }                
                if( ( $section_id != $key ) 
                and ($value[parent::KEY_COOKIE][parent::KEY_COOKIE_NAME] != '' ) 
                and ($value[parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] != '' ) ) {
                    $select .= '<option value="'. $key.'"'.$selected.'>'.$value[parent::KEY_TITLE].'</option>';
                }
        }            

        foreach ( $sections as $key => $value ){
                
                if( !isset( $sections[$section_id][parent::KEY_COOKIE_CONDITION] ) ){ $selected = ''; }else{
                    $selected = ($sections[$section_id][parent::KEY_COOKIE_CONDITION]  ==  $key) ? ' selected="selected"' : '';
                }                
                if( !isset( $hardsections[$section_id][parent::KEY_COOKIE_CONDITION] ) ){ }else{
                    $selected = ($hardsections[$section_id][parent::KEY_COOKIE_CONDITION]  ==  $key) ? ' selected="selected"' : '';
                }
                if( ( $section_id != $key ) 
                and ($value[parent::KEY_COOKIE][parent::KEY_COOKIE_NAME] != '' ) 
                and ($value[parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] != '' ) ) {
                            $select .= '<option value="'. $key.'"'.$selected.'>'.$value[parent::KEY_TITLE].'</option>';
                }
            
        }
        
        $select .= '</select>';
            
        return $select;
    }  
    
    function getHtmlMultiSectionBoxes( $section_id = '', $action = '', $stay = ''){
 
        $html = '';
        $type = '';
        $choices = '';
        $jsscript_open = '';
        $jsscript = '';
        $jsscript_close = '';
        $jsscript_in = '';
        $twiz_custom_logic ='';
        $twiz_section_name = '';
        $twiz_blog_id_array = array();
        $twiz_shortcode = '';
        $twiz_shortcode_sample = '';
        $array_sections = array();
        $twiz_cookie_name = '';
        $twiz_shortcode_html = '';
        $twiz_output_choice_0 = '';
        $twiz_output_choice_1 = '';
        $twiz_output_choice_2 = '';
        $twiz_output_choice_3 = '';
        $twiz_output_choice_4 = '';
        $lbl_action = '';
        
        if(in_array($section_id, $this->array_default_section)){

            $section_id = 'default_' . $section_id;
        }
        
        if(( $section_id != "" )
        and( $action != parent::ACTION_NEW ) ){
         
            list($type, $id ) = preg_split('/_/', $section_id);
            
            switch($type){
            
                case 'sc': // is shortcode
                
                    $twiz_output_choice_0 = ' class="twiz-bold"';
                    $jsscript_in = '$(".twiz-custom-message").html("");
$("#twiz_output_choice_0").attr("checked", "checked");
$(".twiz-block-ouput").hide();
$("#twiz_shortcode_output").show();
$("#twiz_shortcode_sample").select();';

                    break;
                    
                case 'ms': // is custom multi-sections
                
                    $twiz_output_choice_2 = ' class="twiz-bold"';
                    $array_sections = $this->array_multi_sections[$section_id];
                    $jsscript_in = '$(".twiz-custom-message").html("");
$(".twiz-block-ouput").hide();
$("#twiz_output_choice_2").attr("checked", "checked");
$("#twiz_multiple_output").show();
$("#twiz_section_name").focus();';

                    
                    break;
                    
                case 'cl': // is custom logic
                    $twiz_output_choice_3 = ' class="twiz-bold"';
                    $jsscript_in = '$(".twiz-custom-message").html("");
$("#twiz_output_choice_3").attr("checked", "checked");
$(".twiz-block-ouput").hide();
$("#twiz_logic_output").show();
$("#twiz_section_name").focus();';
                    break;
                    
                case 'default': // is default section
                
                    $twiz_output_choice_4 = ' class="twiz-bold"';
                    $section_id = str_replace('default_','',$section_id);
                    $jsscript_in = '$(".twiz-custom-message").html("");
$("#twiz_output_choice_0").attr("checked", "checked");
$(".twiz-block-ouput").hide();
$("#twiz_logic_output").hide();
$("#twiz_shortcode_output").hide();
$("#twiz_section_name").focus();';
                    break;
                    
                default:
                
                    $twiz_output_choice_1 = ' class="twiz-bold"';
                    $jsscript_in = '$(".twiz-custom-message").html("");
$("#twiz_output_choice_1").attr("checked", "checked");
$(".twiz-block-ouput").hide();
$("#twiz_single_output").show();
$("#twiz_section_name").focus();';
            }
            
        }else{
            
                $jsscript_in = '$(".twiz-custom-message").html("");
$("#twiz_output_choice_0").attr("checked", "checked");
$(".twiz-block-ouput").hide();
$("#twiz_shortcode_output").show();
$("#twiz_section_name").focus();';
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
        if( !isset($twiz_slc_cookie_option['perwebsite']) ) $twiz_slc_cookie_option['perwebsite'] = '';
        if( !isset($twiz_slc_cookie_option['perdirectory']) ) $twiz_slc_cookie_option['perdirectory'] = '';
        if( !isset($twiz_slc_cookie_with['all']) ) $twiz_slc_cookie_with['all'] = '';
        if( !isset($twiz_slc_cookie_with['js']) ) $twiz_slc_cookie_with['js'] = '';
        if( !isset($twiz_slc_cookie_with['php']) ) $twiz_slc_cookie_with['php'] = '';
        
        if( !isset($twiz_visibility[parent::VISIBILITY_EVERYONE]) )$twiz_visibility[parent::VISIBILITY_EVERYONE] = '';
        if( !isset($twiz_visibility[parent::VISIBILITY_VISITORS]) )$twiz_visibility[parent::VISIBILITY_VISITORS] = '';
        if( !isset($twiz_visibility[parent::VISIBILITY_MEMBERS]) )$twiz_visibility[parent::VISIBILITY_MEMBERS] = '';
        if( !isset($twiz_visibility[parent::VISIBILITY_ADMINS]) )$twiz_visibility[parent::VISIBILITY_ADMINS] = '';
        
        if(( $section_id != "" )
        and( $action != parent::ACTION_NEW ) ){
        
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
            
            $twiz_slc_cookie_option['perwebsite'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_SCOPE] == 'perwebsite') ? ' selected="selected"' : '';
            $twiz_slc_cookie_option['perdirectory'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_SCOPE] == 'perdirectory') ? ' selected="selected"' : '';
            
            $twiz_slc_cookie_with['all'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH] == 'all') ? ' selected="selected"' : '';
            $twiz_slc_cookie_with['js'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH] == 'js') ? ' selected="selected"' : '';
            $twiz_slc_cookie_with['php'] = ($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH] == 'php') ? ' selected="selected"' : '';
            $twiz_cookie_name = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME];

            
            $twiz_visibility[parent::VISIBILITY_EVERYONE] = ($sections[$section_id][parent::KEY_VISIBILITY] == parent::VISIBILITY_EVERYONE) ? ' selected="selected"' : '';
            $twiz_visibility[parent::VISIBILITY_VISITORS] = ($sections[$section_id][parent::KEY_VISIBILITY] == parent::VISIBILITY_VISITORS) ? ' selected="selected"' : '';
            $twiz_visibility[parent::VISIBILITY_MEMBERS] = ($sections[$section_id][parent::KEY_VISIBILITY] == parent::VISIBILITY_MEMBERS) ? ' selected="selected"' : '';
            $twiz_visibility[parent::VISIBILITY_ADMINS] = ($sections[$section_id][parent::KEY_VISIBILITY] == parent::VISIBILITY_ADMINS) ? ' selected="selected"' : '';
 
            $twiz_shortcode = $sections[$section_id][parent::KEY_SHORTCODE];
            $twiz_shortcode_html = $sections[$section_id][parent::KEY_SHORTCODE_HTML];
            $twiz_custom_logic = $sections[$section_id][parent::KEY_CUSTOM_LOGIC];
            
            $twiz_section_status = ( $sections[$section_id][parent::F_STATUS] == parent::STATUS_ACTIVE ) ? ' checked="checked"' : '';
            
            if($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] != '') {

                $jsscript_in .= '
$("#twiz_div_cookie_with").show();
$("#twiz_div_cookie_name").show();
$("#twiz_div_cookie_option_2").show();
$("#twiz_div_cookie_condition").show();
';
            }
            
            $twiz_section_name = $this->getSectionName($section_id);
            
            // Remove red from deleted
            $twiz_section_name = str_replace('<span class="twiz-status-red">', '', $twiz_section_name );
            $twiz_section_name = str_replace('</span>', '', $twiz_section_name );
        
        }else{
            
            $twiz_section_status = ' checked="checked"';
        
        }        
        
        $hide_shortcode_section = ( $twiz_shortcode_html == '' ) ? ' twiz-display-none': '';
        
        $twiz_shortcode_sample = ( $twiz_shortcode != '' ) ? '[twiz id="'.$twiz_shortcode.'"]' : '[twiz id="'. __('Example', 'the-welcomizer').'"]';
        $twiz_shortcode_sample_theme = ( $twiz_shortcode != '' ) ? '<?php echo do_shortcode( \'[twiz id="'.$twiz_shortcode.'"]\' ); ?>' : '<?php echo do_shortcode( \'[twiz id="'. __('Example', 'the-welcomizer').'"]\' ); ?>';
                
        $jsscript_open = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) { ';

        switch( $action ){
        
            case parent::ACTION_NEW:
            
                $lbl_action = __('Add New', 'the-welcomizer');
                $jsscript .= '$("#twiz_import").fadeIn("fast");';
                break;
                
            case parent::ACTION_EDIT:
            
                $lbl_action = __('Edit', 'the-welcomizer');
                $jsscript .= '$("#twiz_export").fadeIn("fast");';
                break;
        }
        
        // Stay on the same page switch
        switch( $stay ) {
        
            case 'true':

                $jsscript .= '$("[name=twiz_listmenu]").css("display", "none");
                $("#twiz_import").hide();
                ';
                break;

            case parent::ACTION_IMPORT: // from server or from computer

                $stay = ''; // not stay exception
                $jsscript .= '
                $("#twiz_shortcode_sample").select();
                $("#twiz_shortcode_sample").animate({opacity:0}, 300, function(){$("#twiz_shortcode_sample").animate({opacity:1}, 300, function(){})});';
                
                break;
        }
        
        $jsscript .= '$(".twiz-more-configs").click(function(){
            var twiz_textid = $(this).attr("id");
            switch(twiz_textid){
                case "twiz_more_options":
                    if ( twiz_showOrHide_more_section_options == false ) {
                        $("#" + twiz_textid).html("'.__('Less options', 'the-welcomizer').' &#187;");
                        twiz_showOrHide_more_section_options = true;
                    } else if ( twiz_showOrHide_more_section_options == true ) {
                        $("#" + twiz_textid).html("'.__('More options', 'the-welcomizer').' &#187;");
                        twiz_showOrHide_more_section_options = false;
                    }
                    $(".twiz-section-more-options").toggle(twiz_showOrHide_more_section_options);
                    break;
                case "twiz_more_shortcode_html":
                    $("#twiz_shortcode_section").fadeIn("fast");
                    $("#twiz_shortcode_html").focus();
                    $(this).after("'.__('HTML(optional)', 'the-welcomizer').'").remove();
                    break;
            }
        });';
    
        $jsscript .= '$(".twiz-tab").click(function(){
        if(($("#twiz_tab_cookie").attr("class") == "twiz-display-none")
        &&($(this).html() == "'.__('jQuery Limit', 'the-welcomizer').'")){
            $(".twiz-tab").attr({"class":"twiz-tab twiz-corner-top"});
            $(this).attr({"class":"twiz-tab twiz-corner-top twiz-tab-selected"});
            $("#twiz_tab_cookie").attr({"class":""});
            $("#twiz_tab_activation").attr({"class":"twiz-display-none"});
        }else if(($("#twiz_tab_activation").attr("class") == "twiz-display-none")
        &&($(this).html() != "'.__('jQuery Limit', 'the-welcomizer').'")){
            $(".twiz-tab").attr({"class":"twiz-tab twiz-corner-top"});
            $(this).attr({"class":"twiz-tab twiz-corner-top twiz-tab-selected"});
            $("#twiz_tab_cookie").attr({"class":"twiz-display-none"});
            $("#twiz_tab_activation").attr({"class":""});
        }});';
 
        $jsscript .= '$("#twiz_far_matches").html("");';
        
        $jsscript .= '
textarea = new Object();
textarea.expand = function(textbox){
    twizsizeOrig(textbox);
    textbox.style.height = (textbox.scrollHeight + 20) + "px";
    textbox.style.width = (textbox.scrollWidth + 40) + "px";
} 
function twizsizeOrig(textbox){
    $(textbox).css({"z-index": 10, "width": "489px","height": "90px"});
}
$("textarea[name=twiz_shortcode_html]").blur(function (){
   twizsizeOrig(this);
   $(this).css({"z-index":1});
});';

        $jsscript .= $jsscript_in;
        
$jsscript_close = '});
//]]>
</script>';


        // Toggle starting config 
        $jsscript_more_options = '$(".twiz-section-more-options").toggle();';
        
        if( !isset($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1]) ) $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] = '';
        if( !isset($sections[$section_id][parent::KEY_COOKIE_CONDITION]) ) $sections[$section_id][parent::KEY_COOKIE_CONDITION] = '';        
        
        // toggle more options by default if we have values        
        if( ( $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] != '' )
         or( $sections[$section_id][parent::KEY_COOKIE_CONDITION] != '' ) ){
         
            $jsscript .= $jsscript_more_options;
            $lbl_more_options = __('Less options', 'the-welcomizer');
            $jsscript_open .= 'twiz_showOrHide_more_section_options = true;';
            
        }else{
        
            $lbl_more_options = __('More options', 'the-welcomizer');
            $jsscript_open .= 'twiz_showOrHide_more_section_options = false;';
        }
        
        
        // radio menu choice
        $choices = ' <fieldset class="twiz-box-fieldset twiz-corner-all">
<legend>'.__('Output type', 'the-welcomizer').'</legend>';

        if(in_array($section_id, $this->array_default_section)){
        
            $choices .= '<input type="radio" id="twiz_output_choice_4" name="twiz_output_choice" class="twiz-output-choice twiz-display-none" value="twiz_default" checked="checked"/> <label for="twiz_output_choice_4"'.$twiz_output_choice_4.'>'.__('Default', 'the-welcomizer').'</label> ';
            
        }else{        
        
            $choices .= '<input type="radio" id="twiz_output_choice_0" name="twiz_output_choice" class="twiz-output-choice" value="twiz_shortcode_output"/> <label for="twiz_output_choice_0"'.$twiz_output_choice_0.'>'.__('Shortcode', 'the-welcomizer').'</label> ';
            $choices .= '<input type="radio" id="twiz_output_choice_1" name="twiz_output_choice" class="twiz-output-choice" value="twiz_single_output"/> <label for="twiz_output_choice_1"'.$twiz_output_choice_1.'>'.__('Unique', 'the-welcomizer').'</label> ';
            $choices .= '<input type="radio" id="twiz_output_choice_2" name="twiz_output_choice" class="twiz-output-choice"  value="twiz_multiple_output"/> <label for="twiz_output_choice_2"'.$twiz_output_choice_2.'>'.__('Multiple', 'the-welcomizer').'</label> ';
            $choices .= '<input type="radio" id="twiz_output_choice_3" name="twiz_output_choice" class="twiz-output-choice"  value="twiz_logic_output"/> <label for="twiz_output_choice_3"'.$twiz_output_choice_3.'>'.__('Custom logic', 'the-welcomizer').'</label>';
        }
        
        $choices .= '</fieldset>';
        
        // main box 
        $html = '<div class="twiz-box-menu">';
        
        // status & save button
        $html.= '<table class="twiz-table-section" cellspacing="0" cellpadding="0"><tr><td>'.__('Status', 'the-welcomizer').': </td><td><div class="twiz-checkbox twiz-float-left"><input type="checkbox" id="twiz_section_'.parent::F_STATUS.'" name="twiz_section_'.parent::F_STATUS.'" '.$twiz_section_status.'/></div><div class="twiz-text-right twiz-float-right twiz-action-box">'.__('Action', 'the-welcomizer').'<div class="twiz-green">'.$lbl_action.'</div></div><div class="twiz-float-right twiz-td-save twiz-save-box-1"><span id="twiz_menu_save_img_box_1" class="twiz-loading-gif-save"></span><a id="twiz_section_cancel_1">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save_section" id="twiz_save_section_1" class="button-primary" value="'.__('Save', 'the-welcomizer').'" /><input type="hidden" value="'.$section_id.'" id="twiz_section_id" name="twiz_section_id"/></div></td>';
 
        // action
        $html .= '</tr>';

        // visibility + blogid
        $html .= '<tr><td class="twiz-vertical-align-top">'.__('Visibility', 'the-welcomizer').':</td><td><select id="twiz_visibility">
        <option value="'.parent::VISIBILITY_EVERYONE.'"'.$twiz_visibility[parent::VISIBILITY_EVERYONE].'>'.__('Everyone', 'the-welcomizer').'</option>  
        <option value="'.parent::VISIBILITY_VISITORS.'"'.$twiz_visibility[parent::VISIBILITY_VISITORS].'>'.__('Visitors Only', 'the-welcomizer').'</option>  
        <option value="'.parent::VISIBILITY_MEMBERS.'"'.$twiz_visibility[parent::VISIBILITY_MEMBERS].'>'.__('Members Only', 'the-welcomizer').'</option>  
        <option value="'.parent::VISIBILITY_ADMINS.'"'.$twiz_visibility[parent::VISIBILITY_ADMINS].'>'.__('Admins Only', 'the-welcomizer').'</option>  
        </select>'.$this->getHTMLBlogIdList( $section_id, $action ).'</div></td></tr>';
        
        // section name
        $html .=  '<tr><td nowrap="nowrap">'.__('Section name', 'the-welcomizer').':</td><td>';

        if(in_array($section_id, $this->array_default_section)){
        
            $html .= $twiz_section_name.'<input type="text" id="twiz_section_name" name="twiz_section_name"  value="'.$twiz_section_name.'" maxlength="255" class="twiz-display-none twiz-input-focus"/></td>';
            
        }else{  
        
            $html .= '<input class="twiz-input-focus" type="text" id="twiz_section_name" name="twiz_section_name"  value="'.$twiz_section_name.'" maxlength="255"/>';
        }
 
        $html .=  '</td></tr></table><div class="twiz-clear"></div>';
 
        $tabselectedcookie = '';
        $tabselectedactivation = '';
        $tabhiddenactivation = '';
        $tabhiddencookie = '';
 
        $tabselectedcookie = (($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] != '' ) 
                          or (($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] == '') 
                          and ($sections[$section_id][parent::KEY_COOKIE_CONDITION]  == '' ))) ? ' twiz-tab-selected' : '';
            
        $tabselectedactivation = (($sections[$section_id][parent::KEY_COOKIE_CONDITION] != '' )
                               and($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1]  == '' )) ? ' twiz-tab-selected' : '';
        
        $tabhiddenactivation = (($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] != '' ) 
                            or (($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] == '') 
                            and ($sections[$section_id][parent::KEY_COOKIE_CONDITION]  == '' ))) ? 'twiz-display-none' : '';
                          
        $tabhiddencookie = (($sections[$section_id][parent::KEY_COOKIE_CONDITION]  != '' )
                         and($sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] == '' )) ? 'twiz-display-none' : '';
        
        // More link
        $html .= '<div class="twiz-spacer"></div><div class="twiz-float-left twiz-text-left"><a id="twiz_more_options" class="twiz-more-configs">'.$lbl_more_options.' &#187;</a></div>';
              
        // Clear import section switch
        $section_id = ( $section_id  ==  parent::ACTION_NEW ) ? '' : $section_id;
       
        // tab menu 
        $html .= '<div class="twiz-clear"></div><div class="twiz-section-more-options"><br>';
        
        $html .= '<div id="twiz_tab_line"></div><div class="twiz-clear"></div>
        <div id="twiz_tabmenu_cookie" class="twiz-tab twiz-corner-top'.$tabselectedcookie.'">'.__('jQuery Limit', 'the-welcomizer').'</div><div class="twiz-tab twiz-corner-top'.$tabselectedactivation.'">'.__('jQuery Activation', 'the-welcomizer').'</div>';
        
        // wrapper 
        $html .= '</div>';
        

        
        // wrapper 
        $html .= '<div class="twiz-clear"></div><div class="twiz-section-more-options">';
         
        // cookie option1
        $html .= '<div id="twiz_tab_cookie" class="'.$tabhiddencookie.'"><div id="twiz_div_cookie_option_1" class="twiz-float-left">'.__('Limit', 'the-welcomizer').': <select id="twiz_slc_cookie_option_1">
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
        <option value="all"'.$twiz_slc_cookie_with['all'].'>'.__('with', 'the-welcomizer').' '.utf8_encode('PHP & JS').'</option>
        </select></div>';
        
        // cookie name
        $html .= '<div id="twiz_div_cookie_name" class="twiz-float-left twiz-display-none">'.__('Cookie name', 'the-welcomizer').': <input class="twiz-input-focus" type="text" id="twiz_cookie_name" name="twiz_cookie_name" value="'.$twiz_cookie_name.'" maxlength="255"/> <select id="twiz_slc_cookie_scope">
        <option value="perwebsite"'.$twiz_slc_cookie_option['perwebsite'].'>'.__('per website', 'the-welcomizer').'</option>
        <option value="perdirectory"'.$twiz_slc_cookie_option['perdirectory'].'>'.__('per directory', 'the-welcomizer').'</option>
        </select></div>

    </div>';       

        // Display only if this cookie condition is met.
        $html .= '<div class="twiz-clear"></div><div id="twiz_tab_activation" class="'.$tabhiddenactivation.'"><div class="twiz-clear"></div><div id="twiz_div_cookie_condition" class="twiz-float-left">'.__('Activated when the cookie\'s condition<br>of this other section is fulfilled', 'the-welcomizer').': '.$this->getHtmlCookieConditionList( $section_id ).'</div></div>';
        
        // wrapper 
        $html .= '</div>';        
        
        $html .= '<div class="twiz-clear"></div>';
        
        $html .= $choices;
        
        // Shortcode section box
        $html .= '<div id="twiz_shortcode_output" class="twiz-block-ouput">'.__('Shortcode ID', 'the-welcomizer').': <div class="twiz-float-right twiz-green">'.__('Copy and paste this into a post, page or text widget.', 'the-welcomizer').'</div><div class="twiz-float-right"><input type="text" class="twiz-shortcode-sample twiz-input-focus twiz-blue" id="twiz_shortcode_sample" value="'.htmlentities($twiz_shortcode_sample).'"/><div class="twiz-text-right twiz-green">'.__('Or this into a theme file.', 'the-welcomizer').'</div><input type="text" class="twiz-shortcode-sample twiz-input-focus twiz-blue" id="twiz_shortcode_sample_theme" value="'.htmlentities($twiz_shortcode_sample_theme).'"/></div><div class="twiz-float-left"><input class="twiz-input-focus" type="text" id="twiz_shortcode" name="twiz_shortcode" value="'.$twiz_shortcode.'" maxlength="255"/> <b>&gt;&gt;</b></div>
        <div class="twiz-clear"></div>';
        
        // Shortcode HTML
  $html .= ( $twiz_shortcode_html == '' ) ? '<a id="twiz_more_shortcode_html" class="twiz-more-configs">'.__('HTML(optional)', 'the-welcomizer').' &#187;</a>' : __('HTML(optional)', 'the-welcomizer');
  $html .= '<div id="twiz_shortcode_section" class="'.$hide_shortcode_section.'"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-html twiz-input-focus" id="twiz_shortcode_html" name="twiz_shortcode_html" type="text">'.$twiz_shortcode_html.'</textarea>
  </div>
        </div> ';
        
        // single section box
        $html .= '<div id="twiz_single_output" class="twiz-block-ouput">'.$this->array_output[self::TYPE_UNIQUE].': <div class="twiz-float-right twiz-text-right twiz-green">'.__('Select to overwrite the section name.', 'the-welcomizer').'</div><br><div id="twiz_custom_message_1" class="twiz-red twiz-custom-message"></div>'.$this->getHtmlSingleSection($section_id).'</div>';
           
        // multiple section box
        $html .= '<div id="twiz_multiple_output" class="twiz-block-ouput">'.$this->array_output[self::TYPE_MULTIPLE].':<div class="twiz-float-right twiz-text-right twiz-green">'.__('DoubleClick to overwrite the section name.', 'the-welcomizer').'<br>'.__('Press CTRL to select multiple output choices.', 'the-welcomizer').'</div><br><div id="twiz_custom_message_2" class="twiz-red twiz-custom-message"></div>'.$this->getHtmlMultiSection($section_id, $array_sections).'</div>';

         // Custom Logic section box
        $html .= '<div id="twiz_logic_output" class="twiz-block-ouput">'.$this->array_output[self::TYPE_CUSTOM_LOGIC].': <br><div id="twiz_custom_message_3" class="twiz-red twiz-custom-message"></div><input class="twiz-input-focus" type="text" id="twiz_custom_logic" name="twiz_custom_logic" value="'.$twiz_custom_logic.'"/>'.__('Examples', 'the-welcomizer').':<br>is_page(32)||is_category(\'55\')||is_single(345)<br>!is_page(32)&&!is_category(\'55\')&&!is_single(345)<br><br><a href="http://codex.wordpress.org/Conditional_Tags#Conditional_Tags_Index" target="_blank">'.__('Conditional Tags on WordPress.org', 'the-welcomizer').'</a></div>';
             
        $twiz_stay_section_checked = ( $stay == 'true' ) ? ' checked="checked"' : '';
        
        // save button and stay.
        $html .= '<br><div class="twiz-text-right"><span id="twiz_menu_save_img_box_2" class="twiz-loading-gif-save"></span><a id="twiz_section_cancel_2">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save_section" id="twiz_save_section_2" class="button-primary" value="'.__('Save', 'the-welcomizer').'" /> <label for="twiz_stay_section">'.__('& Stay', 'the-welcomizer').'</label> <input type="checkbox" id="twiz_stay_section" name="twiz_stay_section" '.$twiz_stay_section_checked.'></div>';
        
        $html .= $jsscript_open.$jsscript.$jsscript_close;
        
        return $html;
    }

    function getHtmlListMenu(){
    
        $html = '<div class="twiz-text-right" name="twiz_listmenu" id="twiz_listmenu"><div id="twiz_ask" class="twiz-float-left twiz-text-left twiz-blue"></div><div id="twiz_far_matches" class="twiz-float-left twiz-text-left twiz-green"></div><span><a id="twiz_new" class="twiz-bold">'.__('Add New', 'the-welcomizer').'</a> '.utf8_encode('|').' <a id="twiz_create_group" class="twiz-bold">'.__('Create Group', 'the-welcomizer').'</a> '.utf8_encode('|').' <a id="twiz_findandreplace" class="twiz-bold">'.__('Find & Replace', 'the-welcomizer').'</a></span></div></div>'; // keep extra closing div.

        return $html;
    }
    
    function getHtmlListSubMenu(){
    
        $html = '<div class="twiz-row-color-1 twiz-text-right" name="twiz_listmenu" id="twiz_listsubmenu"><span><a id="twiz_empty_list" class="twiz-bold twiz-red">'.__('Empty list', 'the-welcomizer').'</a></span></div>';

        return $html;
    }
   
    function getHtmlFooterMenu(){
    
      $import_menu = '<div id="twiz_import_menu" class="twiz-footer-import-menu twiz-corner-all twiz-absolute twiz-display-none">
<div id="twiz_import_from_computer" class="qq-upload-button twiz-footer-menu-button twiz-corner-top"></div>
<div id="twiz_import_from_server" class="twiz-footer-menu-button twiz-corner-bottom">'.__('From Server', 'the-welcomizer').'</div>
</div>';
      
      $upload_menu = '<div id="twiz_footer_library_menu" class="twiz-footer-import-menu twiz-corner-all twiz-absolute twiz-display-none">
<div id="twiz_upload_javascript" class="twiz-footer-menu-button twiz-corner-top"></div>
<div id="twiz_upload_css" class="qq-upload-button twiz-footer-menu-button twiz-corner-bottom"></div>
</div>';

      $import = '<div id="twiz_import" class="twiz-footer-menu-button twiz-corner-bottom">'.__('Import', 'the-welcomizer').'</div>';
      
      $export = '<div id="twiz_export" class="twiz-footer-menu-button twiz-corner-bottom">'.__('Export', 'the-welcomizer').'</div>';
      
      $library_upload = '<div id="twiz_library_upload" class="twiz-footer-menu-button twiz-corner-bottom twiz-display-none">'.__('Upload', 'the-welcomizer').'</div>';
      
      $library = '<div id="twiz_library" class="twiz-footer-menu-button twiz-corner-bottom">'.__('Library', 'the-welcomizer').'</div>';
      
      $admin = '<div id="twiz_admin" class="twiz-footer-menu-button twiz-corner-bottom">'.__('Admin', 'the-welcomizer').'</div>';
      
      $html = '<div id="twiz_footer_menu" class="twiz-relative twiz-reset-nav">'.$import_menu.$upload_menu.$import.$export.$library_upload.$admin.$library.'</div><div id="twiz_export_url"></div>';
      
      return $html;
      
    }
    
    function getHtmlMenu( $selected_id = '' ){
    
        // retrieve stored sections
        $sections = $this->array_sections;
        $hardsections = $this->array_hardsections;
       
        $menu = '';

        $statusimg = '<div id="twiz_status_menu_'.$this->DEFAULT_SECTION_HOME.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( $this->DEFAULT_SECTION_HOME, $hardsections[$this->DEFAULT_SECTION_HOME][parent::F_STATUS], 'menu' ).'</div>';
       
        // default home section 
        $menu .=  $statusimg . '<div id="twiz_menu_'.$this->DEFAULT_SECTION_HOME.'" class="twiz-menu twiz-menu-selected twiz-display-none">'.__('Home').'</div>';
               
        // generate the section menu
        foreach( $sections as $key => $value ){
       
             if( $key != $this->DEFAULT_SECTION_HOME ){
                       
                $menu .= $this->getHtmlSectionMenu($key, $value[parent::KEY_TITLE], $value[parent::F_STATUS], $selected_id);
            }
        }

      
        // default everywhere section 
        $statusimg = '<div id="twiz_status_menu_'.$this->DEFAULT_SECTION_EVERYWHERE.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( $this->DEFAULT_SECTION_EVERYWHERE, $hardsections[$this->DEFAULT_SECTION_EVERYWHERE][parent::F_STATUS], 'menu' ).'</div>';
          
        $menu .= $statusimg. '<div id="twiz_menu_'.$this->DEFAULT_SECTION_EVERYWHERE.'" class="twiz-menu twiz-display-none">'.__('Everywhere', 'the-welcomizer').'</div>';
        
        $statusimg = '<div id="twiz_status_menu_'.$this->DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( $this->DEFAULT_SECTION_ALL_CATEGORIES, $hardsections[$this->DEFAULT_SECTION_ALL_CATEGORIES][parent::F_STATUS], 'menu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_menu_'.$this->DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-menu twiz-display-none">'.__('All Categories', 'the-welcomizer').'</div>';
        
        $statusimg = '<div id="twiz_status_menu_'.$this->DEFAULT_SECTION_ALL_PAGES.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( $this->DEFAULT_SECTION_ALL_PAGES, $hardsections[$this->DEFAULT_SECTION_ALL_PAGES][parent::F_STATUS], 'menu' ).'</div>';

        $menu .= $statusimg . '<div id="twiz_menu_'.$this->DEFAULT_SECTION_ALL_PAGES.'" class="twiz-menu twiz-display-none">'.__('All Pages', 'the-welcomizer').'</div>';
        
        $statusimg = '<div id="twiz_status_menu_'.$this->DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( $this->DEFAULT_SECTION_ALL_ARTICLES, $hardsections[$this->DEFAULT_SECTION_ALL_ARTICLES][parent::F_STATUS], 'menu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_menu_'.$this->DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-menu twiz-display-none">'.__('All Posts', 'the-welcomizer').'</div>';

        $menu .= '<div id="twiz_library_menu" class="twiz-menu twiz-display-none">'.__('Library', 'the-welcomizer').'</div>';

        $menu .= '<div id="twiz_admin_menu" class="twiz-menu twiz-display-none">'.__('Admin', 'the-welcomizer').'</div>';
        $menu .= '<div id="twiz_edit_menu" class="twiz-noborder-right twiz-icon-edit" title="'.__('Edit this section', 'the-welcomizer').'"></div>';
        $menu .= '<div id="twiz_delete_menu" class="twiz-icon-close" title="'.__('Delete this section', 'the-welcomizer').'"></div>';

        return $menu;
    }
    
    function getHtmlVerticalMenu( $selected_id = '' ){
    
        // retrieve stored sections
        $sections = $this->array_sections;
        $hardsections = $this->array_hardsections;
        $menu = '';
        $menu_open = '';
        $menu_close = '';
     
        // Button Export All 
        $export_all = '<div class="twiz-relative twiz-footer-vmenu"><div id="twiz_export_all" class="twiz-footer-menu-button twiz-relative twiz-corner-bottom twiz-float-right">'.__('Export All', 'the-welcomizer').'</div></div>' . '<div id="twiz_export_all_url"></div>';
        
        // ajax container 
        if( !in_array($_POST['twiz_action'], $this->array_action_excluded) ){
        
            $menu_open = '<div id="twiz_vertical_menu_box" class="twiz-display-none"><div id="twiz_vertical_menu" class="twiz-reset-nav twiz-corner-all">';
            $menu_close .= '</div>'.$export_all.'</div>';
        }

        $header = '<div class="twiz-menu-header twiz-corner-top"><div id="twiz_menuheader_status" class="twiz-text-left twiz-float-left">'.__('Status', 'the-welcomizer').'</div>';
        $header .='<div id="twiz_menuheader_section" class="twiz-text-left twiz-float-left">'.__('Section', 'the-welcomizer').'';
        $header .='<div id="twiz_menuheader_type" class="twiz-text-center twiz-float-right ">'.__('Type', 'the-welcomizer').'</div></div></div>';
       
        $menu .= $header;

        // All custom sections
        foreach( $sections as $key => $value ){
       
            if( $key != $this->DEFAULT_SECTION_HOME ){
            
                $menu .= $this->getHtmlSectionvMenu($key, $value[parent::KEY_TITLE], $value[parent::F_STATUS], $selected_id);
            }
        }
        
        // Default section, (hardsection)
        $statusimg = '<div id="twiz_status_vmenu_'.$this->DEFAULT_SECTION_HOME.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( $this->DEFAULT_SECTION_HOME, $hardsections[$this->DEFAULT_SECTION_HOME][parent::F_STATUS], 'vmenu' ).'</div>';
        $output_type_default = '<div class="twiz-output-label">'.$this->array_output[self::TYPE_DEFAULT].'</div>';
       
        $menu .=  $statusimg . '<div id="twiz_vmenu_'.$this->DEFAULT_SECTION_HOME.'" class="twiz-menu">'.__('Home'). $output_type_default .'</div>';
        
        $statusimg = '<div id="twiz_status_vmenu_'.$this->DEFAULT_SECTION_EVERYWHERE.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( $this->DEFAULT_SECTION_EVERYWHERE, $hardsections[$this->DEFAULT_SECTION_EVERYWHERE][parent::F_STATUS], 'vmenu' ).'</div>';
          
        $menu .= $statusimg. '<div id="twiz_vmenu_'.$this->DEFAULT_SECTION_EVERYWHERE.'" class="twiz-menu">'.__('Everywhere', 'the-welcomizer').$output_type_default.'</div>';
        
        $statusimg = '<div id="twiz_status_vmenu_'.$this->DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( $this->DEFAULT_SECTION_ALL_CATEGORIES, $hardsections[$this->DEFAULT_SECTION_ALL_CATEGORIES][parent::F_STATUS], 'vmenu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_vmenu_'.$this->DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-menu">'.__('All Categories', 'the-welcomizer').$output_type_default.'</div>';
        
        $statusimg = '<div id="twiz_status_vmenu_'.$this->DEFAULT_SECTION_ALL_PAGES.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( $this->DEFAULT_SECTION_ALL_PAGES, $hardsections[$this->DEFAULT_SECTION_ALL_PAGES][parent::F_STATUS], 'vmenu' ).'</div>';

        $menu .= $statusimg . '<div id="twiz_vmenu_'.$this->DEFAULT_SECTION_ALL_PAGES.'" class="twiz-menu">'.__('All Pages', 'the-welcomizer'). $output_type_default .'</div>';
        
        $statusimg = '<div id="twiz_status_vmenu_'.$this->DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( $this->DEFAULT_SECTION_ALL_ARTICLES, $hardsections[$this->DEFAULT_SECTION_ALL_ARTICLES][parent::F_STATUS], 'vmenu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_vmenu_'.$this->DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-menu twiz-corner-bottom">'.__('All Posts', 'the-welcomizer'). $output_type_default.'</div>';
        
        return $menu_open . $menu . $menu_close ;
    }
    
    private function getHtmlSectionMenu( $section_id = '', $section_name = '', $status = parent::STATUS_ACTIVE, $selected_id = ''){

        if( $section_id == '' ){return '';}
        if( $section_name == '' ){return '';}
        
        $section_title = '';
        $selected = ($selected_id == $section_id ) ? ' twiz-menu-selected' : '';
       
        $statusimg = '<div id="twiz_status_menu_'.$section_id.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( $section_id, $status, 'menu' ).'</div>';
       
        if( strlen($section_name) > self::MAX_LENGHT_NAME_MENU + 2 )
        {
            $section_title = ' title="'.$section_name.'"';
            $section_name = mb_substr( $section_name, 0, self::MAX_LENGHT_NAME_MENU,'UTF-8').'...';
        }
        $html = $statusimg.'<div id="twiz_menu_'.$section_id.'"'.$section_title.' class="twiz-menu twiz-display-none'.$selected.'">'.$section_name.'</div>';
            
       return $html;
    }
    
    private function getHtmlTypeLabel( $section_id = '' ){
    
        if( $section_id == '' ){return '';}
       
        list( $type, $id ) = preg_split('/_/', $section_id);
        
        switch($type){
         
            case 'sc': // is shortcode
            
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
    
    private function getOutputChoiceValue( $section_id = '' ){

        if( $section_id == '' ){return 'twiz_shortcode_output';}
       
        list( $type, $id ) = preg_split('/_/', $section_id);
        
        switch( $type ){
         
            case 'sc': // is shortcode
            
                return 'twiz_shortcode_output';
            
                break;
                
            case 'ms': // is custom multi-sections
            
                return 'twiz_multiple_output';
            
                break;
                
            case 'cl': // is custom logic
            
                return 'twiz_logic_output';
            
                break;
          //  case 'p': // is page
          //  case 'a': // is post
          //  case 'c': // is category 
            default: // convert to multiple
            
                return 'twiz_multiple_output';
                
            break;
        }
    }
    
    private function getHtmlSectionvMenu( $section_id = '', $section_name = '', $status = parent::STATUS_ACTIVE, $selected_id = '' ){
    
        if( $section_id == '' ){return '';}
        if( $section_name == '' ){return '';}
       
        $section_title = '';
        $selected = ($selected_id == $section_id ) ? ' twiz-menu-selected' : '';
       
        $statusimg = '<div id="twiz_status_vmenu_'.$section_id.'" class="twiz-status-menu twiz-display-block">'.$this->getHtmlImgStatus( $section_id, $status, 'vmenu' ).'</div>';
        
        if( strlen($section_name) > self::MAX_LENGHT_NAME_VMENU + 2 )
        {
            $section_title = ' title="'.$section_name.'"';
            $section_name = mb_substr( $section_name, 0, self::MAX_LENGHT_NAME_VMENU,'UTF-8').'...';
        }
       
        $html = $statusimg.'<div id="twiz_vmenu_'.$section_id.'"'.$section_title.' class="twiz-menu twiz-display-block'.$selected.'">'.$section_name.$this->getHtmlTypeLabel($section_id).'</div>';
            
        return $html;
    }
    
    function getSectionName( $key = ''){
    
        if( $key  == '' ){return '';}
        
        if( $this->matchDefaultSection( $key ) ){
       
            if(!isset( $this->array_sections[$key])){
            
                $name = strtr($key, $this->array_hardsections_translation);
                
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
        
            case 'sc': // is shortcode
            
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
                
                $name = str_replace(' - ('. __('non-existent!', 'the-welcomizer'). ')', '', $name); 
                
                // non-existent!
                if ( ( $wpname == "" ) and ( $name != 'Uncategorized' ) ){
                
                    $name = $name. ' - ('. __('non-existent!', 'the-welcomizer'). ')' ;
                }
                break;
                
            case 'p': // is page
            
                $page = get_page($id);
                
                if(is_object($page)){
                
                    $wpname = $page->post_title;
                }
                
                $name = $this->array_sections[$key][parent::KEY_TITLE];
                
                $name = str_replace(' - ('. __('non-existent!', 'the-welcomizer'). ')', '', $name); 
                
                // non-existent!
                if ( $wpname == "" ){
                
                    $name = $name. ' - ('. __('non-existent!', 'the-welcomizer'). ')' ;
                }
                break;
                
            case 'a': // is post
                   
                $post = get_post( $id );
                
                if(is_object($post)){
                
                    $wpname = mysql2date('Y-m-d', $post->post_date).' : '.$post->post_title;
                }
                
                $name = $this->array_sections[$key][parent::KEY_TITLE];
                
                $name = str_replace(' - ('. __('non-existent!', 'the-welcomizer'). ')', '', $name); 
                
                // non-existent!
                if ( $wpname == "" ){
                    $name = $name. ' - ('. __('non-existent!', 'the-welcomizer'). ')' ;
                }
                break;
        }
        
        return $name;
    }
    
    private function clearMultiSectionNotIsset(){
    
        $error = false;        
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $this->array_multi_sections = get_option('twiz_multi_sections');
            
        }else{

            $this->array_multi_sections = get_site_option('twiz_multi_sections');
        }         
                
        if( !is_array($this->array_multi_sections) ){
        
            $this->array_multi_sections = array();
            
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                $code = update_option('twiz_multi_sections', $this->array_multi_sections);
                
            }else{

                $code = update_site_option('twiz_multi_sections', $this->array_multi_sections);
            }             
        }
        
        foreach( $this->array_multi_sections as $key => $value){
        
            if( !isset($this->array_sections[$key][parent::F_STATUS]) ){
                
                unset($this->array_multi_sections[$key]);
                $error = true;
            }
            // clean obsolete data since v2.8
            if( !is_array($this->array_multi_sections[$key]) ){
                
                unset($this->array_multi_sections[$key]);
                $error = true;
            }            
        }
        
        if( $error == true ){
        
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                $code = update_option('twiz_multi_sections', $this->array_multi_sections);
                $this->array_multi_sections = get_option('twiz_multi_sections');
            
            }else{

                $code = update_site_option('twiz_multi_sections', $this->array_multi_sections);
                $this->array_multi_sections = get_site_option('twiz_multi_sections');
            }         
        }
        
        return true;
    }
    
    private function loadSections(){
    
        $key_title = array();
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
            $this->array_sections = get_option('twiz_sections');
            $this->array_hardsections = get_option('twiz_hardsections');
            
        }else{
        
            $this->array_sections = get_site_option('twiz_sections');
            $this->array_hardsections = get_site_option('twiz_hardsections');
        }         
        
        $sections = $this->array_sections;

        // clear bug section shortcode to single output.
        $cleared = $this->clearMultiSectionNotIsset();
        
        if( !is_array($this->array_sections) ){
        
            $this->array_sections = array();
            
        }else{
        
            // Reformat array if necessary
            foreach( $sections as $key => $value ) {

                if( !is_array($sections[$key]) ){ // reformat array for the first time
                    
                    if( !isset($this->array_sections[$value]) ) $this->array_sections[$value] = '';
                    
                    $section = array(parent::F_STATUS   => parent::STATUS_ACTIVE
                                    ,parent::KEY_VISIBILITY => parent::VISIBILITY_EVERYONE                              
                                    ,parent::F_BLOG_ID => array($this->BLOG_ID)
                                    ,parent::KEY_TITLE  => $this->getSectionName($key)
                                    ,parent::KEY_SHORTCODE => ''                                    
                                    ,parent::KEY_SHORTCODE_HTML => ''                                    
                                    ,parent::KEY_CUSTOM_LOGIC => ''                                    
                                    ,parent::KEY_COOKIE_CONDITION  => ''
                                    ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => '' 
                                                                ,parent::KEY_COOKIE_OPTION_1 => ''
                                                                ,parent::KEY_COOKIE_OPTION_2 => ''
                                                                ,parent::KEY_COOKIE_WITH     => '' 
                                                                ,parent::KEY_COOKIE_SCOPE    => '' 
                                                                )   
                                    ); // new array
                   
                    $this->array_sections[$value] = $section; // new value
                    
                    unset($this->array_sections[$key]); // delete old format
                    
                }else{ 
            
                    if( !isset($this->array_sections[$key][parent::KEY_VISIBILITY]) ) $this->array_sections[$key][parent::KEY_VISIBILITY] = '';
                    if( !isset($this->array_sections[$key][parent::F_BLOG_ID]) ) $this->array_sections[$key][parent::F_BLOG_ID] = '';
                    if( !isset($this->array_sections[$key][parent::KEY_SHORTCODE]) ) $this->array_sections[$key][parent::KEY_SHORTCODE] = '';
                    if( !isset($this->array_sections[$key][parent::KEY_SHORTCODE_HTML]) ) $this->array_sections[$key][parent::KEY_SHORTCODE_HTML] = '';
                    if( !isset($this->array_sections[$key][parent::KEY_CUSTOM_LOGIC]) ) $this->array_sections[$key][parent::KEY_CUSTOM_LOGIC] = '';
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE_CONDITION]) ) $this->array_sections[$key][parent::KEY_COOKIE_CONDITION] = '';
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE]) ) $this->array_sections[$key][parent::KEY_COOKIE] = '';
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME]) ) $this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME] = '';
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1]) ) $this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] = '';
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2]) ) $this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2] = '';
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH]) ) $this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH] = '';
                    if( !isset($this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_SCOPE]) ) $this->array_sections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_SCOPE] = '';
                 
                    // set default value 
                    $this->array_sections[$key][parent::KEY_VISIBILITY] = ($this->array_sections[$key][parent::KEY_VISIBILITY] == '') ? parent::VISIBILITY_EVERYONE : $this->array_sections[$key][parent::KEY_VISIBILITY];
                    $this->array_sections[$key][parent::F_BLOG_ID] = (!is_array($this->array_sections[$key][parent::F_BLOG_ID])) ? array($this->BLOG_ID): $this->array_sections[$key][parent::F_BLOG_ID];
                
                    // update the shortcode from $this->array_multi_section
                    if( $this->array_sections[$key][parent::KEY_SHORTCODE] == '' ){
                        
                        if( !isset( $this->array_multi_sections[$key] ) ){}else{
                        
                            list( $type, $id ) = preg_split('/_/', $key);
                            
                            if( $type == 'sc' ){
                            
                                $this->array_sections[$key][parent::KEY_SHORTCODE] = $this->array_multi_sections[$key];
                            }
                        }                       
                    }
                    
                    // update the custom logic from $this->array_multi_section
                    if( $this->array_sections[$key][parent::KEY_CUSTOM_LOGIC] == '' ){
                        
                        if( !isset( $this->array_multi_sections[$key] ) ){}else{
                        
                            list( $type, $id ) = preg_split('/_/', $key);
                            
                            if( $type == 'cl' ){
                            
                                $this->array_sections[$key][parent::KEY_CUSTOM_LOGIC] = $this->array_multi_sections[$key];
                            }
                        }                       
                    }   
                    
                    if( !isset($this->array_sections[$key][parent::F_SECTION_ID]) ){ // Always update the title, this array has been cleaned already
                        
                        if( !isset($this->array_sections[$key][parent::KEY_TITLE]) ) $this->array_sections[$key][parent::KEY_TITLE] = ''; // Register the key

                         
                        $this->array_sections[$key][parent::KEY_TITLE] = $this->getSectionName($key); // Fresh title
                      
                    }else{ // it has already been formated once v1.3.8.6(broken), we need to format it again for those users.
                                             
                        $newkey = $this->array_sections[$key][parent::F_SECTION_ID]; // New key from array
                        $samestatus = $this->array_sections[$key][parent::F_STATUS]; // Same status
                        
                        if( !isset($this->array_sections[$newkey]) ) $this->array_sections[$newkey] = '';
                        
                        $section = array( parent::F_STATUS   => $samestatus
                                         ,parent::KEY_VISIBILITY => parent::VISIBILITY_EVERYONE
                                         ,parent::F_BLOG_ID => array($this->BLOG_ID)
                                         ,parent::KEY_TITLE  => $this->getSectionName($newkey)
                                         ,parent::KEY_SHORTCODE => ''
                                         ,parent::KEY_SHORTCODE_HTML => ''
                                         ,parent::KEY_CUSTOM_LOGIC => ''
                                         ,parent::KEY_COOKIE_CONDITION  => ''
                                         ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => '' 
                                                                     ,parent::KEY_COOKIE_OPTION_1 => ''
                                                                     ,parent::KEY_COOKIE_OPTION_2 => ''
                                                                     ,parent::KEY_COOKIE_WITH     => '' 
                                                                     ,parent::KEY_COOKIE_SCOPE    => '' 
                                                                     )                                         
                                         ); // new array
                        
                        $this->array_sections[$newkey] = $section; // new value
                        
                        unset($this->array_sections[$key]); // delete old format
                        
                    }
                }
            }
        }
        
        // update section option
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $code = update_option('twiz_sections', $this->array_sections);
            
        }else{
        
            $code = update_site_option('twiz_sections', $this->array_sections);
        }             
   
        // Sort sections by title for the menu
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

                $section = array(parent::F_STATUS   => parent::STATUS_INACTIVE                                     
                                ,parent::KEY_VISIBILITY => parent::VISIBILITY_EVERYONE                                    
                                ,parent::F_BLOG_ID => array($this->BLOG_ID)
                                ,parent::KEY_TITLE  => $this->getSectionName($key)
                                ,parent::KEY_SHORTCODE => ''                                    
                                ,parent::KEY_SHORTCODE_HTML => ''                                    
                                ,parent::KEY_CUSTOM_LOGIC => ''                                    
                                ,parent::KEY_COOKIE_CONDITION  => ''
                                ,parent::KEY_COOKIE => array(parent::KEY_COOKIE_NAME     => '' 
                                                            ,parent::KEY_COOKIE_OPTION_1 => ''
                                                            ,parent::KEY_COOKIE_OPTION_2 => ''
                                                            ,parent::KEY_COOKIE_WITH     => '' 
                                                            ,parent::KEY_COOKIE_SCOPE    => '' 
                                                            )     
                                );                

                $this->array_hardsections[$key] = $section; // Activated by default
                
            }else{
            
                if( !isset($this->array_hardsections[$key][parent::KEY_VISIBILITY]) ) $this->array_hardsections[$key][parent::KEY_VISIBILITY] = '';
                if( !isset($this->array_hardsections[$key][parent::F_BLOG_ID]) ) $this->array_hardsections[$key][parent::F_BLOG_ID] = '';
                if( !isset($this->array_hardsections[$key][parent::KEY_SHORTCODE]) ) $this->array_hardsections[$key][parent::KEY_SHORTCODE] = '';
                if( !isset($this->array_hardsections[$key][parent::KEY_SHORTCODE_HTML]) ) $this->array_hardsections[$key][parent::KEY_SHORTCODE_HTML] = '';
                if( !isset($this->array_hardsections[$key][parent::KEY_CUSTOM_LOGIC]) ) $this->array_hardsections[$key][parent::KEY_CUSTOM_LOGIC] = '';
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE_CONDITION]) ) $this->array_hardsections[$key][parent::KEY_COOKIE_CONDITION] = '';
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE]) ) $this->array_hardsections[$key][parent::KEY_COOKIE] = '';
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME]) ) $this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME] = '';
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1]) ) $this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1] = '';
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2]) ) $this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2] = '';
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH]) ) $this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH] = '';
                if( !isset($this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_SCOPE]) ) $this->array_hardsections[$key][parent::KEY_COOKIE][parent::KEY_COOKIE_SCOPE] = '';
                
                // set default value 
                $this->array_hardsections[$key][parent::KEY_VISIBILITY] = ($this->array_hardsections[$key][parent::KEY_VISIBILITY] == '') ? parent::VISIBILITY_EVERYONE : $this->array_hardsections[$key][parent::KEY_VISIBILITY];
                $this->array_hardsections[$key][parent::F_BLOG_ID] = (!is_array($this->array_hardsections[$key][parent::F_BLOG_ID])) ? array($this->BLOG_ID) : $this->array_hardsections[$key][parent::F_BLOG_ID];
                
            }
        }
        
        // update hardsection option
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $code = update_option('twiz_hardsections', $this->array_hardsections);
            
        }else{

            $code = update_site_option('twiz_hardsections', $this->array_hardsections);
        }         
    }  
    
    function switchMenuStatus( $id = '' ){ 
    
       $htmlstatus = '';
       $newstatus = '';
       $sections = $this->array_sections;
       $hardsections = $this->array_hardsections;
       
       $search = array('vmenu_', 'menu_');
       $cleanid = str_replace($search,'',$id);

        foreach( $sections as $key => $value ){
   
            if( $key == $cleanid ){
                
                $newstatus = ($value[parent::F_STATUS] == parent::STATUS_INACTIVE) ? parent::STATUS_ACTIVE : parent::STATUS_INACTIVE; // swicth the status value
                $ddcleanid = str_replace('_'.$cleanid,'',$id);
            
                $htmlstatus = ($newstatus == parent::STATUS_ACTIVE) ? $this->getHtmlImgStatus($cleanid, parent::STATUS_ACTIVE, $ddcleanid ) : $this->getHtmlImgStatus($cleanid , parent::STATUS_INACTIVE, $ddcleanid );
                
                $this->array_sections[$key][parent::F_STATUS] = $newstatus;
                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                    $code = update_option('twiz_sections', $this->array_sections);
                    
                }else{

                    $code = update_site_option('twiz_sections', $this->array_sections);
            
                } 
            }
        }

        foreach( $hardsections as $key => $value ){
   
            if( $key == $cleanid ){
                
                $newstatus = ($value[parent::F_STATUS] == parent::STATUS_INACTIVE) ? parent::STATUS_ACTIVE : parent::STATUS_INACTIVE; // swicth the status value
                $ddcleanid = str_replace('_'.$cleanid,'',$id);
            
                $htmlstatus = ($newstatus == parent::STATUS_ACTIVE) ? $this->getHtmlImgStatus($cleanid, parent::STATUS_ACTIVE, $ddcleanid ) : $this->getHtmlImgStatus($cleanid , parent::STATUS_INACTIVE, $ddcleanid );
                                  
                $this->array_hardsections[$key][parent::F_STATUS] = $newstatus;
                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                    $code = update_option('twiz_hardsections', $this->array_hardsections);
                    
                }else{

                    $code = update_site_option('twiz_hardsections', $this->array_hardsections);
                } 
            }
        }
        
        return $htmlstatus;
    }
    
    private function getHTMLBlogIdList( $section_id , $action = '' ){
    
        $select = '';
        
        if( ( is_multisite() ) and ( is_super_admin() ) and ( $this->override_network_settings != '1' ) ){
        
           if( $action == self::ACTION_NEW ){
            
               $section_blogid = array($this->BLOG_ID);
               
           }else{
            
               $section_blogid = $this->getSectionBlogId( $section_id, $action );
           }
           
           $blog_array = $this->getAllBlogIds();
           
           $select = '<select name="twiz_'.parent::F_BLOG_ID.'" id="twiz_'.parent::F_BLOG_ID.'" multiple="multiple" size="5">';
            
           $selected = ( in_array(parent::ALL_SITES, $section_blogid) ) ? ' selected="selected"' : '';
           $select .= '<option value="'.parent::ALL_SITES.'"'.$selected.'>'.__('All Sites', 'the-welcomizer').'</option>';
           
  
           foreach( $blog_array as $key => $value ){
           
                if( $action == parent::ACTION_NEW ){

                    $selected = ( $this->BLOG_ID == $value['id'] )? ' selected="selected"' : '';
                    $action = '';
                    
                }else{
                
                    $selected = ( in_array($value['id'], $section_blogid) )? ' selected="selected"' : '';
                }
                
                $select .= '<option value="'.$value['id'].'"'.$selected.'>'.$value['id'].' - '.$value['path'].'</option>';
           }
           
           $select .= '</select>' ;
        }
        
        return $select;
    }
    
    private function validateSectionId( $sectionid = '' ){
       
        $sectionid = esc_attr(trim( $sectionid ));
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $sections = get_option('twiz_sections');
            $hardsections = get_option('twiz_hardsections');
            
        }else{
        
            $sections = get_site_option('twiz_sections');
            $hardsections = get_site_option('twiz_hardsections');        
        }
        
        foreach ( $hardsections as $key => $value ){
           
           if( $key == $sectionid ){
           
                return true;
           }
        }            

        foreach ( $sections as $key => $value ){
                
           if( $key == $sectionid ){
           
                return true;
           }
        }
        
        return false;
    }    
}
?>