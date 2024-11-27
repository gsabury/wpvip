<div class="wrap">
    <h2>اضافه کردن کاربر جدید
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
                <th scope="row">شناسه کاربری</th>
                <td>
                    <select name="user_id" id="user_id">
                        <option value="0">لطفا انتخاب کنید ...</option>
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user->ID ?>"><?php echo $user->display_name; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">شناسه طرح</th>
                <td>
                    <select name="plan_id" id="plan_id">
                        <option value="0">لطفا انتخاب کنید ...</option>
                        <?php if (count($plans) > 0): ?>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?php echo $plan->plan_ID ?>"><?php echo $plan->title; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">تعداد روز اعتبار</th>
                <td>
                    <input type="text" name="credit" value="1">
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>