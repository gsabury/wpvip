<?php
// Handle plan form *
add_shortcode('wpvip_frm', 'wpvip_order_vip_form');
function wpvip_order_vip_form()
{
    global $wpdb, $table_prefix;
    $options = wpvip_get_options();
    $current_user = wp_get_current_user();
    $current_user_wallet = wpvip_get_user_wallet($current_user->ID);

    if (isset($_POST['wpvip_frm_submit'])) {

        $plan = intval($_POST['plan']);
        $payment = $_POST['payment'];
        $off_code = $_POST['off_code'];

        if ($plan) {
            if (wpvip_is_user_vip()) {
                wpvip_flash_msg('error', 'شما در حال حاضر کاربر ویژه سایت هستید.');
            } else {

                $planDetails = $wpdb->get_row($wpdb->prepare("SELECT *  
                                                               FROM {$table_prefix}vip_plans 
                                                               WHERE plan_ID = %d", $plan));
                $amount  = $planDetails->price;
                $final_amount = $amount;
                $has_code = false;

                if (wpvip_is_code_valid($off_code, $current_user->ID)) {
                    $code_result = wpvip_get_code($off_code);
                    $final_amount = wpvip_calculate_with_off($amount, $code_result->code_percent);
                    $has_code = true;
                }

                if (isset($payment) && $payment == 'account') {

                    if ($final_amount < $current_user_wallet) {

                        wpvip_add_user_to_vip($plan, $current_user->ID);
                        $new_user_wallet = wpvip_update_user_wallet($current_user->ID, $final_amount, 2);

                        $wpdb->insert($table_prefix . 'vip_bills', array(
                            'user_id' => $current_user->ID,
                            'type'     => 2,
                            'amount'   => $final_amount,
                            'date'     => date('Y-m-d H:i:s'),
                            'balance' => $new_user_wallet,
                            'description'  => 'خرید طرح عضویت ویژه وب سایت : ' . $plan
                        ), array(
                            '%d',
                            '%d',
                            '%d',
                            '%s',
                            '%d',
                            '%s'
                        ));
                        wpvip_flash_msg('success', 'تبریک، شما موفقانه حق اشتراک خریداری نموده اید.');
                    } else {
                        wpvip_flash_msg('error', 'موجودی حساب شما برای انجام این خرید کافی نمی باشد');
                    }
                } elseif ($payment == 'online') {

                    $payment_res_num = time() . $current_user->ID;
                    $_SESSION['res_num'] = $payment_res_num;

                    if ($has_code) {
                        $_SESSION['amount_with_off'] = $final_amount;
                    }

                    $params = array(
                        'amount' =>  $final_amount,
                        'res_num' => $payment_res_num,
                        'user_id' => $current_user->ID,
                        'user_email' => '',
                        'user_mobile' => '',
                    );

                    $result = wpvip_zarinpal_request($params);

                    echo $result;
                }
            }
        } else {
            wpvip_flash_msg('error', 'انتخاب طرح الزامی می باشد');
        }
    }

    if (isset($_GET['gateway']) && !empty($_GET['gateway'])) {
        $gateway = $_GET['gateway'];
        if ($gateway == 'zarinpal') {
            $res_num = $_SESSION['res_num'];
            $payment_details = $wpdb->get_row($wpdb->prepare("SELECT * 
                                                              FROM {$table_prefix}vip_payments
                                                              WHERE payment_res_num=%s", $res_num));
            $params = array(
                'authority'  => $_GET['Authority'],
                'status'    =>  $_GET['Status'],
                'amount'   =>   isset($_SESSION['amount_with_off']) ? $_SESSION['amount_with_off'] : $payment_details->payment_amount,
                'res_num'   =>  $res_num
            );
            $result = wpvip_zarinpal_verify($params);
            if ($result['status']) {
                echo 'پرداخت شما با موفقیت انجام شد';
                echo 'شماره پیگیری شما : ' . $result['ref_id'];
            } else {
                // show error message
            }
        }
    }
    $plans = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_plans");
    ob_start();
    include WPVIP_TPL . 'user/vip_form.php';
    return ob_get_clean();
}

//Filter part of the content for vip users *
add_shortcode('wpvip_content', 'wpvip_content_callback');
function wpvip_content_callback($atts, $content = '')
{
    $args = shortcode_atts(array(
        'plan' => 0,
    ), $atts);

    if (!intval($args['plan'])) {
        return do_shortcode($content);
    }

    $planID = intval($args['plan']);

    $current_user = wp_get_current_user();
    $user_can_view_section = wpvip_check_user_by_plan($current_user->ID, $planID);

    if ($user_can_view_section) {
        return do_shortcode($content);
    }
    return '<div class="wpvip_post_content_limit">این محتوا مخصوص کاربران ویژه سایت می باشد</div>';
}

//download file shortcode *
add_shortcode('wpvip_file_dl', 'wpvip_file_download_shortcode');
function wpvip_file_download_shortcode($atts, $content = null)
{
    $args = shortcode_atts(array(
        'id' => 0,
    ), $atts);

    $file_id = intval($args['id']);

    global $wpdb, $table_prefix;
    $file_item = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_prefix}vip_files WHERE file_id=%d", $file_id));

    if (!$file_item) {
        return 'فایل معتبر نمی باشد';
    }

    $content = '<a target="_blank" href="/download/file/' . $file_item->hash_code . '" >دانلود فایل این جلسه</a>';

    return $content;
    
}
