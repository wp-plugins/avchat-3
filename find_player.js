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
	document.getElementById("av_message").innerHTML = "You do not have the proper Flash Player installed. Click below to download the newest version of Flash Player: <p><a href=\"http://www.adobe.com/go/getflashplayer\"><img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" /></a></p>";
}