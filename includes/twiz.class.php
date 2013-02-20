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
require_once(dirname(__FILE__).'/twiz.admin.class.php'); 
require_once(dirname(__FILE__).'/twiz.menu.class.php'); 
  
class Twiz{
    
    /* variable declaration */
    public $skin;
    public $userid;
    public $dbVersion;
    public $cssVersion;
    public $pluginUrl;
    public $pluginDir;
    public $toggle_option;
    public $admin_option;
    protected $table;
    protected $nonce;    
    protected $version;  
    protected $pluginName;
    protected $DEFAULT_SECTION;
    protected $import_path_message;
    protected $export_path_message;
       
    /* section constants */ 
    const DEFAULT_SECTION_HOME           = 'home';
    const DEFAULT_SECTION_EVERYWHERE     = 'everywhere';
    const DEFAULT_SECTION_ALL_CATEGORIES = 'allcategories';
    const DEFAULT_SECTION_ALL_PAGES      = 'allpages';
    const DEFAULT_SECTION_ALL_ARTICLES   = 'allarticles'; // allposts
    
    /* default min role level required */
    const DEFAULT_MIN_ROLE_LEVEL = 'activate_plugins'; // http://codex.wordpress.org/Roles_and_Capabilities

    /* default skin  constant */ 
    const DEFAULT_SKIN  = '_default';
    const SKIN_PATH     = '/skins/';
    
    /* default number of posts to display */
    const DEFAULT_NUMBER_POSTS = '25'; // Last 25 posts
        
    /* element type constants */ 
    const ELEMENT_TYPE_ID    = 'id';
    const ELEMENT_TYPE_CLASS = 'class';
    const ELEMENT_TYPE_NAME  = 'name';
    const ELEMENT_TYPE_TAG   = 'tag';
    const ELEMENT_TYPE_GROUP = 'group'; 
    
    /* status constants*/
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';
    
    /* library order constants */
    const LB_ORDER_UP   = 'up';
    const LB_ORDER_DOWN = 'down';
    
    /* directional image suffix constants */ 
    const DIMAGE_N  = 'n';
    const DIMAGE_NE = 'ne';
    const DIMAGE_E  = 'e';
    const DIMAGE_SE = 'se';
    const DIMAGE_S  = 's';
    const DIMAGE_SW = 'sw';
    const DIMAGE_W  = 'w';
    const DIMAGE_NW = 'nw';
    
    /* action constants */ 
    const ACTION_TOGGLE         = 'toggle';
    const ACTION_DROP_ROW       = 'droprow';
    const ACTION_BULLET_UP      = 'bulletup';
    const ACTION_BULLET_DOWN    = 'bulletdown';
    const ACTION_MENU           = 'menu';
    const ACTION_MENU_STATUS    = 'menustatus';
    const ACTION_VMENU_STATUS   = 'vmenustatus';
    const ACTION_GET_MENU       = 'getmenu';
    const ACTION_GET_VMENU      = 'getvmenu';
    const ACTION_SAVE           = 'save';
    const ACTION_CANCEL         = 'cancel';
    const ACTION_OPTIONS        = 'options';
    const ACTION_VIEW           = 'view';
    const ACTION_NEW            = 'Add New';
    const ACTION_EDIT           = 'Edit';
    const ACTION_EDIT_TD        = 'tdedit';
    const ACTION_COPY           = 'Copy';
    const ACTION_DELETE         = 'delete';
    const ACTION_STATUS         = 'status';
    const ACTION_IMPORT         = 'import';
    const ACTION_EXPORT         = 'export';
    const ACTION_LIBRARY        = 'library';
    const ACTION_LIBRARY_STATUS = 'libstatus';
    const ACTION_UPLOAD_LIBRARY = 'uploadlib';
    const ACTION_GLOBAL_STATUS  = 'gstatus';
    const ACTION_SAVE_SECTION      = 'savesection';
    const ACTION_GET_MULTI_SECTION = 'getmultisection';
    const ACTION_DELETE_SECTION = 'deletesection';
    const ACTION_GET_FINDANDREPLACE = 'getfindandreplace';
    const ACTION_FAR_REPLACE    = 'far_replace';
    const ACTION_FAR_FIND       = 'far_find';
    const ACTION_SAVE_FAR_PREF_METHOD = 'far_saveprefmethod';
    const ACTION_GET_LIBRARY_DIR = 'getlibdir';
    const ACTION_LINK_LIBRARY_DIR = 'linklibdir';
    const ACTION_UNLINK_LIBRARY_DIR = 'unlinklibdir';
    const ACTION_DELETE_LIBRARY = 'deletelib';
    const ACTION_ORDER_LIBRARY  = 'orderlib';
    const ACTION_ADMIN          = 'admin';
    const ACTION_SAVE_ADMIN     = 'adminsave';
    const ACTION_SAVE_SKIN      = 'skinsave';
    const ACTION_GET_MAIN_ADS   = 'getmainads';
    const ACTION_GET_EVENT_LIST = 'geteventlist';
    const ACTION_GET_GROUP      = 'getgroup';
    const ACTION_SAVE_GROUP     = 'savegroup';
    const ACTION_COPY_GROUP     = 'copygroup';
    const ACTION_DELETE_GROUP   = 'deletegroup';
    
    /* jquery common options constants */ 
    const JQ_TOP               = 'top: \'10px\'';
    const JQ_LEFT              = 'left: \'10px\'';
    const JQ_WITDH             = 'width: \'10px\'';
    const JQ_HEIGHT            = 'height: \'10px\'';
    const JQ_OPACITY           = 'opacity: 0.5';
    const JQ_FONTSIZE          = 'fontSize: \'10px\'';    
    const JQ_MARGINTOP         = 'marginTop: \'10px\'';
    const JQ_MARGINBOTTOM      = 'marginBottom: \'10px\'';
    const JQ_MARGINLEFT        = 'marginLeft: \'10px\'';
    const JQ_MARGINRIGHT       = 'marginRight: \'10px\'';
    const JQ_PADDINGTOP        = 'paddingTop: \'10px\'';
    const JQ_PADDINGBOTTOM     = 'paddingBottom: \'10px\'';    
    const JQ_PADDINGLEFT       = 'paddingLeft: \'10px\'';
    const JQ_PADDINGRIGHT      = 'paddingRight: \'10px\'';
    const JQ_BORDERWIDTH       = 'borderWidth: \'10px\'';
    const JQ_BORDERTOPWIDTH    = 'borderTopWidth: \'10px\'';
    const JQ_BORDERBOTTOMWIDTH = 'borderBottomWidth: \'10px\'';        
    const JQ_BORDERIGHTWIDTH   = 'borderRightWidth: \'10px\'';
    const JQ_BORDERLEFTWIDTH   = 'borderLeftWidth: \'10px\'';
    
    /* jquery jquery-animate-css-rotate-scale options constants */ 
    const JQ_ACRS_ROTATE       = 'rotate: \'+=45deg\'';
    const JQ_ACRS_SCALE        = 'scale: \'+=1.5\'';
  
    /* jquery transform options constants */ 
    const JQ_TRANSFORM_MATRIX    = 'matrix: [[1, 0, 0, 1, 0, 0]]'; 
    const JQ_TRANSFORM_REFLECT   = 'reflect: true'; 
    const JQ_TRANSFORM_REFLECTX  = 'reflectX: true';
    const JQ_TRANSFORM_REFLECTXY = 'reflectXY: true';
    const JQ_TRANSFORM_REFLECTY  = 'reflectY: true';
    const JQ_TRANSFORM_ROTATE    = 'rotate: \'45deg\'';
    const JQ_TRANSFORM_SKEW      = 'skew: [[\'10deg\', \'10deg\']]';
    const JQ_TRANSFORM_SKEWX     = 'skewX: \'10deg\'';
    const JQ_TRANSFORM_SKEWY     = 'skewY: \'10deg\'';
    const JQ_TRANSFORM_SCALE     = 'scale: [[1.5, 1.5]]';
    const JQ_TRANSFORM_SCALEX    = 'scaleX: 1.5';
    const JQ_TRANSFORM_SCALEY    = 'scaleY: 1.5';
    const JQ_TRANSFORM_TRANSLATE = 'translate: [[\'10px\', \'10px\']]';
    const JQ_TRANSFORM_TRANSLATEX = 'translateX: \'10px\'';
    const JQ_TRANSFORM_TRANSLATEY = 'translateY: \'10px\'';
    const JQ_TRANSFORM_ORIGIN    = 'origin: [[\'20%\', \'20%\']]';

    /* jquery transit options constants */ 
    const JQ_TRANSIT_X         = 'x: \'10px\'';
    const JQ_TRANSIT_Y         = 'y: \'10px\'';
    const JQ_TRANSIT_TRANSLATE = 'translate: [10px, 10px]';
    const JQ_TRANSIT_PERSPECTIVE = 'perspective: 100';
    const JQ_TRANSIT_ROTATE    = 'rotate: \'45deg\'';
    const JQ_TRANSIT_ROTATEX   = 'rotateX: 30';
    const JQ_TRANSIT_ROTATEY   = 'rotateY: 30';
    const JQ_TRANSIT_ROTATE3D  = 'rotate3d: [1, 1, 0, 45]';
    const JQ_TRANSIT_SCALE     = 'scale: 2';
    const JQ_TRANSIT_SCALE2    = 'scale: [1.5, 1.5]';
    const JQ_TRANSIT_SKEWX     = 'skewX: \'10deg\'';
    const JQ_TRANSIT_SKEWY     = 'skewY: \'10deg\'';

    /* jquery transform code snippet constants */
    const CS_TRANSFORM_MATRIX    = '$(\'#sampleid\').css({matrix: [1, 0, 0, 1, 0, 0]});';
    const CS_TRANSFORM_REFLECT   = '$(\'#sampleid\').css({reflect: true});'; 
    const CS_TRANSFORM_REFLECTX  = '$(\'#sampleid\').css({reflectX: true});';
    const CS_TRANSFORM_REFLECTXY = '$(\'#sampleid\').css({reflectXY: true});';
    const CS_TRANSFORM_REFLECTY  = '$(\'#sampleid\').css({reflectY: true});';
    const CS_TRANSFORM_ROTATE    = '$(\'#sampleid\').css({rotate: \'45deg\'});';
    const CS_TRANSFORM_SKEW      = '$(\'#sampleid\').css({skew: [\'10deg\', \'10deg\']});';
    const CS_TRANSFORM_SKEWX     = '$(\'#sampleid\').css({skewX: \'10deg\'});';
    const CS_TRANSFORM_SKEWY     = '$(\'#sampleid\').css({skewY: \'10deg\'});';
    const CS_TRANSFORM_SCALE     = '$(\'#sampleid\').css({scale: [1.5, 1.5]});';
    const CS_TRANSFORM_SCALEX    = '$(\'#sampleid\').css({scaleX: 1.5});';
    const CS_TRANSFORM_SCALEY    = '$(\'#sampleid\').css({scaleY: 1.5});';
    const CS_TRANSFORM_TRANSLATE = '$(\'#sampleid\').css({translate: [\'10px\', \'10px\']});';
    const CS_TRANSFORM_TRANSLATEX = '$(\'#sampleid\').css({translateX: \'10px\'});';
    const CS_TRANSFORM_TRANSLATEY = '$(\'#sampleid\').css({translateY: \'10px\'});';
    const CS_TRANSFORM_ORIGIN    = '$(\'#sampleid\').css({origin: [\'20%\', \'20%\']});';
    
    /* jquery jquery-animate-css-rotate-scale code snippet constants */
    const CS_ACRS_ROTATE       = '$(\'#sampleid\').rotate(\'45deg\');';
    const CS_ACRS_SCALE        = '$(\'#sampleid\').scale(1.5);';
    const CS_ACRS_CHAINING     = '$(\'#sampleid\').scale(1.5).rotate(\'45deg\');';
    
    /* jquery transit code snippet constants */
    const CS_TRANSIT_X           = '$(\'#sampleid\').css({ x: \'10px\' });';
    const CS_TRANSIT_Y           = '$(\'#sampleid\').css({ y: \'10px\' });';
    const CS_TRANSIT_TRANSLATE   = '$(\'#sampleid\').css({ translate: [10px, 10px] });';
    const CS_TRANSIT_PERSPECTIVE = '$(\'#sampleid\').css({ perspective: 100 });';
    const CS_TRANSIT_PERSPECTIVE2 = '$(\'#sampleid\').css({ perspective: 100, rotateX: 30});';
    const CS_TRANSIT_ROTATE      = '$(\'#sampleid\').css({ rotate: \'45deg\' });';
    const CS_TRANSIT_ROTATEX     = '$(\'#sampleid\').css({ rotateX: 30 });';
    const CS_TRANSIT_ROTATEY     = '$(\'#sampleid\').css({ rotateY: 30 });';
    const CS_TRANSIT_ROTATE3D    = '$(\'#sampleid\').css({ rotate3d: [1, 1, 0, 45] });';
    const CS_TRANSIT_SCALE       = '$(\'#sampleid\').css({ scale: 2 });';
    const CS_TRANSIT_SCALE2      = '$(\'#sampleid\').css({ scale: [1.5, 1.5] });';
    const CS_TRANSIT_SKEWX       = '$(\'#sampleid\').css({ skewX: \'10deg\' });';
    const CS_TRANSIT_SKEWY       = '$(\'#sampleid\').css({ skewY: \'10deg\' });';
    const CS_TRANSIT_TRANSITION  = '$(\'#sampleid\').transition({ opacity: 0.1, scale: 0.5, 1000, \'in\', function() { });';
    
    /* jquery Rotate3Di code snippet constants */ 
    const CS_ROTATE3DI = '$(\'#sampleid\').rotate3Di(\'+=180\', 2000);';
    
    /* table field constants */ 
    const F_ID                     = 'id';   
    const F_PARENT_ID              = 'parent_id';   
    const F_EXPORT_ID              = 'export_id';
    const F_SECTION_ID             = 'section_id';    
    const F_STATUS                 = 'status';  
    const F_TYPE                   = 'type';  
    const F_LAYER_ID               = 'layer_id'; 
    const F_ON_EVENT               = 'on_event';  
    const F_LOCK_EVENT             = 'lock_event';  
    const F_LOCK_EVENT_TYPE        = 'lock_event_type';  
    const F_START_DELAY            = 'start_delay';  
    const F_DURATION               = 'duration';  
    const F_OUTPUT                 = 'output';  
    const F_OUTPUT_POS             = 'output_pos';
    const F_JAVASCRIPT             = 'javascript';
    const F_START_TOP_POS_SIGN     = 'start_top_pos_sign';        
    const F_START_TOP_POS          = 'start_top_pos';  
    const F_START_TOP_POS_FORMAT   = 'start_top_pos_format';
    const F_START_LEFT_POS_SIGN    = 'start_left_pos_sign';  
    const F_START_LEFT_POS         = 'start_left_pos';  
    const F_START_LEFT_POS_FORMAT  = 'start_left_pos_format';  
    const F_POSITION               = 'position';  
    const F_ZINDEX                 = 'zindex';  
    const F_EASING_A               = 'easing_a';
    const F_EASING_B               = 'easing_b';
    const F_MOVE_TOP_POS_SIGN_A    = 'move_top_pos_sign_a';  
    const F_MOVE_TOP_POS_A         = 'move_top_pos_a'; 
    const F_MOVE_TOP_POS_FORMAT_A  = 'move_top_pos_format_a'; 
    const F_MOVE_LEFT_POS_SIGN_A   = 'move_left_pos_sign_a';  
    const F_MOVE_LEFT_POS_A        = 'move_left_pos_a';  
    const F_MOVE_LEFT_POS_FORMAT_A = 'move_left_pos_format_a'; 
    const F_MOVE_TOP_POS_SIGN_B    = 'move_top_pos_sign_b';  
    const F_MOVE_TOP_POS_B         = 'move_top_pos_b';  
    const F_MOVE_TOP_POS_FORMAT_B  = 'move_top_pos_format_b'; 
    const F_MOVE_LEFT_POS_SIGN_B   = 'move_left_pos_sign_b'; 
    const F_MOVE_LEFT_POS_B        = 'move_left_pos_b'; 
    const F_MOVE_LEFT_POS_FORMAT_B = 'move_left_pos_format_b'; 
    const F_OPTIONS_A              = 'options_a'; 
    const F_OPTIONS_B              = 'options_b'; 
    const F_EXTRA_JS_A             = 'extra_js_a'; 
    const F_EXTRA_JS_B             = 'extra_js_b';     
    const F_ROW_LOCKED             = 'row_locked';     
 
 
    /* Field UI constants keys */
    const KEY_USER_ID      = 'userid';      
    const KEY_BULLET_POS   = 'bulletpos';      
    
    /* Field constants keys */
    const KEY_FILENAME      = 'filename';  
    const KEY_DIRECTORY     = 'directory';  
    const KEY_ORDER         = 'order';  
    const KEY_TITLE         = 'title'; 
    const KEY_COOKIE        = 'cookie'; 
    const KEY_COOKIE_NAME   = 'cookie_name'; 
    const KEY_COOKIE_OPTION_1 = 'cookie_option_1'; 
    const KEY_COOKIE_OPTION_2 = 'cookie_option_2'; 
    const KEY_COOKIE_WITH     = 'cookie_with'; 
    const KEY_COOKIE_SCOPE    = 'cookie_scope'; 
    
    /* Toggle constants keys */
    const KEY_TOGGLE_GROUP    = 'toggle_group'; 
    const KEY_TOGGLE_ADMIN   = 'toggle_admin'; 
    const KEY_TOGGLE_LIBRARY = 'toggle_library'; 
    const KEY_TOGGLE_FAR     = 'toggle_far'; 
    
    /* Libary constants keys */    
    const KEY_SORT_LIB_DIR = 'sort_lib_dir'; 
        
    /* Output constants keys */
    const KEY_OUTPUT             = 'output';
    const KEY_OUTPUT_COMPRESSION = 'output_compression';
    
    /* Default jQuery constant key */
    const KEY_REGISTER_JQUERY = 'register_jquery';
    const KEY_REGISTER_JQUERY_TRANSIT = 'register_jquery_transition';
    const KEY_REGISTER_JQUERY_TRANSFORM = 'register_jquery_transform';
    const KEY_REGISTER_JQUERY_ROTATE3DI = 'register_jquery_rotate3di';
    const KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE = 'register_jquery_animatecssrotatescale';
    const KEY_REGISTER_JQUERY_EASING = 'register_jquery_easing';
    
    /* Minimal role level constant key */
    const KEY_MIN_ROLE_LEVEL   = 'min_role_level';
    const KEY_MIN_ROLE_ADMIN   = 'min_role_admin';
    const KEY_MIN_ROLE_LIBRARY = 'min_role_library';
    
    /* Output protected constant key */
    const KEY_OUTPUT_PROTECTED = 'output_protected';    
    
    /* Prefered method constant key */
    const KEY_PREFERED_METHOD = 'prefered_method';
    
    /* Footer ads constant key */
    const KEY_FOOTER_ADS = 'footer_ads';
    
    /* Deactivation constant key */
    const KEY_DELETE_ALL = 'delete_all';
    
    /* Extra easing key */
    const KEY_EXTRA_EASING = 'extra_easing';
    
    /* Number Posts to display key */
    const KEY_NUMBER_POSTS = 'number_posts';

    /* Starting position by default key */
    const KEY_STARTING_POSITION = 'starting_position';
    
    /* Output constants*/  
    const OUTPUT_HEADER = 'wp_head';    
    const OUTPUT_FOOTER = 'wp_footer'; 
    
    /* extension constants */
    const EXT_JS   = 'js';
    const EXT_CSS  = 'css';
    const EXT_TWZ  = 'twz'; 
    const EXT_TWIZ = 'twiz';
    const EXT_XML  = 'xml'; 
    
    /* Format constants */
    const FORMAT_PIXEL    = 'px';
    const FORMAT_PERCENT  = '%';
    const FORMAT_EM       = 'em'; 
    const FORMAT_INCH     = 'in';
    
    /* on event constants */
    const EV_MANUAL     = 'Manually'; 
    const EV_BLUR       = 'Blur'; 
    const EV_CHANGE     = 'Change'; 
    const EV_CLICK      = 'Click'; 
    const EV_DBLCLICK   = 'DblClick';
    const EV_ERROR      = 'Error'; 
    const EV_FOCUS      = 'Focus'; 
    const EV_FOCUSIN    = 'FocusIn'; 
    const EV_FOCUSOUT   = 'FocusOut';
    const EV_HOVER      = 'Hover';
    const EV_KEYDOWN    = 'KeyDown';
    const EV_KEYPRESS   = 'KeyPress';
    const EV_KEYUP      = 'KeyUp';
    const EV_MOUSEDOWN  = 'MouseDown';
    const EV_MOUSEENTER = 'MouseEnter';
    const EV_MOUSELEAVE = 'MouseLeave';
    const EV_MOUSEMOVE  = 'MouseMove';
    const EV_MOUSEOUT   = 'MouseOut';
    const EV_MOUSEOVER  = 'MouseOver'; 
    const EV_MOUSEUP    = 'MouseUp'; 
    const EV_RESIZE     = 'Resize'; 
    const EV_SCROLL     = 'Scroll'; 
    const EV_SELECT     = 'Select'; 
    const EV_SUBMIT     = 'Submit'; 
    const EV_TOGGLE     = 'Toggle'; 
    const EV_UNLOAD     = 'Unload'; 

    /* starting position constants */
    const POS_NO_POS   = ''; 
    const POS_ABSOLUTE = 'absolute'; 
    const POS_RELATIVE = 'relative';
    const POS_FIXED    = 'fixed';
    const POS_STATIC   = 'static';
    
    /* extra easing constants */
    const EASEINQUAD     = 'easeInQuad'; 
    const EASEOUTQUAD    = 'easeOutQuad'; 
    const EASEINOUTQUAD  = 'easeInOutQuad'; 
    const EASEINCUBIC    = 'easeInCubic'; 
    const EASEOUTCUBIC   = 'easeOutCubic'; 
    const EASEINOUTCUBIC = 'easeInOutCubic'; 
    const EASEINQUART    = 'easeInQuart'; 
    const EASEOUTQUART   = 'easeOutQuart'; 
    const EASEINOUTQUART = 'easeInOutQuart'; 
    const EASEINQUINT    = 'easeInQuint'; 
    const EASEOUTQUINT   = 'easeOutQuint'; 
    const EASEINOUTQUINT = 'easeInOutQuint'; 
    const EASEINSINE     = 'easeInSine'; 
    const EASEOUTSINE    = 'easeOutSine'; 
    const EASEINOUTSINE  = 'easeInOutSine'; 
    const EASEINEXPO     = 'easeInExpo'; 
    const EASEOUTEXPO    = 'easeOutExpo'; 
    const EASEINOUTEXPO  = 'easeInOutExpo'; 
    const EASEINCIRC     = 'easeInCirc'; 
    const EASEOUTCIRC    = 'easeOutCirc'; 
    const EASEINOUTCIRC  = 'easeInOutCirc'; 
    const EASEINELASTIC  = 'easeInElastic'; 
    const EASEOUTELASTIC = 'easeOutElastic'; 
    const EASEINOUTELASTIC = 'easeInOutElastic'; 
    const EASEINBACK      = 'easeInBack'; 
    const EASEOUTBACK     = 'easeOutBack'; 
    const EASEINOUTBACK   = 'easeInOutBack'; 
    const EASEINBOUNCE    = 'easeInBounce'; 
    const EASEOUTBOUNCE   = 'easeOutBounce'; 
    const EASEINOUTBOUNCE = 'easeInOutBounce'; 


     /* transit easing constants */
    const TRANSIT_EASE   = 'ease';
    const TRANSIT_IN     = 'in';
    const TRANSIT_OUT    = 'out';
    const TRANSIT_IN_OUT = 'in-out';
    const TRANSIT_SNAP   = 'snap';

    
    /* default starting position on add new */    
    const DEFAULT_STARTING_POSITION = self::POS_RELATIVE;

                                         
    /* extra easing array */
    protected $array_extra_easing = array(self::EASEINQUAD
                                         ,self::EASEOUTQUAD
                                         ,self::EASEINOUTQUAD
                                         ,self::EASEINCUBIC
                                         ,self::EASEOUTCUBIC
                                         ,self::EASEINOUTCUBIC 
                                         ,self::EASEINQUART
                                         ,self::EASEOUTQUART
                                         ,self::EASEINOUTQUART
                                         ,self::EASEINQUINT
                                         ,self::EASEOUTQUINT
                                         ,self::EASEINOUTQUINT
                                         ,self::EASEINSINE
                                         ,self::EASEOUTSINE
                                         ,self::EASEINOUTSINE
                                         ,self::EASEINEXPO
                                         ,self::EASEOUTEXPO
                                         ,self::EASEINOUTEXPO
                                         ,self::EASEINCIRC
                                         ,self::EASEOUTCIRC
                                         ,self::EASEINOUTCIRC
                                         ,self::EASEINELASTIC
                                         ,self::EASEOUTELASTIC
                                         ,self::EASEINOUTELASTIC
                                         ,self::EASEINBACK
                                         ,self::EASEOUTBACK
                                         ,self::EASEINOUTBACK
                                         ,self::EASEINBOUNCE
                                         ,self::EASEOUTBOUNCE
                                         ,self::EASEINOUTBOUNCE
                                         );

