=== Arconix Flexslider ===
Contributors: jgardner03
Donate link: http://arcnx.co/acfsdonation
Tags: arconix, flexslider, slider, carousel, portfolio, testimonial, responsive
Requires at least: 4.0
Tested up to: 4.1
Stable tag: 1.0.1
License: GPLv2 or later

A multi-purpose responsive slider that supports custom post types and responsive themes.

== Description ==

Showcase your existing content in a rotating slider or multi-image carousel that fits the user's display. Easily customizable, supporting default and custom post types with or without images. Use a shortcode or widget to display your content.

= Features =
* Resizes based on the user's device, supporting touch navigation for phones and tablets
* Supports default and user-created post-types and image sizes for unmatched flexibility
* Traditional single-item, rotating slider or multi-image carousel
* Supports upgrade-safe CSS and javascript customizations

= Great for =
* Creating a "Recent Work" image carousel for your Portfolio
* Adding a Testimonial rotator to your sidebar or footer
* and many more...

= Documentation =
Documentation can be found [here](http://arcnx.co/afswiki)

= Demo =
Head over to the [demo site](http://demo.arconixpc.com/arconix-flexslider) to see the plugin in action

= Note =
This plugin does not create a dedicated "Slide" Custom Post Type. There are plenty of plugins in the WordPress repository for that purpose. I recommend [Meteor Slides](https://wordpress.org/plugins/meteor-slides/)

== Installation ==

You can download and install Arconix FlexSlider using the built in WordPress plugin installer. If you download the plugin manually, make sure the files are uploaded to `/wp-content/plugins/arconix-flexslider/`.

Activate Arconix-FlexSlider in the "Plugins" admin panel using the "Activate" link.

== Upgrade Notice ==

Upgrade normally via your WordPress admin -> Plugins panel.

== Frequently Asked Questions ==

= How do I use the slider?  =
* Place the Arconix - Flexslider widget in the desired widget area
* Use the shortcode `[ac-flexslider]` on a post, page or other area
* Place `<?php echo do_shortcode( "[ac-flexslider]" ); ?>` in the desired page template

= Is there any other documentation? =
* Visit the plugin's [Documentation](http://arcnx.co/afswiki) for setup and configuration information
* Tutorials on advanced plugin usage can be found at [Arconix Computers](http://arconixpc.com/tag/arconix-flexslider)

= The slider isn't styled properly =
With no 2 themes exactly alike, it's extremely difficult to style a plugin that seamlessly integrates without issue. That's why the plugin was made so flexible -- Tighter integration between your theme and the flexslider can be achieved by copying `includes/css/arconix-flexslider.css` to the root of your theme folder. The plugin will load that file instead, keeping your changes safe on upgrades.

= What script powers the slider? =
This plugin uses [Owl Carousel](http://owlgraphic.com/owlcarousel/) slider. While this plugin doesn't currently have built-in support for all the options supported by Owl Carousel, knowledgeable users will be able to implement nearly any option they'd like.

= I want to change how the slider works =
Most changes can be achieved by copying `includes/js/arconix-flexslider.js` to the root of your theme folder and then making any modifications there. That file will be loaded in place of the plugin default and will keep your changes safe on upgrade.

= What is responsive design? =
Responsive design is in essence designing a website to cater to the dimensions of the user's device. It has become very popular with the proliferation of web-enabled smartphones and tablets.

= Do I need a responsive theme to use this plugin? =
Absolutely not. The slider will conform to the dimensions provided to it

= I need help, or I found a bug =

* Check out the WordPress [support forum](http://arcnx.co/afshelp) or the [Issues section on Github](http://arcnx.co/afsissues)

= I have a great idea for your plugin! =

That's fantastic! Feel free to submit a pull request over at [Github](http://arcnx.co/afssource), or you can contact me through [Twitter](http://arcnx.co/twitter), [Facebook](http://arcnx.co/facebook) or my [Website](http://arcnx.co/1).

== Screenshots ==
1. Widget Options overview
2. Standard and user-created post-type selection box
3. Builtin and user-added image sizes
4. Slider and carousel setup
5. Text-focused (testimonials) slider display
== Changelog ==

= 1.0.1 =
* Bugfix - The widget should now fire properly
* Bugfix - Image size dropdown will now act normally
* Bugfix - `link_image` value now honored correctly

= 1.0.0 =
**This version represents a significant rewrite of the plugin including a completely different slider script. Best efforts were made to maintain as much backwards compatibility as possible, however some filter names and functions have changed, therefore it is highly recommended that existing users who've made modifications test this update on a local host or staging environment before deploying to a live environment.**

* Switched from Flexslider script to Owl Carousel
* Added an image carousel option
* Now supports content-focused post types (e.g. testimonials, etc...)
* Improved inline documentation
* Updated filter names to be more consistent
* Massive improvement to code efficiency and structure

= 0.5.3 =
* Fixed a bug in the widget update function
* Fixed a bug which was preventing category and tag filters from firing properly.

= 0.5.2 =
* Fixed an error with the Widget Title that was preventing it from saving
* Reworked some function names to minimize potential conflicts with other plugins

= 0.5.1 =
* Added a filter to the flexslider script registration, allowing the use of a different flexslider script than what's supplied in the plugin

= 0.5 =
* Added ability to call the slider from a shortcode
* Added the ability to display the chosen post-type's content (either in whole or just the excerpt)
* Added the option to display just the images without link tags (making the images un-clickable)
* Added the ability to display specific categories or tags (works with 'posts' only)

= 0.2.1 =
* fixed ie8 image scale issue

= 0.2 =
* Added additional options for image captions
* Only return posts to display if they have featured images

= 0.1 =
* Initial release