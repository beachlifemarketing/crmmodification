<?php

defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
/*
Module Name: Ìventory
Description: Inventory module by Beach Life Marketing
Version: 1.0.0
Requires at least: 2.3.*
*/

define('INVENTORY_MODULE_NAME', 'inventory');
$CI->load->helper(INVENTORY_MODULE_NAME . '/inventory');
hooks()->add_action('admin_init', 'inventory_module_init_menu_items');
hooks()->add_action('admin_init', 'inventory_permissions');

function inventory_permissions(){
	$capabilities = [];

	$capabilities['capabilities'] = [
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];

	register_staff_capabilities('inventory', $capabilities, _l('inventory'));
}

/**
 * Register activation module hook
 */
register_activation_hook(INVENTORY_MODULE_NAME, 'inventory_module_activation_hook');

function inventory_module_activation_hook(){
	require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(INVENTORY_MODULE_NAME, [INVENTORY_MODULE_NAME]);

/**
 * Init inventory module menu items in setup in admin_init hook
 * @return null
 */
function inventory_module_init_menu_items(){
	$CI = &get_instance();

	$CI->app->add_quick_actions_link([
		'name' => _l('Inventory'),
		'url' => 'inventory',
		'permission' => 'inventory',
		'position' => 56,
	]);


	if (has_permission('inventory', '', 'view')) {
		$CI = &get_instance();
		$CI->app_menu->add_sidebar_menu_item('inventory', [
			'collapse' => true,
			'name' => _l('Inventory'),
			'position' => 40,
			'icon' => 'fa fa-cogs',
		]);
		$CI->app_menu->add_sidebar_children_item('inventory', [
			'slug' => 'manufactures',
			'name' => 'Manufacture - Manage',
			'href' => admin_url('inventory/manufactures'),
			'position' => 5,
		]);
		$CI->app_menu->add_sidebar_children_item('inventory', [
			'slug' => 'products',
			'name' => 'Product - Manage',
			'href' => admin_url('inventory/products'),
			'position' => 6,
		]);

		$CI->app_menu->add_sidebar_children_item('inventory', [
			'slug' => 'orders',
			'name' => 'Orders - Manage',
			'href' => admin_url('inventory/orders'),
			'position' => 7,
		]);
	}
}
