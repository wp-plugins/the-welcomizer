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
require_once(dirname(__FILE__).'/twiz.compatibility.class.php');  
require_once(dirname(__FILE__).'/twiz.admin.class.php');
require_once(dirname(__FILE__).'/twiz.menu.class.php');

class Twiz{
    
    // variable declaration
    public $skin;
    public $user_id;
    public $dbVersion;
    public $cssVersion;
    public $pluginUrl;
    public $pluginDir;
    public $uploadDir;
    public $toggle_option;
    public $admin_option;
    public $global_status;
    public $hscroll_status;
    public $BLOG_ID;
    public $network_activated;
    public $override_network_settings;
    public $privacy_question_answered;
    protected $table;
    protected $nonce;
    protected $version;
    protected $pluginName;
    protected $DEFAULT_SECTION;
    protected $import_path_message;
    protected $export_path_message;
    
    // default sections
    protected $DEFAULT_SECTION_HOME;
    protected $DEFAULT_SECTION_EVERYWHERE;
    protected $DEFAULT_SECTION_ALL_CATEGORIES;
    protected $DEFAULT_SECTION_ALL_PAGES;
    protected $DEFAULT_SECTION_ALL_ARTICLES;

    // All Sites constant
    const ALL_SITES = 'all';
    
    // pure section constants 
    const DEFAULT_SECTION_HOME           = 'home'; 
    const DEFAULT_SECTION_EVERYWHERE     = 'everywhere';
    const DEFAULT_SECTION_ALL_CATEGORIES = 'allcategories';
    const DEFAULT_SECTION_ALL_PAGES      = 'allpages';
    const DEFAULT_SECTION_ALL_ARTICLES   = 'allarticles'; // allposts
    
    // default min role level required
    protected $DEFAULT_MIN_ROLE_LEVEL = ''; // see __construct()

    // default skin  constant 
    const DEFAULT_SKIN  = '_default';
    const SKIN_PATH     = '/skins/';
    
    // default number of posts to display
    const DEFAULT_NUMBER_POSTS = '25'; // Last 25 posts
        
    // element type constants 
    const ELEMENT_TYPE_ID    = 'id';
    const ELEMENT_TYPE_CLASS = 'class';
    const ELEMENT_TYPE_NAME  = 'name';
    const ELEMENT_TYPE_TAG   = 'tag';
    const ELEMENT_TYPE_GROUP = 'group';
    const ELEMENT_TYPE_OTHER = 'other';
    
    // status constants
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';
    
    // library order constants
    const LB_ORDER_UP   = 'up';
    const LB_ORDER_DOWN = 'down';
    
    // directional image suffix constants 
    const DIMAGE_N  = 'n';
    const DIMAGE_NE = 'ne';
    const DIMAGE_E  = 'e';
    const DIMAGE_SE = 'se';
    const DIMAGE_S  = 's';
    const DIMAGE_SW = 'sw';
    const DIMAGE_W  = 'w';
    const DIMAGE_NW = 'nw';
    
    // action constants 
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
    const ACTION_OPTIONS        = 'options';
    const ACTION_VIEW           = 'view';
    const ACTION_NEW            = 'addnew';
    const ACTION_EDIT           = 'edit';
    const ACTION_EDIT_TD        = 'tdedit';
    const ACTION_COPY           = 'copy';
    const ACTION_DELETE         = 'delete';
    const ACTION_STATUS         = 'status';
    const ACTION_IMPORT         = 'import';
    const ACTION_IMPORT_FROM_COMPUTER = 'importfromcomputer';
    const ACTION_IMPORT_FROM_SERVER   = 'importfromserver';
    const ACTION_EXPORT         = 'export';
    const ACTION_EXPORT_ALL     = 'exportall';
    const ACTION_LIBRARY        = 'library';
    const ACTION_LIBRARY_STATUS = 'libstatus';
    const ACTION_UPLOAD_LIBRARY = 'uploadlib';
    const ACTION_GLOBAL_STATUS  = 'gstatus';
    const ACTION_HSCROLL_STATUS  = 'hscrollstatus';
    const ACTION_GET_EXPORT_FILE_LIST = 'getexportfilelist';
    const ACTION_DELETE_EXPORT_FILE = 'deleteexportfile';
    const ACTION_SAVE_SECTION       = 'savesection';
    const ACTION_GET_MULTI_SECTION  = 'getmultisection';
    const ACTION_DELETE_SECTION     = 'deletesection';
    const ACTION_EMPTY_SECTION     = 'emptysection';
    const ACTION_GET_FINDANDREPLACE = 'getfindandreplace';
    const ACTION_FAR_REPLACE        = 'far_replace';
    const ACTION_FAR_FIND           = 'far_find';
    const ACTION_SAVE_FAR_PREF_METHOD = 'far_saveprefmethod';
    const ACTION_GET_LIBRARY_DIR    = 'getlibdir';
    const ACTION_LINK_LIBRARY_DIR   = 'linklibdir';
    const ACTION_UNLINK_LIBRARY_DIR = 'unlinklibdir';
    const ACTION_DELETE_LIBRARY = 'deletelib';
    const ACTION_ORDER_LIBRARY  = 'orderlib';
    const ACTION_ORDER_GROUP    = 'ordergroup';
    const ACTION_ADMIN          = 'admin';
    const ACTION_SAVE_ADMIN     = 'adminsave';
    const ACTION_SAVE_SKIN      = 'skinsave';
    const ACTION_GET_MAIN_ADS   = 'getmainads';
    const ACTION_GET_EVENT_LIST = 'geteventlist';
    const ACTION_GET_GROUP      = 'getgroup';
    const ACTION_SAVE_GROUP     = 'savegroup';
    const ACTION_COPY_GROUP     = 'copygroup';
    const ACTION_DELETE_GROUP   = 'deletegroup';
    const ACTION_PRIVACY_SAVE   = 'privacysave';
    const ACTION_GET_VAR_DUMP   = 'getvardump';
    
    // jquery common options constants 
    const JQ_TOP               = 'top: \'10px\'';
    const JQ_BOTTOM            = 'bottom: \'10px\'';
    const JQ_LEFT              = 'left: \'10px\'';
    const JQ_RIGHT             = 'right: \'10px\'';
    const JQ_WITDH             = 'width: \'10px\'';
    const JQ_HEIGHT            = 'height: \'10px\'';
    const JQ_OPACITY           = 'opacity: 0.5';
    const JQ_FONTSIZE          = 'fontSize: \'10px\'';
    const JQ_SCROLLLEFT        = 'scrollLeft: 0';
    const JQ_SCROLLTOP         = 'scrollTop: 0';
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
    
    // jquery jquery-animate-css-rotate-scale options constants 
    const JQ_ACRS_ROTATE       = 'rotate: \'+=45deg\'';
    const JQ_ACRS_SCALE        = 'scale: \'+=1.5\'';
  
    // jquery transform options constants 
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

    // jquery transit options constants 
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
    const JQ_TRANSIT_SCALEX     = 'scaleX: 2';
    const JQ_TRANSIT_SCALEY     = 'scaleY: 2';
    const JQ_TRANSIT_SKEWX     = 'skewX: \'10deg\'';
    const JQ_TRANSIT_SKEWY     = 'skewY: \'10deg\'';

    // jquery transform code snippet constants
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
    
    // jquery jquery-animate-css-rotate-scale code snippet constants
    const CS_ACRS_ROTATE       = '$(\'#sampleid\').rotate(\'45deg\');';
    const CS_ACRS_SCALE        = '$(\'#sampleid\').scale(1.5);';
    const CS_ACRS_CHAINING     = '$(\'#sampleid\').scale(1.5).rotate(\'45deg\');';
    
    // jquery transit code snippet constants
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
    const CS_TRANSIT_SCALEX      = '$(\'#sampleid\').css({ scaleX: 2 });';
    const CS_TRANSIT_SCALEY      = '$(\'#sampleid\').css({ scaleY: 2 });';
    const CS_TRANSIT_SKEWX       = '$(\'#sampleid\').css({ skewX: \'10deg\' });';
    const CS_TRANSIT_SKEWY       = '$(\'#sampleid\').css({ skewY: \'10deg\' });';
    const CS_TRANSIT_TRANSITION  = '$(\'#sampleid\').transition({ opacity: 0.1, scale: 0.5, 1000, \'in\', function() { });';

    // jquery Rotate3Di code snippet constants 
    const CS_ROTATE3DI = '$(\'#sampleid\').rotate3Di(\'+=180\', 2000);';
    
    // table field constants 
    const F_ID                     = 'id';
    const F_BLOG_ID                = 'blog_id';
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
    const F_DURATION_B             = 'duration_b';
    const F_OUTPUT                 = 'output';
    const F_OUTPUT_POS             = 'output_pos';
    const F_JAVASCRIPT             = 'javascript';
    const F_CSS                    = 'css';
    const F_START_ELEMENT_TYPE     = 'start_element_type';
    const F_START_ELEMENT          = 'start_element';
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
    const F_MOVE_ELEMENT_TYPE_A    = 'move_element_type_a';
    const F_MOVE_ELEMENT_A         = 'move_element_a';
    const F_MOVE_TOP_POS_SIGN_A    = 'move_top_pos_sign_a';
    const F_MOVE_TOP_POS_A         = 'move_top_pos_a';
    const F_MOVE_TOP_POS_FORMAT_A  = 'move_top_pos_format_a';
    const F_MOVE_LEFT_POS_SIGN_A   = 'move_left_pos_sign_a';
    const F_MOVE_LEFT_POS_A        = 'move_left_pos_a';
    const F_MOVE_LEFT_POS_FORMAT_A = 'move_left_pos_format_a';
    const F_MOVE_ELEMENT_TYPE_B    = 'move_element_type_b';
    const F_MOVE_ELEMENT_B         = 'move_element_b';
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
    const F_GROUP_ORDER            = 'group_order';
    const F_ROW_LOCKED             = 'row_locked';
 
    // Field UI constants keys
    const KEY_USER_ID      = 'userid';
    const KEY_BULLET_POS   = 'bulletpos';
    
    // Field constants keys
    const KEY_FILENAME      = 'filename';
    const KEY_DIRECTORY     = 'directory';
    const KEY_ORDER         = 'order';
    const KEY_TITLE         = 'title';
    const KEY_VISIBILITY    = 'visibility';
    const KEY_COOKIE_CONDITION = 'cookie_condition';
    const KEY_COOKIE           = 'cookie';
    const KEY_COOKIE_NAME      = 'cookie_name';
    const KEY_COOKIE_OPTION_1  = 'cookie_option_1';
    const KEY_COOKIE_OPTION_2  = 'cookie_option_2';
    const KEY_COOKIE_WITH      = 'cookie_with';
    const KEY_COOKIE_SCOPE     = 'cookie_scope';
    const KEY_SHORTCODE        = 'shortcode';
    const KEY_SHORTCODE_HTML   = 'shortcode_html';
    const KEY_MULTI_SECTIONS   = 'multi_sections';
    const KEY_CUSTOM_LOGIC     = 'custom_logic';
    
    // VARDUMP constants key
    const KEY_DISPLAY_VAR_DUMP   = 'display_var_dump';    
    
    // Toggle constants keys
    const KEY_TOGGLE_GROUP   = 'toggle_group';
    const KEY_TOGGLE_ADMIN   = 'toggle_admin';
    const KEY_TOGGLE_LIBRARY = 'toggle_library';
    const KEY_TOGGLE_FAR     = 'toggle_far';
    const KEY_TOGGLE_EXPORT  = 'toggle_export';
    
    // Libary constants keys    
    const KEY_SORT_LIB_DIR = 'sort_lib_dir';
        
    // Output constants keys
    const KEY_OUTPUT             = 'output';
    const KEY_OUTPUT_COMPRESSION = 'output_compression';
    
    // Default jQuery constant key
    const KEY_REGISTER_JQUERY = 'register_jquery';
    const KEY_REGISTER_JQUERY_TRANSIT = 'register_jquery_transition';
    const KEY_REGISTER_JQUERY_TRANSFORM = 'register_jquery_transform';
    const KEY_REGISTER_JQUERY_ROTATE3DI = 'register_jquery_rotate3di';
    const KEY_REGISTER_JQUERY_ANIMATECSSROTATESCALE = 'register_jquery_animatecssrotatescale';
    const KEY_REGISTER_JQUERY_EASING = 'register_jquery_easing';
    
    // Minimal role level constant key
    const KEY_MIN_ROLE_LEVEL   = 'min_role_level';
    const KEY_MIN_ROLE_ADMIN   = 'min_role_admin';
    const KEY_MIN_ROLE_LIBRARY = 'min_role_library';
    
    // the_content filter constant key
    const KEY_THE_CONTENT_FILTER = 'the_content_filter';    
    
    // Output protected constant key
    const KEY_OUTPUT_PROTECTED = 'output_protected';
    
    // Find & replace method constant key
    const KEY_PREFERED_METHOD = 'prefered_method';
    
    // Promote this plugin constant key
    const KEY_PROMOTE_PLUGIN = 'promote_plugin';
    const KEY_PROMOTE_POSITION = 'promote_position';
    
    // FB like constant key
    const KEY_FB_LIKE = 'fb_like';

    // Deactivation constant key
    const KEY_DELETE_ALL = 'delete_all';
    
    // Deactivation constant key
    const KEY_REMOVE_CREATED_DIRECTORIES = 'remove_created_directories';
    
    // Extra easing key
    const KEY_EXTRA_EASING = 'extra_easing';
    
    // Number Posts to display key
    const KEY_NUMBER_POSTS = 'number_posts';

    // Starting position by default key
    const KEY_STARTING_POSITION = 'starting_position';
    const KEY_POSITIONING_METHOD = 'positioning_method';
    
    // Visibility constant
    const VISIBILITY_EVERYONE = 'everyone';
    const VISIBILITY_VISITORS = 'visitors';
    const VISIBILITY_MEMBERS = 'members';
    const VISIBILITY_ADMINS = 'admins';
    
    // Output constants  
    const OUTPUT_HEADER = 'wp_head';
    const OUTPUT_FOOTER = 'wp_footer';
    
    // extension constants
    const EXT_JS   = 'js';
    const EXT_CSS  = 'css';
    const EXT_TWZ  = 'twz';
    const EXT_TWIZ = 'twiz';
    const EXT_XML  = 'xml';
    
    // Format constants
    const FORMAT_PIXEL    = 'px';
    const FORMAT_PERCENT  = '%';
    const FORMAT_EM       = 'em';
    const FORMAT_INCH     = 'in';
    
    // on event constants
    const EV_PREFIX_ON  = 'on';
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

    // starting position constants
    const POS_NO_POS   = '';
    const POS_ABSOLUTE = 'absolute';
    const POS_RELATIVE = 'relative';
    const POS_FIXED    = 'fixed';
    const POS_STATIC   = 'static';
    
    // extra easing constants
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

     // transit easing constants
    const TRANSIT_EASE   = 'ease';
    const TRANSIT_IN     = 'in';
    const TRANSIT_OUT    = 'out';
    const TRANSIT_IN_OUT = 'in-out';
    const TRANSIT_SNAP   = 'snap';
    
    // positioning constants
    const POS_TOP_LEFT = 'Top &amp; Left';
    const POS_TOP_RIGHT = 'Top &amp; Right';
    const POS_BOTTOM_LEFT = 'Bottom - Left';
    const POS_BOTTOM_RIGHT = 'Bottom - Right';
    
    // shortcode constants
    const SC_WP_UPLOAD_DIR = '[twiz_wp_upload_dir]';
    
    // default position constants    
    const DEFAULT_STARTING_POSITION = self::POS_RELATIVE;
    const DEFAULT_POSITIONING_METHOD = self::POS_TOP_LEFT;
    const DEFAULT_PROMOTE_POSITION = self::POS_BOTTOM_RIGHT;
                                         
    // extra easing array
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

    // transit excluded extra easing
    protected $array_transit_excluded_extra_easing = array(self::EASEINELASTIC
                                                          ,self::EASEOUTELASTIC
                                                          ,self::EASEINOUTELASTIC
                                                          ,self::EASEINBOUNCE
                                                          ,self::EASEOUTBOUNCE 
                                                          ,self::EASEINOUTBOUNCE
                                                          );
                                         
    // transit easing array
    protected $array_transit_easing = array(self::TRANSIT_EASE
                                           ,self::TRANSIT_IN
                                           ,self::TRANSIT_OUT
                                           ,self::TRANSIT_IN_OUT
                                           ,self::TRANSIT_SNAP
                                           );


    // Position array
    protected $array_position = array(self::POS_NO_POS
                                     ,self::POS_ABSOLUTE   
                                     ,self::POS_RELATIVE
                                     ,self::POS_FIXED
                                     ,self::POS_STATIC 
                                     );
    
    // Positioning array
    protected $array_positioning_method = array(self::POS_TOP_LEFT
                                               ,self::POS_TOP_RIGHT 
                                               );

    // Format array
    private $array_format = array(self::FORMAT_PIXEL     
                                 ,self::FORMAT_PERCENT  
                                 ,self::FORMAT_EM  
                                 ,self::FORMAT_INCH 
                                 );

    // on event array 
    private $array_element_type = array(self::ELEMENT_TYPE_ID      
                                       ,self::ELEMENT_TYPE_CLASS  
                                       ,self::ELEMENT_TYPE_NAME  
                                       ,self::ELEMENT_TYPE_TAG  
                                       ,self::ELEMENT_TYPE_OTHER 
                                       );

    // section constants array
    protected $array_default_section = array(); // __contruct()
    protected $array_default_section_noblogid = array(); // __contruct()

    // on event array 
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
 
    // directional array image suffix 
    private $array_arrows = array(self::DIMAGE_N   
                                 ,self::DIMAGE_NE    
                                 ,self::DIMAGE_E  
                                 ,self::DIMAGE_SE     
                                 ,self::DIMAGE_S    
                                 ,self::DIMAGE_SW  
                                 ,self::DIMAGE_W  
                                 ,self::DIMAGE_NW  
                                 );

    // action array used to exclude ajax container
    protected $array_action_excluded = array(self::ACTION_MENU   
                                          ,self::ACTION_SAVE    
                                          ,self::ACTION_NEW     
                                          ,self::ACTION_EDIT   
                                          ,self::ACTION_COPY
                                          ,self::ACTION_DELETE
                                          ,self::ACTION_DROP_ROW
                                          ,self::ACTION_COPY_GROUP
                                          ,self::ACTION_DELETE_GROUP
                                          ,self::ACTION_SAVE_GROUP
                                          ,self::ACTION_ORDER_GROUP
                                          ,self::ACTION_FAR_FIND
                                          ,self::ACTION_IMPORT_FROM_SERVER
                                          ,self::ACTION_GET_VMENU
                                          ,self::ACTION_GET_VAR_DUMP
                                          );

