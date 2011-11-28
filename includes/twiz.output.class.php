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
    
class TwizOutput extends Twiz{

    private $listarray;
    private $generatedscript;
    private $generatedscriptonready;    
    private $generatedscriptonevent;    
    private $newElementFormat;
    private $linebreak;
    private $tab;
    
    const COMPRESS_LINEBREAK = "\n";
    const COMPRESS_TAB = "\t";
    
    function __construct(){
    
        parent::__construct();
        
        $this->listarray = $this->getCurrentList();
        
        $admin_option = get_option('twiz_admin');
        
        if($admin_option[parent::KEY_OUTPUT_COMPRESSION] != '1'){
        
            $this->linebreak = self::COMPRESS_LINEBREAK;
            $this->tab = self::COMPRESS_TAB;
        }       
    }

    function generateOutput(){
                
        // no data, no output
        if( count($this->listarray) == 0 ){ return ''; }
        
        $gstatus = get_option('twiz_global_status');

        if( $gstatus  == '1' ){
       
            // script header 
            $this->generatedscript .="<!-- ".$this->pluginName." ".$this->version." -->".self::COMPRESS_LINEBREAK;
            
            $this->generatedscript .= '<script type="text/javascript">'.$this->linebreak.'jQuery(document).ready(function($){ '.$this->linebreak;

            // this used by javasccript before.
            $this->generatedscript .= 'var twiz_this = "";';
            
            // Get starting positions
            $this->generatedscript .= $this->getStartingPositionsOnReady();
                        
            // generates the code
            foreach($this->listarray as $value){

                $have_active = '';
               
                $repeatname = $value[parent::F_SECTION_ID] ."_".str_replace("-","_",$value[parent::F_LAYER_ID])."_".$value[parent::F_EXPORT_ID];
                $repeatname_var = str_replace("-","_", $value[parent::F_LAYER_ID])."_".$value[parent::F_EXPORT_ID];
                
                $this->newElementFormat = $this->replacejElementType($value[parent::F_TYPE], $value[parent::F_LAYER_ID]);
                
                // replace numeric entities
                $value[parent::F_JAVASCRIPT] = $this->replaceNumericEntities($value[parent::F_JAVASCRIPT]);
                
                // replace this
                $value[parent::F_JAVASCRIPT] = str_replace("(this)", "(twiz_this)" , $value[parent::F_JAVASCRIPT]);
                $value[parent::F_EXTRA_JS_A] = str_replace("(this)", "(twiz_this)" , $value[parent::F_EXTRA_JS_A]);
                $value[parent::F_EXTRA_JS_B] = str_replace("(this)", "(twiz_this)" , $value[parent::F_EXTRA_JS_B]);
                
                // repeat animation function 
                $this->generatedscript .= '$.fn.twiz_'.$repeatname.' = function(twiz_this, twiz_repeat_nbr){ '.$this->linebreak;
                $this->generatedscript .= 'if(twiz_repeat_'.$repeatname.' == 0){ twiz_repeat_'.$repeatname.' = null; return true;} '.$this->linebreak;
                $this->generatedscript .= 'if((twiz_repeat_'.$repeatname.' == null) && (twiz_repeat_nbr != null)){ '.$this->linebreak;
                $this->generatedscript .= 'twiz_repeat_'.$repeatname.' = twiz_repeat_nbr;} '.$this->linebreak;
                $this->generatedscript .= 'if((twiz_repeat_'.$repeatname.' == null) || (twiz_repeat_'.$repeatname.' > 0)){ '.$this->linebreak; 
                $this->generatedscript .= 'if(twiz_repeat_'.$repeatname.' > 0){ '.$this->linebreak;
                $this->generatedscript .= 'twiz_repeat_'.$repeatname.'--;} '.$this->linebreak;;

                if(($value[parent::F_OUTPUT_POS]=='b')or ($value[parent::F_OUTPUT_POS]=='')){ // before
                    
                    $this->generatedscript .= $this->getStartingPositions($value);    
                }
                
                if(($value[parent::F_OUTPUT]=='b') or ($value[parent::F_OUTPUT]=='')){ // before
                
                    // js
                    $value[parent::F_JAVASCRIPT] = ($value[parent::F_JAVASCRIPT] != '') ? $this->linebreak.$this->tab.str_replace("$(document).twizRepeat(", "$(document).twiz_".$repeatname.'(twiz_this,' , $value[parent::F_JAVASCRIPT]) : '';
                    
                    $this->generatedscript .= str_replace("(twiz_this,)", "(twiz_this, null)" , $value[parent::F_JAVASCRIPT]);
                }
                
                $hasSomething = $this->hasSomething($value);
                
                if($hasSomething == true ){
                    
                    // start delay 
                    $this->generatedscript .= $this->linebreak.$this->tab.'setTimeout(function(){'; 
                
                    if($value[parent::F_OUTPUT_POS]=='a'){ // after
                        
                        $this->generatedscript .= $this->getStartingPositions($value);
                       
                    }
                
                    if( $value[parent::F_OUTPUT] == 'a' ){ // after 
                        
                        // js 
                        $value[parent::F_JAVASCRIPT] = str_replace("$(document).twizRepeat(", "$(document).twiz_".$repeatname.'(twiz_this,' , $value[parent::F_JAVASCRIPT]);
                        $this->generatedscript .= str_replace("(twiz_this,)", "(twiz_this, null)" , $value[parent::F_JAVASCRIPT]);
                        
                    }
                    
                    $value[parent::F_OPTIONS_A] = (($value[parent::F_OPTIONS_A]!='') and (($value[parent::F_MOVE_LEFT_POS_A]!="") or ($value[parent::F_MOVE_TOP_POS_A]!=""))) ? ','.$value[parent::F_OPTIONS_A] :  $value[parent::F_OPTIONS_A];
                    $value[parent::F_OPTIONS_A] = str_replace(self::COMPRESS_LINEBREAK, $this->linebreak.$this->tab.",", $value[parent::F_OPTIONS_A]);
                
                    // replace numeric entities   
                    $value[parent::F_OPTIONS_A] = $this->replaceNumericEntities($value[parent::F_OPTIONS_A]);

                    $hasMovements = $this->hasMovements($value);
                    
                    if($hasMovements == true ){
                        
                        // animate jquery a 
                        $this->generatedscript .= $this->linebreak.$this->tab.'$("'. $this->newElementFormat . '").animate({';

                        $value[parent::F_MOVE_TOP_POS_SIGN_A] = ($value[parent::F_MOVE_TOP_POS_SIGN_A]!='')? $value[parent::F_MOVE_TOP_POS_SIGN_A].'=' : '';
                        $value[parent::F_MOVE_LEFT_POS_SIGN_A] = ($value[parent::F_MOVE_LEFT_POS_SIGN_A]!='')? $value[parent::F_MOVE_LEFT_POS_SIGN_A].'=' : '';

                        $this->generatedscript .= ($value[parent::F_MOVE_LEFT_POS_A]!="") ? 'left: "'.$value[parent::F_MOVE_LEFT_POS_SIGN_A].$value[parent::F_MOVE_LEFT_POS_A].$value[parent::F_MOVE_LEFT_POS_FORMAT_A].'"' : '';
                        $this->generatedscript .= (($value[parent::F_MOVE_LEFT_POS_A]!="") and ($value[parent::F_MOVE_TOP_POS_A]!="")) ? ',' : '';
                        $this->generatedscript .= ($value[parent::F_MOVE_TOP_POS_A]!="") ? 'top: "'.$value[parent::F_MOVE_TOP_POS_SIGN_A].$value[parent::F_MOVE_TOP_POS_A].$value[parent::F_MOVE_TOP_POS_FORMAT_A].'"' : '';
                        $this->generatedscript .= $value[parent::F_OPTIONS_A];
                        
                        $this->generatedscript .= $this->linebreak.$this->tab.'},'.$value[parent::F_DURATION].',"'.$value[parent::F_EASING_A].'", function(){';
                        $value[parent::F_EXTRA_JS_A] = ($value[parent::F_EXTRA_JS_A] != '') ? $this->linebreak.$value[parent::F_EXTRA_JS_A] : $value[parent::F_EXTRA_JS_A];
                        // replace numeric entities
                        $value[parent::F_EXTRA_JS_A] = $this->replaceNumericEntities($value[parent::F_EXTRA_JS_A]).$this->linebreak;
            
                        // extra js a
                        $value[parent::F_EXTRA_JS_A] = str_replace("$(document).twizRepeat(", "$(document).twiz_".$repeatname.'(twiz_this,' , $value[parent::F_EXTRA_JS_A]);
                        $this->generatedscript .= str_replace("(twiz_this,)", "(twiz_this, null)" , $value[parent::F_EXTRA_JS_A]);
                        
   
                        // b
                                    
                        $have_b = (($value[parent::F_MOVE_TOP_POS_B] !='' ) or ( $value[parent::F_MOVE_LEFT_POS_B] !='' ) or ( $value[parent::F_OPTIONS_B] !='' ) or ( $value[parent::F_EXTRA_JS_B] !='' )) ? true : false;
                        
                        // add a coma between each options 
                        $value[parent::F_OPTIONS_B] = (($value[parent::F_OPTIONS_B]!='') and ((($value[parent::F_MOVE_LEFT_POS_B]!="") or ($value[parent::F_MOVE_TOP_POS_B]!="")))) ? ','.$value[parent::F_OPTIONS_B] : $value[parent::F_OPTIONS_B];
                        $value[parent::F_OPTIONS_B] = str_replace(self::COMPRESS_LINEBREAK, $this->linebreak.$this->tab.$this->tab."," , $value[parent::F_OPTIONS_B]);
                        
                        // replace numeric entities              
                        $value[parent::F_OPTIONS_B] = $this->replaceNumericEntities($value[parent::F_OPTIONS_B]);
                        
                        // animate jquery b
                        
                        $this->generatedscript .= $this->linebreak.$this->tab.$this->tab.'$("'. $this->newElementFormat . '").animate({';

                        $value[parent::F_MOVE_TOP_POS_SIGN_B] = ($value[parent::F_MOVE_TOP_POS_SIGN_B]!='')? $value[parent::F_MOVE_TOP_POS_SIGN_B].'=' : '';
                        $value[parent::F_MOVE_LEFT_POS_SIGN_B] = ($value[parent::F_MOVE_LEFT_POS_SIGN_B]!='')? $value[parent::F_MOVE_LEFT_POS_SIGN_B].'=' : '';

                        $this->generatedscript .= ($value[parent::F_MOVE_LEFT_POS_B]!="") ? 'left: "'.$value[parent::F_MOVE_LEFT_POS_SIGN_B].$value[parent::F_MOVE_LEFT_POS_B].$value[parent::F_MOVE_LEFT_POS_FORMAT_B].'"' : '';
                        $this->generatedscript .= (($value[parent::F_MOVE_LEFT_POS_B]!="") and ($value[parent::F_MOVE_TOP_POS_B]!="")) ? ',' : '';
                        $this->generatedscript .= ($value[parent::F_MOVE_TOP_POS_B]!="") ? 'top: "'.$value[parent::F_MOVE_TOP_POS_SIGN_B].$value[parent::F_MOVE_TOP_POS_B].$value[parent::F_MOVE_TOP_POS_FORMAT_B].'"' : '';
                        $this->generatedscript .=  $value[parent::F_OPTIONS_B];
                        
                        // set to sero
                        $value[parent::F_DURATION] = (!$have_b)? '0' : $value[parent::F_DURATION];
                        $value[parent::F_EASING_B] = (!$have_b)? '' : $value[parent::F_EASING_B];
                        
                        $this->generatedscript .= $this->linebreak.$this->tab.$this->tab.'},'.$value[parent::F_DURATION].',"'.$value[parent::F_EASING_B].'", function(){';
                            
                        $value[parent::F_EXTRA_JS_B] = ($value[parent::F_EXTRA_JS_B] != '') ? $this->linebreak.$value[parent::F_EXTRA_JS_B] : $value[parent::F_EXTRA_JS_B];
                        
                        // replace numeric entities
                        $value[parent::F_EXTRA_JS_B] = $this->replaceNumericEntities($value[parent::F_EXTRA_JS_B]).$this->linebreak;
                        
                        if($have_b){
                        
                            $this->generatedscript .= $this->linebreak.$this->tab.$this->tab.'twiz_active_'.$repeatname_var.' = 0;';
                            $have_active = true;
                        }
                        
                        // extra js b    
                        $value[parent::F_EXTRA_JS_B] = str_replace("$(document).twizRepeat(", $this->tab.$this->tab."$(document).twiz_".$repeatname.'(twiz_this,', $value[parent::F_EXTRA_JS_B]);
                         $this->generatedscript .= str_replace("(twiz_this,)", "(twiz_this, null)" , $value[parent::F_EXTRA_JS_B]);
                        
                        // closing functions
                        $this->generatedscript .= $this->tab.$this->tab.'});'.$this->linebreak;
                            
                        if( !$have_b ){
                        
                            $this->generatedscript .= $this->tab.'twiz_active_'.$repeatname_var.' = 0;'.$this->linebreak;
                            $have_active = true;
                        }
                        
                        $this->generatedscript .= $this->tab.'});'.$this->linebreak;
                    }
                    
                    // Closing timout
                    $this->generatedscript .= $this->tab.'},'.$value[parent::F_START_DELAY].');'.$this->linebreak;

                }
                
                if( $have_active != true ){
               
                    $this->generatedscript .= $this->linebreak.$this->tab.'twiz_active_'.$repeatname_var.' = 0;';
                }
                
                
                // closing functions
                $this->generatedscript .= '}}'.self::COMPRESS_LINEBREAK;
                
                $this->generatedscriptonevent .= $this->getOnEventFunction( $value, $repeatname, $repeatname_var );
   
            } // End loop
            
            
            $this->generatedscript .= $this->getReplayFunction();
            $this->generatedscript .= $this->generatedscriptonevent;
            $this->generatedscript .= $this->getJavaScriptOnReady();
            $this->generatedscript .= $this->generatedscriptonready;
            
            $this->generatedscript.= $this->linebreak.'});</script>';
        }
        return $this->generatedscript;
    }
      
