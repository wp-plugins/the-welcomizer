<?php
/*  Copyright 2011  Sebastien Laframboise  (email:wordpress@sebastien-laframboise.com)

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

class Twiz{
    
    /* variable declaration */
    private $table;
    private $version;
    private $dbVersion;
    private $pluginUrl;
    private $pluginDir;
    private $pluginName;
    private $logobigUrl;
    private $logoUrl;
    private $nonce;    
    
     /* class default section constant */ 
    const SECTION_DEFAULT  = 'home';
    
    /* class directional image suffix constants */ 
    const DIMAGE_N  = 'n';
    const DIMAGE_NE = 'ne';
    const DIMAGE_E  = 'e';
    const DIMAGE_SE = 'se';
    const DIMAGE_S  = 's';
    const DIMAGE_SW = 'sw';
    const DIMAGE_W  = 'w';
    const DIMAGE_NW = 'nw';
    
    /* class action constants */ 
    const ACTION_MENU          = 'menu';
    const ACTION_SAVE          = 'save';
    const ACTION_CANCEL        = 'cancel';
    const ACTION_ID_LIST       = 'idlist';
    const ACTION_OPTIONS       = 'options';
    const ACTION_VIEW          = 'view';
    const ACTION_NEW           = 'new';
    const ACTION_EDIT          = 'edit';
    const ACTION_EDIT_TD       = 'tdedit';
    const ACTION_DELETE        = 'delete';
    const ACTION_STATUS        = 'status';
    const ACTION_IMPORT        = 'import';
    const ACTION_EXPORT        = 'export';
    const ACTION_GLOBAL_STATUS = 'gstatus';
    const ACTION_ADD_SECTION   = 'addsection';
    
    /* class jquery common options constants */ 
    const JQUERY_HEIGHT            = 'height';
    const JQUERY_WITDH             = 'width';
    const JQUERY_OPACITY           = 'opacity';
    const JQUERY_FONTSIZE          = 'fontSize';    
    const JQUERY_MARGINTOP         = 'marginTop';
    const JQUERY_MARGINBOTTOM      = 'marginBottom';
    const JQUERY_MARGINLEFT        = 'marginLeft';
    const JQUERY_MARGINRIGHT       = 'marginRight';
    const JQUERY_PADDINGTOP        = 'paddingTop';
    const JQUERY_PADDINGBOTTOM     = 'paddingBottom';    
    const JQUERY_PADDINGLEFT       = 'paddingLeft';
    const JQUERY_PADDINGRIGHT      = 'paddingRight';
    const JQUERY_BORDERWIDTH       = 'borderWidth';
    const JQUERY_BORDERTOPWIDTH    = 'borderTopWidth';
    const JQUERY_BORDERBOTTOMWIDTH = 'borderBottomWidth';        
    const JQUERY_BORDERIGHTWIDTH   = 'borderRightWidth';
    const JQUERY_BORDERLEFTWIDTH   = 'borderLeftWidth';
    
    /* class table field constants */ 
    const F_ID                   = 'id';   
    const F_SECTION_ID           = 'section_id';    
    const F_STATUS               = 'status';  
    const F_LAYER_ID             = 'layer_id';  
    const F_START_DELAY          = 'start_delay';  
    const F_DURATION             = 'duration';  
    const F_START_TOP_POS_SIGN   = 'start_top_pos_sign';        
    const F_START_TOP_POS        = 'start_top_pos';  
    const F_START_LEFT_POS_SIGN  = 'start_left_pos_sign';  
    const F_START_LEFT_POS       = 'start_left_pos';  
    const F_POSITION             = 'position';  
    const F_MOVE_TOP_POS_SIGN_A  = 'move_top_pos_sign_a';  
    const F_MOVE_TOP_POS_A       = 'move_top_pos_a';  
    const F_MOVE_LEFT_POS_SIGN_A = 'move_left_pos_sign_a';  
    const F_MOVE_LEFT_POS_A      = 'move_left_pos_a';  
    const F_MOVE_TOP_POS_SIGN_B  = 'move_top_pos_sign_b';  
    const F_MOVE_TOP_POS_B       = 'move_top_pos_b';  
    const F_MOVE_LEFT_POS_SIGN_B = 'move_left_pos_sign_b'; 
    const F_MOVE_LEFT_POS_B      = 'move_left_pos_b'; 
    const F_OPTIONS_A            = 'options_a'; 
    const F_OPTIONS_B            = 'options_b'; 
    const F_EXTRA_JS_A           = 'extra_js_a'; 
    const F_EXTRA_JS_B           = 'extra_js_b';     
    
    /* action array used to exclude ajax container */
    var $actiontypes = array('ACTION_MENU'   => self::ACTION_MENU    // form and list action
                            ,'ACTION_SAVE'   => self::ACTION_SAVE    // form action
                            ,'ACTION_CANCEL' => self::ACTION_CANCEL  // form action
                            ,'ACTION_NEW'    => self::ACTION_NEW     // list action
                            ,'ACTION_EDIT'   => self::ACTION_EDIT    // list action
                            );
                            
    /* jQuery common options array */
    var $jQueryoptions = array('JQUERY_HEIGHT'            => self::JQUERY_HEIGHT
                              ,'JQUERY_WITDH'             => self::JQUERY_WITDH
                              ,'JQUERY_OPACITY'           => self::JQUERY_OPACITY
                              ,'JQUERY_FONTSIZE'          => self::JQUERY_FONTSIZE
                              ,'JQUERY_MARGINTOP'         => self::JQUERY_MARGINTOP
                              ,'JQUERY_MARGINBOTTOM'      => self::JQUERY_MARGINBOTTOM
                              ,'JQUERY_MARGINLEFT'        => self::JQUERY_MARGINLEFT
                              ,'JQUERY_MARGINRIGHT'       => self::JQUERY_MARGINRIGHT
                              ,'JQUERY_PADDINGTOP'        => self::JQUERY_PADDINGTOP
                              ,'JQUERY_PADDINGBOTTOM'     => self::JQUERY_PADDINGBOTTOM
                              ,'JQUERY_PADDINGLEFT'       => self::JQUERY_PADDINGLEFT
                              ,'JQUERY_PADDINGRIGHT'      => self::JQUERY_PADDINGRIGHT
                              ,'JQUERY_BORDERWIDTH'       => self::JQUERY_BORDERWIDTH
                              ,'JQUERY_BORDERTOPWIDTH'    => self::JQUERY_BORDERTOPWIDTH
                              ,'JQUERY_BORDERBOTTOMWIDTH' => self::JQUERY_BORDERBOTTOMWIDTH
                              ,'JQUERY_BORDERIGHTWIDTH'   => self::JQUERY_BORDERIGHTWIDTH
                              ,'JQUERY_BORDERLEFTWIDTH'   => self::JQUERY_BORDERLEFTWIDTH
                              );
                
    /* XML MULTI-VERSION mapping values */
    var $twzmappingvalues = array(self::F_SECTION_ID           => 'AA' 
                                 ,self::F_STATUS               => 'BB'
                                 ,self::F_LAYER_ID             => 'CC'    
                                 ,self::F_START_DELAY          => 'DD'
                                 ,self::F_DURATION             => 'EE'
                                 ,self::F_START_TOP_POS_SIGN   => 'FF'
                                 ,self::F_START_TOP_POS        => 'GG'    
                                 ,self::F_START_LEFT_POS_SIGN  => 'HH'
                                 ,self::F_START_LEFT_POS       => 'II'
                                 ,self::F_POSITION             => 'JJ'
                                 ,self::F_MOVE_TOP_POS_SIGN_A  => 'KK'    
                                 ,self::F_MOVE_TOP_POS_A       => 'LL'
                                 ,self::F_MOVE_LEFT_POS_SIGN_A => 'MM'
                                 ,self::F_MOVE_LEFT_POS_A      => 'OO'
                                 ,self::F_MOVE_TOP_POS_SIGN_B  => 'PP'    
                                 ,self::F_MOVE_TOP_POS_B       => 'QQ'
                                 ,self::F_MOVE_LEFT_POS_SIGN_B => 'RR'
                                 ,self::F_MOVE_LEFT_POS_B      => 'SS'
                                 ,self::F_OPTIONS_A            => 'TT'
                                 ,self::F_OPTIONS_B            => 'UU'
                                 ,self::F_EXTRA_JS_A           => 'VV'
                                 ,self::F_EXTRA_JS_B           => 'WW'
                                 );
                                 
    /* class upload import path constants*/
    const IMPORT_PATH = '/includes/import/server/uploads/';
        
    function __construct(){
    
        global $wpdb;
        
        /* Twiz variable configuration */
        $this->pluginName = __('The Welcomizer', 'the-welcomizer');
        $this->pluginUrl  = WP_PLUGIN_URL.'/the-welcomizer';
        $this->pluginDir  = WP_PLUGIN_DIR.'/the-welcomizer';
        $this->table      = $wpdb->prefix .'the_welcomizer';
        $this->version    = 'v1.3.3.9';
        $this->dbVersion  = 'v1.1.1';
        $this->logoUrl    = '/images/twiz-logo.png';
        $this->logobigUrl = '/images/twiz-logo-big.png';
        $this->nonce      = wp_create_nonce('twiz-nonce');
    }
    
    function twizIt(){

        $html = '<div id="twiz_plugin">';
        $html.= '<div id="twiz_background"></div>';
        $html.= '<div id="twiz_master">';
        $html.= $this->getHtmlGlobalstatus(); // private 
        $html.= $this->getAjaxHeader();       // private 
        $html.= $this->getHtmlHeader();       // private 
        $html.= $this->getHtmlMenu();         // private 
        $html.= $this->getHtmlList();
        $html.= $this->getHtmlFooter();       // private 
        $html.= $this->getHtmlImportExport(); // private 
        $html.= '</div>';
        $html.= '<div id="twiz_right_panel"></div>';
        $html.= '</div>'; 
        
        return $html;
    }
    
    function addSectionMenu( $section_id = '' ){
        
        if($section_id==''){return '';}
            
        $sections = get_option('twiz_sections');
        
        $section_name = $this->getSectionName($section_id);
        
        $sections[$section_name] = '';
        $sections[$section_name] = $section_id;
    
        update_option('twiz_sections', $sections);
        
        $html = $this->getHtmlSectionMenu($section_id, $section_name);
        
        return $html;
    }    
    
    private function getHtmlAddSection(){
    
        global $wpdb;
 
        $sections = get_option('twiz_sections');
  
        $addsection = '<div id="twiz_add_sections">';
        
        $select = '<select name="twiz_slc_sections" id="twiz_slc_sections">';
        
        $select .= '<option value="" selected="selected">'.__('Choose', 'the-welcomizer').'</option>';
        
        /* get categories */
        $categories = get_categories('sort_order=asc'); 
 
        foreach($categories as $value){
        
            if(!in_array('c_'.$value->cat_ID, $sections)){
            
                $select_cat .= '<option value="c_'.$value->cat_ID.'">'.$value->cat_name.'</option>';
            }
        }
        
        /* get pages */
        $pages = get_pages('sort_order=asc'); 
        
        foreach($pages as $value){
        
            if(!in_array('p_'.$value->ID, $sections)){
            
                $separator = '<option value="+++ +++ +++">+++ +++ +++</option>';
               
                $select_page .= '<option value="p_'.$value->ID.'">'.$value->post_title.'</option>';
            }
        }
        
        /* close select */
        $addsection .=  $select.$select_cat.$separator.$select_page.'</select>';

        $addsection .= '<input type="button" name="twiz_save_section" id="twiz_save_section" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /> <a name="twiz_cancel_section" id="twiz_cancel_section">'.__('Cancel', 'the-welcomizer').'</a>';
        
        $addsection .='</div>';
        
        return $addsection;
    }
    
    private function getHtmlGlobalstatus(){
    
        return '<div id="twiz_global_status">'.$this->getImgGlobalStatus().'</div>';
    }
    
    private function getHtmlHeader(){
    
        $header = '<div id="twiz_header">
<div id="twiz_head_logo"><img src="'.$this->pluginUrl.$this->logoUrl.'"/></div>
<span id="twiz_head_title">'.$this->pluginName.'</span><br>
<span id="twiz_head_version"><a href="http://wordpress.org/extend/plugins/the-welcomizer/" target="_blank">'.$this->version.'</a></span> 
<span id="twiz_head_addnew"><a class="button-secondary" id="twiz_new" name="twiz_new">'.__('Add New', 'the-welcomizer').'</a></span></div><div class="twiz-clear"></div>
    ';
        
        return $header;
    }
    
    private function getHtmlImportExport(){
    
      $import = '<div id="twiz_import_container">'.__('Import').'</div>';
      $export = '<div id="twiz_export">'.__('Export').'</div>';
      
      $html = '<div id="twiz_import_export">'.$import.$export.'</div>';
      
      return $html;
      
    }
    
    private function getHtmlFooter(){

        $footer = '
<div id="twiz_footer">
'.__('Developed by', 'the-welcomizer').' <a href="http://www.sebastien-laframboise.com" target="_blank">'.utf8_encode('Sébastien Laframboise').'</a>. '.__('Licensed under the GPL version 2.0', 'the-welcomizer').'</div>';
        
        return $footer;
    }    
    
    private function getHtmlMenu(){
    
           /* retrieve stored sections */
           $sections = get_option('twiz_sections');
    
           $menu = '
<div id="twiz_menu">';
           
           /* default home section */
           $menu .= '<div id="twiz_menu_home" class="twiz-menu twiz-menu-selected">'.__('Home').'</div>';
           
           /* generate the section menu */
           foreach($sections as $key => $value){
           
                $name = $this->getSectionName($value, $key);
                
                $menu .= $this->getHtmlSectionMenu( $value, $name );
           }
           
           $menu .= '<div id="twiz_add_menu">+</div>';
 
           $menu .= $this->getHtmlAddSection(); // private
 
           $menu .= '
</div><div class="twiz-clear"></div>';
        
        return $menu;
        
    }

    private function getAjaxHeader(){
    
        $header = '
<script src="'.$this->pluginUrl.'/includes/import/client/fileuploader.js" type="text/javascript"></script>
<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
 var twiz_hide_MessageDelay = 1234;
 var twiz_view_id = null;
 var twiz_current_section_id = "home";
 var twiz_array_view_id = new Array();
 var uploader = new qq.FileUploader({
        element: document.getElementById("twiz_import_container"),
        action: "'.$this->pluginUrl.'/includes/import/server/php.php",
        debug: false,
        allowedExtensions: ["twz"],
        sizeLimit: 8388608, // max size   
        minSizeLimit: 1, // min size
        onSubmit: function (){ uploader.setParams({ twiz_nonce: "'.$this->nonce.'", twiz_action: "'.self::ACTION_IMPORT.'", twiz_section_id: twiz_current_section_id }); },
        onComplete: function (){postMenu();}
 });    
 var bind_twiz_New = function() {
    $("#twiz_new").click(function(){
     twiz_view_id = "edit";
     $(this).fadeOut("slow");
     $("#twiz_container").fadeOut("slow");
        $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.self::ACTION_NEW.'"
        }, function(data) {
            $("#twiz_container").html(data);
            $("#twiz_container").fadeIn("fast");
            twiz_view_id = null;
            bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
            bind_twiz_More_Options();bind_twiz_Choose_FromId();bind_twiz_Choose_Options();
            bind_twiz_DynArrows();
        });
    });
 }
 var bind_twiz_Status = function() {
    $("img[name^=twiz_status]").mouseover(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(12,textid.length);
        if((twiz_view_id != numid)&&(twiz_view_id!="edit")&&(numid!="global")){
            twiz_view_id = numid;
            $("#twiz_right_panel").html("<div class=\"twiz-panel-loading\"><img src=\"'.$this->pluginUrl.'/images/twiz-big-loading.gif\"></div>");
            $("#twiz_right_panel").fadeIn("slow");    
            if(twiz_array_view_id[numid]==undefined){
                $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.self::ACTION_VIEW.'",
                "twiz_id": numid
                }, function(data) {
                    $("#twiz_right_panel").html(data);
                    twiz_array_view_id[numid] = data;
                });    
            }else{
                $("#twiz_right_panel").html(twiz_array_view_id[numid]);
            }
        }
    });   
    $("img[name^=twiz_status]").click(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(12,textid.length);
        if(numid!="global"){
            $(this).hide();
            $("#twiz_img_status_" + numid).fadeIn("slow");        
            $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.self::ACTION_STATUS.'",
            "twiz_id": numid
            }, function(data) {
                $("#twiz_td_status_" + numid).html(data);
                $("img[name^=twiz_status]").unbind("click");
                twiz_array_view_id[numid]=undefined;
                twiz_view_id = null;
                if((twiz_view_id != numid)&&(twiz_view_id!="edit")){
                    twiz_view_id = numid;
                    if(twiz_array_view_id[numid]==undefined){
                        $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
                        "twiz_nonce": "'.$this->nonce.'", 
                        "twiz_action": "'.self::ACTION_VIEW.'",
                        "twiz_id": numid
                        }, function(data) {
                            $("#twiz_right_panel").html(data);
                            twiz_array_view_id[numid] = data;
                        });    
                    }else{
                        $("#twiz_right_panel").html(twiz_array_view_id[numid]);
                    }
                }                
                bind_twiz_Status();
            });
        }else{
            $(this).hide();
            $("#twiz_img_status_global").fadeIn("slow");        
            $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.self::ACTION_GLOBAL_STATUS.'"
            }, function(data) {
                $("#twiz_global_status").html(data);
                $("img[name^=twiz_status]").unbind("click");
                bind_twiz_Status();
            });
        }
    });
 }
 var bind_twiz_Edit = function() {
    $("img[name^=twiz_edit]").mouseover(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(10,textid.length);
        if((twiz_view_id != numid)&&(twiz_view_id!="edit")){
            twiz_view_id = numid;
            $("#twiz_right_panel").html("<div class=\"twiz-panel-loading\"><img src=\"'.$this->pluginUrl.'/images/twiz-big-loading.gif\"></div>");
            $("#twiz_right_panel").fadeIn("slow");    
            if(twiz_array_view_id[numid]==undefined){
                $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.self::ACTION_VIEW.'",
                "twiz_id": numid
                }, function(data) {
                    $("#twiz_right_panel").html(data);
                    twiz_array_view_id[numid] = data;
                });    
            }else{
                $("#twiz_right_panel").html(twiz_array_view_id[numid]);
            }
        }
    });   
    $("img[name^=twiz_edit]").click(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(10,textid.length);
        twiz_view_id = "edit";
        $(this).hide();
        $("#twiz_img_edit_" + numid).fadeIn("slow");    
        $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.self::ACTION_EDIT.'",
        "twiz_id": numid
        }, function(data) {
            $("#twiz_container").html(data);
            twiz_view_id = null;
            $("#twiz_container").show("fast");
            $("img[name^=twiz_status]").unbind("click");
            $("img[name^=twiz_edit]").unbind("click");
            $("img[name^=twiz_edit]").unbind("mouseover");
            bind_twiz_Status();bind_twiz_Save();bind_twiz_Cancel();bind_twiz_Number_Restriction();
            bind_twiz_More_Options();bind_twiz_Choose_FromId();bind_twiz_Choose_Options();
            bind_twiz_DynArrows();
        });
    });
 }
 var bind_twiz_Delete = function() {
    $("img[name^=twiz_delete]").mouseover(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(12,textid.length);
        if((twiz_view_id != numid)&&(twiz_view_id!="edit")){
            twiz_view_id = numid;        
            $("#twiz_right_panel").html("<div class=\"twiz-panel-loading\"><img src=\"'.$this->pluginUrl.'/images/twiz-big-loading.gif\"></div>");
            $("#twiz_right_panel").fadeIn("slow");    
            if(twiz_array_view_id[numid]==undefined){
                $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.self::ACTION_VIEW.'",
                "twiz_id": numid}, 
                function(data) {
                    $("#twiz_right_panel").html(data);
                    twiz_array_view_id[numid] = data;
                });    
            }else{
                $("#twiz_right_panel").html(twiz_array_view_id[numid]);
            }
        }
    });  
    $("img[name^=twiz_delete]").click(function(){
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            var textid = $(this).attr("name");
            var numid = textid.substring(12,textid.length);    
            $(this).hide();
            $("#twiz_img_delete_" + numid).fadeIn("slow");
            $("#twiz_right_panel").fadeOut("slow");
            $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.self::ACTION_DELETE.'",
             "twiz_id": numid 
             }, function(data) {        
                 $("#twiz_list_tr_" + numid).fadeOut();
                 twiz_array_view_id[numid] = undefined;
            });
        }
   });
 }
 var bind_twiz_Cancel = function() {
    $("#twiz_cancel").click(function(){
    $("#twiz_container").fadeOut("slow");
    $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
    "twiz_nonce": "'.$this->nonce.'", 
    "twiz_action": "'.self::ACTION_CANCEL.'",
    "twiz_section_id": twiz_current_section_id
    }, function(data) {
        $("#twiz_container").fadeIn("fast");
        $("#twiz_container").html(data);
        twiz_view_id = "";
        $("img[name^=twiz_status]").unbind("click");
        $("img[name^=twiz_cancel]").unbind("click");
        $("img[name^=twiz_save]").unbind("click");
        bind_twiz_Status();bind_twiz_Delete();bind_twiz_Edit();bind_twiz_DynArrows();
        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
        bind_twiz_More_Options();bind_twiz_Choose_FromId();bind_twiz_Choose_Options();
        bind_twiz_Ajax_TD();bind_twiz_TR_View();
    });
   });
 }
 var bind_twiz_Save = function() {
    $("#twiz_save").click(function(){
    $("#twiz_save").attr({"disabled" : "true"});
    $("#twiz_save_img").fadeIn("slow");
    var numid = $("#twiz_id").val();
    $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
         "twiz_nonce": "'.$this->nonce.'", 
         "twiz_action": "'.self::ACTION_SAVE.'",
         "twiz_section_id": twiz_current_section_id, 
         "twiz_id": numid,
         "twiz_status": $("#twiz_status").is(":checked"),
         "twiz_layer_id": $("#twiz_layer_id").val(),
         "twiz_start_delay": $("#twiz_start_delay").val(),
         "twiz_duration": $("#twiz_duration").val(),
         "twiz_move_top_pos_sign_a": $("#twiz_move_top_pos_sign_a").val(),
         "twiz_move_top_pos_a": $("#twiz_move_top_pos_a").val(),
         "twiz_move_left_pos_sign_a": $("#twiz_move_left_pos_sign_a").val(),
         "twiz_move_left_pos_a": $("#twiz_move_left_pos_a").val(),
         "twiz_move_top_pos_sign_b": $("#twiz_move_top_pos_sign_b").val(),
         "twiz_move_top_pos_b": $("#twiz_move_top_pos_b").val(),
         "twiz_move_left_pos_sign_b": $("#twiz_move_left_pos_sign_b").val(),         
         "twiz_move_left_pos_b": $("#twiz_move_left_pos_b").val(),         
         "twiz_options_a": $("#twiz_options_a").val(),
         "twiz_options_b": $("#twiz_options_b").val(),
         "twiz_extra_js_a": $("#twiz_extra_js_a").val(),
         "twiz_extra_js_b": $("#twiz_extra_js_b").val(),         
         "twiz_start_top_pos_sign": $("#twiz_start_top_pos_sign").val(),
         "twiz_start_top_pos": $("#twiz_start_top_pos").val(),
         "twiz_start_left_pos_sign": $("#twiz_start_left_pos_sign").val(),         
         "twiz_start_left_pos": $("#twiz_start_left_pos").val(),         
         "twiz_position": $("#twiz_position").val()
        }, function(data) {
        $("#twiz_container").html(data);
        $("img[name^=twiz_status]").unbind("click");
        $("img[name^=twiz_cancel]").unbind("click");
        $("img[name^=twiz_save]").unbind("click");
        twiz_array_view_id[numid] = undefined;
        bind_twiz_Status();bind_twiz_Delete();bind_twiz_Edit();
        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
        bind_twiz_More_Options();bind_twiz_Choose_FromId();bind_twiz_Choose_Options();
        bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();
        $("#twiz_list_tr_" + numid).animate({opacity:0}, 320); // needs a rebind for add new
        $("#twiz_list_tr_" + numid).animate({opacity:1}, 320); // needs a rebind for add new
        $("#twiz_list_tr_" + numid).animate({opacity:0}, 300); // needs a rebind for add new
        $("#twiz_list_tr_" + numid).animate({opacity:1}, 300); // needs a rebind for add new
        setTimeout(function(){
            $("#twiz_messagebox").hide("slow");
        }, twiz_hide_MessageDelay);    
    });
   });
  }
  var bind_twiz_Number_Restriction = function() {
    $("input[name^=twiz_input]").keypress(function (e){
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}}); 
    $("#twiz_start_delay").keypress(function (e){
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}}); 
    $("#twiz_duration").keypress(function (e){
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_start_top_pos").keypress(function (e)
    {if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_start_left_pos").keypress(function (e)
    {if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_end_top_pos").keypress(function (e)
    {if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_end_left_pos").keypress(function (e)
    {if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_move_top_pos_a").keypress(function (e)
    {if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_move_left_pos_a").keypress(function (e)
    {if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_move_top_pos_b").keypress(function (e)
    {if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_move_left_pos_b").keypress(function (e)
    {if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
  }
  var bind_twiz_More_Options = function() {
    $(".twiz-more-options").click(function(){
        $(".twiz-table-more-options").toggle();
    });
  }
  var bind_twiz_Choose_FromId = function() {
    $("#twiz_choose_fromId").click(function(){
        $("#twiz_td_full_chooseid").html(\'<img src="'.$this->pluginUrl.'/images/twiz-loading.gif">\');
        $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", { 
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.self::ACTION_ID_LIST.'"
        }, function(data) {
        $("#twiz_td_full_chooseid").html(data);
        bind_twiz_Select_Id();
        });
    });
  }    
  var bind_twiz_Select_Id = function() {
    $("#twiz_slc_id").change(function(){
        $("#twiz_layer_id").attr({"value" : $(this).val()});
    });
  }    
  var bind_twiz_Choose_Options = function() {
    $("a[name^=twiz_choose_options]").click(function(){
        var textid = $(this).attr("name");
        var charid = textid.substring(20,textid.length);
        $("#twiz_td_full_option_" + charid).html(\'<img src="'.$this->pluginUrl.'/images/twiz-loading.gif">\');
        $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", { 
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.self::ACTION_OPTIONS.'",
        "twiz_charid": charid
        }, function(data) {
        $("#twiz_td_full_option_" + charid).html(data);
        bind_twiz_Select_Options(charid);
        });
    });
  }    
  var bind_twiz_Select_Options = function(charid) {
    $("#twiz_slc_options_" + charid).change(function(){
        var curval = $("#twiz_options_" + charid).val();
        if($(this).val()!=""){
            if(curval!=""){ var curval = curval + "\n";}
            var optionstring =  $(this).val() + ":\"\"";
            $("#twiz_options_" + charid).attr({"value" : curval + optionstring}) 
        }
    });    
  }    
  var bind_twiz_Ajax_TD = function() {
    $("div[id^=twiz_ajax_td_val]").mouseover(function(){
            $(this).attr({"title" : "'.__('Edit', 'the-welcomizer').'"});
            $(this).css({cursor:"pointer"});
    });        
    $("input[name^=twiz_input]").blur(function (e){
        var textid = $(this).attr("name");
        var columnName = textid.substring(11,16);
        switch(columnName){
            case "delay":        
                var numid = textid.substring(17,textid.length);
                break;
            case "durat": 
                columnName = "duration";
                var numid = textid.substring(20,textid.length);         
                break;  
        }
        var txtval = $("#twiz_ajax_td_val_" + columnName + "_" + numid).html();
        $("#twiz_ajax_td_edit_" + columnName + "_" + numid).hide();
        $("#twiz_input_duration" + columnName + "_" + numid).attr({"value" : txtval});
        $("#twiz_ajax_td_val_" + columnName + "_" + numid).fadeIn("fast");   
    });
    $("input[name^=twiz_input]").keypress(function (e){
        var textid = $(this).attr("name");
        var columnName = textid.substring(11,16);
        switch(columnName){
            case "delay": 
                var numid = textid.substring(17,textid.length);
                break;
            case "durat":
                columnName = "duration";
                var numid = textid.substring(20,textid.length);
                break;
        }
        switch(e.keyCode){
            case 27:
                $("#twiz_ajax_td_edit_" + columnName + "_" + numid).hide();
                $("#twiz_ajax_td_val_" + columnName + "_" + numid).fadeIn("fast");
            break;
            case 13:
                var txtval = $("#twiz_input_" + columnName + "_" + numid).val();
                 $("#twiz_ajax_td_edit_" + columnName + "_" + numid).hide();
                 $("#twiz_ajax_td_val_" + columnName + "_" + numid).html("");
                 $("#twiz_ajax_td_val_" + columnName + "_" + numid).hide();
                 $("#twiz_ajax_td_loading_" + columnName + "_" + numid).html(\'<img name="twiz_img_loading_delay_\' + numid + \'[]" id="twiz_img_loading_delay_\' + numid + \'[]" src="'.$this->pluginUrl.'/images/twiz-loading.gif">\');
                $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", { 
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.self::ACTION_EDIT_TD.'",
                "twiz_id": numid, 
                "twiz_column": columnName, 
                "twiz_value": txtval
                }, function(data) {
                    $("#twiz_ajax_td_loading_" + columnName + "_" + numid).html("");
                    $("#twiz_ajax_td_val_" + columnName + "_" + numid).html(data);
                    $("#twiz_ajax_td_val_" + columnName + "_" + numid).fadeIn("fast");
                    $("#twiz_ajax_td_val_" + columnName + "_" + numid).css({color:"green"});
                    $("input[name^=twiz_input_delay]").unbind("keypress");
                    $("div[id^=twiz_ajax_td_val]").unbind("click");
                    $("input[name^=twiz_input_delay]").unbind("blur");
                    $("div[id^=twiz_ajax_td_val_delay]").unbind("mouseover");
                    bind_twiz_Ajax_TD();bind_twiz_TR_View();
                });
            break;
        }            
    });
    $("div[id^=twiz_ajax_td_val]").click(function(){
        var textid = $(this).attr("id");
        var columnName = textid.substring(17,22);
        switch(columnName){
            case "delay": 
                var numid = textid.substring(23,textid.length);
                break;
            case "durat":
                columnName = "duration";            
                var numid = textid.substring(26,textid.length);
                break;
        }        
        $("#twiz_ajax_td_val_" + columnName + "_" + numid).hide();
        $("#twiz_ajax_td_edit_" + columnName + "_" + numid).fadeIn("fast");
        $("#twiz_input_" + columnName + "_" + numid).focus();
    });
  }
  var bind_twiz_TR_View = function() {
    $("tr[name^=twiz_list_tr]").mouseover(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(13,textid.length);
        if((twiz_view_id != numid)&&(twiz_view_id!="edit")){
            twiz_view_id = numid;
            $("#twiz_right_panel").html("<div class=\"twiz-panel-loading\"><img src=\"'.$this->pluginUrl.'/images/twiz-big-loading.gif\"></div>");
            $("#twiz_right_panel").fadeIn("fast");    
            if(twiz_array_view_id[numid]==undefined){
                $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.self::ACTION_VIEW.'",
                "twiz_id": numid
                }, function(data) {
                    $("#twiz_right_panel").html(data);
                    twiz_array_view_id[numid] = data;
                });    
            }else{
                $("#twiz_right_panel").html(twiz_array_view_id[numid]);
            }
        }
    });
  } 
  var bind_twiz_Menu = function() {
    $("#twiz_cancel_section").click(function(){
        $("#twiz_add_sections").slideToggle("fast");  
    });
    $("#twiz_add_menu").click(function(){    
        $("#twiz_add_sections").slideToggle("fast");  
        $("input[id=twiz_save_section]").removeAttr("disabled");
        twiz_view_id = null;
    });
    $("div[id^=twiz_menu_]").click(function(){
        var textid = $(this).attr("id");
        twiz_current_section_id = textid.substring(10,textid.length);
        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu"});
        $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
        $("#qq_upload_list li").remove(); 
       postMenu();
    });
  }    
  function postMenu(){
   $("#twiz_container").slideToggle("fast"); 
   $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.self::ACTION_MENU.'",
            "twiz_section_id": twiz_current_section_id
            }, function(data) {
                $("#twiz_container").html(data);
                $("#twiz_container").slideToggle("slow");  
                twiz_view_id = null;
                twiz_array_view_id = new Array();
                $("img[name^=twiz_status]").unbind("click");
                $("img[name^=twiz_cancel]").unbind("click");
                $("img[name^=twiz_save]").unbind("click");
                bind_twiz_Status();bind_twiz_Delete();bind_twiz_Edit();
                bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
                bind_twiz_More_Options();bind_twiz_Choose_FromId();bind_twiz_Choose_Options();
                bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();           
            });
  }
  var bind_twiz_Save_Section = function() {
    $("#twiz_save_section").click(function(){
        var sectionid = $("#twiz_slc_sections").val();
        if((sectionid != "")&&(sectionid != "+++ +++ +++")){
            $("input[id=twiz_save_section]").attr({"disabled" : "true"});
            $("#twiz_add_sections").hide();
            $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
                 "twiz_nonce": "'.$this->nonce.'", 
                 "twiz_action": "'.self::ACTION_ADD_SECTION.'",
                 "twiz_section_id": sectionid
                }, function(data) {
                    $("#twiz_add_menu").before(data);
                    $("#twiz_container").fadeOut("slow");   
                    $.post("'.$this->pluginUrl.'/twiz-ajax.php'.'", {
                    "twiz_nonce": "'.$this->nonce.'", 
                    "twiz_action": "'.self::ACTION_MENU.'",
                    "twiz_section_id": sectionid
                    }, function(data) {
                        twiz_current_section_id = sectionid; 
                        $("#twiz_slc_sections option[value=\'" + sectionid + "\']").remove();
                        $("#twiz_slc_sections").val("");
                        $("#twiz_container").html(data);
                        $("#twiz_container").slideToggle("slow");  
                        $("div[id^=twiz_menu_]").unbind("click");
                        $("#twiz_add_menu").unbind("click");
                        $("#twiz_cancel_section").unbind("click");
                        bind_twiz_Status();bind_twiz_Delete();bind_twiz_Edit();
                        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
                        bind_twiz_More_Options();bind_twiz_Choose_FromId();bind_twiz_Choose_Options();
                        bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Menu();
                        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu"});
                        $("#twiz_menu_" + sectionid).attr({"class" : "twiz-menu twiz-menu-selected"});
                    });  
                });
        }
    });
  }  
  var bind_twiz_DynArrows = function() {
   $("select[id^=twiz_move_left_pos_sign_a]").change(function(){changeDirectionImage("a");});  
   $("select[id^=twiz_move_top_pos_sign_a]").change(function(){changeDirectionImage("a");});   
   $("input[name^=twiz_move_top_pos_a]").blur(function(){changeDirectionImage("a");});
   $("input[name^=twiz_move_left_pos_a]").blur(function(){changeDirectionImage("a");}); 
   $("select[id^=twiz_move_left_pos_sign_b]").change(function(){changeDirectionImage("b");});
   $("select[id^=twiz_move_top_pos_sign_b]").change(function(){changeDirectionImage("b");});
   $("input[name^=twiz_move_top_pos_b]").blur(function(){changeDirectionImage("b");});
   $("input[name^=twiz_move_left_pos_b]").blur(function(){changeDirectionImage("b");});    
   function changeDirectionImage(ab) {
      var top_sign  = $("#twiz_move_top_pos_sign_" + ab).val();
      var top_val   = $("#twiz_move_top_pos_" + ab).val();
      var left_sign = $("#twiz_move_left_pos_sign_" + ab).val();
      var left_val  = $("#twiz_move_left_pos_" + ab).val();
      var direction = "";
      var htmlimage = "";
      switch(true){
         case ((top_val!="")&&(top_sign=="-")&&(left_val=="")): 
            direction = "'.self::DIMAGE_N.'";
            break;
         case ((top_val!="")&&(top_sign=="-")&&(left_val!="")&&(left_sign=="+")):
            direction = "'.self::DIMAGE_NE.'";
            break;        
         case ((top_val=="")&&(left_val!="")&&(left_sign=="+")): 
            direction = "'.self::DIMAGE_E.'";
            break;         
         case ((top_val!="")&&(top_sign=="+")&&(left_val!="")&&(left_sign=="+")): 
            direction = "'.self::DIMAGE_SE.'";    
            break;     
         case ((top_val!="")&&(top_sign=="+")&&(left_val=="")): 
            direction = "'.self::DIMAGE_S.'";    
            break; 
         case ((top_val!="")&&(top_sign=="+")&&(left_val!="")&&(left_sign=="-")): 
            direction = "'.self::DIMAGE_SW.'";    
            break;    
         case ((top_val=="")&&(left_val!="")&&(left_sign=="-")): 
            direction = "'.self::DIMAGE_W.'";    
            break;          
         case ((top_val!="")&&(top_sign=="-")&&(left_val!="")&&(left_sign=="-")): 
            direction = "'.self::DIMAGE_NW.'";    
            break;           
      }
      if(direction!=""){ 
          htmlimage = "<img width=\"45\" height=\"45\" src=\"'.$this->pluginUrl.'/images/twiz-arrow-" + direction + ".png\">";
      }
      $("#twiz_td_arrow_" + ab).html(htmlimage);
    }
  }
  var bind_twiz_ImportExport = function() {
    $("#twiz_export").click(function(){
          var sectionid = $("#twiz_slc_sections").val();
          var superiframe = document.createElement("iframe");
          superiframe.src = "'.$this->pluginUrl.'/twiz-ajax.php?twiz_nonce='.$this->nonce.'&twiz_action='.self::ACTION_EXPORT.'&twiz_section_id=" + twiz_current_section_id;
          superiframe.style.display = "none";
          document.body.appendChild(superiframe); 
    });   
  }  
  $("#twiz_import_export").mouseover(function(){
     $("#twiz_right_panel").fadeOut("fast");   
     twiz_view_id = null;
  });    
  $("#twiz_footer").mouseover(function(){
     $("#twiz_right_panel").fadeOut("fast");   
     twiz_view_id = null;
  });  
  $("#twiz_header").mouseover(function(){
     $("#twiz_right_panel").fadeOut("fast");   
     twiz_view_id = null;
  });    
  $("#twiz_menu").mouseover(function(){   
     $("#twiz_right_panel").fadeOut("fast");   
     twiz_view_id = null;
  });     
  bind_twiz_Status();bind_twiz_Delete();bind_twiz_New();bind_twiz_Edit();
  bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
  bind_twiz_More_Options();bind_twiz_Choose_FromId();bind_twiz_Choose_Options();
  bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Menu();
  bind_twiz_Save_Section();bind_twiz_ImportExport();
  $("#twiz_container").slideToggle("slow");
 });
 //]]>
