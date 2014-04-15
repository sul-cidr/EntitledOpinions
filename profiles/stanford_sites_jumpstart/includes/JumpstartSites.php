<?php
/**
 * @file
 * Default Installation Class for Jumpstart.
 * @author Shea McKinney <sheamck@stanford.edu>
 * @author John Bickar <jbickar@stanford.edu>
 */

/**
 * JumpStart Installation Profile Class
 */
class JumpstartSites extends JumpstartProfileAbstract {

  /**
   * This function returns additional installation tasks that need to be
   * executed in order. This should install before any child tasks although
   * a child may alter that.
   * @return array an array of tasks to be performed after all the modules have
   * been enabled and installed.
   */
  public function get_install_tasks(&$install_state) {

     // Get parent tasks.
    $parent_tasks = parent::get_install_tasks($install_state);
    $tasks = array();

    $tasks['stanford_sites_jumpstart_enable_modules'] = array(
      'display_name' => st('JumpstartSites - Enable Modules.'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'enable_modules',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_install_jumpstart_generic'] = array(
      'display_name' => st('Jumpstart Sites Setup.'),
      'display' => TRUE,
      'type' => 'normal',
      'function' => 'install_generic_jumpstart',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_install_jumpstart_users'] = array(
      'display_name' => st('Create Jumpstart Users.'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'install_jumpstart_users',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_install_jumpstart_specific'] = array(
      'display_name' => st('Jumpstart Sites Install.'),
      'display' => TRUE,
      'type' => 'normal',
      'function' => 'install_specific_jumpstart',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $this->prepare_tasks($tasks, get_class());
    return array_merge($parent_tasks, $tasks);
  }

  /**
   * Adds a jumpstart sites fieldset and configuration options to the
   * installation process.
   * @param  [array] $form       [the install configuration form]
   * @param  [array] $form_state [the form state]
   * @return [array]             [the altered form array]
   */
  public function get_config_form(&$form, &$form_state) {
    // Get parent altered configuration first.
    $form = parent::get_config_form($form, $form_state);
    return $form;
   }

  /**
   * Handles the submit hook on the configuration form.
   * @param  [array] $form       [the form array]
   * @param  [array] $form_state [the form state]
   */
  public function get_config_form_submit($form, &$form_state) {

    $jumpstart_vars = array();
    foreach ($form['jumpstart'] as $key => $value) {
      if (substr($key, 1) == "#") { continue; }
      if (!isset($value['#value'])) { continue; }
      $jumpstart_vars[$key] = check_plain($value['#value']);
    }

    variable_set('stanford_jumpstart_install', $jumpstart_vars);
  }


  // ///////////////////////////////////////////////////////////////////////////

  /**
   * Enables modules that cannot be enabled safely through the dependencies
   * stated in the .info file of profiles and other modules.
   * @param  [array] $install_state information about the curent installation.
   */
  public function enable_modules(&$install_state) {
    drupal_flush_all_caches();

    $enable_these_modules = array(
      'stanford_jumpstart_content',
    );

    module_enable($enable_these_modules);
  }

  /**
   * Base configuraiton for jumpstart sites :O
   * Set variables, enable themes, adjust blocks, and set pathologic
   * @param  [array] $install_state [the current installation state]
   */
  public function install_generic_jumpstart(&$install_state) {

    $site_name  = variable_get('site_name', "Stanford Jumpstart");

    // Variables.
    variable_set('theme_default', 'stanford_wilbur');
    variable_set('admin_theme', 'stanford_seven');
    variable_set('node_admin_theme', 'stanford_seven');
    variable_set('webauth_link_text', "SUNetID Login");
    variable_set('webauth_allow_local', 0);
    // Unset user menu as secondary links.
    variable_set('menu_secondary_links_source', "");
    variable_set('site_name', $site_name);

    // Enable themes.
    $themes = array('stanford_framework', 'stanford_wilbur', 'stanford_seven');
    theme_enable($themes);

    // This is needed here after enabling themes so that blocks get built into
    // the blocks table with the new themes.
    drupal_flush_all_caches();

    // Blocks. Turn em off.
    db_update('block')
    ->fields(array('status' => 0))
    ->condition('module', 'webauth')
    ->condition('delta', 'webauth_login_block')
    ->execute();

    db_update('block')
    ->fields(array('status' => 0))
    ->condition('module', 'system')
    ->condition('delta', 'navigation')
    ->execute();

    db_update('block')
    ->fields(array('status' => 0))
    ->condition('module', 'search')
    ->condition('delta', 'form')
    ->execute();

    db_update('block')
    ->fields(array('status' => 0))
    ->condition('module', 'stanford_sites_helper')
    ->condition('delta', 'firststeps')
    ->execute();

    db_update('block')
    ->fields(array('status' => 0))
    ->condition('module', 'stanford_sites_helper')
    ->condition('delta', 'helplinks')
    ->execute();

    db_update('block')
    ->fields(array('status' => 0))
    ->condition('module', 'user')
    ->condition('delta', 'login')
    ->execute();

    // Set pathologic Base Paths.
    global $base_url;
    $subdir = str_replace('https://sites.stanford.edu', '', $base_url) . '/';
    $settings = serialize(array(
      'local_paths' => $subdir,
      'protocol_style' => 'full',
    ));
    db_merge('filter')
      ->key(array(
        'format' => 'content_editor_text_format',
        'name' => 'pathologic',
      ))
      ->fields(array(
        'settings' => $settings,
      ))
      ->execute();

    // Revert the shortcuts feature so that the menu items
    // are in the correct place.
    features_revert(
      array(
        'stanford_jumpstart_shortcuts' => array(
          'ctools',
          'menu_links',
          'menu_custom',
        )
      )
    );

  }

  /**
   * Installs and configures the default users for jumpstart
   * @param  [array] $install_state [the current installation state]
   */
  public function install_jumpstart_users(&$install_state) {

    // Need this for UI install.
    require_once DRUPAL_ROOT . '/includes/password.inc';
    $install_vars = variable_get('stanford_jumpstart_install', array());

    // Get some stored variables.
    if ($install_state['interactive']) {
      $full_name  = isset($install_vars['full_name']) ? $install_vars['full_name'] : "";
      $sunetid    = isset($install_vars['sunetid']) ? $install_vars['sunetid'] : "";
    }
    else if (function_exists('drush_get_option')) {
      $full_name  = drush_get_option('full_name', 'Stanford Webservices');
      $sunetid    = drush_get_option('sunetid', 'webservices');
    }
    else {
      $full_name  = "Stanford Webservices";
      $sunetid    = "webservices";
    }

    // add WMD user (site owner)
    // drush waau $sunetid --name="$fullname"
    $sunet = strtolower(trim($sunetid));
    $authname = $sunet . '@stanford.edu';
    $sunet_role = user_role_load_by_name('SUNet User');
    $owner_role = user_role_load_by_name('site owner');
    $editor_role = user_role_load_by_name('editor');

    $account = new stdClass;
    $account->is_new = TRUE;
    $account->name = $full_name;
    $account->pass = user_hash_password(user_password());
    $account->mail = "sws-developers@lists.stanford.edu";
    $account->init = $authname;
    $account->status = TRUE;
    $roles = array(DRUPAL_AUTHENTICATED_RID => TRUE, $sunet_role->rid => TRUE, $owner_role->rid => TRUE);

    $account->roles = $roles;
    $account->timezone = variable_get('date_default_timezone', '');
    $account = user_save($account);
    user_set_authmaps($account, array('authname_webauth' => $authname));

    // Map itservices:webservices to administrator role
    // drush wamr itservices:webservices administrator
    module_load_include('inc', 'webauth_extras', 'webauth_extras.drush');
    drush_webauth_extras_webauth_map_role('itservices:webservices', 'administrator');

    // Create a Howard user for testing and give him the "site owner" role
    // drush ucrt Howard --password="howard" --mail="sws-developers+howard@lists.stanford.edu"
    // drush  urol "site owner" Howard

    if (isset($owner_role->rid)) {
      $howard = new stdClass;
      $howard->is_new = TRUE;
      $howard->name = 'Howard';
      $howard->pass = user_hash_password('howard');
      $howard->mail = "sws-developers+howard@lists.stanford.edu";
      $howard->init = "sws-developers+howard@lists.stanford.edu";
      $howard->status = TRUE;
      $howard_roles = array(DRUPAL_AUTHENTICATED_RID => TRUE, $owner_role->rid => TRUE);
      $howard->roles = $howard_roles;
      $howard->timezone = variable_get('date_default_timezone', '');
      $howard = user_save($howard);
    }

    // create a Lindsey user for testing and give her the "editor" role
    // drush --root=$siteroot ucrt Lindsey --password="lindsey" --mail="sws-developers+lindsey@lists.stanford.edu"
    // drush --root=$siteroot urol "editor" Lindsey

    if (isset($editor_role->rid)) {
      $lindsey = new stdClass;
      $lindsey->is_new = TRUE;
      $lindsey->name = 'Lindsey';
      $lindsey->pass = user_hash_password('lindsey');
      $lindsey->mail = "sws-developers+lindsey@lists.stanford.edu";
      $lindsey->init = "sws-developers+lindsey@lists.stanford.edu";
      $lindsey->status = TRUE;
      $lindsey_roles = array(DRUPAL_AUTHENTICATED_RID => TRUE, $editor_role->rid => TRUE);
      $lindsey->roles = $lindsey_roles;
      $lindsey->timezone = variable_get('date_default_timezone', '');
      $lindsey = user_save($lindsey);
    }

  }


  /**
   * Install Jumpstart Specific Tasks. This function will be disabled by JSA
   * and should contain JS only configuration
   * @param  [array] $install_state [the current installation state]
   * @return [type]                [description]
   */
  public function install_specific_jumpstart(&$install_state) {

    // Remove the content module. Dun need it.
    module_disable(array('stanford_jumpstart_content'));

    // Re-enable private files.
    variable_set('file_private_path', 'sites/default/files/private');

    // Install block classes:
    $fields = array('module', 'delta', 'css_class');
    $values = array(
      array("bean","social-media","span4"),
      array("bean","contact-block","span4"),
      array("bean","optional-footer-block","span4"),
      array("bean","homepage-about-block","well"),
      array("bean","flexi-block-for-the-home-page","well"),
      array("bean","jumpstart-footer-social-media-co","span4"),
      array("bean","jumpstart-footer-contact-block","span4"),
      array("bean","jumpstart-footer-social-media--0","span4"),
      array("menu","menu-admin-shortcuts-home","shortcuts-home"),
      array("menu","menu-admin-shortcuts-site-action","shortcuts-actions shortcuts-dropdown"),
      array("menu","menu-admin-shortcuts-add-feature","shortcuts-features"),
      array("menu","menu-admin-shortcuts-get-help","shortcuts-help"),
      array("menu","menu-admin-shortcuts-ready-to-la","shortcuts-launch"),
      array("stanford_jumpstart_layouts","jumpstart-launch","shortcuts-launch-block"),
      array("menu","menu-admin-shortcuts-logout-link","shortcuts-logout"),
    );

    // Key all the values.
    $insert = db_insert('block_class')->fields($fields);
    foreach ($values as $k => $value) {
      $db_values = array_combine($fields, $value);
      $insert->values($db_values);
    }
    $insert->execute();

  }

}
