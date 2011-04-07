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

class TwizLibrary extends Twiz{
	
    /* variable declaration */
    private $array_library;
	
	
    function __construct(){
    
        parent::__construct();
                 
        $this->loadLibrary();
    }

    function getHtmlLibrary(){
    
        $html = '<div id="twiz_library_master">';
        
        $html .= $this->getHtmlLibraryList();

        $html .= '</div>';
        
        return $html;
    }
    
    private function getHtmlLibraryList(){
    
        $opendiv = '';
        $closediv = '';
        $rowcolor = '';
        
         /* hide element */
         $jquery = '<script>
//<![CDATA[
jQuery(document).ready(function($) {
    $("#twiz_new").fadeOut("slow");
    $("#twiz_add_menu").fadeOut("slow");
    $("#twiz_delete_menu").fadeOut("slow");
    $("#twiz_add_sections").fadeOut("slow"); 
    $("#twiz_right_panel").fadeOut("slow");
    $("#twiz_library_upload").fadeIn("slow");    
});
//]]>
</script>';

        /* ajax container */ 
        if( !in_array($_POST['twiz_action'], $this->array_action_excluded) ){
        
            $opendiv = '<div id="twiz_container">';
            $closediv = '</div>';
        }

        $html = $opendiv.'<table class="twiz-table-list" cellspacing="0">';
        
        $html.= '<tr class="twiz-table-list-tr-h">
<td class="twiz-table-list-td-h twiz-td-center twiz-td-status">'.__('Status', 'the-welcomizer').'</td>
<td class="twiz-table-list-td-h twiz-td-left">'.__('Filename', 'the-welcomizer').'</td>
<td class="twiz-table-list-td-h twiz-td-action twiz-td-right">'.__('Action', 'the-welcomizer').'</td></tr>';
      
        
        foreach( $this->array_library as $value ){
             
            if( $this->libraryExists($value[parent::KEY_FILENAME], $value[parent::F_ID]) ) {
            
                $rowcolor = ($rowcolor=='twiz-row-color-1') ? 'twiz-row-color-2' : 'twiz-row-color-1';
            
                $statushtmlimg = ($value[parent::F_STATUS]=='1') ? $this->getHtmlImgStatus($value[parent::F_ID], parent::STATUS_ACTIVE) : $this->getHtmlImgStatus($value[parent::F_ID], parent::STATUS_INACTIVE);
                        
                $html.= '
    <tr class="'.$rowcolor.'" name="twiz_list_tr_'.$value[parent::F_ID].'" id="twiz_list_tr_'.$value[parent::F_ID].'" ><td class="twiz-td-status twiz-td-center" id="twiz_td_status_'.$value[parent::F_ID].'">'.$statushtmlimg.'</td>
    <td class="twiz-table-list-td"><a href="'.WP_CONTENT_URL.parent::IMPORT_PATH.$value[parent::KEY_FILENAME].'" target="_blank">'.$value[parent::KEY_FILENAME].'</a></td>
    <td class="twiz-table-list-td twiz-td-right"><img height="25" src="'.$this->pluginUrl.'/images/twiz-delete.gif" id="twiz_delete_'.$value[parent::F_ID].'" name="twiz_delete_'.$value[parent::F_ID].'" alt="'.__('Delete', 'the-welcomizer').'" title="'.__('Delete', 'the-welcomizer').'"/><img class="twiz-loading-gif" src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_img_delete_'.$value[parent::F_ID].'" name="twiz_img_delete_'.$value[parent::F_ID].'"></td></tr>';
            }
        }
        
        $html.= '</table>'.$closediv.$jquery;
                 
        return $html;
        
    }
    
    protected function addLibrary( $lib = array() ){
        
        if( count($lib)==0 ){return false;}
        
        $lib[parent::F_ID] = '';
        $lib[parent::F_ID] = $this->getMaxId() + 1;
        
        $this->array_library[] = $lib;
        update_option('twiz_library', $this->array_library);
        
        return true;
    }
    
