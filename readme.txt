﻿=== Simple Feed List ===
Contributors: dartiss
Donate link: http://artiss.co.uk/donate
Tags: feed, rss, list, sidebar, news
Requires at least: 2.0.0
Tested up to: 3.1
Stable tag: 2.3.2

Simple Feed List is a WordPress plugin that displays a list of entries from a valid RSS feed.

== Description ==

This is a very useful way to display RSS feed data in a list format - news updates, site updates, software updates, etc. All you need is the URL of the RSS feed.

To display a feed on your WordPress site you will need to either insert a call to a PHP function, where appropriate, into your theme or use a shortcode. Use the latter for displaying a list in a post/page and the former for displaying a list in the sidebar or elsewhere on the site.

**PHP Function**

`<?php simple_feed_list('listurl','paras'); ?>`

Where..

*listurl* : This is the URL of your RSS feed

*paras* : This is an optional list of parameters, each seperated by an ampersand (&)

The `paras` are as follows...

*limit* : The number of items to display from 1 to 20 (default is 5)

*desc* : This indicates whether you wish to display the description on the line below. This should be "Yes" or "No" or a numeric length. If omitted, it will assume to be "No". If a number is specified this will be used as the maximum length of the description.

*title* : This indicates the length that you wish to restrict the feed title to (in characters). By default, this will be unlimited.

*more* : By default, "More..." will be displayed at the bottom of the list with a link to the list URL. If you specify "No" then this will be omitted.
 
*nofollow* : If set as "Yes", this adds a `REL="NOFOLLOW"` tag to the links.

*target* : Specify the target of any links (default is _BLANK)

*cache* : Modify the time that the feed is cached (specify in hours - default is 1)

The plugin will then display a list of entries from the RSS as an HTML list (i.e. with `<li>` and `</li>` around each entry).

An example would be...

`<?php simple_feed_list('http://images.apple.com/trailers/home/rss/newtrailers.rss','limit=5'); ?>`

This would display a list of the latest 5 movie trailers from the Apple site. Descriptions will not be shown.

`<?php simple_feed_list('http://images.apple.com/trailers/home/rss/newtrailers.rss','limit=5&desc=200'); ?>`

This would display a list of the latest 5 movie trailers from the Apple site. Descriptions will be shown, up to a maximum length of 200 characters.

The following is an example of how it could be used, with a `function_exists` check so that it doesn't cause problems if the plugin is not active...

`<?php if (function_exists('simple_feed_list')) : ?>
<h2>Latest Apple Movie Trailers</h2>
<ul><?php simple_feed_list('http://images.apple.com/trailers/home/rss/newtrailers.rss','limit=5'); ?></ul>
<?php endif; ?>`

If you specify a `limit` of just 1, then the `<li>` tags will not be used, allowing you to embed the result in a sentence. For example, you could use a feed of quotes and use this to just display the latest quote.

**Shortcode**

A shortcode of `[feedlist]` can be used with any of the following parameters (only the first is required) - `listurl`, `limit`, `desc` , `more`, `target`, `nofollow` and `cache`. The formats of these parameters are the same as those above.

Using the Apple trailers example from above, you would write...

`[feedlist listurl="http://images.apple.com/trailers/home/rss/newtrailers.rss" limit=5]`

The results are presented as an unordered list - unlike the PHP function, this includes the `<ul>` and `</ul>` tags surrounding it. The exception, like with the PHP function, is if you request only one feed item to be output - this is written out without any list tags.

