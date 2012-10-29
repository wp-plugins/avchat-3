<?php
if(session_id() == ""){ 
		session_start();
	}
/**
 * @package AVChat3 Flash Video Chat
 * @author AVChat Software
 * @version 1.1
 */
/*
Plugin Name: AVChat3 wrapped up as a plugin for wordpress
Plugin URI: http://avchat.net/
Description: This plugin integrates AVChat 3 into any wordpress blog.
Author: AVChat Software
Version: 1.1
Author URI: http://avchat.net/



Copyright (C) 2009-2010 Mihai Frentiu, avchat.net

This WordPress Plugin is distributed under the terms of the GNU General Public License.
You can redistribute it and/or modify it under the terms of the GNU General Public License 
as published by the Free Software Foundation, either version 3 of the License, or any later version.

You should have received a copy of the GNU General Public License
along with this plugin.  If not, see <http://www.gnu.org/licenses/>.
*/


function avchat3_install(){
   global $wpdb;
   global $wp_roles;

   $table_name = $wpdb->prefix . "avchat3_permissions";
   $table2_name = $wpdb->prefix . "avchat3_general_settings";
   
   
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name && $wpdb->get_var("SHOW TABLES LIKE '$table2_name'") != $table2_name) {
   		$sql = "CREATE TABLE " . $table_name . " (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  user_role varchar(50) DEFAULT '0' NOT NULL,
			  can_access_chat tinyint(1) NOT NULL,
			  can_access_admin_chat tinyint(1) NOT NULL,
			  can_publish_audio_video tinyint(1) NOT NULL,
			  can_stream_private tinyint(1) NOT NULL,
			  can_send_files_to_rooms tinyint(1) NOT NULL,
			  can_send_files_to_users tinyint(1) NOT NULL,
			  can_pm tinyint(1) NOT NULL,
			  can_create_rooms tinyint(1) NOT NULL,
			  free_video_time mediumint(5) NOT NULL,
			  drop_in_room varchar(5) NOT NULL,
			  max_streams mediumint(2) NOT NULL,
			  max_rooms mediumint(2) NOT NULL,
			  UNIQUE KEY id (id)
			);
				CREATE TABLE " . $table2_name . " (
				connection_string TEXT NOT NULL,
				invite_link TEXT NOT NULL,
				disconnect_link TEXT NOT NULL,
				login_page_url TEXT NOT NULL,
				register_page_url TEXT NOT NULL,
				text_char_limit mediumint(2) NOT NULL,
				background_image TEXT NOT NULL,
				history_lenght mediumint(3) NOT NULL,
				display_mode ENUM ('embed', 'popup') NOT NULL
				);
			";		
   		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      	dbDelta($sql);
      	
      	foreach($wp_roles->roles as $role => $details){
			$user_roles[$role] = $details["name"];
		}
		
		unset($user_roles['administrator']);
	    
		foreach($user_roles as $key=>$value){
			$insert = "INSERT INTO " . $table_name .
	            	  " (user_role, can_access_chat, can_access_admin_chat, can_publish_audio_video, can_stream_private, can_send_files_to_rooms, can_send_files_to_users, can_pm, can_create_rooms, free_video_time, drop_in_room, max_streams, max_rooms) " .
	                  "VALUES ('" . $key . "','1','0', '1', '1', '1', '1', '1', '1', '3600', 'r0', '4', '4')";
			 $results = $wpdb->query( $insert );
		}
		
		$insert = "INSERT INTO " . $table2_name .
	            	  " (connection_string, invite_link, disconnect_link, login_page_url, register_page_url, text_char_limit, background_image, history_lenght) " .
	                  "VALUES ('rtmp://','','/','/', '/', '200', 'pattern_061.gif', '20')";
		$results = $wpdb->query( $insert );
   }	
}


function avchat3_get_user_details(){
	global $current_user;
	global $wpdb;
	global $blog_id;
	get_currentuserinfo();
	
	$user_roles = array();
	$user_info = array();
	
	if(function_exists(is_site_admin)){
		$avchat3_is_on_wpmu = true;
	}else{
		$avchat3_is_on_wpmu = false;
	}

	
	if($current_user->ID == null || $current_user->ID == ""){
		$user_info['user_id'] = '0';
	}else{
		
		/*
		if($avchat3_is_on_wpmu){
			$av3_current_blog_capabilities = 'wp_'.$blog_id.'_capabilities';
		}else{
			$av3_current_blog_capabilities = 'wp_capabilities';
		}
		*/
		
		$av3_current_blog_capabilities = 'wp_capabilities';
		
		$user_roles = array_keys($current_user->$av3_current_blog_capabilities);
		
		$user_info['user_id'] = $current_user->ID;
		$user_info['user_login'] = $current_user->user_login;
		$user_info['user_display_name'] = $current_user->display_name;
		$user_info['user_level_id'] = $current_user->user_level;
		$user_info['email'] = $current_user->user_email;
		$user_info['user_role'] = $user_roles[0];
		
		if($user_info['user_role'] != "administrator"){
			$query = "SELECT * FROM ".$wpdb->prefix . "avchat3_permissions"." WHERE user_role = '".$user_info['user_role']."'";
			$user_permissions = $wpdb->get_results($query);
			
			unset($user_permissions[0]->id);
			unset($user_permissions[0]->user_role);
			
			foreach($user_permissions[0] as $key=>$value){
				$user_info[$key] = $value;
			}
		}
	}
	
	return $user_info;
	
}