    private function getJavaScriptOnReady(){
    
        $generatedscript = '';
    
       // generates the code
        foreach($this->listarray as $value){   
                            
            if( $value[parent::F_OUTPUT] == 'r' ){ // ready 
                
                $repeatname = $value[parent::F_SECTION_ID] ."_".str_replace("-","_",$value[parent::F_LAYER_ID])."_".$value[parent::F_EXPORT_ID];
                
                // replace numeric entities
                $value[parent::F_JAVASCRIPT] = $this->replaceNumericEntities($value[parent::F_JAVASCRIPT]);
                $value[parent::F_JAVASCRIPT] = ($value[parent::F_JAVASCRIPT] != '') ? $this->linebreak.$value[parent::F_JAVASCRIPT] : '';
                
                // js 
                $generatedscript .= str_replace("$(document).twizRepeat(", "$(document).twiz_".$repeatname.'($("'.$this->newElementFormat.'", null)' , $value[parent::F_JAVASCRIPT]);
              
            }   

        }
        
        return $generatedscript;
    }      
    
    private function getOnEventFunction( $value = '' , $repeatname = '', $repeatname_var = '' ){
    
        $generatedscript = '';
    
          // trigger on event
        if( $value[parent::F_ON_EVENT] != '' ){
        
           if( $value[parent::F_ON_EVENT] != parent::EV_MANUAL ){
           
               $generatedscript .= '$("'.$this->newElementFormat.'").'.strtolower($value[parent::F_ON_EVENT]).'(function(){'.$this->linebreak;
               $generatedscript .= $this->tab.'if(twiz_active_'.$repeatname_var.' == 0){'.$this->linebreak;
               $generatedscript .= $this->tab.$this->tab.'twiz_active_'.$repeatname_var.' = 1;'.$this->linebreak;
               $generatedscript .= $this->tab.$this->tab.'$(document).twiz_'.$repeatname.'(this, null);'.$this->linebreak.$this->tab.'}';
               $generatedscript .= $this->linebreak.'});'.$this->linebreak;                    
           }
           
        } else{
        
            // trigger the animation if not on event
            $this->generatedscriptonready .=  $this->linebreak.'$(document).twiz_'.$repeatname.'($("'.$this->newElementFormat.'"), null);';
        }  
        
        return $generatedscript;
            
    }
    