    /* transit excluded extra easing */
    protected $array_transit_excluded_extra_easing = array(self::EASEINCUBIC
                                                          ,self::EASEINELASTIC
                                                          ,self::EASEOUTELASTIC
                                                          ,self::EASEINOUTELASTIC
                                                          ,self::EASEINBOUNCE
                                                          ,self::EASEOUTBOUNCE 
                                                          ,self::EASEINOUTBOUNCE
                                                          );
                                         
    /* transit easing array */
    protected $array_transit_easing = array(self::TRANSIT_EASE
                                           ,self::TRANSIT_IN
                                           ,self::TRANSIT_OUT
                                           ,self::TRANSIT_IN_OUT
                                           ,self::TRANSIT_SNAP
                                           );


    /* Position array */
    protected $array_position = array(self::POS_NO_POS
                                     ,self::POS_ABSOLUTE   
                                     ,self::POS_RELATIVE
                                     ,self::POS_FIXED
                                     ,self::POS_STATIC 
                                     );

    /* Format array */
    private $array_format = array(self::FORMAT_PIXEL     
                                 ,self::FORMAT_PERCENT  
                                 ,self::FORMAT_EM  
                                 ,self::FORMAT_INCH 
                                 );

    /* on event array */ 
    private $array_element_type = array(self::ELEMENT_TYPE_ID      
                                       ,self::ELEMENT_TYPE_CLASS  
                                       ,self::ELEMENT_TYPE_NAME  
                                       ,self::ELEMENT_TYPE_TAG  
                                       );

    /* section constants array */
    protected $array_default_section = array(self::DEFAULT_SECTION_HOME    
                                            ,self::DEFAULT_SECTION_EVERYWHERE  
                                            ,self::DEFAULT_SECTION_ALL_CATEGORIES
                                            ,self::DEFAULT_SECTION_ALL_PAGES 
                                            ,self::DEFAULT_SECTION_ALL_ARTICLES 
                                            );

    /* on event array */ 
    private $array_on_event = array(self::EV_MANUAL
                                   ,self::EV_BLUR      
                                   ,self::EV_CHANGE    
                                   ,self::EV_CLICK     
                                   ,self::EV_DBLCLICK  
                                   ,self::EV_ERROR      
                                   ,self::EV_FOCUS      
                                   ,self::EV_FOCUSIN    
                                   ,self::EV_FOCUSOUT   
                                   ,self::EV_HOVER      
                                   ,self::EV_KEYDOWN    
                                   ,self::EV_KEYPRESS   
                                   ,self::EV_KEYUP      
                                   ,self::EV_MOUSEDOWN  
                                   ,self::EV_MOUSEENTER 
                                   ,self::EV_MOUSELEAVE 
                                   ,self::EV_MOUSEMOVE  
                                   ,self::EV_MOUSEOUT   
                                   ,self::EV_MOUSEOVER  
                                   ,self::EV_MOUSEUP    
                                   ,self::EV_RESIZE     
                                   ,self::EV_SCROLL     
                                   ,self::EV_SELECT    
                                   ,self::EV_SUBMIT    
                                   ,self::EV_TOGGLE    
                                   ,self::EV_UNLOAD
                                   );
 
    /* directional array image suffix */ 
    private $array_arrows = array(self::DIMAGE_N   
                                 ,self::DIMAGE_NE    
                                 ,self::DIMAGE_E  
                                 ,self::DIMAGE_SE     
                                 ,self::DIMAGE_S    
                                 ,self::DIMAGE_SW  
                                 ,self::DIMAGE_W  
                                 ,self::DIMAGE_NW  
                                 );

    /* action array used to exclude ajax container */
    private $array_action_excluded = array(self::ACTION_MENU   
                                          ,self::ACTION_SAVE    
                                          ,self::ACTION_CANCEL  
                                          ,self::ACTION_NEW     
                                          ,self::ACTION_EDIT   
                                          ,self::ACTION_COPY
                                          ,self::ACTION_DELETE
                                          ,self::ACTION_DROP_ROW
                                          ,self::ACTION_COPY_GROUP
                                          ,self::ACTION_DELETE_GROUP
                                          ,self::ACTION_SAVE_GROUP
                                          ,self::ACTION_FAR_FIND
                                          );

    /* jQuery jquery-animate-css-rotate-scale code snippets array */
    private $array_jQuery_acrs_code_snippets = array(self::CS_ACRS_ROTATE
                                                         ,self::CS_ACRS_SCALE
                                                         ,self::CS_ACRS_CHAINING
                                                         );    

    /* jQuery transform code snippets array */
    private $array_jQuery_transform_code_snippets = array(self::CS_TRANSFORM_MATRIX
                                                         ,self::CS_TRANSFORM_REFLECT
                                                         ,self::CS_TRANSFORM_REFLECTX
                                                         ,self::CS_TRANSFORM_REFLECTXY
                                                         ,self::CS_TRANSFORM_REFLECTY
                                                         ,self::CS_TRANSFORM_ROTATE
                                                         ,self::CS_TRANSFORM_SKEW
                                                         ,self::CS_TRANSFORM_SKEWX
                                                         ,self::CS_TRANSFORM_SKEWY
                                                         ,self::CS_TRANSFORM_SCALE
                                                         ,self::CS_TRANSFORM_SCALEX
                                                         ,self::CS_TRANSFORM_SCALEY
                                                         ,self::CS_TRANSFORM_TRANSLATE
                                                         ,self::CS_TRANSFORM_TRANSLATEX
                                                         ,self::CS_TRANSFORM_TRANSLATEY
                                                         ,self::CS_TRANSFORM_ORIGIN
                                                         );     

    /* jQuery transit code snippets array */
    private $array_jQuery_transit_code_snippets = array(self::CS_TRANSIT_X
                                                       ,self::CS_TRANSIT_Y
                                                       ,self::CS_TRANSIT_TRANSLATE
                                                       ,self::CS_TRANSIT_PERSPECTIVE
                                                       ,self::CS_TRANSIT_PERSPECTIVE2
                                                       ,self::CS_TRANSIT_ROTATE
                                                       ,self::CS_TRANSIT_ROTATEX
                                                       ,self::CS_TRANSIT_ROTATEY
                                                       ,self::CS_TRANSIT_ROTATE3D
                                                       ,self::CS_TRANSIT_SCALE
                                                       ,self::CS_TRANSIT_SCALE2
                                                       ,self::CS_TRANSIT_SKEWX
                                                       ,self::CS_TRANSIT_SKEWY
                                                       ,self::CS_TRANSIT_TRANSITION
                                                       );                                              

    /* jQuery common options array */
    private $array_jQuery_options = array(self::JQ_TOP
                                         ,self::JQ_LEFT
                                         ,self::JQ_WITDH
                                         ,self::JQ_HEIGHT
                                         ,self::JQ_OPACITY
                                         ,self::JQ_FONTSIZE
                                         ,self::JQ_MARGINTOP
                                         ,self::JQ_MARGINBOTTOM
                                         ,self::JQ_MARGINLEFT
                                         ,self::JQ_MARGINRIGHT
                                         ,self::JQ_PADDINGTOP
                                         ,self::JQ_PADDINGBOTTOM
                                         ,self::JQ_PADDINGLEFT
                                         ,self::JQ_PADDINGRIGHT
                                         ,self::JQ_BORDERWIDTH
                                         ,self::JQ_BORDERTOPWIDTH
                                         ,self::JQ_BORDERBOTTOMWIDTH
                                         ,self::JQ_BORDERIGHTWIDTH
                                         ,self::JQ_BORDERLEFTWIDTH
                                         );

    /* jQuery jquery-animate-css-rotate-scale options array */
    private $array_jQuery_acrs_options = array(self::JQ_ACRS_ROTATE
                                              ,self::JQ_ACRS_SCALE
                                              );

    /* jQuery transform options array */
    private $array_jQuery_transform_options = array(self::JQ_TRANSFORM_MATRIX
                                                   ,self::JQ_TRANSFORM_REFLECT
                                                   ,self::JQ_TRANSFORM_REFLECTX
                                                   ,self::JQ_TRANSFORM_REFLECTXY
                                                   ,self::JQ_TRANSFORM_REFLECTY
                                                   ,self::JQ_TRANSFORM_ROTATE
                                                   ,self::JQ_TRANSFORM_SKEW
                                                   ,self::JQ_TRANSFORM_SKEWX
                                                   ,self::JQ_TRANSFORM_SKEWY
                                                   ,self::JQ_TRANSFORM_SCALE
                                                   ,self::JQ_TRANSFORM_SCALEX
                                                   ,self::JQ_TRANSFORM_SCALEY
                                                   ,self::JQ_TRANSFORM_TRANSLATE
                                                   ,self::JQ_TRANSFORM_TRANSLATEX
                                                   ,self::JQ_TRANSFORM_TRANSLATEY
                                                   ,self::JQ_TRANSFORM_ORIGIN
                                                   );

    /* jQuery transit options array */
    private $array_jQuery_transit_options = array(self::JQ_TRANSIT_X
                                                 ,self::JQ_TRANSIT_Y
                                                 ,self::JQ_TRANSIT_TRANSLATE
                                                 ,self::JQ_TRANSIT_PERSPECTIVE
                                                 ,self::JQ_TRANSIT_ROTATE
                                                 ,self::JQ_TRANSIT_ROTATEX
                                                 ,self::JQ_TRANSIT_ROTATEY
                                                 ,self::JQ_TRANSIT_ROTATE3D
                                                 ,self::JQ_TRANSIT_SCALE
                                                 ,self::JQ_TRANSIT_SCALE2
                                                 ,self::JQ_TRANSIT_SKEWX
                                                 ,self::JQ_TRANSIT_SKEWY
                                                 );                                         

    /* XML MULTI-VERSION mapping values */
    private $array_twz_mapping = array(self::F_PARENT_ID                => 'AP'
                                      ,self::F_EXPORT_ID                => 'AA'
                                      ,self::F_SECTION_ID               => 'AH' 
                                      ,self::F_STATUS                   => 'BB'
                                      ,self::F_TYPE                     => 'BL' 
                                      ,self::F_LAYER_ID                 => 'CC'    
                                      ,self::F_ON_EVENT                 => 'DA'
                                      ,self::F_LOCK_EVENT               => 'DB'
                                      ,self::F_LOCK_EVENT_TYPE          => 'DC'
                                      ,self::F_START_DELAY              => 'DD'
                                      ,self::F_DURATION                 => 'EE'
                                      ,self::F_OUTPUT                   => 'EG'
                                      ,self::F_OUTPUT_POS               => 'EJ'
                                      ,self::F_JAVASCRIPT               => 'EL'
                                      ,self::F_START_TOP_POS_SIGN       => 'FF'
                                      ,self::F_START_TOP_POS            => 'GG'    
                                      ,self::F_START_TOP_POS_FORMAT     => 'GF'  
                                      ,self::F_START_LEFT_POS_SIGN      => 'HH'
                                      ,self::F_START_LEFT_POS           => 'II'
                                      ,self::F_START_LEFT_POS_FORMAT    => 'IF'
                                      ,self::F_POSITION                 => 'JJ'
                                      ,self::F_ZINDEX                   => 'JL'
                                      ,self::F_EASING_A                 => 'KD'
                                      ,self::F_EASING_B                 => 'KH'
                                      ,self::F_MOVE_TOP_POS_SIGN_A      => 'KK'    
                                      ,self::F_MOVE_TOP_POS_A           => 'LL'
                                      ,self::F_MOVE_TOP_POS_FORMAT_A    => 'LF'
                                      ,self::F_MOVE_LEFT_POS_SIGN_A     => 'MM'
                                      ,self::F_MOVE_LEFT_POS_A          => 'OO'
                                      ,self::F_MOVE_LEFT_POS_FORMAT_A   => 'OF'
                                      ,self::F_MOVE_TOP_POS_SIGN_B      => 'PP'    
                                      ,self::F_MOVE_TOP_POS_B           => 'QQ'
                                      ,self::F_MOVE_TOP_POS_FORMAT_B    => 'QF'
                                      ,self::F_MOVE_LEFT_POS_SIGN_B     => 'RR'
                                      ,self::F_MOVE_LEFT_POS_B          => 'SS'
                                      ,self::F_MOVE_LEFT_POS_FORMAT_B   => 'SF'
                                      ,self::F_OPTIONS_A                => 'TT'
                                      ,self::F_OPTIONS_B                => 'UU'
                                      ,self::F_EXTRA_JS_A               => 'VV'
                                      ,self::F_EXTRA_JS_B               => 'WW'
                                      );

    /* Fields array */ 
    protected $array_fields = array(self::F_ID          
                                   ,self::F_PARENT_ID 
                                   ,self::F_EXPORT_ID 
                                   ,self::F_SECTION_ID          
                                   ,self::F_STATUS 
                                   ,self::F_TYPE                                   
                                   ,self::F_LAYER_ID 
                                   ,self::F_ON_EVENT   
                                   ,self::F_LOCK_EVENT                                 
                                   ,self::F_LOCK_EVENT_TYPE                                 
                                   ,self::F_START_DELAY          
                                   ,self::F_DURATION    
                                   ,self::F_OUTPUT  
                                   ,self::F_OUTPUT_POS
                                   ,self::F_JAVASCRIPT   
                                   ,self::F_START_TOP_POS_SIGN 
                                   ,self::F_START_TOP_POS           
                                   ,self::F_START_TOP_POS_FORMAT   
                                   ,self::F_START_LEFT_POS_SIGN  
                                   ,self::F_START_LEFT_POS      
                                   ,self::F_START_LEFT_POS_FORMAT 
                                   ,self::F_POSITION             
                                   ,self::F_ZINDEX     
                                   ,self::F_EASING_A
                                   ,self::F_EASING_B     
                                   ,self::F_MOVE_TOP_POS_SIGN_A      
                                   ,self::F_MOVE_TOP_POS_A      
                                   ,self::F_MOVE_TOP_POS_FORMAT_A   
                                   ,self::F_MOVE_LEFT_POS_SIGN_A 
                                   ,self::F_MOVE_LEFT_POS_A      
                                   ,self::F_MOVE_LEFT_POS_FORMAT_A   
                                   ,self::F_MOVE_TOP_POS_SIGN_B     
                                   ,self::F_MOVE_TOP_POS_B      
                                   ,self::F_MOVE_TOP_POS_FORMAT_B  
                                   ,self::F_MOVE_LEFT_POS_SIGN_B 
                                   ,self::F_MOVE_LEFT_POS_B     
                                   ,self::F_MOVE_LEFT_POS_FORMAT_B    
                                   ,self::F_OPTIONS_A           
                                   ,self::F_OPTIONS_B            
                                   ,self::F_EXTRA_JS_A          
                                   ,self::F_EXTRA_JS_B           
                                   );                                 


    /* upload import export path constant*/
    const IMPORT_PATH = '/twiz/';       
    const EXPORT_PATH = 'export/';       
  
    /* import max file size constant */ 
    const IMPORT_MAX_SIZE = '2097152';
    
    function __construct(){
    
        global $wpdb;
        
        /* PLUGIN URL */
        $pluginUrl = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        $pluginUrl = str_replace('/includes/','',$pluginUrl);
        
        /* PLUGIN DIR */
        $pluginDir = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        $pluginDir = str_replace('/includes/','',$pluginDir);

        /* Twiz variable configuration */
        $this->version    = '1.5.9';
        $this->cssVersion = '1-32';
        $this->dbVersion  = '2.83';
        $this->pluginUrl  = $pluginUrl;
        $this->pluginDir  = $pluginDir;
        $this->nonce      =  wp_create_nonce('twiz-nonce');
        $this->table      = $wpdb->prefix .'the_welcomizer';
        $this->pluginName = __('The Welcomizer', 'the-welcomizer');
        $this->import_path_message = '/wp-content'.self::IMPORT_PATH;
        $this->export_path_message = '/wp-content'.self::IMPORT_PATH.self::EXPORT_PATH;
        $this->skin = get_option('twiz_skin');
        $this->admin_option = get_option('twiz_admin');
        $this->toggle_option = get_option('twiz_toggle');
        $this->DEFAULT_SECTION = get_option('twiz_setting_menu');
        $this->userid = get_current_user_id(); // Used for UIsettings since v1.5
        $ok = $this->SetUserSettings();
        
    }
    
    private function SetUserSettings(){
    
       // toggle, updated <= v 1.5  
       if(!isset($this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD])) $this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD] = '';
       
       if( $this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD] == '' ) {
       
            $this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD] = 'twiz_far_simple';
            $code = update_option('twiz_toggle', $this->toggle_option);
        }
            
        // Selected menu, updated <= v 1.5  
        $twiz_setting_menu_old = ( !is_array($this->DEFAULT_SECTION) ) ? $this->DEFAULT_SECTION : ''; // Migrate setting
        $this->DEFAULT_SECTION = ( !is_array($this->DEFAULT_SECTION) ) ? '' : $this->DEFAULT_SECTION;
        if(!isset($this->DEFAULT_SECTION[$this->userid])) $this->DEFAULT_SECTION[$this->userid] = '';
        
        if( $this->DEFAULT_SECTION[$this->userid] == '' ) {
        
            $this->DEFAULT_SECTION[$this->userid] = ($twiz_setting_menu_old != '') ? $twiz_setting_menu_old : self::DEFAULT_SECTION_HOME;
            $code = update_option('twiz_setting_menu', $this->DEFAULT_SECTION);
        }
        
        // Skin, updated <= v 1.5  
        $skin_old_format = ( !is_array($this->skin) ) ? $this->skin : ''; // migrate setting <= v1.5
        $this->skin = ( !is_array($this->skin) ) ? '' : $this->skin;
        $this->skin[$this->userid] = (!isset($this->skin[$this->userid]) ) ? '' : $this->skin[$this->userid] ;
        
        if(( $this->skin[$this->userid] == '' ) or ( $this->skin[$this->userid] == self::SKIN_PATH ) ) {
        
            $this->skin[$this->userid] = ( $skin_old_format != '' ) ? $skin_old_format : self::SKIN_PATH.self::DEFAULT_SKIN; // migrate setting <= v1.5  
            $code = update_option('twiz_skin', $this->skin);
        }
        
        return true;
    }

    function twizIt(){
        
        $html = '<div id="twiz_plugin">';
        $html .= '<div id="twiz_background"></div>';
        $html .= '<div id="twiz_master">';
        
        $html .= $this->getHtmlSkinBullets();
        $html .= $this->getHtmlGlobalstatus();
        $html .= $this->getHtmlHeader();
        
        $myTwizMenu = new TwizMenu(); 
        $html .= '<div><div id="twiz_menu" class="twiz-reset-nav"><div id="twiz_ajax_menu">'.$myTwizMenu->getHtmlMenu().'</div>';
        $html .= '<div id="twiz_option_menu"><div id="twiz_more_menu">&gt;&gt;</div>';
        $html .= '<div id="twiz_add_menu">+</div></div>';
        $html .= '<div id="twiz_loading_menu"></div>';
        $html .= '<div class="twiz-clear"></div></div>';
        $html .= '<div id="twiz_sub_container"></div>';
        
        $html .= $this->getHtmlListMenu();
        $html .= $this->getHtmlList();
        $html .= $this->getHtmlFooter();
        $html .= $this->getHtmlFooterMenu();
        
        $html .= '</div>';
        $html .= '<div id="twiz_vertical_menu" class="twiz-reset-nav">'.$myTwizMenu->getHtmlVerticalMenu().'</div>';
        $html .= '<div id="twiz_right_panel"></div>';
        
        $html .= $this->preloadImages();
        $html .= '</div>'; 
        
   
        return $html;
    }
      
    private function getHtmlGlobalstatus(){
    
        return '<div id="twiz_global_status">'.$this->getImgGlobalStatus().'</div>';
    }
    
    private function getHtmlHeader(){
        
        $header = '<div id="twiz_header" class="twiz-reset-nav"><div id="twiz_head_logo"></div><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FThe-Welcomizer%2F173368186051321&amp;send=false&amp;layout=button_count&amp;width=150&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=20&amp;appId=24077487353" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px; width:150px;" allowTransparency="true"></iframe><span id="twiz_head_title"><a href="http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/" target="_blank">'.$this->pluginName.'</a></span><div id="twiz_head_version"><a href="http://www.wordpress.org/extend/plugins/the-welcomizer/changelog/" target="_blank">v'.$this->version.'</a></div> 
</div><div class="twiz-clear"></div>
    ';
        
        return $header;
    }
    
    private function getHtmlFooterMenu(){

      $import = '<div id="twiz_import_container">'.__('Import', 'the-welcomizer').'</div>';
      $export = '<div id="twiz_export">'.__('Export', 'the-welcomizer').'</div>';
      $library_upload = '<div id="twiz_library_upload" class="twiz-display-none">'.__('Upload', 'the-welcomizer').'</div>';
      $library = '<div id="twiz_library">'.__('Library', 'the-welcomizer').'</div>';
      $admin = '<div id="twiz_admin">'.__('Admin', 'the-welcomizer').'</div>';
      
      $html = '<div id="twiz_footer_menu" class="twiz-reset-nav">'.$library_upload.$import.$export.$admin.$library.'</div><div id="twiz_export_url"></div>';
      
      return $html;
    }
    
    function getHtmlAds(){

        $extraspaces = '&nbsp;&nbsp;&nbsp;';
        
        $ads['1and1'] = '<a href="http://www.anrdoezrs.net/dp101vpyvpxCIJLIMFFCEDHFLMDJ" target="_blank" title="1and1.com"><img src="http://www.ftjcfx.com/h7104ltxlrpAGHJGKDDACBFDJKBH" border="0" class="twiz-ads-img"/></a>';
      
        $ads['All-Battery'] = '<a href="http://www.tkqlhce.com/g8104nmvsmu9FGIFJCC9BAGADEJH" target="_blank" title="All-Battery.com"><img src="http://www.tqlkg.com/4c106jy1qwuFLMOLPIIFHGMGJKPN" border="0" class="twiz-ads-img"/></a>';
      
        $ads['Cell Phone Shop'] = '<a href="http://www.dpbolvw.net/97115tenkem178A7B441326B6779" target="_blank" title="Cell Phone Shop"><img src="http://www.awltovhc.com/id102h48x20MSTVSWPPMONRWRSSU" border="0" class="twiz-ads-img"/></a>';
      
        $ads['bluehost'] = '<a href="http://www.bluehost.com/track/affordable_web_hosting/" target="_blank" title="bluehost.com"><img border="0" src="'.$this->pluginUrl.'/images/ads/bh_88x31_04.gif" class="twiz-ads-img"/></a>';
        
        $ads['watches'] = '<a href="http://www.dpbolvw.net/1h66r09608OUVXUYRROQPXSSWRU" target="_blank" title="Free Shipping on ALL orders!"><img src="http://www.lduhtrp.net/b8103kpthnl6CDFCG99687FAAE9C" border="0" class="twiz-ads-img"/></a>';
        
        $ads['Gravity Defyer'] = '<a href="http://www.kqzyfj.com/tn83vpyvpxCIJLIMFFCEDJIMKLD" target="_blank" title="Gravity Defyer"><img src="http://www.awltovhc.com/ec106g04tzxIOPROSLLIKJPOSQRJ" border="0"  class="twiz-ads-img"/></a>';
        
        $ads['myhosting'] = '<a href="http://www.anrdoezrs.net/dc100r09608OUVXUYRROQPWSRUWY" title="myhosting.com" target="_blank"><img src="http://www.lduhtrp.net/rl82g04tzxIOPROSLLIKJQMLOQS" alt="" border="0" class="twiz-ads-img"/></a>';
        
        $ads['mypcbackup'] = '<a href="http://www.jdoqocy.com/68104shqnhp4ABDAE77465DE7B5D" target="_blank" title="mypcbackup.com"><img src="http://www.lduhtrp.net/7r79qmqeki39AC9D66354CD6A4C" alt="" border="0" class="twiz-ads-img"/></a>';
        
        $ads['PetFoodDirect'] = '<a href="http://www.tkqlhce.com/tq119tenkem178A7B44132A76668" title="PetFoodDirect.com" target="_blank"><img src="http://www.awltovhc.com/6i104qmqeki39AC9D66354C9888A" alt="" border="0" class="twiz-ads-img"/></a>';
        
        $ads['Order Flowers Online'] = '<a href="http://www.anrdoezrs.net/fc81lnwtnvAGHJGKDDAKIDIECK" target="_blank" title="Order Flowers Online"><img src="http://www.lduhtrp.net/3b111nswkqo9FGIFJCC9JHCHDBJ" border="0" class="twiz-ads-img"/></a>';
        
        $ads['SuperJeweler'] = '<a href="http://www.anrdoezrs.net/9c102mu2-u1HNOQNRKKHJINJOOJK" target="_blank" title="Shop SuperJeweler - Free Shipping & Free Gift!"><img src="http://www.tqlkg.com/2t70h48x20MSTVSWPPMONSOTTOP" border="0" class="twiz-ads-img"/></a>';
        
        $ads['AccessoryGeeks'] = '<a href="http://www.dpbolvw.net/ka108iqzwqyDJKMJNGGDFEIIKHNE" target="_blank" title="Shop AccessoryGeeks.com!"><img src="http://www.awltovhc.com/47102snrflj4ABDAE7746599B8E5" border="0" class="twiz-ads-img"/></a>';
        
        $ok = shuffle($ads);
        
        $html = '<div id="twiz_ads">'
            . $ads[0] . $extraspaces  
            . $ads[1]  
            .'</div>';
        
        return $html;
    }
    
    private function getHtmlFooter(){

        $html = '
<div class="twiz-clear"></div><div id="twiz_footer" class="twiz-reset-nav">';

        if($this->admin_option[self::KEY_FOOTER_ADS] != '1'){
        
            $html .= '<a href="http://www.bluehost.com/track/affordable_web_hosting/" target="_blank" title="bluehost.com"><img border="0" src="'.$this->pluginUrl.'/images/ads/bh_88x31_04.gif" class="twiz-ads-img"/></a>';
        }
        
        $html .= '</div>';
        
        return $html;
    }    
      
    private function getHtmlListMenu(){
    
        $html = '<div class="twiz-row-color-1 twiz-text-right" name="twiz_listmenu" id="twiz_listmenu"><div id="twiz_far_matches" class="twiz-float-left twiz-text-left twiz-green"></div><span><a id="twiz_new" class="twiz-bold">'.__('Add New', 'the-welcomizer').'</a> '.utf8_encode('|').' <a id="twiz_create_group" class="twiz-bold">'.__('Create Group', 'the-welcomizer').'</a> '.utf8_encode('|').' <a id="twiz_findandreplace" class="twiz-bold">'.__('Find & Replace', 'the-welcomizer').'</a></span></div></div>';

        return $html;
    }
    
    private function add_animation_link( $javascript = '', $listarray = array() ){
        
        if( $javascript == ''){return '';}
        
        $searchstring = '';
        $htmllink = '';

        foreach( $listarray as $value ){
        
            $group = ($value[self::F_TYPE] == self::ELEMENT_TYPE_GROUP) ? '_'.self::ELEMENT_TYPE_GROUP : '';
            
            $searchstring = "$(document).twiz".$group."_".$value[self::F_SECTION_ID]."_".str_replace("-","_",sanitize_title_with_dashes($value[self::F_LAYER_ID]))."_".$value[self::F_EXPORT_ID]."();";
            
            $htmllink = '<a id="twiz'.$group.'_anim_link_'.$value[self::F_ID].'" name="twiz'.$group.'_anim_link_'.$value[self::F_EXPORT_ID].'" class="twiz-anim-link">'.$searchstring.'</a>';
        
       
            $javascript = str_replace($searchstring, $htmllink, $javascript);
        }
        
        return $javascript;
    }
    
    protected function createHtmlList( $listarray = array(), $saved_id = '', $parent_id = '', $action = '' ){ 

        if( count($listarray) == 0 ){return false;}
        
        $bullet = ' &#8226;';
        $opendiv = '';
        $closediv = '';
        $rowcolor = '';
        $saveeffect = '';
        $twiz_order_by = get_option('twiz_order_by');
        $twiz_order_by[$this->userid] = (!isset($twiz_order_by[$this->userid])) ? '' : $twiz_order_by[$this->userid];
        $twiz_order_by_status = ($twiz_order_by[$this->userid] == self::F_STATUS)? $bullet : '' ;
        $twiz_order_by_on_event = ($twiz_order_by[$this->userid] == self::F_ON_EVENT)? $bullet : '' ;
        $twiz_order_by_element = ($twiz_order_by[$this->userid] == self::F_LAYER_ID)? $bullet : '' ;
        $twiz_order_by_delay = ($twiz_order_by[$this->userid] == self::F_START_DELAY)? $bullet : '' ;
        $twiz_order_by_duration = ($twiz_order_by[$this->userid] == self::F_DURATION)? $bullet : '' ;
        
        $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'];
        
        /* ajax container */ 
        if( !in_array($_POST['twiz_action'], $this->array_action_excluded) ){
        
            $opendiv = '<div id="twiz_container">';
            $closediv = '</div>';
        }
        

        /* save effect */
        if( $saved_id != '' ){

            $saveeffect .= '$("#twiz_list_div_element_'.$saved_id.'").animate({opacity:0}, 300, 
function(){
$("#twiz_list_div_element_'.$saved_id.'").css({"color":"green"});
$("#twiz_list_div_element_'.$saved_id.'").animate({opacity:1}, 300, function(){
});
}); ';
        }
        
        if( (( $action == self::ACTION_SAVE_GROUP ) 
        or ( $action == self::ACTION_COPY_GROUP ) )
        and ( $saved_id != '' ) ) {
            $exportid = $this->getValue($saved_id, self::F_EXPORT_ID);
            $parent_id = $exportid;
        }
        
        /* show element */
        $jsscript_show = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
        $("#twiz_listmenu").css("display", "block"); 
        $(".twiz-status-menu").css("visibility","visible");
        $("#twiz_add_menu").fadeIn("fast");
        $("#twiz_import").fadeIn("fast");
        $("#twiz_export").fadeIn("fast");  
        twiz_parent_id = "'.$parent_id.'";
