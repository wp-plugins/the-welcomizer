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

require_once('../../../../../../wp-load.php');

class TwizTinymceShortcode {

    function __construct(){}

    function getjQuery(){

        $upload_dir = twizReplaceShortCodeDir();
     
        $jQuery = '
    if(tinyMCE.majorVersion>="4"){
        (function(){ tinymce.create("tinymce.plugins.thewelcomizer", {
                init : function(editor) {
                    var t = this;
                    editor.on("BeforeSetContent", function(e) { e.content = t._do_shortcode(e.content);});
                    editor.on("ExecCommand", function(e) {
                        if(e.action === "mceInsertContent"){ 
                            tinyMCE.activeEditor.setContent(t._do_shortcode(tinyMCE.activeEditor.getContent()));
                        }
                    });               
                }
                ,_do_shortcode : function(content){ return content.replace(/\[twiz_wp_upload_dir\]/g, function(){ return "'.$upload_dir.'";});}
            });   
        tinymce.PluginManager.add("thewelcomizer", tinymce.plugins.thewelcomizer);})();
    }else{   
        (function(){ tinymce.create("tinymce.plugins.thewelcomizer", {
                init : function(editor){ 
                    var t = this;
                    editor.onBeforeSetContent.add(function(editor, o){ o.content = t._do_shortcode(o.content);});
                    editor.onExecCommand.add(function(editor, action){ 
                        if(action === "mceInsertContent"){ 
                            tinyMCE.activeEditor.setContent(t._do_shortcode(tinyMCE.activeEditor.getContent()));
                        }
                    });
                }
                ,_do_shortcode : function(content){ return content.replace(/\[twiz_wp_upload_dir\]/g, function(){ return "'.$upload_dir.'";});}
        }); 
        tinymce.PluginManager.add("thewelcomizer", tinymce.plugins.thewelcomizer);})();
    }';

        return $jQuery;
    }
}

$mytinymceshortcode = new TwizTinymceShortcode();

echo( $mytinymceshortcode->getjQuery() );

?>