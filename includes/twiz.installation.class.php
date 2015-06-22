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
    require_once(dirname(__FILE__).'/twiz.admin.class.php');
    require_once(dirname(__FILE__).'/twiz.library.class.php');
    
class TwizInstallation extends Twiz{
   
                                       
    function __construct(){

        parent::__construct();
    }

    // create directories if not exists
    private function createDirectories(){
    
        if (defined("FS_CHMOD_DIR")){
    
            if( !@file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH) ){
            
                mkdir(WP_CONTENT_DIR. parent::IMPORT_PATH, FS_CHMOD_DIR);
                chmod(WP_CONTENT_DIR. parent::IMPORT_PATH, FS_CHMOD_DIR);
            }
            
            if( !@file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH) ){        
            
                mkdir(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH, FS_CHMOD_DIR);
                chmod(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH, FS_CHMOD_DIR);
            }
            
            if( !@file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH . parent::BACKUP_PATH) ){        
            
                mkdir(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH. parent::BACKUP_PATH, FS_CHMOD_DIR);
                chmod(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH. parent::BACKUP_PATH, FS_CHMOD_DIR);
            }            
            
        }else{
        
            if( !@file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH) ){
            
                wp_mkdir_p(WP_CONTENT_DIR. parent::IMPORT_PATH);
            }
            
            if( !@file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH) ){
            
                wp_mkdir_p(WP_CONTENT_DIR. parent::IMPORT_PATH . parent::EXPORT_PATH);
            }
            
            if( !@file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH . parent::BACKUP_PATH) ){
            
                wp_mkdir_p(WP_CONTENT_DIR. parent::IMPORT_PATH . parent::EXPORT_PATH . parent::BACKUP_PATH);
            }            
        }
    }    
 
    function install( $network_activation = '' ){

        global $wpdb;

        $sql = "CREATE TABLE ".$this->table." (". 
                parent::F_ID . " bigint(20) NOT NULL AUTO_INCREMENT, ". 
                parent::F_PARENT_ID . " varchar(13) NOT NULL default '', ". 
                parent::F_EXPORT_ID . " varchar(13) NOT NULL default '', ". 
                parent::F_BLOG_ID . " varchar(250) NOT NULL default '[".$this->BLOG_ID."]', ". 
                parent::F_SECTION_ID . " varchar(22) NOT NULL default '".$this->DEFAULT_SECTION_HOME."', ". 
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
                                         
             
             $current_table = $wpdb->get_var( "show tables like '".$this->table."'" );
             
             
        if ( $current_table != $this->table ){ // new install

            // create directories on install if non-existent).
            $ok = $this->createDirectories();  
            
            // Create table
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql); 
            
            $ok = $this->setConfigurationSettings( $network_activation, false );   
            
        }else{ // UPDATE TO NEW VERSION, or install new separatly
        
            $is_overriding_network_settings = get_option('twiz_override_network_settings');

            if( ( is_multisite() ) and ( $network_activation  == '' ) ){ // blog activated separately on network         
                
                $twiz_privacy_question_answered = get_option('twiz_privacy_question_answered');
                $twiz_network_activated = get_option('twiz_network_activated');
                if($twiz_privacy_question_answered == ''){ $code = update_option('twiz_privacy_question_answered', false); }
                
                if($is_overriding_network_settings == ''){ 
                
                    $is_overriding_network_settings = '1';
                    $code = update_option('twiz_override_network_settings', '1'); 
                }
                if($twiz_network_activated == ''){ $code = update_option('twiz_network_activated', '0'); }
               
            }else if( is_multisite() and ( $network_activation  == '1' ) ){  // network activated after blog activated separately on network 
            
                $is_overriding_network_settings = ($is_overriding_network_settings == '') ? '0' : $is_overriding_network_settings ;
                
                $twiz_privacy_question_answered = get_site_option('twiz_privacy_question_answered');
                if($twiz_privacy_question_answered == ''){$code = update_site_option('twiz_privacy_question_answered', false);}
                
                $twiz_privacy_question_answered = get_option('twiz_privacy_question_answered');
                if($twiz_privacy_question_answered == ''){$code = update_option('twiz_privacy_question_answered', false);}
                
                // Do not overwrite twiz_override_network_settings, for those already single site activated. 
                $code = update_option('twiz_override_network_settings', $is_overriding_network_settings); 
              
                $code = update_option('twiz_network_activated', '1');
                $code = update_site_option('twiz_network_activated', '1');

            }else{ // v2.8+ blog update on single installation
            
                $is_overriding_network_settings = ($is_overriding_network_settings == '') ? '0' : '1';
                $twiz_privacy_question_answered = get_option('twiz_privacy_question_answered');
                $code = update_option('twiz_override_network_settings',  '0'); 
                $code = update_option('twiz_network_activated', '0');
            }  
            
            if( $twiz_privacy_question_answered == '' ){
            
                $altersql = "ALTER TABLE ".$this->table 
                . " MODIFY ". parent::F_ID . " bigint(20) NOT NULL AUTO_INCREMENT";
                $code = $wpdb->query($altersql);
                
                if( ( !is_multisite() ) or ( $is_overriding_network_settings  == '1' ) ){                 
                
                    $code = update_option('twiz_privacy_question_answered', false);
                    
                }else{

                    $code = update_site_option('twiz_privacy_question_answered', false);
                }                    
            }
            
            // create directories on update(if non-existent).
            $ok = $this->createDirectories();     
 
            if( ( !is_multisite() ) or ( $is_overriding_network_settings  == '1' ) ){
                
                $dbversion = get_option('twiz_db_version');
                
            }else{

                $dbversion = get_site_option('twiz_db_version');
            }             
            
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
                    " ADD ". parent::F_SECTION_ID . " varchar(22) NOT NULL default '".$this->DEFAULT_SECTION_HOME."' after ".parent::F_ID."";
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
                        $exportid = $this->getUniqid();
                        
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
                
                // Bullet menu from <= v 1.5 - KEEP
                if( ( !is_multisite() ) or ( $is_overriding_network_settings  == '1' ) ){

                    $bullet = get_option('twiz_bullet');
                    
                }else{

                    $bullet = get_site_option('twiz_bullet');
                }
                
                $bullet = ( !is_array($bullet) ) ? '' : $bullet;
                
                if( $bullet == '' ){

                    // Plus admin & toggle key changes from <= v 1.5 
                    if(!isset($this->admin_option[parent::KEY_PREFERED_METHOD])){}else{
                    
                        $this->admin_option[parent::KEY_PREFERED_METHOD] = '';
                        unset($this->admin_option[parent::KEY_PREFERED_METHOD]);
                    }
                    
                    if( ( !is_multisite() ) or ( $is_overriding_network_settings  == '1' ) ){

                        $code = update_option('twiz_admin', $this->admin_option);
 
                    }else{

                        $code = update_site_option('twiz_admin', $this->admin_option);
                    }                     
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
                    
                    //Begin Hot Fix from <= v 1.7 - clean unused Library keys - KEEP
                    $myTwizLibrary  = new TwizLibrary();
                    $library = $myTwizLibrary->array_library;
                    $library_dir = $myTwizLibrary->array_library_dir;
                    
                    foreach( $library as $key => $value ){
                     
                        if( !in_array($value[parent::KEY_DIRECTORY], $library_dir)){
              
                            $myTwizLibrary->array_library[$key] = '';
                            
                            unset($myTwizLibrary->array_library[$key]);
                        }
                    }
                    
                    if( ( !is_multisite() ) or ( $is_overriding_network_settings  == '1' ) ){

                        $code = update_option('twiz_library', $myTwizLibrary->array_library);
                        
                    }else{

                        $code = update_site_option('twiz_library', $myTwizLibrary->array_library);        
                    }                     

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
                   
                // from <= v 2.1
                if( !in_array(parent::F_GROUP_ORDER, $array_describe) ){
                
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_GROUP_ORDER . " int(5) NOT NULL default 0 after ".parent::F_EXTRA_JS_B."";
                    $code = $wpdb->query($altersql);
                }
                
                // from <= v2.8 MULTISITE SUPPORT
                if( !in_array(parent::F_BLOG_ID, $array_describe) ){
                
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". parent::F_BLOG_ID . " varchar(250) NOT NULL default '[".$this->BLOG_ID."]' after ".parent::F_PARENT_ID."";
                    $code = $wpdb->query($altersql);
                    
                    // RESET USER OPTIONS ONLY ONCE
                    $code = update_option('twiz_toggle', array()); 
                    $code = update_option('twiz_order_by',array()); 
                    $code = update_option('twiz_skin', array());  
                    $code = update_option('twiz_bullet', array());                         
                    $code = update_option('twiz_setting_menu', array());      
                    $code = update_option('twiz_hscroll_status', array());      
                } 
                                        
                // Set the network activated option 
                if( ( !is_multisite() ) or ( $is_overriding_network_settings  == '1' ) ){
                    
                    $twiz_network_activated = get_option('twiz_network_activated');
                    $twiz_network_activated = ( $twiz_network_activated == '')? '0' : $twiz_network_activated;
                    $code = update_option('twiz_network_activated', $twiz_network_activated); 

                }else{

                    $twiz_network_activated = get_site_option('twiz_network_activated');
                    $twiz_network_activated = ( $twiz_network_activated == '')? '1' : $twiz_network_activated;
                    $code = update_site_option('twiz_network_activated', $twiz_network_activated);
                } 

                // Table indexes
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
                if( ( !is_multisite() ) or ( $is_overriding_network_settings  == '1' ) ){

                    $twiz_cookie_js_status = get_option('twiz_cookie_js_status');
                    
                }else{

                    $twiz_cookie_js_status = get_site_option('twiz_cookie_js_status');
                }                 
                
                if( $twiz_cookie_js_status == '' ){
                    
                    if( ( !is_multisite() ) or ( $is_overriding_network_settings  == '1' ) ){

                        $code = update_option('twiz_cookie_js_status', false);
                        
                    }else{

                        $code = update_site_option('twiz_cookie_js_status', false);
                    }                 
                }                
                
                if( ( !is_multisite() ) or ( $is_overriding_network_settings  == '1' ) ){

                    $code = update_option('twiz_admin', $this->admin_option);
                    
                }else{

                    $code = update_site_option('twiz_admin', $this->admin_option);
                }       
                
                $code = new TwizAdmin();// Default settings
                $code = new TwizMenu(); // Menu reformating
                        
                // db version
                if( ( !is_multisite() ) or ( $is_overriding_network_settings  == '1' ) ){

                    $code = update_option('twiz_db_version', $this->dbVersion);
                    
                    if( is_multisite() ){
                    
                        if( $dbversion == '' ){ $code = update_site_option('twiz_db_version', $this->dbVersion); }
                    }
                
                }else{

                    $code = update_site_option('twiz_db_version', $this->dbVersion);
                } 
            }
        }
        
        return true;
    }

    // Remove directory andor file
    private function RemoveDirectoryAndOrFile( $directory = '' ){
    
        foreach(glob($directory . '/*') as $file){

            if( is_dir($file) ){
            
                $this->RemoveDirectoryAndOrFile($file);
                
            }else{
            
                @unlink($file);
            }
        }
        
        if( substr($directory,-1) == "/" ){
        
            $directory = substr($directory,0,-1);
        }         
        
        @rmdir($directory);
        
        return true;
    }
    
    // remove created directories
    private function removeCreatedDirectories(){
    
        if( @file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH. parent::BACKUP_PATH) ){
        
            $ok = $this->RemoveDirectoryAndOrFile(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH. parent::BACKUP_PATH);
        }
        
        if( @file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH) ){
        
            $ok = $this->RemoveDirectoryAndOrFile(WP_CONTENT_DIR. parent::IMPORT_PATH. parent::EXPORT_PATH);
        }
        
        if( @file_exists(WP_CONTENT_DIR. parent::IMPORT_PATH) ){        
        
            $ok = $this->RemoveDirectoryAndOrFile(WP_CONTENT_DIR. parent::IMPORT_PATH);
        }
    }

    function uninstall( $network_deactivation = '', $is_last_network = ''){
    
        global $wpdb;
        
        $reset_stored_request ='';
        $admin_option ='';
        
        $is_overriding_network_settings = get_option('twiz_override_network_settings');
        
        if( ( !is_multisite() ) or ( $network_deactivation  == '' ) ){ //and ($is_overriding_network_settings == '1'))  ){
        
            $this->admin_option = get_option('twiz_admin');
       
        }else{
        
            $this->admin_option = get_site_option('twiz_admin');
            $admin_option =  get_option('twiz_admin');
        }
        
        if( !isset($this->admin_option[parent::KEY_DELETE_ALL] ) ) { $this->admin_option[parent::KEY_DELETE_ALL] = ''; }
        if( !isset($admin_option[parent::KEY_DELETE_ALL] ) ) { $admin_option[parent::KEY_DELETE_ALL] = ''; }
        
        if( $this->admin_option[parent::KEY_DELETE_ALL] == '' ) { $this->admin_option[parent::KEY_DELETE_ALL] = '1'; }
        if( $admin_option[parent::KEY_DELETE_ALL] == '' ) { $admin_option[parent::KEY_DELETE_ALL] = '1'; }
       
        if( ( $this->admin_option[parent::KEY_DELETE_ALL] == '1' ) or ( $admin_option[parent::KEY_DELETE_ALL] == '1' ) ){
        
            if( ( !is_multisite() ) or ( ( $network_deactivation  == '' ) and ( $is_overriding_network_settings == '1' ) ) ){
                
                // delete table
                if ( $wpdb->get_var( "SHOW TABLES LIKE '".$this->table."'" ) == $this->table ){
                
                    $sql = "DROP TABLE ". $this->table;
                    $wpdb->query($sql);
                    $reset_stored_request = $wpdb->get_var( "show tables like '".$this->table."'" );                       
                }
                
                if( !isset($this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] ) ) { $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] = ''; }
                if( $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] == '' ) { $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] = '1'; }    
                
                // remove directories
                if( $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] == '1' ){        
                
                    $ok = $this->removeCreatedDirectories();
                }                
            }

            // All settings
            delete_option('twiz_network_activated'); 
            delete_option('twiz_override_network_settings'); // site only
            
            delete_option('twiz_privacy_question_answered');
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
            
            if( $is_last_network == '1'){
            
                if ( $wpdb->get_var( "SHOW TABLES LIKE '".$this->table."'" ) == $this->table ){
                
                    $sql = "DROP TABLE ". $this->table;
                    $wpdb->query($sql);
                    $reset_stored_request = $wpdb->get_var( "show tables like '".$this->table."'" ); 
                }     
                
                if( !isset($this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] ) ) { $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] = ''; }
                if( $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] == '' ) { $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] = '1'; }    
                // remove directories
                if( $this->admin_option[parent::KEY_REMOVE_CREATED_DIRECTORIES] == '1' ){        
                
                    $ok = $this->removeCreatedDirectories();
                } 
                
                delete_site_option('twiz_network_activated');
                
                delete_site_option('twiz_privacy_question_answered');
                delete_site_option('twiz_db_version');
                delete_site_option('twiz_global_status');
                delete_site_option('twiz_hscroll_status');
                delete_site_option('twiz_cookie_js_status');
                delete_site_option('twiz_sections');
                delete_site_option('twiz_multi_sections');
                delete_site_option('twiz_hardsections');
                delete_site_option('twiz_library');
                delete_site_option('twiz_library_dir');
                delete_site_option('twiz_admin');
                delete_site_option('twiz_setting_menu');  // per user
                delete_site_option('twiz_skin');          // per user
                delete_site_option('twiz_order_by');      // per user
                delete_site_option('twiz_bullet');        // per user
                delete_site_option('twiz_toggle');        // per user
                delete_site_option('twiz_export_filter'); // per user, per section
            }     
        }

        return true;
    }    
}
?>