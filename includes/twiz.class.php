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

class Twiz{
	
	/* variable declaration */
	var $plugin_name;
	var $plugin_url;
	var $table;
	var $version;
	var $dbversion;
	var $logoUrl;
	var $logobigUrl;
	
	/* class action constants */ 
	const ACTION_SAVE		= 'save';
	const ACTION_CANCEL		= 'cancel';
	const ACTION_ID_LIST	= 'idlist';
	const ACTION_OPTIONS	= 'options';
	const ACTION_NEW		= 'new';
	const ACTION_EDIT		= 'edit';
	const ACTION_DELETE		= 'delete';
	const ACTION_STATUS		= 'status';
	const ACTION_GLOBAL_STATUS = 'gstatus';
	
	/* class jquery common options constants */ 
	const JQUERY_HEIGHT			= 'height';
	const JQUERY_WITDH			= 'width';
	const JQUERY_OPACITY		= 'opacity';
	const JQUERY_MARGINLEFT		= 'marginLeft';
	const JQUERY_FONTSIZE		= 'fontSize';
	const JQUERY_BORDERWIDTH	= 'borderWidth';
	const JQUERY_BORDERIGHTWIDTH = 'borderRightWidth';
	const JQUERY_BORDERLEFTWIDTH = 'borderLeftWidth';

	/* action type array */
	var $actiontypes = array('ACTION_SAVE'		=> self::ACTION_SAVE		// form action
							,'ACTION_CANCEL'	=> self::ACTION_CANCEL		// form action
							,'ACTION_ID_LIST'	=> self::ACTION_ID_LIST		// form action
							,'ACTION_OPTIONS'	=> self::ACTION_OPTIONS		// form action
							,'ACTION_NEW'		=> self::ACTION_NEW			// list action
							,'ACTION_EDIT'		=> self::ACTION_EDIT		// list action
							,'ACTION_DELETE'	=> self::ACTION_DELETE		// list action
							,'ACTION_STATUS'	=> self::ACTION_STATUS		// list action
							,'ACTION_GLOBAL_STATUS'	=> self::ACTION_GLOBAL_STATUS	// list action
							);
							
	/* jQuery common options array */
	var $jQueryoptions = array('JQUERY_HEIGHT'			=> self::JQUERY_HEIGHT
							  ,'JQUERY_WITDH'			=> self::JQUERY_WITDH	
							  ,'JQUERY_OPACITY'			=> self::JQUERY_OPACITY
							  ,'JQUERY_MARGINLEFT'		=> self::JQUERY_MARGINLEFT
							  ,'JQUERY_FONTSIZE'		=> self::JQUERY_FONTSIZE
							  ,'JQUERY_BORDERWIDTH'		=> self::JQUERY_BORDERIGHTWIDTH
							  ,'JQUERY_BORDERIGHTWIDTH'	=> self::JQUERY_BORDERIGHTWIDTH
							  );
		
	function __construct(){
	
		global $wpdb;
		
		/* Twiz variable configuration */
		$this->plugin_name	= __('The Welcomizer', 'the-welcomizer');
		$this->plugin_url	= get_option('siteurl').'/wp-content/plugins/the-welcomizer';
		$this->table		= $wpdb->prefix .'the_welcomizer';
		$this->version		= 'v1.2.4';
		$this->dbversion	= 'v1.0';
		$this->logoUrl		= '/images/twiz-logo.png';
		$this->logobigUrl	= '/images/twiz-logo-big.png';
	}
	