    // jQuery jquery-animate-css-rotate-scale code snippets array
    private $array_jQuery_acrs_code_snippets = array(self::CS_ACRS_ROTATE
                                                     ,self::CS_ACRS_SCALE
                                                     ,self::CS_ACRS_CHAINING
                                                     );

    // jQuery transform code snippets array
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

    // jQuery transit code snippets array
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
                                                       ,self::CS_TRANSIT_SCALEX
                                                       ,self::CS_TRANSIT_SCALEY
                                                       ,self::CS_TRANSIT_SKEWX
                                                       ,self::CS_TRANSIT_SKEWY
                                                       ,self::CS_TRANSIT_TRANSITION
                                                       );

    // jQuery common options array
    private $array_jQuery_options = array(self::JQ_TOP
                                         ,self::JQ_BOTTOM
                                         ,self::JQ_LEFT
                                         ,self::JQ_RIGHT
                                         ,self::JQ_WITDH
                                         ,self::JQ_HEIGHT
                                         ,self::JQ_OPACITY
                                         ,self::JQ_FONTSIZE
                                         ,self::JQ_SCROLLLEFT
                                         ,self::JQ_SCROLLTOP
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

    // jQuery jquery-animate-css-rotate-scale options array
    private $array_jQuery_acrs_options = array(self::JQ_ACRS_ROTATE
                                              ,self::JQ_ACRS_SCALE
                                              );

    // jQuery transform options array
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

    // jQuery transit options array
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
                                                 ,self::JQ_TRANSIT_SCALEX
                                                 ,self::JQ_TRANSIT_SCALEY
                                                 ,self::JQ_TRANSIT_SKEWX
                                                 ,self::JQ_TRANSIT_SKEWY
                                                 );
                                           
    // twiz options array
    private $array_twiz_snippets  = array('$(document).twizRepeat();'
                                         ,'$(document).twizRepeat(10);'
                                         ,'$(document).twizReplay();'
                                         );
    // jquery options array 
    private $array_jQuery_snippets  = array('$(\'#sampleid\').attr({\'value\':\'Hello\'});'
                                           ,'$(\'#sampleid\').css({\'display\':\'block\'});'
                                           ,'$(\'#sampleid\').css({\'visibility\':\'visible\'});'
                                           ,'$(\'#sampleid\').fadeIn(\'slow\',function(){});'
                                           ,'$(\'#sampleid\').fadeOut(\'slow\',function(){});'
                                           ,'$(\'#sampleid\').hide(\'slow\',function(){});'
                                           ,'$(\'#sampleid\').show(\'slow\',function(){});'
                                           ,'$(\'#sampleid\').hover(function(){},function(){});'
                                           ,'$(\'#sampleid\').toggle(\'slow\',function(){});'
                                           ,'$(\'#sampleid\').animate({\'opacity\':0}, 1000, \'linear\', function(){});'
                                           ,'$(\'#sampleid\').stop().animate({\'opacity\':0}, 1000, \'linear\', function(){});'
                                           );
    // css options array 
    private $array_css_snippets  = array('#sampleid { display: none; }'
                                        ,'#sampleid { visibility: hidden; }'
                                        ,'#sampleid { opacity:0;filter:alpha(opacity=0); }'
                                        ,'#sampleid { background-image: url(\'[twiz_wp_upload_dir]/my-image.png\'); }'
                                        ,'body { overflow-x: hidden; overflow-y: auto; }'
                                        );

    private $array_css_shortcode  = array(self::SC_WP_UPLOAD_DIR);
                               

    // XML MULTI-VERSION mapping values. Do not modify values.
    protected $array_twz_section_mapping = array(self::F_SECTION_ID          => 'SAA'
                                                ,self::F_STATUS              => 'SBA'
                                                ,self::KEY_VISIBILITY        => 'SCA'
                                                ,self::F_BLOG_ID             => 'SDA'
                                                ,self::KEY_TITLE             => 'SEA' 
                                                ,self::KEY_SHORTCODE         => 'SFA'
                                                ,self::KEY_SHORTCODE_HTML    => 'SGA'
                                                ,self::KEY_MULTI_SECTIONS    => 'SHA'
                                                ,self::KEY_CUSTOM_LOGIC      => 'SIA'
                                                ,self::KEY_COOKIE_CONDITION  => 'SJA'
                                                ,self::KEY_COOKIE_NAME       => 'SKA'
                                                ,self::KEY_COOKIE_OPTION_1   => 'SLA'
                                                ,self::KEY_COOKIE_OPTION_2   => 'SMA'
                                                ,self::KEY_COOKIE_WITH       => 'SNA'
                                                ,self::KEY_COOKIE_SCOPE      => 'SOA'
                                                );
                                                
    // XML MULTI-VERSION mapping values. Do not modify values.
    protected $array_twz_mapping = array(self::F_EXPORT_ID                => 'AA'
                                        ,self::F_BLOG_ID                  => 'AB'
                                        ,self::F_SECTION_ID               => 'AH' 
                                        ,self::F_PARENT_ID                => 'AP'
                                        ,self::F_STATUS                   => 'BB'
                                        ,self::F_TYPE                     => 'BL' 
                                        ,self::F_LAYER_ID                 => 'CC'    
                                        ,self::F_ON_EVENT                 => 'DA'
                                        ,self::F_LOCK_EVENT               => 'DB'
                                        ,self::F_LOCK_EVENT_TYPE          => 'DC'
                                        ,self::F_START_DELAY              => 'DD'
                                        ,self::F_DURATION                 => 'EE'
                                        ,self::F_DURATION_B               => 'EF'
                                        ,self::F_OUTPUT                   => 'EG'
                                        ,self::F_OUTPUT_POS               => 'EJ'
                                        ,self::F_JAVASCRIPT               => 'EL'
                                        ,self::F_CSS                      => 'EM'
                                        ,self::F_START_ELEMENT_TYPE       => 'FA'
                                        ,self::F_START_ELEMENT            => 'FB'
                                        ,self::F_START_TOP_POS_SIGN       => 'FF'
                                        ,self::F_START_TOP_POS_FORMAT     => 'GF'  
                                        ,self::F_START_TOP_POS            => 'GG'    
                                        ,self::F_START_LEFT_POS_SIGN      => 'HH'
                                        ,self::F_START_LEFT_POS_FORMAT    => 'IF'
                                        ,self::F_START_LEFT_POS           => 'II'
                                        ,self::F_POSITION                 => 'JJ'
                                        ,self::F_ZINDEX                   => 'JL'
                                        ,self::F_EASING_A                 => 'KD'
                                        ,self::F_EASING_B                 => 'KH'
                                        ,self::F_MOVE_ELEMENT_TYPE_A      => 'KA'
                                        ,self::F_MOVE_ELEMENT_A           => 'KB'
                                        ,self::F_MOVE_TOP_POS_SIGN_A      => 'KK'    
                                        ,self::F_MOVE_TOP_POS_FORMAT_A    => 'LF'
                                        ,self::F_MOVE_TOP_POS_A           => 'LL'
                                        ,self::F_MOVE_LEFT_POS_SIGN_A     => 'MM'
                                        ,self::F_MOVE_LEFT_POS_FORMAT_A   => 'OF'
                                        ,self::F_MOVE_LEFT_POS_A          => 'OO'
                                        ,self::F_MOVE_ELEMENT_TYPE_B      => 'PA'
                                        ,self::F_MOVE_ELEMENT_B           => 'PB'
                                        ,self::F_MOVE_TOP_POS_SIGN_B      => 'PP'
                                        ,self::F_MOVE_TOP_POS_FORMAT_B    => 'QF'
                                        ,self::F_MOVE_TOP_POS_B           => 'QQ'
                                        ,self::F_MOVE_LEFT_POS_SIGN_B     => 'RR'
                                        ,self::F_MOVE_LEFT_POS_FORMAT_B   => 'SF'
                                        ,self::F_MOVE_LEFT_POS_B          => 'SS'
                                        ,self::F_OPTIONS_A                => 'TT'
                                        ,self::F_OPTIONS_B                => 'UU'
                                        ,self::F_EXTRA_JS_A               => 'VV'
                                        ,self::F_EXTRA_JS_B               => 'WW'
                                        ,self::F_GROUP_ORDER              => 'XX'
                                        );

    // Fields array 
    protected $array_fields = array(self::F_ID          
                                   ,self::F_EXPORT_ID 
                                   ,self::F_BLOG_ID                                     
                                   ,self::F_SECTION_ID
                                   ,self::F_PARENT_ID
                                   ,self::F_STATUS 
                                   ,self::F_TYPE        
                                   ,self::F_LAYER_ID 
                                   ,self::F_ON_EVENT   
                                   ,self::F_LOCK_EVENT                                 
                                   ,self::F_LOCK_EVENT_TYPE                                 
                                   ,self::F_START_DELAY          
                                   ,self::F_DURATION    
                                   ,self::F_DURATION_B    
                                   ,self::F_OUTPUT  
                                   ,self::F_OUTPUT_POS
                                   ,self::F_JAVASCRIPT   
                                   ,self::F_CSS   
                                   ,self::F_START_ELEMENT_TYPE
                                   ,self::F_START_ELEMENT                                
                                   ,self::F_START_TOP_POS_SIGN 
                                   ,self::F_START_TOP_POS_FORMAT   
                                   ,self::F_START_TOP_POS           
                                   ,self::F_START_LEFT_POS_SIGN  
                                   ,self::F_START_LEFT_POS_FORMAT 
                                   ,self::F_START_LEFT_POS      
                                   ,self::F_POSITION             
                                   ,self::F_ZINDEX     
                                   ,self::F_EASING_A
                                   ,self::F_EASING_B   
                                   ,self::F_MOVE_ELEMENT_TYPE_A
                                   ,self::F_MOVE_ELEMENT_A
                                   ,self::F_MOVE_TOP_POS_SIGN_A      
                                   ,self::F_MOVE_TOP_POS_FORMAT_A   
                                   ,self::F_MOVE_TOP_POS_A      
                                   ,self::F_MOVE_LEFT_POS_SIGN_A 
                                   ,self::F_MOVE_LEFT_POS_FORMAT_A
                                   ,self::F_MOVE_LEFT_POS_A      
                                   ,self::F_MOVE_ELEMENT_TYPE_B
                                   ,self::F_MOVE_ELEMENT_B                                   
                                   ,self::F_MOVE_TOP_POS_SIGN_B     
                                   ,self::F_MOVE_TOP_POS_FORMAT_B  
                                   ,self::F_MOVE_TOP_POS_B      
                                   ,self::F_MOVE_LEFT_POS_SIGN_B 
                                   ,self::F_MOVE_LEFT_POS_FORMAT_B    
                                   ,self::F_MOVE_LEFT_POS_B     
                                   ,self::F_OPTIONS_A           
                                   ,self::F_OPTIONS_B            
                                   ,self::F_EXTRA_JS_A          
                                   ,self::F_EXTRA_JS_B 
                                   ,self::F_GROUP_ORDER                                    
                                   );
     // fb like button
     const IFRAME_FB_LIKE = '<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FThe-Welcomizer%2F173368186051321&amp;send=false&amp;layout=button_count&amp;width=125&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=20&amp;appId=24077487353" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px; width:125px;" allowTransparency="true"></iframe>';

    // upload import export path constant
    const IMPORT_PATH = '/twiz/';
    const EXPORT_PATH = 'export/';
    const BACKUP_PATH = 'backup/';

    // import max file size constant 
    const IMPORT_MAX_SIZE = '2097152';
    
    function __construct(){
    
        global $wpdb;
        
        $this->dbVersion  = 'v2.8';
        $this->table      = $wpdb->base_prefix .'the_welcomizer';
        $this->BLOG_ID = get_current_blog_id();
        $this->user_id = get_current_user_id(); 
        $this->uploadDir = wp_upload_dir();

        // PLUGIN URL
        $pluginUrl = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        $pluginUrl = str_replace('/includes/','',$pluginUrl);           
        $this->pluginUrl  = $pluginUrl;
        
        $this->override_network_settings = get_option('twiz_override_network_settings'); //  Main switch between get_option and get_site_option

        if( $this->override_network_settings == '' ) {
        
            $this->override_network_settings = '0';
        }
        
        $ok = $this->setDefaultSectionArrayValues();

        if( ( $this->user_id == '' ) or ($this->user_id == '0' ) ){ // for the front-end or installation only

            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                $this->admin_option = get_option('twiz_admin');
                $this->global_status = get_option('twiz_global_status');
                $this->override_network_settings = $this->override_network_settings ; 
                
            }else{
            
                $this->admin_option = get_site_option('twiz_admin');
                $this->global_status = get_site_option('twiz_global_status');    
                $this->override_network_settings = '0';                
            }
                
        }else{ // for the wp admin.

            // PLUGIN DIR
            $pluginDir = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
            $pluginDir = str_replace('/includes/','',$pluginDir);

            // Twiz variable configuration
            $this->version    = '2.8.1'; 
            $this->cssVersion = '2-8';
            $this->pluginDir  = $pluginDir;
            $this->nonce      = wp_create_nonce('twiz-nonce');
            $this->pluginName = __('The Welcomizer', 'the-welcomizer');
            $this->import_path_message = '/wp-content'.self::IMPORT_PATH;
            $this->export_path_message = '/wp-content'.self::IMPORT_PATH.self::EXPORT_PATH;
            
            // options - user settings - default section.
            $ok = $this->setOptions();
            $ok = $this->setUserSettings();
        }
    }
        
    protected function setOptions(){
    
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $this->network_activated = get_option('twiz_network_activated');
            $this->DEFAULT_MIN_ROLE_LEVEL = 'manage_options'; 
            $this->skin = get_option('twiz_skin');
            $this->hscroll_status = get_option('twiz_hscroll_status');
            $this->admin_option = get_option('twiz_admin');
            $this->toggle_option = get_option('twiz_toggle');
            $this->DEFAULT_SECTION = get_option('twiz_setting_menu'); 
            $this->global_status = get_option('twiz_global_status');
            $this->privacy_question_answered = get_option('twiz_privacy_question_answered');
            
        }else{
        
            $this->network_activated = get_site_option('twiz_network_activated');
            $this->DEFAULT_MIN_ROLE_LEVEL = 'manage_network'; 
            $this->skin = get_site_option('twiz_skin');
            $this->hscroll_status = get_site_option('twiz_hscroll_status');
            $this->admin_option = get_site_option('twiz_admin');
            $this->toggle_option = get_site_option('twiz_toggle');
            $this->DEFAULT_SECTION = get_site_option('twiz_setting_menu'); 
            $this->global_status = get_site_option('twiz_global_status');            
            $this->privacy_question_answered = get_site_option('twiz_privacy_question_answered');

        }
        
        return true;
    }
    
    protected function setUserSettings(){

        // Set DEFAULT_SECTION v2.8
        if(!isset($this->DEFAULT_SECTION[$this->user_id])){

            $this->DEFAULT_SECTION[$this->user_id] = '';
            $this->DEFAULT_SECTION[$this->user_id] = $this->DEFAULT_SECTION_HOME;
       
        }
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $code = update_option('twiz_setting_menu', $this->DEFAULT_SECTION);       
            
        }else{
        
            $code = update_site_option('twiz_setting_menu', $this->DEFAULT_SECTION);    
        }  
        
        // Skin
        if(!isset($this->skin[$this->user_id])) $this->skin[$this->user_id] = '';
        if( $this->skin[$this->user_id] == '' ) {
        
            $this->skin[$this->user_id] =  self::SKIN_PATH . self::DEFAULT_SKIN; 
             
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_skin', $this->skin);    
                
            }else{
            
                $code = update_site_option('twiz_skin', $this->skin);   
            }              
        }
              
        // hscroll_status
        if(!isset($this->hscroll_status[$this->user_id])) $this->hscroll_status[$this->user_id] = '';
        if( $this->hscroll_status[$this->user_id] == '' ) {
        
            $this->hscroll_status[$this->user_id] =  '1'; 
             
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
            
                $code = update_option('twiz_hscroll_status', $this->hscroll_status);    
                
            }else{
            
                $code = update_site_option('twiz_hscroll_status', $this->hscroll_status);   
            }              
        }          
        return true;
    }    
    
    private function setDefaultSectionArrayValues(){

            if( ( !is_multisite() ) or ( $this->override_network_settings  == '1' ) ){
                
                if( !is_multisite() ){
                
                    $this->DEFAULT_SECTION_HOME = self::DEFAULT_SECTION_HOME; 
                    $this->DEFAULT_SECTION_EVERYWHERE = self::DEFAULT_SECTION_EVERYWHERE;
                    $this->DEFAULT_SECTION_ALL_CATEGORIES = self::DEFAULT_SECTION_ALL_CATEGORIES;
                    $this->DEFAULT_SECTION_ALL_PAGES = self::DEFAULT_SECTION_ALL_PAGES;
                    $this->DEFAULT_SECTION_ALL_ARTICLES = self::DEFAULT_SECTION_ALL_ARTICLES; // allposts
                    
                }elseif( $this->override_network_settings == '1'){
                
                    $this->DEFAULT_SECTION_HOME = self::DEFAULT_SECTION_HOME.'_'.$this->BLOG_ID; 
                    $this->DEFAULT_SECTION_EVERYWHERE = self::DEFAULT_SECTION_EVERYWHERE.'_'.$this->BLOG_ID;
                    $this->DEFAULT_SECTION_ALL_CATEGORIES = self::DEFAULT_SECTION_ALL_CATEGORIES.'_'.$this->BLOG_ID;
                    $this->DEFAULT_SECTION_ALL_PAGES = self::DEFAULT_SECTION_ALL_PAGES.'_'.$this->BLOG_ID;
                    $this->DEFAULT_SECTION_ALL_ARTICLES = self::DEFAULT_SECTION_ALL_ARTICLES.'_'.$this->BLOG_ID; // allposts

                }
                
            }else{ // single site

                $this->DEFAULT_SECTION_HOME = self::DEFAULT_SECTION_HOME;
                $this->DEFAULT_SECTION_EVERYWHERE = self::DEFAULT_SECTION_EVERYWHERE;
                $this->DEFAULT_SECTION_ALL_CATEGORIES = self::DEFAULT_SECTION_ALL_CATEGORIES;
                $this->DEFAULT_SECTION_ALL_PAGES = self::DEFAULT_SECTION_ALL_PAGES;
                $this->DEFAULT_SECTION_ALL_ARTICLES = self::DEFAULT_SECTION_ALL_ARTICLES; // allposts
            }
             
            // section constants array
            $this->array_default_section = array($this->DEFAULT_SECTION_HOME    
                                                ,$this->DEFAULT_SECTION_EVERYWHERE  
                                                ,$this->DEFAULT_SECTION_ALL_CATEGORIES
                                                ,$this->DEFAULT_SECTION_ALL_PAGES 
                                                ,$this->DEFAULT_SECTION_ALL_ARTICLES 
                                                );
                                                
            $this->array_default_section_noblogid = array(self::DEFAULT_SECTION_HOME    
                                                ,self::DEFAULT_SECTION_EVERYWHERE  
                                                ,self::DEFAULT_SECTION_ALL_CATEGORIES
                                                ,self::DEFAULT_SECTION_ALL_PAGES 
                                                ,self::DEFAULT_SECTION_ALL_ARTICLES 
                                                );  
        return true;
    }
   
    protected function setPositioningMethod(){
    
        if( !isset($this->admin_option[self::KEY_POSITIONING_METHOD]) ) $this->admin_option[self::KEY_POSITIONING_METHOD] = '';
 
        if( $this->admin_option[self::KEY_POSITIONING_METHOD] == self::POS_TOP_LEFT ){
                
            $this->label_y = __('Top', 'the-welcomizer');
            $this->label_x = __('Left', 'the-welcomizer');

        }else{  
              
            $this->label_y = __('Top', 'the-welcomizer');
            $this->label_x = __('Right', 'the-welcomizer');
        }  
        
        return true;
    }    
    
    function getHTMLPrivacyQuestion(){
    
            $checked = ' checked="checked"';
            $input_jquery = '<input type="checkbox" id="twiz_privacy_'.self::KEY_REGISTER_JQUERY.'" name="twiz_privacy_'.self::KEY_REGISTER_JQUERY.'"'.$checked.'/>';
            $input_fb = '<input type="checkbox" id="twiz_privacy_'.self::KEY_FB_LIKE.'" name="twiz_privacy_'.self::KEY_FB_LIKE.'"'.$checked.'/>';
            $input_delete_all = '<input type="checkbox" id="twiz_privacy_'.self::KEY_DELETE_ALL.'" name="twiz_privacy_'.self::KEY_DELETE_ALL.'"/>';
            $input_remove_created_directories = '<input type="checkbox" id="twiz_privacy_'.self::KEY_REMOVE_CREATED_DIRECTORIES.'" name="twiz_privacy_'.self::KEY_REMOVE_CREATED_DIRECTORIES.'"/>';
            
            $html = '<div id="twiz_background"></div>
            <div id="twiz_master">
                <div id="twiz_header" class="twiz-reset-nav twiz-corner-top"><div id="twiz_head_logo"></div>
                    <span id="twiz_head_title">'.__('Plugin Installation Setup', 'the-welcomizer').'</span>
                    <span id="twiz_head_version">'.__('Please answer these questions to continue.', 'the-welcomizer').'</span>
                </div>
                <div>
                    <table class="twiz-table-form" cellspacing="0" cellpadding="0">
                    <tr><td colspan="2" class="twiz-admin-form-td-left"><b>'.__('Basic Setting', 'the-welcomizer').'</b></td></tr>   
                    <tr><td colspan="2"></td></tr>   
                    <tr><td class="twiz-admin-form-td-left">'.__('Include the jQuery default library on the front-end', 'the-welcomizer').':</td><td class="twiz-form-td-right twiz-form-small twiz-text-left">'.$input_jquery.'</td></tr>
                    <tr><td colspan="2"></td></tr>                    
                    <tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr>
                    <tr><td colspan="2" class="twiz-admin-form-td-left"><b>'.__('Privacy Setting', 'the-welcomizer').'</b></td></tr>   
                    <tr><td colspan="2"></td></tr>   
                    <tr><td class="twiz-admin-form-td-left">'.__('Authorize facebook like button inside the plugin', 'the-welcomizer').':</td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><label for="twiz_extra_easing">'.$input_fb .'</td></tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr>          
                    <tr><td colspan="2" class="twiz-admin-form-td-left"><b>'.__('Removal Settings', 'the-welcomizer').'</b></td></tr>   
                    <tr><td colspan="2"></td></tr>   
                    <tr><td class="twiz-admin-form-td-left">'.__('Delete all settings when disabling the plugin', 'the-welcomizer').':</td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><label for="twiz_extra_easing">'.$input_delete_all .'</td></tr>                    
                    <tr><td class="twiz-admin-form-td-left">'.__('Delete created directories when disabling the plugin', 'the-welcomizer').':</td><td class="twiz-form-td-right twiz-form-small twiz-text-left"><label for="twiz_extra_easing">'.$input_remove_created_directories .'</td></tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td class="twiz-td-save" colspan="2"><span id="twiz_privacy_save_img_box" class="twiz-loading-gif-save"></span><input type="button" name="twiz_privacy_save" id="twiz_privacy_save" class="button-primary" value="'.__('Continue', 'the-welcomizer').'"/></td></tr>
                    </table>
                </div>
                <div class="twiz-clear"></div><div id="twiz_footer" class="twiz-reset-nav twiz-corner-bottom twiz-green"><div class="twiz-spacer-footer"></div>'.__('You will be able to access these settings anytime under the Admin section.', 'the-welcomizer').'</div>
            </div>';
            
        $jquery = '<script>
//<![CDATA[
jQuery(document).ready(function($) {';


        if( ( $this->admin_option[self::KEY_DISPLAY_VAR_DUMP] == true ) or ( TWIZ_FORCE_VARDUMP ==  true ) ){
        
         $jquery .= '
         $("#twiz_var_dump").css({"display":"block","height":$(window).height()-65, "width":$(window).width()-720});';
         
        }
        $jquery .= '
        $.ajaxSetup({ cache: false });
        $("#twiz_privacy_save").click(function(){
             $("#twiz_privacy_save_img_box").show();
            $("#twiz_privacy_save_img_box").attr("class","twiz-save twiz-loading-gif-save");
         $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.self::ACTION_PRIVACY_SAVE.'",
         "twiz_privacy_'.self::KEY_REGISTER_JQUERY.'": $("#twiz_privacy_'.self::KEY_REGISTER_JQUERY.'").is(":checked"),
         "twiz_privacy_'.self::KEY_FB_LIKE.'": $("#twiz_privacy_'.self::KEY_FB_LIKE.'").is(":checked"),
         "twiz_privacy_'.self::KEY_DELETE_ALL.'": $("#twiz_privacy_'.self::KEY_DELETE_ALL.'").is(":checked"),
         "twiz_privacy_'.self::KEY_REMOVE_CREATED_DIRECTORIES.'": $("#twiz_privacy_'.self::KEY_REMOVE_CREATED_DIRECTORIES.'").is(":checked")
        }, function(data){                
            $("#twiz_master").html(data);
        }).fail(function(){ alert("'.__('An error occured, please try again.', 'the-welcomizer').'"); });
        });
});
//]]>
</script>';

        if( ( $this->admin_option[self::KEY_DISPLAY_VAR_DUMP] == true ) or ( TWIZ_FORCE_VARDUMP ==  true ) ){
        
            $myTwizMenu = new TwizMenu();
            $html .= '<div id="twiz_var_dump" title="'.__('Click to Refresh', 'the-welcomizer').'">'.$myTwizMenu->getVarDump().'</div>';
        }
        return $html.$jquery;
    }

    function twizIt(){

        $html = '<div id="twiz_plugin">';
        
        if( $this->privacy_question_answered == true ){
        
            $html .= $this->getHtmlHScrollStatus();
            $html .= '<div id="twiz_like">';
            
            if( $this->admin_option[self::KEY_FB_LIKE] != 1 ){
            
                $html.= self::IFRAME_FB_LIKE; 
            }
            
            $html.='</div>';
            $html .= '<div id="twiz_background"></div>';
            $html .= '<div id="twiz_master">';
            
            $html .= $this->getHtmlSkinBullets();
            $html .= $this->getHtmlGlobalStatus();
            
            if( is_multisite() ){
            
                $html .= $this->getHtmlNetworkStatus();
            }
            
            $html .= $this->getHtmlHeader();
            
            $myTwizMenu = new TwizMenu();
            
            $html .= '<div><div id="twiz_menu" class="twiz-reset-nav"><div id="twiz_ajax_menu">'.$myTwizMenu->getHtmlMenu().'</div>';
            $html .= '<div id="twiz_option_menu"><div id="twiz_more_menu" class="twiz-noborder-right twiz-icon-menu" title="'.__('Browse all sections', 'the-welcomizer').'"></div>';
            $html .= '<div id="twiz_add_menu" class="twiz-noborder-right twiz-icon-plus" title="'.__('Create a new section', 'the-welcomizer').'"></div></div>';
            $html .= '<div id="twiz_loading_menu"><div class="twiz-menu twiz-noborder-right"><div class="twiz-loading-bar"></div></div></div>';
            $html .= '<div class="twiz-clear"></div></div>';
            $html .= '<div id="twiz_sub_container"></div>';
            
            $html .= $myTwizMenu->getHtmlListMenu();
            $html .= $this->getHtmlList();
            $html .= $myTwizMenu->getHtmlListSubMenu();
            $html .= $this->getHtmlFooter();
            $html .= $myTwizMenu->getHtmlFooterMenu();
            
            $html .= '</div>';         
            $html .= '<div id="twiz_var_dump" title="'.__('Click to Refresh', 'the-welcomizer').'">'.$myTwizMenu->getVarDump().'</div>';
            $html .= $myTwizMenu->getHtmlVerticalMenu();
            $html .= '<div id="twiz_view_box"></div><div id="twiz_view_image"></div>';

            $html .= $this->preloadImages();     
            
        }else{ // Ask privacy question.
            
            $html .= $this->getHTMLPrivacyQuestion();  
        }
        
        $html .= '</div>';

        return $html;
    }
    
    function getPromotePluginImageLink(){
    
        if( $this->admin_option[self::KEY_PROMOTE_PLUGIN] == '1' ){
        
            switch( $this->admin_option[self::KEY_PROMOTE_POSITION] ){

                case self::POS_BOTTOM_RIGHT :
                
                    $position = 'right:0;bottom:0;';
                    break;
                    
                case self::POS_BOTTOM_LEFT :
                
                    $position = 'left:0;bottom:0;';
                    break;                
                    
                default:
                
                    $position = 'right:0;bottom:0;';
            }
            
            $image_link = '<style type="text/css">#twiz-promote-plugin{opacity:0.65;filter:alpha(opacity=65);position:fixed;'.$position.'}</style>';
            $image_link .= '<a href="http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/" title="Quickly create animations for your WordPress blog."><img src="'.$this->pluginUrl.'/images/twiz-logo.png" id="twiz-promote-plugin" width="50" height="39"/></a>';

            return $image_link;
        }
        
        return '';
    }
    
    private function getHtmlHScrollStatus(){
    
        return '<div id="twiz_hscroll_status" class="twiz-corner-bottom">'.$this->getImgHScrollStatus().'<div class="twiz-arrow twiz-arrow-e twiz-hscroll-arrow"></div></div>';
    }
    
    private function getHtmlGlobalStatus(){
    
        return '<div id="twiz_global_status" class="twiz-corner-top">'.$this->getImgGlobalStatus().'</div>';
    }
    
    private function getHtmlNetworkStatus(){
    
        return '<div id="twiz_network_status" class="twiz-icon-network" title="'.__('Connected to the network','the-welcomizer').'"></div>';
    }    
    
    
    private function getHtmlHeader(){
        
        $header = '<div id="twiz_header" class="twiz-reset-nav twiz-corner-top"><div id="twiz_head_logo"></div><span id="twiz_head_title"><a href="http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/" target="_blank">'.$this->pluginName.'</a></span><div id="twiz_head_version"><a href="'.$this->pluginUrl.'/readme.txt" target="_blank">'.__('Version', 'the-welcomizer').' '.$this->version.'</a></div>
</div><div class="twiz-clear"></div>
    ';
        
        return $header;
    }

    private function getHtmlFooter(){

        $html = '
<div class="twiz-clear"></div><div id="twiz_footer" class="twiz-reset-nav twiz-corner-bottom">';
        
        $html .= '</div>';
        
        return $html;
    }    
    
    protected function getSectionBlogId( $section_id = ''){
    
        $myTwizMenu = new TwizMenu();

        if( in_array($section_id, $myTwizMenu->array_default_section) ){
        
            $sections = $myTwizMenu->array_hardsections; // default sections
            
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                $blogid_array = array($this->BLOG_ID); 
            
            }else{
            
                $blogid_array = $sections[$section_id][self::F_BLOG_ID];            
            }
            
        }else{
        
            $sections = $myTwizMenu->array_sections;
            
            if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

                $blogid_array = array($this->BLOG_ID); 
            
            }else{
            
                $blogid_array = $sections[$section_id][self::F_BLOG_ID];            
            }
        }
  
     
        return $blogid_array;  
    }
    
    protected function createHtmlList( $listarray = array(), $saved_id = '', $parent_id = '', $action = '' ){ 

        if( count($listarray) == 0 ){return false;}
        
        $bullet = ' &#8226;';
        $opendiv = '';
        $closediv = '';
        $rowcolor = '';
        $saveeffect = '';
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $twiz_order_by = get_option('twiz_order_by');      
            
        }else{
        
            $twiz_order_by = get_site_option('twiz_order_by');   
        }         

        $twiz_order_by[$this->user_id] = (!isset($twiz_order_by[$this->user_id])) ? '' : $twiz_order_by[$this->user_id];
        $twiz_order_by_status = ($twiz_order_by[$this->user_id] == self::F_STATUS)? $bullet : '' ;
        $twiz_order_by_on_event = ($twiz_order_by[$this->user_id] == self::F_ON_EVENT)? $bullet : '' ;
        $twiz_order_by_element = ($twiz_order_by[$this->user_id] == self::F_LAYER_ID)? $bullet : '' ;
        $twiz_order_by_delay = ($twiz_order_by[$this->user_id] == self::F_START_DELAY)? $bullet : '' ;
        $twiz_order_by_duration = ($twiz_order_by[$this->user_id] == self::F_DURATION)? $bullet : '' ;
        
        $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'];
        
        // ajax container 
        if( !in_array($_POST['twiz_action'], $this->array_action_excluded) ){
        
            $opendiv = '<div id="twiz_container">';
            $closediv = '</div>';
        }
        

        // save effect
        if( $saved_id != '' ){

            $saveeffect .= 'if($("#twiz_list_div_element_'.$saved_id.'").offset().top > $(window).height()-20){$("html, body").animate({ scrollTop: $("#twiz_list_div_element_'.$saved_id.'").offset().top - 300 }, "slow", function(){
    $("#twiz_list_div_element_'.$saved_id.'").css({"color":"green"});
    $("#twiz_list_div_element_'.$saved_id.'").animate({opacity:0}, 300, 
    function(){$("#twiz_list_div_element_'.$saved_id.'").animate({opacity:1}, 300, 
    function(){});
});});
}else{
    $("#twiz_list_div_element_'.$saved_id.'").css({"color":"green"});
        $("#twiz_list_div_element_'.$saved_id.'").animate({opacity:0}, 300, 
        function(){$("#twiz_list_div_element_'.$saved_id.'").animate({opacity:1}, 300, 
        function(){});
    });
}';
        }
        
        if( (( $action == self::ACTION_SAVE_GROUP ) 
        or ( $action == self::ACTION_COPY_GROUP ) )
        and ( $saved_id != '' ) ) {
            $exportid = $this->getValue($saved_id, self::F_EXPORT_ID);
            $parent_id = $exportid;
        }
        
        // show element
        $jsscript_show = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
        $("#twiz_loading_menu").html("");
        $(".twiz-status-menu").css("visibility","visible");
        $("#twiz_add_menu").fadeIn("fast");
        $("#twiz_export").fadeIn("fast");
        twiz_parent_id = "'.$parent_id.'";
