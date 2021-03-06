=== PressBooks for MobiApp121 Publishing ===

Contributors: PressBooks <code@pressbooks.org>, MobiApp121 <info@mobiapp121.com>
Version: 2.0.3
Tags: ebooks, publishing, webbooks
Requires at least: WordPress 3.6
Tested up to: WordPress 3.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

PressBooks for MobiApp121 Publishing is a publishing tool, customized by and for [MobiApp121 Publishing](https://www.mobiapp121.com)  based on a WordPress multisite platform and the open source PressBooks plugin. PressBooks outputs books in multiple formats, including PDF EPUB/MOBI, web, and a variety of XML flavours, using a theming/templating system, driven by CSS. For more information, visit http://pressbooks.com.

== Contact ==

You can contact us at [MobiApp121 Publishing](https://www.mobiapp121.com) or by sending an email to <info@mobiapp121.com>. We are available for hire, to install a custom version, including from the latest code and other publishing platforms for books or magazines. 

== Contributors ==

All PressBooks code is copyright Book Oven Inc. Contributors are acknowledged in the "docs/contributors.txt" file, not in source code headers.

Contributors should watch [Google Tech Talks: How Open Source Projects Survive...](http://www.youtube.com/watch?v=ZSFDm3UYkeE).

== Installation ==

IMPORTANT!

 * Do not install PressBooks on an existing WordPress blog -- create a new WordPress install instead.
 * PressBooks for MobiApp121 Publishing is very old but very stable and produces excellent outputs
 * It works with PHP 5.6.x and WordPress 3.6 only. Lower or higher versions are not supported.
 * This configuration is designed specifically to work in conjunction with several tested - current as of 6/6/2017 - plugins:
 1 [WP-Quicklatex](https://en-gb.wordpress.org/plugins/wp-quicklatex/) + WP Super Cache, important for Latex.
 2 [MCE Table Buttons](https://wordpress.org/plugins/mce-table-buttons/), for tables.
 3 [Tiny MCE Spellcheck](https://wordpress.org/plugins/tinymce-spellcheck/), for inline proofing.
 4 [Disable All WP Updates](https://wordpress.org/plugins/disable-wordpress-updates/) security against fatal plugin updates and nag prevention.
 5 Install reliable Malware/Firewall/Virus protection, especially with this older install.


 VERY IMPORTANT! 

This version contains custom links,icons and urls THROUGH-OUT, specific to MobiApp121 Publishing. You will need to look closely at all headers and footers, assets in each theme, export and particularly pb-admin-laf.php, in the admin folder, to customize your own. Do NOT change plugin references to 'pressbooks', only text custom text and urls. We probably should have built child themes for every theme, which is what we're doing with our new platform and maybe one way this version could be useful to you. You are probably best advised with so much work, for what is after-all very old software, to use the PressBooks up to date plugins - You have been warned!

*Part 1, WordPress generic:*

 1. Install WordPress using the [Famous 5-Minute Install](http://codex.wordpress.org/Installing_WordPress).

 2. [Create a Network](http://codex.wordpress.org/Create_A_Network) of WordPress sites, i.e.:

 3. Edit the wp-config.php file and add the following:

    `define('WP_ALLOW_MULTISITE', true);`

 4. Login to the WordPress admin area. Navigate to Tools → Network Setup, click Install.

 5. Complete the steps printed on the screen (i.e. edit your `wp-config.php` and `.htaccess files` with the information provided.)

*Part 2, PressBooks for MobiApp121 Publishing specific:*

 1. Copy/move PressBooks plugin files to: __PATH_TO_YOUR_SITE__/wp-content/plugins/pressbooks/*.

 2. Log out, log in, navigate to: My Sites → Network Admin → Dashboard.

 3. Navigate to: Plugins → Installed Plugins.

 4. Network Enable "PressBooks."

 5. Navigate to: Themes → Installed Themes.

 6. Network Enable "Luther", "Clarke", "Donham", "Fitzgerald", "Austen", "Pressbooks Publisher One", and any other
    Pressbooks theme you want to use. *IMPORTANT* If any theme doesn't appear, we advise copying ALL themes to the generic Wordpress 	themes folder and activating as normal. Do not delete them from the PressBooks plugin.

 7. Navigate to: Settings → Network Settings.

 8. Pick the most appropriate Registration Setting:
    + User accounts may be registered. (do not use this setting, since it will not allow users to create new sites/books)
    + Logged in users may register new sites. (if you are a publisher using PressBooks as a production tool, this is the best setting: it allows network administrators to add new users, who can then create books/sites. However, registration is not available to the public.)
    + Both sites and user accounts can be registered. (use this setting if you intend on offering a publishing-platform open to the public, such as PressBooks.com)

 9. Navigate to: My Books → __YOUR_SITE__ → Dashboard

 10. Activate "PressBooks Publisher One"

 11. Navigate to: My Books → Network Admin → Sites

 12. Add a new site (this will be your first book).

 13. Navigate to: My Books → __YOUR_FIRST_BOOK__

 14. Navigate to: Book Information. Make sure to fill out Title, Author and Publication Date.

 15. Navigate to: Text → Organize. Make sure some content is selected for export.

*Part 3, PressBooks dependencies:*

 * For PDF export you need to install [Prince](http://pressbooks.com/prince) (note: this is not free software).
 * For MOBI export you need to install [KindleGen](http://www.amazon.com/gp/feature.html?docId=1000765211).
 * For EPUB validation you need to install [EpubCheck](http://code.google.com/p/epubcheck/).
 * Form XML validation you need to install [xmllint](http://xmlsoft.org/xmllint.html).

Once installed, define the following wp-config.php variables. The defaults are:

	define( 'PB_PRINCE_COMMAND', '/usr/bin/prince' );
	define( 'PB_KINDLEGEN_COMMAND', '/opt/kindlegen/kindlegen' );
	define( 'PB_EPUBCHECK_COMMAND', '/usr/bin/java -jar /opt/epubcheck/epubcheck.jar' );
	define( 'PB_XMLLINT_COMMAND', '/usr/bin/xmllint' );


Example config files for a dev site hosted at /home/mobiapp121/webapps/mobiapp121/publishing
### wp-config.php file [snippet]: ###

	/**
	 * For developers: WordPress debugging mode.
	 *
	 * Change this to true to enable the display of notices during development.
	 * It is strongly recommended that plugin and theme developers use WP_DEBUG
	 * in their development environments.
	 */
	define('WP_DEBUG', true);
	define('WP_DEBUG_LOG', true);

	/**
	 * Multi-site support, Part 1
	 */
	define('WP_ALLOW_MULTISITE', true);

	/**
	 * Multi-site support, Part 2
	 */
	define('WP_ALLOW_MULTISITE', true);

	define('MULTISITE', true);
	define('SUBDOMAIN_INSTALL', false);
	define('DOMAIN_CURRENT_SITE', 'mobiapp121.com');
	define('PATH_CURRENT_SITE', '/publishing/');
	define('SITE_ID_CURRENT_SITE', 1);
	define('BLOG_ID_CURRENT_SITE', 1);

	/**
	 * PressBooks
	 */
	define( 'PB_PRINCE_COMMAND', '/home/mobiapp121/bin/prince/bin/prince' );
	define( 'PB_KINDLEGEN_COMMAND', '/home/mobiapp121/bin/kindlegen/kindlegen' );
	define( 'PB_EPUBCHECK_COMMAND', '/usr/bin/java -jar /home/mobiapp121/bin/epubcheck/epubcheck-3.0.1.jar' );
	define( 'PB_XMLLINT_COMMAND', '/usr/bin/xmllint' );

	define('WP_MEMORY_LIMIT', '128M');

	/**
	 * Optional definitions
	 */
	// define( 'WP_POST_REVISIONS', 5 ); // Limit post revisions: int or false
	// define( 'EMPTY_TRASH_DAYS', 1 ); // Purge trash interval
	// define( 'AUTOSAVE_INTERVAL', 60 ); // Autosave every N seconds

	/* That's all, stop editing! Happy blogging. */


### .htaccess file: ###

	RewriteEngine On
	RewriteBase /~dac514/textopress/
	RewriteRule ^index\.php$ - [L]

	# add a trailing slash to /wp-admin
	RewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]

	RewriteCond %{REQUEST_FILENAME} -f [OR]
	RewriteCond %{REQUEST_FILENAME} -d
	RewriteRule ^ - [L]
	RewriteRule  ^[_0-9a-zA-Z-]+/(wp-(content|admin|includes).*) $1 [L]
	RewriteRule  ^[_0-9a-zA-Z-]+/(.*\.php)$ $1 [L]
	RewriteRule . index.php [L]

== Frequently Asked Questions ==

TK.

== Screenshots ==

1. Your Book
2. Book Information
3. Themes
4. Theme Options
5. Export

== Upgrade Notice ==

TK.

== Changelog ==

= 2.0 =

Initial open source release of PressBooks.