</script>';
       return $header;
    }
     
    private function createHtmlList( $listarray = array() ){ 
    
        if(count($listarray)==0){return false;}
    
        $opendiv = '';
        $closediv = '';
        $rowcolor = '';
        
        /* ajax container */ 
        if(!in_array($_POST['twiz_action'], $this->actiontypes)){
        
            $opendiv = '<div id="twiz_container">';
            $closediv = '</div>';
        }
        
        /* show element */
        $jsscript_show = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
        $("#twiz_new").fadeIn("slow");
        $("#twiz_add_menu").fadeIn("slow");
        $("#twiz_import").fadeIn("slow");
        $("#twiz_export").fadeIn("slow");
  });
 //]]>
</script>';

        $htmllist = $opendiv.'<table class="twiz-table-list" cellspacing="0">';
        
        $htmllist.= '<tr class="twiz-table-list-tr-h twiz-td-center"><td class="twiz-table-list-td-h">'.__('Status', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-left" nowrap>'.__('Element ID', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-right twiz-td-delay" nowrap><b>&#8681;</b> '.__('Delay', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-right twiz-td-duration" nowrap>'.__('Duration', 'the-welcomizer').'</td><td class="twiz-table-list-td-h  twiz-td-action" nowrap>'.__('Action', 'the-welcomizer').'</td></tr>';
        
        foreach($listarray as $value){
            
            $rowcolor= ($rowcolor=='twiz-row-color-1') ?'twiz-row-color-2' : 'twiz-row-color-1';
            
            $statushtmlimg = ($value[self::F_STATUS]=='1') ? $this->getHtmlImgStatus($value[self::F_ID], 'active') : $this->getHtmlImgStatus($value[self::F_ID], 'inactive');
            
            /* add a '2x' to the duration if necessary */
            $duration = $this->formatDuration($value[self::F_ID], $value);

            /* the table row */
            $htmllist.= '
    <tr class="'.$rowcolor.'" name="twiz_list_tr_'.$value[self::F_ID].'" id="twiz_list_tr_'.$value[self::F_ID].'" ><td class="twiz-td-center" id="twiz_td_status_'.$value[self::F_ID].'">'.$statushtmlimg.'</td><td class="twiz-td-left">'.$value[self::F_LAYER_ID].'</td><td class="twiz-td-delay twiz-td-right"><div id="twiz_ajax_td_val_delay_'.$value[self::F_ID].'">'.$value[self::F_START_DELAY].'</div><div id="twiz_ajax_td_loading_delay_'.$value[self::F_ID].'" name="twiz_ajax_td_loading_delay_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_delay_'.$value[self::F_ID].'" name="twiz_ajax_td_edit_delay_'.$value[self::F_ID].'" class="twiz_ajax_td_edit"><input type="text" name="twiz_input_delay_'.$value[self::F_ID].'" id="twiz_input_delay_'.$value[self::F_ID].'" value="'.$value[self::F_START_DELAY].'" maxlength="5"></div></td><td name="twiz_ajax_td_duration_'.$value[self::F_ID].'" id="twiz_ajax_td_duration_'.$value[self::F_ID].'"  class="twiz-td-right twiz-td-duration" nowrap><div id="twiz_ajax_td_val_duration_'.$value[self::F_ID].'">'.$duration.'</div><div id="twiz_ajax_td_loading_duration_'.$value[self::F_ID].'" name="twiz_ajax_td_loading_duration_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_duration_'.$value[self::F_ID].'" name="twiz_ajax_td_edit_duration_'.$value[self::F_ID].'" class="twiz_ajax_td_edit"><input type="text" name="twiz_input_duration_'.$value[self::F_ID].'" id="twiz_input_duration_'.$value[self::F_ID].'" value="'.$value[self::F_DURATION].'" maxlength="5"></div></td><td class="twiz-td-right" nowrap><img  src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_img_edit_'.$value[self::F_ID].'" name="twiz_img_edit_'.$value[self::F_ID].'" class="twiz-loading-gif"><img id="twiz_edit_'.$value[self::F_ID].'" name="twiz_edit_'.$value[self::F_ID].'" alt="'.__('Edit', 'the-welcomizer').'" title="'.__('Edit', 'the-welcomizer').'" src="'.$this->pluginUrl.'/images/twiz-edit.gif" height="25"/> <img height="25" src="'.$this->pluginUrl.'/images/twiz-delete.gif" id="twiz_delete_'.$value[self::F_ID].'" name="twiz_delete_'.$value[self::F_ID].'" alt="'.__('Delete', 'the-welcomizer').'" title="'.__('Delete', 'the-welcomizer').'"/><img class="twiz-loading-gif" src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_img_delete_'.$value[self::F_ID].'" name="twiz_img_delete_'.$value[self::F_ID].'"></td></tr>';
         
         }
         
         $htmllist.= '</table>'.$closediv.$jsscript_show;
         
         return $htmllist;
    }
    
    function export( $section_id = '' ){
    
        $where = ($section_id != '') ? " where ".self::F_SECTION_ID." = '".$section_id."'" : " where ".self::F_SECTION_ID." = '".self::SECTION_DEFAULT."'";
      
        $listarray = $this->getListArray( $where ); // get all the data
        
        $filedata = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        
        $filedata .= '<TWIZ>'."\n";

        foreach($listarray as $value){
     
              if ($sectionname == '') {
              
                  $sectionname = sanitize_title_with_dashes($this->getSectionName($value[self::F_SECTION_ID]));
              }
              
              $filedata .= '<ROW>'."\n";
              
              $filedata .= '<'.$this->twzmappingvalues[self::F_STATUS].'>'.$value[self::F_STATUS].'</'.$this->twzmappingvalues[self::F_STATUS].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_LAYER_ID].'>'.$value[self::F_LAYER_ID].'</'.$this->twzmappingvalues[self::F_LAYER_ID].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_START_DELAY].'>'.$value[self::F_START_DELAY].'</'.$this->twzmappingvalues[self::F_START_DELAY].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_DURATION].'>'.$value[self::F_DURATION].'</'.$this->twzmappingvalues[self::F_DURATION].'>'."\n";;
              $filedata .= '<'.$this->twzmappingvalues[self::F_START_TOP_POS_SIGN].'>'.$value[self::F_START_TOP_POS_SIGN].'</'.$this->twzmappingvalues[self::F_START_TOP_POS_SIGN].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_START_TOP_POS].'>'.$value[self::F_START_TOP_POS].'</'.$this->twzmappingvalues[self::F_START_TOP_POS].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_START_LEFT_POS_SIGN].'>'.$value[self::F_START_LEFT_POS_SIGN].'</'.$this->twzmappingvalues[self::F_START_LEFT_POS_SIGN].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_START_LEFT_POS].'>'.$value[self::F_START_LEFT_POS].'</'.$this->twzmappingvalues[self::F_START_LEFT_POS].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_POSITION].'>'.$value[self::F_POSITION].'</'.$this->twzmappingvalues[self::F_POSITION].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_MOVE_TOP_POS_SIGN_A].'>'.$value[self::F_MOVE_TOP_POS_SIGN_A].'</'.$this->twzmappingvalues[self::F_MOVE_TOP_POS_SIGN_A].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_MOVE_TOP_POS_A].'>'.$value[self::F_MOVE_TOP_POS_A].'</'.$this->twzmappingvalues[self::F_MOVE_TOP_POS_A].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_MOVE_LEFT_POS_SIGN_A].'>'.$value[self::F_MOVE_LEFT_POS_SIGN_A].'</'.$this->twzmappingvalues[self::F_MOVE_LEFT_POS_SIGN_A].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_MOVE_LEFT_POS_A].'>'.$value[self::F_MOVE_LEFT_POS_A].'</'.$this->twzmappingvalues[self::F_MOVE_LEFT_POS_A].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_MOVE_TOP_POS_SIGN_B].'>'.$value[self::F_MOVE_TOP_POS_SIGN_B].'</'.$this->twzmappingvalues[self::F_MOVE_TOP_POS_SIGN_B].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_MOVE_TOP_POS_B].'>'.$value[self::F_MOVE_TOP_POS_B].'</'.$this->twzmappingvalues[self::F_MOVE_TOP_POS_B].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_MOVE_LEFT_POS_SIGN_B].'>'.$value[self::F_MOVE_LEFT_POS_SIGN_B].'</'.$this->twzmappingvalues[self::F_MOVE_LEFT_POS_SIGN_B].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_MOVE_LEFT_POS_B].'>'.$value[self::F_MOVE_LEFT_POS_B].'</'.$this->twzmappingvalues[self::F_MOVE_LEFT_POS_B].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_OPTIONS_A].'>'.$value[self::F_OPTIONS_A].'</'.$this->twzmappingvalues[self::F_OPTIONS_A].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_OPTIONS_B].'>'.$value[self::F_OPTIONS_B].'</'.$this->twzmappingvalues[self::F_OPTIONS_B].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_EXTRA_JS_A].'>'.$value[self::F_EXTRA_JS_A].'</'.$this->twzmappingvalues[self::F_EXTRA_JS_A].'>'."\n";
              $filedata .= '<'.$this->twzmappingvalues[self::F_EXTRA_JS_B].'>'.$value[self::F_EXTRA_JS_B].'</'.$this->twzmappingvalues[self::F_EXTRA_JS_B].'>'."\n";
        
              $filedata .= '</ROW>'."\n";
        
        }

        $filedata .= '</TWIZ>'."\n";
        
        $sectionname = ($sectionname == '') ? $sectionname = self::SECTION_DEFAULT : $sectionname;
        
        header("Content-Type: text/twz;\n"); 
        header("Content-Transfer-Encoding: binary\n");
        header('Content-length: '.$this->utf8_strlen($filedata));
        header("Content-Disposition: attachment; filename=\"".$sectionname.".twz\"\n");
        header("Content-Description: The Welcomizer generated data: ".date('Y-m-d H:i:s')."\n");
        header("Expires: 0");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache"); 
        
        return $filedata;
    }
    
    private function utf8_strlen( $string = '' ) {
    
        $char = strlen($string); 
        $l = 0;
        
        for ($i = 0; $i < $char; ++$i){
            
            if ( ( ord($string[$i]) & 0xC0 ) != 0x80 ){ 
                ++$l;
            }
        }
        
        return $l;
    }
    
    function delete( $id = '' ){
    
        global $wpdb;
        
        if($id==''){return false;}
         
        $sql = "DELETE from ".$this->table." where ".self::F_ID." = ".$id.";";
        $code = $wpdb->query($sql);
    
        return $code;

    }        
    
    function install(){ 
    
        global $wpdb;
                 
        $sql = "CREATE TABLE ".$this->table." (". 
                self::F_ID . " int NOT NULL AUTO_INCREMENT, ". 
                self::F_SECTION_ID . " varchar(22) NOT NULL default '".self::SECTION_DEFAULT."', ". 
                self::F_STATUS . " tinyint(3) NOT NULL default 0, ". 
                self::F_LAYER_ID . " varchar(50) NOT NULL default '', ". 
                self::F_START_DELAY . " int(5) NOT NULL default 0, ". 
                self::F_DURATION . " int(5) NOT NULL default 0, ". 
                self::F_START_TOP_POS_SIGN . " varchar(1) NOT NULL default '', ". 
                self::F_START_TOP_POS . " int(5) default NULL, ". 
                self::F_START_LEFT_POS_SIGN . " varchar(1) NOT NULL default '', ". 
                self::F_START_LEFT_POS . " int(5) default NULL, ". 
                self::F_POSITION . " varchar(8) NOT NULL default '', ". 
                self::F_MOVE_TOP_POS_SIGN_A . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_TOP_POS_A . " int(5) default NULL, ". 
                self::F_MOVE_LEFT_POS_SIGN_A . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_LEFT_POS_A . " int(5) default NULL, ". 
                self::F_MOVE_TOP_POS_SIGN_B . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_TOP_POS_B . " int(5) default NULL, ". 
                self::F_MOVE_LEFT_POS_SIGN_B . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_LEFT_POS_B . " int(5) default NULL, ". 
                self::F_OPTIONS_A . " text NOT NULL default '', ". 
                self::F_OPTIONS_B . " text NOT NULL default '', ". 
                self::F_EXTRA_JS_A . " text NOT NULL default '', ". 
                self::F_EXTRA_JS_B . " text NOT NULL default '', " .  
                "PRIMARY KEY (". self::F_ID . "));";
                
        if ( $wpdb->get_var( "show tables like '".$this->table."'" ) != $this->table ) {

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
            dbDelta($sql);
        
            update_option('twiz_db_version', $this->dbVersion);
            update_option('twiz_global_status', '1');
            
        }else{
            
            if( get_option('twiz_db_version') != $this->dbVersion ){
            
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            
                dbDelta($sql);
                
                update_option('twiz_db_version', $this->dbVersion);
            }
        }
        
        return true;
    }
    
    function import( $sectionid = self::SECTION_DEFAULT ){
    
        $filearray = $this->getImportDirectory();
        
        foreach($filearray as $filename){
            
            if($code = $this->importData($filename, $sectionid)){
 
                return true;
            }
        }
        
        return true;
    }
    
    private function importData( $filename = '', $sectionid = self::SECTION_DEFAULT ){
 
        /* full file path */
        $file = $this->pluginDir.self::IMPORT_PATH.$filename;

        if (file_exists($file)) {
        
            if($twz = simplexml_load_file($file)){

               /* flip array mapping value to match*/
               $reverse_twzmappingvalues = array_flip($this->twzmappingvalues);
               
                /* loop xml entities */              
                foreach($twz->children() as $twzrow)
                {                  
                    $row = array();
                    $row[self::F_SECTION_ID] = $sectionid;
                    
                    foreach($twzrow->children() as $twzfield)
                    {
                        $fieldname = '';
                        $fieldvalue = '';
                        
                        $fieldname = strtr($twzfield->getName(), $reverse_twzmappingvalues);

                        if($fieldname != "") {                        

                            /* get the real name of the field */
                            $fieldvalue = $twzfield;

                            /* build record array */
                            $row[$fieldname] = $fieldvalue;
                           
                        }
                    }

                    /* insert row  */
                    if(! $code = $this->importInsert($row)){
                        
                        return false;
                    }
                }
                /* imported */     
                return  true;
            }
        }
        
        return false;
    }
    
    private function importInsert( $data = array() ){
        
        global $wpdb;
        
        $twiz_move_top_pos_a  = esc_attr(trim($data[self::F_MOVE_TOP_POS_A]));
        $twiz_move_left_pos_a = esc_attr(trim($data[self::F_MOVE_LEFT_POS_A]));
        $twiz_move_top_pos_b  = esc_attr(trim($data[self::F_MOVE_TOP_POS_B]));
        $twiz_move_left_pos_b = esc_attr(trim($data[self::F_MOVE_LEFT_POS_B]));
        $twiz_start_top_pos   = esc_attr(trim($data[self::F_START_TOP_POS]));
        $twiz_start_left_pos  = esc_attr(trim($data[self::F_START_LEFT_POS]));
        
        $twiz_move_top_pos_a  = ($twiz_move_top_pos_a=='') ? 'NULL' : $twiz_move_top_pos_a;
        $twiz_move_left_pos_a = ($twiz_move_left_pos_a=='') ? 'NULL' : $twiz_move_left_pos_a;
        $twiz_move_top_pos_b  = ($twiz_move_top_pos_b=='') ? 'NULL' : $twiz_move_top_pos_b;
        $twiz_move_left_pos_b = ($twiz_move_left_pos_b=='') ? 'NULL' : $twiz_move_left_pos_b;
        $twiz_start_top_pos   = ($twiz_start_top_pos=='') ? 'NULL' : $twiz_start_top_pos;
        $twiz_start_left_pos  = ($twiz_start_left_pos=='') ? 'NULL' : $twiz_start_left_pos;
      
        $sql = "INSERT INTO ".$this->table." 
             (".self::F_SECTION_ID."
             ,".self::F_STATUS."
             ,".self::F_LAYER_ID."
             ,".self::F_START_DELAY."
             ,".self::F_DURATION."
             ,".self::F_START_TOP_POS_SIGN."
             ,".self::F_START_TOP_POS."
             ,".self::F_START_LEFT_POS_SIGN."
             ,".self::F_START_LEFT_POS."    
             ,".self::F_POSITION."    
             ,".self::F_MOVE_TOP_POS_SIGN_A."
             ,".self::F_MOVE_TOP_POS_A."
             ,".self::F_MOVE_LEFT_POS_SIGN_A."
             ,".self::F_MOVE_LEFT_POS_A."
             ,".self::F_MOVE_TOP_POS_SIGN_B."
             ,".self::F_MOVE_TOP_POS_B."
             ,".self::F_MOVE_LEFT_POS_SIGN_B."
             ,".self::F_MOVE_LEFT_POS_B."
             ,".self::F_OPTIONS_A."
             ,".self::F_OPTIONS_B."
             ,".self::F_EXTRA_JS_A."
             ,".self::F_EXTRA_JS_B."       
             )values('".esc_attr(trim($data[self::F_SECTION_ID]))."'
             ,'".esc_attr(trim($data[self::F_STATUS]))."'
             ,'".esc_attr(trim($data[self::F_LAYER_ID]))."'
             ,'0".esc_attr(trim($data[self::F_START_DELAY]))."'
             ,'0".esc_attr(trim($data[self::F_DURATION]))."'
             ,'".esc_attr(trim($data[self::F_START_TOP_POS_SIGN]))."'    
             ,".$twiz_start_top_pos."
             ,'".esc_attr(trim($data[self::F_START_LEFT_POS_SIGN]))."'    
             ,".$twiz_start_left_pos."
             ,'".esc_attr(trim($data[self::F_POSITION]))."'                     
             ,'".esc_attr(trim($data[self::F_MOVE_TOP_POS_SIGN_A]))."'    
             ,".$twiz_move_top_pos_a."
             ,'".esc_attr(trim($data[self::F_MOVE_LEFT_POS_SIGN_A]))."'    
             ,".$twiz_move_left_pos_a."
             ,'".esc_attr(trim($data[self::F_MOVE_TOP_POS_SIGN_B]))."'                     
             ,".$twiz_move_top_pos_b."
             ,'".esc_attr(trim($data[self::F_MOVE_LEFT_POS_SIGN_B]))."'    
             ,".$twiz_move_left_pos_b."
             ,'".esc_attr(trim($data[self::F_OPTIONS_A]))."'                             
             ,'".esc_attr(trim($data[self::F_OPTIONS_B]))."'
             ,'".esc_attr(trim($data[self::F_EXTRA_JS_A]))."'                             
             ,'".esc_attr(trim($data[self::F_EXTRA_JS_B]))."'                 
             );";
            
            $code = $wpdb->query($sql);
            
            if($code){return true;}
            
            return $code;
    }
    
    private function fileGetHtml() {
    
        $dom = new twiz_simple_html_dom;
        
        $args = func_get_args();
        
        $dom->load(call_user_func_array('file_get_contents', $args), true);
        
        return $dom;
    }
    
    function formatDuration( $id = '', $data = null ){
        
        $data = '';
        
        if($id==''){return false;}
       
        $data = ($data==null) ? $this->getRow($id) : $data;
        
        $duration = (($data[self::F_MOVE_TOP_POS_B] !='' ) or( $data[self::F_MOVE_LEFT_POS_B] !='' ) or( $data[self::F_OPTIONS_B] !='' ) or( $data[self::F_EXTRA_JS_B] !='' )) ? $data[self::F_DURATION].'<b class="twiz-xx"> x2</b>' : $data[self::F_DURATION];
        
        return $duration;
    }
    
    private function getImportDirectory(){
        
        if ($handle = opendir($this->pluginDir.self::IMPORT_PATH)) {
        
            while (false !== ($file = readdir($handle))) {
            
                if ($file != "." && $file != "..") {
                
                    $filearray[] = $file;
                }
            }
            
            closedir($handle);
        }
        
        if(!is_array($filearray)){ $filearray = array();}
         
        return $filearray;
    }
    
    function getFrontEnd(){
      
        global $post;
      
        wp_reset_query(); // fix is_home() due to a custom query.
        
        /* true super logical swicth */
        switch( true ){
        
            case is_home():
                
                // get the active data list array
                $listarray = $this->getListArray(" where ".self::STATUS." = 1 and ".self::SECTION_ID." = '".self::SECTION_DEFAULT."' "); 
            
                break;
                
            case is_category():
                
                $category_id = 'c_'.get_query_var('cat');
                
                // get the active data list array
                $listarray = $this->getListArray(" where ".self::STATUS." = 1 and ".self::SECTION_ID." = '".$category_id."' "); 
                
                break;
                
            case is_page():
            
                $page_id = 'p_'.$post->ID;
                
                // get the active data list array
                $listarray = $this->getListArray(" where ".self::STATUS." = 1 and ".self::SECTION_ID." = '".$page_id."' ");             
                break;
                
            case is_single():
                return '';
                break;
                
            case is_feed():
                return '';
                break;                
        }
        
        /* no data, no output */
        if(count($listarray)==0){
            return '';
        }
        
        if(get_option('twiz_global_status')=='1'){
        
            /* script header */
            $generatedscript.="<!-- ".$this->pluginName." ".$this->version." -->\n";
            $generatedscript.= '<script type="text/javascript">
jQuery(document).ready(function($) {';
             
             /* generates the code */
            foreach($listarray as $value){
            
                /* start delay */ 
                $generatedscript .= '
setTimeout(function(){'; 
            
                /* css position */ 
                $generatedscript .= ($value[self::F_POSITION]!='') ?'
$("#'.$value[self::F_LAYER_ID].'").css("position", "'.$value[self::F_POSITION].'");' : ''; 
                
                /* starting positions */ 
                $generatedscript .=($value[self::F_START_LEFT_POS]!='') ? '$("#'.$value[self::F_LAYER_ID].'").css("left", "'.$value[self::F_START_LEFT_POS_SIGN].$value[self::F_START_LEFT_POS].'px");' : '';
                $generatedscript .=($value[self::F_START_TOP_POS]!='') ? '$("#'.$value[self::F_LAYER_ID].'").css("top", "'.$value[self::F_START_TOP_POS_SIGN].$value[self::F_START_TOP_POS].'px");' : '';
                
                $value[self::F_OPTIONS_A] = (($value[self::F_OPTIONS_A]!='') and (($value[self::F_MOVE_LEFT_POS_A]!="") or ($value[self::F_MOVE_TOP_POS_A]!=""))) ? ','.$value[self::F_OPTIONS_A] :  $value[self::F_OPTIONS_A];
                $value[self::F_OPTIONS_A] = str_replace("\n", "," , $value[self::F_OPTIONS_A]);
            
                /* replace numeric entities */    
                $value[self::F_OPTIONS_A] = $this->replaceNumericEntities($value[self::F_OPTIONS_A]);

                /* animate jquery a */ 
                $generatedscript .= '
$("#'.$value[self::F_LAYER_ID].'").animate({';

                $generatedscript .= ($value[self::F_MOVE_LEFT_POS_A]!="") ? 'left:  "'.$value[self::F_MOVE_LEFT_POS_SIGN_A].'='.$value[self::F_MOVE_LEFT_POS_A].'"' : '';
                $generatedscript .= (($value[self::F_MOVE_LEFT_POS_A]!="") and ($value[self::F_MOVE_TOP_POS_A]!="")) ? ',' : '';
                $generatedscript .= ($value[self::F_MOVE_TOP_POS_A]!="") ? 'top:  "'.$value[self::F_MOVE_TOP_POS_SIGN_A].'='.$value[self::F_MOVE_TOP_POS_A].'"' : '';
                $generatedscript .= $value[self::F_OPTIONS_A];
                
                $generatedscript .= '
}, '.$value[self::F_DURATION].', function() {';
        
                /* replace numeric entities */
                $value[self::F_EXTRA_JS_A] = $this->replaceNumericEntities($value[self::F_EXTRA_JS_A]);
    
                /* extra js a */    
                $generatedscript .= ($value[self::F_EXTRA_JS_A]!='') ? $value[self::F_EXTRA_JS_A] : '';
                
                /* ************************* */
                
                /* add a coma between each options */ 
                $value[self::F_OPTIONS_B] = (($value[self::F_OPTIONS_B]!='') and ((($value[self::F_MOVE_LEFT_POS_B]!="") or ($value[self::F_MOVE_TOP_POS_B]!="")))) ? ','.$value[self::F_OPTIONS_B] :  $value[self::F_OPTIONS_B];
                $value[self::F_OPTIONS_B] = str_replace("\n", "," , $value[self::F_OPTIONS_B]);
                
                /* replace numeric entities */                
                $value[self::F_OPTIONS_B] = $this->replaceNumericEntities($value[self::F_OPTIONS_B]);
                
                /* animate jquery b */ 
                $generatedscript .= '
$("#'.$value[self::F_LAYER_ID].'").animate({';

                $generatedscript .= ($value[self::F_MOVE_LEFT_POS_B]!="") ? 'left:  "'.$value[self::F_MOVE_LEFT_POS_SIGN_B].'='.$value[self::F_MOVE_LEFT_POS_B].'"' : '';
                $generatedscript .= (($value[self::F_MOVE_LEFT_POS_B]!="") and ($value[self::F_MOVE_TOP_POS_B]!="")) ? ',' : '';
                $generatedscript .= ($value[self::F_MOVE_TOP_POS_B]!="") ? 'top:  "'.$value[self::F_MOVE_TOP_POS_SIGN_B].'='.$value[self::F_MOVE_TOP_POS_B].'"' : '';
                $generatedscript .=  $value[self::F_OPTIONS_B];
                
                $generatedscript .= '
}, '.$value[self::F_DURATION].', function() {';
                    
                /* replace numeric entities */
                $value[self::F_EXTRA_JS_B] = $this->replaceNumericEntities($value[self::F_EXTRA_JS_B]);
    
                /* extra js b */    
                $generatedscript .= ($value[self::F_EXTRA_JS_B]!='') ? $value[self::F_EXTRA_JS_B] : '';
                
                /* closing functions */
                $generatedscript .= '});';
                $generatedscript .= '});';
                $generatedscript .= '},'.$value[self::F_START_DELAY].');';
            }
            
            /* script footer */
            $generatedscript.= '});';
            $generatedscript.= '
</script>';
        }
        return $generatedscript;
    }
    
    private function getDirectionalImage( $data = '', $ab = ''){
    
        if($data==''){return '';}
        if($ab==''){return '';}
    
        /* true super fast logical switch */
        switch(true){
        
            case (($data['move_top_pos_'.$ab]!= '') 
                 and ($data['move_top_pos_sign_'.$ab] == '-') 
                 and ($data['move_left_pos_'.$ab]== '') ): // N
                 
                 $direction = self::DIMAGE_N;
                 
                break;
                
            case (($data['move_top_pos_'.$ab]!= '') 
                 and ($data['move_top_pos_sign_'.$ab] == '-') 
                 and ($data['move_left_pos_'.$ab]!= '') 
                 and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // NE
                 
                $direction = self::DIMAGE_NE;
            
                break;       
                
            case (($data['move_top_pos_'.$ab]== '') 
                 and ($data['move_left_pos_'.$ab]!= '') 
                 and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // E
                 
                $direction = self::DIMAGE_E;
            
                break;    
                
            case (($data['move_top_pos_'.$ab]!= '') 
                 and ($data['move_top_pos_sign_'.$ab] == '+') 
                 and ($data['move_left_pos_'.$ab]!= '') 
                 and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // SE
                 
                $direction = self::DIMAGE_SE;
            
                break;  
                
           case (($data['move_top_pos_'.$ab]!= '') 
                 and ($data['move_top_pos_sign_'.$ab] == '+') 
                 and ($data['move_left_pos_'.$ab]== '') ): // S
                 
                $direction = self::DIMAGE_S;                 
            
                break;  
                
           case (($data['move_top_pos_'.$ab]!= '') 
                 and ($data['move_top_pos_sign_'.$ab] == '+') 
                 and ($data['move_left_pos_'.$ab]!= '') 
                 and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // SW
                 
                 $direction = self::DIMAGE_SW;
            
                break; 
                
           case (($data['move_top_pos_'.$ab]== '') 
                 and ($data['move_left_pos_'.$ab]!= '') 
                 and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // W
                 
                 $direction = self::DIMAGE_W;
            
                break;      
                
           case (($data['move_top_pos_'.$ab]!= '') 
                 and ($data['move_top_pos_sign_'.$ab] == '-') 
                 and ($data['move_left_pos_'.$ab]!= '') 
                 and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // NW
                 
                $direction = self::DIMAGE_NW;
            
                break;     
        }
        
        if($direction!=''){ 
           
            return '<img width="45" height="45" src="'.$this->pluginUrl.'/images/twiz-arrow-'.$direction.'.png">';
            
        }else{
        
            return '';
            
        }
    }

    function getHtmlForm( $id = '' ){ 
    
        $data = '';        
        $opendiv = '';
        $closediv = '';
             
        if($id!=''){
            if(!$data = $this->getRow($id)){return false;}
            $hideimport = '$("#twiz_import").fadeOut("slow");';
        }
        
        /* Toggle More Options */
        $jsscript_moreoptions = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
  $(".twiz-table-more-options").toggle();
  });
 //]]>
</script>';
        
        /* hide element */
        $jsscript_hide = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
        $("#twiz_new").fadeOut("slow");
        $("#twiz_add_menu").fadeOut("slow");
        $("#twiz_add_sections").fadeOut("slow"); 
        $("#twiz_right_panel").fadeOut("slow");
        $("#twiz_export").fadeOut("slow");
        '.$hideimport .'
  });
 //]]>
</script>';


        /* Text Area auto expand */
        $jsscript_autoexpand = '<script language="JavaScript" type="text/javascript">
 //<![CDATA[
  jQuery(document).ready(function($) {
    textarea = new Object();
    textarea.expand = function(input){
        input.style.height = "65px";
        input.style.height = (input.scrollHeight + 20) + "px";
    } 
  });
 //]]>
</script>';


        /* ajax container */ 
        if(!in_array($_POST['twiz_action'], $this->actiontypes)){
             $opendiv = '<div id="twiz_container">';
             $closediv = '</div>';
        }
        
        if( !isset($data[self::F_OPTIONS_A]) ) $data[self::F_OPTIONS_A] = '' ;
        if( !isset($data[self::F_OPTIONS_B]) ) $data[self::F_OPTIONS_B] = '' ;
        if( !isset($data[self::F_EXTRA_JS_A]) ) $data[self::F_EXTRA_JS_A] = '' ;
        if( !isset($data[self::F_EXTRA_JS_B]) ) $data[self::F_EXTRA_JS_B] = '' ;
        if( !isset($data[self::F_STATUS]) ) $data[self::F_STATUS] = '' ;
        if( !isset($data[self::F_POSITION]) ) $data[self::F_POSITION] = '' ;
        if( !isset($data[self::F_START_TOP_POS_SIGN]) ) $data[self::F_START_TOP_POS_SIGN] = '' ;
        if( !isset($data[self::F_START_LEFT_POS_SIGN]) ) $data[self::F_START_LEFT_POS_SIGN] = '' ;
        if( !isset($data[self::F_MOVE_TOP_POS_SIGN_A]) ) $data[self::F_MOVE_TOP_POS_SIGN_A] = '' ;
        if( !isset($data[self::F_MOVE_TOP_POS_SIGN_B]) ) $data[self::F_MOVE_TOP_POS_SIGN_B] = '' ;
        if( !isset($data[self::F_MOVE_LEFT_POS_SIGN_A]) ) $data[self::F_MOVE_LEFT_POS_SIGN_A] = '' ;
        if( !isset($data[self::F_MOVE_LEFT_POS_SIGN_B]) ) $data[self::F_MOVE_LEFT_POS_SIGN_B] = '' ;
        if( !isset($data[self::F_LAYER_ID]) ) $data[self::F_LAYER_ID] = '' ;
        if( !isset($data[self::F_START_DELAY]) ) $data[self::F_START_DELAY] = '' ;
        if( !isset($data[self::F_DURATION]) ) $data[self::F_DURATION] = '' ;
        if( !isset($data[self::F_START_TOP_POS]) ) $data[self::F_START_TOP_POS] = '' ;
        if( !isset($data[self::F_START_LEFT_POS]) ) $data[self::F_START_LEFT_POS] = '' ;
        if( !isset($data[self::F_MOVE_TOP_POS_A]) ) $data[self::F_MOVE_TOP_POS_A] = '' ;
        if( !isset($data[self::F_MOVE_TOP_POS_B]) ) $data[self::F_MOVE_TOP_POS_B] = '' ;
        if( !isset($data[self::F_MOVE_LEFT_POS_A]) ) $data[self::F_MOVE_LEFT_POS_A] = '' ;
        if( !isset($data[self::F_MOVE_LEFT_POS_B]) ) $data[self::F_MOVE_LEFT_POS_B] = '' ;
        if( !isset($twiz_position['absolute'] ) ) $twiz_position['absolute']  = '' ;
        if( !isset($twiz_position['relative']) ) $twiz_position['relative'] = '' ;
        if( !isset($twiz_position['static']) ) $twiz_position['static'] = '' ;
        if( !isset($twiz_start_top_pos_sign['nothing'] ) ) $twiz_start_top_pos_sign['nothing']  = '' ;
        if( !isset($twiz_start_top_pos_sign['-']) ) $twiz_start_top_pos_sign['-'] = '' ;
        if( !isset($twiz_start_left_pos_sign['nothing'] ) ) $twiz_start_left_pos_sign['nothing']  = '' ;
        if( !isset($twiz_start_left_pos_sign['-']) ) $twiz_start_left_pos_sign['-'] = '' ;        
        if( !isset($twiz_move_top_pos_sign_a['+'] ) ) $twiz_move_top_pos_sign_a['+']  = '' ;
        if( !isset($twiz_move_top_pos_sign_a['-'] ) ) $twiz_move_top_pos_sign_a['-']  = '' ;
        if( !isset($twiz_move_top_pos_sign_b['+']) ) $twiz_move_top_pos_sign_b['+'] = '' ;
        if( !isset($twiz_move_top_pos_sign_b['-']) ) $twiz_move_top_pos_sign_b['-'] = '' ;        
        
        /* toggle more options by default if we have values */        
        if(($data[self::F_OPTIONS_A]!='')or($data[self::F_EXTRA_JS_A]!='')
         or($data[self::F_OPTIONS_B]!='')or($data[self::F_EXTRA_JS_B]!='')){
            $toggleoptions = $jsscript_moreoptions;
        }else{
            $toggleoptions = '';
        }
    
        /* checked */
        $twiz_status = (($data[self::F_STATUS]==1)or($id=='')) ? ' checked="checked"' : '';
        
        /* selected */
        $twiz_position['absolute'] = ($data[self::F_POSITION]=='absolute') ? ' selected="selected"' : '';
        $twiz_position['relative'] = (($data[self::F_POSITION]=='relative')or($id=='')) ? ' selected="selected"' : '';
        $twiz_position['static']   = ($data[self::F_POSITION]=='static') ? ' selected="selected"' : '';

        $twiz_start_top_pos_sign['nothing']  = ($data[self::F_START_TOP_POS_SIGN]=='') ? ' selected="selected"' : '';
        $twiz_start_top_pos_sign['-']        = ($data[self::F_START_TOP_POS_SIGN]=='-') ? ' selected="selected"' : '';
        $twiz_start_left_pos_sign['nothing'] = ($data[self::F_START_LEFT_POS_SIGN]=='') ? ' selected="selected"' : '';
        $twiz_start_left_pos_sign['-']       = ($data[self::F_START_LEFT_POS_SIGN]=='-') ? ' selected="selected"' : '';
        
        $twiz_move_top_pos_sign_a['+']  = ($data[self::F_MOVE_TOP_POS_SIGN_A]=='+') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_a['-']  = ($data[self::F_MOVE_TOP_POS_SIGN_A]=='-') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_a['+'] = ($data[self::F_MOVE_LEFT_POS_SIGN_A]=='+') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_a['-'] = ($data[self::F_MOVE_LEFT_POS_SIGN_A]=='-') ? ' selected="selected"' : '';

        $twiz_move_top_pos_sign_b['+']  = ($data[self::F_MOVE_TOP_POS_SIGN_B]=='+') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_b['-']  = ($data[self::F_MOVE_TOP_POS_SIGN_B]=='-') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_b['+'] = ($data[self::F_MOVE_LEFT_POS_SIGN_B]=='+') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_b['-'] = ($data[self::F_MOVE_LEFT_POS_SIGN_B]=='-') ? ' selected="selected"' : '';

        $imagemove_a = $this->getDirectionalImage($data, 'a');
        $imagemove_b = $this->getDirectionalImage($data, 'b');

        /* creates the form */
        $htmlform = $opendiv.'<table class="twiz-table-form" cellspacing="0" cellpadding="0">
<tr><td class="twiz-form-td-left">'.__('Status', 'the-welcomizer').':</td>
<td class="twiz-form-td-right"><input type="checkbox" id="twiz_status" name="twiz_status" '.$twiz_status.'></td></tr>
<tr><td class="twiz-form-td-left">'.__('Element ID', 'the-welcomizer').':</td><td class="twiz-form-td-right"><input class="twiz-input" id="twiz_layer_id" name="twiz_layer_id" type="text" value="'.$data[self::F_LAYER_ID].'" maxlength="50"></td></tr>
<tr><td colspan="2" id="twiz_td_full_chooseid" class="twiz-td-picklist"><a id="twiz_choose_fromId" name="twiz_choose_fromId">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>
<tr><td class="twiz-form-td-left">'.__('Start delay', 'the-welcomizer').': </td><td class="twiz-form-td-right"><input class="twiz-input twiz-input-small" id="twiz_start_delay" name="twiz_start_delay" type="text" value="'.$data[self::F_START_DELAY].'" maxlength="5"> <small>1000 = 1 '.__('sec', 'the-welcomizer').'</small></td></tr>
<tr><td class="twiz-form-td-left">'.__('Duration', 'the-welcomizer').': <div class="twiz-xx twiz-float-right">2x</div></td><td class="twiz-form-td-right"><input class="twiz-input twiz-input-small" id="twiz_duration" name="twiz_duration" type="text" value="'.$data[self::F_DURATION].'" maxlength="5"> <small>1000 = 1 '.__('sec', 'the-welcomizer').'</small></td></tr>
<tr><td colspan="2"><hr></td></tr>
 <tr><td colspan="2" class="twiz-caption">'.__('Starting position', 'the-welcomizer').' <b>'.__('(optional)', 'the-welcomizer').'</b></tr>
<tr>
    <td>
        <table>
            <tr><td class="twiz-td-small-left" nowrap>'.__('Top', 'the-welcomizer').':</td><td>
            <select name="twiz_start_top_pos_sign" id="twiz_start_top_pos_sign">
                <option value="" '.$twiz_start_top_pos_sign['nothing'].'>+</option>
                <option value="-" '.$twiz_start_top_pos_sign['-'].'>-</option>
                </select><input class="twiz-input twiz-input-small" id="twiz_start_top_pos" name="twiz_start_top_pos" type="text" value="'.$data[self::F_START_TOP_POS].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr>
            <tr><td class="twiz-td-small-left" nowrap>'.__('Left', 'the-welcomizer').':</td><td>
            <select name="twiz_start_left_pos_sign" id="twiz_start_left_pos_sign">
                <option value="" '.$twiz_start_left_pos_sign['nothing'].'>+</option>
                <option value="-" '.$twiz_start_left_pos_sign['-'].'>-</option>
                </select><input class="twiz-input twiz-input-small" id="twiz_start_left_pos" name="twiz_start_left_pos" type="text" value="'.$data[self::F_START_LEFT_POS].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr>
        </table>
    </td>
    <td>
        <table>
            <tr><td rowspan="2">'.__('Position', 'the-welcomizer').':</td><td>
            <select name="twiz_position" id="twiz_position"><option value="" > </option>
            <option value="absolute" '.$twiz_position['absolute'].'>'.__('absolute', 'the-welcomizer').'</option>
            <option value="relative" '.$twiz_position['relative'].'>'.__('relative', 'the-welcomizer').'</option>
            </select>
            </td></tr>
        </table>
    </td>
</tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td valign="top">
        <table>
            <tr><td class="twiz-caption" colspan="3"><b>'.__('First Move', 'the-welcomizer').'</b></td></tr>
            <tr><td class="twiz-td-small-left" nowrap>'.__('Top', 'the-welcomizer').':</td><td nowrap>
            <select name="" id="twiz_move_top_pos_sign_a">
            <option value="+" '.$twiz_move_top_pos_sign_a['+'].'>+</option>
            <option value="-" '.$twiz_move_top_pos_sign_a['-'].'>-</option>
            </select><input class="twiz-input twiz-input-small" id="twiz_move_top_pos_a" name="twiz_move_top_pos_a" type="text" value="'.$data[self::F_MOVE_TOP_POS_A].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td><td rowspan="2" align="center" width="95" id="twiz_td_arrow_a">'.$imagemove_a.'</td></tr>
            <tr><td class="twiz-td-small-left" nowrap>'.__('Left', 'the-welcomizer').':</td><td nowrap>
            <select name="twiz_move_left_pos_sign_a" id="twiz_move_left_pos_sign_a">
            <option value="+" '.$twiz_move_left_pos_sign_a['+'].'>+</option>
            <option value="-" '.$twiz_move_left_pos_sign_a['-'].'>-</option>
            </select><input class="twiz-input twiz-input-small" id="twiz_move_left_pos_a" name="twiz_move_left_pos_a" type="text" value="'.$data[self::F_MOVE_LEFT_POS_A].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr><tr><td></td><td><a name="twiz_more_options_a" id="twiz_more_options_a"  class="twiz-more-options">'.__('More Options', 'the-welcomizer').' &#187;</a></td></tr></table>
            <table class="twiz-table-more-options">
                <tr><td colspan="2"><hr></td></tr>
                <tr><td colspan="2" class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td colspan="2"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_options_a" name="twiz_options_a" type="text" >'.$data[self::F_OPTIONS_A].'</textarea></td></tr>
                <tr><td colspan="2" id="twiz_td_full_option_a" class="twiz-td-picklist"><a id="twiz_choose_options_a" name="twiz_choose_options_a">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>
                <tr><td colspan="2" class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').' <br>
                opacity:0.5<br>
                width:"200px"<br>
                <a href="http://api.jquery.com/animate/" alt="'.__('Learn more about jQuery .animate() properties', 'the-welcomizer').'" title="'.__('Learn more about jQuery .animate() properties', 'the-welcomizer').'" target="_blank">jQuery .animate()</a>
                </td></tr>        
                <tr><td colspan="2"><hr></td></tr>        
                <tr><td colspan="2" class="twiz-caption">'.__('Extra JavaScript', 'the-welcomizer').'</td></tr><tr><td colspan="2"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_extra_js_a" name="twiz_extra_js_a" type="text">'.$data[self::F_EXTRA_JS_A].'</textarea></td></tr><tr><td colspan="2" class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').'<br>alert("'.__('Welcome!', 'the-welcomizer').'");</td></tr>
        </table>
</td>
<td valign="top">    
    <table>
        <tr><td class="twiz-caption" colspan="3"><b>'.__('Second Move', 'the-welcomizer').'</b></td></tr>
        <tr><td class="twiz-td-small-left" nowrap>'.__('Top', 'the-welcomizer').':</td><td nowrap>
        <select name="twiz_move_top_pos_sign_b" id="twiz_move_top_pos_sign_b">
        <option value="-" '.$twiz_move_top_pos_sign_b['-'].'>-</option>
        <option value="+" '.$twiz_move_top_pos_sign_b['+'].'>+</option>
        </select><input class="twiz-input twiz-input-small" id="twiz_move_top_pos_b" name="twiz_move_top_pos_b" type="text" value="'.$data[self::F_MOVE_TOP_POS_B].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td><td rowspan="2" align="center" width="95" id="twiz_td_arrow_b">'.$imagemove_b.'</td></tr>
        <tr><td class="twiz-td-small-left" nowrap>'.__('Left', 'the-welcomizer').':</td><td nowrap>
        <select name="twiz_move_left_pos_sign_b" id="twiz_move_left_pos_sign_b">
        <option value="-" '.$twiz_move_left_pos_sign_b['-'].'>-</option>
        <option value="+" '.$twiz_move_left_pos_sign_b['+'].'>+</option>
        </select><input class="twiz-input twiz-input-small" id="twiz_move_left_pos_b" name="twiz_move_left_pos_b" type="text" value="'.$data[self::F_MOVE_LEFT_POS_B].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr><tr><td></td><td><a name="twiz_more_options_b" id="twiz_more_options_b" class="twiz-more-options">'.__('More Options', 'the-welcomizer').' &#187;</a></td></tr>
        </table>
        <table class="twiz-table-more-options">
            <tr><td colspan="2"><hr></td></tr>
            <tr><td colspan="2" class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td colspan="2"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_options_b" name="twiz_options_b" type="text">'.$data[self::F_OPTIONS_B].'</textarea></td></tr>
            <tr><td colspan="2" id="twiz_td_full_option_b" class="twiz-td-picklist"><a id="twiz_choose_options_b" name="twiz_choose_options_b">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>
            <tr><td colspan="2" class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').' <br> 
                opacity:1<br>
                width:"100px"<br><a href="http://api.jquery.com/animate/" alt="'.__('Learn more about jQuery .animate() properties', 'the-welcomizer').'" title="'.__('Learn more about jQuery .animate() properties', 'the-welcomizer').'" target="_blank">jQuery .animate()</a>
                </td></tr>        
            <tr><td colspan="2"><hr></td></tr>
            <tr><td colspan="2" class="twiz-caption">'.__('Extra JavaScript', 'the-welcomizer').'</td></tr><tr><td colspan="2"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_extra_js_b" name="twiz_extra_js_b" type="text" value="">'.$data[self::F_EXTRA_JS_B].'</textarea></td></tr><tr><td colspan="2" class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').'<br>$(this).css({position:"static"});</td></tr>
        </table>
</td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td class="twiz-td-save" colspan="2"><img src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_save_img" name="twiz_save_img" class="twiz-loading-gif twiz-loading-gif-save"><a name="twiz_cancel" id="twiz_cancel">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save" id="twiz_save" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /><input type="hidden" name="twiz_id" id="twiz_id" value="'.$id.'"></td></tr>
</table>'.$closediv.$toggleoptions.$jsscript_autoexpand.$jsscript_hide;
    
        return $htmlform;
    }

    function getHtmlView( $id ){ 
        
        $data = '';
        
        if($id!=''){
            if(!$data = $this->getRow($id)){return false;}
        }

        $start_top_pos = ($data[self::F_START_TOP_POS]!='') ? $data[self::F_START_TOP_POS_SIGN].$data[self::F_START_TOP_POS].' '.__('px', 'the-welcomizer') : '';
        $start_left_pos = ($data[self::F_START_LEFT_POS]!='') ? $data[self::F_START_LEFT_POS_SIGN].$data[self::F_START_LEFT_POS].' '.__('px', 'the-welcomizer') : '';
        $move_top_pos_a = ($data[self::F_MOVE_TOP_POS_A]!='') ? $data[self::F_MOVE_TOP_POS_SIGN_A].$data[self::F_MOVE_TOP_POS_A].' '.__('px', 'the-welcomizer') : '';
        $move_left_pos_a = ($data[self::F_MOVE_LEFT_POS_A]!='') ? $data[self::F_MOVE_LEFT_POS_SIGN_A].$data[self::F_MOVE_LEFT_POS_A].' '.__('px', 'the-welcomizer') : '';
        $move_top_pos_b = ($data[self::F_MOVE_TOP_POS_B]!='') ? $data[self::F_MOVE_TOP_POS_SIGN_B].$data[self::F_MOVE_TOP_POS_B].' '.__('px', 'the-welcomizer') : '';
        $move_left_pos_b = ($data[self::F_MOVE_LEFT_POS_B]!='') ? $data[self::F_MOVE_LEFT_POS_SIGN_B].$data[self::F_MOVE_LEFT_POS_B].' '.__('px', 'the-welcomizer') : '';
        
        $titleclass = ($data[self::F_STATUS]=='1') ?'twiz-green' : 'twiz-red';
        
        $imagemove_a = $this->getDirectionalImage($data, 'a');
        $imagemove_b = $this->getDirectionalImage($data, 'b');
        
        /* creates the view */
        $htmlview = '<table class="twiz-table-view" cellspacing="0" cellpadding="0">
        <tr><td class="twiz-view-td-left">'.__('Element ID', 'the-welcomizer').':</td><td class="twiz-view-td-right twiz-bold '.$titleclass.'" nowrap="nowrap">'.$data[self::F_LAYER_ID].'</td></tr>
<tr><td colspan="2"><hr></td></tr>
    <td>
        <table>
            <tr><td class="twiz-view-td-small-left" nowrap>'.__('Top', 'the-welcomizer').':</td><td>'.$start_top_pos.'</td></tr>
            <tr><td class="twiz-view-td-small-left" nowrap>'.__('Left', 'the-welcomizer').':</td><td>'.$start_left_pos.'</td></tr>
        </table>
    </td>
    <td>
        <table>
            <tr><td rowspan="2">'.__('Position', 'the-welcomizer').':</td><td>'.$data[self::F_POSITION].'</td></tr>
        </table>
    </td>
</tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td valign="top">
        <table>
            <tr><td class="twiz-caption" colspan="3" nowrap><b>'.__('First Move', 'the-welcomizer').'</b></td></tr>
            <tr><td class="twiz-view-td-small-left" valign="top" height="20" nowrap>'.__('Top', 'the-welcomizer').':</td><td valign="top" nowrap>'.$move_top_pos_a .'</td><td rowspan="2" align="center" width="95">'.$imagemove_a.'</td></tr>
            <tr><td class="twiz-view-td-small-left"  nowrap valign="top">'.__('Left', 'the-welcomizer').':</td><td valign="top" nowrap>'.$move_left_pos_a .'</td></tr></table>
            <table class="twiz-view-table-more-options">
                <tr><td colspan="2"><hr></td></tr>
                <tr><td colspan="2">'.str_replace("\n", "<br>",$data[self::F_OPTIONS_A]).'</td></tr>    
                <tr><td colspan="2"><hr></td></tr>        
                <tr><td colspan="2">'.str_replace("\n", "<br>",$data[self::F_EXTRA_JS_A]).'</td></tr>
        </table>
</td>
<td valign="top">    
    <table>
        <tr><td class="twiz-caption" colspan="3" nowrap><b>'.__('Second Move', 'the-welcomizer').'</b></td></tr>
        <tr><td class="twiz-view-td-small-left" valign="top" height="20" nowrap>'.__('Top', 'the-welcomizer').':</td><td valign="top" nowrap>'.$move_top_pos_b.'</td><td rowspan="2" align="center" width="95">'.$imagemove_b.'</td></tr>
        <tr><td class="twiz-view-td-small-left" nowrap valign="top">'.__('Left', 'the-welcomizer').':</td><td valign="top" nowrap>'.$move_left_pos_b .'</td></tr>
        </table>
        <table class="twiz-view-table-more-options">
            <tr><td colspan="2"><hr></td></tr>
            <tr><td colspan="2">'.str_replace("\n", "<br>", $data[self::F_OPTIONS_B]).'</td></tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr><td colspan="2">'.str_replace("\n", "<br>", $data[self::F_EXTRA_JS_B]).'</td></tr>
        </table>
</td></tr>
</table>';
    
        return $htmlview;
    }
    
    private function getListArray( $where = '' ){ 
    
        global $wpdb;

        $sql = "SELECT * from ".$this->table.$where." order by ".self::F_START_DELAY;
      
        $rows = $wpdb->get_results($sql, ARRAY_A);
        
        return $rows;
    }

    function getHtmlList( $section_id = '' ){ 
       
        /* from the menu */ 
        $where = ($section_id!='') ? " where ".self::F_SECTION_ID." = '".$section_id."'" : " where ".self::F_SECTION_ID." = '".self::SECTION_DEFAULT."'";
      
        $listarray = $this->getListArray( $where ); // get all the data
        
        if(count($listarray)==0){ // if, display the default new form
            
            return $this->getHtmlForm(); 
            
        }else{ // else display the list
        
            return $this->createHtmlList($listarray); // private
        }
        
    }
    
    private function getHtmlImgStatus( $id = '', $status = ''){
    
        if($id==''){return '';}
        if($status==''){return '';}
    
        return '<img src="'.$this->pluginUrl.'/images/twiz-'.$status.'.png" id="twiz_status_'.$id.'" name="twiz_status_'.$id.'"><img src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_img_status_'.$id.'" name="twiz_img_status_'.$id.'" class="twiz-loading-gif">';

    }
    
    function getHtmlIdList(){
    
        $html = $this->fileGetHtml(get_option('siteurl')); // private
        
        $select = '<select name="twiz_slc_id" id="twiz_slc_id">';
            
        $select .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';
            
        foreach ($html->find('[id]') as $element){
                    
            $select .= '<option value="'.$element->id.'">'.$element->id.'</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }    
    
    function getHtmlOptionList( $id = '' ){
        
        if($id==''){return '';}
        
        $select = '<select class="twiz-slc-options" name="twiz_slc_options_'.$id.'" id="twiz_slc_options_'.$id.'">';
            
        $select .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';
            
        foreach ($this->jQueryoptions as $value){
                    
            $select .= '<option value="'.$value.'">'.$value.'</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }    
    
    private function getHtmlSectionMenu( $section_id = '', $section_name = ''){
    
       if($section_id==''){return '';}
       if($section_name==''){return '';}
    
       $html = '<div id="twiz_menu_'.$section_id.'" class="twiz-menu">'.$section_name.'</div>';
            
       return $html;
    }
    
    function getHtmlSuccess( $message = '' ){
        
        if($message==''){return '';}
    
        $htmlmessage = '<p id="twiz_messagebox">'.$message.'</p>';
        
        return $htmlmessage;
    }
        
    private function getRow( $id = '' ){ 
    
        global $wpdb;
        
        if($id==''){return false;}
    
        $sql = "SELECT * from ".$this->table." where ".self::F_ID." = '".$id."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
        
        return $row;
    }
    
    private function getSectionName( $value = '', $key = null ){
    
        list($type, $id) = split('_', $value);
                
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
                if ($name==""){
                
                    $page = get_page_by_title($key);
                    
                    /*  Give the key instead if empty, and update the key if possible */
                    return $this->updateSectionMenuKey($key, $type.'_'.$page->ID); // private
                }
                break;
        }
        
        return $name;
    }
    
    function getValue( $id = '', $column = '' ){ 
    
        global $wpdb;
        
        if($id==''){return false;}
        if($column==''){return false;}
        
        $column = ($column=="delay") ? self::F_START_DELAY : $column;
    
        $sql = "SELECT ".$column." from ".$this->table." where ".self::F_ID." = '".$id."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        $value = $row[$column];
  
        return $value;
    }
    
    private function replaceNumericEntities( $value = '' ){
            
        /* entities array */
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
            
        /* replace numeric entities */
        $value = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $value);
        $value = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $value);
        $newvalue = strtr($value, $trans_tbl);
        
        return $newvalue;
    }
    
    function save( $id = '' ){

        global $wpdb;

        $twiz_status = esc_attr(trim($_POST['twiz_'.self::F_STATUS]));
        $twiz_status = ($twiz_status=='true') ? 1 : 0;
        
        $twiz_layer_id = esc_attr(trim($_POST['twiz_'.self::F_LAYER_ID]));
        $twiz_layer_id = ($twiz_layer_id=='') ? '***' : $twiz_layer_id;
        
        $twiz_move_top_pos_a  = esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_A]));
        $twiz_move_left_pos_a = esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_A]));
        $twiz_move_top_pos_b  = esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_B]));
        $twiz_move_left_pos_b = esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_B]));
        $twiz_start_top_pos   = esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS]));
        $twiz_start_left_pos  = esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS]));
        
        $twiz_move_top_pos_a  = ($twiz_move_top_pos_a=='') ? 'NULL' : $twiz_move_top_pos_a;
        $twiz_move_left_pos_a = ($twiz_move_left_pos_a=='') ? 'NULL' : $twiz_move_left_pos_a;
        $twiz_move_top_pos_b  = ($twiz_move_top_pos_b=='') ? 'NULL' : $twiz_move_top_pos_b;
        $twiz_move_left_pos_b = ($twiz_move_left_pos_b=='') ? 'NULL' : $twiz_move_left_pos_b;
        $twiz_start_top_pos   = ($twiz_start_top_pos=='') ? 'NULL' : $twiz_start_top_pos;
        $twiz_start_left_pos  = ($twiz_start_left_pos=='') ? 'NULL' : $twiz_start_left_pos;
        
        /* user syntax auto correction */
        $twiz_options_a = str_replace("'", "\"" , $_POST['twiz_'.self::F_OPTIONS_A]);    
        $twiz_options_b = str_replace("'", "\"" , $_POST['twiz_'.self::F_OPTIONS_B]);
        $twiz_options_a = esc_attr(trim($twiz_options_a));
        $twiz_options_b = esc_attr(trim($twiz_options_b));
        $twiz_options_a = str_replace("=", ":" , $twiz_options_a );
        $twiz_options_b = str_replace("=", ":" , $twiz_options_b );

        $twiz_extra_js_a = str_replace("'", "\"" , $_POST['twiz_'.self::F_EXTRA_JS_A]);    
        $twiz_extra_js_b = str_replace("'", "\"" , $_POST['twiz_'.self::F_EXTRA_JS_B]);
        $twiz_extra_js_a = esc_attr(trim($twiz_extra_js_a));    
        $twiz_extra_js_b = esc_attr(trim($twiz_extra_js_b));
        
        if($id==""){ // add new

            $sql = "INSERT INTO ".$this->table." 
                 (".self::F_SECTION_ID."
                 ,".self::F_STATUS."
                 ,".self::F_LAYER_ID."
                 ,".self::F_START_DELAY."
                 ,".self::F_DURATION."
                 ,".self::F_START_TOP_POS_SIGN."
                 ,".self::F_START_TOP_POS."
                 ,".self::F_START_LEFT_POS_SIGN."
                 ,".self::F_START_LEFT_POS."    
                 ,".self::F_POSITION."    
                 ,".self::F_MOVE_TOP_POS_SIGN_A."
                 ,".self::F_MOVE_TOP_POS_A."
                 ,".self::F_MOVE_LEFT_POS_SIGN_A."
                 ,".self::F_MOVE_LEFT_POS_A."
                 ,".self::F_MOVE_TOP_POS_SIGN_B."
                 ,".self::F_MOVE_TOP_POS_B."
                 ,".self::F_MOVE_LEFT_POS_SIGN_B."
                 ,".self::F_MOVE_LEFT_POS_B."
                 ,".self::F_OPTIONS_A."
                 ,".self::F_OPTIONS_B."
                 ,".self::F_EXTRA_JS_A."
                 ,".self::F_EXTRA_JS_B."         
                 )values('".esc_attr(trim($_POST['twiz_'.self::F_SECTION_ID]))."'
                 ,'".$twiz_status."'
                 ,'".$twiz_layer_id."'
                 ,'0".esc_attr(trim($_POST['twiz_'.self::F_START_DELAY]))."'
                 ,'0".esc_attr(trim($_POST['twiz_'.self::F_DURATION]))."'
                 ,'".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_SIGN]))."'    
                 ,".$twiz_start_top_pos."
                 ,'".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_SIGN]))."'    
                 ,".$twiz_start_left_pos."
                 ,'".esc_attr(trim($_POST['twiz_'.self::F_POSITION]))."'                     
                 ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A]))."'    
                 ,".$twiz_move_top_pos_a."
                 ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A]))."'    
                 ,".$twiz_move_left_pos_a."
                 ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B]))."'                     
                 ,".$twiz_move_top_pos_b."
                 ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B]))."'    
                 ,".$twiz_move_left_pos_b."
                 ,'".$twiz_options_a."'                             
                 ,'".$twiz_options_b."'
                 ,'".$twiz_extra_js_a."'                             
                 ,'".$twiz_extra_js_b."'                 
                 );";
            
            $code = $wpdb->query($sql);
             
            if($code){return $wpdb->insert_id;}
            
            return $code;

        }else{ // update

            $sql = "UPDATE ".$this->table." 
                  SET ".self::F_SECTION_ID." = '".esc_attr(trim($_POST['twiz_'.self::F_SECTION_ID]))."'
                 ,".self::F_STATUS." = '".$twiz_status."'
                 ,".self::F_LAYER_ID." = '".$twiz_layer_id."'
                 ,".self::F_START_DELAY." = '0".esc_attr(trim($_POST['twiz_'.self::F_START_DELAY]))."'
                 ,".self::F_DURATION." = '0".esc_attr(trim($_POST['twiz_'.self::F_DURATION]))."'
                 ,".self::F_START_TOP_POS_SIGN." = '".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_SIGN]))."'
                 ,".self::F_START_TOP_POS." = ".$twiz_start_top_pos."
                 ,".self::F_START_LEFT_POS_SIGN." = '".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_SIGN]))."'
                 ,".self::F_START_LEFT_POS."  = ".$twiz_start_left_pos."
                 ,".self::F_POSITION."  = '".esc_attr(trim($_POST['twiz_'.self::F_POSITION]))."'                 
                 ,".self::F_MOVE_TOP_POS_SIGN_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A]))."'
                 ,".self::F_MOVE_TOP_POS_A." = ".$twiz_move_top_pos_a."
                 ,".self::F_MOVE_LEFT_POS_SIGN_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A]))."'
                 ,".self::F_MOVE_LEFT_POS_A." = ".$twiz_move_left_pos_a."
                 ,".self::F_MOVE_TOP_POS_SIGN_B." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B]))."'
                 ,".self::F_MOVE_TOP_POS_B." = ".$twiz_move_top_pos_b."
                 ,".self::F_MOVE_LEFT_POS_SIGN_B." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B]))."'
                 ,".self::F_MOVE_LEFT_POS_B." = ".$twiz_move_left_pos_b."
                 ,".self::F_OPTIONS_A." = '".$twiz_options_a."'
                 ,".self::F_OPTIONS_B." = '".$twiz_options_b."'
                 ,".self::F_EXTRA_JS_A." = '".$twiz_extra_js_a."'
                 ,".self::F_EXTRA_JS_B." = '".$twiz_extra_js_b."'                 
                  WHERE ".self::F_ID." = '".$id."';";
                    
            $code = $wpdb->query($sql);
                    
            return $code;
        }
    }
    
    function saveValue( $id = '', $column = '', $value = '' ){ 
        
        global $wpdb;
            
            if($id==''){return false;}
            if($column==''){return false;}
            
            $column = ($column=="delay") ? self::F_START_DELAY : $column;
            
            $sql = "UPDATE ".$this->table." 
                    SET ".$column." = '".$value."'                 
                    WHERE id='".$id."';";
            $code = $wpdb->query($sql);
                        
            return $code;
    }
    
    function switchGlobalStatus(){ 

        $newglobalstatus = (get_option('twiz_global_status')=='0') ? '1' : '0'; // swicth the status value
                
        update_option('twiz_global_status', $newglobalstatus);
    
        $htmlstatus = ($newglobalstatus=='1') ? $this->getHtmlImgStatus('global','active') : $this->getHtmlImgStatus('global','inactive');

        return $htmlstatus;
    }
    
    private function getImgGlobalStatus(){ 

        $htmlstatus = (get_option('twiz_global_status')=='1') ? $this->getHtmlImgStatus('global','active') : $this->getHtmlImgStatus('global','inactive');

        return $htmlstatus;
    }
    
    function switchStatus( $id ){ 
    
        global $wpdb;
        
        if($id==''){return false;}
    
        $value = $this->getValue($id, self::F_STATUS);
        
        $newstatus = ($value[self::F_STATUS]=='1') ? '0' : '1'; // swicth the status value
        
        $sql = "UPDATE ".$this->table." 
                SET status = '".$newstatus."'
                WHERE id = '".$id."'";
        $code = $wpdb->query($sql);
        
        if($code){
            $htmlstatus = ($newstatus=='1') ? $this->getHtmlImgStatus($id,'active') : $this->getHtmlImgStatus($id, 'inactive');
        }else{ 
            $htmlstatus = ($value[self::F_STATUS]=='1') ? $this->getHtmlImgStatus($id,'active') : $this->getHtmlImgStatus($id,'inactive');
        }
        
        return $htmlstatus;
    }
    
    function uninstall(){
    
        global $wpdb;

        if ($wpdb->get_var( "show tables like '".$this->table."'" ) == $this->table) {
            $sql = "DROP TABLE ". $this->table;
            $wpdb->query($sql);
        }
        
        delete_option('twiz_db_version');
        delete_option('twiz_global_status');
        delete_option('twiz_sections');
        
        return true;
    }
    
    private function updateSectionMenuKey( $keyid = '', $newid = '' ){
           
        if(($keyid!='')and($newid!='c_')and($newid!='p_')){
            
            $sections = get_option('twiz_sections');
            
            $sections[$keyid] = '';
            $sections[$keyid] = $newid;
        
            update_option('twiz_sections', $sections);
            
        }
        
        return $keyid;
    }
}
?>