<?php
add_action('admin_menu', 'wpvip_add_admin_menu');

function wpvip_add_admin_menu()
{
    add_menu_page(
                    'کاربران ویژه',
                    'کاربران ویژه',
                    'manage_options',
                    'wpvip_admin', 
                    'wpvip_dashboard', 
                    'dashicons-groups'
                );

    add_submenu_page(
                        'wpvip_admin', 
                        'داشبورد',
                        'داشبورد', 
                        'manage_options', 
                        'wpvip_admin', 
                        'wpvip_dashboard'
                    );

    add_submenu_page(
                        'wpvip_admin', 
                        'محصولات',
                        'محصولات', 
                        'manage_options', 
                        'wpvip_admin_plans', 
                        'wpvip_plans_page'
                    );

    add_submenu_page(
                        'wpvip_admin',
                        'کاربران',
                        'کاربران',
                        'manage_options',
                        'wpvip_admin_users',
                        'wpvip_users_page'
                    );

    add_submenu_page(
                        'wpvip_admin',
                        'صورت حساب ها',
                        'صورت حساب ها',
                        'manage_options', 
                        'wpvip_admin_bills', 
                        'wpvip_bills_page'
                    );

    add_submenu_page(
                        'wpvip_admin',
                        'مدیریت فایل ها',
                        'مدیریت فایل ها',
                        'manage_options',
                        'wpvip_admin_files',
                        'wpvip_files_page'
                    );

    add_submenu_page(
                        'wpvip_admin',
                        'کدهای تخفیف',
                        'کدهای تخفیف',
                        'manage_options',
                        'wpvip_admin_codes',
                        'wpvip_codes_page'
                    );

    add_submenu_page(
                        'wpvip_admin',
                        'تنظیمات',
                        'تنظیمات',
                        'manage_options',
                        'wpvip_admin_options',
                        'wpvip_admin_options'
                    );

    wpvip_add_assets();
}

function wpvip_add_assets()
{
    wp_register_style('wpvip_admin_css', WPVIP_CSS . 'admin.css');
    wp_enqueue_style('wpvip_admin_css');
}
