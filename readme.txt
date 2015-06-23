=== The Welcomizer ===
Contributors: sebwordpress
Donate link: http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/
Tags: jquery, move, movement, simple, le welcomizeur, animation, animate, welcome, div, opacity, effects, Homepage, plugin, javascript, ajax, code, style, formatting, advertising, ads, promotions, easy, montreal, admin, wordpress, transparency, posts, Post, sidebar, images, image, page, pages, categories, category, android, blackberry, cellular, device, iphone, mobile, ipad, blog, enqueue, css, js, event, onclick, ondblclick, onmouseover, onmouseenter, onmouseout, onmousedown, onfocus, rotate, rotation, free, scale, like, bird, word, class, id, name, attribute
Requires at least: 3.2
Tested up to: 4.2.2
Stable tag: 2.8.1
License: GPLv2

This plugin allows you to quickly animate your WordPress blog.

== Description ==

Quickly create animations for your WordPress blog.
    
Designed for webmasters, marketing consultants, web designers and bloggers.

[Demo page](http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/) <- Basic examples

Multisite friendly.

∞

== Installation ==

**To install:**

1. Upload `/the-welcomizer` directory and files to the `/wp-content/plugins/` directory.
2. Activate the plugin through the Plugins menu in WordPress.
3. Find the plugin on the admin bar or under the menu Appearance.

**To uninstall:**

1. Go to plugin page, click `Admin` button, click `Removal settings`
2. check the option **Delete all settings when disabling the plugin**
3. check the option **Delete created directories when disabling the plugin** If you don't need them anymore.
4. Deactivate the plugin through the Plugins menu in WordPress, and then delete it.
 
== Screenshots ==

1. Panel when viewing data. 
2. Panel when adding a new animation. 

== Changelog ==


= 2.8.1 =

* Fixed section_id on a multisite installation. 

= 2.8 =

* Added compatibility with multisite.
* Added the ability to Import/Export a whole section.
* Added option `Display plugin environment variables` and constant TWIZ_FORCE_VARDUMP.
* Added constant TWIZ_LOG_ACTIVATION -> wp-content/uploads/the-welcomizer-activation-error.log
* Added constant TWIZ_LOG_DEACTIVATION -> wp-content/uploads/the-welcomizer-deactivation-error.log
* Added `Admins Only` to visibility options.
* Fixed twiz filename.
* Various UI adjustments and bug fixes.
* Replaced menu icons with SVG.
* Reviewed installation code & initialization settings.
* Resetted all UI settings.
* Updated jQuery libraries.

= 2.7.9.9 =

* Fixed admin role translation.

= 2.7.9.8 =

* Fixed Admin notice error.
* Fixed Delete created directories.
* Fixed Export notice.
* Fixed missing translation.
* Removed plugin footer ads.

= 2.7.9.7 =

* Fixed export all notice.

= 2.7.9.6 =

* Fixed import notice.
* Put back sans-serif.

= 2.7.9.5 =

* Fixed the categories results inside the output list.

= 2.7.9.4 =

* Fixed the export filenames to match the real time.
* Fixed the display of the export file button.

= 2.7.9.3 =

* Hidden the + and - drop down when the Transit jQuery library is selected.
* Added Privacy questions before accessing the plugin.
* Added a link to the plug-in on the admin bar.
* Added trailing slash verification to link directory.
* Maintained compatibility with WordPress version 3.1+.
* Fixed Uncategorized category, and non-existent pages or posts.
* Added verification of broken links to images within the view.
* Added download links to twiz files into the export file listing.
* Added the ability to Export All sections in one click + backup file.
* Added Settings link beside the activate/deactivate links on the plugins page.
* Added an option into admin section to remove created directories when disabling the plugin.
* Added visibility options in section creation.
* Added a Save & Stay checkbox in section creation.
* Adjusted UI a little bit here and there.
* Cleaned unused toggle options upon deleting a section, and clean obsolete ones.
* Created the necessary directories during installation or updates, if non-existent.
* Fixed a bug with copy group.
* Modified TwizGroup->copyGroup(), redirect to copy group form.
* Optimized loops in TwizOutput class.
* Kept in memory the menu settings that are inactive(shortcode ID, custom logic).
* Reviewed the labels and terms used.
* Shortened new unique numbers(export_id, parent_id).

= 2.7.9.1 =

* Replaced deprecated preg_replace /e with preg_replace_callback.

= 2.7.9 =

* Fixed compatibility with wp-minify.

= 2.7.8 =

* Minor adjustment.

= 2.7.7 =

* Adjusted shortcode replacement(visual/text mode). Once it's converted to url, it won't transform back into a shortcode to prevent replacing other link.

= 2.7.6 =

* Adjusted shortcode replacement(visual mode).
* Minor adjustment.

= 2.7.5 =

* Fixed Starting positions.

= 2.7.4 =

* Fixed bug with $(document).twizReplay();

= 2.7.3 =

* Added max-height to UI textarea.
* Added export filter to user settings.
* Added admin option to apply filter the_content.
* Fixed admin bug.

= 2.7.2 =

* Fixed import bug.

= 2.7.1 =

* Sorted export list.

= 2.7 =

* Added an option to import from the server.
* Added an optional HTML textbox to output type shortcode.
* Various bug fixes, output bug included.
* Modified and optimized UI.