'.$saveeffect.'     
});
 //]]>
</script>';

        $htmllist = $opendiv.'<table class="twiz-table-list" cellspacing="0"><tbody>';
        $htmllist.= '<tr class="twiz-table-list-tr-h"><td class="twiz-td-v-line"></td><td class="twiz-td-status twiz-table-list-td-h twiz-text-center"><a id="twiz_order_by_'.self::F_STATUS.'">'.__('Status', 'the-welcomizer').'</a>'.$twiz_order_by_status.'</td><td class="twiz-table-list-td-h twiz-text-left twiz-td-element" nowrap="nowrap"><a id="twiz_order_by_'.self::F_LAYER_ID.'">'.__('Element', 'the-welcomizer').'</a>'.$twiz_order_by_element.'</td><td class="twiz-table-list-td-h twiz-text-center twiz-td-event" nowrap="nowrap"><a id="twiz_order_by_'.self::F_ON_EVENT.'">'.__('Event', 'the-welcomizer').'</a>'.$twiz_order_by_on_event.'</td><td class="twiz-table-list-td-h twiz-text-right twiz-td-delay" nowrap="nowrap"><a id="twiz_order_by_'.self::F_START_DELAY.'">'.__('Delay', 'the-welcomizer').'</a>'.$twiz_order_by_delay.'</td><td class="twiz-table-list-td-h twiz-text-right twiz-td-duration" nowrap="nowrap"><a id="twiz_order_by_'.self::F_DURATION.'">'.__('Duration', 'the-welcomizer').'</a>'.$twiz_order_by_duration.'</td><td class="twiz-table-list-td-h  twiz-td-action twiz-text-right" nowrap="nowrap">'.__('Action', 'the-welcomizer').'</td></tr>';
        
        foreach($listarray as $value){
            
            $hide = '' ;
            $toggleimg = '';
            $boldclass = '';
            $borderbggroupclass = '';

            $statushtmlimg = ( $value[self::F_STATUS] == '1' ) ? $this->getHtmlImgStatus($value[self::F_ID], self::STATUS_ACTIVE) : $this->getHtmlImgStatus($value[self::F_ID], self::STATUS_INACTIVE);
            
            /* add a '2x' to the duration if necessary */
            $duration = $this->formatDuration($value[self::F_ID], $value);

            if( $value[self::F_ON_EVENT] != self::EV_MANUAL) {
                $on_event = ( $value[self::F_ON_EVENT] != '' ) ? 'On'.$value[self::F_ON_EVENT] : '-';
            }else{
                $on_event = self::EV_MANUAL;    
            }
            
            $elementype = ($value[self::F_TYPE] == '') ? self::ELEMENT_TYPE_ID : $value[self::F_TYPE];

            if( $action != self::ACTION_FAR_FIND ){
            
              
                if( $value[self::F_TYPE] != self::ELEMENT_TYPE_GROUP ){    
                
                    $exid = $value[self::F_PARENT_ID];
                    
                }else{
                
                    $exid = $value[self::F_EXPORT_ID];
                }
                
                $exid = ( $exid == '' ) ? 'can' :$exid;
            
               if(!isset($this->toggle_option[$this->userid][self::KEY_TOGGLE_GROUP][$exid])) $this->toggle_option[$this->userid][self::KEY_TOGGLE_GROUP][$exid] = '';
                
   
                if( $this->toggle_option[$this->userid][self::KEY_TOGGLE_GROUP][$exid] == '1' ) {
                
                    $hide = '';
                    //$borderbggroupclass = '';
                    $toggleimg = 'minus';
                    $boldclass = ' twiz-bold';
                    
                }else{
    
                    $hide = ( $value[self::F_PARENT_ID] != '' ) ? ' twiz-display-none' : '';
                    $hide = ( $parent_id == $value[self::F_PARENT_ID] ) ? '' : $hide;
                    $toggleimg = ( $parent_id == $value[self::F_EXPORT_ID] ) ? 'minus' : 'plus';
                    $boldclass = ( $parent_id == $value[self::F_EXPORT_ID] ) ? ' twiz-bold' : '';
                }
            }
                    $borderbggroupclass = ( $value[self::F_PARENT_ID] != '' ) ? ' twiz-row-color-3' : '';
            
            if( $value[self::F_TYPE] != self::ELEMENT_TYPE_GROUP ){
            
                $rowcolor = ( $rowcolor == 'twiz-row-color-2' ) ? 'twiz-row-color-1' : 'twiz-row-color-2';
                
                /* the table row */
                $htmllist.= '
        <tr class="twiz-list-tr '.$rowcolor.' '.$value[self::F_PARENT_ID].$hide.'" name="twiz_list_tr_'.$value[self::F_ID].'" id="twiz_list_tr_'.$value[self::F_ID].'"><td class="twiz-td-v-line '.$borderbggroupclass.'"></td><td class="twiz-td-status twiz-text-center" id="twiz_td_status_'.$value[self::F_ID].'">'.$statushtmlimg.'</td><td class="twiz-td-element twiz-text-left"><div id="twiz_list_div_element_'.$value[self::F_ID].'" name="twiz_list_div_element_'.$value[self::F_ID].'">'.$value[self::F_LAYER_ID].'<span class="twiz-green"> - ['.$elementype.']</span></div><div class="twiz-list-tr-action" name="twiz_list_tr_action_'.$value[self::F_ID].'" id="twiz_list_tr_action_'.$value[self::F_ID].'" ><a id="twiz_edit_a_'.$value[self::F_ID].'" name="twiz_edit_a_'.$value[self::F_ID].'" class="twiz-edit">'.__('Edit', 'the-welcomizer').'</a> | <a id="twiz_copy_a_'.$value[self::F_ID].'" name="twiz_copy_a_'.$value[self::F_ID].'" class="twiz-copy">'.__('Copy', 'the-welcomizer').'</a> | <a id="twiz_delete_a_'.$value[self::F_ID].'" name="twiz_delete_a_'.$value[self::F_ID].'" class="twiz-red twiz-delete">'.__('Delete', 'the-welcomizer').'</a></div></td><td class="twiz-td-event twiz-blue twiz-text-center"><div id="twiz_ajax_td_val_on_event_'.$value[self::F_ID].'">'.$on_event.'</div><div id="twiz_ajax_td_loading_on_event_'.$value[self::F_ID].'" name="twiz_ajax_td_loading_on_event_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_on_event_'.$value[self::F_ID].'" name="twiz_ajax_td_edit_on_event_'.$value[self::F_ID].'" class="twiz_ajax_td_edit"></div></td><td class="twiz-td-delay twiz-text-right"><div id="twiz_ajax_td_val_delay_'.$value[self::F_ID].'">'.$value[self::F_START_DELAY].'</div><div id="twiz_ajax_td_loading_delay_'.$value[self::F_ID].'" name="twiz_ajax_td_loading_delay_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_delay_'.$value[self::F_ID].'" name="twiz_ajax_td_edit_delay_'.$value[self::F_ID].'" class="twiz_ajax_td_edit"><input class="twiz-input-focus" type="text" name="twiz_input_delay_'.$value[self::F_ID].'" id="twiz_input_delay_'.$value[self::F_ID].'" value="'.$value[self::F_START_DELAY].'" maxlength="100"/></div></td><td name="twiz_ajax_td_duration_'.$value[self::F_ID].'" id="twiz_ajax_td_duration_'.$value[self::F_ID].'" class="twiz-td-duration twiz-text-right" nowrap="nowrap"><div id="twiz_ajax_td_val_duration_'.$value[self::F_ID].'">'.$duration.'</div><div id="twiz_ajax_td_loading_duration_'.$value[self::F_ID].'" name="twiz_ajax_td_loading_duration_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_duration_'.$value[self::F_ID].'" name="twiz_ajax_td_edit_duration_'.$value[self::F_ID].'" class="twiz_ajax_td_edit"><input class="twiz-input-focus" type="text" name="twiz_input_duration_'.$value[self::F_ID].'" id="twiz_input_duration_'.$value[self::F_ID].'" value="'.$value[self::F_DURATION].'" maxlength="100"/></div></td><td class="twiz-td-action twiz-text-right" nowrap="nowrap"><img id="twiz_edit_'.$value[self::F_ID].'" name="twiz_edit_'.$value[self::F_ID].'" title="'.__('Edit', 'the-welcomizer').'" src="'.$this->pluginUrl.'/images/twiz-edit.gif" height="25" class="twiz-edit" /><img id="twiz_copy_'.$value[self::F_ID].'" name="twiz_copy_'.$value[self::F_ID].'" title="'.__('Copy', 'the-welcomizer').'" src="'.$this->pluginUrl.'/images/twiz-copy.png" height="25" class="twiz-copy" /><img height="25" src="'.$this->pluginUrl.'/images/twiz-delete.gif" id="twiz_delete_'.$value[self::F_ID].'" name="twiz_delete_'.$value[self::F_ID].'" title="'.__('Delete', 'the-welcomizer').'" class="twiz-delete" /></td></tr>';
        
            }else{
             
                $where = "where ".self::F_PARENT_ID." = '".$value[self::F_EXPORT_ID]."'";

                $rowcount = $this->getRowCount( $where );
                $rowcount = ( $rowcount > 1 ) ? $rowcount.' '.__('elements','the-welcomizer') : $rowcount.' '.__('element','the-welcomizer') ;
                
                $rowcolor = 'twiz-row-color-1';
                
                 /* a group */
                $htmllist.= '<tr><td class="twiz-border-bottom" colspan="7"></td></tr>
        <tr class="twiz-list-group-tr '.$rowcolor.'" name="twiz_list_group_tr_'.$value[self::F_EXPORT_ID].'" id="twiz_list_group_tr_'.$value[self::F_EXPORT_ID].'"><td class="twiz-td-v-line '.$borderbggroupclass.'"><div class="twiz-relative"><img id="twiz_group_img_'.$value[self::F_EXPORT_ID].'" name="twiz_group_img_'.$value[self::F_EXPORT_ID].'" src="'.$this->pluginUrl.'/images/twiz-'.$toggleimg.'.gif" width="18" height="18" class="twiz-toggle-group twiz-toggle-img"/></div></td><td class="twiz-td-status twiz-text-center" id="twiz_td_status_'.$value[self::F_ID].'">'.$statushtmlimg.'</td><td class="twiz-td-element twiz-text-left"><div id="twiz_list_div_element_'.$value[self::F_ID].'" name="twiz_list_div_element_'.$value[self::F_ID].'"><a id="twiz_element_a_'.$value[self::F_EXPORT_ID].'" name="twiz_element_a_'.$value[self::F_EXPORT_ID].'" class="twiz-toggle-group'.$boldclass.'">'.$value[self::F_LAYER_ID].'</a></div><div class="twiz-list-tr-action" name="twiz_list_tr_action_'.$value[self::F_EXPORT_ID].'" id="twiz_list_tr_action_'.$value[self::F_EXPORT_ID].'" ><small>'.$rowcount.'</small></div></td><td class="twiz-td-event twiz-blue twiz-text-center">Manually</td><td class="twiz-td-delay twiz-text-right"></td><td name="twiz_ajax_td_duration_'.$value[self::F_ID].'" id="twiz_ajax_td_duration_'.$value[self::F_ID].'" class="twiz-td-duration twiz-text-right" nowrap="nowrap"></td><td class="twiz-td-action twiz-text-right" nowrap="nowrap"><img id="twiz_group_edit_'.$value[self::F_ID].'" name="twiz_group_edit_'.$value[self::F_ID].'" title="'.__('Edit', 'the-welcomizer').'" src="'.$this->pluginUrl.'/images/twiz-edit.gif" height="25" class="twiz-group-edit"/><img id="twiz_group_copy_'.$value[self::F_ID].'" name="twiz_group_copy_'.$value[self::F_ID].'" title="'.__('Copy', 'the-welcomizer').'" src="'.$this->pluginUrl.'/images/twiz-copy.png" height="25" class="twiz-group-copy" /><img height="25" src="'.$this->pluginUrl.'/images/twiz-delete.gif" id="twiz_group_delete_'.$value[self::F_ID].'" name="twiz_group_delete_'.$value[self::F_ID].'" title="'.__('Delete', 'the-welcomizer').'" class="twiz-group-delete" /></td></tr>';
        
            }
        }
         
        $htmllist.= '</tbody></table>'.$closediv.$jsscript_show;
         
        return $htmllist;
    }
    
    private function getRowCount( $where = ''){
        
        global $wpdb;
        
        $sql = "SELECT count(".self::F_ID.") AS rowcount FROM ".$this->table." ".$where;
        $row = $wpdb->get_row($sql, ARRAY_A);
        $rowcount = $row['rowcount'];
        
        return $rowcount;
    }
    
    function export( $section_id = '', $id = '' ){
  
        $sectionname = '';
        $error = '';
        
        if( $id != '' ) {
        
           $where = " WHERE ".self::F_ID." = '".$id."'";
           $id = "_".$id;
        }else{
        
            $where = ($section_id != '') ? " WHERE ".self::F_SECTION_ID." = '".$section_id."'" : " WHERE ".self::F_SECTION_ID." = '".$this->DEFAULT_SECTION[$this->userid]."'";
        }
     
        $listarray = $this->getListArray( $where ); 

        $filedata = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        
        $filedata .= '<TWIZ>'."\n";

        foreach( $listarray as $value ){

              if ( $sectionname == '' ) {
              
                  $myTwizMenu  = new TwizMenu(); 
                  $sectionname = sanitize_title_with_dashes($myTwizMenu->getSectionName($value[self::F_SECTION_ID]));
              }
              
              $filedata .= '<ROW>'."\n";
              
              $count_array = count($this->array_fields);
                    
              /* loop fields array */
              foreach( $this->array_fields as $key ){
              
                  if( $key != self::F_ID ){
             
                     $filedata .= '<'.$this->array_twz_mapping[$key].'>'.$value[$key].'</'.$this->array_twz_mapping[$key].'>'."\n";
                  }
              }
                
              $filedata .= '</ROW>'."\n";
        }

        $filedata .= '</TWIZ>'."\n";
        
        $sectionname = ($sectionname == '') ? $sectionname = $section_id.$id : $sectionname.$id;
        $sectionname =  str_replace(self::DEFAULT_SECTION_ALL_ARTICLES, 'allposts', $sectionname);
       
        $filename = urldecode($sectionname).".".self::EXT_TWIZ;
        $filepath = self::IMPORT_PATH.self::EXPORT_PATH;
        $filepathdir = WP_CONTENT_DIR.$filepath;
        $filefullpathdir = $filepathdir.$filename;
        $filefullpathurl = WP_CONTENT_URL.$filepath.$filename;
 
        if (is_writable($filepathdir)) {

            if (!$handle = fopen($filefullpathdir, 'w')) {
                $error =  __("Cannot open file", 'the-welcomizer').' ('.$filename.')';
                exit;
            }

            if (fwrite($handle, $filedata) === FALSE) {
                $error = __("Cannot write to file", 'the-welcomizer').' ('.$filename.')';
                exit;
            }

            fclose($handle);

        } else {
           $error =  __("You must first create this directory", 'the-welcomizer').':<br>'.$this->export_path_message;
        }
        
        $html = ($error!='')? '<div class="twiz-red">' . $error .'</div>' : ' <a href="'.$filefullpathurl.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'"><img name="twiz_img_download_export" id="twiz_img_download_export" src="'.$this->pluginUrl.'/images/twiz-download.png" /></a><a href="'.$filefullpathurl.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'">'.__('Download file', 'the-welcomizer').'<br>'. $filename .'</a>' ;
        
        return $html;
    }
   
    private function utf8_strlen( $string = '' ) {
    
        $char = strlen($string); 
        $l = 0;
        
        for ( $i = 0; $i < $char; ++$i ){
            
            if ( ( ord($string[$i]) & 0xC0 ) != 0x80 ){ 
            
                ++$l;
            }
        }
        
        return $l;
    }
    
    function delete( $id = '' ){
    
        global $wpdb;
        
        if( $id == '' ){return false;}
         
        $clean = $this->cleanTwizFunction( $id );
        
        $sql = "DELETE from ".$this->table." where ".self::F_ID." = '".$id."';";
        $code = $wpdb->query($sql);


        return $code;

    }        
    
    protected function cleanTwizFunction( $id = '' ){
 
        global $wpdb;
       
        $value = $this->getRow($id);

        $group = ($value[self::F_TYPE] == self::ELEMENT_TYPE_GROUP) ? '_'.self::ELEMENT_TYPE_GROUP : '';
            
        $searchstring = "$(document).twiz".$group."_".$value[self::F_SECTION_ID]."_".str_replace("-","_",sanitize_title_with_dashes($value[self::F_LAYER_ID]))."_".$value[self::F_EXPORT_ID]."();";
            
        // Remove function
        $updatesql = "UPDATE ".$this->table . " SET
         ". self::F_JAVASCRIPT . " = replace(". self::F_JAVASCRIPT . ", '".$searchstring."', '') 
        ,". self::F_EXTRA_JS_A . " = replace(". self::F_EXTRA_JS_A . ", '".$searchstring."', '') 
        ,". self::F_EXTRA_JS_B . " = replace(". self::F_EXTRA_JS_B . ", '".$searchstring."', '')";
        $code = $wpdb->query($updatesql);
        
        return $code;
        
    }
    
    protected function in_multi_array($needle, $haystack){

        $in_multi_array = false;
        
        if(in_array($needle, $haystack)){
        
            $in_multi_array = true;
            
        }else{   
        
            foreach( $haystack as $i => $value ) {
                
                if(is_array($haystack[$i])){
                
                    if($this->in_multi_array($needle, $haystack[$i])) {
                    
                        $in_multi_array = true;
                        
                        break;
                    }
                }
            }
        }
        
        return $in_multi_array;
    } 
    
    function install(){ 
    
        global $wpdb;

        $sql = "CREATE TABLE ".$this->table." (". 
                self::F_ID . " int NOT NULL AUTO_INCREMENT, ". 
                self::F_PARENT_ID . " varchar(13) NOT NULL default '', ". 
                self::F_EXPORT_ID . " varchar(13) NOT NULL default '', ". 
                self::F_SECTION_ID . " varchar(22) NOT NULL default '".self::DEFAULT_SECTION_HOME."', ". 
                self::F_STATUS . " tinyint(3) NOT NULL default 0, ". 
                self::F_TYPE . " varchar(5) NOT NULL default '".self::ELEMENT_TYPE_ID."', ". 
                self::F_LAYER_ID . " varchar(50) NOT NULL default '', ". 
                self::F_ON_EVENT . " varchar(15) NOT NULL default '', ".
                self::F_LOCK_EVENT . " tinyint(3) NOT NULL default 1, ". 
                self::F_LOCK_EVENT_TYPE . " varchar(4) NOT NULL default 'auto', ". 
                self::F_START_DELAY . " varchar(100) NOT NULL default '0', ". 
                self::F_DURATION . " varchar(100) NOT NULL default '0', ". 
                self::F_OUTPUT . " varchar(1) NOT NULL default 'b', ".
                self::F_OUTPUT_POS . " varchar(1) NOT NULL default 'b', ".
                self::F_JAVASCRIPT . " text NOT NULL default '', ". 
                self::F_START_TOP_POS_SIGN . " varchar(1) NOT NULL default '', ". 
                self::F_START_TOP_POS . " int(5) default NULL, ". 
                self::F_START_TOP_POS_FORMAT . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_START_LEFT_POS_SIGN . " varchar(1) NOT NULL default '', ". 
                self::F_START_LEFT_POS . " int(5) default NULL, ". 
                self::F_START_LEFT_POS_FORMAT . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_POSITION . " varchar(8) NOT NULL default '', ". 
                self::F_ZINDEX . " varchar(5) NOT NULL default '', ". 
                self::F_EASING_A . " varchar(20) NOT NULL default 'swing', ". 
                self::F_EASING_B . " varchar(20) NOT NULL default 'swing', ". 
                self::F_MOVE_TOP_POS_SIGN_A . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_TOP_POS_A . " int(5) default NULL, ". 
                self::F_MOVE_TOP_POS_FORMAT_A . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_MOVE_LEFT_POS_SIGN_A . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_LEFT_POS_A . " int(5) default NULL, ". 
                self::F_MOVE_LEFT_POS_FORMAT_A . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_MOVE_TOP_POS_SIGN_B . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_TOP_POS_B . " int(5) default NULL, ". 
                self::F_MOVE_TOP_POS_FORMAT_B . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_MOVE_LEFT_POS_SIGN_B . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_LEFT_POS_B . " int(5) default NULL, ". 
                self::F_MOVE_LEFT_POS_FORMAT_B . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_OPTIONS_A . " text NOT NULL default '', ". 
                self::F_OPTIONS_B . " text NOT NULL default '', ". 
                self::F_EXTRA_JS_A . " text NOT NULL default '', ". 
                self::F_EXTRA_JS_B . " text NOT NULL default '', " .  
                self::F_ROW_LOCKED . " tinyint(3) NOT NULL default 0, " .  
                "PRIMARY KEY (". self::F_ID . ")
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
                
                
        if ( $wpdb->get_var( "show tables like '".$this->table."'" ) != $this->table ) {

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
            dbDelta($sql);
        
            $code = update_option('twiz_db_version', $this->dbVersion);
            $code = update_option('twiz_global_status', '1');
            $code = update_option('twiz_cookie_js_status', false);  
            
            $code = new TwizAdmin(); 
            
            if(!isset($setting_menu[$this->userid])) $setting_menu[$this->userid] = '';
            $setting_menu[$this->userid] = self::DEFAULT_SECTION_HOME ; 
            $code = update_option('twiz_setting_menu', $setting_menu);   
            
            if(!isset($bullet[$this->userid])) $bullet[$this->userid] = '';
            $bullet[$this->userid] = self::LB_ORDER_DOWN ; 
            $code = update_option('twiz_bullet', $bullet);   
        
            if(!isset($twiz_order_by[$this->userid])) $twiz_order_by[$this->userid] = '';       
            $twiz_order_by[$this->userid] =  self::F_ON_EVENT;
            $code = update_option('twiz_order_by',  $twiz_order_by);
            
            if(!isset($this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD])) $this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD] = '';
            $this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD] = 'twiz_far_simple';
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
                
                if( !in_array(self::F_SECTION_ID, $array_describe) ){
                
                    // Add the new field from <= v.1.3.2.3
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_SECTION_ID . " varchar(22) NOT NULL default '".self::DEFAULT_SECTION_HOME."' after ".self::F_ID."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_ON_EVENT, $array_describe) ){    
                
                    // Add the new field from <= v 1.3.5.7
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".self::F_ON_EVENT." varchar(15) NOT NULL default '' after ".self::F_LAYER_ID."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_JAVASCRIPT, $array_describe) ){
                
                    // Add the new field from <= v 1.3.5.8
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_JAVASCRIPT . " text NOT NULL default '' after ".self::F_DURATION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_ZINDEX, $array_describe) ){
                
                    // Add the new field from <= v 1.3.5.8
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_ZINDEX . " varchar(5) NOT NULL default '' after ".self::F_POSITION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_TYPE, $array_describe) ){        
                
                    // Add the new field from <= v 1.3.6.1 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_TYPE . " varchar(5) NOT NULL default '".self::ELEMENT_TYPE_ID."' after ".self::F_STATUS."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_OUTPUT, $array_describe) ){
                
                    // Add the new field from <= v 1.3.6.6
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_OUTPUT . " varchar(1) NOT NULL default 'b' after ".self::F_DURATION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_OUTPUT_POS, $array_describe) ){
                
                    // Add the new field from <= v 1.3.7
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_OUTPUT_POS . " varchar(1) NOT NULL default 'b' after ".self::F_OUTPUT."";
                    $code = $wpdb->query($altersql);
                }
                
              
                /*
                * HOTFIX - Export ID
                *
                * Adds the new field export_id and applies a Hotfix for updating and replacing ids.
                */
                if( !in_array(self::F_EXPORT_ID, $array_describe) ){
                    

                    // Add the new field from <= v 1.3.7.6 
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".  self::F_EXPORT_ID . " varchar(13) NOT NULL default '' after ". self::F_ID ."";
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
                        if( !isset($array_ids[$value[self::F_ID]]) ) $array_ids[$value[self::F_ID]] = '' ;
                        $array_ids[$value[self::F_ID]] = $exportid;
                        
                        // update each row with the unique id and removes section_id.
                        $updatesql = "UPDATE ".$this->table . " SET ".  self::F_EXPORT_ID . " = '".$exportid."'
                        WHERE ". self::F_ID ." = '".$value[self::F_ID]."'";
                        $code = $wpdb->query($updatesql);

                    }
                    
                    // loops the previous temp array
                    foreach ( $array_ids as $t_id => $t_export_id ){
                    
                        // loop all rows again and again
                        foreach ( $rows as $value ){
                        
                            // Replace all current ids. all functions and activations vars included
                            $updatesql = "UPDATE ".$this->table . " SET
                             ". self::F_JAVASCRIPT . " = replace(". self::F_JAVASCRIPT . ", '_".$t_id."', '_".$t_export_id."') 
                            ,". self::F_EXTRA_JS_A . " = replace(". self::F_EXTRA_JS_A . ", '_".$t_id."', '_".$t_export_id."') 
                            ,". self::F_EXTRA_JS_B . " = replace(". self::F_EXTRA_JS_B . ", '_".$t_id."', '_".$t_export_id."') 
                            WHERE ". self::F_ID ." = '".$value[self::F_ID]."'";
                            $code = $wpdb->query($updatesql);
                            
                        }
                    }                    
                }
                
                // Add the new field from <= v 1.3.7.9
                if( !in_array(self::F_START_TOP_POS_FORMAT, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_START_TOP_POS_FORMAT . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_START_TOP_POS."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_START_LEFT_POS_FORMAT, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_START_LEFT_POS_FORMAT . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_START_LEFT_POS."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_MOVE_TOP_POS_FORMAT_A, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_MOVE_TOP_POS_FORMAT_A . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_MOVE_TOP_POS_A."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_MOVE_LEFT_POS_FORMAT_A, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_MOVE_LEFT_POS_FORMAT_A . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_MOVE_LEFT_POS_A."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_MOVE_TOP_POS_FORMAT_B, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_MOVE_TOP_POS_FORMAT_B . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_MOVE_TOP_POS_B."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_MOVE_LEFT_POS_FORMAT_B, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_MOVE_LEFT_POS_FORMAT_B . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_MOVE_LEFT_POS_B."";
                    $code = $wpdb->query($altersql);
                }
                
                //  Add the new field from <= v 1.3.8.5
                if( !in_array(self::F_EASING_A, $array_describe) ){

                    $altersql = "ALTER TABLE ".$this->table 
                    . " ADD ". self::F_EASING_B . " varchar(20) NOT NULL default 'swing' after ".self::F_ZINDEX."," 
                    . " ADD ". self::F_EASING_A . " varchar(20) NOT NULL default 'swing' after ".self::F_ZINDEX."";
                    $code = $wpdb->query($altersql);
                }              
                
                
                //  Alter fields from <= v 1.3.9.8
                $altersql = "ALTER TABLE ".$this->table 
                . " MODIFY ". self::F_START_DELAY . " varchar(100) NOT NULL default '0'," 
                . " MODIFY ". self::F_DURATION . " varchar(100) NOT NULL default '0'";
                $code = $wpdb->query($altersql);  
                            

                // Add the new field from <= v 1.4.4.5 
                if( !in_array(self::F_LOCK_EVENT, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_LOCK_EVENT . " tinyint(3) NOT NULL default 1 after ".self::F_ON_EVENT."";
                    $code = $wpdb->query($altersql);
                }
                
                // Add the new field from <= v 1.4.8.4 
                if( !in_array(self::F_LOCK_EVENT_TYPE, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_LOCK_EVENT_TYPE . " varchar(4) NOT NULL default 'auto' after ".self::F_LOCK_EVENT."";
                    $code = $wpdb->query($altersql);
                }

                // Add the new field from <= v 1.5 
                if( !in_array(self::F_PARENT_ID, $array_describe) ){

                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".  self::F_PARENT_ID . " varchar(13) NOT NULL default '' after ". self::F_ID ."";
                    $code = $wpdb->query($altersql);
                    
                    // Rename the twiz_active variable to twiz_locked
                    $updatesql = "UPDATE ".$this->table . " SET
                     ". self::F_JAVASCRIPT . " = replace(". self::F_JAVASCRIPT . ", 'twiz_active', 'twiz_locked') 
                    ,". self::F_EXTRA_JS_A . " = replace(". self::F_EXTRA_JS_A . ", 'twiz_active', 'twiz_locked') 
                    ,". self::F_EXTRA_JS_B . " = replace(". self::F_EXTRA_JS_B . ", 'twiz_active', 'twiz_locked')";
                    $code = $wpdb->query($updatesql);
                    
                }
                if( !in_array(self::F_ROW_LOCKED, $array_describe) ){                
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".  self::F_ROW_LOCKED . " tinyint(3) NOT NULL default 0 after ". self::F_EXTRA_JS_B ."";
                    $code = $wpdb->query($altersql);
                }
                
                
                // Bullet menu from <= v 1.5 
                $bullet = get_option('twiz_bullet'); 
                $bullet = ( !is_array($bullet) ) ? '' : $bullet;
                
                if( $bullet == '' ) {
                
                    $bullet[$this->userid] = self::LB_ORDER_UP ; // same setting as before
                    $code = update_option('twiz_bullet', $bullet);      
                
                    // Plus admin & toggle key changes from <= v 1.5 
                    if(!isset($this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD])) $this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD] = '';

                    if(!isset($this->admin_option[self::KEY_PREFERED_METHOD])){
                    
                        $this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD] =  'twiz_far_simple';
                        
                    }else{
                    
                        $this->toggle_option[$this->userid][self::KEY_PREFERED_METHOD] = $this->admin_option[self::KEY_PREFERED_METHOD];
                        $this->admin_option[self::KEY_PREFERED_METHOD] = '';
                        unset($this->admin_option[self::KEY_PREFERED_METHOD]);
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
                    $twiz_order_by[$this->userid] = ($twiz_order_by_old != '') ? $twiz_order_by_old : self::F_ON_EVENT;
                    $code = update_option('twiz_order_by', $twiz_order_by);
                }    
                
                // from <= v 1.5.5
                
                if(!isset($this->admin_option[self::KEY_REGISTER_JQUERY_TRANSIT])) $this->admin_option[self::KEY_REGISTER_JQUERY_TRANSIT] = '';
                if(!isset($this->admin_option[self::KEY_EXTRA_EASING])) $this->admin_option[self::KEY_EXTRA_EASING] = '';
                
                if( ( $this->admin_option[self::KEY_REGISTER_JQUERY_TRANSIT] == '1' )
                and ( $this->admin_option[self::KEY_EXTRA_EASING] != '1' ) ){
                
                    $this->admin_option[self::KEY_EXTRA_EASING] = '1';
                    $code = update_option('twiz_admin', $this->admin_option);
                }
                
                // from <= v 1.5.9
                $updatesql = "UPDATE ".$this->table . " SET ".  self::F_PARENT_ID . " = ''
                WHERE ". self::F_PARENT_ID ." LIKE 'twiz%'";
                $code = $wpdb->query($updatesql);
                        
                // option cookie js 
                $twiz_cookie_js_status = get_option('twiz_cookie_js_status'); 
                
                if( $twiz_cookie_js_status == '' ) {
                
                    $code = update_option('twiz_cookie_js_status', false); 
                }                
                 
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
    
    protected function import( $sectionid = self::DEFAULT_SECTION_HOME ){
    
        $filearray = $this->getFileDirectory(array(self::EXT_TWZ, self::EXT_TWIZ, self::EXT_XML), WP_CONTENT_DIR.self::IMPORT_PATH);
        
        foreach( $filearray as $filename ){
            
            if( $code = $this->importData($filename, $sectionid) ){
 
                return true;
            }
        }
        
        return true;
    }
    
    private function importData( $filename = '', $sectionid = self::DEFAULT_SECTION_HOME ){
 
        /* full file path */
        $file = WP_CONTENT_DIR.self::IMPORT_PATH.$filename;
        $rows = '';
        if ( @file_exists($file) ) {
        
            if( $twz = @simplexml_load_file($file) ){

               /* flip array mapping value to match */
               $reverse_array_twz_mapping = array_flip($this->array_twz_mapping);
               
                /* loop xml entities */              
                foreach( $twz->children() as $twzrow ) { 
                
                    $row = array();
                    $row[self::F_SECTION_ID] = $sectionid; 
                    
                    foreach( $twzrow->children() as $twzfield ) {
                    
                        $fieldname = '';
                        $fieldvalue = '';
                        
                        $fieldname = strtr($twzfield->getName(), $reverse_array_twz_mapping);

                        if( $fieldname != "" ) {                        

                            /* get the real name of the field */
                            $fieldvalue = $twzfield;

                            /* build record array */
                            $row[$fieldname] = $fieldvalue;
                        }
                    }
                    $rows[] = $row;
                }
                if(count($rows > 0 )){
                    /* insert row  */
                    if( !$code = $this->importInsert($rows, $sectionid) ){
                        
                        return false;
                    }
                }
                /* imported */     
                return  true;
            }
        }
        
        return false;
    }
    
    private function importInsert( $rows = array(), $newsectionid = self::DEFAULT_SECTION_HOME  ){
        
        global $wpdb;
        
        $newrows = '';
        $tempdata = '';
        $updatesql = '';
        
        foreach( $rows as $data ){
        
            usleep(100000);
            
            $exportid = uniqid();
            
            $data[self::F_EXPORT_ID] = ($data[self::F_EXPORT_ID]=='')? $exportid : $data[self::F_EXPORT_ID];
            
            $exportidExists = $this->ExportidExists( $data[self::F_EXPORT_ID] );
            
            if( !isset($data[self::F_EXPORT_ID.'_old']) ) $data[self::F_EXPORT_ID.'_old'] = '';
            
            if( $exportidExists == true ){
                
                // New values
                $data[self::F_EXPORT_ID.'_old'] = $data[self::F_EXPORT_ID];
                $data[self::F_EXPORT_ID] = $exportid;
            }
           
            $newrows['key_'.$data[self::F_EXPORT_ID]] = $data;
            $tempdata['key_'.$data[self::F_EXPORT_ID]] = $data;
        }
        
        $newrows = ( !is_array($newrows) ) ? array() : $newrows;
         
        foreach( $newrows as $data ){ 
                      
            foreach( $newrows as $exportid => $newdata ){
            
                if($data[self::F_EXPORT_ID.'_old'] !=''){ 

                    $newdata[self::F_JAVASCRIPT] = str_replace($data[self::F_EXPORT_ID.'_old'], $data[self::F_EXPORT_ID], $newdata[self::F_JAVASCRIPT]);
                    $newdata[self::F_EXTRA_JS_A] = str_replace($data[self::F_EXPORT_ID.'_old'], $data[self::F_EXPORT_ID], $newdata[self::F_EXTRA_JS_A]);
                    $newdata[self::F_EXTRA_JS_B] = str_replace($data[self::F_EXPORT_ID.'_old'], $data[self::F_EXPORT_ID], $newdata[self::F_EXTRA_JS_B]); 
 
                    $tempdata[$exportid] = $newdata;
                }
                $newrows = $tempdata;
            }
        }

        $rows = ( !is_array($tempdata) ) ? array() : $tempdata;
       
        foreach( $rows as $data ){
        
            /* Fields added after */
            if( !isset($data[self::F_ON_EVENT]) ) $data[self::F_ON_EVENT] = '';
            if( !isset($data[self::F_LOCK_EVENT]) ) $data[self::F_LOCK_EVENT] = '';
            if( !isset($data[self::F_LOCK_EVENT_TYPE]) ) $data[self::F_LOCK_EVENT_TYPE] = '';
            if( !isset($data[self::F_JAVASCRIPT]) ) $data[self::F_JAVASCRIPT] = '';
            if( !isset($data[self::F_ZINDEX]) ) $data[self::F_ZINDEX] = '';
            if( !isset($data[self::F_TYPE]) ) $data[self::F_TYPE] = '';
            if( !isset($data[self::F_OUTPUT]) ) $data[self::F_OUTPUT] = '';
            if( !isset($data[self::F_OUTPUT_POS]) ) $data[self::F_OUTPUT_POS] = '';
            if( !isset($data[self::F_PARENT_ID]) ) $data[self::F_PARENT_ID] = '';
            if( !isset($data[self::F_EXPORT_ID]) ) $data[self::F_EXPORT_ID] = '';
            if( !isset($data[self::F_SECTION_ID]) ) $data[self::F_SECTION_ID] = '';
            if( !isset($data[self::F_START_TOP_POS_FORMAT]) ) $data[self::F_START_TOP_POS_FORMAT] = '';
            if( !isset($data[self::F_START_LEFT_POS_FORMAT]) ) $data[self::F_START_LEFT_POS_FORMAT] = '';
            if( !isset($data[self::F_MOVE_TOP_POS_FORMAT_A]) ) $data[self::F_MOVE_TOP_POS_FORMAT_A] = '';
            if( !isset($data[self::F_MOVE_LEFT_POS_FORMAT_A]) ) $data[self::F_MOVE_LEFT_POS_FORMAT_A] = '';
            if( !isset($data[self::F_MOVE_TOP_POS_FORMAT_B]) ) $data[self::F_MOVE_TOP_POS_FORMAT_B] = '';
            if( !isset($data[self::F_MOVE_LEFT_POS_FORMAT_B]) ) $data[self::F_MOVE_LEFT_POS_FORMAT_B] = '';
            if( !isset($data[self::F_EASING_A]) ) $data[self::F_EASING_A] = '';
            if( !isset($data[self::F_EASING_B]) ) $data[self::F_EASING_B] = '';
            
            $twiz_move_top_pos_a  = esc_attr(trim($data[self::F_MOVE_TOP_POS_A]));
            $twiz_move_left_pos_a = esc_attr(trim($data[self::F_MOVE_LEFT_POS_A]));
            $twiz_move_top_pos_b  = esc_attr(trim($data[self::F_MOVE_TOP_POS_B]));
            $twiz_move_left_pos_b = esc_attr(trim($data[self::F_MOVE_LEFT_POS_B]));
            $twiz_start_top_pos   = esc_attr(trim($data[self::F_START_TOP_POS]));
            $twiz_start_left_pos  = esc_attr(trim($data[self::F_START_LEFT_POS]));
            
            $twiz_move_top_pos_a  = ($twiz_move_top_pos_a=='') ? 'NULL' : $twiz_move_top_pos_a;
            $twiz_move_left_pos_a = ($twiz_move_left_pos_a=='') ? 'NULL' : $twiz_move_left_pos_a;
            $twiz_move_top_pos_b  = ($twiz_move_top_pos_b=='') ? 'NULL' : $twiz_move_top_pos_b;
            $twiz_move_left_pos_b = ($twiz_move_left_pos_b=='') ? 'NULL' : $twiz_move_left_pos_b;
            $twiz_start_top_pos   = ($twiz_start_top_pos=='') ? 'NULL' : $twiz_start_top_pos;
            $twiz_start_left_pos  = ($twiz_start_left_pos=='') ? 'NULL' : $twiz_start_left_pos;
          
            $twiz_javascript = str_replace("\\", "\\\\" , $data[self::F_JAVASCRIPT]);
            $twiz_extra_js_a = str_replace("\\", "\\\\" , $data[self::F_EXTRA_JS_A]);
            $twiz_extra_js_b = str_replace("\\", "\\\\" , $data[self::F_EXTRA_JS_B]);
            
            $twiz_status = esc_attr(trim($data[self::F_STATUS]));
            $twiz_lock_event = esc_attr(trim($data[self::F_LOCK_EVENT]));
            $twiz_lock_event_type = esc_attr(trim($data[self::F_LOCK_EVENT_TYPE]));
            $twiz_start_delay = esc_attr(trim($data[self::F_START_DELAY]));
            $twiz_duration = esc_attr(trim($data[self::F_DURATION]));
            
            $twiz_status = ( $twiz_status == '' ) ? '0' : $twiz_status;
            $twiz_lock_event = ( ( $twiz_lock_event == '' ) and ( ( $data[self::F_ON_EVENT] !='') and ( $data[self::F_ON_EVENT] !='Manually') ) ) ? '1' : $twiz_lock_event; // old format locked by default
            $twiz_lock_event = ( $twiz_lock_event == '' ) ? '0' : $twiz_lock_event;
            $twiz_lock_event_type = ( $twiz_lock_event_type == '' ) ? 'auto' : $twiz_lock_event_type;
            $twiz_start_delay = ( $twiz_start_delay == '' ) ? '0' : $twiz_start_delay;
            $twiz_duration = ( $twiz_duration == '' ) ? '0' : $twiz_duration;
            
            $group = '';
    
            // replace section id
            $twiz_javascript = str_replace("$(document).twiz_group_".$data[self::F_SECTION_ID]."_", "$(document).twiz_group_".$newsectionid."_", $data[self::F_JAVASCRIPT]);
            $twiz_extra_js_a = str_replace("$(document).twiz_group_".$data[self::F_SECTION_ID]."_", "$(document).twiz_group_".$newsectionid."_", $data[self::F_EXTRA_JS_A]);
            $twiz_extra_js_b = str_replace("$(document).twiz_group_".$data[self::F_SECTION_ID]."_", "$(document).twiz_group_".$newsectionid."_", $data[self::F_EXTRA_JS_B]);  
            
            // replace section id part2
            $twiz_javascript = str_replace("$(document).twiz_".$data[self::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $twiz_javascript);
            $twiz_extra_js_a = str_replace("$(document).twiz_".$data[self::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $twiz_extra_js_a);
            $twiz_extra_js_b = str_replace("$(document).twiz_".$data[self::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $twiz_extra_js_b);
            
            // default output pos for older export files. b r default, because no backward v to check
            $data[self::F_OUTPUT] = ($data[self::F_OUTPUT] == '')? 'b' : $data[self::F_OUTPUT];
            $data[self::F_OUTPUT_POS] = ($data[self::F_OUTPUT_POS] == '') ? 'r' : $data[self::F_OUTPUT_POS];
            $data[self::F_START_TOP_POS_FORMAT] = ($data[self::F_START_TOP_POS_FORMAT] == '') ? self::FORMAT_PIXEL : $data[self::F_START_TOP_POS_FORMAT];
            $data[self::F_START_LEFT_POS_FORMAT] = ($data[self::F_START_LEFT_POS_FORMAT] == '') ? self::FORMAT_PIXEL : $data[self::F_START_LEFT_POS_FORMAT];
            $data[self::F_MOVE_TOP_POS_FORMAT_A] = ($data[self::F_MOVE_TOP_POS_FORMAT_A] == '') ? self::FORMAT_PIXEL : $data[self::F_MOVE_TOP_POS_FORMAT_A];
            $data[self::F_MOVE_LEFT_POS_FORMAT_A] = ($data[self::F_MOVE_LEFT_POS_FORMAT_A] == '') ? self::FORMAT_PIXEL : $data[self::F_MOVE_LEFT_POS_FORMAT_A];
            $data[self::F_MOVE_TOP_POS_FORMAT_B] = ($data[self::F_MOVE_TOP_POS_FORMAT_B] == '') ? self::FORMAT_PIXEL : $data[self::F_MOVE_TOP_POS_FORMAT_B];
            $data[self::F_MOVE_LEFT_POS_FORMAT_B] = ($data[self::F_MOVE_LEFT_POS_FORMAT_B] == '') ? self::FORMAT_PIXEL : $data[self::F_MOVE_LEFT_POS_FORMAT_B];
            
            $data[self::F_EASING_A] = ($data[self::F_EASING_A] == '')? 'swing' : $data[self::F_EASING_A];
            $data[self::F_EASING_B] = ($data[self::F_EASING_B] == '')? 'swing' : $data[self::F_EASING_B];
  
                    
            $sql = "INSERT INTO ".$this->table." 
                 (".self::F_PARENT_ID."
                 ,".self::F_EXPORT_ID."
                 ,".self::F_SECTION_ID."
                 ,".self::F_STATUS."
                 ,".self::F_TYPE."
                 ,".self::F_LAYER_ID."
                 ,".self::F_ON_EVENT."
                 ,".self::F_LOCK_EVENT."
                 ,".self::F_LOCK_EVENT_TYPE."
                 ,".self::F_START_DELAY."
                 ,".self::F_DURATION."
                 ,".self::F_OUTPUT."
                 ,".self::F_OUTPUT_POS."
                 ,".self::F_JAVASCRIPT."
                 ,".self::F_START_TOP_POS_SIGN."
                 ,".self::F_START_TOP_POS."
                 ,".self::F_START_TOP_POS_FORMAT."
                 ,".self::F_START_LEFT_POS_SIGN."
                 ,".self::F_START_LEFT_POS."    
                 ,".self::F_START_LEFT_POS_FORMAT."    
                 ,".self::F_POSITION."    
                 ,".self::F_ZINDEX."    
                 ,".self::F_EASING_A."  
                 ,".self::F_EASING_B."               
                 ,".self::F_MOVE_TOP_POS_SIGN_A."
                 ,".self::F_MOVE_TOP_POS_A."
                 ,".self::F_MOVE_TOP_POS_FORMAT_A."
                 ,".self::F_MOVE_LEFT_POS_SIGN_A."
                 ,".self::F_MOVE_LEFT_POS_A."
                 ,".self::F_MOVE_LEFT_POS_FORMAT_A."
                 ,".self::F_MOVE_TOP_POS_SIGN_B."
                 ,".self::F_MOVE_TOP_POS_B."
                 ,".self::F_MOVE_TOP_POS_FORMAT_B."
                 ,".self::F_MOVE_LEFT_POS_SIGN_B."
                 ,".self::F_MOVE_LEFT_POS_B."
                 ,".self::F_MOVE_LEFT_POS_FORMAT_B."
                 ,".self::F_OPTIONS_A."
                 ,".self::F_OPTIONS_B."
                 ,".self::F_EXTRA_JS_A."
                 ,".self::F_EXTRA_JS_B."       
                 ,".self::F_ROW_LOCKED."       
                 )VALUES('".esc_attr(trim($data[self::F_PARENT_ID]))."'
                 ,'".esc_attr(trim($data[self::F_EXPORT_ID]))."'
                 ,'".$newsectionid."'
                 ,'".$twiz_status."'
                 ,'".esc_attr(trim($data[self::F_TYPE]))."'
                 ,'".esc_attr(trim($data[self::F_LAYER_ID]))."'
                 ,'".esc_attr(trim($data[self::F_ON_EVENT]))."'             
                 ,'".$twiz_lock_event."'             
                 ,'".$twiz_lock_event_type."'             
                 ,'".$twiz_start_delay."'
                 ,'".$twiz_duration."'
                 ,'".esc_attr(trim($data[self::F_OUTPUT]))."'
                 ,'".esc_attr(trim($data[self::F_OUTPUT_POS]))."'
                 ,'".esc_attr($twiz_javascript)."'    
                 ,'".esc_attr(trim($data[self::F_START_TOP_POS_SIGN]))."'    
                 ,".$twiz_start_top_pos."
                 ,'".esc_attr(trim($data[self::F_START_TOP_POS_FORMAT]))."'   
                 ,'".esc_attr(trim($data[self::F_START_LEFT_POS_SIGN]))."'    
                 ,".$twiz_start_left_pos."
                 ,'".esc_attr(trim($data[self::F_START_LEFT_POS_FORMAT]))."'    
                 ,'".esc_attr(trim($data[self::F_POSITION]))."'
                 ,'".esc_attr(trim($data[self::F_ZINDEX]))."'                      
                 ,'".esc_attr(trim($data[self::F_EASING_A]))."'  
                 ,'".esc_attr(trim($data[self::F_EASING_B]))."'               
                 ,'".esc_attr(trim($data[self::F_MOVE_TOP_POS_SIGN_A]))."'    
                 ,".$twiz_move_top_pos_a."
                 ,'".esc_attr(trim($data[self::F_MOVE_TOP_POS_FORMAT_A]))."'    
                 ,'".esc_attr(trim($data[self::F_MOVE_LEFT_POS_SIGN_A]))."'    
                 ,".$twiz_move_left_pos_a."
                 ,'".esc_attr(trim($data[self::F_MOVE_LEFT_POS_FORMAT_A]))."'    
                 ,'".esc_attr(trim($data[self::F_MOVE_TOP_POS_SIGN_B]))."'                     
                 ,".$twiz_move_top_pos_b."
                 ,'".esc_attr(trim($data[self::F_MOVE_TOP_POS_FORMAT_B]))."'                     
                 ,'".esc_attr(trim($data[self::F_MOVE_LEFT_POS_SIGN_B]))."'    
                 ,".$twiz_move_left_pos_b."
                 ,'".esc_attr(trim($data[self::F_MOVE_LEFT_POS_FORMAT_B]))."'    
                 ,'".esc_attr(trim($data[self::F_OPTIONS_A]))."'                             
                 ,'".esc_attr(trim($data[self::F_OPTIONS_B]))."'
                 ,'".esc_attr($twiz_extra_js_a)."'                             
                 ,'".esc_attr($twiz_extra_js_b)."'                 
                 ,'3'                
                 );"; // Lock
                
                $code = $wpdb->query($sql);
                
                $exportidExists = $this->ExportidExists( $data[self::F_EXPORT_ID.'_old'] );

                if( $exportidExists == true ){

                    // Update new parent_id
                    $updatesql[] = "UPDATE ".$this->table . " SET
                    ". self::F_PARENT_ID . " = '".$data[self::F_EXPORT_ID] ."' 
                    WHERE ". self::F_PARENT_ID . " = '".$data[self::F_EXPORT_ID.'_old']."' 
                    AND ". self::F_ROW_LOCKED . " = '3';";
                }                
            }
                     
            // Update new parent_id
            $updatesql = ( !is_array($updatesql) ) ? array() : $updatesql;
            foreach( $updatesql as $sql ){ 
                $code = $wpdb->query($sql);
            }
           
            $code = $this->UnlockRows(3);
 
            return true;
    }

    protected function UnlockRows( $value = '' ){
    
        global $wpdb;
        
        $unlockrow = "UPDATE ".$this->table . " SET ". self::F_ROW_LOCKED. " = 0 
                      WHERE ". self::F_ROW_LOCKED . " = ".$value."";
                      
        $code = $wpdb->query($unlockrow);
        
        return $code;
    }
    
    function formatDuration( $id = '', $data = null ){
        
        $data = '';
        
        if($id==''){return false;}
       
        $data = ($data==null) ? $this->getRow($id) : $data;
        
        $duration = (($data[self::F_MOVE_TOP_POS_B] !='' ) or( $data[self::F_MOVE_LEFT_POS_B] !='' ) or( $data[self::F_OPTIONS_B] !='' ) or( $data[self::F_EXTRA_JS_B] !='' )) ? $data[self::F_DURATION].'<span class="twiz-green"> x2</span>' : $data[self::F_DURATION];
        
        return $duration;
    }
    
    function getSkinsDirectory(){
        
        $dirarray = '';
        
        if ( $handle = @opendir($this->pluginDir .self::SKIN_PATH ) ) {
        
            while ( false !== ( $file = readdir($handle) ) ) {
            
                if ( $file != "." && $file != ".." ) {
                
                    $dirarray[] = $file;
                
                }
            }
            
            closedir($handle);
        }
        
        if( !is_array($dirarray) ){ $dirarray = array(); }
         
        return $dirarray;
    }
    
    protected function getFileDirectory( $extensions = array(self::EXT_TWZ, self::EXT_TWIZ, self::EXT_XML) , $path = ''){
        
        $filearray = '';
        $path = ( $path == '' ) ? WP_CONTENT_DIR.self::IMPORT_PATH : $path;
    
        if ( $handle = @opendir( $path ) ) {
          
            while ( false !== ( $file = readdir($handle) ) ) {
            
               $pathinfo = pathinfo($path.$file);
               if( !isset($pathinfo['extension']) ) $pathinfo['extension'] = '' ;
               $ext = $pathinfo['extension'];
        
                if ( ( $file != "." && $file != ".." ) && (in_array(strtolower($ext), $extensions) ) ) {
                
              
                    $filearray[] = $file;
                }
            }
            
            closedir($handle);
        }
        if( !is_array($filearray) ){ $filearray = array(); }
         
        return $filearray;
    }
       
    function hasStartingConfigs( $value = array() ){

        if(($value[self::F_START_TOP_POS]!='') or ($value[self::F_START_LEFT_POS]!='')
         or($value[self::F_ZINDEX]!='') or ($value[self::F_JAVASCRIPT]!='')
         or ($value[self::F_POSITION]!='')){
         
             return true;
        }
        
        return false;
    
    }
    
    function hasMovements( $value = array() ){

        if(($value[self::F_OPTIONS_A]!='') or ($value[self::F_EXTRA_JS_A]!='')
         or($value[self::F_OPTIONS_B]!='') or ($value[self::F_EXTRA_JS_B]!='')
         or($value[self::F_MOVE_TOP_POS_A]!='') or($value[self::F_MOVE_LEFT_POS_A]!='')
         or($value[self::F_MOVE_TOP_POS_B]!='') or ($value[self::F_MOVE_LEFT_POS_B]!='')){
         
             return true;
        }
        
        return false;
    
    }
    
    function hasSomething( $value = array() ){

        if(($value[self::F_OPTIONS_A]!='') or ($value[self::F_EXTRA_JS_A]!='')
         or($value[self::F_OPTIONS_B]!='') or ($value[self::F_EXTRA_JS_B]!='')
         or($value[self::F_MOVE_TOP_POS_A]!='') or($value[self::F_MOVE_LEFT_POS_A]!='')
         or($value[self::F_MOVE_TOP_POS_B]!='') or ($value[self::F_MOVE_LEFT_POS_B]!='')
         or (($value[self::F_JAVASCRIPT]!='') and ($value[self::F_OUTPUT] == 'a' ))){
         
             return true;
        }
        
        return false;
    
    }
    
    private function getDirectionalImage( $data = '', $ab = ''){
    
        if($data==''){return '';}
        if($ab==''){return '';}
        $direction = '';
        
        if((($data['move_top_pos_sign_'.$ab] != '') and ($data['move_left_pos_'.$ab] == ''))
        or (($data['move_left_pos_sign_'.$ab] != '') and ($data['move_top_pos_'.$ab] == ''))
        or (($data['move_left_pos_sign_'.$ab] != '') and ($data['move_top_pos_'.$ab] != '')
        and ($data['move_top_pos_sign_'.$ab] != '') and ($data['move_left_pos_'.$ab] != ''))
        ){
        
            /* true super fast logical switch */
            switch(true){
            
                case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '-') 
                     and ($data['move_left_pos_'.$ab]== '') ): // N
                     
                     $direction = self::DIMAGE_N;
                     
                    break;
                    
                case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '-') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // NE
                     
                    $direction = self::DIMAGE_NE;
                
                    break;       
                    
                case (($data['move_top_pos_'.$ab]== '') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // E
                     
                    $direction = self::DIMAGE_E;
                
                    break;    
                    
                case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '+') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // SE
                     
                    $direction = self::DIMAGE_SE;
                
                    break;  
                    
               case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '+') 
                     and ($data['move_left_pos_'.$ab]== '') ): // S
                     
                    $direction = self::DIMAGE_S;                 
                
                    break;  
                    
               case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '+') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // SW
                     
                     $direction = self::DIMAGE_SW;
                
                    break; 
                    
               case (($data['move_top_pos_'.$ab]== '') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // W
                     
                     $direction = self::DIMAGE_W;
                
                    break;      
                    
               case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '-') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // NW
                     
                    $direction = self::DIMAGE_NW;
                
                    break;     
            }
        }
        
        if($direction!=''){ 

            return '<div class="twiz-arrow twiz-arrow-'.$direction.'"></div>';
            
        }else{
        
            return '';
            
        }
    }

    function getHtmlForm( $id = '', $action = self::ACTION_NEW, $section_id = '', $parent_id = '' ){ 
    
        $data = '';        
        $opendiv = '';
        $closediv = '';
        $hideimport = '';
        $toggleoptions = '';
        if(!isset($_POST['twiz_action'])) $_POST['twiz_action'] = '';  
        if(!isset($_POST['twiz_stay'])) $_POST['twiz_stay'] = '';   
        $twiz_stay = esc_attr(trim($_POST['twiz_stay']));
                
        if($id!=''){
            
            if(!$data = $this->getRow($id)){return false;}
            
            if( $action == self::ACTION_COPY ){
            
                $hideimport = '$("#twiz_export").fadeOut("fast");';
            }
        }else{
        
            $hideimport = '$("#twiz_export").fadeOut("fast");';
        }
        
        $jsscript_open = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {';
 
        $jsscript_close = '});
 //]]>
</script>';
        
        /* Toggle starting config */
        $jsscript_starting_config = '$("#twiz_tr_starting_config").toggle();';
        
        /* Toggle More Options */
        $jsscript_moreoptions = '$(".twiz-table-more-options").toggle();';
        
        /* hide element */
        $jsscript_hide = '$("#twiz_far_matches").html("");
$("#twiz_listmenu").css("display", "none"); 
$("#twiz_right_panel").fadeOut("fast");
$("#twiz_add_menu").fadeIn("fast");
$("#twiz_import").fadeIn("fast");
$("#qq_upload_list li").remove(); 
$("#twiz_export_url").html(""); 
        '.$hideimport;
         
        /* Text Area auto expand */
        $jsscript_autoexpand = '
textarea = new Object();
textarea.expand = function(textbox){
    twizsizeOrig(textbox);
    textbox.style.height = (textbox.scrollHeight + 20) + "px";
    textbox.style.width = (textbox.scrollWidth + 8) + "px";
} 
function twizsizeOrig(textbox){
    $(textbox).css({"height":"50px", "width" : "230px"});
}
$("textarea[name^=twiz_javascript]").blur(function (){
   twizsizeOrig(this);
});
$("textarea[name^=twiz_options]").blur(function (){
   twizsizeOrig(this);
});
 $("textarea[name^=twiz_extra]").blur(function (){
   twizsizeOrig(this);
});';

        /* ajax container */ 
        if(!in_array($_POST['twiz_action'], $this->array_action_excluded)){
             $opendiv = '<div id="twiz_container">';
             $closediv = '</div>';
        }

        if( !isset($data[self::F_EXPORT_ID]) ) $data[self::F_EXPORT_ID] = '';
        if( !isset($data[self::F_PARENT_ID]) ) $data[self::F_PARENT_ID] = '';
        if( !isset($data[self::F_OPTIONS_A]) ) $data[self::F_OPTIONS_A] = '';
        if( !isset($data[self::F_OPTIONS_B]) ) $data[self::F_OPTIONS_B] = '';
        if( !isset($data[self::F_EXTRA_JS_A]) ) $data[self::F_EXTRA_JS_A] = '';
        if( !isset($data[self::F_EXTRA_JS_B]) ) $data[self::F_EXTRA_JS_B] = '';
        if( !isset($data[self::F_STATUS]) ) $data[self::F_STATUS] = '';
        if( !isset($data[self::F_POSITION]) ) $data[self::F_POSITION] = '';
        if( !isset($data[self::F_ZINDEX]) ) $data[self::F_ZINDEX] = '';
        if( !isset($data[self::F_TYPE]) ) $data[self::F_TYPE] = '';
        if( !isset($data[self::F_EASING_A]) ) $data[self::F_EASING_A] = '';
        if( !isset($data[self::F_EASING_B]) ) $data[self::F_EASING_B] = '';
        if( !isset($data[self::F_START_TOP_POS_SIGN]) ) $data[self::F_START_TOP_POS_SIGN] = '';
        if( !isset($data[self::F_START_LEFT_POS_SIGN]) ) $data[self::F_START_LEFT_POS_SIGN] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_SIGN_A]) ) $data[self::F_MOVE_TOP_POS_SIGN_A] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_SIGN_B]) ) $data[self::F_MOVE_TOP_POS_SIGN_B] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_SIGN_A]) ) $data[self::F_MOVE_LEFT_POS_SIGN_A] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_SIGN_B]) ) $data[self::F_MOVE_LEFT_POS_SIGN_B] = '';
        if( !isset($data[self::F_LAYER_ID]) ) $data[self::F_LAYER_ID] = '';
        if( !isset($data[self::F_START_DELAY]) ) $data[self::F_START_DELAY] = '';
        if( !isset($data[self::F_ON_EVENT]) ) $data[self::F_ON_EVENT] = '';
        if( !isset($data[self::F_LOCK_EVENT]) ) $data[self::F_LOCK_EVENT] = '';
        if( !isset($data[self::F_LOCK_EVENT_TYPE]) ) $data[self::F_LOCK_EVENT_TYPE] = '';
        if( !isset($data[self::F_DURATION]) ) $data[self::F_DURATION] = '';
        if( !isset($data[self::F_OUTPUT]) ) $data[self::F_OUTPUT] = '';
        if( !isset($data[self::F_OUTPUT_POS]) ) $data[self::F_OUTPUT_POS] = '';
        if( !isset($data[self::F_JAVASCRIPT]) ) $data[self::F_JAVASCRIPT] = '';
        if( !isset($data[self::F_START_TOP_POS]) ) $data[self::F_START_TOP_POS] = '';
        if( !isset($data[self::F_START_LEFT_POS]) ) $data[self::F_START_LEFT_POS] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_A]) ) $data[self::F_MOVE_TOP_POS_A] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_B]) ) $data[self::F_MOVE_TOP_POS_B] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_A]) ) $data[self::F_MOVE_LEFT_POS_A] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_B]) ) $data[self::F_MOVE_LEFT_POS_B] = '';
        if( !isset($data[self::F_START_TOP_POS_FORMAT]) ) $data[self::F_START_TOP_POS_FORMAT] = '';
        if( !isset($data[self::F_START_LEFT_POS_FORMAT]) ) $data[self::F_START_LEFT_POS_FORMAT] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_FORMAT_A]) ) $data[self::F_MOVE_TOP_POS_FORMAT_A] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_FORMAT_B]) ) $data[self::F_MOVE_TOP_POS_FORMAT_B] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_FORMAT_A]) ) $data[self::F_MOVE_LEFT_POS_FORMAT_A] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_FORMAT_B]) ) $data[self::F_MOVE_LEFT_POS_FORMAT_B] = '';
    
        if( !isset($twiz_position[self::POS_ABSOLUTE] ) ) $twiz_position[self::POS_ABSOLUTE] = '';
        if( !isset($twiz_position[self::POS_RELATIVE]) ) $twiz_position[self::POS_RELATIVE] = '';
        if( !isset($twiz_position[self::POS_FIXED]) ) $twiz_position[self::POS_FIXED] = '';
        if( !isset($twiz_position[self::POS_STATIC]) ) $twiz_position[self::POS_STATIC] = '';
        
        if( !isset($twiz_start_top_pos_sign['nothing'] ) ) $twiz_start_top_pos_sign['nothing'] = '';
        if( !isset($twiz_start_top_pos_sign['-']) ) $twiz_start_top_pos_sign['-'] = '';
        if( !isset($twiz_start_left_pos_sign['nothing'] ) ) $twiz_start_left_pos_sign['nothing'] = '';
        if( !isset($twiz_start_left_pos_sign['-']) ) $twiz_start_left_pos_sign['-'] = '';
        
        if( !isset($twiz_move_top_pos_sign_a['+'] ) ) $twiz_move_top_pos_sign_a['+']  = '';
        if( !isset($twiz_move_top_pos_sign_a['-'] ) ) $twiz_move_top_pos_sign_a['-']  = '';
        if( !isset($twiz_move_top_pos_sign_a['='] ) ) $twiz_move_top_pos_sign_a['=']  = '';
        if( !isset($twiz_move_top_pos_sign_b['+']) ) $twiz_move_top_pos_sign_b['+'] = '';
        if( !isset($twiz_move_top_pos_sign_b['-']) ) $twiz_move_top_pos_sign_b['-'] = '';
        if( !isset($twiz_move_top_pos_sign_b['=']) ) $twiz_move_top_pos_sign_b['='] = '';
            
        if( !isset($twiz_move_left_pos_sign_a['+'] ) ) $twiz_move_left_pos_sign_a['+']  = '';
        if( !isset($twiz_move_left_pos_sign_a['-'] ) ) $twiz_move_left_pos_sign_a['-']  = '';
        if( !isset($twiz_move_left_pos_sign_a['='] ) ) $twiz_move_left_pos_sign_a['=']  = '';
        if( !isset($twiz_move_left_pos_sign_b['+']) ) $twiz_move_left_pos_sign_b['+'] = '';
        if( !isset($twiz_move_left_pos_sign_b['-']) ) $twiz_move_left_pos_sign_b['-'] = '';
        if( !isset($twiz_move_left_pos_sign_b['=']) ) $twiz_move_left_pos_sign_b['='] = '';

        $jsscript_hide .= (($data[self::F_ON_EVENT]!='')and($data[self::F_ON_EVENT]!='Manually')) ? '$("#twiz_div_lock_event").show();' : '$("#twiz_div_no_event").show();';
                 
        if(($data[self::F_ON_EVENT]!='')
        and($data[self::F_ON_EVENT]!='Manually')
        and($data[self::F_LOCK_EVENT] != 1)){
            $jsscript_hide .= '$("#twiz_lock_event_type").hide();';
        }
        
        $hasStartingConfigs = $this->hasStartingConfigs($data);

        /* toggle starting config if we have values */        
        if($hasStartingConfigs){
        
            $toggleoptions = $jsscript_starting_config;
        }
        
        /* toggle more options by default if we have values */        
        if(($data[self::F_OPTIONS_A]!='')or($data[self::F_EXTRA_JS_A]!='')
         or($data[self::F_OPTIONS_B]!='')or($data[self::F_EXTRA_JS_B]!='')){
         
            $toggleoptions .= $jsscript_moreoptions;
        }
       
        $twiz_export_id = (($data[self::F_EXPORT_ID] == '' ) or ( $action == self::ACTION_COPY ) or ( $action == self::ACTION_NEW )) ? uniqid() : $data[self::F_EXPORT_ID];

    
        /* checked */
        $twiz_status = (( $data[self::F_STATUS] == 1 ) or ( $id == '' )) ? ' checked="checked"' : '';
        $twiz_lock_event = (( $data[self::F_LOCK_EVENT] == 1 ) or ( $id == '' )) ? ' checked="checked"' : '';
        $twiz_stay = ( $twiz_stay == 'true' ) ? ' checked="checked"' : '';
 
        /* selected */
        $twiz_position[self::POS_NO_POS] = (( $data[self::F_POSITION] == self::POS_NO_POS ) or ($this->admin_option[self::KEY_STARTING_POSITION] == self::POS_NO_POS  )) ? ' selected="selected"' : '';
        $twiz_position[self::POS_ABSOLUTE] = (( $data[self::F_POSITION] == self::POS_ABSOLUTE ) or ($this->admin_option[self::KEY_STARTING_POSITION] == self::POS_ABSOLUTE  )) ? ' selected="selected"' : '';
        $twiz_position[self::POS_RELATIVE] = (( $data[self::F_POSITION] == self::POS_RELATIVE) or ($this->admin_option[self::KEY_STARTING_POSITION] == self::POS_RELATIVE )) ? ' selected="selected"' : '';
        $twiz_position[self::POS_FIXED]   = (( $data[self::F_POSITION] == self::POS_FIXED) or ($this->admin_option[self::KEY_STARTING_POSITION] == self::POS_FIXED )) ? ' selected="selected"' : '';
        $twiz_position[self::POS_STATIC]   = (( $data[self::F_POSITION] == self::POS_STATIC) or ($this->admin_option[self::KEY_STARTING_POSITION] == self::POS_STATIC )) ? ' selected="selected"' : '';
        
        $twiz_lock_type['auto'] = ($data[self::F_LOCK_EVENT_TYPE] == 'auto') ? ' selected="selected"' : '';
        $twiz_lock_type['manu'] = ($data[self::F_LOCK_EVENT_TYPE] == 'manu') ? ' selected="selected"' : '';
        
        $twiz_ouput_pos['ready'] = ($data[self::F_OUTPUT_POS] == 'r') ? ' selected="selected"' : '';
        $twiz_ouput_pos['before'] = ($data[self::F_OUTPUT_POS] == 'b') ? ' selected="selected"' : '';
        $twiz_ouput_pos['after'] = ($data[self::F_OUTPUT_POS] == 'a') ? ' selected="selected"' : '';
        $twiz_ouput_pos['ready'] = ($data[self::F_OUTPUT_POS] == '') ? ' selected="selected"' : $twiz_ouput_pos['ready'];
        
        $twiz_ouput['ready'] = ($data[self::F_OUTPUT] == 'r') ? ' selected="selected"' : '';
        $twiz_ouput['before'] = ($data[self::F_OUTPUT] == 'b') ? ' selected="selected"' : '';
        $twiz_ouput['after'] = ($data[self::F_OUTPUT] == 'a') ? ' selected="selected"' : '';
        $twiz_ouput['before'] = ($data[self::F_OUTPUT] == '') ? ' selected="selected"' : $twiz_ouput['before'];
        
        $twiz_start_top_pos_sign['nothing']  = ($data[self::F_START_TOP_POS_SIGN] == '') ? ' selected="selected"' : '';
        $twiz_start_top_pos_sign['-']        = ($data[self::F_START_TOP_POS_SIGN] == '-') ? ' selected="selected"' : '';
        $twiz_start_left_pos_sign['nothing'] = ($data[self::F_START_LEFT_POS_SIGN] == '') ? ' selected="selected"' : '';
        $twiz_start_left_pos_sign['-']       = ($data[self::F_START_LEFT_POS_SIGN] == '-') ? ' selected="selected"' : '';
        
        $twiz_move_top_pos_sign_a['+']  = ($data[self::F_MOVE_TOP_POS_SIGN_A] == '+') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_a['-']  = ($data[self::F_MOVE_TOP_POS_SIGN_A] == '-') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_a[' ']  = ($data[self::F_MOVE_TOP_POS_SIGN_A] == '') ? ' selected="selected"' : '';
        
        $twiz_move_left_pos_sign_a['+'] = ($data[self::F_MOVE_LEFT_POS_SIGN_A] == '+') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_a['-'] = ($data[self::F_MOVE_LEFT_POS_SIGN_A] == '-') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_a[' '] = ($data[self::F_MOVE_LEFT_POS_SIGN_A] == '') ? ' selected="selected"' : '';

        $twiz_move_top_pos_sign_b['+']  = ($data[self::F_MOVE_TOP_POS_SIGN_B] == '+') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_b['-']  = ($data[self::F_MOVE_TOP_POS_SIGN_B] == '-') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_b[' ']  = ($data[self::F_MOVE_TOP_POS_SIGN_B] == '') ? ' selected="selected"' : '';
        
        $twiz_move_left_pos_sign_b['+'] = ($data[self::F_MOVE_LEFT_POS_SIGN_B] == '+') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_b['-'] = ($data[self::F_MOVE_LEFT_POS_SIGN_B] == '-') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_b[' '] = ($data[self::F_MOVE_LEFT_POS_SIGN_B] == '') ? ' selected="selected"' : '';

        $imagemove_a = $this->getDirectionalImage($data, 'a');
        $imagemove_b = $this->getDirectionalImage($data, 'b');
        
        /* reset id if it's a new copy */
        $id = ($action == self::ACTION_COPY) ? '' : $id;

        /* Added to be recognized by the translator */
        $ttcopy = __('Copy', 'the-welcomizer');
        
        // under group Manually by default
        $data[self::F_ON_EVENT] = (($parent_id != '')and($data[self::F_ON_EVENT] == '')) ? 'Manually' : $data[self::F_ON_EVENT];
        
        $eventlist = $this->getHtmlEventList($data[self::F_ON_EVENT],'','');
        $element_type_list = $this->getHtmlElementTypeList($data[self::F_TYPE]);
        
        /* easing */
        $easing_a = $this->getHtmlEasingOptions($data[self::F_EASING_A], self::F_EASING_A);
        $easing_b = $this->getHtmlEasingOptions($data[self::F_EASING_B], self::F_EASING_B);
        
        $twiz_parent_id = ($parent_id != '') ? $parent_id : $data[self::F_PARENT_ID];
        
        /* creates the form */
        $htmlform = $opendiv.'<table class="twiz-table-form" cellspacing="0" cellpadding="0">
