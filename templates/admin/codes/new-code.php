<div class="wrap">
    <h2>
        ایجاد کد تخفیف جدید
        <a class="page-title-action" href="<?php echo admin_url('admin.php?page=wpvip_admin_codes') ?>">لیست کدهای تخفیف</a>
    </h2>
    <form action="" method="post">
        <table class="form-table">
            <tr valign="top">
                <th scope="row">درصد کد تخفیف : </th>
                <td>
                    <input type="text" name="percent">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> تعداد روز برای تاریخ انقضاء: </th>
                <td>
                    <input type="text" name="expire_days">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php submit_button('ذخیره ') ?>
                </td>
            </tr>
        </table>
    </form>
</div>