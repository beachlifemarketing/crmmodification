<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Mestimates
Description: Beach Life Marketing
Version: 1.0.0
Requires at least: 2.3.*
*/

define('MESTIMATES_MODULE_NAME', 'mestimates');

$CI = &get_instance();
$CI->load->helper(MESTIMATES_MODULE_NAME . '/mestimates');
hooks()->add_action('admin_init', 'mestimates_module_init_menu_items');
hooks()->add_action('staff_member_deleted', 'mestimates_staff_member_deleted');
hooks()->add_action('admin_init', 'mestimates_permissions');

hooks()->add_filter('migration_tables_to_replace_old_links', 'mestimates_migration_tables_to_replace_old_links');
hooks()->add_filter('global_search_result_query', 'mestimates_global_search_result_query', 10, 3);
hooks()->add_filter('global_search_result_output', 'mestimates_global_search_result_output', 10, 2);


function mestimates_staff_member_deleted($data)
{
    $CI = &get_instance();
    $CI->db->where('staff_id', $data['id']);
    $CI->db->update(db_prefix() . 'mestimates', [
        'staff_id' => $data['transfer_data_to'],
    ]);
}

function mestimates_global_search_result_output($output, $data)
{
    if ($data['type'] == 'mestimates') {
        $output = '<a href="' . admin_url('mestimates/mestimate/' . $data['result']['id']) . '">' . $data['result']['subject'] . '</a>';
    }

    return $output;
}

function mestimates_global_search_result_query($result, $q, $limit)
{
    $CI = &get_instance();
    if (has_permission('mestimates', '', 'view')) {
        // Mestimates
        $CI->db->select()->from(db_prefix() . 'mestimates')->like('description', $q)->or_like('subject', $q)->limit($limit);

        $CI->db->order_by('subject', 'ASC');

        $result[] = [
            'result' => $CI->db->get()->result_array(),
            'type' => 'mestimates',
            'search_heading' => _l('mestimates'),
        ];
    }

    return $result;
}

function mestimates_migration_tables_to_replace_old_links($tables)
{
    $tables[] = [
        'table' => db_prefix() . 'mestimates',
        'field' => 'description',
    ];

    return $tables;
}

function mestimates_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('mestimates', $capabilities, _l('mestimates'));
}

/**
 * Register activation module hook
 */
register_activation_hook(MESTIMATES_MODULE_NAME, 'mestimates_module_activation_hook');

function mestimates_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(MESTIMATES_MODULE_NAME, [MESTIMATES_MODULE_NAME]);

/**
 * Init mestimates module menu items in setup in admin_init hook
 * @return null
 */
function mestimates_module_init_menu_items()
{
    if (is_admin()) {
        $CI = &get_instance();
        $CI->app_menu->add_sidebar_menu_item('api-options', [
            'collapse' => true,
            'name' => _l('mestimate'),
            'position' => 40,
            'icon' => 'fa fa-cogs',
        ]);
        $CI->app_menu->add_sidebar_children_item('api-options', [
            'slug' => 'api-register-options',
            'name' => 'M-Estimate - Manage',
            'href' => admin_url('mestimates'),
            'position' => 5,
        ]);
        $CI->app_menu->add_sidebar_children_item('api-options', [
            'slug' => 'api-register-options',
            'name' => 'Templates - Manage',
            'href' => admin_url('mestimates/template'),
            'position' => 6,
        ]);
    }
}