'.$saveeffect.'     
});
 //]]>
</script>';

        $htmllist = $opendiv.'<table class="twiz-table-list" cellspacing="0"><tbody>';
        $htmllist.= '<tr class="twiz-table-list-tr-h"><td class="twiz-td-v-line"></td><td class="twiz-td-status twiz-table-list-td-h twiz-text-center" nowrap="nowrap"><a id="twiz_order_by_'.self::F_STATUS.'">'.__('Status', 'the-welcomizer').'</a>'.$twiz_order_by_status.'</td><td class="twiz-table-list-td-h twiz-text-left twiz-td-element" nowrap="nowrap"><a id="twiz_order_by_'.self::F_LAYER_ID.'">'.__('Element', 'the-welcomizer').'</a>'.$twiz_order_by_element.'</td><td class="twiz-table-list-td-h twiz-text-center twiz-td-event" nowrap="nowrap"><a id="twiz_order_by_'.self::F_ON_EVENT.'">'.__('Event', 'the-welcomizer').'</a>'.$twiz_order_by_on_event.'</td><td class="twiz-table-list-td-h twiz-text-right twiz-td-delay" nowrap="nowrap"><a id="twiz_order_by_'.self::F_START_DELAY.'">'.__('Delay', 'the-welcomizer').'</a>'.$twiz_order_by_delay.'</td><td class="twiz-table-list-td-h twiz-text-right twiz-td-duration" nowrap="nowrap"><a id="twiz_order_by_'.self::F_DURATION.'">'.__('Duration', 'the-welcomizer').'</a>'.$twiz_order_by_duration.'</td><td class="twiz-table-list-td-h  twiz-td-action twiz-text-right" nowrap="nowrap">'.__('Action', 'the-welcomizer').'</td></tr>';
        
        foreach($listarray as $value){
            
            $hide = '' ;
            $toggleimg = '';
            $boldclass = '';
            $borderbggroupclass = '';

            $statushtmlimg = ( $value[self::F_STATUS] == '1' ) ? $this->getHtmlImgStatus($value[self::F_ID], self::STATUS_ACTIVE) : $this->getHtmlImgStatus($value[self::F_ID], self::STATUS_INACTIVE);
            
            // add a '2x' to the duration if necessary
            $duration = $this->formatListDuration($value[self::F_ID], $value);

            if( $value[self::F_ON_EVENT] != self::EV_MANUAL) {
                $on_event = ( $value[self::F_ON_EVENT] != '' ) ? self::EV_PREFIX_ON.$value[self::F_ON_EVENT] : '-';
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
            
               if(!isset($this->toggle_option[$this->user_id][self::KEY_TOGGLE_GROUP][$exid])) $this->toggle_option[$this->user_id][self::KEY_TOGGLE_GROUP][$exid] = '';
                
   
                if( $this->toggle_option[$this->user_id][self::KEY_TOGGLE_GROUP][$exid] == '1' ) {
                
                    $hide = '';
                    $toggleimg = 'twiz-minus';
                    $boldclass = ' twiz-bold';
                    
                }else{
    
                    $hide = ( $value[self::F_PARENT_ID] != '' ) ? ' twiz-display-none' : '';
                    $hide = ( $parent_id == $value[self::F_PARENT_ID] ) ? '' : $hide;
                    $toggleimg = ( $parent_id == $value[self::F_EXPORT_ID] ) ? 'twiz-minus' : 'twiz-plus';
                    $boldclass = ( $parent_id == $value[self::F_EXPORT_ID] ) ? ' twiz-bold' : '';
                }
            }
                    $borderbggroupclass = ( $value[self::F_PARENT_ID] != '' ) ? ' twiz-row-color-4' : '';
            
            if( $value[self::F_TYPE] != self::ELEMENT_TYPE_GROUP ){
            
                $rowcolor = ( $rowcolor == 'twiz-row-color-2' ) ? 'twiz-row-color-1' : 'twiz-row-color-2';
                
                // the table row
                $htmllist.= '
        <tr class="twiz-list-tr '.$rowcolor.' '.$value[self::F_PARENT_ID].$hide.'" parentid="'.$value[self::F_PARENT_ID].'" id="twiz_list_tr_'.$value[self::F_ID].'"><td class="twiz-td-v-line '.$borderbggroupclass.'"></td><td class="twiz-td-status twiz-text-center" id="twiz_td_status_'.$value[self::F_ID].'">'.$statushtmlimg.'</td><td class="twiz-td-element twiz-text-left"><div id="twiz_list_div_element_'.$value[self::F_ID].'" class="twiz-element">'.$value[self::F_LAYER_ID].'<span class="twiz-green"> - ['.$elementype.']</span></div><div class="twiz-list-tr-action" id="twiz_list_tr_action_'.$value[self::F_ID].'" ><a id="twiz_edit_a_'.$value[self::F_ID].'" class="twiz-edit">'.__('Edit', 'the-welcomizer').'</a> | <a id="twiz_copy_a_'.$value[self::F_ID].'" class="twiz-copy">'.__('Copy', 'the-welcomizer').'</a> | <a id="twiz_delete_a_'.$value[self::F_ID].'" class="twiz-red twiz-delete">'.__('Delete', 'the-welcomizer').'</a></div></td><td class="twiz-td-event twiz-blue twiz-text-center"><div id="twiz_ajax_td_val_on_event_'.$value[self::F_ID].'" class="twiz-cursor" title="'.__('Edit', 'the-welcomizer').'">'.$on_event.'</div><div id="twiz_ajax_td_loading_on_event_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_on_event_'.$value[self::F_ID].'" class="twiz-display-none"></div></td><td class="twiz-td-delay twiz-text-right"><div id="twiz_ajax_td_val_delay_'.$value[self::F_ID].'" class="twiz-cursor" title="'.__('Edit', 'the-welcomizer').'">'.$value[self::F_START_DELAY].'</div><div id="twiz_ajax_td_loading_delay_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_delay_'.$value[self::F_ID].'" class="twiz-display-none"><input class="twiz-input-focus" type="text" name="twiz_input_delay_'.$value[self::F_ID].'" id="twiz_input_delay_'.$value[self::F_ID].'" value="'.$value[self::F_START_DELAY].'" maxlength="100"/></div></td><td id="twiz_ajax_td_duration_'.$value[self::F_ID].'" class="twiz-td-duration twiz-text-right">';
        
        if($value[self::F_DURATION_B] == ''){
        
            $htmllist.= '<div id="twiz_ajax_td_val_duration_'.$value[self::F_ID].'" class="twiz-cursor" title="'.__('Edit', 'the-welcomizer').'">'.$duration.'</div><div id="twiz_ajax_td_loading_duration_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_duration_'.$value[self::F_ID].'" class="twiz-display-none"><input class="twiz-input-focus" type="text" name="twiz_input_duration_'.$value[self::F_ID].'" id="twiz_input_duration_'.$value[self::F_ID].'" value="'.$value[self::F_DURATION].'" maxlength="100"/></div>';
        
        }else{
        
            $htmllist.= $duration;
        }
        
        $htmllist.= '</td><td class="twiz-td-action twiz-text-right" nowrap="nowrap"><div id="twiz_delete_'.$value[self::F_ID].'" title="'.__('Delete', 'the-welcomizer').'" class="twiz-delete twiz-delete-img"></div><div id="twiz_copy_'.$value[self::F_ID].'" title="'.__('Copy', 'the-welcomizer').'" class="twiz-copy twiz-copy-img"></div><div id="twiz_edit_'.$value[self::F_ID].'" title="'.__('Edit', 'the-welcomizer').'" class="twiz-edit twiz-edit-img"></div></td></tr>';
        
            }else{
             
                $where = "where ".self::F_PARENT_ID." = '".$value[self::F_EXPORT_ID]."'";

                $rowcount = $this->getRowCount( $where );
                $rowcount = ( $rowcount > 1 ) ? $rowcount.' '.__('elements','the-welcomizer') : $rowcount.' '.__('element','the-welcomizer') ;
                
                $rowcolor = 'twiz-row-color-1';
                
                 // a group
                $htmllist.= '<tr><td class="twiz-border-bottom" colspan="7"></td></tr>
        <tr class="twiz-list-group-tr '.$rowcolor.'" id="twiz_list_group_tr_'.$value[self::F_EXPORT_ID].'"><td class="twiz-td-v-line '.$borderbggroupclass.'"><div class="twiz-relative"><div id="twiz_group_img_'.$value[self::F_EXPORT_ID].'" class="twiz-toggle-group twiz-toggle-img '.$toggleimg.'"></div></div></td><td class="twiz-td-status twiz-text-center" id="twiz_td_status_'.$value[self::F_ID].'">'.$statushtmlimg.'</td><td class="twiz-td-element twiz-text-left"><div id="twiz_list_div_element_'.$value[self::F_ID].'" class="twiz-element"><a id="twiz_element_a_'.$value[self::F_EXPORT_ID].'" class="twiz-toggle-group'.$boldclass.'">'.$value[self::F_LAYER_ID].'</a></div><div class="twiz-list-tr-action" id="twiz_list_tr_action_'.$value[self::F_EXPORT_ID].'"><small>'.$rowcount.'</small></div></td><td class="twiz-td-event twiz-blue twiz-text-center">Manually</td><td class="twiz-td-delay twiz-text-right"></td><td id="twiz_ajax_td_order_'.$value[self::F_ID].'" class="twiz-td-duration twiz-text-left" nowrap="nowrap"><div class="twiz-arrow-lib twiz-arrow-lib-n" name="twiz_'.self::ACTION_ORDER_GROUP.'_'.$value[self::F_EXPORT_ID].'" id="twiz_'.self::ACTION_ORDER_GROUP.'_up_'.$value[self::F_ID].'"></div><div class="twiz-arrow-lib twiz-arrow-lib-s" name="twiz_'.self::ACTION_ORDER_GROUP.'_'.$value[self::F_EXPORT_ID].'" id="twiz_'.self::ACTION_ORDER_GROUP.'_down_'.$value[self::F_ID].'"></div></td><td class="twiz-td-action twiz-text-right" nowrap="nowrap"><div id="twiz_group_delete_'.$value[self::F_ID].'" title="'.__('Delete', 'the-welcomizer').'" class="twiz-group-delete twiz-delete-img"></div><div id="twiz_group_copy_'.$value[self::F_ID].'" title="'.__('Copy', 'the-welcomizer').'" class="twiz-group-copy twiz-copy-img"></div><div id="twiz_group_edit_'.$value[self::F_ID].'" title="'.__('Edit', 'the-welcomizer').'" class="twiz-group-edit twiz-edit-img"></div></td></tr>';
        
            }
        }
        $rowcolor = ( $rowcolor == 'twiz-row-color-2' ) ? 'twiz-row-color-1' : 'twiz-row-color-2';
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
    
    function formatDuration( $id = '', $data = null ){
        
        $data = '';
        
        if($id==''){return false;}
       
        $data = ($data==null) ? $this->getRow($id) : $data;
        
        $duration_b = ($data[self::F_DURATION_B] != '') ? $data[self::F_DURATION] . ' <small>ms</small> <span class="twiz-green"> + </span>' . $data[self::F_DURATION_B].' <small>ms</small>': '0' . ' <small>ms</small>' ;
        
      if((($data[self::F_MOVE_TOP_POS_B] !='' ) or( $data[self::F_MOVE_LEFT_POS_B] !='' ) or( $data[self::F_OPTIONS_B] !='' ) or( $data[self::F_EXTRA_JS_B] !='' )) 
           and ( $data[self::F_DURATION_B] == '' )){
            $duration = $data[self::F_DURATION].' <small>ms</small> <span class="twiz-green"> x2</span>';
        } else if( ($data[self::F_DURATION] != '') and ($data[self::F_DURATION_B] == '')) { 
            $duration = $data[self::F_DURATION] . ' <small>ms</small>';
        } else { 
            $duration =  $duration_b;
        }
        
        return $duration;
    }
    
    function formatListDuration( $id = '', $data = null ){
        
        $data = '';
        
        if($id==''){return false;}
       
        $data = ($data==null) ? $this->getRow($id) : $data;
        
        $duration_b = ($data[self::F_DURATION_B] != '') ? $data['total_duration']. '<span class="twiz-green"> xT</span>': '0' ;
        
        if((($data[self::F_MOVE_TOP_POS_B] !='' ) or( $data[self::F_MOVE_LEFT_POS_B] !='' ) or( $data[self::F_OPTIONS_B] !='' ) or( $data[self::F_EXTRA_JS_B] !='' )) 
           and ( $data[self::F_DURATION_B] == '' )){
            $duration = $data[self::F_DURATION].'<span class="twiz-green"> x2</span>';
        } else if( ($data[self::F_DURATION] != '') and ($data[self::F_DURATION_B] == '')) { 
            $duration = $data[self::F_DURATION];
        } else { 
            $duration =  $duration_b;
        }
        
        return $duration;
    }
    
    
    function getSkinsDirectory(){
        
        $dirarray = '';
        
        if ( $handle = @opendir($this->pluginDir .self::SKIN_PATH ) ) {
        
            while ( false !== ( $file = readdir($handle) ) ) {

                if ( $file != "." && $file != ".." && $file != "index.html" ) {
                
                    $dirarray[] = $file;
                }
            }
            
            closedir($handle);
        }
        
        if( !is_array($dirarray) ){ $dirarray = array(); }
         
        return $dirarray;
    }
    
    protected function getFileDirectory( $extensions = array() , $path = ''){
        
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
       
    protected function hasStartingConfigsAfter( $value = array() ){

        if( ( ($value[self::F_OUTPUT_POS]=='a') or ($value[self::F_OUTPUT] == 'a') )
         and (($value[self::F_START_TOP_POS]!='') or ($value[self::F_START_LEFT_POS]!='')
         or($value[self::F_ZINDEX]!='') or ($value[self::F_JAVASCRIPT]!='')
         or ($value[self::F_POSITION]!=''))){
         
             return true;
        }
        
        return false;
    
    }    
    
    protected function hasStartingConfigs( $value = array() ){

        if(($value[self::F_START_TOP_POS]!='') or ($value[self::F_START_LEFT_POS]!='')
         or($value[self::F_ZINDEX]!='') or ($value[self::F_JAVASCRIPT]!='')
         or ($value[self::F_POSITION]!='')){
         
             return true;
        }
        
        return false;
    
    }
    
    protected function hasMovements( $value = array() ){

        if(($value[self::F_OPTIONS_A]!='') or ($value[self::F_EXTRA_JS_A]!='')
         or($value[self::F_OPTIONS_B]!='') or ($value[self::F_EXTRA_JS_B]!='')
         or($value[self::F_MOVE_TOP_POS_A]!='') or($value[self::F_MOVE_LEFT_POS_A]!='')
         or($value[self::F_MOVE_TOP_POS_B]!='') or ($value[self::F_MOVE_LEFT_POS_B]!='')){
         
             return true;
        }
        
        return false;
    
    }
    
    protected function hasSomething( $value = array() ){

        if(($value[self::F_OPTIONS_A]!='') or ($value[self::F_EXTRA_JS_A]!='')
         or($value[self::F_OPTIONS_B]!='') or ($value[self::F_EXTRA_JS_B]!='')
         or($value[self::F_MOVE_TOP_POS_A]!='') or($value[self::F_MOVE_LEFT_POS_A]!='')
         or($value[self::F_MOVE_TOP_POS_B]!='') or ($value[self::F_MOVE_LEFT_POS_B]!='')
         or (($value[self::F_JAVASCRIPT]!='') and ($value[self::F_OUTPUT] == 'a' ))){
         
             return true;
        }
        
        return false;
    
    }

    protected function searchOnlyCSS( $value = array() ){
    
        if( ($value[self::F_OPTIONS_A]=='') 
        and ($value[self::F_JAVASCRIPT]=='')
        and ($value[self::F_MOVE_ELEMENT_A]=='')
        and ($value[self::F_MOVE_TOP_POS_A]=='')
        and ($value[self::F_MOVE_LEFT_POS_A]=='')
        and ($value[self::F_OPTIONS_A]=='')
        and ($value[self::F_EXTRA_JS_A]=='')
        and ($value[self::F_MOVE_ELEMENT_B]=='')
        and ($value[self::F_MOVE_TOP_POS_B]=='')
        and ($value[self::F_MOVE_LEFT_POS_B]=='')
        and ($value[self::F_OPTIONS_B]=='')
        and ($value[self::F_EXTRA_JS_B]=='')
        and ( ( $value[self::F_CSS]!='') 
        or  ($value[self::F_OUTPUT_POS] == 'c') 
        and ( ($value[self::F_START_ELEMENT_TYPE]!='')
        or ($value[self::F_START_ELEMENT]!='')
        or ($value[self::F_START_TOP_POS]!='')
        or ($value[self::F_START_LEFT_POS]!='' ) 
        or ($value[self::F_POSITION]!='')
        or ($value[self::F_ZINDEX]!='') )
        )
        ){
             return true;
        }
    
        return false;
    }

    function exportIdExists( $exportid = '' ){ 
    
        global $wpdb;
        
        if($exportid==''){return false;}
    
        $sql = "SELECT ".self::F_EXPORT_ID." FROM ".$this->table." WHERE ".self::F_EXPORT_ID." = '".$exportid."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        if($row[self::F_EXPORT_ID]!=''){

            return true;
        }
  
        return false;
    }
    
    function getDirectionalImage( $data = '', $ab = ''){
    
        if($data==''){return '';}
        if($ab==''){return '';}
        $direction = '';
        
        if((($data['move_top_pos_sign_'.$ab] != '') and ($data['move_left_pos_'.$ab] == ''))
        or (($data['move_left_pos_sign_'.$ab] != '') and ($data['move_top_pos_'.$ab] == ''))
        or (($data['move_left_pos_sign_'.$ab] != '') and ($data['move_top_pos_'.$ab] != '')
        and ($data['move_top_pos_sign_'.$ab] != '') and ($data['move_left_pos_'.$ab] != ''))
        ){
        
            if($this->label_x == __('Left', 'the-welcomizer') ){
            
                // true super fast logical switch
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
                
            }else{ // positioning method Top & Right
            
                // true super fast logical switch
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
                         
                        $direction = self::DIMAGE_NW;
                    
                        break;
                        
                    case (($data['move_top_pos_'.$ab]== '') 
                         and ($data['move_left_pos_'.$ab]!= '') 
                         and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // E
                         
                        $direction = self::DIMAGE_W;
                    
                        break;
                        
                    case (($data['move_top_pos_'.$ab]!= '') 
                         and ($data['move_top_pos_sign_'.$ab] == '+') 
                         and ($data['move_left_pos_'.$ab]!= '') 
                         and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // SE
                         
                        $direction = self::DIMAGE_SW;
                    
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
                         
                         $direction = self::DIMAGE_SE;
                    
                        break;
                        
                   case (($data['move_top_pos_'.$ab]== '') 
                         and ($data['move_left_pos_'.$ab]!= '') 
                         and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // W
                         
                         $direction = self::DIMAGE_E;
                    
                        break;
                        
                   case (($data['move_top_pos_'.$ab]!= '') 
                         and ($data['move_top_pos_sign_'.$ab] == '-') 
                         and ($data['move_left_pos_'.$ab]!= '') 
                         and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // NW
                         
                        $direction = self::DIMAGE_NE;
                    
                        break;
                }            
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
        $showexport = '';
        $toggleoptions = '';
        $lbl_action = '';
        $twiz_blog_id = '';
        
        if(!isset($_POST['twiz_action'])) $_POST['twiz_action'] = '';
        if(!isset($_POST['twiz_stay'])) $_POST['twiz_stay'] = '';
        $twiz_stay = esc_attr(trim($_POST['twiz_stay']));
                
        $ok = $this->setPositioningMethod();
        
        switch( $action ){
            case self::ACTION_NEW:
            
                    $lbl_action = __('Add New', 'the-welcomizer');
                    
                break;
                
            case self::ACTION_EDIT:
                    
                    $lbl_action = __('Edit', 'the-welcomizer');
                    if(!$data = $this->getRow($id)){return false;}
                    $showexport = '$("#twiz_export").fadeIn("fast");';
                break;
                
            case self::ACTION_COPY:

                    $lbl_action = __('Copy', 'the-welcomizer');
                    if(!$data = $this->getRow($id)){return false;}

                break;
        }  
        
        $jsscript_open = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
';
 
        $jsscript = '$(".twiz-tab").click(function(){
        if(($("#twiz_tab_js").attr("class") == "twiz-display-none")
        &&($(this).html() == "'.__('jQuery', 'the-welcomizer').'")){
            $(".twiz-tab").attr({"class":"twiz-tab twiz-corner-top"});
            $(this).attr({"class":"twiz-tab twiz-corner-top twiz-tab-selected"});
            $("#twiz_tab_js").attr({"class":""});
            $("#twiz_tab_css").attr({"class":"twiz-display-none"});
        }else if(($("#twiz_tab_css").attr("class") == "twiz-display-none")
        &&($(this).html() != "'.__('jQuery', 'the-welcomizer').'")){
            $(".twiz-tab").attr({"class":"twiz-tab twiz-corner-top"});
            $(this).attr({"class":"twiz-tab twiz-corner-top twiz-tab-selected"});
            $("#twiz_tab_js").attr({"class":"twiz-display-none"});
            $("#twiz_tab_css").attr({"class":""});
        }});
        $("#twiz_more_duration").click(function(){
            $(this).hide();
            $("#twiz_div_duration_b").fadeIn("fast");
        });
        $(".twiz-more-configs").click(function(){
        var twiz_textid = $(this).attr("id");
        if(twiz_textid == "twiz_starting_config"){
            if ( twiz_showOrHide_more_config == false ) {
                $("#" + twiz_textid).html("'.__('Less configurations', 'the-welcomizer').' &#187;");
                twiz_showOrHide_more_config = true;
            } else if ( twiz_showOrHide_more_config == true ) {
                $("#" + twiz_textid).html("'.__('More configurations', 'the-welcomizer').' &#187;");
                twiz_showOrHide_more_config = false;
            }
            $("#twiz_tr_starting_config").toggle(twiz_showOrHide_more_config);
        }else{
            if ( twiz_showOrHide_more_options == false ) {
                $("a[id^=twiz_more_options]").html("'.__('Less options', 'the-welcomizer').' &#187;");
                twiz_showOrHide_more_options = true;
            } else if ( twiz_showOrHide_more_options == true ) {
                $("a[id^=twiz_more_options]").html("'.__('More options', 'the-welcomizer').' &#187;");
                twiz_showOrHide_more_options = false;
            }        
            $(".twiz-table-more-options").toggle(twiz_showOrHide_more_options);
        }
    });';
    
$jsscript_close = '});
//]]>
</script>';
        
        // Toggle starting config 
        $jsscript_starting_config = '$("#twiz_tr_starting_config").toggle();';
        
        // Toggle More Options
        $jsscript_moreoptions = '$(".twiz-table-more-options").toggle();';
        
        // hide element
        $jsscript_hide = '$("#twiz_far_matches").html("");
$("[name=twiz_listmenu]").css("display", "none");
$(".twiz-right-panel").fadeOut("fast");
$("#twiz_add_menu").fadeIn("fast");
        '.$showexport;
         
        // Text Area auto expand
        $jsscript_autoexpand = '
textarea = new Object();
textarea.expand = function(textbox){
    twizsizeOrig(textbox);
    textbox.style.height = (textbox.scrollHeight + 20) + "px";
    textbox.style.width = (textbox.scrollWidth + 40) + "px";
} 
textarea.expandcss = function(textbox){
    twizsizeOrigCss(textbox);
    textbox.style.height = (textbox.scrollHeight + 20) + "px";
    textbox.style.width = (textbox.scrollWidth + 40) + "px";
} 
function twizsizeOrig(textbox){
    $(textbox).css({"z-index":10, "width": "230px","height": "50px"});
}
function twizsizeOrigCss(textbox){
    $(textbox).css({"z-index":10, "width": "230px","height": "93px"});
}
$("textarea[name^=twiz_javascript]").blur(function (){
   twizsizeOrig(this);
   $(this).css({"z-index":1});
});
$("textarea[name=twiz_css]").blur(function (){
   twizsizeOrigCss(this);
});
$("textarea[name^=twiz_options]").blur(function (){
   twizsizeOrig(this);
   $(this).css({"z-index":1});
});
 $("textarea[name^=twiz_extra]").blur(function (){
   twizsizeOrig(this);
   $(this).css({"z-index":1});
});';

        // Dynamic arrows
        $jsscript_arrows = '   $("select[id^=twiz_'.self::F_MOVE_LEFT_POS_SIGN_A.']").change(function(){twizChangeDirectionImage("a");});
   $("select[id^=twiz_'.self::F_MOVE_TOP_POS_SIGN_A.']").change(function(){twizChangeDirectionImage("a");});
   $("input[name^=twiz_'.self::F_MOVE_TOP_POS_A.']").blur(function(){twizChangeDirectionImage("a");});
   $("input[name^=twiz_'.self::F_MOVE_LEFT_POS_A.']").blur(function(){twizChangeDirectionImage("a");});
   $("select[id^=twiz_'.self::F_MOVE_LEFT_POS_SIGN_B.']").change(function(){twizChangeDirectionImage("b");});
   $("select[id^=twiz_'.self::F_MOVE_TOP_POS_SIGN_B.']").change(function(){twizChangeDirectionImage("b");});
   $("input[name^=twiz_'.self::F_MOVE_TOP_POS_B.']").blur(function(){twizChangeDirectionImage("b");});
   $("input[name^=twiz_'.self::F_MOVE_LEFT_POS_B.']").blur(function(){twizChangeDirectionImage("b");});
   function twizChangeDirectionImage(ab) {
      var twiz_top_sign  = $("#twiz_move_top_pos_sign_" + ab).val();
      var twiz_top_val   = $("#twiz_move_top_pos_" + ab).val();
      var twiz_left_sign = $("#twiz_move_left_pos_sign_" + ab).val();
      var twiz_left_val  = $("#twiz_move_left_pos_" + ab).val();
      var twiz_direction = "";
      var twiz_htmlimage = "";
      if((twiz_top_sign!="=")&&(twiz_left_sign!="=")){';
      
      if($this->label_x == __('Left', 'the-welcomizer') ){
      
          $jsscript_arrows.= 'switch(true){
             case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val=="")): 
                twiz_direction = "'.self::DIMAGE_N.'";
                break;
             case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val!="")&&(twiz_left_sign=="+")):
                twiz_direction = "'.self::DIMAGE_NE.'";
                break;
             case ((twiz_top_val=="")&&(twiz_left_val!="")&&(twiz_left_sign=="+")): 
                twiz_direction = "'.self::DIMAGE_E.'";
                break;
             case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val!="")&&(twiz_left_sign=="+")): 
                twiz_direction = "'.self::DIMAGE_SE.'";
                break;
             case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val=="")): 
                twiz_direction = "'.self::DIMAGE_S.'";
                break;
             case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                twiz_direction = "'.self::DIMAGE_SW.'";
                break;
             case ((twiz_top_val=="")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                twiz_direction = "'.self::DIMAGE_W.'";
                break;
             case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                twiz_direction = "'.self::DIMAGE_NW.'";
                break;
          }';
         
         }else{
         
              $jsscript_arrows.= 'switch(true){
                 case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val=="")): 
                    twiz_direction = "'.self::DIMAGE_N.'";
                    break;
                 case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val!="")&&(twiz_left_sign=="+")):
                    twiz_direction = "'.self::DIMAGE_NW.'";
                    break;
                 case ((twiz_top_val=="")&&(twiz_left_val!="")&&(twiz_left_sign=="+")): 
                    twiz_direction = "'.self::DIMAGE_W.'";
                    break;
                 case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val!="")&&(twiz_left_sign=="+")): 
                    twiz_direction = "'.self::DIMAGE_SW.'";
                    break;
                 case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val=="")): 
                    twiz_direction = "'.self::DIMAGE_S.'";
                    break;
                 case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                    twiz_direction = "'.self::DIMAGE_SE.'";
                    break;
                 case ((twiz_top_val=="")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                    twiz_direction = "'.self::DIMAGE_E.'";
                    break;
                 case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                    twiz_direction = "'.self::DIMAGE_NE.'";
                    break;
              }';
          
          }
          $jsscript_arrows.= ' if(twiz_direction!=""){ 
              twiz_htmlimage = \'<div\' + \' class="twiz-arrow twiz-arrow-\' + twiz_direction + \'"></div>\';
          }
      }
      $("#twiz_td_arrow_" + ab).html(twiz_htmlimage);
    }';

        // ajax container 
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
        if( !isset($data[self::F_DURATION_B]) ) $data[self::F_DURATION_B] = '';
        if( !isset($data[self::F_OUTPUT]) ) $data[self::F_OUTPUT] = '';
        if( !isset($data[self::F_OUTPUT_POS]) ) $data[self::F_OUTPUT_POS] = '';
        if( !isset($data[self::F_JAVASCRIPT]) ) $data[self::F_JAVASCRIPT] = '';
        if( !isset($data[self::F_CSS]) ) $data[self::F_CSS] = '';
        if( !isset($data[self::F_START_ELEMENT_TYPE]) ) $data[self::F_START_ELEMENT_TYPE] = '';
        if( !isset($data[self::F_START_ELEMENT]) ) $data[self::F_START_ELEMENT] = '';
        if( !isset($data[self::F_START_TOP_POS]) ) $data[self::F_START_TOP_POS] = '';
        if( !isset($data[self::F_START_LEFT_POS]) ) $data[self::F_START_LEFT_POS] = '';
        if( !isset($data[self::F_MOVE_ELEMENT_TYPE_A]) ) $data[self::F_MOVE_ELEMENT_TYPE_A] = '';
        if( !isset($data[self::F_MOVE_ELEMENT_A]) ) $data[self::F_MOVE_ELEMENT_A] = '';
        if( !isset($data[self::F_MOVE_ELEMENT_TYPE_B]) ) $data[self::F_MOVE_ELEMENT_TYPE_B] = '';
        if( !isset($data[self::F_MOVE_ELEMENT_B]) ) $data[self::F_MOVE_ELEMENT_B] = '';
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
        if( !isset($data[self::F_GROUP_ORDER]) ) $data[self::F_GROUP_ORDER] = '';
    
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

        // toggle starting config if we have values        
        if( ($hasStartingConfigs) or ( $data[self::F_CSS] != '' ) ){
        
            $lbl_more_config = __('Less configurations', 'the-welcomizer');
            $jsscript_open .= 'var twiz_showOrHide_more_config = true;';
            $toggleoptions = $jsscript_starting_config;
            
        }else{
        
            $lbl_more_config = __('More configurations', 'the-welcomizer');
            $jsscript_open .= 'var twiz_showOrHide_more_config = false;';
        }
        
        // toggle more options by default if we have values        
        if(($data[self::F_OPTIONS_A]!='')or($data[self::F_EXTRA_JS_A]!='')
         or($data[self::F_OPTIONS_B]!='')or($data[self::F_EXTRA_JS_B]!='')){
         
            $toggleoptions .= $jsscript_moreoptions;
            $lbl_more_options = __('Less options', 'the-welcomizer');
            $jsscript_open .= 'var twiz_showOrHide_more_options = true;';
            
        }else{
        
            $lbl_more_options = __('More options', 'the-welcomizer');
            $jsscript_open .= 'var twiz_showOrHide_more_options = false;';
        }
       
        $twiz_export_id = (($data[self::F_EXPORT_ID] == '' ) or ( $action == self::ACTION_COPY ) or ( $action == self::ACTION_NEW )) ? $this->getUniqid() : $data[self::F_EXPORT_ID];

    
        // checked
        $twiz_status = (( $data[self::F_STATUS] == 1 ) or ( $id == '' )) ? ' checked="checked"' : '';
        $twiz_lock_event = (( $data[self::F_LOCK_EVENT] == 1 ) or ( $id == '' )) ? ' checked="checked"' : '';
        $twiz_stay = ( $twiz_stay == 'true' ) ? ' checked="checked"' : '';
 
        $twiz_group_order = ($data[self::F_GROUP_ORDER] == '') ? '0' : $data[self::F_GROUP_ORDER];
 
        // selected
        $twiz_position[self::POS_NO_POS] = (( $data[self::F_POSITION] == self::POS_NO_POS ) or (($this->admin_option[self::KEY_STARTING_POSITION] == self::POS_NO_POS  )and ( $id == '' ))) ? ' selected="selected"' : '';
        $twiz_position[self::POS_ABSOLUTE] = (( $data[self::F_POSITION] == self::POS_ABSOLUTE ) or (($this->admin_option[self::KEY_STARTING_POSITION] == self::POS_ABSOLUTE  ) and ( $id == '' ))) ? ' selected="selected"' : '';
        $twiz_position[self::POS_RELATIVE] = (( $data[self::F_POSITION] == self::POS_RELATIVE) or (($this->admin_option[self::KEY_STARTING_POSITION] == self::POS_RELATIVE )and ( $id == '' ))) ? ' selected="selected"' : '';
        $twiz_position[self::POS_FIXED]   = (( $data[self::F_POSITION] == self::POS_FIXED) or (($this->admin_option[self::KEY_STARTING_POSITION] == self::POS_FIXED )and ( $id == '' ))) ? ' selected="selected"' : '';
        $twiz_position[self::POS_STATIC]   = (( $data[self::F_POSITION] == self::POS_STATIC) or (($this->admin_option[self::KEY_STARTING_POSITION] == self::POS_STATIC )and ( $id == '' ))) ? ' selected="selected"' : '';
        
        $twiz_lock_type['auto'] = ($data[self::F_LOCK_EVENT_TYPE] == 'auto') ? ' selected="selected"' : '';
        $twiz_lock_type['manu'] = ($data[self::F_LOCK_EVENT_TYPE] == 'manu') ? ' selected="selected"' : '';
        
        $twiz_ouput_pos['css'] = ($data[self::F_OUTPUT_POS] == 'c') ? ' selected="selected"' : '';
        $twiz_ouput_pos['ready'] = ($data[self::F_OUTPUT_POS] == 'r') ? ' selected="selected"' : '';
        $twiz_ouput_pos['before'] = ($data[self::F_OUTPUT_POS] == 'b') ? ' selected="selected"' : '';
        $twiz_ouput_pos['after'] = ($data[self::F_OUTPUT_POS] == 'a') ? ' selected="selected"' : '';
        $twiz_ouput_pos['css'] = ($data[self::F_OUTPUT_POS] == '') ? ' selected="selected"' : $twiz_ouput_pos['css'];
        
        $twiz_ouput['ready'] = ($data[self::F_OUTPUT] == 'r') ? ' selected="selected"' : '';
        $twiz_ouput['before'] = ($data[self::F_OUTPUT] == 'b') ? ' selected="selected"' : '';
        $twiz_ouput['after'] = ($data[self::F_OUTPUT] == 'a') ? ' selected="selected"' : '';
        $twiz_ouput['after'] = ($data[self::F_OUTPUT] == '') ? ' selected="selected"' : $twiz_ouput['after'];
        
        $twiz_start_top_pos_sign['nothing']  = ($data[self::F_START_TOP_POS_SIGN] == '') ? ' selected="selected"' : '';
        $twiz_start_top_pos_sign['-']        = ($data[self::F_START_TOP_POS_SIGN] == '-') ? ' selected="selected"' : '';
        $twiz_start_left_pos_sign['nothing'] = ($data[self::F_START_LEFT_POS_SIGN] == '') ? ' selected="selected"' : '';
        $twiz_start_left_pos_sign['-']       = ($data[self::F_START_LEFT_POS_SIGN] == '-') ? ' selected="selected"' : '';
              
        if( $action == self::ACTION_NEW ){
        
            if( $parent_id != '' ){ // set group order
            
                $parent_real_id = $this->getId(self::F_EXPORT_ID, $parent_id );
                $twiz_group_order = $this->getValue($parent_real_id, self::F_GROUP_ORDER);
                
            }
        
            $twiz_move_top_pos_sign_a['+']  = ' selected="selected"';
            $twiz_move_top_pos_sign_a['-']  = '';
            $twiz_move_top_pos_sign_a[' ']  = '';
            $twiz_move_left_pos_sign_a['+']  = ' selected="selected"';
            $twiz_move_left_pos_sign_a['-']  = '';
            $twiz_move_left_pos_sign_a[' ']  = '';
            $twiz_move_top_pos_sign_b['+']  = '';
            $twiz_move_top_pos_sign_b['-']  = ' selected="selected"';
            $twiz_move_top_pos_sign_b[' ']  = '';
            $twiz_move_left_pos_sign_b['+'] = '';
            $twiz_move_left_pos_sign_b['-'] = ' selected="selected"';
            $twiz_move_left_pos_sign_b[' '] = '';
            
        }else{
        
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
        }
        
        $imagemove_a = $this->getDirectionalImage($data, 'a');
        $imagemove_b = $this->getDirectionalImage($data, 'b');
        
        // reset id if it's a new copy
        $id = ($action == self::ACTION_COPY) ? '' : $id;

        // Added to be recognized by the translator
        $ttcopy = __('Copy', 'the-welcomizer');
        
        // under group Manually by default
        $data[self::F_ON_EVENT] = (($parent_id != '')and($data[self::F_ON_EVENT] == '')) ? 'Manually' : $data[self::F_ON_EVENT];
        
        $eventlist = $this->getHtmlEventList($data[self::F_ON_EVENT],'','');
        $element_type_list = $this->getHtmlElementTypeList($data[self::F_TYPE], self::F_TYPE);
        $element_type_list_start = $this->getHtmlElementTypeList($data[self::F_START_ELEMENT_TYPE], self::F_START_ELEMENT_TYPE);
        $element_type_list_move_a = $this->getHtmlElementTypeList($data[self::F_MOVE_ELEMENT_TYPE_A], self::F_MOVE_ELEMENT_TYPE_A);
        $element_type_list_move_b = $this->getHtmlElementTypeList($data[self::F_MOVE_ELEMENT_TYPE_B], self::F_MOVE_ELEMENT_TYPE_B);
        
        // easing
        $easing_a = $this->getHtmlEasingOptions($data[self::F_EASING_A], self::F_EASING_A, '');
        $easing_b = $this->getHtmlEasingOptions($data[self::F_EASING_B], self::F_EASING_B, '');
        
        $twiz_parent_id = ($parent_id != '') ? $parent_id : $data[self::F_PARENT_ID];
        
        $hide_start_element = ($data[self::F_START_ELEMENT] == '')? ' class="twiz-display-none"' : '';
        $hide_move_element_a = ($data[self::F_MOVE_ELEMENT_A] == '')? ' class="twiz-display-none"' : '';
        $hide_move_element_b = ($data[self::F_MOVE_ELEMENT_B] == '')? ' class="twiz-display-none"' : '';

        $show_add_start_element = ($data[self::F_START_ELEMENT] == '')? '' : ' class="twiz-display-none"';
        $show_add_move_element_a = ($data[self::F_MOVE_ELEMENT_A] == '')? '' : ' class="twiz-display-none"';
        $show_add_move_element_b = ($data[self::F_MOVE_ELEMENT_B] == '')? '' : ' class="twiz-display-none"';
        
        if( $data[self::F_DURATION_B] == '' ){
        
            $hide_duration_b = 'twiz-display-none';
            $show_more_duration = '';
            
        }else{
        
            $hide_duration_b = '';
            $show_more_duration = 'class="twiz-display-none"';
        }
        
        $tabselectedjs = (($data[self::F_JAVASCRIPT] != '' ) 
                  or (($data[self::F_JAVASCRIPT] == '') and ($data[self::F_CSS] == '' ))) ? ' twiz-tab-selected' : '';
                    
        $tabselectedcss = (($data[self::F_CSS] != '' )and($data[self::F_JAVASCRIPT] == '' )) ? ' twiz-tab-selected' : '';
        $tabhiddencss = (($data[self::F_JAVASCRIPT] != '' ) 
                          or (($data[self::F_JAVASCRIPT] == '') and ($data[self::F_CSS] == '' ))) ? 'twiz-display-none' : '';
        $tabhiddenjs = (($data[self::F_CSS] != '' )and($data[self::F_JAVASCRIPT] == '' )) ? 'twiz-display-none' : '';
        
        $start_delay = ($data[self::F_START_DELAY] != '' ) ? $data[self::F_START_DELAY] : '0';
        $duration = ($data[self::F_DURATION] != '' ) ? $data[self::F_DURATION] : '1000';
        
        // creates the form
        $htmlform = $opendiv.'<table class="twiz-table-form" cellspacing="0" cellpadding="0">
