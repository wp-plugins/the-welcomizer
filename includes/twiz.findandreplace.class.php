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

require_once(dirname(__FILE__).'/twiz.group.class.php');
    
class TwizFindAndReplace extends Twiz{
  
    function __construct(){
    
        parent::__construct();
    }

    function getHtmlFormFindAndReplace( $section_id = '' ){
    
        $myTwizGroup  = new TwizGroup();
        $HTMLGroupList = $myTwizGroup->getHTMLGroupList( $section_id );
                
        $jsscript = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {';
 
        // Text Area auto expand 
        $jsscript .= '
textarea = new Object();
textarea.expand = function(textbox){
    twizsizeOrig(textbox);
    textbox.style.height = (textbox.scrollHeight + 20) + "px";
    textbox.style.width = (textbox.scrollWidth + 40) + "px";
} 
function twizsizeOrig(textbox){
    $(textbox).css({"z-index":10, "height":"50px", "width" : "160px"});
}
$("textarea[name^=twiz_javascript]").blur(function (){
   twizsizeOrig(this);
   $(this).css({"z-index":1});
});
$("textarea[name^=twiz_css]").blur(function (){
   twizsizeOrig(this);
   $(this).css({"z-index":1});
});
$("textarea[name^=twiz_options]").blur(function (){
   twizsizeOrig(this);
   $(this).css({"z-index":1});
});
 $("textarea[name^=twiz_extra]").blur(function (){
   twizsizeOrig(this);
   $(this).css({"z-index":1});
});';

        $jsscript .= '
$("[name^=twiz_listmenu]").css("display", "none");';
 
        $jsscript .= '});
 //]]>
</script>';
        $eventlist1 = $this->getHtmlEventList('','_far_1','');
        $eventlist2 = $this->getHtmlEventList('','_far_2','');
        
        $elementtypelist1 = $this->getHtmlElementTypeList('',self::F_TYPE, '_far_1' );
        $elementtypelist2 = $this->getHtmlElementTypeList('',self::F_TYPE, '_far_2' );
        
        // easing 
        $easing_a1 = $this->getHtmlEasingOptions('', parent::F_EASING_A, '_far_1');
        $easing_a2 = $this->getHtmlEasingOptions('', parent::F_EASING_A, '_far_2');
        $easing_b1 = $this->getHtmlEasingOptions('', parent::F_EASING_B, '_far_1');
        $easing_b2 = $this->getHtmlEasingOptions('', parent::F_EASING_B, '_far_2');

        $buttons = '<div class="twiz-clear"></div><div class="twiz-text-right twiz-td-save"> <span id="twiz_far_save_img_box_1" class="twiz-loading-gif-save"></span><a id="twiz_far_cancel_1">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_far_find" id="twiz_far_find_1" class="button-primary" value="'.__('Find', 'the-welcomizer').'"/> <input type="button" name="twiz_far_replace" id="twiz_far_replace_1" class="button-primary" value="'.__('Replace', 'the-welcomizer').'"/></div>';
            
        $choices = $buttons . ' <fieldset class="twiz-box-fieldset twiz-corner-all">
<legend>'.__('Method', 'the-welcomizer').'</legend>';

        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar0'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar0'] = '' ;
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar1'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar1'] = '' ;
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar12'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar12'] = '' ;
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar2'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar2'] = '' ;
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar3'])) $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar3'] = '' ;
                
        if(!isset($this->toggle_option[$this->user_id][parent::KEY_PREFERED_METHOD])) $this->toggle_option[$this->user_id][parent::KEY_PREFERED_METHOD] = '';
         
        if('twiz_far_precise' == $this->toggle_option[$this->user_id][parent::KEY_PREFERED_METHOD]) { 
            $twiz_far_precise =  ' checked="checked"';
            $twiz_far_simple =  '';
            $twiz_display_far_precise = '';
            $twiz_display_far_simple = ' twiz-display-none';
            
        }else{ //'twiz_far_simple'
        
            $twiz_far_simple =  ' checked="checked"';
            $twiz_far_precise =  '';
            $twiz_display_far_simple = '';
            $twiz_display_far_precise = ' twiz-display-none';
        }

        $choices .= '<input type="radio" id="twiz_far_choice_0" name="twiz_far_choice" class="twiz-output-choice" value="twiz_far_simple"'.$twiz_far_simple.'/> <label for="twiz_far_choice_0">'.__('Simple', 'the-welcomizer').'</label> ';
        
        $choices .= '<input type="radio" id="twiz_far_choice_1" name="twiz_far_choice" class="twiz-output-choice" value="twiz_far_precise"'.$twiz_far_precise.'/> <label for="twiz_far_choice_1">'.__('Precise', 'the-welcomizer').'</label> ';
     
        $choices .= '</fieldset>';
        
        $grouplist = '<div>'.__('Group name', 'the-welcomizer').': '.$HTMLGroupList.'</div><div class="twiz-spacer"></div>';
        
        $form = $choices . $grouplist.'<table class="twiz-table-far'.$twiz_display_far_simple.'" id="twiz_far_simple" name="twiz_far_table" cellspacing="0" cellpadding="0">
<tr class="twiz-table-list-tr-h"><td class="twiz-form-td-left twiz-text-right twiz-bold">'.__('Find', 'the-welcomizer').'</td>
<td class="twiz-form-td-left twiz-bold">'.__('Replace', 'the-welcomizer').'</td></tr>
       
<tr class="twiz-row-color-2"><td class="twiz-form-td-left">'.__('Everywhere', 'the-welcomizer').': <div class="twiz-float-right"><input class="twiz-input-far twiz-input-focus" id="twiz_far_everywhere_1" name="="twiz_far_everywhere_1" type="text" value="" maxlength="50"/></div></td><td class="twiz-form-td-left"><input class="twiz-input-far twiz-input-focus" id="twiz_far_everywhere_2" name="="twiz_far_everywhere_2" type="text" value="" maxlength="50"/></td></tr>

<tr><td colspan="2">&nbsp;</td></tr>
</table>';

        $form .= '<table id="twiz_far_precise" name="twiz_far_table" class="twiz-table-far'.$twiz_display_far_precise.'" cellspacing="0" cellpadding="0">
<tr class="twiz-table-list-tr-h"><td class="twiz-form-td-left twiz-text-right twiz-bold">'.__('Find', 'the-welcomizer').'</td>
<td class="twiz-form-td-left twiz-bold">'.__('Replace', 'the-welcomizer').'</td></tr>

<tr class="twiz-row-color-2"><td class="twiz-form-td-left">'.__('Status', 'the-welcomizer').': <div class="twiz-float-right"><input id="twiz_'.parent::F_STATUS.'_far_1" name="twiz_'.parent::F_STATUS.'_far_1" type="checkbox" value="" maxlength="50"/></div></td><td class="twiz-form-td-left"><input id="twiz_'.parent::F_STATUS.'_far_2" name="twiz_'.parent::F_STATUS.'_far_2" type="checkbox" value="" maxlength="50"/></td></tr>

<tr class="twiz-row-color-2"><td class="twiz-form-td-left">'.__('Event', 'the-welcomizer').': <div class="twiz-float-right">'.$eventlist1.'</div></td>
<td class="twiz-form-td-right twiz-text-left">'.$eventlist2.'</td></tr>

<tr class="twiz-row-color-2"><td class="twiz-form-td-left">'.__('Element type', 'the-welcomizer').': <div class="twiz-float-right">'.$elementtypelist1.'</div></td><td class="twiz-form-td-left">'.$elementtypelist2.'</td></tr>

<tr class="twiz-row-color-2"><td class="twiz-form-td-left">'.__('Element', 'the-welcomizer').': <div class="twiz-float-right"><input class="twiz-input-far twiz-input-focus" id="twiz_'.parent::F_LAYER_ID.'_far_1" name="twiz_'.parent::F_LAYER_ID.'_far_1" type="text" value="" maxlength="50"/></div></td><td class="twiz-form-td-left"><input class="twiz-input-far twiz-input-focus" id="twiz_'.parent::F_LAYER_ID.'_far_2" name="twiz_'.parent::F_LAYER_ID.'_far_2" type="text" value="" maxlength="50"/></td></tr>