    function deleteLibrary( $id = '' ){
    
        $library = $this->array_library;
 
        foreach( $library as $key => $value ){
        
            $file = WP_CONTENT_DIR . parent::IMPORT_PATH . $value[parent::KEY_FILENAME];
         
            if( $value[parent::F_ID] == $id ){
    
                if( file_exists($file) ){
                
                    unlink($file);
                }
  
                $this->array_library[$key] = '';
                
                unset($this->array_library[$key]);
                
                update_option('twiz_library', $this->array_library);
                
                return true;
                
            }
        }
        
        return true;
    }
    
    private function getLibraryArrayKey($keyneeded){
        
        $result = '';
    
        foreach( $this->array_library as $value ){
            
            $result[] = $value[$keyneeded];
        }
        
        return $result;
    }
    
    private function getLibraryValue( $id = '', $field ) {

        foreach( $this->array_library as $value ){
        
            if( $value[parent::F_ID] == $id ){
       
                return $value[$field];
            }
        }
        
        return '';
    }
    
    private function getMaxId(){
        
        $id = '';
        
        foreach( $this->array_library as $value ){

            $id[] = $value[parent::F_ID];
        }

        if( !is_array($id) ){
        
            $id = array();
        }
        
        $max = max($id);

        return $max;
    }
    
    private function loadLibrary(){
    
        $this->array_library = get_option('twiz_library');
            
        /* get loaded library */
        if( !is_array($this->array_library) ){
        
            $this->array_library = array();
        }
        
        /* get libray filename array */
        $array_filename = $this->getLibraryArrayKey(parent::KEY_FILENAME);
        
        /* synchronize js files from /wp-content/twiz/ */
        $filearray = $this->getImportDirectory('.js');
        
        foreach( $filearray as $filename ){
            
            /* synchronize new js files */
            if(!in_array($filename, $array_filename)){
            
                $library = array(parent::F_ID => $this->getMaxId() + 1,
                                parent::F_STATUS => 0, 
                                parent::KEY_FILENAME => $filename);
                                      
                $code = $this->addLibrary($library);
            }
        }
    }
    
    private function libraryExists( $filename = '', $id = '' ){
    
        if( $filename=='' ){return false;}
        if( $id=='' ){return false;}
        
        $file = WP_CONTENT_DIR.parent::IMPORT_PATH.$filename;
        
        if( file_exists( $file ) ){
            
            return true;
           
        }else{
            
            if( $this->deleteLibrary($id) ){
            
                return false;
            }
        }
    }
    
    function switchLibraryStatus( $id = '' ){ 
    
        global $wpdb;
        
        if( $id=='' ){return false;}
    
        $value = $this->getLibraryValue($id, parent::F_STATUS);
        
        $newstatus = ( $value == '1' ) ? '0' : '1'; // swicth the status value
               
        if( $code = $this->updateLibraryValue($id, parent::F_STATUS, $newstatus) ){
        
            $html = ( $newstatus == '1' ) ? $this->getHtmlImgStatus($id, parent::STATUS_ACTIVE) : $this->getHtmlImgStatus($id, parent::STATUS_INACTIVE);
        }else{ 
        
            $html = ( $value[parent::F_STATUS] == '1' ) ? $this->getHtmlImgStatus($id, parent::STATUS_ACTIVE) : $this->getHtmlImgStatus($id, parent::STATUS_INACTIVE);
            
        }
        
        return $html;
    }
      
    private function updateLibraryValue( $id = '', $field = '', $newvalue = '' ) {

        foreach( $this->array_library as $key => $value ){
        
            if( $value[parent::F_ID] == $id ){
       
                $this->array_library[$key][$field] = $newvalue;
                
                update_option('twiz_library', $this->array_library);
                
                return true;
            }
        }
        
        return false;
    }
}
?>