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


    /* Require wp-config */
    require_once(dirname(__FILE__).'/../../../wp-config.php');
    
    /* Require Twiz Class */
    require_once(dirname(__FILE__).'/includes/twiz.class.php'); 
    require_once(dirname(__FILE__).'/includes/twiz.library.class.php'); 

    /* Nonce security (number used once) */
    $_POST['twiz_nonce'] = (!isset($_POST['twiz_nonce'])) ? '' : $_POST['twiz_nonce'] ;
    $_GET['twiz_nonce'] = (!isset($_GET['twiz_nonce'])) ? '' : $_GET['twiz_nonce'] ;
    $nonce = ($_POST['twiz_nonce']=='') ? $_GET['twiz_nonce'] : $_POST['twiz_nonce'];
    
    if (! wp_verify_nonce($nonce, 'twiz-nonce') ) {
    
        die("Security check"); 
    }

    /* actions */
    $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'] ;
    $_GET['twiz_action'] = (!isset($_GET['twiz_action'])) ? '' : $_GET['twiz_action'];    
    $action = ($_POST['twiz_action']=='') ? $_GET['twiz_action'] : $_POST['twiz_action'];
    
    $htmlresponse = '';
     
    switch(esc_attr(trim($action))){ 
    
        case Twiz::ACTION_MENU:
        
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
        
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlList($twiz_section_id);
            
            break;

        case Twiz::ACTION_ADD_SECTION:
        
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
        
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->addSectionMenu($twiz_section_id);
            
            break;

        case Twiz::ACTION_DELETE_SECTION:
        
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
        
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->deleteSectionMenu($twiz_section_id);
            
            break;
            
        case Twiz::ACTION_SAVE:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            
            if(($saved = $myTwiz->save($twiz_id)) // insert or update
            or($saved=='0')){ // success, but no differences
            
               // $htmlresponse = $myTwiz->getHtmlSuccess(__('Saved!', 'the-welcomizer'));
                $htmlresponse.= $myTwiz->getHtmlList($twiz_section_id);        
                
            }else{
            
                $htmlresponse = $myTwiz->getHtmlForm();
                
            }
            break;
            
        case Twiz::ACTION_CANCEL:
        
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlList($twiz_section_id);       
            
            break;
            
        case Twiz::ACTION_ID_LIST:
        
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlIdList();
            
            break;    
            
        case Twiz::ACTION_OPTIONS:
        
            $twiz_charid = esc_attr(trim($_POST['twiz_charid']));
            
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlOptionList($twiz_charid);
            
            break;                        
            
        case Twiz::ACTION_VIEW:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlView($twiz_id);    
            
            break;
            
        case Twiz::ACTION_NEW:
        
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlForm();     
            
            break;

        case Twiz::ACTION_EDIT:
            
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            
            if($htmlresponse = $myTwiz->getHtmlForm($twiz_id)){}else{
                $htmlresponse = $myTwiz->getHtmlList($twiz_section_id);
            }
            
            break;
            
        case Twiz::ACTION_EDIT_TD:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_value = esc_attr(trim($_POST['twiz_value']));
            $twiz_column = esc_attr(trim($_POST['twiz_column']));
            
            $myTwiz  = new Twiz();
                       
            if(($saved = $myTwiz->saveValue($twiz_id, $twiz_column, $twiz_value)) // insert or update
            or($saved=='0')){ // success, but no differences
            
                if($twiz_column=="duration"){
                
                   $htmlresponse = $myTwiz->formatDuration($twiz_id);
                   
                }else{
                
                   $htmlresponse = $myTwiz->getValue($twiz_id, $twiz_column);
                   
                }
            }
        
            break;
            
        case Twiz::ACTION_DELETE:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->delete($twiz_id);
    
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
        
            $twiz_section_id = esc_attr(trim($_GET['twiz_section_id']));
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->export($twiz_section_id);    
            
            break;             
            
        case Twiz::ACTION_LIBRARY:

            $myTwizLibrary  = new TwizLibrary();
            $htmlresponse = $myTwizLibrary->getHtmlLibrary();    
            
            break;    
            
        case Twiz::ACTION_LIBRARY_STATUS:
            
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            
            $myTwizLibrary  = new TwizLibrary();
            $htmlresponse = $myTwizLibrary->switchLibraryStatus($twiz_id);    
            
            break;   
            
       case Twiz::ACTION_DELETE_LIBRARY:
            
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            
            $myTwizLibrary  = new TwizLibrary();
            $htmlresponse = $myTwizLibrary->deleteLibrary($twiz_id);    
            
            break; 
    }
    
    echo($htmlresponse); // output the result
?>