<tr class="twiz-row-color-2"><td class="twiz-form-td-left">'.__('Delay', 'the-welcomizer').': <div class="twiz-float-right"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.parent::F_START_DELAY.'_far_1" name="twiz_'.parent::F_START_DELAY.'_far_1" type="text" value="" maxlength="50"/></div></td><td class="twiz-form-td-left"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.parent::F_START_DELAY.'_far_2" name="twiz_'.parent::F_START_DELAY.'_far_2" type="text" value="" maxlength="50"/></td></tr>

<tr class="twiz-row-color-2"><td class="twiz-form-td-left">'.__('Duration', 'the-welcomizer').': <div class="twiz-float-right"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.parent::F_DURATION.'_far_1" name="twiz_'.parent::F_DURATION.'_far_1" type="text" value="" maxlength="50"/></div></td><td class="twiz-form-td-left"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.parent::F_DURATION.'_far_2" name="twiz_'.parent::F_DURATION.'_far_2" type="text" value="" maxlength="50"/></td></tr><tr class="twiz-row-color-2"><td class="twiz-form-td-left">'.__('Second duration', 'the-welcomizer').': <div class="twiz-float-right"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.parent::F_DURATION_B.'_far_1" name="twiz_'.parent::F_DURATION_B.'_far_1" type="text" value="" maxlength="50"/></div></td><td class="twiz-form-td-left"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.parent::F_DURATION_B.'_far_2" name="twiz_'.parent::F_DURATION_B.'_far_2" type="text" value="" maxlength="50"/></td></tr>';

    if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar0'] == '1' ){

        $hide = '';
        $toggleimg = 'twiz-minus';
        $boldclass = ' twiz-bold';

    }else{

        $hide = ' twiz-display-none';
        $toggleimg = 'twiz-plus';
        $boldclass = '';
    }
        
$form .= '<tr class="twiz-row-color-1"><td class="twiz-form-td-left twiz-border-bottom" colspan="2"><div class="twiz-relative"><div id="twiz_far_img_twizfar0" name="twiz_far_img_twizfar0" class="twiz-toggle-far twiz-toggle-img-far '.$toggleimg.'"></div></div><a id="twiz_far_e_a_twizfar0" name="twiz_far_e_a_twizfar0" class="twiz-toggle-far'.$boldclass.'">'.__('Starting Positions', 'the-welcomizer').'</a></td></tr>
<tr class="twiz-row-color-2 twizfar0'.$hide.'"><td class="twiz-form-td-left">'.__('Output', 'the-welcomizer').': <div class="twiz-float-right"><select name="twiz_'.parent::F_OUTPUT_POS.'_far_1" id="twiz_'.parent::F_OUTPUT_POS.'_far_1">
        <option value=""></option>
        <option value="c">'.__('CSS Styles', 'the-welcomizer').'</option> 
        <option value="r">'.__('onReady', 'the-welcomizer').'</option>
        <option value="b">'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a">'.__('After the delay', 'the-welcomizer').'</option>
        </select></div></td><td class="twiz-form-td-left"><select name="twiz_'.parent::F_OUTPUT_POS.'_far_2" id="twiz_'.parent::F_OUTPUT_POS.'_far_2">
        <option value=""></option>
        <option value="c">'.__('CSS Styles', 'the-welcomizer').'</option> 
        <option value="r">'.__('onReady', 'the-welcomizer').'</option>
        <option value="b">'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a">'.__('After the delay', 'the-welcomizer').'</option>
        </select></td></tr>

        <tr class="twiz-row-color-2 twizfar0'.$hide.'"><td class="twiz-form-td-left">'.__('Element', 'the-welcomizer').': <div class="twiz-float-right"><input class="twiz-input-far twiz-input-focus" id="twiz_'.parent::F_START_ELEMENT.'_far_1" name="twiz_'.parent::F_START_ELEMENT.'_far_1" type="text" value="" maxlength="50"/></div></td><td class="twiz-form-td-left"><input class="twiz-input-far twiz-input-focus" id="twiz_'.parent::F_START_ELEMENT.'_far_2" name="twiz_'.parent::F_START_ELEMENT.'_far_2" type="text" value="" maxlength="50"/></td></tr>

        
<tr class="twiz-row-color-2 twizfar0'.$hide.'"><td class="twiz-form-td-left">'.__('Top', 'the-welcomizer').': <div class="twiz-float-right"><select name="twiz_'.parent::F_START_TOP_POS_SIGN.'_far_1" id="twiz_'.parent::F_START_TOP_POS_SIGN.'_far_1">
                <option value="">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_START_TOP_POS.'_far_1" name="twiz_'.parent::F_START_TOP_POS.'_far_1" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_START_TOP_POS_FORMAT, '','far_1').'</div></td><td class="twiz-form-td-left"><select name="twiz_'.parent::F_START_TOP_POS_SIGN.'_far_2" id="twiz_'.parent::F_START_TOP_POS_SIGN.'_far_2">
                <option value="">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_START_TOP_POS.'_far_2" name="twiz_'.parent::F_START_TOP_POS.'_far_2" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_START_TOP_POS_FORMAT, '','far_2').'</td></tr>

<tr class="twiz-row-color-2 twizfar0'.$hide.'"><td class="twiz-form-td-left">'.__('Left', 'the-welcomizer').': <div class="twiz-float-right"><select name="twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_1" id="twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_1">
                <option value="">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_START_LEFT_POS.'_far_1" name="twiz_'.parent::F_START_LEFT_POS.'_far_1" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_START_LEFT_POS_FORMAT, '','far_1').'</div></td><td class="twiz-form-td-left"><select name="twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_2" id="twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_2">
                <option value="">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_START_LEFT_POS.'_far_2" name="twiz_'.parent::F_START_LEFT_POS.'_far_2" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_START_LEFT_POS_FORMAT, '','far_2').'</td></tr>


<tr class="twiz-row-color-2 twizfar0'.$hide.'"><td class="twiz-form-td-left">'.__('Position', 'the-welcomizer').': <div class="twiz-float-right"><select name="twiz_'.parent::F_POSITION.'_far_1" id="twiz_'.parent::F_POSITION.'_far_1">
        <option value="'.parent::POS_NO_POS.'"></option>
        <option value="'.parent::POS_ABSOLUTE.'">'.__('absolute', 'the-welcomizer').'</option>
        <option value="'.parent::POS_RELATIVE.'">'.__('relative', 'the-welcomizer').'</option>
        <option value="'.parent::POS_FIXED.'">'.__('fixed', 'the-welcomizer').'</option>
        <option value="'.parent::POS_STATIC.'">'.__('static', 'the-welcomizer').'</option>
        </select></div></td><td class="twiz-form-td-left"><select name="twiz_'.parent::F_POSITION.'_far_2" id="twiz_'.parent::F_POSITION.'_far_2">
        <option value="'.parent::POS_NO_POS.'"></option>
        <option value="'.parent::POS_ABSOLUTE.'">'.__('absolute', 'the-welcomizer').'</option>
        <option value="'.parent::POS_RELATIVE.'">'.__('relative', 'the-welcomizer').'</option>
        <option value="'.parent::POS_FIXED.'">'.__('fixed', 'the-welcomizer').'</option>
        <option value="'.parent::POS_STATIC.'">'.__('static', 'the-welcomizer').'</option>
        </select></td></tr>
        
<tr class="twiz-row-color-2 twizfar0'.$hide.'"><td class="twiz-form-td-left">'.__('z-index', 'the-welcomizer').': <div class="twiz-float-right"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.parent::F_ZINDEX.'_far_1" name="twiz_'.parent::F_ZINDEX.'_far_1" type="text" value="" maxlength="50"/></div></td><td class="twiz-form-td-left"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.parent::F_ZINDEX.'_far_2" name="twiz_'.parent::F_ZINDEX.'_far_2" type="text" value="" maxlength="50"/></td></tr>';

    if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar1'] == '1' ){

        $hide = '';
        $toggleimg = 'twiz-minus';
        $boldclass = ' twiz-bold';

    }else{

        $hide = ' twiz-display-none';
        $toggleimg = 'twiz-plus';
        $boldclass = '';
    }
                
$form .= '<tr class="twiz-row-color-1"><td class="twiz-form-td-left twiz-border-bottom" colspan="2"><div class="twiz-relative"><div id="twiz_far_img_twizfar1" name="twiz_far_img_twizfar1"  class="twiz-toggle-far twiz-toggle-img-far '.$toggleimg.'"></div></div><a id="twiz_far_e_a_twizfar1" name="twiz_far_e_a_twizfar1" class="twiz-toggle-far '.$boldclass.'">'.__('jQuery', 'the-welcomizer').'</a></td></tr>

