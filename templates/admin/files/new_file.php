<div class="wrap">
    <h2>
        ایجاد فایل جدید
        <a class="page-title-action" href="<?php echo admin_url('admin.php?page=wpvip_admin_files') ?>">مشاهده همه فایل ها</a>
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
    
    <form action="" method="post" enctype="multipart/form-data">
        <table class="form-table">
            <tr valign="top">
                <th scope="row">فایل خود را انتخاب کنید:</th>
                <td>
                    <input type="file" name="file">
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