    private function getCurrentList(){
    
        global $post;
                   
        wp_reset_query(); // fix is_home() due to a custom query.
        
        $sections = get_option('twiz_sections');
        $hardsections = get_option('twiz_hardsections');
        
        if($hardsections[parent::DEFAULT_SECTION_EVERYWHERE][parent::F_STATUS] == parent::STATUS_ACTIVE){
                        
            $listarray_e = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".parent::DEFAULT_SECTION_EVERYWHERE."' "); 
            
        }else{
            $listarray_e = array();
        }
       
        switch( true ){

            case ( is_home() || is_front_page() ):
                
                if($hardsections[parent::DEFAULT_SECTION_HOME][parent::F_STATUS] == parent::STATUS_ACTIVE){
                
                    // get the active data list array
                    $listarray_h = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".parent::DEFAULT_SECTION_HOME."' "); 
                
                    $this->listarray = array_merge($listarray_e, $listarray_h);
                }
                break;
                
            case is_category():
                
                $category_id = 'c_'.get_query_var('cat');
                
                if($hardsections[parent::DEFAULT_SECTION_ALL_CATEGORIES][parent::F_STATUS] == parent::STATUS_ACTIVE){
                
                    // get the active data list array
                    $listarray_allc = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".parent::DEFAULT_SECTION_ALL_CATEGORIES."' "); 
                
                }else{
                    $listarray_allc = array();
                }
                
                if( !isset($sections[$category_id]) ) $sections[$category_id][parent::F_STATUS] = parent::STATUS_INACTIVE;
                if($sections[$category_id][parent::F_STATUS] == parent::STATUS_ACTIVE){                
                    $listarray_c = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".$category_id."' "); 
                }else{
                    $listarray_c = array();
                }
                
                $listarray_T = array_merge($listarray_e, $listarray_c);
                $this->listarray = array_merge($listarray_T, $listarray_allc);
                
                break;
                
            case is_page():
            
                $page_id = 'p_'.$post->ID;
                
                if($hardsections[parent::DEFAULT_SECTION_ALL_PAGES][parent::F_STATUS] == parent::STATUS_ACTIVE){
                
                    // get the active data list array
                    $listarray_allp = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".parent::DEFAULT_SECTION_ALL_PAGES."' "); 
                }else{
                    $listarray_allp = array();
                }

                if( !isset($sections[$page_id]) ) $sections[$page_id][parent::F_STATUS] = parent::STATUS_INACTIVE;
                if($sections[$page_id][parent::F_STATUS] == parent::STATUS_ACTIVE){                 
                    $listarray_p = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".$page_id."' ");             
                }else{
                    $listarray_p = array();
                }
                
                $listarray_T = array_merge($listarray_e, $listarray_p);
                $this->listarray = array_merge($listarray_T, $listarray_allp);
                
                break;

            case is_single(): 

                $post_id = 'a_'.$post->ID;
                
                if($hardsections[parent::DEFAULT_SECTION_ALL_ARTICLES][parent::F_STATUS] == parent::STATUS_ACTIVE){
                
                    // get the active data list array
                    $listarray_alla = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".parent::DEFAULT_SECTION_ALL_ARTICLES."' ");   
                }else{
                    $listarray_alla = array();
                }
                
                if( !isset($sections[$post_id]) ) $sections[$post_id][parent::F_STATUS] = parent::STATUS_INACTIVE;
                if($sections[$post_id][parent::F_STATUS] == parent::STATUS_ACTIVE){                   
                    $listarray_a = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".$post_id."' ");                 
                }else{
                    $listarray_a = array();
                }
                
                $listarray_T = array_merge($listarray_e, $listarray_a);
                $this->listarray = array_merge($listarray_T, $listarray_alla);
                
                break;
                
            case is_feed(): 
                
                return '';
                
                break;       
        }
        
        $this->listarray = (is_array($this->listarray)) ? $this->listarray : $listarray_e;
        
        return $this->listarray;
    }
    