<tr><td class="twiz-form-td-left">'.__('Status', 'the-welcomizer').': <div class="twiz-float-right"><input type="checkbox" id="twiz_'.self::F_STATUS.'" name="twiz_'.self::F_STATUS.'" '.$twiz_status.'/></div></td>
<td class="twiz-form-td-right"><div id="twiz_action_box"  class="twiz-float-right">'.__('Action', 'the-welcomizer').'<div class="twiz-green">'.__($action, 'the-welcomizer').'</div></div><div id="twiz_save_box_1" class="twiz-float-right twiz-td-save"><span id="twiz_save_img_box_1" name="twiz_save_img_box" class="twiz-loading-gif-save"></span><a name="twiz_cancel" id="twiz_cancel_1">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save" id="twiz_save_1" class="button-primary" value="'.__('Save', 'the-welcomizer').'" /></div></td></tr>
<tr><td class="twiz-form-td-left">'.__('Trigger by Event', 'the-welcomizer').': <div id="twiz_div_choose_event" class="twiz-float-right">'.$eventlist.'</div><td class="twiz-form-td-right"><div id="twiz_div_no_event" class="twiz-display-none twiz-float-left"></div><div id="twiz_div_lock_event"  class="twiz-display-none twiz-float-left"><input type="checkbox" id="twiz_'.self::F_LOCK_EVENT.'" name="twiz_'.self::F_LOCK_EVENT.'" '.$twiz_lock_event.'/><label for="twiz_'.self::F_LOCK_EVENT.'"> '.__('Locked', 'the-welcomizer').'</label> <select name="twiz_'.self::F_LOCK_EVENT_TYPE.'" id="twiz_'.self::F_LOCK_EVENT_TYPE.'">
        <option value="auto" '.$twiz_lock_type['auto'].'>'.__('Automatic unlock', 'the-welcomizer').'</option>
        <option value="manu" '.$twiz_lock_type['manu'].'>'.__('Manual unlock', 'the-welcomizer').'</option>
        </select></div></td></tr>
