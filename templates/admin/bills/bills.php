<div class="wrap">
    <h2>مدیریت صورت حساب های کاربران</h2>
    <table class="widefat">
        <thead>
            <tr>
                <th>کاربر</th>
                <th>نوع تراکنش</th>
                <th>مبلغ</th>
                <th>تاریخ</th>
                <th>موجودی</th>
                <th>توضیحات</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>کاربر</th>
                <th>نوع تراکنش</th>
                <th>مبلغ</th>
                <th>تاریخ</th>
                <th>موجودی</th>
                <th>توضیحات</th>
            </tr>
        </tfoot>
        <tbody>
            <?php if ($bills && count($bills) > 0): ?>
                <?php foreach ($bills as $bill): ?>
                    <tr>
                        <td><?php echo $bill->display_name; ?></td>
                        <td><?php echo wpvip_get_bill_type($bill->type); ?></td>
                        <td><?php echo number_format($bill->amount) . ' ریال'; ?></td>
                        <td><span dir="ltr"><?php echo wpvip_persian_date($bill->date); ?></span></td>
                        <td><?php echo number_format($bill->balance) . ' ریال';  ?></td>
                        <td><?php echo $bill->description; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">اطلاعاتی یافت نشد</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>