= 2.6.3 =

* Minor UI adjustments.

= 2.6.2 =

* Fixed bold on selected group in list.

= 2.6.1 =

* Fixed bold on selected group in list.

= 2.6 =

* Transformed plugin images to background css images.

= 2.5.4 =

* Minor UI optimization.

= 2.5.3 =

* Fixed Output type unique on new sections.
* Fixed activation condition on default sections.

= 2.5.2 =

* Minor UI adjustments. (section menu)

= 2.5.1 =

* Modified label.

= 2.5 =

* Shortcode [twiz_wp_upload_dir] for image url is replaced(in visual mode:)
* Updated jquery included files for drag&drop in list.
* Modified textbox validation when editing an animation, you can now paste numbers for top, left etc...
* Added an option to promote this plugin under the admin section.

= 2.4 =

* Import into a group or export a group list while editing a group.
* Fixed import bugs.
* Minor adjustments. 

= 2.3.5 =

* Minor adjustments. 

= 2.3.4 =

* Fixed notice error. 
* Removed import export button when editing a group until import under a group and export a group is coded.

= 2.3.3 =

* Fixed Parse error bug.

= 2.3.2 =

* Fixed Parse error bug.

= 2.3.1 =

* Fixed bug.

= 2.3 =

* Added field element type inside Search and Replace.
* Added links to images inside the view with a preview on hover.
* Fixed droppable outside a group and overall.
* Modified import export button context.
* Reordered Group name inside Search and Replace as it appears in list.
* Reorganized view source code.
* Shortened function names inside the view.
* Only Manually event are triggered when Group function is called.

= 2.2.2 =

* Fixed group order on actions.

= 2.2.1 =

* Added indexes to db.

= 2.2 =

* Fixed section creation in IE.
* Modified the view, added group toggle.
* Fixed some css.

= 2.1.2 =

* Fixed drop row under group.

= 2.1.1 =

* Reestablished UI css compatibility with common browsers.

= 2.1 =

* Added ability to `reorder` created Groups in list.
* Fixed List order.
* Optimized some code.

= 2.0.2 =

* Fixed Group output bug.

= 2.0.1 =

* Adjusted stylesheets.

= 2.0 =

* Added find and replace under a group.
* Adjusted stylesheets.
* Minor fixes.

= 1.9.9.9 =

* Adjusted stylesheets.

= 1.9.9.8 =

* Adjusted stylesheets.

= 1.9.9.7 =

* Adjusted stylesheets.

= 1.9.9.6 =

* Adjusted stylesheets.

= 1.9.9.5 =

* Adjusted stylesheets.
* Modified layout.

= 1.9.9.4 =

* Corrected stylesheets.
* Modified images preload.
* Minor fixes.

= 1.9.9.3 =

* Adjusted stylesheets.

= 1.9.9.2 =

* Modified image preload.

= 1.9.9.1 =

* Put back images preload.

= 1.9.9 =

* Modified stylesheets to better match with the awesomeness of WordPress version 3.8.
* Modified UI.
* Major bug fixes.

= 1.9.8.9 =

* Adjusted order in list.
* Minor adjustments.

= 1.9.8.8 =

* Fixed order under groups in list.
* Adjusted order in list.

= 1.9.8.7 =

* Adjusted order in list.

= 1.9.8.6 =

* Adjusted order in list.
* Modified label Libraries to Examples.

= 1.9.8.5 =

* Optimized style sheets.
* Fixed ajax on link directory after validation.
* Minor layout adjustment.

= 1.9.8.4 =

* Fixed variable replacement while importing a twiz file.

= 1.9.8.3 =

* Minor layout adjustment.

= 1.9.8.2 =

* Fixed new element type.

= 1.9.8.1 =

* Fixed new element type.

= 1.9.8 =

* Added the possibility to turn on/off the Horizontal Auto Scrolling. 
* Added [other] to element type. e.g. window

= 1.9.7.9 =

* Applied a filter onto the Unlock dropdown list used to unlock animations that are triggered by event.

= 1.9.7.8 =

* Fixed URL shortcode for images in feed.

= 1.9.7.7 =

* Cleaned shortcode in feed.

= 1.9.7.6 =

* Fixed drag&drop in list.

= 1.9.7.5 =

* Fixed drag&drop in list.
* Updated jQuery UI.

= 1.9.7.4 =

* Minor adjustments.

= 1.9.7.3 =

* Modified menu layout.
* Minor adjustments.

= 1.9.7.2 =

* Fixed pre-selected section when cookie is disabled.

= 1.9.7.1 =

* Fixed selected section on default sections.

= 1.9.7 =

* Implemented Seth Godin’s idea, added cookie condition to sections.
* Added the option `Empty list`.
* Modified the scope of twiz_repeat_xx,twiz_locked_xx variables so they can be used inside another section that is outputted with a shortcode.
* Modified the order of the Duration column based on the real total.
* Fixed ajax on some actions.
* Fixed cookie feature.
* Fixed section prefixes on save section.

= 1.9.6 =

* Modified some labels.

= 1.9.5.9 =

* Fixed twizGetView.

= 1.9.5.8 =

* Fixed ajax in list.

= 1.9.5.7 =

* Fixed Event list in list.

