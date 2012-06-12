=== AVChat Video Chat  Plugin for WordPress ===
Tags: chat, video chat, flash, flash video chat, flash chat, videochat, streaming, red5, fms, wowza
Requires at least: 3.0.0
Tested up to: 3.3.2
Stable tag: 1.0

The AVChat Video Chat Plugin for WordPress handles the integration between AVChat and your WordPress web site.

== Description ==

The AVChat Video Chat Plugin for WordPress handles the integration between AVChat and your WordPress web site.

AVChat is a group video chat script that can be hosted by you and installed on your web site.

If you have purchased AVChat and your website is based on WordPress this Plugin will take care of:

* username integration (users logged in the Word Press web site will not have to login again in the video chat )
* compatible with buddypress: buddypress avatars are automatically added to users profiles and users profiles can be accessed directly from AVChat
* setting up different permissions/limits/features for each user role in your WorPress web site
* compatible with SEO friendly URL�s in WordPress
* you can choose the video chat location: embedded or popup
* having access to the most important settings directly from the WrodPress admin area


The AVChat3 Flash Video Chat plugin for WordPress is licensed under GPL v2. See the full text of the GPL v2 license in license.txt.

== Installation ==

1. Connect to your web site hosting account using FTP
2. Upload the avchat3 folder (from avchat3_wordpress_plugins.zip) corresponding to your WordPress version to your /wp-content/plugins folder
3. Upload the contents of the folder named Files to upload to your web site from the AVChat 3.0.zip archive to /wp-content/plugins/avchat3/
4. Chmod the /wp-content/plugins/avchat3/uploadedFiles folder to 777 (otherwise the upload function might not work)
5. Create a new folder tokens (/wp-content/plugins/avchat3/tokens) and CHMOD it to 777 (otherwise we might have token generation issues later on)
6.  Activate the plugin from the admin area
7.  Enter the rtmp connectionstring in Settings -> AVChat 3 . It should look like this:
	rtmp://myFMSserver.com/avchat30/_definst_
    where myFMSserver.com is the domain name or ip of the server where your media server is installed.
8.  Create a new page with this content: [chat]
9.  Login the video chat, you will be asked for the license key (it's in your client/trial area on avchathq.com).
10. That's it you're logged in the AVChat video chat.


For detailed information, see http://avchat.net/support/documentation/wordpress

== Frequently Asked Questions ==

= What do I need to use this plugin ? =

A WordPress web site and AVChat Software

= Do I get support ? =

Support options for clients:
* Personalized support by email and updates for 1 year after the purchase.
* Support forum: http://avchat.net/forum

Support options for trial users and other users:
* Support forum: http://avchat.net/forum
