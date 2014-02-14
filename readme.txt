=== Buckets ===
Contributors: matthewordie
Tags: widgets, buckets, acf, advanced custom fields, custom, field, widgets alternative, sidebar
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 3.4.1

A widgets alternative that lets you place content anywhere easily.

== Description ==

This plugin is designed as a widgets alternative. However it's uses can be expanded beyond that. It works ok on it's own, but really flys when paired with the Advanced Custom Fields plugin.

I was tired of my widgets not migrating properly. On top of not having full control over simple things. So I built this.

Now you can make a reusable piece of content. Place it right in the middle of another content area. Or even inside another bucket. Additionally you can use the Advanced Custom Fields plugin to create your own sidebars and add new fields to really customize your Buckets.

You can even create a fully modular site using sidebar areas and just throwing buckets in everywhere!

This plugin is made for developers who like to make their client's lives easier (and their own).

Documentation is available on google docs: https://docs.google.com/document/d/1fDhqmtKWTy-0oxTP8GUg7wdhv-VULNoBFkJbLvKNdzo/edit?usp=sharing

Video docs coming soon!


== Installation ==

1. Upload 'buckets' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Read the documentation to learn how to create buckets.

Read the docs for more info: https://docs.google.com/document/d/1fDhqmtKWTy-0oxTP8GUg7wdhv-VULNoBFkJbLvKNdzo/edit?usp=sharing


== Other Notes ==

You can view the documentation here: https://docs.google.com/document/d/1fDhqmtKWTy-0oxTP8GUg7wdhv-VULNoBFkJbLvKNdzo/edit?usp=sharing



== Changelog ==
= 0.2.4 =
[Fixed] - Added prefixes to functions to fix any incompatibility with other plugins.

= 0.2.3 =
[Fixed] - Buckets can be added/edited without leaving the page you're editing again. Now works with the newest ACF Relationship Field.
[Fixed] - Dashboard Icon to match the changes in Wordpress 3.8

= 0.2.2 =
[Fixed] - Various minor fixes and updates
[Added] - Contextual Help Tab that links to the Buckets Documentation on Google Docs
[Fixed] - Add/Edit on page feature is back. Went missing after the upgrade to ACF 4.0.

= 0.2.1 =
[Added] - Better display on Buckets page, now shows shortcode and pages that Buckets are on.
[Fixed] - Now loops through get_bucket function if more then one flex layout are added to a single bucket.


= 0.2 =
[Fixed] - Updated Buckets plugin to work with ACF 4.0.

= 0.1.9.3 =
[Fixed] - Removed ZeroClipboard function because of security exploit found.

= 0.1.9.2 =
[Fixed] - Error in Buckets Area field.

= 0.1.9.1 =
[Added] - Documentation

= 0.1.9 =
[Fixed] - Changed Shortcode "Copy to Clipboard" feature to just use a link. The shortcode text is now selectable (for none flash browsers).
[Added] - Bucket type displays on Bucket Area field.
[Fixed] - Improved Add and Edit functions of the Buckets Area field. No longer saves as draft.
[Fixed] - Cleaned up minor styles.

= 0.1.8 =
[Fixed] - TinyMCE shortcode was inserting incorrectly
[Fixed] - Uploaded deprecated wp_get_single_post function to get_post
[Fixed] - Adjusted Buckets Area field styles
[Fixed] - Bucket Area field output to be slightly more efficient.
[Fixed] - Added wpautop function to content output
[Added] - You can now add new Buckets from the Buckets Sidebar field! Now you can edit all your content from a single page.
[Fixed] - Fixed plugin initilization function to prevent compatibility issues.

= 0.1.7 =
[Added] - Added a TinyMCE button that will let you select a bucket and it will automatically insert the shortcode for you. Must faster for editing your content and easier on your clients.

= 0.1.6 =
[Added] - Automatically setups default fields for ACF when installed
[Fixed] - Minor Bug Fixes

= 0.1.5 =
[Added] - Buckets Area field - The beginning of an easier way for creating your "sidebars".
[Fixed] - Updated to include new ACF has_sub_field function
[Fixed] - Bug with template output
[Fixed] - Bucket icon not displaying

= 0.1.4 =
[Fixed] - Updated plugin and field to work with newest ACF changes.
[Fixed] - Minor Bug Fixes
