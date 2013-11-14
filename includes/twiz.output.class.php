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
    
class TwizOutput extends Twiz{

    private $listarray;
    private $generatedScript;
    private $generatedScriptonReady;
    private $generatedScriptonEvent;
    private $newElementFormat;
    private $linebreak;
    private $tab;
    private $outputStatus;
    private $sections;
    private $hardsections;
    private $multi_sections;
    private $shortcode_id;
    private $animate;
    private $stop;
    private $y;
    private $x;
    private $css_y;
    private $css_x;
    private $global_js;
    private $generatedCookie;
    private $PHPCookieMax = array();
    private $array_cookie;
    private $cookieLooped = array();
    private $CookieMaxjQueryValidation = array();
    
    const COMPRESS_LINEBREAK = "\n";
    const COMPRESS_TAB = "\t";

    private $array_restricted = array("/\.ajax/i"
                                     ,"/\.post/i"
                                     ,"/\.cookie/i"
                                     );

    private $array_cookieval= array("onlyonce"   => 1
                                   ,"onlytwice"  => 2
                                   ,"onlythrice" => 3
                                   );
                                     
    function __construct( $shortcode_id = '' ){
    
        parent::__construct();
        
        $this->shortcode_id = $shortcode_id;
        $this->outputStatus = get_option('twiz_global_status');
        $this->sections = get_option('twiz_sections');
        $this->hardsections = get_option('twiz_hardsections');
        $this->multi_sections = get_option('twiz_multi_sections');
        $this->multi_sections = ( is_array($this->multi_sections) ) ? $this->multi_sections : array();
        $this->upload_dir = wp_upload_dir();

        $this->listarray = $this->getCurrentList($shortcode_id);
        
        if( $this->admin_option[parent::KEY_OUTPUT_COMPRESSION] != '1' ){
        
            $this->linebreak = self::COMPRESS_LINEBREAK;
            $this->tab = self::COMPRESS_TAB;
        }       
        
        if( $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSIT] == '1' ){
        
            $this->animate = 'transition';
            
            if( $this->admin_option[parent::KEY_POSITIONING_METHOD] == parent::POS_TOP_LEFT ){
            
                $this->y = 'y';
                $this->x = 'x';
                $this->css_y = 'top';
                $this->css_x = 'left';

            }else{  
            
                $this->y = 'y';
                $this->x = 'right';
                $this->css_y = 'top';
                $this->css_x = 'right';
            }    
            
        }else{
            
            $this->animate = 'animate';
            
            if( $this->admin_option[parent::KEY_POSITIONING_METHOD] == parent::POS_TOP_LEFT ){
                
                $this->y = 'top';
                $this->x = 'left';
                $this->css_y = 'top';
                $this->css_x = 'left';

            }else{  
            
                $this->y = 'top';
                $this->x = 'right';
                $this->css_y = 'top';
                $this->css_x = 'right';
            }      
        }
    }
    
    function generateOutput(){
                
        $this_prefix = '';
        
        // no data, no output
        if( count($this->listarray) == 0 ){ return ''; }
        
        if( $this->shortcode_id != '' ){
        
            $this_prefix = '_'.$this->listarray[0][parent::F_SECTION_ID];
        }

        if( $this->outputStatus == '1' ){
       
            // script header 
            $this->generatedScript .="<!-- ".$this->pluginName." ".$this->version." -->".self::COMPRESS_LINEBREAK;
            
            $this->generatedScript .= '<script type="text/javascript">[GLOBAL_JS]'.$this->linebreak.'jQuery(document).ready(function($){
'.$this->linebreak;

            $this->generatedScript .= $this->getStartingPositionsOnReady(); // Also set twiz_repeat and twiz_locked global variables. And also PHPCookieMax.
                        
            // this used by javasccript before.
            $this->generatedScript .= 'var twiz'.$this_prefix.'_this = "";';
            
            $this->cookieLooped = array();
            
                    
            // generates the code
            foreach($this->listarray as $value){
                               
                $sections = $this->getSectionArray($value[parent::F_SECTION_ID]);
                
                // Check for post, get, cookies.
                $hasRestrictedCode = $this->searchRestrictedCode($value);
                $hasOnlyCSS = $this->hasOnlyCSS($value);
                $hasValidParendId = $this->validateParentId($value[parent::F_PARENT_ID]);
                
                if( ( ($hasRestrictedCode) and ($this->admin_option[parent::KEY_OUTPUT_PROTECTED] == '1' ) ) 
                or ( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] == true ) // cookie condition true
                or (( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] == true ) and ( $this->PHPCookieMax[$sections[$value[parent::F_SECTION_ID]][parent::KEY_COOKIE_CONDITION]] == false ) ) // cookie condition true
                or ( $hasOnlyCSS  == true ) // Nothing but CSS Styles 
                or ( $value[parent::F_TYPE] ==  parent::ELEMENT_TYPE_GROUP ) ){ // skip group
                // Nothing to do
                }else if($hasValidParendId == true){                

                    $has_active = '';
                   
                    $name = $value[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($value[parent::F_LAYER_ID]))."_".$value[parent::F_EXPORT_ID];

                    $this->newElementFormat = str_replace('"', '\"', $this->replacejElementType($value[parent::F_TYPE], $value[parent::F_LAYER_ID]));
                    
                    // replace numeric entities
                    $value[parent::F_JAVASCRIPT] = $this->replaceNumericEntities($value[parent::F_JAVASCRIPT]);
                    
                    // replace this
                    $value[parent::F_JAVASCRIPT] = str_replace("(this)", "(twiz".$this_prefix."_this)" , $value[parent::F_JAVASCRIPT]);
                    $value[parent::F_EXTRA_JS_A] = str_replace("(this)", "(twiz".$this_prefix."_this)" , $value[parent::F_EXTRA_JS_A]);
                    $value[parent::F_EXTRA_JS_B] = str_replace("(this)", "(twiz".$this_prefix."_this)" , $value[parent::F_EXTRA_JS_B]);
                    
                    // repeat animation function 
                    $this->generatedScript .= '$.fn.twiz_'.$name.' = function(twiz'.$this_prefix.'_this, twiz_repeat_nbr, e){ '.$this->linebreak;
                    $this->generatedScript .= str_replace("[REPEAT_VAR]", 'twiz_repeat_'.$name, $this->CookieMaxjQueryValidation[$value[parent::F_SECTION_ID]]);
                    $this->generatedScript .= 'if(twiz_repeat_'.$name.' == 0){'.$this->linebreak.$this->tab.'twiz_repeat_'.$name.' = null; '.$this->linebreak.$this->tab.'return true;'.$this->linebreak.'} '.$this->linebreak;
                    $this->generatedScript .= 'if((twiz_repeat_'.$name.' == null) && (twiz_repeat_nbr != null)){ ';
                    $this->generatedScript .=  $this->linebreak.$this->tab.'twiz_repeat_'.$name.' = twiz_repeat_nbr;'.$this->linebreak.'} '.$this->linebreak;
                    $this->generatedScript .= 'if((twiz_repeat_'.$name.' == null) || (twiz_repeat_'.$name.' > 0)){ '.$this->linebreak;
  
                    $this->generatedScript .= 'if(e==undefined){var twiz_element_'.$name.' = eval($("<div />").html("'.$this->newElementFormat.'").text());}else{var twiz_element_'.$name.' = twiz'.$this_prefix.'_this;}';
                    
                    if(($value[parent::F_OUTPUT_POS]=='b')or ($value[parent::F_OUTPUT_POS]=='')){ // before
                        
                        $this->generatedScript .= $this->getStartingPositions($value);
                    }
                    
                    if(($value[parent::F_OUTPUT]=='b') or ($value[parent::F_OUTPUT]=='')){ // before
                    
                            // Replace parameters
                            $pattern = "/twizRepeat\((.*?)\)/";
                            preg_match($pattern, $value[parent::F_JAVASCRIPT], $params);
                            if (!isset($params[1])){$params[1] = '';}
                            if ( $params[1] == '' ) { $params[1] = 'null'; }
                
                            // js     
                            $value[parent::F_JAVASCRIPT] = ($value[parent::F_JAVASCRIPT] != '') ? $this->linebreak.$this->tab.str_replace("$(document).twizRepeat(", "$(document).twiz_".$name.'(twiz'.$this_prefix.'_this,'.$params[1].',e', $value[parent::F_JAVASCRIPT]) : '';
                            
                            $value[parent::F_JAVASCRIPT] = str_replace(",e".$params[1]."", ",e" , $value[parent::F_JAVASCRIPT]);
                            
                            $value[parent::F_JAVASCRIPT] = str_replace("$(document).twizReplay(", $this->tab."$(document).twizReplay_".$value[parent::F_SECTION_ID] .'(' , $value[parent::F_JAVASCRIPT].self::COMPRESS_LINEBREAK);
                            
                            $this->generatedScript .= $value[parent::F_JAVASCRIPT];
                    }
                    
                    $hasSomething = $this->hasSomething($value);
                    $hasStartingConfigsAfter = $this->hasStartingConfigsAfter($value);
                    
                    if( ( $hasSomething == true ) or ( $hasStartingConfigsAfter == true ) ){
                        
                        // start delay 
                        $this->generatedScript .= $this->linebreak.$this->tab.'setTimeout(function(){';
                    
                        if($value[parent::F_OUTPUT_POS]=='a'){ // after
                            
                            $this->generatedScript .= $this->getStartingPositions($value);
                           
                        }
                    
                        if( $value[parent::F_OUTPUT] == 'a' ){ // after 
                            
                            // Replace parameters
                            $pattern = "/twizRepeat\((.*?)\)/";
                            preg_match($pattern, $value[parent::F_JAVASCRIPT], $params);
                            if (!isset($params[1])){$params[1] = '';}
                            if ( $params[1] == '' ) { $params[1] = 'null'; }
                
                            // js     
                            $value[parent::F_JAVASCRIPT] = str_replace("$(document).twizRepeat(", "$(document).twiz_".$name.'(twiz'.$this_prefix.'_this,'.$params[1].',e', $value[parent::F_JAVASCRIPT]);
                            
                            $value[parent::F_JAVASCRIPT] = str_replace(",e".$params[1]."", ",e" , $value[parent::F_JAVASCRIPT]);
                            
                            $value[parent::F_JAVASCRIPT] = str_replace("$(document).twizReplay(", "$(document).twizReplay_".$value[parent::F_SECTION_ID] .'(' , $value[parent::F_JAVASCRIPT].self::COMPRESS_LINEBREAK);
                            
                            $this->generatedScript .= $value[parent::F_JAVASCRIPT];
                            
                        }
                        
                        $value[parent::F_OPTIONS_A] = (($value[parent::F_OPTIONS_A]!='') and (($value[parent::F_MOVE_LEFT_POS_A]!="") or ($value[parent::F_MOVE_TOP_POS_A]!=""))) ? ','.$value[parent::F_OPTIONS_A] :  $value[parent::F_OPTIONS_A];
                        $value[parent::F_OPTIONS_A] = str_replace(self::COMPRESS_LINEBREAK, $this->linebreak.$this->tab.",", $value[parent::F_OPTIONS_A]);
                    
                        // replace numeric entities   
                        $value[parent::F_OPTIONS_A] = $this->replaceNumericEntities($value[parent::F_OPTIONS_A]);

                        $hasMovements = $this->hasMovements($value);
                        
                        if( $hasMovements == true ){
                            
                            if($value[parent::F_MOVE_ELEMENT_A] == ''){
                            
                                $moveElementFormat_a = 'twiz_element_'.$name;
                                
                            }else{ // Attach a different element.
                            
                                $moveElementFormat_a = str_replace('"', '\"', $this->replacejElementType($value[parent::F_MOVE_ELEMENT_TYPE_A], $value[parent::F_MOVE_ELEMENT_A]));
                            }
            
                            // animate jquery a 
                            $this->generatedScript .= $this->linebreak.$this->tab.'$(eval($("<div />").html("'.$moveElementFormat_a.'").text())).'.$this->stop.$this->animate.'({';

                            $value[parent::F_MOVE_TOP_POS_SIGN_A] = ($value[parent::F_MOVE_TOP_POS_SIGN_A]!='')? $value[parent::F_MOVE_TOP_POS_SIGN_A].'=' : '';
                            $value[parent::F_MOVE_LEFT_POS_SIGN_A] = ($value[parent::F_MOVE_LEFT_POS_SIGN_A]!='')? $value[parent::F_MOVE_LEFT_POS_SIGN_A].'=' : '';

                            $this->generatedScript .= ($value[parent::F_MOVE_LEFT_POS_A]!="") ? $this->x.': "'.$value[parent::F_MOVE_LEFT_POS_SIGN_A].$value[parent::F_MOVE_LEFT_POS_A].$value[parent::F_MOVE_LEFT_POS_FORMAT_A].'"' : '';
                            $this->generatedScript .= (($value[parent::F_MOVE_LEFT_POS_A]!="") and ($value[parent::F_MOVE_TOP_POS_A]!="")) ? ',' : '';
                            $this->generatedScript .= ($value[parent::F_MOVE_TOP_POS_A]!="") ? $this->y.': "'.$value[parent::F_MOVE_TOP_POS_SIGN_A].$value[parent::F_MOVE_TOP_POS_A].$value[parent::F_MOVE_TOP_POS_FORMAT_A].'"' : '';
                            $this->generatedScript .= $value[parent::F_OPTIONS_A];
                            
                            $this->generatedScript .= $this->linebreak.$this->tab.'},'.$value[parent::F_DURATION].',"'.$value[parent::F_EASING_A].'", function(){ ';
                            $value[parent::F_EXTRA_JS_A] = ($value[parent::F_EXTRA_JS_A] != '') ? $this->linebreak.$value[parent::F_EXTRA_JS_A].self::COMPRESS_LINEBREAK : '';
                            // replace numeric entities
                            $value[parent::F_EXTRA_JS_A] = $this->replaceNumericEntities($value[parent::F_EXTRA_JS_A]);
                

                            // Replace parameters
                            $pattern = "/twizRepeat\((.*?)\)/";
                            preg_match($pattern, $value[parent::F_EXTRA_JS_A], $params);
                            if (!isset($params[1])){$params[1] = '';}
                            if ( $params[1] == '' ) { $params[1] = 'null'; }
                
                            // extra js a    
                            $value[parent::F_EXTRA_JS_A] = str_replace("$(document).twizRepeat(", "$(document).twiz_".$name.'(twiz'.$this_prefix.'_this,'.$params[1].',e', $value[parent::F_EXTRA_JS_A]);
                            
                            $value[parent::F_EXTRA_JS_A] = str_replace(",e".$params[1]."", ",e" , $value[parent::F_EXTRA_JS_A]);
                            
                            $value[parent::F_EXTRA_JS_A] = str_replace("$(document).twizReplay(", "$(document).twizReplay_".$value[parent::F_SECTION_ID] .'(' , $value[parent::F_EXTRA_JS_A]);
                            
                            $this->generatedScript .= $value[parent::F_EXTRA_JS_A];
                            
       
                            // b
                                        
                            $has_b = (($value[parent::F_MOVE_TOP_POS_B] !='' ) or ( $value[parent::F_MOVE_LEFT_POS_B] !='' ) or ( $value[parent::F_OPTIONS_B] !='' ) or ( $value[parent::F_EXTRA_JS_B] !='' )) ? true : false;
                            
                            // add a coma between each options 
                            $value[parent::F_OPTIONS_B] = (($value[parent::F_OPTIONS_B] != '') and ((($value[parent::F_MOVE_LEFT_POS_B]!="") or ($value[parent::F_MOVE_TOP_POS_B]!="")))) ? ','.$value[parent::F_OPTIONS_B] : $value[parent::F_OPTIONS_B];
                            $value[parent::F_OPTIONS_B] = str_replace(self::COMPRESS_LINEBREAK, $this->linebreak.$this->tab.$this->tab."," , $value[parent::F_OPTIONS_B]);
                            
                            // replace numeric entities              
                            $value[parent::F_OPTIONS_B] = $this->replaceNumericEntities($value[parent::F_OPTIONS_B]);
                            
                            // animate jquery b
                            if($value[parent::F_MOVE_ELEMENT_B] == ''){
                            
                                $moveElementFormat_b = 'twiz_element_'.$name;
                                
                            }else{ // Attach a different element.
                            
                                $moveElementFormat_b = str_replace('"', '\"', $this->replacejElementType($value[parent::F_MOVE_ELEMENT_TYPE_B], $value[parent::F_MOVE_ELEMENT_B]));
                            }
                            $this->generatedScript .= $this->linebreak.$this->tab.$this->tab.'$(eval($("<div />").html("'.$moveElementFormat_a.'").text())).'.$this->stop.$this->animate.'({';

                            $value[parent::F_MOVE_TOP_POS_SIGN_B] = ($value[parent::F_MOVE_TOP_POS_SIGN_B]!='')? $value[parent::F_MOVE_TOP_POS_SIGN_B].'=' : '';
                            $value[parent::F_MOVE_LEFT_POS_SIGN_B] = ($value[parent::F_MOVE_LEFT_POS_SIGN_B]!='')? $value[parent::F_MOVE_LEFT_POS_SIGN_B].'=' : '';

                            $this->generatedScript .= ($value[parent::F_MOVE_LEFT_POS_B]!="") ? $this->x.': "'.$value[parent::F_MOVE_LEFT_POS_SIGN_B].$value[parent::F_MOVE_LEFT_POS_B].$value[parent::F_MOVE_LEFT_POS_FORMAT_B].'"' : '';
                            $this->generatedScript .= (($value[parent::F_MOVE_LEFT_POS_B]!="") and ($value[parent::F_MOVE_TOP_POS_B]!="")) ? ',' : '';
                            $this->generatedScript .= ($value[parent::F_MOVE_TOP_POS_B]!="") ? $this->y.': "'.$value[parent::F_MOVE_TOP_POS_SIGN_B].$value[parent::F_MOVE_TOP_POS_B].$value[parent::F_MOVE_TOP_POS_FORMAT_B].'"' : '';
                            $this->generatedScript .=  $value[parent::F_OPTIONS_B];
                            
                            // set to sero
                            $value[parent::F_DURATION] = (!$has_b)? '0' : $value[parent::F_DURATION];
                            $value[parent::F_EASING_B] = (!$has_b)? '' : $value[parent::F_EASING_B];
                            
                            // set same duration(or 0) if second one is empty
                            $duration_b = ($value[parent::F_DURATION_B] != "") ? $value[parent::F_DURATION_B] : $value[parent::F_DURATION];
                            
                            $this->generatedScript .= $this->linebreak.$this->tab.$this->tab.'},'.$duration_b.',"'.$value[parent::F_EASING_B].'", function(){ ';
                                
                            $value[parent::F_EXTRA_JS_B] = ($value[parent::F_EXTRA_JS_B] != '') ? $this->linebreak.$value[parent::F_EXTRA_JS_B].self::COMPRESS_LINEBREAK : '';
                   
                            // replace numeric entities
                            $value[parent::F_EXTRA_JS_B] = $this->replaceNumericEntities($value[parent::F_EXTRA_JS_B]);
                            
                            if( ( $value[parent::F_LOCK_EVENT] == '1' ) 
                            and ( ( $value[parent::F_LOCK_EVENT_TYPE] == 'auto') 
                            and ( $value[parent::F_ON_EVENT] != '') 
                            and ( $value[parent::F_ON_EVENT] != 'Manually') ) ){                          
                                if($has_b){
                                
                                     $this->generatedScript .= $this->linebreak.$this->tab.$this->tab.$this->tab.'if((twiz_repeat_'.$name.' == 0) || (twiz_repeat_'.$name.' == null)){ '.$this->linebreak.$this->tab.$this->tab.$this->tab.$this->tab.'twiz_locked_'.$name.' = 0;'.$this->linebreak.$this->tab.$this->tab.$this->tab.' } ';
                                     $has_active = true;
                                }
                            }
                            
                            // Replace parameters
                            $pattern = "/twizRepeat\((.*?)\)/";
                            preg_match($pattern, $value[parent::F_EXTRA_JS_B], $params);
                            if (!isset($params[1])){$params[1] = '';}
                            if ( $params[1] == '' ) { $params[1] = 'null'; }
                
                            // extra js b    
                            $value[parent::F_EXTRA_JS_B] = str_replace("$(document).twizRepeat(", $this->tab.$this->tab."$(document).twiz_".$name.'(twiz'.$this_prefix.'_this,'.$params[1].',e', $value[parent::F_EXTRA_JS_B]);
                            
                            $value[parent::F_EXTRA_JS_B] = str_replace(",e".$params[1]."", ",e" , $value[parent::F_EXTRA_JS_B]);
                            
                            $value[parent::F_EXTRA_JS_B] = str_replace("$(document).twizReplay(", $this->tab.$this->tab."$(document).twizReplay_".$value[parent::F_SECTION_ID] .'(' , $value[parent::F_EXTRA_JS_B]);
                            
                            $this->generatedScript .= $value[parent::F_EXTRA_JS_B];
                            
                            // substract repeat var
                            if( $has_b ){  
                                $this->generatedScript .= $this->linebreak.$this->tab.$this->tab.$this->tab.'if(twiz_repeat_'.$name.' > 0){ ';
                                $this->generatedScript .= $this->linebreak.$this->tab.$this->tab.$this->tab.$this->tab.'twiz_repeat_'.$name.'--;'.$this->linebreak.$this->tab.$this->tab.$this->tab.'}'.$this->linebreak;
                            }
                            
                            // closing functions
                            $this->generatedScript .= $this->tab.$this->tab.'});'.$this->linebreak;
                            
                            if( ( $value[parent::F_LOCK_EVENT] == '1' ) 
                            and ( ( $value[parent::F_LOCK_EVENT_TYPE] == 'auto') 
                            and ( $value[parent::F_ON_EVENT] != '') 
                            and ( $value[parent::F_ON_EVENT] != 'Manually') ) ){   
                                if( !$has_b ){                       
                                    $this->generatedScript .= $this->tab.'if((twiz_repeat_'.$name.' == 0) || (twiz_repeat_'.$name.' == null)){ twiz_locked_'.$name.' = 0;}'.$this->linebreak;
                                    $has_active = true;
                                }
                            }
                            
                            // substract repeat var
                            if( !$has_b ){  
                                $this->generatedScript .= $this->linebreak.'if(twiz_repeat_'.$name.' > 0){ ';
                                $this->generatedScript .= $this->linebreak.$this->tab.'twiz_repeat_'.$name.'--;'.$this->linebreak.$this->tab.'}'.$this->linebreak;
                            }                            
                            
                            $this->generatedScript .= $this->tab.'});'.$this->linebreak;
                        }
                        
                        // Closing timout
                        $this->generatedScript .= $this->tab.'},'.$value[parent::F_START_DELAY].');'.$this->linebreak;

                    }
                    
                    if( ( $value[parent::F_LOCK_EVENT] == '1' ) 
                    and ( ( $value[parent::F_LOCK_EVENT_TYPE] == 'auto') 
                    and ( $value[parent::F_ON_EVENT] != '') 
                    and ( $value[parent::F_ON_EVENT] != 'Manually') ) ){                   
                    
                        if( $has_active != true ){
                       
                            $this->generatedScript .= $this->linebreak.$this->tab.'if((twiz_repeat_'.$name.' == 0) || (twiz_repeat_'.$name.' == null)){twiz_locked_'.$name.' = 0;}';
                            
                        }
                    }

                    
                    // closing functions
                    $this->generatedScript .= '}}'.self::COMPRESS_LINEBREAK;
                    
                    $this->generatedScriptonEvent .= $this->getOnEventFunction( $value, $name );
                    
                } // End if hasDisabledCode
                
            } // End loop
            
            $this->generatedScript .= $this->generatedCookie;
            $this->generatedScript .= $this->getReplayFunctions();
            $this->generatedScript .= $this->getGroupFunctions();
            $this->generatedScript .= $this->generatedScriptonEvent;
            $this->generatedScript .= $this->getJavaScriptonReady();
            $this->generatedScript .= $this->generatedScriptonReady;
            $this->generatedScript .= $this->linebreak.'});</script>';
            $this->generatedScript .= $this->getStyleCSS();
            $this->generatedScript = str_replace( '[GLOBAL_JS]', $this->global_js, $this->generatedScript ); 
        }
        
        return $this->generatedScript;
    }
    
    private function getStyleCSS(){
    
        $looped = 0;
        $newElementFormat = '';
        $generatedScript = $this->linebreak.'<style type="text/css">';
        
        // generates the code
        foreach( $this->listarray as $value ){   

            $hasRestrictedCode = $this->searchRestrictedCode($value);
            $hasValidParendId = $this->validateParentId($value[parent::F_PARENT_ID]);

            if( ( ($hasRestrictedCode) and ($this->admin_option[parent::KEY_OUTPUT_PROTECTED] == '1' ) ) 
            or ( $value[parent::F_TYPE] ==  parent::ELEMENT_TYPE_GROUP ) ){ // skip group
            // Nothing to do
            }else{

                if( ( $hasValidParendId == true )and( $value[parent::F_CSS] != '' ) ){    
                 
                    $generatedScript .= $this->linebreak.$this->replaceNumericEntities($value[parent::F_CSS]);
                    $looped = 1;
                }
            
                if( ( $hasValidParendId == true )and( $value[parent::F_OUTPUT_POS] == 'c' ) ){ 
                
                    if(($value[parent::F_POSITION]!='') 
                    or ($value[parent::F_ZINDEX]!='') 
                    or ($value[parent::F_START_LEFT_POS]!='') 
                    or ($value[parent::F_START_TOP_POS]!='')) {

                        if($value[parent::F_START_ELEMENT] == ''){
                        
                            $newElementFormat = $this->replacejElementType($value[parent::F_TYPE], $value[parent::F_LAYER_ID]);
                            
                        }else{ // Attach a different element.
                        
                            $newElementFormat = $this->replacejElementType($value[parent::F_START_ELEMENT_TYPE], $value[parent::F_START_ELEMENT]);
                        }

                        $newElementFormat = str_replace(array('"'
                                                             ,'\''
                                                             ,'&quot;'
                                                             ,'&#39;'
                                                             ), '', $newElementFormat);
                        
                        $generatedScript .= $this->linebreak.$newElementFormat.'{';
                        
                        if($value[parent::F_POSITION]!=''){
                        
                            $generatedScript .= $this->linebreak.$this->tab.'position:'.$value[parent::F_POSITION].';';
                        }
                        
                        if($value[parent::F_ZINDEX]!=''){
                        
                            $generatedScript .= $this->linebreak.$this->tab.'z-index:'.$value[parent::F_ZINDEX].';';
                        }
                        
                        if($value[parent::F_START_LEFT_POS]!=''){
                        
                            $generatedScript .=  $this->linebreak.$this->tab.$this->css_x.':'.$value[parent::F_START_LEFT_POS_SIGN].$value[parent::F_START_LEFT_POS].$value[parent::F_START_LEFT_POS_FORMAT].';';
                        }
                        
                        if($value[parent::F_START_TOP_POS]!=''){
                        
                            $generatedScript .= $this->linebreak.$this->tab.$this->css_y.':'.$value[parent::F_START_TOP_POS_SIGN].$value[parent::F_START_TOP_POS].$value[parent::F_START_TOP_POS_FORMAT].';';
                        }
                        
                        $generatedScript .= $this->linebreak.'}';
                        
                        $looped = 1;
                    }       
                }                
            }
        }
        
        $generatedScript = $generatedScript.$this->linebreak.'</style>'.$this->linebreak;
        
        $generatedScript = ( $looped == 1 ) ? $generatedScript : '';
        
        return $generatedScript;
    }       
      
    private function getJavaScriptonReady(){
    
        $generatedScript = '';
        $this_prefix = '';
        
        if( $this->shortcode_id != '' ){

            $this_prefix = '_'.$this->listarray[0][parent::F_SECTION_ID];
        }
                    
        // generates the code
        foreach( $this->listarray as $value ){   

            $sections = $this->getSectionArray($value[parent::F_SECTION_ID]);
                    
            $hasRestrictedCode = $this->searchRestrictedCode($value);
            $hasValidParendId = $this->validateParentId($value[parent::F_PARENT_ID]);

            if( ( ($hasRestrictedCode) and ($this->admin_option[parent::KEY_OUTPUT_PROTECTED] == '1' ) ) 
            or ( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] == true ) // cookie condition true
            or (( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] != true ) and ( $this->PHPCookieMax[$sections[$value[parent::F_SECTION_ID]][parent::KEY_COOKIE_CONDITION]] == true ) ) // cookie condition true
            or ( $value[parent::F_TYPE] ==  parent::ELEMENT_TYPE_GROUP ) ){ // skip group
            // Nothing to do
            }else if($hasValidParendId == true){    
             
                if( $value[parent::F_OUTPUT] == 'r' ){ // onready 
                    
                    $generatedCondition = $this->generateJSCookieCondition($value[parent::F_SECTION_ID]);
                    
                    $name = $value[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($value[parent::F_LAYER_ID]))."_".$value[parent::F_EXPORT_ID];
                    
                    // replace numeric entities
                    $value[parent::F_JAVASCRIPT] = $this->replaceNumericEntities($value[parent::F_JAVASCRIPT]);
                    $value[parent::F_JAVASCRIPT] = ($value[parent::F_JAVASCRIPT] != '') ? $this->linebreak.$value[parent::F_JAVASCRIPT] : '';
                    
                    // Replace parameters
                    $pattern = "/twizRepeat\((.*?)\)/";
                    preg_match($pattern, $value[parent::F_JAVASCRIPT], $params);
                    if (!isset($params[1])){$params[1] = '';}
                    if ( $params[1] == '' ) { $params[1] = 'null'; }

                    // js     
                    $value[parent::F_JAVASCRIPT] = $generatedCondition['open'].str_replace("$(document).twizRepeat(", "$(document).twiz_".$name.'(twiz'.$this_prefix.'_this,'.$params[1].',e', $value[parent::F_JAVASCRIPT]);
                    
                    $value[parent::F_JAVASCRIPT] = str_replace(",e".$params[1]."", ",e" , $value[parent::F_JAVASCRIPT]);
                    
                    $value[parent::F_JAVASCRIPT] = str_replace("$(document).twizReplay(", $this->tab."$(document).twizReplay_".$value[parent::F_SECTION_ID] .'(' , $value[parent::F_JAVASCRIPT]);
                    
                    $generatedScript .= $value[parent::F_JAVASCRIPT];
                            
                    $generatedScript .= self::COMPRESS_LINEBREAK.$generatedCondition['close'];
                }   
            }
        }

        return $generatedScript;
    }      
    
    private function getOnEventFunction( $value = '' , $name = '' ){
    
        $generatedScript = '';
        $generatedCondition = $this->generateJSCookieCondition($value[parent::F_SECTION_ID]);
        
         // trigger on event
        if( $value[parent::F_ON_EVENT] != '' ){
        
           if( $value[parent::F_ON_EVENT] != parent::EV_MANUAL ){
           
                $generatedScript .= 'var twiz_event_'.$name.' = (function(e){'.$this->linebreak;
               
                if( ( $value[parent::F_LOCK_EVENT] == '1' ) 
                and ( ( $value[parent::F_ON_EVENT] != '') 
                and ( $value[parent::F_ON_EVENT] != 'Manually') ) ){ 
                   
                   $generatedScript .= $this->tab.'if(twiz_locked_'.$name.' == 0){'.$this->linebreak;
                   $generatedScript .= $this->tab.$this->tab.'twiz_locked_'.$name.' = 1;'.$this->linebreak;
                   $generatedScript .= $this->tab.$this->tab.'$(document).twiz_'.$name.'(this,null,e);'.$this->linebreak.$this->tab.'}';
                   
                }else{  
                
                   $generatedScript .= $this->tab.$this->tab.'$(document).twiz_'.$name.'(this,null,e);'.$this->linebreak;
                }
                
                $generatedScript .= $this->linebreak.'});'.$this->linebreak;
               
                $generatedScript .= $generatedCondition['open'].'$(eval($("<div />").html("'.$this->newElementFormat.'").text())).bind("'.strtolower($value[parent::F_ON_EVENT]).'", twiz_event_'.$name.');'.$generatedCondition['close'].$this->linebreak;
                       
           }
           
        } else{
        
            if( ( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] == false )
            or (( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] != true ) and ( $this->PHPCookieMax[$sections[$value[parent::F_SECTION_ID]][parent::KEY_COOKIE_CONDITION]] == true ) ) ){ // cookie condition true
            
                // trigger the animation if not on event
                $this->generatedScriptonReady .=  $generatedCondition['open'].$this->linebreak.'$(document).twiz_'.$name.'($(eval($("<div />").html("'.$this->newElementFormat.'").text())),null);'.$generatedCondition['close'];
                
            }
        }  
        
        return $generatedScript;
    }
    
    private function generateSQLMultiSections( $sectionid = '', $shortcode_id = ''){
        
        $comma = ',';
        $and_multi_sections = '';
        $field_key = parent::F_SECTION_ID.' IN(';
        
        foreach($this->multi_sections as $key => $value){
        
            if( is_array($value) ){ // multi output

                foreach($value as $key_val => $value_val){
                
                    if( !isset($this->sections[$key]) ){$this->sections[$key][parent::F_STATUS] = '';}
                    
                    if( ($sectionid == $value_val)
                    and ($this->sections[$key][parent::F_STATUS] == parent::STATUS_ACTIVE) ){

                        $and_multi_sections .= $field_key."'".$key."'".$comma;
                        $field_key = '';
                        
                    }
                }
                
            }else{ 
            
                list( $type, $id ) = preg_split('/_/', $key);
                
                switch ($type){
                
                    case 'cl'; // custom logic

                        if( ( $shortcode_id == '' )
                        and ($this->sections[$key][parent::F_STATUS] == parent::STATUS_ACTIVE) ){
                        
                            $islogic = $this->evaluateCustomLogic($value);
                        
                            if($islogic){
                        
                                $and_multi_sections .= $field_key."'".$key."'".$comma;
                                $field_key = '';
                                
                            }
                        }
                        break;
                        
                    case 'sc': // short code
                        
                        if( ($sectionid == $key)
                        and ($this->sections[$key][parent::F_STATUS] == parent::STATUS_ACTIVE) ){
                       
                            if( $shortcode_id != '' ){                        

                                $and_multi_sections .= $field_key."'".$key."'".$comma;
                                $field_key = '';
                                
                            }                
                        }
                        break;
                }
            }
        }
        
        $and_multi_sections .= ( $field_key == '' ) ? ') ' : '';
        $and_multi_sections = str_replace(",)", ")", $and_multi_sections);

        return $and_multi_sections;
    }
    
    private function evaluateCustomLogic( $customlogic = '' ) {
    
        $customlogic = 'return (' . $customlogic . ');';
        
        $customlogic =  @eval($customlogic);
     
        return $customlogic;
    }
    
    private function removeDuplicates( $sections = '' )
    {
        if( !is_array($sections) ){
        
            return $sections;
        }
        
        foreach($sections as &$value ){
        
            $value = serialize($value);
        }

        $sections = array_unique($sections);

        foreach( $sections as &$value ){
        
            $value = unserialize($value);
        }

        return $sections;
    } 
    
    private function getSectionIdByShortCode( $shortcode_id = '' ){
    
        foreach( $this->multi_sections as $key => $value){
        
            list( $type, $id ) = preg_split('/_/', $key);
            
            if(($type == 'sc')
            and($value == $shortcode_id)){ // short code
                
                return $key;
            }        
        }
    }
    
    private function getCurrentList( $shortcode_id = '' ){
    
        global $post;
                   
        wp_reset_query(); // fix is_home() due to a custom query.
        
        $and_multi_sections = '';
        $and_shortcode = '';
        $section_id = '';
        
        if( $shortcode_id != '' ){
        
            $section_id = $this->getSectionIdByShortCode($shortcode_id);
            $and_shortcode = $this->generateSQLMultiSections($section_id, $shortcode_id);
            
            if( $and_shortcode != '' ){
            
                $listarray_sc = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".$and_shortcode." ");
                
            }else{
            
                $listarray_sc = array();
            }            
        }
        
        if( $shortcode_id == '' ){
        
            $and_multi_sections = $this->generateSQLMultiSections(parent::DEFAULT_SECTION_EVERYWHERE);

            if( $and_multi_sections != '' ){
            
                $listarray_e_m = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".$and_multi_sections." ");
                
            }else{
            
                $listarray_e_m = array();
            }
            
            if($this->hardsections[parent::DEFAULT_SECTION_EVERYWHERE][parent::F_STATUS] == parent::STATUS_ACTIVE){

                $listarray_e = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".parent::DEFAULT_SECTION_EVERYWHERE."' ");
                
            }else{
                $listarray_e = array ();
            }

        
            switch( true ){

                case ( is_home() || is_front_page() ):

                    $and_multi_sections = $this->generateSQLMultiSections(parent::DEFAULT_SECTION_HOME);
                    
                    if( $and_multi_sections != '' ){
                    
                        $listarray_h_m = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".$and_multi_sections." ");
                    }else{
                        $listarray_h_m = array();
                    }
            
                    if($this->hardsections[parent::DEFAULT_SECTION_HOME][parent::F_STATUS] == parent::STATUS_ACTIVE){
                    
                        // get the active data list array
                        $listarray_h = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".parent::DEFAULT_SECTION_HOME."' ");
                    }else{
                        $listarray_h = array();
                    }
       
                    $this->listarray = array_merge($listarray_e, $listarray_e_m, $listarray_h, $listarray_h_m);
                   
                    break;
                    
                case is_category():

                    $and_multi_sections = $this->generateSQLMultiSections(parent::DEFAULT_SECTION_ALL_CATEGORIES);
                    
                    if( $and_multi_sections != '' ){
                    
                        $listarray_allc_m = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".$and_multi_sections." ");
                    }else{
                        $listarray_allc_m = array();
                    }
                    
                    $category_id = 'c_'.get_query_var('cat');
                    
                    if($this->hardsections[parent::DEFAULT_SECTION_ALL_CATEGORIES][parent::F_STATUS] == parent::STATUS_ACTIVE){
                    
                        // get the active data list array
                        $listarray_allc = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".parent::DEFAULT_SECTION_ALL_CATEGORIES."' ");
                    
                    }else{
                        $listarray_allc = array();
                    }
                    
                    $and_multi_sections = $this->generateSQLMultiSections($category_id);
                    
                    if( $and_multi_sections != '' ){
                    
                        $listarray_c_m = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".$and_multi_sections." ");
                    }else{
                        $listarray_c_m = array();
                    }
                    
                    if( !isset($this->sections[$category_id]) ) $this->sections[$category_id][parent::F_STATUS] = parent::STATUS_INACTIVE;
                    if($this->sections[$category_id][parent::F_STATUS] == parent::STATUS_ACTIVE){                
                        $listarray_c = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".$category_id."' ");
                    }else{
                        $listarray_c = array();
                    }
                    
                    $listarray_T = array_merge($listarray_e, $listarray_e_m, $listarray_c, $listarray_c_m);
                    $this->listarray = array_merge($listarray_T, $listarray_allc, $listarray_allc_m);
                    
                    break;
                    
                case is_page():

                    $and_multi_sections = $this->generateSQLMultiSections(parent::DEFAULT_SECTION_ALL_PAGES);
                    
                    if( $and_multi_sections != '' ){

                        $listarray_allp_m = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".$and_multi_sections." ");
                    }else{
                        $listarray_allp_m = array();
                    }
                    
                    $page_id = 'p_'.$post->ID;
                    
                    if($this->hardsections[parent::DEFAULT_SECTION_ALL_PAGES][parent::F_STATUS] == parent::STATUS_ACTIVE){
                    
                        // get the active data list array
                        $listarray_allp = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".parent::DEFAULT_SECTION_ALL_PAGES."' ");
                    }else{
                        $listarray_allp = array();
                    }

                    $and_multi_sections = $this->generateSQLMultiSections($page_id);
                    
                    if( $and_multi_sections != '' ){
                    
                        $listarray_p_m = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".$and_multi_sections." ");
                    }else{
                        $listarray_p_m = array();
                    }
                    
                    if( !isset($this->sections[$page_id]) ) $this->sections[$page_id][parent::F_STATUS] = parent::STATUS_INACTIVE;
                    if($this->sections[$page_id][parent::F_STATUS] == parent::STATUS_ACTIVE){                 
                        $listarray_p = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".$page_id."' ");
                    }else{
                        $listarray_p = array();
                    }
                    
                    $listarray_T = array_merge($listarray_e, $listarray_e_m, $listarray_p, $listarray_p_m);
                    $this->listarray = array_merge($listarray_T, $listarray_allp, $listarray_allp_m);
                    
                    break;

                case is_single(): 

                    $and_multi_sections = $this->generateSQLMultiSections(parent::DEFAULT_SECTION_ALL_ARTICLES);
                    
                    if( $and_multi_sections != '' ){
                    
                        $listarray_alla_m = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".$and_multi_sections." ");
                    }else{
                        $listarray_alla_m = array();
                    }
                    
                    $post_id = 'a_'.$post->ID;
                    
                    if($this->hardsections[parent::DEFAULT_SECTION_ALL_ARTICLES][parent::F_STATUS] == parent::STATUS_ACTIVE){
                        // get the active data list array
                        $listarray_alla = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".parent::DEFAULT_SECTION_ALL_ARTICLES."' ");
                    }else{
                        $listarray_alla = array();
                    }
                    
                    $and_multi_sections = $this->generateSQLMultiSections($post_id);
                    
                    if( $and_multi_sections != '' ){
                    
                        $listarray_a_m = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".$and_multi_sections." ");
                    }else{
                        $listarray_a_m = array();
                    }                
                    
                    if( !isset($this->sections[$post_id]) ) $this->sections[$post_id][parent::F_STATUS] = parent::STATUS_INACTIVE;
                    if($this->sections[$post_id][parent::F_STATUS] == parent::STATUS_ACTIVE){                   
                        $listarray_a = $this->getListArray(" where ".parent::F_STATUS." = 1 and ".parent::F_SECTION_ID." = '".$post_id."' ");
                    }else{
                        $listarray_a = array();
                    }
                    
                    $listarray_T = array_merge($listarray_e, $listarray_e_m, $listarray_a, $listarray_a_m);
                    $this->listarray = array_merge($listarray_T, $listarray_alla, $listarray_alla_m);
                    break;
                    
                case is_feed(): 
                    
                    return '';
                    
                    break;
            }
        
            $this->listarray = ( is_array($this->listarray) ) ? $this->listarray : array_merge($listarray_e, $listarray_e_m);
            $this->listarray = $this->removeDuplicates($this->listarray);
            
        }else{ // shortcode
        
            $this->listarray = $listarray_sc;
        }
        
        return $this->listarray;
    }
    
    private function getGroupFunctions(){
    
        $generatedScript = '';
        $generatedScript_function = array();
        $generatedScript_repeatvar = '';
        
        $groupid = '';
        
        foreach( $this->listarray as $value ){
        
            $sections = $this->getSectionArray($value[parent::F_SECTION_ID]);        
            $hasRestrictedCode = $this->searchRestrictedCode($value);
                
            if( ( ($hasRestrictedCode) and ($this->admin_option[parent::KEY_OUTPUT_PROTECTED] == '1' ) ) 
            or ( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] == true )  // cookie condition true
            or (( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] != true ) and ( $this->PHPCookieMax[$sections[$value[parent::F_SECTION_ID]][parent::KEY_COOKIE_CONDITION]] == true ) ) ){ // cookie condition true
            // Nothing to do
            }else if( (( $value[parent::F_TYPE] ==  parent::ELEMENT_TYPE_GROUP ) or ( $value[parent::F_PARENT_ID] != '' ))
            and (( $value[parent::F_ON_EVENT] == '' ) or ( $value[parent::F_ON_EVENT] == parent::EV_MANUAL )) ){
                
                $name = $value[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($value[parent::F_LAYER_ID]))."_".$value[parent::F_EXPORT_ID];
                
                if( $value[parent::F_TYPE] ==  parent::ELEMENT_TYPE_GROUP ) {
                
                    $groupid = $value[parent::F_EXPORT_ID];
                    
                    if(!isset($generatedScript_function[$groupid] )) $generatedScript_function[$groupid]  = '';
                    
                    $generatedScript_function[$groupid] = '$.fn.twiz_group_'.$name .' = function(){[STRINGTOREPLACE]'.$this->linebreak.'}'.self::COMPRESS_LINEBREAK;

                }else{
                
                    $groupid = $value[parent::F_PARENT_ID];
                    
                    if(!isset($generatedScript_repeatvar[$groupid] )) $generatedScript_repeatvar[$groupid]  = '';
                    if(!isset($generatedScript[$groupid] )) $generatedScript[$groupid]  = '';
                    
                    $newElementFormat = str_replace('"', '\"', $this->replacejElementType($value[parent::F_TYPE], $value[parent::F_LAYER_ID]));
                    $generatedScript_repeatvar[$groupid] .= $this->linebreak.$this->tab.'twiz_repeat_'.$name.' = null;';
                    $generatedScript[$groupid] .= $this->linebreak.$this->tab.'$(document).twiz_'.$name.'($(eval($("<div />").html("'.$newElementFormat.'").text())), null);';
                }
            }
        }

        foreach ($generatedScript_function as $key => $value ){
        
            if(!isset($generatedScript_repeatvar[$key] )) $generatedScript_repeatvar[$key]  = '';
            if(!isset($generatedScript[$key] )) $generatedScript[$key]  = '';
            
            $generatedScript_function[$key] = str_replace('[STRINGTOREPLACE]', $generatedScript_repeatvar[$key].$generatedScript[$key], $value);
        }

        $generatedScript_function = implode('', $generatedScript_function);
        
        
        return $generatedScript_function;
    }
    
    private function getReplayFunctions(){
   
        $generatedScript = '';
        $generatedScript_function = array();
        $generatedScript_repeatvar = '';

        
        foreach( $this->listarray as $value ){

            $sections = $this->getSectionArray($value[parent::F_SECTION_ID]);        
            
            $hasRestrictedCode = $this->searchRestrictedCode($value);
            $hasValidParendId = $this->validateParentId($value[parent::F_PARENT_ID]);

            if( ( ($hasRestrictedCode) and ($this->admin_option[parent::KEY_OUTPUT_PROTECTED] == '1' ) ) 
            or ( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] == true ) // cookie condition true
            or (( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] != true ) and ( $this->PHPCookieMax[$sections[$value[parent::F_SECTION_ID]][parent::KEY_COOKIE_CONDITION]] == true ) ) // cookie condition true
            or ( $value[parent::F_TYPE] ==  parent::ELEMENT_TYPE_GROUP ) ){ // skip group
            // Nothing to do
            }else if($hasValidParendId == true){  
            
                // Excluding only repeated animations that are running forever.(manually called are not verified)
                $pos = strpos($value[parent::F_JAVASCRIPT], "$(document).twizRepeat()");
                $posa = strpos($value[parent::F_EXTRA_JS_A], "$(document).twizRepeat()");
                $posb = strpos($value[parent::F_EXTRA_JS_B], "$(document).twizRepeat()");
                
                if(!isset($generatedScript_function[$value[parent::F_SECTION_ID]] )) $generatedScript_function[$value[parent::F_SECTION_ID]]  = '';
                if(!isset($generatedScript_repeatvar[$value[parent::F_SECTION_ID]] )) $generatedScript_repeatvar[$value[parent::F_SECTION_ID]]  = '';
                if(!isset($generatedScript[$value[parent::F_SECTION_ID]] )) $generatedScript[$value[parent::F_SECTION_ID]]  = '';

                $generatedScript_function[$value[parent::F_SECTION_ID]] = '$.fn.twizReplay_'.$value[parent::F_SECTION_ID].' = function(){';

                if (($pos === false) 
                and ($posa === false) 
                and ($posb === false)
                and ( $value[parent::F_ON_EVENT] == '' ) ) {

                    $newElementFormat = str_replace('"', '\"', $this->replacejElementType($value[parent::F_TYPE], $value[parent::F_LAYER_ID]));
                    $name = $value[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($value[parent::F_LAYER_ID]))."_".$value[parent::F_EXPORT_ID];
                    
                    $generatedScript_repeatvar[$value[parent::F_SECTION_ID]] .= $this->linebreak.$this->tab.'twiz_repeat_'.$name.' = null;';
                    $generatedScript[$value[parent::F_SECTION_ID]] .= $this->linebreak.$this->tab.'$(document).twiz_'.$name.'($(eval($("<div />").html("'.$newElementFormat.'").text())), null);';
                }
                    
                $generatedScript_function[$value[parent::F_SECTION_ID]].= $generatedScript_repeatvar[$value[parent::F_SECTION_ID]].$generatedScript[$value[parent::F_SECTION_ID]];
                $generatedScript_function[$value[parent::F_SECTION_ID]].= $this->linebreak.'}'.self::COMPRESS_LINEBREAK;
            
            }
        }
        
        $generatedScript_function = implode('', $generatedScript_function);
        
        return $generatedScript_function;
    }
    
    private function getStartingPositions( $value = '' ){
   
        $generatedScript_block = '';
        $generatedScript_pos = '';
        $newElementFormat = '';
        
        if(($value[parent::F_POSITION]!='') 
        or ($value[parent::F_ZINDEX]!='') 
        or ($value[parent::F_START_LEFT_POS]!='') 
        or ($value[parent::F_START_TOP_POS]!='')) {

            if($value[parent::F_START_ELEMENT] == ''){
            
                $newElementFormat = str_replace('"', '\"', $this->replacejElementType($value[parent::F_TYPE], $value[parent::F_LAYER_ID]));
                
            }else{ // Attach a different element.
            
                $newElementFormat = str_replace('"', '\"', $this->replacejElementType($value[parent::F_START_ELEMENT_TYPE], $value[parent::F_START_ELEMENT]));
            }
                
            if($value[parent::F_POSITION]!=''){
            
                $generatedScript_pos[] =  '"position":"'.$value[parent::F_POSITION].'"';
            }
            
            if($value[parent::F_ZINDEX]!=''){
            
                $generatedScript_pos[] =  '"z-index":"'.$value[parent::F_ZINDEX].'"'.$this->linebreak;
            }
            
            if($value[parent::F_START_LEFT_POS]!=''){
            
                $generatedScript_pos[] =  '"left":"'.$value[parent::F_START_LEFT_POS_SIGN].$value[parent::F_START_LEFT_POS].$value[parent::F_START_LEFT_POS_FORMAT].'"';
            }
            
            if($value[parent::F_START_TOP_POS]!=''){
            
                $generatedScript_pos[] =  '"top":"'.$value[parent::F_START_TOP_POS_SIGN].$value[parent::F_START_TOP_POS].$value[parent::F_START_TOP_POS_FORMAT].'"';
            }
            
        }
        
        if(is_array($generatedScript_pos)){
        
            $generatedScript_block .= $this->linebreak.'$(eval($("<div />").html("'.$newElementFormat.'").text())).css({'.implode(",", $generatedScript_pos).'});';
        }
        
        return $generatedScript_block;
    
    }

    private function getStartingPositionsOnReady(){
        
        $generatedScript_pos = '';
        
        foreach($this->listarray as $value){   
        
            $hasRestrictedCode = $this->searchRestrictedCode($value);
            $hasValidParendId = $this->validateParentId($value[parent::F_PARENT_ID]);

            $sections = $this->getSectionArray($value[parent::F_SECTION_ID]);        
            
            // This line must be inside the first output loop
            if( !isset($this->PHPCookieMax[$value[parent::F_SECTION_ID]]) ){$this->PHPCookieMax[$value[parent::F_SECTION_ID]] = '';}
            if( !isset($this->PHPCookieMax[$sections[$value[parent::F_SECTION_ID]][parent::KEY_COOKIE_CONDITION]]) ){$this->PHPCookieMax[$sections[$value[parent::F_SECTION_ID]][parent::KEY_COOKIE_CONDITION]] = '';}

            if( ( ($hasRestrictedCode) and ($this->admin_option[parent::KEY_OUTPUT_PROTECTED] == '1' ) ) 
            or ( $value[parent::F_TYPE] ==  parent::ELEMENT_TYPE_GROUP ) ){ // skip group
            // Nothing to do
            }else if($hasValidParendId == true){  
            
                // Generates and validates cookie
                $this->generatedCookie .= $this->generateCookies($value[parent::F_SECTION_ID]);
                    
                if( !isset($this->CookieMaxjQueryValidation[$value[parent::F_SECTION_ID]]) ){$this->CookieMaxjQueryValidation[$value[parent::F_SECTION_ID]] = '';}
                $this->PHPCookieMax[$value[parent::F_SECTION_ID]] = ($this->PHPCookieMax[$value[parent::F_SECTION_ID]] == '') ? false : $this->PHPCookieMax[$value[parent::F_SECTION_ID]];
                
                if( ( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] == false ) 
                or (( $this->PHPCookieMax[$value[parent::F_SECTION_ID]] != true ) and ( $this->PHPCookieMax[$sections[$value[parent::F_SECTION_ID]][parent::KEY_COOKIE_CONDITION]] == true ) ) ){            
                
                    $generatedCondition = $this->generateJSCookieCondition($value[parent::F_SECTION_ID]);
                    $name = $value[parent::F_SECTION_ID] ."_".str_replace("-","_",sanitize_title_with_dashes($value[parent::F_LAYER_ID]))."_".$value[parent::F_EXPORT_ID];
                    $this->global_js .= $this->linebreak.'var twiz_repeat_'.$name.' = null;';
                    
                    if( ( $value[parent::F_LOCK_EVENT] == '1' ) 
                    and ( ( $value[parent::F_ON_EVENT] != '') 
                    and ( $value[parent::F_ON_EVENT] != 'Manually') ) ){             
                    
                        $this->global_js .= $this->linebreak.'var twiz_locked_'.$name.' = 0;';
                    }
                    
                    if( $value[parent::F_OUTPUT_POS] == 'r' ){ // onready
                    
                        $generatedScript_pos .= $generatedCondition['open'].$this->getStartingPositions($value).$generatedCondition['close'];
                    
                    }    
                }  
            }
        }

        return $generatedScript_pos.$this->linebreak;
    }
    
    private function replacejElementType ( $type = '', $element = '' ){
            
        switch($type){
        
            case parent::ELEMENT_TYPE_ID:
            
                $this->stop = '';
                
                return '"#'.$element.'"';
 
                break;

            case parent::ELEMENT_TYPE_CLASS:
                
                $this->stop = 'stop().';
                
                return '".'.$element.'"';
                
                break;

            case parent::ELEMENT_TYPE_NAME:
                
                $this->stop = 'stop().';
                
                return '"[name='.$element.']"';
                
                break;
            
            case parent::ELEMENT_TYPE_TAG:
                
                $this->stop = 'stop().';
                
                return '"'.$element.'"';
                
                break;
                
            case parent::ELEMENT_TYPE_OTHER:
                
                $this->stop = 'stop().';
                
                return $element;
                
                break;                
        }
        
        $this->stop = '';
        
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
        
        $newvalue = str_replace(parent::SC_WP_UPLOAD_DIR, $this->upload_dir['baseurl'],  $newvalue);
        
        return $newvalue;
    }
    
    private function searchRestrictedCode( $value = array() ){
    
        foreach( $this->array_restricted as $string ){

            foreach( $this->array_fields as $field ){
                    
                if( preg_match($string, $value[$field] ) ) {
                
                   return true;
                }
            }
        }
        
        return false;
    }

    private function generateJSCookieCondition( $section_id = '' ){
      
        $generatedCondition['open'] = '';
        $generatedCondition['close'] = '';
        if($section_id == ''){ return $generatedCondition; }

        $this->cookieLooped[] = $section_id;
        
        $sections = $this->getSectionArray($section_id);
        
        $cookie_name = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME];
        $cookie_condition = $sections[$section_id][parent::KEY_COOKIE_CONDITION];
        $option_1 = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1];
        $option_2 = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2];
        $with = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH];
      
            if( ( ( $option_1 != '' ) and (  $cookie_name != '' ) ) 
            or ( $cookie_condition != '') ){ // cookie option is enabled
        
            switch($with){ // cookie type
                    
                case 'js';
                    
                    if( $cookie_condition == '' ) {
                    
                        $generatedCondition['open'] = 'if(twiz_'.$section_id.'_cookie_Max != true){ ';
                        $generatedCondition['close'] = $this->linebreak.'}';
                        
                    }else{
                    
                        $generatedCondition['open'] = 'if((twiz_'.$section_id.'_cookie_Max != true)&&(twiz_'.$cookie_condition.'_cookie_Max == true)) { ';
                        $generatedCondition['close'] = $this->linebreak.'}';                    
                    }
                    
                    return $generatedCondition;
                    
                    break;
                    
                case 'all':
                
                    if( $cookie_condition == '' ) {
                    
                        $generatedCondition['open'] = 'if(twiz_'.$section_id.'_cookie_Max != true){ ';
                        $generatedCondition['close'] = $this->linebreak.'}';
                        
                    }else{
                    
                        $generatedCondition['open'] = 'if((twiz_'.$section_id.'_cookie_Max != true)&&(twiz_'.$cookie_condition.'_cookie_Max == true)) { ';
                        $generatedCondition['close'] = $this->linebreak.'}';                    
                    }
                    
                    return $generatedCondition;
                    
                    break;
            }
        }

        return $generatedCondition;
    }
    
    private function generateCookies( $section_id = '' ){
      
        if($section_id==''){return '';}
        
        // loops only once per section
        if( in_array($section_id, $this->cookieLooped) ){
        
            return '';
            
        }else{
             
            $this->cookieLooped[] = $section_id;
            
            $sections = $this->getSectionArray($section_id);
                    
            $cookie_name = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME];
            $cookie_condition = $sections[$section_id][parent::KEY_COOKIE_CONDITION];            
            $with = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_WITH];
            $option_1 = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1];
      
            if( ( $option_1 != '' ) and (  $cookie_name != '' ) ){ // cookie option is enabled
            
                switch($with){ // cookie type
                
                    case 'php';
                    
                        $phpcookie = $this->getPHPCookie($section_id, $sections);
                        
                        break;
                        
                    case 'js';
                    
                        $jscookie = $this->getJSCookie($section_id, $sections);
                        
                        return $jscookie;
                        
                        break;
                        
                    case 'all':
                    
                        $phpcookie = $this->getPHPCookie($section_id, $sections);
                        
                        if( $this->PHPCookieMax[$section_id] == false ){
                        
                            $jscookie = $this->getJSCookie($section_id, $sections);
                            
                            return $jscookie;
                        }
                        
                        break;
                }
            }else if ( $cookie_condition != '' ) {
                    
                    $this->global_js .= $this->linebreak.'var twiz_'.$section_id.'_cookie_Max = false; ';
                    
                    return ''; 
            }
        }
        
        return '';
    }
    
    private function getPHPCookie( $section_id = '', $sections = array() ) {
    
        $cookieprefix = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME];

        $cookiename = 'twiz_cookie_php_'.$section_id.'_'.sanitize_title_with_dashes($cookieprefix);
        
        $expiration_option = $this->formatCookieExpiration( $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2], 'php' );
        
        $arrayscope = $this->getCookieScope( $section_id );
        
        if( !isset($_COOKIE[$cookiename]) ){

            $_COOKIE[$cookiename] = '';
            
            setcookie($cookiename, '1_'.$expiration_option, $expiration_option, $arrayscope['path'],$arrayscope['domain']);
            
            $this->global_js .= $this->linebreak.'var twiz_'.$section_id.'_cookie_Max = false; ';
            
            $this->PHPCookieMax[$section_id] = false;
        
        }else{

            list($counter, $expiration_old) = preg_split('/_/',$_COOKIE[$cookiename]);
            
            // Calculates the time diff and substracts it.
            $expiration_diff = $expiration_option - $expiration_old;
            $expiration_new = $expiration_old - $expiration_diff ;
            
        
            $cookie_max_val = $this->array_cookieval[$sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1]];
            
            // validate counter
            if( $counter < $cookie_max_val ){
                
                $this->global_js .= $this->linebreak.'var twiz_'.$section_id.'_cookie_Max = false; ';
                $this->PHPCookieMax[$section_id] = false;
                
            }else{
            
                $this->global_js .= $this->linebreak.'var twiz_'.$section_id.'_cookie_Max = true; ';
                $this->PHPCookieMax[$section_id] = true;
            }
            
            $counter = $counter + 1;
            setcookie($cookiename,  $counter.'_'.$expiration_new, $expiration_new, $arrayscope['path'],$arrayscope['domain'] );
        }
        
        return '';
    }
    
    private function getJSCookie( $section_id = '', $sections = array() ){
            

        $cookiename = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_NAME];
        $arrayscope = $this->getCookieScope( $section_id );
              
        if( $cookiename == '' ){
        
            $cookiename = $section_id;
        }
        
        $jscookie = 'var twiz_'.$section_id.'_cookiename = "twiz_cookie_js_'.$section_id.'_'.sanitize_title_with_dashes($cookiename).'";'.$this->linebreak;
        $jscookie .= 'var twiz_'.$section_id.'_cookie_expiration_option = '.$this->formatCookieExpiration( $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_2], 'js' ).';'.$this->linebreak;
       
        $this->global_js .= $this->linebreak.'var twiz_'.$section_id.'_cookie_Max = false; ';

        $jscookie .= 'if($.cookie(twiz_'.$section_id.'_cookiename) == null){'.$this->linebreak;
        $jscookie .= $this->tab.'$.cookie(twiz_'.$section_id.'_cookiename, "1_" + twiz_'.$section_id.'_cookie_expiration_option, { expires: twiz_'.$section_id.'_cookie_expiration_option, path: "'.$arrayscope['path'].'", domain: "'.$arrayscope['domain'].'"});'.$this->linebreak;
        $jscookie .= '}else{'.$this->linebreak;
        
        $jscookie .= $this->tab.'var twiz_'.$section_id.'_cookie_value = $.cookie(twiz_'.$section_id.'_cookiename).split("_");'.$this->linebreak;
        $jscookie .= $this->tab.'var twiz_'.$section_id.'_cookie_counter = parseInt(twiz_'.$section_id.'_cookie_value[0]);'.$this->linebreak;
        $jscookie .= $this->tab.'var twiz_'.$section_id.'_cookie_expiration_old = twiz_'.$section_id.'_cookie_value[1];'.$this->linebreak;
        $jscookie .= $this->tab.'var twiz_'.$section_id.'_cookie_expiration_diff = twiz_'.$section_id.'_cookie_expiration_option - twiz_'.$section_id.'_cookie_expiration_old;'.$this->linebreak;
        $jscookie .= $this->tab.'var twiz_'.$section_id.'_cookie_expiration_new = twiz_'.$section_id.'_cookie_expiration_old - twiz_'.$section_id.'_cookie_expiration_diff;'.$this->linebreak;
        $jscookie .= $this->tab.'var twiz_'.$section_id.'_cookie_option_1 = '.$this->array_cookieval[$sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_OPTION_1]].';'.$this->linebreak;
        
        $jscookie .= $this->tab.'if(twiz_'.$section_id.'_cookie_counter < twiz_'.$section_id.'_cookie_option_1){'.$this->linebreak;
        $jscookie .= $this->tab.$this->tab.'twiz_'.$section_id.'_cookie_Max = false;'.$this->linebreak;
        $jscookie .= $this->tab.'}else{'.$this->linebreak;
        $jscookie .= $this->tab.$this->tab.'twiz_'.$section_id.'_cookie_Max = true;'.$this->linebreak;
        $jscookie .= $this->tab.'}'.$this->linebreak;        

        $jscookie .= $this->tab.'twiz_'.$section_id.'_cookie_counter = twiz_'.$section_id.'_cookie_counter + 1;'.$this->linebreak;
        $jscookie .= $this->tab.'$.cookie(twiz_'.$section_id.'_cookiename, twiz_'.$section_id.'_cookie_counter + "_" + twiz_'.$section_id.'_cookie_expiration_new, { expires: twiz_'.$section_id.'_cookie_expiration_new, path: "'.$arrayscope['path'].'", domain: "'.$arrayscope['domain'].'"});'.$this->linebreak;
        $jscookie .= '}';
        
        $this->CookieMaxjQueryValidation[$section_id] = 'if(twiz_'.$section_id.'_cookie_Max == true){ [REPEAT_VAR] = 0;}';
        
        return $jscookie;
    }
     
    private function getSectionArray( $section_id = '' ){
    
        if( in_array($section_id, $this->array_default_section) ){
        
            $sections = $this->hardsections;
        }else{
        
            $sections = $this->sections;
        }
        
        return $sections;
    }
    
    private function getCookieScope( $section_id = '' ){
    
        $sections = $this->getSectionArray($section_id);
        $scope = $sections[$section_id][parent::KEY_COOKIE][parent::KEY_COOKIE_SCOPE];
        
        $hostname = $_SERVER['SERVER_NAME'];
        $hostname = str_replace('www.', '', $hostname);
        
        $arrayscope['domain'] = $hostname;
        $arrayscope['path'] = '/';
        
        if( $scope == 'perdirectory' ){
  
            $arrayscope['path'] = $_SERVER["REQUEST_URI"];
        }

        return $arrayscope;
    }
    
    private function formatCookieExpiration( $value = '', $type = '' ){
    
        $expiration = '';
        $time = time();
    
        switch($value){
        
            case 'pervisit':
            
                $expiration = 0;
                
                break;
                
            case 'perhour':
            
                $expiration = $time + 3600;
                
                if( $type == 'js' ){
                
                    $expiration = $expiration / $time / 24;
                }    
                
                break;
                
            case 'perday':
            
                $expiration = $time + 3600*24;
                
                if( $type == 'js' ){
                
                    $expiration = $expiration / $time - 0.0000640479945;
                }    

                break;
                
            case 'perweek':
            
                $expiration = $time + 3600*24*7;
                
                if( $type == 'js' ){
                
                    $expiration = $expiration / $time * 7 - 0.003138351625;
                }    

                break;
                
            case 'permonth':
            
                $expiration = $time + 3600*24*30;

                if( $type == 'js' ){
                
                    $expiration = $expiration / $time * 30 - 0.057643191403;
                }                    
                
                break;
                
            case 'peryear':
            
                $expiration = $time + 3600*24*365;
                
                if( $type == 'js' ){
                
                    $expiration = $expiration / $time * 365 - 8.5327933819;
                }        

                break;
        }

        return $expiration;
    }    

    private function validateParentId( $parentid = '' ){ 
    
        global $wpdb;
        
        if($parentid==''){return true;} 
    
        $sql = "SELECT ".parent::F_EXPORT_ID." FROM ".$this->table." WHERE ".parent::F_EXPORT_ID." = '".$parentid."' 
                AND ".parent::F_TYPE." = '".parent::ELEMENT_TYPE_GROUP."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        if($row[parent::F_EXPORT_ID]!=''){

            return true;
        }
  
        return false;
    }
    
}?>