<tr><td class="twiz-form-td-left">'.__('Status', 'the-welcomizer').': <div class="twiz-float-right"><input type="checkbox" id="twiz_'.self::F_STATUS.'" name="twiz_'.self::F_STATUS.'" '.$twiz_status.'/></div></td>
<td class="twiz-form-td-right"><div class="twiz-float-right twiz-action-box">'.__('Action', 'the-welcomizer').'<div class="twiz-green">'.$lbl_action.'</div></div><div class="twiz-float-right twiz-td-save twiz-save-box-1"><span id="twiz_save_img_box_1" name="twiz_save_img_box" class="twiz-loading-gif-save"></span><a id="twiz_cancel_1">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save" id="twiz_save_1" class="button-primary" value="'.__('Save', 'the-welcomizer').'" /></div></td></tr>
<tr><td class="twiz-form-td-left">'.__('Trigger by event', 'the-welcomizer').': <div id="twiz_div_choose_event" class="twiz-float-right">'.$eventlist.'</div><td class="twiz-form-td-right"><div id="twiz_div_no_event" class="twiz-display-none twiz-float-left"></div><div id="twiz_div_lock_event"  class="twiz-display-none twiz-float-left"><input type="checkbox" id="twiz_'.self::F_LOCK_EVENT.'" name="twiz_'.self::F_LOCK_EVENT.'" '.$twiz_lock_event.'/><label for="twiz_'.self::F_LOCK_EVENT.'"> '.__('Locked', 'the-welcomizer').'</label> <select name="twiz_'.self::F_LOCK_EVENT_TYPE.'" id="twiz_'.self::F_LOCK_EVENT_TYPE.'">
        <option value="auto" '.$twiz_lock_type['auto'].'>'.__('Automatic unlock', 'the-welcomizer').'</option>
        <option value="manu" '.$twiz_lock_type['manu'].'>'.__('Manual unlock', 'the-welcomizer').'</option>
        </select></div></td></tr>
