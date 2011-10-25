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
    
    /* array hard menu */
    private $array_hardmenu = array(parent::DEFAULT_SECTION_HOME
                                ,parent::DEFAULT_SECTION_EVERYWHERE
                                ,parent::DEFAULT_SECTION_ALL_CATEGORIES
                                ,parent::DEFAULT_SECTION_ALL_PAGES
                                ,parent::DEFAULT_SECTION_ALL_ARTICLES
                                );                
                                
    function __construct(){
    
        parent::__construct();
                 
        $this->loadSections();
        
    }

    function addSectionMenu( $section_id = '' ){
     
        if( $section_id == '' ){return '';}
            
        $sections = $this->array_sections;
        
        $section_name = $this->getSectionName($section_id);
        
        $section = array( parent::F_SECTION_ID => $section_id,
                          parent::F_STATUS     => parent::STATUS_ACTIVE 
                   );
                                              
        $sections[$section_name] = '';
        $sections[$section_name] = $section;
        
        $code = update_option('twiz_sections', $sections);
        
        $html = $this->getHtmlSectionMenu($section_id, $section_name, parent::STATUS_ACTIVE);
        
        return $html;
    }    
    
    function deleteSectionMenu( $section_id = '' ){
    
        global $wpdb;
        
        if( $section_id == '' ){return false;}
         
        $sql = "DELETE from ".$this->table." where ".parent::F_SECTION_ID." = '".$section_id."';";
        $code = $wpdb->query($sql);
        
        // Hard sections are not deleted
        if( !$this->in_multi_array($section_id, $this->array_default_section) ){
            
            $sections = $this->array_sections;
               
            foreach( $this->array_sections as $key => $value ){
        
                if(( $value[parent::F_SECTION_ID] == $section_id ) and ($key != "")){
           
                    $sections[$key] = '';
                    unset($sections[$key]);
                }
            }
            
           $code = update_option('twiz_sections', $sections);
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
        
            if( !$this->in_multi_array('c_'.$value->cat_ID, $sections) ){
            
                $select_cat .= '<option value="c_'.$value->cat_ID.'">'.$value->cat_name.'</option>';
            }
        }
        
        /* get pages */
        $pages = get_pages('sort_order=asc'); 
        
        foreach( $pages as $value ){
        
            if( !$this->in_multi_array('p_'.$value->ID, $sections )){
            
                $separator_page = '<option value="+++ +++ +++">+++ +++ +++</option>';
               
                $select_page .= '<option value="p_'.$value->ID.'">'.$value->post_title.'</option>';
            }
        }
        
        /* get last 125 posts */
        $posts = get_posts('sort_order=asc&numberposts=125'); 
        
        foreach( $posts as $value ){
        
            if( !$this->in_multi_array('a_'.$value->ID, $sections )){
            
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
       
             if( $value != parent::DEFAULT_SECTION_HOME ){
            
                $name = $this->getSectionName($value[parent::F_SECTION_ID], $key);
            
                $menu .= $this->getHtmlSectionMenu($value[parent::F_SECTION_ID], $name,  $value[parent::F_STATUS]);
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
       
            if( $value != parent::DEFAULT_SECTION_HOME ){
            
                $name = $this->getSectionName($value[parent::F_SECTION_ID], $key);
            
                $menu .= $this->getHtmlSectionvMenu($value[parent::F_SECTION_ID], $name, $value[parent::F_STATUS]);
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
    
    function getSectionName( $value = '', $key = null ){
    
        if(!preg_match("/_/i", $value)){
        
            return $value;
        }
        
        list( $type, $id ) = split('_', $value);
                
        $name = '';
        
        switch($type){
        
            case 'c': // is category
            
                $name = get_cat_name($id);
                
                /* User deleted category */
                if ($name==""){
                    
                    /*  Give the key instead if empty, and update the key if possible */
                    return $this->updateSectionMenuKey($key, $type.'_'.get_cat_id($key));
                }
                break;
                
            case 'p': // is page
            
                $page = get_page( $id ) ;
                $name = $page->post_title;
                
                /* User deleted page */
                if ( $name == "" ){
                
                    $page = get_page_by_title($key);
                    
                    /*  Give the key instead if empty, and update the key if possible */
                    return $this->updateSectionMenuKey($key, $type.'_'.$page->ID); // private
                }
                break;
                
            case 'a': // is post
            
                $post = get_post( $id ) ;
                $name = mysql2date('Y-m-d', $post->post_date).' : '.$post->post_title;
                
                /* User deleted post */
                if ( $name == "" ){
                
                    $post = get_page_by_title($key, 'OBJECT', 'post');
                    
                    /*  Give the key instead if empty, and update the key if possible */
                    return $this->updateSectionMenuKey($key, $type.'_'.$post->ID); // private
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
        
            // Reformat as array if necessary
            foreach( $sections as $key => $value ) {

                if( !is_array($sections[$key]) ){
                
                    $section = array( parent::F_SECTION_ID => $value,
                                      parent::F_STATUS     => parent::STATUS_ACTIVE);
                                  
                    $this->array_sections[$key] = $section; // Activated by default
                }
            }
            
            $code = update_option('twiz_sections', $this->array_sections); 
        }
                    
        // Register hard menu
        foreach( $this->array_hardmenu as $key ) {

            if( !isset($this->array_hardsections[$key]) ) $this->array_hardsections[$key] = '';
            
            if( $this->array_hardsections[$key] == '' ) {
            
                $section = array( parent::F_SECTION_ID => $key,
                                  parent::F_STATUS     => parent::STATUS_ACTIVE);
                              
                $this->array_hardsections[$key] = $section; // Activated by default
            }
        }
        
        $code = update_option('twiz_hardsections', $this->array_hardsections); 
        
        ksort($this->array_sections);
    }
    
    private function updateSectionMenuKey( $keyid = '', $newid = '' ){
           
        if(( $keyid != '' ) and ( $newid != 'c_' ) and ( $newid != 'p_' ) and ( $newid != 'a_' )){
            
            $sections = $this->array_sections;
            
            $section = array( parent::F_SECTION_ID => $newid,
                              parent::F_STATUS     => parent::STATUS_ACTIVE
            );

            $sections[$keyid] = '';
            $sections[$keyid] = $section;
            
            $code = update_option('twiz_sections', $sections);
        }
        
        return $keyid;
    }    
    
    function switchMenuStatus($id){ 
    
       $htmlstatus = '';
       $newstatus = '';
       $sections = $this->array_sections;
       $hardsections = $this->array_hardsections;
       
       $cleanid = str_replace('vmenu_','',$id);
       $cleanid = str_replace('menu_','',$cleanid);

        foreach( $sections as $key => $value ){
   
            if( $value[parent::F_SECTION_ID] == $cleanid ){
                
                
                $newstatus = ($value[parent::F_STATUS] == self::STATUS_INACTIVE) ? self::STATUS_ACTIVE : self::STATUS_INACTIVE; // swicth the status value
                $ddcleanid = str_replace('_'.$cleanid,'',$id);
            
                $htmlstatus = ($newstatus==self::STATUS_ACTIVE) ? $this->getHtmlImgStatus($cleanid , self::STATUS_ACTIVE, $ddcleanid ) : $this->getHtmlImgStatus($cleanid , self::STATUS_INACTIVE, $ddcleanid );
                
                $section = array( parent::F_SECTION_ID => $value[parent::F_SECTION_ID],
                                  parent::F_STATUS     => $newstatus);            
                $this->array_sections[$key] = $section;

                $code = update_option('twiz_sections', $this->array_sections);
        
            }
        }

        foreach( $hardsections as $key => $value ){
   
            if( $value[parent::F_SECTION_ID] == $cleanid ){
                
                
                $newstatus = ($value[parent::F_STATUS] == self::STATUS_INACTIVE) ? self::STATUS_ACTIVE : self::STATUS_INACTIVE; // swicth the status value
                $ddcleanid = str_replace('_'.$cleanid,'',$id);
            
                $htmlstatus = ($newstatus==self::STATUS_ACTIVE) ? $this->getHtmlImgStatus($cleanid , self::STATUS_ACTIVE, $ddcleanid ) : $this->getHtmlImgStatus($cleanid , self::STATUS_INACTIVE, $ddcleanid );
                
                $section = array( parent::F_SECTION_ID => $value[parent::F_SECTION_ID],
                                  parent::F_STATUS     => $newstatus);    
                                  
                $this->array_hardsections[$key] = $section;

                $code = update_option('twiz_hardsections', $this->array_hardsections);
        
            }
        }
        
        return $htmlstatus;
    }
}
?>