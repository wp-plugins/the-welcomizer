=== The Welcomizer ===
Contributors: sebwordpress
Donate link: http://www.sebastien-laframboise.com/the-welcomizer-souvenir-shop/
Tags: jquery, move, movement, simple, le welcomizeur, animation, animate, welcome, div, opacity, effects, Homepage, plugin, javascript, ajax, code, style, formatting, advertising, ads, promotions, easy, montreal, admin, wordpress, transparency, posts, Post, sidebar, images, image, page, pages, categories, category, android, blackberry, cellular, device, iphone, mobile, ipad, blog, enqueue, css, js, event, onclick, ondblclick, onmouseover, onmouseenter, onmouseout, onmousedown, onfocus, rotate, rotation, free, scale
Requires at least: 3.1
Tested up to: 3.1.2
Stable tag: 1.3.5.8
License: GPLv2

This plugin allows you to animate your blog using jQuery effects. (100% AJAX) + .js/.css Includer.

== Description ==

Welcomize your visitors also on categories and pages. This plugin allows you to animate your blog using jQuery effects. (100% AJAX) + .js/.css Includer. 
    
Designed for webmasters, marketing consultants, web designers and bloggers.

[Demo page](http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/) <- Learn by Example & Useful Tips

* Repeat one set of 2 movements with: `$(document).twizRepeat();`
* Or Replay the entire list of animations with: `$(document).twizReplay();`

Thanks for your feedback and support!

The Welcomizer has Spirit!

∞

