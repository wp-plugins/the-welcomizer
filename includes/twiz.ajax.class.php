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
    
class TwizAjax extends Twiz{

    function __construct(){
    
        parent::__construct();
    }

    function getAjaxHeader(){
    $header = '
 jQuery(document).ready(function($) {
 var twiz_skin =  "'.$this->skin.'";
 if((twiz_skin == "")||(twiz_skin = "'.parent::SKIN_PATH.'")){ twiz_skin = "'.parent::SKIN_PATH.''.parent::DEFAULT_SKIN.'";}
 var twiz_view_id = null;
 var twiz_current_section_id = "'.$this->DEFAULT_SECTION.'";
 if(twiz_current_section_id == ""){ twiz_current_section_id = "'.parent::DEFAULT_SECTION_HOME.'";}
 var twiz_default_section_id = "'.parent::DEFAULT_SECTION_HOME.'";
 var twiz_array_view_id = new Array();
 var twiz_import_file = new qq.FileUploader({
    element: document.getElementById("twiz_import_container"),
    action: "'.$this->pluginUrl.'/includes/import/server/php.php",
    debug: false,
    id: "twiz_import",
    label: "'.__('Import', 'the-welcomizer').'",
    allowedExtensions: ["'.parent::EXT_TWZ.'", "'.parent::EXT_XML.'"],
    sizeLimit: '.parent::IMPORT_MAX_SIZE.', // max size   
    minSizeLimit: 1, // min size
    onSubmit: function (){ twiz_import_file.setParams({action: "twiz_ajax_callback", twiz_nonce: "'.$this->nonce.'", twiz_action: "'.parent::ACTION_IMPORT.'", twiz_section_id: twiz_current_section_id }); },
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
    allowedExtensions: ["'.parent::EXT_JS.'", "'.parent::EXT_CSS.'"],
    sizeLimit: '.parent::IMPORT_MAX_SIZE.', // max size   
    minSizeLimit: 1, // min size
    onSubmit: function (){ twiz_upload_file.setParams({action: "twiz_ajax_callback", twiz_nonce: "'.$this->nonce.'", twiz_action: "'.parent::ACTION_UPLOAD_LIBRARY.'"});},
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
 var bind_twiz_New = function() {
     $("#twiz_new").click(function(){
     $("#twiz_add_sections").html("");  
     twiz_view_id = "edit";
     $(this).fadeOut("fast");
     $("#twiz_container").slideToggle("fast");
        $.post(ajaxurl,  {
        action: "twiz_ajax_callback",
        twiz_action: "'.parent::ACTION_NEW.'",
        twiz_section_id: twiz_current_section_id,
        twiz_nonce: "'.$this->nonce.'"
     }, function(data) {
            $("#twiz_container").html(data);
            $("#twiz_container").slideToggle("fast");
            twiz_view_id = null;
            bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
            bind_twiz_More_Options();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
            bind_twiz_DynArrows();
        });
    });
 }
 function twizRightPanel(twiz_id){
    if((twiz_view_id != twiz_id)&&(twiz_view_id!="edit")&&(twiz_id!="global")&&(twiz_current_section_id!="library")){
        twiz_view_id = twiz_id;
        $("#twiz_right_panel").html(\'<div\' + \' class="twiz-panel-loading">\' + \'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-big-loading.gif"></div>\');
        $("#twiz_right_panel").fadeIn("fast");    
        if(twiz_array_view_id[twiz_id]==undefined){
            $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: "'.parent::ACTION_VIEW.'",
            twiz_id: twiz_id
            }, function(data) {
                $("#twiz_right_panel").html(data);
                twiz_array_view_id[twiz_id] = data;
                bind_twiz_view_show_more();
            });    
        }else{
            $("#twiz_right_panel").html(twiz_array_view_id[twiz_id]);
            bind_twiz_view_show_more();
        }
    }
 }
 var bind_twiz_Status = function() { 
    $("img[name^=twiz_status_img]").click(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(16,textid.length);
        var c_action = "'.parent::ACTION_STATUS.'";
        if(twiz_current_section_id=="library"){
            c_action = "'.parent::ACTION_LIBRARY_STATUS.'";
        }
        var menuid = numid.substring(0,5);
        switch(menuid){
            case "vmenu":
            $(this).hide();
            $("#twiz_status_img_" + numid.replace("vmenu_", "menu_")).hide();
            $("#twiz_img_status_" + numid).fadeIn("slow");  
            $("#twiz_img_status_" + numid.replace("vmenu_", "menu_")).fadeIn("slow");                    
            $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: "'.parent::ACTION_VMENU_STATUS.'",
            twiz_id: numid
            }, function(data) {
                $("img[name^=twiz_status_img]").unbind("click");
                $("#twiz_status_" + numid).html(data);
                $("#twiz_status_" + numid.replace("vmenu_", "menu_")).html(data.replace("vmenu_", "menu_"));                
                bind_twiz_Status();
            });
            break;    
        case "menu_":
            $(this).hide();
            $("#twiz_status_img_" + numid.replace("menu_", "vmenu_")).hide();    
            $("#twiz_img_status_" + numid).fadeIn("slow");   
            $("#twiz_img_status_" + numid.replace("menu_", "vmenu_")).fadeIn("slow");                
            $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: "'.parent::ACTION_MENU_STATUS.'",
            twiz_id: numid
            }, function(data) {
                $("img[name^=twiz_status_img]").unbind("click");
                $("#twiz_status_" + numid).html(data);
                $("#twiz_status_" + numid.replace("menu_", "vmenu_")).html(data.replace("menu_", "vmenu_"));
                bind_twiz_Status();
            });
            break;
        default:
             switch(numid){
                case "global":
                    $(this).hide();
                    $("#twiz_img_status_global").fadeIn("slow");        
                    $.post(ajaxurl, {
                    action: "twiz_ajax_callback",
                    twiz_nonce: "'.$this->nonce.'", 
                    twiz_action: "'.parent::ACTION_GLOBAL_STATUS.'"
                    }, function(data) {
                        $("img[name^=twiz_status_img]").unbind("click");
                        $("#twiz_global_status").html(data);
                        bind_twiz_Status();
                    });
                    break;
                default:
                    $(this).hide();
                    $("#twiz_img_status_" + numid).fadeIn("slow");        
                    $.post(ajaxurl, {
                    action: "twiz_ajax_callback",
                    twiz_nonce: "'.$this->nonce.'", 
                    twiz_action: c_action,
                    twiz_id: numid
                    }, function(data) {
                        $("img[name^=twiz_status_img]").unbind("click");
                        $("#twiz_td_status_" + numid).html(data);
                        twiz_array_view_id[numid]=undefined;
                        twiz_view_id = null;
                        if((twiz_view_id != numid)&&(twiz_view_id!="edit")&&(twiz_current_section_id!="library")){
                            twiz_view_id = numid;
                            if(twiz_array_view_id[numid]==undefined){
                                $.post(ajaxurl, {
                                action: "twiz_ajax_callback",
                                twiz_nonce: "'.$this->nonce.'", 
                                twiz_action: "'.parent::ACTION_VIEW.'",
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
                    break;
                }
        }            
    });
 }
 var bind_twiz_Edit = function() {
    $("img[name^=twiz_edit]").mouseover(function(){
        var textid = $(this).attr("name");
        var numid = textid.substring(10,textid.length);
        $(".twiz_list_tr_action").css("visibility", "hidden");
        $("#twiz_list_tr_action_" + numid).css("visibility", "visible");        
        twizRightPanel(numid);
    });   
    $(".twiz-edit").click(function(){
        var textid = $(this).attr("name");
        var textidtemp = textid.substring(10,textid.length);
        if(textidtemp.substring(0,1) == "a"){
            var numid = textid.substring(12,textid.length);
        }else{
            var numid = textidtemp;
        }
        twiz_view_id = "edit";
        if(textidtemp.substring(0,1) == "a"){
            $("#twiz_list_tr_action_" + numid).html(\'<img\' + \' name="twiz_img_loading_export"\' + \' id="twiz_img_loading_export"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif">\');
            
        }else{
            $(this).hide();
            $("#twiz_img_edit_" + numid).fadeIn("slow");    
        }    
        $.post(ajaxurl, {
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.parent::ACTION_EDIT.'",
        twiz_id: numid,
        twiz_section_id: twiz_current_section_id
        }, function(data) {
            $("img[name^=twiz_status_img]").unbind("click");
            $(".twiz-edit").unbind("click");
            $("img[name^=twiz_edit]").unbind("mouseover");
            $(".twiz-copy").unbind("click");
            $("img[name^=twiz_copy]").unbind("mouseover");              
            $(".twiz-delete").unbind("click");
            $("img[name^=twiz_delete]").unbind("mouseover");            
            $("#twiz_cancel").unbind("click");
            $("#twiz_container").html(data);
            twiz_view_id = null;
            $("#twiz_container").show("slow");
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
        $(".twiz_list_tr_action").css("visibility", "hidden");
        $("#twiz_list_tr_action_" + numid).css("visibility", "visible");
        twizRightPanel(numid);
    });   
    $(".twiz-copy").click(function(){
        var textid = $(this).attr("name");
        var textidtemp = textid.substring(10,textid.length);
        if(textidtemp.substring(0,1) == "a"){
            var numid = textid.substring(12,textid.length);
        }else{
            var numid = textidtemp;
        }
        twiz_view_id = "edit";
        if(textidtemp.substring(0,1) == "a"){
            $("#twiz_list_tr_action_" + numid).html(\'<img\' + \' name="twiz_img_loading_export"\' + \' id="twiz_img_loading_export"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif">\');
            
        }else{
            $(this).hide();
            $("#twiz_img_copy_" + numid).fadeIn("slow");    
        }  
        $.post(ajaxurl, {
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.parent::ACTION_COPY.'",
        twiz_id: numid,
        twiz_section_id: twiz_current_section_id
        }, function(data) {
            $("img[name^=twiz_status_img]").unbind("click");
            $(".twiz-edit").unbind("click");
            $("img[name^=twiz_edit]").unbind("mouseover");
            $(".twiz-copy").unbind("click");
            $("img[name^=twiz_copy]").unbind("mouseover");            
            $(".twiz-delete").unbind("click");
            $("img[name^=twiz_delete]").unbind("mouseover");
            $("#twiz_cancel").unbind("click");
            $("#twiz_container").html(data);
            twiz_view_id = null;
            $("#twiz_container").show("slow");
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
        $(".twiz_list_tr_action").css("visibility", "hidden");
        $("#twiz_list_tr_action_" + numid).css("visibility", "visible");
        twizRightPanel(numid);
    });  
    $(".twiz-delete").click(function(){
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            var textid = $(this).attr("name");
            var textidtemp = textid.substring(12,textid.length);
            if(textidtemp.substring(0,1) == "a"){
                var numid = textid.substring(14,textid.length);
            }else{
                var numid = textidtemp;
            } 
            var c_action = "'.parent::ACTION_DELETE.'";
            if(twiz_current_section_id=="library"){
                c_action = "'.parent::ACTION_DELETE_LIBRARY.'";
            }            
            if(textidtemp.substring(0,1) == "a"){
                $("#twiz_list_tr_action_" + numid).html(\'<img\' + \' name="twiz_img_loading_action"\' + \' id="twiz_img_loading_action"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif">\');
                
            }else{
                $(this).hide();
                $("#twiz_img_delete_" + numid).fadeIn("slow");    
            }  
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
    $("#twiz_container").slideToggle("fast");
    $.post(ajaxurl, {
    action: "twiz_ajax_callback",
    twiz_nonce: "'.$this->nonce.'", 
    twiz_action: "'.parent::ACTION_CANCEL.'",
    twiz_section_id: twiz_current_section_id
    }, function(data) {
        $("img[name^=twiz_status_img]").unbind("click");
        $("#twiz_cancel").unbind("click");
        $("#twiz_save").unbind("click");
        $("#twiz_container").html(data);
        $("#twiz_container").slideToggle("fast");
        twiz_view_id = "";
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
         twiz_action: "'.parent::ACTION_SAVE.'", ';
         
         $i=0;
         $count_array = count($this->array_fields);
         
         foreach($this->array_fields as $value){
         
             $comma = ( $count_array != $i ) ? ','."\n" : '';
          
             switch($value){
                 
                 case parent::F_EXPORT_ID: // Skipped
                 
                    break;
                    
                 case parent::F_SECTION_ID:
                 
                    $header.= 'twiz_'.$value.': twiz_current_section_id'.$comma;
                    break;  
                                   
                 case parent::F_ID:
                 
                    $header.= 'twiz_'.$value.': numid'.$comma;
                    break;
                    
                 case parent::F_STATUS:
                 
                    $header.= 'twiz_'.$value.': $("#twiz_'.$value.'").is(":checked")'.$comma;
                    break;
                                      
                 default:
                 
                    $header.= 'twiz_'.$value.': $("#twiz_'.$value.'").val()'.$comma;
             }
             
             $i++;
         }
         
        $header.= '}, function(data) {
        $("img[name^=twiz_status_img]").unbind("click");
        $("#twiz_cancel").unbind("click");
        $("#twiz_save").unbind("click");
        $("#twiz_container").html(data);
        twiz_array_view_id[numid] = undefined;
        bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
        bind_twiz_More_Options();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
        bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();
        $("#twiz_list_tr_" + numid).animate({opacity:0}, 320); // needs a rebind for add new
        $("#twiz_list_tr_" + numid).animate({opacity:1}, 320); // needs a rebind for add new
        $("#twiz_list_tr_" + numid).animate({opacity:0}, 300); // needs a rebind for add new
        $("#twiz_list_tr_" + numid).animate({opacity:1}, 300); // needs a rebind for add new 
    });
   });
  }
  var bind_twiz_AdminSave = function() {
    $("#twiz_admin_save").click(function(){
    $("#twiz_admin_save").attr({"disabled" : "true"});
    $("#twiz_admin_save_img").fadeIn("fast");
    var numid = $("#twiz_id").val();
    $.post(ajaxurl, {
         action: "twiz_ajax_callback",
         twiz_nonce: "'.$this->nonce.'", 
         twiz_action: "'.parent::ACTION_SAVE_ADMIN.'",
         twiz_register_jquery: $("#twiz_register_jquery").is(":checked"),
         twiz_output_compression: $("#twiz_output_compression").is(":checked"),
         twiz_slc_output: $("#twiz_slc_output").val(),
         twiz_number_posts: $("#twiz_number_posts").val(),
         twiz_min_rolelevel: $("#twiz_min_rolelevel").val(),
         twiz_delete_all: $("#twiz_delete_all").is(":checked")
    }, function(data) {
        $("#twiz_admin_save_img").fadeOut("fast"); 
        $("#twiz_admin_save").removeAttr("disabled");
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
    $("#twiz_start_top_pos").keypress(function (e){
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_start_left_pos").keypress(function (e){
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_zindex").keypress(function (e){
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {if( e.which!=45 ) { return false; }}});
    $("#twiz_move_top_pos_a").keypress(function (e){
    if( e.which!=8 && e.which!=0 && e.which!=45 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_move_left_pos_a").keypress(function (e){
    if( e.which!=8 && e.which!=0 && e.which!=45 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_move_top_pos_b").keypress(function (e){
    if( e.which!=8 && e.which!=0 && e.which!=45 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_move_left_pos_b").keypress(function (e){
    if( e.which!=8 && e.which!=0 && e.which!=45 && (e.which<48 || e.which>57))
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
        $("#twiz_td_full_option_" + charid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif">\');
        $.post(ajaxurl, { 
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.parent::ACTION_OPTIONS.'",
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
                 $("#twiz_ajax_td_loading_" + columnName + "_" + numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + numid + \'[]" id="twiz_img_loading_delay_\' + numid + \'[]" src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif">\');
                $.post(ajaxurl, { 
                action: "twiz_ajax_callback",
                twiz_nonce: "'.$this->nonce.'", 
                twiz_action: "'.parent::ACTION_EDIT_TD.'",
                twiz_id: numid, 
                twiz_column: columnName, 
                twiz_value: txtval
                }, function(data) {
                    $("#twiz_ajax_td_loading_" + columnName + "_" + numid).html("");
                    $("input[name^=twiz_input_delay]").unbind("keypress");
                    $("div[id^=twiz_ajax_td_val]").unbind("click");
                    $("input[name^=twiz_input_delay]").unbind("blur");
                    $("div[id^=twiz_ajax_td_val_delay]").unbind("mouseover");
                    $("#twiz_ajax_td_val_" + columnName + "_" + numid).html(data);
                    $("#twiz_ajax_td_val_" + columnName + "_" + numid).fadeIn("fast");
                    $("#twiz_ajax_td_val_" + columnName + "_" + numid).css({color:"green"});
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
    $(".twiz_list_tr").mouseover(function(){
    if(twiz_current_section_id!="library"){
        var textid = $(this).attr("name");
        var numid = textid.substring(13, textid.length);
        $("#twiz_vertical_menu").hide();   
        $("#twiz_right_panel").css({"position":"relative", "top":$(this).offset().top - 203});
        if((twiz_view_id != numid)&&(twiz_view_id!="edit")){
            $("#twiz_list_tr_action_" + twiz_view_id).css("visibility", "hidden");
            $("#twiz_list_tr_action_" + numid).css("visibility", "visible");
            twiz_view_id = numid;
            $("#twiz_right_panel").html(\'<div\' + \' class="twiz-panel-loading"><img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-big-loading.gif"></div>\');
            $("#twiz_right_panel").fadeIn("fast");    
            if(twiz_array_view_id[numid]==undefined){
                $.post(ajaxurl, {
                action: "twiz_ajax_callback",
                twiz_nonce: "'.$this->nonce.'", 
                twiz_action: "'.parent::ACTION_VIEW.'",
                twiz_id: numid
                }, function(data) {
                    $("#twiz_right_panel").html(data);
                    twiz_array_view_id[numid] = data;
                    bind_twiz_view_show_more();
                });    
            }else{
                $("#twiz_list_tr_action_" + numid).css("visibility", "visible");
                $("#twiz_right_panel").html(twiz_array_view_id[numid]);
                bind_twiz_view_show_more();
                twiz_skip_view = 0;
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
        $("#twiz_add_sections").html("");  
        $("#twiz_add_sections").show("fast");  
        twizGetMultiSection("", "'.parent::ACTION_NEW.'");
        twiz_view_id = null;
    });   
    $("#twiz_edit_menu").click(function(){    
        $("#twiz_add_sections").html("");  
        $("#twiz_add_sections").show("fast");  
        twizGetMultiSection(twiz_current_section_id, "'.parent::ACTION_EDIT.'");
        twiz_view_id = null;
    });     
    $("#twiz_delete_menu").click(function(){  
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: "'.parent::ACTION_DELETE_SECTION.'",
            twiz_section_id: twiz_current_section_id
            }, function(data) {                
            if(twiz_current_section_id!=twiz_default_section_id){
                twiz_current_section_id = twiz_default_section_id;
                twizGetMenu();           
            }
            $("#qq_upload_list li").remove();
            $("#twiz_export_url").html(""); 
            twizPostMenu(twiz_default_section_id);
            });
        }
   });     
    $("div[id^=twiz_menu_]").click(function(){
        var textid = $(this).attr("id");
        twiz_current_section_id = textid.substring(10,textid.length);
        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
        $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
        $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});
        $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
        $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
        $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
        $("#qq_upload_list li").remove(); 
        $("#twiz_export_url").html(""); 
        twizPostMenu(twiz_current_section_id);
    });   
    $("div[id^=twiz_vmenu_]").click(function(){
        var textid = $(this).attr("id");
        twiz_current_section_id = textid.substring(11,textid.length);
        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
        $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
        $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});
        $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
        $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
        $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
        $("#qq_upload_list li").remove(); 
        $("#twiz_export_url").html(""); 
        twizPostMenu(twiz_current_section_id);
    });
  }    
  function twizPostMenu(twiz_section_id){
   $("#twiz_add_sections").html("");  
   $("#twiz_library_menu").attr({"class" : "twiz-menu twiz-display-none"});
   $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-display-none"});
   $("#twiz_container").slideToggle("fast"); 
   $("#twiz_library_upload").fadeOut("fast");
   $.post(ajaxurl, {
            action: "twiz_ajax_callback",
            twiz_nonce: "'.$this->nonce.'", 
            twiz_action: "'.parent::ACTION_MENU.'",
            twiz_section_id: twiz_section_id
            }, function(data) {
                $("img[name^=twiz_status_img]").unbind("click");
                $("#twiz_cancel").unbind("click");
                $("#twiz_save").unbind("click");
                $(".twiz-edit").unbind("click");
                $("img[name^=twiz_edit]").unbind("mouseover");
                $(".twiz-copy").unbind("click");
                $("img[name^=twiz_copy]").unbind("mouseover");                  
                $(".twiz-delete").unbind("click");
                $("img[name^=twiz_delete]").unbind("mouseover");
                $("#twiz_container").html(data);
                $("#twiz_container").slideToggle("fast");  
                twiz_view_id = null;
                twiz_array_view_id = new Array();
                bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
                bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
                bind_twiz_More_Options();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
                bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();           
            });
  }
  var bind_twiz_Save_Section = function() {
        $("input[name=twiz_output_choice]").click(function(){
        var twizblockid = $(this).val();
        $(".twiz-custom-message").html("");
        $(".twiz-block-ouput").hide();
        $("#" + twizblockid).show();
    });
    $("#twiz_slc_multi_sections").dblclick(function(){
        var twiz_selectval = $("#twiz_slc_multi_sections option:selected").text();
        $("input[id=twiz_section_name]").attr({"value" : twiz_selectval});
        $("#twiz_section_name").css("color", "#000000");
    });
    $("#twiz_slc_sections").change(function(){
        var twiz_section = $("#twiz_slc_sections option:selected").val();
        if((twiz_section!="")){
            $("input[id=twiz_section_name]").attr({"value" : $("#twiz_slc_sections option:selected").text()});
             $("#twiz_section_name").css("color", "#000000");
        }
    });
    $("#twiz_section_name").click(function(){
        $("#twiz_section_name").css("color", "#000000");
    });    
    $("#twiz_save_section").click(function(){
        var sectionid = [];
        var validsection = true;
        var sectiontovalid = "";
        var sectionok = "";
        if(($("#twiz_section_name").val() == "'.__('Give the section a name', 'the-welcomizer').'")
        || ($("#twiz_section_name").val() == "")){
            $("#twiz_section_name").val("'.__('Give the section a name', 'the-welcomizer').'");
            $("#twiz_section_name").css("color", "#BC0B0B");
            validsection = false;
        }
        $("input[name=twiz_output_choice]:radio").each(function(){
        if ($(this).attr("checked")){
            sectiontovalid = $(this).val();
        }});
        switch(sectiontovalid){
            case "twiz_single_output":
                sectionid[0] = $("#twiz_slc_sections").val();
                if(sectionid[0] == ""){
                    $("#twiz_custom_message_1").html("' .__('Please choose an output option.', 'the-welcomizer').'");
                    $("#twiz_loading_menu").html("");
                     validsection = false;
                }else{
                    $("#twiz_custom_message_1").html("");
                }              
                break;
            case "twiz_multiple_output":
                if( $("#twiz_slc_multi_sections :selected").length > 0){
                    $("#twiz_slc_multi_sections :selected").each(function(i, selected) {
                        sectionid[i] = $(selected).val();
                    });
                }            
                if(sectionid.length == 0){
                    $("#twiz_custom_message_2").html("' .__('Please select at least one option.', 'the-welcomizer').'");
                    $("#twiz_loading_menu").html("");
                     validsection = false;
                }else{
                    $("#twiz_custom_message_2").html("");
                }     
            break;
            case "twiz_logic_output":
                if($("#twiz_custom_logic").val()== ""){
                    $("#twiz_custom_message_3").html("' .__('Please type a custom logic.', 'the-welcomizer').'");
                    $("#twiz_loading_menu").html("");
                     validsection = false;
                }else{
                    $("#twiz_custom_message_3").html("");
                }              
                break;            
        }
        if(validsection == true){
            $("#twiz_loading_menu").html(\'<div\' + \' class="twiz-menu twiz-noborder-right"><img\' + \' name="twiz_img_loading_add_sections"\' + \' id="twiz_img_loading_add_sections"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif"></div>\');
            $("input[id=twiz_save_section]").attr({"disabled" : "true"});
            $("#twiz_add_sections").hide();
            $.post(ajaxurl, {
                 action: "twiz_ajax_callback",
                 twiz_nonce: "'.$this->nonce.'", 
                 twiz_action: "'.parent::ACTION_SAVE_SECTION.'",
                 twiz_section_name: $("#twiz_section_name").val(),
                 twiz_current_section_id: $("#twiz_section_id").val(),
                 twiz_output_choice: $("input[name=twiz_output_choice]:checked").val(),
                 twiz_custom_logic: $("#twiz_custom_logic").val(),
                 twiz_section_id: JSON.stringify(sectionid)
                }, function(data) {
                    twiz_current_section_id = data; 
                    sectionok = twizGetMenu();
                    twizPostMenu(twiz_current_section_id);
                    $("#twiz_loading_menu").html("");
                });
        }
    });
  }  
  var bind_twiz_DynArrows = function() {
   $("select[id^=twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.']").change(function(){twizChangeDirectionImage("a");});  
   $("select[id^=twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.']").change(function(){twizChangeDirectionImage("a");});   
   $("input[name^=twiz_'.parent::F_MOVE_TOP_POS_A.']").blur(function(){twizChangeDirectionImage("a");});
   $("input[name^=twiz_'.parent::F_MOVE_LEFT_POS_A.']").blur(function(){twizChangeDirectionImage("a");}); 
   $("select[id^=twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.']").change(function(){twizChangeDirectionImage("b");});
   $("select[id^=twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.']").change(function(){twizChangeDirectionImage("b");});
   $("input[name^=twiz_'.parent::F_MOVE_TOP_POS_B.']").blur(function(){twizChangeDirectionImage("b");});
   $("input[name^=twiz_'.parent::F_MOVE_LEFT_POS_B.']").blur(function(){twizChangeDirectionImage("b");});    
   function twizChangeDirectionImage(ab) {
      var top_sign  = $("#twiz_move_top_pos_sign_" + ab).val();
      var top_val   = $("#twiz_move_top_pos_" + ab).val();
      var left_sign = $("#twiz_move_left_pos_sign_" + ab).val();
      var left_val  = $("#twiz_move_left_pos_" + ab).val();
      var direction = "";
      var htmlimage = "";
      if((top_sign!="=")&&(left_sign!="=")){
          switch(true){
             case ((top_val!="")&&(top_sign=="-")&&(left_val=="")): 
                direction = "'.parent::DIMAGE_N.'";
                break;
             case ((top_val!="")&&(top_sign=="-")&&(left_val!="")&&(left_sign=="+")):
                direction = "'.parent::DIMAGE_NE.'";
                break;        
             case ((top_val=="")&&(left_val!="")&&(left_sign=="+")): 
                direction = "'.parent::DIMAGE_E.'";
                break;         
             case ((top_val!="")&&(top_sign=="+")&&(left_val!="")&&(left_sign=="+")): 
                direction = "'.parent::DIMAGE_SE.'";    
                break;     
             case ((top_val!="")&&(top_sign=="+")&&(left_val=="")): 
                direction = "'.parent::DIMAGE_S.'";    
                break; 
             case ((top_val!="")&&(top_sign=="+")&&(left_val!="")&&(left_sign=="-")): 
                direction = "'.parent::DIMAGE_SW.'";    
                break;    
             case ((top_val=="")&&(left_val!="")&&(left_sign=="-")): 
                direction = "'.parent::DIMAGE_W.'";    
                break;          
             case ((top_val!="")&&(top_sign=="-")&&(left_val!="")&&(left_sign=="-")): 
                direction = "'.parent::DIMAGE_NW.'";    
                break;           
          }
          if(direction!=""){ 
              htmlimage = \'<img\' + \' width="45"\' + \' height="45"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-arrow-\' + direction + \'.png">\';
          }
      }
      $("#twiz_td_arrow_" + ab).html(htmlimage);
    }
   }
    var bind_twiz_Library_New_Order = function() {
        $("img[name^=twiz_new_order_up]").click(function(){
            var textid = $(this).attr("id");
            var numid = textid.substring(18,textid.length);
            $("#twiz_list_td_" + numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + numid + \'[]"\' + \' id="twiz_img_loading_delay_\' + numid + \'[]"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif">\');
            twizOrderLibrary("'.parent::LB_ORDER_UP.'", numid);
        });
        $("img[name^=twiz_new_order_down]").click(function(){
            var textid = $(this).attr("id");
            var numid = textid.substring(20, textid.length);
            $("#twiz_list_td_" + numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + numid + \'[]"\' + \' id="twiz_img_loading_delay_\' + numid + \'[]"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif">\');
            twizOrderLibrary("'.parent::LB_ORDER_DOWN.'", numid);
        });        
    }
    function twizOrderLibrary(twiz_order, twiz_id){
      $.post(ajaxurl, {
       action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.parent::ACTION_ORDER_LIBRARY.'",
        twiz_order: twiz_order,
        twiz_id : twiz_id
        }, function(data) {                
            $("img[name^=twiz_new_order_up]").unbind("click");
            $("img[name^=twiz_new_order_down]").unbind("click");
            $("img[name^=twiz_new_order]").unbind("click");
            $(".twiz-delete").unbind("click");            
            $("#twiz_container").html(data);
            bind_twiz_Status();bind_twiz_Delete();
            bind_twiz_Library_New_Order();
        });   
  }
  var bind_twiz_FooterMenu = function() {
    $("#twiz_export").click(function(){
        $("#twiz_export_url").html(\'<img\' + \' name="twiz_img_loading_export"\' + \' id="twiz_img_loading_export"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif">\');
        $("#qq_upload_list li").remove(); 
        var animid = $("#twiz_id").val();
        if(animid==undefined){
           animid = "";
        }
        $.post(ajaxurl, {
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.parent::ACTION_EXPORT.'",
        twiz_section_id: twiz_current_section_id,
        twiz_id: animid
        }, function(data) {
           var superiframe = document.createElement("iframe");
           $("#twiz_export_url").html( data );
           document.body.appendChild(data); 
        });
    });   
    $("#twiz_library").click(function(){
        $("#twiz_add_sections").hide();
        twizSwitchFooterMenu();
        twizPostLibrary();
    });     
    $("#twiz_admin").click(function(){
      $("#twiz_add_sections").hide();
      twizSwitchFooterMenu();
      $("#twiz_container").slideToggle("fast"); 
      $.post(ajaxurl, {
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.parent::ACTION_ADMIN.'"
        }, function(data) {                
            $("#twiz_container").html(data);
            $("#twiz_container").slideToggle("fast"); 
            $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
            $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
            $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});
            $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
            $("#twiz_library_menu").attr({"class" : "twiz-menu twiz-display-none"});
            bind_twiz_AdminSave();
            twiz_current_section_id = "admin";
        }); 
    });     
  }  
  function twizSwitchFooterMenu(){
      $("#twiz_export").fadeOut("fast");
      $("#twiz_import").fadeOut("fast");
      $("#qq_upload_list li").remove();
      $("#twiz_export_url").html(""); 
  }  
  function twizPostLibrary(){
      $("#twiz_container").slideToggle("fast"); 
      $.post(ajaxurl, {
        action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.parent::ACTION_LIBRARY.'"
        }, function(data) {                
            $("img[name^=twiz_status]").unbind("click");
            $(".twiz-delete").unbind("click");            
            $("#twiz_container").html(data);
            $("#twiz_container").slideToggle("fast"); 
            twiz_current_section_id = "library";
            $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-display-none"});
            $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
            $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
            $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});            
            $("#twiz_library_menu").attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
            bind_twiz_Status();bind_twiz_Delete();
            bind_twiz_Library_New_Order();
        });   
  }
  function twizGetMenu(){
      $.post(ajaxurl, {
       action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'",       
        twiz_action: "'.parent::ACTION_GET_MENU.'",
        twiz_section_id: twiz_current_section_id
        }, function(datamenu) {   
              $.post(ajaxurl, {
               action: "twiz_ajax_callback",
                twiz_nonce: "'.$this->nonce.'",       
                twiz_action: "'.parent::ACTION_GET_VMENU.'",
                twiz_section_id: twiz_current_section_id
                }, function(data) {                
                    $("#twiz_ajax_menu").html(datamenu);              
                    $("div[id^=twiz_menu_]").unbind("click");
                    $("div[id^=twiz_vmenu_]").unbind("click");
                    $("#twiz_add_menu").unbind("click");
                    $("#twiz_delete_menu").unbind("click");
                    $("#twiz_cancel_section").unbind("click");
                    $("#twiz_vertical_menu").html(data);
                    $("img[name^=twiz_status_img]").unbind("click");
                    bind_twiz_Menu(); 
                    bind_twiz_Status();                    
                    $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
                    $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
                    $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});                    
                    return true;
                });  
        });   
  }  
  function twizGetMultiSection(twiz_section_id, twiz_action_lbl){
      $("#twiz_loading_menu").html(\'<div\' + \' class="twiz-menu twiz-noborder-right"><img\' + \' name="twiz_img_loading_add_sections"\' + \' id="twiz_img_loading_add_sections"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif"></div>\');
      $.post(ajaxurl, {
       action: "twiz_ajax_callback",
        twiz_nonce: "'.$this->nonce.'",
        twiz_action: "'.parent::ACTION_GET_MULTI_SECTION.'",
        twiz_action_lbl: twiz_action_lbl,
        twiz_section_id: twiz_section_id
        }, function(data) {                
            $("div[id^=twiz_menu_]").unbind("click");
            $("div[id^=twiz_vmenu_]").unbind("click");
            $("#twiz_add_menu").unbind("click");
            $("#twiz_edit_menu").unbind("click");
            $("#twiz_delete_menu").unbind("click");
            $("#twiz_cancel_section").unbind("click");
            $("#twiz_save_section").unbind("click");
            $("#twiz_section_name").unbind("click");
            $("#twiz_slc_sections").unbind("change");
            $("#twiz_loading_menu").html("");
            $("#twiz_add_sections").html(data);
            $("img[name^=twiz_status_img]").unbind("click");
            bind_twiz_Menu();     
            bind_twiz_Status();             
            bind_twiz_Save_Section();
        });   
  }  
  var bind_twiz_view_show_more = function() {
    $("a[name^=twiz_view_show_more]").click(function(){
        $("[id^=twiz-tr-view-more]").toggle();
    }); 
  }
  $("#twiz_footer_menu").mouseover(function(){
     $("#twiz_right_panel").fadeOut("fast");   
     $(".twiz_list_tr_action").css("visibility", "hidden");
     twiz_view_id = null;
  });    
  $("#twiz_footer").mouseover(function(){
     $("#twiz_right_panel").fadeOut("fast");   
     $(".twiz_list_tr_action").css("visibility", "hidden");
     twiz_view_id = null;
  });  
  $("#twiz_header").mouseover(function(){
     $("#twiz_right_panel").fadeOut("fast");   
     $(".twiz_list_tr_action").css("visibility", "hidden");
     twiz_view_id = null;
  });    
  $("#twiz_menu").mouseover(function(){   
     $("#twiz_right_panel").fadeOut("fast");   
     $(".twiz_list_tr_action").css("visibility", "hidden");
     twiz_view_id = null;
  });   
  $("#twiz_more_menu").click(function(){   
     $("#twiz_vertical_menu").toggle();     
  });
  $("#twiz_vertical_menu").mouseleave(function(){   
     $("#twiz_vertical_menu").toggle();   
  });  
  $("#twiz_vertical_menu").mouseenter(function(){   
     $("#twiz_right_panel").fadeOut("fast");   
     $(".twiz_list_tr_action").css("visibility", "hidden");
     twiz_view_id = null; 
  });  
  $("#twiz_head_logo").click(function (){   
    if($("#twiz_skin_bullet").css("top")=="22px"){
        $("#twiz_skin_bullet").stop().animate({top:"-=22px"},1000, function(){
        $("#twiz_skin_bullet").css("z-index","1");});
    }
    if($("#twiz_skin_bullet").css("top")=="0px"){
        $("#twiz_skin_bullet").css("z-index","-1");
        $("#twiz_skin_bullet").stop().animate({top:"+=22px"},500, function(){});
    }    
  });       
  $(".twiz-skins").click(function(){   
        var textid = $(this).attr("id");
        var skinname = textid.substring(10, textid.length); 
        skin = "'.$this->pluginUrl.parent::SKIN_PATH.'" + skinname + "/twiz-style.css";
        $("#twiz-css-a-css").attr("href",skin);
        $.post(ajaxurl, {
        action: "twiz_ajax_callback",
        twiz_skin: skinname, 
        twiz_nonce: "'.$this->nonce.'", 
        twiz_action: "'.parent::ACTION_SAVE_SKIN.'"
        }, function(data) {
        twiz_view_id = null;
        twiz_array_view_id = new Array();
        }); 
  });    
  if($("#twiz_skin_bullet").css("top")=="0px"){
      $("#twiz_skin_bullet").css("z-index","-1");
      $("#twiz_skin_bullet").stop().animate({top:"+=22px"},1000, function(){});
  } 
  $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
  $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
  $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
  bind_twiz_Status();bind_twiz_New();bind_twiz_Edit();bind_twiz_Copy();bind_twiz_Delete();
  bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
  bind_twiz_More_Options();bind_twiz_Choose_Options();
  bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Menu();
  bind_twiz_Save_Section();bind_twiz_FooterMenu();bind_twiz_Select_Functions();
  $("#twiz_container").slideToggle("slow");
 });';
       return $header;
    }
}?>