    private function getReplayFunction(){
        $generatedscript = '';
        $generatedscript_repeatvar = '';
        $generatedscript_function = '$.fn.twizReplay = function(){';
        // generates the code
        foreach($this->listarray as $value){
            if( $value[parent::F_ON_EVENT] == '' ){
                
                // Excluding only repeated animations that are running forever.(manually called are not verified)
                $pos = strpos($value[parent::F_JAVASCRIPT], "$(document).twizRepeat()");
                $posa = strpos($value[parent::F_EXTRA_JS_A], "$(document).twizRepeat()");
                $posb = strpos($value[parent::F_EXTRA_JS_B], "$(document).twizRepeat()");

                if (($pos === false) and ($posa === false) and ($posb === false)) {
                    $newElementFormat = $this->replacejElementType($value[parent::F_TYPE], $value[parent::F_LAYER_ID]);
                    $repeatname = $value[parent::F_SECTION_ID] ."_".str_replace("-","_",$value[parent::F_LAYER_ID])."_".$value[parent::F_EXPORT_ID];
                    $generatedscript_repeatvar .= 'twiz_repeat_'.$repeatname.' = null;';
                    $generatedscript .= $this->linebreak.$this->tab.'$(document).twiz_'.$repeatname.'($("'.$newElementFormat.'"), null);';
                }
            }
        }
        $generatedscript_function .= $generatedscript_repeatvar.$generatedscript.$this->linebreak.'}'.self::COMPRESS_LINEBREAK;
        
        return $generatedscript_function;
    }
    
