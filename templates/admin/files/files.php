<div class="wrap">
    <h2>مدیریت فایل های ویژه
        <a class="page-title-action" href="<?php echo add_query_arg(array(
                                                'action' => 'new'
                                            )); ?>">ایجاد فایل جدید</a>
    </h2>
    <table class="widefat">
        <thead>
            <tr>
                <th>شناسه فایل</th>
                <th>نام فایل</th>
                <th>هش کد</th>
                <th>تعداد دانلود</th>
                <th>اندازه </th>
                <th>کد استفاده</th>
                <th>وضعیت</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>شناسه فایل</th>
                <th>نام فایل</th>
                <th>هش کد</th>
                <th>تعداد دانلود</th>
                <th>اندازه </th>
                <th>کد استفاده</th>
                <th>وضعیت</th>
            </tr>
        </tfoot>
        <tbody>
            <?php if ($all_files && count($all_files) > 0): ?>
                <?php foreach ($all_files as $file): ?>
                    <tr>
                        <td><?php echo $file->file_id; ?></td>
                        <td><?php echo $file->file_name; ?></td>
                        <td><?php echo $file->hash_code ?></td>
                        <td><?php echo $file->download_count; ?></td>
                        <td><?php echo wpvip_show_file_size($file->file_size); ?></td>
                        <td><?php echo '[wpvip_file_dl id="' . $file->file_id . '"]'; ?></td>
                        <td><?php echo $file->status; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>