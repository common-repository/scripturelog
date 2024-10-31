=== ScriptureLog ===
Contributors: J. Max Wilson, Daniel Bartholomew
Donate link: http://scripturelog.com
Tags: Scripture, LDS, Mormon, Religion, Study, Christianity
Requires at least: 2.7
Tested up to: 2.9.2
Stable tag: 1.1.1

ScriptureLog - an Open Source plugin that turns Wordpress into a platform for collaborative LDS scripture study 

== Description ==

ScriptureLog is an Open Source plugin for the popular WordPress blogging platform that allows you to take advantage of Open Source technologies and standards by turning Wordpress into a platform for collaborative scripture study.

ScriptureLog installs volumes of scripture into Wordpress as hierarchical, inter-linking pages of books, chapters, and verses.  Once the pages are installed, you can use the built-in features of Wordpress to collaborate with friends or family, a seminary or institute class, or members of your congregation or mission, to coordinate scripture reading, take notes, and discuss the gospel.

== Installation ==

1. Upload `scripturelog` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the ScriptureLog Sidebar Widget to your Sidebar
4. Install the Scripture Volume from the ScriptureLog manager in the menu

== Changelog ==

= 1.1.1 =
* Added indexes to scripturelog fields to improve database performance.  If needed, the required database changes will be made when the plugin is activated.
* Optimized SQL query parameters to improve database performance
* Fixed bug where links to scripturelog pages would not be found when using rewritten friendly permalinks and using a static page for the posts page.

= 1.1.0 =
* Added the Old Testament
* Fixed various typos in the Book fo Mormon Outline Headers and added additional information to exceprts from Isaiah

= 1.0.0 =
* This is the initial release of ScriptureLog.  It features only the Book of Mormon.  Other volumes of scripture will be added in future versions

== Frequently Asked Questions ==

= How does ScriptureLog Work? =

Websites built on Wordpress have two types of content: Posts and Pages.  Posts are listed in reverse chronological order in your blog.  Pages, on the other hand, contain additional information and are often displayed as links across the top of the website or in the sidebar.  The ScriptureLog Plugin inserts thousands upon thousands of inter-linking pages directly into the database that Wordpress uses, and then modifies the default behavior of Wordpress to exclude these pages from its normal list of pages and instead present them in a custom sidebar widget.

== Appropriate Hosting ==

Because the plugin inserts thousands of pages, ScriptureLog is not suitable for sites hosted on Wordpress.com, which limits the number of pages an individual account can have.  It is also not recommended for established blogs with a large number of existing posts.   It would be best used on a fresh, Self-Hosted install of Wordpress.
