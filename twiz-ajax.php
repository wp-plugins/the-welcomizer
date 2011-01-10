<?
/*  Copyright 2010  Sebastien Laframboise  (email:wordpress@sebastien-laframboise.com)

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
	
	$postid = attribute_escape(trim($_POST['twiz_id']));
			
	/* Switch action.. */
	switch($_POST['action']){
	
		case Twiz::ACTION_NEW:
		
			$myTwiz  = new Twiz();
			$htmlresponse = $myTwiz->getHtmlForm();		
			break;
			
		case Twiz::ACTION_DELETE:
		
			$myTwiz  = new Twiz();
			$htmlresponse = $myTwiz->delete($postid);
	
			break;
			
		case Twiz::ACTION_EDIT:
		
			$myTwiz  = new Twiz();
			if($htmlresponse = $myTwiz->getHtmlForm($postid)){}else{
				//$htmlresponse = $myTwiz->getHtmlEror($postid, __('Error!', 'the-welcomizer');
				$htmlresponse = $myTwiz->getHtmlList();
			}
			break;
			
		case Twiz::ACTION_CANCEL:
		
			$myTwiz  = new Twiz();
			$htmlresponse = $myTwiz->getHtmlList();		
			break;
			
		case Twiz::ACTION_SAVE:
		
			$myTwiz  = new Twiz();
			if(($saved = $myTwiz->save($postid)) // insert or update
			or($saved=='0')){ // success, but no differences
				$htmlresponse = $myTwiz->getHtmlSuccess($postid,  __('Saved!', 'the-welcomizer'));
				$htmlresponse.= $myTwiz->getHtmlList();		
			}else{
				//$htmlresponse = $myTwiz->getHtmlError($postid,__('Error!', 'the-welcomizer'));
				$htmlresponse = $myTwiz->getHtmlForm();
			}
			break;
			
		case Twiz::ACTION_STATUS:
		
			$myTwiz  = new Twiz();
			$htmlresponse = $myTwiz->switchStatus($postid);	
			
			break;	
			
		case Twiz::ACTION_ID_LIST:
		
			$myTwiz  = new Twiz();
			$htmlresponse = $myTwiz->getHtmlIdList();
			
			break;			 
	}
	
	echo($htmlresponse); // output the result
?>