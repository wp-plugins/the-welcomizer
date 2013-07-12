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
    
class TwizAjax extends Twiz{

    function __construct(){
    
        parent::__construct();
        
    }

    function getAjaxHeader(){
    $header = 'var twiz_parent_id = ""; var twiz_current_section_id = "'.$this->DEFAULT_SECTION[$this->userid].'"; jQuery(document).ready(function($) {
 $.ajaxSetup({ cache: false });
 var twiz_skin =  "'.$this->skin[$this->userid].'";
 if((twiz_skin == "")||(twiz_skin == "'.parent::SKIN_PATH.'")){ twiz_skin = "'.parent::SKIN_PATH.''.parent::DEFAULT_SKIN.'";}
 var twiz_view_id = null;
 if(twiz_current_section_id == ""){ twiz_current_section_id = "'.parent::DEFAULT_SECTION_HOME.'";}
 var twiz_default_section_id = "'.parent::DEFAULT_SECTION_HOME.'";
 var twiz_array_view_id = new Array();
 var twiz_library_active = false;
 var twiz_orig_anim_link_class = "";
 var twiz_group_orig_anim_link_class = "";
 var twiz_panel_offset_switch = "";
 $(document).scrollTop(0);
 $(document).scrollLeft(0); 
 var twiz_import_file = new qq.FileUploader({
    element: document.getElementById("twiz_import_container"),
    action: "'.$this->pluginUrl.'/includes/import/server/php.php",
    debug: false,
    id: "twiz_import",
    label: "'.__('Import', 'the-welcomizer').'",
    allowedExtensions: ["'.parent::EXT_TWZ.'", "'.parent::EXT_TWIZ.'", "'.parent::EXT_XML.'"],
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
 function twiz_ListMenu_Unbind(){
    twiz_view_id = null;
    $("a[name^=twiz_far_cancel]").unbind("click");
    $("input[name^=twiz_far_find]").unbind("click");
    $("input[name^=twiz_far_replace]").unbind("click");
    $("#twiz_findandreplace").unbind("click");
    $("#twiz_listmenu").unbind("mouseenter");
    $("#twiz_new").unbind("click");
    $("input[name=twiz_far_choice]").unbind("click");    
    $("#twiz_create_group").unbind("click");
    $("a[name=twiz_group_cancel]").unbind("click");
    $("input[name=twiz_group_save]").unbind("click");
    $("#twiz_group_name").unbind("click");
 }
 function twiz_ListMenu_Cancel(){
        $("#twiz_far_matches").html("");
        $("#twiz_sub_container").hide();
        $("#twiz_listmenu").css("display", "block"); 
        $("#twiz_container").css("display", "block");  
 } 
 var bind_twiz_ListMenu = function() {
     $("#twiz_listmenu").mouseenter(function(){   
        twiz_reset_nav();
     });      
     $("#twiz_new").click(function(){
         twizShowMainLoadingImage();
         twiz_view_id = "edit";     
         $("#twiz_container").css("display", "none"); 
         $("#twiz_far_matches").html("");
         $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_NEW.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_parent_id": twiz_parent_id,
            "twiz_nonce": "'.$this->nonce.'"
         }, function(data) {
                $("#twiz_container").html(data);
                $("#twiz_container").css("display", "block"); 
                twiz_view_id = null;
                bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
                bind_twiz_Choose_Options();
                bind_twiz_DynArrows();
                $("#twiz_loading_menu").html("");
                $("#twiz_layer_id").focus();
            });
     });
     $("#twiz_create_group").click(function(){
         twizShowMainLoadingImage();
         twiz_view_id = "edit";     
         $("#twiz_container").css("display", "none");
         $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_GET_GROUP.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_nonce": "'.$this->nonce.'"
         }, function(data) {
                $("#twiz_sub_container").html(data);
                $("#twiz_sub_container").show();  
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                $("#twiz_group_name").focus();
                $("#twiz_loading_menu").html("");
            });
    });        
    $("a[name=twiz_group_cancel]").click(function(){
        twiz_ListMenu_Cancel();
    });      
    $("#twiz_group_name").click(function(){
        if($("#twiz_group_name").val() == "'.__('Give the group a name.', 'the-welcomizer').'"){
            $("#twiz_group_name").attr({"value" : ""});
            $("#twiz_group_name").css("color", "#333333");
        }
    });
    $("input[name=twiz_group_save]").click(function(){
        var twiz_validgroup = true;
        if(($("#twiz_group_name").val() == "'.__('Give the group a name.', 'the-welcomizer').'")
        || ($("#twiz_group_name").val() == "")){
            $("#twiz_group_name").val("'.__('Give the group a name.', 'the-welcomizer').'");
            $("#twiz_group_name").css("color", "#BC0B0B");
            twiz_validgroup = false;
        }
        if(twiz_validgroup == true){
            $("#twiz_group_save_img_box").show();  
            $("#twiz_group_save_img_box").html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" />\');  
            $("input[id=twiz_group_save]").css({"color" : "#9FD0D5"});        
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_SAVE_GROUP.'",
            "twiz_group_export_id": $("#twiz_group_export_id").val(),
            "twiz_section_id": twiz_current_section_id,
            "twiz_group_status": $("#twiz_group_status").is(":checked"),
            "twiz_group_name": $("#twiz_group_name").val(),
            "twiz_group_id": $("#twiz_group_id").val(),
            "twiz_nonce": "'.$this->nonce.'"
         }, function(data) {
                data = JSON.parse(data);
                $("img[name^=twiz_status_img]").unbind("click");
                $("[name=twiz_cancel]").unbind("click");
                $("[name=twiz_save]").unbind("click");
                $("#twiz_on_event").unbind("change");
                $("[class^=twiz-slc-js-features]").unbind("change");
                $(".twiz-js-features a").unbind("click");
                $("#twiz_sub_container").html("");  
                $("#twiz_container").html(data.html);
                $("#twiz_container").css("display", "block"); 
                if(data.result > 0){
                    twiz_view_id = null;
                    twiz_array_view_id = new Array();
                }
                bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();bind_twiz_DynArrows();
                bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
                bind_twiz_Choose_Options();
                bind_twiz_Ajax_TD();bind_twiz_TR_View();bind_twiz_Order_by(); 
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                if($("#twiz_list_div_element_" + data.id).offset().top > $(window).height()-20){$("html, body").animate({ scrollTop: $("#twiz_list_div_element_" + data.id).offset().top - 300 }, "slow", function(){
                    $("#twiz_list_div_element_" + data.id).css({"color":"green"});
                    $("#twiz_list_div_element_" + data.id).animate({opacity:0}, 300, 
                    function(){$("#twiz_list_div_element_" + data.id).animate({opacity:1}, 300, 
                    function(){});
                });});
                }else{
                    $("#twiz_list_div_element_" + data.id).css({"color":"green"});
                        $("#twiz_list_div_element_" + data.id).animate({opacity:0}, 300, 
                        function(){$("#twiz_list_div_element_" + data.id).animate({opacity:1}, 300, 
                        function(){});
                    }); 
                }
            });
        }
    });      
     $("#twiz_findandreplace").click(function(){
         twizShowMainLoadingImage();
         twiz_view_id = "edit";     
         $("#twiz_container").css("display", "none");
         $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_GET_FINDANDREPLACE.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_nonce": "'.$this->nonce.'"
         }, function(data) {
                $("#twiz_sub_container").html(data);
                $("#twiz_sub_container").show();  
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                $("#twiz_loading_menu").html("");
            });
    });    
    $("input[name=twiz_far_choice]").click(function(){ 
        twiz_far_choice = $("input[name=twiz_far_choice]:checked").val();
        $("[name=twiz_far_table]").hide();
        $("#" + $(this).val()).show();
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_SAVE_FAR_PREF_METHOD.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_far_choice": twiz_far_choice,     
            "twiz_nonce": "'.$this->nonce.'"
         }, function(data) { });           
    });     
    $("a[name^=twiz_far_cancel]").click(function(){
        twiz_ListMenu_Cancel(); 
    });  
    $("#twiz_far_matches").mouseenter(function(){
        $("#twiz_far_matches").stop().animate({"opacity":0},500,function(){$("#twiz_far_matches").html("");});
    });  
    $("input[name^=twiz_far_find]").click(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(14,twiz_textid.length);       
        var twiz_far_choice = $("input[name=twiz_far_choice]:checked").val();   
        $("input[name^=twiz_far_find]").css({"color" : "#9FD0D5 "});
        $("#twiz_far_save_img_box_" + twiz_charid).show();  
        $("#twiz_far_save_img_box_" + twiz_charid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" />\');
        switch(twiz_far_choice){
        case "twiz_far_simple":
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_FAR_FIND.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_far_choice": twiz_far_choice,
            "twiz_far_everywhere_1": $("#twiz_far_everywhere_1").val(),
            "twiz_far_everywhere_2": $("#twiz_far_everywhere_2").val(),         
            "twiz_nonce": "'.$this->nonce.'"
         }, function(data) {
                data = JSON.parse(data);
                $("#twiz_container").html(data.html);
                $("#twiz_save_img_box_" + twiz_charid).html("");
                $("#twiz_sub_container").hide();  
                $("#twiz_container").slideToggle("fast");
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                twiz_view_id = null;
                twiz_array_view_id = new Array();
                twizList_ReBind();
                $("#twiz_listmenu").css("display", "block"); 
                $("#twiz_far_matches").stop().animate({"opacity":1},0,function(){
                    switch(true){
                        case ( data.result > 1 ):
                        $("#twiz_far_matches").html(data.result + " '.__('results found.', 'the-welcomizer').'");
                        break;
                        case ( data.result == 1 ):
                            $("#twiz_far_matches").html(data.result + " '.__('result found.', 'the-welcomizer').'");
                        break;            
                        case ( data.result == 0 ):
                            $("#twiz_far_matches").html(\'<span\' + \' class="twiz-red">'.__('No results found.', 'the-welcomizer').'</span>\');
                        break; 
                    }
                });
            });         
            break;        
        case "twiz_far_precise":
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_FAR_FIND.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_far_choice": twiz_far_choice,
            "twiz_'.parent::F_STATUS.'_far_1": $("#twiz_'.parent::F_STATUS.'_far_1").is(":checked"),        
            "twiz_'.parent::F_ON_EVENT.'_far_1": $("#twiz_'.parent::F_ON_EVENT.'_far_1").val(),            
            "twiz_'.parent::F_LAYER_ID.'_far_1": $("#twiz_'.parent::F_LAYER_ID.'_far_1").val(),
            "twiz_'.parent::F_START_DELAY.'_far_1": $("#twiz_'.parent::F_START_DELAY.'_far_1").val(),
            "twiz_'.parent::F_DURATION.'_far_1": $("#twiz_'.parent::F_DURATION.'_far_1").val(),
            "twiz_'.parent::F_OUTPUT_POS.'_far_1": $("#twiz_'.parent::F_OUTPUT_POS.'_far_1").val(),   
            "twiz_'.parent::F_START_ELEMENT.'_far_1": $("#twiz_'.parent::F_START_ELEMENT.'_far_1").val(),
            "twiz_'.parent::F_START_TOP_POS_SIGN.'_far_1": $("#twiz_'.parent::F_START_TOP_POS_SIGN.'_far_1").val(),
            "twiz_'.parent::F_START_TOP_POS.'_far_1": $("#twiz_'.parent::F_START_TOP_POS.'_far_1").val(),
            "twiz_'.parent::F_START_TOP_POS_FORMAT.'_far_1": $("#twiz_'.parent::F_START_TOP_POS_FORMAT.'_far_1").val(),
            "twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_1": $("#twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_1").val(),
            "twiz_'.parent::F_START_LEFT_POS.'_far_1": $("#twiz_'.parent::F_START_LEFT_POS.'_far_1").val(),
            "twiz_'.parent::F_START_LEFT_POS_FORMAT.'_far_1": $("#twiz_'.parent::F_START_LEFT_POS_FORMAT.'_far_1").val(),
            "twiz_'.parent::F_POSITION.'_far_1": $("#twiz_'.parent::F_POSITION.'_far_1").val(),   
            "twiz_'.parent::F_ZINDEX.'_far_1": $("#twiz_'.parent::F_ZINDEX.'_far_1").val(),   
            "twiz_'.parent::F_OUTPUT.'_far_1": $("#twiz_'.parent::F_OUTPUT.'_far_1").val(),   
            "twiz_'.parent::F_JAVASCRIPT.'_far_1": $("#twiz_'.parent::F_JAVASCRIPT.'_far_1").val(),   
            "twiz_'.parent::F_CSS.'_far_1": $("#twiz_'.parent::F_CSS.'_far_1").val(),   
            "twiz_'.parent::F_EASING_A.'_far_1": $("#twiz_'.parent::F_EASING_A.'_far_1").val(),   
            "twiz_'.parent::F_MOVE_ELEMENT_A.'_far_1": $("#twiz_'.parent::F_MOVE_ELEMENT_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_A.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_FORMAT_A.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_FORMAT_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_A.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_A.'_far_1").val(),
            "twiz_'.parent::F_OPTIONS_A.'_far_1": $("#twiz_'.parent::F_OPTIONS_A.'_far_1").val(),
            "twiz_'.parent::F_EXTRA_JS_A.'_far_1": $("#twiz_'.parent::F_EXTRA_JS_A.'_far_1").val(),
            "twiz_'.parent::F_EASING_B.'_far_1": $("#twiz_'.parent::F_EASING_B.'_far_1").val(),   
            "twiz_'.parent::F_MOVE_ELEMENT_B.'_far_1": $("#twiz_'.parent::F_MOVE_ELEMENT_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_B.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_FORMAT_B.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_FORMAT_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_B.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_B.'_far_1").val(),
            "twiz_'.parent::F_OPTIONS_B.'_far_1": $("#twiz_'.parent::F_OPTIONS_B.'_far_1").val(),
            "twiz_'.parent::F_EXTRA_JS_B.'_far_1": $("#twiz_'.parent::F_EXTRA_JS_B.'_far_1").val(),
            "twiz_nonce": "'.$this->nonce.'"
         }, function(data) {
                data = JSON.parse(data);
                $("#twiz_container").html(data.html);
                $("#twiz_save_img_box_" + twiz_charid).html("");
                $("#twiz_sub_container").hide();  
                $("#twiz_container").slideToggle("fast");
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                twiz_view_id = null;
                twiz_array_view_id = new Array();
                twizList_ReBind();
                $("#twiz_listmenu").css("display", "block"); 
                $("#twiz_far_matches").stop().animate({"opacity":1},0,function(){
                    switch(true){
                        case ( data.result > 1 ):
                        $("#twiz_far_matches").html(data.result + " '.__('results found.', 'the-welcomizer').'");
                        break;
                        case ( data.result == 1 ):
                            $("#twiz_far_matches").html(data.result + " '.__('result found.', 'the-welcomizer').'");
                        break;            
                        case ( data.result == 0 ):
                            $("#twiz_far_matches").html(\'<span\' + \' class="twiz-red">'.__('No results found.', 'the-welcomizer').'</span>\');
                        break; 
                    }
                });
            });        
            break;
        }
    });      
    $("input[name^=twiz_far_replace]").click(function(){  
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(17,twiz_textid.length);       
        var twiz_far_choice = $("input[name=twiz_far_choice]:checked").val();  
        if (confirm("'.__('Are you sure to replace?', 'the-welcomizer').'")) {     
        $("input[name^=twiz_far_replace]").css({"color" : "#9FD0D5 "});
        $("#twiz_far_save_img_box_" + twiz_charid).show();  
        $("#twiz_far_save_img_box_" + twiz_charid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" />\');  
        switch(twiz_far_choice){
        case "twiz_far_simple":
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_FAR_REPLACE.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_far_choice": twiz_far_choice,
            "twiz_far_everywhere_1": $("#twiz_far_everywhere_1").val(),
            "twiz_far_everywhere_2": $("#twiz_far_everywhere_2").val(),         
            "twiz_nonce": "'.$this->nonce.'"
         }, function(data) { 
                $("#twiz_save_img_box_" + twiz_charid).html("");
                $("#twiz_sub_container").hide();  
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                twizPostMenu(twiz_current_section_id);
                $("#twiz_far_matches").stop().animate({"opacity":1},0,function(){
                    switch(true){
                        case ( data > 1 ):
                            $("#twiz_far_matches").html(data + " '.__('results modified.', 'the-welcomizer').'");
                            break;
                        case ( data == 1 ):
                            $("#twiz_far_matches").html(data + " '.__('result modified.', 'the-welcomizer').'");
                            break;            
                        case ( data == 0 ):
                            $("#twiz_far_matches").html(\'<span\' + \' class="twiz-red">'.__('No results modified.', 'the-welcomizer').'</span>\');
                            break; 
                    }
                });
                twiz_view_id = null;
            });         
            break;   
        case "twiz_far_precise":
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_FAR_REPLACE.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_far_choice": twiz_far_choice,
            "twiz_'.parent::F_STATUS.'_far_1": $("#twiz_'.parent::F_STATUS.'_far_1").is(":checked"),        
            "twiz_'.parent::F_STATUS.'_far_2": $("#twiz_'.parent::F_STATUS.'_far_2").is(":checked"),        
            "twiz_'.parent::F_ON_EVENT.'_far_1": $("#twiz_'.parent::F_ON_EVENT.'_far_1").val(),
            "twiz_'.parent::F_ON_EVENT.'_far_2": $("#twiz_'.parent::F_ON_EVENT.'_far_2").val(),
            "twiz_'.parent::F_LAYER_ID.'_far_1": $("#twiz_'.parent::F_LAYER_ID.'_far_1").val(),
            "twiz_'.parent::F_LAYER_ID.'_far_2": $("#twiz_'.parent::F_LAYER_ID.'_far_2").val(),
            "twiz_'.parent::F_START_DELAY.'_far_1": $("#twiz_'.parent::F_START_DELAY.'_far_1").val(),
            "twiz_'.parent::F_START_DELAY.'_far_2": $("#twiz_'.parent::F_START_DELAY.'_far_2").val(),       
            "twiz_'.parent::F_DURATION.'_far_1": $("#twiz_'.parent::F_DURATION.'_far_1").val(),
            "twiz_'.parent::F_DURATION.'_far_2": $("#twiz_'.parent::F_DURATION.'_far_2").val(),
            "twiz_'.parent::F_OUTPUT_POS.'_far_1": $("#twiz_'.parent::F_OUTPUT_POS.'_far_1").val(),   
            "twiz_'.parent::F_OUTPUT_POS.'_far_2": $("#twiz_'.parent::F_OUTPUT_POS.'_far_2").val(),         
            "twiz_'.parent::F_START_ELEMENT.'_far_1": $("#twiz_'.parent::F_START_ELEMENT.'_far_1").val(),
            "twiz_'.parent::F_START_ELEMENT.'_far_2": $("#twiz_'.parent::F_START_ELEMENT.'_far_2").val(),
            "twiz_'.parent::F_START_TOP_POS_SIGN.'_far_1": $("#twiz_'.parent::F_START_TOP_POS_SIGN.'_far_1").val(),
            "twiz_'.parent::F_START_TOP_POS_SIGN.'_far_2": $("#twiz_'.parent::F_START_TOP_POS_SIGN.'_far_2").val(),
            "twiz_'.parent::F_START_TOP_POS.'_far_1": $("#twiz_'.parent::F_START_TOP_POS.'_far_1").val(),
            "twiz_'.parent::F_START_TOP_POS.'_far_2": $("#twiz_'.parent::F_START_TOP_POS.'_far_2").val(),
            "twiz_'.parent::F_START_TOP_POS_FORMAT.'_far_1": $("#twiz_'.parent::F_START_TOP_POS_FORMAT.'_far_1").val(),
            "twiz_'.parent::F_START_TOP_POS_FORMAT.'_far_2": $("#twiz_'.parent::F_START_TOP_POS_FORMAT.'_far_2").val(),
            "twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_1": $("#twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_1").val(),
            "twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_2": $("#twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_2").val(),
            "twiz_'.parent::F_START_LEFT_POS.'_far_1": $("#twiz_'.parent::F_START_LEFT_POS.'_far_1").val(),
            "twiz_'.parent::F_START_LEFT_POS.'_far_2": $("#twiz_'.parent::F_START_LEFT_POS.'_far_2").val(),
            "twiz_'.parent::F_START_LEFT_POS_FORMAT.'_far_1": $("#twiz_'.parent::F_START_LEFT_POS_FORMAT.'_far_1").val(),
            "twiz_'.parent::F_START_LEFT_POS_FORMAT.'_far_2": $("#twiz_'.parent::F_START_LEFT_POS_FORMAT.'_far_2").val(),   
            "twiz_'.parent::F_POSITION.'_far_1": $("#twiz_'.parent::F_POSITION.'_far_1").val(),   
            "twiz_'.parent::F_POSITION.'_far_2": $("#twiz_'.parent::F_POSITION.'_far_2").val(), 
            "twiz_'.parent::F_ZINDEX.'_far_1": $("#twiz_'.parent::F_ZINDEX.'_far_1").val(),   
            "twiz_'.parent::F_ZINDEX.'_far_2": $("#twiz_'.parent::F_ZINDEX.'_far_2").val(),         
            "twiz_'.parent::F_OUTPUT.'_far_1": $("#twiz_'.parent::F_OUTPUT.'_far_1").val(),   
            "twiz_'.parent::F_OUTPUT.'_far_2": $("#twiz_'.parent::F_OUTPUT.'_far_2").val(),           
            "twiz_'.parent::F_JAVASCRIPT.'_far_1": $("#twiz_'.parent::F_JAVASCRIPT.'_far_1").val(),   
            "twiz_'.parent::F_JAVASCRIPT.'_far_2": $("#twiz_'.parent::F_JAVASCRIPT.'_far_2").val(),                  
            "twiz_'.parent::F_CSS.'_far_1": $("#twiz_'.parent::F_CSS.'_far_1").val(),   
            "twiz_'.parent::F_CSS.'_far_2": $("#twiz_'.parent::F_CSS.'_far_2").val(),         
            "twiz_'.parent::F_EASING_A.'_far_1": $("#twiz_'.parent::F_EASING_A.'_far_1").val(),   
            "twiz_'.parent::F_EASING_A.'_far_2": $("#twiz_'.parent::F_EASING_A.'_far_2").val(),           
            "twiz_'.parent::F_MOVE_ELEMENT_A.'_far_1": $("#twiz_'.parent::F_MOVE_ELEMENT_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_ELEMENT_A.'_far_2": $("#twiz_'.parent::F_MOVE_ELEMENT_A.'_far_2").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_2": $("#twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_2").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_A.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_A.'_far_2": $("#twiz_'.parent::F_MOVE_TOP_POS_A.'_far_2").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_FORMAT_A.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_FORMAT_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_FORMAT_A.'_far_2": $("#twiz_'.parent::F_MOVE_TOP_POS_FORMAT_A.'_far_2").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_2": $("#twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_2").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_2": $("#twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_2").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_A.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_A.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_A.'_far_2": $("#twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_A.'_far_2").val(), 
            "twiz_'.parent::F_OPTIONS_A.'_far_1": $("#twiz_'.parent::F_OPTIONS_A.'_far_1").val(),
            "twiz_'.parent::F_OPTIONS_A.'_far_2": $("#twiz_'.parent::F_OPTIONS_A.'_far_2").val(), 
            "twiz_'.parent::F_EXTRA_JS_A.'_far_1": $("#twiz_'.parent::F_EXTRA_JS_A.'_far_1").val(),
            "twiz_'.parent::F_EXTRA_JS_A.'_far_2": $("#twiz_'.parent::F_EXTRA_JS_A.'_far_2").val(), 
            "twiz_'.parent::F_EASING_B.'_far_1": $("#twiz_'.parent::F_EASING_B.'_far_1").val(),   
            "twiz_'.parent::F_EASING_B.'_far_2": $("#twiz_'.parent::F_EASING_B.'_far_2").val(),  
            "twiz_'.parent::F_MOVE_ELEMENT_B.'_far_1": $("#twiz_'.parent::F_MOVE_ELEMENT_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_ELEMENT_B.'_far_2": $("#twiz_'.parent::F_MOVE_ELEMENT_B.'_far_2").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_2": $("#twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_2").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_B.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_B.'_far_2": $("#twiz_'.parent::F_MOVE_TOP_POS_B.'_far_2").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_FORMAT_B.'_far_1": $("#twiz_'.parent::F_MOVE_TOP_POS_FORMAT_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_TOP_POS_FORMAT_B.'_far_2": $("#twiz_'.parent::F_MOVE_TOP_POS_FORMAT_B.'_far_2").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_2": $("#twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_2").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_2": $("#twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_2").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_B.'_far_1": $("#twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_B.'_far_1").val(),
            "twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_B.'_far_2": $("#twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_B.'_far_2").val(),         
            "twiz_'.parent::F_OPTIONS_B.'_far_1": $("#twiz_'.parent::F_OPTIONS_B.'_far_1").val(),
            "twiz_'.parent::F_OPTIONS_B.'_far_2": $("#twiz_'.parent::F_OPTIONS_B.'_far_2").val(),          
            "twiz_'.parent::F_EXTRA_JS_B.'_far_1": $("#twiz_'.parent::F_EXTRA_JS_B.'_far_1").val(),
            "twiz_'.parent::F_EXTRA_JS_B.'_far_2": $("#twiz_'.parent::F_EXTRA_JS_B.'_far_2").val(),           
            "twiz_nonce": "'.$this->nonce.'"
         }, function(data) {
                $("#twiz_sub_container").hide();   
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                twizPostMenu(twiz_current_section_id);
                $("#twiz_far_matches").stop().animate({"opacity":1},0,function(){
                switch(true){
                    case ( data > 1 ):
                        $("#twiz_far_matches").html(data + " '.__('results modified.', 'the-welcomizer').'");
                        break;
                    case ( data == 1 ):
                        $("#twiz_far_matches").html(data + " '.__('result modified.', 'the-welcomizer').'");
                        break;            
                    case ( data == 0 ):
                        $("#twiz_far_matches").html(\'<span\' + \' class="twiz-red">'.__('No results modified.', 'the-welcomizer').'</span>\');
                        break; 
                }
                });
                twiz_view_id = null;
            });
            break;
        }}
    });  
    $(".twiz-toggle-far").click(function(){
        var twiz_toggle_status = 0;    
        var twiz_textid = $(this).attr("name");
        var twiz_charid = twiz_textid.substring(13,twiz_textid.length);   
        var twiz_src = $("#twiz_far_img_" + twiz_charid).attr("src");
        if(twiz_src.indexOf("twiz-plus.gif") != -1){
            $("#twiz_far_img_" + twiz_charid).attr({"src" : "'.$this->pluginUrl.'" + "/images/twiz-minus.gif"});                      
            $("#twiz_far_e_a_" + twiz_charid).attr("class","twiz-toggle-far twiz-bold");       
            $("." + twiz_charid).show("fast");  
            twiz_toggle_status = 1;             
        }else{
            $("#twiz_far_img_" + twiz_charid).attr({"src" : "'.$this->pluginUrl.'" + "/images/twiz-plus.gif"});
            $("#twiz_far_e_a_" + twiz_charid).attr("class","twiz-toggle-far"); 
            $("." + twiz_charid).hide("fast");   
        }
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_TOGGLE.'",
        "twiz_toggle_status": twiz_toggle_status,
        "twiz_toggle_type": "'.parent::KEY_TOGGLE_FAR.'",
        "twiz_charid": twiz_charid
        }, function(data) {});            
    });          
 } 
 var bind_twiz_Status = function() { 
    $("img[name^=twiz_status_img]").click(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(16,twiz_textid.length);
        var twiz_action = "'.parent::ACTION_STATUS.'";
        if(twiz_library_active == true){
            twiz_action = "'.parent::ACTION_LIBRARY_STATUS.'";
        }
        var twiz_menuid = twiz_numid.substring(0,5);
        switch(twiz_menuid){
            case "vmenu":
            $(this).hide();
            $("#twiz_status_img_" + twiz_numid.replace("vmenu_", "menu_")).hide();
            $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');            
            $("#twiz_status_img_" + twiz_numid.replace("vmenu_", "menu_")).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');                              
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.parent::ACTION_VMENU_STATUS.'",
            "twiz_id": twiz_numid
            }, function(data) {
                $("img[name^=twiz_status_img]").unbind("click");
                $("#twiz_status_" + twiz_numid).html(data);
                $("#twiz_status_" + twiz_numid.replace("vmenu_", "menu_")).html(data.replace(/vmenu_/g, "menu_"));                
                bind_twiz_Status();
            });
            break;    
        case "menu_":
            $(this).hide();
            $("#twiz_status_img_" + twiz_numid.replace("menu_", "vmenu_")).hide();    
            $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');   
            $("#twiz_status_img_" + twiz_numid.replace("menu_", "vmenu_")).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');   
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.parent::ACTION_MENU_STATUS.'",
            "twiz_id": twiz_numid
            }, function(data) {
                $("img[name^=twiz_status_img]").unbind("click");
                $("#twiz_status_" + twiz_numid).html(data);
                $("#twiz_status_" + twiz_numid.replace("menu_", "vmenu_")).html(data.replace(/menu_/g, "vmenu_"));
                bind_twiz_Status();
            });
            break;
        default:
             switch(twiz_numid){
                case "global":
                    $(this).hide();
                    $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');        
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": "'.$this->nonce.'", 
                    "twiz_action": "'.parent::ACTION_GLOBAL_STATUS.'"
                    }, function(data) {
                        $("img[name^=twiz_status_img]").unbind("click");
                        $("#twiz_global_status").html(data);
                        bind_twiz_Status();
                    });
                    break;
                default:
                    $(this).hide();
                    $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');      
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": "'.$this->nonce.'", 
                    "twiz_action": twiz_action,
                    "twiz_id": twiz_numid
                    }, function(data) { 
                        $("img[name^=twiz_status_img]").unbind("click");
                        $("#twiz_td_status_" + twiz_numid).html(data);
                        twiz_array_view_id[twiz_numid + "_1"] = undefined;
                        bind_twiz_Status();
                    });
                    break;
                }
        }            
    });
 }
 function twiz_Action_Edit_Copy_Rebind(){
    twiz_view_id = null;
    bind_twiz_Status();bind_twiz_Save();bind_twiz_Cancel();bind_twiz_Number_Restriction();
    bind_twiz_Choose_Options();
    bind_twiz_DynArrows();    
    $(document).scrollTop(0);
    $(document).scrollLeft(0);
    $("#twiz_layer_id").focus();
 }
 var bind_twiz_Edit = function() {  
        var twiz_c = {};
        $(".twiz-list-tr").draggable({
            delay: 150,
            containment: ".twiz-table-list",
            axis: "y",
            opacity: 0.9,
            revert: true,
            helper: "clone",
            numid: "",
            distance: 10, 
            start: function(event, ui) {
                var twiz_textid = $(this).attr("name");
                var twiz_numid = twiz_textid.substring(13, twiz_textid.length);
                twiz_c.numid = twiz_numid;
                twiz_c.tr = this;
                twiz_c.helper = ui.helper;
            }
        });
        var twiz_orig = "";
        $(".twiz-list-group-tr").droppable({
            tolerance: "touch",
            over: function() {
                 twiz_orig = $(this).attr("class");
                 $(this).attr({"class":"twiz-row-color-3"});
            },
            out: function() {
                 $(this).attr({"class":twiz_orig});
            },
            drop: function() {
                twizShowMainLoadingImage();
                var twiz_textid = $(this).attr("name");
                var twiz_numid = twiz_textid.substring(19, twiz_textid.length);            
                $.post(ajaxurl, {
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.self::ACTION_DROP_ROW.'",
                "twiz_from_id": twiz_c.numid,
                "twiz_to_id": twiz_numid,
                "twiz_section_id": twiz_current_section_id
                }, function(data) {
                    $("#twiz_container").html(data);
                    twizList_ReBind();
                    $("#twiz_loading_menu").html("");
                });  
                $(this).attr({"class":twiz_orig});
                $(twiz_c.tr).remove();
                $(twiz_c.helper).remove();
            }
        }); 
        $(".twiz-table-list-tr-h").droppable({
            drop: function() {       
                twizShowMainLoadingImage();
                $.post(ajaxurl, {
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.self::ACTION_DROP_ROW.'",
                "twiz_from_id": twiz_c.numid,
                "twiz_to_id": "",
                "twiz_section_id": twiz_current_section_id
                }, function(data) {
                    $("#twiz_container").html(data);
                    twizList_ReBind();
                    $("#twiz_loading_menu").html("");
                });  
                $(twiz_c.tr).remove();
                $(twiz_c.helper).remove();
            }
        });        
     $(".twiz-toggle-group").click(function(){
        var twiz_toggle_status = 0;      
        var twiz_textid = $(this).attr("name");
        var twiz_charid = twiz_textid.substring(15,twiz_textid.length);   
        var twiz_src = $("#twiz_group_img_" + twiz_charid).attr("src");
        if(twiz_src.indexOf("twiz-plus.gif") != -1){
            $("#twiz_group_img_" + twiz_charid).attr({"src" : "'.$this->pluginUrl.'" + "/images/twiz-minus.gif"}); 
            $("#twiz_element_a_" + twiz_parent_id).attr("class","twiz-toggle-group");                        
            $("#twiz_element_a_" + twiz_charid).attr("class","twiz-toggle-group twiz-bold");       
            $("." + twiz_charid).show("fast");  
            twiz_toggle_status = 1;    
            twiz_parent_id = twiz_charid;
        }else{
            $("#twiz_group_img_" + twiz_charid).attr({"src" : "'.$this->pluginUrl.'" + "/images/twiz-plus.gif"});
            $("#twiz_element_a_" + twiz_charid).attr("class","twiz-toggle-group"); 
            if(twiz_charid==twiz_parent_id){
            $("#twiz_element_a_" + twiz_parent_id).attr("class","twiz-toggle-group");                      
            twiz_parent_id = "";       
            }
            $("." + twiz_charid).hide("fast");   
        }
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_TOGGLE.'",
        "twiz_toggle_status": twiz_toggle_status,
        "twiz_toggle_type": "'.parent::KEY_TOGGLE_GROUP.'",
        "twiz_charid": twiz_charid
        }, function(data) {});              
    });       
    $(".twiz-group-edit").click(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(16,twiz_textid.length);
        twiz_view_id = "edit";
        $(this).hide();
        $(this).after(\'<img\' + \' id="twiz_img_group_edit" src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif-action" />\');  
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_GET_GROUP.'",
        "twiz_group_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data) {
            $("#twiz_sub_container").html(data);
            $("#twiz_sub_container").show();
            $("#" + twiz_textid).show();
            $("#twiz_img_group_edit").remove();
            $("#twiz_container").css("display", "none"); 
            twiz_ListMenu_Unbind();
            bind_twiz_ListMenu();
            $("#twiz_group_name").focus();
        });
    });       
    $(".twiz-edit").click(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_textidtemp = twiz_textid.substring(10,twiz_textid.length);
        var twiz_numid = "";
        if((twiz_textidtemp.substring(0,1) == "a") || (twiz_textidtemp.substring(0,1) == "v")){
            twiz_numid = twiz_textid.substring(12,twiz_textid.length);
            $(this).parent().html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
        }else{
            twiz_numid = twiz_textidtemp;
            $(this).hide();
            $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif-action" />\');  
        }
        twiz_view_id = "edit";
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_EDIT.'",
        "twiz_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data) {
            $("#twiz_container").html(data);
            twiz_Action_Edit_Copy_Rebind();
        });
    });
 }
 var bind_twiz_Copy = function() {
    $(".twiz-copy").click(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_textidtemp = twiz_textid.substring(10,twiz_textid.length);
        var twiz_numid = "";
        if((twiz_textidtemp.substring(0,1) == "a") || (twiz_textidtemp.substring(0,1) == "v")) {
            twiz_numid = twiz_textid.substring(12,twiz_textid.length);
            $(this).parent().html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');   
        }else{
            twiz_numid = twiz_textidtemp;
            $(this).hide();
            $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif-action" />\'); 
        }        
        twiz_view_id = "edit";
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_COPY.'",
        "twiz_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data) {
            $("#twiz_container").html(data);
            twiz_Action_Edit_Copy_Rebind();
        });
    });
    $(".twiz-group-copy").click(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(16,twiz_textid.length);
        var twiz_action = "'.parent::ACTION_COPY_GROUP.'";
        $(this).hide();
        $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif-action" />\');  
        $(".twiz-right-panel").fadeOut("slow");
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": twiz_action,
        "twiz_group_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id,
        "twiz_parent_id": twiz_parent_id
        }, function(data) {           
            $("#twiz_container").html(data);
            twiz_array_view_id[twiz_numid + "_1"] = undefined;        
            twizList_ReBind();
        });
    });      
 } 
 var bind_twiz_Delete = function() {
    $(".twiz-delete").click(function(){
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            var twiz_textid = $(this).attr("name");
            var twiz_textidtemp = twiz_textid.substring(12,twiz_textid.length);
            var twiz_numid = "";            
            var twiz_action = "'.parent::ACTION_DELETE.'";
            if(twiz_library_active == true){
                twiz_action = "'.parent::ACTION_DELETE_LIBRARY.'";
            }        
            if((twiz_textidtemp.substring(0,1) == "a")|| (twiz_textidtemp.substring(0,1) == "v")){
                twiz_numid = twiz_textid.substring(14,twiz_textid.length);
                $(this).parent().html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');               
            }else{
                twiz_numid = twiz_textidtemp;
                $(this).hide();
                $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif-action" />\');  
            }             
            $(".twiz-right-panel").fadeOut("slow");
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": twiz_action,
            "twiz_id": twiz_numid,
            "twiz_section_id": twiz_current_section_id,
            "twiz_parent_id": twiz_parent_id
            }, function(data) {              
                $("#twiz_container").html(data);
                if(twiz_library_active == true){
                    twizLibrary_Bind();
                }else{     
                    twiz_array_view_id[twiz_numid + "_1"] = undefined;            
                    twizList_ReBind();  
                }
            });
        }
   });
    $(".twiz-group-delete").click(function(){
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            var twiz_textid = $(this).attr("name");
            var twiz_numid = twiz_textid.substring(18,twiz_textid.length);
            var twiz_action = "'.parent::ACTION_DELETE_GROUP.'";
            $(this).hide();
            $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif-action" />\');  
            $(".twiz-right-panel").fadeOut("slow");
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": twiz_action,
            "twiz_group_id": twiz_numid,
            "twiz_section_id": twiz_current_section_id,
            "twiz_parent_id": twiz_parent_id
            }, function(data) {     
                $("#twiz_container").html(data);
                twiz_array_view_id[twiz_numid + "_1"] = undefined;            
                twizList_ReBind();
            });
        }
   });   
 }
 var bind_twiz_Cancel = function() {
    $("[name=twiz_cancel]").click(function(){
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
 var bind_twiz_Save = function() {
    $(".twiz-add-element").click(function() {
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(9,twiz_textid.length);  
        $("#twiz_tr_add_" + twiz_charid).hide();
        $("#twiz_tr_" + twiz_charid).fadeIn("fast");
    });   
    $("[class^=twiz-slc-js-features]").change(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(14,twiz_textid.length);    
        var twiz_current_js_id = "";
        if($(this).val()!=""){
            var twiz_optionstring =  $(this).val();
            switch(twiz_charid){
                case "css":
                    twiz_current_js_id = "#twiz_css";
                    break;             
                case "javascript":
                    twiz_current_js_id = "#twiz_javascript";
                    break;              
                case "javascript_a":
                    twiz_current_js_id = "#twiz_extra_js_a";
                    break;            
                case "javascript_b":
                    twiz_current_js_id = "#twiz_extra_js_b";
                    break;
            }
            var twiz_curval = $(twiz_current_js_id).val();
            if(twiz_curval!=""){ twiz_curval = twiz_curval + "\n";}
            $(twiz_current_js_id).attr({"value" : twiz_curval + twiz_optionstring});
        }
    });  
    $("#twiz_on_event").change(function() {
    if(($(this).val() != "")&&($(this).val() != "Manually")){
         $("#twiz_div_lock_event").show();
         $("#twiz_div_no_event").hide();
    }else{
         $("#twiz_div_lock_event").hide();
         $("#twiz_div_no_event").show();
    }
    });
    $("#twiz_lock_event").change(function() {
    if($(this).is(":checked")){
         $("#twiz_lock_event_type").show();
    }else{
         $("#twiz_lock_event_type").hide();
    }
    });    
    $(".twiz-js-features a").click(function() {
        var twiz_thtml = $(this).html();
        var twiz_textid = $(this).closest("div").attr("id");
        var twiz_id = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(17,twiz_textid.length);
        $("#" + twiz_textid + " a").attr("class", "");
        $(this).attr("class", "twiz-black");
        $("#twiz_slc_code_" + twiz_charid).hide();  
        $("#twiz_slc_stop_" + twiz_charid).hide();  
        $("#twiz_slc_bind_" + twiz_charid).hide(); 
        $("#twiz_slc_unlo_" + twiz_charid).hide(); 
        $("#twiz_slc_func_" + twiz_charid).hide();   
        switch(twiz_id){
            case "twiz_jsf_functions_" + twiz_charid:
                $("#twiz_slc_func_" + twiz_charid).show();            
                break;
            case "twiz_jsf_code_snippets_" + twiz_charid:
                $("#twiz_slc_code_" + twiz_charid).show();            
                break;
            case "twiz_jsf_stop_" + twiz_charid:
                $("#twiz_slc_stop_" + twiz_charid).show();            
                break;
            case "twiz_jsf_unlock_" + twiz_charid:
                $("#twiz_slc_unlo_" + twiz_charid).show();            
                break;
            case "twiz_jsf_bind_" + twiz_charid:
                $("#twiz_slc_bind_" + twiz_charid).show();            
               break;
        }
    });    
    $("#twiz_layer_id").click(function(){
        if($("#twiz_layer_id").val() == "'.__('Please type a main element.', 'the-welcomizer').'"){
            $("#twiz_layer_id").attr({"value" : ""});
            $("#twiz_layer_id").css("color", "#333333");
        }
    });   
    $("[name=twiz_save]").click(function(){
    var twiz_valid_element = true;
    if(($("#twiz_layer_id").val() == "'.__('Please type a main element.', 'the-welcomizer').'")
    || ($("#twiz_layer_id").val() == "")){
        $("#twiz_layer_id").val("'.__('Please type a main element.', 'the-welcomizer').'");
        $("#twiz_layer_id").css("color", "#BC0B0B");
        twiz_valid_element = false;
    }
    if(twiz_valid_element == true){
    var twiz_textid = $(this).attr("id");
    var twiz_charid = twiz_textid.substring(10,twiz_textid.length);   
    var twiz_stay = $("#twiz_stay").is(":checked");    
    $("[name=twiz_save]").css({"color" : "#9FD0D5 "});
    $("#twiz_save_img_box_" + twiz_charid).html("").show();
    $("#twiz_save_img_box_" + twiz_charid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" />\');
    var twiz_numid = $("#twiz_id").val();
    $.post(ajaxurl, {
         "action": "twiz_ajax_callback",
         "twiz_nonce": "'.$this->nonce.'", 
         "twiz_action": "'.parent::ACTION_SAVE.'",
         "twiz_stay": $("#twiz_stay").is(":checked"), ';
         
         $i=0;
         $count_array = count($this->array_fields);
         
         foreach($this->array_fields as $value){
         
             $comma = ( $count_array != $i ) ? ','."\n" : '';
          
             switch($value){
                 
                 case parent::F_SECTION_ID:
                 
                    $header.= '"twiz_'.$value.'": twiz_current_section_id'.$comma;
                    break;  
                                   
                 case parent::F_ID:
                 
                    $header.= '"twiz_'.$value.'": twiz_numid'.$comma;
                    break;
                    
                 case parent::F_STATUS:
                 
                    $header.= '"twiz_'.$value.'": $("#twiz_'.$value.'").is(":checked")'.$comma;
                    break;

                 case parent::F_LOCK_EVENT:
                 
                    $header.= '"twiz_'.$value.'": $("#twiz_'.$value.'").is(":checked")'.$comma;
                    break;
                    
                 default:
                 
                    $header.= '"twiz_'.$value.'": $("#twiz_'.$value.'").val()'.$comma;
             }
             
             $i++;
         }
         
        $header.= '}, function(data) {
        data = JSON.parse(data);
        $("img[name^=twiz_status_img]").unbind("click");
        $("[name=twiz_cancel]").unbind("click");
        $("[name=twiz_save]").unbind("click");
        $("#twiz_on_event").unbind("change");
        $("[class^=twiz-slc-js-features]").unbind("change");
        $(".twiz-js-features a").unbind("click");              
        $("#twiz_container").html(data.html);
        if(data.result > 0){
            twiz_view_id = null;
            twiz_array_view_id = new Array();
        }else{ 
            var twiz_for_numid = "";
            var twiz_for_view_level = "";        
            for (twiz_array_view_key in twiz_array_view_id){
                twiz_array_view_key = twiz_array_view_key.split("_");
                twiz_for_numid = twiz_array_view_key[0];
                twiz_for_view_level = twiz_array_view_key[1];
                if (twiz_numid == twiz_for_numid) {
                    twiz_array_view_id[twiz_numid + "_" + twiz_for_view_level] = undefined;
                }
            }
        }
        bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
        bind_twiz_Choose_Options();
        bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Order_by(); 
        if(twiz_stay ==  true){
            $("#twiz_save_img_box_" + twiz_charid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' +  \'/images/twiz-success.gif"\' + \'/>\'); 
            $("#twiz_save_img_box_" + twiz_charid).fadeOut("slow"); 
            if(twiz_charid==2){
                $("#twiz_stay").focus(); 
            }
        }
        $("[name=twiz_save]").css({"color" : "#ffffff"});
    });
   }});
  }
  var bind_twiz_AdminSave = function() {
     $(".twiz-toggle-admin").click(function(){
        var twiz_toggle_status = 0;
        var twiz_textid = $(this).attr("name");
        var twiz_charid = twiz_textid.substring(15,twiz_textid.length);   
        var twiz_src = $("#twiz_admin_img_" + twiz_charid).attr("src");
        if(twiz_src.indexOf("twiz-plus.gif") != -1){
            $("#twiz_admin_img_" + twiz_charid).attr({"src" : "'.$this->pluginUrl.'" + "/images/twiz-minus.gif"});                      
            $("#twiz_admin_e_a_" + twiz_charid).attr("class","twiz-toggle-admin twiz-bold");       
            $("." + twiz_charid).show("fast");  
            twiz_toggle_status = 1;
        }else{
            $("#twiz_admin_img_" + twiz_charid).attr({"src" : "'.$this->pluginUrl.'" + "/images/twiz-plus.gif"});
            $("#twiz_admin_e_a_" + twiz_charid).attr("class","twiz-toggle-admin"); 
            $("." + twiz_charid).hide("fast");   
        }
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_TOGGLE.'",
        "twiz_toggle_status": twiz_toggle_status,
        "twiz_toggle_type": "'.parent::KEY_TOGGLE_ADMIN.'",
        "twiz_charid": twiz_charid
        }, function(data) { });        
    });  
    $("[name=twiz_admin_save]").click(function(){
    var twiz_textid = $(this).attr("id");
    var twiz_charid = twiz_textid.substring(16,twiz_textid.length);       
    $("[name=twiz_admin_save]").css({"color" : "#9FD0D5 "});
    $("#twiz_admin_save_img_box_" + twiz_charid).show();  
    $("#twiz_admin_save_img_box_" + twiz_charid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" />\');   
    var twiz_numid = $("#twiz_id").val();
    $.post(ajaxurl, {
         "action": "twiz_ajax_callback",
         "twiz_nonce": "'.$this->nonce.'", 
         "twiz_action": "'.parent::ACTION_SAVE_ADMIN.'",
         "twiz_'.parent::KEY_REGISTER_JQUERY.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY.'").is(":checked"),
         "twiz_'.parent::KEY_REGISTER_JQUERY_TRANSIT.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY_TRANSIT.'").is(":checked"),
         "twiz_'.parent::KEY_REGISTER_JQUERY_TRANSFORM.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY_TRANSFORM.'").is(":checked"),
         "twiz_'.parent::KEY_REGISTER_JQUERY_ROTATE3DI.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY_ROTATE3DI.'").is(":checked"),
         "twiz_'.parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE.'").is(":checked"),
         "twiz_'.parent::KEY_REGISTER_JQUERY_EASING.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY_EASING.'").is(":checked"),
         "twiz_'.parent::KEY_OUTPUT_COMPRESSION.'": $("#twiz_'.parent::KEY_OUTPUT_COMPRESSION.'").is(":checked"),
         "twiz_'.parent::KEY_OUTPUT.'": $("#twiz_'.parent::KEY_OUTPUT.'").val(),
         "twiz_'.parent::KEY_OUTPUT_PROTECTED.'": $("#twiz_'.parent::KEY_OUTPUT_PROTECTED.'").is(":checked"),
         "twiz_'.parent::KEY_EXTRA_EASING.'": $("#twiz_'.parent::KEY_EXTRA_EASING.'").is(":checked"),
         "twiz_'.parent::KEY_NUMBER_POSTS.'": $("#twiz_'.parent::KEY_NUMBER_POSTS.'").val(),
         "twiz_'.parent::KEY_SORT_LIB_DIR.'": $("#twiz_'.parent::KEY_SORT_LIB_DIR.'").val(),
         "twiz_'.parent::KEY_STARTING_POSITION.'": $("#twiz_'.parent::KEY_STARTING_POSITION.'").val(),
         "twiz_'.parent::KEY_MIN_ROLE_LEVEL.'": $("#twiz_'.parent::KEY_MIN_ROLE_LEVEL.'").val(),
         "twiz_'.parent::KEY_MIN_ROLE_ADMIN.'": $("#twiz_'.parent::KEY_MIN_ROLE_ADMIN.'").val(),
         "twiz_'.parent::KEY_MIN_ROLE_LIBRARY.'": $("#twiz_'.parent::KEY_MIN_ROLE_LIBRARY.'").val(),
         "twiz_'.parent::KEY_FOOTER_ADS.'": $("#twiz_'.parent::KEY_FOOTER_ADS.'").is(":checked"),
         "twiz_'.parent::KEY_DELETE_ALL.'": $("#twiz_'.parent::KEY_DELETE_ALL.'").is(":checked")
    }, function(data) {
        data = JSON.parse(data);
        if(data.rebind == "rebind"){
            $("#twiz_footer").html(data.html);
            bind_twiz_ads();
        }else if(data.rebind == "remove"){
            $("#twiz_footer").html(data.html);
        }
        if(data.extra_easing == "1"){
            $("#twiz_'.parent::KEY_EXTRA_EASING.'").attr({"checked":"checked"}); 
        }    
        $("#twiz_admin_save_img_box_" + twiz_charid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' +  \'/images/twiz-success.gif"\' + \'/>\');    
        $("#twiz_admin_save_img_box_" + twiz_charid).fadeOut("slow");         
        $("[name=twiz_admin_save]").removeAttr("disabled");
        $("[name=twiz_admin_save]").css({"color" : "#ffffff"});
    });
   });
  }
  var bind_twiz_Number_Restriction = function() {
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
  var bind_twiz_Choose_Options = function() {
    $("a[name^=twiz_choose_options]").click(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_charid = twiz_textid.substring(20,twiz_textid.length);
        $("#twiz_td_full_option_" + twiz_charid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_OPTIONS.'",
        "twiz_charid": twiz_charid
        }, function(data) {
        $("#twiz_td_full_option_" + twiz_charid).html(data);
        bind_twiz_Select_Options(twiz_charid);
        });
    });
  }    
  var bind_twiz_Select_Options = function(twiz_charid) {
    $("#twiz_slc_options_" + twiz_charid).change(function(){
        var twiz_curval = $("#twiz_options_" + twiz_charid).val();
        if($(this).val()!=""){
            if(twiz_curval!=""){ twiz_curval = twiz_curval + "\n";}
            var twiz_optionstring =  $(this).val();
            $("#twiz_options_" + twiz_charid).attr({"value" : twiz_curval + twiz_optionstring}) 
        }
    });    
  }    
  var bind_twiz_Ajax_TD = function() { 
    $("div[id^=twiz_ajax_td_val]").mouseover(function(){
            $(this).attr({"title" : "'.__('Edit', 'the-welcomizer').'"});
            $(this).css({"cursor":"pointer"});
    });        
    $("[name^=twiz_on_event]").change(function (){
        var twiz_textid = $(this).attr("name");        
        var twiz_numid = twiz_textid.substring(14,twiz_textid.length);
        var twiz_txtval = $("#twiz_on_event_" + twiz_numid).val();
        $("#twiz_ajax_td_edit_on_event_" + twiz_numid).hide();        
        $("#twiz_ajax_td_loading_on_event_" + twiz_numid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');        
        $.post(ajaxurl, { 
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.parent::ACTION_EDIT_TD.'",
                "twiz_id": twiz_numid, 
                "twiz_column": "on_event", 
                "twiz_value": twiz_txtval
                }, function(data) {
                    $("#twiz_ajax_td_loading_on_event_" + twiz_numid).html("");
                    $("[name^=twiz_on_event]").unbind("focusout");
                    $("[name^=twiz_on_event]").unbind("change");
                    $("div[id^=twiz_ajax_td_val]").unbind("click");
                    $("div[id^=twiz_ajax_td_val]").unbind("mouseover");
                    $("input[name^=twiz_input]").unbind("keypress");
                    $("input[name^=twiz_input]").unbind("blur");
                    $("#twiz_ajax_td_val_on_event_" + twiz_numid).html(data);
                    $("#twiz_ajax_td_val_on_event_" + twiz_numid).fadeIn("fast");
                    $("#twiz_ajax_td_val_on_event_" + twiz_numid).css({"color":"green"});
                    bind_twiz_Ajax_TD();bind_twiz_TR_View();
                });
    });    
    $("[name^=twiz_on_event]").focusout(function (){
        var twiz_textid = $(this).attr("name");        
        var twiz_numid = twiz_textid.substring(14,twiz_textid.length);
        var twiz_columnName = "on_event";
        var twiz_txtval = $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).html();
        $("#twiz_ajax_td_edit_" + twiz_columnName + "_" + twiz_numid).hide();
        $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).fadeIn("fast");  
    }); 
    $("input[name^=twiz_input]").blur(function (){
        var twiz_textid = $(this).attr("name");
        var twiz_columnName = twiz_textid.substring(11,16);
        var twiz_numid = "";
        switch(twiz_columnName){
            case "delay":        
                twiz_numid = twiz_textid.substring(17,twiz_textid.length);
                break;
            case "durat": 
                twiz_columnName = "duration";
                twiz_numid = twiz_textid.substring(20,twiz_textid.length);         
                break;  
        }
        var twiz_txtval = $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).html();
        $("#twiz_ajax_td_edit_" + twiz_columnName + "_" + twiz_numid).hide();
        $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).fadeIn("fast");   
    });
    $("input[name^=twiz_input]").keypress(function (e){
        var twiz_textid = $(this).attr("name");
        var twiz_columnName = twiz_textid.substring(11,16);
        var twiz_numid = "";        
        switch(twiz_columnName){
            case "delay": 
                twiz_numid = twiz_textid.substring(17,twiz_textid.length);
                break;
            case "durat":
                twiz_columnName = "duration";
                twiz_numid = twiz_textid.substring(20,twiz_textid.length);
                break;
        }
        switch(e.keyCode){
            case 27:
                $("#twiz_ajax_td_edit_" + twiz_columnName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).fadeIn("fast");
            break;
            case 13:
                var twiz_txtval = $("#twiz_input_" + twiz_columnName + "_" + twiz_numid).val();
                $("#twiz_ajax_td_edit_" + twiz_columnName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).html("");
                $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_loading_" + twiz_columnName + "_" + twiz_numid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
                $.post(ajaxurl, { 
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.parent::ACTION_EDIT_TD.'",
                "twiz_id": twiz_numid, 
                "twiz_column": twiz_columnName, 
                "twiz_value": twiz_txtval
                }, function(data) {
                    $("#twiz_ajax_td_loading_" + twiz_columnName + "_" + twiz_numid).html("");
                    $("[name^=twiz_on_event]").unbind("change");
                    $("[name^=twiz_on_event]").unbind("focusout");
                    $("input[name^=twiz_input]").unbind("keypress");
                    $("input[name^=twiz_input]").unbind("blur");
                    $("div[id^=twiz_ajax_td_val]").unbind("click");
                    $("div[id^=twiz_ajax_td_val]").unbind("mouseover");
                    $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).html(data);
                    $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).fadeIn("fast");
                    $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).css({color:"green"});
                    bind_twiz_Ajax_TD();bind_twiz_TR_View();
                });
            break;
        }            
    });
    $("div[id^=twiz_ajax_td_val]").click(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_columnName = twiz_textid.substring(17,22);
        var twiz_columnRealName = "";
        var twiz_numid = "";
        var twiz_tdvalue = $(this).html();
        if(twiz_tdvalue.substring(0, 2) == "On" ){
           twiz_tdvalue =  twiz_tdvalue.substring(2, twiz_tdvalue.length);
        }
        switch(twiz_columnName){
            case "delay": 
                twiz_columnRealName = "delay";    
                twiz_numid = twiz_textid.substring(23,twiz_textid.length);
                $("#twiz_ajax_td_val_" + twiz_columnRealName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_edit_" + twiz_columnRealName + "_" + twiz_numid).fadeIn("fast");                
                $("#twiz_input_" + twiz_columnRealName + "_" + twiz_numid).select();
                break;
            case "durat":
                twiz_columnRealName = "duration";            
                twiz_numid = twiz_textid.substring(26,twiz_textid.length);
                $("#twiz_ajax_td_val_" + twiz_columnRealName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_edit_" + twiz_columnRealName + "_" + twiz_numid).fadeIn("fast");         
                $("#twiz_input_" + twiz_columnRealName + "_" + twiz_numid).select();                
                break;
            case "on_ev":
                twiz_columnRealName = "on_event"; 
                twiz_numid = twiz_textid.substring(26,twiz_textid.length);
                $("#twiz_ajax_td_edit_" + twiz_columnRealName + "_" + twiz_numid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
                $("#twiz_ajax_td_val_" + twiz_columnRealName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_edit_" + twiz_columnRealName + "_" + twiz_numid).fadeIn("fast");                
                $.post(ajaxurl, {
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.parent::ACTION_GET_EVENT_LIST.'",
                "twiz_event": twiz_tdvalue,
                "twiz_id": twiz_numid
                }, function(data) {
                    $("#twiz_ajax_td_edit_on_event_" + twiz_numid ).html(data);
                    $("[name^=twiz_on_event]").unbind("change");
                    $("[name^=twiz_on_event]").unbind("focusout");
                    $("input[name^=twiz_input]").unbind("keypress");
                    $("input[name^=twiz_input]").unbind("blur");
                    $("div[id^=twiz_ajax_td_val]").unbind("click");
                    $("div[id^=twiz_ajax_td_val]").unbind("mouseover");
                    bind_twiz_Ajax_TD();bind_twiz_TR_View();                  
                    $("#twiz_" + twiz_columnRealName + "_" + twiz_numid).focus();
                }); 
                break;                
        }                
    });
  }
  function twizGetView(twiz_numid, e, twizviewlevel){
      if(twiz_view_id!="edit"){
          var twiz_right_panel = "twiz_right_panel_" + twizviewlevel; 
          var twiz_from_level = "twiz_right_panel_" + (twizviewlevel - 1); 
          $("#twiz_vertical_menu").hide();
          $("#twiz_view_box").css({"float":"left", "position":"relative", "top":"0px"});      
          $("#twiz_list_tr_action_" + twiz_view_id).css("visibility", "hidden");
          $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");
          twiz_view_id = twiz_numid;
          if(twiz_array_view_id[twiz_numid + "_" + twizviewlevel] === undefined) {
              $("#" + twiz_right_panel).remove();
              if(twizviewlevel == 1){
                  $(".twiz-right-panel").css("display", "none");
                  $("#twiz_view_box").append(\'<div\' + \' id="twiz_right_panel_\' + twizviewlevel + \'" class="twiz-right-panel"></div>\');
              }else{
                  $("#" + twiz_from_level).after(\'<div\' + \' id="twiz_right_panel_\' + twizviewlevel + \'" class="twiz-right-panel"></div>\');
              }          
              $("#" + twiz_right_panel).html(\'<div\' + \' class="twiz-panel-loading"></div>\');
              $("#" + twiz_right_panel).css("display", "block");    
              $.post(ajaxurl, {
              "action": "twiz_ajax_callback",
              "twiz_nonce": "'.$this->nonce.'", 
              "twiz_action": "'.parent::ACTION_VIEW.'",
              "twiz_view_level": twizviewlevel,
              "twiz_id": twiz_numid
              }, function(data) {
                  $("#" + twiz_right_panel).html(data);
                  twiz_array_view_id[twiz_numid + "_" + twizviewlevel] = data;
                  twizView_Rebind();
              });    
          }else{
              $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");
              $(".twiz-right-panel").css("display", "none");
              $("#" + twiz_right_panel).remove();
              if(twizviewlevel == 1){
                  $("#twiz_view_box").append(\'<div\' + \' id="twiz_right_panel_\' + twizviewlevel + \'" class="twiz-right-panel">\' + twiz_array_view_id[twiz_numid + "_" + twizviewlevel] +\'</div>\');
              }else{
                  $("#" + twiz_from_level).after(\'<div\' + \' id="twiz_right_panel_\' + twizviewlevel + \'" class="twiz-right-panel">\' + twiz_array_view_id[twiz_numid + "_" + twizviewlevel] +\'</div>\');
              }
              twizView_Rebind();
          }
          for(var twiz_i = 1;twiz_i <= twizviewlevel;twiz_i++){ 
              $("#twiz_right_panel_" + twiz_i).css("display", "block");  
          }        
          if(twizviewlevel == 1) {
              twiz_panel_offset_switch = $(window).width() - 180;
              $("#" + twiz_right_panel).css({"float":"left","position":"relative", "top":e.pageY - 220 + "px"});             
          }else{
              var twiz_top_pos = $("#" + twiz_from_level).position().top;
              $("#" + twiz_right_panel).css({"float":"left","position":"relative", "top":twiz_top_pos + "px"});
          }
          if($("#" + twiz_right_panel).offset().left > twiz_panel_offset_switch){ 
              twiz_panel_offset_switch = $("#" + twiz_from_level).offset().left + ($(window).width() - 220);
              $("html, body").animate({ scrollLeft:  $("#" + twiz_from_level).offset().left - 100}, 2000, function(){});
          }          
          return true;
      }else{
          return false;
      }
    }  
  var bind_twiz_TR_View = function() {    
    $(".twiz-list-tr").mouseenter(function(e){
    if(twiz_library_active == false){
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(13, twiz_textid.length);
        var twiz_ok = twizGetView(twiz_numid, e, 1);
    }
    });
    $(".twiz-list-group-tr").mouseover(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(19, twiz_textid.length);
        $("#twiz_vertical_menu").hide();   
        $("#twiz_list_tr_action_" + twiz_view_id).css("visibility", "hidden");
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");
        $(".twiz-right-panel").css("display", "none");
        twiz_view_id = twiz_numid;
    });        
  } 
  var bind_twiz_Menu = function() {
    $("a[name^=twiz_section_cancel]").click(function(){
        $("#twiz_sub_container").hide();
        if( $("#twiz_id").val() === undefined ){ 
            $("#twiz_listmenu").css("display", "block"); 
        }
        $("#twiz_container").css("display", "block");  
    });
    $("#twiz_add_menu").click(function(){    
        $("#twiz_sub_container").html("");  
        twizGetMultiSection("", "'.parent::ACTION_NEW.'");
        $("#twiz_sub_container").show("fast");  
        $("#twiz_listmenu").css("display", "none");          
        $("#twiz_container").css("display", "none");  
        twiz_view_id = null;
    });   
    $("#twiz_edit_menu").click(function(){    
        $("#twiz_sub_container").html("");  
        $("#twiz_sub_container").show("fast");  
        $("#twiz_listmenu").css("display", "none");  
        $("#twiz_container").css("display", "none");  
        twizGetMultiSection(twiz_current_section_id, "'.parent::ACTION_EDIT.'");
        twiz_view_id = null;
    });     
    $("#twiz_delete_menu").click(function(){  
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.parent::ACTION_DELETE_SECTION.'",
            "twiz_section_id": twiz_current_section_id
            }, function(data) {                
                twiz_current_section_id = data;
                twizGetMenu();           
                $("#qq_upload_list li").remove();
                $("#twiz_export_url").html(""); 
                var twiz_section = "";
                twizPostMenu(data);
            });
        }
    });     
    $("div[id^=twiz_menu_]").click(function(){
        var twiz_textid = $(this).attr("id");
        twiz_current_section_id = twiz_textid.substring(10,twiz_textid.length);
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
        var twiz_textid = $(this).attr("id");
        twiz_current_section_id = twiz_textid.substring(11,twiz_textid.length);
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
  var bind_twiz_Order_by = function() {
    $("a[id^=twiz_order_by_]").click(function(){
        var twiz_textid = $(this).attr("id");
        twiz_order_by = twiz_textid.substring(14,twiz_textid.length);
        twizPostMenu(twiz_current_section_id, twiz_order_by);
    });  
  } 
  function twizList_ReBind(){
    $("img[name^=twiz_status_img]").unbind("click");
    $("[name=twiz_cancel]").unbind("click");
    $("a[id^=twiz_order_by_]").unbind("click");
    $("[name=twiz_save]").unbind("click");
    $("#twiz_on_event").unbind("change");   
    $("[class^=twiz-slc-js-features]").unbind("change");
    $(".twiz-js-features a").unbind("click");            
    $(".twiz-edit").unbind("click");
    $(".twiz-copy").unbind("click");                 
    $(".twiz-delete").unbind("click");                 
    $(".twiz-group-edit").unbind("click");
    $(".twiz-group-delete").unbind("click");
    $(".twiz-list-tr").unbind("draggable");
    $(".twiz-list-group-tr").unbind("droppable");
    $(".twiz-table-list-tr-h").unbind("droppable");
    bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
    bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();bind_twiz_Choose_Options();
    bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Order_by();    
  }  
  function twizPostMenu(twiz_section_id, twiz_order_by){
   twizShowMainLoadingImage();
   $("#twiz_far_matches").html("");
   $("#twiz_sub_container").html("");  
   $("#twiz_edit_menu").show();
   $("#twiz_delete_menu").show();   
   $("#twiz_library_menu").attr({"class" : "twiz-menu twiz-display-none"});
   $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-display-none"});
   $("#twiz_container").css("display", "block");    
   $("#twiz_container").html("");
   $("#twiz_container").slideToggle("fast"); 
   $("#twiz_library_upload").fadeOut("fast");
   $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_MENU.'",
        "twiz_section_id": twiz_section_id,
        "twiz_order_by": twiz_order_by
        }, function(data) {
            $("#twiz_container").html(data);
            twiz_view_id = null;
            twiz_array_view_id = new Array();
            twizList_ReBind();
            $("#twiz_container").slideToggle("fast");  
            $("#twiz_loading_menu").html("");
            twiz_library_active = false;
        });
  }
  var bind_twiz_Save_Section = function() {
    $("#twiz_slc_cookie_option_1").change(function() {
        if($(this).val() != ""){
             $("#twiz_div_cookie_name").show();
             $("#twiz_div_cookie_with").show();
             $("#twiz_div_cookie_option_2").show();
        }else{
             $("#twiz_div_cookie_name").hide();
             $("#twiz_div_cookie_with").hide();
             $("#twiz_div_cookie_option_2").hide();             
        }
    });  
    $(".twiz-shortcode-sample").click(function(){    
        $(this).select();  
    });     
    $("#twiz_shortcode").keyup(function (e){
        $("#twiz_shortcode_sample").attr({"value" :"[twiz id=\"" + $("#twiz_shortcode").val() + "\"]"});
        $("#twiz_shortcode_sample_theme").attr({"value" :"<?php echo do_shortcode( \'[twiz id=\"" + $("#twiz_shortcode").val() + "\"]\' ); ?>"});
    });
    $(".twiz-shortcode-sample").keypress(function (e){
        if(e.ctrlKey || e.metaKey){
            return true;
        }else{
            return false;
        }
    });
    $("input[name=twiz_output_choice]").click(function(){
        var twiz_blockid = $(this).val();
        $(".twiz-custom-message").html("");
        $(".twiz-block-ouput").hide();
        $("#" + twiz_blockid).show();
    });
    $("#twiz_slc_multi_sections").dblclick(function(){
        var twiz_selectval = $("#twiz_slc_multi_sections option:selected").text();
        $("input[id=twiz_section_name]").attr({"value" : twiz_selectval});
        $("#twiz_section_name").css("color", "#333333");
    });
    $("#twiz_slc_sections").change(function(){
        var twiz_section = $("#twiz_slc_sections option:selected").val();
        if((twiz_section!="")){
            $("input[id=twiz_section_name]").attr({"value" : $("#twiz_slc_sections option:selected").text()});
             $("#twiz_section_name").css("color", "#333333");
        }
    });
    $("#twiz_section_name").click(function(){
        if($("#twiz_section_name").val() == "'.__('Give the section a name.', 'the-welcomizer').'"){
            $("#twiz_section_name").attr({"value" : ""});
            $("#twiz_section_name").css("color", "#333333");
        }
    });   
    $("#twiz_cookie_name").click(function(){
        if($("#twiz_cookie_name").val() == "'.__('Please type a name.', 'the-welcomizer').'"){
            $("#twiz_cookie_name").attr({"value" : ""});
            $("#twiz_cookie_name").css("color", "#333333");
        }
    });   
    $("#twiz_shortcode").click(function(){
        if($("#twiz_shortcode").val() == "' .__('Please type a short code.', 'the-welcomizer').'"){
            $("#twiz_shortcode").attr({"value" : ""});
            $("#twiz_shortcode").css("color", "#333333");
        }
    });        
    $("#twiz_save_section").click(function(){
        var twiz_sectionid = [];
        var twiz_validsection = true;
        var twiz_sectiontovalid = "";
        var twiz_sectionok = "";
        if(($("#twiz_section_name").val() == "'.__('Give the section a name.', 'the-welcomizer').'")
        || ($("#twiz_section_name").val() == "")){
            $("#twiz_section_name").val("'.__('Give the section a name.', 'the-welcomizer').'");
            $("#twiz_section_name").css("color", "#BC0B0B");
            twiz_validsection = false;
        }
        if((($("#twiz_cookie_name").val() == "' .__('Please type a name.', 'the-welcomizer').'")
        || ($("#twiz_cookie_name").val() == "")) && ($("#twiz_slc_cookie_option_1").val() != "")){
            $("#twiz_cookie_name").val("'.__('Please type a name.', 'the-welcomizer').'");
            $("#twiz_cookie_name").css("color", "#BC0B0B");
            twiz_validsection = false;
        }        
        $("input[name=twiz_output_choice]:radio").each(function(){
        if ($(this).attr("checked")){
            twiz_sectiontovalid = $(this).val();
        }});
        switch(twiz_sectiontovalid){
            case "twiz_shortcode_output":
                if(($("#twiz_shortcode").val()== "") || ($("#twiz_shortcode").val() == "' .__('Please type a short code.', 'the-welcomizer').'")){
                    $("#twiz_shortcode").attr({"value" : "' .__('Please type a short code.', 'the-welcomizer').'"});
                    $("#twiz_shortcode").css("color", "#BC0B0B");
                    $("#twiz_loading_menu").html("");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_0").html("");
                }              
                break;                 
            case "twiz_single_output":
                twiz_sectionid[0] = $("#twiz_slc_sections").val();
                if(twiz_sectionid[0] == ""){
                    $("#twiz_custom_message_1").html("' .__('Please choose an output option.', 'the-welcomizer').'");
                    $("#twiz_loading_menu").html("");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_1").html("");
                }              
                break;
            case "twiz_multiple_output":
                if( $("#twiz_slc_multi_sections :selected").length > 0){
                    $("#twiz_slc_multi_sections :selected").each(function(i, selected) {
                        twiz_sectionid[i] = $(selected).val();
                    });
                }            
                if(twiz_sectionid.length == 0){
                    $("#twiz_custom_message_2").html("' .__('Please select at least one option.', 'the-welcomizer').'");
                    $("#twiz_loading_menu").html("");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_2").html("");
                }     
            break;
            case "twiz_logic_output":
                if($("#twiz_custom_logic").val()== ""){
                    $("#twiz_custom_message_3").html("' .__('Please type a custom logic.', 'the-welcomizer').'");
                    $("#twiz_loading_menu").html("");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_3").html("");
                }              
                break;    
        }
        if(twiz_validsection == true){
            $("#twiz_menu_save_img_box").show();  
            $("#twiz_menu_save_img_box").html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" />\');  
            $("input[id=twiz_save_section]").css({"color" : "#9FD0D5"});
            $.post(ajaxurl, {
                 "action": "twiz_ajax_callback",
                 "twiz_nonce": "'.$this->nonce.'", 
                 "twiz_action": "'.parent::ACTION_SAVE_SECTION.'",
                 "twiz_section_status": $("#twiz_section_status").is(":checked"),
                 "twiz_section_name": $("#twiz_section_name").val(),
                 "twiz_current_section_id": $("#twiz_section_id").val(),
                 "twiz_output_choice": $("input[name=twiz_output_choice]:checked").val(),
                 "twiz_custom_logic": $("#twiz_custom_logic").val(),
                 "twiz_shortcode": $("#twiz_shortcode").val(),
                 "twiz_section_id": JSON.stringify(twiz_sectionid),
                 "twiz_cookie_option_1": $("#twiz_slc_cookie_option_1").val(),
                 "twiz_cookie_option_2": $("#twiz_slc_cookie_option_2").val(),
                 "twiz_cookie_with": $("#twiz_slc_cookie_with").val(),
                 "twiz_cookie_name": $("#twiz_cookie_name").val(),
                 "twiz_cookie_scope": $("#twiz_slc_cookie_scope").val()                 
                }, function(data) { 
                    $("#twiz_sub_container").hide();
                    twiz_current_section_id = data; 
                    twiz_sectionok = twizGetMenu();
                    $("#twiz_container").html("");
                    twizPostMenu(twiz_current_section_id);
                    $("#twiz_loading_menu").html("");
                    $("input[id=twiz_save_section]").css({"color" : "#ffffff"});
                    $("#twiz_menu_save_img_box").html(\'<img\' + \' src="'.$this->pluginUrl.'\' +  \'/images/twiz-success.gif"\' + \'/>\');    
                    $("#twiz_menu_save_img_box").fadeOut("slow");                       
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
      var twiz_top_sign  = $("#twiz_move_top_pos_sign_" + ab).val();
      var twiz_top_val   = $("#twiz_move_top_pos_" + ab).val();
      var twiz_left_sign = $("#twiz_move_left_pos_sign_" + ab).val();
      var twiz_left_val  = $("#twiz_move_left_pos_" + ab).val();
      var twiz_direction = "";
      var twiz_htmlimage = "";
      if((twiz_top_sign!="=")&&(twiz_left_sign!="=")){
          switch(true){
             case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val=="")): 
                twiz_direction = "'.parent::DIMAGE_N.'";
                break;
             case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val!="")&&(twiz_left_sign=="+")):
                twiz_direction = "'.parent::DIMAGE_NE.'";
                break;        
             case ((twiz_top_val=="")&&(twiz_left_val!="")&&(twiz_left_sign=="+")): 
                twiz_direction = "'.parent::DIMAGE_E.'";
                break;         
             case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val!="")&&(twiz_left_sign=="+")): 
                twiz_direction = "'.parent::DIMAGE_SE.'";    
                break;     
             case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val=="")): 
                twiz_direction = "'.parent::DIMAGE_S.'";    
                break; 
             case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                twiz_direction = "'.parent::DIMAGE_SW.'";    
                break;    
             case ((twiz_top_val=="")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                twiz_direction = "'.parent::DIMAGE_W.'";    
                break;          
             case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                twiz_direction = "'.parent::DIMAGE_NW.'";    
                break;           
          }
          if(twiz_direction!=""){ 
              twiz_htmlimage = \'<div\' + \' class="twiz-arrow twiz-arrow-\' + twiz_direction + \'"></div>\';
          }
      }
      $("#twiz_td_arrow_" + ab).html(twiz_htmlimage);
    }
   }
    var bind_twiz_Library_New_Order = function() {
        $("div[name^=twiz_new_order_up]").click(function(){
            var twiz_textid = $(this).attr("id");
            var twiz_numid = twiz_textid.substring(18,twiz_textid.length);
            $("#twiz_list_td_" + twiz_numid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
            twizOrderLibrary("'.parent::LB_ORDER_UP.'", twiz_numid);
        });
        $("div[name^=twiz_new_order_down]").click(function(){
            var twiz_textid = $(this).attr("id");
            var twiz_numid = twiz_textid.substring(20, twiz_textid.length);
            $("#twiz_list_td_" + twiz_numid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
            twizOrderLibrary("'.parent::LB_ORDER_DOWN.'", twiz_numid);
        });        
    }
    function twizOrderLibrary(twiz_order, twiz_id){
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_ORDER_LIBRARY.'",
        "twiz_order": twiz_order,
        "twiz_id": twiz_id
        }, function(data) {                
            $("div[name^=twiz_new_order_up]").unbind("click");
            $("div[name^=twiz_new_order_down]").unbind("click");
            $(".twiz-delete").unbind("click");            
            $("#twiz_container").html(data);
            twizLibrary_Bind();
        });   
  }
  var bind_twiz_FooterMenu = function() {
    $("#twiz_export").click(function(){
        $("#twiz_export_url").html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
        $("#qq_upload_list li").remove(); 
        var twiz_animid = $("#twiz_id").val();
        if(twiz_animid===undefined){
           twiz_animid = "";
        }
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_EXPORT.'",
        "twiz_section_id": twiz_current_section_id,
        "twiz_id": twiz_animid
        }, function(data) {
           $("#twiz_export_url").html(data);
        });
    });';         
    
 if(current_user_can($this->admin_option[parent::KEY_MIN_ROLE_LIBRARY])){    
 
    $header.= '
    $("#twiz_library").click(function(){
        $("#twiz_sub_container").hide();
        $("#twiz_listmenu").css("display", "none"); 
        twizSwitchFooterMenu();
        twizPostLibrary();
    });     
    $("#twiz_library_menu").click(function(){
        $("#twiz_sub_container").hide();
        twizSwitchFooterMenu();
        twizPostLibrary();
    });';
  }else{
  
      $header.= '
      $("#twiz_library").css("color", "#999999");
      ';    
  }
 
  if(current_user_can($this->admin_option[parent::KEY_MIN_ROLE_ADMIN])){   
  
   $header.= '
   $("div[id^=twiz_admin]").click(function(){
      twizShowMainLoadingImage();
      $("#twiz_sub_container").hide();
      $("#twiz_listmenu").css("display", "none"); 
      twizSwitchFooterMenu();
      $("#twiz_edit_menu").hide();
      $("#twiz_delete_menu").hide();       
      $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
      $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
      $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});
      $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
      $("#twiz_library_menu").attr({"class" : "twiz-menu twiz-display-none"});
      $("#twiz_container").css("display", "block");    
      $("#twiz_container").html("");
      $("#twiz_container").slideToggle("fast"); 
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_ADMIN.'"
        }, function(data) {                
            $("#twiz_container").html(data);
            $("#twiz_container").slideToggle("fast"); 
            bind_twiz_AdminSave();
            bind_twiz_Cancel();
            $("#twiz_loading_menu").html("");
        }); 
    });';     
    
  }else{
  
      $header.= '
      $("#twiz_admin").css("color", "#999999");
      ';    
  }
  
  $header.= '
  }  
  function twizSwitchFooterMenu(){
      $("#twiz_export").fadeOut("fast");
      $("#twiz_import").fadeOut("fast");
      $("#qq_upload_list li").remove();
      $("#twiz_export_url").html(""); 
  }   
  var binb_twiz_Link_dir = function() {
     $("#twiz_lib_dir").click(function(){
        if($("#twiz_lib_dir").val() == "'.__('Type a directory name.', 'the-welcomizer').'"){
            $("#twiz_lib_dir").attr({"value" : ""});
            $("#twiz_lib_dir").css("color", "#333333");
        }
    });  
    $("a[name=twiz_lib_cancel]").click(function(){
        $("#twiz_sub_container").hide();
        $("#twiz_lib_menu").css("display", "block"); 
        $("#twiz_container").css("display", "block");  
    });  
    $("input[name=twiz_lib_save]").click(function(){ 
        var twiz_validlib = true;
        if(($("#twiz_lib_dir").val() == "'.__('Type a directory name.', 'the-welcomizer').'")
        || ($("#twiz_lib_dir").val() == "")){
            $("#twiz_lib_dir").val("'.__('Type a directory name.', 'the-welcomizer').'");
            $("#twiz_lib_dir").css("color", "#BC0B0B");
            twiz_validlib = false;
        }
        if(twiz_validlib == true){
            $("#twiz_lib_save_img_box").show();  
            $("#twiz_lib_save_img_box").html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" />\');  
            $("input[id=twiz_lib_save]").css({"color" : "#9FD0D5"});        
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_LINK_LIBRARY_DIR.'",
            "twiz_lib_dir": $("#twiz_lib_dir").val(),
            "twiz_nonce": "'.$this->nonce.'"
         }, function(data) {
                $("img[name^=twiz_status]").unbind("click");
                $(".twiz-delete").unbind("click");    
                $("#twiz_sub_container").html("");  
                $("#twiz_container").html(data);
                $("#twiz_container").css("display", "block"); 
                twizLibrary_Bind();
            });
        }
    }); 
  }
  function twizLibrary_Bind(){
    binb_twiz_Link_dir(); bind_twiz_Library();
    bind_twiz_Status();bind_twiz_Delete();
    bind_twiz_Library_New_Order();
  }
  var bind_twiz_Library = function() {  
     $("#twiz_lib_menu").click(function(){
         twizShowMainLoadingImage();
         $("#twiz_container").css("display", "none");  
         $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.parent::ACTION_GET_LIBRARY_DIR.'"
            }, function(data) {                         
                $("#twiz_sub_container").html(data);
                $("#twiz_sub_container").show();   
                binb_twiz_Link_dir();                
                $("#twiz_loading_menu").html("");
                $("#twiz_lib_dir").focus();
            });   
     });
     $(".twiz-toggle-library").click(function(){
        var twiz_toggle_status = 0;
        var twiz_textid = $(this).attr("name");
        var twiz_charid = twiz_textid.substring(17,twiz_textid.length);   
        var twiz_src = $("#twiz_library_img_" + twiz_charid).attr("src");
        if(twiz_src.indexOf("twiz-plus.gif") != -1){
            $("#twiz_library_img_" + twiz_charid).attr({"src" : "'.$this->pluginUrl.'" + "/images/twiz-minus.gif"});                     
            $("#twiz_library_e_a_" + twiz_charid).attr("class","twiz-toggle-library twiz-bold");       
            $("." + twiz_charid).show("fast"); 
            twiz_toggle_status = 1;            
        }else{
            $("#twiz_library_img_" + twiz_charid).attr({"src" : "'.$this->pluginUrl.'" + "/images/twiz-plus.gif"});
            $("#twiz_library_e_a_" + twiz_charid).attr("class","twiz-toggle-library"); 
            $("." + twiz_charid).hide("fast");   
        }
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_TOGGLE.'",
        "twiz_toggle_status": twiz_toggle_status,
        "twiz_toggle_type": "'.parent::KEY_TOGGLE_LIBRARY.'",
        "twiz_charid": twiz_charid
        }, function(data) {});             
     }); 
     $(".twiz-library-unlink").click(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_id = twiz_textid.substring(20,twiz_textid.length);   
        $(this).hide();
        $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif"/>\');          
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_UNLINK_LIBRARY_DIR.'",
        "twiz_id": twiz_id
        },function(data){
            $("#twiz_container").html(data);
            twizLibrary_Bind();
        }); 
     });   
  }   
  function twizPostLibrary(){
      twizShowMainLoadingImage();
      twiz_library_active = true;
      $("#twiz_edit_menu").hide();
      $("#twiz_delete_menu").hide();      
      $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-display-none"});
      $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
      $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
      $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});            
      $("#twiz_library_menu").attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
      $("#twiz_container").css("display", "block");    
      $("#twiz_container").html("");
      $("#twiz_container").slideToggle("fast"); 
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_LIBRARY.'"
        }, function(data) {                
            $("img[name^=twiz_status]").unbind("click");
            $(".twiz-delete").unbind("click");            
            $("#twiz_container").html(data);
            $("#twiz_container").slideToggle("fast"); 
            twizLibrary_Bind();
            $("#twiz_loading_menu").html("");
        });   
  }
  function twizGetMenu(){
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'",       
        "twiz_action": "'.parent::ACTION_GET_MENU.'",
        "twiz_section_id": twiz_current_section_id
        }, function(datamenu) {   
              $.post(ajaxurl, {
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'",       
                "twiz_action": "'.parent::ACTION_GET_VMENU.'",
                "twiz_section_id": twiz_current_section_id
                }, function(data) {                
                    $("#twiz_ajax_menu").html(datamenu);              
                    $("#twiz_section_cancel").unbind("click");
                    $("#twiz_add_menu").unbind("click");
                    $("#twiz_edit_menu").unbind("click");
                    $("#twiz_delete_menu").unbind("click");
                    $("div[id^=twiz_menu_]").unbind("click");
                    $("div[id^=twiz_vmenu_]").unbind("click");
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
  function twizShowMainLoadingImage(){
      $("#twiz_loading_menu").html(\'<div\' + \' class="twiz-menu twiz-noborder-right"><img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" /></div>\');
  }
  function twizGetMultiSection(twiz_section_id, twiz_action_lbl){
      twizShowMainLoadingImage();
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'",
        "twiz_action": "'.parent::ACTION_GET_MULTI_SECTION.'",
        "twiz_action_lbl": twiz_action_lbl,
        "twiz_section_id": twiz_section_id
        }, function(data) {                
            $("#twiz_section_cancel").unbind("click");
            $("#twiz_add_menu").unbind("click");
            $("#twiz_edit_menu").unbind("click");
            $("#twiz_delete_menu").unbind("click");
            $("div[id^=twiz_menu_]").unbind("click");
            $("div[id^=twiz_vmenu_]").unbind("click");
            $("#twiz_save_section").unbind("click");
            $("#twiz_section_name").unbind("click");
            $("#twiz_slc_sections").unbind("change");
            $("#twiz_sub_container").html(data);
            $("#twiz_loading_menu").html("");
            $("img[name^=twiz_status_img]").unbind("click");
            bind_twiz_Menu();     
            bind_twiz_Status();             
            bind_twiz_Save_Section();
        });   
  }  
  function twizView_Rebind(){
      $("[name^=twiz_anim_link]").unbind("click");
      $("[name^=twiz_anim_link]").unbind("hover");   
      $("[name^=twiz_group_anim_link]").unbind("click");
      $("[name^=twiz_group_anim_link]").unbind("hover");
      $("[id^=twiz_right_panel_]").unbind("hover");   
      $("[id^=twiz_edit_v_]").unbind("hover");
      $(".twiz-toggle-group").unbind("click"); 
      $(".twiz-table-list-tr-h").unbind("droppable"); 
      $(".twiz-list-group-tr").unbind("droppable"); 
      $(".twiz-list-tr").unbind("draggable"); 
      $(".twiz-edit").unbind("click");  
      $(".twiz-group-edit").unbind("click");  
      $(".twiz-copy").unbind("click");  
      $(".twiz-group-copy").unbind("click");  
      $(".twiz-delete").unbind("click");  
      $(".twiz-group-delete").unbind("click"); 
      bind_twiz_View();bind_twiz_Edit();bind_twiz_Copy();bind_twiz_Delete();
  }
  var bind_twiz_View = function() {
    $("[id^=twiz_right_panel_]").hover(function(){
    $(".twiz-list-tr-action").css("visibility", "hidden");  
        var twiz_textid = $(this).attr("id");
        var twiz_view_level  = twiz_textid.substring(17,twiz_textid.length);
        $("#twiz_view_tr_action_" + twiz_view_level).css("visibility", "visible");              
    },
    function(){
        var twiz_textid = $(this).attr("id");  
        var twiz_view_level  = twiz_textid.substring(17,twiz_textid.length);
        $("#twiz_view_tr_action_" + twiz_view_level).css("visibility", "hidden");    
    });  
    $("[id^=twiz_edit_v_]").hover(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid  = twiz_textid.substring(12,twiz_textid.length);
        if($("#twiz_list_tr_" + twiz_numid).attr("class") != "twiz-list-tr twiz-row-color-3 ui-draggable"){
            twiz_orig_anim_link_class = $("#twiz_list_tr_" + twiz_numid).attr("class");
        }
        $("#twiz_list_tr_" + twiz_numid).attr({"class" : "twiz-list-tr twiz-row-color-3 ui-draggable"});
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible"); 
    },function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid  = twiz_textid.substring(12,twiz_textid.length);    
        $("#twiz_list_tr_" + twiz_numid).attr({"class" : twiz_orig_anim_link_class});
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "hidden");  
    });   
    $("[id^=twiz_copy_v_]").hover(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid  = twiz_textid.substring(12,twiz_textid.length);
        if($("#twiz_list_tr_" + twiz_numid).attr("class") != "twiz-list-tr twiz-row-color-3 ui-draggable"){
            twiz_orig_anim_link_class = $("#twiz_list_tr_" + twiz_numid).attr("class");
        }
        $("#twiz_list_tr_" + twiz_numid).attr({"class" : "twiz-list-tr twiz-row-color-3 ui-draggable"});
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible"); 
    },function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid  = twiz_textid.substring(12,twiz_textid.length);    
        $("#twiz_list_tr_" + twiz_numid).attr({"class" : twiz_orig_anim_link_class});
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "hidden");  
    }); 
    $("[id^=twiz_delete_v_]").hover(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid  = twiz_textid.substring(14,twiz_textid.length);
        if($("#twiz_list_tr_" + twiz_numid).attr("class") != "twiz-list-tr twiz-row-color-3 ui-draggable"){
            twiz_orig_anim_link_class = $("#twiz_list_tr_" + twiz_numid).attr("class");
        }
        $("#twiz_list_tr_" + twiz_numid).attr({"class" : "twiz-list-tr twiz-row-color-3 ui-draggable"});
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible"); 
    },function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid  = twiz_textid.substring(14,twiz_textid.length);    
        $("#twiz_list_tr_" + twiz_numid).attr({"class" : twiz_orig_anim_link_class});
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "hidden");  
    });     
    $("[name^=twiz_anim_link]").click(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_charid  = twiz_textid.substring(15,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];
        var twiz_view_level = twiz_charid[1];
        twiz_view_id = "edit";
        $("#twiz_anim_link_img_box_" + twiz_numid + "_" + twiz_view_level).show();  
        $("#twiz_anim_link_img_box_" + twiz_numid + "_" + twiz_view_level).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" />\');          
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_EDIT.'",
        "twiz_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data) {
            $("#twiz_anim_link_img_box_" + twiz_numid + "_" + twiz_view_level).fadeOut("slow");   
            $("#twiz_container").html(data);
            twiz_Action_Edit_Copy_Rebind();
            $("#twiz_container").show("slow");
        });
    });       
    $("[name^=twiz_anim_link]").hover(function(e){
        var twiz_textid = $(this).attr("id");
        var twiz_charid  = twiz_textid.substring(15,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];
        var twiz_view_level = twiz_charid[1];
        if($("#twiz_list_tr_" + twiz_numid).attr("class") != "twiz-list-tr twiz-row-color-3 ui-draggable"){
            twiz_orig_anim_link_class = $("#twiz_list_tr_" + twiz_numid).attr("class");
        }
        $("#twiz_list_tr_" + twiz_numid).attr({"class" : "twiz-list-tr twiz-row-color-3 ui-draggable"});
        var twiz_ok = twizGetView(twiz_numid, e, twiz_view_level);
    },function(e){
        var twiz_textid = $(this).attr("id");
        var twiz_charid  = twiz_textid.substring(15,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];      
        $("#twiz_list_tr_" + twiz_numid).attr({"class" : twiz_orig_anim_link_class});
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "hidden"); 
    });     
    $("[name^=twiz_group_anim_link]").click(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_charid  = twiz_textid.substring(21,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];
        var twiz_view_level = twiz_charid[1];
        twiz_view_id = "edit";
        $("#twiz_group_anim_link_img_box_" + twiz_numid + "_" + twiz_view_level).show();  
        $("#twiz_group_anim_link_img_box_" + twiz_numid + "_" + twiz_view_level).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" />\');  
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_GET_GROUP.'",
        "twiz_group_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data) {
            $("#twiz_group_anim_link_img_box_" + twiz_numid + "_" + twiz_view_level).fadeOut("slow");   
            $(".twiz-right-panel").fadeOut("fast");   
            $("#twiz_sub_container").html(data);
            $("#twiz_sub_container").show();
            $("#twiz_container").css("display", "none"); 
            twiz_ListMenu_Unbind();
            bind_twiz_ListMenu();
            $("#twiz_group_name").focus();
        });
    });       
    $("[name^=twiz_group_anim_link]").hover(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_charid  = twiz_textid.substring(21,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];
        if($("#twiz_list_group_tr_" + twiz_numid).attr("class") != "twiz-list-tr twiz-row-color-3 ui-draggable"){
            twiz_group_orig_anim_link_class = $("#twiz_list_group_tr_" + twiz_numid).attr("class");
        }        
        $("#twiz_list_group_tr_" + twiz_numid).attr({"class" : "twiz-list-tr twiz-row-color-3 ui-droppable"});
    },function(){
        var twiz_textid = $(this).attr("name");
        var twiz_charid  = twiz_textid.substring(21,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];
        $("#twiz_list_group_tr_" + twiz_numid).attr({"class" : twiz_group_orig_anim_link_class});
    });      
  }
  function twiz_reset_nav(){
     $(".twiz-right-panel").fadeOut("fast");   
     $(".twiz-list-tr-action").css("visibility", "hidden");
     twiz_view_id = null;
  }
  $(".twiz-reset-nav").mouseover(function(){
     twiz_reset_nav();
  });       
  $("#twiz_more_menu").click(function(){   
     $("#twiz_vertical_menu").toggle();     
  });
  $("#twiz_head_logo").click(function (){   
    if($("#twiz_skin_bullet").css("top")=="22px"){
        $("#twiz_skin_bullet").stop().animate({top:"-=22px"},1000, function(){
        $("#twiz_skin_bullet").css("z-index","1");
        });
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_BULLET_UP.'"
        }, function(data) { }); 
    }
    if($("#twiz_skin_bullet").css("top")=="0px"){
        $("#twiz_skin_bullet").css("z-index","-1");
        $("#twiz_skin_bullet").stop().animate({top:"+=22px"},500, function(){
        });
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_BULLET_DOWN.'"
        }, function(data) { });         
    }    
  });       
  $(".twiz-skins").click(function(){   
      var twiz_textid = $(this).attr("id");
      var twiz_skinname = twiz_textid.substring(10, twiz_textid.length); 
      var twiz_css_skin = "'.$this->pluginUrl.parent::SKIN_PATH.'" + twiz_skinname + "/twiz-style.css?version='.$this->cssVersion.'";
      twiz_skin = "'.parent::SKIN_PATH.'" + twiz_skinname;
      $("#twiz-'.$this->cssVersion.'-a-css").attr("href",twiz_css_skin);
      $.post(ajaxurl, {
      "action": "twiz_ajax_callback",
      "twiz_skin": twiz_skinname, 
      "twiz_nonce": "'.$this->nonce.'", 
      "twiz_action": "'.parent::ACTION_SAVE_SKIN.'"
      }, function(data) { }); 
  }); 
  var bind_twiz_ads = function() {  
      $(".twiz-ads-img").hover(function (){   
          $(this).stop().animate({opacity:1},100, function(){});   
      },function (){   
          $(this).stop().animate({opacity:0.3},50, function(){});   
      });    
  }';
    
    if($this->admin_option[self::KEY_FOOTER_ADS] != "1"){
      
     $header .= '
  function twiz_ads(){
    $.post(ajaxurl, {
    "action": "twiz_ajax_callback",
    "twiz_nonce": "'.$this->nonce.'", 
    "twiz_action": "'.parent::ACTION_GET_MAIN_ADS.'"
    }, function(data) { 
      $("#twiz_footer").html(data);
      bind_twiz_ads();
    });  
  }
  twiz_ads();';
  }
  
  $header .= '
  $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
  $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
  $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
  bind_twiz_Status();bind_twiz_ListMenu();bind_twiz_Edit();bind_twiz_Copy();bind_twiz_Delete();
  bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
  bind_twiz_Choose_Options();bind_twiz_Order_by();
  bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Menu();
  bind_twiz_Save_Section();bind_twiz_FooterMenu();
  $("#twiz_container").slideToggle("slow");
 });';
       return $header;
    }
}?>