<tr><td class="twiz-form-td-left">'.__('Element', 'the-welcomizer').': <div class="twiz-float-right">'.$element_type_list.'</div></td><td  class="twiz-form-td-right twiz-float-left"><input class="twiz-input twiz-input-focus" id="twiz_'.self::F_LAYER_ID.'" name="twiz_'.self::F_LAYER_ID.'" type="text" value="'.$data[self::F_LAYER_ID].'" maxlength="50"/></td></tr>
<tr><td class="twiz-form-td-left"></td><td class="twiz-form-td-right"><div class="twiz-float-left">'.__('Start delay', 'the-welcomizer').':</div> <div class="twiz-green twiz-float-right"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.self::F_START_DELAY.'" name="twiz_'.self::F_START_DELAY.'" type="text" value="'.$data[self::F_START_DELAY].'" maxlength="100"/><small>1000 = 1 '.__('sec', 'the-welcomizer').'</small></div></td></tr>
<tr><td class="twiz-form-td-left"><a name="twiz_starting_config" id="twiz_starting_config" class="twiz-more-configs">'.__('More configurations', 'the-welcomizer').' &#187;</a></td><td class="twiz-form-td-right"><div class="twiz-float-left">'.__('Duration', 'the-welcomizer').':</div> <div class="twiz-green twiz-float-right">2x <input class="twiz-input-small-d twiz-input-focus" id="twiz_'.self::F_DURATION.'" name="twiz_'.self::F_DURATION.'" type="text" value="'.$data[self::F_DURATION].'" maxlength="100"/><small>1000 = 1 '.__('sec', 'the-welcomizer').'</small></div></td></tr>
<tr id="twiz_tr_starting_config">
    <td valign="top"><hr>
        <table> 
 <tr><td colspan="2" class="twiz-caption"><b>'.__('Starting Positions', 'the-welcomizer').'</b> <select name="twiz_'.self::F_OUTPUT_POS.'" id="twiz_'.self::F_OUTPUT_POS.'">
        <option value="r" '.$twiz_ouput_pos['ready'].'>'.__('OnReady', 'the-welcomizer').'</option>
        <option value="b" '.$twiz_ouput_pos['before'].'>'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a" '.$twiz_ouput_pos['after'].'>'.__('After the delay', 'the-welcomizer').'</option>
        </select></td></tr>        
            <tr><td class="twiz-td-small-left-start" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td>
            <select name="twiz_'.self::F_START_TOP_POS_SIGN.'" id="twiz_'.self::F_START_TOP_POS_SIGN.'">
                <option value="" '.$twiz_start_top_pos_sign['nothing'].'>+</option>
                <option value="-" '.$twiz_start_top_pos_sign['-'].'>-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.self::F_START_TOP_POS.'" name="twiz_'.self::F_START_TOP_POS.'" type="text" value="'.$data[self::F_START_TOP_POS].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_START_TOP_POS_FORMAT, $data[self::F_START_TOP_POS_FORMAT]).'</td></tr>
            <tr><td class="twiz-td-small-left-start" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td>
            <select name="twiz_'.self::F_START_LEFT_POS_SIGN.'" id="twiz_'.self::F_START_LEFT_POS_SIGN.'">
                <option value="" '.$twiz_start_left_pos_sign['nothing'].'>+</option>
                <option value="-" '.$twiz_start_left_pos_sign['-'].'>-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.self::F_START_LEFT_POS.'" name="twiz_'.self::F_START_LEFT_POS.'" type="text" value="'.$data[self::F_START_LEFT_POS].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_START_LEFT_POS_FORMAT, $data[self::F_START_LEFT_POS_FORMAT]).'</td></tr>
        <tr><td class="twiz-td-small-left-start">'.__('Position', 'the-welcomizer').':</td><td>
        <select name="twiz_'.self::F_POSITION.'" id="twiz_'.self::F_POSITION.'"><option value="'.self::POS_NO_POS.'" '.$twiz_position[self::POS_NO_POS].'></option>
        <option value="'.self::POS_ABSOLUTE.'" '.$twiz_position[self::POS_ABSOLUTE].'>'.__('absolute', 'the-welcomizer').'</option>
        <option value="'.self::POS_RELATIVE.'" '.$twiz_position[self::POS_RELATIVE].'>'.__('relative', 'the-welcomizer').'</option>
        <option value="'.self::POS_FIXED.'" '.$twiz_position[self::POS_FIXED].'>'.__('fixed', 'the-welcomizer').'</option>
        <option value="'.self::POS_STATIC.'" '.$twiz_position[self::POS_STATIC].'>'.__('static', 'the-welcomizer').'</option>
        </select>
        </td></tr>                
          <tr><td class="twiz-td-small-left-start">'.__('z-index', 'the-welcomizer').':</td><td>
        <input class="twiz-input-small twiz-input-focus" id="twiz_'.self::F_ZINDEX.'" name="twiz_'.self::F_ZINDEX.'" type="text" value="'.$data[self::F_ZINDEX].'" maxlength="5"/>
            </td></tr>            
        </table>
    </td>
    <td valign="top"><hr>