= 1.9.5.6 =

* Adjusted some UI labels.
* Cleaned some code.

= 1.9.5.5 =

* Added header to vertical menu.
* Fixed binding after datagrid editing.
* Minor fixes.

= 1.9.5.4 =

* Fixed multiple ajax requests within the UI.
* Fixed ajax duration format in list.
* Fixed loading image on edit sections.

= 1.9.5.3 =

* Fixed dynamic arrows.

= 1.9.5.1 =

* Fixed arrows.

= 1.9.5 = 

* Optimized jQuery transit integration.
* Added a new optional positioning method (for right to left websites).
* Added date and time to export filename.
* Modified the view.
* Modified admin panel.
* Modified some labels.

= 1.9.4 = 

* Added the ability to specify an optional duration.
* Added shortcode [twiz_wp_upload_dir].
* Fixed quote character in Extra CSS.
* Various UI adjustments.
* Other minor fixes.
 
= 1.9.3.1 =

* Minor adjustments.

= 1.9.3 = 

* Fixed Import mapping bug concerning event functions.
* Fixed Find&Replace bug.
* Modified the view.
* Added some jQuery code snippet.
* Minor adjustment.

= 1.9.2.2 = 

* Reset scroll position to zero after clicking a group link.
* Minor adjustments.

= 1.9.2.1 = 

* Minor adjustments.

= 1.9.2 = 

* Fixed the view synchronization after inline list editing.

= 1.9.1.1 = 

* Fixed duration format.

= 1.9.1 = 

* Added action links on views.
* Modified the view.
* Various UI improvements.

= 1.9 = 

* Added infinite depth on views. 
* Added a jQuery code snippet.
* Added element name to export file name when applicable.
* Added missing `CSS Styles` to `Starting Positions` inside Search&Replace.
* Optimized output for animations that contains nothing but CSS Styles.
* Minor fixes.

= 1.8.8 = 

* Added `CSS Styles` to `Starting Positions`.
* Modified default output of Starting Positions to `CSS Styles`.
* Modified default output of JavaScript to `After the delay`.

= 1.8.7.2 = 

* Fixed Output bug concerning events.
* Modified translation.

= 1.8.7.1 = 

* Layout adjustment.

= 1.8.7 = 

* Modified the view.
* Modified ads.
* Minor fixes.

= 1.8.6 = 

* Fixed $(document).twizRepeat(X); with class or name or tag.
* Automaticaly adding stop().animate...with class or name or tag.
* UI adjustments.
* Optimized the view.

= 1.8.5.3 = 

* Fixed copy group feature.
* Layout adjustment.

= 1.8.5.2 = 

* Layout adjustment.

= 1.8.5.1 = 

* Optimized and Modified the view.
* Fixed toggle of `More configurations` with CSS.

= 1.8.5 = 

* Fixed min-height of the right panel.

= 1.8.4 = 

* Fixed Extra JavaScript inside the right panel.

= 1.8.3 = 

* Excluded jQuery restricted for CSS field.

= 1.8.2 = 

* Fixed Undefined index in find and replace.

= 1.8.1 = 

* Removed style tag when empty.
* Removed php cookie validation for styles.

= 1.8 = 

* Added the possibility to add CSS styles in each animation.
* Added some CSS snippets.
* Added new fields to the database.
* Added Cancel links in admin section.
* Fixed a find&replace tab bug.
* Modified the view.
* Modified Find & Replace.
* Modified and optimized UI.
* Optimized some source code.

= 1.7.1 = 

* Fixed the position by default under Starting Positions.

= 1.7 = 

* Added the possibility to attach a different element under each moves, and also under the Starting Positions.
* Added some jQuery code snippets.
* Added new fields to the database.
* Fixed the missing output of the starting positions after the delay.
* Fixed the action binding of the Library.
* Fixed a find&replace bug.
* Fixed Library Order Keys.
* Modified Ads conditions.
* Modified Find & Replace.
* Modified the view.
* Optimized some source code.

= 1.6.1 = 

* Fixed comments within the JavaScript text box.
* Fixed comments within the JavaScript text box.

= 1.6 = 

* Added loading gif on certain actions.
* Auto-checked display easing option.
* Added parentid validation in output code.
* Added output code verification everywhere.
* Fixed toggle css.

= 1.5.9 = 

* Cleaned the potential mess of the previous bug.

= 1.5.8 = 

* Fixed twiz_parent_id bug.

= 1.5.7 = 

* Fixed update. 

= 1.5.6 = 

* Updated jQuery transform.. 

= 1.5.5 = 

* Small fix for update to jQuery Transit. 

= 1.5.4 = 

* Fixed Option Display extra easing in lists.

= 1.5.3 = 

* Updated jQuery Transit.
* Minor adjustments.

= 1.5.2 = 

* Updated rotate3Di.js to v0.9.2.
* Updated jquery-animate-css-rotate-scale.js.

= 1.5.1 = 

* Added a missing translation.
* Modified custom logic e.g.

= 1.5 = 

* Added the ability to create smart groups (rows are draggable).
* Added the ability to link additional directories to the Library.
* Added an option jQuery Easing under Built-in jQuery packages.
* Renamed twiz_active variables to twiz_locked.
* Prepared Library section for the bext version.
* Various UI improvements and bug fixes etc...
* Modified UI preferences, added per user.
* Modified section context.

