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

class TwizLibrary extends Twiz{
    
    // variable declaration 
    public $array_library;
    public $array_library_dir;
    private $default_lib_dir;
    
    function __construct(){
      
        parent::__construct();
                 
        $this->default_lib_dir = str_replace(ABSPATH,"", WP_CONTENT_DIR . parent::IMPORT_PATH);
        
        $this->loadLibrary();
    }

    function getHtmlLibrary( $id = '' ){
    
        $html = '<div id="twiz_library_master">';

        $html .= $this->getHtmlLibraryMenu();
        $html .= $this->getHtmlLibraryList( $id );

        $html .= '</div>';
        
        return $html;
    }
    
    function GetHtmlFormLibrary( ){

        $twiz_group_name = '';
        $twiz_group_start_delay = '';     
        $twiz_status = '';     

        $action = __('Add New', 'the-welcomizer');

        $jsscript = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {';
 
        $jsscript .= '
$("#twiz_lib_menu").css("display", "none");
';

        $jsscript .= '});
 //]]>
</script>';

        $html = '<div class="twiz-box-menu"><div class="twiz-text-right twiz-float-right">'.__('Action', 'the-welcomizer').'<div class="twiz-green">'.$action.'</div></div>';

        $html .='<br>'.__('Directory name', 'the-welcomizer').': ';
        $html .= '<input type="text" id="twiz_lib_dir" name="twiz_lib_dir"  value="" maxlength="50" class="twiz-input-focus"/><br><br>'.__('Example', 'the-welcomizer').':<br><span class="twiz-green">wp-includes/js/jquery/ui/</span>';

        // cancel and save button
        $html .= '<div class="twiz-text-right twiz-float-right"><span id="twiz_lib_save_img_box" name="twiz_lib_save_img_box" class="twiz-loading-gif-save"></span><a name="twiz_lib_cancel" id="twiz_lib_cancel">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_lib_save" id="twiz_lib_save" class="button-primary" value="'.__('Save', 'the-welcomizer').'" /></div>';
        
        $html .= '</div>'.$jsscript;
        
        return $html;
    }
    
    private function getHtmlLibraryList( $dir = '' ){

        $rowcolor = '';
        
         // hide element 
         $jquery = '<script>
//<![CDATA[
jQuery(document).ready(function($) {
    $("#twiz_add_menu").fadeOut("slow");
    $("#twiz_add_sections").fadeOut("slow"); 
    $("#twiz_right_panel").fadeOut("slow");
    $("#twiz_library_upload").fadeIn("slow");   
});
//]]>
</script>';


        $html = '<table class="twiz-table-list" cellspacing="0">';
        
        $html.= '<tr class="twiz-table-list-tr-h"><td class="twiz-td-v-line"></td>
<td class="twiz-table-list-td-h twiz-text-center twiz-td-status">'.__('Status', 'the-welcomizer').'</td>
<td class="twiz-table-list-td-h twiz-text-left">'.__('Filename', 'the-welcomizer').'</td>
<td class="twiz-table-list-td-h twiz-td-lib-order twiz-text-center">'.__('Order', 'the-welcomizer').'</td>
<td class="twiz-table-list-td-h twiz-td-lib-action twiz-text-right">'.__('Action', 'the-welcomizer').'</td>
</tr>';
        $countdir = count( $this->array_library_dir );
        
        if($this->admin_option[parent::KEY_SORT_LIB_DIR] == 'reversed' ){
        
           $library_dir_array = array_reverse($this->array_library_dir, true);
           
        }else{
        
           $library_dir_array = $this->array_library_dir;
        }
         
        $twiz_toggle = get_option('twiz_toggle');
        
        foreach( $library_dir_array  as $key => $directory ){
           
            if(!isset($this->toggle_option[$this->userid][parent::KEY_TOGGLE_LIBRARY])) $this->toggle_option[$this->userid][parent::KEY_TOGGLE_LIBRARY] = '';
            
            if(!isset($this->toggle_option[$this->userid][parent::KEY_TOGGLE_LIBRARY]['twizlib'.$key])) $this->toggle_option[$this->userid][parent::KEY_TOGGLE_LIBRARY]['twizlib'.$key] = '';
     
            if ( $dir  == $directory ) {
                // Set Toggle On
                $twiz_toggle[$this->userid][parent::KEY_TOGGLE_LIBRARY]['twizlib'.$key] = 1;
                $this->toggle_option = $twiz_toggle;
            }
            
            if( ( $countdir == 1 ) 
            or ( $this->toggle_option[$this->userid][parent::KEY_TOGGLE_LIBRARY]['twizlib'.$key] == '1' ) ) {
           
                $hide = '';
                $toggleimg = 'minus';
                $boldclass = ' twiz-bold';
                
            }else{

                $hide = ' twiz-display-none';
                $toggleimg = 'plus';
                $boldclass = '';
            }
            
            $html.= '
        <tr class="twiz-row-color-1" id="twiz_list_tr_twizlib'.$key.'"><td><div class="twiz-relative"><img id="twiz_library_img_twizlib'.$key.'" name="twiz_library_img_twizlib'.$key.'" src="'.$this->pluginUrl.'/images/twiz-'.$toggleimg.'.gif" width="18" height="18" class="twiz-toggle-library twiz-toggle-img"/></div></td>
        <td class="twiz-table-list-td" colspan="2"><a id="twiz_library_e_a_twizlib'.$key.'" name="twiz_library_e_a_twizlib'.$key.'"  class="twiz-toggle-library'.$boldclass.'">'.$directory.'</a></td>   <td class="twiz-table-list-td twiz-text-center" id="twiz_list_td_twizlib'.$key.'"></td>
        <td class="twiz-table-list-td twiz-text-right">';
        
            // Not for twiz dir
            if($this->default_lib_dir != $directory){
            
                $html.= '<img width="22"  height="11" src="'.$this->pluginUrl.'/images/twiz-unlink.png" id="twiz_library_unlink_'.$key.'" name="twiz_library_unlink_'.$key.'" title="'.__('Unlink', 'the-welcomizer').'" class="twiz-library-unlink" />';
            }
    
        $html.= '</td></tr>';
            
            $countlib = 0;
            foreach( $this->array_library as $value ){
                
                if( $value[parent::KEY_DIRECTORY] == $directory ){
                
                    if( $this->libraryExists($directory.$value[parent::KEY_FILENAME], $value[parent::F_ID]) ) {
                    
                    $countlib++;
                    
                        if(!isset($value[parent::KEY_ORDER])) $value[parent::KEY_ORDER] = '0';

                        $rowcolor = ( $rowcolor == 'twiz-row-color-2' ) ? 'twiz-row-color-1' : 'twiz-row-color-2';
                    
                        $statushtmlimg = ($value[parent::F_STATUS]=='1') ? $this->getHtmlImgStatus($value[parent::F_ID], parent::STATUS_ACTIVE, 'library') : $this->getHtmlImgStatus($value[parent::F_ID], parent::STATUS_INACTIVE, 'library');
                                
                        $html.= '
            <tr class="twizlib'.$key.' '.$rowcolor.$hide.'" id="twiz_list_tr_'.$value[parent::F_ID].'"><td class="twiz-td-v-line twiz-row-color-3">&nbsp;'.$value[parent::KEY_ORDER].'&nbsp;</td><td class="twiz-td-status twiz-text-center" id="twiz_td_status_library_'.$value[parent::F_ID].'">'.$statushtmlimg.'</td>
            <td class="twiz-table-list-td"><a href="'.get_site_url().'/'.$value[parent::KEY_DIRECTORY].$value[parent::KEY_FILENAME].'" target="_blank">'.$value[parent::KEY_FILENAME].'</a></td>
             <td class="twiz-table-list-td twiz-text-center" id="twiz_list_td_'.$value[parent::F_ID].'"><div class="twiz-arrow-lib twiz-arrow-lib-n" name="twiz_new_order_up_'.$value[parent::F_ID].'" id="twiz_new_order_up_'.$value[parent::F_ID].'"></div><div class="twiz-arrow-lib twiz-arrow-lib-s" name="twiz_new_order_down_'.$value[parent::F_ID].'" id="twiz_new_order_down_'.$value[parent::F_ID].'"></div></td>
            <td class="twiz-table-list-td twiz-text-right">';
            
                        // Only for twiz dir
                        if($this->default_lib_dir == $directory){
                        
                            $html.= '<img height="25" src="'.$this->pluginUrl.'/images/twiz-delete.gif" id="twiz_delete_'.$value[parent::F_ID].'" name="twiz_delete_'.$value[parent::F_ID].'" title="'.__('Delete', 'the-welcomizer').'" class="twiz-delete" />';
                            
                        }
                        
                        $html.= '</td></tr>';
            
                    }
                }
            }
            $code = update_option('twiz_toggle', $twiz_toggle);      
            
            if( $countlib == 0 ){
    
                $html .= '
    <tr class="twizlib'.$key.$hide.' twiz-row-color-2"><td></td>
    <td class="twiz-table-list-td twiz-text-center twiz-blue" colspan="4">'. __('This directory is empty.', 'the-welcomizer').'</td>   
</tr>';
            }
        }
        
        $html.= '</table>'.$jquery;
                 
        return $html;
        
    }
    
    protected function addLibrary( $lib = array() ){
        
        if( count($lib)==0 ){return false;}
                
        $this->array_library[] = $lib;
        
        $code = update_option('twiz_library', $this->array_library);
        
        return $code;

    }
    
    function linkLibraryDir ( $directory = '' ){
        
        if( $directory == '' ){return false;}
                
        $this->array_library_dir[] = ltrim($directory, "/");
        
        $code = update_option('twiz_library_dir', $this->array_library_dir);
        $code = $this->loadLibrary(); // reload library
        $id = count($this->array_library_dir) - 1;
 
        return $directory;
    }
    
    function unlinkLibraryDir( $id = '' ){
    
        $twiz_toggle = '';
        $library = $this->array_library;
        $library_dir = $this->array_library_dir;
 
        foreach( $library_dir as $key => $directory ){

            if( $key == $id ){
 
                $this->array_library_dir[$key] = '';
                unset($this->array_library_dir[$key]);
                
                // Unset Toggle
                $twiz_toggle = get_option('twiz_toggle');
                $twiz_toggle[$this->userid][parent::KEY_TOGGLE_LIBRARY]['twizlib'.$key] = '';
                unset($twiz_toggle[$this->userid][parent::KEY_TOGGLE_LIBRARY]['twizlib'.$key]);
                $this->toggle_option = $twiz_toggle;
                
                        
                foreach( $library as $key => $value ){
                 
                    if( $value[parent::KEY_DIRECTORY] == $directory ){
          
                        $this->array_library[$key] = '';
                        
                        unset($this->array_library[$key]);
                    }
                }
            }
        }
        $code = update_option('twiz_toggle', $twiz_toggle);  
        $code = update_option('twiz_library', $this->array_library);
        $code = update_option('twiz_library_dir', $this->array_library_dir);
       
        $ok = $this->rebuildOrderKeys();
        
        return true;
    }
        
    function deleteLibrary( $id = '' ){
    
        $library = $this->array_library;
 
        foreach( $library as $key => $value ){
        
            $file = WP_CONTENT_DIR . parent::IMPORT_PATH . $value[parent::KEY_FILENAME]; // twiz only
         
            if( $value[parent::F_ID] == $id ){
    
                if( @file_exists($file) ){
                
                    unlink($file);                  
                }
  
                $this->array_library[$key] = '';
                
                unset($this->array_library[$key]);
                
                $code = update_option('twiz_library', $this->array_library);
                
                $ok = $this->rebuildOrderKeys();
                
                return $code;

            }
        }
        
        $ok = $this->rebuildOrderKeys();
        
        return true;
    }
    
    private function getLibraryArrayKey( $keyneeded = '' ){
        
        $result = '';
        $i = 1;
        foreach( $this->array_library as $value ){
        
            if(!isset($value[$keyneeded])) $value[$keyneeded] = $i;
            
            $result[] = $value[$keyneeded];
            $i++;
        }
        
        if(!is_array($result)){ $result = array();}
        
        return $result;
    }
    
    private function getHtmlLibraryMenu(){
    
        $html = '<div class="twiz-row-color-1 twiz-text-right" name="twiz_lib_menu" id="twiz_lib_menu"><div id="twiz_far_matches" class="twiz-float-left twiz-text-right twiz-green"></div><span><a id="twiz_link_directory" class="twiz-bold">'.__('Link a Directory', 'the-welcomizer').'</a></span></div></div>';

        return $html;
    }
    
    private function getLibraryValue( $id = '', $field ) {

        foreach( $this->array_library as $value ){
        
            if( $value[parent::F_ID] == $id ){
       
                return $value[$field];
            }
        }
        
        return '';
    }
    
    protected function getMax( $key = '' ){
        
        $id = '';
        $i = 0;
        
        foreach( $this->array_library as $value ){
           
            if(!isset($value[$key])) $value[$key] = $i;

            $id[] = $value[$key];
            $i++;
        }

        if( !is_array($id) ){
        
            $id = array($i);
        }
        if( $key == parent::KEY_ORDER ){
            $max = max($id);
            $max = ($max < count($id)) ? count($this->array_library) : max($id);
  
        }else{
        
            $max = max($id);
        }
        
        return $max;
    }
    
    private function loadLibrary(){
    
        $this->array_library = get_option('twiz_library');
        $this->array_library_dir = get_option('twiz_library_dir');
            
        if( !is_array($this->array_library) ){
        
            $arraylib = array();
            $this->array_library = array();
            
        }else{
        
            $arraylib = $this->array_library;
        }
        
     
        if( !is_array($this->array_library_dir) ){
        
            $this->array_library_dir = array($this->default_lib_dir);
        }        
        
        // get libray filename array 
        $array_filename = $this->getLibraryArrayKey(parent::KEY_FILENAME);
        
        // loop directories
        foreach( $this->array_library_dir as $directory ){
                

            // synchronize js files 
            $filearray = $this->getFileDirectory(array(parent::EXT_JS, parent::EXT_CSS), ABSPATH.$directory);
            
            
            // check isset for field added later
            foreach( $arraylib as $key => $value ){
            
                if( !isset($this->array_library[$key][parent::KEY_DIRECTORY]) ){
                
                    $this->array_library[$key][parent::KEY_DIRECTORY] = ''; 
                    $this->array_library[$key][parent::KEY_DIRECTORY] = $directory;
                }
            }

            // Loop files
            foreach( $filearray as $filename ){

                // add it if not found
                if(!in_array($filename, $array_filename)){
                
                    $library = array(parent::F_ID          => $this->getMax(parent::F_ID) + 1
                                    ,parent::F_STATUS      => 0
                                    ,parent::KEY_ORDER     => $this->getMax(parent::KEY_ORDER) + 1
                                    ,parent::KEY_DIRECTORY => $directory
                                    ,parent::KEY_FILENAME  => $filename
                                    );
                    
                    $code = $this->addLibrary($library);
                }
            }
        }
        
        $ok = $this->rebuildOrderKeys();
        
        return true;
    }
    
    private function libraryExists( $path_filename = '', $id = '' ){
    
        if( $path_filename == '' ){return false;}
        if( $id == '' ){return false;}

        if( @file_exists( ABSPATH.$path_filename ) ){
           
            return true;
           
        }else{

            $library = $this->array_library;
 
            foreach( $library as $key => $value ){

                if( $value[parent::F_ID] == $id ){
        
                    $this->array_library[$key] = '';
                    
                    unset($this->array_library[$key]); // unlink arraykey
                    
                    $code = update_option('twiz_library', $this->array_library);
                }
            }
            
            $ok = $this->rebuildOrderKeys();
        
            return false;
        }
    }
    
    private function rebuildOrderKeys(){
    
        // get library order array 
        $array_order = $this->getLibraryArrayKey(parent::KEY_ORDER);
        $array_order_dir = $this->getLibraryArrayKey(parent::KEY_DIRECTORY);
        
        if( $this->admin_option[parent::KEY_SORT_LIB_DIR] == 'original' ){
            // resort Library 
            array_multisort($array_order_dir, SORT_ASC, SORT_STRING,
                            $array_order, SORT_ASC, SORT_NUMERIC, $this->array_library);    
        }else{
            // resort Library 
            array_multisort($array_order_dir, SORT_DESC, SORT_STRING,
                            $array_order, SORT_ASC, SORT_NUMERIC, $this->array_library);    
        
        }
        
        $library = $this->array_library;
        $i = 1;
        
        foreach( $library as $key => $value ){
        
            $this->array_library[$key][parent::KEY_ORDER] = $i;
            $i = $i + 1;
        }
        
        $code = update_option('twiz_library', $this->array_library);    
        
        return true;
    }   
    
    function switchLibraryStatus( $id = '' ){ 
    
        global $wpdb;
        
        if( $id=='' ){return false;}
    
        $cleanid = str_replace('library_','',$id);
           
        $value = $this->getLibraryValue($cleanid, parent::F_STATUS);
        
        $newstatus = ( $value == '1' ) ? '0' : '1'; // swicth the status value
        
        $code = $this->updateLibraryValue($cleanid, parent::F_STATUS, $newstatus);
        
        if( $code == true ){
        
            $html = ( $newstatus == '1' ) ? $this->getHtmlImgStatus($cleanid, parent::STATUS_ACTIVE, 'library') : $this->getHtmlImgStatus($cleanid, parent::STATUS_INACTIVE, 'library');
        }else{ 
        
            $html = ( $value[parent::F_STATUS] == '1' ) ? $this->getHtmlImgStatus($cleanid, parent::STATUS_ACTIVE, 'library') : $this->getHtmlImgStatus($cleanid, parent::STATUS_INACTIVE, 'library');
            
        }
        
        return $html;
    }
      
    private function updateLibraryValue( $id = '', $field = '', $newvalue = '' ) {

        $library = $this->array_library;
 
        foreach( $library  as $key => $value ){
        
            if( $value[parent::F_ID] == $id ){
       
                $this->array_library[$key][$field] = $newvalue;
                
                $code = update_option('twiz_library', $this->array_library);
                
                return $code;
            }
        }
        
        return false;
    }
    
    function updateLibraryOrder( $id = '', $order = '' ) {
        
        $library = $this->array_library;
        $neworder = 1;
        $array_id = '';
        $max = '';
        $i = 1;
        
        // Update/set order
        foreach( $library as $key => $value ){
 
            $array_id[$i] = $value[parent::F_ID];
            
            // Check the id
            if( $value[parent::F_ID] == $id ){
            
                $ibase = $i; // We keep the key
                
                if(!isset($value[parent::KEY_ORDER]))$value[parent::KEY_ORDER] = $this->getMax( parent::KEY_ORDER ) + 1;             
                $maxkeyorder = $this->getMax( parent::KEY_ORDER );
 
                $neworder = ( $order == parent::LB_ORDER_UP ) ? $value[parent::KEY_ORDER] - 1 : $value[parent::KEY_ORDER] + 1;
                $neworder = ( $neworder < 1 ) ? 1 : $neworder;
                
                if( $maxkeyorder > 1 ){
                
                    $neworder = ( $neworder > $maxkeyorder ) ? $maxkeyorder : $neworder;
                }
              
                $ok = $this->updateLibraryValue( $value[parent::F_ID], parent::KEY_ORDER, $neworder );
            }
            $i++;
        }
        
        // Switch order
        switch($order){

            case parent::LB_ORDER_UP:
            
                $ibase--;
                $neworder++;
                
                break;
                
            case parent::LB_ORDER_DOWN:
            
                $ibase++;
                $neworder--;
                
                break;                    
        }

        if( $ibase < 1 ){ // no actions
        
            return true;
        }
       
        if( $ibase > $maxkeyorder ){  // no actions
        
            return true;
        }
        
        $neworder = ( $neworder < 1 ) ? 1 : $neworder;
          
        if( $maxkeyorder > 1 ){
        
           $neworder = ( $neworder > $maxkeyorder ) ? $maxkeyorder : $neworder;
        }
        
        if( !isset($array_id[$ibase]) ){
        
            $ibase = $id;
        }
        
        $ok = $this->updateLibraryValue( $array_id[$ibase] , parent::KEY_ORDER, $neworder);
        $ok = $this->rebuildOrderKeys();

        
        return true;
    }    
}
?>