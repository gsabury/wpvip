<tr valign="top">
    <th scope="row">
        فعال بودن افزونه
    </th>
    <td>
        <input type="checkbox" name="wpvip_is_active" <?php isset($wpvip_options['general']['wpvip_is_active']) ? checked(1, $wpvip_options['general']['wpvip_is_active']) : ''; ?>>
    </td>
</tr>
<tr valign="top">
    <th scope="row">
        پیام انقضای اکانت از چند روز قبل ارسال شود ؟
    </th>
    <td>
        <input type="text" name="notification_day_before" value="<?php echo isset($wpvip_options['general']['notification_day_before'])
                                                                        ? intval($wpvip_options['general']['notification_day_before']) : ''; ?>">
    </td>
</tr>
<tr valign="top">
    <th scope="row">
        دسته بندی ساید بار :
    </th>
    <td>
        <select name="sidebar_category" id="sidebarCat">
            <?php foreach ($sidebar_category as $cat): ?>
                <option value="<?php echo $cat->term_id; ?>" <?php echo (isset($wpvip_options['general']['wpvip_sidebar_cat']) && $wpvip_options['general']['wpvip_sidebar_cat'] == $cat->term_id) ? "selected" : ''; ?>><?php echo $cat->name; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>
<tr valign="top">
    <th scope="row">
        متن ایمیل ارسالی برای کاربران ویژه جدید :
    </th>
    <td>
        <?php wp_editor(isset($wpvip_options['general']['wpvip_new_users_email']) ? $wpvip_options['general']['wpvip_new_users_email'] : '', 'wpvip_new_users_email', array('media_buttons' => false)); ?>
    </td>
</tr>