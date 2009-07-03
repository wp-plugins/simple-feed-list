=== Simple Feed List ===
Contributors: dartiss
Donate link: http://tinyurl.com/bdc4uu
Tags: feed, list, sidebar, RSS, news, Apple, movie, FileHippo
Requires at least: 2.0.0
Tested up to: 2.8
Stable tag: 1.3

Simple Feed List is a WordPress plugin that displays a list of entries from a valid RSS feed.

== Description ==

This is a very useful sidebar addition, as it can be used to display all sorts of useful information in a list format - news updates, site updates, software updates, etc. All you need is the URL of the RSS feed.

To display an RSS feed on your WordPress site you will need to insert the following code, where appropriate, into your theme…

`<?php simple_feed_list('listurl','limit','descflag'); ?>`

Where..

`listurl` : This is the URL of your RSS feed

`limit` : The number of items to display from 1 to 20 (default is 5)

`descflag` : This indicates whether you wish to display the description on the line below. This should be "Yes" or "No" or a numeric length. If left blank, it will assume to be "No". If a number is specified this will be used as the maximum length of the description.

This will then display a list of entries from the RSS as an HTML list (i.e. with `<li>` and `</li>` around each entry).

An example would be...

`<?php simple_feed_list('http://images.apple.com/trailers/home/rss/newtrailers.rss','5',''); ?>`

This would display a list of the latest 5 movie trailers from the Apple site. Descriptions will not be shown.

`<?php simple_feed_list('http://images.apple.com/trailers/home/rss/newtrailers.rss','5','200'); ?>`

This would display a list of the latest 5 movie trailers from the Apple site. Descriptions will be shown, up to a maximum length of 200 characters.

The following is an example of how it could be used, with a `function_exists` check so that it doesn't cause problems if the plugin is not active...

`<?php if (function_exists('simple_feed_list')) : ?>`
`<h2>Latest Apple Movie Trailers</h2>`
`<ul><?php simple_feed_list('http://images.apple.com/trailers/home/rss/newtrailers.rss','5',''); ?></ul>`
`<?php endif; ?>`

If you specify a `limit` of just 1, then the `<li>` tags will not be used, allowing you to embed the result in a sentence. For example, you could use a feed of quotes and use this to just display the latest quote.

== Installation ==

1. Upload the entire simple-feed-list folder to your wp-content/plugins/ directory.
2. Activate the plugin through the ‘Plugins’ menu in WordPress.
3. There is no options screen - configuration is done in your code.

== Frequently Asked Questions ==

= Do you have any example RSS feeds to use? =

Yes.

http://images.apple.com/trailers/home/rss/newtrailers.rss - the latest movie trailers from the Apple website
http://feeds2.feedburner.com/filehippo - latest software updates from FileHippo.com
http://feeds2.feedburner.com/quotationspage/qotd - quote of the day

= How can I get help or request possible changes =

Feel free to report any problems, or suggestions for enhancements, to me either via my contact form or by the plugins homepage at http://www.artiss.co.uk/simple-feed-list

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