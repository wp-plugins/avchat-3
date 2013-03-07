<?php
if(session_id() == ""){
		session_start();
	}
/**
 * @package AVChat Video Chat Plugin for WordPress
 * @author  AVChat Software
 * @version 1.3.2
 */
/*
Plugin Name: AVChat Video Chat Plugin for WordPress
Plugin URI: http://avchat.net/integrations/wordpress
Description: This plugin integrates AVChat 3 into any WordPress website.
Author: AVChat Software
Version: 1.3.2
Author URI: http://avchat.net/



Copyright (C) 2009-2012 AVChat Software, avchat.net

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
			  can_watch_other_people_streams tinyint(1) NOT NULL,
			  can_join_other_rooms tinyint(1) NOT NULL,
			  show_users_online_stay tinyint(1) NOT NULL,
			  view_who_is_watching_me tinyint(1) NOT NULL,
			  can_block_other_users tinyint(1) NOT NULL,
			  can_buzz tinyint(1) NOT NULL,
			  can_stop_viewer tinyint(1) NOT NULL,
			  can_ignore_pm tinyint(1) NOT NULL,
			  typing_enabled tinyint(1) NOT NULL,
			  free_video_time mediumint(5) NOT NULL,
			  drop_in_room varchar(5) NOT NULL,
			  max_streams mediumint(2) NOT NULL,
			  max_rooms mediumint(2) NOT NULL,
			  username_prefix varchar(10) NOT NULL,
			  UNIQUE KEY id (id)
			);
				CREATE TABLE " . $table2_name . " (
				connection_string TEXT NOT NULL,
				invite_link TEXT NOT NULL,
				disconnect_link TEXT NOT NULL,
				login_page_url TEXT NOT NULL,
				register_page_url TEXT NOT NULL,
				text_char_limit mediumint(2) NOT NULL,
				history_lenght mediumint(3) NOT NULL,
				hide_left_side ENUM ('yes', 'no') NOT NULL,
				p2t_default ENUM ('yes', 'no') NOT NULL,
				flip_tab_menu ENUM ('top', 'bottom') NOT NULL,
				display_mode ENUM ('embed', 'popup') NOT NULL,
				allow_facebook_login ENUM ('yes', 'no') NOT NULL,
				FB_appId TEXT NOT NULL
				);
			";		
   		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      	dbDelta($sql);
      	
      	foreach($wp_roles->roles as $role => $details){
			$user_roles[$role] = $details["name"];
		}
		
		unset($user_roles['administrator']);
		
		$user_roles['visitors'] = "Visitors";
	    
		foreach($user_roles as $key=>$value){
			$insert = "INSERT INTO " . $table_name .
	            	  " (user_role, can_access_chat, can_access_admin_chat, can_publish_audio_video, can_stream_private, can_send_files_to_rooms, can_send_files_to_users, can_pm, can_create_rooms, can_watch_other_people_streams, can_join_other_rooms, show_users_online_stay, view_who_is_watching_me, can_block_other_users, can_buzz, can_stop_viewer, can_ignore_pm, typing_enabled, free_video_time, drop_in_room, max_streams, max_rooms) " .
	                  "VALUES ('" . $key . "','1','0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '3600', '', '4', '4')";
			 $results = $wpdb->query( $insert );
		}
		
		$insert = "INSERT INTO " . $table2_name .
	            	  " (connection_string, invite_link, disconnect_link, login_page_url, register_page_url, text_char_limit, history_lenght, hide_left_side, p2t_default, flip_tab_menu, display_mode, allow_facebook_login, FB_appId) " .
	                  "VALUES ('rtmp://','','/','/', '/', '200', '20', 'no', 'yes', 'top', 'embed', 'yes', '')";
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

function get_avchat3_visitor_permissions(){
	global $wpdb;
	
	$query = "SELECT * FROM ".$wpdb->prefix . "avchat3_permissions"." WHERE user_role = 'visitors'";
	$user_permissions = $wpdb->get_results($query);
	
	unset($user_permissions[0]->id);
	unset($user_permissions[0]->user_role);
	
	foreach($user_permissions[0] as $key=>$value){
		$user_info[$key] = $value;
	}
	$user_info['user_role'] = 'visitor';
	
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
		$user_info = get_avchat3_visitor_permissions();
	}else{
		$_SESSION['user_logged_in'] = true;
	}
	
	foreach($user_info as $key=>$val){
			$_SESSION[$key] = $val;
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
		
		//$_SESSION['raluca'] = 'administrator';
		
		$display_mode = get_avchat3_general_setting('display_mode')->display_mode;
		
		
		//Check if buddypress is installed
		if(in_array( 'buddypress/bp-loader.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )){
			
			
			//Get buddypress member avatar
			$buddy_details['avatar'] = bp_core_fetch_avatar( array( 'item_id' => $user_info['user_id'], 'type' => 'thumb', 'alt' => '', 'css_id' => '', 'class' => 'avatar', 'width' => '40', 'height' => '40', 'email' => $user_info['user_email'], 'html' => 'false') ) ;
			$buddy_details['is_buddy'] = 1;
			
			
			avchat3_set_avchat3_buddy_details_on_session($buddy_details);	
		}	
		
		if($_SESSION['FB_appId'] != "") {
			$FB_appId = $_SESSION['FB_appId'];
		}
		else {
			$FB_appId = "447812388589757";
		}
		
			
		if($display_mode == 'embed'){
		$embed = '
			<input type="hidden" name="FB_appId" id="FB_appId" value="'.$FB_appId.'" />
			<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/avchat-3/facebook_integration.js"></script>
			<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/avchat-3/swfobject.js"></script>
			<script type="text/javascript">
				var flashvars = {
					lstext : "Loading Settings...",
					sscode : "php",
					userId : ""
				};
				var params = {
					quality : "high",
					bgcolor : "#272727",
					play : "true",
					loop : "false",
					allowFullScreen : "true",
					base : "'.get_bloginfo("url").'/wp-content/plugins/avchat-3/"
				};
				var attributes = {
					name : "index_embed",
					id :   "index_embed",
					align : "middle"
				};
			</script>
			<script type="text/javascript">
			swfobject.embedSWF("'.get_bloginfo('url').'/wp-content/plugins/avchat-3/'.$movie_param.'", "myContent", "100%", "600", "10.3.0", "", flashvars, params, attributes);</script>
			<!-- This script changes the window title when a user receives a new message in chat -->
			<script type="text/javascript">
				function onNewMessageReceived(message){
					document.title = "("+message+") AVChat 3.0 User Interface";
				}
				function onNewMessageRead(){
					document.title = "AVChat 3.0 User Interface";
				}
			</script>
				  
			<div id="myContent">
				<div id="av_message" style="color:#ff0000"> </div>
			</div>
			<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/avchat-3/find_player.js"></script>';
		}else{
			$chat_window = '&#39;'.get_bloginfo('url').'/wp-content/plugins/avchat-3/index_popup.php?movie_param='.$movie_param.'&#39;';
  			$page_content = '<a style="display:block;padding:5px 3px;width:200px;margin:5px 0;text-align:center;background:#f3f3f3;border:1px solid #ccc" href="#" onclick="javascript:window.open('.$chat_window.')">Open chat in popup</a>';
			
			$embed = $page_content;
		}
		return str_replace('[chat]', $embed, $content);
}

function avchat3_admin_config(){
	add_options_page('AVChat3 Permissions & Config', 'AVChat3',  'manage_options', 'avchat-3/avchat3-settings.php');
}

register_activation_hook(__FILE__,'avchat3_install');
add_action('wp_logout', 'avchat3_clear_session');
add_action('admin_menu', 'avchat3_admin_config');
add_filter('the_content', 'avchat3_get_user_chat', 7);

?>