**For help with this plugin, or simply to comment or get in touch, please read the appropriate section in "Other Notes" for details. This plugin, and all support, is supplied for free, but [donations](http://artiss.co.uk/donate "Donate") are always welcome.**

== Licence ==

This WordPress plugin is licensed under the [GPLv2 (or later)](http://wordpress.org/about/gpl/ "GNU General Public License
").

== Support ==

All of my plugins are supported via [my website](http://www.artiss.co.uk "Artiss.co.uk").

Please feel free to visit the site for plugin updates and development news - either visit the site regularly, follow [my news feed](http://www.artiss.co.uk/feed "RSS News Feed") or [follow me on Twitter](http://www.twitter.com/artiss_tech "Artiss.co.uk on Twitter") (@artiss_tech).

For problems, suggestions or enhancements for this plugin, there is [a dedicated page](http://www.artiss.co.uk/simple-feed-list "Simple Feed List") and [a forum](http://www.artiss.co.uk/forum "WordPress Plugins Forum"). The dedicated page will also list any known issues and planned enhancements.

Alternatively, please [contact me directly](http://www.artiss.co.uk/contact "Contact Me"). 

**This plugin, and all support, is supplied for free, but [donations](http://artiss.co.uk/donate "Donate") are always welcome.**

== Installation ==

1. Upload the entire `simple-feed-list` folder to your `wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. There is no options screen - configuration is done in your code.

== Frequently Asked Questions ==

= Do you have any example RSS feeds to use? =

Yes.

* [The latest movie trailers from the Apple website](http://images.apple.com/trailers/home/rss/newtrailers.rss "")
* [Latest software updates from FileHippo.com](http://feeds2.feedburner.com/filehippo "")
* [Quote of the day](http://feeds2.feedburner.com/quotationspage/qotd "")

= Which version of PHP does this plugin work with? =

It has been tested and been found valid from PHP 4 upwards.

== Screenshots ==

1. A sample feed list.

== Changelog ==  
  
= 1.0 =  
* Initial release

= 1.1 =  
* Added ability to specify a maximum length for the description

= 1.2 =
* Check for CDATA within the title or description and strip out the tags if found. This resolves an issue where some feeds weren't displaying (especially those provided by FeedBurner)
* Use alternative method to file_get_contents to read content of feed - this means that allow_url_fopen does not need to be switched on in users PHP configuration. This has also improved performance of the plugin - it is now approx. 50% quicker

= 1.3 =
* Ok, that last change didn't work as well as I expected - the replacement file routine isn't compatible with PHP 4. I have therefore converted this to ANOTHER version, but one that is at least compatible with PHP 4.

= 1.4 =
* CDATA now stripped from URL address

= 2.0 =
* Complete re-write using WordPress' own RSS parsing and caching
* Added functionality to read multiple parameters seperated by an ampersand
* Added new `nofollow`, `target`, `cache` and `more` parameters

= 2.1 =
* Improved the message output when a feed can't be displayed
* Added shortcode option

= 2.2 =
* Added new parameter to restrict length of feed title

= 2.2.1 =
* Added ellipsis to end of truncated title

= 2.2.2 =
* Fixed issue where <ul> tags were appearing for single feed output on shortcodes
* Fixed issue where ampersands in URLs were encoded when using shortcodes

= 2.3 =
* Improved processing of ATOM feeds
* Ensure parameters are lower case
* Add translation to "More" text
* Modified the way that UL tags are added to shortcodes
* Write out debug information as 2 additional HTML comment lines
* Description details are only fetched if option requested
* Tidied up code

= 2.3.1 =
* When using a shortcode, the terminator for the unordered list was <ul> instead of </ul>. Now corrected. Thanks to sm4r7m0u53 for identifying that.

= 2.3.2 =
* Added a number of CLASS parameters to appropriate tags for CSS styling purposes

== Upgrade Notice ==

= 2.0 =
* More efficient feed retrieval, including caching

= 2.1 =
* Ability to use a shortcode to display a feed list

= 2.2 =
* New parameter to restrict length of feed title

= 2.2.1 =
* Shows ellipsis on end of truncated title

= 2.2.2 =
* Fixs bugs relating to shortcode use

= 2.3 =
* Various improvements and bug fixes
* New parameter to specify caching period

= 2.3.1 =
* Fixed shortcode bug

= 2.3.2 =
* Upgrade if you want improved CSS styling abilities