=== Tabbed Posts ===
Contributors: KlmnWeb
Donate link: https://example.com/
Tags: tabbed, blog, tabbed-posts, responsive, tab posts
Requires at least: 5.9.5
Tested up to: 6.2
Stable tag: 1.0
Requires PHP: 7.4.33
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display your recent blog posts in a Tabbed Format with Tabbed Posts plugin.

== Description ==

Tabbed Posts is a very lightweight plugin, which uses shortcode to display recent blog posts in a responsive Tabbed Format.

The Tabbed Posts plugin comes with two navbar styles - **Advanced** and **Basic**. The Advanced navbar style uses a few additional styling and js files, while the Basic navbar style removes them.

Multiple instances of the shortcode can be used in a single page. However, both types of the navbar styles should not be used in a single page since the Basic navbar styles removes the redundant js and css files.

View a demo [here](https://tabbed-posts.klmnweb.com/ "Tabbed Post Shortcode").

The tabs are the Post Category Names which you can add by including Category IDs inside the shortcode separated by commas.


**Default Usage**

	[tabbed_posts]

**Custom Usage**
	[tabbed_posts date_format="Y-m-d" cats="1,2,3,4,5,6" show_excerpt="yes" view_all="yes"]

**Shortcode Attributes**

* _cats_ = Get the Post IDS and place them one by one seperated by comma. Default is _all categories_ which is not recommended.
* _show_excerpt_ = This will display a short excerpt of posts of 20 words beneath the post thumbnails. The default is _none_.
* _view_all_ = This creates the archive link for the selected post category. By default, this is disabled.
* For full list of parameters, please visit [this page](https://tabbed-posts.klmnweb.com/shortcode-usage/).

There are currently two Navbar styles. The default is **Advanced**. This calls a few jQuery and CSS files necessary for the navbar to execute.

If you use **Basic** style, the navbar will have a horizontal scrollbar. This will also remove all the redundant jQuery and CSS files that are called with the **Advanced** navbar option.

== Frequently Asked Questions ==

= Can we use shortcode more than once in a page? =

Yes, the shortcode can be used more than once in a page. However, make sure both the **Advanced** and **Basic** styles navbars are not used together. This will break the layout.

= Why the post excerpt does not show up under each blog post?  =

The plugin is designed to show only the excerpt on the first post to maintain the layout. If you need such a feature, feel free to reach us.

= Does the plugin look good on mobile devices?  =
Yes, it does. However, care has been taken to consider all the devices. Still, if any issues are found, please feel free to reach us. We will fix them in our next versions.

== Screenshots ==

1. Shortcode panel
2. Front-end view
3. Mobile layout.

== Changelog ==

= 1.0 =
* Initial release


`<?php code(); ?>`