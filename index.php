<?php

require_once('settings.php');
require 'vendor/autoload.php';

header('Content-Type: text/html; charset=utf-8');

// Sanitize the post params! Pretty neat
$post_input = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
$post_input['custom_canvas_user_id'] = filter_input(INPUT_POST, 'custom_canvas_user_id', FILTER_SANITIZE_NUMBER_INT);
$post_input['custom_canvas_course_id'] = filter_input(INPUT_POST, 'custom_canvas_course_id', FILTER_SANITIZE_NUMBER_INT);

// set session to invalid ONLY when we need to verify the LTI oauth or an existing valid session doesn't exist
if ( isset($post_input["oauth_consumer_key"]) || ! isset($_SESSION['valid'])) {
    $_SESSION['valid'] = false; // force BLTI verification
}
// Validate LTI and store Launch Params in session
if ($_SESSION['valid'] == false) {
    require_once('lib/ims-blti/blti.php');
    // Initialize, all secrets are 'secret', do not set session, and do not redirect
    $context = new BLTI($consumer_key, $shared_secret, false, false);
    $templates = new League\Plates\Engine('templates');

    if ( ! $context->valid) {
        // Authentication error
        $other_msg = "other possible msg";
        $render_params = [
            'msg' => "An error occurred, please refresh and try again. If this error persists, please contact support."
        ];
        echo($templates->render('error', $render_params));
    } else {

        $_SESSION['launch_params']['custom_canvas_user_id'] = $post_input['custom_canvas_user_id'];
        $_SESSION['launch_params']['custom_canvas_course_id'] = $post_input['custom_canvas_course_id'];
        $_SESSION['launch_params']['context_label'] = $post_input['context_label'];
        $_SESSION['launch_params']['context_title'] = $post_input['context_title'];
        $_SESSION['valid'] = true;

        $render_params = [
            "user_id" => $_SESSION['launch_params']['custom_canvas_user_id'],
            "course_id" => $_SESSION['launch_params']['custom_canvas_course_id']
        ];
        echo($templates->render('welcome', $render_params));
    }
}

?>