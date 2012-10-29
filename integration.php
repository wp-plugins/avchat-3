<?php
/*
Copyright (C) 2009-2010 AVChat Software, avchat.net

This WordPress Plugin is distributed under the terms of the GNU General Public License.
You can redistribute it and/or modify it under the terms of the GNU General Public License 
as published by the Free Software Foundation, either version 3 of the License, or any later version.

You should have received a copy of the GNU General Public License
along with this plugin.  If not, see <http://www.gnu.org/licenses/>.
*/


session_start();
$allowVisitors = 0;



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
	$avconfig['username'] = 'AVChat_user_'.rand(0,999);
	$avconfig['changeuser'] = 0;
	
	//----------------------------------------------------
	//Deny access to chat admin to unauthorized users 
	//----------------------------------------------------
	if(isset($_GET['admin']) && $_GET['admin'] == 'true'){
		$avconfig['showLoginError'] = 1;
		return 0;	
	}
}else{
	//------------------------------------------
	//Deny chat access to unregistered users
	//------------------------------------------
	$avconfig['showLoginError'] = 1;
}

?>