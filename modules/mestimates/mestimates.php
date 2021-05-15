<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Mestimates
Description: Default module for defining mestimates
Version: 2.3.0
Requires at least: 2.3.*
*/

define('MESTIMATES_MODULE_NAME', 'mestimates');

$CI = &get_instance();
$CI->load->helper(MESTIMATES_MODULE_NAME . '/mestimates');
hooks()->add_action('after_cron_run', 'mestimates_notification');
hooks()->add_action('admin_init', 'mestimates_module_init_menu_items');
hooks()->add_action('staff_member_deleted', 'mestimates_staff_member_deleted');
hooks()->add_action('admin_init', 'mestimates_permissions');

hooks()->add_filter('migration_tables_to_replace_old_links', 'mestimates_migration_tables_to_replace_old_links');
hooks()->add_filter('global_search_result_query', 'mestimates_global_search_result_query', 10, 3);
hooks()->add_filter('global_search_result_output', 'mestimates_global_search_result_output', 10, 2);
hooks()->add_filter('get_dashboard_widgets', 'mestimates_add_dashboard_widget');

function mestimates_add_dashboard_widget($widgets)
{
    $widgets[] = [
        'path' => 'mestimates/widget',
        'container' => 'right-4',
    ];

    return $widgets;
}

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

function mestimates_notification()
{
    $CI = &get_instance();
    $CI->load->model('mestimates/mestimates_model');
    $mestimates = $CI->mestimates_model->get('', true);
    foreach ($mestimates as $mestimate) {
        $achievement = $CI->mestimates_model->calculate_mestimate_achievement($mestimate['id']);

        if ($achievement['percent'] >= 100) {
            if (date('Y-m-d') >= $mestimate['end_date']) {
                if ($mestimate['notify_when_achieve'] == 1) {
                    $CI->mestimates_model->notify_staff_members($mestimate['id'], 'success', $achievement);
                } else {
                    $CI->mestimates_model->mark_as_notified($mestimate['id']);
                }
            }
        } else {
            // not yet achieved, check for end date
            if (date('Y-m-d') > $mestimate['end_date']) {
                if ($mestimate['notify_when_fail'] == 1) {
                    $CI->mestimates_model->notify_staff_members($mestimate['id'], 'failed', $achievement);
                } else {
                    $CI->mestimates_model->mark_as_notified($mestimate['id']);
                }
            }
        }
    }
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
            'name' => _l('mestimates'),
            'href' => admin_url('mestimates'),
            'position' => 5,
        ]);
    }
}


/**
 * Get mestimate types for the mestimates feature
 *
 * @return array
 */
function get_mestimate_types()
{
    $types = [
        [
            'key' => 1,
            'lang_key' => 'mestimate_type_total_income',
            'subtext' => 'mestimate_type_income_subtext',
            'dashboard' => has_permission('invoices', 'view'),
        ],
        [
            'key' => 8,
            'lang_key' => 'mestimate_type_invoiced_amount',
            'subtext' => '',
            'dashboard' => has_permission('invoices', 'view'),
        ],
        [
            'key' => 2,
            'lang_key' => 'mestimate_type_convert_leads',
            'dashboard' => is_staff_member(),
        ],
        [
            'key' => 3,
            'lang_key' => 'mestimate_type_increase_customers_without_leads_conversions',
            'subtext' => 'mestimate_type_increase_customers_without_leads_conversions_subtext',
            'dashboard' => has_permission('customers', 'view'),
        ],
        [
            'key' => 4,
            'lang_key' => 'mestimate_type_increase_customers_with_leads_conversions',
            'subtext' => 'mestimate_type_increase_customers_with_leads_conversions_subtext',
            'dashboard' => has_permission('customers', 'view'),

        ],
        [
            'key' => 5,
            'lang_key' => 'mestimate_type_make_contracts_by_type_calc_database',
            'subtext' => 'mestimate_type_make_contracts_by_type_calc_database_subtext',
            'dashboard' => has_permission('contracts', 'view'),
        ],
        [
            'key' => 7,
            'lang_key' => 'mestimate_type_make_contracts_by_type_calc_date',
            'subtext' => 'mestimate_type_make_contracts_by_type_calc_date_subtext',
            'dashboard' => has_permission('contracts', 'view'),
        ],
        [
            'key' => 6,
            'lang_key' => 'mestimate_type_total_estimates_converted',
            'subtext' => 'mestimate_type_total_estimates_converted_subtext',
            'dashboard' => has_permission('estimates', 'view'),
        ],
    ];

    return hooks()->apply_filters('get_mestimate_types', $types);
}

/**
 * Get mestimate type by given key
 *
 * @param int $key
 *
 * @return array
 */
function get_mestimate_type($key)
{
    foreach (get_mestimate_types() as $type) {
        if ($type['key'] == $key) {
            return $type;
        }
    }
}

/**
 * Translate mestimate type based on passed key
 *
 * @param mixed $key
 *
 * @return string
 */
function format_mestimate_type($key)
{
    foreach (get_mestimate_types() as $type) {
        if ($type['key'] == $key) {
            return _l($type['lang_key']);
        }
    }

    return $type;
}
