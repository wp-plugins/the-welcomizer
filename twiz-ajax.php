<?
/*  Copyright 2011  Sebastien Laframboise  (email:wordpress@sebastien-laframboise.com)

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

    /* Nonce security (number used once) */
    $nonce = $_POST['twiz_nonce'];
    if (! wp_verify_nonce($nonce, 'twiz-nonce') ){
        die("0101001101100101011000110111010101110010011010010111010001111001001000000110001101101000011001010110001101101011"); 
    }

    /* Require Twiz Class */
    require_once(dirname(__FILE__).'/includes/twiz.class.php'); 
            
    /* Switch action.. */
    switch(attribute_escape(trim($_POST['twiz_action']))){
        case Twiz::ACTION_SAVE:
        
            $twiz_id = attribute_escape(trim($_POST['twiz_id']));
            
            $myTwiz  = new Twiz();
            if(($saved = $myTwiz->save($twiz_id)) // insert or update
            or($saved=='0')){ // success, but no differences
                $htmlresponse = $myTwiz->getHtmlSuccess(__('Saved!', 'the-welcomizer'));
                $htmlresponse.= $myTwiz->getHtmlList();        
            }else{
                // $htmlresponse = $myTwiz->getHtmlError(__('Error!', 'the-welcomizer'));
                $htmlresponse = $myTwiz->getHtmlForm();
            }
            break;
            
        case Twiz::ACTION_CANCEL:
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->getHtmlList();        
            break;
            
        case Twiz::ACTION_ID_LIST:
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->getHtmlIdList();
            
            break;    
            
        case Twiz::ACTION_OPTIONS:
        
            $twiz_charid = attribute_escape(trim($_POST['twiz_charid']));
            
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->getHtmlOptionList($twiz_charid);
            
            break;                        
            
        case Twiz::ACTION_VIEW:
        
            $twiz_id = attribute_escape(trim($_POST['twiz_id']));
            
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->getHtmlView($twiz_id);    
            
            break;
            
        case Twiz::ACTION_NEW:
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->getHtmlForm();        
            break;

        case Twiz::ACTION_EDIT:
            
            $twiz_id = attribute_escape(trim($_POST['twiz_id']));
            
            $myTwiz  = new Twiz();
            if($htmlresponse = $myTwiz->getHtmlForm($twiz_id)){}else{
                // $htmlresponse = $myTwiz->getHtmlEror(__('Error!', 'the-welcomizer');
                $htmlresponse = $myTwiz->getHtmlList();
            }
            break;
            
        case Twiz::ACTION_EDIT_TD:
        
            $twiz_id = attribute_escape(trim($_POST['twiz_id']));
            $twiz_value = attribute_escape(trim($_POST['twiz_value']));
            $twiz_column = attribute_escape(trim($_POST['twiz_column']));
            
            $myTwiz  = new Twiz();
            if(($saved = $myTwiz->saveValue($twiz_id, $twiz_column, $twiz_value)) // insert or update
            or($saved=='0')){ // success, but no differences
                $htmlresponse = $twiz_value;
            }
        
            break;
            
        case Twiz::ACTION_DELETE:
        
            $twiz_id = attribute_escape(trim($_POST['twiz_id']));
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->delete($twiz_id);
    
            break;

        case Twiz::ACTION_STATUS:
        
            $twiz_id = attribute_escape(trim($_POST['twiz_id']));
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->switchStatus($twiz_id);    
            
            break;    

        case Twiz::ACTION_GLOBAL_STATUS:
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->switchGlobalStatus();    
            
            break;    
    }
    
    echo($htmlresponse); // output the result
?>