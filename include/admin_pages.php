<?php

// Manage Dashboard Page *
function wpvip_dashboard()
{
    global $wpdb, $table_prefix;

    $all_vip_users_count = $wpdb->get_var("SELECT COUNT(ID) FROM {$table_prefix}vip_users");

    $all_vip_files_count = $wpdb->get_var("SELECT COUNT(file_id) FROM {$table_prefix}vip_files");

    $total_today_payments = $wpdb->get_var("SELECT SUM(payment_amount)
                                            FROM {$table_prefix}vip_payments
                                            WHERE payment_status=1
                                            AND DATE(payment_date)=DATE(NOW())");
    $today = date('Y-m');
    $total_month_payments = $wpdb->get_var("SELECT SUM(payment_amount)
                                            FROM {$table_prefix}vip_payments
                                            WHERE payment_status=1
                                            AND DATE(payment_date) BETWEEN '{$today}-01' AND '{$today}-30'");


    include WPVIP_TPL . 'admin/dashboard/dashboard.php';
}

// Manage Plans Page *
function wpvip_plans_page()
{
    global $wpdb, $table_prefix;
    $success = FALSE;
    $error = FALSE;
    $message = "";

    $action = isset($_GET['action']) && !empty($_GET['action']) && ctype_alpha($_GET['action']) ? $_GET['action'] : NULL;

    switch ($action) {
        case 'edit':

            $item_id = isset($_GET['item_id']) && ctype_digit($_GET['item_id']) ? intval($_GET['item_id']) : NULL;

            if (isset($_POST['submit'])) {

                $title = sanitize_text_field($_POST['title']);
                $price = intval($_POST['price']);
                $credit = intval($_POST['credit']);
                $plan_ID = isset($_POST['plan_ID']) ?  $_POST['plan_ID'] : NULL;

                if (intval($plan_ID)) {
                    $wpdb->update($table_prefix . 'vip_plans', array(
                        'title'  => $title,
                        'price'  => $price,
                        'credit' => $credit
                    ), array(
                        'plan_ID' => $plan_ID
                    ), array(
                        '%s',
                        '%d',
                        '%d'
                    ), array(
                        '%d'
                    ));
                } else {
                    $wpdb->insert($table_prefix . 'vip_plans', array(
                        'title'  => $title,
                        'price'  => $price,
                        'credit' => $credit
                    ), array(
                        '%s',
                        '%d',
                        '%d'
                    ));
                }
                $success = TRUE;
                $message = "اطلاعات با موفقیت ذخیره گردید.";
            }

            if ($item_id) {
                $plan_edit = $wpdb->get_row($wpdb->prepare("SELECT * 
                                                                FROM {$table_prefix}vip_plans 
                                                                WHERE plan_ID=%d", $item_id));
            }

            include WPVIP_TPL . 'admin/plans/edit.php';

            break;
        case 'delete':

            $item_id = isset($_GET['item_id']) && ctype_digit($_GET['item_id']) ? intval($_GET['item_id']) : NULL;

            if ($item_id) {

                $wpdb->query($wpdb->prepare("DELETE FROM {$table_prefix}vip_plans
                                             WHERE plan_ID = %d", $item_id));
            }
            wp_redirect(admin_url('admin.php?page=wpvip_admin_plans'));
            exit();
            break;
        default:
            $plans = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_plans ORDER BY plan_ID DESC");
            include WPVIP_TPL . 'admin/plans/plans.php';
            break;
    }
}

// Manage VIP Users *
function wpvip_users_page()
{
    global $wpdb, $table_prefix;
    $success = FALSE;
    $error = FALSE;
    $message = "";

    $action = isset($_GET['action']) && !empty($_GET['action']) && ctype_alpha($_GET['action']) ? $_GET['action'] : NULL;

    switch ($action) {
        case 'delete':
            $uid = isset($_GET['uid']) && ctype_digit($_GET['uid']) ? intval($_GET['uid']) : null;

            if ($uid) {
                $wpdb->query($wpdb->prepare("DELETE 
                                             FROM {$table_prefix}vip_users 
                                             WHERE user_id = %d", $uid));

                wp_redirect(admin_url('admin.php?page=wpvip_admin_users'));
                exit();
            }

            break;
        case 'new':
            if (isset($_POST['submit'])) {

                $user_id = intval($_POST['user_id']);
                $plan_id = intval($_POST['plan_id']);
                $credit = intval($_POST['credit']);

                $wpdb->insert($table_prefix . 'vip_users', array(
                    'plan_id' => $plan_id,
                    'user_id' => $user_id,
                    'expire_date' => date("Y-m-d H:i:s", strtotime("+ {$credit} days"))
                ), array(
                    '%d',
                    '%d',
                    '%s'
                ));

                $success = TRUE;
                $message = "اطلاعات با موفقیت ذخیره گردید.";
            }

            $users = $wpdb->get_results("SELECT ID,display_name
                                         FROM {$wpdb->users}");

            $plans = $wpdb->get_results("SELECT plan_ID,title 
                                        FROM {$table_prefix}vip_plans");

            include WPVIP_TPL . 'admin/users/new.php';

            break;
        case 'change':
            $uid = isset($_GET['uid']) && ctype_digit($_GET['uid']) ? intval($_GET['uid']) : null;

            if (isset($_POST['submit'])) {
                $type = intval($_POST['type']);
                $credit = intval($_POST['credit']);
                $uid = intval($_POST['uid']);

                if ($uid) {
                    if ($type == 1)
                        $mysql_date_type = 'DATE_ADD';
                    if ($type == 2)
                        $mysql_date_type = 'DATE_SUB';

                    $result = $wpdb->query($wpdb->prepare("UPDATE {$table_prefix}vip_users
                                             SET expire_date = {$mysql_date_type}(expire_date,INTERVAL %d DAY)
                                             WHERE user_id = %d", $credit, $uid));
                }

                $success = TRUE;
                $message = "اطلاعات با موفقیت ذخیره گردید.";
            }

            include WPVIP_TPL . 'admin/users/change.php';

            break;
        case 'balance':
            $uid = isset($_GET['uid']) && ctype_digit($_GET['uid']) ? intval($_GET['uid']) : null;

            if (isset($_POST['submit'])) {

                $type = intval($_POST['type']);

                if (!intval($type))
                    break;

                $amount = intval($_POST['amount']);
                $uid = intval($_POST['uid']);

                if ($uid) {
                    $new_wallet = wpvip_update_user_wallet($uid, $amount, $type);

                    $wpdb->insert($table_prefix . 'vip_bills', array(
                        'user_id' => $uid,
                        'type'     => $type,
                        'amount'   => $amount,
                        'date'     => date('Y-m-d H:i:s'),
                        'balance' => $new_wallet,
                        'description'  => 'تغییر موجودی کاربر توسط مدیریت'
                    ), array(
                        '%d',
                        '%d',
                        '%d',
                        '%s',
                        '%d',
                        '%s'
                    ));
                }

                $success = TRUE;
                $message = "اطلاعات با موفقیت ذخیره گردید.";
            }

            include WPVIP_TPL . 'admin/users/balance.php';

            break;
        default:

            $vip_users = $wpdb->get_results("SELECT u.display_name,u.ID as userID,vu.*,vp.title as plan_title
                                             FROM {$wpdb->users} u
                                             LEFT JOIN {$table_prefix}vip_users vu
                                             ON u.ID = vu.user_id
                                             LEFT JOIN {$table_prefix}vip_plans vp
                                             ON vu.plan_id = vp.plan_ID");

            include WPVIP_TPL . 'admin/users/users.php';
            break;
    }
}

// Show Bills *
function wpvip_bills_page()
{
    global $wpdb, $table_prefix;

    $bills = $wpdb->get_results("SELECT b.*,u.display_name 
                                 FROM {$table_prefix}vip_bills b
                                 JOIN {$wpdb->users} u ON b.user_id = u.ID
                                 ORDER BY b.date DESC");

    include  WPVIP_TPL . 'admin/bills/bills.php';
}

// Manage Files
function wpvip_files_page()
{
    global $wpdb, $table_prefix;

    $success = FALSE;
    $error = FALSE;
    $message = "";

    $action = isset($_GET['action']) && !empty($_GET['action']) && ctype_alpha($_GET['action']) ? $_GET['action'] : NULL;

    switch ($action) {
        case 'new':
            if (isset($_POST['submit'])) {

                $file_types_white_list = array(
                    'image/jpeg',
                    'image/png',
                    'application/x-zip-compressed',
                    'application/pdf'
                );

                if (isset($_FILES['file']['name']) && $_FILES['file']['error'] == 0 && in_array($_FILES['file']['type'], $file_types_white_list)) {

                    $name = $_FILES['file']['name'];
                    $upload_path = WPVIP_UPLOAD_DIR . $name;

                    if (!file_exists(WPVIP_UPLOAD_DIR)) {
                        @mkdir(WPVIP_UPLOAD_DIR);
                    }

                    $result_file_upload = move_uploaded_file($_FILES['file']['tmp_name'], $upload_path);

                    if ($result_file_upload) {
                        $file_hash = md5_file($upload_path);
                        $wpdb->insert($table_prefix . 'vip_files', array(
                            'file_name' => $name,
                            'file_size' => $_FILES['file']['size'],
                            'hash_code' => $file_hash,
                            'status'    => 1
                        ), array(
                            '%s',
                            '%d',
                            '%s',
                            '%d'
                        ));
                    }

                    $success = TRUE;
                    $message = "اطلاعات با موفقیت ذخیره گردید.";
                }
            }
            include  WPVIP_TPL . 'admin/files/new_file.php';
            break;
        default:
            $all_files = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_files");
            include  WPVIP_TPL . 'admin/files/files.php';
            break;
    }
}

// Manage Discount Codes
function wpvip_codes_page()
{

    global $wpdb, $table_prefix;

    $action = isset($_GET['action']) && !empty($_GET['action']) && ctype_alpha($_GET['action']) ? $_GET['action'] : NULL;

    switch ($action) {
        case 'new':
            if (isset($_POST['submit'])) {

                $percent = intval($_POST['percent']);
                $expire_days = intval($_POST['expire_days']);

                if ($percent && $expire_days) {
                    $code_hash =  substr(bin2hex(random_bytes(6)), 0, 6);
                    $status = 1;
                    $count_limit = 0;
                    $wpdb->query($wpdb->prepare("
                        INSERT INTO {$table_prefix}vip_codes 
                        (`code_hash`,`code_percent`,`code_expire_date`,`code_count_limit`,`code_status`) 
                        VALUES(%s,%d,DATE_ADD(NOW(),INTERVAL %d DAY),%d,%d)
                    ", $code_hash, $percent, $expire_days, $count_limit, $status));
                }
            }
            include  WPVIP_TPL . 'admin/codes/new-code.php';
            break;
        default:
            $all_codes = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_codes");
            include  WPVIP_TPL . 'admin/codes/codes.php';
            break;
    }
}

// Manage Settings
function wpvip_admin_options()
{
    $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
    $tabs =  array(
        'general' => 'عمومی',
        'message' => 'اطلاع رسانی',
        'gateways' => 'درگاه های پرداخت'
    );

    $wpvip_options = get_option('wpvip_options');

    $sidebar_category = get_categories(array('hide_empty' => 0));

    $success = FALSE;
    $error = FALSE;
    $message = "";

    if (isset($_POST['submit'])) {

        switch ($current_tab) {

            case 'general':
                isset($_POST['wpvip_is_active']) ? $wpvip_options['general']['wpvip_is_active'] = 1 : $wpvip_options['general']['wpvip_is_active'] = 0;
                isset($_POST['notification_day_before']) ? $wpvip_options['general']['notification_day_before'] = intval($_POST['notification_day_before']) : null;
                !empty($_POST['wpvip_new_users_email']) ? $wpvip_options['general']['wpvip_new_users_email'] = $_POST['wpvip_new_users_email'] : null;
                !empty($_POST['sidebar_category']) ? $wpvip_options['general']['wpvip_sidebar_cat'] = $_POST['sidebar_category'] : 1;
                $success = TRUE;
                $message = "اطلاعات با موفقیت ذخیره گردید.";
                break;
            case 'message':
                isset($_POST['wpvip_new_user_sms']) ? $wpvip_options['messages']['wpvip_new_user_sms'] = sanitize_text_field($_POST['wpvip_new_user_sms']) : null;
                $success = TRUE;
                $message = "اطلاعات با موفقیت ذخیره گردید.";
                break;
            case 'gateways':
                break;
        }
    }
    update_option('wpvip_options', $wpvip_options);
    $wpvip_options = get_option('wpvip_options');
    include  WPVIP_TPL . 'admin/options/main.php';
}
