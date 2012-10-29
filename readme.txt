=== Video Chat  Plugin ===
Tags: chat, video chat, chatroom, flash, flash video chat, flash chat, videochat, streaming, image, images, red5, fms, wowza, avchat
Requires at least: 3.0.0
Tested up to: 3.4
Stable tag: 1.1
Version : 1.1
Contributors: stefan.avchat
License: GPLv2 or later

The AVChat Video Chat Plugin for WordPress is designed to increase members engagement and web site revenues with a stunning video chat solution.

== Description ==

The <a href='http://avchat.net/integrations/wordpress' title='AVChat plugin for Wordpress'>Video Chat Plugin for WordPress</a> handles the integration between AVChat and your WordPress web site.

<a href='http://avchat.net/features' title='AVChat software'>AVChat</a> is a group video chat script that can be hosted by you and installed on your web site. You can purchase it from <a href='http://avchat.net/buy-now' title='Buy AVChat'>here</a>: 

If you have purchased AVChat and your website is based on WordPress this plugin will take care of:

* username integration (users logged in the Word Press web site will not have to login again in the video chat )
* compatible with buddypress: buddypress avatars are automatically added to users profiles and users profiles can be accessed directly from AVChat
* compatible with SEO friendly URL's in WordPress
* setting up different permissions/limits/features for each user role in your WorPress web site (in the PRO verision)
* you can choose the video chat location: embedded or popup (PRO version)
* having access to the most important settings directly from the WrodPress admin area (PRO version)

The PRO version can be bought from <a href='http://avchat.net/integrations/wordpress' title='AVChat plugin for Wordpress'>our website</a> and costs 49$.
With the PRO version you also get 1 year of full support and updates , and free installation and of course, our gratitude for helping us sustain this plugin.

The AVChat Video Chat plugin for WordPress is licensed under GPL v2. See the full text of the GPL v2 license in license.txt.

== Installation ==
For this plugin to work, ***you first need to buy or get a trial of the AVChat Video Chat software*** from <a href='http://avchat.net/buy-now' title='Buy AVChat'>here</a>. This plugin only takes care of the integration with Wordpress of the AVChat software, but does not contain the actual video chat.


After you obtain the AVChat archive from our site, follow these steps :

1. Connect to your web site hosting account using FTP
2. Upload the avchat3 folder (from avchat-3.zip) to your ***/wp-content/plugins*** folder
3. Upload the contents of the folder named Files to upload to your web site from the AVChat 3.0.zip archive to ***/wp-content/plugins/avchat3/***
4. Chmod the ***/wp-content/plugins/avchat3/uploadedFiles*** folder to 777 (otherwise the upload function might not work)
5. Create a new folder tokens (***/wp-content/plugins/avchat3/tokens***) and CHMOD it to 777 (otherwise we might have token generation issues later on)
6.  Activate the plugin from the admin area
7.  Enter the rtmp connectionstring in Settings -> AVChat 3 (in the PRO version) . It should look like this:
	***rtmp://myFMSserver.com/avchat30/_definst_***
    where myFMSserver.com is the domain name or ip of the server where your media server is installed.
	Otherwise, got to the avc_settings.php file and look for the "connectionstring" property. There you should put the connection to your media server.
8.  Create a new page with this content: ***[chat]***
9.  Login the video chat, you will be asked for the license key (it's in your client/trial area on avchathq.com).
10. That's it you're logged in the AVChat video chat.

Get ***FREE*** installation with the <a href='http://avchat.net/integrations/wordpress' title='AVChat plugin for Wordpress'> PRO version </a> of the plugin, plus other much welcome additions.


For further information, see <a href='http://avchat.net/support/documentation/wordpress' title='Buy AVChat'>our detailed documentation</a>

== Frequently Asked Questions ==

= What do I need to use this plugin ? =

A WordPress web site and AVChat 3 ( http://avchat.net/ )

= Do I get support ? =

Yes, of course. If you've noticed a bug or a problem with the plugin, please report it to support@avchathq.com. 

Please take into consideration that ***PRO*** users have personalized support, but we will take into consideration all the emails. 

We also have a support forum: http://avchat.net/forum that is very useful in obtaining the information you desire. 


== Screenshots ==

1. Login Screen

2. Create a new room

3. Share content among users

4. Video-chat with another person 

5. Video chat permissions for the PRO Version