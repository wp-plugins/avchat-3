<?php
if(session_id() == ""){
		session_start();
	}

$movie_param = "index.swf";
if(isset($_GET['movie_param'])){
	$movie_param = $_GET['movie_param'];
}


echo '
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
</script>
<script type="text/javascript">
swfobject.embedSWF("'.$movie_param.'", "myContent", "100%", "600", "10.3.0", "", flashvars, params, attributes);</script>
	  
<div id="myContent">
	<div id="av_message" style="color:#ff0000"> </div>
</div>
<script type="text/javascript" src="find_player.js"></script>';
?>