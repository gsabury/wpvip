<?php

global $wpdb,$table_prefix;

$charset_collate = $wpdb->get_charset_collate();

$vip_bills = 'CREATE TABLE IF NOT EXISTS `'.$table_prefix.'vip_bills` (
                  `ID` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                  `user_id` bigint(20) NOT NULL,
                  `type` tinyint(4) NOT NULL,
                  `amount` bigint(20) NOT NULL,
                  `date` datetime NOT NULL,
                  `balance` bigint(20) NOT NULL,
                  `description` text COLLATE utf8_persian_ci NOT NULL,
                  INDEX idx_user_id (user_id),
                  INDEX idx_type (type),
                  INDEX idx_date (date)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

$vip_codes = 'CREATE TABLE IF NOT EXISTS `'.$table_prefix.'vip_codes` (
                  `code_ID` int(11) AUTO_INCREMENT PRIMARY KEY,
                  `code_hash` varchar(10) COLLATE utf8_bin NOT NULL,
                  `code_percent` tinyint(3) unsigned NOT NULL,
                  `code_expire_date` date NOT NULL,
                  `code_count_limit` int(11) NOT NULL,
                  `code_status` tinyint(1) NOT NULL,
                  INDEX idx_code_status (code_status),
                  INDEX idx_code_expire_date (code_expire_date)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

$vip_code_logs = 'CREATE TABLE IF NOT EXISTS `'.$table_prefix.'vip_codes_logs` (
                      `code_log_id` int(11) AUTO_INCREMENT PRIMARY KEY,
                      `code_log_code_id` int(11) NOT NULL,
                      `code_log_user_id` int(11) NOT NULL,
                      `code_log_created_at` datetime NOT NULL
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

$vip_files = 'CREATE TABLE IF NOT EXISTS `'.$table_prefix.'vip_files` (
              `file_id` int(11) AUTO_INCREMENT PRIMARY KEY,
              `file_name` varchar(250) COLLATE utf8_persian_ci NOT NULL,
              `file_size` int(11) NOT NULL,
              `download_count` int(11) NOT NULL DEFAULT \'0\',
              `hash_code` varchar(100) COLLATE utf8_persian_ci NOT NULL,
              `status` tinyint(2) NOT NULL,
              INDEX idx_status (status)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

$vip_payments = 'CREATE TABLE IF NOT EXISTS `'.$table_prefix.'vip_payments` (
                  `payment_id` int(11) AUTO_INCREMENT PRIMARY KEY,
                  `payment_user_id` int(11) NOT NULL,
                  `payment_amount` int(11) NOT NULL,
                  `payment_res_num` varchar(250) COLLATE utf8_persian_ci NOT NULL,
                  `payment_ref_num` varchar(250) COLLATE utf8_persian_ci DEFAULT NULL,
                  `payment_date` datetime NOT NULL,
                  `payment_status` tinyint(1) NOT NULL DEFAULT \'0\',
                  INDEX idx_payment_res_num (payment_res_num),
                  INDEX idx_payment_status (payment_status)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

$vip_plans = 'CREATE TABLE IF NOT EXISTS `'.$table_prefix.'vip_plans` (
                 `plan_ID` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                  `title` varchar(500) COLLATE utf8_persian_ci NOT NULL,
                  `price` int(11) NOT NULL,
                  `credit` int(11) NOT NULL,
                  `status` tinyint(4) NOT NULL,
                  INDEX idx_status (status)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

$vip_users = 'CREATE TABLE IF NOT EXISTS `'.$table_prefix.'vip_users` (
                  `ID` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                  `plan_id` bigint(20) NOT NULL,
                  `user_id` bigint(20) NOT NULL,
                  `expire_date` datetime NOT NULL,
                  INDEX idx_plan_id (plan_id),
                  INDEX idx_user_id (user_id)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';


require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

$wpvip_db_version = get_option('wpvip_db_version');

if(intval($wpvip_db_version) != WPVIP_DB_VERSION){

    dbDelta($vip_users);
    dbDelta($vip_plans);
    dbDelta($vip_bills);
    dbDelta($vip_files);
    dbDelta($vip_payments);
    dbDelta($vip_codes);
    dbDelta($vip_code_logs);

    update_option('wpvip_db_version',WPVIP_DB_VERSION);
}



