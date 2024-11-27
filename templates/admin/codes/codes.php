<div class="wrap">
    <h2>مدیریت کدهای تخفیف
        <a class="page-title-action" href="<?php echo add_query_arg(array(
                                                'action' => 'new'
                                            )); ?>">ایجاد کد تخفیف جدید</a>
    </h2>
    <table class="widefat">
        <thead>
            <tr>
                <th>شناسه کد</th>
                <th>کد تخفیف</th>
                <th>درصد تخفیف</th>
                <th>تاریخ انقضاء</th>
                <th>تعداد محدودیت </th>
                <th>وضعیت</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>شناسه کد</th>
                <th>کد تخفیف</th>
                <th>درصد تخفیف</th>
                <th>تاریخ انقضاء</th>
                <th>تعداد محدودیت </th>
                <th>وضعیت</th>
            </tr>
        </tfoot>
        <tbody>
            <?php if ($all_codes && count($all_codes) > 0): ?>
                <?php foreach ($all_codes as $code): ?>
                    <tr>
                        <td><?php echo $code->code_ID; ?></td>
                        <td><?php echo $code->code_hash; ?></td>
                        <td><?php echo $code->code_percent ?></td>
                        <td><?php echo $code->code_expire_date; ?></td>
                        <td><?php echo $code->code_count_limit; ?></td>
                        <td><?php echo $code->code_status; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>