= 1.4.9.1 = 

* Fixed returned message from find and replace.

= 1.4.9 = 

* Added a find and replace (simple or precise).
* Added support for importing a twz file more than once.
* Added 2 new admin options. (1. Remove ads. 2. Find & Replace method.)
* Removed unmaintained and obsolete translation files.
* Various UI adjusments.

= 1.4.8.6 = 

* Fixed the dashboard bug.
* Fixed a missing css.

= 1.4.8.5 = 

* Fixed a translation string.
* Added a code snippet.
* Minor adjusments.

= 1.4.8.4 = 

* Fixed bugs.
* Merged Bind and Unbind.
* Added an option to manually unlock events (automatic by default).
* Added Unlock variables.

= 1.4.8.3 = 

* Fixed PHP cookie for multi-section animations.

= 1.4.8.2 = 

* Revised the cookie options.
* Fixed some bugs.

= 1.4.8.1 = 

* Fixed JScookie: per hour, per day, etc...

= 1.4.8 = 

* Added the element type `tag` for HTML tags.
* Moved the shortcode sample into a textbox.
* Added an optional `Cookie options` (JS or PHP or both) to menu sections.
* Various UI improvements.
* Fixed a lot of bugs.

= 1.4.7 = 

* Added a second save button in admin section.
* Added an extra easing option in admin section.
* Added 2 more Minimum Role option in admin section.
* Adjusted and fixed layout.
* Fixed a missing saved value in admin section.
* Integrated some libraries as options in admin section.

= 1.4.6.1 = 

* Fixed z-index of textareas.
* Removed the right view width.
* Adjusted navigation.

= 1.4.6 = 

* Added a new admin option. Disable \'ajax, post and cookie\' (Checked by default)
* Modified default settings.
* Modified menu navigation.
* Fixed admin panel layout.
* Various bug fixes. 

= 1.4.5.3 = 

* Available events only, for binding lists.
 
= 1.4.5.2 = 

* Bug fixes.

= 1.4.5.1 = 

* Added a Save & Stay option.
* Bug fixes and adjustments.

= 1.4.5 = 

* Added new features under JavaScript textboxes.
* Added a new cancel and save button. 
* Bug fixes. 
* Fixed layout. 

= 1.4.4.7 = 

* Fixed default values in the list.

= 1.4.4.6 = 

* Added a new checkbox to lock an event or not.
* The current object is now used for event triggered animations.
* Modified installation procedure, and removed the code to create directories.

= 1.4.4.5 = 

* Fixed new names inside the right panel.

= 1.4.4.4 = 

* Added Support for multiple element names. e.g. 'recent-posts-3 li a'
* Various fixes.

= 1.4.4.3 = 

* Fixed a compatibility issue.

= 1.4.4.2 = 

* Various minor adjustments.

= 1.4.4.1 = 

* Fixed a string replacement within Twiz functions.

= 1.4.4 = 

* Added [Shortcode] to the output choices.
* Fixed offline status of sections with custom logic.
* Minor fixes.

= 1.4.3 =

* Added the ability to sort the list.
* Added links to twiz functions in the right panel.
* Added an editable `Event` column.
* Added random ads in the footer.
* Modified the display of `More configurations`.
* Modified the closing of the vertical menu.
* Solved compatibility with Opera.
* Sorted items in the vertical menu.
* Sorted the `Event` column.
* Various bug fixes, adjusments and code review.

= 1.4.2.1 = 

* Removed auto-draft items from lists.

= 1.4.2 = 

* Added unpublished pages & articles to lists.

= 1.4.1 = 

* Added preloading of 2 loading images.

= 1.4 = 

* Optimized the behavior of all loading images.
* Updating the names of functions twiz etc…when the name is changed.
* Added current animation to functions list.

= 1.3.9.9 = 

* Fixed event bug.
* Modified the skin preloads.

= 1.3.9.8 = 

* Added the `e` parameter for events.
* Added the ability to unbind and bind events.
* Added Admin option `Starting position by default`.
* Changed output type by default to `Multiple`.
* Modified the data type and validation of `Start delay` and `Duration` to accept variables.
* Preloads the default skin.
* Minor fixes.
* Improved the UI speed.
* Fixed later: The event bug.

= 1.3.9.7 = 

* Turned some images into CSS background.
* Re-established CSS compatibility with WP v3.1 
* Minor fixes.

= 1.3.9.6 = 

* Modified the effect after saving.(finally)
* Modified stylesheets.
* Created ajax gif for skins.
* Happy holidays to All.
* Updated later: Italian translation.

= 1.3.9.5 = 

* Fixed activation button.
* Added hooks for scripts: front-end, and admin panel.
* Added some skins, click the logo.
* Fixed stylesheet.
* Fixed later: js skin var.
* Updated later: skins css.

= 1.3.9.4 = 

* Added the ability to create custom menu.
* Added support for multiple $(document).twizReplay(); on the same page from different sections.
* Added Admin option `Maximum number of posts in lists`.
* Multiple layout adjustments.
* Fixed later: Menu translation.
* Fixed undefined ABSPATH in twiz-ajax.php

= 1.3.9.3 = 

