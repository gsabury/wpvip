<?php
/*
Plugin Name:  VIP Users
Plugin URI: http://yaransoft.com
Description: Powerful Plugin for VIP Users
Author: Abdul Ghafor Sabury
Version: 1.0.0
Author URI:  http://yaransoft.com
*/

// Check if the file is accessed by URL *
defined('ABSPATH') || exit('NO ACCESS');

// Define constants for wpvip plugin directory or URL *
define('WPVIP_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WPVIP_URL', trailingslashit(plugin_dir_url(__FILE__)));

define('WPVIP_INC', trailingslashit(WPVIP_DIR . 'include'));
define('WPVIP_LIBS', trailingslashit(WPVIP_INC . 'libs'));
define('WPVIP_TPL', trailingslashit(WPVIP_DIR . 'templates'));

define('WPVIP_CSS', trailingslashit(WPVIP_URL . 'assets' . '/' . 'css'));
define('WPVIP_JS', trailingslashit(WPVIP_URL . 'assets' . '/' . 'js'));
define('WPVIP_IMAGES', trailingslashit(WPVIP_URL . 'assets' . '/' . 'images'));

$upload_directory = wp_upload_dir();
define('WPVIP_UPLOAD_DIR', trailingslashit($upload_directory['basedir'] . DIRECTORY_SEPARATOR . 'wpvip'));
define('WPVIP_UPLOAD_URL', trailingslashit($upload_directory['baseurl'] . '/' . 'wpvip'));

define('WPVIP_DB_VERSION', 1);

// Start Session and prvent outputing *
add_action('init', 'wpvip_buffer');
function wpvip_buffer()
{
    ob_start();
    if (!session_id()) {
        session_start();
    }
}

// Define Activation & Deactivation Hooks *
function wpvip_activate()
{
    wp_schedule_event(strtotime(date('Y-m-d 00:00:00')), 'daily', 'wpvip_remove_expire_users');
    include WPVIP_INC . 'upgrade.php';
}

// Removes expired users *
add_action('wpvip_remove_expire_users', 'wpvip_remove_expire_users_callback');
function wpvip_remove_expire_users_callback()
{
    global $wpdb, $table_prefix;
    $today = date('Y-m-d H:i:s');
    $expired_users = $wpdb->get_results("DELETE FROM {$table_prefix}vip_users
                                         WHERE DATE(NOW()) >= DATE('expire_date')");
}

// Send notifications (sms or email) to users that will expire soon *
add_action('wpvip_remove_expire_users', 'wpvip_remove_expire_users_notification_callback');
function wpvip_remove_expire_users_notification_callback()
{
    global $wpdb, $table_prefix;
    $options = wpvip_get_options();

    $days = isset($options['general']['notification_day_before']) ? intval($options['general']['notification_day_before']) : 3;
    $email_content = isset($options['general']['wpvip_new_users_email']) ? $options['general']['wpvip_new_users_email'] : "";
    $sms_cotnent = isset($options['messages']['wpvip_new_user_sms']) ? $options['messages']['wpvip_new_user_sms'] : "";
    
    $today = date('Y-m-d');

    $expire_date = date('Y-m-d', strtotime($today . ' + ' . $days . ' days'));
   
    // $expired_users = $wpdb->get_results("SELECT user_id FROM {$table_prefix}vip_users
    //                                      WHERE  DATEDIFF('expire_date',NOW()) <= {$days}");

    $expired_users = $wpdb->get_results("SELECT user_id FROM {$table_prefix}vip_users
                                         WHERE  DATE(expire_date) <= '{$expire_date}'");
    $result = array();
    foreach($expired_users as $user){
        // Send EMAIL
        $user_email = get_user_by("ID",$user->user_id)->user_email;
        $reusult[] = wpvip_send_email(array(
            'to' => $user_email,
            'subject' => 'تجدید پلان',
            'message' => $email_content
        ));

        // Send SMS
        $username = get_user_by("ID", $user->user_id)->display_name;
        $user_phone ="+93787509653";
        $final_message = str_replace(array('#username#'), array("".$username.""), $sms_cotnent);
        wpvip_send_sms(array('to' => $user_phone, 'msg' => $final_message));
        $result[] = $final_message;
    }
}

// Deactivate Hook *
function wpvip_deactivate()
{
    wp_clear_scheduled_hook('wpvip_remove_expire_users');
}

register_activation_hook(__FILE__, 'wpvip_activate');
register_deactivation_hook(__FILE__, 'wpvip_deactivate');

// Include Files  for front & admin panesl*
include WPVIP_INC . 'front_end.php';
include WPVIP_INC . 'shortcodes.php';
include WPVIP_INC . 'gateways.php';

// Include files for admin panel *
if (is_admin()) {
    include WPVIP_INC . 'admin_menu.php';
    include WPVIP_INC . 'admin_pages.php';
    include WPVIP_INC . 'back_end.php';
    include WPVIP_INC . 'metaboxes.php';
}

// Create links for file downlaod *
add_action('init', function () {
    add_rewrite_rule('^download/file/([^/].+)/?$', 'index.php?file_hash=$matches[1]', 'top');
    if (intval(get_option('wpvip_rewrite_rules')) == 0) {
        flush_rewrite_rules();
        update_option('wpvip_rewrite_rules', 1);
    }
});

// ilters the query variables allowed before processing. *
add_filter('query_vars', function ($vars) {
    $vars[] = "file_hash";
    return $vars;
});

// Fires once all query variables for the current request have been parsed *
add_action('parse_request', 'wpvip_parse_request');
function wpvip_parse_request($query)
{
    if (isset($query->query_vars['file_hash'])) {
        $file_hash = $query->query_vars['file_hash'];
        wpvip_download_handler($file_hash);
        exit();
    }
}

// Handle file download *
function wpvip_download_handler($file_hash)
{
    !empty($file_hash) || exit();
    global $wpdb, $table_prefix;
    $file_item = $wpdb->get_row($wpdb->prepare("SELECT * 
                                                    FROM {$table_prefix}vip_files 
                                                    WHERE hash_code=%s", $file_hash));
    if ($file_item) {
        $file_name = $file_item->file_name;
        $file_path = WPVIP_UPLOAD_DIR . $file_name;
        wpvip_download_file($file_path, $file_item->file_id);
    }
}

// Single schedule Event which run once *
wp_schedule_single_event(time() + 3600, 'wpvip_schedule_single_event');
add_action('wpvip_schedule_single_event', 'wpvip_schedule_single_event_callback');
function wpvip_schedule_single_event_callback()
{
    // do something at once
}

