<?php
// Create dd function for debugging *
if (!function_exists('dd')) {
    function dd($data)
    {

        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        exit();
    }
}

// Convert Miladi date to Shamsi *
function wpvip_persian_date($date)
{
    if (!function_exists('parsidate'))
        return $date;

    return parsidate("Y/m/d", $date, 'per');
}

// Send Email *
function wpvip_send_email($params = array())
{
    $headers = array();
    $headers[] = 'From: 7learn.com <gh.sabury@gmail.com>';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    wp_mail($params['to'], $params['subject'], $params['message'], $headers);
}

// Send SMS *
function wpvip_send_sms($params = array())
{
    !class_exists('farapayamak') ? require_once WPVIP_LIBS . 'farapayamak.class.php' : NULL;
    $fp = new farapayamak();
    $fp->user = "5689452";
    $fp->pass = "6546554";
    $fp->from = "100020003000";
    $fp->to = $params['to'];
    $fp->msg = $params['msg'];
    $fp->send_sms();
}

//Get user walet *
function wpvip_get_user_wallet($userID)
{
    if (intval($userID) > 0) {
        return intval(get_user_meta($userID, 'wallet', TRUE));
    }
}

// Update user wallet *
function wpvip_update_user_wallet($userID, $amount, $type)
{
    if (intval($userID) > 0) {

        $wallet = wpvip_get_user_wallet($userID);

        $new_wallet = NULL;
        if (intval($type) == 1)
            $new_wallet = $wallet + intval($amount);

        if (intval($type) == 2)
            $new_wallet = $wallet - intval($amount);

        update_user_meta($userID, 'wallet', intval($new_wallet));

        return $new_wallet;
    }
}

// Cange rial to toman *
function wpvip_format_money($value)
{
    return number_format($value / 10) . ' تومان ';
}

