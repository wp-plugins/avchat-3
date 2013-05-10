var mobile = false;
var ua = navigator.userAgent.toLowerCase();
if(navigator.appVersion.indexOf("iPad") != -1 || navigator.appVersion.indexOf("iPhone") != -1 || ua.indexOf("android") != -1 || ua.indexOf("ipod") != -1 || ua.indexOf("windows ce") != -1 || ua.indexOf("windows phone") != -1){
	mobile = true;
}
if(iOS == true){
	document.getElementById("av_message").innerHTML = "This content requires Adobe Flash Player, which is not supported by your device. This content can be viewed on a desktop computer or on mobile devices that support Flash Player.";
}else{
	document.getElementById("av_message").innerHTML = "You do not have the proper Flash Player installed. Click below to download the newest version of Flash Player: <p><a href=\"http://www.adobe.com/go/getflashplayer\"><img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" /></a></p>";
}