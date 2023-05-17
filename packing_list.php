<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Packing list
Version: 1.0.0
Requires at least: 2.3.*
*/

define('PACKING_LIST_MODULE_NAME', 'packing_list');

/**
 * Initiate a new instance
 */
$CI = &get_instance();

/**
 * Loads the module function helper
 */
$CI->load->helper(PACKING_LIST_MODULE_NAME . '/packing_list');

/**
 * Activate the packing_list module
 */
register_activation_hook(PACKING_LIST_MODULE_NAME, 'packing_list_activation_hook');
function packing_list_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
    $alreadyInstalled = get_option('packing_list_active');
    if ($alreadyInstalled != "" && $alreadyInstalled != null) {
        update_option('packing_list_active', 1);
    } else {
        add_option('packing_list_active', 1);
    }
}

/**
 * Register language files, must be registered if the module is using languages
 */
//register_language_files(PACKING_LIST_MODULE_NAME, [PACKING_LIST_MODULE_NAME]);

/**
 * Deactivation module hook
 *
 *
 */
register_deactivation_hook(PACKING_LIST_MODULE_NAME, 'packing_list_deactivation_hook');
function packing_list_deactivation_hook()
{
    update_option('packing_list_active', 0, 1);
}

hooks()->add_action('admin_init', 'packing_list_project_tab');
function packing_list_project_tab()
{
    $CI = &get_instance();
    // Add new tab in customers profile
    $CI->app_tabs->add_project_tab('packing_list', [
        'name'                      => _l('Packing list'),
        'icon'                      => 'fa fa-list',
        'view'                      => PACKING_LIST_MODULE_NAME.'/project_packing_list',
        'position'                  => 10,
    ]);

}

hooks()->add_action('app_admin_footer', 'packing_list_load_js');
function packing_list_load_js(){
    if (get_option('packing_list_active') == '1') {
        $CI = &get_instance();
        echo '<script src="'.module_dir_url('packing_list', 'assets/js/packing_list.js').'?v=' . $CI->app_scripts->core_version().'"></script>';
    }
}







