<?php
function wpvip_zarinpal_request($params = array())
{
    global $wpdb, $table_prefix;
    $MerchantID = 0;
    $Amount = $params['amount'] / 10; //Amount will be based on Toman - Required
    $Description = 'پرداخت برای خرید عضویت ویژه سایت فلان'; // Required
    $Email = $params['user_email']; // Optional
    $Mobile = $params['user_mobile']; // Optional
    $CallbackURL = add_query_arg(array('gateway' => 'zarinpal'), get_permalink(23));
    dd($CallbackURL);

    $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

    $result = $client->PaymentRequest(
        [
            'MerchantID'  => $MerchantID,
            'Amount'      => $Amount,
            'Description' => $Description,
            'Email'       => $Email,
            'Mobile'      => $Mobile,
            'CallbackURL' => $CallbackURL,
        ]
    );

    //Redirect to URL You can do it also by creating a form
    if ($result->Status == 100) {

        $wpdb->insert($table_prefix . 'vip_paymets', array(
            'payment_user_id' => $params['user_id'],
            'payment_amount'  => $params['amount'],
            'payment_res_num' => $params['res_num'],
            'payment_date'    => date('Y-m-d H:i:s'),
        ), array(
            '%d',
            '%d',
            '%s',
            '%s'
        ));

        header('Location: https://www.zarinpal.com/pg/StartPay/' . $result->Authority);
        exit();
        //برای استفاده از زرین گیت باید ادرس به صورت زیر تغییر کند:
        //Header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority.'/ZarinGate');
    } else {
        return $result->Status;
    }
}

function wpvip_zarinpal_verify($params = array())
{
    global $wpdb, $table_prefix;
    $MerchantID = 0;
    $Amount = $params['amount']; //Amount will be based on Toman
    $Authority = $params['authority'];
    if ($params['status'] == 'OK') {

        $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

        $result = $client->PaymentVerification(
            [
                'MerchantID' => $MerchantID,
                'Authority'  => $Authority,
                'Amount'     => $Amount,
            ]
        );

        if ($result->Status == 100) {
            $wpdb->update($table_prefix . vip_payments, array(
                'payment_ref_num' => $result->RefID,
                'status'          => 1
            ), array('payment_res_num' => $params['res_num']), array('%s', '%d'), array('%s'));
            return array(
                'status' => TRUE,
                'ref_id' => $result->RefID
            );
        } else {
            return array(
                'status'     => FALSE,
                'error_code' => $result->Status
            );
        }
    } else {
        return array(
            'status'         => FALSE,
            'cancel_by_user' => TRUE
        );
    }
}