    private function getStartingPositions( $value = '' ){
   
        $generatedscript_block = '';
        $generatedscript_pos = '';
        
        if(($value[parent::F_POSITION]!='') or ($value[parent::F_ZINDEX]!='') 
        or ($value[parent::F_START_LEFT_POS]!='') or ($value[parent::F_START_TOP_POS]!='')) {

            $this->newElementFormat = $this->replacejElementType($value[parent::F_TYPE], $value[parent::F_LAYER_ID]);
                
            if($value[parent::F_POSITION]!=''){
            
                $generatedscript_pos[] =  '"position":"'.$value[parent::F_POSITION].'"'; 
            }
            
            if($value[parent::F_ZINDEX]!=''){
            
                $generatedscript_pos[] =  '"z-index":"'.$value[parent::F_ZINDEX].'"'.$this->linebreak; 
            }
            
            if($value[parent::F_START_LEFT_POS]!=''){
            
                $generatedscript_pos[] =  '"left":"'.$value[parent::F_START_LEFT_POS_SIGN].$value[parent::F_START_LEFT_POS].$value[parent::F_START_LEFT_POS_FORMAT].'"'; 
            }
            
            if($value[parent::F_START_TOP_POS]!=''){
            
                $generatedscript_pos[] =  '"top":"'.$value[parent::F_START_TOP_POS_SIGN].$value[parent::F_START_TOP_POS].$value[parent::F_START_TOP_POS_FORMAT].'"'; 
            }
            
        }
        
        if(is_array($generatedscript_pos)){
        
            $generatedscript_block .= $this->linebreak.'$("'. $this->newElementFormat . '").css({'.implode(",", $generatedscript_pos).'});';
        }
        
        return $generatedscript_block;  
    
    }