<tr><td class="twiz-form-td-left">'.__('Main element', 'the-welcomizer').': <div class="twiz-float-right">'.$element_type_list.'</div></td><td  class="twiz-form-td-right twiz-float-left"><input class="twiz-input twiz-input-focus" id="twiz_'.self::F_LAYER_ID.'" name="twiz_'.self::F_LAYER_ID.'" type="text" value="'.$data[self::F_LAYER_ID].'" maxlength="50"/></td></tr>
<tr><td class="twiz-form-td-left"><div class="twiz-float-right">'.__('Start delay', 'the-welcomizer').':</div> </td><td class="twiz-form-td-right"><div class="twiz-float-left"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.self::F_START_DELAY.'" name="twiz_'.self::F_START_DELAY.'" type="text" value="'.$start_delay.'" maxlength="100"/> <small>ms</small></div></td></tr>
<tr><td class="twiz-form-td-left"><a id="twiz_starting_config" class="twiz-more-configs">'.$lbl_more_config.' &#187;</a><div class="twiz-float-right">'.__('Duration', 'the-welcomizer').':</div> </td><td class="twiz-form-td-right"><div class="twiz-float-left"><input class="twiz-input-small-d twiz-input-focus" id="twiz_'.self::F_DURATION.'" name="twiz_'.self::F_DURATION.'" type="text" value="'.$duration.'" maxlength="100"/> <small>ms</small> <span class="twiz-green"><a id="twiz_more_duration" '.$show_more_duration.'>(2x) &#187;</a></span></div><div id="twiz_div_duration_b" class="twiz-float-left '.$hide_duration_b.'">&nbsp;<input class="twiz-input-small-d twiz-input-focus" id="twiz_'.self::F_DURATION_B.'" name="twiz_'.self::F_DURATION_B.'" type="text" value="'.$data[self::F_DURATION_B].'" maxlength="100"/> <small>ms</small></div></td></tr>
<tr id="twiz_tr_starting_config">
    <td valign="top">
        <table class="twiz-table-js-css"><tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr>
 <tr><td colspan="2" class="twiz-caption"><b>'.__('Starting Positions', 'the-welcomizer').'</b> <select name="twiz_'.self::F_OUTPUT_POS.'" id="twiz_'.self::F_OUTPUT_POS.'">
        <option value="c" '.$twiz_ouput_pos['css'].'>'.__('CSS Styles', 'the-welcomizer').'</option> 
        <option value="r" '.$twiz_ouput_pos['ready'].'>'.__('onReady', 'the-welcomizer').'</option>
        <option value="b" '.$twiz_ouput_pos['before'].'>'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a" '.$twiz_ouput_pos['after'].'>'.__('After the delay', 'the-welcomizer').'</option>
        </select></td></tr> 
