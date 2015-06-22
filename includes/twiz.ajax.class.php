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
    
class TwizAjax extends Twiz{

    function __construct(){
    
        parent::__construct();
        
    }
 
    function getAjaxHeader(){

    $header = 'var twiz_parent_id = ""; var twiz_showOrHide_more_section_options = false; var twiz_current_section_id = "'.$this->DEFAULT_SECTION[$this->user_id].'"; var twiz_current_group_id = ""; jQuery(document).ready(function($){
 $.ajaxSetup({ cache: false });';
 
    // restore admin
    if( is_multisite() ){
    
        $header.='
        var twiz_override_network_settings = "'.$this->override_network_settings.'";';
    }
    
 $header.='
 var twiz_skin =  "'.$this->skin[$this->user_id].'";
 if((twiz_skin == "")||(twiz_skin == "'.parent::SKIN_PATH.'")){ twiz_skin = "'.parent::SKIN_PATH.''.parent::DEFAULT_SKIN.'";}
 var twiz_view_id = null;
 if(twiz_current_section_id == ""){ twiz_current_section_id = "'.$this->DEFAULT_SECTION_HOME.'";}
 var twiz_last_section_id = "";
 var twiz_default_section_id = "'.$this->DEFAULT_SECTION_HOME.'";
 var twiz_array_view_id = new Array();
 var twiz_library_active = false;
 var twiz_orig_anim_link_class = "";
 var twiz_group_orig_anim_link_class = "";
 var twiz_panel_offset_switch = "";
 var twiz_ajax_locked = false;
 var twiz_nonce = "'.$this->nonce.'";
 var twiz_hscroll_status = "'.$this->hscroll_status[$this->user_id].'";
 var twiz_import_file = new qq.FileUploader({
    element: document.getElementById("twiz_import_from_computer"),
    action: "'.$this->pluginUrl.'/includes/import/server/php.php",
    debug: false,
    id: "twiz_import_file",
    label: "'.__('From Computer', 'the-welcomizer').'",
    allowedExtensions: ["'.parent::EXT_TWZ.'", "'.parent::EXT_TWIZ.'", "'.parent::EXT_XML.'"],
    sizeLimit: '.parent::IMPORT_MAX_SIZE.', // max size   
    minSizeLimit: 1, // min size
    onSubmit: function (){ $("#qq_upload_list li").remove(); $("#twiz_export_url").html(""); twiz_import_file.setParams({action: "twiz_ajax_callback", twiz_nonce: twiz_nonce, twiz_action: "'.parent::ACTION_IMPORT_FROM_COMPUTER.'", twiz_section_id: twiz_current_section_id, twiz_group_id: twiz_current_group_id }); },
    onComplete: function (id, fileName, data){ twiz_current_group_id = ""; twizScrollTop();
    if(data.isnewsection == "1") {
        twiz_current_section_id = data.section_id;
        twizGetMenu();
        twizPostMenu(twiz_current_section_id,"","none");
        twizGetMultiSection(twiz_current_section_id, "'.parent::ACTION_EDIT.'", "'.parent::ACTION_IMPORT.'");
    }else{ 
        restoreTwizCurrentSectionId();
        twizPostMenu(twiz_current_section_id,"","block");
    }},
    messages: {
        typeError: "'.__('{file} has invalid extension. Only ', 'the-welcomizer').parent::EXT_TWIZ.','.parent::EXT_XML.__(' are allowed.', 'the-welcomizer').'",
        sizeError: "'.__('{file} is too large, maximum file size is {sizeLimit}.', 'the-welcomizer').'",
        minSizeError: "'.__('{file} is too small, minimum file size is {minSizeLimit}.', 'the-welcomizer').'",
        emptyError: "'.__('{file} is empty, please select files again without it.', 'the-welcomizer').'",
        onLeave: "'.__('The files are being uploaded, if you leave now the upload will be cancelled.', 'the-welcomizer').'",
        showMessage: function(message){ alert(message); }
    }
 });
 var twiz_upload_javascript = new qq.FileUploader({
    element: document.getElementById("twiz_upload_javascript"),
    action: "'.$this->pluginUrl.'/includes/import/server/php.php",
    debug: false,
    id: "twiz_upload_javascript",
    label: "'.__('JavaScript', 'the-welcomizer').'",
    allowedExtensions: ["'.parent::EXT_JS.'","'.parent::EXT_CSS.'"],
    sizeLimit: '.parent::IMPORT_MAX_SIZE.', // max size   
    minSizeLimit: 1, // min size
    onSubmit: function (){ $("#qq_upload_list li").remove(); twiz_upload_javascript.setParams({action: "twiz_ajax_callback", twiz_nonce: twiz_nonce, twiz_action: "'.parent::ACTION_UPLOAD_LIBRARY.'"});},
    onComplete: function (){ twizPostLibrary(); twizScrollTop(); },
    messages: {
        typeError: "'.__('{file} has invalid extension. Only {extensions} are allowed.', 'the-welcomizer').'",
        sizeError: "'.__('{file} is too large, maximum file size is {sizeLimit}.', 'the-welcomizer').'",
        minSizeError: "'.__('{file} is too small, minimum file size is {minSizeLimit}.', 'the-welcomizer').'",
        emptyError: "'.__('{file} is empty, please select files again without it.', 'the-welcomizer').'",
        onLeave: "'.__('The files are being uploaded, if you leave now the upload will be cancelled.', 'the-welcomizer').'",
        showMessage: function(message){ alert(message); }
    }
 }); 
 var twiz_upload_css = new qq.FileUploader({
    element: document.getElementById("twiz_upload_css"),
    action: "'.$this->pluginUrl.'/includes/import/server/php.php",
    debug: false,
    id: "twiz_upload_css",
    label: "'.__('Style Sheet', 'the-welcomizer').'",
    allowedExtensions: ["'.parent::EXT_CSS.'","'.parent::EXT_JS.'"],
    sizeLimit: '.parent::IMPORT_MAX_SIZE.', // max size   
    minSizeLimit: 1, // min size
    onSubmit: function (){ $("#qq_upload_list li").remove(); twiz_upload_css.setParams({action: "twiz_ajax_callback", twiz_nonce: twiz_nonce, twiz_action: "'.parent::ACTION_UPLOAD_LIBRARY.'"});},
    onComplete: function (){ twizPostLibrary(); twizScrollTop(); },
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
    $("a[id^=twiz_far_cancel]").unbind("click");
    $("input[name=twiz_far_find]").unbind("click");
    $("input[name=twiz_far_replace]").unbind("click");
    $("#twiz_findandreplace").unbind("click");
    $("[name=twiz_listmenu]").unbind("mouseenter");
    $("#twiz_new").unbind("click");
    $("input[name=twiz_far_choice]").unbind("click");
    $("#twiz_create_group").unbind("click");
    $("#twiz_group_cancel").unbind("click");
    $("input[name=twiz_group_save]").unbind("click");
    $("#twiz_group_name").unbind("click");
 }
 function twiz_ListMenu_Cancel(){
        $("#twiz_far_matches").html("");
        $("#twiz_sub_container").hide();
        $("[name=twiz_listmenu]").css("display", "block");
        $("#twiz_container").css("display", "block");
        $("#twiz_import").fadeIn("fast");
        $("#twiz_export").fadeIn("fast");
        $("[id^=twiz_group_edit_]").attr("class","twiz-group-edit twiz-edit-img");
 } 
 var bind_twiz_ListMenu = function(){
     $("[name=twiz_listmenu]").mouseenter(function(){   
        twiz_reset_nav();
     });
     $("#twiz_new").click(function(){
        if(twiz_ajax_locked == false){    
         twiz_ajax_locked = true;
         twiz_view_id = "edit";
         twizShowMainLoadingImage();
         twizSwitchFooterMenu();
         $("#twiz_container").css("display", "none");
         $("#twiz_far_matches").html("");
         $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_NEW.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_parent_id": twiz_parent_id,
            "twiz_nonce": twiz_nonce
         }, function(data){
                twizUnLockedAction();
                $("#twiz_container").html(data);
                $("#twiz_container").css("display", "block");
                bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
                bind_twiz_Choose_Options();
                twizHideMainLoadingImage();
                $("#twiz_layer_id").focus();
            }).fail(function(){ twizUnLockedAction(); });
     }else{twizLockedAction();}});
     $("#twiz_create_group").click(function(){
     if(twiz_ajax_locked == false){         
         twiz_ajax_locked = true;
         twiz_view_id = "edit";
         twizShowMainLoadingImage();
         twizSwitchFooterMenu();         
         $("#twiz_container").css("display", "none");
         $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_nonce": twiz_nonce,
            "twiz_action": "'.parent::ACTION_GET_GROUP.'",
            "twiz_sub_action": "'.parent::ACTION_NEW.'",
            "twiz_section_id": twiz_current_section_id
         }, function(data){
                twizUnLockedAction();
                $("#twiz_sub_container").html(data);
                $("#twiz_sub_container").show();
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                $("#twiz_group_name").focus();
                twizHideMainLoadingImage();
            }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
    $("#twiz_group_cancel").click(function(){
        twiz_current_group_id = "";
        twiz_ListMenu_Cancel();
    });
    $("#twiz_group_name").click(function(){
        if($("#twiz_group_name").val() == "'.__('Give the group a name.', 'the-welcomizer').'"){
            $("#twiz_group_name").attr({"value" : ""});
            $("#twiz_group_name").css("color", "#333333");
        }
    });
    $("input[name=twiz_group_save]").click(function(){
     if(twiz_ajax_locked == false){    
        var twiz_validgroup = true;
        if(($("#twiz_group_name").val() == "'.__('Give the group a name.', 'the-welcomizer').'")
        || ($("#twiz_group_name").val() == "")){
            $("#twiz_group_name").val("'.__('Give the group a name.', 'the-welcomizer').'");
            $("#twiz_group_name").css("color", "#BC0B0B");
            twiz_validgroup = false;
        }
        if(twiz_validgroup == true){
            twiz_ajax_locked = true;
            $("#twiz_group_save_img_box").show();
            $("#twiz_group_save_img_box").attr("class","twiz-save twiz-loading-gif-save");
            $("input[id=twiz_group_save]").css({"color" : "#9FD0D5"});
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_SAVE_GROUP.'",
            "twiz_sub_action": $("#twiz_sub_action").val(),
            "twiz_group_export_id": $("#twiz_group_export_id").val(),
            "twiz_section_id": twiz_current_section_id,
            "twiz_group_status": $("#twiz_group_status").is(":checked"),
            "twiz_group_name": $("#twiz_group_name").val(),
            "twiz_group_id": $("#twiz_group_id").val(),
            "twiz_nonce": twiz_nonce
         }, function(data){
                twiz_current_group_id = "";
                twizUnLockedAction();
                data = JSON.parse(data);
                $("img[name^=twiz_status_img]").unbind("click");
                $("a[id^=twiz_cancel]").unbind("click");
                $("input[name=twiz_save]").unbind("click");
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
                $("[name=twiz_listmenu]").css("display", "block");
                $("#twiz_import").fadeIn("fast");  
                bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
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
            }).fail(function(){ twizUnLockedAction(); });
        }
     }else{twizLockedAction();}});
     $("#twiz_findandreplace").click(function(){
     if(twiz_ajax_locked == false){  
     twiz_ajax_locked = true;
     twiz_view_id = "edit";
     twizShowMainLoadingImage();
     twizSwitchFooterMenu();
     $("#twiz_container").css("display", "none");
     $.post(ajaxurl,  {
        "action": "twiz_ajax_callback",
        "twiz_action": "'.parent::ACTION_GET_FINDANDREPLACE.'",
        "twiz_section_id": twiz_current_section_id,
        "twiz_nonce": twiz_nonce
     }, function(data){
            twizUnLockedAction();
            $("#twiz_sub_container").html(data);
            $("#twiz_sub_container").show();
            twiz_ListMenu_Unbind();
            bind_twiz_ListMenu();
            twizHideMainLoadingImage();
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
    $("input[name=twiz_far_choice]").click(function(){ 
    if(twiz_ajax_locked == false){ 
        twiz_ajax_locked = true;
        twiz_far_choice = $("input[name=twiz_far_choice]:checked").val();
        $("[name=twiz_far_table]").hide();
        $("#" + $(this).val()).show();
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_SAVE_FAR_PREF_METHOD.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_far_choice": twiz_far_choice,     
            "twiz_nonce": twiz_nonce
         }, function(data){ twizUnLockedAction(); }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
    $("a[id^=twiz_far_cancel]").click(function(){
        twiz_ListMenu_Cancel();
    });
    $("#twiz_far_matches").mouseenter(function(){
        $("#twiz_far_matches").stop().animate({"opacity":0},500,function(){$("#twiz_far_matches").html("");});
    });
    $("input[name=twiz_far_find]").click(function(){
        if(twiz_ajax_locked == false){  
        twiz_ajax_locked = true;
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(14,twiz_textid.length);
        var twiz_far_choice = $("input[name=twiz_far_choice]:checked").val();
        $("input[name=twiz_far_find]").css({"color" : "#9FD0D5 "});
        $("#twiz_far_save_img_box_" + twiz_charid).show();
        $("#twiz_far_save_img_box_" + twiz_charid).attr("class","twiz-save twiz-loading-gif-save");
        switch(twiz_far_choice){
        case "twiz_far_simple":
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_FAR_FIND.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_far_choice": twiz_far_choice,
            "twiz_group_id": $("#twiz_slc_group").val(),            
            "twiz_far_everywhere_1": $("#twiz_far_everywhere_1").val(),
            "twiz_far_everywhere_2": $("#twiz_far_everywhere_2").val(),         
            "twiz_nonce": twiz_nonce
         }, function(data){
                twizUnLockedAction();
                data = JSON.parse(data);
                $("#twiz_container").html(data.html);
                $("#twiz_save_img_box_" + twiz_charid).html("");
                $("#twiz_sub_container").hide();
                $("#twiz_container").toggle();
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                twiz_view_id = null;
                twiz_array_view_id = new Array();
                twizList_ReBind();
                $("[name=twiz_listmenu]").css("display", "block");
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
            }).fail(function(){ twizUnLockedAction(); });
            break;
        case "twiz_far_precise":
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_FAR_FIND.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_far_choice": twiz_far_choice,
            "twiz_group_id": $("#twiz_slc_group").val(),
            "twiz_'.parent::F_STATUS.'_far_1": $("#twiz_'.parent::F_STATUS.'_far_1").is(":checked"),        
            "twiz_'.parent::F_ON_EVENT.'_far_1": $("#twiz_'.parent::F_ON_EVENT.'_far_1").val(),            
            "twiz_'.parent::F_TYPE.'_far_1": $("#twiz_'.parent::F_TYPE.'_far_1").val(),
            "twiz_'.parent::F_LAYER_ID.'_far_1": $("#twiz_'.parent::F_LAYER_ID.'_far_1").val(),
            "twiz_'.parent::F_START_DELAY.'_far_1": $("#twiz_'.parent::F_START_DELAY.'_far_1").val(),
            "twiz_'.parent::F_DURATION.'_far_1": $("#twiz_'.parent::F_DURATION.'_far_1").val(),
            "twiz_'.parent::F_DURATION_B.'_far_1": $("#twiz_'.parent::F_DURATION_B.'_far_1").val(),
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
            "twiz_nonce": twiz_nonce
         }, function(data){
                twizUnLockedAction();
                data = JSON.parse(data);
                $("#twiz_container").html(data.html);
                $("#twiz_save_img_box_" + twiz_charid).html("");
                $("#twiz_sub_container").hide();
                $("#twiz_container").toggle();
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                twiz_view_id = null;
                twiz_array_view_id = new Array();
                twizList_ReBind();
                $("[name=twiz_listmenu]").css("display", "block");
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
            }).fail(function(){ twizUnLockedAction(); });
            break;
        }
    }else{twizLockedAction();}});
    $("input[name=twiz_far_replace]").click(function(){  
        if(twiz_ajax_locked == false){  
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(17,twiz_textid.length);
        var twiz_far_choice = $("input[name=twiz_far_choice]:checked").val();
        if (confirm("'.__('Are you sure to replace?', 'the-welcomizer').'")){     
        twiz_ajax_locked = true;
        $("input[name=twiz_far_replace]").css({"color" : "#9FD0D5 "});
        $("#twiz_far_save_img_box_" + twiz_charid).show();
        $("#twiz_far_save_img_box_" + twiz_charid).attr("class","twiz-save twiz-loading-gif-save");
        switch(twiz_far_choice){
        case "twiz_far_simple":
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_FAR_REPLACE.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_far_choice": twiz_far_choice,
            "twiz_group_id": $("#twiz_slc_group").val(),            
            "twiz_far_everywhere_1": $("#twiz_far_everywhere_1").val(),
            "twiz_far_everywhere_2": $("#twiz_far_everywhere_2").val(),         
            "twiz_nonce": twiz_nonce
         }, function(data){ 
                twizUnLockedAction();
                $("#twiz_save_img_box_" + twiz_charid).html("");
                $("#twiz_sub_container").hide();
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                twizPostMenu(twiz_current_section_id,"","block");
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
            }).fail(function(){ twizUnLockedAction(); });
            break;
        case "twiz_far_precise":
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_FAR_REPLACE.'",
            "twiz_section_id": twiz_current_section_id,
            "twiz_far_choice": twiz_far_choice,
            "twiz_group_id": $("#twiz_slc_group").val(),            
            "twiz_'.parent::F_STATUS.'_far_1": $("#twiz_'.parent::F_STATUS.'_far_1").is(":checked"),        
            "twiz_'.parent::F_STATUS.'_far_2": $("#twiz_'.parent::F_STATUS.'_far_2").is(":checked"),        
            "twiz_'.parent::F_ON_EVENT.'_far_1": $("#twiz_'.parent::F_ON_EVENT.'_far_1").val(),
            "twiz_'.parent::F_ON_EVENT.'_far_2": $("#twiz_'.parent::F_ON_EVENT.'_far_2").val(),
            "twiz_'.parent::F_TYPE.'_far_1": $("#twiz_'.parent::F_TYPE.'_far_1").val(),
            "twiz_'.parent::F_TYPE.'_far_2": $("#twiz_'.parent::F_TYPE.'_far_2").val(),            
            "twiz_'.parent::F_LAYER_ID.'_far_1": $("#twiz_'.parent::F_LAYER_ID.'_far_1").val(),
            "twiz_'.parent::F_LAYER_ID.'_far_2": $("#twiz_'.parent::F_LAYER_ID.'_far_2").val(),
            "twiz_'.parent::F_START_DELAY.'_far_1": $("#twiz_'.parent::F_START_DELAY.'_far_1").val(),
            "twiz_'.parent::F_START_DELAY.'_far_2": $("#twiz_'.parent::F_START_DELAY.'_far_2").val(),       
            "twiz_'.parent::F_DURATION.'_far_1": $("#twiz_'.parent::F_DURATION.'_far_1").val(),
            "twiz_'.parent::F_DURATION.'_far_2": $("#twiz_'.parent::F_DURATION.'_far_2").val(),            
            "twiz_'.parent::F_DURATION_B.'_far_1": $("#twiz_'.parent::F_DURATION_B.'_far_1").val(),
            "twiz_'.parent::F_DURATION_B.'_far_2": $("#twiz_'.parent::F_DURATION_B.'_far_2").val(),
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
            "twiz_nonce": twiz_nonce
         }, function(data){
                twizUnLockedAction();
                $("#twiz_sub_container").hide();
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
                twizPostMenu(twiz_current_section_id,"","block");
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
            }).fail(function(){ twizUnLockedAction(); });
            break;
        }}
    }else{twizLockedAction();}});
    $(".twiz-toggle-far").click(function(){
    if(twiz_ajax_locked == false){  
        twiz_ajax_locked = true;
        var twiz_toggle_status = 0;
        var twiz_textid = $(this).attr("name");
        var twiz_charid = twiz_textid.substring(13,twiz_textid.length);
        var twiz_src = $("#twiz_far_img_" + twiz_charid).attr("class");
        if(twiz_src.indexOf("twiz-plus") != -1){
            $("#twiz_far_img_" + twiz_charid).removeClass("twiz-plus").addClass("twiz-minus");
            $("#twiz_far_e_a_" + twiz_charid).attr("class","twiz-toggle-far twiz-bold");
            $("." + twiz_charid).removeClass("twiz-display-none");
            twiz_toggle_status = 1;
        }else{
            $("#twiz_far_img_" + twiz_charid).removeClass("twiz-minus").addClass("twiz-plus");
            $("#twiz_far_e_a_" + twiz_charid).attr("class","twiz-toggle-far");
            $("." + twiz_charid).addClass("twiz-display-none");
        }
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_TOGGLE.'",
        "twiz_toggle_status": twiz_toggle_status,
        "twiz_toggle_type": "'.parent::KEY_TOGGLE_FAR.'",
        "twiz_charid": twiz_charid
        }, function(data){ twizUnLockedAction(); }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
 } 
 var bind_twiz_Status = function(){ 
    $("[id^=twiz_status_img]").click(function(){
        if(twiz_ajax_locked == false){  
        twiz_ajax_locked = true;
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(16,twiz_textid.length);
        var twiz_action = "'.parent::ACTION_STATUS.'";
        if(twiz_library_active == true){
            twiz_action = "'.parent::ACTION_LIBRARY_STATUS.'";
        }
        var twiz_menuid = twiz_numid.substring(0,5);
        switch(twiz_menuid){
            case "vmenu":
            $(this).attr("class","twiz-save-dark twiz-status");
            $("#twiz_status_img_" + twiz_numid.replace("vmenu_", "menu_")).attr("class","twiz-save-dark twiz-status");
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": twiz_nonce, 
            "twiz_action": "'.parent::ACTION_VMENU_STATUS.'",
            "twiz_id": twiz_numid
            }, function(data){
                twizUnLockedAction();
                $("[id^=twiz_status_img]").unbind("click");
                $("#twiz_status_" + twiz_numid).html(data);
                $("#twiz_status_" + twiz_numid.replace("vmenu_", "menu_")).html(data.replace(/vmenu_/g, "menu_"));
                bind_twiz_Status();
            }).fail(function(){ twizUnLockedAction(); });
            break;
        case "menu_":
            $(this).attr("class","twiz-save-dark twiz-status");
            $("#twiz_status_img_" + twiz_numid.replace("menu_", "vmenu_")).attr("class","twiz-save-dark twiz-status");
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": twiz_nonce, 
            "twiz_action": "'.parent::ACTION_MENU_STATUS.'",
            "twiz_id": twiz_numid
            }, function(data){
                twizUnLockedAction();
                $("[id^=twiz_status_img]").unbind("click");
                $("#twiz_status_" + twiz_numid).html(data);
                $("#twiz_status_" + twiz_numid.replace("menu_", "vmenu_")).html(data.replace(/menu_/g, "vmenu_"));
                bind_twiz_Status();
            }).fail(function(){ twizUnLockedAction(); });
            break;
        default:
             switch(twiz_numid){             
                case "global":
                    $(this).attr("class","twiz-save-dark twiz-status");
                    twiz_ajax_locked = true;
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": twiz_nonce, 
                    "twiz_action": "'.parent::ACTION_GLOBAL_STATUS.'"
                    }, function(data){
                        twizUnLockedAction();
                        $("[id^=twiz_status_img]").unbind("click");
                        $("#twiz_global_status").html(data);
                        bind_twiz_Status();
                    }).fail(function(){ twizUnLockedAction(); });
                    break;
                case "hscroll":
                    $(this).attr("class","twiz-save-dark twiz-status");
                    twiz_ajax_locked = true;
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": twiz_nonce, 
                    "twiz_action": "'.parent::ACTION_HSCROLL_STATUS.'"
                    }, function(data){
                        data = JSON.parse(data);
                        twizUnLockedAction();
                        $("[id^=twiz_status_img]").unbind("click");
                        $("#twiz_hscroll_status").html(data.html);
                        twiz_hscroll_status = data.status;
                        bind_twiz_Status();
                    }).fail(function(){ twizUnLockedAction(); });
                    break;                    
                default:
                    $(this).attr("class","twiz-save-bigger twiz-status");
                    twiz_ajax_locked = true;
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": twiz_nonce, 
                    "twiz_action": twiz_action,
                    "twiz_id": twiz_numid
                    }, function(data){ 
                        data = JSON.parse(data);
                        twizUnLockedAction();
                        $("[id^=twiz_status_img]").unbind("click");
                        $("#twiz_td_status_" + twiz_numid).html(data.html);
                        if(twiz_array_view_id[twiz_numid + "_1"] == undefined){}else{
                            twiz_array_view_id[twiz_numid + "_1"] = twiz_array_view_id[twiz_numid + "_1"].replace(data.searchclass, data.newclassname);
                            $("#twiz_view_box").html(\'<div\' + \' id="twiz_right_panel_1" class="twiz-right-panel twiz-corner-all">\' + twiz_array_view_id[twiz_numid + "_1"] +\'</div>\');
                        }
                        bind_twiz_Status();
                    }).fail(function(){ twizUnLockedAction(); });
                    break;
                }
        } 
    }else{twizLockedAction();}});
 }
 function twiz_Action_Edit_Copy_Rebind(){
    bind_twiz_Status();bind_twiz_Save();bind_twiz_Cancel();bind_twiz_Number_Restriction();
    bind_twiz_Choose_Options();
    twizScrollTop();
    $("#twiz_layer_id").focus();
 }
 var bind_twiz_Edit = function(){  
        var twiz_c = {};
        $(".twiz-list-tr").draggable({
            delay: 150,
            containment: "#twiz_container",
            axis: "y",
            opacity: 0.9,
            revert: true,
            helper: "clone",
            distance: 10, 
            start: function(event, ui){
                var twiz_textid = $(this).attr("id");
                var twiz_numid = twiz_textid.substring(13, twiz_textid.length);
                twiz_c.numid = twiz_numid;
                twiz_c.tr = this;
                twiz_c.helper = ui.helper;
                twiz_view_id = "edit";
            }
        });
        var twiz_orig = "";
        $(".twiz-list-group-tr").droppable({
            tolerance: "pointer",
            over: function(){
                 twiz_orig = $(this).attr("class");
                 $(this).attr({"class":"twiz-row-color-3"});
                 twiz_view_id = "edit";
            },
            out: function(){
                 $(this).attr({"class":twiz_orig});
            },
            drop: function(){
                if(twiz_ajax_locked == false){  
                    twiz_ajax_locked = true;
                    twizShowMainLoadingImage();
                    var twiz_textid = $(this).attr("id");
                    var twiz_numid = twiz_textid.substring(19, twiz_textid.length);  
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": twiz_nonce, 
                    "twiz_action": "'.parent::ACTION_DROP_ROW.'",
                    "twiz_from_id": twiz_c.numid,
                    "twiz_to_id": twiz_numid,
                    "twiz_section_id": twiz_current_section_id
                    }, function(data){
                        twizUnLockedAction();
                        twiz_view_id = null;
                        $("#twiz_container").html(data);
                        twizList_ReBind();
                        twizHideMainLoadingImage();
                    }).fail(function(){ twizUnLockedAction(); twiz_view_id = null;});
                    $(this).attr({"class":twiz_orig});
                    $(twiz_c.tr).remove();
                    $(twiz_c.helper).remove();
                }else{twizLockedAction();}
            }});
        $(".twiz-list-tr").droppable({
            over: function(){twiz_view_id = "edit";},
            tolerance: "pointer",        
            drop: function(){       
                if(twiz_ajax_locked == false){  
                    twiz_ajax_locked = true;
                    twizShowMainLoadingImage();
                    var twiz_parentid = $(this).attr("parentid");
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": twiz_nonce, 
                    "twiz_action": "'.parent::ACTION_DROP_ROW.'",
                    "twiz_from_id": twiz_c.numid,
                    "twiz_to_id": twiz_parentid,
                    "twiz_section_id": twiz_current_section_id
                    }, function(data){
                        twizUnLockedAction();
                        twiz_view_id = null;
                        $("#twiz_container").html(data);
                        twizList_ReBind();
                        twizHideMainLoadingImage();
                    }).fail(function(){ twizUnLockedAction(); twiz_view_id = null;});
                    $(twiz_c.tr).remove();
                    $(twiz_c.helper).remove();
                }else{twizLockedAction();}
            }});        
            $(".twiz-table-list-tr-h").droppable({
            over: function(){twiz_view_id = "edit";},
            tolerance: "pointer",
            drop: function(){       
                if(twiz_ajax_locked == false){  
                    twiz_ajax_locked = true;
                    twizShowMainLoadingImage();
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": twiz_nonce, 
                    "twiz_action": "'.parent::ACTION_DROP_ROW.'",
                    "twiz_from_id": twiz_c.numid,
                    "twiz_to_id": "",
                    "twiz_section_id": twiz_current_section_id
                    }, function(data){
                        twizUnLockedAction();
                        twiz_view_id = null;
                        $("#twiz_container").html(data);
                        twizList_ReBind();
                        twizHideMainLoadingImage();
                    }).fail(function(){ twizUnLockedAction(); });
                    $(twiz_c.tr).remove();
                    $(twiz_c.helper).remove();
                }else{twizLockedAction();twiz_view_id = null;}
            }});
     $(".twiz-toggle-group").click(function(){
     if(twiz_ajax_locked == false){  
        twizShowMainLoadingImage();
        twiz_ajax_locked = true;
        var twiz_toggle_status = 0;
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(15,twiz_textid.length);
        var twiz_src = $("[id^=twiz_group_img_" + twiz_charid + "]").attr("class");
        if(twiz_src.indexOf("twiz-plus") != -1){
            $("[id^=twiz_group_img_" + twiz_charid + "]").removeClass("twiz-plus").addClass("twiz-minus");
            $("[id^=twiz_element_a_" + twiz_parent_id + "]").attr("class","twiz-toggle-group");
            $("[id^=twiz_element_a_" + twiz_charid + "]").attr("class","twiz-toggle-group twiz-bold");
            $("." + twiz_charid).removeClass("twiz-display-none");
            twiz_toggle_status = 1;
            twiz_parent_id = twiz_charid;
        }else{
            $("[id^=twiz_group_img_" + twiz_charid + "]").removeClass("twiz-minus").addClass("twiz-plus");
            $("[id^=twiz_element_a_" + twiz_charid + "]").attr("class","twiz-toggle-group");
            if(twiz_charid==twiz_parent_id){
            $("[id^=twiz_element_a_" + twiz_parent_id + "]").attr("class","twiz-toggle-group");
            twiz_parent_id = "";
            }
            $("." + twiz_charid).addClass("twiz-display-none");
        }
        twiz_array_view_id = new Array();
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_TOGGLE.'",
        "twiz_toggle_status": twiz_toggle_status,
        "twiz_toggle_type": "'.parent::KEY_TOGGLE_GROUP.'",
        "twiz_charid": twiz_charid
        }, function(data){ twizUnLockedAction(); twizHideMainLoadingImage(); }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
    $(".twiz-group-edit").click(function(){
    if(twiz_ajax_locked == false){ 
        twizCleanImportMenu();
        twiz_ajax_locked = true;
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(16,twiz_textid.length);
        twiz_view_id = "edit";
        $(this).attr("class","twiz-save-bigger twiz-loading-gif-action");
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_GET_GROUP.'",
        "twiz_sub_action": "'.parent::ACTION_EDIT.'",
        "twiz_group_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data){
            twizUnLockedAction(); 
            twiz_current_group_id = twiz_numid;
            $("#twiz_sub_container").html(data);
            $("#twiz_sub_container").show();
            $("#" + twiz_textid).show();
            $("#twiz_container").css("display", "none");
            twiz_ListMenu_Unbind();
            bind_twiz_ListMenu();
            $("#twiz_group_name").focus();
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
    $(".twiz-edit").click(function(){
    if(twiz_ajax_locked == false){  
        twiz_ajax_locked = true;
        var twiz_textid = $(this).attr("id");
        var twiz_textidtemp = twiz_textid.substring(10,twiz_textid.length);
        var twiz_numid = "";
        if((twiz_textidtemp.substring(0,1) == "a") || (twiz_textidtemp.substring(0,1) == "v")){
            twiz_numid = twiz_textid.substring(12,twiz_textid.length);
            $(this).parent().html(\'<div\' + \' class="twiz-loading-bar"></div>\');
        }else{
            twiz_numid = twiz_textidtemp;
            $(this).attr("class","twiz-save-bigger twiz-loading-gif-action");
        }
        twiz_view_id = "edit";
        twizSwitchFooterMenu();
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_EDIT.'",
        "twiz_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data){
            twizUnLockedAction();
            $("#twiz_container").html(data);
            twiz_Action_Edit_Copy_Rebind();
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
 }
 var bind_twiz_Copy = function(){
    $(".twiz-copy").click(function(){
    if(twiz_ajax_locked == false){  
        twiz_ajax_locked = true;
        var twiz_textid = $(this).attr("id");
        var twiz_textidtemp = twiz_textid.substring(10,twiz_textid.length);
        var twiz_numid = "";
        if((twiz_textidtemp.substring(0,1) == "a") || (twiz_textidtemp.substring(0,1) == "v")){
            twiz_numid = twiz_textid.substring(12,twiz_textid.length);
            $(this).parent().html(\'<div\' + \' class="twiz-loading-bar"></div>\');
        }else{
            twiz_numid = twiz_textidtemp;
            $(this).attr("class","twiz-save-bigger twiz-loading-gif-action");
        }        
        twiz_view_id = "edit";
        twizSwitchFooterMenu();
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_COPY.'",
        "twiz_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data){
            twizUnLockedAction();
            $("#twiz_container").html(data);
            twiz_Action_Edit_Copy_Rebind();
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
    $(".twiz-group-copy").click(function(){
    if(twiz_ajax_locked == false){ 
        twizCleanImportMenu();
        twiz_ajax_locked = true;
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(16,twiz_textid.length);
        var twiz_img = $(this);
        twiz_view_id = "edit";
        twiz_img.attr("class","twiz-save-bigger twiz-loading-gif-action");
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_GET_GROUP.'",
        "twiz_sub_action": "'.parent::ACTION_COPY.'",
        "twiz_group_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data){
            twizUnLockedAction(); 
            twiz_current_group_id = twiz_numid;
            $("#twiz_sub_container").html(data);
            $("#twiz_sub_container").show();
            $("#" + twiz_textid).show();
            twiz_img.attr("class","twiz-group-copy twiz-copy-img");
            $("#twiz_container").css("display", "none");
            twiz_ListMenu_Unbind();
            bind_twiz_ListMenu();
            $("#twiz_group_name").focus();
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
 } 
 var bind_twiz_Delete = function(){
      $("#twiz_empty_list").click(function(){  
        if(twiz_ajax_locked == false){  
        if (confirm("'.__('Are you sure you want to empty the list?', 'the-welcomizer').'")){
            twizShowMainLoadingImage();
            twiz_ajax_locked = true;
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": twiz_nonce, 
            "twiz_action": "'.parent::ACTION_EMPTY_SECTION.'",
            "twiz_section_id": twiz_current_section_id
            }, function(data){                
                twizUnLockedAction(); 
                twizCleanImportMenu();
                twizHideMainLoadingImage();
                twizPostMenu(data,"","block");
            }).fail(function(){ twizUnLockedAction(); });
        }
    }else{twizLockedAction();}});
    $(".twiz-delete").click(function(){
    if(twiz_ajax_locked == false){  
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")){
            twiz_ajax_locked = true;
            var twiz_textid = $(this).attr("id");
            var twiz_textidtemp = twiz_textid.substring(12,twiz_textid.length);
            var twiz_numid = "";
            var twiz_action = "'.parent::ACTION_DELETE.'";
            if(twiz_library_active == true){
                twiz_action = "'.parent::ACTION_DELETE_LIBRARY.'";
            }        
            if((twiz_textidtemp.substring(0,1) == "a")|| (twiz_textidtemp.substring(0,1) == "v")){
                twiz_numid = twiz_textid.substring(14,twiz_textid.length);
                $(this).parent().html(\'<div\' + \' class="twiz-loading-bar"></div>\');
            }else{
                twiz_numid = twiz_textidtemp;
                $(this).attr("class","twiz-save-bigger twiz-loading-gif-action");
            }             
            $(".twiz-right-panel").fadeOut("fast");
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": twiz_nonce, 
            "twiz_action": twiz_action,
            "twiz_id": twiz_numid,
            "twiz_section_id": twiz_current_section_id,
            "twiz_parent_id": twiz_parent_id
            }, function(data){
                twizUnLockedAction();
                $("#twiz_container").html(data);
                if(twiz_library_active == true){
                    twizLibrary_Bind();
                }else{     
                    twiz_array_view_id = new Array();
                    twizList_ReBind();
                }
            }).fail(function(){ twizUnLockedAction(); });
        }
    }else{twizLockedAction();}});
    $(".twiz-group-delete").click(function(){
    if(twiz_ajax_locked == false){  
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")){
            twiz_ajax_locked = true;
            var twiz_textid = $(this).attr("id");
            var twiz_numid = twiz_textid.substring(18,twiz_textid.length);
            var twiz_action = "'.parent::ACTION_DELETE_GROUP.'";
            $(this).attr("class","twiz-save-bigger twiz-loading-gif-action");
            $(".twiz-right-panel").fadeOut("fast");
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": twiz_nonce, 
            "twiz_action": twiz_action,
            "twiz_group_id": twiz_numid,
            "twiz_section_id": twiz_current_section_id,
            "twiz_parent_id": twiz_parent_id
            }, function(data){     
                twizUnLockedAction();
                $("#twiz_container").html(data);
                twiz_array_view_id = new Array();
                twizList_ReBind();
            }).fail(function(){ twizUnLockedAction(); });
        }
    }else{twizLockedAction();}});
 }
 var bind_twiz_Cancel = function(){
    $("a[id^=twiz_cancel]").click(function(){
        if(twiz_ajax_locked == false){
        restoreTwizCurrentSectionId();
        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
        $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
        $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});
        $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
        $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
        $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
        $("#qq_upload_list li").remove();
        $("#twiz_export_url").html("");
        twizPostMenu(twiz_current_section_id,"","block");
        }else{twizLockedAction();}
    });
 }
 var bind_twiz_Save = function(){
    $(".twiz-add-element").click(function(){
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
    $("#twiz_on_event").change(function(){
    if(($(this).val() != "")&&($(this).val() != "Manually")){
         $("#twiz_div_lock_event").show();
         $("#twiz_div_no_event").hide();
    }else{
         $("#twiz_div_lock_event").hide();
         $("#twiz_div_no_event").show();
    }
    });
    $("#twiz_lock_event").change(function(){
    if($(this).is(":checked")){
         $("#twiz_lock_event_type").show();
    }else{
         $("#twiz_lock_event_type").hide();
    }
    });
    $(".twiz-js-features a").click(function(){
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
    $("input[name=twiz_save]").click(function(){
    if(twiz_ajax_locked == false){ 
    var twiz_valid_element = true;
    if(($("#twiz_layer_id").val() == "'.__('Please type a main element.', 'the-welcomizer').'")
    || ($("#twiz_layer_id").val() == "")){
        $("#twiz_layer_id").val("'.__('Please type a main element.', 'the-welcomizer').'");
        $("#twiz_layer_id").css("color", "#BC0B0B");
        twiz_valid_element = false;
    }
    if(twiz_valid_element == true){
    twiz_ajax_locked = true;
    var twiz_textid = $(this).attr("id");
    var twiz_charid = twiz_textid.substring(10,twiz_textid.length);
    var twiz_stay = $("#twiz_stay").is(":checked");
    $("input[name=twiz_save]").css({"color" : "#9FD0D5 "});
    $("#twiz_save_img_box_" + twiz_charid).show();
    $("#twiz_save_img_box_" + twiz_charid).attr("class","twiz-save twiz-loading-gif-save");
    var twiz_numid = $("#twiz_id").val();
    $.post(ajaxurl, {
         "action": "twiz_ajax_callback",
         "twiz_nonce": twiz_nonce, 
         "twiz_action": "'.parent::ACTION_SAVE.'",
         "twiz_stay": twiz_stay, ';
         
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
                
                case parent::F_BLOG_ID: // nothing to do, Twiz::getSectionBlogId(); on most saving actions.
                 
                    $header.= '';
                    
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
         
        $header.= '}, function(data){
        twizUnLockedAction();
        data = JSON.parse(data);
        $("img[name^=twiz_status_img]").unbind("click");
        $("a[id^=twiz_cancel]").unbind("click");
        $("input[name=twiz_save]").unbind("click");
        $("#twiz_on_event").unbind("change");
        $("[class^=twiz-slc-js-features]").unbind("change");
        $(".twiz-js-features a").unbind("click");
        $("#twiz_container").html(data.html);
        twiz_view_id = null;
        if(data.result > 0){
            twiz_array_view_id = new Array();
        }else{ 
            twiz_unset_array_view_id(twiz_numid);
        }
        bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
        bind_twiz_Choose_Options();
        bind_twiz_Ajax_TD();bind_twiz_TR_View();bind_twiz_Order_by();
        if(twiz_stay ==  true){
            $("#twiz_save_img_box_" + twiz_charid).attr("class","twiz-success twiz-loading-gif-save");
            $("#twiz_save_img_box_" + twiz_charid).fadeOut("slow");
            if(twiz_charid==2){
                $("#twiz_stay").focus();
            }
        }else{
            $("[name=twiz_listmenu]").css("display", "block");
            $("#twiz_import").fadeIn("fast");        
        }
        $("input[name=twiz_save]").css({"color" : "#ffffff"});
    }).fail(function(){ twizUnLockedAction(); });
   }}else{twizLockedAction();}});
  }
  function twiz_unset_array_view_id(twiz_numid){
      var twiz_for_numid = "";
      var twiz_for_view_level = "";
      for (twiz_array_view_key in twiz_array_view_id){
          twiz_array_view_key = twiz_array_view_key.split("_");
          twiz_for_numid = twiz_array_view_key[0];
          twiz_for_view_level = twiz_array_view_key[1];
          if (twiz_numid == twiz_for_numid){
              twiz_array_view_id[twiz_numid + "_" + twiz_for_view_level] = undefined;
          }
      }
  }
  var bind_twiz_AdminSave = function(){
     $("#twiz_register_jquery_transition").click(function(){
        $("#twiz_register_jquery_animatecssrotatescale").prop("checked",false);
        $("#twiz_register_jquery_transform").prop("checked",false);
     });  
     $("#twiz_register_jquery_animatecssrotatescale").click(function(){
        $("#twiz_register_jquery_transition").prop("checked",false);
        $("#twiz_register_jquery_transform").prop("checked",false);
     });   
     $("#twiz_register_jquery_transform").click(function(){
        $("#twiz_register_jquery_transition").prop("checked",false);
        $("#twiz_register_jquery_animatecssrotatescale").prop("checked",false);
     });     
     $(".twiz-promote-plugin").click(function(){
        if( $("#twiz_'.parent::KEY_PROMOTE_PLUGIN.'").is(":checked") )
        {
            $("#twiz_label_promote_plugin").hide();
            $("#twiz_div_promote_position").fadeIn("fast");
        
        }else{
            $("#twiz_div_promote_position").hide();       
            $("#twiz_label_promote_plugin").fadeIn("fast"); 
        }
     });
     $(".twiz-toggle-admin").click(function(){
        if(twiz_ajax_locked == false){  
        twiz_ajax_locked = true;
        var twiz_toggle_status = 0;
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(15,twiz_textid.length);
        var twiz_src = $("#twiz_admin_img_" + twiz_charid).attr("class");
        if(twiz_textid == "twiz_admin_m_u_twizadmin5"){
            $("html, body").animate({ scrollTop:  $("#twiz_admin_e_a_" + twiz_charid).offset().top}, 0, function(){});
            $("#twiz_admin_img_" + twiz_charid).removeClass("twiz-plus").addClass("twiz-minus");
            $("#twiz_admin_e_a_" + twiz_charid).attr("class","twiz-toggle-admin twiz-bold");
            $("." + twiz_charid).removeClass("twiz-display-none");
            twiz_toggle_status = 1;
        }else{
            if(twiz_src.indexOf("twiz-plus") != -1){
                $("#twiz_admin_img_" + twiz_charid).removeClass("twiz-plus").addClass("twiz-minus");
                $("#twiz_admin_e_a_" + twiz_charid).attr("class","twiz-toggle-admin twiz-bold");
                $("." + twiz_charid).removeClass("twiz-display-none");
                twiz_toggle_status = 1;
            }else{    
                $("#twiz_admin_img_" + twiz_charid).removeClass("twiz-minus").addClass("twiz-plus");
                $("#twiz_admin_e_a_" + twiz_charid).attr("class","twiz-toggle-admin");
                $("." + twiz_charid).addClass("twiz-display-none");
            }
        }
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_TOGGLE.'",
        "twiz_toggle_status": twiz_toggle_status,
        "twiz_toggle_type": "'.parent::KEY_TOGGLE_ADMIN.'",
        "twiz_charid": twiz_charid
        }, function(data){ twizUnLockedAction(); }).fail(function(){ twizUnLockedAction(); });
       }else{twizLockedAction();} 
    });
   
';
    // restore admin
    if( is_multisite() ){
    
    // Display or not the update or restore radio button options
    $header.= '
    $("#twiz_override_network_settings").click(function(){ 
    if((twiz_override_network_settings == "1") && ($("#twiz_override_network_settings").is(":checked") == false)){
        $("#twiz_restore_network_options").fadeIn("fast");
        $("#twiz_span_update").hide();           
        $("#twiz_span_restore").show();   
        $("#twiz_update").prop( "checked", false );
        $("#twiz_restore").prop( "checked", true );
    }else if((twiz_override_network_settings == "1") && ($("#twiz_override_network_settings").is(":checked") == true)){
        $("#twiz_restore_network_options").fadeOut("fast");
        $("#twiz_span_update").hide();           
        $("#twiz_span_restore").hide();   
        $("#twiz_update").prop( "checked", true );
        $("#twiz_restore").prop( "checked", false );
    }else if ((twiz_override_network_settings == "0") && ($("#twiz_override_network_settings").is(":checked") == false)){
        $("#twiz_restore_network_options").fadeOut("fast");
        $("#twiz_span_update").hide();         
        $("#twiz_span_restore").hide(); 
        $("#twiz_update").prop( "checked", true );
        $("#twiz_restore").prop( "checked", false );
    }else if((twiz_override_network_settings == "0") && ($("#twiz_override_network_settings").is(":checked") == true)){
        $("#twiz_restore_network_options").fadeIn("fast");
        $("#twiz_span_update").show();              
        $("#twiz_span_restore").hide();        
        $("#twiz_update").prop( "checked", true );
        $("#twiz_restore").prop( "checked", false );
    }
    });';
    }
    $header.= '
    $("[name=twiz_admin_save]").click(function(){
    if(twiz_ajax_locked == false){  
    twiz_ajax_locked = true;
    var twiz_textid = $(this).attr("id");
    var twiz_charid = twiz_textid.substring(16,twiz_textid.length);
    $("input[name=twiz_admin_save]").css({"color" : "#9FD0D5 "});
    $("#twiz_admin_save_img_box_" + twiz_charid).show();
    $("#twiz_admin_save_img_box_" + twiz_charid).attr("class","twiz-save twiz-loading-gif-save");
    var twiz_numid = $("#twiz_id").val();';
    
    // restore admin
    if( is_multisite() ){
    
        $header.=  '
    var twiz_restore_settings = $("input[name=twiz_restore_settings]:checked").val();';
    
    }
    
    $header.=  '
    $.post(ajaxurl, {
         "action": "twiz_ajax_callback",
         "twiz_nonce": twiz_nonce, 
         "twiz_action": "'.parent::ACTION_SAVE_ADMIN.'",
         "twiz_'.parent::KEY_REGISTER_JQUERY.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY.'").is(":checked"),
         "twiz_'.parent::KEY_REGISTER_JQUERY_TRANSIT.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY_TRANSIT.'").is(":checked"),
         "twiz_'.parent::KEY_REGISTER_JQUERY_TRANSFORM.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY_TRANSFORM.'").is(":checked"),
         "twiz_'.parent::KEY_REGISTER_JQUERY_ROTATE3DI.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY_ROTATE3DI.'").is(":checked"),
         "twiz_'.parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE.'").is(":checked"),
         "twiz_'.parent::KEY_REGISTER_JQUERY_EASING.'": $("#twiz_'.parent::KEY_REGISTER_JQUERY_EASING.'").is(":checked"),
         "twiz_'.parent::KEY_OUTPUT_COMPRESSION.'": $("#twiz_'.parent::KEY_OUTPUT_COMPRESSION.'").is(":checked"),
         "twiz_'.parent::KEY_OUTPUT.'": $("#twiz_'.parent::KEY_OUTPUT.'").val(),
         "twiz_'.parent::KEY_THE_CONTENT_FILTER.'": $("#twiz_'.parent::KEY_THE_CONTENT_FILTER.'").is(":checked"),
         "twiz_'.parent::KEY_OUTPUT_PROTECTED.'": $("#twiz_'.parent::KEY_OUTPUT_PROTECTED.'").is(":checked"),
         "twiz_'.parent::KEY_EXTRA_EASING.'": $("#twiz_'.parent::KEY_EXTRA_EASING.'").is(":checked"),
         "twiz_'.parent::KEY_NUMBER_POSTS.'": $("#twiz_'.parent::KEY_NUMBER_POSTS.'").val(),
         "twiz_'.parent::KEY_SORT_LIB_DIR.'": $("#twiz_'.parent::KEY_SORT_LIB_DIR.'").val(),
         "twiz_'.parent::KEY_STARTING_POSITION.'": $("#twiz_'.parent::KEY_STARTING_POSITION.'").val(),
         "twiz_'.parent::KEY_POSITIONING_METHOD.'": $("#twiz_'.parent::KEY_POSITIONING_METHOD.'").val(),
         "twiz_'.parent::KEY_MIN_ROLE_LEVEL.'": $("#twiz_'.parent::KEY_MIN_ROLE_LEVEL.'").val(),
         "twiz_'.parent::KEY_MIN_ROLE_ADMIN.'": $("#twiz_'.parent::KEY_MIN_ROLE_ADMIN.'").val(),
         "twiz_'.parent::KEY_MIN_ROLE_LIBRARY.'": $("#twiz_'.parent::KEY_MIN_ROLE_LIBRARY.'").val(),
         "twiz_'.parent::KEY_PROMOTE_PLUGIN.'": $("#twiz_'.parent::KEY_PROMOTE_PLUGIN.'").is(":checked"),
         "twiz_'.parent::KEY_PROMOTE_POSITION.'": $("#twiz_'.parent::KEY_PROMOTE_POSITION.'").val(),
         "twiz_'.parent::KEY_DISPLAY_VAR_DUMP.'": $("#twiz_'.parent::KEY_DISPLAY_VAR_DUMP.'").is(":checked"),
         "twiz_'.parent::KEY_FB_LIKE.'": $("#twiz_'.parent::KEY_FB_LIKE.'").is(":checked"),
         "twiz_'.parent::KEY_DELETE_ALL.'": $("#twiz_'.parent::KEY_DELETE_ALL.'").is(":checked"),';
        // Put next admin option here..
      
         // restore admin
         if( is_multisite() ){ 
         
         $header.=  '
         "twiz_override_network_settings": $("#twiz_override_network_settings").is(":checked"),
         "twiz_restore_settings": twiz_restore_settings,';
         }
         
        $header.= '
        "twiz_'.parent::KEY_REMOVE_CREATED_DIRECTORIES.'": $("#twiz_'.parent::KEY_REMOVE_CREATED_DIRECTORIES.'").is(":checked")
    }, function(data){
        twizUnLockedAction();
        data = JSON.parse(data);';
        
        // restore admin
        if( is_multisite() ){
        
        $header.='
        if(twiz_skin != data.skin){
            var twiz_css_skin = "'.$this->pluginUrl.'" + data.skin + "/twiz-style.css?version='.$this->cssVersion.'";
            $("#twiz-'.$this->cssVersion.'-a-css").attr("href",twiz_css_skin);
            twiz_skin = data.skin;
        }
        if(data.ImgHScrollStatus != ""){   
            $("#twiz_hscroll_status").html(data.ImgHScrollStatus); 
        }
        if(data.ImgGlobalstatus != ""){        
            $("#twiz_global_status").html(data.ImgGlobalstatus); 
        }        
        twiz_current_section_id = data.section_id;
        
        twiz_override_network_settings = data.override_network_settings;
        if(twiz_override_network_settings == "1"){
            $("#twiz_network_status").hide();
        }else{
            $("#twiz_network_status").show();
        }
        ';
        }
        
        $header.='
        if(data.'.parent::KEY_DISPLAY_VAR_DUMP.' == "1"){
            twizGetVardump(false);
        }else if(data.'.parent::KEY_DISPLAY_VAR_DUMP.' == "0"){
            $("#twiz_var_dump").css({"display":"none"});
            $("#twiz_var_dump").html("");
        }
        if(data.debug != ""){
            alert(data.debug);
        }
        if(data.relike == "relike"){
            $("#twiz_like").html(data.htmllike);
        }else if(data.relike == "remove"){
            $("#twiz_like").html("");
        }        
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_ADMIN.'"
        }, function(data){                
            twizUnLockedAction();
            $("#twiz_container").html(data);
            bind_twiz_AdminSave();
            bind_twiz_Cancel();';
            
         // restore admin
         if( is_multisite() ){
         
            $header.='
            twizGetMenu("restore");';
         }
         
       $header.='
       $("#twiz_admin_save_img_box_" + twiz_charid).attr("class","twiz-success twiz-loading-gif-save");
            $("#twiz_admin_save_img_box_" + twiz_charid).fadeOut("slow");
        }).fail(function(){ twizUnLockedAction(); });
    }).fail(function(){ twizUnLockedAction(); });
   }else{twizLockedAction();}});
  }'; // end  bind_twiz_AdminSave
            
        
  $header.='
  var bind_twiz_Number_Restriction = function(){
    $("#twiz_start_top_pos").keyup(function (e){
    this.value = this.value.replace(/[^0-9]/g,"");});
    $("#twiz_start_left_pos").keyup(function (e){
    this.value = this.value.replace(/[^0-9]/g,"");});
    $("#twiz_zindex").keyup(function (e){
    this.value = this.value.replace(/[^0-9\-]/g,"");});
    $("#twiz_move_top_pos_a").keyup(function (e){
    this.value = this.value.replace(/[^0-9\-]/g,"");});
    $("#twiz_move_left_pos_a").keyup(function (e){
    this.value = this.value.replace(/[^0-9\-]/g,"");});
    $("#twiz_move_top_pos_b").keyup(function (e){
    this.value = this.value.replace(/[^0-9\-]/g,"");});
    $("#twiz_move_left_pos_b").keyup(function (e){
    this.value = this.value.replace(/[^0-9\-]/g,"");});
  }
  var bind_twiz_Choose_Options = function(){
    $("a[id^=twiz_choose_options]").click(function(){
        if(twiz_ajax_locked == false){  
        twiz_ajax_locked = true;
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(20,twiz_textid.length);
        $("#twiz_td_full_option_" + twiz_charid).html(\'<div\' + \' class="twiz-loading-bar"></div>\');
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_OPTIONS.'",
        "twiz_charid": twiz_charid
        }, function(data){
        twizUnLockedAction();
        $("#twiz_td_full_option_" + twiz_charid).html(data);
        bind_twiz_Select_Options(twiz_charid);
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
  }    
  var bind_twiz_Select_Options = function(twiz_charid){
    $("#twiz_slc_options_" + twiz_charid).change(function(){
        var twiz_curval = $("#twiz_options_" + twiz_charid).val();
        if($(this).val()!=""){
            if(twiz_curval!=""){ twiz_curval = twiz_curval + "\n";}
            var twiz_optionstring =  $(this).val();
            $("#twiz_options_" + twiz_charid).attr({"value" : twiz_curval + twiz_optionstring}) 
        }
    });
  }    
  var bind_twiz_Ajax_TD = function(){ 
    $("[name^=twiz_on_event]").change(function (){
        if(twiz_ajax_locked == false){  
        twiz_ajax_locked = true;
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(14,twiz_textid.length);
        var twiz_txtval = $("#twiz_on_event_" + twiz_numid).val();
        $("#twiz_ajax_td_edit_on_event_" + twiz_numid).hide();
        $("#twiz_ajax_td_loading_on_event_" + twiz_numid).html(\'<div\' + \' class="twiz-loading-bar"></div>\');
        $.post(ajaxurl, { 
                "action": "twiz_ajax_callback",
                "twiz_nonce": twiz_nonce, 
                "twiz_action": "'.parent::ACTION_EDIT_TD.'",
                "twiz_id": twiz_numid, 
                "twiz_column": "on_event", 
                "twiz_value": twiz_txtval
                }, function(data){
                    twizUnLockedAction();
                    $("#twiz_ajax_td_loading_on_event_" + twiz_numid).html("");
                    $("[name^=twiz_on_event]").unbind("focusout");
                    $("[name^=twiz_on_event]").unbind("change");
                    $("div[id^=twiz_ajax_td_val]").unbind("click");
                    $("input[name^=twiz_input]").unbind("keypress");
                    $("input[name^=twiz_input]").unbind("blur");
                    $("#twiz_ajax_td_val_on_event_" + twiz_numid).html(data);
                    $("#twiz_ajax_td_val_on_event_" + twiz_numid).fadeIn("fast");
                    $("#twiz_ajax_td_val_on_event_" + twiz_numid).css({"color":"green"});
                    bind_twiz_Ajax_TD();twiz_TR_View_ReBind();
                    twiz_unset_array_view_id(twiz_numid);
                }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
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
                if(twiz_ajax_locked == false){  
                    twiz_ajax_locked = true;            
                    var twiz_txtval = $("#twiz_input_" + twiz_columnName + "_" + twiz_numid).val();
                    $("#twiz_ajax_td_edit_" + twiz_columnName + "_" + twiz_numid).hide();
                    $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).html("");
                    $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).hide();
                    $("#twiz_ajax_td_loading_" + twiz_columnName + "_" + twiz_numid).html(\'<div\' + \' class="twiz-loading-bar"></div>\');
                    $.post(ajaxurl, { 
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": twiz_nonce, 
                    "twiz_action": "'.parent::ACTION_EDIT_TD.'",
                    "twiz_id": twiz_numid, 
                    "twiz_column": twiz_columnName, 
                    "twiz_value": twiz_txtval
                    }, function(data){
                        twizUnLockedAction();
                        $("#twiz_ajax_td_loading_" + twiz_columnName + "_" + twiz_numid).html("");
                        $("[name^=twiz_on_event]").unbind("change");
                        $("[name^=twiz_on_event]").unbind("focusout");
                        $("input[name^=twiz_input]").unbind("keypress");
                        $("input[name^=twiz_input]").unbind("blur");
                        $("div[id^=twiz_ajax_td_val]").unbind("click");
                        $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).html(data);
                        $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).fadeIn("fast");
                        $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).css({color:"green"});
                        bind_twiz_Ajax_TD();twiz_TR_View_ReBind();
                        twiz_unset_array_view_id(twiz_numid);
                    }).fail(function(){ twizUnLockedAction(); });
                }else{twizLockedAction();}
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
                if(twiz_ajax_locked == false){  
                twiz_ajax_locked = true;
                twiz_columnRealName = "on_event";
                twiz_numid = twiz_textid.substring(26,twiz_textid.length);
                $("#twiz_ajax_td_edit_" + twiz_columnRealName + "_" + twiz_numid).html(\'<div\' + \' class="twiz-loading-bar"></div>\');
                $("#twiz_ajax_td_val_" + twiz_columnRealName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_edit_" + twiz_columnRealName + "_" + twiz_numid).fadeIn("fast");
                $.post(ajaxurl, {
                "action": "twiz_ajax_callback",
                "twiz_nonce": twiz_nonce, 
                "twiz_action": "'.parent::ACTION_GET_EVENT_LIST.'",
                "twiz_event": twiz_tdvalue,
                "twiz_id": twiz_numid
                }, function(data){
                    twizUnLockedAction();
                    $("#twiz_ajax_td_edit_on_event_" + twiz_numid ).html(data);
                    $("[name^=twiz_on_event]").unbind("change");
                    $("[name^=twiz_on_event]").unbind("focusout");
                    $("input[name^=twiz_input]").unbind("keypress");
                    $("input[name^=twiz_input]").unbind("blur");
                    $("div[id^=twiz_ajax_td_val]").unbind("click");
                    bind_twiz_Ajax_TD();twiz_TR_View_ReBind();
                    $("#twiz_" + twiz_columnRealName + "_" + twiz_numid).focus();
                }).fail(function(){ twizUnLockedAction(); });
                break;
        }else{twizLockedAction();}                
    }});
  }
  function twizGetView(twiz_numid, e, twiz_view_level){
      if(twiz_view_id!="edit"){
      if(twiz_ajax_locked == false){ 
          twiz_ajax_locked = true;
          var twiz_right_panel = "twiz_right_panel_" + twiz_view_level;
          var twiz_from_level = "twiz_right_panel_" + (twiz_view_level - 1);
          $("#twiz_vertical_menu_box").hide();
          $("#twiz_view_box").css({"float":"left", "position":"relative", "top":"0px"});
          $("#twiz_list_tr_action_" + twiz_view_id).css("visibility", "hidden");
          $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");
          twiz_view_id = twiz_numid;
          if(twiz_array_view_id[twiz_numid + "_" + twiz_view_level] === undefined){
              $("#" + twiz_right_panel).remove();
              if(twiz_view_level == 1){
                  $(".twiz-right-panel").css("display", "none");
                  $("#twiz_view_box").append(\'<div\' + \' id="twiz_right_panel_\' + twiz_view_level + \'" class="twiz-right-panel twiz-corner-all"></div>\');
              }else{
                  $("#" + twiz_from_level).after(\'<div\' + \' id="twiz_right_panel_\' + twiz_view_level + \'" class="twiz-right-panel twiz-corner-all"></div>\');
              }          
              $("#" + twiz_right_panel).html(\'<div\' + \' class="twiz-panel-loading twiz-corner-all"></div>\');
              $("#" + twiz_right_panel).css("display", "block");
              $.post(ajaxurl, {
              "action": "twiz_ajax_callback",
              "twiz_nonce": twiz_nonce, 
              "twiz_action": "'.parent::ACTION_VIEW.'",
              "twiz_view_level": twiz_view_level,
              "twiz_id": twiz_numid
              }, function(data){
                  twizUnLockedAction();
                  $("#" + twiz_right_panel).html(data);
                  twiz_array_view_id[twiz_numid + "_" + twiz_view_level] = data;
                  twizView_Rebind();
              }).fail(function(){ twizUnLockedAction(); });
          }else{
              twizUnLockedAction();
              $(".twiz-right-panel").css("display", "none");
              $("#" + twiz_right_panel).remove();
              if(twiz_view_level == 1){
                  $("#twiz_view_box").append(\'<div\' + \' id="twiz_right_panel_\' + twiz_view_level + \'" class="twiz-right-panel twiz-corner-all">\' + twiz_array_view_id[twiz_numid + "_" + twiz_view_level] +\'</div>\');
              }else{
                  $("#" + twiz_from_level).after(\'<div\' + \' id="twiz_right_panel_\' + twiz_view_level + \'" class="twiz-right-panel twiz-corner-all">\' + twiz_array_view_id[twiz_numid + "_" + twiz_view_level] +\'</div>\');
              }
              twizView_Rebind();
          }
          for(var twiz_i = 1;twiz_i <= twiz_view_level;twiz_i++){ 
              $("#twiz_right_panel_" + twiz_i).css("display", "block");
          }        
          if(twiz_view_level == 1){
              twiz_panel_offset_switch = $(window).width() - 300;
              $("#" + twiz_right_panel).css({"float":"left","position":"relative", "top":e.pageY - 220 + "px"});
          }else{
              var twiz_top_pos = $("#" + twiz_from_level).position().top;
              $("#" + twiz_right_panel).css({"float":"left","position":"relative", "top":twiz_top_pos + "px"});
          }
          if(($("#" + twiz_right_panel).offset().left > twiz_panel_offset_switch) && (twiz_hscroll_status == "1")){ 
              twiz_view_id = "edit";
              twiz_panel_offset_switch = $("#" + twiz_from_level).offset().left + ($(window).width() - 220);
              $("html, body").animate({ scrollLeft:  $("#" + twiz_from_level).offset().left - 100}, 2000, function(){
              twiz_view_id = twiz_numid;});
          }          
          return true;
      }else{twizLockedAction();}}else{
          return false;
      }
  }
  function twiz_TR_View_ReBind(){
    $(".twiz-list-tr").unbind("mouseenter");
    $(".twiz-list-group-tr").unbind("mouseover");
    $("div[id^=twiz_'.parent::ACTION_ORDER_GROUP.'_up]").unbind("click");
    $("div[id^=twiz_'.parent::ACTION_ORDER_GROUP.'_down]").unbind("click");
    bind_twiz_TR_View();
  }      
  var bind_twiz_TR_View = function(){
      $("div[id^=twiz_'.parent::ACTION_ORDER_GROUP.'_up]").click(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(19,twiz_textid.length);
        $("#twiz_ajax_td_order_" + twiz_numid).html(\'<div\' + \' class="twiz-loading-bar"></div>\');
        twizOrderList("'.parent::LB_ORDER_UP.'", twiz_numid, "'.parent::ACTION_ORDER_GROUP.'");
    });
    $("div[id^=twiz_'.parent::ACTION_ORDER_GROUP.'_down]").click(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(21, twiz_textid.length);
        $("#twiz_ajax_td_order_" + twiz_numid).html(\'<div\' + \' class="twiz-loading-bar"></div>\');
        twizOrderList("'.parent::LB_ORDER_DOWN.'", twiz_numid, "'.parent::ACTION_ORDER_GROUP.'");
    });
    $(".twiz-list-tr").mouseenter(function(e){
    if(twiz_library_active == false){
        if(twiz_ajax_locked == false){  
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(13, twiz_textid.length);
        var twiz_ok = twizGetView(twiz_numid, e, 1);
        }else{twizLockedAction();}
    }});
    $(".twiz-list-group-tr").hover(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(19, twiz_textid.length);
        $("#twiz_vertical_menu_box").hide();
        $("#twiz_list_tr_action_" + twiz_view_id).css("visibility", "hidden");
        $("[name=twiz_'.parent::ACTION_ORDER_GROUP.'_" + twiz_numid +"]" ).show();
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");
        $(".twiz-right-panel").css("display", "none");
        twiz_view_id = twiz_numid;
    }, function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(19, twiz_textid.length);    
        $("[name=twiz_'.parent::ACTION_ORDER_GROUP.'_" + twiz_numid +"]" ).hide();
    });   
  } 
  var bind_twiz_Menu = function(){
    $("a[id^=twiz_section_cancel]").click(function(){
        $("#twiz_sub_container").hide();
        if( $("#twiz_id").val() === undefined ){ 
            $("[name=twiz_listmenu]").css("display", "block");
        }
        $("#twiz_import").fadeIn("fast");
        $("#twiz_export").fadeIn("fast"); 
        $("#twiz_ajax_menu").show();
        $("#twiz_container").css("display", "block");
        restoreTwizCurrentSectionId();
    });
    $("#twiz_add_menu").click(function(){    
        if(twiz_ajax_locked == false){ 
        twizSwitchFooterMenu();
        $("#twiz_sub_container").html("");
        $("#twiz_sub_container").show("fast");
        $("[name=twiz_listmenu]").css("display", "none");
        $("#twiz_container").css("display", "none");
        twiz_last_section_id = twiz_current_section_id;
        twiz_current_section_id = ""; 
        twizGetMultiSection("", "'.parent::ACTION_NEW.'", "");
        }else{twizLockedAction();}
    });
    $("#twiz_edit_menu").click(function(){    
        if(twiz_ajax_locked == false){     
        twizSwitchFooterMenu();
        $("#twiz_sub_container").html("");
        $("#twiz_sub_container").show("fast");
        $("[name=twiz_listmenu]").css("display", "none");
        $("#twiz_container").css("display", "none");
        twizGetMultiSection(twiz_current_section_id, "'.parent::ACTION_EDIT.'", "");
        }else{twizLockedAction();}       
    });
    $("#twiz_delete_menu").click(function(){  
        if(twiz_ajax_locked == false){  
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")){
            twiz_ajax_locked = true;
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": twiz_nonce, 
            "twiz_action": "'.parent::ACTION_DELETE_SECTION.'",
            "twiz_section_id": twiz_current_section_id
            }, function(data){                
                twizUnLockedAction();
                twiz_current_section_id = data;
                twizGetMenu();
                twizCleanImportMenu();
                twizPostMenu(data,"","block");
            }).fail(function(){ twizUnLockedAction(); });
        }
    }else{twizLockedAction();}});
    $("div[id^=twiz_menu_]").click(function(){
    if(twiz_ajax_locked == false){      
        var twiz_textid = $(this).attr("id");
        twiz_current_section_id = twiz_textid.substring(10,twiz_textid.length);
        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
        $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
        $("#twiz_vmenu_allarticles").attr({"class" : "twiz-menu twiz-corner-bottom"});
        $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});
        $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
        $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
        $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
        twizCleanImportMenu();
        twizPostMenu(twiz_current_section_id,"","block");
    }else{twizLockedAction();}});
    $("div[id^=twiz_vmenu_]").click(function(){
    if(twiz_ajax_locked == false){      
        var twiz_textid = $(this).attr("id");
        twiz_current_section_id = twiz_textid.substring(11,twiz_textid.length);
        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
        $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
        $("#twiz_vmenu_allarticles").attr({"class" : "twiz-menu twiz-corner-bottom"});
        $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});
        $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
        $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
        $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
        twizCleanImportMenu();
        twizPostMenu(twiz_current_section_id,"","block");
    }else{twizLockedAction();}});
  }    
  var bind_twiz_Order_by = function(){
    $("a[id^=twiz_order_by_]").click(function(){
    if(twiz_ajax_locked == false){     
        var twiz_textid = $(this).attr("id");
        twiz_order_by = twiz_textid.substring(14,twiz_textid.length);
        twizPostMenu(twiz_current_section_id, twiz_order_by, "block");
    }else{twizLockedAction();}});
  }
  function restoreTwizCurrentSectionId(){
    if(twiz_last_section_id != ""){
        twiz_current_section_id = twiz_last_section_id;
        twiz_last_section_id = "";
    }
  }
  function twizList_ReBind(){
    $("img[name^=twiz_status_img]").unbind("click");
    $("a[id^=twiz_cancel]").unbind("click");
    $("a[id^=twiz_order_by_]").unbind("click");
    $("input[name=twiz_save]").unbind("click");
    $("#twiz_on_event").unbind("change");
    $("[class^=twiz-slc-js-features]").unbind("change");
    $(".twiz-js-features a").unbind("click");
    $(".twiz-edit").unbind("click");
    $(".twiz-copy").unbind("click");
    $(".twiz-delete").unbind("click");
    $("#twiz_empty_list").unbind("click");
    $(".twiz-group-edit").unbind("click");
    $(".twiz-group-delete").unbind("click");
    $(".twiz-list-tr").unbind("draggable");
    $(".twiz-list-tr").unbind("droppable");
    $(".twiz-list-group-tr").unbind("droppable");
    $(".twiz-table-list-tr-h").unbind("droppable");
    bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
    bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();bind_twiz_Choose_Options();
    bind_twiz_Ajax_TD();twiz_TR_View_ReBind();bind_twiz_Order_by();
  }  
  function twizPostMenu(twiz_section_id, twiz_order_by, twiz_display){
   if(twiz_ajax_locked == false){ 
   twizShowMainLoadingImage();
   $("#twiz_far_matches").html("");
   $("#twiz_sub_container").html("");
   $("#twiz_ajax_menu").show();    
   $("#twiz_edit_menu").show();
   $("#twiz_delete_menu").show();
   $("#twiz_library_menu").attr({"class" : "twiz-menu twiz-display-none"});
   $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-display-none"});
   $("#twiz_container").css("display", twiz_display);
   $("#twiz_container").html("");
   $("#twiz_container").toggle();
   $("#twiz_library_upload").fadeOut("fast");
   $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_MENU.'",
        "twiz_section_id": twiz_section_id,
        "twiz_order_by": twiz_order_by
        }, function(data){
            $("#twiz_container").html(data);
            twiz_view_id = null;
            twiz_array_view_id = new Array();
            twizList_ReBind();
            $("#twiz_container").toggle();
            twiz_library_active = false;
            twiz_current_group_id = "";      
            twiz_last_section_id = "";
            if($("#twiz_stay_section").is(":checked") != true){
                $("[name=twiz_listmenu]").css("display", twiz_display);
                $("#twiz_import").fadeIn("fast");
                $("#twiz_export").fadeIn("fast");                
            }            
        });
  }else{twizLockedAction();}}
  var bind_twiz_Save_Section = function(){
    $("#twiz_slc_cookie_option_1").change(function(){
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
        if($("#twiz_shortcode").val() == "' .__('Please type a shortcode ID.', 'the-welcomizer').'"){
            $("#twiz_shortcode").attr({"value" : ""});
            $("#twiz_shortcode").css("color", "#333333");
        }
    });
    $("input[id^=twiz_save_section]").click(function(){
    if(twiz_ajax_locked == false){  
        var twiz_sectionid = [];
        var twiz_validsection = true;
        var twiz_output = "";
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
            $("#twiz_more_options").html("'.__('Less options', 'the-welcomizer').' &#187;");
            twiz_showOrHide_more_section_options = true;
            $(".twiz-section-more-options").toggle(twiz_showOrHide_more_section_options);
            $(".twiz-tab").attr({"class":"twiz-tab twiz-corner-top"});
            $("#twiz_tabmenu_cookie").attr({"class":"twiz-tab twiz-corner-top twiz-tab-selected"});
            $("#twiz_tab_activation").attr({"class":"twiz-display-none"});            
            $("#twiz_tab_cookie").attr({"class":""});
            twiz_validsection = false;            
        }        
        $("input[name=twiz_output_choice]:radio").each(function(){
        if ($(this).attr("checked")){
            twiz_output = $(this).val();
        }}); 
        switch(twiz_output){
            case "twiz_shortcode_output":
                if(($("#twiz_shortcode").val()== "") || ($("#twiz_shortcode").val() == "' .__('Please type a shortcode ID.', 'the-welcomizer').'")){
                    $("#twiz_shortcode").attr({"value" : "' .__('Please type a shortcode ID.', 'the-welcomizer').'"});
                    $("#twiz_shortcode").css("color", "#BC0B0B");
                     twiz_validsection = false;
                } 
                break;
            case "twiz_single_output":
                twiz_sectionid[0] = $("#twiz_slc_sections").val();
                if(twiz_sectionid[0] == ""){
                    $("#twiz_custom_message_1").html("' .__('Please choose an output option.', 'the-welcomizer').'");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_1").html("");
                }              
                break;
            case "twiz_multiple_output":
                if( $("#twiz_slc_multi_sections :selected").length > 0){
                    $("#twiz_slc_multi_sections :selected").each(function(i, selected){
                        twiz_sectionid[i] = $(selected).val();
                    });
                }            
                if(twiz_sectionid.length == 0){
                    $("#twiz_custom_message_2").html("' .__('Please select at least one option.', 'the-welcomizer').'");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_2").html("");
                }     
            break;
            case "twiz_logic_output":
                if($("#twiz_custom_logic").val()== ""){
                    $("#twiz_custom_message_3").html("' .__('Please type a custom logic.', 'the-welcomizer').'");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_3").html("");
                }              
                break;
        }
        if(twiz_validsection == true){
            twiz_ajax_locked = true;
            var twiz_textid = $(this).attr("id");
            var twiz_charid = twiz_textid.substring(18,twiz_textid.length);
            var twiz_stay_section = $("#twiz_stay_section").is(":checked");
            $("#twiz_menu_save_img_box_" + twiz_charid).show();
            $("#twiz_menu_save_img_box_" + twiz_charid).attr("class","twiz-save twiz-loading-gif-save");
            $("input[name=twiz_save_section]").css({"color" : "#9FD0D5"});
            $.post(ajaxurl, {
                 "action": "twiz_ajax_callback",
                 "twiz_nonce": twiz_nonce, 
                 "twiz_action": "'.parent::ACTION_SAVE_SECTION.'",
                 "twiz_section_status": $("#twiz_section_status").is(":checked"),
                 "twiz_visibility": $("#twiz_visibility").val(),
                 "twiz_section_name": $("#twiz_section_name").val(),
                 "twiz_current_section_id": $("#twiz_section_id").val(),
                 "twiz_output_choice": $("input[name=twiz_output_choice]:checked").val(),
                 "twiz_custom_logic": $("#twiz_custom_logic").val(),
                 "twiz_shortcode": $("#twiz_shortcode").val(),
                 "twiz_section_id": JSON.stringify(twiz_sectionid),
                 "twiz_cookie_condition": $("#twiz_slc_cookie_condition").val(),
                 "twiz_cookie_name": $("#twiz_cookie_name").val(),
                 "twiz_cookie_option_1": $("#twiz_slc_cookie_option_1").val(),
                 "twiz_cookie_option_2": $("#twiz_slc_cookie_option_2").val(),
                 "twiz_cookie_with": $("#twiz_slc_cookie_with").val(),
                 "twiz_cookie_scope": $("#twiz_slc_cookie_scope").val(),                
                 "twiz_shortcode_html": $("#twiz_shortcode_html").val(),
                 "twiz_stay_section": $("#twiz_stay_section").is(":checked")';
                
                  if( is_multisite() ){
                  
                    $header .= ',"twiz_'.parent::F_BLOG_ID.'": JSON.stringify($("#twiz_'.parent::F_BLOG_ID.'").val())';
                  }
                 
         $header.= '}, function(data){ 
                    twizUnLockedAction();
                    data = JSON.parse(data);   
                    twiz_current_section_id = data.sectionid;
                    twizGetMenu();
                    if(data.stay == "true"){
                        twizPostMenu(twiz_current_section_id,"","block");
                        $("#twiz_sub_container").html(data.html);
                        $("#twiz_ajax_menu").hide();
                        $("#twiz_section_cancel").unbind("click");
                        $("#twiz_add_menu").unbind("click");
                        $("#twiz_edit_menu").unbind("click");
                        $("#twiz_delete_menu").unbind("click");
                        $("div[id^=twiz_menu_]").unbind("click");
                        $("div[id^=twiz_vmenu_]").unbind("click");
                        $("input[id^=twiz_save_section]").unbind("click");
                        $("#twiz_section_name").unbind("click");
                        $("#twiz_slc_sections").unbind("change");
                        twizHideMainLoadingImage();
                        $("img[name^=twiz_status_img]").unbind("click");
                        twiz_view_id = null; 
                        twiz_ListMenu_Unbind();
                        bind_twiz_ListMenu();
                        twizList_ReBind();
                        bind_twiz_Menu();
                        bind_twiz_Status();
                        bind_twiz_Save_Section();
                        $("#twiz_container").toggle();
                        $("#twiz_export").show();               
                        $("#twiz_menu_save_img_box_" + twiz_charid).attr("class","twiz-success twiz-loading-gif-save");
                        $("#twiz_menu_save_img_box_" + twiz_charid).animate({ opacity: 0 });                        
                        
                    }else{
                        twizPostMenu(twiz_current_section_id,"","block");
                        $("[name=twiz_listmenu]").css("display", "block");
                        // line 1849 for buttons
                    }
                }).fail(function(){ twizUnLockedAction(); });
        }}else{twizLockedAction();}});
  }  
    var bind_twiz_Library_New_Order = function(){
        $("div[id^=twiz_'.parent::ACTION_ORDER_LIBRARY.'_up]").click(function(){
            var twiz_textid = $(this).attr("id");
            var twiz_numid = twiz_textid.substring(17,twiz_textid.length);
            $("#twiz_list_td_" + twiz_numid).html(\'<div\' + \' class="twiz-loading-bar"></div>\');
            twizOrderList("'.parent::LB_ORDER_UP.'", twiz_numid, "'.parent::ACTION_ORDER_LIBRARY.'");
        });
        $("div[id^=twiz_'.parent::ACTION_ORDER_LIBRARY.'_down]").click(function(){
            var twiz_textid = $(this).attr("id");
            var twiz_numid = twiz_textid.substring(19, twiz_textid.length);
            $("#twiz_list_td_" + twiz_numid).html(\'<div\' + \' class="twiz-loading-bar"></div>\');
            twizOrderList("'.parent::LB_ORDER_DOWN.'", twiz_numid, "'.parent::ACTION_ORDER_LIBRARY.'");
        });
    }
    function twizOrderList(twiz_order, twiz_id, twiz_action){
      if(twiz_ajax_locked == false){ 
      twiz_ajax_locked = true;
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": twiz_action,
        "twiz_order": twiz_order,
        "twiz_section_id": twiz_current_section_id,
        "twiz_id": twiz_id
        }, function(data){                
            twizUnLockedAction();
            $("div[id^=twiz_" + twiz_action + "_up]").unbind("click");
            $("div[id^=twiz_" + twiz_action + "_down]").unbind("click");
            $(".twiz-delete").unbind("click");
            $("#twiz_container").html(data);
            switch(twiz_action){
                case "'.parent::ACTION_ORDER_LIBRARY.'":
                    twizLibrary_Bind();
                    break;
                case "'.parent::ACTION_ORDER_GROUP.'":
                    twizList_ReBind();
                    break;
            }
        }).fail(function(){ twizUnLockedAction(); });
  }else{twizLockedAction();}}
  var bind_twiz_Import_From_Server = function(){
      $("[id^=twiz_list_tr_]").hover(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(13, twiz_textid.length);
        $("[id=twiz_download_" + twiz_numid +"]" ).show();

    }, function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(13, twiz_textid.length);    
        $("[id=twiz_download_" + twiz_numid +"]" ).hide();
    });  
      $("#twiz_input_ifs_filter").keypress(function (e){
        var twiz_export_filter = "";
        if($("#twiz_input_ifs_filter").val() == ""){
          twiz_export_filter = "twiz_export_filter_none";
        }else{
          twiz_export_filter =  $("#twiz_input_ifs_filter").val();
        }
        switch(e.keyCode){
            case 27:
                    $("#twiz_ajax_td_edit_ifs_filter").hide();
                    $("#twiz_ajax_td_a_ifs_filter").fadeIn("fast"); 
            break;
            case 13:
                if(twiz_ajax_locked == false){  
                    twiz_ajax_locked = true;            
                    twizShowMainLoadingImage();
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": twiz_nonce, 
                    "twiz_action": "'.parent::ACTION_GET_EXPORT_FILE_LIST.'",
                    "twiz_section_id": twiz_current_section_id,
                    "twiz_export_filter": twiz_export_filter
                    }, function(data){
                        twizUnLockedAction();
                        twizHideMainLoadingImage();
                        $("#twiz_sub_container").html(data);
                        bind_twiz_Import_From_Server();
                    }).fail(function(){ twizUnLockedAction(); });
                }else{twizLockedAction();}
            break;
        }            
     });  
     $("#twiz_ajax_td_a_ifs_filter").click(function(){
        $(this).hide();
        $("#twiz_ajax_td_edit_ifs_filter").fadeIn("fast"); 
        $("#twiz_input_ifs_filter").select();
     });    
     $("#twiz_input_ifs_filter").blur(function(){
        $("#twiz_ajax_td_edit_ifs_filter").hide();
        $("#twiz_ajax_td_a_ifs_filter").fadeIn("fast"); 
     });       
     $(".twiz-delete-export").click(function(){
        if(twiz_ajax_locked == false){ 
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")){
        twiz_ajax_locked = true;
        $(this).attr("class","twiz-save-bigger twiz-loading-gif-action");
        var twiz_textid = $(this).attr("id");
        var twiz_id = twiz_textid.substring(12,twiz_textid.length);       
        var twiz_export_filter = "";
        if($("#twiz_input_ifs_filter").val() == ""){
          twiz_export_filter = "twiz_export_filter_none";
        }else{
          twiz_export_filter =  $("#twiz_input_ifs_filter").val();
        }        
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_DELETE_EXPORT_FILE.'",
        "twiz_section_id": twiz_current_section_id,
        "twiz_id": twiz_id,        
        "twiz_export_filter": twiz_export_filter
        }, function(data){
            twizUnLockedAction();
            if(data!=""){
                $("#twiz_sub_container").html(data);
                bind_twiz_Import_From_Server();
            }
        }).fail(function(){ twizUnLockedAction(); });
    }}else{twizLockedAction();}});    
  $(".twiz-import-from-server").click(function(){
        if(twiz_ajax_locked == false){ 
         twiz_ajax_locked = true;
         twizShowMainLoadingImage();
         twizSwitchFooterMenu();         
         $("#twiz_sub_container").html("");
         $("#twiz_sub_container").css("display", "none");
         $("[name=twiz_listmenu]").css("display", "none");
         var twiz_textid = $(this).attr("id");
         var twiz_id = twiz_textid.substring(24,twiz_textid.length);
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_IMPORT_FROM_SERVER.'",
        "twiz_section_id": twiz_current_section_id,
        "twiz_group_id": twiz_current_group_id,
        "twiz_id": twiz_id
        }, function(data){
            twizUnLockedAction();
            data = JSON.parse(data);
            twizHideMainLoadingImage();
            $("#twiz_container").html(data.html);
            if((data.newsectionid != "")&&(data.sectionid == "")){
                twiz_last_section_id = "";
                twiz_current_section_id = data.newsectionid; 
                twizGetMenu();
                twizGetMultiSection(twiz_current_section_id, "'.parent::ACTION_EDIT.'", "'.parent::ACTION_IMPORT.'");
                $("#twiz_sub_container").css("display", "block");              
                twiz_view_id = null; 
                twiz_ListMenu_Unbind();
                bind_twiz_ListMenu();
            }else{
                $("#twiz_container").toggle();    
                $("[name=twiz_listmenu]").css("display", "block");
                $("#twiz_import").fadeIn("fast");
            }
            twizList_ReBind();
            switch(true){
                case (data.error == ""):
                    $("#qq_upload_list").html("<li>" + data.filename + "</li>"); 
                    break;
                case (data.error != ""):
                    alert(data.error);
                    twiz_current_group_id = "";
                break;
            }
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});    
   $(".twiz-toggle-export").click(function(){
        if(twiz_ajax_locked == false){ 
        twiz_ajax_locked = true;
        twizShowMainLoadingImage();
        var twiz_toggle_status = 0;
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(16,twiz_textid.length);
        var twiz_src = $("#twiz_export_img_" + twiz_charid).attr("class");
        if(twiz_src.indexOf("twiz-plus") != -1){
            $("#twiz_export_img_" + twiz_charid).removeClass("twiz-plus").addClass("twiz-minus");
            $("#twiz_export_a_e_" + twiz_charid).attr("class","twiz-toggle-export twiz-bold");
            $("." + twiz_charid).removeClass("twiz-display-none");
            twiz_toggle_status = 1;
        }else{
            $("#twiz_export_img_" + twiz_charid).removeClass("twiz-minus").addClass("twiz-plus");
            $("#twiz_export_a_e_" + twiz_charid).attr("class","twiz-toggle-export");
            $("." + twiz_charid).addClass("twiz-display-none");
        }
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_TOGGLE.'",
        "twiz_toggle_status": twiz_toggle_status,
        "twiz_toggle_type": "'.parent::KEY_TOGGLE_EXPORT.'",
        "twiz_charid": twiz_charid
        }, function(data){ twizUnLockedAction(); twizHideMainLoadingImage(); }).fail(function(){ twizUnLockedAction(); });
     }else{twizLockedAction();}});
   }
   function twiz_FooterVMenu_Rebind(){
        $("#twiz_export_all").unbind("click");
        bind_twiz_FooterVMenu();
   }
   var bind_twiz_FooterVMenu = function(){  
        $("#twiz_export_all").click(function(){
        if(twiz_ajax_locked == false){ 
        twiz_ajax_locked = true;
        $("#twiz_export_all_url").html(\'<div\' + \' class="twiz-loading-bar"></div>\');
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_EXPORT_ALL.'",
        }, function(data){
           twizUnLockedAction();
           $("#twiz_export_all_url").html(data);
        }).fail(function(){ twizUnLockedAction(); });
        }else{twizLockedAction();}});
  }
  var bind_twiz_FooterMenu = function(){  
    $("#twiz_import_from_server").click(function(){
        if(twiz_ajax_locked == false){ 
         twiz_ajax_locked = true;
         twizShowMainLoadingImage();
         $("#twiz_import_menu").toggle();
         $("#twiz_import_menu").hide();
         $("#qq_upload_list li").remove();         
         $("#twiz_export_url").html("");
         $("#twiz_export").fadeOut("fast"); 
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_GET_EXPORT_FILE_LIST.'",
        "twiz_section_id": twiz_current_section_id,
        "twiz_export_filter": "",
        }, function(data){
            twizUnLockedAction(); 
            twizHideMainLoadingImage();
            $("#twiz_sub_container").html(data);
            $("#twiz_sub_container").show();
            $("#twiz_container").css("display", "none");
            $("[name=twiz_listmenu]").css("display", "none");
            $(".twiz-right-panel").fadeOut("fast");
            bind_twiz_Import_From_Server();
            twizScrollTop();
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});    
    $("#twiz_import").click(function(){
        $("#twiz_import_menu").toggle();
        $("#qq_upload_list li").remove();
    });    
    $("#twiz_library_upload").click(function(){
        $("#twiz_footer_library_menu").toggle();
        $("#qq_upload_list li").remove();
    });      
    $("#twiz_export").click(function(){
        if(twiz_ajax_locked == false){ 
        twiz_ajax_locked = true;
        twizCleanImportMenu();
        $("#twiz_export_url").html(\'<div\' + \' class="twiz-loading-bar"></div>\');
        var twiz_animid = $("#twiz_id").val();
        if(twiz_animid===undefined){
           twiz_animid = "";
        }
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_EXPORT.'",
        "twiz_section_id": twiz_current_section_id,
        "twiz_group_id": twiz_current_group_id,
        "twiz_id": twiz_animid
        }, function(data){
           twizUnLockedAction();
           $("#twiz_export_url").html(data);
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});';
    
    if(current_user_can($this->admin_option[parent::KEY_MIN_ROLE_LIBRARY])){    
 
        $header.= '
        $("#twiz_library").click(function(){
            twizScrollTop();
            $("#twiz_sub_container").hide();
            $("[name=twiz_listmenu]").css("display", "none");
            twizSwitchFooterMenu();
            twizPostLibrary();
        });
        $("#twiz_library_menu").click(function(){
            twizScrollTop();
            $("#twiz_sub_container").hide();
            twizSwitchFooterMenu();
            twizPostLibrary();
        });';
  
  }else{
  
        $header.= '
        $("#twiz_library").css("color", "#999999");
        $("#twiz_library").click(function(){
            alert("'.__('You do not have sufficient permissions to access this section.').'");
      });
      ';
  }
 
  if(current_user_can($this->admin_option[parent::KEY_MIN_ROLE_ADMIN])){   
  
   $header.= '
   $("div[id^=twiz_admin]").click(function(){
      if(twiz_ajax_locked == false){ 
      twiz_ajax_locked = true;
      twizScrollTop();
      twizShowMainLoadingImage();
      $("#twiz_sub_container").hide();
      $("[name=twiz_listmenu]").css("display", "none");
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
      $("#twiz_container").toggle();
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_ADMIN.'"
        }, function(data){                
            twizUnLockedAction();
            $("#twiz_container").html(data);
            $("#twiz_container").toggle();
            bind_twiz_AdminSave();
            bind_twiz_Cancel();
            twizHideMainLoadingImage();
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});';
    
  }else{
  
      $header.= '
      $("#twiz_admin").css("color", "#999999");
      $("div[id^=twiz_admin]").click(function(){
          alert("'.__('You do not have sufficient permissions to access this section.').'");
      });      
      ';
  }
  
  $header.= '
  }  
  function twizScrollTop(){
     $(document).scrollTop(0);
     $(document).scrollLeft(0);  
  }
  function twizCleanImportMenu(){
    $("#twiz_import_menu").hide();
    $("#qq_upload_list li").remove();
    $("#twiz_export_url").html("");
    $("#twiz_export_all_url").html("");
    $("#twiz_footer_library_menu").hide();
  }
  function twizSwitchFooterMenu(){
      twizCleanImportMenu();
      $("#twiz_export").fadeOut("fast");
      $("#twiz_import").fadeOut("fast");
      $("#twiz_ajax_menu").show();
  }   
  var binb_twiz_Link_dir = function(){
     $("#twiz_lib_dir").click(function(){
        if($("#twiz_lib_dir").val() == "'.__('Type a directory name.', 'the-welcomizer').'"){
            $("#twiz_lib_dir").attr({"value" : ""});
            $("#twiz_lib_dir").css("color", "#333333");
        }
    });
    $("#twiz_lib_cancel").click(function(){
        $("#twiz_sub_container").hide();
        $("#twiz_lib_menu").css("display", "block");
        $("#twiz_container").css("display", "block");
        $("#twiz_library_upload").fadeIn("fast");
    });
    $("input[name=twiz_lib_save]").click(function(){ 
      if(twiz_ajax_locked == false){ 
        twiz_ajax_locked = true;
        var twiz_validlib = true;
        if(($("#twiz_lib_dir").val() == "'.__('Type a directory name.', 'the-welcomizer').'")
        || ($("#twiz_lib_dir").val() == "")){
            $("#twiz_lib_dir").val("'.__('Type a directory name.', 'the-welcomizer').'");
            $("#twiz_lib_dir").css("color", "#BC0B0B");
            twiz_validlib = false;
            twizUnLockedAction();
        }
        if(twiz_validlib == true){
            $("#twiz_lib_save_img_box").show();
            $("#twiz_lib_save_img_box").attr("class","twiz-save twiz-loading-gif-save");
            $("input[id=twiz_lib_save]").css({"color" : "#9FD0D5"});
            $.post(ajaxurl,  {
            "action": "twiz_ajax_callback",
            "twiz_action": "'.parent::ACTION_LINK_LIBRARY_DIR.'",
            "twiz_lib_dir": $("#twiz_lib_dir").val(),
            "twiz_nonce": twiz_nonce
         }, function(data){
                twizUnLockedAction();
                $("img[name^=twiz_status]").unbind("click");
                $(".twiz-delete").unbind("click");
                $("#twiz_sub_container").html("");
                $("#twiz_container").html(data);
                $("#twiz_container").css("display", "block");
                twizLibrary_Bind();
            }).fail(function(){ twizUnLockedAction(); });
        }
    }else{twizLockedAction();}});
  }
  function twizLibrary_Bind(){
    binb_twiz_Link_dir(); bind_twiz_Library();
    bind_twiz_Status();bind_twiz_Delete();
    bind_twiz_Library_New_Order();
  }
  var bind_twiz_Library = function(){  
    $("[id^=twiz_list_tr_]").hover(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(13, twiz_textid.length);
        $("[name=twiz_'.parent::ACTION_ORDER_LIBRARY.'_" + twiz_numid +"]" ).show();

    }, function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid = twiz_textid.substring(13, twiz_textid.length);    
        $("[name=twiz_'.parent::ACTION_ORDER_LIBRARY.'_" + twiz_numid +"]" ).hide();
    });  
     $("#twiz_lib_menu").click(function(){
      if(twiz_ajax_locked == false){ 
         twiz_ajax_locked = true;
         twizShowMainLoadingImage();
         $("#twiz_footer_library_menu").hide();
         $("#twiz_library_upload").fadeOut("fast");
         $("#twiz_container").css("display", "none");
         $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": twiz_nonce, 
            "twiz_action": "'.parent::ACTION_GET_LIBRARY_DIR.'"
            }, function(data){                         
                twizUnLockedAction();
                $("#twiz_sub_container").html(data);
                $("#twiz_sub_container").show();
                binb_twiz_Link_dir();
                twizHideMainLoadingImage();
                $("#twiz_lib_dir").focus();
            }).fail(function(){ twizUnLockedAction(); });
     }else{twizLockedAction();}});
     $(".twiz-toggle-library").click(function(){
        if(twiz_ajax_locked == false){ 
        twiz_ajax_locked = true;
        twizShowMainLoadingImage();
        var twiz_toggle_status = 0;
        var twiz_textid = $(this).attr("id");
        var twiz_charid = twiz_textid.substring(17,twiz_textid.length);
        var twiz_src = $("#twiz_library_img_" + twiz_charid).attr("class");
        if(twiz_src.indexOf("twiz-plus") != -1){
            $("#twiz_library_img_" + twiz_charid).removeClass("twiz-plus").addClass("twiz-minus");
            $("#twiz_library_e_a_" + twiz_charid).attr("class","twiz-toggle-library twiz-bold");
            $("." + twiz_charid).removeClass("twiz-display-none");
            twiz_toggle_status = 1;
        }else{
            $("#twiz_library_img_" + twiz_charid).removeClass("twiz-minus").addClass("twiz-plus");
            $("#twiz_library_e_a_" + twiz_charid).attr("class","twiz-toggle-library");
            $("." + twiz_charid).addClass("twiz-display-none");
        }
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_TOGGLE.'",
        "twiz_toggle_status": twiz_toggle_status,
        "twiz_toggle_type": "'.parent::KEY_TOGGLE_LIBRARY.'",
        "twiz_charid": twiz_charid
        }, function(data){ twizUnLockedAction(); twizHideMainLoadingImage();}).fail(function(){ twizUnLockedAction(); });
     }else{twizLockedAction();}});
     $(".twiz-library-unlink").click(function(){
        if(twiz_ajax_locked == false){ 
        twiz_ajax_locked = true;
        var twiz_textid = $(this).attr("id");
        var twiz_id = twiz_textid.substring(20,twiz_textid.length);
        $(this).attr("class", "twiz-save twiz-unlink");
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_UNLINK_LIBRARY_DIR.'",
        "twiz_id": twiz_id
        },function(data){
            twizUnLockedAction();
            $("#twiz_container").html(data);
            twizLibrary_Bind();
        }).fail(function(){ twizUnLockedAction(); });
     }else{twizLockedAction();}});
  }   
  function twizPostLibrary(){
      if(twiz_ajax_locked == false){ 
      twiz_ajax_locked = true;
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
      $("#twiz_container").toggle();
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_LIBRARY.'"
        }, function(data){                
            twizUnLockedAction();
            $("img[name^=twiz_status]").unbind("click");
            $(".twiz-delete").unbind("click");
            $("#twiz_container").html(data);
            $("#twiz_container").toggle();
            twizLibrary_Bind();
            twizHideMainLoadingImage();
        }).fail(function(){ twizUnLockedAction(); });
  }else{twizLockedAction();}}
  function twizGetMenu( twiz_restore ){
      if(twiz_ajax_locked == false){
      twizScrollTop();
      $("#twiz_ajax_menu").html("");    
      $("#twiz_ajax_menu").show();    
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce,       
        "twiz_action": "'.parent::ACTION_GET_MENU.'",
        "twiz_section_id": twiz_current_section_id
        }, function(datamenu){   
              $("#twiz_ajax_menu").html(datamenu);
              if( twiz_restore != "restore" ) {
                $("#twiz_status_menu_" + twiz_current_section_id).addClass("twiz-display-block");
                $("#twiz_menu_" + twiz_current_section_id).addClass("twiz-menu-selected twiz-display-block");
              }else{
                $("#twiz_edit_menu").hide();
                $("#twiz_delete_menu").hide();
                $("#twiz_status_menu_" + twiz_current_section_id).addClass("twiz-display-none");
                $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});      
                $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});                 
              }     
              $.post(ajaxurl, {
                "action": "twiz_ajax_callback",
                "twiz_nonce": twiz_nonce,       
                "twiz_action": "'.parent::ACTION_GET_VMENU.'",
                "twiz_section_id": twiz_current_section_id
                }, function(data){      
                    $("#twiz_section_cancel").unbind("click");
                    $("#twiz_add_menu").unbind("click");
                    $("#twiz_edit_menu").unbind("click");
                    $("#twiz_delete_menu").unbind("click");
                    $("div[id^=twiz_menu_]").unbind("click");
                    $("div[id^=twiz_vmenu_]").unbind("click");
                    $("#twiz_vertical_menu").html(data);
                    $("img[name^=twiz_status_img]").unbind("click");
                    $("#twiz_vmenu_" + twiz_current_section_id).addClass("twiz-menu-selected");
                    bind_twiz_Menu();
                    bind_twiz_Status();
                    twiz_FooterVMenu_Rebind();      
                });
        });
  }else{twizLockedAction();}}  
  function twizShowMainLoadingImage(){
      $("#twiz_loading_menu").html(\'<div\' + \' class="twiz-menu twiz-noborder-right"><div\' + \' class="twiz-loading-bar"></div></div>\');
  }
  function twizHideMainLoadingImage(){
      $("#twiz_loading_menu").html("");
  }  
  function twizGetMultiSection(twiz_section_id, twiz_sub_action, twiz_stay_section){
      if(twiz_ajax_locked == false){ 
      twizShowMainLoadingImage();
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce,
        "twiz_action": "'.parent::ACTION_GET_MULTI_SECTION.'",
        "twiz_sub_action": twiz_sub_action,
        "twiz_section_id": twiz_section_id,
        "twiz_stay_section": twiz_stay_section
        }, function(data){                
            twizUnLockedAction();
            if(twiz_sub_action == "'.parent::ACTION_NEW.'"){
                $("#twiz_ajax_menu").hide();
            }
            $("#twiz_section_cancel").unbind("click");
            $("#twiz_add_menu").unbind("click");
            $("#twiz_edit_menu").unbind("click");
            $("#twiz_delete_menu").unbind("click");
            $("div[id^=twiz_menu_]").unbind("click");
            $("div[id^=twiz_vmenu_]").unbind("click");
            $("input[id^=twiz_save_section]").unbind("click");
            $("#twiz_section_name").unbind("click");
            $("#twiz_slc_sections").unbind("change");
            $("#twiz_sub_container").html(data);
            twizHideMainLoadingImage();
            $("img[name^=twiz_status_img]").unbind("click");
            bind_twiz_Menu();
            bind_twiz_Status();
            bind_twiz_Save_Section();
        });
  }else{twizLockedAction();}} 
  function twizView_Rebind(){
      $(".twiz-view-image-link").unbind("hover");
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
      $(".twiz-list-tr").unbind("droppable");
      $(".twiz-edit").unbind("click");
      $(".twiz-group-edit").unbind("click");
      $(".twiz-copy").unbind("click");
      $(".twiz-group-copy").unbind("click");
      $(".twiz-delete").unbind("click");
      $("#twiz_empty_list").unbind("click");
      $(".twiz-group-delete").unbind("click");
      bind_twiz_View();bind_twiz_Edit();bind_twiz_Copy();bind_twiz_Delete();
  }
  var bind_twiz_View = function(){
        $(".twiz-view-image-link").hover(function (e){
        $("#twiz_view_image").css({"top":e.pageY - 10 + "px", "left":e.pageX - 260 + "px"});
            $("#twiz_view_image").html(\'<img\' + \' src="\' + $(this).attr("href") + \'"/>\');
        }, function (){
            $("#twiz_view_image").html("");
        });  
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
       if(twiz_ajax_locked == false){ 
        twiz_ajax_locked = true;
        var twiz_textid = $(this).attr("id");
        var twiz_charid  = twiz_textid.substring(15,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];
        var twiz_view_level = twiz_charid[1];
        twiz_view_id = "edit";
        $("#twiz_anim_link_img_box_" + twiz_numid + "_" + twiz_view_level).show();
        $("#twiz_anim_link_img_box_" + twiz_numid + "_" + twiz_view_level).attr("class","twiz-save twiz-loading-gif-save");
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_EDIT.'",
        "twiz_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data){
            twizUnLockedAction();
            $("#twiz_anim_link_img_box_" + twiz_numid + "_" + twiz_view_level).fadeOut("fast");
            $("#twiz_container").html(data);
            twiz_Action_Edit_Copy_Rebind();
            $("#twiz_container").show("slow");
        }).fail(function(){ twizUnLockedAction(); });
    }else{twizLockedAction();}});
    $("[name^=twiz_anim_link]").hover(function(e){
        if(twiz_view_id != null){
        var twiz_textid = $(this).attr("id");
        var twiz_charid  = twiz_textid.substring(15,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];
        var twiz_view_level = twiz_charid[1];
        if($("#twiz_list_tr_" + twiz_numid).attr("class") != "twiz-list-tr twiz-row-color-3 ui-draggable"){
            twiz_orig_anim_link_class = $("#twiz_list_tr_" + twiz_numid).attr("class");
        }
        $("#twiz_list_tr_" + twiz_numid).attr({"class" : "twiz-list-tr twiz-row-color-3 ui-draggable"});
        var twiz_ok = twizGetView(twiz_numid, e, twiz_view_level);
        }
    },function(e){
        if(twiz_view_id != null){
        var twiz_textid = $(this).attr("id");
        var twiz_charid  = twiz_textid.substring(15,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];
        $("#twiz_list_tr_" + twiz_numid).attr({"class" : twiz_orig_anim_link_class});
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "hidden");
        }
    });
    $("[name^=twiz_group_anim_link]").click(function(){
     if(twiz_ajax_locked == false){ 
        twiz_ajax_locked = true;
        twizShowMainLoadingImage();
        var twiz_toggle_status = 0;
        var twiz_textid = $(this).attr("name");
        var twiz_charid  = twiz_textid.substring(21,twiz_textid.length).split("_");
        var twiz_src = $("[id^=twiz_group_img_" + twiz_charid[0] + "]").attr("class");
        if(twiz_src.indexOf("twiz-plus") != -1){
            $("[id^=twiz_group_img_" + twiz_charid[0] + "]").removeClass("twiz-plus").addClass("twiz-minus");
            $("[id^=twiz_element_a_" + twiz_parent_id + "]").attr("class","twiz-toggle-group");
            $("[id^=twiz_element_a_" + twiz_charid[0] + "]").attr("class","twiz-toggle-group twiz-bold");
            $("[name^=twiz_group_anim_link_" + twiz_charid[0] + "_" + twiz_charid[1] +"]").attr("class","twiz-toggle-group twiz-bold");
            $("." + twiz_charid[0]).removeClass("twiz-display-none");
            twiz_toggle_status = 1;
            twiz_parent_id = twiz_charid[0];
        }else{
            $("[id^=twiz_group_img_" + twiz_charid[0] + "]").removeClass("twiz-minus").addClass("twiz-plus");
            $("[id^=twiz_element_a_" + twiz_charid[0] + "]").attr("class","twiz-toggle-group");
            $("[name^=twiz_group_anim_link_" + twiz_charid[0] + "_" + twiz_charid[1] +"]").attr("class","twiz-toggle-group");
            if(twiz_charid[0]==twiz_parent_id){
            $("[id^=twiz_element_a_" + twiz_parent_id + "]").attr("class","twiz-toggle-group");
            twiz_parent_id = "";
            }
            $("." + twiz_charid[0]).addClass("twiz-display-none");
        }
        twiz_array_view_id = new Array();
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_TOGGLE.'",
        "twiz_toggle_status": twiz_toggle_status,
        "twiz_toggle_type": "'.parent::KEY_TOGGLE_GROUP.'",
        "twiz_charid": twiz_charid[0]
        }, function(data){ twizUnLockedAction(); twizHideMainLoadingImage();}).fail(function(){ twizUnLockedAction(); });
     }else{twizLockedAction();}
    });
    $("[name^=twiz_group_anim_link]").hover(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_charid  = twiz_textid.substring(21,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];
        var twiz_view_level = twiz_charid[1];
        twiz_view_level--;
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");
        if($("#twiz_list_group_tr_" + twiz_numid).attr("class") != "twiz-list-tr twiz-row-color-3 ui-draggable"){
            twiz_group_orig_anim_link_class = $("#twiz_list_group_tr_" + twiz_numid).attr("class");
        }        
        $("#twiz_list_group_tr_" + twiz_numid).attr({"class" : "twiz-list-tr twiz-row-color-3 ui-droppable"});
        $(".twiz-right-panel").css("display", "none");
        for(var twiz_i = 1;twiz_i <= twiz_view_level;twiz_i++){ 
            $("#twiz_right_panel_" + twiz_i).css("display", "block");
        }  
    },function(){
        var twiz_textid = $(this).attr("name");
        var twiz_charid  = twiz_textid.substring(21,twiz_textid.length).split("_");
        var twiz_numid = twiz_charid[0];
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "hidden");
        $("#twiz_list_group_tr_" + twiz_numid).attr({"class" : twiz_group_orig_anim_link_class});
    });
  }
  function twiz_reset_nav(){
     $(".twiz-right-panel").fadeOut("fast");
     $(".twiz-list-tr-action").css("visibility", "hidden");
  }
  $(".twiz-reset-nav").mouseover(function(){
     twiz_reset_nav();
  });
  $("#twiz_more_menu").click(function(){   
     $("#twiz_vertical_menu_box").toggle();
  });
  $("#twiz_head_logo").click(function (){   
    if(twiz_ajax_locked == false){ 
    if($("#twiz_skin_bullet").css("top")=="22px"){
        twiz_ajax_locked = true;
        $("#twiz_skin_bullet").stop().animate({top:"-=22px"},450,"swing", function(){
        $("#twiz_skin_bullet").css("z-index","0");
        });
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_BULLET_UP.'"
        }, function(data){ 
            twizUnLockedAction(); 
        }).fail(function(){ twizUnLockedAction(); }); ;
    }
    if($("#twiz_skin_bullet").css("top")=="0px"){
        twiz_ajax_locked = true;
        $("#twiz_skin_bullet").css("z-index","-1");
        $("#twiz_skin_bullet").stop().animate({top:"+=22px"},300,"swing", function(){
        });
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": twiz_nonce, 
        "twiz_action": "'.parent::ACTION_BULLET_DOWN.'"
        }, function(data){ 
            twizUnLockedAction(); 
        }).fail(function(){ twizUnLockedAction(); });
    }    
  }else{twizLockedAction();}});
  $(".twiz-skins").click(function(){   
      if(twiz_ajax_locked == false){ 
      twiz_ajax_locked = true;
      twizShowMainLoadingImage();
      var twiz_textid = $(this).attr("id");
      var twiz_skinname = twiz_textid.substring(10, twiz_textid.length);
      var twiz_css_skin = "'.$this->pluginUrl. parent::SKIN_PATH.'" + twiz_skinname + "/twiz-style.css?version='.$this->cssVersion.'";
      $("#twiz-'.$this->cssVersion.'-a-css").attr("href",twiz_css_skin);
      twiz_skin = "'.parent::SKIN_PATH.'" + twiz_skinname;
      $.post(ajaxurl, {
      "action": "twiz_ajax_callback",
      "twiz_skin": twiz_skinname, 
      "twiz_nonce": twiz_nonce, 
      "twiz_action": "'.parent::ACTION_SAVE_SKIN.'"
      }, function(data){ 
          twizHideMainLoadingImage();  
          twizUnLockedAction(); 
      }).fail(function(){ twizUnLockedAction(); });
  }else{twizLockedAction();}}); 
  function twizLockedAction(){
     $("body").css("cursor", "progress");
  }
  function twizUnLockedAction(){
     twiz_ajax_locked = false;
     $("body").css("cursor", "default");
  }
  function twizDisplayVardump(){
     $("#twiz_var_dump").css({"display":"block","height":$(window).height()-105, "width":$(window).width()-720});     
  }  
  function twizGetVardump(twiz_effect){
      if(twiz_ajax_locked == false){ 
      twizDisplayVardump();
      if(twiz_effect == ""){twiz_effect = false;}
      if(twiz_effect == true){
        $("#twiz_var_dump").animate({opacity:0.6}, 0,  function(){});
      }
      twizShowMainLoadingImage();
      $.post(ajaxurl, {
      "action": "twiz_ajax_callback",
      "twiz_nonce": twiz_nonce, 
      "twiz_action": "'.parent::ACTION_GET_VAR_DUMP.'"
      }, function(data){ 
          $("#twiz_var_dump").html(data);
          $("#twiz_var_dump").animate({opacity:1}, 0,  function(){});
          twizHideMainLoadingImage();  
          twizUnLockedAction(); 
      });
  }else{twizLockedAction();}
  }
  $("#twiz_var_dump").click(function(){  
    twizGetVardump(true);
  });';
  
    $header .= '
$("#twiz_footer").html(\'<div\' + \' class="twiz-spacer-footer"></div>';
  
    $header .= '<a\' + \' href="http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/" target="_blank">'.__('Make a donation!', 'the-welcomizer').'</a>\');';
    
  if( ( $this->admin_option[parent::KEY_DISPLAY_VAR_DUMP] == true ) or ( TWIZ_FORCE_VARDUMP ==  true ) ){
  
  $header .= '
twizDisplayVardump();';

  }
 if( ( $this->network_activated == '1' ) and ( $this->override_network_settings != '1' ) ){
   $header .= '
$("#twiz_network_status").show();';
 }
  $header .= '
twizScrollTop();bind_twiz_Status();bind_twiz_ListMenu();bind_twiz_Edit();bind_twiz_Copy();bind_twiz_Delete();
  bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();bind_twiz_Choose_Options();
  bind_twiz_Order_by();bind_twiz_Ajax_TD();bind_twiz_TR_View();bind_twiz_Menu();
  bind_twiz_Save_Section();bind_twiz_FooterMenu();bind_twiz_FooterVMenu();
  $("#twiz_container").toggle();
  $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
  $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
  $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
 });';
       return $header;
    }
}?>