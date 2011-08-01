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

require_once(dirname(__FILE__).'/twiz.menu.class.php'); 
    
class Twiz{
    
    /* variable declaration */
    protected $table;
    protected $import_path_message;
    protected $DEFAULT_SECTION;
    private $version;
    private $pluginName;
    private $logobigUrl;
    private $logoUrl;
    private $nonce;    
    public $dbVersion;
    public $pluginUrl;
    public $pluginDir;
    
    /* section constants */ 
    const DEFAULT_SECTION_HOME           = 'home';
    const DEFAULT_SECTION_EVERYWHERE     = 'everywhere';
    const DEFAULT_SECTION_ALL_CATEGORIES = 'allcategories';
    const DEFAULT_SECTION_ALL_PAGES      = 'allpages';
    const DEFAULT_SECTION_ALL_ARTICLES   = 'allarticles'; // allposts
    
    /* element type constants */ 
    const ELEMENT_TYPE_ID    = 'id';
    const ELEMENT_TYPE_CLASS = 'class';
    const ELEMENT_TYPE_NAME  = 'name';
    
    /* status constants*/
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';
    
    /* library order constants */
    const LB_ORDER_UP   = 'up';
    const LB_ORDER_DOWN = 'down';
    
    /* directional image suffix constants */ 
    const DIMAGE_N  = 'n';
    const DIMAGE_NE = 'ne';
    const DIMAGE_E  = 'e';
    const DIMAGE_SE = 'se';
    const DIMAGE_S  = 's';
    const DIMAGE_SW = 'sw';
    const DIMAGE_W  = 'w';
    const DIMAGE_NW = 'nw';
    
    /* action constants */ 
    const ACTION_MENU           = 'menu';
    const ACTION_SAVE           = 'save';
    const ACTION_CANCEL         = 'cancel';
    const ACTION_OPTIONS        = 'options';
    const ACTION_VIEW           = 'view';
    const ACTION_NEW            = 'Add New';
    const ACTION_EDIT           = 'Edit';
    const ACTION_EDIT_TD        = 'tdedit';
    const ACTION_COPY           = 'Copy';
    const ACTION_DELETE         = 'delete';
    const ACTION_STATUS         = 'status';
    const ACTION_IMPORT         = 'import';
    const ACTION_EXPORT         = 'export';
    const ACTION_LIBRARY        = 'library';
    const ACTION_LIBRARY_STATUS = 'libstatus';
    const ACTION_UPLOAD_LIBRARY = 'uploadlib';
    const ACTION_GLOBAL_STATUS  = 'gstatus';
    const ACTION_ADD_SECTION    = 'addsection';
    const ACTION_DELETE_SECTION = 'deletesection';
    const ACTION_DELETE_LIBRARY = 'deletelib';
    const ACTION_ORDER_LIBRARY  = 'orderlib';
    
    /* jquery common options constants */ 
    const JQ_HEIGHT            = 'height';
    const JQ_WITDH             = 'width';
    const JQ_OPACITY           = 'opacity';
    const JQ_FONTSIZE          = 'fontSize';    
    const JQ_MARGINTOP         = 'marginTop';
    const JQ_MARGINBOTTOM      = 'marginBottom';
    const JQ_MARGINLEFT        = 'marginLeft';
    const JQ_MARGINRIGHT       = 'marginRight';
    const JQ_PADDINGTOP        = 'paddingTop';
    const JQ_PADDINGBOTTOM     = 'paddingBottom';    
    const JQ_PADDINGLEFT       = 'paddingLeft';
    const JQ_PADDINGRIGHT      = 'paddingRight';
    const JQ_BORDERWIDTH       = 'borderWidth';
    const JQ_BORDERTOPWIDTH    = 'borderTopWidth';
    const JQ_BORDERBOTTOMWIDTH = 'borderBottomWidth';        
    const JQ_BORDERIGHTWIDTH   = 'borderRightWidth';
    const JQ_BORDERLEFTWIDTH   = 'borderLeftWidth';
    
    /* table field constants */ 
    const F_ID                   = 'id';   
    const F_SECTION_ID           = 'section_id';    
    const F_STATUS               = 'status';  
    const F_TYPE                 = 'type';  
    const F_LAYER_ID             = 'layer_id';  
    const F_ON_EVENT             = 'on_event';  
    const F_START_DELAY          = 'start_delay';  
    const F_DURATION             = 'duration';  
    const F_OUTPUT               = 'output';  
    const F_OUTPUT_POS           = 'output_pos';
    const F_JAVASCRIPT           = 'javascript';
    const F_START_TOP_POS_SIGN   = 'start_top_pos_sign';        
    const F_START_TOP_POS        = 'start_top_pos';  
    const F_START_LEFT_POS_SIGN  = 'start_left_pos_sign';  
    const F_START_LEFT_POS       = 'start_left_pos';  
    const F_POSITION             = 'position';  
    const F_ZINDEX               = 'zindex';  
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
 
    /* key field constants */
    const KEY_FILENAME           = 'filename';  
    const KEY_ORDER              = 'order';  
    
    /* extension constants */
    const EXT_JS   = 'js';
    const EXT_CSS  = 'css';
    const EXT_TWZ  = 'twz'; 
    const EXT_XML  = 'xml'; 
    
    /* on event constants */
    const EV_MANUAL     = 'Manually'; 
    const EV_BLUR       = 'Blur'; 
    const EV_CHANGE     = 'Change'; 
    const EV_CLICK      = 'Click'; 
    const EV_DBLCLICK   = 'DblClick';
    const EV_ERROR      = 'Error'; 
    const EV_FOCUS      = 'Focus'; 
    const EV_FOCUSIN    = 'FocusIn'; 
    const EV_FOCUSOUT   = 'FocusOut';
    const EV_HOVER      = 'Hover';
    const EV_KEYDOWN    = 'KeyDown';
    const EV_KEYPRESS   = 'KeyPress';
    const EV_KEYUP      = 'KeyUp';
    const EV_MOUSEDOWN  = 'MouseDown';
    const EV_MOUSEENTER = 'MouseEnter';
    const EV_MOUSELEAVE = 'MouseLeave';
    const EV_MOUSEMOVE  = 'MouseMove';
    const EV_MOUSEOUT   = 'MouseOut';
    const EV_MOUSEOVER  = 'MouseOver'; 
    const EV_MOUSEUP    = 'MouseUp'; 
    const EV_RESIZE     = 'Resize'; 
    const EV_SCROLL     = 'Scroll'; 
    const EV_SELECT     = 'Select'; 
    const EV_SUBMIT     = 'Submit'; 
    const EV_TOGGLE     = 'Toggle'; 
    const EV_UNLOAD     = 'Unload'; 

    /* on event array */ 
    private $array_element_type = array(self::ELEMENT_TYPE_ID      
                                       ,self::ELEMENT_TYPE_CLASS  
                                       ,self::ELEMENT_TYPE_NAME  
                                       );
    /* section constants array */
    protected $array_default_section = array(self::DEFAULT_SECTION_HOME    
                                            ,self::DEFAULT_SECTION_EVERYWHERE  
                                            ,self::DEFAULT_SECTION_ALL_CATEGORIES
                                            ,self::DEFAULT_SECTION_ALL_PAGES 
                                            ,self::DEFAULT_SECTION_ALL_ARTICLES 
                                            );
    /* on event array */ 
    private $array_on_event = array(self::EV_MANUAL
                                   ,self::EV_BLUR      
                                   ,self::EV_CHANGE    
                                   ,self::EV_CLICK     
                                   ,self::EV_DBLCLICK  
                                   ,self::EV_ERROR      
                                   ,self::EV_FOCUS      
                                   ,self::EV_FOCUSIN    
                                   ,self::EV_FOCUSOUT   
                                   ,self::EV_HOVER      
                                   ,self::EV_KEYDOWN    
                                   ,self::EV_KEYPRESS   
                                   ,self::EV_KEYUP      
                                   ,self::EV_MOUSEDOWN  
                                   ,self::EV_MOUSEENTER 
                                   ,self::EV_MOUSELEAVE 
                                   ,self::EV_MOUSEMOVE  
                                   ,self::EV_MOUSEOUT   
                                   ,self::EV_MOUSEOVER  
                                   ,self::EV_MOUSEUP    
                                   ,self::EV_RESIZE     
                                   ,self::EV_SCROLL     
                                   ,self::EV_SELECT    
                                   ,self::EV_SUBMIT    
                                   ,self::EV_TOGGLE    
                                   ,self::EV_UNLOAD
                                   );
                                      
    /* directional array image suffix */ 
    private $array_arrows = array(self::DIMAGE_N   
                                 ,self::DIMAGE_NE    
                                 ,self::DIMAGE_E  
                                 ,self::DIMAGE_SE     
                                 ,self::DIMAGE_S    
                                 ,self::DIMAGE_SW  
                                 ,self::DIMAGE_W  
                                 ,self::DIMAGE_NW  
                                 );
   
    /* action array used to exclude ajax container */
    private $array_action_excluded = array(self::ACTION_MENU   
                                          ,self::ACTION_SAVE    
                                          ,self::ACTION_CANCEL  
                                          ,self::ACTION_NEW     
                                          ,self::ACTION_EDIT   
                                          ,self::ACTION_COPY
                                          );
                            
    /* jQuery common options array */
    private $array_jQuery_options = array(self::JQ_HEIGHT
                                     ,self::JQ_WITDH
                                     ,self::JQ_OPACITY
                                     ,self::JQ_FONTSIZE
                                     ,self::JQ_MARGINTOP
                                     ,self::JQ_MARGINBOTTOM
                                     ,self::JQ_MARGINLEFT
                                     ,self::JQ_MARGINRIGHT
                                     ,self::JQ_PADDINGTOP
                                     ,self::JQ_PADDINGBOTTOM
                                     ,self::JQ_PADDINGLEFT
                                     ,self::JQ_PADDINGRIGHT
                                     ,self::JQ_BORDERWIDTH
                                     ,self::JQ_BORDERTOPWIDTH
                                     ,self::JQ_BORDERBOTTOMWIDTH
                                     ,self::JQ_BORDERIGHTWIDTH
                                     ,self::JQ_BORDERLEFTWIDTH
                                     );
                