* Added a new OPTIONAL parameter to $(document).twizRepeat(X);  X = number of times to repeat, BLANK equal infinite repetition.
* Added current object to `Repeat` & `Replay` functions.
* Replaced `this` inside the 2 JavaScript textbox.
* Fixed later: Fixed repeat parameter.
* Updated later: Updated Italian translation.

= 1.3.9.2 = 

* Fixed `Home` activation button display.
* Fixed plugin update.

= 1.3.9.1 = 

* Added Admin option `Minimum Role to access this plugin`.
* Fixed some display activation(Everywhere).
* Fixed `Home` translation.
 
= 1.3.9 =

* Added positions `fixed` and `static`.
* Added Admin option `Delete all when disabling the plugin`. (Now inactive by default)
* Fixed the Library Order feature. 
* Adjusted the layout.

= 1.3.8.9 =

* Added a vertical alignment to the right panel.
* Excluded auto-repeated animations from the Replay.
* Updated Italian translation

= 1.3.8.8 =

* Layout fixes.

= 1.3.8.7 =

* Bug fixes.
* Restructured sections.

= 1.3.8.6 =

* Added a vertical menu.
* Removed radio buttons from the header.
* Added status image on each sections.
* Added Ajax refresh to the section scroll list, no need to refresh the page anymore.
* Remove the default `***` when saving an empty element.
* Added a title section for the Library and the Admin section.
* Merged the date with the title of a post.
* Fixed bugs.

= 1.3.8.5 =

* Added `swing` and `linear` option above each moves. (swing by default)
* Added links `Edit - Copy - Delete` under each element.
* Added option `Register jQuery default library`. (active by default)
* Modified the right panel view.
* Fixed layout stylesheet.

= 1.3.8.4 =

* Fixed `The plugin generated 2 characters of unexpected output.`
* Modified global status. (Active by default)

= 1.3.8.3 =

* Added `Output code compression` option. (Inactive by default)

= 1.3.8.2 =

* Fixed time of second move when empty.
* Rewritten the output code.
* Minor fixes.

= 1.3.8.1 =

* Updated Italian translation.
* Fixed button translation.
* Fixed directional images.

= 1.3.8 =

* Added Output code hook choices - Admin section. (default wp-head)
* Added `empty` for Top and Left under the First and Second move.
* Many minor fixes.

= 1.3.7.9 =

* Added `px, %, em, in` as choices for Top and Left.
* Layout fixes.

= 1.3.7.8 =

* Reordered the animations list by ID.
* Fixed Twiz functions calls.
* Minor adjustments.

= 1.3.7.7 =

* Fixed JavaScript OnReady.
* Fixed some output bugs.
* Fixed status bug.
* Adjusted Labels.

= 1.3.7.6 =

* Added New export id for functions and variables.
* Added Hotfix to replace current ids.
* Role is set to `manage_options`.

= 1.3.7.5 =

* Adjusted Right panel inside two conditional blocks.
* Renamed functions to twiz_*
* Fixed Animations list.
* Layout fixes.
* Minor fixes.

= 1.3.7.4 =

* Added the ability to easily call manually triggered animations and others into the JavaScript.
* The id of each row is displayed on status image.
* Simplified the right panel view.
* Layout adjustments.

= 1.3.7.3 =

* Added the ability to choose a post when creating menus.

= 1.3.7.2 =

* Fixed conflict with upload media manager in post page.

= 1.3.7.1 =

* Fixed wp-minify incompatibility(new).

= 1.3.7 =

* Added the ability to output starting positions OnReady, before or after the delay. 
* Added the ability to output the JavaScript also OnReady.
* Export files are created and moved to /wp-content/twiz/export/
* All scripts are enqueued only on plugin page.
* Layout adjustments.
* Minor fixes.

= 1.3.6.9 =

* Optimized output code.

= 1.3.6.8 =

* Correction, small configs are always output before the delay. 
* The JavaScript has an option before or after the delay.

= 1.3.6.7 =

* Output small configs before the JavaScript.
* Small layout adustement.

= 1.3.6.6 =

* Simplified the editing panel.
* Added the ability to output config. before the delay, or after the delay.
* All previous starting configurations are switched before the delay by default.

= 1.3.6.5 =

* Fixed navigation import menu, and default action label.

= 1.3.6.4 =

* Added support for $(this). inside the textbox `JavaScript Before`.

= 1.3.6.3 =

* All menu buttons are now visible when editing.

= 1.3.6.2 =

* Added the layout of the admin button.
* Layout adjustments.
* Minor fixes.

= 1.3.6.1 =

* Added the ability to animate elements also with attribute "name" or "class".
* Pick an ID from list has been removed, get Firebug.
* Adjusted textarea resize behavior.
* Added New field to the database. 
* Layout adjustments.
* Minor fixes.

= 1.3.6 =

* Updated Italian translation
* Fixed Everywhere, Everywhere.

= 1.3.5.9 =

* Added the ability to add animations everywhere, all articles, all categories and all pages.
* Added another JavaScript textbox to the editing panel.
* Added a Z-Index textbox to the editing panel.
* Added the ability to export only one animation.
* Added New fields to the database. 
* Layout adjustments.
* Minor fixes.

= 1.3.5.8 =

* Restricted the `buildups` of animations.
* New fields are now `simply` added to the database.

= 1.3.5.7 =