<tr class="twiz-row-color-2 twizfar1'.$hide.'"><td class="twiz-form-td-left">'.__('Output', 'the-welcomizer').': <div class="twiz-float-right"><select name="twiz_'.parent::F_OUTPUT.'_far_1" id="twiz_'.parent::F_OUTPUT.'_far_1">
        <option value=""></option>
        <option value="r">'.__('onReady', 'the-welcomizer').'</option>
        <option value="b">'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a">'.__('After the delay', 'the-welcomizer').'</option>
        </select></div></td><td class="twiz-form-td-left"><select name="twiz_'.parent::F_OUTPUT.'_far_2" id="twiz_'.parent::F_OUTPUT.'_far_2">
        <option value=""></option>
        <option value="r">'.__('onReady', 'the-welcomizer').'</option>
        <option value="b">'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a">'.__('After the delay', 'the-welcomizer').'</option>
        </select></td></tr>
        
<tr class="twiz-row-color-2 twizfar1'.$hide.'"><td class="twiz-form-td-left">'.__('jQuery', 'the-welcomizer').': <div class="twiz-float-right"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_JAVASCRIPT.'_far_1" name="twiz_'.parent::F_JAVASCRIPT.'_far_1" type="text" ></textarea></div></div></td><td class="twiz-form-td-left"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_JAVASCRIPT.'_far_2" name="twiz_'.parent::F_JAVASCRIPT.'_far_2" type="text" ></textarea></div></td></tr>';

    if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar12'] == '1' ){

        $hide = '';
        $toggleimg = 'twiz-minus';
        $boldclass = ' twiz-bold';

    }else{

        $hide = ' twiz-display-none';
        $toggleimg = 'twiz-plus';
        $boldclass = '';
    }
                
$form .= '<tr class="twiz-row-color-1"><td class="twiz-form-td-left twiz-border-bottom" colspan="2"><div class="twiz-relative"><div id="twiz_far_img_twizfar12" name="twiz_far_img_twizfar12" class="twiz-toggle-far twiz-toggle-img-far '.$toggleimg.'"></div></div><a id="twiz_far_e_a_twizfar12" name="twiz_far_e_a_twizfar12" class="twiz-toggle-far '.$boldclass.'">'.__('Extra CSS', 'the-welcomizer').'</a></td></tr>
<tr class="twiz-row-color-2 twizfar12'.$hide.'"><td class="twiz-form-td-left">'.__('Extra CSS', 'the-welcomizer').': <div class="twiz-float-right"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_CSS.'_far_1" name="twiz_'.parent::F_CSS.'_far_1" type="text" ></textarea></div></div></td><td class="twiz-form-td-left"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_CSS.'_far_2" name="twiz_'.parent::F_CSS.'_far_2" type="text" ></textarea></div></td></tr>';
                
    if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar2'] == '1' ){

        $hide = '';
        $toggleimg = 'twiz-minus';
        $boldclass = ' twiz-bold';

    }else{

        $hide = ' twiz-display-none';
        $toggleimg = 'twiz-plus';
        $boldclass = '';
    }
    
$form .= '<tr class="twiz-row-color-1"><td class="twiz-form-td-left twiz-border-bottom" colspan="2"><div class="twiz-relative"><div id="twiz_far_img_twizfar2" name="twiz_far_img_twizfar2" class="twiz-toggle-far twiz-toggle-img-far '.$toggleimg.'"></div></div></div><a id="twiz_far_e_a_twizfar2" name="twiz_far_e_a_twizfar2" class="twiz-toggle-far'.$boldclass.'">'.__('First Move', 'the-welcomizer').'</a></td></tr>

<tr class="twiz-row-color-2 twizfar2'.$hide.'"><td class="twiz-form-td-left">'.__('Easing', 'the-welcomizer').': <div class="twiz-float-right">'.$easing_a1.'</div></td><td class="twiz-form-td-left">'.$easing_a2.'</td></tr>

<tr class="twiz-row-color-2 twizfar2'.$hide.'"><td class="twiz-form-td-left">'.__('Element', 'the-welcomizer').': <div class="twiz-float-right"><input class="twiz-input-far twiz-input-focus" id="twiz_'.parent::F_MOVE_ELEMENT_A.'_far_1" name="twiz_'.parent::F_MOVE_ELEMENT_A.'_far_1" type="text" value="" maxlength="50"/></div></td><td class="twiz-form-td-left"><input class="twiz-input-far twiz-input-focus" id="twiz_'.parent::F_MOVE_ELEMENT_A.'_far_2" name="twiz_'.parent::F_MOVE_ELEMENT_A.'_far_2" type="text" value="" maxlength="50"/></td></tr>
        
<tr class="twiz-row-color-2 twizfar2'.$hide.'"><td class="twiz-form-td-left">'.__('Top', 'the-welcomizer').': <div class="twiz-float-right"><select name="twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_1" id="twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_1">
                <option value=""></option>
                <option value="+">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_MOVE_TOP_POS_A.'_far_1" name="twiz_'.parent::F_MOVE_TOP_POS_A.'_far_1" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_MOVE_TOP_POS_FORMAT_A, '','far_1').'</div></td><td class="twiz-form-td-left"><select name="twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_2" id="twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_2">
                <option value=""></option>
                <option value="+">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_MOVE_TOP_POS_A.'_far_2" name="twiz_'.parent::F_MOVE_TOP_POS_A.'_far_2" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_MOVE_TOP_POS_FORMAT_A, '','far_2').'</td></tr>

<tr class="twiz-row-color-2 twizfar2'.$hide.'"><td class="twiz-form-td-left">'.__('Left', 'the-welcomizer').': <div class="twiz-float-right"><select name="twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_1" id="twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_1">
                <option value=""></option>
                <option value="+">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_1" name="twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_1" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_MOVE_LEFT_POS_FORMAT_A, '','far_1').'</div></td><td class="twiz-form-td-left"><select name="twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_2" id="twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_2">
                <option value=""></option>
                <option value="+">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_2" name="twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_2" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_MOVE_LEFT_POS_FORMAT_A, '','far_2').'</td></tr>

<tr class="twiz-row-color-2 twizfar2'.$hide.'"><td class="twiz-form-td-left">'.__('Options', 'the-welcomizer').': <div class="twiz-float-right"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_OPTIONS_A.'_far_1" name="twiz_'.parent::F_OPTIONS_A.'_far_1" type="text" ></textarea></div></div></td><td class="twiz-form-td-left"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_OPTIONS_A.'_far_2" name="twiz_'.parent::F_OPTIONS_A.'_far_2" type="text" ></textarea></div></td></tr>
                
<tr class="twiz-row-color-2 twizfar2'.$hide.'"><td class="twiz-form-td-left">'.__('Extra jQuery', 'the-welcomizer').': <div class="twiz-float-right"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_EXTRA_JS_A.'_far_1" name="twiz_'.parent::F_EXTRA_JS_A.'_far_1" type="text" ></textarea></div></div></td><td class="twiz-form-td-left"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_EXTRA_JS_A.'_far_2" name="twiz_'.parent::F_EXTRA_JS_A.'_far_2" type="text" ></textarea></div></td></tr>';

   if( $this->toggle_option[$this->user_id][parent::KEY_TOGGLE_FAR]['twizfar3'] == '1' ){

        $hide = '';
        $toggleimg = 'twiz-minus';
        $boldclass = ' twiz-bold';

    }else{

        $hide = ' twiz-display-none';
        $toggleimg = 'twiz-plus';
        $boldclass = '';
    }
    
$form .= '<tr class="twiz-row-color-1"><td class="twiz-form-td-left twiz-border-bottom" colspan="2"><div class="twiz-relative"><div id="twiz_far_img_twizfar3" name="twiz_far_img_twizfar3" class="twiz-toggle-far twiz-toggle-img-far '.$toggleimg.'"></div></div><a id="twiz_far_e_a_twizfar3" name="twiz_far_e_a_twizfar3" class="twiz-toggle-far'.$boldclass.'">'.__('Second Move', 'the-welcomizer').'</a></td></tr>

