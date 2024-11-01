=== WPNeon GoCodes 2 ===
Contributors: wpneon
Plugin URL: http://wpneon.com/gocodes-wordpress-redirection-plugin/
Donate link: http://www.wpneon.com/donate/
Tags: redirection, tinyurl, 301, url shortener, url
Requires at least: 4.9
Tested up to: 4.9.8
Stable tag: 2.0
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Based on the original GoCodes plugin, "WPNeon GoCodes 2" is a revamnped URL redirection/shortener plugin. Great for podcasting and redirecting affiliate program URLs.

== Description ==

Have you ever had to give someone a shortened version of a URL? Maybe you're a podcaster, and you can't say "visit mydomain.com/2008/01/03/my-post-with-a-long-url/ for more info." 
Wouldn't it be useful if you could just say "go to mydomain.com/go/mycoolpost/ ?" Sure, you *could* use a service like tinyurl.com, but that's still not too great if you need the URL for a podcast. It's still awkward to read-out "tinyurl.com/27asr9," isn't it? It's less professional too. 
GoCodes let's you create shortcut URLs to anywhere on the internet, right from your WordPress Admin. 
The plugin is also useful for masking affiliate program URLs.

== Installation ==

#FROM YOUR WORDPRESS DASHBOARD
1. Visit 'Plugins -> Add New'.
2. Search for 'Gocodes 2'.
3. Activate Gocodes 2 from your Plugins page.

#FROM WORDPRESS.ORG
1. Download Gocodes 2 plugin.
2. Upload the 'gocodes2' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc…)
3. Activate GoCodes 2 from your Plugins page.

#FROM YOUR FTP Client
1. FTP the entire gocodes directory to your WordPress blog's plugins folder (/wp-content/plugins/).
2. Activate the plugin on the "Plugins" tab of the administration panel.

#ONCE ACTIVATED
1. Visit 'Settings -> Gocodes' and adjust the preferences.
2. Visit 'Tools -> Gocodes' and create your first link.
3. Check your link works fine and enjoy plugin usage.

== Upgrading ==
1. Deactivate plugin
2. Upload updated files
3. Reactivate plugin

== Upgrade Notice ==
*  not yet

== Origin ==

We are proud that original base of this plugin is a fork of GoCodes by redwall_hp (Not available for download and Supported currently).
We worked on the plugin before before releasing it is "WPNeon GoCodes2", stripped down some code to make it a simple & lightweight. 

== Frequently Asked Questions ==

= How do I add a redirect? =
To manage your redirects, open your WordPress admin, and go to the Manage -> GoCodes menu. From there you can remove redirects by clicking on the "Delete" button next to their entries, and you can add new ones using the form on the page. The "Key" field is where you enter the redirection string (e.g. "orange" in yourdomain.com/go/orange/). The URL field is where you enter the URL that users will be redirected to ("http://" is required). Note that the Key can only contain alphanumeric characters.

= Are the redirects search engine friendly? =
As of version 1.0, yes. 301 header redirects are used, as opposed to 302 redirects. This ensures that search engines will not rank the GoCode URL, and move on to the target URL, thus preventing duplicate content problems.

= I often create redirects to sites that I don't particularly trust. Can I automatically nofollow the redirects? =
Go to the GoCodes Settings page (Settings -> GoCodes) and tick the Nofollow checkbox. This will instruct GoCodes to send a "noindex, nofollow" message to search engines accessing a redirect.


== Screenshots ==
1. Links Page
2. General Settings
3. Edit Links

== Known Issues ==

= WP Super Cache =
There seems to be a conflict with the WP Super Cache plugin where a redirect will only work once before the cache is cleared. There are a couple of workarounds:

1. Add "index.php" on a new line in the "Rejected URLs" field of the WP Super Cache options page. yourdomain.com/ will be cached still, but /index.php won't.
2. Frederick of frederickding.com put together another method. Add this line to your .htaccess file above the WP Super Cache line: "RewriteCond %{QUERY_STRING} !.*gocode=.*" It should look something like this:

RewriteCond %{QUERY_STRING} !.*gocode=.*
RewriteRule ^(.*) /wp-content/cache/supercache/%{HTTP_HOST}/$1/index.html [L]


== Changelog ==
* Version 1.0 - Initial Release by previous author
* Version 2.0 - Upgraded and re-released by new contributer,
                UI fix for new admin design in WordPress



