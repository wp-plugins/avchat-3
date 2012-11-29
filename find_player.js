var iOS = false;
if(navigator.appVersion.indexOf("iPad") != -1 || navigator.appVersion.indexOf("iPhone") != -1){
	iOS = true;
}
if (navigator.userAgent.indexOf("iPad") != -1){
	iOS = true;
}
if(iOS == true){
	document.getElementById("av_message").innerHTML = "This content requires Adobe Flash Player, which is not supported by your device. This content can be viewed on a desktop computer or on mobile devices that support Flash Player.";
}else{
	if(document.getElementById("AVChat_exists").value == "false"){
		document.getElementById("av_message").innerHTML = "For the plugin to work, you need the AVChat files to be copied to the plugin directory! Request a <a href='http://avchat.net/integrations/wordpress' target='_blank'>trial</a> or review the <a href='http://avchat.net/support/documentation/wordpress#121' target='_blank'>Documentation!</a>";
	} 
	else{
		document.getElementById("av_message").innerHTML = "You do not have the proper Flash Player installed. Click below to download the newest version of Flash Player: <p><a href=\"http://www.adobe.com/go/getflashplayer\"><img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" /></a></p>";
	}
}