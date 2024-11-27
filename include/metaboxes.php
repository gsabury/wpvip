<?php
// Create Metabox
add_action('add_meta_boxes', 'wpvip_set_content_plan');
function wpvip_set_content_plan()
{
    add_meta_box('wpvip_set_content_plan', 'انتخاب این محتوای برای بخش VIP', 'wpvip_content_plan_callback', 'post');

}

// Create the dropdown for plans
function wpvip_content_plan_callback($post)
{
    $postID = $post->ID;
    global $wpdb, $table_prefix;
    $plans = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_plans");

    $is_current_post_vip = intval(get_post_meta($postID, 'plan', true));

    include WPVIP_TPL . 'admin/metaboxes/content_plan.php';
}

// save_post hook is called whenever the post is published or updated
add_action('save_post', 'wpvip_set_content_plan_save');
function wpvip_set_content_plan_save($postID)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (! current_user_can('edit_post', $postID)) {
        return;
    }

    if (isset($_POST['wpvip_plan'])) {
        $plan = intval($_POST['wpvip_plan']);
        update_post_meta($postID, 'plan', $plan);
    }
}
