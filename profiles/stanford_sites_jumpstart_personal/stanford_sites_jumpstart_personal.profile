<?php
/**
 * @file
 */

require_once "includes/autoloader.php";

/**
 * Implements hook_install_tasks().
 */

function stanford_sites_jumpstart_personal_install_tasks(&$install_state) {
  $tasks = array();
  $profile = new JumpstartSitesPersonal();
  $tasks = $profile->get_install_tasks($install_state);
  return $tasks;
}

/**
 * Implements hook_install_tasks_alter().
 */
function stanford_sites_jumpstart_personal_install_tasks_alter(&$tasks, &$install_state) {
  $profile = new JumpstartSitesPersonal();
  $profile->prepare_task_handlers($install_state);
  $profile->install_tasks_alter($tasks, $install_state);
  /**
   * Your alter code here.
   */
}

/**
 * [stanford_sites_jumpstart_personal_form_install_configure_form_alter description]
 * @param  [type] $form       [description]
 * @param  [type] $form_state [description]
 * @return [type]             [description]
 */
function stanford_sites_jumpstart_personal_form_install_configure_form_alter(&$form, &$form_state) {
  $profile_name = JumpstartProfileAbstract::get_active_profile();
  $profile = new $profile_name();
  $form = $profile->get_config_form($form, $form_state);
  return $form;
}
