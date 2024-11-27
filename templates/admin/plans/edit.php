<div class="wrap">
    <h2>اضافه کردن محسول جدید
        <a href="<?php echo  admin_url('admin.php?page=wpvip_admin_plans');  ?>" class="page-title-action">لیست محصولات</a>
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
        <table class="form-table">
            <tr valign="top">
                <th scope="row">عنوان محصول: </th>
                <td>
                    <input type="text" name="title" value="<?php echo  isset($plan_edit) ? $plan_edit->title : ""; ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> قیمت: </th>
                <td>
                    <input type="text" name="price" value="<?php echo  isset($plan_edit) ? $plan_edit->price : ""; ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> اعتبار روزانه : </th>
                <td>
                    <input type="text" name="credit" value="<?php echo  isset($plan_edit) ? $plan_edit->credit : ""; ?>">
                </td>
            </tr>
        </table>
        <?php if (isset($plan_edit)) :  ?>
            <input type="hidden" name="plan_ID" value="<?php echo $plan_edit->plan_ID; ?>">
        <?php endif; ?>
        <?php submit_button(); ?>
    </form>
</div>