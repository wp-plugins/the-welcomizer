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

  // Info: http://wordpress.org/support/topic/fatal-error-call-to-undefined-function-wp_verify_nonce
  if ( defined('ABSPATH') ){
  
    require_once(ABSPATH .'wp-includes/pluggable.php'); 
    require_once(ABSPATH .'wp-includes/l10n.php'); 
    
  }else{
  
    require_once( '../../../wp-includes/pluggable.php'); 
    require_once( '../../../wp-includes/l10n.php'); 
  }
    
  function twiz_ajax_callback(){
  
   global $wpdb;
   
    /* Nonce security (number used once) */
    if(!isset($_POST['twiz_nonce'])) $_POST['twiz_nonce'] =  '';
    $nonce = $_POST['twiz_nonce'];
    
    if (!wp_verify_nonce($nonce, 'twiz-nonce') ) {
    
        die("You are not logged in."); 
    }

    /* actions */
    if(!isset($_POST['twiz_action'])) $_POST['twiz_action'] =  '';   
    if(!isset($_POST['twiz_stay'])) $_POST['twiz_stay'] =  '';   

    $twiz_action = $_POST['twiz_action'];
    
    if(!isset($_POST['twiz_section_id'])) $_POST['twiz_section_id'] =  '';   

    $htmlresponse = '';
    
    switch(esc_attr(trim($twiz_action))){ 
    
       case Twiz::ACTION_TOGGLE:
       
            $twiz_charid = esc_attr(trim($_POST['twiz_charid']));  
            $twiz_toggle_type = esc_attr(trim($_POST['twiz_toggle_type']));  
            $twiz_toggle_status = esc_attr(trim($_POST['twiz_toggle_status']));  
            
            $myTwiz  = new Twiz();
            
            if(!isset($myTwiz->toggle_option[$myTwiz->userid][$twiz_toggle_type][$twiz_charid])) $myTwiz->toggle_option[$myTwiz->userid][$twiz_toggle_type][$twiz_charid] = '';
            
            $myTwiz->toggle_option[$myTwiz->userid][$twiz_toggle_type][$twiz_charid] = $twiz_toggle_status;

            $code = update_option('twiz_toggle', $myTwiz->toggle_option);

            break;
            
        case Twiz::ACTION_MENU:
        
            $_POST['twiz_order_by'] = (!isset($_POST['twiz_order_by'])) ? '' : esc_attr($_POST['twiz_order_by']) ;
            
            $twiz_section_id = esc_attr($_POST['twiz_section_id']);
                
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, '' , $_POST['twiz_order_by'], '', $twiz_action);

            break;
            
        case Twiz::ACTION_MENU_STATUS:
            
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
        
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->switchMenuStatus($twiz_id);

            break;    
        case Twiz::ACTION_VMENU_STATUS:

            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->switchMenuStatus($twiz_id);

            break;  

        case Twiz::ACTION_GET_MENU:
       
            // Needed for translation
            load_default_textdomain();
            
            $twiz_section_id = $_POST['twiz_section_id'];
            
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->getHtmlMenu($twiz_section_id);

            break; 
            
        case Twiz::ACTION_GET_VMENU:
       
            // Needed for translation
            load_default_textdomain();
            
            $twiz_section_id = $_POST['twiz_section_id'];
            
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->getHtmlVerticalMenu($twiz_section_id);

            break;  
            
        case Twiz::ACTION_SAVE_SECTION:

            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));

            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->saveSectionMenu( $twiz_section_id );
            
            break;
            
        case Twiz::ACTION_GET_MULTI_SECTION:

            // Needed for translation
            load_default_textdomain();
            
            $twiz_section_id = esc_attr($_POST['twiz_section_id']);
            $twiz_action_lbl = esc_attr($_POST['twiz_action_lbl']);
            
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->GetHtmlMultiSectionBoxes($twiz_section_id, $twiz_action_lbl);
            
            break;            

        case Twiz::ACTION_DELETE_SECTION:
        
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
        
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->deleteSectionMenu($twiz_section_id);
            
            break;

        case Twiz::ACTION_GET_FINDANDREPLACE:

            require_once(dirname(__FILE__).'/includes/twiz.findandreplace.class.php'); 

            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            $myTwizFindAndReplace  = new TwizFindAndReplace();
            
            $htmlresponse = $myTwizFindAndReplace->GetHtmlFormFindAndReplace( $twiz_section_id );
            
            break;  

        case Twiz::ACTION_FAR_FIND:

            require_once(dirname(__FILE__).'/includes/twiz.findandreplace.class.php'); 
            
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            $myTwizFindAndReplace  = new TwizFindAndReplace();
            
            $htmlresponse = $myTwizFindAndReplace->Find( $twiz_section_id );
            
            break; 
    
        case Twiz::ACTION_FAR_REPLACE:

            require_once(dirname(__FILE__).'/includes/twiz.findandreplace.class.php'); 
            
            $twiz_section_id = esc_attr($_POST['twiz_section_id']);
            $myTwizFindAndReplace  = new TwizFindAndReplace();
            
            $htmlresponse = $myTwizFindAndReplace->Replace( $twiz_section_id );
            
            break; 
            
        case Twiz::ACTION_SAVE_FAR_PREF_METHOD:
        
            $myTwiz  = new Twiz();
            $twiz_far_choice = esc_attr(trim($_POST['twiz_far_choice']));
        
            $myTwiz  = new Twiz();
            
            if(!isset($myTwiz->toggle_option[$myTwiz->userid][Twiz::KEY_PREFERED_METHOD])) $myTwiz->toggle_option[$myTwiz->userid][Twiz::KEY_PREFERED_METHOD] = '';
            
            $myTwiz->toggle_option[$myTwiz->userid][Twiz::KEY_PREFERED_METHOD] = $twiz_far_choice;

            $code = update_option('twiz_toggle', $myTwiz->toggle_option);
                    
            break;
            
        case Twiz::ACTION_DROP_ROW:
        
            if(!isset($_POST['twiz_from_id'])) $_POST['twiz_from_id'] = '';
            if(!isset($_POST['twiz_to_id'])) $_POST['twiz_to_id'] = '';
            $twiz_section_id = esc_attr($_POST['twiz_section_id']);     
            $twiz_from_id = esc_attr(trim($_POST['twiz_from_id']));
            $twiz_to_id = esc_attr(trim($_POST['twiz_to_id']));
            
            $myTwiz  = new Twiz();
            
            $parentid = $myTwiz->getValue($twiz_from_id, Twiz::F_PARENT_ID);
            
            if($save = $myTwiz->saveValue( $twiz_from_id, Twiz::F_PARENT_ID,  $twiz_to_id )){ 
            
                $htmlresponse = $myTwiz->getHtmlList($twiz_section_id,$twiz_from_id, '', $twiz_to_id, $twiz_action);     
                
            }

            break; 
            
        case Twiz::ACTION_GET_GROUP:

            require_once(dirname(__FILE__).'/includes/twiz.group.class.php'); 
                
            if(!isset($_POST['twiz_group_id'])) $_POST['twiz_group_id'] = '';
            $twiz_group_id = esc_attr(trim($_POST['twiz_group_id']));
            $twiz_section_id = esc_attr($_POST['twiz_section_id']);
            $myTwizGroup  = new TwizGroup();
            
            $htmlresponse = $myTwizGroup->GetHtmlFormGroup( $twiz_group_id, $twiz_section_id );
            
            break; 
            
        case Twiz::ACTION_SAVE_GROUP:

            require_once(dirname(__FILE__).'/includes/twiz.group.class.php'); 
            $twiz_group_id = esc_attr(trim($_POST['twiz_group_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            $myTwizGroup  = new TwizGroup();
            
            if($save = $myTwizGroup->SaveGroup( $twiz_group_id )){ 

                $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, $save['id'], '','' , $twiz_action);   
            }
            
            $htmlresponse = json_encode( array('result' => $save['result'], 'html' =>  $htmlresponse)); 
            
            break; 
            
        case Twiz::ACTION_COPY_GROUP:

            require_once(dirname(__FILE__).'/includes/twiz.group.class.php'); 
            
            $twiz_group_id = esc_attr(trim($_POST['twiz_group_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            $myTwiz  = new Twiz();
            $myTwizGroup  = new TwizGroup();
            
            if( $parentid = $myTwizGroup->CopyGroup( $twiz_group_id )){
            
                $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, $parentid, '', '', $twiz_action);  
            }
            
            break; 
            
        case Twiz::ACTION_DELETE_GROUP:

            require_once(dirname(__FILE__).'/includes/twiz.group.class.php'); 
            
            $twiz_group_id = esc_attr(trim($_POST['twiz_group_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            $myTwiz  = new Twiz();
            $myTwizGroup  = new TwizGroup();
            
            if( $code = $myTwizGroup->DeleteGroup( $twiz_group_id ) ){
            
                $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, '', '', '', $twiz_action);  
            }
            
            break; 
            
        case Twiz::ACTION_SAVE:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_parent_id = esc_attr(trim($_POST['twiz_parent_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            $twiz_stay = esc_attr(trim($_POST['twiz_stay']));
            
            $myTwiz  = new Twiz();
            
            if($save = $myTwiz->save($twiz_id)){ 
            
                if($twiz_stay != 'true'){
              
                    $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, $save['id'], '', $twiz_parent_id, $twiz_action);   
                    
                }else{
                    if($htmlresponse = $myTwiz->getHtmlForm($save['id'], Twiz::ACTION_EDIT, $twiz_section_id)){}else{
                    
                        $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, '', '', $twiz_parent_id, $twiz_action);
                    }
                }
                
            }else{
            
                $htmlresponse = $myTwiz->getHtmlForm();
            }
            
            $htmlresponse = json_encode( array('id' => $save['id'],'result' => $save['result'], 'html' => $htmlresponse )); 
                
            break;
            
        case Twiz::ACTION_CANCEL:
        
            $twiz_parent_id = esc_attr(trim($_POST['twiz_parent_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, '', '', $twiz_parent_id, $twiz_action);       
            
            break;

        case Twiz::ACTION_OPTIONS:
            
            $twiz_charid = esc_attr(trim($_POST['twiz_charid']));        
            
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlOptionList( $twiz_charid );
            
            break;                        
            
        case Twiz::ACTION_VIEW:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlView($twiz_id);    
            
            break;
            
        case Twiz::ACTION_NEW:
        
            $myTwiz  = new Twiz();
            
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            $twiz_parent_id = esc_attr(trim($_POST['twiz_parent_id']));
            $htmlresponse = $myTwiz->getHtmlForm('', Twiz::ACTION_NEW, $twiz_section_id, $twiz_parent_id);     
            
            break;

        case Twiz::ACTION_EDIT:
            
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            
            if($htmlresponse = $myTwiz->getHtmlForm($twiz_id, Twiz::ACTION_EDIT, $twiz_section_id)){}else{
            
                $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, '', '', '', $twiz_action);
            }
            
            break;
            
        case Twiz::ACTION_EDIT_TD:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_value = esc_attr(trim($_POST['twiz_value']));
            $twiz_column = esc_attr(trim($_POST['twiz_column']));
            
            $myTwiz  = new Twiz();
                       
            if(($twiz_column == 'duration') or ($twiz_column == 'delay')){
            
                $twiz_value = ( $twiz_value == '' ) ? '0' : $twiz_value;
            }        
            
            if(($saved = $myTwiz->saveValue($twiz_id, $twiz_column, $twiz_value)) // insert or update
            or($saved=='0')){ // success, but no differences
            
                switch($twiz_column){
                
                    case 'duration':
                    
                       $htmlresponse = $myTwiz->formatDuration($twiz_id);
                       
                       break;
                       
                    case 'delay':
                    
                       $htmlresponse = $twiz_value;
                       
                       break;
                       
                    case 'on_event':
                    
                       $htmlresponse = $myTwiz->format_on_event($twiz_value);
                       
                       break;
                }
            }
        
            break;
            
         case Twiz::ACTION_COPY:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            
            if($htmlresponse = $myTwiz->getHtmlForm($twiz_id, Twiz::ACTION_COPY, $twiz_section_id)){}else{
            
                $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, '', '', '', $twiz_action);
            }
    
            break;
            
        case Twiz::ACTION_DELETE:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            $twiz_parent_id = esc_attr(trim($_POST['twiz_parent_id']));
            
            $myTwiz  = new Twiz();
            $ok = $myTwiz->delete($twiz_id);
            $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, '', '', $twiz_parent_id, $twiz_action);       
    
            break;

        case Twiz::ACTION_STATUS:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->switchStatus($twiz_id);    
            
            break;    

        case Twiz::ACTION_GLOBAL_STATUS:
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->switchGlobalStatus();    
            
            break;    
            
        case Twiz::ACTION_EXPORT:
        
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->export($twiz_section_id, $twiz_id);    
            
            break;             
            
        case Twiz::ACTION_LIBRARY:
        
            $admin_option = get_option('twiz_admin'); 
        
            if((current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LEVEL]))
            and(current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LIBRARY]))){
            
                $myTwizLibrary  = new TwizLibrary();
                $htmlresponse = $myTwizLibrary->getHtmlLibrary();    
            }
            
            break;    
        
        case Twiz::ACTION_GET_LIBRARY_DIR:
        
            $admin_option = get_option('twiz_admin'); 
        
            if((current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LEVEL]))
            and(current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LIBRARY]))){
            
                $myTwizLibrary  = new TwizLibrary();
                $htmlresponse = $myTwizLibrary->GetHtmlFormLibrary();    
            }
            
            break;          
            
        case Twiz::ACTION_LINK_LIBRARY_DIR:
        
            $admin_option = get_option('twiz_admin'); 
        
            if((current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LEVEL]))
            and(current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LIBRARY]))){
            
                $twiz_lib_dir = esc_attr(trim($_POST['twiz_lib_dir']));
                $myTwizLibrary  = new TwizLibrary();
                
                if( $dir = $myTwizLibrary->linkLibraryDir($twiz_lib_dir ) ){
                
                    $htmlresponse = $myTwizLibrary->getHtmlLibrary( $dir );      
                }                
            }
            
            break;          
            
        case Twiz::ACTION_UNLINK_LIBRARY_DIR:
        
            $admin_option = get_option('twiz_admin'); 
        
            if((current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LEVEL]))
            and(current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LIBRARY]))){
            
                $twiz_id = esc_attr(trim($_POST['twiz_id']));
                $myTwizLibrary  = new TwizLibrary();
                
                if( $ok = $myTwizLibrary->unlinkLibraryDir( $twiz_id ) ){
                
                    $htmlresponse = $myTwizLibrary->getHtmlLibrary( );      
                }                
            }
            
            break;  
            
        case Twiz::ACTION_LIBRARY_STATUS:
        
            $admin_option = get_option('twiz_admin'); 
        
            if((current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LEVEL]))
            and(current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LIBRARY]))){
            
                $twiz_id = esc_attr(trim($_POST['twiz_id']));
                
                $myTwizLibrary  = new TwizLibrary();
                $htmlresponse = $myTwizLibrary->switchLibraryStatus($twiz_id);    
            }
            
            break;   
            
       case Twiz::ACTION_DELETE_LIBRARY:
       
            $admin_option = get_option('twiz_admin'); 
            
            if((current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LEVEL]))
            and(current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LIBRARY]))){
                
                $twiz_id = esc_attr(trim($_POST['twiz_id']));
                
                $myTwizLibrary  = new TwizLibrary();
                if($ok = $myTwizLibrary->deleteLibrary($twiz_id)){
                    $htmlresponse = $myTwizLibrary->getHtmlLibrary(); 
                }
            }
            
            break; 
            
       case Twiz::ACTION_ORDER_LIBRARY:
       
            $admin_option = get_option('twiz_admin'); 
            
            if((current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LEVEL]))
            and(current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LIBRARY]))){
            
                $twiz_id = esc_attr(trim($_POST['twiz_id']));
                $twiz_order = esc_attr(trim($_POST['twiz_order']));
                
                $myTwizLibrary  = new TwizLibrary();
                
                if( $updated =  $myTwizLibrary->updateLibraryOrder($twiz_id, $twiz_order) ) {
                
                    $htmlresponse = $myTwizLibrary->getHtmlLibrary(); 
                }
            }
            
            break;       

        case Twiz::ACTION_ADMIN:
        
            $admin_option = get_option('twiz_admin'); 
            
            if((current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LEVEL]))
            and(current_user_can($admin_option[Twiz::KEY_MIN_ROLE_ADMIN]))){            

                $myTwizAdmin  = new TwizAdmin();
                $htmlresponse = $myTwizAdmin->getHtmlAdmin();    
            }
            
            break;     

        case Twiz::ACTION_SAVE_ADMIN:
        
            $admin_option = get_option('twiz_admin'); 
           
            if((current_user_can($admin_option[Twiz::KEY_MIN_ROLE_LEVEL]))
            and(current_user_can($admin_option[Twiz::KEY_MIN_ROLE_ADMIN]))){

                $myTwizAdmin  = new TwizAdmin();
                $htmlresponse = $myTwizAdmin->saveAdmin();    
            }
            
            break;             
            
        case Twiz::ACTION_BULLET_UP:
        
            $myTwiz  = new Twiz();
            $bullet = get_option('twiz_bullet'); 
            
            if(!isset($bullet[$myTwiz->userid])) $bullet[$myTwiz->userid] =  '';
            $bullet[$myTwiz->userid] = Twiz::LB_ORDER_UP;

            $code = update_option('twiz_bullet', $bullet);
            
            break;
            
        case Twiz::ACTION_BULLET_DOWN:

            $myTwiz  = new Twiz();
            $bullet = get_option('twiz_bullet'); 
            
            if(!isset($bullet[$myTwiz->userid])) $bullet[$myTwiz->userid] =  '';
            $bullet[$myTwiz->userid] = Twiz::LB_ORDER_DOWN;

            $code = update_option('twiz_bullet', $bullet);

            break;
                        
        case Twiz::ACTION_SAVE_SKIN:
                    
            $myTwiz  = new Twiz();
            $twiz_skin = esc_attr(trim($_POST['twiz_skin']));
            
            $myTwiz->skin[$myTwiz->userid] = Twiz::SKIN_PATH.$twiz_skin;  
            $code = update_option('twiz_skin', $myTwiz->skin);
            
            break;
            
        case Twiz::ACTION_GET_MAIN_ADS:
           
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->getHtmlAds();    
            
            break;           
            
        case Twiz::ACTION_GET_EVENT_LIST:
           
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_event = esc_attr(trim($_POST['twiz_event']));
           
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->getHTMLEventList($twiz_event, $twiz_id, 'twiz-select-event-list');
            
            break;
    }
    
    echo($htmlresponse); // output the result
    die();
 }
?>