    private function getStartingPositionsOnReady(){
        
        $generatedscript_pos = '';
        $generatedscript_repeat = '';
        $generatedscript_active = '';
        
        // generates the code starting positions 
        foreach($this->listarray as $value){   
            
            $repeatname = $value[parent::F_SECTION_ID] ."_".str_replace("-","_",$value[parent::F_LAYER_ID])."_".$value[parent::F_EXPORT_ID];
            $generatedscript_repeat .= $this->linebreak.'var twiz_repeat_'.$repeatname.' = null;';
            
            $repeatname_var = str_replace("-","_", $value[parent::F_LAYER_ID])."_".$value[parent::F_EXPORT_ID];
            $generatedscript_active .= $this->linebreak.'var twiz_active_'.$repeatname_var.' = 0;';

            if( $value[parent::F_OUTPUT_POS] == 'r' ){ // ready
            
                $generatedscript_pos .= $this->getStartingPositions($value);
            
            }           
        }
        
        $this->generatedscript .= $generatedscript_repeat.$generatedscript_active;

        return  $generatedscript_pos.$this->linebreak;
    }
    
    private function replacejElementType ( $type = '', $element = '' ){
            
            switch($type){
            
                case parent::ELEMENT_TYPE_ID:
                
                    return '#'.$element;
     
                break;

                case parent::ELEMENT_TYPE_CLASS:
                
                    return '.'.$element;
                    
                break;

                case parent::ELEMENT_TYPE_NAME:
                    
                    return '[name='.$element.']';
                    
                break; 
                
            }
            
            return '#'.$element;
    }
    
    private function replaceNumericEntities( $value = '' ){
            
        // entities array
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
            
        // replace numeric entities
        $value = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $value);
        $value = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $value);
        $newvalue = strtr($value, $trans_tbl);
        
        return $newvalue;
    }
}?>