* Added `Trigger by Event`.
* Fixes and adjusmtents.

= 1.3.5.6 = 

* Added `Copy` action and action label.
* Ordered the list by `Delay` and by `Element Id`.
* Layout adjustments and fixes.

= 1.3.5.5 = 

* .twz and .xml are supported for import. 

= 1.3.5.4 = 

* The Library files are now enqueued in the configured order.

= 1.3.5.3 = 

* Added ability to upload `.css` files into the Library.
* Added ability to `reorder` the Library.
* The menu is displayed in alphabetical order.
* Textarea auto-resizing adjustments.

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
* Minor layout adjustments.

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
* Some minor, and major layout adjustments.
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

== Upgrade Notice ==

= 2.8.1 =

Bug fixe.

= 2.8 =

Major update and bug fixes.

= 2.7.9.9 =

Bug fixe.

= 2.7.9.8 =

Bug fixes.

= 2.7.9.7 =

Minor bug fixes.

= 2.7.9.6 =

Minor bug fixes.

= 2.7.9.5 =

Fixed the categories results inside the output list.

= 2.7.9.4 =

Minor bug fixes.

= 2.7.9.3 =

New features, bug fixes.

= 2.7.9.1 =

Replaced deprecated preg_replace /e with preg_replace_callback.

= 2.7.9 =

Fixed compatibility with wp-minify.

= 2.7.8 =

Minor adjustment.

= 2.7.7 =

Adjusted shortcode replacement(visual/text mode).

= 2.7.6 =

Adjusted shortcode replacement(visual mode).

= 2.7.5 =

Fixed Starting positions.

= 2.7.4 =

Fixed bug with $(document).twizReplay();

= 2.7.3 =

Fixed bug.

= 2.7.2 =

Fixed import bug.

= 2.7.1 =

Sorted export list. 

= 2.7 =

Many adjustments, new features. 

= 2.6.3 =

Minor UI adjustments.

= 2.6.2 =

Fixed bold on selected group in list.

= 2.6.1 =

Fixed bold on selected group in list.

= 2.6 =

Transformed plugin images to background css images.

= 2.5.4 =

Minor UI optimization.

= 2.5.3 =

Fixed 2 major bugs.

= 2.5.2 =

Minor UI adjustments. 

= 2.5.1 =

Modified label.

= 2.5 =

Many adjustments.

= 2.4 =

Minor adjustments. 

= 2.3.5 =

Minor adjustments. 

= 2.3.4 =

Fixed notice error.

= 2.3.3 =

Fixed Parse error bug.

= 2.3.2 =

Fixed Parse error bug.

= 2.3.1 =

Fixed bug.

= 2.3 =

Many adjustments.

= 2.2.2 =

Fixed group order on actions.

= 2.2.1 =

Added indexes to db.

= 2.2 =

Modified the view, added group toggle.

= 2.1.2 =

Fixed drop row under group.

= 2.1.1 =

Reestablished UI css compatibility with common browsers.

= 2.1 =

Added the ability to `reorder` created Groups in list.

= 2.0.2 =

Fixed Group output bug.

= 2.0.1 =

Adjusted stylesheets.

= 2.0 =

Adjusted stylesheets.

= 1.9.9.9 =

Adjusted stylesheets.

= 1.9.9.8 =

Adjusted stylesheets.

= 1.9.9.7 =

Adjusted stylesheets.

= 1.9.9.6 =

Adjusted stylesheets.

= 1.9.9.5 =

Modified layout.

= 1.9.9.4 =

Corrected stylesheets.

= 1.9.9.3 =

Adjusted stylesheets.

= 1.9.9.2 =

Modified image preload.

= 1.9.9.1 =

Put back images preload.

= 1.9.9 =

Modified stylesheets

= 1.9.8.9 =

Minor adjustments.

= 1.9.8.8 =

Adjusted order in list.

= 1.9.8.7 =

Adjusted order in list.

= 1.9.8.6 =

Adjusted order in list.

= 1.9.8.5 =

Optimized style sheets.

= 1.9.8.4 =

Fixed variable replacement while importing a twiz file.

= 1.9.8.3 =

Minor layout adjustment.

= 1.9.8.2 =

Fixed new element type.

= 1.9.8.1 =

Fixed new element type.

= 1.9.8 =

Added new features. 

= 1.9.7.9 =

Applied a filter onto the Unlock dropdown list.

= 1.9.7.8 =

Fixed URL shortcode for images in feed.

= 1.9.7.7 =

Cleaned shortcode in feed.

= 1.9.7.6 =

Fixed drag&drop in list.

= 1.9.7.5 =

Fixed drag&drop in list.

= 1.9.7.4 =

Minor adjustments.

= 1.9.7.3 =

Modified menu layout.

= 1.9.7.2 =

Fixed pre-selected section when cookie is disabled.

= 1.9.7.1 =

Fixed selected section on default sections.

= 1.9.7 =

Implemented Seth Godin’s idea.

= 1.9.6 =

Modified some labels.

= 1.9.5.9 =

Fixed twizGetView.

= 1.9.5.8 =

Fixed ajax in list.

= 1.9.5.7 =

Fixed Event list in list.

= 1.9.5.6 =

Adjusted some UI labels.

= 1.9.5.5 =

Fixed binding in list.

