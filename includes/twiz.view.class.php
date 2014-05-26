<?php
/*  Copyright 2014  Sébastien Laframboise  (email:wordpress@sebastien-laframboise.com)

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
    
class TwizView extends Twiz{

                                     
    function __construct( ){
    
        parent::__construct();
    }    
    
    private function addViewLinks( $string = '', $listarray = array(), $level = 1 ){
        
        if( $string == ''){return '';}
        
        $searchstring = '';
        $level++;
        
        foreach( $listarray as $value ){

            $type = ($value[parent::F_TYPE] == parent::ELEMENT_TYPE_GROUP) ? '_'.parent::ELEMENT_TYPE_GROUP : '';
            
            // Anim & Group links
            $searchstring = "$(document).twiz".$type."_".$value[parent::F_SECTION_ID]."_".str_replace("-","_",sanitize_title_with_dashes($value[parent::F_LAYER_ID]))."_".$value[parent::F_EXPORT_ID]."();";
            $string = $this->replaceViewLinks($type, $level, $value, $searchstring, $string);
            
            // Bind/Unbind - Event links
            $searchstring = "twiz_event_".$value[parent::F_SECTION_ID]."_".str_replace("-","_",sanitize_title_with_dashes($value[parent::F_LAYER_ID]))."_".$value[parent::F_EXPORT_ID]."";
            $string = $this->replaceViewLinks($type, $level, $value, $searchstring, $string);
            
        }
        
        return $string;
    }
    
    private function replaceViewLinks($type = '', $level = '', $data = '', $searchstring = '', $string = ''){

            if(!isset($this->toggle_option[$this->userid][parent::KEY_TOGGLE_GROUP][$data[parent::F_EXPORT_ID]])) $this->toggle_option[$this->userid][parent::KEY_TOGGLE_GROUP][$data[parent::F_EXPORT_ID]] = '';
            
            if( $this->toggle_option[$this->userid][parent::KEY_TOGGLE_GROUP][$data[parent::F_EXPORT_ID]] == '1' ) {

                $boldclass = ' twiz-bold';
            
            }else{
            
                $boldclass = '';
            }
            
            $textstring = str_replace('$(document).', '', $searchstring);
            
            // Merge child links
            if( $data[parent::F_TYPE] == parent::ELEMENT_TYPE_GROUP ){
            
                $html = '<a id="twiz'.$type.'_anim_link_'.$data[parent::F_ID].'_'.$level.'" name="twiz'.$type.'_anim_link_'.$data[parent::F_EXPORT_ID].'_'.$level.'" class="twiz-anim-link'.$boldclass.'">'.$textstring.'</a>';
            
                $html .= $this->addGroupChildLinks($data[parent::F_EXPORT_ID], $level);
                
            }else{
            
                $html = '<span id="twiz'.$type.'_anim_link_img_box_'.$data[parent::F_ID].'_'.$level.'" name="twiz'.$type.'_anim_link_img_box" class="twiz-loading-gif"></span><a title="'.__('Edit', 'the-welcomizer').'" id="twiz'.$type.'_anim_link_'.$data[parent::F_ID].'_'.$level.'" name="twiz'.$type.'_anim_link_'.$data[parent::F_EXPORT_ID].'_'.$level.'" class="twiz-anim-link'.$boldclass.'">'.$textstring.'</a>';            
            }
            
            $string = str_replace($searchstring, $html, $string);    
            
            return $string;            
    
    }

    private function addLinkToImage( $value = '' ){
        
        $value = preg_replace('/((http:\/\/)(.*?)(\/uploads\/)(.*?)(.png|.jpg|.jpeg|.gif))/i', '<a href="\\0" class="twiz-view-image-link">\\5\\6</a>', $value);

        return $value;
    }
    
    private function addGroupChildLinks( $export_id = '', $level = '' ){
    
        $html = '';
        $open = '';
        
        // Get the list
        $where = " WHERE ".parent::F_PARENT_ID." = '".$export_id."' and ".parent::F_ON_EVENT." = '".parent::EV_MANUAL."'";
        $listchildarray = $this->getListArray( $where ); 
        
        if( $this->toggle_option[$this->userid][parent::KEY_TOGGLE_GROUP][$export_id] == '1' ) {
        
            $hide = '';
            $toggleimg = 'twiz-minus';
            $boldclass = ' twiz-bold';
            
        }else{

            $hide = ' twiz-display-none';
            $toggleimg = 'twiz-plus';
            $boldclass = '';
        }
        
        //Toggle group image
        $open .= '<div class="twiz-relative"><div id="twiz_group_img_'.$export_id.'" class="twiz-toggle-group twiz-toggle-img-view '.$toggleimg.'"></div></div>';
        
        $open .= '<div class="'.$export_id.$hide.'">';
        $close = '</div>';
        
        foreach ($listchildarray as $value){
        
            $string = "$(document).twiz_".$value[parent::F_SECTION_ID]."_".str_replace("-","_",sanitize_title_with_dashes($value[parent::F_LAYER_ID]))."_".$value[parent::F_EXPORT_ID]."();";
            
            $html .= '&nbsp;&nbsp;<span id="twiz_anim_link_img_box_'.$value[parent::F_ID].'_'.$level.'" name="twiz_anim_link_img_box" class="twiz-loading-gif"></span><a id="twiz_anim_link_'.$value[parent::F_ID].'_'.$level.'" name="twiz_anim_link_'.$value[parent::F_EXPORT_ID].'_'.$level.'" class="twiz-anim-link">'.$string.'</a><br />';
        
        }
        
        $html = ($html != '')? $open.$html.$close : $open.$close;
        
        return $html;
    }
    
    function getHtmlView( $id, $level = 0 ){ 
        
        $data = '';
        
        if($id != ''){
            if(!$data = $this->getRow($id)){return false;}
        }

        $hasMovements = $this->hasMovements($data);
        $hasStartingConfigs = $this->hasStartingConfigs($data);
        

        $start_top_pos = ($data[parent::F_START_TOP_POS]!='') ? $data[parent::F_START_TOP_POS_SIGN].$data[parent::F_START_TOP_POS].' '.$data[parent::F_START_TOP_POS_FORMAT] : '';
        $start_left_pos = ($data[parent::F_START_LEFT_POS]!='') ? $data[parent::F_START_LEFT_POS_SIGN].$data[parent::F_START_LEFT_POS].' '.$data[parent::F_START_LEFT_POS_FORMAT] : '';
        $move_top_pos_a = ($data[parent::F_MOVE_TOP_POS_A]!='') ? $data[parent::F_MOVE_TOP_POS_SIGN_A].$data[parent::F_MOVE_TOP_POS_A].' '.$data[parent::F_MOVE_TOP_POS_FORMAT_A] : '';
        $move_left_pos_a = ($data[parent::F_MOVE_LEFT_POS_A]!='') ? $data[parent::F_MOVE_LEFT_POS_SIGN_A].$data[parent::F_MOVE_LEFT_POS_A].' '.$data[parent::F_MOVE_LEFT_POS_FORMAT_A] : '';
        $move_top_pos_b = ($data[parent::F_MOVE_TOP_POS_B]!='') ? $data[parent::F_MOVE_TOP_POS_SIGN_B].$data[parent::F_MOVE_TOP_POS_B].' '.$data[parent::F_MOVE_TOP_POS_FORMAT_B] : '';
        $move_left_pos_b = ($data[parent::F_MOVE_LEFT_POS_B]!='') ? $data[parent::F_MOVE_LEFT_POS_SIGN_B].$data[parent::F_MOVE_LEFT_POS_B].' '.$data[parent::F_MOVE_LEFT_POS_FORMAT_B] : '';
        
        $titleclass = ($data[parent::F_STATUS]=='1') ? 'twiz-status-green' : 'twiz-status-red';
        $event_locked = (($data[parent::F_LOCK_EVENT]=='1') and ( ( $data[parent::F_ON_EVENT] !='') and ( $data[parent::F_ON_EVENT] !='Manually') and ( $data[parent::F_LOCK_EVENT_TYPE] == 'auto') ) )  ? __('Automatic unlock', 'the-welcomizer') : '';
        $event_locked .= (($data[parent::F_LOCK_EVENT]=='1') and ( ( $data[parent::F_ON_EVENT] !='') and ( $data[parent::F_ON_EVENT] !='Manually') and ( $data[parent::F_LOCK_EVENT_TYPE] == 'manu') ) )  ? __('Manual unlock', 'the-welcomizer') : '';
        $imagemove_a = $this->getDirectionalImage($data, 'a');
        $imagemove_b = $this->getDirectionalImage($data, 'b');
        $elementype = ($data[parent::F_TYPE] == '') ? parent::ELEMENT_TYPE_ID : $data[parent::F_TYPE];
        
        $element_start = ($data[parent::F_START_ELEMENT] != '') ? '<span class="'.$titleclass.'">'.$data[parent::F_START_ELEMENT_TYPE].'</span> = '.$data[parent::F_START_ELEMENT]: '';
        $element_move_a = ($data[parent::F_MOVE_ELEMENT_A] != '') ? '<span class="'.$titleclass.'">'.$data[parent::F_MOVE_ELEMENT_TYPE_A].'</span> = '.$data[parent::F_MOVE_ELEMENT_A]: '';
        $element_move_b = ($data[parent::F_MOVE_ELEMENT_B] != '') ?'<span class="'.$titleclass.'">'.$data[parent::F_MOVE_ELEMENT_TYPE_B].'</span> = '.$data[parent::F_MOVE_ELEMENT_B]: '';
        
        $output_starting_pos = $this->getOutputLabel($data[parent::F_OUTPUT_POS]);
        $output_javascript = $this->getOutputLabel($data[parent::F_OUTPUT]);
        
        $easing_a = $this->getOutputEasingLabel($data[parent::F_EASING_A]);
        $easing_b = $this->getOutputEasingLabel($data[parent::F_EASING_B]);
        
        $javascript = str_replace("\n", "<br>", $data[parent::F_JAVASCRIPT]);
        $css = str_replace("\n", "<br>", $data[parent::F_CSS]);
        $extra_js_a = str_replace("\n", "<br>", $data[parent::F_EXTRA_JS_A]);
        $extra_js_b = str_replace("\n", "<br>", $data[parent::F_EXTRA_JS_B]);
        
        $javascript = str_replace(" ", "&nbsp;", $javascript);
        $css = str_replace(" ", "&nbsp;", $css);
        $extra_js_a = str_replace(" ", "&nbsp;", $extra_js_a);
        $extra_js_b = str_replace(" ", "&nbsp;", $extra_js_b);
             
        $where = " WHERE ".parent::F_SECTION_ID." = '".$data[parent::F_SECTION_ID]."'";
        $listarray = $this->getListArray($where);
        
        // anim links
        $javascript = $this->addViewLinks($javascript, $listarray, $level);
        $extra_js_a = $this->addViewLinks($extra_js_a, $listarray, $level);
        $extra_js_b = $this->addViewLinks($extra_js_b, $listarray, $level);
        
        // shortcode
      // $javascript = $this->replaceTwizShortCode( parent::SC_WP_UPLOAD_DIR, $javascript ); 
      // $extra_js_a = $this->replaceTwizShortCode( parent::SC_WP_UPLOAD_DIR, $extra_js_a ); 
      // $extra_js_b = $this->replaceTwizShortCode( parent::SC_WP_UPLOAD_DIR, $extra_js_b ); 
        $css = $this->replaceTwizShortCode( parent::SC_WP_UPLOAD_DIR, $css ); 
        
        $css = $this->addLinkToImage( $css );

        $on_event = $this->format_on_event($data[parent::F_ON_EVENT]);
        // creates the view
        $htmlview = '<table class="twiz-table-view" cellspacing="0" cellpadding="0">
        <tr><td class="twiz-view-td-left twiz-bold" valign="top"><span class="'.$titleclass.'">'.$elementype.'</span> = '.$data[parent::F_LAYER_ID].'
        </td><td class="twiz-view-td-right" nowrap="nowrap"><div class="twiz-list-tr-action" name="twiz_view_tr_action_'.$level.'" id="twiz_view_tr_action_'.$level.'" ><a id="twiz_edit_v_'.$data[parent::F_ID].'" name="twiz_edit_v_'.$data[parent::F_ID].'" class="twiz-edit">'.__('Edit', 'the-welcomizer').'</a> | <a id="twiz_copy_v_'.$data[parent::F_ID].'" name="twiz_copy_v_'.$data[parent::F_ID].'" class="twiz-copy">'.__('Copy', 'the-welcomizer').'</a> | <a id="twiz_delete_v_'.$data[parent::F_ID].'" name="twiz_delete_v_'.$data[parent::F_ID].'" class="twiz-red twiz-delete">'.__('Delete', 'the-welcomizer').'</a></div></div>';
        
        if( ($hasStartingConfigs) 
        or ($data[parent::F_CSS] != '') 
        or ((!$hasStartingConfigs) and (!$hasMovements) ) ) {
        
            $hidetop = '';
            
        }else{
        
            $hidetop = '1';
        }
              
        $htmlview .='</td></tr>';
        
        if(!($onlycss = $this->hasOnlyCSS($data))){

            $htmlview .='<tr><td class="twiz-view-td-left" valign="top" ><div class="twiz-blue">'.$on_event.'</div><div class="twiz-add-element">'.$event_locked.'</div></td><td class="twiz-view-td-right" nowrap="nowrap"><table><tr><td>'.__('Delay', 'the-welcomizer').':</td><td>'.$data[parent::F_START_DELAY].' <small>ms</small></td></tr>';
            
            if($hasMovements){
            
                $htmlview .='<tr><td>'.__('Duration', 'the-welcomizer').':</td><td>'.$this->formatDuration($data[parent::F_ID], $data).'</td></tr>';
            }
            
            $htmlview .= '</table></td></tr>';
        }
        
        if($hidetop == ''){
        
            $htmlview .='<tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr><tr>';

            if ( ($element_start != '')
            or ( $start_top_pos != '' )
            or ( $start_left_pos != '' )
            or ( $data[parent::F_POSITION] != '' )
            or ( $data[parent::F_ZINDEX] != '' ) ){
        
                $colspan2 = '';
                $htmlview .='<td class="twiz-view-td-left" valign="top"><table>
             <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('Starting Positions', 'the-welcomizer').'</b>
             <div class="twiz-green">'.$output_starting_pos.'</div><div class="twiz-spacer"></div></td></tr>';
         
                $htmlview .= ($element_start != '') ? '<tr><td colspan="2" class="twiz-view-td-small-left twiz-bold" nowrap="nowrap">'.$element_start.'</td></tr>' : '';
                
                $htmlview .= ( $start_top_pos != '' ) ? '<tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.$this->label_y.':</td><td>'.$start_top_pos.'</td></tr>' : '';
                
                $htmlview .= ( $start_left_pos != '' ) ? '<tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.$this->label_x.':</td><td>'.$start_left_pos.'</td></tr>' : '';
                
                $htmlview .= ( $data[parent::F_POSITION] != '' ) ? '<tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Position', 'the-welcomizer').':</td><td>'.' '.$data[parent::F_POSITION].'</td></tr>' : '';
                
                $htmlview .= ( $data[parent::F_ZINDEX] != '' ) ? '<tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('z-index', 'the-welcomizer').':</td><td>'.' '.$data[parent::F_ZINDEX].'</td></tr>' : '';
                    
                $htmlview .= '</table></td>';
            
            }else{
            
                $colspan2 = ' colspan="2"';
            }
        
            $htmlview .='
    <td valign="top"'.$colspan2.'>
    <table>';
            
            $htmlview .= ( $javascript != '' ) ? '<tr><td class="twiz-caption"  nowrap="nowrap"><b>'.__('jQuery', 'the-welcomizer').'</b><div class="twiz-green">'.$output_javascript.'</div><div class="twiz-spacer"></div></td></tr><tr><td nowrap="nowrap">'.$javascript.'</td></tr><tr><td><div class="twiz-spacer"></div></td></tr>' : '';

            $htmlview .= ( $css != '' ) ? '<tr><td class="twiz-caption" nowrap="nowrap"><b>'.__('Extra CSS', 'the-welcomizer').'</b><div class="twiz-spacer"></div></td></tr>
    <tr><td nowrap="nowrap">'.$css.'</td></tr>' : '';
            
            $htmlview .= '
</table>    
</td>
</tr></table>';
        }
    
       if ( ( $element_move_b != '' )
            or ( $data[parent::F_MOVE_LEFT_POS_B] != '' ) 
            or ( $data[parent::F_MOVE_TOP_POS_B] != '' )
            or ( $data[parent::F_OPTIONS_B] != '' )
            or ( $extra_js_b != '' ) ){
             
             $colspan2 = '';
             
       }else{
        
            $colspan2 = ' colspan="2"';
       }
    
        if( $hasMovements ) {

            $htmlview .= '<table class="twiz-table-view-b" cellspacing="0" cellpadding="0">
        <tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr>
<tr><td class="twiz-view-td-left" valign="top"'.$colspan2.'>
<table>
    <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('First Move', 'the-welcomizer').'</b>
    <div class="twiz-green">'.$easing_a.'</div><div class="twiz-spacer"></div></td></tr>';
    
             
            $htmlview .= ($element_move_a != '') ? '<tr><td colspan="2" class="twiz-view-td-small-left twiz-bold" nowrap="nowrap">'.$element_move_a.'</td></tr>' : '';
            
            $htmlview .= (($data[parent::F_MOVE_TOP_POS_A]!='') or ($data[parent::F_MOVE_LEFT_POS_A]!='')) ? '<tr><td class="twiz-view-td-small-left" valign="top" height="20" nowrap="nowrap">'.$this->label_y.':</td><td valign="top" nowrap="nowrap">'.$move_top_pos_a .'</td><td rowspan="2" align="center" width="95">'.$imagemove_a.'</td></tr>
        <tr><td class="twiz-view-td-small-left"  nowrap="nowrap" valign="top">'.$this->label_x.':</td><td valign="top" nowrap="nowrap">'.$move_left_pos_a .'</td></tr>' : '';
        
            $htmlview .= '</table><table class="twiz-view-table-more-options"><tr><td><hr class="twiz-hr twiz-corner-all"></td></tr>';

            $htmlview .= ( $data[parent::F_OPTIONS_A] != '' ) ? '<tr><td nowrap="nowrap">'.str_replace("\n", "<br>",$data[parent::F_OPTIONS_A]).'</td></tr>' : '';

            $htmlview .= '<tr><td><hr class="twiz-hr twiz-corner-all"></td></tr>';

            $htmlview .= ( $extra_js_a != '' ) ? '<tr><td nowrap="nowrap">'.$extra_js_a.'</td></tr>' : '';
        
            $htmlview .= '</table>';
    
            $htmlview .= '</td>
<td valign="top">';

            if ( ($element_move_b != '')
            or ( $data[parent::F_MOVE_LEFT_POS_B] != '' ) 
            or ( $data[parent::F_MOVE_TOP_POS_B] != '' )
            or ( $data[parent::F_OPTIONS_B] != '' )
            or ( $extra_js_b != '' ) ){
            
                $htmlview .= '<table>
                <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('Second Move', 'the-welcomizer').'</b>
                <div class="twiz-green">'.$easing_b.'</div><div class="twiz-spacer"></div></td></tr>';
                

                $htmlview .= ($element_move_b != '') ? '<tr><td colspan="2" class="twiz-view-td-small-left twiz-bold" nowrap="nowrap">'.$element_move_b.'</td></tr>' : '';
                
                
                $htmlview .= (($data[parent::F_MOVE_TOP_POS_B]!='') or ($data[parent::F_MOVE_LEFT_POS_B]!='')) ? '<tr><td class="twiz-view-td-small-left" valign="top" height="20" nowrap="nowrap">'.$this->label_y.':</td><td valign="top" nowrap="nowrap">'.$move_top_pos_b.'</td><td rowspan="2" align="center" width="95">'.$imagemove_b.'</td></tr>
    <tr><td class="twiz-view-td-small-left" nowrap="nowrap" valign="top">'.$this->label_x.':</td><td valign="top" nowrap="nowrap">'.$move_left_pos_b .'</td></tr>' : '';
            
                $htmlview .= '</table><table class="twiz-view-table-more-options"><tr><td><hr class="twiz-hr twiz-corner-all"></td></tr>';
                
                $htmlview .= ( $data[parent::F_OPTIONS_B] != '' ) ? '<tr><td nowrap="nowrap">'.str_replace("\n", "<br>",$data[parent::F_OPTIONS_B]).'</td></tr>' : '';

                $htmlview .= '<tr><td><hr class="twiz-hr twiz-corner-all"></td></tr>';
                
                $htmlview .= ( $extra_js_b != '' ) ? '<tr><td nowrap="nowrap">'.$extra_js_b.'</td></tr>' : '';

                $htmlview .= '</table>';
            }
                
                $htmlview .= '</td></tr>';
        }
        
        $htmlview .= '</table>';
    
        return $htmlview;
    }

    private function getOutputLabel( $type = '' ){
    
        switch($type){
        
            case 'r':
            
                return ''.__('onReady', 'the-welcomizer').'';
                
                break;
                
            case 'b':
            
                return ''.__('Before the delay', 'the-welcomizer').'';
                
                break;
                
            case 'a':
            
                return ''.__('After the delay', 'the-welcomizer').'';
                
                break;
            case 'c':
            
                return ''.__('CSS Styles', 'the-welcomizer').'';
                
                break;
        }
        
        return '';
    }
}
?>