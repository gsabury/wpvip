<div class="wrap">
    <h2>تغییر موجودی کاربران
        <a href="<?php echo admin_url('admin.php?page=wpvip_admin_users') ?>" class="page-title-action"> برگشت به کاربران</a>
    </h2>

    <?php if ($success): ?>
        <div class="notice updated">
            <p><?php echo $message; ?></p>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="notice error">
            <p><?php echo $message; ?></p>
        </div>
    <?php endif; ?>
    
    <form action="" method="post">
        <table class="form-table wpvip_table">
            <tr valign="top">
                <th scope="row">نوع تغییر : </th>
                <td>
                    <select name="type" id="type">
                        <option value="0">لطفا انتخاب کنید ...</option>
                        <option value="1">افزایش موجودی </option>
                        <option value="2">کاهش موجودی </option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">مبلغ : </th>
                <td>
                    <input type="text" name="amount" value="50000">
                </td>
            </tr>
        </table>
        <input type="hidden" name="uid" value="<?php echo $uid; ?>">
        <?php submit_button(); ?>
    </form>
</div>