<table> 
 <tr><td class="twiz-caption">
    <b>'.__('JavaScript', 'the-welcomizer').'</b> <select name="twiz_'.self::F_OUTPUT.'" id="twiz_'.self::F_OUTPUT.'">
        <option value="r" '.$twiz_ouput['ready'].'>'.__('OnReady', 'the-welcomizer').'</option>
        <option value="b" '.$twiz_ouput['before'].'>'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a" '.$twiz_ouput['after'].'>'.__('After the delay', 'the-welcomizer').'</option>
        </select>
      </td></tr>            
        </table>
<div class="twiz-wrap-input-large"><textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large twiz-input-large-zzz twiz-input-focus" id="twiz_'.self::F_JAVASCRIPT.'" name="twiz_'.self::F_JAVASCRIPT.'" type="text" >'.$data[self::F_JAVASCRIPT].'</textarea></div>'.$this->getHtmlJSFeatures($id, 'javascript', $section_id).'</td>
</tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td valign="top">
        <table>
            <tr><td class="twiz-caption" colspan="3"><b>'.__('First Move', 'the-welcomizer').'</b> '.$easing_a.'</td></tr>
            <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td nowrap="nowrap">
            <select name="twiz_'.self::F_MOVE_TOP_POS_SIGN_A.'" id="twiz_'.self::F_MOVE_TOP_POS_SIGN_A.'">
            <option value="" '.$twiz_move_top_pos_sign_a[' '].'> </option>
            <option value="+" '.$twiz_move_top_pos_sign_a['+'].'>+</option>
            <option value="-" '.$twiz_move_top_pos_sign_a['-'].'>-</option>
            </select><input class="twiz-input twiz-input-small twiz-input-focus" id="twiz_move_top_pos_a" name="twiz_move_top_pos_a" type="text" value="'.$data[self::F_MOVE_TOP_POS_A].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_MOVE_TOP_POS_FORMAT_A, $data[self::F_MOVE_TOP_POS_FORMAT_A]).'</td><td rowspan="2" align="center" width="95" id="twiz_td_arrow_a">'.$imagemove_a.'</td></tr>
            <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td nowrap="nowrap">
            <select name="twiz_'.self::F_MOVE_LEFT_POS_SIGN_A.'" id="twiz_'.self::F_MOVE_LEFT_POS_SIGN_A.'">
            <option value="" '.$twiz_move_left_pos_sign_a[' '].'> </option>
            <option value="+" '.$twiz_move_left_pos_sign_a['+'].'>+</option>
            <option value="-" '.$twiz_move_left_pos_sign_a['-'].'>-</option>
            </select><input class="twiz-input twiz-input-small twiz-input-focus" id="twiz_'.self::F_MOVE_LEFT_POS_A.'" name="twiz_'.self::F_MOVE_LEFT_POS_A.'" type="text" value="'.$data[self::F_MOVE_LEFT_POS_A].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_MOVE_LEFT_POS_FORMAT_A, $data[self::F_MOVE_LEFT_POS_FORMAT_A]).'</td></tr><tr><td></td><td><a name="twiz_more_options_a" id="twiz_more_options_a"  class="twiz-more-configs">'.__('More Options', 'the-welcomizer').' &#187;</a></td></tr></table>
            <table class="twiz-table-more-options">
                <tr><td><hr></td></tr><tr><td class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td><div class="twiz-wrap-input-large"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large twiz-input-large-zzz twiz-input-focus" id="twiz_'.self::F_OPTIONS_A.'" name="twiz_'.self::F_OPTIONS_A.'" type="text" >'.$data[self::F_OPTIONS_A].'</textarea></div></td></tr>
                <tr><td id="twiz_td_full_option_a" class="twiz-td-picklist twiz-float-left"><a id="twiz_choose_options_a" name="twiz_choose_options_a">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>      
                <tr><td><hr></td></tr>        
                <tr><td class="twiz-caption">'.__('Extra JavaScript', 'the-welcomizer').'</td></tr><tr><td ><div class="twiz-wrap-input-large"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large twiz-input-large-zz twiz-input-focus" id="twiz_'.self::F_EXTRA_JS_A.'" name="twiz_'.self::F_EXTRA_JS_A.'" type="text">'.$data[self::F_EXTRA_JS_A].'</textarea></div>'.$this->getHtmlJSFeatures($id, 'javascript_a', $section_id).'</td></tr>
        </table>
</td>
<td valign="top">    
    <table>
        <tr><td class="twiz-caption" colspan="3"><b>'.__('Second Move', 'the-welcomizer').'</b> '.$easing_b.'</td></tr>
        <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td nowrap="nowrap">
        <select name="twiz_'.self::F_MOVE_TOP_POS_SIGN_B.'" id="twiz_'.self::F_MOVE_TOP_POS_SIGN_B.'">
        <option value="" '.$twiz_move_top_pos_sign_b[' '].'> </option>
        <option value="-" '.$twiz_move_top_pos_sign_b['-'].'>-</option>
        <option value="+" '.$twiz_move_top_pos_sign_b['+'].'>+</option>
        </select><input class="twiz-input twiz-input-small twiz-input-focus" id="twiz_move_top_pos_b" name="twiz_move_top_pos_b" type="text" value="'.$data[self::F_MOVE_TOP_POS_B].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_MOVE_TOP_POS_FORMAT_B, $data[self::F_MOVE_TOP_POS_FORMAT_B]).'</td><td rowspan="2" align="center" width="95" id="twiz_td_arrow_b">'.$imagemove_b.'</td></tr>
        <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td nowrap="nowrap">
        <select name="twiz_'.self::F_MOVE_LEFT_POS_SIGN_B.'" id="twiz_'.self::F_MOVE_LEFT_POS_SIGN_B.'">
        <option value="" '.$twiz_move_left_pos_sign_b[' '].'> </option>
        <option value="-" '.$twiz_move_left_pos_sign_b['-'].'>-</option>
        <option value="+" '.$twiz_move_left_pos_sign_b['+'].'>+</option>
        </select><input class="twiz-input twiz-input-small twiz-input-focus" id="twiz_'.self::F_MOVE_LEFT_POS_B.'" name="twiz_'.self::F_MOVE_LEFT_POS_B.'" type="text" value="'.$data[self::F_MOVE_LEFT_POS_B].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_MOVE_LEFT_POS_FORMAT_B, $data[self::F_MOVE_LEFT_POS_FORMAT_B]).'</td></tr><tr><td></td><td><a name="twiz_more_options_b" id="twiz_more_options_b" class="twiz-more-configs">'.__('More Options', 'the-welcomizer').' &#187;</a></td></tr>
        </table>
        <table class="twiz-table-more-options">
            <tr><td><hr></td></tr><tr><td class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td><div class="twiz-wrap-input-large"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large twiz-input-large-zz twiz-input-focus" id="twiz_'.self::F_OPTIONS_B.'" name="twiz_'.self::F_OPTIONS_B.'" type="text">'.$data[self::F_OPTIONS_B].'</textarea></div></td></tr>
            <tr><td  id="twiz_td_full_option_b" class="twiz-td-picklist twiz-float-left"><a id="twiz_choose_options_b" name="twiz_choose_options_b">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>     
            <tr><td ><hr></td></tr>
            <tr><td  class="twiz-caption">'.__('Extra JavaScript', 'the-welcomizer').'</td></tr><tr><td ><div class="twiz-wrap-input-large"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large twiz-input-large-z twiz-input-focus" id="twiz_'.self::F_EXTRA_JS_B.'" name="twiz_'.self::F_EXTRA_JS_B.'" type="text" value="">'.$data[self::F_EXTRA_JS_B].'</textarea></div>'.$this->getHtmlJSFeatures($id, 'javascript_b', $section_id).'</td></tr>
        </table>
</td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td class="twiz-td-save" colspan="2"><span id="twiz_save_img_box_2" name="twiz_save_img_box" class="twiz-loading-gif-save"></span><a name="twiz_cancel" id="twiz_cancel_2">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save" id="twiz_save_2" class="button-primary" value="'.__('Save', 'the-welcomizer').'"/> <label for="twiz_stay">'.__('& Stay', 'the-welcomizer').'</label>  <input type="checkbox" id="twiz_stay" name="twiz_stay" '.$twiz_stay.'> <input type="hidden" name="twiz_'.self::F_ID.'" id="twiz_'.self::F_ID.'" value="'.$id.'"/><input type="hidden" name="twiz_'.self::F_PARENT_ID.'" id="twiz_'.self::F_PARENT_ID.'" value="'.$twiz_parent_id.'"/><input type="hidden" name="twiz_'.self::F_EXPORT_ID.'" id="twiz_'.self::F_EXPORT_ID.'" value="'.$twiz_export_id.'"/></td></tr>
</table>'.$closediv.$jsscript_open.$jsscript_autoexpand.$toggleoptions.$jsscript_hide.$jsscript_close;
    
        return $htmlform;
    }

    function getHtmlView( $id ){ 
        
        $data = '';
        
        if($id != ''){
            if(!$data = $this->getRow($id)){return false;}
        }

        $hasMovements = $this->hasMovements($data);
        $hasStartingConfigs = $this->hasStartingConfigs($data);
        

        $start_top_pos = ($data[self::F_START_TOP_POS]!='') ? $data[self::F_START_TOP_POS_SIGN].$data[self::F_START_TOP_POS].' '.$data[self::F_START_TOP_POS_FORMAT] : '';
        $start_left_pos = ($data[self::F_START_LEFT_POS]!='') ? $data[self::F_START_LEFT_POS_SIGN].$data[self::F_START_LEFT_POS].' '.$data[self::F_START_LEFT_POS_FORMAT] : '';
        $move_top_pos_a = ($data[self::F_MOVE_TOP_POS_A]!='') ? $data[self::F_MOVE_TOP_POS_SIGN_A].$data[self::F_MOVE_TOP_POS_A].' '.$data[self::F_MOVE_TOP_POS_FORMAT_A] : '';
        $move_left_pos_a = ($data[self::F_MOVE_LEFT_POS_A]!='') ? $data[self::F_MOVE_LEFT_POS_SIGN_A].$data[self::F_MOVE_LEFT_POS_A].' '.$data[self::F_MOVE_LEFT_POS_FORMAT_A] : '';
        $move_top_pos_b = ($data[self::F_MOVE_TOP_POS_B]!='') ? $data[self::F_MOVE_TOP_POS_SIGN_B].$data[self::F_MOVE_TOP_POS_B].' '.$data[self::F_MOVE_TOP_POS_FORMAT_B] : '';
        $move_left_pos_b = ($data[self::F_MOVE_LEFT_POS_B]!='') ? $data[self::F_MOVE_LEFT_POS_SIGN_B].$data[self::F_MOVE_LEFT_POS_B].' '.$data[self::F_MOVE_LEFT_POS_FORMAT_B] : '';
        
        $titleclass = ($data[self::F_STATUS]=='1') ? 'twiz-status-green' : 'twiz-status-red';
        $event_locked = (($data[self::F_LOCK_EVENT]=='1') and ( ( $data[self::F_ON_EVENT] !='') and ( $data[self::F_ON_EVENT] !='Manually') and ( $data[self::F_LOCK_EVENT_TYPE] == 'auto') ) )  ? __('Automatic unlock', 'the-welcomizer') : '';
        $event_locked .= (($data[self::F_LOCK_EVENT]=='1') and ( ( $data[self::F_ON_EVENT] !='') and ( $data[self::F_ON_EVENT] !='Manually') and ( $data[self::F_LOCK_EVENT_TYPE] == 'manu') ) )  ? __('Manual unlock', 'the-welcomizer') : '';        
        $imagemove_a = $this->getDirectionalImage($data, 'a');
        $imagemove_b = $this->getDirectionalImage($data, 'b');
        $elementype = ($data[self::F_TYPE] == '') ? self::ELEMENT_TYPE_ID : $data[self::F_TYPE];
        
        
        $output_starting_pos = $this->getOutputLabel($data[self::F_OUTPUT_POS]);
        $output_javascript = $this->getOutputLabel($data[self::F_OUTPUT]);
        
        $easing_a = $this->getOutputEasingLabel($data[self::F_EASING_A]);
        $easing_b = $this->getOutputEasingLabel($data[self::F_EASING_B]);
        
        $javascript = str_replace("\n", "<br>", $data[self::F_JAVASCRIPT]);
        $extra_js_a = str_replace("\n", "<br>", $data[self::F_EXTRA_JS_A]);
        $extra_js_b = str_replace("\n", "<br>", $data[self::F_EXTRA_JS_B]);
             
        $where = " WHERE ".self::F_SECTION_ID." = '".$data[self::F_SECTION_ID]."'";
        $listarray = $this->getListArray($where); 
        
        $javascript = $this->add_animation_link($javascript, $listarray);
        $extra_js_a = $this->add_animation_link($extra_js_a, $listarray);
        $extra_js_b = $this->add_animation_link($extra_js_b, $listarray);
        
        /* creates the view */
        $htmlview = '<table class="twiz-table-view" cellspacing="0" cellpadding="0">
        <tr><td class="twiz-view-td-left twiz-bold" valign="top"><span class="'.$titleclass.'">'.$elementype.'</span> = '.$data[self::F_LAYER_ID].'</td><td class="twiz-view-td-right" nowrap="nowrap"><div class="twiz-blue">'.$event_locked.'</div>';
        
        if( ($hasStartingConfigs) or ((!$hasStartingConfigs) and (!$hasMovements)) ) {
        
            $hideclass = '';
        }else{
            $hideclass = 'class="twiz-tr-view-more"';
        }

        $htmlview .='</td></tr>
<tr '.$hideclass.'>
    <td colspan="2"><hr></td></tr>
        <tr '.$hideclass.'><td valign="top">
        <table>
         <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('Starting Positions', 'the-welcomizer').'</b>
         <div class="twiz-green">'.$output_starting_pos.'</div><div class="twiz-spacer"></div></td></tr>
            <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td>'.$start_top_pos.'</td></tr>
            <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td>'.$start_left_pos.'</td></tr>
            <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Position', 'the-welcomizer').':</td><td>'.' '.$data[self::F_POSITION].'</td></tr>
             <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('z-index', 'the-welcomizer').':</td><td>'.' '.$data[self::F_ZINDEX].'</td></tr>
        </table>
        </td>
        <td valign="top">
        <table>
         <tr><td class="twiz-caption"  nowrap="nowrap"><b>'.__('JavaScript', 'the-welcomizer').'</b>
         <div class="twiz-green">'.$output_javascript.'</div><div class="twiz-spacer"></div></td></tr>
             <tr><td>'.$javascript.'</td></tr>
        </table>    
        </td>
    </tr>';
    
    
        if($hasMovements) {

            $htmlview .= '<tr><td colspan="2"><hr></td></tr>
            <tr><td valign="top">
                    <table>
                        <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('First Move', 'the-welcomizer').'</b>
                        <div class="twiz-green">'.$easing_a.'</div><div class="twiz-spacer"></div></td></tr>
                        <tr><td class="twiz-view-td-small-left" valign="top" height="20" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_top_pos_a .'</td><td rowspan="2" align="center" width="95">'.$imagemove_a.'</td></tr>
                        <tr><td class="twiz-view-td-small-left"  nowrap="nowrap" valign="top">'.__('Left', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_left_pos_a .'</td></tr></table>
                        <table class="twiz-view-table-more-options">
                            <tr><td><hr></td></tr>
                            <tr><td>'.str_replace("\n", "<br>",$data[self::F_OPTIONS_A]).'</td></tr>    
                            <tr><td><hr></td></tr>        
                            <tr><td>'.$extra_js_a.'</td></tr>
                    </table>
            </td>
            <td valign="top">    
                <table>
                    <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('Second Move', 'the-welcomizer').'</b>
                    <div class="twiz-green">'.$easing_b.'</div><div class="twiz-spacer"></div></td></tr>
                    <tr><td class="twiz-view-td-small-left" valign="top" height="20" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_top_pos_b.'</td><td rowspan="2" align="center" width="95">'.$imagemove_b.'</td></tr>
                    <tr><td class="twiz-view-td-small-left" nowrap="nowrap" valign="top">'.__('Left', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_left_pos_b .'</td></tr>
                    </table>
                    <table class="twiz-view-table-more-options">
                        <tr><td><hr></td></tr>
                        <tr><td>'.str_replace("\n", "<br>", $data[self::F_OPTIONS_B]).'</td></tr>
                        <tr><td><hr></td></tr>
                        <tr><td>'.$extra_js_b.'</td></tr>
                    </table></td></tr>';
        
        }
        
        $htmlview .= '</table>';
    
        return $htmlview;
    }

    protected function getListArray( $where = '', $orderby = '' ){ 

        global $wpdb;

        $sql = "SELECT *,(
        SELECT LPAD(p.".self::F_EXPORT_ID.", 13, '0') 
        FROM ".$this->table." p 
        WHERE p.".self::F_EXPORT_ID." = t.".self::F_EXPORT_ID." AND p.".self::F_PARENT_ID." = '' AND p.".self::F_TYPE." = '".self::ELEMENT_TYPE_GROUP."'
        UNION
        SELECT CONCAT(LPAD(p.".self::F_EXPORT_ID.", 13, '0'), '.', LPAD(c.".self::F_EXPORT_ID.", 13, '0')) 
        FROM ".$this->table." p 
        INNER JOIN ".$this->table." c ON (p.".self::F_EXPORT_ID." = c.".self::F_PARENT_ID.") 
        WHERE c.".self::F_EXPORT_ID." = t.".self::F_EXPORT_ID." AND p.".self::F_PARENT_ID." = '' AND p.".self::F_TYPE." = '".self::ELEMENT_TYPE_GROUP."'
       ) AS level, IF(".self::F_ON_EVENT." = '','0','1') AS event_order, IF(".self::F_ON_EVENT." = 'Manually','0','1') AS event_order_2 from ".$this->table." t ".$where;

        $twiz_order_by = get_option('twiz_order_by');
        $twiz_order_by = ( !is_array($twiz_order_by) ) ? '' : $twiz_order_by;
        $twiz_order_by[$this->userid] = (!isset($twiz_order_by[$this->userid])) ? '' : $twiz_order_by[$this->userid];
        
        switch($orderby){
        
            case '':

                if( $twiz_order_by[$this->userid] != '' ){
                
                    return $this->getListArray( $where , $twiz_order_by[$this->userid]);
                    
                }else{
                
                    return $this->getListArray( $where , self::F_ON_EVENT);
                }
                
                break;
                
            case self::F_ON_EVENT:
             
                $orderby = " ORDER BY level, event_order, event_order_2, ".self::F_ON_EVENT.",".self::F_START_DELAY.", ".self::F_LAYER_ID;
                
                $twiz_order_by[$this->userid] = self::F_ON_EVENT;
                $code = update_option('twiz_order_by', $twiz_order_by);
   
                break;
                
            case self::F_STATUS:
             
                $orderby = " ORDER BY level, ".self::F_STATUS.", ".self::F_LAYER_ID.", ".self::F_START_DELAY.", event_order, event_order_2,".self::F_ON_EVENT."";
                
                $twiz_order_by[$this->userid] = self::F_STATUS;
                $code = update_option('twiz_order_by', $twiz_order_by);
                
                break;
                
            case self::F_LAYER_ID:
             
                $orderby = " ORDER BY level, ".self::F_LAYER_ID.", ".self::F_START_DELAY.", event_order, event_order_2, ".self::F_ON_EVENT."";
                
                $twiz_order_by[$this->userid] = self::F_LAYER_ID;
                $code = update_option('twiz_order_by', $twiz_order_by);
                
                break;
                
            case self::F_START_DELAY:

                $orderby = " ORDER BY level, CAST(".self::F_START_DELAY." AS SIGNED), ".self::F_LAYER_ID.", event_order, event_order_2, ".self::F_ON_EVENT."";            

                $twiz_order_by[$this->userid] = self::F_START_DELAY;
                $code = update_option('twiz_order_by', $twiz_order_by);              
                
                break;  
                
            case self::F_DURATION:
            
                $orderby = " ORDER BY level, CAST(".self::F_DURATION." AS SIGNED), ".self::F_LAYER_ID.", event_order, event_order_2, ".self::F_ON_EVENT."";                  
                
                $twiz_order_by[$this->userid] = self::F_DURATION;
                $code = update_option('twiz_order_by', $twiz_order_by);   

                break;  
        }
        
        $rows = $wpdb->get_results($sql.$orderby, ARRAY_A);

        return $rows;
    }

    function getHtmlList( $section_id = '', $saved_id = '', $orderby = '', $parent_id = '', $action = '' ){ 
    
        $container = '';
        $section_id = ( $section_id == '' ) ? $this->DEFAULT_SECTION[$this->userid] : $section_id;
        $code = $this->updateSettingMenu( $section_id );
        
        /* from the menu */ 
        $where = " WHERE ".self::F_SECTION_ID." = '".$section_id."'";
        $listarray = $this->getListArray( $where, $orderby ); // get all the data
        if(count($listarray)==0){ 
            
            $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'];
                    
        
             /* show element */
            $container = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
        $("#twiz_listmenu").css("display", "block"); 
        $(".twiz-status-menu").css("visibility","visible");
        $("#twiz_add_menu").fadeIn("fast");
        $("#twiz_import").fadeIn("fast");
        $("#twiz_export").fadeIn("fast");      
        $("#twiz_container").html("<div class=\"twiz-row-color-3 twiz-text-center twiz-blue\">'. __('This section is empty.', 'the-welcomizer').'</div>");      
  });
 //]]>