<tr id="twiz_tr_add_'.self::F_START_ELEMENT.'"'.$show_add_start_element.'><td colspan="2" nowrap="nowrap"><a name="twiz_add_'.self::F_START_ELEMENT.'" id="twiz_add_'.self::F_START_ELEMENT.'" class="twiz-add-element">'.__('Assign a different element', 'the-welcomizer').' &#187;</a></td></tr>    
<tr id="twiz_tr_'.self::F_START_ELEMENT.'"'.$hide_start_element.'><td colspan="2" nowrap="nowrap">'.$element_type_list_start.' <input class="twiz-input-e twiz-input-focus" id="twiz_'.self::F_START_ELEMENT.'" name="twiz_'.self::F_START_ELEMENT.'" type="text" value="'.$data[self::F_START_ELEMENT].'" maxlength="50"/></td></tr>
            <tr><td class="twiz-td-small-left-start" nowrap="nowrap">'.$this->label_y.':</td><td>
            <select name="twiz_'.self::F_START_TOP_POS_SIGN.'" id="twiz_'.self::F_START_TOP_POS_SIGN.'">
                <option value="" '.$twiz_start_top_pos_sign['nothing'].'>+</option>
                <option value="-" '.$twiz_start_top_pos_sign['-'].'>-</option>
                </select><input class="twiz-input-small twiz-input-focus" id="twiz_'.self::F_START_TOP_POS.'" name="twiz_'.self::F_START_TOP_POS.'" type="text" value="'.$data[self::F_START_TOP_POS].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_START_TOP_POS_FORMAT, $data[self::F_START_TOP_POS_FORMAT]).'</td></tr>
            <tr><td class="twiz-td-small-left-start" nowrap="nowrap">'.$this->label_x.':</td><td>
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
    <td valign="top">
<table class="twiz-table-js-css"><tr><td><hr class="twiz-hr twiz-corner-all"></td></tr>
<tr><td class="twiz-caption">
<div id="twiz_tab_line"></div>
<div class="twiz-clear"></div><div class="twiz-tab twiz-corner-top'.$tabselectedjs.'">'.__('jQuery', 'the-welcomizer').'</div> <div class="twiz-tab twiz-corner-top'.$tabselectedcss.'">'.__('Extra CSS', 'the-welcomizer').'</div><div id="twiz_tab_js" class="'.$tabhiddenjs.'"><select name="twiz_'.self::F_OUTPUT.'" id="twiz_'.self::F_OUTPUT.'">
        <option value="r" '.$twiz_ouput['ready'].'>'.__('onReady', 'the-welcomizer').'</option>
        <option value="b" '.$twiz_ouput['before'].'>'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a" '.$twiz_ouput['after'].'>'.__('After the delay', 'the-welcomizer').'</option>
        </select>
<div class="twiz-wrap-input-large"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input twiz-input-large twiz-input-focus" id="twiz_'.self::F_JAVASCRIPT.'" name="twiz_'.self::F_JAVASCRIPT.'" type="text" >'.$data[self::F_JAVASCRIPT].'</textarea></div>'.$this->getHtmlJSFeatures($id, 'javascript', $section_id).'
</div><div class="twiz-clear"></div>
<div id="twiz_tab_css" class="'.$tabhiddencss.'"><div class="twiz-wrap-input-large-css"><textarea onclick="textarea.expandcss(this)" rows="1" onkeyup="textarea.expandcss(this)" WRAP="OFF" class="twiz-input twiz-input-large-css twiz-input-focus" id="twiz_'.self::F_CSS.'" name="twiz_'.self::F_CSS.'" type="text">'.$data[self::F_CSS].'</textarea></div>'.$this->getHTMLCSSSnippets().'</div>
</td></tr>            
        </table></td>
</tr>
<tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr>
<tr><td valign="top">
        <table>
            <tr><td class="twiz-caption" colspan="3"><b>'.__('First Animation', 'the-welcomizer').'</b> '.$easing_a.'</td></tr>
<tr id="twiz_tr_add_'.self::F_MOVE_ELEMENT_A.'"'.$show_add_move_element_a.'><td colspan="3" nowrap="nowrap"><a name="twiz_add_'.self::F_MOVE_ELEMENT_A.'" id="twiz_add_'.self::F_MOVE_ELEMENT_A.'" class="twiz-add-element">'.__('Assign a different element', 'the-welcomizer').' &#187;</a></td></tr>              
<tr id="twiz_tr_'.self::F_MOVE_ELEMENT_A.'"'.$hide_move_element_a.'><td class="twiz-td-move-e" colspan="3" nowrap="nowrap">'.$element_type_list_move_a.' <input class="twiz-input-e twiz-input-focus" id="twiz_'.self::F_MOVE_ELEMENT_A.'" name="twiz_'.self::F_MOVE_ELEMENT_A.'" type="text" value="'.$data[self::F_MOVE_ELEMENT_A].'" maxlength="50"/></td></tr>            
            <tr><td class="twiz-td-small-left" nowrap="nowrap">'.$this->label_y.':</td><td nowrap="nowrap">
            ';
        
        if( $this->admin_option[self::KEY_REGISTER_JQUERY_TRANSIT] != '1' ){
        
            $htmlform .= '<select name="twiz_'.self::F_MOVE_TOP_POS_SIGN_A.'" id="twiz_'.self::F_MOVE_TOP_POS_SIGN_A.'">
            <option value="" '.$twiz_move_top_pos_sign_a[' '].'> </option><option value="-" '.$twiz_move_top_pos_sign_a['-'].'>-</option>
            <option value="+" '.$twiz_move_top_pos_sign_a['+'].'>+</option></select>';
        }
        
        $htmlform .= '<input class="twiz-input twiz-input-small twiz-input-focus" id="twiz_move_top_pos_a" name="twiz_move_top_pos_a" type="text" value="'.$data[self::F_MOVE_TOP_POS_A].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_MOVE_TOP_POS_FORMAT_A, $data[self::F_MOVE_TOP_POS_FORMAT_A]).'</td><td rowspan="2" align="center" width="95" id="twiz_td_arrow_a">'.$imagemove_a.'</td></tr>
            <tr><td class="twiz-td-small-left" nowrap="nowrap">'.$this->label_x.':</td><td nowrap="nowrap">
            ';
        
        if( $this->admin_option[self::KEY_REGISTER_JQUERY_TRANSIT] != '1' ){
        
            $htmlform .= '<select name="twiz_'.self::F_MOVE_LEFT_POS_SIGN_A.'" id="twiz_'.self::F_MOVE_LEFT_POS_SIGN_A.'">
            <option value="" '.$twiz_move_left_pos_sign_a[' '].'> </option><option value="-" '.$twiz_move_left_pos_sign_a['-'].'>-</option>
            <option value="+" '.$twiz_move_left_pos_sign_a['+'].'>+</option></select>';
        }
        
        $htmlform .= '<input class="twiz-input twiz-input-small twiz-input-focus" id="twiz_'.self::F_MOVE_LEFT_POS_A.'" name="twiz_'.self::F_MOVE_LEFT_POS_A.'" type="text" value="'.$data[self::F_MOVE_LEFT_POS_A].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_MOVE_LEFT_POS_FORMAT_A, $data[self::F_MOVE_LEFT_POS_FORMAT_A]).'</td></tr><tr><td></td><td colspan="2"><a id="twiz_more_options_a"  class="twiz-more-configs">'.$lbl_more_options.' &#187;</a></td></tr></table>
            <table class="twiz-table-more-options">
                <tr><td><hr class="twiz-hr twiz-corner-all"></td></tr><tr><td class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td><div class="twiz-wrap-input-large"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input twiz-input-large twiz-input-focus" id="twiz_'.self::F_OPTIONS_A.'" name="twiz_'.self::F_OPTIONS_A.'" type="text" >'.$data[self::F_OPTIONS_A].'</textarea></div></td></tr>
                <tr><td id="twiz_td_full_option_a" class="twiz-td-picklist twiz-float-left"><a id="twiz_choose_options_a">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>      
                <tr><td><hr class="twiz-hr twiz-corner-all"></td></tr>        
                <tr><td class="twiz-caption">'.__('Extra jQuery', 'the-welcomizer').'</td></tr><tr><td ><div class="twiz-wrap-input-large"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input twiz-input-large twiz-input-focus" id="twiz_'.self::F_EXTRA_JS_A.'" name="twiz_'.self::F_EXTRA_JS_A.'" type="text">'.$data[self::F_EXTRA_JS_A].'</textarea></div>'.$this->getHtmlJSFeatures($id, 'javascript_a', $section_id).'</td></tr>
        </table>
</td>
<td valign="top">    
    <table>
        <tr><td class="twiz-caption" colspan="3"><b>'.__('Second Animation', 'the-welcomizer').'</b> '.$easing_b.'</td></tr>
        <tr id="twiz_tr_add_'.self::F_MOVE_ELEMENT_B.'"'.$show_add_move_element_b.'><td colspan="3" nowrap="nowrap"><a name="twiz_add_'.self::F_MOVE_ELEMENT_B.'" id="twiz_add_'.self::F_MOVE_ELEMENT_B.'" class="twiz-add-element">'.__('Assign a different element', 'the-welcomizer').' &#187;</a></td></tr>   
        <tr id="twiz_tr_'.self::F_MOVE_ELEMENT_B.'"'.$hide_move_element_b.'><td class="twiz-td-move-e"  colspan="3" nowrap="nowrap">'.$element_type_list_move_b.' <input class="twiz-input-e twiz-input-focus" id="twiz_'.self::F_MOVE_ELEMENT_B.'" name="twiz_'.self::F_MOVE_ELEMENT_B.'" type="text" value="'.$data[self::F_MOVE_ELEMENT_B].'" maxlength="50"/></td></tr>          
        <tr><td class="twiz-td-small-left" nowrap="nowrap">'.$this->label_y.':</td><td nowrap="nowrap">
        ';
        
        if( $this->admin_option[self::KEY_REGISTER_JQUERY_TRANSIT] != '1' ){
        
            $htmlform .= '<select name="twiz_'.self::F_MOVE_TOP_POS_SIGN_B.'" id="twiz_'.self::F_MOVE_TOP_POS_SIGN_B.'">
        <option value="" '.$twiz_move_top_pos_sign_b[' '].'> </option><option value="-" '.$twiz_move_top_pos_sign_b['-'].'>-</option>
            <option value="+" '.$twiz_move_top_pos_sign_b['+'].'>+</option></select>';
        }
        
        $htmlform .= '<input class="twiz-input twiz-input-small twiz-input-focus" id="twiz_move_top_pos_b" name="twiz_move_top_pos_b" type="text" value="'.$data[self::F_MOVE_TOP_POS_B].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_MOVE_TOP_POS_FORMAT_B, $data[self::F_MOVE_TOP_POS_FORMAT_B]).'</td><td rowspan="2" align="center" width="95" id="twiz_td_arrow_b">'.$imagemove_b.'</td></tr>
        <tr><td class="twiz-td-small-left" nowrap="nowrap">'.$this->label_x.':</td><td nowrap="nowrap">
        ';
        
        if( $this->admin_option[self::KEY_REGISTER_JQUERY_TRANSIT] != '1' ){
        
            $htmlform .= '<select name="twiz_'.self::F_MOVE_LEFT_POS_SIGN_B.'" id="twiz_'.self::F_MOVE_LEFT_POS_SIGN_B.'">
        <option value="" '.$twiz_move_left_pos_sign_b[' '].'> </option><option value="-" '.$twiz_move_left_pos_sign_b['-'].'>-</option>
            <option value="+" '.$twiz_move_left_pos_sign_b['+'].'>+</option></select>';
        }
        
        $htmlform .= '<input class="twiz-input twiz-input-small twiz-input-focus" id="twiz_'.self::F_MOVE_LEFT_POS_B.'" name="twiz_'.self::F_MOVE_LEFT_POS_B.'" type="text" value="'.$data[self::F_MOVE_LEFT_POS_B].'" maxlength="5"/> '.$this->getHtmlFormatList(self::F_MOVE_LEFT_POS_FORMAT_B, $data[self::F_MOVE_LEFT_POS_FORMAT_B]).'</td></tr><tr><td></td><td colspan="2"><a id="twiz_more_options_b" class="twiz-more-configs">'.$lbl_more_options.' &#187;</a></td></tr>
        </table>
        <table class="twiz-table-more-options">
            <tr><td><hr class="twiz-hr twiz-corner-all"></td></tr><tr><td class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td><div class="twiz-wrap-input-large"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input twiz-input-large twiz-input-focus" id="twiz_'.self::F_OPTIONS_B.'" name="twiz_'.self::F_OPTIONS_B.'" type="text">'.$data[self::F_OPTIONS_B].'</textarea></div></td></tr>
            <tr><td id="twiz_td_full_option_b" class="twiz-td-picklist twiz-float-left"><a id="twiz_choose_options_b">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>     
            <tr><td><hr class="twiz-hr twiz-corner-all"></td></tr>
            <tr><td class="twiz-caption">'.__('Extra jQuery', 'the-welcomizer').'</td></tr><tr><td ><div class="twiz-wrap-input-large"><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP="OFF" class="twiz-input twiz-input-large twiz-input-focus" id="twiz_'.self::F_EXTRA_JS_B.'" name="twiz_'.self::F_EXTRA_JS_B.'" type="text" value="">'.$data[self::F_EXTRA_JS_B].'</textarea></div>'.$this->getHtmlJSFeatures($id, 'javascript_b', $section_id).'</td></tr>
        </table>