= 1.9.5.4 =

Minor fixes.

= 1.9.5.3 =

Fixed dynamic arrows.

= 1.9.5.1 =

Fixed arrows.

= 1.9.5 = 

Added a new optional positioning method.

= 1.9.4 = 

Added optional duration.

= 1.9.3.1 = 

Minor adjustments.

= 1.9.3 =

Minor fixes and adjustement.

= 1.9.2.2 = 

Minor adjustments.

= 1.9.2.1 = 

Minor adjustments.

= 1.9.2 = 

Fixed the view synchronization.

= 1.9.1.1 = 

Fixed duration format.

= 1.9.1 = 

Various UI improvements.

= 1.9 = 

Added infinite depth on views. 

= 1.8.8 = 

Added CSS Styles to Starting Positions.

= 1.8.7.2 = 

Fixed Output bug concerning events.

= 1.8.7.1 = 

Layout adjustment.

= 1.8.7 = 

Minor fixes.

= 1.8.6 = 

Major fixes.

= 1.8.5.3 = 

Fixed copy group feature.

= 1.8.5.2 = 

Layout adjustment.

= 1.8.5.1 = 

Modified the view.

= 1.8.5 = 

Fixed min-height of the right panel.

= 1.8.4 = 

Fixed Extra JavaScript inside the right panel.

= 1.8.3 = 

Minor fixes.

= 1.8.2 = 

Fixed Undefined index in find and replace.

= 1.8.1 =

Fixes.

= 1.8 = 

Added the possibility to add CSS styles in each animation.

= 1.7.1 = 

Fixed the position by default under Starting Positions.

= 1.7 = 

Major update.

= 1.6.1 = 

Fixed comments within the JavaScript text box.

= 1.6 = 

Added loading gif. Minor fixes.

= 1.5.9 = 

Cleaned the potential mess of the previous bug.

= 1.5.8 = 

Fixed twiz_parent_id bug.

= 1.5.7 = 

Fixed update. 

= 1.5.6 = 

Updated jQuery transform.. 

= 1.5.5 = 

Small fix for update to jQuery Transit. 

= 1.5.4 = 

Fixed Option Display extra easing in lists.

= 1.5.3 = 

Updated jQuery Transit.

= 1.5.2 = 

Updated rotate3Di.js to v0.9.2.

= 1.5.1 = 

Added a missing translation.

= 1.5 = 

Added the ability to create smart groups.

= 1.4.9.1 = 

Fixed returned message from find and replace.

= 1.4.9 = 

Added a find and replace.

= 1.4.8.6 = 

Fixed bugs.

= 1.4.8.5 = 

Fixed a translation string, and minor adjusments.

= 1.4.8.4 = 

Fixed bugs, added Unlock variables.

= 1.4.8.3 = 

Fixed PHP cookie 

= 1.4.8.2 = 

Revised the cookie options.

= 1.4.8.1 = 

Fixed JScookie: per hour, per day etc...

= 1.4.8 = 

Added element type tag, and added cookie options.

= 1.4.7 = 

Mega update.

= 1.4.6.1 = 

Fixed z-index of textareas.

= 1.4.6 = 

Major update. 

= 1.4.5.3 = 

Available events only, for binding lists.

= 1.4.5.2 = 

Bug fixes.

= 1.4.5.1 = 

Added a Save & Stay option.

= 1.4.5 = 

Added new features, and bug fixes.

= 1.4.4.7 = 

Fixed default values in the list.

= 1.4.4.6 = 

Current object for event animations, and new installation procedure.

= 1.4.4.5 = 

Fixed new names inside the right panel.

= 1.4.4.4 = 

Added Support for multiple element names.

= 1.4.4.3 = 

Fixed a compatibility issue.

= 1.4.4.2 = 

Various minor adjustments.

= 1.4.4.1 = 

Fixed a string replacement within Twiz functions.

= 1.4.4 = 

Added Shortcode to output choices.

= 1.4.3 =

Major update. 

= 1.4.2.1 = 

Removed auto-draft items from lists.

= 1.4.2 = 

Added unpublished pages & articles to lists.

= 1.4.1 = 

Added preloading of twiz-loading.gif.

= 1.4 =

Optimizations.

= 1.3.9.9 = 

Fixed event bug.

= 1.3.9.8 = 

Added features.

= 1.3.9.7 = 

Turned some images into css background.

= 1.3.9.6 = 

Fixes and stabilization.

= 1.3.9.5 = 

Fixes, and plugin skins.

= 1.3.9.4 = 

Added the ability to create custom menu.

= 1.3.9.3 = 

Output optimizations, added a new parameter to the function repeat.

= 1.3.9.2 = 

Fixed `Home` activation button display.

= 1.3.9.1 = 

Added Admin option.

= 1.3.9 =

Added features, and fixed bug.

= 1.3.8.9 =

Added a vertical alignment to the right panel.

= 1.3.8.8 =

Layout fixes.

= 1.3.8.7 =

Bug fixes.

= 1.3.8.6 =

Added a vertical menu. Bug fixes.

= 1.3.8.5 =

Added many features. Layout fixes.

= 1.3.8.4 =

Fixes.

= 1.3.8.3 =

Added `Output code compression` option.

= 1.3.8.2 =

Rewritten the output code.