    /* XML MULTI-VERSION mapping values */
    private $array_twz_mapping = array(self::F_SECTION_ID           => 'AA' 
                                      ,self::F_STATUS               => 'BB'
                                      ,self::F_TYPE                 => 'BL' 
                                      ,self::F_LAYER_ID             => 'CC'    
                                      ,self::F_ON_EVENT             => 'DA'
                                      ,self::F_START_DELAY          => 'DD'
                                      ,self::F_DURATION             => 'EE'
                                      ,self::F_OUTPUT               => 'EG'
                                      ,self::F_OUTPUT_POS           => 'EJ'
                                      ,self::F_JAVASCRIPT           => 'EL'
                                      ,self::F_START_TOP_POS_SIGN   => 'FF'
                                      ,self::F_START_TOP_POS        => 'GG'    
                                      ,self::F_START_LEFT_POS_SIGN  => 'HH'
                                      ,self::F_START_LEFT_POS       => 'II'
                                      ,self::F_POSITION             => 'JJ'
                                      ,self::F_ZINDEX               => 'JL'
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

    private $array_fields = array(self::F_ID          
                                 ,self::F_SECTION_ID          
                                 ,self::F_STATUS 
                                 ,self::F_TYPE                                   
                                 ,self::F_LAYER_ID 
                                 ,self::F_ON_EVENT                             
                                 ,self::F_START_DELAY          
                                 ,self::F_DURATION    
                                 ,self::F_OUTPUT  
                                 ,self::F_OUTPUT_POS
                                 ,self::F_JAVASCRIPT   
                                 ,self::F_START_TOP_POS_SIGN 
                                 ,self::F_START_TOP_POS           
                                 ,self::F_START_LEFT_POS_SIGN  
                                 ,self::F_START_LEFT_POS      
                                 ,self::F_POSITION             
                                 ,self::F_ZINDEX     
                                 ,self::F_MOVE_TOP_POS_SIGN_A      
                                 ,self::F_MOVE_TOP_POS_A      
                                 ,self::F_MOVE_LEFT_POS_SIGN_A 
                                 ,self::F_MOVE_LEFT_POS_A      
                                 ,self::F_MOVE_TOP_POS_SIGN_B     
                                 ,self::F_MOVE_TOP_POS_B      
                                 ,self::F_MOVE_LEFT_POS_SIGN_B 
                                 ,self::F_MOVE_LEFT_POS_B     
                                 ,self::F_OPTIONS_A           
                                 ,self::F_OPTIONS_B            
                                 ,self::F_EXTRA_JS_A          
                                 ,self::F_EXTRA_JS_B           
                                 );                                 
                                 
    /* upload import export path constant*/
    const IMPORT_PATH = '/twiz/';       
    const EXPORT_PATH = 'export/';       
  
    /* import max file size constant */ 
    const IMPORT_MAX_SIZE = '2097152';
    
    function __construct(){
    
        global $wpdb;
        
        /* PLUGIN URL */
        $pluginUrl = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        $pluginUrl = str_replace('/includes/','',$pluginUrl);
        
        /* PLUGIN DIR */
        $pluginDir = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        $pluginDir = str_replace('/includes/','',$pluginDir);

        /* Twiz variable configuration */
        $this->pluginUrl  = $pluginUrl;
        $this->pluginDir  = $pluginDir;
        $this->pluginName = __('The Welcomizer', 'the-welcomizer');
        $this->version    = '1.3.7.4';
        $this->dbVersion  = '2.3';
        $this->table      = $wpdb->prefix .'the_welcomizer';
        $this->logoUrl    = '/images/twiz-logo.png';
        $this->logobigUrl = '/images/twiz-logo-big.png';
        $this->nonce      =  wp_create_nonce('twiz-nonce');
        $this->import_path_message = '/wp-content'.self::IMPORT_PATH;
        $this->DEFAULT_SECTION = get_option('twiz_setting_menu');

    }
    
    function twizIt(){
        
        $html = '<div id="twiz_plugin">';
        $html.= '<div id="twiz_background"></div>';
        $html.= '<div id="twiz_master">';
        
        $html.= $this->getHtmlGlobalstatus();
        $html.= $this->getHtmlHeader();
        
        $myTwizMenu  = new TwizMenu(); 
        $html.= $myTwizMenu->getHtmlMenu();
        
        $html.= $this->getHtmlList();
        $html.= $this->getHtmlFooter();
        $html.= $this->getHtmlFooterMenu();
        
        $html.= '</div>';
        $html.= '<div id="twiz_right_panel"></div>';
        $html.= $this->preloadImages();
        $html.= '</div>'; 
        
        return $html;
    }
      
    private function getHtmlGlobalstatus(){
    
        return '<div id="twiz_global_status">'.$this->getImgGlobalStatus().'</div>';
    }
    
    private function getHtmlHeader(){
    
        $twiz_setting_menu_1 = ( ( $this->DEFAULT_SECTION == self::DEFAULT_SECTION_HOME ) || ( $this->DEFAULT_SECTION  == '' ) ) ? ' checked="checked"' : '';
    
        $twiz_setting_menu_2 = ( $this->DEFAULT_SECTION == self::DEFAULT_SECTION_EVERYWHERE ) ? ' checked="checked"' : '';
        
        $header = '<div id="twiz_header">
<div id="twiz_head_logo"><a href="http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/" target="_blank"><img src="'.$this->pluginUrl.$this->logoUrl.'"/></a><span id="twiz_head_version"><a href="http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/" target="_blank">v'.$this->version.'</a></span> </div>
<span id="twiz_head_title"><a href="http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/" target="_blank">'.$this->pluginName.'</a></span>
<span id="twiz_head_addnew"><a class="button-secondary" id="twiz_new" name="twiz_new">'.__('Add New', 'the-welcomizer').'</a></span>
<span id="twiz_head_setting"><input type="radio" name="twiz_setting_menu" id="twiz_setting_menu_1" value="'.self::DEFAULT_SECTION_HOME.'"'.$twiz_setting_menu_1.'> <label for="twiz_setting_menu_1">'.__('Home').' / ' . __('Category').' / ' . __('Page').' / ' . __('Post').'</label> <br>
<input type="radio" name="twiz_setting_menu" id="twiz_setting_menu_2" value="'.self::DEFAULT_SECTION_EVERYWHERE.'"'.$twiz_setting_menu_2.'> <label for="twiz_setting_menu_2">'.__('Everywhere', 'the-welcomizer') . ' / ' . __('All', 'the-welcomizer') . ' ' . __('Categories') . ' / ' . __('All', 'the-welcomizer') . ' '  . __('Pages')  . ' / ' . __('All', 'the-welcomizer')  . ' ' . __('Posts') .'</label></span> 
</div><div class="twiz-clear"></div>
    ';
        
        return $header;
    }
    
    private function getHtmlFooterMenu(){
    
      $import = '<div id="twiz_import_container">'.__('Import', 'the-welcomizer').'</div>';
      $export = '<div id="twiz_export">'.__('Export', 'the-welcomizer').'</div>';
      $library_upload = '<div id="twiz_library_upload">'.__('Upload', 'the-welcomizer').'</div>';
      $library = '<div id="twiz_library">'.__('Library', 'the-welcomizer').'</div>';
      $admin = '<div id="twiz_admin">'.__('Admin', 'the-welcomizer').'</div>';
      
      $html = '<div id="twiz_footer_menu">'.$library_upload.$import.$export.$admin.$library.'</div><div id="twiz_export_url"></div>';
      
      return $html;
      
    }
    
    private function getHtmlFooter(){

        $footer = '
<div class="twiz-clear"></div><div id="twiz_footer">
'.__('Developed by', 'the-welcomizer').' <a href="http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/" target="_blank">'.utf8_encode('Sébastien Laframboise').'</a>. '.__('Licensed under the GPL version 2.0', 'the-welcomizer').'</div>';
        
        return $footer;
    }    

 function getAjaxHeader(){
     $header = '
 jQuery(document).ready(function($) {
 var twiz_hide_MessageDelay = 1234;
 var twiz_view_id = null;
 var twiz_current_section_id = "'.$this->DEFAULT_SECTION.'";
 var twiz_default_section_id = "'.self::DEFAULT_SECTION_HOME.'";
 var twiz_array_view_id = new Array();
 var twiz_import_file = new qq.FileUploader({
        element: document.getElementById("twiz_import_container"),
        action: "'.$this->pluginUrl.'/includes/import/server/php.php",
        debug: false,
        id: "twiz_import",
        label: "'.__('Import', 'the-welcomizer').'",
        allowedExtensions: ["'.self::EXT_TWZ.'", "'.self::EXT_XML.'"],
        sizeLimit: '.self::IMPORT_MAX_SIZE.', // max size   
        minSizeLimit: 1, // min size
        onSubmit: function (){ twiz_import_file.setParams({action: "twiz_ajax_callback", twiz_nonce: "'.$this->nonce.'", twiz_action: "'.self::ACTION_IMPORT.'", twiz_section_id: twiz_current_section_id }); },
        onComplete: function (){$("#twiz_export_url").html(""); twizPostMenu(twiz_current_section_id);},
        messages: {
            typeError: "'.__('{file} has invalid extension. Only {extensions} are allowed.', 'the-welcomizer').'",
            sizeError: "'.__('{file} is too large, maximum file size is {sizeLimit}.', 'the-welcomizer').'",
            minSizeError: "'.__('{file} is too small, minimum file size is {minSizeLimit}.', 'the-welcomizer').'",
            emptyError: "'.__('{file} is empty, please select files again without it.', 'the-welcomizer').'",
            onLeave: "'.__('The files are being uploaded, if you leave now the upload will be cancelled.', 'the-welcomizer').'",
            showMessage: function(message){ alert(message); }
        }
 });      
 var twiz_upload_file = new qq.FileUploader({
        element: document.getElementById("twiz_library_upload"),
        action: "'.$this->pluginUrl.'/includes/import/server/php.php",
        debug: false,
        id: "twiz_upload",
        label: "'.__('Upload', 'the-welcomizer').'",
        allowedExtensions: ["'.self::EXT_JS.'", "'.self::EXT_CSS.'"],
        sizeLimit: '.self::IMPORT_MAX_SIZE.', // max size   
        minSizeLimit: 1, // min size
        onSubmit: function (){ twiz_upload_file.setParams({action: "twiz_ajax_callback", twiz_nonce: "'.$this->nonce.'", twiz_action: "'.self::ACTION_UPLOAD_LIBRARY.'"});},
        onComplete: function (){$("#twiz_export_url").html(""); twizPostLibrary();},
        messages: {
            typeError: "'.__('{file} has invalid extension. Only {extensions} are allowed.', 'the-welcomizer').'",
            sizeError: "'.__('{file} is too large, maximum file size is {sizeLimit}.', 'the-welcomizer').'",
            minSizeError: "'.__('{file} is too small, minimum file size is {minSizeLimit}.', 'the-welcomizer').'",
            emptyError: "'.__('{file} is empty, please select files again without it.', 'the-welcomizer').'",
            onLeave: "'.__('The files are being uploaded, if you leave now the upload will be cancelled.', 'the-welcomizer').'",
            showMessage: function(message){ alert(message); }
        }
 });   
 var bind_twiz_setting_menu = function() {
    $("input[name=twiz_setting_menu]").click(function(){
      twizSettingDisplay($(this).val());
    });
 } 
 function twizSettingDisplay(section){
    switch(section){
        case "'.self::DEFAULT_SECTION_HOME.'":
            $("#twiz_menu-'.self::DEFAULT_SECTION_EVERYWHERE.'").hide();
            $("#twiz_menu").show();
            break;
        case "'.self::DEFAULT_SECTION_EVERYWHERE.'":
            $("#twiz_menu").hide();
            $("#twiz_menu-" + section).show();
            break;
    }
    twiz_current_section_id = section;
    $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu"});
    $("#twiz_menu_" + section).attr({"class" : "twiz-menu twiz-menu-selected"});
    $("#qq_upload_list li").remove(); 
    $("#twiz_export_url").html(""); 
    twizPostMenu(section);
 }
 var bind_twiz_New = function() {
     $("#twiz_new").click(function(){
     twiz_view_id = "edit";
     $(this).fadeOut("fast");
     $("#twiz_container").fadeOut("slow");
        $.post(ajaxurl,  {
        action: "twiz_ajax_callback",
        twiz_action: "'.self::ACTION_NEW.'",
        twiz_section_id: twiz_current_section_id,
        twiz_nonce: "'.$this->nonce.'"
     }, function(data) {
            $("#twiz_container").html(data);
            $("#twiz_container").fadeIn("slow");
            twiz_view_id = null;
            bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
            bind_twiz_More_Options();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
            bind_twiz_DynArrows();
        });
    });
 }
 function twizRightPanel(textid, numid){
         if((twiz_view_id != numid)&&(twiz_view_id!="edit")&&(numid!="global")&&(twiz_current_section_id!="library")){
            twiz_view_id = numid;
            $("#twiz_right_panel").html(\'<div\' + \' class="twiz-panel-loading">\' + \'<img\' + \' src="'.$this->pluginUrl.'/images/twiz-big-loading.gif"></div>\');
            $("#twiz_right_panel").fadeIn("slow");    
            if(twiz_array_view_id[numid]==undefined){
                $.post(ajaxurl, {
                action: "twiz_ajax_callback",
                twiz_nonce: "'.$this->nonce.'", 
                twiz_action: "'.self::ACTION_VIEW.'",
                twiz_id: numid
                }, function(data) {
                    $("#twiz_right_panel").html(data);
                    twiz_array_view_id[numid] = data;
                    bind_twiz_view_show_more();
                });    
            }else{
                $("#twiz_right_panel").html(twiz_array_view_id[numid]);
                bind_twiz_view_show_more();
            }
        }
 }
 var bind_twiz_Status = function() {
    $("img[name^=twiz_status]").mouseover(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(12,textid.length);
        twizRightPanel(textid, numid);
    });   
    $("img[name^=twiz_status]").click(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(12,textid.length);
        var c_action = "'.self::ACTION_STATUS.'";
        if(twiz_current_section_id=="library"){
            c_action = "'.self::ACTION_LIBRARY_STATUS.'";
        }
        if(numid!="global"){
            $(this).hide();
            $("#twiz_img_status_" + numid).fadeIn("slow");        
            $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: c_action,
            twiz_id: numid
            }, function(data) {
                $("#twiz_td_status_" + numid).html(data);
                $("img[name^=twiz_status]").unbind("click");
                twiz_array_view_id[numid]=undefined;
                twiz_view_id = null;
                if((twiz_view_id != numid)&&(twiz_view_id!="edit")&&(twiz_current_section_id!="library")){
                    twiz_view_id = numid;
                    if(twiz_array_view_id[numid]==undefined){
                        $.post(ajaxurl, {
                        action: "twiz_ajax_callback",
                        twiz_nonce: "'.$this->nonce.'", 
                        twiz_action: "'.self::ACTION_VIEW.'",
                        twiz_id: numid
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
            $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: "'.self::ACTION_GLOBAL_STATUS.'"
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
        twizRightPanel(textid, numid);
    });   
    $("img[name^=twiz_edit]").click(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(10,textid.length);
        twiz_view_id = "edit";
        $(this).hide();
        $("#twiz_img_edit_" + numid).fadeIn("slow");    
        $.post(ajaxurl, {
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.self::ACTION_EDIT.'",
        twiz_id: numid,
        twiz_section_id: twiz_current_section_id
        }, function(data) {
            $("#twiz_container").html(data);
            twiz_view_id = null;
            $("#twiz_container").show("slow");
            $("img[name^=twiz_status]").unbind("click");
            $("img[name^=twiz_edit]").unbind("click");
            $("img[name^=twiz_edit]").unbind("mouseover");
            $("img[name^=twiz_copy]").unbind("click");
            $("img[name^=twiz_copy]").unbind("mouseover");              
            $("img[name^=twiz_delete]").unbind("click");
            $("img[name^=twiz_delete]").unbind("mouseover");            
            $("img[name^=twiz_cancel]").unbind("click");
            bind_twiz_Status();bind_twiz_Save();bind_twiz_Cancel();bind_twiz_Number_Restriction();
            bind_twiz_More_Options();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
            bind_twiz_DynArrows();
        });
    });
 }
 var bind_twiz_Copy = function() {
    $("img[name^=twiz_copy]").mouseover(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(10,textid.length);
        twizRightPanel(textid, numid);
    });   
    $("img[name^=twiz_copy]").click(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(10,textid.length);
        twiz_view_id = "edit";
        $(this).hide();
        $("#twiz_img_copy_" + numid).fadeIn("slow");    
        $.post(ajaxurl, {
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.self::ACTION_COPY.'",
        twiz_id: numid,
        twiz_section_id: twiz_current_section_id
        }, function(data) {
            $("#twiz_container").html(data);
            twiz_view_id = null;
            $("#twiz_container").show("slow");
            $("img[name^=twiz_status]").unbind("click");
            $("img[name^=twiz_edit]").unbind("click");
            $("img[name^=twiz_edit]").unbind("mouseover");
            $("img[name^=twiz_copy]").unbind("click");
            $("img[name^=twiz_copy]").unbind("mouseover");            
            $("img[name^=twiz_delete]").unbind("click");
            $("img[name^=twiz_delete]").unbind("mouseover");
            $("img[name^=twiz_cancel]").unbind("click");
            bind_twiz_Status();bind_twiz_Save();bind_twiz_Cancel();bind_twiz_Number_Restriction();
            bind_twiz_More_Options();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
            bind_twiz_DynArrows();
        });
    });
 } 
 var bind_twiz_Delete = function() {
    $("img[name^=twiz_delete]").mouseover(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(12,textid.length);
        twizRightPanel(textid, numid);
    });  
    $("img[name^=twiz_delete]").click(function(){
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            var textid = $(this).attr("name");
            var numid = textid.substring(12,textid.length);    
            var c_action = "'.self::ACTION_DELETE.'";
            if(twiz_current_section_id=="library"){
                c_action = "'.self::ACTION_DELETE_LIBRARY.'";
            }            
            $(this).hide();
            $("#twiz_img_delete_" + numid).fadeIn("slow");
            $("#twiz_right_panel").fadeOut("slow");
            $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: c_action,
            twiz_id: numid 
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
    $.post(ajaxurl, {
    action: "twiz_ajax_callback",
    twiz_nonce: "'.$this->nonce.'", 
    twiz_action: "'.self::ACTION_CANCEL.'",
    twiz_section_id: twiz_current_section_id
    }, function(data) {
        $("#twiz_container").html(data);
        $("#twiz_container").fadeIn("slow");
        twiz_view_id = "";
        $("img[name^=twiz_status]").unbind("click");
        $("img[name^=twiz_cancel]").unbind("click");
        $("img[name^=twiz_save]").unbind("click");
        bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();bind_twiz_DynArrows();
        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
        bind_twiz_More_Options();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
        bind_twiz_Ajax_TD();bind_twiz_TR_View();
    });
   });
 }
 var bind_twiz_Save = function() {
    $("#twiz_save").click(function(){
    $("#twiz_save").attr({"disabled" : "true"});
    $("#twiz_save_img").fadeIn("slow");
    var numid = $("#twiz_id").val();
    $.post(ajaxurl, {
         action: "twiz_ajax_callback",
         twiz_nonce: "'.$this->nonce.'", 
         twiz_action: "'.self::ACTION_SAVE.'", ';
         
         $i=0;
         $count_array = count($this->array_fields);
         
         foreach($this->array_fields as $value){
         
             $comma = ( $count_array != $i ) ? ','."\n" : '';
          
             switch($value){
                    
                 case self::F_SECTION_ID:
                 
                    $header.= 'twiz_'.$value.': twiz_current_section_id'.$comma;
                    break;  
                                   
                 case self::F_ID:
                 
                    $header.= 'twiz_'.$value.': numid'.$comma;
                    break;
                    
                 case self::F_STATUS:
                 
                    $header.= 'twiz_'.$value.': $("#twiz_'.$value.'").is(":checked")'.$comma;
                    break;
                                      
                 default:
                 
                    $header.= 'twiz_'.$value.': $("#twiz_'.$value.'").val()'.$comma;
             }
             
             $i++;
         }
         
        $header.= '}, function(data) {
        $("#twiz_container").html(data);
        $("img[name^=twiz_status]").unbind("click");
        $("img[name^=twiz_cancel]").unbind("click");
        $("img[name^=twiz_save]").unbind("click");
        twiz_array_view_id[numid] = undefined;
        bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
        bind_twiz_More_Options();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
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
    $("#twiz_zindex").keypress(function (e)
    {if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {if( e.which!=45 ) { return false; }}});
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
        var textname = $(this).attr("name");
        if(textname == "twiz_starting_config"){
            $("#twiz_tr_starting_config").toggle();
        }else{
            $(".twiz-table-more-options").toggle();
        }
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
        $("#twiz_td_full_option_" + charid).html(\'<img\' + \' src="'.$this->pluginUrl.'/images/twiz-loading.gif">\');
        $.post(ajaxurl, { 
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.self::ACTION_OPTIONS.'",
        twiz_charid: charid
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
            var optionstring =  $(this).val() + ":";
            $("#twiz_options_" + charid).attr({"value" : curval + optionstring}) 
        }
    });    
  }    
  var bind_twiz_Select_Functions = function() {
    $("#twiz_slc_functions_javascript").change(function(){
        var curval = $("#twiz_javascript").val();
        if($(this).val()!=""){
            if(curval!=""){ var curval = curval + "\n";}
            var optionstring =  $(this).val();
            $("#twiz_javascript").attr({"value" : curval + optionstring}) 
        }
    });   
    $("#twiz_slc_functions_javascript_a").change(function(){
        var curval = $("#twiz_extra_js_a").val();
        if($(this).val()!=""){
            if(curval!=""){ var curval = curval + "\n";}
            var optionstring =  $(this).val();
            $("#twiz_extra_js_a").attr({"value" : curval + optionstring}) 
        }
    }); 
    $("#twiz_slc_functions_javascript_b").change(function(){
        var curval = $("#twiz_extra_js_b").val();
        if($(this).val()!=""){
            if(curval!=""){ var curval = curval + "\n";}
            var optionstring =  $(this).val();
            $("#twiz_extra_js_b").attr({"value" : curval + optionstring}) 
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
                 $("#twiz_ajax_td_loading_" + columnName + "_" + numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + numid + \'[]" id="twiz_img_loading_delay_\' + numid + \'[]" src="'.$this->pluginUrl.'/images/twiz-loading.gif">\');
                $.post(ajaxurl, { 
                action: "twiz_ajax_callback",
                twiz_nonce: "'.$this->nonce.'", 
                twiz_action: "'.self::ACTION_EDIT_TD.'",
                twiz_id: numid, 
                twiz_column: columnName, 
                twiz_value: txtval
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
    if(twiz_current_section_id!="library"){
        var textid = $(this).attr("name");
        var numid = textid.substring(13, textid.length);
        if((twiz_view_id != numid)&&(twiz_view_id!="edit")){
            twiz_view_id = numid;
            $("#twiz_right_panel").html(\'<div\' + \' class="twiz-panel-loading"><img\' + \' src="'.$this->pluginUrl.'/images/twiz-big-loading.gif"></div>\');
            $("#twiz_right_panel").fadeIn("fast");    
            if(twiz_array_view_id[numid]==undefined){
                $.post(ajaxurl, {
                action: "twiz_ajax_callback",
                twiz_nonce: "'.$this->nonce.'", 
                twiz_action: "'.self::ACTION_VIEW.'",
                twiz_id: numid
                }, function(data) {
                    $("#twiz_right_panel").html(data);
                    twiz_array_view_id[numid] = data;
                    bind_twiz_view_show_more();
                });    
            }else{
                $("#twiz_right_panel").html(twiz_array_view_id[numid]);
                bind_twiz_view_show_more();
            }
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
    $("#twiz_delete_menu").click(function(){  
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            if(twiz_current_section_id!=twiz_default_section_id){
                $("#twiz_menu_" + twiz_current_section_id).fadeOut("slow");
            }
            $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: "'.self::ACTION_DELETE_SECTION.'",
            twiz_section_id: twiz_current_section_id
            }, function(data) {                
            if(twiz_current_section_id!=twiz_default_section_id){
                if($("#twiz_slc_sections").prop) {
                  var options = $("#twiz_slc_sections").prop("options");
                } else {
                  var options = $("#twiz_slc_sections").attr("options");
                }
                options[options.length] = new Option($("#twiz_menu_" + twiz_current_section_id).html(), twiz_current_section_id, false, false);
                $("#twiz_menu_" + twiz_current_section_id).remove();
                $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu"});
                $("#twiz_menu_" + twiz_default_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});           
                $("div[id^=twiz_menu_]").unbind("click");
                $("#twiz_add_menu").unbind("click");
                $("#twiz_delete_menu").unbind("click");
                $("#twiz_cancel_section").unbind("click");
                twiz_current_section_id = twiz_default_section_id;
                bind_twiz_Menu();            
            }
             $("#qq_upload_list li").remove();
             $("#twiz_export_url").html(""); 
            twizPostMenu(twiz_default_section_id);
            });
        }
   });    
    $("#twiz_delete_menu_everywhere").click(function(){  
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: "'.self::ACTION_DELETE_SECTION.'",
            twiz_section_id: twiz_current_section_id
            }, function(data) {                
            $("#qq_upload_list li").remove();
            $("#twiz_export_url").html(""); 
            twizPostMenu(twiz_current_section_id);
            });
        }
   });   
    $("div[id^=twiz_menu_]").click(function(){
        var textid = $(this).attr("id");
        twiz_current_section_id = textid.substring(10,textid.length);
        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu"});
        $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
        $("#qq_upload_list li").remove(); 
        $("#twiz_export_url").html(""); 
        twizPostMenu(twiz_current_section_id);
    });
  }    
  function twizPostMenu(section_id){
   $("#twiz_container").slideToggle("fast"); 
   $("#twiz_library_upload").fadeOut("fast");
   $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: "'.self::ACTION_MENU.'",
            twiz_section_id: section_id
            }, function(data) {
                $("#twiz_container").html(data);
                $("#twiz_container").slideToggle("slow");  
                twiz_view_id = null;
                twiz_array_view_id = new Array();
                $("img[name^=twiz_status]").unbind("click");
                $("img[name^=twiz_cancel]").unbind("click");
                $("img[name^=twiz_save]").unbind("click");
                $("img[name^=twiz_edit]").unbind("click");
                $("img[name^=twiz_edit]").unbind("mouseover");
                $("img[name^=twiz_copy]").unbind("click");
                $("img[name^=twiz_copy]").unbind("mouseover");                  
                $("img[name^=twiz_delete]").unbind("click");
                $("img[name^=twiz_delete]").unbind("mouseover");
                bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
                bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
                bind_twiz_More_Options();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
                bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();           
            });
  }
  var bind_twiz_Save_Section = function() {
    $("#twiz_save_section").click(function(){
        var sectionid = $("#twiz_slc_sections").val();
        if((sectionid != "")&&(sectionid != "+++ +++ +++")){
            $("input[id=twiz_save_section]").attr({"disabled" : "true"});
            $("#twiz_add_sections").hide();
            $.post(ajaxurl, {
                 action: "twiz_ajax_callback",
                 twiz_nonce: "'.$this->nonce.'", 
                 twiz_action: "'.self::ACTION_ADD_SECTION.'",
                 twiz_section_id: sectionid
                }, function(data) {
                    $("#twiz_delete_menu").before(data);
                    $("#twiz_container").fadeOut("slow");   
                    $.post(ajaxurl, {
                    action: "twiz_ajax_callback",
                    twiz_nonce: "'.$this->nonce.'", 
                    twiz_action: "'.self::ACTION_MENU.'",
                    twiz_section_id: sectionid
                    }, function(data) {
                        twiz_current_section_id = sectionid; 
                        $("#twiz_slc_sections option[value=\'" + sectionid + "\']").remove();
                        $("#twiz_slc_sections").val("");
                        $("#twiz_container").html(data);
                        $("#twiz_container").slideToggle("slow");  
                        $("div[id^=twiz_menu_]").unbind("click");
                        $("#twiz_add_menu").unbind("click");
                        $("#twiz_delete_menu").unbind("click");
                        $("#twiz_cancel_section").unbind("click");
                        bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
                        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
                        bind_twiz_More_Options();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
                        bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Menu();
                        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu"});
                        $("#twiz_menu_" + sectionid).attr({"class" : "twiz-menu twiz-menu-selected"});
                    });  
                });
        }
    });
  }  
  var bind_twiz_DynArrows = function() {
   $("select[id^=twiz_'.self::F_MOVE_LEFT_POS_SIGN_A.']").change(function(){twizChangeDirectionImage("a");});  
   $("select[id^=twiz_'.self::F_MOVE_TOP_POS_SIGN_A.']").change(function(){twizChangeDirectionImage("a");});   
   $("input[name^=twiz_'.self::F_MOVE_TOP_POS_A.']").blur(function(){twizChangeDirectionImage("a");});
   $("input[name^=twiz_'.self::F_MOVE_LEFT_POS_A.']").blur(function(){twizChangeDirectionImage("a");}); 
   $("select[id^=twiz_'.self::F_MOVE_LEFT_POS_SIGN_B.']").change(function(){twizChangeDirectionImage("b");});
   $("select[id^=twiz_'.self::F_MOVE_TOP_POS_SIGN_B.']").change(function(){twizChangeDirectionImage("b");});
   $("input[name^=twiz_'.self::F_MOVE_TOP_POS_B.']").blur(function(){twizChangeDirectionImage("b");});
   $("input[name^=twiz_'.self::F_MOVE_LEFT_POS_B.']").blur(function(){twizChangeDirectionImage("b");});    
   function twizChangeDirectionImage(ab) {
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
          htmlimage = \'<img\' + \' width="45"\' + \' height="45"\' + \' src="'.$this->pluginUrl.'\' + \'/images/twiz-arrow-\' + direction + \'.png">\';
      }
      $("#twiz_td_arrow_" + ab).html(htmlimage);
    }
   }
    var bind_twiz_Library_New_Order = function() {
        $("img[name^=twiz_new_order_up]").click(function(){
            var textid = $(this).attr("id");
            var numid = textid.substring(18,textid.length);
            $("#twiz_list_td_" + numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + numid + \'[]"\' + \' id="twiz_img_loading_delay_\' + numid + \'[]"\' + \' src="'.$this->pluginUrl.'/images/twiz-loading.gif">\');
            twizOrderLibrary("'.self::LB_ORDER_UP.'", numid);
        });
        $("img[name^=twiz_new_order_down]").click(function(){
            var textid = $(this).attr("id");
            var numid = textid.substring(20, textid.length);
            $("#twiz_list_td_" + numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + numid + \'[]"\' + \' id="twiz_img_loading_delay_\' + numid + \'[]"\' + \' src="'.$this->pluginUrl.'/images/twiz-loading.gif">\');
            twizOrderLibrary("'.self::LB_ORDER_DOWN.'", numid);
        });        
    }
    function twizOrderLibrary(order, numid){
      $.post(ajaxurl, {
       action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.self::ACTION_ORDER_LIBRARY.'",
        twiz_order: order,
        twiz_id : numid
        }, function(data) {                
            $("#twiz_container").html(data);
            $("img[name^=twiz_new_order_up]").unbind("click");
            $("img[name^=twiz_new_order_down]").unbind("click");
            $("img[name^=twiz_new_order]").unbind("click");
            $("img[name^=twiz_delete]").unbind("click");            
            bind_twiz_Status();bind_twiz_Delete();
            bind_twiz_Library_New_Order();
        });   
  }
  var bind_twiz_FooterMenu = function() {
    $("#twiz_export").click(function(){
        $("#twiz_export_url").html(\'<img\' + \' name="twiz_img_loading_export"\' + \' id="twiz_img_loading_export"\' + \' src="'.$this->pluginUrl.'/images/twiz-loading.gif">\');
        $("#qq_upload_list li").remove(); 
        var animid = $("#twiz_id").val();
        if(animid==undefined){
           animid = "";
        }
        $.post(ajaxurl, {
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.self::ACTION_EXPORT.'",
        twiz_section_id: twiz_current_section_id,
        twiz_id: animid
        }, function(data) {
           var superiframe = document.createElement("iframe");
           $("#twiz_export_url").html( data );
           document.body.appendChild(data); 
        });
    });   
    $("#twiz_library").click(function(){
        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu"}); 
        $("#twiz_export").fadeOut("fast");
        $("#twiz_import").fadeOut("fast");
        $("#qq_upload_list li").remove();
        $("#twiz_export_url").html(""); 
        twizPostLibrary();
    });     
    $("#twiz_admin").click(function(){
     $("#twiz_admin").html("'.__('This section is under construction.', 'the-welcomizer').'");
    });     
  }  
  function twizPostLibrary(){
      $("#twiz_container").slideToggle("fast"); 
      $.post(ajaxurl, {
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.self::ACTION_LIBRARY.'"
        }, function(data) {                
            $("#twiz_container").html(data);
            $("#twiz_container").slideToggle("slow"); 
            twiz_current_section_id = "library";
            $("img[name^=twiz_status]").unbind("click");
            $("img[name^=twiz_delete]").unbind("click");            
            bind_twiz_Status();bind_twiz_Delete();
            bind_twiz_Library_New_Order();
        });   
  }
  var bind_twiz_view_show_more = function() {
    $("a[name^=twiz_view_show_more]").click(function(){
        $("[id^=twiz-tr-view-more]").toggle();
    }); 
  }
  $("#twiz_footer_menu").mouseover(function(){
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
  bind_twiz_Status();bind_twiz_New();bind_twiz_Edit();bind_twiz_Copy();bind_twiz_Delete();
  bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
  bind_twiz_More_Options();bind_twiz_Choose_Options();
  bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Menu();
  bind_twiz_Save_Section();bind_twiz_FooterMenu();
  bind_twiz_setting_menu();bind_twiz_Select_Functions();
  $("#twiz_container").slideToggle("slow");
 });';
       return $header;
       
    }
    
    private function createHtmlList( $listarray = array() ){ 
    
        if( count($listarray) == 0 ){return false;}
    
        $opendiv = '';
        $closediv = '';
        $rowcolor = '';
        $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'];
        
        /* ajax container */ 
        if( !in_array($_POST['twiz_action'], $this->array_action_excluded) ){
        
            $opendiv = '<div id="twiz_container">';
            $closediv = '</div>';
        }
        
        /* show element */
        $jsscript_show = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
        $("#twiz_new").fadeIn("fast");
        $("#twiz_add_menu").fadeIn("fast");
        $("#twiz_delete_menu").fadeIn("fast");
        $("#twiz_delete_menu_everywhere").fadeIn("fast");
        $("#twiz_import").fadeIn("fast");
        $("#twiz_export").fadeIn("fast");
  });
 //]]>
</script>';

        $htmllist = $opendiv.'<table class="twiz-table-list" cellspacing="0">';
        
        $htmllist.= '<tr class="twiz-table-list-tr-h"><td class="twiz-td-status twiz-table-list-td-h twiz-td-center">'.__('Status', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-left" nowrap="nowrap">'.__('Element', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-center twiz-td-event" nowrap="nowrap">'.__('Event', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-right twiz-td-delay" nowrap="nowrap">'.__('Delay', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-right twiz-td-duration" nowrap="nowrap">'.__('Duration', 'the-welcomizer').'</td><td class="twiz-table-list-td-h  twiz-td-action twiz-td-right" nowrap="nowrap">'.__('Action', 'the-welcomizer').'</td></tr>';
        
        foreach($listarray as $value){
            
            $rowcolor= ($rowcolor=='twiz-row-color-1') ?'twiz-row-color-2' : 'twiz-row-color-1';
            
            $statushtmlimg = ($value[self::F_STATUS]=='1') ? $this->getHtmlImgStatus($value[self::F_ID], self::STATUS_ACTIVE) : $this->getHtmlImgStatus($value[self::F_ID], self::STATUS_INACTIVE);
            
            /* add a '2x' to the duration if necessary */
            $duration = $this->formatDuration($value[self::F_ID], $value);

            if( $value[self::F_ON_EVENT] != self::EV_MANUAL) {
                $on_event = ( $value[self::F_ON_EVENT] != '' ) ? 'On'.$value[self::F_ON_EVENT] : '-';
            }else{
                $on_event = self::EV_MANUAL;    
            }
            
            $elementype = ($value[self::F_TYPE] == '') ? self::ELEMENT_TYPE_ID : $value[self::F_TYPE];
            
            /* the table row */
            $htmllist.= '
    <tr class="'.$rowcolor.'" name="twiz_list_tr_'.$value[self::F_ID].'" id="twiz_list_tr_'.$value[self::F_ID].'" ><td class="twiz-td-center" id="twiz_td_status_'.$value[self::F_ID].'">'.$statushtmlimg.'</td><td class="twiz-td-left">'.$value[self::F_LAYER_ID].'<span class="twiz-green"> - ['.$elementype.']</span></td><td class="twiz-blue twiz-td-center">'.$on_event.'</td><td class="twiz-td-delay twiz-td-right"><div id="twiz_ajax_td_val_delay_'.$value[self::F_ID].'">'.$value[self::F_START_DELAY].'</div><div id="twiz_ajax_td_loading_delay_'.$value[self::F_ID].'" name="twiz_ajax_td_loading_delay_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_delay_'.$value[self::F_ID].'" name="twiz_ajax_td_edit_delay_'.$value[self::F_ID].'" class="twiz_ajax_td_edit"><input type="text" name="twiz_input_delay_'.$value[self::F_ID].'" id="twiz_input_delay_'.$value[self::F_ID].'" value="'.$value[self::F_START_DELAY].'" maxlength="5"></div></td><td name="twiz_ajax_td_duration_'.$value[self::F_ID].'" id="twiz_ajax_td_duration_'.$value[self::F_ID].'" class="twiz-td-right twiz-td-duration" nowrap="nowrap"><div id="twiz_ajax_td_val_duration_'.$value[self::F_ID].'">'.$duration.'</div><div id="twiz_ajax_td_loading_duration_'.$value[self::F_ID].'" name="twiz_ajax_td_loading_duration_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_duration_'.$value[self::F_ID].'" name="twiz_ajax_td_edit_duration_'.$value[self::F_ID].'" class="twiz_ajax_td_edit"><input type="text" name="twiz_input_duration_'.$value[self::F_ID].'" id="twiz_input_duration_'.$value[self::F_ID].'" value="'.$value[self::F_DURATION].'" maxlength="5"></div></td><td class="twiz-td-right" nowrap="nowrap"><img src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_img_edit_'.$value[self::F_ID].'" name="twiz_img_edit_'.$value[self::F_ID].'" class="twiz-loading-gif-action "><img id="twiz_edit_'.$value[self::F_ID].'" name="twiz_edit_'.$value[self::F_ID].'" alt="'.__('Edit', 'the-welcomizer').'" title="'.__('Edit', 'the-welcomizer').'" src="'.$this->pluginUrl.'/images/twiz-edit.gif" height="25"/> <img src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_img_copy_'.$value[self::F_ID].'" name="twiz_img_copy_'.$value[self::F_ID].'" class="twiz-loading-gif-action "><img id="twiz_copy_'.$value[self::F_ID].'" name="twiz_copy_'.$value[self::F_ID].'" alt="'.__('Copy', 'the-welcomizer').'" title="'.__('Copy', 'the-welcomizer').'" src="'.$this->pluginUrl.'/images/twiz-copy.png" height="25"/> <img class="twiz-loading-gif-action-d" src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_img_delete_'.$value[self::F_ID].'" name="twiz_img_delete_'.$value[self::F_ID].'"><img height="25" src="'.$this->pluginUrl.'/images/twiz-delete.gif" id="twiz_delete_'.$value[self::F_ID].'" name="twiz_delete_'.$value[self::F_ID].'" alt="'.__('Delete', 'the-welcomizer').'" title="'.__('Delete', 'the-welcomizer').'"/></td></tr>';
         
         }
         
         $htmllist.= '</table>'.$closediv.$jsscript_show;
         
         return $htmllist;
    }
    
    function export( $section_id = '', $id = '' ){
  
        $sectionname = '';
        $error = '';
        
        if( $id != '' ) {
        
           $where = " where ".self::F_ID." = '".$id."'";
           $id = "_".$id;
        }else{
        
            $where = ($section_id != '') ? " where ".self::F_SECTION_ID." = '".$section_id."'" : " where ".self::F_SECTION_ID." = '".$this->DEFAULT_SECTION."'";
        }
     
        $listarray = $this->getListArray( $where ); // get all the data

        $filedata = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        
        $filedata .= '<TWIZ>'."\n";

        foreach( $listarray as $value ){

              if ( $sectionname == '' ) {
              
                  $myTwizMenu  = new TwizMenu(); 
                  $sectionname = sanitize_title_with_dashes($myTwizMenu->getSectionName($value[self::F_SECTION_ID]));
              }
              
              $filedata .= '<ROW>'."\n";
              
              $count_array = count($this->array_fields);
                    
              /* loop fields array */
              foreach( $this->array_fields as $key ){
              
                  if(( $key != self::F_ID ) and ( $key != self::F_SECTION_ID )){
             
                     $filedata .= '<'.$this->array_twz_mapping[$key].'>'.$value[$key].'</'.$this->array_twz_mapping[$key].'>'."\n";
                  }
              }
                
              $filedata .= '</ROW>'."\n";
        }

        $filedata .= '</TWIZ>'."\n";
        
        $sectionname = ($sectionname == '') ? $sectionname = $section_id.$id : $sectionname.$id;
       
        $filename = urldecode($sectionname).".".self::EXT_TWZ;
        $filepath = self::IMPORT_PATH.self::EXPORT_PATH;
        $filepathdir = WP_CONTENT_DIR.$filepath;
        $filefullpathdir = $filepathdir.$filename;
        $filefullpathurl = WP_CONTENT_URL.$filepath.$filename;
 
        if (!is_writable($filepathdir)) {
            @mkdir($filepathdir, 755); 
        }
 
        if (is_writable($filepathdir)) {

            if (!$handle = fopen($filefullpathdir, 'w')) {
                $error =  __("Cannot open file", 'the-welcomizer').' ('.$filename.')';
                exit;
            }

            if (fwrite($handle, $filedata) === FALSE) {
                $error = __("Cannot write to file", 'the-welcomizer').' ('.$filename.')';
                exit;
            }

            fclose($handle);

        } else {
           $error =  __("You must first create this directory", 'the-welcomizer').':<br>'.$this->import_path_message;
        }
        
        $html = ($error!='')? '<div class="twiz-red">' . $error .'</div>' : ' <a href="'.$filefullpathurl.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'"><img name="twiz_img_download_export" id="twiz_img_download_export" src="'.$this->pluginUrl.'/images/twiz-download.png"></a><a href="'.$filefullpathurl.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'">'.__('Download file', 'the-welcomizer').'<br>'. $filename .'</a>' ;
        
        return $html;
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
        
        if( $id == '' ){return false;}
         
        $sql = "DELETE from ".$this->table." where ".self::F_ID." = ".$id.";";
        $code = $wpdb->query($sql);
    
        return $code;

    }        
    
    function install(){ 
    
        global $wpdb;

        $sql = "CREATE TABLE ".$this->table." (". 
                self::F_ID . " int NOT NULL AUTO_INCREMENT, ". 
                self::F_SECTION_ID . " varchar(22) NOT NULL default '".self::DEFAULT_SECTION_HOME."', ". 
                self::F_STATUS . " tinyint(3) NOT NULL default 0, ". 
                self::F_TYPE . " varchar(5) NOT NULL default '".self::ELEMENT_TYPE_ID."', ". 
                self::F_LAYER_ID . " varchar(50) NOT NULL default '', ". 
                self::F_ON_EVENT . " varchar(15) NOT NULL default '', ".
                self::F_START_DELAY . " int(5) NOT NULL default 0, ". 
                self::F_DURATION . " int(5) NOT NULL default 0, ". 
                self::F_OUTPUT . " varchar(1) NOT NULL default 'b', ".
                self::F_OUTPUT_POS . " varchar(1) NOT NULL default 'b', ".
                self::F_JAVASCRIPT . " text NOT NULL default '', ". 
                self::F_START_TOP_POS_SIGN . " varchar(1) NOT NULL default '', ". 
                self::F_START_TOP_POS . " int(5) default NULL, ". 
                self::F_START_LEFT_POS_SIGN . " varchar(1) NOT NULL default '', ". 
                self::F_START_LEFT_POS . " int(5) default NULL, ". 
                self::F_POSITION . " varchar(8) NOT NULL default '', ". 
                self::F_ZINDEX . " varchar(5) NOT NULL default '', ". 
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
                "PRIMARY KEY (". self::F_ID . ")
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
                
                
        if ( $wpdb->get_var( "show tables like '".$this->table."'" ) != $this->table ) {

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
            dbDelta($sql);
        
            $code = update_option('twiz_db_version', $this->dbVersion);
            $code = update_option('twiz_global_status', '0');
            $code = update_option('twiz_setting_menu', self::DEFAULT_SECTION_HOME); // home / cat / page
        
        }else{
            
           $dbversion = get_option('twiz_db_version');
           $array_describe = '';       
           
            if( $dbversion != $this->dbVersion ){
                
                
                /* Describe table */
                $describe = "DESCRIBE ".$this->table ."";
                $describe_rows = $wpdb->get_results($describe, ARRAY_A);
                
                foreach($describe_rows as $values){
                
                    $array_describe[] = $values['Field'];
                }
                
                if( !in_array(self::F_SECTION_ID, $array_describe) ){
                
                    /* Add the new field from <= v.1.3.2.3 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_SECTION_ID . " varchar(22) NOT NULL default '".self::DEFAULT_SECTION_HOME."' after ".self::F_ID."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_ON_EVENT, $array_describe) ){    
                
                    /* Add the new field from <= v 1.3.5.7 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".self::F_ON_EVENT." varchar(15) NOT NULL default '' after ".self::F_LAYER_ID."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_JAVASCRIPT, $array_describe) ){
                
                    /* Add the new field from <= v 1.3.5.8 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_JAVASCRIPT . " text NOT NULL default '' after ".self::F_DURATION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_ZINDEX, $array_describe) ){
                
                    /* Add the new field from <= v 1.3.5.8 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_ZINDEX . " varchar(5) NOT NULL default '' after ".self::F_POSITION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_TYPE, $array_describe) ){        
                
                    /* Add the new field from <= v 1.3.6.1 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_TYPE . " varchar(5) NOT NULL default '".self::ELEMENT_TYPE_ID."' after ".self::F_STATUS."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_OUTPUT, $array_describe) ){
                
                    /* Add the new field from <= v 1.3.6.6 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_OUTPUT . " varchar(1) NOT NULL default 'b' after ".self::F_DURATION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_OUTPUT_POS, $array_describe) ){
                
                    /* Add the new field from <= v 1.3.7 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_OUTPUT_POS . " varchar(1) NOT NULL default 'b' after ".self::F_OUTPUT."";
                    $code = $wpdb->query($altersql);
                }
                
                $tsoption = get_option('twiz_setting_menu'); // home / cat / page
                
                if( $tsoption == "" ) {
                
                    $code = update_option('twiz_setting_menu', self::DEFAULT_SECTION_HOME); // home / cat / page
                }
            
                $code = update_option('twiz_db_version', $this->dbVersion);
                
            }
        }
        
        return true;
    }
    
    protected function import( $sectionid = self::DEFAULT_SECTION_HOME ){
    
        $filearray = $this->getImportDirectory(array(self::EXT_TWZ, self::EXT_XML));
        
        foreach( $filearray as $filename ){
            
            if( $code = $this->importData($filename, $sectionid) ){
 
                return true;
            }
        }
        
        return true;
    }
    
    private function importData( $filename = '', $sectionid = self::DEFAULT_SECTION_HOME ){
 
        /* full file path */
        $file = WP_CONTENT_DIR.self::IMPORT_PATH.$filename;

        if ( @file_exists($file) ) {
        
            if( $twz = @simplexml_load_file($file) ){

               /* flip array mapping value to match*/
               $reverse_array_twz_mapping = array_flip($this->array_twz_mapping);
               
                /* loop xml entities */              
                foreach( $twz->children() as $twzrow )
                {                  
                    $row = array();
                    $row[self::F_SECTION_ID] = $sectionid;
                    
                    foreach( $twzrow->children() as $twzfield )
                    {
                        $fieldname = '';
                        $fieldvalue = '';
                        
                        $fieldname = strtr($twzfield->getName(), $reverse_array_twz_mapping);

                        if( $fieldname != "" ) {                        

                            /* get the real name of the field */
                            $fieldvalue = $twzfield;

                            /* build record array */
                            $row[$fieldname] = $fieldvalue;
                           
                        }
                    }

                    /* insert row  */
                    if( !$code = $this->importInsert($row) ){
                        
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
        
        /* field added, older xml are always supported  */
        if( !isset($data[self::F_ON_EVENT]) ) $data[self::F_ON_EVENT] = '' ;
        if( !isset($data[self::F_JAVASCRIPT]) ) $data[self::F_JAVASCRIPT] = '' ;
        if( !isset($data[self::F_ZINDEX]) ) $data[self::F_ZINDEX] = '' ;
        if( !isset($data[self::F_TYPE]) ) $data[self::F_TYPE] = '' ;
        if( !isset($data[self::F_OUTPUT]) ) $data[self::F_OUTPUT] = '' ;
        if( !isset($data[self::F_OUTPUT_POS]) ) $data[self::F_OUTPUT_POS] = '' ;
        
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
      
        $twiz_extra_js_a = str_replace("\\", "\\\\" , $data[self::F_EXTRA_JS_A]);
        $twiz_extra_js_b = str_replace("\\", "\\\\" , $data[self::F_EXTRA_JS_B]);
        $twiz_javascript = str_replace("\\", "\\\\" , $data[self::F_JAVASCRIPT]);
      
        $sql = "INSERT INTO ".$this->table." 
             (".self::F_SECTION_ID."
             ,".self::F_STATUS."
             ,".self::F_TYPE."
             ,".self::F_LAYER_ID."
             ,".self::F_ON_EVENT."
             ,".self::F_START_DELAY."
             ,".self::F_DURATION."
             ,".self::F_OUTPUT."
             ,".self::F_OUTPUT_POS."
             ,".self::F_JAVASCRIPT."
             ,".self::F_START_TOP_POS_SIGN."
             ,".self::F_START_TOP_POS."
             ,".self::F_START_LEFT_POS_SIGN."
             ,".self::F_START_LEFT_POS."    
             ,".self::F_POSITION."    
             ,".self::F_ZINDEX."    
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
             ,'".esc_attr(trim($data[self::F_TYPE]))."'
             ,'".esc_attr(trim($data[self::F_LAYER_ID]))."'
             ,'".esc_attr(trim($data[self::F_ON_EVENT]))."'             
             ,'0".esc_attr(trim($data[self::F_START_DELAY]))."'
             ,'0".esc_attr(trim($data[self::F_DURATION]))."'
             ,'".esc_attr(trim($data[self::F_OUTPUT]))."'
             ,'".esc_attr(trim($data[self::F_OUTPUT_POS]))."'
             ,'".esc_attr($twiz_javascript)."'    
             ,'".esc_attr(trim($data[self::F_START_TOP_POS_SIGN]))."'    
             ,".$twiz_start_top_pos."
             ,'".esc_attr(trim($data[self::F_START_LEFT_POS_SIGN]))."'    
             ,".$twiz_start_left_pos."
             ,'".esc_attr(trim($data[self::F_POSITION]))."'
             ,'".esc_attr(trim($data[self::F_ZINDEX]))."'                      
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
             ,'".esc_attr($twiz_extra_js_a)."'                             
             ,'".esc_attr($twiz_extra_js_b)."'                 
             );";
            
            $code = $wpdb->query($sql);
            
            if($code){return true;}
            
            return $code;
    }
    
    function formatDuration( $id = '', $data = null ){
        
        $data = '';
        
        if($id==''){return false;}
       
        $data = ($data==null) ? $this->getRow($id) : $data;
        
        $duration = (($data[self::F_MOVE_TOP_POS_B] !='' ) or( $data[self::F_MOVE_LEFT_POS_B] !='' ) or( $data[self::F_OPTIONS_B] !='' ) or( $data[self::F_EXTRA_JS_B] !='' )) ? $data[self::F_DURATION].'<b class="twiz-green"> x2</b>' : $data[self::F_DURATION];
        
        return $duration;
    }
    
    protected function getImportDirectory( $extensions = array(self::EXT_TWZ, self::EXT_XML) ){
        
        $filearray = '';
        if ( $handle = @opendir(WP_CONTENT_DIR.self::IMPORT_PATH ) ) {
        
            while ( false !== ( $file = readdir($handle) ) ) {
            
               $pathinfo = pathinfo(WP_CONTENT_DIR.self::IMPORT_PATH.$file);
               if( !isset($pathinfo['extension']) ) $pathinfo['extension'] = '' ;
               $ext = $pathinfo['extension'];
        
                if ( ( $file != "." && $file != ".." ) && (in_array(strtolower($ext), $extensions) ) ) {
                
                    $filearray[] = $file;
                }
            }
            
            closedir($handle);
        }
        
        if( !is_array($filearray) ){ $filearray = array(); }
         
        return $filearray;
    }
        
    function hasMovements( $value = array() ){

        if(($value[self::F_OPTIONS_A]!='') or ($value[self::F_EXTRA_JS_A]!='')
         or($value[self::F_OPTIONS_B]!='') or ($value[self::F_EXTRA_JS_B]!='')
         or($value[self::F_MOVE_TOP_POS_A]!='') or($value[self::F_MOVE_LEFT_POS_A]!='')
         or($value[self::F_MOVE_TOP_POS_B]!='') or ($value[self::F_MOVE_LEFT_POS_B]!='')){
         
             return true;
        }
        
        return false;
    
    }
    
    function hasSomething( $value = array() ){

        if(($value[self::F_OPTIONS_A]!='') or ($value[self::F_EXTRA_JS_A]!='')
         or($value[self::F_OPTIONS_B]!='') or ($value[self::F_EXTRA_JS_B]!='')
         or($value[self::F_MOVE_TOP_POS_A]!='') or($value[self::F_MOVE_LEFT_POS_A]!='')
         or($value[self::F_MOVE_TOP_POS_B]!='') or ($value[self::F_MOVE_LEFT_POS_B]!='')
         or (($value[self::F_JAVASCRIPT]!='') and ($value[self::F_OUTPUT] == 'a' ))){
         
             return true;
        }
        
        return false;
    
    }
    
    function getFrontEnd(){
      
        global $post;
      
        $generatedscript = '';
      
        wp_reset_query(); // fix is_home() due to a custom query.
        
        // get the active data list array
        $listarray_e = $this->getListArray(" where ".self::F_STATUS." = 1 and ".self::F_SECTION_ID." = '".self::DEFAULT_SECTION_EVERYWHERE."' "); 
                
        /* true super logical switch */
        switch( true ){

            case ( is_home() || is_front_page() ):
                
                // get the active data list array
                $listarray_h = $this->getListArray(" where ".self::F_STATUS." = 1 and ".self::F_SECTION_ID." = '".self::DEFAULT_SECTION_HOME."' "); 
            
                $listarray = array_merge($listarray_e, $listarray_h);
                
                break;
                
            case is_category():
                
                $category_id = 'c_'.get_query_var('cat');
                
                // get the active data list array
                $listarray_allc = $this->getListArray(" where ".self::F_STATUS." = 1 and ".self::F_SECTION_ID." = '".self::DEFAULT_SECTION_ALL_CATEGORIES."' "); 
                $listarray_c = $this->getListArray(" where ".self::F_STATUS." = 1 and ".self::F_SECTION_ID." = '".$category_id."' "); 
                
                $listarray_T = array_merge($listarray_e, $listarray_c);
                $listarray = array_merge($listarray_T, $listarray_allc);
                
                break;
                
            case is_page():
            
                $page_id = 'p_'.$post->ID;
                
                // get the active data list array
                $listarray_allp = $this->getListArray(" where ".self::F_STATUS." = 1 and ".self::F_SECTION_ID." = '".self::DEFAULT_SECTION_ALL_PAGES."' "); 
                $listarray_p = $this->getListArray(" where ".self::F_STATUS." = 1 and ".self::F_SECTION_ID." = '".$page_id."' ");             
                
                $listarray_T = array_merge($listarray_e, $listarray_p);
                $listarray = array_merge($listarray_T, $listarray_allp);
                
                break;

            case is_single(): 

                $post_id = 'a_'.$post->ID;
                
                // get the active data list array
                $listarray_alla = $this->getListArray(" where ".self::F_STATUS." = 1 and ".self::F_SECTION_ID." = '".self::DEFAULT_SECTION_ALL_ARTICLES."' ");          
                $listarray_a = $this->getListArray(" where ".self::F_STATUS." = 1 and ".self::F_SECTION_ID." = '".$post_id."' "); 
                
                $listarray_T = array_merge($listarray_e, $listarray_a);
                $listarray = array_merge($listarray_T, $listarray_alla);
                
                break;
                
            case is_feed(): 
                
                return '';
                
                break;       
                
            default:
                
                // get the active data list array
                $listarray = $listarray_e;
        }
        
        /* no data, no output */
        if( count($listarray) == 0 ){
        
            return '';
        }
        
        $gstatus = get_option('twiz_global_status');
        
        if( $gstatus  == '1' ){
       
            /* script header */
            $generatedscript.="<!-- ".$this->pluginName." ".$this->version." -->\n";
            $generatedscript.= '<script type="text/javascript">
jQuery(document).ready(function($){';

            /* this used by javasccript before. */
            $generatedscript .= 'var twiz_this = "";';

            /* generates the code */
            foreach($listarray as $value){   
            
                $repeatname_var = str_replace("-","_", $value[self::F_LAYER_ID])."_".$value[self::F_ID];
                $generatedscript .= 'var twiz_active_'.$repeatname_var.' = 0;';
                                
                if( $value[self::F_OUTPUT] == 'r' ){ // ready 

                    $repeatname = $value[self::F_SECTION_ID]."_".str_replace("-","_",$value[self::F_LAYER_ID])."_".$value[self::F_ID];
                    /* js */    
                    $value[self::F_JAVASCRIPT] = str_replace("$(document).twizRepeat()", "$(document).twizRepeat_".$repeatname.'()' , $value[self::F_JAVASCRIPT]);
                    $value[self::F_JAVASCRIPT] = str_replace("$(document).twiz_", "$(document).twizRepeat_" , $value[self::F_JAVASCRIPT]);
                    $generatedscript .= $value[self::F_JAVASCRIPT];
                }   
                
                if($value[self::F_OUTPUT_POS]=='r'){ // ready
               
                    $newElementFormat = $this->replacejElementType($value[self::F_TYPE], $value[self::F_LAYER_ID]);
                        
                    /* css position */ 
                    $generatedscript .= ($value[self::F_POSITION]!='') ? '$("'. $newElementFormat . '").css("position", "'.$value[self::F_POSITION].'");' : ''; 
                    $generatedscript .= ($value[self::F_ZINDEX]!='') ? '$("'. $newElementFormat . '").css("z-index", "'.$value[self::F_ZINDEX].'");' : ''; 

                    /* starting positions */ 
                    $generatedscript .=($value[self::F_START_LEFT_POS]!='') ? '$("'. $newElementFormat . '").css("left", "'.$value[self::F_START_LEFT_POS_SIGN].$value[self::F_START_LEFT_POS].'px");' : '';
                    $generatedscript .=($value[self::F_START_TOP_POS]!='') ? '$("'. $newElementFormat . '").css("top", "'.$value[self::F_START_TOP_POS_SIGN].$value[self::F_START_TOP_POS].'px");' : '';
               }                
            }
            
            $generatedscript .= '
$.fn.twizReplay = function(){ ';
           
             /* generates the code */
            foreach($listarray as $value){               
               
                $repeatname = $value[self::F_SECTION_ID]."_".str_replace("-","_",$value[self::F_LAYER_ID])."_".$value[self::F_ID];
                $repeatname_var = str_replace("-","_", $value[self::F_LAYER_ID])."_".$value[self::F_ID];
                
                $newElementFormat = $this->replacejElementType($value[self::F_TYPE], $value[self::F_LAYER_ID]);
                
                /* replace numeric entities */
                $value[self::F_JAVASCRIPT] = $this->replaceNumericEntities($value[self::F_JAVASCRIPT]);
                
                /* replace this */
                $value[self::F_JAVASCRIPT] = str_replace("(this)", "(twiz_this)" , $value[self::F_JAVASCRIPT]);
                
                /* repeat animation function */
                $generatedscript .= '
$.fn.twizRepeat_'.$repeatname.' = function(twiz_this){ ';
          
                if(($value[self::F_OUTPUT_POS]=='b')or ($value[self::F_OUTPUT_POS]=='')){ // before
                    
                    /* css position */ 
                    $generatedscript .= ($value[self::F_POSITION]!='') ? '$("'. $newElementFormat . '").css("position", "'.$value[self::F_POSITION].'");' : ''; 
                    $generatedscript .= ($value[self::F_ZINDEX]!='') ? '$("'. $newElementFormat . '").css("z-index", "'.$value[self::F_ZINDEX].'");' : ''; 

                    /* starting positions */ 
                    $generatedscript .=($value[self::F_START_LEFT_POS]!='') ? '$("'. $newElementFormat . '").css("left", "'.$value[self::F_START_LEFT_POS_SIGN].$value[self::F_START_LEFT_POS].'px");' : '';
                    $generatedscript .=($value[self::F_START_TOP_POS]!='') ? '$("'. $newElementFormat . '").css("top", "'.$value[self::F_START_TOP_POS_SIGN].$value[self::F_START_TOP_POS].'px");' : '';
                       
                }
                
                if(($value[self::F_OUTPUT]=='b')or ($value[self::F_OUTPUT]=='')){ // before
                
                    /* js */    
                    $value[self::F_JAVASCRIPT] = str_replace("$(document).twizRepeat()", "$(document).twizRepeat_".$repeatname.'()' , $value[self::F_JAVASCRIPT]);
                    $value[self::F_JAVASCRIPT] = str_replace("$(document).twiz_", "$(document).twizRepeat_" , $value[self::F_JAVASCRIPT]);
                    $generatedscript .= $value[self::F_JAVASCRIPT];
                }
                
                $hasSomething = $this->hasSomething($value);
                
                if($hasSomething == true ){
                    
                    /* start delay */ 
                    $generatedscript .= 'setTimeout(function(){'; 
                

                    if($value[self::F_OUTPUT_POS]=='a'){ // after
                        
                    /* css position */ 
                    $generatedscript .= ($value[self::F_POSITION]!='') ? '$("'. $newElementFormat . '").css("position", "'.$value[self::F_POSITION].'");' : ''; 
                    $generatedscript .= ($value[self::F_ZINDEX]!='') ? '$("'. $newElementFormat . '").css("z-index", "'.$value[self::F_ZINDEX].'");' : ''; 

                    /* starting positions */ 
                    $generatedscript .=($value[self::F_START_LEFT_POS]!='') ? '$("'. $newElementFormat . '").css("left", "'.$value[self::F_START_LEFT_POS_SIGN].$value[self::F_START_LEFT_POS].'px");' : '';
                    $generatedscript .=($value[self::F_START_TOP_POS]!='') ? '$("'. $newElementFormat . '").css("top", "'.$value[self::F_START_TOP_POS_SIGN].$value[self::F_START_TOP_POS].'px");' : '';
                       
                    }
                
                    if( $value[self::F_OUTPUT] == 'a' ){ // after 
                        
                        /* js */    
                        $value[self::F_JAVASCRIPT] = str_replace("$(document).twizRepeat()", "$(document).twizRepeat_".$repeatname.'()' , $value[self::F_JAVASCRIPT]);
                        $value[self::F_JAVASCRIPT] = str_replace("$(document).twiz_", "$(document).twizRepeat_" , $value[self::F_JAVASCRIPT]);
                        $generatedscript .= $value[self::F_JAVASCRIPT];
                    }
                    
                    $value[self::F_OPTIONS_A] = (($value[self::F_OPTIONS_A]!='') and (($value[self::F_MOVE_LEFT_POS_A]!="") or ($value[self::F_MOVE_TOP_POS_A]!=""))) ? ','.$value[self::F_OPTIONS_A] :  $value[self::F_OPTIONS_A];
                    $value[self::F_OPTIONS_A] = str_replace("\n", "," , $value[self::F_OPTIONS_A]);
                
                    /* replace numeric entities */    
                    $value[self::F_OPTIONS_A] = $this->replaceNumericEntities($value[self::F_OPTIONS_A]);

                    $hasMovements = $this->hasMovements($value);
                    
                    if($hasMovements == true ){
                        
                        /* animate jquery a */ 
                        $generatedscript .= '
$("'. $newElementFormat . '").animate({';

                        $generatedscript .= ($value[self::F_MOVE_LEFT_POS_A]!="") ? 'left: "'.$value[self::F_MOVE_LEFT_POS_SIGN_A].'='.$value[self::F_MOVE_LEFT_POS_A].'"' : '';
                        $generatedscript .= (($value[self::F_MOVE_LEFT_POS_A]!="") and ($value[self::F_MOVE_TOP_POS_A]!="")) ? ',' : '';
                        $generatedscript .= ($value[self::F_MOVE_TOP_POS_A]!="") ? 'top: "'.$value[self::F_MOVE_TOP_POS_SIGN_A].'='.$value[self::F_MOVE_TOP_POS_A].'"' : '';
                        $generatedscript .= $value[self::F_OPTIONS_A];
                        
                        $generatedscript .= '}, '.$value[self::F_DURATION].' , function() {';
                
                        /* replace numeric entities */
                        $value[self::F_EXTRA_JS_A] = $this->replaceNumericEntities($value[self::F_EXTRA_JS_A]);
            
                        /* extra js a */    
                        $value[self::F_EXTRA_JS_A] = str_replace("$(document).twizRepeat()", "$(document).twizRepeat_".$repeatname.'()' , $value[self::F_EXTRA_JS_A]);
                        $value[self::F_EXTRA_JS_A] = str_replace("$(document).twiz_", "$(document).twizRepeat_" , $value[self::F_EXTRA_JS_A]);
                        $generatedscript .= $value[self::F_EXTRA_JS_A];
   
                        /* b */ 
                                    
                        $have_b = (($value[self::F_MOVE_TOP_POS_B] !='' ) or ( $value[self::F_MOVE_LEFT_POS_B] !='' ) or ( $value[self::F_OPTIONS_B] !='' ) or ( $value[self::F_EXTRA_JS_B] !='' )) ? true : false;
                        
                        /* add a coma between each options */ 
                        $value[self::F_OPTIONS_B] = (($value[self::F_OPTIONS_B]!='') and ((($value[self::F_MOVE_LEFT_POS_B]!="") or ($value[self::F_MOVE_TOP_POS_B]!="")))) ? ','.$value[self::F_OPTIONS_B] :  $value[self::F_OPTIONS_B];
                        $value[self::F_OPTIONS_B] = str_replace("\n", "," , $value[self::F_OPTIONS_B]);
                        
                        /* replace numeric entities */                
                        $value[self::F_OPTIONS_B] = $this->replaceNumericEntities($value[self::F_OPTIONS_B]);
                        
                        /* animate jquery b */ 
                        
                        $generatedscript .= '
$("'. $newElementFormat . '").animate({';

                        $generatedscript .= ($value[self::F_MOVE_LEFT_POS_B]!="") ? 'left: "'.$value[self::F_MOVE_LEFT_POS_SIGN_B].'='.$value[self::F_MOVE_LEFT_POS_B].'"' : '';
                        $generatedscript .= (($value[self::F_MOVE_LEFT_POS_B]!="") and ($value[self::F_MOVE_TOP_POS_B]!="")) ? ',' : '';
                        $generatedscript .= ($value[self::F_MOVE_TOP_POS_B]!="") ? 'top: "'.$value[self::F_MOVE_TOP_POS_SIGN_B].'='.$value[self::F_MOVE_TOP_POS_B].'"' : '';
                        $generatedscript .=  $value[self::F_OPTIONS_B];
                        
                        $generatedscript .= '}, '.$value[self::F_DURATION].', function() {';
                            
                        /* replace numeric entities */
                        $value[self::F_EXTRA_JS_B] = $this->replaceNumericEntities($value[self::F_EXTRA_JS_B]);
            
                        if($have_b){
                            $generatedscript .= 'twiz_active_'.$repeatname_var.' = 0;';
                        }
                        
                        /* extra js b */    
                        $value[self::F_EXTRA_JS_B] = str_replace("$(document).twizRepeat()", "$(document).twizRepeat_".$repeatname.'()' , $value[self::F_EXTRA_JS_B]);
                        $value[self::F_EXTRA_JS_B] = str_replace("$(document).twiz_", "$(document).twizRepeat_" , $value[self::F_EXTRA_JS_B]);
                        $generatedscript .= $value[self::F_EXTRA_JS_B];
                        
                        /* closing functions */
                        $generatedscript .= '});';
                            
                        if(!$have_b){
                            $generatedscript .= 'twiz_active_'.$repeatname_var.' = 0;';
                        }
                        
                        $generatedscript .= '});';
                    }
                    
                    /* Closing timout */
                    $generatedscript .= '},'.$value[self::F_START_DELAY].');';

                }
                
                if(($hasMovements == false ) or ($hasSomething == false )){
               
                    $generatedscript .= 'twiz_active_'.$repeatname_var.' = 0;';
                }
                
                
                /* closing functions */
                $generatedscript .= '};';
                
                /* trigger on event */
                if($value[self::F_ON_EVENT] != ''){
                
                   if($value[self::F_ON_EVENT] != self::EV_MANUAL){
                       $generatedscript .= '
$("'.$newElementFormat.'").'.strtolower($value[self::F_ON_EVENT]).'(function(){ ';
                       $generatedscript .= 'if(twiz_active_'.$repeatname_var.' == 0) {';
                       $generatedscript .= 'twiz_active_'.$repeatname_var.' = 1;';
                       $generatedscript .= '$(document).twizRepeat_'.$repeatname.'(this);}';
                       $generatedscript .= '});';                    
                   }
                   
                }else{
                
                    /* trigger the animation if not on event */
                    $generatedscript .=  '$(document).twizRepeat_'.$repeatname.'($("'.$newElementFormat.'"));';
                }
            }
            
            /* script footer */
            $generatedscript.= '}
$(document).twizReplay();
});';
            $generatedscript.= '
</script>';
        }
        return $generatedscript;
    }
    
    private function getDirectionalImage( $data = '', $ab = ''){
    
        if($data==''){return '';}
        if($ab==''){return '';}
        $direction = '';
        
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

    function getHtmlForm( $id = '', $action = self::ACTION_NEW, $section_id = ''){ 
    
        $data = '';        
        $opendiv = '';
        $closediv = '';
        $hideimport = '';
        $toggleoptions = '';
        $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'] ;  
        
        if($id!=''){
            
            if(!$data = $this->getRow($id)){return false;}
            
            if( $action == self::ACTION_COPY ){
            
                $hideimport .= '$("#twiz_export").fadeOut("fast");';
            }
        }else{
        
            $hideimport = '$("#twiz_export").fadeOut("fast");';
        }
        
        $jsscript_open = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {';
 
        $jsscript_close = '});
 //]]>
</script>';
        
        /* Toggle starting config */
        $jsscript_starting_config = '$("#twiz_tr_starting_config").toggle();';
        
        /* Toggle More Options */
        $jsscript_moreoptions = '$(".twiz-table-more-options").toggle();';
        
        /* hide element */
        $jsscript_hide = '$("#twiz_new").fadeOut("fast");
$("#twiz_right_panel").fadeOut("fast");
$("#twiz_add_menu").fadeIn("fast");
$("#twiz_import").fadeIn("fast");
$("#twiz_delete_menu").fadeIn("fast");
$("#twiz_delete_menu_everywhere").fadeIn("fast");
$("#qq_upload_list li").remove(); 
$("#twiz_export_url").html(""); 
        '.$hideimport;

        /* Text Area auto expand */
        $jsscript_autoexpand = '
textarea = new Object();
textarea.expand = function(textbox){
    twizsizeOrig(textbox);
    textbox.style.height = (textbox.scrollHeight + 20) + "px";
    textbox.style.width = (textbox.scrollWidth + 8) + "px";
} 
function twizsizeOrig(textbox){
    $(textbox).css({"height":"50px", "width" : "230px"});
}
$("textarea[name^=twiz_javascript]").blur(function (){
   twizsizeOrig(this);
});
 $("textarea[name^=twiz_extra]").blur(function (){
   twizsizeOrig(this);
});';

        /* ajax container */ 
        if(!in_array($_POST['twiz_action'], $this->array_action_excluded)){
             $opendiv = '<div id="twiz_container">';
             $closediv = '</div>';
        }
        
        if( !isset($data[self::F_OPTIONS_A]) ) $data[self::F_OPTIONS_A] = '' ;
        if( !isset($data[self::F_OPTIONS_B]) ) $data[self::F_OPTIONS_B] = '' ;
        if( !isset($data[self::F_EXTRA_JS_A]) ) $data[self::F_EXTRA_JS_A] = '' ;
        if( !isset($data[self::F_EXTRA_JS_B]) ) $data[self::F_EXTRA_JS_B] = '' ;
        if( !isset($data[self::F_STATUS]) ) $data[self::F_STATUS] = '' ;
        if( !isset($data[self::F_POSITION]) ) $data[self::F_POSITION] = '' ;
        if( !isset($data[self::F_ZINDEX]) ) $data[self::F_ZINDEX] = '' ;
        if( !isset($data[self::F_TYPE]) ) $data[self::F_TYPE] = '' ;
        if( !isset($data[self::F_START_TOP_POS_SIGN]) ) $data[self::F_START_TOP_POS_SIGN] = '' ;
        if( !isset($data[self::F_START_LEFT_POS_SIGN]) ) $data[self::F_START_LEFT_POS_SIGN] = '' ;
        if( !isset($data[self::F_MOVE_TOP_POS_SIGN_A]) ) $data[self::F_MOVE_TOP_POS_SIGN_A] = '' ;
        if( !isset($data[self::F_MOVE_TOP_POS_SIGN_B]) ) $data[self::F_MOVE_TOP_POS_SIGN_B] = '' ;
        if( !isset($data[self::F_MOVE_LEFT_POS_SIGN_A]) ) $data[self::F_MOVE_LEFT_POS_SIGN_A] = '' ;
        if( !isset($data[self::F_MOVE_LEFT_POS_SIGN_B]) ) $data[self::F_MOVE_LEFT_POS_SIGN_B] = '' ;
        if( !isset($data[self::F_LAYER_ID]) ) $data[self::F_LAYER_ID] = '' ;
        if( !isset($data[self::F_START_DELAY]) ) $data[self::F_START_DELAY] = '' ;
        if( !isset($data[self::F_ON_EVENT]) ) $data[self::F_ON_EVENT] = '' ;
        if( !isset($data[self::F_DURATION]) ) $data[self::F_DURATION] = '' ;
        if( !isset($data[self::F_OUTPUT]) ) $data[self::F_OUTPUT] = '' ;
        if( !isset($data[self::F_OUTPUT_POS]) ) $data[self::F_OUTPUT_POS] = '' ;
        if( !isset($data[self::F_JAVASCRIPT]) ) $data[self::F_JAVASCRIPT] = '' ;
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
    
        /* toggle starting config if we have values */        
        if(($data[self::F_START_TOP_POS]!='')or($data[self::F_START_LEFT_POS]!='')
         or($data[self::F_ZINDEX]!='')or($data[self::F_JAVASCRIPT]!='')){
            $toggleoptions = $jsscript_starting_config;
        }
        
        /* toggle more options by default if we have values */        
        if(($data[self::F_OPTIONS_A]!='')or($data[self::F_EXTRA_JS_A]!='')
         or($data[self::F_OPTIONS_B]!='')or($data[self::F_EXTRA_JS_B]!='')){
            $toggleoptions .= $jsscript_moreoptions;
        }
    
        /* checked */
        $twiz_status = (( $data[self::F_STATUS] == 1 ) or ( $id == '' )) ? ' checked="checked"' : '';
        
        /* selected */
        $twiz_position['absolute'] = ( $data[self::F_POSITION] == 'absolute' ) ? ' selected="selected"' : '';
        $twiz_position['relative'] = (( $data[self::F_POSITION] == 'relative' ) or ( $id == '' )) ? ' selected="selected"' : '';
        $twiz_position['static']   = ($data[self::F_POSITION] == 'static') ? ' selected="selected"' : '';
      
      
        $twiz_ouput_pos['ready'] = ($data[self::F_OUTPUT_POS] == 'r') ? ' selected="selected"' : '';
        $twiz_ouput_pos['before'] = ($data[self::F_OUTPUT_POS] == 'b') ? ' selected="selected"' : '';
        $twiz_ouput_pos['after'] = ($data[self::F_OUTPUT_POS] == 'a') ? ' selected="selected"' : '';
        $twiz_ouput_pos['ready'] = ($data[self::F_OUTPUT_POS] == '') ? ' selected="selected"' : $twiz_ouput_pos['ready'];
        
        
        $twiz_ouput['ready'] = ($data[self::F_OUTPUT] == 'r') ? ' selected="selected"' : '';
        $twiz_ouput['before'] = ($data[self::F_OUTPUT] == 'b') ? ' selected="selected"' : '';
        $twiz_ouput['after'] = ($data[self::F_OUTPUT] == 'a') ? ' selected="selected"' : '';
        $twiz_ouput['before'] = ($data[self::F_OUTPUT] == '') ? ' selected="selected"' : $twiz_ouput['before'];

        $twiz_start_top_pos_sign['nothing']  = ($data[self::F_START_TOP_POS_SIGN] == '') ? ' selected="selected"' : '';
        $twiz_start_top_pos_sign['-']        = ($data[self::F_START_TOP_POS_SIGN] == '-') ? ' selected="selected"' : '';
        $twiz_start_left_pos_sign['nothing'] = ($data[self::F_START_LEFT_POS_SIGN] == '') ? ' selected="selected"' : '';
        $twiz_start_left_pos_sign['-']       = ($data[self::F_START_LEFT_POS_SIGN] == '-') ? ' selected="selected"' : '';
        
        $twiz_move_top_pos_sign_a['+']  = ($data[self::F_MOVE_TOP_POS_SIGN_A] == '+') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_a['-']  = ($data[self::F_MOVE_TOP_POS_SIGN_A] == '-') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_a['+'] = ($data[self::F_MOVE_LEFT_POS_SIGN_A] == '+') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_a['-'] = ($data[self::F_MOVE_LEFT_POS_SIGN_A] == '-') ? ' selected="selected"' : '';

        $twiz_move_top_pos_sign_b['+']  = ($data[self::F_MOVE_TOP_POS_SIGN_B] == '+') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_b['-']  = ($data[self::F_MOVE_TOP_POS_SIGN_B] == '-') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_b['+'] = ($data[self::F_MOVE_LEFT_POS_SIGN_B] == '+') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_b['-'] = ($data[self::F_MOVE_LEFT_POS_SIGN_B] == '-') ? ' selected="selected"' : '';

        $imagemove_a = $this->getDirectionalImage($data, 'a');
        $imagemove_b = $this->getDirectionalImage($data, 'b');
        
        /* reset id if it's a new copy */
        $id = ($action == self::ACTION_COPY) ? '' : $id;
        
        /* Added to be recognized by the translator */
        $ttcopy = __('Copy', 'the-welcomizer');
        
        $eventlist = $this->getHtmlEventList($data[self::F_ON_EVENT]);
        $element_type_list = $this->getHtmlElementTypeList($data[self::F_TYPE]);
        
        /* creates the form */
        $htmlform = $opendiv.'<table class="twiz-table-form" cellspacing="0" cellpadding="0">
<tr><td class="twiz-form-td-left">'.__('Status', 'the-welcomizer').': <div class="twiz-float-right"><input type="checkbox" id="twiz_'.self::F_STATUS.'" name="twiz_'.self::F_STATUS.'" '.$twiz_status.'></div></td>
<td class="twiz-form-td-right">'.__('Action', 'the-welcomizer').'<div class="twiz-green">'.__($action, 'the-welcomizer').'</div></td></tr>
<tr><td class="twiz-form-td-left">'.__('Trigger by Event', 'the-welcomizer').': <div id="twiz_div_choose_event" class="twiz-float-right">'.$eventlist.'</div><td class="twiz-form-td-right twiz-float-left">'.__('(optional)', 'the-welcomizer').'</td></tr>
<tr><td class="twiz-form-td-left" >'.__('Element', 'the-welcomizer').': <div class="twiz-float-right">'.$element_type_list.'</div></td><td  class="twiz-form-td-right twiz-float-left" rowspan="3" ><input class="twiz-input-text" id="twiz_'.self::F_LAYER_ID.'" name="twiz_'.self::F_LAYER_ID.'" type="text" value="'.$data[self::F_LAYER_ID].'" maxlength="50"></td></tr>
<tr><td class="twiz-form-td-left"></td><td class="twiz-form-td-right"><div class="twiz-float-left">'.__('Start delay', 'the-welcomizer').':</div> <div class="twiz-green twiz-float-right"><input class="twiz-input-small" id="twiz_'.self::F_START_DELAY.'" name="twiz_'.self::F_START_DELAY.'" type="text" value="'.$data[self::F_START_DELAY].'" maxlength="5"><small>1000 = 1 '.__('sec', 'the-welcomizer').'</small></div></td></tr>
<tr><td class="twiz-form-td-left"><a name="twiz_starting_config" id="twiz_starting_config" class="twiz-more-options">'.__('More configurations', 'the-welcomizer').' &#187;</a></td><td class="twiz-form-td-right"><div class="twiz-float-left">'.__('Duration', 'the-welcomizer').':</div> <div class="twiz-green twiz-float-right">2x <input class="twiz-input-small" id="twiz_'.self::F_DURATION.'" name="twiz_'.self::F_DURATION.'" type="text" value="'.$data[self::F_DURATION].'" maxlength="5"><small>1000 = 1 '.__('sec', 'the-welcomizer').'</small></div></td></tr>
<tr id="twiz_tr_starting_config">
    <td valign="top"><hr>
        <table> 
 <tr><td colspan="2" class="twiz-caption"><b>'.__('Starting Positions', 'the-welcomizer').'</b> <select name="twiz_'.self::F_OUTPUT_POS.'" id="twiz_'.self::F_OUTPUT_POS.'">
        <option value="r" '.$twiz_ouput_pos['ready'].'>'.__('OnReady', 'the-welcomizer').'</option>
        <option value="b" '.$twiz_ouput_pos['before'].'>'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a" '.$twiz_ouput_pos['after'].'>'.__('After the delay', 'the-welcomizer').'</option>
        </select></td></tr>        
            <tr><td class="twiz-td-small-left-start" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td>
            <select name="twiz_'.self::F_START_TOP_POS_SIGN.'" id="twiz_'.self::F_START_TOP_POS_SIGN.'">
                <option value="" '.$twiz_start_top_pos_sign['nothing'].'>+</option>
                <option value="-" '.$twiz_start_top_pos_sign['-'].'>-</option>
                </select><input class="twiz-input-small" id="twiz_'.self::F_START_TOP_POS.'" name="twiz_'.self::F_START_TOP_POS.'" type="text" value="'.$data[self::F_START_TOP_POS].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr>
            <tr><td class="twiz-td-small-left-start" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td>
            <select name="twiz_'.self::F_START_LEFT_POS_SIGN.'" id="twiz_'.self::F_START_LEFT_POS_SIGN.'">
                <option value="" '.$twiz_start_left_pos_sign['nothing'].'>+</option>
                <option value="-" '.$twiz_start_left_pos_sign['-'].'>-</option>
                </select><input class="twiz-input-small" id="twiz_'.self::F_START_LEFT_POS.'" name="twiz_'.self::F_START_LEFT_POS.'" type="text" value="'.$data[self::F_START_LEFT_POS].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr>
        <tr><td class="twiz-td-small-left-start">'.__('Position', 'the-welcomizer').':</td><td>
        <select name="twiz_'.self::F_POSITION.'" id="twiz_'.self::F_POSITION.'"><option value="" > </option>
        <option value="absolute" '.$twiz_position['absolute'].'>'.__('absolute', 'the-welcomizer').'</option>
        <option value="relative" '.$twiz_position['relative'].'>'.__('relative', 'the-welcomizer').'</option>
        </select>
        </td></tr>                
          <tr><td class="twiz-td-small-left-start">'.__('Z-Index', 'the-welcomizer').':</td><td>
        <input class="twiz-input-small" id="twiz_'.self::F_ZINDEX.'" name="twiz_'.self::F_ZINDEX.'" type="text" value="'.$data[self::F_ZINDEX].'" maxlength="5">
            </td></tr>            
        </table>
    </td>
    <td valign="top"><hr>
<table> 
 <tr><td class="twiz-caption">
    <b>'.__('JavaScript', 'the-welcomizer').'</b> <select name="twiz_'.self::F_OUTPUT.'" id="twiz_'.self::F_OUTPUT.'">
        <option value="r" '.$twiz_ouput['ready'].'>'.__('OnReady', 'the-welcomizer').'</option>
        <option value="b" '.$twiz_ouput['before'].'>'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a" '.$twiz_ouput['after'].'>'.__('After the delay', 'the-welcomizer').'</option>
        </select>
      </td></tr>            
        </table>
<textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_'.self::F_JAVASCRIPT.'" name="twiz_'.self::F_JAVASCRIPT.'" type="text" >'.$data[self::F_JAVASCRIPT].'</textarea>'.$this->getHtmlFunctionList($id, 'javascript', $section_id).'
    </td>
</tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td valign="top">
        <table>
            <tr><td class="twiz-caption" colspan="3"><b>'.__('First Move', 'the-welcomizer').'</b></td></tr>
            <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td nowrap="nowrap">
            <select name="twiz_'.self::F_MOVE_TOP_POS_SIGN_A.'" id="twiz_'.self::F_MOVE_TOP_POS_SIGN_A.'">
            <option value="+" '.$twiz_move_top_pos_sign_a['+'].'>+</option>
            <option value="-" '.$twiz_move_top_pos_sign_a['-'].'>-</option>
            </select><input class="twiz-input twiz-input-small" id="twiz_move_top_pos_a" name="twiz_move_top_pos_a" type="text" value="'.$data[self::F_MOVE_TOP_POS_A].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td><td rowspan="2" align="center" width="95" id="twiz_td_arrow_a">'.$imagemove_a.'</td></tr>
            <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td nowrap="nowrap">
            <select name="twiz_'.self::F_MOVE_LEFT_POS_SIGN_A.'" id="twiz_'.self::F_MOVE_LEFT_POS_SIGN_A.'">
            <option value="+" '.$twiz_move_left_pos_sign_a['+'].'>+</option>
            <option value="-" '.$twiz_move_left_pos_sign_a['-'].'>-</option>
            </select><input class="twiz-input twiz-input-small" id="twiz_'.self::F_MOVE_LEFT_POS_A.'" name="twiz_'.self::F_MOVE_LEFT_POS_A.'" type="text" value="'.$data[self::F_MOVE_LEFT_POS_A].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr><tr><td></td><td><a name="twiz_more_options_a" id="twiz_more_options_a"  class="twiz-more-options">'.__('More Options', 'the-welcomizer').' &#187;</a></td></tr></table>
            <table class="twiz-table-more-options">
                <tr><td><hr></td></tr><tr><td class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td ><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_'.self::F_OPTIONS_A.'" name="twiz_'.self::F_OPTIONS_A.'" type="text" >'.$data[self::F_OPTIONS_A].'</textarea></td></tr>
                <tr><td id="twiz_td_full_option_a" class="twiz-td-picklist twiz-float-right"><a id="twiz_choose_options_a" name="twiz_choose_options_a">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>
                <tr><td  class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').' <br>
                opacity:0.5<br>
                width:\'200px\'
                </td></tr>        
                <tr><td><hr></td></tr>        
                <tr><td class="twiz-caption">'.__('Extra JavaScript', 'the-welcomizer').'</td></tr><tr><td ><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_'.self::F_EXTRA_JS_A.'" name="twiz_'.self::F_EXTRA_JS_A.'" type="text">'.$data[self::F_EXTRA_JS_A].'</textarea>'.$this->getHtmlFunctionList($id, 'javascript_a', $section_id).'</td></tr><tr><td  class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').'<br>$(this).css({position:\'static\',<br>\'z-index\':\'1\'});</td></tr>
        </table>
</td>
<td valign="top">    
    <table>
        <tr><td class="twiz-caption" colspan="3"><b>'.__('Second Move', 'the-welcomizer').'</b></td></tr>
        <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td nowrap="nowrap">
        <select name="twiz_'.self::F_MOVE_TOP_POS_SIGN_B.'" id="twiz_'.self::F_MOVE_TOP_POS_SIGN_B.'">
        <option value="-" '.$twiz_move_top_pos_sign_b['-'].'>-</option>
        <option value="+" '.$twiz_move_top_pos_sign_b['+'].'>+</option>
        </select><input class="twiz-input twiz-input-small" id="twiz_move_top_pos_b" name="twiz_move_top_pos_b" type="text" value="'.$data[self::F_MOVE_TOP_POS_B].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td><td rowspan="2" align="center" width="95" id="twiz_td_arrow_b">'.$imagemove_b.'</td></tr>
        <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td nowrap="nowrap">
        <select name="twiz_'.self::F_MOVE_LEFT_POS_SIGN_B.'" id="twiz_'.self::F_MOVE_LEFT_POS_SIGN_B.'">
        <option value="-" '.$twiz_move_left_pos_sign_b['-'].'>-</option>
        <option value="+" '.$twiz_move_left_pos_sign_b['+'].'>+</option>
        </select><input class="twiz-input twiz-input-small" id="twiz_'.self::F_MOVE_LEFT_POS_B.'" name="twiz_'.self::F_MOVE_LEFT_POS_B.'" type="text" value="'.$data[self::F_MOVE_LEFT_POS_B].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr><tr><td></td><td><a name="twiz_more_options_b" id="twiz_more_options_b" class="twiz-more-options">'.__('More Options', 'the-welcomizer').' &#187;</a></td></tr>
        </table>
        <table class="twiz-table-more-options">
            <tr><td><hr></td></tr><tr><td class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td ><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_'.self::F_OPTIONS_B.'" name="twiz_'.self::F_OPTIONS_B.'" type="text">'.$data[self::F_OPTIONS_B].'</textarea></td></tr>
            <tr><td  id="twiz_td_full_option_b" class="twiz-td-picklist twiz-float-right"><a id="twiz_choose_options_b" name="twiz_choose_options_b">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>
            <tr><td  class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').' <br> 
                opacity:1<br>
                width:\'100px\'
                </td></tr>        
            <tr><td ><hr></td></tr>
            <tr><td  class="twiz-caption">'.__('Extra JavaScript', 'the-welcomizer').'</td></tr><tr><td ><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_'.self::F_EXTRA_JS_B.'" name="twiz_'.self::F_EXTRA_JS_B.'" type="text" value="">'.$data[self::F_EXTRA_JS_B].'</textarea>'.$this->getHtmlFunctionList($id, 'javascript_b', $section_id).'</td></tr><tr><td  class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').'<br>$(document).twizRepeat();<br>$(document).twizReplay();</td></tr>
        </table>
</td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td class="twiz-td-save" colspan="2"><img src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_save_img" name="twiz_save_img" class="twiz-loading-gif twiz-loading-gif-save"><a name="twiz_cancel" id="twiz_cancel">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save" id="twiz_save" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /><input type="hidden" name="twiz_'.self::F_ID.'" id="twiz_'.self::F_ID.'" value="'.$id.'"></td></tr>
</table>'.$closediv.$jsscript_open.$jsscript_autoexpand.$toggleoptions.$jsscript_hide.$jsscript_close;
    
        return $htmlform;
    }

    function getHtmlView( $id ){ 
        
        $data = '';
        
        if($id != ''){
            if(!$data = $this->getRow($id)){return false;}
        }

        $start_top_pos = ($data[self::F_START_TOP_POS]!='') ? $data[self::F_START_TOP_POS_SIGN].$data[self::F_START_TOP_POS].' '.__('px', 'the-welcomizer') : '';
        $start_left_pos = ($data[self::F_START_LEFT_POS]!='') ? $data[self::F_START_LEFT_POS_SIGN].$data[self::F_START_LEFT_POS].' '.__('px', 'the-welcomizer') : '';
        $move_top_pos_a = ($data[self::F_MOVE_TOP_POS_A]!='') ? $data[self::F_MOVE_TOP_POS_SIGN_A].$data[self::F_MOVE_TOP_POS_A].' '.__('px', 'the-welcomizer') : '';
        $move_left_pos_a = ($data[self::F_MOVE_LEFT_POS_A]!='') ? $data[self::F_MOVE_LEFT_POS_SIGN_A].$data[self::F_MOVE_LEFT_POS_A].' '.__('px', 'the-welcomizer') : '';
        $move_top_pos_b = ($data[self::F_MOVE_TOP_POS_B]!='') ? $data[self::F_MOVE_TOP_POS_SIGN_B].$data[self::F_MOVE_TOP_POS_B].' '.__('px', 'the-welcomizer') : '';
        $move_left_pos_b = ($data[self::F_MOVE_LEFT_POS_B]!='') ? $data[self::F_MOVE_LEFT_POS_SIGN_B].$data[self::F_MOVE_LEFT_POS_B].' '.__('px', 'the-welcomizer') : '';
        
        $titleclass = ($data[self::F_STATUS]=='1') ?'twiz-status-green' : 'twiz-status-red';
        
        $imagemove_a = $this->getDirectionalImage($data, 'a');
        $imagemove_b = $this->getDirectionalImage($data, 'b');
        $elementype = ($data[self::F_TYPE] == '') ? self::ELEMENT_TYPE_ID : $data[self::F_TYPE];
        
        
        $output_starting_pos = $this->getOutputLabel($data[self::F_OUTPUT_POS]);
        $output_javascript = $this->getOutputLabel($data[self::F_OUTPUT]);
            
        
        /* creates the view */
        $htmlview = '<table class="twiz-table-view" cellspacing="0" cellpadding="0">
        <tr><td class="twiz-view-td-left twiz-bold" valign="top"><span class="'.$titleclass.'">'.$elementype.'</span> = '.$data[self::F_LAYER_ID].'</td><td class="twiz-view-td-right" nowrap="nowrap">
        <a id="twiz_view_show_more_'.$data[self::F_ID].'" name="twiz_view_show_more_'.$data[self::F_ID].'" class="twiz-more-options">'.__('More configurations', 'the-welcomizer').' &#187;</a></td></tr>
<tr id="twiz-tr-view-more-'.$data[self::F_ID].'" class="twiz-tr-view-more"><td colspan="2"><hr></td></tr>
    <tr id="twiz-tr-view-more-'.$data[self::F_ID].'" class="twiz-tr-view-more"><td valign="top">
    <table>
     <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('Starting Positions', 'the-welcomizer').'</b>
     <div class="twiz-green">'.$output_starting_pos.'</div>
&nbsp;</td></tr>
        <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td>'.$start_top_pos.'</td></tr>
        <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td>'.$start_left_pos.'</td></tr>
        <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Position', 'the-welcomizer').':</td><td>'.' '.$data[self::F_POSITION].'</td></tr>
         <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Z-Index', 'the-welcomizer').':</td><td>'.' '.$data[self::F_ZINDEX].'</td></tr>
    </table>
    </td>
    <td valign="top">
    <table>
     <tr><td class="twiz-caption"  nowrap="nowrap"><b>'.__('JavaScript', 'the-welcomizer').'</b>
     <div class="twiz-green">'.$output_javascript.'</div>
&nbsp;</td></tr>
         <tr><td>'.str_replace("\n", "<br>", $data[self::F_JAVASCRIPT]).'</td></tr>
    </table>    
    </td>
</tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td valign="top">
        <table>
            <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('First Move', 'the-welcomizer').'</b></td></tr>
            <tr><td class="twiz-view-td-small-left" valign="top" height="20" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_top_pos_a .'</td><td rowspan="2" align="center" width="95">'.$imagemove_a.'</td></tr>
            <tr><td class="twiz-view-td-small-left"  nowrap="nowrap" valign="top">'.__('Left', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_left_pos_a .'</td></tr></table>
            <table class="twiz-view-table-more-options">
                <tr><td><hr></td></tr>
                <tr><td>'.str_replace("\n", "<br>",$data[self::F_OPTIONS_A]).'</td></tr>    
                <tr><td><hr></td></tr>        
                <tr><td>'.str_replace("\n", "<br>",$data[self::F_EXTRA_JS_A]).'</td></tr>
        </table>
</td>
<td valign="top">    
    <table>
        <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('Second Move', 'the-welcomizer').'</b></td></tr>
        <tr><td class="twiz-view-td-small-left" valign="top" height="20" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_top_pos_b.'</td><td rowspan="2" align="center" width="95">'.$imagemove_b.'</td></tr>
        <tr><td class="twiz-view-td-small-left" nowrap="nowrap" valign="top">'.__('Left', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_left_pos_b .'</td></tr>
        </table>
        <table class="twiz-view-table-more-options">
            <tr><td><hr></td></tr>
            <tr><td>'.str_replace("\n", "<br>", $data[self::F_OPTIONS_B]).'</td></tr>
            <tr><td><hr></td></tr>
            <tr><td>'.str_replace("\n", "<br>", $data[self::F_EXTRA_JS_B]).'</td></tr>
        </table>
</td></tr>
</table>';
    
        return $htmlview;
    }
    
    private function getListArray( $where = '' ){ 
    
        global $wpdb;

        $sql = "SELECT * from ".$this->table.$where." order by ".self::F_ON_EVENT.", ".self::F_START_DELAY.", ".self::F_LAYER_ID;
      
        $rows = $wpdb->get_results($sql, ARRAY_A);
        
        return $rows;
    }

    function getHtmlList( $section_id = '' ){ 
       
        $section_id = ( $section_id == '' ) ? $this->DEFAULT_SECTION : $section_id;
        $code = $this->updateSettingMenu( $section_id );
        
        /* from the menu */ 
        $where = " where ".self::F_SECTION_ID." = '".$section_id."'";
      
        $listarray = $this->getListArray( $where ); // get all the data
        if(count($listarray)==0){ // if, display the default new form
            
            return $this->getHtmlForm('', self::ACTION_NEW, $section_id); 
            
        }else{ // else display the list
        
            return $this->createHtmlList($listarray); // private
        }
        
        
    }
   
    protected function getHtmlImgStatus( $id = '', $status = ''){
    
        if($id==''){ return ''; }
        if($status==''){ return ''; }
    
        return '<img src="'.$this->pluginUrl.'/images/twiz-'.$status.'.png" id="twiz_status_'.$id.'" name="twiz_status_'.$id.'" title="'.$id.'"><img src="'.$this->pluginUrl.'/images/twiz-save.gif" id="twiz_img_status_'.$id.'" name="twiz_img_status_'.$id.'" class="twiz-loading-gif">';

    }
    
    private function getHtmlFunctionList( $id = '', $name = '', $section_id = ''){

        $where = ($section_id!='') ? " where ".self::F_SECTION_ID." = '".$section_id."'"." and ".self::F_ID." <> '".$id."'" : '';
        
        $listarray = $this->getListArray( $where ); // get all the data
        
        if( $name == '' ){ return ''; }
        if( count($listarray) == 0 ){return '';}
        
        $select = '<select class="twiz-slc-options" name="twiz_slc_functions_'.$name.'" id="twiz_slc_functions_'.$name.'">';
        $select .= '<option value="">'.__('All animations', 'the-welcomizer').'</option>';
        
        foreach ( $listarray as $value ){
       
            $functionnames = 'twiz_'.$value[self::F_SECTION_ID].'_'.str_replace("-","_",$value[self::F_LAYER_ID]).'_'.$value[self::F_ID].'();';
            $select .= '<option value="$(document).'.$functionnames.'">'.$functionnames.'</option>';
        }
        
        $select .= '</select>';
        
        return $select;
    }
    
    private function getHtmlElementTypeList( $type = self::ELEMENT_TYPE_ID ){
        
        $select = '<select name="twiz_'.self::F_TYPE.'" id="twiz_'.self::F_TYPE.'">';
         
        foreach ($this->array_element_type as $value){

            $selected = ($type == $value) ? ' selected="selected"' : '';
            
            $select .= '<option value="'.$value.'"'.$selected.'>'.$value.' =</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }  
    
    private function getHtmlEventList( $event = '' ){
        
        $select = '<select name="twiz_'.self::F_ON_EVENT.'" id="twiz_'.self::F_ON_EVENT.'">';
        $select .= '<option value=""></option>';
            
        foreach ($this->array_on_event as $value){

            $selected = ($event == $value) ? ' selected="selected"' : '';
            $on = ($value != self::EV_MANUAL) ? 'On': '';
            $select .= '<option value="'.$value.'"'.$selected.'>'.$on.$value.'</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }  
    
    function getHtmlOptionList( $charid = '' ){
        
        if( $charid == '' ){ return ''; }
        
        $select = '<select class="twiz-slc-options" name="twiz_slc_options_'.$charid.'" id="twiz_slc_options_'.$charid.'">';
            
        $select .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';
            
        foreach ( $this->array_jQuery_options as $value ){
                    
            $select .= '<option value="'.$value.'">'.$value.'</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }    
    
    private function getOutputLabel( $type = '' ){
    
        switch($type){
            case 'r':
                return '('.__('OnReady', 'the-welcomizer').')';
            break;
            case 'b':
                return '('.__('Before the delay', 'the-welcomizer').')';
            break;
            case 'a':
                return '('.__('After the delay', 'the-welcomizer').')';    
            break;
        }
        
        return '';
    }
    
    private function getRow( $id = '' ){ 
    
        global $wpdb;
        
        if( $id == '' ){ return false; }
    
        $sql = "SELECT * from ".$this->table." where ".self::F_ID." = '".$id."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
        
        return $row;
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
    
    private function preloadImages(){
    
        $html = '';
        
        foreach( $this->array_arrows as $value ) {
          
          $html .='<img width="45" height="45" src="'.$this->pluginUrl.'/images/twiz-arrow-'.$value.'.png" class="twiz-preload-images">';
        
        }
        
        $html .'<img src="http://www.sebastien-laframboise.com/go-green/wp-content/plugins/the-welcomizer/images/twiz-download.png"  class="twiz-preload-images">';
    
        return $html;
    
    }
    
    private function replacejElementType ( $type = '', $element = '' ){
            
            switch($type){
            
                case self::ELEMENT_TYPE_ID:
                
                    return '#'.$element;
     
                break;

                case self::ELEMENT_TYPE_CLASS:
                
                    return '.'.$element;
                    
                break;

                case self::ELEMENT_TYPE_NAME:
                    
                    return '[name='.$element.']';
                    
                break; 
                
            }
            
            return '#'.$element;
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

    private function updateSettingMenu( $section_id = '' ){
    
        /* update setting menu */
        switch($section_id){
            case self::DEFAULT_SECTION_HOME:
                $code = update_option('twiz_setting_menu', self::DEFAULT_SECTION_HOME);
                break;
            case self::DEFAULT_SECTION_EVERYWHERE:
                $code = update_option('twiz_setting_menu', self::DEFAULT_SECTION_EVERYWHERE);
                break;
        }
        
        return true;
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
        $twiz_options_a = esc_attr(trim($_POST['twiz_'.self::F_OPTIONS_A]));
        $twiz_options_b = esc_attr(trim($_POST['twiz_'.self::F_OPTIONS_B]));

        $twiz_extra_js_a = esc_attr(trim( $_POST['twiz_'.self::F_EXTRA_JS_A]));    
        $twiz_extra_js_b = esc_attr(trim($_POST['twiz_'.self::F_EXTRA_JS_B]));
        
        $twiz_javascript = esc_attr(trim($_POST['twiz_'.self::F_JAVASCRIPT]));
        
        if( $id == "" ){ // add new

            $sql = "INSERT INTO ".$this->table." 
                  (".self::F_SECTION_ID."
                  ,".self::F_STATUS."
                  ,".self::F_TYPE."
                  ,".self::F_LAYER_ID."
                  ,".self::F_ON_EVENT."                  
                  ,".self::F_START_DELAY."
                  ,".self::F_DURATION."
                  ,".self::F_OUTPUT."
                  ,".self::F_OUTPUT_POS."
                  ,".self::F_JAVASCRIPT."
                  ,".self::F_START_TOP_POS_SIGN."
                  ,".self::F_START_TOP_POS."
                  ,".self::F_START_LEFT_POS_SIGN."
                  ,".self::F_START_LEFT_POS."    
                  ,".self::F_POSITION."    
                  ,".self::F_ZINDEX."    
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
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_TYPE]))."'
                  ,'".$twiz_layer_id."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_ON_EVENT]))."'
                  ,'0".esc_attr(trim($_POST['twiz_'.self::F_START_DELAY]))."'
                  ,'0".esc_attr(trim($_POST['twiz_'.self::F_DURATION]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT_POS]))."'
                  ,'".$twiz_javascript."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_SIGN]))."'    
                  ,".$twiz_start_top_pos."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_SIGN]))."'    
                  ,".$twiz_start_left_pos."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_POSITION]))."'                     
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_ZINDEX]))."'
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
             
            if( $code ){ 
            
                return $wpdb->insert_id;
            }
            
            return $code;

        }else{ // update

            $sql = "UPDATE ".$this->table." 
                  SET ".self::F_SECTION_ID." = '".esc_attr(trim($_POST['twiz_'.self::F_SECTION_ID]))."'
                 ,".self::F_STATUS." = '".$twiz_status."'
                 ,".self::F_TYPE."  = '".esc_attr(trim($_POST['twiz_'.self::F_TYPE]))."' 
                 ,".self::F_LAYER_ID." = '".$twiz_layer_id."'
                 ,".self::F_ON_EVENT." = '".esc_attr(trim($_POST['twiz_'.self::F_ON_EVENT]))."'
                 ,".self::F_START_DELAY." = '0".esc_attr(trim($_POST['twiz_'.self::F_START_DELAY]))."'
                 ,".self::F_DURATION." = '0".esc_attr(trim($_POST['twiz_'.self::F_DURATION]))."'
                 ,".self::F_OUTPUT." = '".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT]))."'
                 ,".self::F_OUTPUT_POS." = '".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT_POS]))."'
                 ,".self::F_JAVASCRIPT." = '".$twiz_javascript."' 
                 ,".self::F_START_TOP_POS_SIGN." = '".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_SIGN]))."'
                 ,".self::F_START_TOP_POS." = ".$twiz_start_top_pos."
                 ,".self::F_START_LEFT_POS_SIGN." = '".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_SIGN]))."'
                 ,".self::F_START_LEFT_POS."  = ".$twiz_start_left_pos."
                 ,".self::F_POSITION."  = '".esc_attr(trim($_POST['twiz_'.self::F_POSITION]))."'                 
                 ,".self::F_ZINDEX."  = '".esc_attr(trim($_POST['twiz_'.self::F_ZINDEX]))."' 
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
            
            if( $id == '' ){return false;}
            if( $column == '' ){return false;}
            
            $column = ($column=="delay") ? self::F_START_DELAY : $column;
            
            $sql = "UPDATE ".$this->table." 
                    SET ".$column." = '".$value."'                 
                    WHERE ".self::F_ID." = '".$id."';";
            $code = $wpdb->query($sql);
                        
            return $code;
    }
    
    function switchGlobalStatus(){ 

        $gstatus = get_option('twiz_global_status');
        
        $newglobalstatus = ($gstatus == '0') ? '1' : '0'; // swicth the status value
                
        $code = update_option('twiz_global_status', $newglobalstatus);
    
        $htmlstatus = ($newglobalstatus=='1') ? $this->getHtmlImgStatus('global', self::STATUS_ACTIVE) : $this->getHtmlImgStatus('global', self::STATUS_INACTIVE);

        return $htmlstatus;
    }
    
    private function getImgGlobalStatus(){ 
        
        $gstatus = get_option('twiz_global_status');
        
        $htmlstatus = ($gstatus == '1') ? $this->getHtmlImgStatus('global', self::STATUS_ACTIVE) : $this->getHtmlImgStatus('global', self::STATUS_INACTIVE);

        return $htmlstatus;
    }
    
    function switchStatus( $id ){ 
    
        global $wpdb;
        
        if( $id == '' ){return false;}
    
        $value = $this->getValue($id, self::F_STATUS);
        
        $newstatus = ($value[self::F_STATUS]=='1') ? '0' : '1'; // swicth the status value
        
        $sql = "UPDATE ".$this->table." 
                SET ".self::F_STATUS." = '".$newstatus."'
                WHERE ".self::F_ID." = '".$id."'";

        $code = $wpdb->query($sql);
        
        if($code){
        
            $htmlstatus = ($newstatus=='1') ? $this->getHtmlImgStatus($id, self::STATUS_ACTIVE) : $this->getHtmlImgStatus($id, self::STATUS_INACTIVE);
        }else{ 
        
            $htmlstatus = ($value[self::F_STATUS]=='1') ? $this->getHtmlImgStatus($id, self::STATUS_ACTIVE) : $this->getHtmlImgStatus($id, self::STATUS_INACTIVE);
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
        delete_option('twiz_setting_menu');
        delete_option('twiz_sections');
        delete_option('twiz_library');
        
        return true;
    }
}
?>