function get_avchat3_general_settings(){
	global $wpdb;
	
	$query = "SELECT * FROM ".$wpdb->prefix . "avchat3_general_settings";
	$general_settings = $wpdb->get_results($query);
	
	return $general_settings;
}

function get_avchat3_general_setting($general_av_setting){
	global $wpdb;
	
	$query = "SELECT ".$general_av_setting." FROM ".$wpdb->prefix . "avchat3_general_settings";
	$result = $wpdb->get_results($query);
	
	
	return $result[0];
}

function avchat3_set_avchat3_general_settings_on_session(){
	if(session_id() == ""){
		session_start();
	}
	
	$general_settings = get_avchat3_general_settings();
	
	foreach($general_settings[0] as $key=>$value){
		$_SESSION[$key] = $value;
	}
	
}

function avchat3_set_avchat3_buddy_details_on_session($buddy_details){
	if(session_id() == ""){
		session_start();
	}
	
	
	foreach($buddy_details as $key=>$value){
		$_SESSION[$key] = $value;
	}
}

function avchat3_set_user_details_on_session($user_info){
	if(session_id() == ""){
		session_start();
	}
	
	if($user_info['user_id'] == "0"){
		$_SESSION = array();
		$_SESSION['user_logged_in'] = false;
	}else{
		$_SESSION['user_logged_in'] = true;
		foreach($user_info as $key=>$val){
			$_SESSION[$key] = $val;
		}
		
		
	}
}

function avchat3_clear_session(){
	session_destroy();
}


function avchat3_get_user_chat($content){
		$user_info = avchat3_get_user_details();
		avchat3_set_user_details_on_session($user_info);
		avchat3_set_avchat3_general_settings_on_session();
		
		
		if($user_info['user_role'] == 'administrator' || $user_info['can_access_admin_chat']){
			$movie_param = 'admin.swf';
		}else{
			$movie_param = 'index.swf';
		}
		
		$display_mode = get_avchat3_general_setting('display_mode')->display_mode;
		
		
		//Check if buddypress is installed
		if(in_array( 'buddypress/bp-loader.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )){
			
			
			//Get buddypress member avatar
			$buddy_details['avatar'] = bp_core_fetch_avatar( array( 'item_id' => $user_info['user_id'], 'type' => 'thumb', 'alt' => '', 'css_id' => '', 'class' => 'avatar', 'width' => '40', 'height' => '40', 'email' => $user_info['user_email'], 'html' => 'false') ) ;
			$buddy_details['is_buddy'] = 1;
			
			
			avchat3_set_avchat3_buddy_details_on_session($buddy_details);	
		}
				
		if($display_mode == 'embed'){
		$embed = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="index_obj" width="100%" height="600" codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
				 	<param name="movie" value="'.get_bloginfo('url').'/wp-content/plugins/avchat3/'.$movie_param.'?lstext=Loading Settings...&sscode=php" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#272727" />
					<param name="allowScriptAccess" value="sameDomain" />
					<param name="allowFullScreen" value="true" />
					<param name="base" value="'.get_bloginfo('url').'/wp-content/plugins/avchat3/" />
					<embed src="'.get_bloginfo('url').'/wp-content/plugins/avchat3/'.$movie_param.'?lstext=Loading Settings...&sscode=php" quality="high" bgcolor="#272727" width="100%" height="600" name="index_embed" align="middle" play="true" loop="false" quality="high" allowFullScreen="true" allowScriptAccess="sameDomain"	type="application/x-shockwave-flash" base="'.get_bloginfo('url').'/wp-content/plugins/avchat3/"	pluginspage="http://www.adobe.com/go/getflashplayer"></embed>
				  </object>';
		}else{
			$chat_window = '&#39;'.get_bloginfo('url').'/wp-content/plugins/avchat3/'.$movie_param.'?lstext=Loading Settings...&sscode=php&#39;,&#39;FlashVideoChat&#39;, &#39;height=500, Width=900&#39;';
  			$page_content = '<a style="display:block;padding:5px 3px;width:200px;margin:5px 0;text-align:center;background:#f3f3f3;border:1px solid #ccc" href="#" onclick="javascript:window.open('.$chat_window.')">Open chat in popup</a>';
			
			$embed = $page_content;
		}
		return str_replace('[chat]', $embed, $content);
}

function avchat3_admin_config(){
	add_options_page('AVChat3 Permissions & Config', 'AVChat3',  'manage_options', 'avchat3/avchat3-settings.php');
}

register_activation_hook(__FILE__,'avchat3_install');
add_action('wp_logout', 'avchat3_clear_session');
add_action('admin_menu', 'avchat3_admin_config');
add_filter('the_content', 'avchat3_get_user_chat', 7);

?>