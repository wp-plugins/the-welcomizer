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
    
    const ITEM_TYPEUNLOAD     = 'Unload'; 

    /* on event array */ 
    private $array_element_type = array(self::ELEMENT_TYPE_ID      
                                       ,self::ELEMENT_TYPE_CLASS  
                                       ,self::ELEMENT_TYPE_NAME  
                                       );
                                       
    function __construct(){
    
        parent::__construct();
                 
        $this->loadSections();
    }

    function addSectionMenu( $section_id = '' ){
        
        if( $section_id == '' ){return '';}
            
        $sections = $this->array_sections;
        
        $section_name = $this->getSectionName($section_id);
        
        $sections[$section_name] = '';
        $sections[$section_name] = $section_id;
    
        $code = update_option('twiz_sections', $sections);
        
        $html = $this->getHtmlSectionMenu($section_id, $section_name);
        
        return $html;
    }    
    
    function deleteSectionMenu( $section_id = '' ){
    
        global $wpdb;
        
        if( $section_id == '' ){return false;}
         
        $sql = "DELETE from ".$this->table." where ".parent::F_SECTION_ID." = '".$section_id."';";
        $code = $wpdb->query($sql);
  
        if( !in_array($section_id, $this->array_default_section) ){
            
            $sections = $this->array_sections;
               
            foreach( $this->array_sections as $key => $value ){
        
                if(( $value == $section_id ) and ($key != "")){
           
                    $sections[$key] = '';
                    unset($sections[$key]);
                }
            }
            
           $code = update_option('twiz_sections', $sections);
        }

        return true;
    }     
    
    private function getHtmlAddSection(){
    
        global $wpdb;
 
        $select_cat = '';
        $select_page = '';
        $select_post = '';
        $separator_page = '';
        $separator_post = '';
        
        $sections = $this->array_sections;
  
        $addsection = '<div id="twiz_add_sections">';
        
        $select = '<select name="twiz_slc_sections" id="twiz_slc_sections">';
        
        $select .= '<option value="" selected="selected">'.__('Choose', 'the-welcomizer').'</option>';
        
        /* get categories */
        $categories = get_categories('sort_order=asc'); 
 
        foreach( $categories as $value ){
        
            if( !in_array('c_'.$value->cat_ID, $sections) ){
            
                $select_cat .= '<option value="c_'.$value->cat_ID.'">'.$value->cat_name.'</option>';
            }
        }
        
        /* get pages */
        $pages = get_pages('sort_order=asc'); 
        
        foreach( $pages as $value ){
        
            if( !in_array('p_'.$value->ID, $sections )){
            
                $separator_page = '<option value="+++ +++ +++">+++ +++ +++</option>';
               
                $select_page .= '<option value="p_'.$value->ID.'">'.$value->post_title.'</option>';
            }
        }
        
        /* get last 125 posts */
        $posts = get_posts('sort_order=asc&numberposts=125'); 
        
        foreach( $posts as $value ){
        
            if( !in_array('a_'.$value->ID, $sections )){
            
                $separator_post = '<option value="+++ +++ +++">+++ +++ +++</option>';
               
                $select_post .= '<option value="a_'.$value->ID.'">'. mysql2date('Y-m-d', $value->post_date). ' : '.$value->post_title.'</option>';
            }
        }
        
        /* close select */
        $addsection .=  $select.$select_cat.$separator_page.$select_page.$separator_post.$select_post.'</select>';

        $addsection .= '<input type="button" name="twiz_save_section" id="twiz_save_section" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /> <a name="twiz_cancel_section" id="twiz_cancel_section">'.__('Cancel', 'the-welcomizer').'</a>';
        
        $addsection .= '</div>';
        
        return $addsection;
    }

    protected function getHtmlMenu(){
    
        /* retrieve stored sections */
        $sections = $this->array_sections;
       
        $twiz_menu = ( ( $this->DEFAULT_SECTION == parent::DEFAULT_SECTION_HOME ) || ( $this->DEFAULT_SECTION  == '' ) ) ? '' : ' style="display: none;"';
        $twiz_menu_everywhere = ( $this->DEFAULT_SECTION == parent::DEFAULT_SECTION_EVERYWHERE ) ? '' : ' style="display: none;"';
        
        $menu = '<div id="twiz_menu"'.$twiz_menu.'>';
       
        /* default home section */
        $menu .= '<div id="twiz_menu_home" class="twiz-menu twiz-menu-selected">'.__('Home').'</div>';
       
        /* generate the section menu */
        foreach( $sections as $key => $value ){
       
            if( $value != parent::DEFAULT_SECTION_HOME ){
            
                $name = $this->getSectionName($value, $key);
            
                $menu .= $this->getHtmlSectionMenu($value, $name);
            }
        }

        $menu .= '<div id="twiz_delete_menu">x</div>';
        $menu .= '<div id="twiz_add_menu">+</div>';

        $menu .= $this->getHtmlAddSection(); // private

        $menu .= '</div>';
        $menu .= '<div class="twiz-clear"></div>';
        
        /* default everywhere section */
        
        $menu .= '<div id="twiz_menu-everywhere"'.$twiz_menu_everywhere.'>';
       
        $menu .= '<div id="twiz_menu_everywhere" class="twiz-menu twiz-menu-selected">'.__('Everywhere', 'the-welcomizer').'</div>';
        $menu .= '<div id="twiz_menu_allcategories" class="twiz-menu">'.__('All', 'the-welcomizer').' '.__('Categories').'</div>';
        $menu .= '<div id="twiz_menu_allpages" class="twiz-menu">'.__('All', 'the-welcomizer').' '.__('Pages').'</div>';
        $menu .= '<div id="twiz_menu_allarticles" class="twiz-menu">'.__('All', 'the-welcomizer').' '.__('Posts').'</div>';
        
        $menu .= '<div id="twiz_delete_menu_everywhere">x</div>';
       
        $menu .= '</div>';
        $menu .= '<div class="twiz-clear"></div>';
        
        return $menu;
    }
    
    private function getHtmlSectionMenu( $section_id = '', $section_name = '' ){
    
       if( $section_id == '' ){return '';}
       if( $section_name == '' ){return '';}
    
       $html = '<div id="twiz_menu_'.$section_id.'" class="twiz-menu">'.$section_name.'</div>';
            
       return $html;
    }

    protected function getSectionName( $value = '', $key = null ){
    
        if( $value == parent::DEFAULT_SECTION_HOME ){ 
        
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
                $name = $post->post_title;
                
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

        if( !is_array($this->array_sections) ){
        
            $this->array_sections = array();
        }
        
        ksort($this->array_sections);
    }
    
    private function updateSectionMenuKey( $keyid = '', $newid = '' ){
           
        if(( $keyid != '' ) and ( $newid != 'c_' ) and ( $newid != 'p_' ) and ( $newid != 'a_' )){
            
            $sections = $this->array_sections;
            
            $sections[$keyid] = '';
            $sections[$keyid] = $newid;
        
            $code = update_option('twiz_sections', $sections);
        }
        
        return $keyid;
    }    
}
?>