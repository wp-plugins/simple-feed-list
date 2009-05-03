=== Simple Feed List ===
Contributors: dartiss
Donate link: http://tinyurl.com/bdc4uu
Tags: feed, list, sidebar, RSS, news, Apple, movie, FileHippo
Requires at least: 2.0.0
Tested up to: 2.7.1
Stable tag: 1.1

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

= What's changed in version 1.1? =

The ability to specify a maximum length for the description.

= Do you have any example RSS feeds to use? =

Yes.

http://images.apple.com/trailers/home/rss/newtrailers.rss - the latest movie trailers from the Apple website
http://feeds2.feedburner.com/filehippo - latest software updates from FileHippo.com
http://feeds2.feedburner.com/quotationspage/qotd - quote of the day

= How can I get help or request possible changes =

Feel free to report any problems, or suggestions for enhancements, to me either via my contact form or by the plugins homepage at http://www.artiss.co.uk/simple-feed-list