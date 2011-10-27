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

class TwizMenu extends Twiz{
        
    /* variable declaration */
    private $array_sections;
    private $array_hardsections; 
                                       
    /* key menu constants */
    const KEY_STATUS = 'status';     
              
    function __construct(){
    
        parent::__construct();
                 
        $this->loadSections();
        
    }

    function addSectionMenu( $section_id = '' ){
     
        if( $section_id == '' ){return '';}
        
        $section_name = $this->getSectionName($section_id);
        
        $section = array(parent::F_STATUS => parent::STATUS_ACTIVE 
                        ,parent::KEY_TITLE => $section_name
                        );
             
        if( !isset($this->array_sections[$section_id]) ) $this->array_sections[$section_id] = '';              
        $this->array_sections[$section_id] = $section;
        
        $code = update_option('twiz_sections', $this->array_sections);
        
        $html = $this->getHtmlSectionMenu($section_id, $section_name, parent::STATUS_ACTIVE);
        
        return $html;
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
            
           $code = update_option('twiz_sections', $this->array_sections);
        }

        return true;
    }     
    
    function getHtmlAddSection(){
    
        global $wpdb;
 
        $select_cat = '';
        $select_page = '';
        $select_post = '';
        $separator_page = '';
        $separator_post = '';
        
        $sections = $this->array_sections;
  
        $addsection = '';
        
        $select = '<select name="twiz_slc_sections" id="twiz_slc_sections">';
        
        $select .= '<option value="" selected="selected">'.__('Choose', 'the-welcomizer').'</option>';
        
        /* get categories */
        $categories = get_categories('sort_order=asc'); 
 
        foreach( $categories as $value ){
        
            if( !array_key_exists('c_'.$value->cat_ID, $sections) ){
            
                $select_cat .= '<option value="c_'.$value->cat_ID.'">'.$value->cat_name.'</option>';
            }
        }
        
        /* get pages */
        $pages = get_pages('sort_order=asc'); 
        
        foreach( $pages as $value ){
        
            if( !array_key_exists('p_'.$value->ID, $sections )){
            
                $separator_page = '<option value="+++ +++ +++">+++ +++ +++</option>';
               
                $select_page .= '<option value="p_'.$value->ID.'">'.$value->post_title.'</option>';
            }
        }
        
        /* get last 125 posts */
        $posts = get_posts('sort_order=asc&numberposts=125'); 
        
        foreach( $posts as $value ){
        
            if( !array_key_exists('a_'.$value->ID, $sections )){
            
                $separator_post = '<option value="+++ +++ +++">+++ +++ +++</option>';
               
                $select_post .= '<option value="a_'.$value->ID.'">'. mysql2date('Y-m-d', $value->post_date). ' : '.$value->post_title.'</option>';
            }
        }
        
        /* close select */
        $addsection .=  $select.$select_cat.$separator_page.$select_page.$separator_post.$select_post.'</select>';

        $addsection .= '<input type="button" name="twiz_save_section" id="twiz_save_section" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /> <a name="twiz_cancel_section" id="twiz_cancel_section">'.__('Cancel', 'the-welcomizer').'</a>';
        
        return $addsection;
    }

    protected function getHtmlMenu(){
    
        /* retrieve stored sections */
        $sections = $this->array_sections;
        $hardsections = $this->array_hardsections;
       
        $menu = '<div id="twiz_menu">';
       
        $statusimg = '<div id="twiz_status_menu_home" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_HOME, parent::STATUS_ACTIVE, 'menu' ).'</div>';
       
        /* default home section */
        $menu .=  $statusimg . '<div id="twiz_menu_home" class="twiz-menu twiz-menu-selected twiz-display-none">'.__('Home').'</div>';
               
        /* generate the section menu */
        foreach( $sections as $key => $value ){
       
             if( $key != parent::DEFAULT_SECTION_HOME ){
                       
                $menu .= $this->getHtmlSectionMenu($key, $value[parent::KEY_TITLE], $value[parent::F_STATUS]);
            }
        }

      
        /* default everywhere section */
        $statusimg = '<div id="twiz_status_menu_'.parent::DEFAULT_SECTION_EVERYWHERE.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_EVERYWHERE, $hardsections[parent::DEFAULT_SECTION_EVERYWHERE][parent::F_STATUS], 'menu' ).'</div>';
          
        $menu .= $statusimg. '<div id="twiz_menu_'.parent::DEFAULT_SECTION_EVERYWHERE.'" class="twiz-menu twiz-display-none">'.__('Everywhere', 'the-welcomizer').'</div>';
        
        $statusimg = '<div id="twiz_status_menu_'.parent::DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_CATEGORIES, $hardsections[parent::DEFAULT_SECTION_ALL_CATEGORIES][parent::F_STATUS], 'menu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_menu_'.parent::DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-menu twiz-display-none">'.__('All', 'the-welcomizer').' '.__('Categories').'</div>';
        
        $statusimg = '<div id="twiz_status_menu_'.parent::DEFAULT_SECTION_ALL_PAGES.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_PAGES, $hardsections[parent::DEFAULT_SECTION_ALL_PAGES][parent::F_STATUS], 'menu' ).'</div>';

        $menu .= $statusimg . '<div id="twiz_menu_'.parent::DEFAULT_SECTION_ALL_PAGES.'" class="twiz-menu twiz-display-none">'.__('All', 'the-welcomizer').' '.__('Pages').'</div>';
        
        $statusimg = '<div id="twiz_status_menu_'.parent::DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_ARTICLES, $hardsections[parent::DEFAULT_SECTION_ALL_ARTICLES][parent::F_STATUS], 'menu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_menu_'.parent::DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-menu twiz-display-none">'.__('All', 'the-welcomizer').' '.__('Posts').'</div>';

        $menu .=  '<div id="twiz_library_menu" class="twiz-menu twiz-display-none">'.__('Library', 'the-welcomizer').'</div>';

        $menu .=  '<div id="twiz_admin_menu" class="twiz-menu twiz-display-none">'.__('Admin', 'the-welcomizer').'</div>';
        
        $menu .= '<div id="twiz_delete_menu">x</div>';
        $menu .= '<div id="twiz_add_menu">+</div>';

        $menu .= '<div id="twiz_more_menu">&gt;&gt;&gt;</div>';
        $menu .= '<div id="twiz_add_sections">'. $this->getHtmlAddSection().'</div>'; // private
        
        $menu .= '</div>';
        $menu .= '<div class="twiz-clear"></div>';
        
        return $menu;
    }
    
    function getHtmlVerticalMenu(){
    
        /* retrieve stored sections */
        $sections = $this->array_sections;
        $hardsections = $this->array_hardsections;
        
        $menu = '<div id="twiz_menu">';
       
       $statusimg = '<div id="twiz_status_vmenu_home" class="twiz-status-menu">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_HOME, parent::STATUS_ACTIVE, 'vmenu' ).'</div>';
       
        /* default home section */
        $menu .=  $statusimg . '<div id="twiz_vmenu_home" class="twiz-menu">'.__('Home').'</div>';
       
        /* generate the section menu */
        foreach( $sections as $key => $value ){
       
            if( $key != parent::DEFAULT_SECTION_HOME ){
            
                $menu .= $this->getHtmlSectionvMenu($key, $value[parent::KEY_TITLE], $value[parent::F_STATUS]);
            }
        }

        /* default everywhere section */
        $statusimg = '<div id="twiz_status_vmenu_'.parent::DEFAULT_SECTION_EVERYWHERE.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_EVERYWHERE, $hardsections[parent::DEFAULT_SECTION_EVERYWHERE][parent::F_STATUS], 'vmenu' ).'</div>';
          
        $menu .= $statusimg. '<div id="twiz_vmenu_'.parent::DEFAULT_SECTION_EVERYWHERE.'" class="twiz-menu">'.__('Everywhere', 'the-welcomizer').'</div>';
        
        $statusimg = '<div id="twiz_status_vmenu_'.parent::DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_CATEGORIES, $hardsections[parent::DEFAULT_SECTION_ALL_CATEGORIES][parent::F_STATUS], 'vmenu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_vmenu_'.parent::DEFAULT_SECTION_ALL_CATEGORIES.'" class="twiz-menu">'.__('All', 'the-welcomizer').' '.__('Categories').'</div>';
        
        $statusimg = '<div id="twiz_status_vmenu_'.parent::DEFAULT_SECTION_ALL_PAGES.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_PAGES, $hardsections[parent::DEFAULT_SECTION_ALL_PAGES][parent::F_STATUS], 'vmenu' ).'</div>';

        $menu .= $statusimg . '<div id="twiz_vmenu_'.parent::DEFAULT_SECTION_ALL_PAGES.'" class="twiz-menu">'.__('All', 'the-welcomizer').' '.__('Pages').'</div>';
        
        $statusimg = '<div id="twiz_status_vmenu_'.parent::DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-status-menu">'.$this->getHtmlImgStatus( parent::DEFAULT_SECTION_ALL_ARTICLES, $hardsections[parent::DEFAULT_SECTION_ALL_ARTICLES][parent::F_STATUS], 'vmenu' ).'</div>';
                
        $menu .= $statusimg . '<div id="twiz_vmenu_'.parent::DEFAULT_SECTION_ALL_ARTICLES.'" class="twiz-menu">'.__('All', 'the-welcomizer').' '.__('Posts').'</div>';
       
        $menu .= '</div>';
        
        return $menu;
    }
    
    private function getHtmlSectionMenu( $section_id = '', $section_name = '', $status = parent::STATUS_ACTIVE ){
    
       if( $section_id == '' ){return '';}
       if( $section_name == '' ){return '';}
       
       $statusimg = '<div id="twiz_status_menu_'.$section_id.'" class="twiz-status-menu twiz-display-none">'.$this->getHtmlImgStatus( $section_id, $status, 'menu' ).'</div>';
       
       $html = $statusimg.'<div id="twiz_menu_'.$section_id.'" class="twiz-menu twiz-display-none">'.$section_name.'</div>';
            
       return $html;
    }

    private function getHtmlSectionvMenu( $section_id = '', $section_name = '', $status = parent::STATUS_ACTIVE ){
    
       if( $section_id == '' ){return '';}
       if( $section_name == '' ){return '';}
       
       $statusimg = '<div id="twiz_status_vmenu_'.$section_id.'" class="twiz-status-menu twiz-display-block">'.$this->getHtmlImgStatus( $section_id, $status, 'vmenu' ).'</div>';
       
       $html = $statusimg.'<div id="twiz_vmenu_'.$section_id.'" class="twiz-menu twiz-display-block">'.$section_name.'</div>';
            
       return $html;
    }
    
    function getSectionName( $key = ''){
    
        if(!preg_match("/_/i", $key)){
        
            return $key;
        }
        
        list( $type, $id ) = split('_', $key);
                
        $name = '';
        $newid = '';
        
        switch($type){
        
            case 'c': // is category
            
                $name = get_cat_name($id);
                
                /* User deleted category */
                if ($name==""){
                
                    $name = $this->array_sections[$key][parent::KEY_TITLE];
                    $name = '<span class="twiz-status-red">'.$name.'</span>';
                }
                break;
                
            case 'p': // is page
            
                $page = get_page($id);
                
                if(is_object($page)){
                
                    $name = $page->post_title;
                }
                
                /* User deleted page */
                if ( $name == "" ){

                    $name = $this->array_sections[$key][parent::KEY_TITLE];
                    $name = '<span class="twiz-status-red">'.$name.'</span>';
                }
                break;
                
            case 'a': // is post
                   
                $post = get_post( $id );
                
                if(is_object($post)){
                
                    $name = mysql2date('Y-m-d', $post->post_date).' : '.$post->post_title;
                }
                
                /* User deleted post */
                if ( $name == "" ){

                    $name = $this->array_sections[$key][parent::KEY_TITLE];
                    $name = '<span class="twiz-status-red">'.$name.'</span>';
                }
                break;                
        }
        
        return $name;
    }
    
    private function loadSections(){
    
        $this->array_sections = get_option('twiz_sections');
        $this->array_hardsections = get_option('twiz_hardsections');
        $sections = $this->array_sections;
        
        if( !is_array($this->array_sections) ){
        
            $this->array_sections = array();
        }else{
        
            // Reformat array if necessary
            foreach( $sections as $key => $value ) {

                if( !is_array($sections[$key]) ){ // reformat array for the first time
                    
                    if( !isset($this->array_sections[$value]) ) $this->array_sections[$value] = ''; 
                    
                    $section = array(parent::F_STATUS  => parent::STATUS_ACTIVE
                                    ,parent::KEY_TITLE => $this->getSectionName($key)
                                    ); // new array
                   
                    $this->array_sections[$value] = $section; // new value
                    
                    unset($this->array_sections[$key]); // delete old format
                    
                }else{ 
                
                    if( !isset($this->array_sections[$key][parent::F_SECTION_ID]) ){ // Always update the title, this array has been cleaned already
                        
                        if( !isset($this->array_sections[$key][parent::KEY_TITLE]) ) $this->array_sections[$key][parent::KEY_TITLE] = ''; // Register the key
                        
                        $this->array_sections[$key][parent::KEY_TITLE] = $this->getSectionName($key); // Fresh title
                      
                    }else{ // it has already been formated once v1.3.8.6(broken), we need to format it again for those users.
                                             
                        $newkey = $this->array_sections[$key][parent::F_SECTION_ID]; // New key from array
                        $samestatus = $this->array_sections[$key][parent::F_STATUS]; // Same status
                        
                        if( !isset($this->array_sections[$newkey]) ) $this->array_sections[$newkey] = ''; 
                        
                        $section = array( parent::F_STATUS => $samestatus
                                         ,parent::KEY_TITLE => $this->getSectionName($newkey)
                                         ); // new array
                        
                        $this->array_sections[$newkey] = $section; // new value
                        
                        unset($this->array_sections[$key]); // delete old format
                    }
                }
            }
            
            $code = update_option('twiz_sections', $this->array_sections); 
        }
                    
        // Register hard menu
        foreach( $this->array_default_section as $key ) {

            if( !isset($this->array_hardsections[$key][parent::F_SECTION_ID]) ){}else{
                unset($this->array_hardsections[$key]);  // delete old format
            }
            
            if( !isset($this->array_hardsections[$key]) ) $this->array_hardsections[$key] = '';
            
            if( $this->array_hardsections[$key] == '' ) {
            
                $section = array( parent::F_STATUS => parent::STATUS_ACTIVE);
                              
                $this->array_hardsections[$key] = $section; // Activated by default
            }
        }
        
        $code = update_option('twiz_hardsections', $this->array_hardsections); 
        
        ksort($this->array_sections);
    }  
    
    function switchMenuStatus($id){ 
    
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