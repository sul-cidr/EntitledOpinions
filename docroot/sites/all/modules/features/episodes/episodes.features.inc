<?php
/**
 * @file
 * episodes.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function episodes_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function episodes_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function episodes_node_info() {
  $items = array(
    'show' => array(
      'name' => t('Episode'),
      'base' => 'node_content',
      'description' => t('One recorded episode, with all its information'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
