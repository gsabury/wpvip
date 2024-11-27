<?php

// Return type of charges
function wpvip_get_bill_type($status)
{
    if (intval($status) == 1)
        return 'واریز';
    if (intval($status) == 2)
        return 'برداشت';
}

// Add options in admin horizontal menu
function wpvip_add_admin_bar_menus()
{
    global $wp_admin_bar;

    $wp_admin_bar->add_menu(array(
        'id' => 'wpvip-menu',
        'title' => 'کاربران ویژه',
        'href' => admin_url('admin.php?page=wpvip_admin'),
        'meta' => array()
    ));

    $wp_admin_bar->add_menu(array(
        'parent' => 'wpvip-menu',
        'id' => 'wpvip-menu-dashboard',
        'title' => 'داشبورد',
        'href' => admin_url('admin.php?page=wpvip_admin'),
    ));

    $wp_admin_bar->add_menu(array(
        'parent' => 'wpvip-menu',
        'id' => 'wpvip-menu-products',
        'title' => 'محصولات',
        'href' => admin_url('admin.php?page=wpvip_admin_plans'),
    ));

    $wp_admin_bar->add_menu(array(
        'parent' => 'wpvip-menu',
        'id' => 'wpvip-menu-users',
        'title' => 'کاربران',
        'href' => admin_url('admin.php?page=wpvip_admin_users'),
    ));

    $wp_admin_bar->add_menu(array(
        'parent' => 'wpvip-menu',
        'id' => 'wpvip-menu-bills',
        'title' => 'صورت حساب ها',
        'href' => admin_url('admin.php?page=wpvip_admin_bills'),
    ));

    $wp_admin_bar->add_menu(array(
        'parent' => 'wpvip-menu',
        'id' => 'wpvip-menu-files',
        'title' => 'مدیریت فایل ها',
        'href' => admin_url('admin.php?page=wpvip_admin_files'),
    ));

    $wp_admin_bar->add_menu(array(
        'parent' => 'wpvip-menu',
        'id' => 'wpvip-menu-discount-codes',
        'title' => 'کدهای تخفیف',
        'href' => admin_url('admin.php?page=wpvip_admin_codes'),
    ));

    $wp_admin_bar->add_menu(array(
        'parent' => 'wpvip-menu',
        'id' => 'wpvip-menu-settings',
        'title' => 'تنظیمات',
        'href' => admin_url('admin.php?page=wpvip_admin_options'),
    ));
}

add_action('admin_bar_menu', 'wpvip_add_admin_bar_menus', 100);