</script>';
           $container .= '<div id="twiz_container"></div>';

            return $container; 
            
        }else{ // else display the list
        
            return $this->createHtmlList($listarray, $saved_id, $parent_id, $action); // private
        }
        
    }
   
    protected function getHtmlImgStatus( $id = '', $status = '', $from = ''){
    
        if($id==''){ return ''; }
        if($status==''){ return ''; }
        $title = '';
        $prefix = '';
        
        switch($id){
        
            case 'global':
                
                $title = __('Global', 'the-welcomizer');
                break;
                
            default:
                
                if( $from == '' ){
                
                    $row = $this->getRow( $id ); 
                    $title = $id . ' - '.$row[self::F_EXPORT_ID];
                    
                }else{
                
                    $prefix = $from."_";
                }
        }
 
        return '<img src="'.$this->pluginUrl.'/images/twiz-'.$status.'.png" id="twiz_status_img_'.$prefix.$id.'" name="twiz_status_img_'.$prefix.$id.'" title="'.$title.'" />';

    }
    
    private function getHtmlJSFeatures( $id = '', $name = '', $section_id = '' ){

        $where = ($section_id!='') ? " WHERE ".self::F_SECTION_ID." = '".$section_id."'"." AND ".self::F_STATUS."=1" : '';
        
        $listarray = $this->getListArray( $where, " ORDER BY ".self::F_ID ); // get all the data
        
        // Animations
        $html = '<select class="twiz-slc-js-features" name="twiz_slc_func_'.$name.'" id="twiz_slc_func_'.$name.'">';
        $html .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';

        foreach ( $listarray as $value ){
        
            $group = ($value[self::F_TYPE] == self::ELEMENT_TYPE_GROUP) ? '_'.self::ELEMENT_TYPE_GROUP : '';
            
            $functionnames = 'twiz'.$group.'_'.$value[self::F_SECTION_ID] .'_'. str_replace("-","_",sanitize_title_with_dashes($value[self::F_LAYER_ID])).'_'.$value[self::F_EXPORT_ID].'();';
            $bold = ( $id== $value[self::F_ID] )? 'twiz-bold' : '';
            $italic = ($value[self::F_TYPE] == self::ELEMENT_TYPE_GROUP) ? ' twiz-italic' : '';
            $html .= '<option value="$(document).'.$functionnames.'" class="'.$bold.$italic.'">'.$value[self::F_ID].' - '.$functionnames.'</option>';
        }
        
        $html .= '</select>';

        // Unlock
        $html .= '<select class="twiz-slc-js-features" name="twiz_slc_unlo_'.$name.'" id="twiz_slc_unlo_'.$name.'">';
        $html .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';

        foreach ( $listarray as $value ){
       
            if( $value[self::F_TYPE] !=  self::ELEMENT_TYPE_GROUP ){
            
                $functionnames = 'twiz_locked_'.$value[self::F_SECTION_ID] .'_'. str_replace("-","_",sanitize_title_with_dashes($value[self::F_LAYER_ID])).'_'.$value[self::F_EXPORT_ID].' = 0;';
                $bold = ( $id== $value[self::F_ID] )? ' class="twiz-bold"' : '';
                $html .= '<option value="'.$functionnames.'"'.$bold.'>'.$value[self::F_ID].' - '.$functionnames.'</option>';
            
            }
        }
        $html .= '</select>';

        // Stop
        $html .= '<select class="twiz-slc-js-features" name="twiz_slc_stop_'.$name.'" id="twiz_slc_stop_'.$name.'">';
        $html .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';

        foreach ( $listarray as $value ){
        
            if( $value[self::F_TYPE] !=  self::ELEMENT_TYPE_GROUP ){
            
                $functionnames = 'twiz_repeat_'.$value[self::F_SECTION_ID] .'_'. str_replace("-","_",sanitize_title_with_dashes($value[self::F_LAYER_ID])).'_'.$value[self::F_EXPORT_ID].' = 0;';
                $bold = ( $id== $value[self::F_ID] )? ' class="twiz-bold"' : '';
                $html .= '<option value="'.$functionnames.'"'.$bold.'>'.$value[self::F_ID].' - '.$functionnames.'</option>';
            }
        }
        
        $html .= '</select>';

        // Bind & Unbind
        $html .= '<select class="twiz-slc-js-features" name="twiz_slc_bind_'.$name.'" id="twiz_slc_bind_'.$name.'">';
        $html .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';
        $html .= '<optgroup label="Bind">';
        
        foreach ( $listarray as $value ){
        
            if( ( $value[self::F_ON_EVENT] != '' ) 
            and ( $value[self::F_ON_EVENT] != self::EV_MANUAL )
            and ( $value[self::F_TYPE] !=  self::ELEMENT_TYPE_GROUP )){
                
                    $functionnames = '$(\'#sampleid\').bind(\''.strtolower($value[self::F_ON_EVENT]).'\', twiz_event_'.$value[self::F_SECTION_ID] .'_'. str_replace("-","_",sanitize_title_with_dashes($value[self::F_LAYER_ID])).'_'.$value[self::F_EXPORT_ID].');';
                    $bold = ( $id== $value[self::F_ID] )? ' class="twiz-bold"' : '';
                    $html .= '<option value="'.$functionnames.'"'.$bold.'>'.$value[self::F_ID].' - '.$functionnames.'</option>';
                
            }
        }
        
        $html .= '</optgroup>';
        $html .= '<optgroup label="Unbind">';

        foreach ( $listarray as $value ){

            if( ( $value[self::F_ON_EVENT] != '' )
            and ( $value[self::F_ON_EVENT] != self::EV_MANUAL )
            and ( $value[self::F_TYPE] !=  self::ELEMENT_TYPE_GROUP )){

                    $functionnames = '$(\'#sampleid\').unbind(\''.strtolower($value[self::F_ON_EVENT]).'\', twiz_event_'.$value[self::F_SECTION_ID] .'_'. str_replace("-","_",sanitize_title_with_dashes($value[self::F_LAYER_ID])).'_'.$value[self::F_EXPORT_ID].');';
                $bold = ( $id== $value[self::F_ID] )? ' class="twiz-bold"' : '';
                    $html .= '<option value="'.$functionnames.'""'.$bold.'>'.$value[self::F_ID].' - '.$functionnames.'</option>';
                
            }
        }
        
        $html .= '</optgroup>'; 
        $html .= '</select>';
       
        // Code Snippets
        $codesnippets = $this->getHTMLCodeSnippets( $name );
        
        if( $id != '' ){
        
            $layer_id = $this->getValue( $id, self::F_LAYER_ID );
            $export_id = $this->getValue( $id, self::F_EXPORT_ID );
            $repeatname = $section_id ."_".str_replace("-","_",sanitize_title_with_dashes($layer_id))."_".$export_id;
            $html .= str_replace("[SNIPPETS_REPEAT_CONDITION]","<option value=\"if(twiz_repeat_".$repeatname." == 0){}\" class=\"twiz-bold\">if(twiz_repeat_".$repeatname." == 0){}</option>", $codesnippets);
            
        }else{
        
            $html .= str_replace("[SNIPPETS_REPEAT_CONDITION]","", $codesnippets);
        }
       
        // Menu
        $html .=  '<br><div id="twiz_js_features_'.$name.'" class="twiz-js-features"><a id="twiz_jsf_code_snippets_'.$name.'" class="twiz-black">'.__('Code Snippets', 'the-welcomizer').'</a> | <a id="twiz_jsf_functions_'.$name.'" class="">'.__('Functions', 'the-welcomizer').'</a> | <a id="twiz_jsf_bind_'.$name.'" class="">Bind</a> | <a id="twiz_jsf_unlock_'.$name.'" class="">Unlock</a> | <a id="twiz_jsf_stop_'.$name.'" class="">'.__('Stop', 'the-welcomizer').'</a></div>';
        
        // js 
        $jsscript = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {';
  $jsscript .= '';
        $jsscript .= '$("#twiz_slc_code_'.$name.'").show();
});
 //]]>