// Check if the user is VIP *
function wpvip_is_user_vip($userID = NULL)
{
    $current_user = wp_get_current_user();
    $userID = intval($userID) ? $userID : $current_user->ID;
    if (intval($userID) == 0)
        return FALSE;

    global $wpdb, $table_prefix;
    $result = $wpdb->get_var($wpdb->prepare("SELECT ID 
                             FROM {$table_prefix}vip_users
                             WHERE user_id = %d", $userID));

    return intval($result) ? TRUE : FALSE;
}

// Add the user as vip *
function wpvip_add_user_to_vip($planID, $userID)
{
    global $wpdb, $table_prefix;
    $plan = $wpdb->get_row($wpdb->prepare("SELECT *
                                               FROM {$table_prefix}vip_plans
                                               WHERE plan_ID = %d
                                               LIMIT 1", $planID));
    if ($plan) {
        $plan_expire_credit_in_day = $plan->credit;
        $current_date = new DateTime();
        $expire_date = $current_date->add(new DateInterval('P' . $plan_expire_credit_in_day . 'D'));

        $wpdb->insert($table_prefix . 'vip_users', array(
            'plan_id'     => $planID,
            'user_id'     => $userID,
            'expire_date' => $expire_date->format('Y-m-d H:i:s')
        ));
        //            $wpdb->query("INSERT INTO {$table_prefix}vip_users
        //                          (plan_id,user_id,expire_date)
        //                          VALUES({$planID},{$userID},DATE_ADD(NOW(),INTERVAL {$plan_expire_credit_in_day} DAY))");
    }
}

// Check if the current user is able to view the post *
function wpvip_user_can_view_post($userID, $postID)
{
    $post_plan = wpvip_get_post_plan($postID);

    if ($post_plan) {
        global $wpdb, $table_prefix;
        $user_plan = $wpdb->get_var($wpdb->prepare("
            SELECT ID FROM {$table_prefix}vip_users
            WHERE user_id = %d AND plan_id = %d LIMIT 1
        ", $userID, $post_plan));

        return intval($user_plan) ? TRUE : FALSE;
    }

    return TRUE;
}

// Check User Plan *
function wpvip_check_user_by_plan($userID, $planID)
{
    global $wpdb, $table_prefix;
    $user_plan = $wpdb->get_var($wpdb->prepare("
        SELECT ID FROM {$table_prefix}vip_users
        WHERE user_id = %d AND plan_id = %d LIMIT 1
    ", $userID, $planID));

    return intval($user_plan) ? TRUE : FALSE;
}

// Count Files Sizes *
function wpvip_show_file_size($size)
{
    if ($size == 0 || $size < 1024)
        return $size . ' Byte';
    if ($size < 1024 * 1024)
        return round($size / 1024, 2) . ' KB ';
    if ($size > 1024 * 1024 && $size < 1024 * 1024 * 1024)
        return round($size / (1024 * 1024), 2) . ' MB';
}

//Register styles && scripts for front *
add_action('wp_enqueue_scripts', 'wpvip_add_user_assets');
function wpvip_add_user_assets()
{
    wp_register_style('wpvip_user_css', WPVIP_CSS . 'user.css');
    wp_enqueue_style('wpvip_user_css');
}

// Handle Message *
function wpvip_flash_msg($type = NULL, $message = NULL)
{
    if (isset($type) && isset($message)) {
        $_SESSION['wpvip']['flash']['type'] = $type;
        $_SESSION['wpvip']['flash']['message'] = $message;
    } else {
        if (isset($_SESSION['wpvip']['flash']['type']) && isset($_SESSION['wpvip']['flash']['message'])) {
            $type = $_SESSION['wpvip']['flash']['type'];
            $message = $_SESSION['wpvip']['flash']['message'];
            echo '<div class="' . $type . '"><p>' . $message . '</p></div>';
            $_SESSION['wpvip']['flash'] = array();
        }
    }
}

// Get ID of post plan *
function wpvip_get_post_plan($postID)
{
    if (!intval($postID)) {
        return FALSE;
    }

    return intval(get_post_meta($postID, 'plan', TRUE));
}

//Filters post content *
add_filter('the_content', 'wpvip_filter_contents');
function wpvip_filter_contents($content)
{
    global $post;
    $post_plan = wpvip_get_post_plan($post->ID);

    if (!intval($post_plan)) {
        return $content;
    }

    $current_user = wp_get_current_user();

    $user_can_view_post = wpvip_user_can_view_post($current_user->ID, $post->ID);

    if ($user_can_view_post) {
        return $content;
    }

    return '<div class="wpvip_post_content_limit">این محتوا مخصوص کاربران ویژه سایت می باشد</div>';
}

// Library to hand file upload *
function wpvip_download_file($file, $file_id)
{
    global $wpdb, $table_prefix;
    /**
     * Copyright 2012 Armand Niculescu - media-division.com
     * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
     * 1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
     * 2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
     * THIS SOFTWARE IS PROVIDED BY THE FREEBSD PROJECT "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE FREEBSD PROJECT OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
     */
    // get the file request, throw error if nothing supplied

    // hide notices
    @ini_set('error_reporting', E_ALL & ~E_NOTICE);

    //- turn off compression on the server
    @apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 'Off');

    if (!isset($file) || empty($file)) {
        header("HTTP/1.0 400 Bad Request");
        exit;
    }

    // sanitize the file request, keep just the name and extension
    // also, replaces the file location with a preset one ('./myfiles/' in this example)
    $file_path  = $file;
    $path_parts = pathinfo($file_path);
    $file_name  = $path_parts['basename'];
    $file_ext   = $path_parts['extension'];
    //$file_path  = './myfiles/' . $file_name;

    // allow a file to be streamed instead of sent as an attachment
    $is_attachment = isset($_REQUEST['stream']) ? false : true;

    // make sure the file exists
    if (is_file($file_path)) {
        $file_size  = filesize($file_path);
        $file = @fopen($file_path, "rb");
        if ($file) {
            // set the headers, prevent caching
            header("Pragma: public");
            header("Expires: -1");
            header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");
            header("Content-Disposition: attachment; filename=\"$file_name\"");

            // set appropriate headers for attachment or streamed file
            if ($is_attachment)
                header("Content-Disposition: attachment; filename=\"$file_name\"");
            else
                header('Content-Disposition: inline;');

            // set the mime type based on extension, add yours if needed.
            $ctype_default = "application/octet-stream";
            $content_types = array(
                "exe" => "application/octet-stream",
                "zip" => "application/zip",
                "mp3" => "audio/mpeg",
                "mpg" => "video/mpeg",
                "avi" => "video/x-msvideo",
            );
            $ctype = isset($content_types[$file_ext]) ? $content_types[$file_ext] : $ctype_default;
            header("Content-Type: " . $ctype);

            //check if http_range is sent by browser (or download manager)
            if (isset($_SERVER['HTTP_RANGE'])) {
                list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                if ($size_unit == 'bytes') {
                    //multiple ranges could be specified at the same time, but for simplicity only serve the first range
                    //http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
                    list($range, $extra_ranges) = explode(',', $range_orig, 2);
                } else {
                    $range = '';
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    exit;
                }
            } else {
                $range = '';
            }

            //figure out download piece from range (if set)
            list($seek_start, $seek_end) = explode('-', $range, 2);

            //set start and end based on range (if set), else set defaults
            //also check for invalid ranges.
            $seek_end   = (empty($seek_end)) ? ($file_size - 1) : min(abs(intval($seek_end)), ($file_size - 1));
            $seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)), 0);

            //Only send partial content header if downloading a piece of the file (IE workaround)
            if ($seek_start > 0 || $seek_end < ($file_size - 1)) {
                header('HTTP/1.1 206 Partial Content');
                header('Content-Range: bytes ' . $seek_start . '-' . $seek_end . '/' . $file_size);
                header('Content-Length: ' . ($seek_end - $seek_start + 1));
            } else
                header("Content-Length: $file_size");

            header('Accept-Ranges: bytes');

            set_time_limit(0);
            fseek($file, $seek_start);

            while (!feof($file)) {
                print(@fread($file, 1024 * 8));
                ob_flush();
                flush();
                if (connection_status() != 0) {
                    @fclose($file);
                    exit;
                }
            }

            // file save was a success
            @fclose($file);
            $wpdb->query($wpdb->prepare("UPDATE {$table_prefix}vip_files 
                                         SET download_count=download_count + 1
                                         WHERE file_id=%d", $file_id));
            exit;
        } else {
            // file couldn't be opened
            header("HTTP/1.0 500 Internal Server Error");
            exit;
        }
    } else {
        // file does not exist
        header("HTTP/1.0 404 Not Found");
        exit;
    }
}

// Calculate the finela price *
function wpvip_calculate_with_off($amount, $off)
{
    if (intval($off) == 0)
        return $amount;
    return ((100 - $off) / 100) * $amount;
}

// Check of discount code *
function wpvip_is_code_valid($code, $user_id)
{
    if (empty($code))
        return false;

    global $wpdb, $table_prefix;
    $code_result = $wpdb->get_row($wpdb->prepare("SELECT * 
                                           FROM {$table_prefix}vip_codes 
                                          WHERE code_hash=%s
                                          AND DATE(NOW()) <= code_expire_date", $code));
    if (empty($code_result))
        return false;

    $code_user_result = $wpdb->get_row($wpdb->prepare("SELECT *
                                                       FROM {$table_prefix}vip_codes_logs
                                                       WHERE code_log_code_id = %d
                                                       AND code_log_user_id = %d", $code_result->code_ID, $user_id));
    if (intval($code_user_result->code_log_id)) {
        return false;
    }

    return true;
}

// Get discount code *
function wpvip_get_code($code)
{
    if (empty($code))
        return false;
    global $wpdb, $table_prefix;
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_prefix}vip_codes WHERE code_hash=%s", $code));
}

// Get Options *
function wpvip_get_options()
{
    return get_option('wpvip_options', array());
}
