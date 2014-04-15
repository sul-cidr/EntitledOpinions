<?php
/**
 * @file
 */
require_once "includes/autoloader.php";


/**
 * Implements hook_install_tasks().
 */
function stanford_sites_jumpstart_academic_install_tasks(&$install_state) {
  $tasks = array();
  $profile = new JumpstartSitesAcademic();
  $tasks = $profile->get_install_tasks($install_state);
  return $tasks;
}

/**
 * Implements hook_install_tasks_alter().
 * @param array $tasks an array of installation tasks
 * @param array $install_state the current installation state
 */
function stanford_sites_jumpstart_academic_install_tasks_alter(&$tasks, &$install_state) {
  $profile = new JumpstartSitesAcademic();
  $profile->prepare_task_handlers($install_state);
  $profile->install_tasks_alter($tasks, $install_state);
}

/**
 * Implements form_install_configure_form_alter.
 * Allows all dependants to alter the configuration form.
 * @param  [array] $form       [the form array]
 * @param  [array] $form_state [the form state array]
 */
function stanford_sites_jumpstart_academic_form_install_configure_form_alter(&$form, &$form_state) {
  $profile_name = JumpstartProfileAbstract::get_active_profile();
  $profile = new $profile_name();
  $form = $profile->get_config_form($form, $form_state);
  return $form;
}
