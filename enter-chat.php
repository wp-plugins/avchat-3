<?php session_start();

/*
 * Check if the Connection string has been set
 */
    // Store any error messages
    $error_messages = array();

    $db_check_settings = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "avchat3_general_settings");
    $db_grab_conn_string = $db_check_settings[0]->connection_string;

    // Pull from DB and do a small check to see if the user has filled Connection string
    if ($db_grab_conn_string === "rtmp://" || empty($db_grab_conn_string))
    {
        $error_messages[] = "Please fill in the <code>Connection string</code> first in Video Chat - Settings - Plugin Settings.";
    }

    // Check if the user has uploaded the AVChat software files
    if (file_exists(plugin_dir_path(__FILE__) . 'audio_video_quality_profiles') == false)
    {
        $error_messages[] = "You need to upload the AVChat software into <code>wp-contents/plugins/avchat-3</code> folder.";
    }

    // If the user hasn't filled Connection string, start nagging
    if (empty($error_messages) === false) {
        foreach ($error_messages as $error_message) {
            echo "
            <div class='wrap'>
                <div class='error form-invalid'>
                    <p>
                        {$error_message}
                    </p>
                </div>
            </div>
            ";
        }
    }

/*
 * Main function for outputting the chat in backend
 */
function start_output_in_admin() {

    // Make sure that there are no errors stored => display the chat
    if (empty($error_messages) === false)
    {
        $display_mode = 'embed';

        $user_info = avchat3_get_user_details();
        avchat3_set_user_details_on_session($user_info);
        avchat3_set_avchat3_general_settings_on_session();

        // Is admin, load admin login
        $movie_param = 'admin.swf';

        // Blank for the moment
        $FB_appId = '';

        // Is the user on a mobile device?
        require_once ABSPATH . 'wp-content/plugins/avchat-3/Mobile_Detect.php';
        $mobilecheck = new Mobile_Detect();

        if ($mobilecheck->isMobile() || $mobilecheck->isTablet()) {

            $embed = '<a href="' . get_bloginfo('url') . '/wp-content/plugins/avchat-3/m/m.php" style="background:#f0f0f0;display:block;padding:10px 20px;width:200px;text-align:center;border:1px solid #ccc">Enter mobile version</a>';

            // No mobile device detected, display the normal version
        } else {

            if ($display_mode == 'embed')
            {
                $embed = '
                        <div id="myContent">
                            <div id="av_message">
                                You need to have JavaScript enabled and <a target="_blank" href="http://get2.adobe.com/flashplayer/">the latest version of Flash Player</a> for the chat to work.
                            </div>
                        </div>
                        <input type="hidden" name="FB_appId" id="FB_appId" value="' . $FB_appId . '" />
                        <script type="text/javascript" src="' . get_bloginfo('url') . '/wp-content/plugins/avchat-3/tinycon.min.js"></script>
                        <script type="text/javascript" src="' . get_bloginfo('url') . '/wp-content/plugins/avchat-3/facebook_integration.js"></script>
                        <script type="text/javascript" src="' . get_bloginfo('url') . '/wp-content/plugins/avchat-3/swfobject.js"></script>
                        <script type="text/javascript" src="' . get_bloginfo('url') . '/wp-content/plugins/avchat-3/new_message.js"></script>
                        <script type="text/javascript">
                        var plugin_path = "' . get_bloginfo('url') . '/wp-content/plugins/avchat-3/"; var embed = "' . $display_mode . '";
                        </script>
                        <script type="text/javascript">
                            var flashvars = {
                                lstext : "Loading Settings...",
                                sscode : "php",
                                userId : ""
                            };
                            var params = {
                                wmode : "transparent",
                                quality : "high",
                                bgcolor : "#272727",
                                play : "true",
                                loop : "false",
                                allowFullScreen : "true",
                                base : "' . get_bloginfo("url") . '/wp-content/plugins/avchat-3/"
                            };
                            var attributes = {
                                name : "index_embed",
                                id :   "index_embed",
                                align : "middle"
                            };
                        </script>
                        <script type="text/javascript">
                        swfobject.embedSWF("' . get_bloginfo('url') . '/wp-content/plugins/avchat-3/' . $movie_param . '", "myContent", "100%", "600", "11.4.0", "", flashvars, params, attributes);
                        </script>';
                $embed .= '<script type="text/javascript" src="' . get_bloginfo('url') . '/wp-content/plugins/avchat-3/find_player.js"></script>';
            } else {
                $chat_window_url = get_bloginfo('url') . '/wp-content/plugins/avchat-3/index_popup.php?movie_param=' . $movie_param . "&FB_appId=" . FB_appId;
                $chat_window_height = 600;
                $chat_window_width = 800;
                $embed = '<a style="display:block;padding:5px 3px;width:200px;margin:5px 0;text-align:center;background:#f3f3f3;border:1px solid #ccc" href="#" onclick="javascript:window.open(' . '&#39;' . $chat_window_url . '&#39;' . ',\'_blank\',\'width=' . $chat_window_width . ',height=' . $chat_window_height . '\')">Open chat in popup</a>';
            }
        }
        return $embed;

    }// End empty($error_messages)

} // End start_output_in_admin()


?>

<!DOCTYPE html>
<html>
<body>

<div class="wrap">

    <?php
    /* -- Start -- */

    // Parse the chat area
    echo start_output_in_admin();

    /* -- End -- */
    ?>

</div>

</body>
</html>