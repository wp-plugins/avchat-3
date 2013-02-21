<?php
if(session_id() == ""){
		session_start();
	}

$movie_param = "index.swf";
if(isset($_GET['movie_param'])){
	$movie_param = $_GET['movie_param'];
}


echo '
<input type="hidden" name="FB_appId" id="FB_appId" value="'.$_GET['FB_appId'].'" />
<input type="hidden" name="AVChat_exists" id="AVChat_exists" value="'.$_GET['AVChat_exists'].'" />
<script type="text/javascript" src="tinycon.min.js"></script>
<script type="text/javascript" src="facebook_integration.js"></script>
<script type="text/javascript" src="swfobject.js"></script>
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
		allowFullScreen : "true"
	};
	var attributes = {
		name : "index_embed",
		id :   "index_embed",
		align : "middle"
	};
	var embed = "embed";
</script>
<script type="text/javascript">
swfobject.embedSWF("'.$movie_param.'", "myContent", "100%", "600", "10.3.0", "", flashvars, params, attributes);</script>
<script type="text/javascript" src="new_message.js"></script>
  
<div id="myContent">
	<div id="av_message" style="color:#ff0000"> </div>
</div>
<script type="text/javascript" src="find_player.js"></script>';
?>