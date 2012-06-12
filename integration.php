<?php
/*
Copyright (C) 2009-2010 Mihai Frentiu, avchat.net

This WordPress Plugin is distributed under the terms of the GNU General Public License.
You can redistribute it and/or modify it under the terms of the GNU General Public License 
as published by the Free Software Foundation, either version 3 of the License, or any later version.

You should have received a copy of the GNU General Public License
along with this plugin.  If not, see <http://www.gnu.org/licenses/>.
*/


session_start();
$allowVisitors = 0;



//-----------------------------------------
//Config general settings
//-----------------------------------------
$avconfig['inviteLink']= $_SESSION['invite_link'];

if($_SESSION['disconnect_link'] != ""){
	$avconfig['disconnectButtonLink'] = $_SESSION['disconnect_link'];
}

if($_SESSION['login_page_url'] != ""){
	$avconfig['loginPageURL']= $_SESSION['login_page_url'];
}

if($_SESSION['register_page_url'] != ""){
	$avconfig['registerPageURL']= $_SESSION['register_page_url'];
}

$avconfig['textChatCharLimit']= $_SESSION['text_char_limit'];
$avconfig['backgroundImageUrl']= $_SESSION['background_image'];

if($_SESSION['history_lenght'] != ""){
	$avconfig['historyLength']= $_SESSION['history_lenght'];
}

$avconfig['connectionstring']= $_SESSION['connection_string'];


if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']){
	//-----------------------------------------
	//Config username
	//-----------------------------------------
	$avconfig['username'] = $_SESSION['user_login'];
	$avconfig['changeuser'] = 0;
	//-----------------------------------------
	//Get user role
	//-----------------------------------------
	$role = $_SESSION['user_role'];
	
	if(isset($_SESSION['is_buddy']) && $_SESSION['is_buddy']){
		
		//USER AVATAR
		$avconfig['usersListType']='thumbnail';
		$avconfig['thumbnailUrl']=$_SESSION['avatar'];
		
		//User profile page
		$avconfig['profileKey'] = 'username';
		$avconfig['profileUrl']='../../../members/';
	}
	
	
	//----------------------------------------------------
	//Deny access to chat admin to unauthorized users 
	//----------------------------------------------------
	if(isset($_GET['admin']) && $_GET['admin'] == 'true'){
		if($_SESSION['user_role'] != "administrator" && !$_SESSION['can_access_admin_chat']){
			
			$avconfig['showLoginError'] = 1;
			return 0;
			
		}	
	}
	if($role != "administrator"){		
		//----------------------------------------------------
		//Config settings & permission for non administrators
		//----------------------------------------------------
		if($_SESSION['can_access_chat'] != '1'){
			$avconfig['showLoginError'] = 1;
		}else{		
			//-----------------------------------------
			//Config send audio/video permission
			//-----------------------------------------
			if($_SESSION['can_publish_audio_video']){
				$avconfig['allowVideoStreaming']=1;
				$avconfig['allowAideoStreaming']=1;
				
				//-----------------------------------------
				//Config private stream permission
				//-----------------------------------------
				if($_SESSION['can_stream_private']){
					$avconfig['allowPrivateStreaming']=1;
				}else{
					$avconfig['allowPrivateStreaming']=0;
				}
			}else{
				$avconfig['allowVideoStreaming']=0;
				$avconfig['allowAideoStreaming']=0;
			}
			
			//-----------------------------------------
			//Config send file to room/user permissions
			//-----------------------------------------
			if($_SESSION['can_send_files_to_rooms']){
				$avconfig['sendFileToRoomsEnabled']=1;
			}else{
				$avconfig['sendFileToRoomsEnabled']=0;
			}
			
			if($_SESSION['can_send_files_to_users']){
				$avconfig['sendFileToUserEnabled']=1;
			}else{
				$avconfig['sendFileToUserEnabled']=0;
			}
			
			//-----------------------------------------
			//Config send private message permission
			//-----------------------------------------
			if($_SESSION['can_pm']){
				$avconfig['pmEnabled']=1;
			}else{
				$avconfig['pmEnabled']=0;
			}
			
			//-----------------------------------------
			//Config room creation permission
			//-----------------------------------------
			if($_SESSION['can_create_rooms']){
				$avconfig['createRoomsEnabled']=1;
			}else{
				$avconfig['createRoomsEnabled']=0;
			}
			
			//-----------------------------------------
			//Config free video time
			//-----------------------------------------
			if($_SESSION['free_video_time'] != ""){
				$avconfig['freeVideoTime']=$_SESSION['free_video_time'];
			}
			
			//-----------------------------------------
			//Config drop in room
			//-----------------------------------------
			if($_SESSION['drop_in_room'] != ""){
				$avconfig['dropInRoom']=$_SESSION['drop_in_room'];
			}
			
			//-----------------------------------------
			//Config max streams a user can watch
			//-----------------------------------------
			if($_SESSION['max_streams'] != ""){
				$avconfig['maxStreams']=$_SESSION['max_streams'];
			}
			
			//-----------------------------------------
			//Config max rooms one can be in
			//-----------------------------------------
			if($_SESSION['max_rooms'] != ""){
				$avconfig["maxRoomsOneCanBeIn"]=$_SESSION['max_rooms'];
			}
			
		}
	}else{
		//------------------------------------------
		//Give maximum permissions to administrators
		//------------------------------------------
		
		$avconfig['freeVideoTime'] = -1;
		$avconfig['maxStreams']=99;
		$avconfig["maxRoomsOneCanBeIn"]=99;
	}
	
}else
if($allowVisitors){
	//--------------------------------------------------------------------
	//Allow chat access to unregistered users but with predefined username
	//--------------------------------------------------------------------	
	$avconfig['username'] = 'user_'.rand(0,999);
	$avconfig['changeuser'] = 0;
}else{
	//------------------------------------------
	//Deny chat access to unregistered users
	//------------------------------------------
	$avconfig['showLoginError'] = 1;
}

?>