<tr class="twiz-row-color-2 twizfar3'.$hide.'"><td class="twiz-form-td-left">'.__('Easing', 'the-welcomizer').': <div class="twiz-float-right">'.$easing_b1.'</div></td><td class="twiz-form-td-left">'.$easing_b2.'</td></tr>

<tr class="twiz-row-color-2 twizfar3'.$hide.'"><td class="twiz-form-td-left">'.__('Element', 'the-welcomizer').': <div class="twiz-float-right"><input class="twiz-input-far twiz-input-focus" id="twiz_'.parent::F_MOVE_ELEMENT_B.'_far_1" name="twiz_'.parent::F_MOVE_ELEMENT_B.'_far_1" type="text" value="" maxlength="50"/></div></td><td class="twiz-form-td-left"><input class="twiz-input-far twiz-input-focus" id="twiz_'.parent::F_MOVE_ELEMENT_B.'_far_2" name="twiz_'.parent::F_MOVE_ELEMENT_B.'_far_2" type="text" value="" maxlength="50"/></td></tr>

<tr class="twiz-row-color-2 twizfar3'.$hide.'"><td class="twiz-form-td-left">'.__('Top', 'the-welcomizer').': <div class="twiz-float-right"><select name="twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_1" id="twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_1">
                <option value=""></option>
                <option value="+">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_MOVE_TOP_POS_B.'_far_1" name="twiz_'.parent::F_MOVE_TOP_POS_B.'_far_1" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_MOVE_TOP_POS_FORMAT_B, '','far_1').'</div></td><td class="twiz-form-td-left"><select name="twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_2" id="twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_2">
                <option value=""></option>
                <option value="+">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_MOVE_TOP_POS_B.'_far_2" name="twiz_'.parent::F_MOVE_TOP_POS_B.'_far_2" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_MOVE_TOP_POS_FORMAT_B, '','far_2').'</td></tr>

<tr class="twiz-row-color-2 twizfar3'.$hide.'"><td class="twiz-form-td-left">'.__('Left', 'the-welcomizer').': <div class="twiz-float-right"><select name="twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_1" id="twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_1">
                <option value=""></option>
                <option value="+">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_1" name="twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_1" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_MOVE_LEFT_POS_FORMAT_B, '','far_1').'</div></td><td class="twiz-form-td-left"><select name="twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_2" id="twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_2">
                <option value=""></option>
                <option value="+">+</option>
                <option value="-">-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_2" name="twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_2" type="text" value="" maxlength="50"/>'.$this->getHtmlFormatList(parent::F_MOVE_LEFT_POS_FORMAT_B, '','far_2').'</td></tr>

<tr class="twiz-row-color-2 twizfar3'.$hide.'"><td class="twiz-form-td-left">'.__('Options', 'the-welcomizer').': <div class="twiz-float-right"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_OPTIONS_B.'_far_1" name="twiz_'.parent::F_OPTIONS_B.'_far_1" type="text" ></textarea></div></div></td><td class="twiz-form-td-left"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_OPTIONS_B.'_far_2" name="twiz_'.parent::F_OPTIONS_B.'_far_2" type="text" ></textarea></div></td></tr>

<tr class="twiz-row-color-2 twizfar3'.$hide.'"><td class="twiz-form-td-left">'.__('Extra jQuery', 'the-welcomizer').': <div class="twiz-float-right"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_EXTRA_JS_B.'_far_1" name="twiz_'.parent::F_EXTRA_JS_B.'_far_1" type="text" ></textarea></div></div></td><td class="twiz-form-td-left"><div class="twiz-wrap-input-large twiz-wrap-input-large-far"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input-far twiz-input-large twiz-input-focus" id="twiz_'.parent::F_EXTRA_JS_B.'_far_2" name="twiz_'.parent::F_EXTRA_JS_B.'_far_2" type="text" ></textarea></div></td></tr>

