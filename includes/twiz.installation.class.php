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
    require_once(dirname(__FILE__).'/twiz.admin.class.php');
    require_once(dirname(__FILE__).'/twiz.library.class.php');
    
class TwizInstallation extends Twiz{
   
                                       
    function __construct(){
    
        parent::__construct();
    }
  
    function proceed(){
    
    global $wpdb;

        $sql = "CREATE TABLE ".$this->table." (". 
                parent::F_ID . " int NOT NULL AUTO_INCREMENT, ". 
                parent::F_PARENT_ID . " varchar(13) NOT NULL default '', ". 
                parent::F_EXPORT_ID . " varchar(13) NOT NULL default '', ". 
                parent::F_SECTION_ID . " varchar(22) NOT NULL default '".parent::DEFAULT_SECTION_HOME."', ". 
                parent::F_STATUS . " tinyint(3) NOT NULL default 0, ". 
                parent::F_TYPE . " varchar(5) NOT NULL default '".parent::ELEMENT_TYPE_ID."', ". 
                parent::F_LAYER_ID . " varchar(50) NOT NULL default '', ". 
                parent::F_ON_EVENT . " varchar(15) NOT NULL default '', ".
                parent::F_LOCK_EVENT . " tinyint(3) NOT NULL default 1, ". 
                parent::F_LOCK_EVENT_TYPE . " varchar(4) NOT NULL default 'auto', ". 
                parent::F_START_DELAY . " varchar(100) NOT NULL default '0', ". 
                parent::F_DURATION . " varchar(100) NOT NULL default '0', ". 
                parent::F_DURATION_B . " varchar(100) NOT NULL default '', ". 
                parent::F_OUTPUT . " varchar(1) NOT NULL default 'b', ".
                parent::F_OUTPUT_POS . " varchar(1) NOT NULL default 'b', ".
                parent::F_JAVASCRIPT . " text NOT NULL default '', ". 
                parent::F_CSS . " text NOT NULL default '', ". 
                parent::F_START_ELEMENT_TYPE . " varchar(5) NOT NULL default '".parent::ELEMENT_TYPE_ID."', ". 
                parent::F_START_ELEMENT . " varchar(50) NOT NULL default '', ".                 
                parent::F_START_TOP_POS_SIGN . " varchar(1) NOT NULL default '', ". 
                parent::F_START_TOP_POS . " int(5) default NULL, ". 
                parent::F_START_TOP_POS_FORMAT . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."', ". 
                parent::F_START_LEFT_POS_SIGN . " varchar(1) NOT NULL default '', ". 
                parent::F_START_LEFT_POS . " int(5) default NULL, ". 
                parent::F_START_LEFT_POS_FORMAT . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."', ". 
                parent::F_POSITION . " varchar(8) NOT NULL default '', ". 
                parent::F_ZINDEX . " varchar(5) NOT NULL default '', ". 
                parent::F_EASING_A . " varchar(20) NOT NULL default 'swing', ". 
                parent::F_EASING_B . " varchar(20) NOT NULL default 'swing', ". 
                parent::F_MOVE_ELEMENT_TYPE_A . " varchar(5) NOT NULL default '".parent::ELEMENT_TYPE_ID."', ". 
                parent::F_MOVE_ELEMENT_A . " varchar(50) NOT NULL default '', ".                    
                parent::F_MOVE_TOP_POS_SIGN_A . " varchar(1) NOT NULL default '', ". 
                parent::F_MOVE_TOP_POS_A . " int(5) default NULL, ". 
                parent::F_MOVE_TOP_POS_FORMAT_A . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."', ". 
                parent::F_MOVE_LEFT_POS_SIGN_A . " varchar(1) NOT NULL default '', ". 
                parent::F_MOVE_LEFT_POS_A . " int(5) default NULL, ". 
                parent::F_MOVE_LEFT_POS_FORMAT_A . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."', ". 
                parent::F_MOVE_ELEMENT_TYPE_B . " varchar(5) NOT NULL default '".parent::ELEMENT_TYPE_ID."', ". 
                parent::F_MOVE_ELEMENT_B . " varchar(50) NOT NULL default '', ".                  
                parent::F_MOVE_TOP_POS_SIGN_B . " varchar(1) NOT NULL default '', ". 
                parent::F_MOVE_TOP_POS_B . " int(5) default NULL, ". 
                parent::F_MOVE_TOP_POS_FORMAT_B . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."', ". 
                parent::F_MOVE_LEFT_POS_SIGN_B . " varchar(1) NOT NULL default '', ". 
                parent::F_MOVE_LEFT_POS_B . " int(5) default NULL, ". 
                parent::F_MOVE_LEFT_POS_FORMAT_B . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."', ". 
                parent::F_OPTIONS_A . " text NOT NULL default '', ". 
                parent::F_OPTIONS_B . " text NOT NULL default '', ". 
                parent::F_EXTRA_JS_A . " text NOT NULL default '', ". 
                parent::F_EXTRA_JS_B . " text NOT NULL default '', " .  
                parent::F_GROUP_ORDER . " int(5) NOT NULL default 0, " .  
                parent::F_ROW_LOCKED . " tinyint(3) NOT NULL default 0, " .  
                "PRIMARY KEY (". parent::F_ID . "),
                KEY ".parent::F_PARENT_ID." (".parent::F_PARENT_ID."),
                KEY ".parent::F_EXPORT_ID." (".parent::F_EXPORT_ID."),
                KEY ".parent::F_SECTION_ID." (".parent::F_SECTION_ID.")                
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
                
                
        if ( $wpdb->get_var( "show tables like '".$this->table."'" ) != $this->table ) {

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
            dbDelta($sql);
        
            $code = update_option('twiz_db_version', $this->dbVersion);
            $code = update_option('twiz_global_status', '1');
            $code = update_option('twiz_hscroll_status', '1');
            $code = update_option('twiz_cookie_js_status', false);
            
            $code = new TwizAdmin();
            
            if(!isset($setting_menu[$this->userid])) $setting_menu[$this->userid] = '';
            $setting_menu[$this->userid] = parent::DEFAULT_SECTION_HOME ;
            $code = update_option('twiz_setting_menu', $setting_menu);
            
            if(!isset($bullet[$this->userid])) $bullet[$this->userid] = '';
            $bullet[$this->userid] = parent::LB_ORDER_DOWN ;
            $code = update_option('twiz_bullet', $bullet);
        
            if(!isset($twiz_order_by[$this->userid])) $twiz_order_by[$this->userid] = '';
            $twiz_order_by[$this->userid] =  parent::F_ON_EVENT;
            $code = update_option('twiz_order_by',  $twiz_order_by);
            
            if(!isset($this->toggle_option[$this->userid][parent::KEY_PREFERED_METHOD])) $this->toggle_option[$this->userid][parent::KEY_PREFERED_METHOD] = '';
            $this->toggle_option[$this->userid][parent::KEY_PREFERED_METHOD] = 'twiz_far_simple';
            $code = update_option('twiz_toggle',  $this->toggle_option);
            
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
                
                if( !in_array(parent::F_SECTION_ID, $array_describe) ){
                
                    // from <= v.1.3.2.3
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_SECTION_ID . " varchar(22) NOT NULL default '".parent::DEFAULT_SECTION_HOME."' after ".parent::F_ID."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_ON_EVENT, $array_describe) ){    
                
                    // from <= v 1.3.5.7
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".parent::F_ON_EVENT." varchar(15) NOT NULL default '' after ".parent::F_LAYER_ID."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_JAVASCRIPT, $array_describe) ){
                
                    // from <= v 1.3.5.8
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_JAVASCRIPT . " text NOT NULL default '' after ".parent::F_DURATION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_ZINDEX, $array_describe) ){
                
                    // from <= v 1.3.5.8
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_ZINDEX . " varchar(5) NOT NULL default '' after ".parent::F_POSITION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_TYPE, $array_describe) ){        
                
                    // from <= v 1.3.6.1 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_TYPE . " varchar(5) NOT NULL default '".parent::ELEMENT_TYPE_ID."' after ".parent::F_STATUS."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_OUTPUT, $array_describe) ){
                
                    // from <= v 1.3.6.6
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_OUTPUT . " varchar(1) NOT NULL default 'b' after ".parent::F_DURATION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_OUTPUT_POS, $array_describe) ){
                
                    // from <= v 1.3.7
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_OUTPUT_POS . " varchar(1) NOT NULL default 'b' after ".parent::F_OUTPUT."";
                    $code = $wpdb->query($altersql);
                }
                
              
                /*
                * HOTFIX - Export ID
                *
                * Adds the new field export_id and applies a Hotfix for updating and replacing ids.
                */
                if( !in_array(parent::F_EXPORT_ID, $array_describe) ){
                    

                    // from <= v 1.3.7.6 
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".  parent::F_EXPORT_ID . " varchar(13) NOT NULL default '' after ". parent::F_ID ."";
                    $code = $wpdb->query($altersql);
                    
                    // Select all the table
                    $sql = "SELECT * from ".$this->table;
                    $rows = $wpdb->get_results($sql, ARRAY_A);
                    
                    foreach ( $rows as $value ){
                        
                        // wait for 1/10 second
                        usleep(100000);
                        
                        // a simple uniq id
                        $exportid = uniqid();
                        
                        // array temp
                        if( !isset($array_ids[$value[parent::F_ID]]) ) $array_ids[$value[parent::F_ID]] = '' ;
                        $array_ids[$value[parent::F_ID]] = $exportid;
                        
                        // update each row with the unique id and removes section_id.
                        $updatesql = "UPDATE ".$this->table . " SET ".  parent::F_EXPORT_ID . " = '".$exportid."'
                        WHERE ". parent::F_ID ." = '".$value[parent::F_ID]."'";
                        $code = $wpdb->query($updatesql);

                    }
                    
                    // loops the previous temp array
                    foreach ( $array_ids as $t_id => $t_export_id ){
                    
                        // loop all rows again and again
                        foreach ( $rows as $value ){
                        
                            // Replace all current ids. all functions and activations vars included
                            $updatesql = "UPDATE ".$this->table . " SET
                             ". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", '_".$t_id."', '_".$t_export_id."') 
                            ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", '_".$t_id."', '_".$t_export_id."') 
                            ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", '_".$t_id."', '_".$t_export_id."') 
                            WHERE ". parent::F_ID ." = '".$value[parent::F_ID]."'";
                            $code = $wpdb->query($updatesql);
                            
                        }
                    }                    
                }
                
                // from <= v 1.3.7.9
                if( !in_array(parent::F_START_TOP_POS_FORMAT, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_START_TOP_POS_FORMAT . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."' after ".parent::F_START_TOP_POS."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_START_LEFT_POS_FORMAT, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_START_LEFT_POS_FORMAT . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."' after ".parent::F_START_LEFT_POS."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_MOVE_TOP_POS_FORMAT_A, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_MOVE_TOP_POS_FORMAT_A . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."' after ".parent::F_MOVE_TOP_POS_A."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_MOVE_LEFT_POS_FORMAT_A, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_MOVE_LEFT_POS_FORMAT_A . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."' after ".parent::F_MOVE_LEFT_POS_A."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_MOVE_TOP_POS_FORMAT_B, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_MOVE_TOP_POS_FORMAT_B . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."' after ".parent::F_MOVE_TOP_POS_B."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_MOVE_LEFT_POS_FORMAT_B, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_MOVE_LEFT_POS_FORMAT_B . " varchar(2) NOT NULL default '".parent::FORMAT_PIXEL."' after ".parent::F_MOVE_LEFT_POS_B."";
                    $code = $wpdb->query($altersql);
                }
                
                //  from <= v 1.3.8.5
                if( !in_array(parent::F_EASING_A, $array_describe) ){

                    $altersql = "ALTER TABLE ".$this->table 
                    . " ADD ". parent::F_EASING_B . " varchar(20) NOT NULL default 'swing' after ".parent::F_ZINDEX."," 
                    . " ADD ". parent::F_EASING_A . " varchar(20) NOT NULL default 'swing' after ".parent::F_ZINDEX."";
                    $code = $wpdb->query($altersql);
                }              
                
                
                //  from <= v 1.3.9.8
                $altersql = "ALTER TABLE ".$this->table 
                . " MODIFY ". parent::F_START_DELAY . " varchar(100) NOT NULL default '0'," 
                . " MODIFY ". parent::F_DURATION . " varchar(100) NOT NULL default '0'";
                $code = $wpdb->query($altersql);
                            

                // from <= v 1.4.4.5 
                if( !in_array(parent::F_LOCK_EVENT, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_LOCK_EVENT . " tinyint(3) NOT NULL default 1 after ".parent::F_ON_EVENT."";
                    $code = $wpdb->query($altersql);
                }
                
                // from <= v 1.4.8.4 
                if( !in_array(parent::F_LOCK_EVENT_TYPE, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_LOCK_EVENT_TYPE . " varchar(4) NOT NULL default 'auto' after ".parent::F_LOCK_EVENT."";
                    $code = $wpdb->query($altersql);
                }

                // from <= v 1.5 
                if( !in_array(parent::F_PARENT_ID, $array_describe) ){

                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".  parent::F_PARENT_ID . " varchar(13) NOT NULL default '' after ". parent::F_ID ."";
                    $code = $wpdb->query($altersql);
                    
                    // Rename the twiz_active variable to twiz_locked
                    $updatesql = "UPDATE ".$this->table . " SET
                     ". parent::F_JAVASCRIPT . " = replace(". parent::F_JAVASCRIPT . ", 'twiz_active', 'twiz_locked') 
                    ,". parent::F_EXTRA_JS_A . " = replace(". parent::F_EXTRA_JS_A . ", 'twiz_active', 'twiz_locked') 
                    ,". parent::F_EXTRA_JS_B . " = replace(". parent::F_EXTRA_JS_B . ", 'twiz_active', 'twiz_locked')";
                    $code = $wpdb->query($updatesql);
                    
                }
                if( !in_array(parent::F_ROW_LOCKED, $array_describe) ){                
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".  parent::F_ROW_LOCKED . " tinyint(3) NOT NULL default 0 after ". parent::F_EXTRA_JS_B ."";
                    $code = $wpdb->query($altersql);
                }
                
                
                // Bullet menu from <= v 1.5 
                $bullet = get_option('twiz_bullet');
                $bullet = ( !is_array($bullet) ) ? '' : $bullet;
                
                if( $bullet == '' ) {
                
                    $bullet[$this->userid] = parent::LB_ORDER_UP ; // same setting as before
                    $code = update_option('twiz_bullet', $bullet);
                
                    // Plus admin & toggle key changes from <= v 1.5 
                    if(!isset($this->toggle_option[$this->userid][parent::KEY_PREFERED_METHOD])) $this->toggle_option[$this->userid][parent::KEY_PREFERED_METHOD] = '';

                    if(!isset($this->admin_option[parent::KEY_PREFERED_METHOD])){
                    
                        $this->toggle_option[$this->userid][parent::KEY_PREFERED_METHOD] =  'twiz_far_simple';
                        
                    }else{
                    
                        $this->toggle_option[$this->userid][parent::KEY_PREFERED_METHOD] = $this->admin_option[parent::KEY_PREFERED_METHOD];
                        $this->admin_option[parent::KEY_PREFERED_METHOD] = '';
                        unset($this->admin_option[parent::KEY_PREFERED_METHOD]);
                    }
                    
                    $code = update_option('twiz_admin', $this->admin_option);
                    $code = update_option('twiz_toggle', $this->toggle_option);
      
                }
                
                // from <= v 1.4.3 and <= v 1.5 
                $twiz_order_by = get_option('twiz_order_by');
                
                $twiz_order_by_old = ( !is_array($twiz_order_by) ) ? $twiz_order_by : ''; // Migrate setting
                $twiz_order_by = ( !is_array($twiz_order_by) ) ? '' : $twiz_order_by;
                 
                if( $twiz_order_by == '' ) {
                
                    if(!isset($twiz_order_by[$this->userid])) $twiz_order_by[$this->userid] = '';
                    $twiz_order_by[$this->userid] = ($twiz_order_by_old != '') ? $twiz_order_by_old : parent::F_ON_EVENT;
                    $code = update_option('twiz_order_by', $twiz_order_by);
                }    
                
                // from <= v 1.5.5
                if(!isset($this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSIT])) $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSIT] = '';
                if(!isset($this->admin_option[parent::KEY_EXTRA_EASING])) $this->admin_option[parent::KEY_EXTRA_EASING] = '';
                
                if( ( $this->admin_option[parent::KEY_REGISTER_JQUERY_TRANSIT] == '1' )
                and ( $this->admin_option[parent::KEY_EXTRA_EASING] != '1' ) ){
                
                    $this->admin_option[parent::KEY_EXTRA_EASING] = '1';
                    $code = update_option('twiz_admin', $this->admin_option);
                }
                

                // from <= v 1.7
                if( !in_array(parent::F_START_ELEMENT_TYPE, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_START_ELEMENT_TYPE . " varchar(5) NOT NULL default '".parent::ELEMENT_TYPE_ID."' after ".parent::F_JAVASCRIPT."";
                    $code = $wpdb->query($altersql);
                }

                if( !in_array(parent::F_MOVE_ELEMENT_TYPE_A, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_MOVE_ELEMENT_TYPE_A . " varchar(5) NOT NULL default '".parent::ELEMENT_TYPE_ID."' after ".parent::F_EASING_B."";
                    $code = $wpdb->query($altersql);
                }

                if( !in_array(parent::F_MOVE_ELEMENT_TYPE_B, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_MOVE_ELEMENT_TYPE_B . " varchar(5) NOT NULL default '".parent::ELEMENT_TYPE_ID."' after ".parent::F_MOVE_LEFT_POS_FORMAT_A."";
                    $code = $wpdb->query($altersql);
                }

                if( !in_array(parent::F_START_ELEMENT, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_START_ELEMENT . " varchar(50) NOT NULL default '' after ".parent::F_START_ELEMENT_TYPE."";
                    $code = $wpdb->query($altersql);
                }

                if( !in_array(parent::F_MOVE_ELEMENT_A, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_MOVE_ELEMENT_A . " varchar(50) NOT NULL default '' after ".parent::F_MOVE_ELEMENT_TYPE_A."";
                    $code = $wpdb->query($altersql);
                }

                if( !in_array(parent::F_MOVE_ELEMENT_B, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_MOVE_ELEMENT_B . " varchar(50) NOT NULL default '' after ".parent::F_MOVE_ELEMENT_TYPE_B."";
                    $code = $wpdb->query($altersql);
                    
                    
                    //Begin Hot Fix from <= v 1.7 - clean unused Library keys
                    $myTwizLibrary  = new TwizLibrary();
                    $library = $myTwizLibrary->array_library;
                    $library_dir = $myTwizLibrary->array_library_dir;
                    
                    foreach( $library as $key => $value ){
                     
                        if( !in_array($value[parent::KEY_DIRECTORY], $library_dir)){
              
                            $myTwizLibrary->array_library[$key] = '';
                            
                            unset($myTwizLibrary->array_library[$key]);
                        }
                    }
                    $code = update_option('twiz_library', $myTwizLibrary->array_library);
                    // End Hot Fix
                    
                    
                    //Begin Hot Fix from <= v 1.5.9
                    $updatesql = "UPDATE ".$this->table . " SET ".  parent::F_PARENT_ID . " = ''
                    WHERE ". parent::F_PARENT_ID ." LIKE 'twiz%'";
                    $code = $wpdb->query($updatesql);
                    // End Hot Fix
                    
                }

                // from <= v 1.8
                if( !in_array(parent::F_CSS, $array_describe) ){
                
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_CSS . " text NOT NULL default '' after ".parent::F_JAVASCRIPT."";
                    $code = $wpdb->query($altersql);
                }
                
                // from <= v 1.9.4
                if( !in_array(parent::F_DURATION_B, $array_describe) ){
                
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_DURATION_B . "  varchar(100) NOT NULL default '' after ".parent::F_DURATION."";
                    $code = $wpdb->query($altersql);
                }         
                
                // from <= v 1.9.8
                $twiz_hscroll_status = get_option('twiz_hscroll_status');
                if( $twiz_hscroll_status == '' ){
                  
                    $code = update_option('twiz_hscroll_status', '1');
                }
                   
                   
                // from <= v 2.1
                if( !in_array(parent::F_GROUP_ORDER, $array_describe) ){
                
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_GROUP_ORDER . " int(5) NOT NULL default 0 after ".parent::F_EXTRA_JS_B."";
                    $code = $wpdb->query($altersql);
                }
                
                
                // from <= v 2.2.1
                $indexes = "SHOW INDEXES from ".$this->table ."";
                $indexes_rows = $wpdb->get_results($indexes, ARRAY_A);
                
                foreach($indexes_rows as $values){
                
                    $array_indexes[] = $values['Column_name'];
                }
                
                if( !in_array(parent::F_PARENT_ID, $array_indexes) ){                 
                    $altersql = "ALTER TABLE ".$this->table .
                                "  ADD INDEX ( ".  parent::F_PARENT_ID . " )";
                                $code = $wpdb->query($altersql);
                }
                if( !in_array(parent::F_EXPORT_ID, $array_indexes) ){                 
                    $altersql = "ALTER TABLE ".$this->table .
                                "  ADD INDEX ( ".  parent::F_EXPORT_ID . " )";
                                $code = $wpdb->query($altersql);
                }                
                if( !in_array(parent::F_SECTION_ID, $array_indexes) ){                 
                    $altersql = "ALTER TABLE ".$this->table .
                                "  ADD INDEX ( ".  parent::F_SECTION_ID . " )";
                                $code = $wpdb->query($altersql);
                }    
                
                // option cookie js 
                $twiz_cookie_js_status = get_option('twiz_cookie_js_status');
                
                if( $twiz_cookie_js_status == '' ) {
                
                    $code = update_option('twiz_cookie_js_status', false);
                }                
                 
                // Set ads On
                if(!isset($this->admin_option[parent::KEY_FOOTER_ADS])) $this->admin_option[parent::KEY_FOOTER_ADS] = '';
                $this->admin_option[parent::KEY_FOOTER_ADS] = '0';
                $code = update_option('twiz_admin', $this->admin_option);
                 
                // Admin Settings
                $code = new TwizAdmin(); // Default settings
                
                // Menu reformating
                $myTwizMenu  = new TwizMenu();
                        
                // db version
                $code = update_option('twiz_db_version', $this->dbVersion);
                
            }
        }
        
        return true;
    }
    
    function uninstall(){
    
        global $wpdb;

        if ($wpdb->get_var( "SHOW TABLES LIKE '".$this->table."'" ) == $this->table) {
        
            $sql = "DROP TABLE ". $this->table;
            $wpdb->query($sql);
        }
        
        delete_option('twiz_db_version');
        delete_option('twiz_global_status');
        delete_option('twiz_hscroll_status');
        delete_option('twiz_cookie_js_status');
        delete_option('twiz_sections');
        delete_option('twiz_multi_sections');
        delete_option('twiz_hardsections');
        delete_option('twiz_library');
        delete_option('twiz_library_dir');
        delete_option('twiz_admin');
        delete_option('twiz_setting_menu'); // v1.5+ converted per user
        delete_option('twiz_skin');         // v1.5+ converted per user
        delete_option('twiz_order_by');     // v1.5+ converted per user
        delete_option('twiz_bullet');       // v1.5+ converted per user
        delete_option('twiz_toggle');       // v1.5+ converted per user
        delete_option('twiz_export_filter');// per user, per section
        
        return true;
    }    
}
?>