</td></tr>
<tr><td colspan="2"><hr class="twiz-hr twiz-corner-all"></td></tr>
<tr><td class="twiz-td-save" colspan="2"><span id="twiz_save_img_box_2" name="twiz_save_img_box" class="twiz-loading-gif-save"></span><a id="twiz_cancel_2">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save" id="twiz_save_2" class="button-primary" value="'.__('Save', 'the-welcomizer').'"/> <label for="twiz_stay">'.__('& Stay', 'the-welcomizer').'</label>  <input type="checkbox" id="twiz_stay" name="twiz_stay" '.$twiz_stay.'> <input type="hidden" name="twiz_'.self::F_ID.'" id="twiz_'.self::F_ID.'" value="'.$id.'"/><input type="hidden" name="twiz_'.self::F_PARENT_ID.'" id="twiz_'.self::F_PARENT_ID.'" value="'.$twiz_parent_id.'"/><input type="hidden" name="twiz_'.self::F_EXPORT_ID.'" id="twiz_'.self::F_EXPORT_ID.'" value="'.$twiz_export_id.'"/><input type="hidden" name="twiz_'.self::F_GROUP_ORDER.'" id="twiz_'.self::F_GROUP_ORDER.'" value="'.$twiz_group_order.'"/></td></tr>
</table>'.$closediv.$jsscript_open.$jsscript_arrows.$jsscript_autoexpand.$toggleoptions.$jsscript_hide.$jsscript.$jsscript_close;
    
        return $htmlform;
    }

    function getOutputEasingLabel( $type = '' ){
    
        switch($type){
        
            case 'swing':
            
                return ''.__('swing', 'the-welcomizer').'';
                
                break;
                
            case 'linear':
            
                return ''.__('linear', 'the-welcomizer').'';
                
                break;
                
            default:
            
                return $type;
                
                break;
        }
        
        return '';
    }

    protected function getListArray( $where = '', $orderby = '' ){ 

        global $wpdb;
        
        $concat_orderby = "";

        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
        
            $twiz_order_by = get_option('twiz_order_by');    
            
        }else{
        
            $twiz_order_by = get_site_option('twiz_order_by');  

        }
        if( ( $this->user_id != '' ) and ( $this->user_id != 0 ) ){  // from front-end
        
            if(!isset($twiz_order_by[$this->user_id])){ $twiz_order_by[$this->user_id] = ''; }
            if($twiz_order_by[$this->user_id] == ''){ $twiz_order_by[$this->user_id] = self::F_ON_EVENT; }
        
        }
        
          switch($orderby){
        
            case '':

                if( ( $this->user_id != '' ) and ( $this->user_id != 0 ) ){ // not for front-end visitors
                
                    return $this->getListArray( $where , $twiz_order_by[$this->user_id]);
                    
                }else{
                
                    return $this->getListArray( $where , self::F_ON_EVENT); // << for front-end
                }
                
                break;
                
            case self::F_ON_EVENT:
             
                $orderby = " ORDER BY t.".self::F_GROUP_ORDER.", level, event_order, event_order_2, t.".self::F_ON_EVENT.", CAST(t.".self::F_START_DELAY." AS SIGNED), CAST(total_duration AS SIGNED), t.".self::F_LAYER_ID.", t.".self::F_TYPE;
                
                $concat_orderby = ", '.', c.".self::F_ON_EVENT.""; 
                
                $twiz_order_by[$this->user_id] = self::F_ON_EVENT;
                
                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
                
                    $code = update_option('twiz_order_by', $twiz_order_by);     
                    
                }else{
                
                    $code = update_site_option('twiz_order_by', $twiz_order_by);
                }                     

                break;
                
            case self::F_STATUS:
             
                $orderby = " ORDER BY t.".self::F_GROUP_ORDER.",level, t.".self::F_STATUS.", CAST(t.".self::F_START_DELAY." AS SIGNED), CAST(total_duration AS SIGNED), event_order, event_order_2, t.".self::F_ON_EVENT." , t.".self::F_LAYER_ID.", t.".self::F_TYPE;
                
                $concat_orderby = ", '.', c.".self::F_STATUS.""; 
                
                $twiz_order_by[$this->user_id] = self::F_STATUS;
                
                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
                
                    $code = update_option('twiz_order_by', $twiz_order_by);     
                    
                }else{
                
                    $code = update_site_option('twiz_order_by', $twiz_order_by);
                }    
                
                break;
                
            case self::F_LAYER_ID:
             
                $orderby = " ORDER BY t.".self::F_GROUP_ORDER.", level, t.".self::F_LAYER_ID.", t.".self::F_TYPE.", CAST(t.".self::F_START_DELAY." AS SIGNED), CAST(total_duration AS SIGNED), event_order, event_order_2, t.".self::F_ON_EVENT;
                
                $concat_orderby = ", '.', c.".self::F_LAYER_ID.", '.', c.".self::F_TYPE.", '.', c.".self::F_START_DELAY.", '.', CAST(total_duration AS SIGNED), '.', IF(c.".self::F_ON_EVENT." = '','0','1'), '.', IF(c.".self::F_ON_EVENT." = 'Manually','0','1')";
                
                $twiz_order_by[$this->user_id] = self::F_LAYER_ID;
                
                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
                
                    $code = update_option('twiz_order_by', $twiz_order_by);     
                    
                }else{
                
                    $code = update_site_option('twiz_order_by', $twiz_order_by);
                }    
                
                break;
                
            case self::F_START_DELAY:

                $orderby = " ORDER BY t.".self::F_GROUP_ORDER.", level, CAST(t.".self::F_START_DELAY." AS SIGNED), CAST(total_duration AS SIGNED), event_order, event_order_2, t.".self::F_ON_EVENT.", t.".self::F_LAYER_ID.", t.".self::F_TYPE;

               $concat_orderby = ", '.', '0'"; 
                 
                $twiz_order_by[$this->user_id] = self::F_START_DELAY;
                
                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
                
                    $code = update_option('twiz_order_by', $twiz_order_by);     
                    
                }else{
                
                    $code = update_site_option('twiz_order_by', $twiz_order_by);
                }    
                
                break;
                
            case self::F_DURATION: 
            
                $orderby = " ORDER BY t.".self::F_GROUP_ORDER.", level, CAST(total_duration AS SIGNED), event_order, event_order_2, t.".self::F_ON_EVENT.", CAST(t.".self::F_START_DELAY." AS SIGNED), t.".self::F_LAYER_ID.", t.".self::F_TYPE;

                $concat_orderby = ", '.', '0'"; 
                
                $twiz_order_by[$this->user_id] = self::F_DURATION;
                
                if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){
                
                    $code = update_option('twiz_order_by', $twiz_order_by);     
                    
                }else{
                
                    $code = update_site_option('twiz_order_by', $twiz_order_by);
                }    

                break;
        }
        
        $sql = "SELECT *, 
                   (".self::F_DURATION." + t.duration_x + IF(".self::F_DURATION_B." != '',".self::F_DURATION_B.",'0')) AS total_duration, 
                   (SELECT p.".self::F_EXPORT_ID."
                    FROM ".$this->table." p 
                    WHERE p.".self::F_EXPORT_ID." = t.".self::F_EXPORT_ID." AND p.".self::F_TYPE." = '".self::ELEMENT_TYPE_GROUP."' 
                    UNION
                    SELECT CONCAT(p.".self::F_EXPORT_ID.$concat_orderby.") 
                    FROM ".$this->table." p
                    INNER JOIN ".$this->table." c ON (p.".self::F_EXPORT_ID." = c.".self::F_PARENT_ID.") 
                    WHERE c.".self::F_EXPORT_ID." = t.".self::F_EXPORT_ID." AND p.".self::F_TYPE." = '".self::ELEMENT_TYPE_GROUP."' 
                   ) AS level, 
                   IF(".self::F_ON_EVENT." = '','0','1') AS event_order, 
                   IF(".self::F_ON_EVENT." = 'Manually','0','1') AS event_order_2 
                FROM (SELECT *,IF((((".self::F_OPTIONS_B." != '') 
                        or (".self::F_EXTRA_JS_B." != '')
                        or (".self::F_MOVE_TOP_POS_B." != '')
                        or (".self::F_MOVE_LEFT_POS_B." != ''))
                        and (".self::F_DURATION_B." = '')),".self::F_DURATION.",'0') AS duration_x FROM ".$this->table.") t " . $where . $orderby;   

        $rows = $wpdb->get_results($sql, ARRAY_A);

        return $rows;
    }
    
    function getHtmlList( $section_id = '', $saved_id = '', $orderby = '', $parent_id = '', $action = '' ){ 
    
        $container = '';
        $section_id = ( $section_id == '' ) ? $this->DEFAULT_SECTION[$this->user_id] : $section_id;
        $code = $this->updateSettingMenu( $section_id );
        
        // from the menu 
        $where = " WHERE ".self::F_SECTION_ID." = '".$section_id."'";
        $listarray = $this->getListArray( $where, $orderby ); // get all the data
        if(count($listarray)==0){ 
            
            $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'];
                    
            // show element
            // $("#twiz_export").fadeIn("fast");
            $container = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
        $("#twiz_loading_menu").html("");
        $(".twiz-status-menu").css("visibility","visible");
        $("#twiz_add_menu").fadeIn("fast");
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

            case 'hscroll':
                
                $title = __('Automatic horizontal scrolling', 'the-welcomizer');
                break;
                
            default:
                
                if( $from == '' ){
                
                    $row = $this->getRow( $id );
                    $title = $id . ' - '.$row[self::F_EXPORT_ID];
                    
                }else{
                
                    $prefix = $from."_";
                }
        }
 
        return '<div id="twiz_status_img_'.$prefix.$id.'" class="twiz-status twiz-'.$status.'" title="'.$title.'"></div>';

    }
    
    private function getHtmlJSFeatures( $id = '', $name = '', $section_id = '' ){

        $where = ($section_id!='') ? " WHERE ".self::F_SECTION_ID." = '".$section_id."' AND ".self::F_STATUS."=1" : '';
        
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
       
            if( ( $value[self::F_LOCK_EVENT] == '1' ) 
            and ( ( $value[self::F_ON_EVENT] != '') 
            and ( $value[self::F_ON_EVENT] != 'Manually') ) ){
            
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
        $html .=  '<br><div id="twiz_js_features_'.$name.'" class="twiz-js-features"><a id="twiz_jsf_code_snippets_'.$name.'" class="twiz-black">'.__('Examples', 'the-welcomizer').'</a> | <a id="twiz_jsf_functions_'.$name.'" class="">'.__('Functions', 'the-welcomizer').'</a> | <a id="twiz_jsf_bind_'.$name.'" class="">Unbind</a> | <a id="twiz_jsf_unlock_'.$name.'" class="">Unlock</a> | <a id="twiz_jsf_stop_'.$name.'" class="">'.__('Stop', 'the-welcomizer').'</a></div>';
        
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
    
    function getHtmlElementTypeList( $type = self::ELEMENT_TYPE_ID , $field = self::F_TYPE, $suffix = ''){
        
        $select = '<select name="twiz_'.$field.$suffix.'" id="twiz_'.$field.$suffix.'">';
        
        foreach ($this->array_element_type as $value){

            $selected = ($type == $value) ? ' selected="selected"' : '';
            
            $select .= '<option value="'.$value.'"'.$selected.'>'.$value.' =</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }  
    
    function getHtmlEventList( $event = '', $suffix = '', $class = '' ){
    
        $valuelbl = '';

        $class = ($class == '') ? '' : 'class="'.$class.'"';
        
        $select = '<select name="twiz_'.self::F_ON_EVENT.$suffix.'" id="twiz_'.self::F_ON_EVENT.$suffix.'" '.$class.'>';
        $select .= '<option value="">'.__('(Optional)', 'the-welcomizer').'</option>';
        
        $event = str_replace( self::EV_PREFIX_ON, "", $event );
        
        foreach ($this->array_on_event as $value){

            $selected = ($event == $value) ? ' selected="selected"' : '';
            $valuelbl = $this->format_on_event($value);
            $select .= '<option value="'.$value.'"'.$selected.'>'.$valuelbl.'</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }  

    function format_on_event( $value = '' ){
    
        if( $value != '' ){
        
            $value = ($value != self::EV_MANUAL) ? self::EV_PREFIX_ON.$value : $value;
            
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
    
     private function getHTMLCSSSnippets(){
        
   $select = '<select class="twiz-slc-js-features-css" name="twiz_slc_code_'.self::F_CSS.'" id="twiz_slc_code_'.self::F_CSS.'">';
        
        $select .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';
          
        $select .= '<optgroup label="'.__('CSS Styles', 'the-welcomizer').'">';
        
        foreach ( $this->array_css_snippets as $value ){
                    
            $select .= '<option value="'.$value.'">'.$value.'</option>';
        }
        
        $select .= '</optgroup>';
        
        $select .= '<optgroup label="'.__('Shortcode', 'the-welcomizer').'">';
            
        foreach ( $this->array_css_shortcode as $value ){
                    
            $select .= '<option value="'.$value.'">'.$value.'</option>';
        }
              
        $select .= '</optgroup>';
        
        $select .= '</select>';
        
        return $select;
    }
    
    private function getHTMLCodeSnippets( $name = '' ){
        
        $select = '<select class="twiz-slc-js-features" name="twiz_slc_code_'.$name.'" id="twiz_slc_code_'.$name.'">';
        
        $select .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';

        
        $select .= '<optgroup label="Twiz">[SNIPPETS_REPEAT_CONDITION]';
            
        foreach ( $this->array_twiz_snippets as $value ){
                    
            $select .= '<option value="'.$value.'">'.$value.'</option>';
        }
              
        $select .= '</optgroup>';
       
        $select .= '<optgroup label="'.__('Shortcode', 'the-welcomizer').'">';
            
        foreach ( $this->array_css_shortcode as $value ){
                    
            $select .= '<option value="'.$value.'">'.$value.'</option>';
        }
              
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
        
        $select .= '<optgroup label="jQuery">';
            
        foreach ( $this->array_jQuery_snippets as $value ){
                    
            $select .= '<option value="'.$value.'">'.$value.'</option>';
        }
              
        $select .= '</optgroup>';

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
    
    protected function getRow( $id = '' ){ 
    
        global $wpdb;
        
        if( $id == '' ){ return ''; }
    
        $sql = "SELECT *,( duration + duration_b ) AS total_duration FROM ".$this->table." WHERE ".self::F_ID." = '".$id."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
        
        return $row;
    }

    function getId( $column = '', $value = '' ){ 
    
        global $wpdb;
        
        if($value==''){return '';}
        if($column==''){return '';}
        
        $column = ($column=="delay") ? self::F_START_DELAY : $column;
    
        $sql = "SELECT ".self::F_ID." FROM ".$this->table." WHERE ".$column." = '".$value."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        $id = $row[self::F_ID];
  
        return $id;
    }

    function getUniqid(){

        $uniqid = substr( md5( uniqid( mt_rand(), true ) ) , 0, 6 );
        
        $alreadyexists = $this->exportIdExists($uniqid);
        
        if( $alreadyexists )$this->getUniqid(); //   get another one.
        
 
        return $uniqid;
    }
    
    function getValue( $id = '', $column = '' ){ 
    
        global $wpdb;
        
        if($id==''){return '';}
        if($column==''){return '';}
        
        $column = ($column=="delay") ? self::F_START_DELAY : $column;
    
        $sql = "SELECT ".$column." FROM ".$this->table." WHERE ".self::F_ID." = '".$id."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        $value = $row[$column];
  
        return $value;
    }

    private function updateSettingMenu( $section_id = '' ){
    
        if( $section_id == '' ){
        
            // DEFAULT_SECTION INIT
            $section_id = $this->DEFAULT_SECTION_HOME;
        }
        
        $this->DEFAULT_SECTION[$this->user_id] = $section_id;
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $code = update_option('twiz_setting_menu', $this->DEFAULT_SECTION);
            
        }else{

            $code = update_site_option('twiz_setting_menu', $this->DEFAULT_SECTION);
    
        }         
        
        return true;
    }
    
    private function updateTwizFunctions( $current_value = '', $new_value = '', $section_id = '') {

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
        
        if( $id == '' ){ 
        
            $action = self::ACTION_NEW;
            
        }else{
        
            $action = self::ACTION_EDIT;
        }
        
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
        $_POST['twiz_'.self::F_DURATION_B] = (!isset($_POST['twiz_'.self::F_DURATION_B])) ? '' :  $_POST['twiz_'.self::F_DURATION_B];
        $_POST['twiz_'.self::F_OUTPUT_POS] = (!isset($_POST['twiz_'.self::F_OUTPUT_POS])) ? '' : $_POST['twiz_'.self::F_OUTPUT_POS];
        $_POST['twiz_'.self::F_START_ELEMENT_TYPE] = (!isset($_POST['twiz_'.self::F_START_ELEMENT_TYPE])) ? '' : $_POST['twiz_'.self::F_START_ELEMENT_TYPE];
        $_POST['twiz_'.self::F_START_ELEMENT] = (!isset($_POST['twiz_'.self::F_START_ELEMENT])) ? '' : $_POST['twiz_'.self::F_START_ELEMENT];
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
        $_POST['twiz_'.self::F_CSS] = (!isset($_POST['twiz_'.self::F_CSS])) ? '' : $_POST['twiz_'.self::F_CSS];
        $_POST['twiz_'.self::F_EASING_A] = (!isset($_POST['twiz_'.self::F_EASING_A])) ? '' : $_POST['twiz_'.self::F_EASING_A];
        $_POST['twiz_'.self::F_MOVE_ELEMENT_TYPE_A] = (!isset($_POST['twiz_'.self::F_MOVE_ELEMENT_TYPE_A])) ? '' : $_POST['twiz_'.self::F_MOVE_ELEMENT_TYPE_A];
        $_POST['twiz_'.self::F_MOVE_ELEMENT_A] = (!isset($_POST['twiz_'.self::F_MOVE_ELEMENT_A])) ? '' : $_POST['twiz_'.self::F_MOVE_ELEMENT_A];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_A] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_A])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_A];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_A] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_A])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_A];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A];
        $_POST['twiz_'.self::F_OPTIONS_A] = (!isset($_POST['twiz_'.self::F_OPTIONS_A])) ? '' : $_POST['twiz_'.self::F_OPTIONS_A];
        $_POST['twiz_'.self::F_EXTRA_JS_A] = (!isset($_POST['twiz_'.self::F_EXTRA_JS_A])) ? '' : $_POST['twiz_'.self::F_EXTRA_JS_A];
        $_POST['twiz_'.self::F_EASING_B] = (!isset($_POST['twiz_'.self::F_EASING_B])) ? '' : $_POST['twiz_'.self::F_EASING_B];
        $_POST['twiz_'.self::F_MOVE_ELEMENT_TYPE_B] = (!isset($_POST['twiz_'.self::F_MOVE_ELEMENT_TYPE_B])) ? '' : $_POST['twiz_'.self::F_MOVE_ELEMENT_TYPE_B];
        $_POST['twiz_'.self::F_MOVE_ELEMENT_B] = (!isset($_POST['twiz_'.self::F_MOVE_ELEMENT_B])) ? '' : $_POST['twiz_'.self::F_MOVE_ELEMENT_B];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_B] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_B])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_B];
        $_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_B] = (!isset($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_B])) ? '' : $_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_B];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_B] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_B])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_B];
        $_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_B] = (!isset($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_B])) ? '' : $_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_B];
        $_POST['twiz_'.self::F_OPTIONS_B] = (!isset($_POST['twiz_'.self::F_OPTIONS_B])) ? '' : $_POST['twiz_'.self::F_OPTIONS_B];
        $_POST['twiz_'.self::F_EXTRA_JS_B] = (!isset($_POST['twiz_'.self::F_EXTRA_JS_B])) ? '' : $_POST['twiz_'.self::F_EXTRA_JS_B];
        $_POST['twiz_'.self::F_GROUP_ORDER] = (!isset($_POST['twiz_'.self::F_GROUP_ORDER])) ? '' : $_POST['twiz_'.self::F_GROUP_ORDER];
        
        
        $twiz_export_id = esc_attr(trim( $_POST['twiz_'.self::F_EXPORT_ID]));
        $twiz_status = esc_attr(trim($_POST['twiz_'.self::F_STATUS]));
        $twiz_status = ($twiz_status=='true') ? 1 : 0;
        
        $twiz_lock_event = esc_attr(trim($_POST['twiz_'.self::F_LOCK_EVENT]));
        $twiz_lock_event = ($twiz_lock_event=='true') ? 1 : 0;
        $twiz_lock_event_type = esc_attr(trim($_POST['twiz_'.self::F_LOCK_EVENT_TYPE]));
        
        $twiz_parent_id = esc_attr(trim($_POST['twiz_'.self::F_PARENT_ID]));
        $twiz_section_id = esc_attr(trim($_POST['twiz_'.self::F_SECTION_ID]));

        $twiz_blog_id = str_replace("\"", "", json_encode( $this->getSectionBlogId( $twiz_section_id ) )); // not from post
        $twiz_blog_id = str_replace("'", "", $twiz_blog_id);
        
        $twiz_type = esc_attr(trim($_POST['twiz_'.self::F_TYPE]));
        
        $twiz_layer_id = esc_attr(trim($_POST['twiz_'.self::F_LAYER_ID]));
        $twiz_layer_id = ($twiz_layer_id=='') ? '' : $twiz_layer_id;
        
        $twiz_start_element_type = esc_attr(trim($_POST['twiz_'.self::F_START_ELEMENT_TYPE]));
        $twiz_start_element = esc_attr(trim($_POST['twiz_'.self::F_START_ELEMENT]));
        $twiz_move_element_type_a = esc_attr(trim($_POST['twiz_'.self::F_MOVE_ELEMENT_TYPE_A]));
        $twiz_move_element_a = esc_attr(trim($_POST['twiz_'.self::F_MOVE_ELEMENT_A]));
        $twiz_move_element_type_b = esc_attr(trim($_POST['twiz_'.self::F_MOVE_ELEMENT_TYPE_B]));
        $twiz_move_element_b = esc_attr(trim($_POST['twiz_'.self::F_MOVE_ELEMENT_B]));
        
        $twiz_start_top_pos   = esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS]));
        $twiz_start_left_pos  = esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS]));
        $twiz_move_top_pos_a  = esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_A]));
        $twiz_move_left_pos_a = esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_A]));
        $twiz_move_top_pos_b  = esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_B]));
        $twiz_move_left_pos_b = esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_B]));
        
        $twiz_start_top_pos   = ( $twiz_start_top_pos == '' ) ? 'NULL' : $twiz_start_top_pos;
        $twiz_start_left_pos  = ( $twiz_start_left_pos == '' ) ? 'NULL' : $twiz_start_left_pos;
        $twiz_move_top_pos_a  = ( $twiz_move_top_pos_a == '' ) ? 'NULL' : $twiz_move_top_pos_a;
        $twiz_move_left_pos_a = ( $twiz_move_left_pos_a == '' ) ? 'NULL' : $twiz_move_left_pos_a;
        $twiz_move_top_pos_b  = ( $twiz_move_top_pos_b == '' ) ? 'NULL' : $twiz_move_top_pos_b;
        $twiz_move_left_pos_b = ( $twiz_move_left_pos_b == '' ) ? 'NULL' : $twiz_move_left_pos_b;
        
        $twiz_options_a = esc_attr(trim($_POST['twiz_'.self::F_OPTIONS_A]));
        $twiz_options_b = esc_attr(trim($_POST['twiz_'.self::F_OPTIONS_B]));

        $twiz_extra_js_a = esc_attr(trim( $_POST['twiz_'.self::F_EXTRA_JS_A]));
        $twiz_extra_js_b = esc_attr(trim($_POST['twiz_'.self::F_EXTRA_JS_B]));
        
        $twiz_javascript = esc_attr(trim($_POST['twiz_'.self::F_JAVASCRIPT]));
        $twiz_css = esc_attr(trim($_POST['twiz_'.self::F_CSS]));
        
        $twiz_start_delay = esc_attr(trim($_POST['twiz_'.self::F_START_DELAY]));
        $twiz_duration = esc_attr(trim($_POST['twiz_'.self::F_DURATION]));
        $twiz_duration_b = esc_attr(trim($_POST['twiz_'.self::F_DURATION_B]));
        $twiz_start_delay = ( $twiz_start_delay == '' ) ? '0' : $twiz_start_delay;
        $twiz_duration = ( $twiz_duration == '' ) ? '0' : $twiz_duration;

        $twiz_group_order = esc_attr(trim($_POST['twiz_'.self::F_GROUP_ORDER]));
        if(($_POST['twiz_'.self::F_ON_EVENT]=='')
        or($_POST['twiz_'.self::F_ON_EVENT]=='Manually')){
            $twiz_lock_event = 1; // by default
        }
        
        if( $action == self::ACTION_NEW ){ // add new

            $sql = "INSERT INTO ".$this->table." 
                  (".self::F_PARENT_ID."
                  ,".self::F_EXPORT_ID."
                  ,".self::F_BLOG_ID."
                  ,".self::F_SECTION_ID."
                  ,".self::F_STATUS."
                  ,".self::F_TYPE."
                  ,".self::F_LAYER_ID."
                  ,".self::F_ON_EVENT."                  
                  ,".self::F_LOCK_EVENT."                  
                  ,".self::F_LOCK_EVENT_TYPE."                  
                  ,".self::F_START_DELAY."
                  ,".self::F_DURATION."
                  ,".self::F_DURATION_B."
                  ,".self::F_OUTPUT."
                  ,".self::F_OUTPUT_POS."
                  ,".self::F_JAVASCRIPT."
                  ,".self::F_CSS."
                  ,".self::F_START_ELEMENT_TYPE."
                  ,".self::F_START_ELEMENT."
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
                  ,".self::F_MOVE_ELEMENT_TYPE_A."
                  ,".self::F_MOVE_ELEMENT_A."                   
                  ,".self::F_MOVE_TOP_POS_SIGN_A."
                  ,".self::F_MOVE_TOP_POS_A."
                  ,".self::F_MOVE_TOP_POS_FORMAT_A."
                  ,".self::F_MOVE_LEFT_POS_SIGN_A."
                  ,".self::F_MOVE_LEFT_POS_A."
                  ,".self::F_MOVE_LEFT_POS_FORMAT_A."
                  ,".self::F_MOVE_ELEMENT_TYPE_B."
                  ,".self::F_MOVE_ELEMENT_B."                  
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
                  ,".self::F_GROUP_ORDER."         
                  )VALUES('".$twiz_parent_id."'
                  ,'".$twiz_export_id."'
                  ,'".$twiz_blog_id."'
                  ,'".$twiz_section_id."'
                  ,'".$twiz_status."'
                  ,'".$twiz_type."'
                  ,'".$twiz_layer_id."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_ON_EVENT]))."'
                  ,'".$twiz_lock_event."'
                  ,'".$twiz_lock_event_type."'
                  ,'".$twiz_start_delay."'
                  ,'".$twiz_duration."'
                  ,'".$twiz_duration_b."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT_POS]))."'
                  ,'".$twiz_javascript."'
                  ,'".$twiz_css."'
                  ,'".$twiz_start_element_type."'
                  ,'".$twiz_start_element."'
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
                  ,'".$twiz_move_element_type_a."'
                  ,'".$twiz_move_element_a."'                  
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A]))."'    
                  ,".$twiz_move_top_pos_a."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A]))."'  
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A]))."'    
                  ,".$twiz_move_left_pos_a."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A]))."' 
                  ,'".$twiz_move_element_type_b."'
                  ,'".$twiz_move_element_b."'                    
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
                  ,'".$twiz_group_order."'                 
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
                 ,".self::F_BLOG_ID." = '".$twiz_blog_id."'
                 ,".self::F_SECTION_ID." = '".$twiz_section_id."'
                 ,".self::F_STATUS." = '".$twiz_status."'
                 ,".self::F_TYPE."  = '".$twiz_type."' 
                 ,".self::F_LAYER_ID." = '".$twiz_layer_id."'
                 ,".self::F_ON_EVENT." = '".esc_attr(trim($_POST['twiz_'.self::F_ON_EVENT]))."'
                 ,".self::F_LOCK_EVENT." = '".$twiz_lock_event."'
                 ,".self::F_LOCK_EVENT_TYPE." = '".$twiz_lock_event_type."'
                 ,".self::F_START_DELAY." = '".$twiz_start_delay."'
                 ,".self::F_DURATION." = '".$twiz_duration."'
                 ,".self::F_DURATION_B." = '".$twiz_duration_b."'
                 ,".self::F_OUTPUT." = '".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT]))."'
                 ,".self::F_OUTPUT_POS." = '".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT_POS]))."'
                 ,".self::F_JAVASCRIPT." = '".$twiz_javascript."' 
                 ,".self::F_CSS." = '".$twiz_css."' 
                 ,".self::F_START_ELEMENT_TYPE." = '".$twiz_start_element_type."' 
                 ,".self::F_START_ELEMENT." = '".$twiz_start_element."'                
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
                 ,".self::F_MOVE_ELEMENT_TYPE_A." = '".$twiz_move_element_type_a."' 
                 ,".self::F_MOVE_ELEMENT_A." = '".$twiz_move_element_a."' 
                 ,".self::F_MOVE_TOP_POS_SIGN_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A]))."'
                 ,".self::F_MOVE_TOP_POS_A." = ".$twiz_move_top_pos_a."
                 ,".self::F_MOVE_TOP_POS_FORMAT_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A]))."'
                 ,".self::F_MOVE_LEFT_POS_SIGN_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A]))."'
                 ,".self::F_MOVE_LEFT_POS_A." = ".$twiz_move_left_pos_a."
                 ,".self::F_MOVE_LEFT_POS_FORMAT_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A]))."'
                 ,".self::F_MOVE_ELEMENT_TYPE_B." = '".$twiz_move_element_type_b."' 
                 ,".self::F_MOVE_ELEMENT_B." = '".$twiz_move_element_b."'                  
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
                 ,".self::F_GROUP_ORDER." = '".$twiz_group_order."'                 
                  WHERE ".self::F_ID." = '".$id."';";
                    
            $code = $wpdb->query($sql);

            $ok = $this->updateTwizFunctions( $current_value, $twiz_layer_id, $twiz_section_id);
            
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
    
    private function getHtmlSkinBullets(){
           
        $dirarray = $this->getSkinsDirectory();

        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $bullet = get_option('twiz_bullet');
            
        }else{

            $bullet = get_site_option('twiz_bullet');
        }         
        
        $bullet[$this->user_id] = (!isset($bullet[$this->user_id])) ? '' : $bullet[$this->user_id];
        
        if( $bullet[$this->user_id] == self::LB_ORDER_UP ){

            $class = 'twiz-skin-bullet-up';
            
        }else{
        
            $class = 'twiz-skin-bullet-down';
        }
        
        $html_open = '<div id="twiz_skin_bullet" class="twiz-corner-top '.$class.'">';
        $html_img = '';
        $html_close = '</div>';
        
        sort($dirarray);
        
        foreach($dirarray as $value){
        
            $html_img .='<div id="twiz_skin_'.$value.'" class="twiz-skins twiz-skins-'.str_replace("_", "", $value).' twiz-float-left"></div>';
        }

        return $html_open.$html_img.$html_close;
    }
    
    protected function getImgGlobalStatus(){ 
        
        $htmlstatus = ($this->global_status == '1') ? $this->getHtmlImgStatus('global', self::STATUS_ACTIVE) : $this->getHtmlImgStatus('global', self::STATUS_INACTIVE);

        return $htmlstatus;
    }
    
    protected function getImgHScrollStatus(){ 
    
        $htmlstatus = ( $this->hscroll_status[$this->user_id] == '1' ) ? $this->getHtmlImgStatus('hscroll', self::STATUS_ACTIVE) : $this->getHtmlImgStatus('hscroll', self::STATUS_INACTIVE);

        return $htmlstatus;
    }    

    function switchHScrollStatus(){ 

        $this->hscroll_status[$this->user_id] = ( $this->hscroll_status[$this->user_id] == '0' ) ? '1' : '0'; // swicth the status value
        
        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $code = update_option('twiz_hscroll_status', $this->hscroll_status);

        }else{

            $code = update_site_option('twiz_hscroll_status', $this->hscroll_status);
        }                 
    
        $htmlstatus =  $this->getImgHScrollStatus();

        $jsonarray = json_encode( array('status' => $this->hscroll_status[$this->user_id], 'html'=> $htmlstatus.'<div class="twiz-arrow twiz-arrow-e twiz-hscroll-arrow"></div>'));
        
        return $jsonarray;
    }   
    
    function switchGlobalStatus(){ 

        $this->global_status = ( $this->global_status == '0' ) ? '1' : '0'; // swicth the status value

        if( ( !is_multisite() ) or ( $this->override_network_settings == '1' ) ){

            $code = update_option('twiz_global_status', $this->global_status );
            
        }else{

            $code = update_site_option('twiz_global_status', $this->global_status );
        } 
    
        $htmlstatus =  $this->getImgGlobalStatus();

        return $htmlstatus;
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
        
            
            if($newstatus=='1'){
            
                $newclassname = 'twiz-status-green';
                $searchclass = 'twiz-status-red';
                $htmlstatus = $this->getHtmlImgStatus($id, self::STATUS_ACTIVE);
                
            }else{
            
                $newclassname = 'twiz-status-red';
                $searchclass = 'twiz-status-green';
                $htmlstatus = $this->getHtmlImgStatus($id, self::STATUS_INACTIVE);
            }
            
        }else{ 
        
            
            if($value[self::F_STATUS]=='1'){ 
            
                $newclassname = 'twiz-status-green';
                $searchclass = 'twiz-status-red';
                $htmlstatus = $this->getHtmlImgStatus($id, self::STATUS_ACTIVE);
                
            }else{ 
            
                $newclassname = 'twiz-status-red';
                $searchclass = 'twiz-status-green';
                $htmlstatus = $this->getHtmlImgStatus($id, self::STATUS_INACTIVE);
            }
        }

        $json = json_encode( array('html' => $htmlstatus, 'searchclass' => $searchclass , 'newclassname' => $newclassname ));

        return $json;
    }
    
    private function preloadImages(){
    
    
        $dirarray = $this->getSkinsDirectory();

        sort($dirarray);
              
        $html = '';
       
        foreach( $dirarray as $value ){
        
            if( $this->skin[$this->user_id] != self::SKIN_PATH.$value ){
            
                if( $value != self::DEFAULT_SKIN ){
                
                    $html .= '<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/images/twiz-logo-big.png" class="twiz-display-none"/>';
                }
                
                $html .= '<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/images/twiz-save.gif" class="twiz-display-none"/>';
                $html .= '<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/images/twiz-save-dark.gif" class="twiz-display-none"/>';
                $html .= '<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/images/twiz-loading.gif" class="twiz-display-none"/>';
                $html .= '<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/images/twiz-big-loading.gif" class="twiz-display-none"/>';
            }
        }
     
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-success.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-download.png" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-inactive.png" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-plus.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-minus.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-edit.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-delete.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-copy.png" class="twiz-display-none"/>';
        
        if($this->skin[$this->user_id] != self::SKIN_PATH.self::DEFAULT_SKIN){
        
            $html .='<img src="'.$this->pluginUrl.$this->skin[$this->user_id].'/images/twiz-logo-big.png" class="twiz-display-none"/>';
        }
        
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->user_id].'/images/twiz-save.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->user_id].'/images/twiz-save-dark.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->user_id].'/images/twiz-loading.gif" class="twiz-display-none"/>';
        $html .='<img src="'.$this->pluginUrl.$this->skin[$this->user_id].'/images/twiz-big-loading.gif" class="twiz-display-none"/>';
    
        return $html;
    }
    
    function replaceTwizShortCode( $shortcode = '', $string = '' ){

        switch( $shortcode ){
        
            case self::SC_WP_UPLOAD_DIR:
                
                $string = str_replace( $shortcode, $this->uploadDir['baseurl'], $string );
                
                return $string;
            
            break;
        }
        
        return '';
    }
    
    function getAllBlogIds(){
                
        $blog_array = array();
        // Backward compatibility to v3.2
        $myCompatibility = new TwizCompatibility();
        
        // Backward compatibility to v3.2
        $sites_array = $myCompatibility->wp_wp_get_sites();       
        
        foreach( $sites_array as $key => $value ){
        
            $blog_array[$key]['id'] = $sites_array[$key]['blog_id'];
            $blog_array[$key]['path'] = $sites_array[$key]['path'];
        }            
        
        return  $blog_array;
    } 
    
    function matchDefaultSection( $section_id = '' ){
        
        foreach($this->array_default_section_noblogid as $value){
        
            if( preg_match("/".$value."/i",$section_id ) ){
            
                return true;
            }
        }
        return false;
    }    
    
    function setConfigurationSettings( $network_activation = '', $privacy_question_answered  = false ){

            if( ( $network_activation == '1' ) and ($this->override_network_settings !='1' ) ){


                $code = update_site_option('twiz_db_version', $this->dbVersion); 
                $code = update_site_option('twiz_global_status', '1'); 
                $code = update_site_option('twiz_cookie_js_status', false); 
                $code = update_site_option('twiz_network_activated', '1'); 
                $code = update_site_option('twiz_privacy_question_answered', $privacy_question_answered); 

                $code = update_site_option('twiz_toggle', array()); 
                $code = update_site_option('twiz_order_by',array()); 
                $code = update_site_option('twiz_skin', array());  
                $code = update_site_option('twiz_bullet', array());                         
                $code = update_site_option('twiz_setting_menu', array());      
                $code = update_site_option('twiz_hscroll_status', array()); 
                $code = update_option('twiz_override_network_settings',  '0');

            }else{

                    if( is_multisite() ) {
                    
                        $code = update_option('twiz_override_network_settings',  '1');
 
                    }else{
                    
                        $code = update_option('twiz_override_network_settings',  '0');
                    }  
                    
                if( is_multisite() ) {

                    $code = update_site_option('twiz_db_version', $this->dbVersion); 

                }
               
                $code = update_option('twiz_db_version', $this->dbVersion); 
                $code = update_option('twiz_global_status', '1'); 
                $code = update_option('twiz_cookie_js_status', false); 
                
                $blog_network_activated = get_option('twiz_network_activated');
                if($blog_network_activated == ''){ $code = update_option('twiz_network_activated', '0'); }
                
                $code = update_option('twiz_privacy_question_answered', $privacy_question_answered); 
                $code = update_option('twiz_toggle', array()); 
                $code = update_option('twiz_order_by',array()); 
                $code = update_option('twiz_skin', array());  
                $code = update_option('twiz_bullet', array());                         
                $code = update_option('twiz_setting_menu', array());      
                $code = update_option('twiz_hscroll_status', array()); 
            }
        
            $code = new TwizAdmin();// Default settings
            $code = new TwizMenu(); // Menu reformating
                   
            return true;
    }
}
?>