<tr><td colspan="2">&nbsp;</td></tr>
</table>';

        $form .= '<div class="twiz-clear"></div><div class="twiz-text-right twiz-td-save"> <span id="twiz_far_save_img_box_2" class="twiz-loading-gif-save"></span><a id="twiz_far_cancel_2">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_far_find" id="twiz_far_find_2" class="button-primary" value="'.__('Find', 'the-welcomizer').'"/> <input type="button" name="twiz_far_replace" id="twiz_far_replace_2" class="button-primary" value="'.__('Replace', 'the-welcomizer').'"/></div>';

        $html = '<div class="twiz-box-menu">'.$form.'</div>'.$jsscript;
       
        return $html;
    }
    
    function find( $section_id = '', $group_id = '' ){
    
        $choice = esc_attr(trim($_POST['twiz_far_choice']));
        $where = ' where ';
        $wheresql = '';
        $open = '((';
        $or = '';
        
        switch( $choice ){
        
            case 'twiz_far_simple' :

                $everywhere_1 = esc_attr(trim($_POST['twiz_far_everywhere_1']));

                if( $everywhere_1 != '' ){
                    $wheresql .= $where. $open .parent::F_ON_EVENT." = '".$everywhere_1."'";
                    $or = ' or ';
        //          $wheresql .= $or .parent::F_STATUS." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_TYPE." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_LAYER_ID." LIKE '%".$everywhere_1."%'";
                    $wheresql .= $or .parent::F_START_DELAY." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_DURATION." = '".$everywhere_1."' ";
                    $wheresql .= $or .parent::F_DURATION_B." = '".$everywhere_1."' ";
                    $wheresql .= $or .parent::F_OUTPUT_POS." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_START_ELEMENT." LIKE '%".$everywhere_1."%'";
        //          $wheresql .= $or .parent::F_START_TOP_POS_SIGN." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_START_TOP_POS." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_START_TOP_POS_FORMAT." = '".$everywhere_1."'";
        //          $wheresql .= $or .parent::F_START_LEFT_POS_SIGN." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_START_LEFT_POS." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_START_LEFT_POS_FORMAT." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_POSITION." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_ZINDEX." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_OUTPUT." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_JAVASCRIPT." LIKE '%".$everywhere_1."%'";
                    $wheresql .= $or .parent::F_CSS." LIKE '%".$everywhere_1."%'";
                    $wheresql .= $or .parent::F_EASING_A." LIKE '%".$everywhere_1."%'";
                    $wheresql .= $or .parent::F_MOVE_ELEMENT_A." LIKE '%".$everywhere_1."%'";
        //          $wheresql .= $or .parent::F_MOVE_TOP_POS_SIGN_A." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_MOVE_TOP_POS_A." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_MOVE_TOP_POS_FORMAT_A." = '".$everywhere_1."'";
        //          $wheresql .= $or .parent::F_MOVE_LEFT_POS_SIGN_A." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_MOVE_LEFT_POS_A." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_MOVE_LEFT_POS_FORMAT_A." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_OPTIONS_A." LIKE '%".$everywhere_1."%'";
                    $wheresql .= $or .parent::F_EXTRA_JS_A." LIKE '%".$everywhere_1."%'";
                    $wheresql .= $or .parent::F_EASING_B." LIKE '%".$everywhere_1."%'";
                    $wheresql .= $or .parent::F_MOVE_ELEMENT_B." LIKE '%".$everywhere_1."%'";
        //          $wheresql .= $or .parent::F_MOVE_TOP_POS_SIGN_B." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_MOVE_TOP_POS_B." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_MOVE_TOP_POS_FORMAT_B." = '%".$everywhere_1."%'";
        //          $wheresql .= $or .parent::F_MOVE_LEFT_POS_SIGN_B." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_MOVE_LEFT_POS_B." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_MOVE_LEFT_POS_FORMAT_B." = '".$everywhere_1."'";
                    $wheresql .= $or .parent::F_OPTIONS_B." LIKE '%".$everywhere_1."%'";
                    $wheresql .= $or .parent::F_EXTRA_JS_B." LIKE '%".$everywhere_1."%'";
                }
                break;
                
            case 'twiz_far_precise' :
            
                $twiz_status = esc_attr(trim($_POST['twiz_'.parent::F_STATUS.'_far_1']));
                $twiz_status = ($twiz_status=='true') ? 1 : 0;
                $twiz_event = esc_attr(trim($_POST['twiz_'.parent::F_ON_EVENT.'_far_1']));
                $twiz_type = esc_attr(trim($_POST['twiz_'.parent::F_TYPE.'_far_1']));
                $twiz_layer_id = esc_attr(trim($_POST['twiz_'.parent::F_LAYER_ID.'_far_1']));
                $twiz_start_delay = esc_attr(trim($_POST['twiz_'.parent::F_START_DELAY.'_far_1']));
                $twiz_duration = esc_attr(trim($_POST['twiz_'.parent::F_DURATION.'_far_1']));
                $twiz_duration_b = esc_attr(trim($_POST['twiz_'.parent::F_DURATION_B.'_far_1']));
                $twiz_output_pos = esc_attr(trim($_POST['twiz_'.parent::F_OUTPUT_POS.'_far_1']));
                $twiz_start_element = esc_attr(trim($_POST['twiz_'.parent::F_START_ELEMENT.'_far_1']));
                $twiz_start_top_pos_sign = esc_attr(trim($_POST['twiz_'.parent::F_START_TOP_POS_SIGN.'_far_1']));
                $twiz_start_top_pos = esc_attr(trim($_POST['twiz_'.parent::F_START_TOP_POS.'_far_1']));
                $twiz_start_top_pos_format = esc_attr(trim($_POST['twiz_'.parent::F_START_TOP_POS_FORMAT.'_far_1']));
                $twiz_start_left_pos_sign = esc_attr(trim($_POST['twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_1']));
                $twiz_start_left_pos  = esc_attr(trim($_POST['twiz_'.parent::F_START_LEFT_POS.'_far_1']));
                $twiz_start_left_pos_format = esc_attr(trim($_POST['twiz_'.parent::F_START_LEFT_POS_FORMAT.'_far_1']));
                $twiz_position = esc_attr(trim($_POST['twiz_'.parent::F_POSITION.'_far_1']));
                $twiz_zindex = esc_attr(trim($_POST['twiz_'.parent::F_ZINDEX.'_far_1']));
                $twiz_output = esc_attr(trim($_POST['twiz_'.parent::F_OUTPUT.'_far_1']));
                $twiz_javascript = esc_attr(trim($_POST['twiz_'.parent::F_JAVASCRIPT.'_far_1']));
                $twiz_css = esc_attr(trim($_POST['twiz_'.parent::F_CSS.'_far_1']));
                $twiz_easing_a= esc_attr(trim($_POST['twiz_'.parent::F_EASING_A.'_far_1']));
                $twiz_move_element_a = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_ELEMENT_A.'_far_1']));
                $twiz_move_top_pos_sign_a = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_1']));
                $twiz_move_top_pos_a = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_A.'_far_1']));
                $twiz_move_top_pos_format_a = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_FORMAT_A.'_far_1']));
                $twiz_move_left_pos_sign_a = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_1']));
                $twiz_move_left_pos_a  = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_1']));
                $twiz_move_left_pos_format_a = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_A.'_far_1']));
                $twiz_options_a = esc_attr(trim($_POST['twiz_'.parent::F_OPTIONS_A.'_far_1']));
                $twiz_js_a = esc_attr(trim($_POST['twiz_'.parent::F_EXTRA_JS_A.'_far_1']));
                $twiz_easing_b = esc_attr(trim($_POST['twiz_'.parent::F_EASING_B.'_far_1']));
                $twiz_move_element_b = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_ELEMENT_B.'_far_1']));
                $twiz_move_top_pos_sign_b = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_1']));
                $twiz_move_top_pos_b = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_B.'_far_1']));
                $twiz_move_top_pos_format_b = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_FORMAT_B.'_far_1']));
                $twiz_move_left_pos_sign_b = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_1']));
                $twiz_move_left_pos_b  = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_1']));
                $twiz_move_left_pos_format_b = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_B.'_far_1']));
                $twiz_options_b = esc_attr(trim($_POST['twiz_'.parent::F_OPTIONS_B.'_far_1']));
                $twiz_js_b = esc_attr(trim($_POST['twiz_'.parent::F_EXTRA_JS_B.'_far_1']));
        
                                       
                if( $twiz_status == 1 ){
                    $wheresql.= $where.$or.$open.parent::F_STATUS." = '".$twiz_status."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_event != '' ){
                    $wheresql.= $where.$or.$open.parent::F_ON_EVENT." = '".$twiz_event."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_type != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_TYPE." = '".$twiz_type."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                
                if( $twiz_layer_id != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_LAYER_ID." LIKE '%".$twiz_layer_id."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_start_delay != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_START_DELAY." = '".$twiz_start_delay."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_duration != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_DURATION." = '".$twiz_duration."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                
                if( $twiz_duration_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_DURATION_B." = '".$twiz_duration_b."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_output_pos != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_OUTPUT_POS." = '".$twiz_output_pos."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }     
                if( $twiz_start_element != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_START_ELEMENT." LIKE '%".$twiz_start_element."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_start_top_pos_sign != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_START_TOP_POS_SIGN." = '".$twiz_start_top_pos_sign."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_start_top_pos != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_START_TOP_POS." = '".$twiz_start_top_pos."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_start_top_pos_format != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_START_TOP_POS_FORMAT." = '".$twiz_start_top_pos_format."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_start_left_pos_sign != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_START_LEFT_POS_SIGN." = '".$twiz_start_left_pos_sign."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                } 
                if( $twiz_start_left_pos != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_START_LEFT_POS." = '".$twiz_start_left_pos."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_start_left_pos_format != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_START_LEFT_POS_FORMAT." = '".$twiz_start_left_pos_format."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_position != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_POSITION." = '".$twiz_position."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }         
                if( $twiz_zindex != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_ZINDEX." = '".$twiz_zindex."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }       
                if( $twiz_output != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_OUTPUT." = '".$twiz_output."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                          
                if( $twiz_javascript != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_JAVASCRIPT." LIKE '%".$twiz_javascript."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                 
                if( $twiz_css != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_CSS." LIKE '%".$twiz_css."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                     
                if( $twiz_easing_a != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_EASING_A." LIKE '%".$twiz_easing_a."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }              
                if( $twiz_move_element_a != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_ELEMENT_A." LIKE '%".$twiz_move_element_a."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                
                if( $twiz_move_top_pos_sign_a != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_TOP_POS_SIGN_A." = '".$twiz_move_top_pos_sign_a."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                       
                if( $twiz_move_top_pos_a != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_TOP_POS_A." = '".$twiz_move_top_pos_a."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                       
                if( $twiz_move_top_pos_format_a != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_TOP_POS_FORMAT_A." = '".$twiz_move_top_pos_format_a."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                       
                if( $twiz_move_left_pos_sign_a != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_LEFT_POS_SIGN_A." = '".$twiz_move_left_pos_sign_a."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                     
                if( $twiz_move_left_pos_a != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_LEFT_POS_A." = '".$twiz_move_left_pos_a."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                        
                if( $twiz_move_left_pos_format_a != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_LEFT_POS_FORMAT_A." = '".$twiz_move_left_pos_format_a."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }     
                if( $twiz_options_a != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_OPTIONS_A." LIKE '%".$twiz_options_a."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }     
                if( $twiz_js_a != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_EXTRA_JS_A." LIKE '%".$twiz_js_a."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }   
                if( $twiz_easing_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_EASING_B." LIKE '%".$twiz_easing_b."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }
                if( $twiz_move_element_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_ELEMENT_B." LIKE '%".$twiz_move_element_b."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }  
                if( $twiz_move_top_pos_sign_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_TOP_POS_SIGN_B." = '".$twiz_move_top_pos_sign_b."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                        
                if( $twiz_move_top_pos_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_TOP_POS_B." = '".$twiz_move_top_pos_b."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                         
                if( $twiz_move_top_pos_format_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_TOP_POS_FORMAT_B." = '".$twiz_move_top_pos_format_b."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                         
                if( $twiz_move_left_pos_sign_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_LEFT_POS_SIGN_B." = '".$twiz_move_left_pos_sign_b."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                         
                if( $twiz_move_left_pos_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_LEFT_POS_B." = '".$twiz_move_left_pos_b."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }                        
                if( $twiz_move_left_pos_format_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_MOVE_LEFT_POS_FORMAT_B." = '".$twiz_move_left_pos_format_b."'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }    
                if( $twiz_options_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_OPTIONS_B." LIKE '%".$twiz_options_b."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }     
                if( $twiz_js_b != '' ){
                    $wheresql.=  $where.$or.$open.parent::F_EXTRA_JS_B." LIKE '%".$twiz_js_b."%'";
                    $or = ' or ';
                    $open = '';
                    $where = '';
                }   
        }
    
        $andgroupid = ( $group_id != '' )? ' and '.parent::F_PARENT_ID." = '".$group_id."'" : '';
        
        if( $or != '' ){
        
            $wheresql .= ") and (".parent::F_SECTION_ID." = '".$section_id."') and (".parent::F_TYPE." <> '".parent::ELEMENT_TYPE_GROUP."')".$andgroupid.")";
        
        }else{
        
            $wheresql = $where.parent::F_SECTION_ID." = '0'"; //empty query, no results
        }
        
        $listarray = $this->getListArray( $wheresql, '' );
        $count = count($listarray);

        if( $count == 0 ){ //reset query
             
            $wheresql = " where ".parent::F_SECTION_ID." = '".$section_id."' ";
      
            $listarray = $this->getListArray( $wheresql, '' );
            $html = $this->createHtmlList($listarray, '' );
            $jsonlistarray = json_encode( array('result' => $count, 'html'=>  $html ));
            return $jsonlistarray;
            
        }else{ 

            $html = $this->createHtmlList($listarray, '', '', parent::ACTION_FAR_FIND);
            $jsonlistarray = json_encode( array('result' => $count, 'html'=> $html )); // Exclude groups from results.
                
            return $jsonlistarray;
        }
        
    }  
    
    function replace( $section_id = '', $group_id = '' ){
    
        global $wpdb;
        
        $choice = esc_attr(trim($_POST['twiz_far_choice']));
        
        switch( $choice ){
        
            case 'twiz_far_simple' :
            
                $andgroupid = ( $group_id != '' )? ' and '.parent::F_PARENT_ID." = '".$group_id."'" : '';
            
                $everywhere_1 = esc_attr(trim($_POST['twiz_far_everywhere_1']));
                $everywhere_2 = esc_attr(trim($_POST['twiz_far_everywhere_2']));

                $updatesql = "UPDATE ".$this->table . " SET ";
                
                if( $everywhere_1 != '' ){
                
                    $updatesql .=  parent::F_ON_EVENT . " = replace(". parent::F_ON_EVENT . ", '".$everywhere_1."', '".$everywhere_2."')";
                //   $updatesql .= " , ". parent::F_STATUS . " = replace(". parent::F_STATUS . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_TYPE . " = replace(". parent::F_TYPE . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_LAYER_ID . " = replace(". parent::F_LAYER_ID . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_START_DELAY . " = replace(". parent::F_START_DELAY . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_DURATION . " = replace(". parent::F_DURATION . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_DURATION_B . " = replace(". parent::F_DURATION_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_OUTPUT_POS . " = replace(". parent::F_OUTPUT_POS . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_START_ELEMENT . " = replace(". parent::F_START_ELEMENT . ", '".$everywhere_1."', '".$everywhere_2."')";
                //   $updatesql .= " ,". parent::F_START_TOP_POS_SIGN . " = replace(". parent::F_START_TOP_POS_SIGN . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_START_TOP_POS . " = replace(". parent::F_START_TOP_POS . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_START_TOP_POS_FORMAT . " = replace(". parent::F_START_TOP_POS_FORMAT . ", '".$everywhere_1."', '".$everywhere_2."')";
                //   $updatesql .= " ,". parent::F_START_LEFT_POS_SIGN . " = replace(". parent::F_START_LEFT_POS_SIGN . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_START_LEFT_POS . " = replace(". parent::F_START_LEFT_POS . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_START_LEFT_POS_FORMAT . " = replace(". parent::F_START_LEFT_POS_FORMAT. ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_POSITION . " = replace(". parent::F_POSITION . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_ZINDEX . " = replace(". parent::F_ZINDEX . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_OUTPUT . " = replace(". parent::F_OUTPUT . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_CSS . " = replace(". parent::F_CSS . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_EASING_A . " = replace(". parent::F_EASING_A . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_MOVE_ELEMENT_A . " = replace(". parent::F_MOVE_ELEMENT_A . ", '".$everywhere_1."', '".$everywhere_2."')";
                //   $updatesql .= " ,". parent::F_MOVE_TOP_POS_SIGN_A . " = replace(". parent::F_MOVE_TOP_POS_SIGN_A . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_MOVE_TOP_POS_A . " = replace(". parent::F_MOVE_TOP_POS_A . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_MOVE_TOP_POS_FORMAT_A . " = replace(". parent::F_MOVE_TOP_POS_FORMAT_A . ", '".$everywhere_1."', '".$everywhere_2."')";
                //   $updatesql .= " ,". parent::F_MOVE_LEFT_POS_SIGN_A . " = replace(". parent::F_MOVE_LEFT_POS_SIGN_A . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_MOVE_LEFT_POS_A . " = replace(". parent::F_MOVE_LEFT_POS_A . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_MOVE_LEFT_POS_FORMAT_A . " = replace(". parent::F_MOVE_LEFT_POS_FORMAT_A . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_OPTIONS_A . " = replace(". parent::F_OPTIONS_A . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_EASING_B . " = replace(". parent::F_EASING_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_MOVE_ELEMENT_B . " = replace(". parent::F_MOVE_ELEMENT_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                //   $updatesql .= " ,". parent::F_MOVE_TOP_POS_SIGN_B . " = replace(". parent::F_MOVE_TOP_POS_SIGN_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_MOVE_TOP_POS_B . " = replace(". parent::F_MOVE_TOP_POS_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_MOVE_TOP_POS_FORMAT_B . " = replace(". parent::F_MOVE_TOP_POS_FORMAT_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                //   $updatesql .= " ,". parent::F_MOVE_LEFT_POS_SIGN_B . " = replace(". parent::F_MOVE_LEFT_POS_SIGN_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_MOVE_LEFT_POS_B . " = replace(". parent::F_MOVE_LEFT_POS_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_MOVE_LEFT_POS_FORMAT_B . " = replace(". parent::F_MOVE_LEFT_POS_FORMAT_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_OPTIONS_B . " = replace(". parent::F_OPTIONS_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", '".$everywhere_1."', '".$everywhere_2."')";
                    $updatesql .= " WHERE ". parent::F_SECTION_ID ." = '".$section_id."' and ".parent::F_TYPE." <> 'group'".$andgroupid;
          
                    $code = $wpdb->query($updatesql);
                    
                    return $code;
                    
                }else{
                
                   return 0;
                }
                
                break;
                
            case 'twiz_far_precise' :
                
                $andgroupid = ( $group_id != '' )? ' and '.parent::F_PARENT_ID." = '".$group_id."'" : '';
                
                $twiz_status_1 = esc_attr(trim($_POST['twiz_'.parent::F_STATUS.'_far_1']));
                $twiz_status_1 = ($twiz_status_1=='true') ? 1 : 0;
                $twiz_event_1 = esc_attr(trim($_POST['twiz_'.parent::F_ON_EVENT.'_far_1']));
                $twiz_type_1 = esc_attr(trim($_POST['twiz_'.parent::F_TYPE.'_far_1']));
                $twiz_layer_id_1 = esc_attr(trim($_POST['twiz_'.parent::F_LAYER_ID.'_far_1']));
                $twiz_start_delay_1 = esc_attr(trim($_POST['twiz_'.parent::F_START_DELAY.'_far_1']));
                $twiz_duration_1 = esc_attr(trim($_POST['twiz_'.parent::F_DURATION.'_far_1']));
                $twiz_duration_b_1 = esc_attr(trim($_POST['twiz_'.parent::F_DURATION_B.'_far_1']));
                $twiz_output_pos_1 = esc_attr(trim($_POST['twiz_'.parent::F_OUTPUT_POS.'_far_1']));
                $twiz_start_element_1 = esc_attr(trim($_POST['twiz_'.parent::F_START_ELEMENT.'_far_1']));
                $twiz_start_top_pos_sign_1 = esc_attr(trim($_POST['twiz_'.parent::F_START_TOP_POS_SIGN.'_far_1']));
                $twiz_start_top_pos_1 = esc_attr(trim($_POST['twiz_'.parent::F_START_TOP_POS.'_far_1']));
                $twiz_start_top_pos_format_1 = esc_attr(trim($_POST['twiz_'.parent::F_START_TOP_POS_FORMAT.'_far_1']));
                $twiz_start_left_pos_sign_1 = esc_attr(trim($_POST['twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_1']));
                $twiz_start_left_pos_1  = esc_attr(trim($_POST['twiz_'.parent::F_START_LEFT_POS.'_far_1']));
                $twiz_start_left_pos_format_1 = esc_attr(trim($_POST['twiz_'.parent::F_START_LEFT_POS_FORMAT.'_far_1']));
                $twiz_position_1 = esc_attr(trim($_POST['twiz_'.parent::F_POSITION.'_far_1']));
                $twiz_zindex_1 = esc_attr(trim($_POST['twiz_'.parent::F_ZINDEX.'_far_1']));
                $twiz_output_1 = esc_attr(trim($_POST['twiz_'.parent::F_OUTPUT.'_far_1']));
                $twiz_javascript_1 = esc_attr(trim($_POST['twiz_'.parent::F_JAVASCRIPT.'_far_1']));
                $twiz_css_1 = esc_attr(trim($_POST['twiz_'.parent::F_CSS.'_far_1']));
                $twiz_easing_a_1= esc_attr(trim($_POST['twiz_'.parent::F_EASING_A.'_far_1']));
                $twiz_move_element_a_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_ELEMENT_A.'_far_1']));
                $twiz_move_top_pos_sign_a_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_1']));
                $twiz_move_top_pos_a_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_A.'_far_1']));
                $twiz_move_top_pos_format_a_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_FORMAT_A.'_far_1']));
                $twiz_move_left_pos_sign_a_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_1']));
                $twiz_move_left_pos_a_1  = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_1']));
                $twiz_move_left_pos_format_a_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_A.'_far_1']));
                $twiz_options_a_1 = esc_attr(trim($_POST['twiz_'.parent::F_OPTIONS_A.'_far_1']));
                $twiz_js_a_1 = esc_attr(trim($_POST['twiz_'.parent::F_EXTRA_JS_A.'_far_1']));
                $twiz_easing_b_1 = esc_attr(trim($_POST['twiz_'.parent::F_EASING_B.'_far_1']));
                $twiz_move_element_b_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_ELEMENT_B.'_far_1']));
                $twiz_move_top_pos_sign_b_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_1']));
                $twiz_move_top_pos_b_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_B.'_far_1']));
                $twiz_move_top_pos_format_b_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_FORMAT_B.'_far_1']));
                $twiz_move_left_pos_sign_b_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_1']));
                $twiz_move_left_pos_b_1  = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_1']));
                $twiz_move_left_pos_format_b_1 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_B.'_far_1']));
                $twiz_options_b_1 = esc_attr(trim($_POST['twiz_'.parent::F_OPTIONS_B.'_far_1']));
                $twiz_js_b_1 = esc_attr(trim($_POST['twiz_'.parent::F_EXTRA_JS_B.'_far_1']));


                $twiz_status_2 = esc_attr(trim($_POST['twiz_'.parent::F_STATUS.'_far_2']));
                $twiz_status_2 = ($twiz_status_2=='true') ? 1 : 0;
                $twiz_event_2 = esc_attr(trim($_POST['twiz_'.parent::F_ON_EVENT.'_far_2']));
                $twiz_type_2 = esc_attr(trim($_POST['twiz_'.parent::F_TYPE.'_far_2']));
                $twiz_layer_id_2 = esc_attr(trim($_POST['twiz_'.parent::F_LAYER_ID.'_far_2']));
                $twiz_start_delay_2 = esc_attr(trim($_POST['twiz_'.parent::F_START_DELAY.'_far_2']));
                $twiz_duration_2 = esc_attr(trim($_POST['twiz_'.parent::F_DURATION.'_far_2']));
                $twiz_duration_b_2 = esc_attr(trim($_POST['twiz_'.parent::F_DURATION_B.'_far_2']));
                $twiz_output_pos_2 = esc_attr(trim($_POST['twiz_'.parent::F_OUTPUT_POS.'_far_2']));
                $twiz_start_element_2 = esc_attr(trim($_POST['twiz_'.parent::F_START_ELEMENT.'_far_2']));
                $twiz_start_top_pos_sign_2 = esc_attr(trim($_POST['twiz_'.parent::F_START_TOP_POS_SIGN.'_far_2']));
                $twiz_start_top_pos_2 = esc_attr(trim($_POST['twiz_'.parent::F_START_TOP_POS.'_far_2']));
                $twiz_start_top_pos_format_2 = esc_attr(trim($_POST['twiz_'.parent::F_START_TOP_POS_FORMAT.'_far_2']));
                $twiz_start_left_pos_sign_2 = esc_attr(trim($_POST['twiz_'.parent::F_START_LEFT_POS_SIGN.'_far_2']));
                $twiz_start_left_pos_2  = esc_attr(trim($_POST['twiz_'.parent::F_START_LEFT_POS.'_far_2']));
                $twiz_start_left_pos_format_2 = esc_attr(trim($_POST['twiz_'.parent::F_START_LEFT_POS_FORMAT.'_far_2']));
                $twiz_position_2 = esc_attr(trim($_POST['twiz_'.parent::F_POSITION.'_far_2']));
                $twiz_zindex_2 = esc_attr(trim($_POST['twiz_'.parent::F_ZINDEX.'_far_2']));
                $twiz_output_2 = esc_attr(trim($_POST['twiz_'.parent::F_OUTPUT.'_far_2']));
                $twiz_javascript_2 = esc_attr(trim($_POST['twiz_'.parent::F_JAVASCRIPT.'_far_2']));
                $twiz_css_2 = esc_attr(trim($_POST['twiz_'.parent::F_CSS.'_far_2']));
                $twiz_easing_a_2= esc_attr(trim($_POST['twiz_'.parent::F_EASING_A.'_far_2']));
                $twiz_move_element_a_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_ELEMENT_A.'_far_2']));
                $twiz_move_top_pos_sign_a_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.'_far_2']));
                $twiz_move_top_pos_a_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_A.'_far_2']));
                $twiz_move_top_pos_format_a_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_FORMAT_A.'_far_2']));
                $twiz_move_left_pos_sign_a_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.'_far_2']));
                $twiz_move_left_pos_a_2  = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_A.'_far_2']));
                $twiz_move_left_pos_format_a_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_A.'_far_2']));
                $twiz_options_a_2 = esc_attr(trim($_POST['twiz_'.parent::F_OPTIONS_A.'_far_2']));
                $twiz_js_a_2 = esc_attr(trim($_POST['twiz_'.parent::F_EXTRA_JS_A.'_far_2']));
                $twiz_easing_b_2 = esc_attr(trim($_POST['twiz_'.parent::F_EASING_B.'_far_2']));
                $twiz_move_element_b_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_ELEMENT_B.'_far_2']));
                $twiz_move_top_pos_sign_b_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.'_far_2']));
                $twiz_move_top_pos_b_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_B.'_far_2']));
                $twiz_move_top_pos_format_b_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_TOP_POS_FORMAT_B.'_far_2']));
                $twiz_move_left_pos_sign_b_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.'_far_2']));
                $twiz_move_left_pos_b_2  = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_B.'_far_2']));
                $twiz_move_left_pos_format_b_2 = esc_attr(trim($_POST['twiz_'.parent::F_MOVE_LEFT_POS_FORMAT_B.'_far_2']));
                $twiz_options_b_2 = esc_attr(trim($_POST['twiz_'.parent::F_OPTIONS_B.'_far_2']));
                $twiz_js_b_2 = esc_attr(trim($_POST['twiz_'.parent::F_EXTRA_JS_B.'_far_2']));
                        
                $updatesql = "UPDATE ".$this->table . " SET ";

                $updatesql .=  parent::F_ON_EVENT . " = replace(". parent::F_ON_EVENT . ", '".$twiz_event_1."', '".$twiz_event_2."')";
                $updatesql .= " , ". parent::F_STATUS . " = replace(". parent::F_STATUS . ", '".$twiz_status_1."', '".$twiz_status_2."')";
                $updatesql .= " ,". parent::F_TYPE . " = replace(". parent::F_TYPE . ", '".$twiz_type_1."', '".$twiz_type_2."')";
                $updatesql .= " ,". parent::F_LAYER_ID . " = replace(". parent::F_LAYER_ID . ", '".$twiz_layer_id_1."', '".$twiz_layer_id_2."')";
                $updatesql .= " ,". parent::F_START_DELAY . " = replace(". parent::F_START_DELAY . ", '".$twiz_start_delay_1."', '".$twiz_start_delay_2."')";
                $updatesql .= " ,". parent::F_DURATION . " = replace(". parent::F_DURATION . ", '".$twiz_duration_1."', '".$twiz_duration_2."')";
                $updatesql .= " ,". parent::F_DURATION_B . " = replace(". parent::F_DURATION_B . ", '".$twiz_duration_b_1."', '".$twiz_duration_b_2."')";
                $updatesql .= " ,". parent::F_OUTPUT_POS . " = replace(". parent::F_OUTPUT_POS . ", '".$twiz_output_pos_1."', '".$twiz_output_pos_2."')";
                $updatesql .= " ,". parent::F_START_ELEMENT . " = replace(". parent::F_START_ELEMENT . ", '".$twiz_start_element_1."', '".$twiz_start_element_2."')";
                $updatesql .= " ,". parent::F_START_TOP_POS_SIGN . " = replace(". parent::F_START_TOP_POS_SIGN . ", '".$twiz_start_top_pos_sign_1."', '".$twiz_start_top_pos_sign_2."')";
                $updatesql .= " ,". parent::F_START_TOP_POS . " = replace(". parent::F_START_TOP_POS . ", '".$twiz_start_top_pos_1."', '".$twiz_start_top_pos_2."')";
                $updatesql .= " ,". parent::F_START_TOP_POS_FORMAT . " = replace(". parent::F_START_TOP_POS_FORMAT . ", '".$twiz_start_top_pos_format_1."', '".$twiz_start_top_pos_format_2."')";
                $updatesql .= " ,". parent::F_START_LEFT_POS_SIGN . " = replace(". parent::F_START_LEFT_POS_SIGN . ", '".$twiz_start_left_pos_sign_1."', '".$twiz_start_left_pos_sign_2."')";
                $updatesql .= " ,". parent::F_START_LEFT_POS . " = replace(". parent::F_START_LEFT_POS . ", '".$twiz_start_left_pos_1."', '".$twiz_start_left_pos_2."')";
                $updatesql .= " ,". parent::F_START_LEFT_POS_FORMAT . " = replace(". parent::F_START_LEFT_POS_FORMAT. ", '".$twiz_start_left_pos_format_1."', '".$twiz_start_left_pos_format_2."')";
                $updatesql .= " ,". parent::F_POSITION . " = replace(". parent::F_POSITION . ", '".$twiz_position_1."', '".$twiz_position_2."')";
                $updatesql .= " ,". parent::F_ZINDEX . " = replace(". parent::F_ZINDEX . ", '".$twiz_zindex_1."', '".$twiz_zindex_2."')";
                $updatesql .= " ,". parent::F_OUTPUT . " = replace(". parent::F_OUTPUT . ", '".$twiz_output_1."', '".$twiz_output_2."')";
                $updatesql .= " ,". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", '".$twiz_javascript_1."', '".$twiz_javascript_2."')";
                $updatesql .= " ,". parent::F_CSS . " = replace(". parent::F_CSS . ", '".$twiz_css_1."', '".$twiz_css_2."')";
                $updatesql .= " ,". parent::F_EASING_A . " = replace(". parent::F_EASING_A . ", '".$twiz_easing_a_1."', '".$twiz_easing_a_2."')";
                $updatesql .= " ,". parent::F_MOVE_ELEMENT_A . " = replace(". parent::F_MOVE_ELEMENT_A . ", '".$twiz_move_element_a_1."', '".$twiz_move_element_a_2."')";
                $updatesql .= " ,". parent::F_MOVE_TOP_POS_SIGN_A . " = replace(". parent::F_MOVE_TOP_POS_SIGN_A . ", '".$twiz_move_top_pos_sign_a_1."', '".$twiz_move_top_pos_sign_a_2."')";
                $updatesql .= " ,". parent::F_MOVE_TOP_POS_A . " = replace(". parent::F_MOVE_TOP_POS_A . ", '".$twiz_move_top_pos_a_1."', '".$twiz_move_top_pos_a_2."')";
                $updatesql .= " ,". parent::F_MOVE_TOP_POS_FORMAT_A . " = replace(". parent::F_MOVE_TOP_POS_FORMAT_A . ", '".$twiz_move_top_pos_format_a_1."', '".$twiz_move_top_pos_format_a_2."')";
                $updatesql .= " ,". parent::F_MOVE_LEFT_POS_SIGN_A . " = replace(". parent::F_MOVE_LEFT_POS_SIGN_A . ", '".$twiz_move_left_pos_sign_a_1."', '".$twiz_move_left_pos_sign_a_2."')";
                $updatesql .= " ,". parent::F_MOVE_LEFT_POS_A . " = replace(". parent::F_MOVE_LEFT_POS_A . ", '".$twiz_move_left_pos_a_1."', '".$twiz_move_left_pos_a_2."')";
                $updatesql .= " ,". parent::F_MOVE_LEFT_POS_FORMAT_A . " = replace(". parent::F_MOVE_LEFT_POS_FORMAT_A . ", '".$twiz_move_left_pos_format_a_1."', '".$twiz_move_left_pos_format_a_2."')";
                $updatesql .= " ,". parent::F_OPTIONS_A . " = replace(". parent::F_OPTIONS_A . ", '".$twiz_options_a_1."', '".$twiz_options_a_2."')";
                $updatesql .= " ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", '".$twiz_js_a_1."', '".$twiz_js_a_2."')";
                $updatesql .= " ,". parent::F_EASING_B . " = replace(". parent::F_EASING_B . ", '".$twiz_easing_b_1."', '".$twiz_easing_b_2."')";
                $updatesql .= " ,". parent::F_MOVE_ELEMENT_B . " = replace(". parent::F_MOVE_ELEMENT_B . ", '".$twiz_move_element_b_1."', '".$twiz_move_element_b_2."')";
                $updatesql .= " ,". parent::F_MOVE_TOP_POS_SIGN_B . " = replace(". parent::F_MOVE_TOP_POS_SIGN_B . ", '".$twiz_move_top_pos_sign_b_1."', '".$twiz_move_top_pos_sign_b_2."')";
                $updatesql .= " ,". parent::F_MOVE_TOP_POS_B . " = replace(". parent::F_MOVE_TOP_POS_B . ", '".$twiz_move_top_pos_b_1."', '".$twiz_move_top_pos_b_2."')";
                $updatesql .= " ,". parent::F_MOVE_TOP_POS_FORMAT_B . " = replace(". parent::F_MOVE_TOP_POS_FORMAT_B . ", '".$twiz_move_top_pos_format_b_1."', '".$twiz_move_top_pos_format_b_2."')";
                $updatesql .= " ,". parent::F_MOVE_LEFT_POS_SIGN_B . " = replace(". parent::F_MOVE_LEFT_POS_SIGN_B . ", '".$twiz_move_left_pos_sign_b_1."', '".$twiz_move_left_pos_sign_b_2."')";
                $updatesql .= " ,". parent::F_MOVE_LEFT_POS_B . " = replace(". parent::F_MOVE_LEFT_POS_B . ", '".$twiz_move_left_pos_b_1."', '".$twiz_move_left_pos_b_2."')";
                $updatesql .= " ,". parent::F_MOVE_LEFT_POS_FORMAT_B . " = replace(". parent::F_MOVE_LEFT_POS_FORMAT_B . ", '".$twiz_move_left_pos_format_b_1."', '".$twiz_move_left_pos_format_b_2."')";
                $updatesql .= " ,". parent::F_OPTIONS_B . " = replace(". parent::F_OPTIONS_B . ", '".$twiz_options_b_1."', '".$twiz_options_b_2."')";
                $updatesql .= " ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", '".$twiz_js_b_2."', '".$twiz_js_b_2."')";
                $updatesql .= " WHERE ". parent::F_SECTION_ID ." = '".$section_id."' and ".parent::F_TYPE." <> 'group'".$andgroupid;
 
                $code = $wpdb->query($updatesql);
                
                return $code;
                    
                break;
        }
    }  
}?>