	function twizIt(){
		$csscript = '<style type="text/css">
#twiz_plugin{
	width:100%;
}
#twiz_master{
	width:350px;
	margin-left:15px;
	margin-bottom:15px;
	float:left;
}
#twiz_container{
	width:350px;
	display:none;
}
.twiz-loading-gif{
	display:none;
	height:20px;
}
#twiz_background {
	z-index:-1;
	position:absolute;
	width:80%;
	min-height:2000px;
	opacity:0.15;	
	background: url("'.$this->plugin_url.$this->logobigUrl.'") top right no-repeat;	
}
#twiz_global_status{
	margin:5px 0 0 176px;
	width:25px;
	height:25px;
	border:1px solid #D1D1D1;
	background: -moz-linear-gradient(center bottom , #D7D7D7, #E4E4E4) repeat scroll 0 0 transparent;
    background: -webkit-gradient(linear, left top, center bottom, from(#D7D7D7), to(#E4E4E4));
    background: -khtml-gradient(linear, left top, center bottom, from(#D7D7D7), to(#E4E4E4));	
	-moz-border-radius-topright:6px;
	-moz-border-radius-topleft:6px;
	-webkit-border-top-right-radius:6px;
	-webkit-border-top-left-radius:6px;	
	-khtml-border-radius-topright:6px;	
	-khtml-border-radius-topleft:6px;		
}
#twiz_global_status img{
	margin:3px;
}
#twiz_global_status img:hover{
	cursor:pointer;
}

</style>';

		$html = $csscript.'<div id="twiz_plugin">';
		$html.= '<div id="twiz_background"></div>';
		$html.= '<div id="twiz_global_status">'.$this->getImgGlobalStatus().'</div>'; 
		$html.= '<div id="twiz_master">';
		$html.= $this->getAjaxHeader(); // private 
		$html.= $this->getHtmlHeader(); // private 
		$html.= $this->getHtmlList();
		$html.= $this->getHtmlFooter(); // private 
		$html.= '</div>';
		$html.= '</div>'; 
		
		return $html;
	}
	
	private function getHtmlHeader(){
	
		$csscript = '<style type="text/css">
#twiz_head_title{
	font-size:16px;
	line-height:30px;
	font-family:tahoma;
}
#twiz_head_version{
	font-size:10px;
}
#twiz_head_addnew{
	float:right;
	margin-right:15px;
}
#twiz_header {
	color:#777777;
	text-shadow:2px 2px 6px #666666;
	border:1px solid #D1D1D1;
	background: -moz-linear-gradient(center bottom , #D7D7D7, #E4E4E4) repeat scroll 0 0 transparent;
    background: -webkit-gradient(linear, left top, center bottom, from(#D7D7D7), to(#E4E4E4));
    background: -khtml-gradient(linear, left top, center bottom, from(#D7D7D7), to(#E4E4E4));
	display:table-cell;
	width:350px;
	padding:5px 0 0 5px;
	-moz-border-radius-topleft:6px;
	-webkit-border-top-left-radius:6px;
	-khtml-border-radius-topleft:6px;
	-moz-border-radius-topright:6px;
	-webkit-border-top-right-radius:6px;
	-khtml-border-radius-topright:6px;	
}
#twiz_header img{
	width:80px;
	padding-right:3px;
	display:inline;
}</style>';

		$header = $csscript.'<div id="twiz_header">
	<img src="'.$this->plugin_url.$this->logoUrl.'" align="left"/>
	<span id="twiz_head_title">'.$this->plugin_name.'</span><br>
	<span id="twiz_head_version">'.$this->version.'</span> 
	<span id="twiz_head_addnew"><a class="button-secondary" id="twiz_new" name="twiz_new">'.__('Add New', 'the-welcomizer').'</a></span> 
</div>
<div style="height:0px;clear:both;"></div>';
		
		return $header;
	}
	
	private function getHtmlFooter(){
	
		$csscript = '<style type="text/css">
#twiz_footer {
	text-align:center;
	font-size:8px;
	color:#464646;
	text-shadow:2px 2px 6px #666666;
	border:1px solid #D1D1D1;
	background:-moz-linear-gradient(center bottom , #D7D7D7, #E4E4E4) repeat scroll 0 0 transparent;
	background: -webkit-gradient(linear, left top, center bottom, from(#D7D7D7), to(#E4E4E4));
    background: -khtml-gradient(linear, left top, center bottom, from(#D7D7D7), to(#E4E4E4));
	display:table-cell;
	width:350px;
	padding:5px;
	-moz-border-radius-bottomleft:6px;
	-moz-border-radius-bottomright:6px;
	-webkit-border-bottom-left-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-khtml-border-radius-bottomleft:6px;
	-khtml-border-radius-bottomright:6px;
}
#twiz_footer a{text-decoration:none;}
#twiz_footer a:hover{text-decoration:underline;}
}</style>';

		$header = $csscript.'<div id="twiz_footer">
'.__('Developed by', 'the-welcomizer').' <a href="http://www.sebastien-laframboise.com" target="_blank">'.utf8_encode('Sébastien Laframboise').'</a>. '.__('Licensed under the GPL version 2.0', 'the-welcomizer').'</div>';
		
		return $header;
	}	
	
	private function getAjaxHeader(){
	
		$header = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
 var hide_MessageDelay = 1234;
 var bind_New = function() {
	$("#twiz_new").click(function(){
	 $(this).fadeOut("fast");
	 $("#twiz_container").fadeOut("slow");
	    $.post("'.$this->plugin_url.'/twiz-ajax.php'.'", { "action": "'.self::ACTION_NEW.'"}, function(data) {
			$("#twiz_container").html(data);
			$("#twiz_container").fadeIn("fast");
			bind_Cancel();bind_Save();bind_Number_Restriction();
			bind_More_Options();bind_Choose_FromId();bind_Choose_Options();
		});
    });
 }
 var bind_Status = function() {
    $("img[name^=twiz_status]").click(function(){
		var textid = $(this).attr("name");
		var numid = textid.substring(12,textid.length);
		if(numid!="global"){
			$(this).hide();
			$("#twiz_img_status_" + numid).fadeIn("slow");		
			$.post("'.$this->plugin_url.'/twiz-ajax.php'.'", { "action": "'.self::ACTION_STATUS.'","twiz_id": numid}, function(data) {
				$("#twiz_td_status_" + numid).html(data);
				$("img[name^=twiz_status]").unbind("click");
				bind_Status();
			});
		}else{
			$(this).hide();
			$("#twiz_img_status_global").fadeIn("slow");		
			$.post("'.$this->plugin_url.'/twiz-ajax.php'.'", { "action": "'.self::ACTION_GLOBAL_STATUS.'"}, function(data) {
				$("#twiz_global_status").html(data);
				$("img[name^=twiz_status]").unbind("click");
				bind_Status();
			});
		}
    });
 }

 var bind_Edit = function() {
    $("img[name^=twiz_edit]").click(function(){
		var textid = $(this).attr("name");
		var numid = textid.substring(10,textid.length);
		$(this).hide();
		$("#twiz_img_edit_" + numid).fadeIn("slow");	
		$("#twiz_new").fadeOut("slow");
	    $.post("'.$this->plugin_url.'/twiz-ajax.php'.'", { "action": "'.self::ACTION_EDIT.'","twiz_id": numid}, function(data) {
			$("#twiz_container").html(data);
			$("#twiz_container").show("fast");
			$("img[name^=twiz_status]").unbind("click");
			$("img[name^=twiz_edit]").unbind("click");
			bind_Status();bind_Save();bind_Cancel();bind_Number_Restriction();
			bind_More_Options();bind_Choose_FromId();bind_Choose_Options();
		});
    });
 }
 var bind_Delete = function() {
	$("img[name^=twiz_delete]").click(function(){
		if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
			var textid = $(this).attr("name");
			var numid = textid.substring(12,textid.length);	
			$(this).hide();
			$("#twiz_img_delete_" + numid).fadeIn("slow");
			$.post("'.$this->plugin_url.'/twiz-ajax.php'.'", { "action": "'.self::ACTION_DELETE.'",
				 "twiz_id": numid }, function(data) {		
				 $("#twiz_list_tr_" + numid).fadeOut();
			});
		}
   });
 }
 var bind_Cancel = function() {
	$("#twiz_cancel").click(function(){
	$("#twiz_new").fadeIn("slow");
	$("#twiz_container").fadeOut("slow");
    $.post("'.$this->plugin_url.'/twiz-ajax.php'.'", {"action": "'.self::ACTION_CANCEL.'"}, function(data) {
		$("#twiz_container").fadeIn("fast");
		$("#twiz_container").html(data);
		$("img[name^=twiz_status]").unbind("click");
		$("img[name^=twiz_cancel]").unbind("click");
		$("img[name^=twiz_save]").unbind("click");
		bind_Status();bind_Delete();bind_Edit();
	});
   });
 }
 var bind_Save = function() {
    $("#twiz_save").click(function(){
    $("#twiz_save_img").fadeIn("slow");
	$("#twiz_new").fadeIn("slow");
    $.post("'.$this->plugin_url.'/twiz-ajax.php'.'", { "action": "'.self::ACTION_SAVE.'",
		 "twiz_id": $("#twiz_id").val(),
		 "twiz_status": $("#twiz_status").is(":checked"),
		 "twiz_layer_id": $("#twiz_layer_id").val(),
		 "twiz_start_delay": $("#twiz_start_delay").val(),
		 "twiz_duration": $("#twiz_duration").val(),
		 "twiz_move_top_pos_sign_a": $("#twiz_move_top_pos_sign_a").val(),
		 "twiz_move_top_position_a": $("#twiz_move_top_position_a").val(),
		 "twiz_move_left_pos_sign_a": $("#twiz_move_left_pos_sign_a").val(),
		 "twiz_move_left_position_a": $("#twiz_move_left_position_a").val(),
		 "twiz_move_top_pos_sign_b": $("#twiz_move_top_pos_sign_b").val(),
		 "twiz_move_top_position_b": $("#twiz_move_top_position_b").val(),
		 "twiz_move_left_pos_sign_b": $("#twiz_move_left_pos_sign_b").val(),		 
		 "twiz_move_left_position_b": $("#twiz_move_left_position_b").val(),		 
		 "twiz_options_a": $("#twiz_options_a").val(),
		 "twiz_options_b": $("#twiz_options_b").val(),
		 "twiz_extra_js_a": $("#twiz_extra_js_a").val(),
		 "twiz_extra_js_b": $("#twiz_extra_js_b").val(),		 
		 "twiz_start_top_pos_sign": $("#twiz_start_top_pos_sign").val(),
		 "twiz_start_top_position": $("#twiz_start_top_position").val(),
		 "twiz_start_left_pos_sign": $("#twiz_start_left_pos_sign").val(),		 
		 "twiz_start_left_position": $("#twiz_start_left_position").val(),		 
		 "twiz_position": $("#twiz_position").val()
		}, function(data) {
		$("#twiz_container").html(data);
		$("img[name^=twiz_status]").unbind("click");
		$("img[name^=twiz_cancel]").unbind("click");
		$("img[name^=twiz_save]").unbind("click");
		bind_Status();bind_Delete();bind_Edit();
		bind_Cancel();bind_Save();bind_Number_Restriction();
		bind_More_Options();bind_Choose_FromId();bind_Choose_Options();
		setTimeout(function(){
		$("#twiz_messagebox").hide("slow");
		}, hide_MessageDelay);	
	});
   });
  }
  var bind_Number_Restriction = function() {
	$("#twiz_start_delay").keypress(function (e){
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{return false;}}); 
	$("#twiz_duration").keypress(function (e){
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{return false;}});
	$("#twiz_start_top_position").keypress(function (e)
	{if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{return false;}});
	$("#twiz_start_left_position").keypress(function (e)
	{if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{return false;}});
	$("#twiz_end_top_position").keypress(function (e)
	{if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{return false;}});
	$("#twiz_end_left_position").keypress(function (e)
	{if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{return false;}});
  }
  var bind_More_Options = function() {
	$(".twiz-more-options").click(function(){
		$(".twiz-table-more-options").toggle();
	});
  }
  var bind_Choose_FromId = function() {
	$("#twiz_choose_fromId").click(function(){
		$("#twiz_td_full_chooseid").html(\'<img src="'.$this->plugin_url.'/images/twiz-loading.gif">\');
		$.post("'.$this->plugin_url.'/twiz-ajax.php'.'", { "action": "'.self::ACTION_ID_LIST.'"}, function(data) {
		$("#twiz_td_full_chooseid").html(data);
		bind_Select_Id();
		});
	});
  }	
  var bind_Select_Id = function() {
	$("#twiz_slc_id").change(function(){
		$("#twiz_layer_id").attr({value: $(this).val()});
	});
  }	
  var bind_Choose_Options = function() {
	$("a[name^=twiz_choose_options]").click(function(){
		var textid = $(this).attr("name");
		var charid = textid.substring(20,textid.length);
		$("#twiz_td_full_option_" + charid).html(\'<img src="'.$this->plugin_url.'/images/twiz-loading.gif">\');
		$.post("'.$this->plugin_url.'/twiz-ajax.php'.'", { "action": "'.self::ACTION_OPTIONS.'","twiz_charid": charid}, function(data) {
		$("#twiz_td_full_option_" + charid).html(data);
		bind_Select_Options(charid);
		});
	});
  }	
  var bind_Select_Options = function(charid) {
		$("#twiz_slc_options_" + charid).change(function(){
			var curval = $("#twiz_options_" + charid).val();
			if($(this).val()!=""){
				if(curval!=""){ var curval = curval + "\n";}
				var optionstring =  $(this).val() + ":\"\"";
				$("#twiz_options_" + charid).attr({value: curval + optionstring}) 
			}
		});	
  }	
  $("#twiz_container").show("slow");
  $("#twiz_background").animate({opacity:0.08}, 500);
  $("#twiz_background").animate({opacity:0.15}, 500);
  bind_Status();bind_Delete();bind_New();bind_Edit();
  bind_Cancel();bind_Save();bind_Number_Restriction();
  bind_More_Options();bind_Choose_FromId();bind_Choose_Options();
 });
 //]]>
</script>';
	   return $header;
	}
	 
	private function createHtmlList($listarray){ 
	
		$csscript = '<style type="text/css">
.twiz-table-list{
	width:350px;
}	
.twiz-table-list-tr-h{
	background:-moz-linear-gradient(center bottom , #ebebeb, #E4E4E4) repeat scroll 0 0 transparent;
	background: -webkit-gradient(linear, left top, center bottom, from(#ebebeb), to(#E4E4E4));
    background: -khtml-gradient(linear, left top, center bottom, from(#ebebeb), to(#E4E4E4));
}	
.twiz-table-list tr:hover{
	background:-moz-linear-gradient(center bottom , #ebebeb, #E4E4E4) repeat scroll 0 0 transparent;
	background: -webkit-gradient(linear, left top, center bottom, from(#ebebeb), to(#E4E4E4));
    background: -khtml-gradient(linear, left top, center bottom, from(#ebebeb), to(#E4E4E4));
}	
.twiz-table-list img:hover{
	cursor:pointer;
}
.twiz-table-list-td-h{
	font-size:11px;
	padding:4px;
}
.twiz-table-list-td{
	padding:2px;
	font-size:12px;
}
.twiz-td-center{
	text-align:center;
}
.twiz-td-left{
	text-align:left;
}
.twiz-td-right{
	text-align:right;
}
.twiz-row-color-1{
	background-color:#F3F3F3;
}
.twiz-row-color-2{
	background-color:#F7F7F7;
}
.twiz-td-action{
	min-width:50px;
}
.twiz-td-action img{
	margin:2px;
}
.twiz-td-duration{
	min-width:50px;
}
.twiz-xx{
	font-size:10px;
	color:green;
}
</style>';
		
		/* ajax container */ 
		if(!in_array($_POST['action'], $this->actiontypes)){
			$opendiv = '<div id="twiz_container">';
			$closediv = '</div>';
		}
		
		$htmllist = $csscript.$opendiv.'<table class="twiz-table-list" cellspacing="0">';
		
		$htmllist.= '<tr class="twiz-table-list-tr-h twiz-td-center"><td class="twiz-table-list-td-h">'.__('Status', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-left" nowrap>'.__('Element ID', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-right" nowrap><b>&#8681;</b> '.__('Delay', 'the-welcomizer').'</td><td class="twiz-table-list-td-h twiz-td-right twiz-td-duration" nowrap>'.__('Duration', 'the-welcomizer').'</td><td class="twiz-table-list-td-h  twiz-td-action" nowrap>'.__('Action', 'the-welcomizer').'</td></tr>';
		
		 foreach($listarray as $key=>$value){
			
			$rowcolor= ($rowcolor=='twiz-row-color-1')?'twiz-row-color-2':'twiz-row-color-1';
			
			$statushtmlimg = ($value['status']=='1')? $this->getHtmlImgStatus($value['id'], 'active'):$this->getHtmlImgStatus($value['id'], 'inactive');
			
			/* add a '2x' to the duration if necessary */
			$duration = (($value['move_top_pos_b'] !='' ) or( $value['move_left_pos_b'] !='' ) or( $value['options_b'] !='' ) or( $value['extra_js_b'] !='' ))?$value['duration'].'<b class="twiz-xx"> x2</b>':$value['duration'];

			/* the table row */
			$htmllist.= '<tr class="'.$rowcolor.'" name="twiz_list_tr_'.$value['id'].'" id="twiz_list_tr_'.$value['id'].'" ><td class="twiz-td-center" id="twiz_td_status_'.$value['id'].'">'.$statushtmlimg.'</td><td class="twiz-td-left">'.$value['layer_id'].'</td><td class="twiz-td-right">'.$value['start_delay'].'</td><td class="twiz-td-right" nowrap>'.$duration.'</td><td class="twiz-td-right" nowrap><img  src="'.$this->plugin_url.'/images/twiz-save.gif" id="twiz_img_edit_'.$value['id'].'" name="twiz_img_edit_'.$value['id'].'" class="twiz-loading-gif"><img id="twiz_edit_'.$value['id'].'" name="twiz_edit_'.$value['id'].'" alt="'.__('Edit', 'the-welcomizer').'" title="'.__('Edit', 'the-welcomizer').'" src="'.$this->plugin_url.'/images/twiz-edit.gif" height="25"/> <img height="25" src="'.$this->plugin_url.'/images/twiz-delete.gif" id="twiz_delete_'.$value['id'].'" name="twiz_delete_'.$value['id'].'" alt="'.__('Delete', 'the-welcomizer').'" title="'.__('Delete', 'the-welcomizer').'"/><img class="twiz-loading-gif" src="'.$this->plugin_url.'/images/twiz-save.gif" id="twiz_img_delete_'.$value['id'].'" name="twiz_img_delete_'.$value['id'].'"></td></tr>';
		 
		 }
		 
		 $htmllist.= '</table>'.$closediv;
		 
		 return $htmllist;
	}
	
	
	function delete($id){
	
		global $wpdb;
		
		if($id!=""){ 
		 
			$sql = "DELETE from ".$this->table." where id='".$id."';";

			$code = $wpdb->query($sql);
	
			return $code;

		}else{return false;}
	}		
	
	function install(){ 
	
		global $wpdb;

		if ( $wpdb->get_var( "show tables like '".$this->table."'" ) != $this->table ) {
	  
			$sql = "CREATE TABLE ".$this->table." (".
				"id int NOT NULL AUTO_INCREMENT, ".
				"status tinyint(3) NOT NULL default 0, ".
				"layer_id varchar(50) NOT NULL default '', ".
				"start_delay int(5) NOT NULL default 0, ".
				"duration int(5) NOT NULL default 0, ".
				"start_top_pos_sign varchar(1) NOT NULL default '', ".
				"start_top_pos int(5) default NULL, ".
				"start_left_pos_sign varchar(1) NOT NULL default '', ".
				"start_left_pos int(5) default NULL, ".
				"position varchar(8) NOT NULL default '', ".			 
				"move_top_pos_sign_a varchar(1) NOT NULL default '', ".
				"move_top_pos_a int(5) default NULL, ".
				"move_left_pos_sign_a varchar(1) NOT NULL default '', ".
				"move_left_pos_a int(5) default NULL, ".		
				"move_top_pos_sign_b varchar(1) NOT NULL default '', ".
				"move_top_pos_b int(5) default NULL, ".
				"move_left_pos_sign_b varchar(1) NOT NULL default '', ".
				"move_left_pos_b int(5) default NULL, ".	
				"options_a text NOT NULL default '', ".	
				"options_b text NOT NULL default '', ".	
				"extra_js_a text NOT NULL default '', ".	
				"extra_js_b text NOT NULL default '', ".				 
				"PRIMARY KEY (id) ".
				");";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
			dbDelta($sql);
		
			update_option('twiz_db_version', $this->dbversion);
			update_option('twiz_global_status', '1');
			
		}else{}
	}
	  
	function getFrontEnd(){
	
		wp_reset_query(); // fix the corrupted is_home() due to a custom query.
		
		if((is_home())
		and(get_option('twiz_global_status')=='1')){
		
			// get the active data list array
			$listarray = $this->getListArray(' where status=1 '); 

			/* script header */
			$generatedscript.="<!-- ".$this->plugin_name." ".$this->version." -->\n";
			$generatedscript.= '<script type="text/javascript">
jQuery(document).ready(function($) {';
			 
			 /* generates the code */
			foreach($listarray as $key=>$value){
			
				/* start delay */ 
				$generatedscript .= '
setTimeout(function(){'; 
			
				/* css position */ 
				$generatedscript .= ($value['position']!='')?'
$("#'.$value['layer_id'].'").css("position", "'.$value['position'].'");':''; 
				
				/* starting positions */ 
				$generatedscript .=($value['start_left_pos']!='')? '$("#'.$value['layer_id'].'").css("left", "'.$value['start_left_pos_sign'].$value['start_left_pos'].'px");':'';
				$generatedscript .=($value['start_top_pos']!='')? '$("#'.$value['layer_id'].'").css("top", "'.$value['start_top_pos_sign'].$value['start_top_pos'].'px");':'';
				
				$value['options_a'] = ($value['options_a']!='')? ','.$value['options_a']:'';
				$value['options_a'] = str_replace("\n", "," , $value['options_a']);
			
				/* replace numeric entities */	
				$value['options_a'] = $this->replaceNumericEntities($value['options_a']);

				/* animate jquery a */ 
				$generatedscript .='
$("#'.$value['layer_id'].'").animate({left: "'.$value['move_left_pos_sign_a'].'='.$value['move_left_pos_a'].'", top:"'.$value['move_top_pos_sign_a'].'='.$value['move_top_pos_a'].'" '.$value['options_a'].'}, '.$value['duration'].', function() {';
		
				/* replace numeric entities */
				$value['extra_js_a'] = $this->replaceNumericEntities($value['extra_js_a']);
	
				/* extra js a */	
				$generatedscript .= ($value['extra_js_a']!='')? $value['extra_js_a']:'';
				
				/* ************************* */
				
				/* add a coma between each options */ 
				$value['options_b'] = ($value['options_b']!='')? ','.$value['options_b']:'';
				$value['options_b'] = str_replace("\n", "," , $value['options_b']);
				
				/* replace numeric entities */				
				$value['options_b'] = $this->replaceNumericEntities($value['options_b']);
				
				/* animate jquery b */ 
				$generatedscript .= '
$("#'.$value['layer_id'].'").animate({left:"'.$value['move_left_pos_sign_b'].'='.$value['move_left_pos_b'].'", top:"'.$value['move_top_pos_sign_b'].'='.$value['move_top_pos_b'].'" '.$value['options_b'].'}, '.$value['duration'].', function() {';
					
				/* replace numeric entities */
				$value['extra_js_b'] = $this->replaceNumericEntities($value['extra_js_b']);
	
				/* extra js b */	
				$generatedscript .= ($value['extra_js_b']!='')? $value['extra_js_b']:'';
				
				/* closing functions */
				$generatedscript .= '});';
				$generatedscript .= '});';
				$generatedscript .= '},'.$value['start_delay'].');';
			}
			
			/* script footer */
			$generatedscript.= '});';
			$generatedscript.= '
</script>';
		}
		return $generatedscript;
	}

	function getHtmlForm($id){ 
		
		if($id!=''){
			if(!$data = $this->getRow($id)){return false;}
		}
		
		/* Toggle More Options */
		$jsscript_moreoptions = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
  $(".twiz-table-more-options").toggle();
  });
 //]]>
</script>';

		/* Text Area auto expand */
		$jsscript_autoexpand = '<script language="JavaScript" type="text/javascript">
 //<![CDATA[
  jQuery(document).ready(function($) {
	textarea = new Object();
	textarea.expand = function(input){
		input.style.height = "50px";
		input.style.height = (input.scrollHeight + 10) + "px";
	} 
  });
 //]]>
</script>';
		/* inline css style */
		$csscript = '<style type="text/css">
.twiz-table-form{
	margin-bottom:15px;
	margin-top:10px;
}
.twiz-table-form hr{
	margin:15px 0 15px 0;
	background-color:#ebebeb;
	border:0px;
	height:5px;
}
.twiz-caption{
	height:30px;
	text-align:left;
	font-size:12px;
}
.twiz-td-left{
	width:190px;
	padding:2px;
	font-size:12px;
}
.twiz-td-right{
	width:160px;
	padding:2px;
	font-size:12px;
	text-align:left;
}
.twiz-td-picklist{
	text-align:right;
	font-size:12px;
	padding:2px 0 2px 0;
	height:30px;
}
.twiz-td-picklist a:hover{
	cursor:pointer;
}
.twiz-td-small-left{
	width:40px;
	font-size:12px;
}								
.twiz-td-save{
	text-align:right;
	font-size:12px;
	height:30px;
}
.twiz-td-save a:hover{
	cursor:pointer;
}
.twiz-input{
	width:158px;
}
.twiz-input[type=text]:focus{
	background-color: lightyellow;
	border:#E6DB55 1px solid;
}
.twiz-input-small{
	width:50px;
	text-align:right;
}
.twiz-input-large{
	width:150px;
	text-align:left;
}
.twiz-slc-options{
	width:100%;
}
.twiz-table-more-options{
	display:none;
}
a.twiz-more-options:hover{
	cursor:pointer;
}
.twiz-more-options{
	font-size:12px;
}
.twiz-td-e-g{
	line-height:15px;
	font-size:10px;
}
.twiz-loading-gif-save{
	height:15px;
}
</style>';

		/* ajax container */ 
		if(!in_array($_POST['action'], $this->actiontypes)){
			 $opendiv = '<div id="twiz_container">';
			 $closediv = '</div>';
		}
		
		/* toggle more options by default if we have values */
		if(($data['options_a']!='')or($data['extra_js_a']!='')
		 or($data['options_b']!='')or($data['extra_js_b']!='')){
			$toggleoptions = $jsscript_moreoptions;
		}else{
			$toggleoptions = '';
		}
	
		/* checked */
		$twiz_status = (($data['status']==1)or($id==''))? ' checked="checked"':'';
		
		/* selected */
		$twiz_position['absolute'] 	= ($data['position']=='absolute')? ' selected="selected"':'';
		$twiz_position['relative'] 	= (($data['position']=='relative')or($id==''))? ' selected="selected"':'';
		$twiz_position['static'] 	= ($data['position']=='static')? ' selected="selected"':'';

		$twiz_start_top_pos_sign['nothing'] = ($data['start_top_pos_sign']=='')? ' selected="selected"':'';
		$twiz_start_top_pos_sign['-'] 		= ($data['start_top_pos_sign']=='-')? ' selected="selected"':'';
		$twiz_start_left_pos_sign['nothing']= ($data['start_left_pos_sign']=='')? ' selected="selected"':'';
		$twiz_start_left_pos_sign['-']		= ($data['start_left_pos_sign']=='-')? ' selected="selected"':'';
		
		$twiz_move_top_pos_sign_a['+'] 	= ($data['move_top_pos_sign_a']=='+')? ' selected="selected"':'';
		$twiz_move_top_pos_sign_a['-'] 	= ($data['move_top_pos_sign_a']=='-')? ' selected="selected"':'';
		$twiz_move_left_pos_sign_a['+'] = ($data['move_left_pos_sign_a']=='+')? ' selected="selected"':'';
		$twiz_move_left_pos_sign_a['-'] = ($data['move_left_pos_sign_a']=='-')? ' selected="selected"':'';

		$twiz_move_top_pos_sign_b['+'] 	= ($data['move_top_pos_sign_b']=='+')? ' selected="selected"':'';
		$twiz_move_top_pos_sign_b['-'] 	= ($data['move_top_pos_sign_b']=='-')? ' selected="selected"':'';
		$twiz_move_left_pos_sign_b['+'] = ($data['move_left_pos_sign_b']=='+')? ' selected="selected"':'';
		$twiz_move_left_pos_sign_b['-'] = ($data['move_left_pos_sign_b']=='-')? ' selected="selected"':'';


		/* creates the form */
		$htmlform = $csscript.$opendiv.'<table class="twiz-table-form" cellspacing="0" cellpadding="0">
<tr><td class="twiz-td-left">'.__('Status', 'the-welcomizer').':</td>
<td class="twiz-td-right"><input type="checkbox" id="twiz_status" name="twiz_status" '.$twiz_status.'></td></tr>
<tr><td class="twiz-td-left">'.__('Element ID', 'the-welcomizer').':</td><td class="twiz-td-right"><input class="twiz-input" id="twiz_layer_id" name="twiz_layer_id" type="text" value="'.$data['layer_id'].'" maxlength="50"></td></tr>
<tr><td colspan="2" id="twiz_td_full_chooseid" class="twiz-td-picklist"><a id="twiz_choose_fromId" name="twiz_choose_fromId">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>
<tr><td class="twiz-td-left">'.__('Start delay', 'the-welcomizer').':</td><td class="twiz-td-right"><input class="twiz-input twiz-input-small" id="twiz_start_delay" name="twiz_start_delay" type="text" value="'.$data['start_delay'].'" maxlength="5"> <small>1000 = 1 '.__('sec', 'the-welcomizer').'</small></td></tr>
<tr><td class="twiz-td-left">'.__('Duration', 'the-welcomizer').':</td><td class="twiz-td-right"><input class="twiz-input twiz-input-small" id="twiz_duration" name="twiz_duration" type="text" value="'.$data['duration'].'" maxlength="5"> <small>1000 = 1 '.__('sec', 'the-welcomizer').'</small></td></tr>
<tr><td colspan="2"><hr></td></tr>
 <tr><td colspan="2" class="twiz-caption">'.__('Starting position', 'the-welcomizer').' <b>'.__('(optional)', 'the-welcomizer').'</b></tr>
<tr>
	<td>
		<table>
			<tr><td class="twiz-td-small-left" nowrap>'.__('Top', 'the-welcomizer').':</td><td>
			<select name="twiz_start_top_pos_sign" id="twiz_start_top_pos_sign">
				<option value="" '.$twiz_start_top_pos_sign['nothing'].'>+</option>
				<option value="-" '.$twiz_start_top_pos_sign['-'].'>-</option>
				</select><input class="twiz-input twiz-input-small" id="twiz_start_top_position" name="twiz_start_top_position" type="text" value="'.$data['start_top_pos'].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr>
			<tr><td class="twiz-td-small-left" nowrap>'.__('Left', 'the-welcomizer').':</td><td>
			<select name="twiz_start_left_pos_sign" id="twiz_start_left_pos_sign">
				<option value="" '.$twiz_start_left_pos_sign['nothing'].'>+</option>
				<option value="-" '.$twiz_start_left_pos_sign['-'].'>-</option>
				</select><input class="twiz-input twiz-input-small" id="twiz_start_left_position" name="twiz_start_left_position" type="text" value="'.$data['start_left_pos'].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr>
		</table>
	</td>
	<td>
		<table>
			<tr><td rowspan="2">'.__('Position', 'the-welcomizer').':</td><td>
			<select name="twiz_position" id="twiz_position"><option value="" > </option>
			<option value="absolute" '.$twiz_position['absolute'].'>'.__('absolute', 'the-welcomizer').'</option>
			<option value="relative" '.$twiz_position['relative'].'>'.__('relative', 'the-welcomizer').'</option>
			</select>
			</td></tr>
		</table>
	</td>
</tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td valign="top">
		<table>
			<tr><td class="twiz-caption" colspan="2"><b>'.__('First Move', 'the-welcomizer').'</b></td></tr>
			<tr><td class="twiz-td-small-left" nowrap>'.__('Top', 'the-welcomizer').':</td><td nowrap>
			<select name="" id="twiz_move_top_pos_sign_a">
			<option value="+" '.$twiz_move_top_pos_sign_a['+'].'>+</option>
			<option value="-" '.$twiz_move_top_pos_sign_a['-'].'>-</option>
			</select><input class="twiz-input twiz-input-small" id="twiz_move_top_position_a" name="twiz_move_top_position_a" type="text" value="'.$data['move_top_pos_a'].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr>
			<tr><td class="twiz-td-small-left" nowrap>'.__('Left', 'the-welcomizer').':</td><td nowrap>
			<select name="twiz_move_left_pos_sign_a" id="twiz_move_left_pos_sign_a">
			<option value="+" '.$twiz_move_left_pos_sign_a['+'].'>+</option>
			<option value="-" '.$twiz_move_left_pos_sign_a['-'].'>-</option>
			</select><input class="twiz-input twiz-input-small" id="twiz_move_left_position_a" name="twiz_move_left_position_a" type="text" value="'.$data['move_left_pos_a'].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr><tr><td></td><td><a name="twiz_more_options_a" id="twiz_more_options_a"  class="twiz-more-options">'.__('More Options', 'the-welcomizer').' &#187;</a></td></tr></table>
			<table class="twiz-table-more-options">
				<tr><td colspan="2"><hr></td></tr>
				<tr><td colspan="2" class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td colspan="2"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_options_a" name="twiz_options_a" type="text" >'.$data['options_a'].'</textarea></td></tr>
				<tr><td colspan="2" id="twiz_td_full_option_a" class="twiz-td-picklist"><a id="twiz_choose_options_a" name="twiz_choose_options_a">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>
				<tr><td colspan="2" class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').' <br>
				opacity:0.5<br>
				width:"200px"<br>
				<a href="http://api.jquery.com/animate/" alt="'.__('Learn more about jQuery .animate() properties', 'the-welcomizer').'" title="'.__('Learn more about jQuery .animate() properties', 'the-welcomizer').'" target="_blank">jQuery .animate()</a>
				</td></tr>		
				<tr><td colspan="2"><hr></td></tr>		
				<tr><td colspan="2" class="twiz-caption">'.__('Extra JavaScript', 'the-welcomizer').'</td></tr><tr><td colspan="2"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_extra_js_a" name="twiz_extra_js_a" type="text">'.$data['extra_js_a'].'</textarea></td></tr><tr><td colspan="2" class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').'<br>alert("'.__('Welcome!', 'the-welcomizer').'");<br><br></td></tr>
		</table>
</td>
<td valign="top">	
	<table>
		<tr><td class="twiz-caption" colspan="2"><b>'.__('Second Move', 'the-welcomizer').'</b></td></tr>
		<tr><td class="twiz-td-small-left" nowrap>'.__('Top', 'the-welcomizer').':</td><td nowrap>
		<select name="twiz_move_top_pos_sign_b" id="twiz_move_top_pos_sign_b">
		<option value="-" '.$twiz_move_top_pos_sign_b['-'].'>-</option>
		<option value="+" '.$twiz_move_top_pos_sign_b['+'].'>+</option>
		</select><input class="twiz-input twiz-input-small" id="twiz_move_top_position_b" name="twiz_move_top_position_b" type="text" value="'.$data['move_top_pos_b'].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr>
		<tr><td class="twiz-td-small-left" nowrap>'.__('Left', 'the-welcomizer').':</td><td nowrap>
		<select name="twiz_move_left_pos_sign_b" id="twiz_move_left_pos_sign_b">
		<option value="-" '.$twiz_move_left_pos_sign_b['-'].'>-</option>
		<option value="+" '.$twiz_move_left_pos_sign_b['+'].'>+</option>
		</select><input class="twiz-input twiz-input-small" id="twiz_move_left_position_b" name="twiz_move_left_position_b" type="text" value="'.$data['move_left_pos_b'].'" maxlength="5"> '.__('px', 'the-welcomizer').'</td></tr><tr><td></td><td><a name="twiz_more_options_b" id="twiz_more_options_b" class="twiz-more-options">'.__('More Options', 'the-welcomizer').' &#187;</a></td></tr>
		</table>
		<table class="twiz-table-more-options">
			<tr><td colspan="2"><hr></td></tr>
			<tr><td colspan="2" class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td colspan="2"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_options_b" name="twiz_options_b" type="text">'.$data['options_b'].'</textarea></td></tr>
			<tr><td colspan="2" id="twiz_td_full_option_b" class="twiz-td-picklist"><a id="twiz_choose_options_b" name="twiz_choose_options_b">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>
			<tr><td colspan="2" class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').' <br> 
				opacity:1<br>
				width:"100px"<br><a href="http://api.jquery.com/animate/" alt="'.__('Learn more about jQuery .animate() properties', 'the-welcomizer').'" title="'.__('Learn more about jQuery .animate() properties', 'the-welcomizer').'" target="_blank">jQuery .animate()</a>
				</td></tr>		
			<tr><td colspan="2"><hr></td></tr>
			<tr><td colspan="2" class="twiz-caption">'.__('Extra JavaScript', 'the-welcomizer').'</td></tr><tr><td colspan="2"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_extra_js_b" name="twiz_extra_js_b" type="text" value="">'.$data['extra_js_b'].'</textarea></td></tr><tr><td colspan="2" class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').'<br>$(this).css({<br>position:"static"});</td></tr>
		</table>
</td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td class="twiz-td-save" colspan="2"><img src="'.$this->plugin_url.'/images/twiz-save.gif" id="twiz_save_img" name="twiz_save_img" class="twiz-loading-gif twiz-loading-gif-save"><a name="twiz_cancel" id="twiz_cancel">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save" id="twiz_save" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /><input type="hidden" name="twiz_id" id="twiz_id" value="'.$id.'"></td></tr>
</table>'.$closediv.$toggleoptions.$jsscript_autoexpand;
	
		return $htmlform;
	}

	private function getListArray($where){ 
	
		global $wpdb;

		$sql = "SELECT * from ".$this->table.$where." order by start_delay";
		$rows = $wpdb->get_results($sql, ARRAY_A);
		
		return $rows;
	}

	function getHtmlList(){ 
		
		$listarray = $this->getListArray(); // get all the data
		
		if(count($listarray)==0){ // if, display the default new form
			
			return $this->getHtmlForm(); 
			
		}else{ // else display the list
		
			return $this->createHtmlList($listarray); // private
		}
		
	}
	
	private function getHtmlImgStatus($id, $status){
	
		return '<img src="'.$this->plugin_url.'/images/twiz-'.$status.'.png" id="twiz_status_'.$id.'" name="twiz_status_'.$id.'"><img src="'.$this->plugin_url.'/images/twiz-save.gif" id="twiz_img_status_'.$id.'" name="twiz_img_status_'.$id.'" class="twiz-loading-gif">';

	}
	
    function getHtmlIdList(){
	
		$html = file_get_html(get_option('siteurl'));
		
		$select = '<select name="twiz_slc_id" id="twiz_slc_id">';
			
		$select .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';
			
		foreach ($html->find('[id]') as $element){
					
			$select .= '<option value="'.$element->id.'">'.$element->id.'</option>';
		}
			
		$select .= '</select>';
			
		return $select;
	}	
	
	function getHtmlOptionList($id){
		
		$select = '<select class="twiz-slc-options" name="twiz_slc_options_'.$id.'" id="twiz_slc_options_'.$id.'">';
			
		$select .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';
			
		foreach ($this->jQueryoptions as $key => $value){
					
			$select .= '<option value="'.$value.'">'.$value.'</option>';
		}
			
		$select .= '</select>';
			
		return $select;
	}	
	
	function getHtmlSuccess($id, $message){
		
		$csscript = '<style type="text/css">
#twiz_messagebox{
	background:url('.$this->plugin_url.'/images/twiz-success.gif) no-repeat 10px 50% #F0FFF0;
	border:1px solid #00FF00;
	height:25px;
	padding-left:35px;
	padding-top:7px;
	font-size:12px;
	font-weight:bold;
	color:#098809;
	width:313px;
}
</style>';
	
		$htmlmessage = $csscript.'<p id="twiz_messagebox">'.$message.'</p>';
		
		return $htmlmessage;
	}
		
	private function getRow($id){ 
	
		global $wpdb;
		
		if($id==''){return false;}
	
		$sql = "SELECT * from ".$this->table." where id='".$id."'";
		$row = $wpdb->get_row($sql, ARRAY_A);
		
		return $row;
	}

	private function replaceNumericEntities($value){
			
		/* entities array */
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
			
		/* replace numeric entities */
		$value = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $value);
		$value = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $value);
		$newvalue = strtr($value, $trans_tbl);
		
		return $newvalue;
				
	}
	
	function save($id){
	
		global $wpdb;

		$twiz_status = attribute_escape(trim($_POST['twiz_status']));
		$twiz_status = ($twiz_status=='true')?1:0;
	
		$twiz_layer_id = attribute_escape(trim($_POST['twiz_layer_id']));
		$twiz_layer_id = ($twiz_layer_id=='')? '*'.__('Delay', 'the-welcomizer'):$twiz_layer_id;
		
		$twiz_move_top_position_a 	= attribute_escape(trim($_POST['twiz_move_top_position_a']));
		$twiz_move_left_position_a 	= attribute_escape(trim($_POST['twiz_move_left_position_a']));
		$twiz_move_top_position_b 	= attribute_escape(trim($_POST['twiz_move_top_position_b']));
		$twiz_move_left_position_b 	= attribute_escape(trim($_POST['twiz_move_left_position_b']));
		$twiz_start_top_position 	= attribute_escape(trim($_POST['twiz_start_top_position']));
		$twiz_start_left_position 	= attribute_escape(trim($_POST['twiz_start_left_position']));
		
		$twiz_move_top_position_a 	= ($twiz_move_top_position_a=='')?'NULL':$twiz_move_top_position_a;
		$twiz_move_left_position_a 	= ($twiz_move_left_position_a=='')?'NULL':$twiz_move_left_position_a;
		$twiz_move_top_position_b 	= ($twiz_move_top_position_b=='')?'NULL':$twiz_move_top_position_b;
		$twiz_move_left_position_b 	= ($twiz_move_left_position_b=='')?'NULL':$twiz_move_left_position_b;
		$twiz_start_top_position 	= ($twiz_start_top_position=='')?'NULL':$twiz_start_top_position;
		$twiz_start_left_position 	= ($twiz_start_left_position=='')?'NULL':$twiz_start_left_position;
		
		/* user syntax auto correction */ 
		$twiz_options_a = str_replace("'", "\"" , $_POST['twiz_options_a']);	
		$twiz_options_b = str_replace("'", "\"" , $_POST['twiz_options_b']);
		$twiz_options_a = attribute_escape(trim($twiz_options_a));
		$twiz_options_b = attribute_escape(trim($twiz_options_b));
		$twiz_options_a = str_replace("=", ":" , $twiz_options_a );
		$twiz_options_b = str_replace("=", ":" , $twiz_options_b );

		$twiz_extra_js_a = str_replace("'", "\"" , $_POST['twiz_extra_js_a']);	
		$twiz_extra_js_b = str_replace("'", "\"" , $_POST['twiz_extra_js_b']);
		$twiz_extra_js_a = attribute_escape(trim($twiz_extra_js_a));	
		$twiz_extra_js_b = attribute_escape(trim($twiz_extra_js_b));
		
		if($id==""){ // add new

			$sql = "INSERT INTO ".$this->table." 
				 (status
				 ,layer_id
				 ,start_delay
				 ,duration
				 ,start_top_pos_sign
				 ,start_top_pos
				 ,start_left_pos_sign
				 ,start_left_pos	
				 ,position	
				 ,move_top_pos_sign_a
				 ,move_top_pos_a
				 ,move_left_pos_sign_a
				 ,move_left_pos_a
				 ,move_top_pos_sign_b
				 ,move_top_pos_b
				 ,move_left_pos_sign_b
				 ,move_left_pos_b
				 ,options_a
				 ,options_b
				 ,extra_js_a
				 ,extra_js_b		 
				 )values('".$twiz_status."'
				 ,'".$twiz_layer_id."'
				 ,'0".attribute_escape(trim($_POST['twiz_start_delay']))."'
				 ,'0".attribute_escape(trim($_POST['twiz_duration']))."'
				 ,'".attribute_escape(trim($_POST['twiz_start_top_pos_sign']))."'	
				 ,".$twiz_start_top_position."
				 ,'".attribute_escape(trim($_POST['twiz_start_left_pos_sign']))."'	
				 ,".$twiz_start_left_position."
				 ,'".attribute_escape(trim($_POST['twiz_position']))."'					 
				 ,'".attribute_escape(trim($_POST['twiz_move_top_pos_sign_a']))."'	
				 ,".$twiz_move_top_position_a."
				 ,'".attribute_escape(trim($_POST['twiz_move_left_pos_sign_a']))."'	
				 ,".$twiz_move_left_position_a."
				 ,'".attribute_escape(trim($_POST['twiz_move_top_pos_sign_b']))."'					 
				 ,".$twiz_move_top_position_b."
				 ,'".attribute_escape(trim($_POST['twiz_move_left_pos_sign_b']))."'	
				 ,".$twiz_move_left_position_b."
				 ,'".$twiz_options_a."'							 
				 ,'".$twiz_options_b."'
				 ,'".$twiz_extra_js_a."'							 
				 ,'".$twiz_extra_js_b."'				 
				 );";
			
			$code = $wpdb->query($sql);
	
			return $code;

		}else{ // update

			$sql = "UPDATE ".$this->table." 
				  SET status = '".$twiz_status."'
				 ,layer_id = '".$twiz_layer_id."'
				 ,start_delay = '0".attribute_escape(trim($_POST['twiz_start_delay']))."'
				 ,duration = '0".attribute_escape(trim($_POST['twiz_duration']))."'
				 ,start_top_pos_sign = '".attribute_escape(trim($_POST['twiz_start_top_pos_sign']))."'
				 ,start_top_pos = ".$twiz_start_top_position."
				 ,start_left_pos_sign = '".attribute_escape(trim($_POST['twiz_start_left_pos_sign']))."'
				 ,start_left_pos = ".$twiz_start_left_position."
				 ,position = '".attribute_escape(trim($_POST['twiz_position']))."'				 
				 ,move_top_pos_sign_a = '".attribute_escape(trim($_POST['twiz_move_top_pos_sign_a']))."'
				 ,move_top_pos_a = ".$twiz_move_top_position_a."
				 ,move_left_pos_sign_a = '".attribute_escape(trim($_POST['twiz_move_left_pos_sign_a']))."'
				 ,move_left_pos_a = ".$twiz_move_left_position_a."
				 ,move_top_pos_sign_b = '".attribute_escape(trim($_POST['twiz_move_top_pos_sign_b']))."'
				 ,move_top_pos_b = ".$twiz_move_top_position_b."
				 ,move_left_pos_sign_b = '".attribute_escape(trim($_POST['twiz_move_left_pos_sign_b']))."'
				 ,move_left_pos_b = ".$twiz_move_left_position_b."
				 ,options_a = '".$twiz_options_a."'
				 ,options_b = '".$twiz_options_b."'
				 ,extra_js_a = '".$twiz_extra_js_a."'
				 ,extra_js_b = '".$twiz_extra_js_b."'				 
				  WHERE id='".$id."';";
					
			$code = $wpdb->query($sql);
					
			return $code;
		}
	}
	
	function switchGlobalStatus(){ 

		$newglobalstatus = (get_option('twiz_global_status')=='0')? '1' : '0'; // swicth the status value
				
		update_option('twiz_global_status', $newglobalstatus);
	
		$htmlstatus = ($newglobalstatus=='1')? $this->getHtmlImgStatus('global','active'):$this->getHtmlImgStatus('global','inactive');

		return $htmlstatus;
	}
	
	private function getImgGlobalStatus(){ 

		$htmlstatus = (get_option('twiz_global_status')=='1')? $this->getHtmlImgStatus('global','active'):$this->getHtmlImgStatus('global','inactive');

		return $htmlstatus;
	}
	
	function switchStatus($id){ 
	
		global $wpdb;
		
		if($id==''){return false;}
	
		$sql = "SELECT status from ".$this->table." where id='".$id."'";
		$row = $wpdb->get_row($sql, ARRAY_A);
		
		$newstatus = ($row['status']=='1')? '0' : '1'; // swicth the status value
		
		$sql = "UPDATE ".$this->table." 
				SET status = '".$newstatus."'
				WHERE id = '".$id."'";
		$code = $wpdb->query($sql);
		
		if($code){
			$htmlstatus = ($newstatus=='1')? $this->getHtmlImgStatus($id,'active'):$this->getHtmlImgStatus($id,'inactive');
		}else{ 
			$htmlstatus = ($row['status']=='1')? $this->getHtmlImgStatus($id,'active'):$this->getHtmlImgStatus($id,'inactive');
		}
		
		return $htmlstatus;
	}
	
	function uninstall(){
	
		global $wpdb;

		if ($wpdb->get_var( "show tables like '".$this->table."'" ) == $this->table) {
			$sql = "DROP TABLE ". $this->table;
			$wpdb->query($sql);
		}
		
		delete_option('twiz_db_version');
		delete_option('twiz_global_status');
		
		return true;
	}	
}
?>