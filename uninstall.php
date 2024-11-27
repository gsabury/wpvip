<?php

// If uninstall is not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

global $wpdb,$table_prefix;

// Delete tables from database
$wpdb->query('DROP TABLE IF EXISTS `'.$table_prefix.'vip_bills`');
$wpdb->query('DROP TABLE IF EXISTS `'.$table_prefix.'vip_codes`');
$wpdb->query('DROP TABLE IF EXISTS `'.$table_prefix.'vip_codes_logs`');
$wpdb->query('DROP TABLE IF EXISTS `'.$table_prefix.'vip_files`');
$wpdb->query('DROP TABLE IF EXISTS `'.$table_prefix.'vip_payments`');
$wpdb->query('DROP TABLE IF EXISTS `'.$table_prefix.'vip_plans`');
$wpdb->query('DROP TABLE IF EXISTS `'.$table_prefix.'vip_users`');

// Delete Options
delete_option('wpvip_db_version');
// delete_option('wallet');
