<?php
if ( defined('ABSPATH') ){
    require_once(ABSPATH . 'wp-load.php');
}
else{
    require_once('../../../wp-load.php');
}

require_once(dirname(__FILE__).'/includes/twiz.class.php'); 
require_once(dirname(__FILE__).'/includes/twiz.library.class.php');  

$myTwiz  = new Twiz();
header("Content-type: text/javascript"); 
echo($myTwiz->getAjaxHeader());
?>