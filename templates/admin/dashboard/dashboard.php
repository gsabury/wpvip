<div class="wrap">
    <h2>مدیریت کاربران ویژه</h2>
</div>

<div id="dashboard-widgets" class="metabox-holder">
    <div id="postbox-container-1" class="postbox-container">
        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
            <div id="dashboard_right_now" class="postbox">
                <button type="button" class="handlediv button-link" aria-expanded="true">
                    <span class="screen-reader-text"></span>
                    <span class="toggle-indicator" aria-hidden="true"></span>
                </button>
                <h2 class="hndle ui-sortable-handle">
                    <span>خلاصه آمار کاربران ویژه</span>
                </h2>
                <div class="inside">
                    <div class="main">
                        <p>
                            <span>تعداد کاربران ویژه :</span>
                            <span><?php echo intval($all_vip_users_count); ?></span>
                        </p>
                        <p>
                            <span>تعداد فایل های ویژه :</span>
                            <span><?php echo intval($all_vip_files_count); ?></span>
                        </p>
                    </div>
                </div>
            </div>
            <div id="dashboard_activity" class="postbox ">
                <button type="button" class="handlediv button-link" aria-expanded="true">
                    <span class="screen-reader-text">تغيير وضعيت پنل: فعاليت</span>
                    <span class="toggle-indicator" aria-hidden="true"></span>
                </button>
                <h2 class="hndle ui-sortable-handle"><span>آمار درآمد های سایت</span></h2>
                <div class="inside">
                    <p>
                        <span>مجموع پرداخت های امروز : </span>
                        <span><?php echo number_format($total_today_payments) . ' ریال' ?></span>
                    </p>
                    <p>
                        <span> مجموع پرداختی های ماه جاری : </span>
                        <span><?php echo number_format($total_month_payments) . ' ریال' ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>