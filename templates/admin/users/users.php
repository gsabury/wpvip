<div class="wrap">
    <h2>مدیریت کاربران
        <a href="<?php echo  esc_url(add_query_arg(array('action' => 'new')));  ?>" class="page-title-action">اضافه کردن کاربر ویژه</a>
    </h2>
    <table class="widefat">
        <thead>
            <tr>
                <th>شناسه کاربری</th>
                <th>نام و نام خانوادگی</th>
                <th>طرح فعال</th>
                <th>تاریخ انقضاء</th>
                <th>کیف پول</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>شناسه کاربری</th>
                <th>نام و نام خانوادگی</th>
                <th>طرح فعال</th>
                <th>تاریخ انقضاء</th>
                <th>کیف پول</th>
                <th>عملیات</th>
            </tr>
        </tfoot>
        <tbody>
            <?php if (isset($vip_users) && count($vip_users) > 0): ?>
                <?php foreach ($vip_users as $vip_user): ?>
                    <tr>
                        <td><?php echo $vip_user->userID; ?></td>
                        <td><?php echo $vip_user->display_name; ?></td>
                        <td><?php echo $vip_user->plan_title; ?></td>
                        <td><span dir="ltr"><?php echo wpvip_persian_date($vip_user->expire_date); ?></span></td>
                        <td><?php echo number_format(wpvip_get_user_wallet($vip_user->userID));  ?></td>
                        <td>
                            <a
                                onclick="return confirm('برای حذف کردن اطمینان دارید؟')"
                                href="<?php echo add_query_arg(array(
                                            'action' => 'delete',
                                            'uid' => $vip_user->userID
                                        )); ?>"><span class="dashicons dashicons-trash"></span>
                            </a>

                            <a title="افزایش یا کاهش اعتبار کاربری"
                                href="<?php echo add_query_arg(array(
                                            'action' => 'change',
                                            'uid' => $vip_user->userID
                                        )); ?>">
                                <span class="dashicons dashicons-image-flip-vertical"></span>
                            </a>

                            <a title="افزایش یا کاهش موجودی"
                                href="<?php echo add_query_arg(array(
                                            'action' => 'balance',
                                            'uid' => $vip_user->userID
                                        )); ?>">
                                <span class="dashicons dashicons-plus-alt"></span>
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">هیچ رکوردی یافت نشد</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>