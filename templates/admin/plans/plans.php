<div class="wrap">
    <h2>مدیریت محصولات
        <a href="<?php echo  esc_url(add_query_arg(array('action' => 'edit')));  ?>" class="page-title-action">محصول جدید</a>
    </h2>

    <table class="widefat">
        <thead>
            <tr>
                <th>عنوان محصول</th>
                <th>قیمت</th>
                <th>اعتبار روزانه</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>عنوان محصول</th>
                <th>قیمت</th>
                <th>اعتبار روزانه</th>
                <th>عملیات</th>
            </tr>
        </tfoot>
        <tbody>
            <?php if (isset($plans) && count($plans) > 0): ?>
                <?php foreach ($plans as $plan): ?>
                    <tr>
                        <td><?php echo $plan->title; ?></td>
                        <td><?php echo number_format($plan->price) . ' ریال'; ?></td>
                        <td><?php echo $plan->credit; ?></td>
                        <td>
                            <a href="<?php echo add_query_arg(array(
                                            'action' => 'edit',
                                            'item_id' => $plan->plan_ID
                                        )); ?>">
                                        <span class="dashicons dashicons-edit"></span>
                            </a>
                            <a href="<?php echo add_query_arg(array(
                                            'action' => 'delete',
                                            'item_id'  => $plan->plan_ID
                                        )); ?>" onclick="return confirm('برای حذف این آیتم اطمینان دارید؟')">
                                        <span class="dashicons dashicons-trash"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">هیچ رکوردی یافت نشد</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>