= 1.3.8.1 =

Fixes.

= 1.3.8 =

Added Output hook choices, and `empty` for Top and Left.

= 1.3.7.9 =

Added px, %, em, in.

= 1.3.7.8 =

Bug fixes.

= 1.3.7.7 =

Bug fixes.

= 1.3.7.6 =

Hotfix.

= 1.3.7.5 =

Layout fixes. Minor fixes.

= 1.3.7.4 =

Simplified right panel, and the ability to easily call animations.

= 1.3.7.3 =

Added the ability to choose a post when creating menus.

= 1.3.7.2 =

Fixed conflict with upload media manager in post page.

= 1.3.7.1 =

Fixed wp-minify incompatibility(new).

= 1.3.7 =

Major update.

= 1.3.6.9 =

Optimized output code.

= 1.3.6.8 =

Correction, only JS can be switched after or before.

= 1.3.6.7 =

Output small configs before the JavaScript.

= 1.3.6.6 =

The editing panel is simplified and more flexible.

= 1.3.6.5 =

Fixed navigation import menu, and default action label.

= 1.3.6.4 =

Added support for $(this). inside the textbox `JavaScript Before`.

= 1.3.6.3 =

All menu buttons are now visible when editing.

= 1.3.6.2 =

Added the layout of the admin button. Minor fixes.

= 1.3.6.1 =

ID, NAME and CLASS are supported.

= 1.3.6 =

Fixed Everywhere, Everywhere.

= 1.3.5.9 =

New features. (Everywhere / All Categories / All Pages)

= 1.3.5.8 =

Major update and fixes.

= 1.3.5.7 = 

Added Trigger animation by event. And fixes.

= 1.3.5.6 = 

Added `Copy` action, list ordered by `Delay` and `Element Id`.

= 1.3.5.5 = 

.twz and .xml are supported for import. 

= 1.3.5.4 = 

The Library files are now enqueued in the configured order.

= 1.3.5.3 = 

Major update, new features.

= 1.3.5.2 = 

Added Repeat function.

= 1.3.5.1 =

Major and minor fixes...

= 1.3.5 = 

Fixed delete section menu.

= 1.3.4.9 = 

Fixed notice and warning messages.

= 1.3.4.8 =

Added Replay function.

= 1.3.4.7 =

Bug fixes.

= 1.3.4.6 =
 
Added a JavaScript File Manager. Bug fixes. Adjustments.

= 1.3.4.5 =
 
Fixed table reinitialization.

= 1.3.4.4 =

Added a delete section button. Fixed export multilingual filename.

= 1.3.4.3 =
 
Major fixes.

= 1.3.4.2 =

Fully compatible with IE9, Minor Fixes.

= 1.3.4.1 =

Minor adjustments.

= 1.3.4 =

Fixed display code.

= 1.3.3.9 =

Optimized code. New constants. Fixed Import-Export.

= 1.3.3.8 =

Modified fileuploader stylesheet.

= 1.3.3.7 =

Enqueued fileuploader stylesheet.

= 1.3.3.6 =

Added Import file feature. Major changes.

= 1.3.3.5 =

Added compatibility with IE8...

= 1.3.3.4 =

Fixed the visibility of the add section menu.

= 1.3.3.3 =

Fixed export file.

= 1.3.3.2 =

Added a new feature to export a list. 

= 1.3.3.1 =

Added Spanish translation. Minor changes.

= 1.3.3 =

Fixed bug (Internet Explorer).

= 1.3.2.9 =

Added dynamic arrows to the editing panel.

= 1.3.2.8 =

Modified the size of the arrows.

= 1.3.2.7 =

Fixed hidden datapreview panel. Added arrows.

= 1.3.2.6 =

Fixed unresolved sections.

= 1.3.2.5 =

Added validation, and modified Installation.

= 1.3.2.4 =

Fixed Installation.

= 1.3.2.3 =

This is the update we've all been waiting for.

= 1.3.2.2 =

Minor fixes and adjustments.

= 1.3.2.1 =

Fixed notice and warning messages.

= 1.3.2 =

Fixed Call to undefined function file_get_html(). 

= 1.3.1.9 =

Fixed Missing arguments.

= 1.3.1.8 =

Added Ajax editable 'Duration' column. Layout adjustments, improvements.

= 1.3.1.7 =

Fixed Enlarged layout.

= 1.3.1.6 =

Enlarged layout.

= 1.3.1.5 =

Improved security using Nonces.

= 1.3.1.4 =

Fixed the validation for the delay textbox.(after cancel), minor fixes.

= 1.3.1.3 =

Fixed the display of the right panel after addnew. 

= 1.3.1.2 =

Disabled submit button on form submit. Minor fixes.

= 1.3.1.1 =

Added validation for the delay textbox.

= 1.3.1 =

Added Ajax editable 'Delay' column. + Major fixes.

= 1.3.0 =

Major fixes. More options. Critical update.

= 1.2.9 =

Optimized stylesheet. (twiz-style.css)

= 1.2.8 =

Enqueued stylesheet. (twiz-style.css)

= 1.2.7 =

Added a full advanced caching feature for the DataPreview results.

= 1.2.6 =

New DataPreview Panel, larger layout.

= 1.2.5 =

Major fixes, and adjustments.

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
* Français