Do you really like this plugin? --> [Share it on Facebook!](http://www.facebook.com/share.php?u=http%3A%2F%2Fwordpress.org%2Fextend%2Fplugins%2Fthe-welcomizer%2F)

== Features ==

* Activate or deactivate movements.
* Optional starting position: Top, Left.
* Ajax editable 'Delay' & 'Duration' column.
* Unlimited number of movements, take it easy! 
* Build lists for homepage, categories or pages.
* Optional css position: nothing, relative, absolute.
* Upload `.js` and/or `.css` files into the `Library`
* Delay and duration of movements are in milliseconds.
* Repeat an animation with `$(document).twizRepeat();`.
* Optional custom options textbox, for more custom options.
* Optional custom JavaScript textbox, triggered after each move.
* Clean uninstallation, all the data are erased on plugin deactivation.
* Automatic recognition of the js and css files under `/wp-content/twiz/`.
* Preview your data without editing them, with an advanced caching feature.
* Optional second move on the same editing panel. (Useful for back and forth).
* Flexible and easy movement to config (+ or -) Top and Left.
* Replay all animations with `$(document).twizReplay();`.
* Optional First and Second move.
* Global Online/Offline status.
* Trigger animation by Event.
* Pick an Option properties.
* Pick an HTML element ID.
* Multilingual Ready.
* Import a list.
* Export a list. 
* 100% AJAX.

== Installation ==

1. Create and make this directory writable: 'wp-content/twiz/'
2. Upload `/the-welcomizer` directory and files to the `/wp-content/plugins/` directory.
3. Activate the plugin through the Plugins menu in WordPress.
4. Find the plugin under the menu Appearance
5. Configure and save your first movement, or Import a twz/xml file.
6. Activate the global Online/Offline status. (The red button at the top.)

Upload `.js` and/or `.css` files into the Library.

Useful jQuery plugins to upload through the Library:
- 
- jQuery UI/Effects - [Download page](http://jqueryui.com/download) - [More info](http://docs.jquery.com/UI/Effects)
- Rotate3Di - [Download page](https://github.com/zachstronaut/rotate3Di) - [Author page](http://www.zachstronaut.com/projects/rotate3di/)
- jquery-animate-css-rotate-scale - [Download page](https://github.com/zachstronaut/jquery-animate-css-rotate-scale) - [Author page](http://www.zachstronaut.com)
- transform  - [Download page](https://github.com/heygrady/transform/) - [Author page](https://github.com/heygrady/)

== Screenshots ==

1. Panel when adding a new movement. 
2. Panel when viewing data. (Right click - View image) 

== Changelog ==

= 1.3.5.8 =

* Restricted the `buildups` of animations.
* New fields are now `simply` added to the database.

= 1.3.5.7 =

* Added `Trigger by Event`.
* Fixes and adjusmtents.

= 1.3.5.6 = 

* Added `Copy` action and action label.
* Ordered the list by `Delay` and by `Element Id`.
* Layout adjustements and fixes.

= 1.3.5.5 = 

* .twz and .xml are supported for import. 

= 1.3.5.4 = 

* The Library files are now enqueued in the configured order.

= 1.3.5.3 = 

* Added ability to upload `.css` files into the Library.
* Added ability to `reorder` the Library.
* The menu is displayed in alphabetical order.
* Textarea auto-resizing adjustements.

= 1.3.5.2 = 

* Repeat an animation with `$(document).twizRepeat();` inside the Extra JavaScript textbox.

= 1.3.5.1 =

* Transferred methods into the new TwizMenu class.
* Major and minor fixes.

= 1.3.5 =

* Fixed delete section menu.

= 1.3.4.9 = 

* Fixed notice and warning messages.

= 1.3.4.8 = 

* Replay all animations with `$(document).twizReplay();`.
* When deactivating and activating the plugin to reinitialize it, `/wp-content/twiz/` is no longer removed.
* Automatic recognition of the js files under `/wp-content/twiz/`.
* Minor fixes and adjustments.

= 1.3.4.7 =

* Bug fixes.

= 1.3.4.6 =
 
* Added a New `JavaScript File Manager`. 
* Added a preloader for directional images.
* Moved and renamed the upload directory to `/wp-content/twiz/`.
* Removed the `Saved!` message for faster editing and better focusing. 
* Optimized the auto-resizing of the textarea for better editing.
* The file uploader is ready for translation.
* Major and minor bug fixes.
* Adjustments.

= 1.3.4.5 =
 
* Fixed table reinitialization.

= 1.3.4.4 =
 
* Added a `Delete section` button.
* Fixed export multilingual filename.
* Other minor fixes.

= 1.3.4.3 =
 
* Single and double quotes are now allowed. 

= 1.3.4.2 =
 
* Fully compatible with Internet Explorer 9.
* Minor fixes.

= 1.3.4.1 =

* Minor adjustments.

= 1.3.4 =

* Fixed display code.

= 1.3.3.9 =

* Optimized code, new constants.
* Renamed elements. 
* Fixed Import-Export bug, missing value in action.

= 1.3.3.8 =

* Modified fileuploader stylesheet.

= 1.3.3.7 =

* Enqueued fileuploader stylesheet.
* Updated Italian translation.

= 1.3.3.6 =

* Added Import file(*.twz) feature. `Share lists and collaborate with friends!` 
* Major changes.

= 1.3.3.5 =

* Added compatibility with IE8...

= 1.3.3.4 =

* Fixed the visibility of the add section menu.

= 1.3.3.3 =

* Fixed export file.

= 1.3.3.2 =

* Added a new feature to export a list. 'Backup and Share lists with friends.'
* Added Layout for the Import feature, it will be available in the next version.
* Some adjustments.

= 1.3.3.1 =

* Added Spanish translation.
* Minor changes.

= 1.3.3 =

* Fixed bug (Internet Explorer).

= 1.3.2.9 =

* Added dynamic directional arrows to the editing panel.
* Updated screenshots.

= 1.3.2.8 =

* Modified the size and location of the arrows.
* Updated screenshots.

= 1.3.2.7 =

* Added directional arrows to the datapreview panel.
* Fixed the hidden datapreview panel.
* Updated screenshots.

= 1.3.2.6 =

* Fixed unresolved sections. 

= 1.3.2.5 =

* Added numeric validation on Left and Top.
* Modified Installation code.

= 1.3.2.4 =

* Fixed Installation.

= 1.3.2.3 =

* Added feature to build lists for Categories and also Pages.
* Modified stylesheet, navigation effects, and layout.
* Modified database, and installation code.
* Modified front-end generated code.
* Added a 2x label on the edition panel.
* Updated screenshots.
* Minor fixes.
* Enjoy!

= 1.3.2.2 =

* Linked the plugin version label to the plugin page on wordpress.org
* Replaced short PHP tags by full PHP tags.
* Applied some CSS Coding standards.
* Updated screenshots.
* Minor fixes.

= 1.3.2.1 =

* Fixed notice and warning messages.

= 1.3.2 =

* Fixed Call to undefined function file_get_html().

= 1.3.1.9 =

* Fixed Missing argument 1 for Twiz::getListArray().
* Fixed Missing argument 1 for Twiz::getHtmlForm().

= 1.3.1.8 =

* Added Ajax editable 'Duration' column.
* Improved Ajax editable 'Delay' column.

= 1.3.1.7 =

* Fixed Enlarged layout.

= 1.3.1.6 =

* Enlarged layout.

= 1.3.1.5 =

* Improved security using Nonces.

= 1.3.1.4 =

* Fixed the validation for the delay textbox.(after cancel)
* Added a nowrap on the title cell from the right panel.

= 1.3.1.3 =

* Fixed the display of the right panel after addnew.

= 1.3.1.2 =

* Replaced tabs by spaces, everywhere in all the source code.
* Disabled submit button on form submit.

= 1.3.1.1 =

* Added validation for the delay textbox.

= 1.3.1 =

* Added status color on the element Id inside the View.
* Display the View when mouseover the list.
* Added Ajax editable 'Delay' column.
* Renamed some variables.
* Fixed jQuery binding.
* Minor layout fixes.

= 1.3.0 =

* Added more personalized options.
* Fixed enqueued Stylesheet.
* Minor changes.

= 1.2.9 =

* Optimized Stylesheet. (twiz-style.css)
* Minor layout adjustements.

= 1.2.8 =

* Enqueued Stylesheet. (twiz-style.css)
* Minor layout fixes.

= 1.2.7 =

* Added a full advanced caching feature for the DataPreview results.
* Fixed empty Labels in the DataPreview.(has already been pushed)

= 1.2.6 =

* Layout adjustments, larger panel, larger textbox, other minor changes.
* Added a new DataPreview Panel.

= 1.2.5 =

* Fixed Pick List Options choices(duplicated).
* Fixed Pick List link, and image right space.
* Fixed Edition panel background color.
* Fixed Plugin Height.

= 1.2.4 =

* Added a Global Online/Offline status over the top.
* Added a big background logo.
* Some minor changes.

= 1.2.3 =

* Added 2 New `Easy Pick List` under `Personalized Options`.
* Some minor, and major layout adjustements.
* Auto-size textarea(in height).

= 1.2.2 =

* When editing an existing movement, `More Options` are now visible by default, if they aren't empty.
* Empty lines has been removed.
* readme.txt has been updated.

= 1.2.1 =

* In the listing panel, a `x2` in green is now displayed next to the `duration` value only when the second move has been configured.
* Added Italian translation.

= 1.2 =

* Now you can easily choose an element Id from a pick list. 100% AJAX.

= 1.1 =

* 2 minor css fixes.
* jQuery .animate() link added under Personalized Options.

= 1.0 =
* First release!

== Frequently Asked Questions == 

= I've translated your awesome plugin in MyLanguage. Could I send you the .po and .mo files? =

Yes, I will include your translation in future releases. E-mail me at `wordpress [at] sebastien-laframboise [dot] com`

= How to bring my element from outside the screen with an opacity effect? =
 
1. Add this style below to your HTML element: 

   style="position: absolute; opacity: 0; filter: alpha(opacity=0); z-index: 1;"

2. Adjust the starting position inside the editing panel. (e.g. Top: - 400 px , position: absolute)

3. Adjust the Top of the first move. (e.g. Top: + 800 px), reverse it for the second move.

4. Add the option 'opacity:1' for the first move, reverse it for the second move.

== Upgrade Notice ==

= 1.3.5.8 =

* Major update and fixes.

= 1.3.5.7 = 

* Added Trigger animation by event. And fixes.

= 1.3.5.6 = 

* Added `Copy` action, list ordered by `Delay` and `Element Id`.

= 1.3.5.5 = 

* .twz and .xml are supported for import. 

= 1.3.5.4 = 

* The Library files are now enqueued in the configured order.

= 1.3.5.3 = 

* Major update, new features.

= 1.3.5.2 = 

* Added Repeat function.

= 1.3.5.1 =

* Major and minor fixes...

= 1.3.5 = 

* Fixed delete section menu.

= 1.3.4.9 = 

* Fixed notice and warning messages.

= 1.3.4.8 =

* Added Replay function.

= 1.3.4.7 =

* Bug fixes.

= 1.3.4.6 =
 
* Added a JavaScript File Manager. Bug fixes. Adjustments.

= 1.3.4.5 =
 
* Fixed table reinitialization.

= 1.3.4.4 =

* Added a delete section button. Fixed export multilingual filename.

= 1.3.4.3 =
 
* Major fixes.

= 1.3.4.2 =

* Fully compatible with IE9, Minor Fixes.

= 1.3.4.1 =

* Minor adjustments.

= 1.3.4 =

* Fixed display code.

= 1.3.3.9 =

* Optimized code. New constants. Fixed Import-Export.

= 1.3.3.8 =

* Modified fileuploader stylesheet.

= 1.3.3.7 =

* Enqueued fileuploader stylesheet.

= 1.3.3.6 =

* Added Import file feature. Major changes.

= 1.3.3.5 =

* Added compatibility with IE8...

= 1.3.3.4 =

* Fixed the visibility of the add section menu.

= 1.3.3.3 =

* Fixed export file.

= 1.3.3.2 =

* Added a new feature to export a list. 

= 1.3.3.1 =

* Added Spanish translation. Minor changes.

= 1.3.3 =

* Fixed bug (Internet Explorer).

= 1.3.2.9 =

* Added dynamic arrows to the editing panel.

= 1.3.2.8 =

* Modified the size of the arrows.

= 1.3.2.7 =

* Fixed hidden datapreview panel. Added arrows.

= 1.3.2.6 =

* Fixed unresolved sections.

= 1.3.2.5 =

* Added validation, and modified Installation.

= 1.3.2.4 =

* Fixed Installation.

= 1.3.2.3 =

* This is the update we've all been waiting for.

= 1.3.2.2 =

* Minor fixes and adjustments.

= 1.3.2.1 =

* Fixed notice and warning messages.

= 1.3.2 =

* Fixed Call to undefined function file_get_html(). 

= 1.3.1.9 =

* Fixed Missing arguments.

= 1.3.1.8 =

* Added Ajax editable 'Duration' column. Layout adjustments, improvements.

= 1.3.1.7 =

* Fixed Enlarged layout.

= 1.3.1.6 =

* Enlarged layout.

= 1.3.1.5 =

* Improved security using Nonces.

= 1.3.1.4 =

* Fixed the validation for the delay textbox.(after cancel), minor fixes.

= 1.3.1.3 =

* Fixed the display of the right panel after addnew. 

= 1.3.1.2 =

* Disabled submit button on form submit. Minor fixes.

= 1.3.1.1 =

* Added validation for the delay textbox.

= 1.3.1 =

* Added Ajax editable 'Delay' column. + Major fixes.

= 1.3.0 =

* Major fixes. More options. Critical update.

= 1.2.9 =

* Optimized stylesheet. (twiz-style.css)

= 1.2.8 =

* Enqueued stylesheet. (twiz-style.css)

= 1.2.7 =

Added a full advanced caching feature for the DataPreview results.

= 1.2.6 =

New DataPreview Panel, larger layout.

= 1.2.5 =

Major fixes, and adjustements.

= 1.2.4 =

Added a Global Online/Offline status over the top.

= 1.2.3 =

Major update 1-2-3, new features, minor fixes.

= 1.2.2 =

`More options` are now visible, if they aren't empty + minor adjustments.

= 1.2.1 =

Added Italian translation + minor adjustments + Thanks for your support!

= 1.2 =

Now you can easily choose an element Id from a pick list.

= 1.1 =

Minor fixes.

= 1.0 =

First release!

== Languages ==

The Welcomizer is currently available in the following languages:

* English 
* Français - 100%
* Italiano ([by Gianni Diurno](http://gidibao.net/)) - 100%
* Español - 90%