</script>';
        
        $html .= $jsscript;
        
        return $html;
    }
    
    private function getHtmlElementTypeList( $type = self::ELEMENT_TYPE_ID ){
        
        $select = '<select name="twiz_'.self::F_TYPE.'" id="twiz_'.self::F_TYPE.'">';
         
        foreach ($this->array_element_type as $value){

            $selected = ($type == $value) ? ' selected="selected"' : '';
            
            $select .= '<option value="'.$value.'"'.$selected.'>'.$value.' =</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }  
    
    function getHtmlEventList( $event = '', $extraid = '', $extraclass = '' ){
    
        $valuelbl = '';
        $extraid = ($extraid == '') ? '' : '_'.$extraid;
        $extraclass = ($extraclass == '') ? '' : 'class="'.$extraclass.'"';
        
        $select = '<select name="twiz_'.self::F_ON_EVENT.$extraid.'" id="twiz_'.self::F_ON_EVENT.$extraid.'" '.$extraclass.'>';
        $select .= '<option value="">'.__('(Optional)', 'the-welcomizer').'</option>';
            
        foreach ($this->array_on_event as $value){

            $selected = ($event == $value) ? ' selected="selected"' : '';
            $on = ($value != self::EV_MANUAL) ? 'On': '';
            $valuelbl = $this->format_on_event($value);
            $select .= '<option value="'.$value.'"'.$selected.'>'.$valuelbl.'</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }  

    function format_on_event( $value = '' ){
    
        if( $value != '' ){
        
            $value = ($value != self::EV_MANUAL) ? 'On'.$value : $value;
            
        }else{
        
            $value = '-';
        }
        
        return $value;
    }
    
    protected function getHtmlFormatList( $name = '', $format = '', $suffix = '' ){
        
        if( $name == '' ){ return ''; }
        $suffix = ($suffix == '') ? '' : '_'.$suffix;
        
        $select = '<select name="twiz_'.$name.$suffix.'" id="twiz_'.$name.$suffix.'">';
        
        if( $suffix != '' ){
        
                $select .= '<option value=""></option>';
        }
         
        foreach ( $this->array_format as $value ){

            $selected = ($format == $value) ? ' selected="selected"' : '';
            
            $select .= '<option value="'.$value.'"'.$selected.'>'.$value.' </option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }  
    
    private function getHTMLCodeSnippets( $name = '' ){
        
        $select = '<select class="twiz-slc-js-features" name="twiz_slc_code_'.$name.'" id="twiz_slc_code_'.$name.'">';
        
        $select .= '<option value="">'.__('Choose', 'the-welcomizer').'</option><optgroup label="Twiz">';
        $select .= '[SNIPPETS_REPEAT_CONDITION]';
        $select .= '<option value="$(document).twizRepeat();">$(document).twizRepeat();</option>';
        $select .= '<option value="$(document).twizRepeat(10);">$(document).twizRepeat(10);</option>';
        $select .= '<option value="$(document).twizReplay();">$(document).twizReplay();</option>';
        $select .= '</optgroup>';
        if( $this->admin_option[Twiz::KEY_REGISTER_JQUERY_ROTATE3DI] ==  '1' ){
        
            $select .= '<optgroup label="rotate3Di">';
                     
             $select .= '<option value="'.self::CS_ROTATE3DI.'">'.self::CS_ROTATE3DI.'</option>';
           
            
            $select .= '</optgroup>';   
        }   
        if( $this->admin_option[Twiz::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] ==  '1' ){   
        
            $select .= '<optgroup label="jquery-animate-css-rotate-scale">';
            
            foreach ( $this->array_jQuery_acrs_code_snippets as $value ){
                        
                $select .= '<option value="'.$value.'">'.$value.'</option>';
            }
            
            $select .= '</optgroup>';
        }
        if( $this->admin_option[Twiz::KEY_REGISTER_JQUERY_TRANSFORM] ==  '1' ){
        
            $select .= '<optgroup label="transform">';
            
            foreach ( $this->array_jQuery_transform_code_snippets as $value ){
                        
                $select .= '<option value="'.$value.'">'.$value.'</option>';
            }
            
            $select .= '</optgroup>';            
        }        
        if( $this->admin_option[Twiz::KEY_REGISTER_JQUERY_TRANSIT] ==  '1' ){
        
            $select .= '<optgroup label="jQuery Transit">';
            
            foreach ( $this->array_jQuery_transit_code_snippets as $value ){
                        
                $select .= '<option value="'.$value.'">'.$value.'</option>';
            }
            
            $select .= '</optgroup>';   
        }     
        
        $select .= '<optgroup label="jQuery"><option value="$(\'#sampleid\').css({\'display\':\'block\'});">$(\'#sampleid\').css({\'display\':\'block\'});</option>';
        $select .= '<option value="$(\'#sampleid\').attr({\'value\':\'Hello\'});">$(\'#sampleid\').attr({\'value\':\'Hello\'});</option>';
        $select .= '<option value="$(\'#sampleid\').hover(function(){},function(){});">$(\'#sampleid\').hover(function(){},function(){});</option>';
        $select .= '<option value="$(\'#sampleid\').animate({\'opacity\':0}, 1000, function(){});">$(\'#sampleid\').animate({\'opacity\':0}, 1000, \'linear\', function(){});</option>';
        $select .= '</select>';   
        
        return $select;
    }
    
    function getHtmlOptionList( $charid = '' ){
        
        if( $charid == '' ){ return ''; }
        
        $select = '<select class="twiz-slc-options" name="twiz_slc_options_'.$charid.'" id="twiz_slc_options_'.$charid.'">';
        
        $select .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';
            
        if( $this->admin_option[Twiz::KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE] ==  '1' ){   
        
            $select .= '<optgroup label="jquery-animate-css-rotate-scale">';
            
            foreach ( $this->array_jQuery_acrs_options as $value ){
                        
                $select .= '<option value="'.$value.'">'.$value.'</option>';
            }
            
            $select .= '</optgroup>';
        }
        if( $this->admin_option[Twiz::KEY_REGISTER_JQUERY_TRANSFORM] ==  '1' ){
        
            $select .= '<optgroup label="transform">';
            
            foreach ( $this->array_jQuery_transform_options as $value ){
                        
                $select .= '<option value="'.$value.'">'.$value.'</option>';
            }
            
            $select .= '</optgroup>';            
        }
        if( $this->admin_option[Twiz::KEY_REGISTER_JQUERY_TRANSIT] ==  '1' ){
        
            $select .= '<optgroup label="jQuery Transit">';
            
            foreach ( $this->array_jQuery_transit_options as $value ){
                        
                $select .= '<option value="'.$value.'">'.$value.'</option>';
            }
            
            $select .= '</optgroup>';   
        }
        
        $select .= '<optgroup label="jQuery">';
        
        foreach ( $this->array_jQuery_options as $value ){
        
            $select .= '<option value="'.$value.'">'.$value.'</option>';
        }
        
        $select .= '</optgroup>';
        
        $select .= '</select>';
            
        return $select;
    }    
    
    protected function getHtmlEasingOptions( $easing_value = '', $fieldname = '', $suffix = '' ){
        
        
        if( !isset($twiz_easing['swing']) ) $twiz_easing_a['swing'] = '';
        if( !isset($twiz_easing['linear']) ) $twiz_easing_a['linear'] = '';
        
        $suffix = ($suffix == '') ? '' : '_'.$suffix;
        $twiz_easing['swing'] = ($easing_value == 'swing') ? ' selected="selected"' : '';
        $twiz_easing['linear'] = ($easing_value == 'linear') ? ' selected="selected"' : '';

        
        $options = '<optgroup label="Easing"><option value="linear" '.$twiz_easing['linear'].'>'.$this->getOutputEasingLabel('linear').'</option>';
        
        if( $this->admin_option[self::KEY_REGISTER_JQUERY_TRANSIT] != '1' ){
                
            $options .='<option value="swing" '.$twiz_easing['swing'].'>'.$this->getOutputEasingLabel('swing').'</option></optgroup>';
            $type = '';
            
        }else{
        
            $options .= $this->getHtmlTransitEasingOptions( $easing_value );
            $type = 'transit';            
        }

        $options .= ( $this->admin_option[self::KEY_EXTRA_EASING] == '1' ) ? $this->getHtmlExtraEasingOptions( $easing_value , $type) : '';
        
        $select = '<select name="twiz_'.$fieldname.$suffix.'" id="twiz_'.$fieldname.$suffix.'" class="twiz-slc-easing">';
        
        if( $suffix != '' ){
        
                $select .= '<option value=""></option>';
        }        
        
        $select .= $options;
        $select .= '</select>';
        
        return $select;
    }    
    
    private function getHtmlExtraEasingOptions( $easing_value = '', $type = '' ){
        
        $options = '';
        $options .= '<optgroup label="Extra easing">';   
        
        foreach ( $this->array_extra_easing as $value ){
        
            if( ( $type == 'transit')
            and ( in_array( $value, $this->array_transit_excluded_extra_easing ) ) ){
            
                // nothing
                 
            }else{
            
                $selected = ($easing_value == $value) ? ' selected="selected"' : '';
                $options .= '<option value="'.$value.'"'.$selected .'>'.$this->getOutputEasingLabel($value).'</option>';            
            }
        }
        
        $options .= '</optgroup>';  
        
        return $options;
    }    
    
    private function getHtmlTransitEasingOptions( $easing_value = '' ){
        
        $options = '';
        $options .= '<optgroup label="jQuery Transit">';   
        
        foreach ( $this->array_transit_easing as $value ){
        
            $selected = ($easing_value == $value) ? ' selected="selected"' : '';
            $options .= '<option value="'.$value.'"'.$selected .'>'.$this->getOutputEasingLabel($value).'</option>';
        }
        
        $options .= '</optgroup>';  
        
        return $options;
    }    
    
    private function getOutputLabel( $type = '' ){
    
        switch($type){
        
            case 'r':
            
                return ''.__('OnReady', 'the-welcomizer').'';
                
                break;
                
            case 'b':
            
                return ''.__('Before the delay', 'the-welcomizer').'';
                
                break;
                
            case 'a':
            
                return ''.__('After the delay', 'the-welcomizer').'';    
                
                break;
        }
        
        return '';
    }
    
    private function getOutputEasingLabel( $type = '' ){
    
        switch($type){
        
            case 'swing':
            
                return ''.__('Swing', 'the-welcomizer').'';
                
                break;
                
            case 'linear':
            
                return ''.__('Linear', 'the-welcomizer').'';
                
                break; 
                
            default:
            
                return ucfirst($type);
                
                break;
        }
        
        return '';
    }
    
    protected function getRow( $id = '' ){ 
    
        global $wpdb;
        
        if( $id == '' ){ return false; }
    
        $sql = "SELECT * FROM ".$this->table." WHERE ".self::F_ID." = '".$id."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
        
        return $row;
    }

    protected function getId( $column = '', $value = '' ){ 
    
        global $wpdb;
        
        if($value==''){return false;}
        if($column==''){return false;}
        
        $column = ($column=="delay") ? self::F_START_DELAY : $column;
    
        $sql = "SELECT ".self::F_ID." FROM ".$this->table." WHERE ".$column." = '".$value."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        $id = $row[self::F_ID];
  
        return $id;
    }
    
    function getValue( $id = '', $column = '' ){ 
    
        global $wpdb;
        
        if($id==''){return false;}
        if($column==''){return false;}
        
        $column = ($column=="delay") ? self::F_START_DELAY : $column;
    
        $sql = "SELECT ".$column." FROM ".$this->table." WHERE ".self::F_ID." = '".$id."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        $value = $row[$column];
  
        return $value;
    }
    
    private function ExportidExists( $exportid = '' ){ 
    
        global $wpdb;
        
        if($exportid==''){return false;}
    
        $sql = "SELECT ".self::F_EXPORT_ID." FROM ".$this->table." WHERE ".self::F_EXPORT_ID." = '".$exportid."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        if($row[self::F_EXPORT_ID]!=''){

            return true;
        }
  
        return false;
    }
    
    private function preloadImages(){
    
    
        $dirarray = $this->getSkinsDirectory();

        sort($dirarray);
              
        $html = '';
       
        foreach( $dirarray as $value ){
        
            if( $this->skin[$this->userid] != self::SKIN_PATH.$value ){
            
                $html .= '<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/images/twiz-save.gif" class="twiz-display-none"/>';
                $html .= '<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/images/twiz-loading.gif" class="twiz-display-none"/>';
            }
        }
     
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-download.png" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-inactive.png" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-edit.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-delete.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-copy.png" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->userid].'/images/twiz-save.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->userid].'/images/twiz-loading.gif" class="twiz-display-none"/>';        
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->userid].'/images/twiz-big-loading.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->userid].'/images/twiz-menu-edit-bw.png" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->userid].'/images/twiz-menu-edit-color.png" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->userid].'/images/twiz-menu-delete-bw.png" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->userid].'/images/twiz-menu-delete-color.png" class="twiz-display-none"/>';
    
        return $html;
    }

    private function updateSettingMenu( $section_id = '' ){
    
        if( $section_id == '' ){
        
            $section_id = self::DEFAULT_SECTION_HOME;
        }
        
        $this->DEFAULT_SECTION[$this->userid] = $section_id;
        
        $code = update_option('twiz_setting_menu', $this->DEFAULT_SECTION);
        
        return true;
    }
    
    private function UpdateTwizFunctions( $current_value = '', $new_value = '', $section_id = '') {

        global $wpdb;
        
        $code = 0;
            
        if($new_value==''){return false;}
        
        if( $current_value != $new_value ) {
                       
            $current_value[self::F_LAYER_ID] = ($current_value[self::F_LAYER_ID] != '') ? sanitize_title_with_dashes($current_value[self::F_LAYER_ID]) : '';
            $new_value = ($new_value != '') ? sanitize_title_with_dashes($new_value) : '';
            
            $current_value[self::F_LAYER_ID] = str_replace("-", "_", $current_value[self::F_LAYER_ID]);
            $new_value = str_replace("-", "_", $new_value);

            // Replace all current ids
            $updatesql = "UPDATE ".$this->table . " SET
             ". self::F_JAVASCRIPT . " = replace(". self::F_JAVASCRIPT . ", '_".$current_value[self::F_LAYER_ID]."_".$current_value[self::F_EXPORT_ID]."', '_".$new_value."_".$current_value[self::F_EXPORT_ID]."') ,". self::F_EXTRA_JS_A . " = replace(". self::F_EXTRA_JS_A . ", '_".$current_value[self::F_LAYER_ID]."_".$current_value[self::F_EXPORT_ID]."', '_".$new_value."_".$current_value[self::F_EXPORT_ID]."') ,". self::F_EXTRA_JS_B . " = replace(". self::F_EXTRA_JS_B . ", '_".$current_value[self::F_LAYER_ID]."_".$current_value[self::F_EXPORT_ID]."', '_".$new_value."_".$current_value[self::F_EXPORT_ID]."') where ". self::F_SECTION_ID ." = '".$section_id."'";
            $code = $wpdb->query($updatesql);
            
        }

        return $code;
    }
    
    function save( $id = '' ){

        global $wpdb;
        
        $_POST['twiz_'.self::F_EXPORT_ID] = (!isset($_POST['twiz_'.self::F_EXPORT_ID])) ? '' : $_POST['twiz_'.self::F_EXPORT_ID];
        $_POST['twiz_'.self::F_PARENT_ID] = (!isset($_POST['twiz_'.self::F_PARENT_ID])) ? '' : $_POST['twiz_'.self::F_PARENT_ID];
        $_POST['twiz_'.self::F_STATUS] = (!isset($_POST['twiz_'.self::F_STATUS])) ? '' : $_POST['twiz_'.self::F_STATUS];
        $_POST['twiz_'.self::F_LAYER_ID] = (!isset($_POST['twiz_'.self::F_LAYER_ID])) ? '' : $_POST['twiz_'.self::F_LAYER_ID];
        $_POST['twiz_'.self::F_TYPE] = (!isset($_POST['twiz_'.self::F_TYPE])) ? '' : $_POST['twiz_'.self::F_TYPE];
        $_POST['twiz_'.self::F_ON_EVENT] = (!isset($_POST['twiz_'.self::F_ON_EVENT])) ? '' : $_POST['twiz_'.self::F_ON_EVENT];
        $_POST['twiz_'.self::F_LOCK_EVENT] = (!isset($_POST['twiz_'.self::F_LOCK_EVENT])) ? '' : $_POST['twiz_'.self::F_LOCK_EVENT];
        $_POST['twiz_'.self::F_LOCK_EVENT_TYPE] = (!isset($_POST['twiz_'.self::F_LOCK_EVENT_TYPE])) ? '' :  $_POST['twiz_'.self::F_LOCK_EVENT_TYPE];
        $_POST['twiz_'.self::F_START_DELAY] = (!isset($_POST['twiz_'.self::F_START_DELAY])) ? '' : $_POST['twiz_'.self::F_START_DELAY];
        $_POST['twiz_'.self::F_DURATION] = (!isset($_POST['twiz_'.self::F_DURATION])) ? '' :  $_POST['twiz_'.self::F_DURATION];
        $_POST['twiz_'.self::F_OUTPUT_POS] = (!isset($_POST['twiz_'.self::F_OUTPUT_POS])) ? '' : $_POST['twiz_'.self::F_OUTPUT_POS];
        $_POST['twiz_'.self::F_START_TOP_POS_SIGN] = (!isset($_POST['twiz_'.self::F_START_TOP_POS_SIGN])) ? '' : $_POST['twiz_'.self::F_START_TOP_POS_SIGN];
        $_POST['twiz_'.self::F_START_TOP_POS] = (!isset($_POST['twiz_'.self::F_START_TOP_POS])) ? '' : $_POST['twiz_'.self::F_START_TOP_POS];
        $_POST['twiz_'.self::F_START_TOP_POS_FORMAT] = (!isset($_POST['twiz_'.self::F_START_TOP_POS_FORMAT])) ? '' : $_POST['twiz_'.self::F_START_TOP_POS_FORMAT];
        $_POST['twiz_'.self::F_START_LEFT_POS_SIGN] = (!isset($_POST['twiz_'.self::F_START_LEFT_POS_SIGN])) ? '' : $_POST['twiz_'.self::F_START_LEFT_POS_SIGN];
        $_POST['twiz_'.self::F_START_LEFT_POS] = (!isset($_POST['twiz_'.self::F_START_LEFT_POS])) ? '' : $_POST['twiz_'.self::F_START_LEFT_POS];
        $_POST['twiz_'.self::F_START_LEFT_POS_FORMAT] = (!isset($_POST['twiz_'.self::F_START_LEFT_POS_FORMAT])) ? '' : $_POST['twiz_'.self::F_START_LEFT_POS_FORMAT];
        $_POST['twiz_'.self::F_POSITION] = (!isset($_POST['twiz_'.self::F_POSITION])) ? '' : $_POST['twiz_'.self::F_POSITION];
        $_POST['twiz_'.self::F_ZINDEX] = (!isset($_POST['twiz_'.self::F_ZINDEX])) ? '' : $_POST['twiz_'.self::F_ZINDEX];
        $_POST['twiz_'.self::F_OUTPUT] = (!isset($_POST['twiz_'.self::F_OUTPUT])) ? '' : $_POST['twiz_'.self::F_OUTPUT];
        $_POST['twiz_'.self::F_JAVASCRIPT] = (!isset($_POST['twiz_'.self::F_JAVASCRIPT])) ? '' : $_POST['twiz_'.self::F_JAVASCRIPT];
        $_POST['twiz_'.self::F_EASING_A] = (!isset($_POST['twiz_'.self::F_EASING_A])) ? '' : $_POST['twiz_'.self::F_EASING_A];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_A] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_A])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_A];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_A] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_A])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_A];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A];        
        $_POST['twiz_'.self::F_OPTIONS_A] = (!isset($_POST['twiz_'.self::F_OPTIONS_A])) ? '' : $_POST['twiz_'.self::F_OPTIONS_A];
        $_POST['twiz_'.self::F_EXTRA_JS_A] = (!isset($_POST['twiz_'.self::F_EXTRA_JS_A])) ? '' : $_POST['twiz_'.self::F_EXTRA_JS_A];
        $_POST['twiz_'.self::F_EASING_B] = (!isset($_POST['twiz_'.self::F_EASING_B])) ? '' : $_POST['twiz_'.self::F_EASING_B];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_B] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_B])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_B];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_B] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_B])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_B];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_B] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_B])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_B];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_B] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_B])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_B];
        $_POST['twiz_'.self::F_OPTIONS_B] = (!isset($_POST['twiz_'.self::F_OPTIONS_B])) ? '' : $_POST['twiz_'.self::F_OPTIONS_B];
        $_POST['twiz_'.self::F_EXTRA_JS_B] = (!isset($_POST['twiz_'.self::F_EXTRA_JS_B])) ? '' : $_POST['twiz_'.self::F_EXTRA_JS_B];
        
        
        $twiz_export_id = esc_attr(trim( $_POST['twiz_'.self::F_EXPORT_ID]));
        $twiz_status = esc_attr(trim($_POST['twiz_'.self::F_STATUS]));
        $twiz_status = ($twiz_status=='true') ? 1 : 0;        
        
        $twiz_lock_event = esc_attr(trim($_POST['twiz_'.self::F_LOCK_EVENT]));
        $twiz_lock_event = ($twiz_lock_event=='true') ? 1 : 0;
        $twiz_lock_event_type = esc_attr(trim($_POST['twiz_'.self::F_LOCK_EVENT_TYPE]));
        
        $twiz_parent_id = esc_attr(trim($_POST['twiz_'.self::F_PARENT_ID]));
        $twiz_section_id = esc_attr(trim($_POST['twiz_'.self::F_SECTION_ID]));
         
        $twiz_layer_id = esc_attr(trim($_POST['twiz_'.self::F_LAYER_ID]));
        $twiz_layer_id = ($twiz_layer_id=='') ? '' : $twiz_layer_id;
        
        $twiz_type = esc_attr(trim($_POST['twiz_'.self::F_TYPE]));
        $twiz_move_top_pos_a  = esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_A]));
        $twiz_move_left_pos_a = esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_A]));
        $twiz_move_top_pos_b  = esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_B]));
        $twiz_move_left_pos_b = esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_B]));
        $twiz_start_top_pos   = esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS]));
        $twiz_start_left_pos  = esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS]));
        
        $twiz_move_top_pos_a  = ($twiz_move_top_pos_a=='') ? 'NULL' : $twiz_move_top_pos_a;
        $twiz_move_left_pos_a = ($twiz_move_left_pos_a=='') ? 'NULL' : $twiz_move_left_pos_a;
        $twiz_move_top_pos_b  = ($twiz_move_top_pos_b=='') ? 'NULL' : $twiz_move_top_pos_b;
        $twiz_move_left_pos_b = ($twiz_move_left_pos_b=='') ? 'NULL' : $twiz_move_left_pos_b;
        $twiz_start_top_pos   = ($twiz_start_top_pos=='') ? 'NULL' : $twiz_start_top_pos;
        $twiz_start_left_pos  = ($twiz_start_left_pos=='') ? 'NULL' : $twiz_start_left_pos;
        
        $twiz_options_a = esc_attr(trim($_POST['twiz_'.self::F_OPTIONS_A]));
        $twiz_options_b = esc_attr(trim($_POST['twiz_'.self::F_OPTIONS_B]));

        $twiz_extra_js_a = esc_attr(trim( $_POST['twiz_'.self::F_EXTRA_JS_A]));    
        $twiz_extra_js_b = esc_attr(trim($_POST['twiz_'.self::F_EXTRA_JS_B]));
        
        $twiz_javascript = esc_attr(trim($_POST['twiz_'.self::F_JAVASCRIPT]));
        
        $twiz_start_delay = esc_attr(trim($_POST['twiz_'.self::F_START_DELAY]));
        $twiz_duration = esc_attr(trim($_POST['twiz_'.self::F_DURATION]));
        $twiz_start_delay = ( $twiz_start_delay == '' ) ? '0' : $twiz_start_delay;
        $twiz_duration = ( $twiz_duration == '' ) ? '0' : $twiz_duration;
                        
                        
        if(($_POST['twiz_'.self::F_ON_EVENT]=='')
        or($_POST['twiz_'.self::F_ON_EVENT]=='Manually')){
            $twiz_lock_event = 1; // by default
        }
        
        if( $id == '' ){ // add new

            $sql = "INSERT INTO ".$this->table." 
                  (".self::F_PARENT_ID."
                  ,".self::F_EXPORT_ID."
                  ,".self::F_SECTION_ID."
                  ,".self::F_STATUS."
                  ,".self::F_TYPE."
                  ,".self::F_LAYER_ID."
                  ,".self::F_ON_EVENT."                  
                  ,".self::F_LOCK_EVENT."                  
                  ,".self::F_LOCK_EVENT_TYPE."                  
                  ,".self::F_START_DELAY."
                  ,".self::F_DURATION."
                  ,".self::F_OUTPUT."
                  ,".self::F_OUTPUT_POS."
                  ,".self::F_JAVASCRIPT."
                  ,".self::F_START_TOP_POS_SIGN."
                  ,".self::F_START_TOP_POS."
                  ,".self::F_START_TOP_POS_FORMAT."
                  ,".self::F_START_LEFT_POS_SIGN."
                  ,".self::F_START_LEFT_POS."    
                  ,".self::F_START_LEFT_POS_FORMAT."    
                  ,".self::F_POSITION."    
                  ,".self::F_ZINDEX."    
                  ,".self::F_EASING_A." 
                  ,".self::F_EASING_B." 
                  ,".self::F_MOVE_TOP_POS_SIGN_A."
                  ,".self::F_MOVE_TOP_POS_A."
                  ,".self::F_MOVE_TOP_POS_FORMAT_A."
                  ,".self::F_MOVE_LEFT_POS_SIGN_A."
                  ,".self::F_MOVE_LEFT_POS_A."
                  ,".self::F_MOVE_LEFT_POS_FORMAT_A."
                  ,".self::F_MOVE_TOP_POS_SIGN_B."
                  ,".self::F_MOVE_TOP_POS_B."
                  ,".self::F_MOVE_TOP_POS_FORMAT_B."
                  ,".self::F_MOVE_LEFT_POS_SIGN_B."
                  ,".self::F_MOVE_LEFT_POS_B."
                  ,".self::F_MOVE_LEFT_POS_FORMAT_B."
                  ,".self::F_OPTIONS_A."
                  ,".self::F_OPTIONS_B."
                  ,".self::F_EXTRA_JS_A."
                  ,".self::F_EXTRA_JS_B."         
                  )VALUES('".$twiz_parent_id."'
                  ,'".$twiz_export_id."'
                  ,'".$twiz_section_id."'
                  ,'".$twiz_status."'
                  ,'".$twiz_type."'
                  ,'".$twiz_layer_id."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_ON_EVENT]))."'
                  ,'".$twiz_lock_event."'
                  ,'".$twiz_lock_event_type."'
                  ,'".$twiz_start_delay."'
                  ,'".$twiz_duration."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT_POS]))."'
                  ,'".$twiz_javascript."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_SIGN]))."'    
                  ,".$twiz_start_top_pos."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_FORMAT]))."'    
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_SIGN]))."'    
                  ,".$twiz_start_left_pos."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_FORMAT]))."' 
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_POSITION]))."'                     
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_ZINDEX]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_EASING_A]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_EASING_B]))."'                  
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A]))."'    
                  ,".$twiz_move_top_pos_a."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A]))."'  
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A]))."'    
                  ,".$twiz_move_left_pos_a."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A]))."' 
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B]))."'                     
                  ,".$twiz_move_top_pos_b."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_B]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B]))."'    
                  ,".$twiz_move_left_pos_b."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_B]))."'    
                  ,'".$twiz_options_a."'                             
                  ,'".$twiz_options_b."'
                  ,'".$twiz_extra_js_a."'                             
                  ,'".$twiz_extra_js_b."'                 
                  );";
            
            $code = $wpdb->query($sql);

            $result = array('id' => $wpdb->insert_id, 'result' => 0);
            
            return $result;

        }else{ // update
            
            $sql = "SELECT ".self::F_EXPORT_ID.", ".self::F_LAYER_ID." FROM ".$this->table." WHERE ".self::F_ID." = '".$id."'";
            $current_value = $wpdb->get_row($sql, ARRAY_A);
        
            $sql = "UPDATE ".$this->table." 
                  SET ".self::F_PARENT_ID." = '".$twiz_parent_id."'
                 ,".self::F_EXPORT_ID." = '".$twiz_export_id."'
                 ,".self::F_SECTION_ID." = '".$twiz_section_id."'
                 ,".self::F_STATUS." = '".$twiz_status."'
                 ,".self::F_TYPE."  = '".$twiz_type."' 
                 ,".self::F_LAYER_ID." = '".$twiz_layer_id."'
                 ,".self::F_ON_EVENT." = '".esc_attr(trim($_POST['twiz_'.self::F_ON_EVENT]))."'
                 ,".self::F_LOCK_EVENT." = '".$twiz_lock_event."'
                 ,".self::F_LOCK_EVENT_TYPE." = '".$twiz_lock_event_type."'
                 ,".self::F_START_DELAY." = '".$twiz_start_delay."'
                 ,".self::F_DURATION." = '".$twiz_duration."'
                 ,".self::F_OUTPUT." = '".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT]))."'
                 ,".self::F_OUTPUT_POS." = '".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT_POS]))."'
                 ,".self::F_JAVASCRIPT." = '".$twiz_javascript."' 
                 ,".self::F_START_TOP_POS_SIGN." = '".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_SIGN]))."'
                 ,".self::F_START_TOP_POS." = ".$twiz_start_top_pos."
                 ,".self::F_START_TOP_POS_FORMAT." = '".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_FORMAT]))."'
                 ,".self::F_START_LEFT_POS_SIGN." = '".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_SIGN]))."'
                 ,".self::F_START_LEFT_POS."  = ".$twiz_start_left_pos."
                 ,".self::F_START_LEFT_POS_FORMAT." = '".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_FORMAT]))."'
                 ,".self::F_POSITION."  = '".esc_attr(trim($_POST['twiz_'.self::F_POSITION]))."'                 
                 ,".self::F_ZINDEX."  = '".esc_attr(trim($_POST['twiz_'.self::F_ZINDEX]))."' 
                 ,".self::F_EASING_A."  = '".esc_attr(trim($_POST['twiz_'.self::F_EASING_A]))."' 
                 ,".self::F_EASING_B."  = '".esc_attr(trim($_POST['twiz_'.self::F_EASING_B]))."'                  
                 ,".self::F_MOVE_TOP_POS_SIGN_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A]))."'
                 ,".self::F_MOVE_TOP_POS_A." = ".$twiz_move_top_pos_a."
                 ,".self::F_MOVE_TOP_POS_FORMAT_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A]))."'
                 ,".self::F_MOVE_LEFT_POS_SIGN_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A]))."'
                 ,".self::F_MOVE_LEFT_POS_A." = ".$twiz_move_left_pos_a."
                 ,".self::F_MOVE_LEFT_POS_FORMAT_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A]))."'
                 ,".self::F_MOVE_TOP_POS_SIGN_B." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B]))."'
                 ,".self::F_MOVE_TOP_POS_B." = ".$twiz_move_top_pos_b."
                 ,".self::F_MOVE_TOP_POS_FORMAT_B." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_B]))."'
                 ,".self::F_MOVE_LEFT_POS_SIGN_B." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B]))."'
                 ,".self::F_MOVE_LEFT_POS_B." = ".$twiz_move_left_pos_b."
                 ,".self::F_MOVE_LEFT_POS_FORMAT_B." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_B]))."'
                 ,".self::F_OPTIONS_A." = '".$twiz_options_a."'
                 ,".self::F_OPTIONS_B." = '".$twiz_options_b."'
                 ,".self::F_EXTRA_JS_A." = '".$twiz_extra_js_a."'
                 ,".self::F_EXTRA_JS_B." = '".$twiz_extra_js_b."'                 
                  WHERE ".self::F_ID." = '".$id."';";
                    
            $code = $wpdb->query($sql);

            $ok = $this->UpdateTwizFunctions( $current_value, $twiz_layer_id, $twiz_section_id);
            
            $result = array('id' => $id, 'result' => $ok);

            return $result;
        }
    }
    
    function saveValue( $id = '', $column = '', $value = '' ){ 
        
        global $wpdb;
            
            if( $id == '' ){return false;}
            if( $column == '' ){return false;}
            
            $column = ( $column == 'delay' ) ? self::F_START_DELAY : $column;    
        
            $sql = "UPDATE ".$this->table." 
                    SET ".$column." = '".$value."'                 
                    WHERE ".self::F_ID." = '".$id."';";
            $code = $wpdb->query($sql);
                       
            return $id;
    }
    
    function switchGlobalStatus(){ 

        $gstatus = get_option('twiz_global_status');
        
        $newglobalstatus = ($gstatus == '0') ? '1' : '0'; // swicth the status value
                
        $code = update_option('twiz_global_status', $newglobalstatus);
    
        $htmlstatus = ($newglobalstatus=='1') ? $this->getHtmlImgStatus('global', self::STATUS_ACTIVE) : $this->getHtmlImgStatus('global', self::STATUS_INACTIVE);

        return $htmlstatus;
    }
    
    private function getImgGlobalStatus(){ 
        
        $gstatus = get_option('twiz_global_status');
        
        $htmlstatus = ($gstatus == '1') ? $this->getHtmlImgStatus('global', self::STATUS_ACTIVE) : $this->getHtmlImgStatus('global', self::STATUS_INACTIVE);

        return $htmlstatus;
    }
       
    private function getHtmlSkinBullets(){
           
        $dirarray = $this->getSkinsDirectory();
       
        $bullet = get_option('twiz_bullet'); 
        $bullet[$this->userid] = (!isset($bullet[$this->userid])) ? '' : $bullet[$this->userid];
        
        $style = ( $bullet[$this->userid] == self::LB_ORDER_UP ) ? ' style="top:0px;z-index:1;"' : ' style="top:22px;z-index:-1;"';
        
        $html_open = '<div id="twiz_skin_bullet"'.$style.'>';
        $html_img = '';
        $html_close = '</div>';
        
        sort($dirarray);
        
        foreach($dirarray as $value){
        
            $html_img .='<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/twiz-bullet.png" id="twiz_skin_'.$value.'" class="twiz-skins" />';
        }

        return $html_open.$html_img.$html_close;
    }
    
    function switchStatus( $id ){ 
    
        global $wpdb;
        
        if( $id == '' ){return false;}
    
        $value = $this->getRow($id);
   
        if($value[self::F_STATUS]=='1') {
            $newstatus = '0'; 
            $newcomment1 = '';   
            $newcomment2 = '// ';     
        }else{ 
            $newcomment1 = '// ';
            $newcomment2 =  '';          
            $newstatus = '1'; 
        }
        
        // swicth the status value
        $sql = "UPDATE ".$this->table." 
                SET ".self::F_STATUS." = '".$newstatus."'
                WHERE ".self::F_ID." = '".$id."'";

        $code = $wpdb->query($sql);
        
        
        $group = ($value[self::F_TYPE] == self::ELEMENT_TYPE_GROUP) ? '_'.self::ELEMENT_TYPE_GROUP : '';
            
        $searchstring = "$(document).twiz".$group."_".$value[self::F_SECTION_ID]."_".str_replace("-","_",sanitize_title_with_dashes($value[self::F_LAYER_ID]))."_".$value[self::F_EXPORT_ID]."();";
            
        // Comment or uncomment functions 
        $updatesql = "UPDATE ".$this->table . " SET
         ". self::F_JAVASCRIPT . " = replace(". self::F_JAVASCRIPT . ", '".$newcomment1.$searchstring."', '".$newcomment2.$searchstring."') 
        ,". self::F_EXTRA_JS_A . " = replace(". self::F_EXTRA_JS_A . ", '".$newcomment1.$searchstring."', '".$newcomment2.$searchstring."') 
        ,". self::F_EXTRA_JS_B . " = replace(". self::F_EXTRA_JS_B . ", '".$newcomment1.$searchstring."', '".$newcomment2.$searchstring."')";
        $ucodecom = $wpdb->query($updatesql);
                    
        if($code){
        
            $htmlstatus = ($newstatus=='1') ? $this->getHtmlImgStatus($id, self::STATUS_ACTIVE) : $this->getHtmlImgStatus($id, self::STATUS_INACTIVE);
        }else{ 
        
            $htmlstatus = ($value[self::F_STATUS]=='1') ? $this->getHtmlImgStatus($id, self::STATUS_ACTIVE) : $this->getHtmlImgStatus($id, self::STATUS_INACTIVE);
        }
        
        return $htmlstatus;
    }
    
    function uninstall(){
    
        global $wpdb;

        if ($wpdb->get_var( "SHOW TABLES LIKE '".$this->table."'" ) == $this->table) {
        
            $sql = "DROP TABLE ". $this->table;
            $wpdb->query($sql);
        }
        
        delete_option('twiz_db_version');
        delete_option('twiz_global_status');
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
        
        return true;
    }
}
?>