<?php
/*

Copyright (C) 2009-2010 Mihai Frentiu, avchat.net

This WordPress Plugin is distributed under the terms of the GNU General Public License.
You can redistribute it and/or modify it under the terms of the GNU General Public License 
as published by the Free Software Foundation, either version 3 of the License, or any later version.

You should have received a copy of the GNU General Public License
along with this plugin.  If not, see <http://www.gnu.org/licenses/>.
*/

	global $wp_roles;
	global $wpdb;
	
	$table_permissions = $wpdb->prefix . "avchat3_permissions";
    $table_general_settings = $wpdb->prefix . "avchat3_general_settings";
	
	$permissions = array(
					'can_access_chat' => 'Can access chat',
					'can_access_admin_chat' => 'Can access admin chat',
					'can_publish_audio_video' =>  'Can publish audio & video stream',
					'can_stream_private' => 'Can stream private',
					'can_send_files_to_rooms' => 'Can send files to rooms',
					'can_send_files_to_users' => 'Can send files to users',
					'can_pm' => 'Can send private messages',
					'can_create_rooms' => 'Can create rooms'
	);
	
	$settings = array(
					'free_video_time' => 'Free video time',
					'drop_in_room' => 'Drop in room',
					'max_streams' => 'Max streams a user can watch',
					'max_rooms' => 'Max rooms one can be in'
	);
	
	$general_settings = array(
					'connection_string' => 'Connection string',
					'invite_link' => 'Invite link',
					'disconnect_link' => 'Disconnect button link',
					'login_page_url' => 'Login page url',
					'register_page_url' => 'Reegister page url' ,
					'text_char_limit' => 'Char limit',
					'background_image' => 'Background image URL',
					'history_lenght' => 'History lenght',
					'display_mode' => 'Display mode'
	);
    
	
	if(isset($_POST) && !empty($_POST)){
		foreach ($_POST as $key=>$avconfs){
			if (strpos($key, "-")){
				$avconf_arrtemp = explode("-", $key);
				if($avconfs == 'on')$avconfs = '1';
				$avconf_arr[$avconf_arrtemp[0]][substr($avconf_arrtemp[1],4)] = $avconfs;
			}else{
				$av_general_confs[substr($key,11)] = $avconfs;
			}
		}
		
		
		
		foreach ($avconf_arr as $key=>$vals){
			$updateString = "";
			foreach($permissions as $pkey => $pvalue){
				if($vals[$pkey] == ""){
					$vals[$pkey] = 0;
				}
				$updateString.= $pkey." = '".$vals[$pkey]."', ";
			}
			
			$i=1;
			foreach($settings as $skey=>$svalue){
				$updateString.= $skey." = '".stripslashes(trim($vals[$skey]))."'";
				if(count($settings) != $i) $updateString.= ', ';
				$i++;
			}
			
			
			$query = "UPDATE ".$table_permissions." SET ".$updateString." WHERE user_role = '".$key."'";
			$wpdb->query($query);
		}
		
		$updateString="";
		$p=1;
		foreach($av_general_confs as $gkey=>$gvalue){
			$updateString.= $gkey." = '".stripslashes(trim($gvalue))."'";
			if(count($av_general_confs) != $p) $updateString.= ', ';
			$p++;
		}
		
		$query = "UPDATE ".$table_general_settings." SET ".$updateString;
		$wpdb->query($query);
	}
	
	
	$location = get_option('siteurl') . '/wp-admin/admin.php?page=avchat3/avchat3-settings.php'; 
	$user_roles = array();
	
	foreach($wp_roles->roles as $role => $details){
		$user_roles[$role] = $details["name"];
	}
	
	unset($user_roles['administrator']);
	
	
	
?>

<div class="wrap">
	<h2>AVChat3 Settings & Permissions</h2>
</div>
<form name="form1" method="post" action="<?php echo $location; ?>">
	<table style="text-align:center">
		
		<tr>
			<th></th>
			<?php foreach($user_roles as $role => $name){?>
				<th style="padding:0 10px !important"><?php echo $name;?></th>
			<?php } ?>
		</tr>
		
		<tr><td colspan="5" style="text-align:left"><h3>Permissions</h3></td>
		<?php foreach($permissions as $key=>$value){ ?>
			
		<tr>
			<td style="text-align:left"><?php echo $value;?></td>
			<?php 
				foreach ($user_roles as $user_role => $name){
					$user_permissions = $wpdb->get_results( "SELECT can_access_chat, can_access_admin_chat, can_publish_audio_video, can_stream_private, can_send_files_to_rooms, can_send_files_to_users, can_pm, can_create_rooms FROM ".$wpdb->prefix . "avchat3_permissions WHERE user_role = '".$user_role."'" );
			?>
				<td style="padding:0 10px !important"><input type="checkbox" <?php if($user_permissions[0]->$key){ echo 'checked="checked"';}?> name="<?php echo strtolower($user_role);?>-avp_<?php echo $key;?>" /></td>
			<?php }?>
		</tr>
		<?php }?>
		
		<tr><td colspan="5" style="text-align:left"><h3>Settings</h3></td>
		<?php foreach($settings as $key=>$value){?>
		<tr>
			<td style="text-align:left"><?php echo $value;?></td>
			<?php 
				foreach ($user_roles as $user_role => $name){
					$user_settings = $wpdb->get_results( "SELECT free_video_time, drop_in_room, max_streams, max_rooms FROM ".$wpdb->prefix . "avchat3_permissions WHERE user_role = '".$user_role."'" );
			?>
				<td style="padding:0 10px !important"><input type="text" name="<?php echo strtolower($user_role);?>-avs_<?php echo $key;?>" value="<?php echo $user_settings[0]->$key;?>" /></td>
			<?php }?>
		</tr>
		<?php }?>
		
		<tr><td colspan="5" style="text-align:left"><h3>General settings</h3></td>
		<?php 
			foreach($general_settings as $key=>$value){
				$av_general_settings = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix . "avchat3_general_settings" );	
		?>
		<tr>
			<td style="text-align:left"><?php echo $value;?></td>
			<td style="padding:0 10px !important;text-align:left;" colspan="4">
				<?php if($key == 'display_mode'){?>
				<select name="avgsetting_<?php echo $key?>">
					<option <?php if ($av_general_settings[0]->$key == 'popup') {echo 'selected="selected"';}?> value="popup">Popup</option>
					<option <?php if ($av_general_settings[0]->$key == 'embed') {echo 'selected="selected"';}?> value="embed">Embed</option>
				</select>
				<?php }else{?>
				<input size="50" type="text" name="avgsetting_<?php echo $key;?>" value="<?php echo $av_general_settings[0]->$key; ?>" />
				<?php }?>
			</td>
		</tr>
		<?php } ?>
	</table>
	<p class="submit"><input type="submit" value="Update Options" class="button-primary" /></p>
</form>
