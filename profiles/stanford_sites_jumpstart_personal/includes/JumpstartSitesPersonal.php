
<?php
/**
 * @file
 * Stanford Jumpstart Personal Installation Profile.
 *
 *
 */

/**
 * JumpStart Installation Profile Class
 */
class JumpstartSitesPersonal extends JumpstartProfileAbstract {

  /**
   * Required function.
   * These install tasks are run in order from the very first profile (stanford)
   * all the way down to this one. They are run after all the dependency modules
   * have been enabled.
   * @param array $install_state the current state of the installation.
   * @return array an array of installation tasks.
   */
  public function get_install_tasks(&$install_state) {

    // With this line we can choose to use all of the parents install tasks and
    // to run them before this profile. If this line is removed then none of the
    // installation tasks of the parents will be run and you can do whatever you
    // want from there.
    $parent_tasks = parent::get_install_tasks($install_state);

    // Sample task declaration differs from the normal task api slightly.
    $tasks['stanford_sites_jumpstart_personal_install'] = array(
      'display_name' => st('My Profile Install Task'),
      'display' => TRUE,
      'type' => 'normal',
      'function' => 'install', // The name of the method in this class to run.
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_personal_create_users'] = array(
      'display_name' => st('Create Site Owner'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'create_users', // The name of the method in this class to run.
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_personal_import_content'] = array(
      'display_name' => st('Import Content'),
      'display' => TRUE,
      'type' => 'normal',
      'function' => 'import_content', // The name of the method in this class to run.
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_personal_import_menu'] = array(
      'display_name' => st('Import Main Menu'),
      'display' => TRUE,
      'type' => 'normal',
      'function' => 'import_menu', // The name of the method in this class to run.
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    // CAP TASKS
    // -------------------------------------------------------------------------

    // 1. Add cap settings
    // 2. Sync Fields
    // 3. Fetch profile

    $tasks['stanford_sites_jumpstart_personal_cap_configure'] = array(
      'display_name' => st('Configure CAP'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'cap_configure', // The name of the method in this class to run.
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    if (variable_get('stanford_sites_personal_cap_ok', FALSE)) {

      // $tasks['stanford_sites_jumpstart_personal_sync_cap'] = array(
      //   'display_name' => st('Syncronise CAP Date'),
      //   'display' => TRUE,
      //   'type' => 'normal',
      //   'function' => 'sync_with_cap', // The name of the method in this class to run.
      //   'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
      // );

      $tasks['stanford_sites_jumpstart_personal_cap_fetch'] = array(
        'display_name' => st('Fetch CAP Profile'),
        'display' => TRUE,
        'type' => 'normal',
        'function' => 'cap_fetch', // The name of the method in this class to run.
        'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
      );

    }
    else {

      // INSTALL BACKUP PLAN.

      $tasks['stanford_sites_jumpstart_personal_install_feature'] = array(
        'display_name' => st('Install Feature Instead of CAP'),
        'display' => TRUE,
        'type' => 'normal',
        'function' => 'install_stanford_person_feature', // The name of the method in this class to run.
        'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
      );

    }


    // -------------------------------------------------------------------------

    $tasks['stanford_sites_jumpstart_personal_disable_modules'] = array(
      'display_name' => st('Disable Modules'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'disable_modules', // The name of the method in this class to run.
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_personal_install_block_classes'] = array(
      'display_name' => st('Install Block Classes'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'install_block_classes', // The name of the method in this class to run.
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_personal_finished'] = array(
      'display_name' => st('Finish Install'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'install_finished', // The name of the method in this class to run.
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    // Drupal does some fun things to run install tasks so we have to do some
    // extra work to ensure that they are run. Use this function to process your
    // new tasks. Do not pass in parent tasks as they have already been
    // processed once and do not need to be processed again.
    $this->prepare_tasks($tasks, get_class());

    // Return both your and the parent tasks as one array.
    return array_merge($parent_tasks, $tasks);
  }

  /**
   * Required function.
   * This function allows you to maninpulate the installation form as well as
   * the parent profiles to make changes.
   * @param  $form  The form array
   * @param  $form  The form state array
   * @return array the form array
   */
  public function get_config_form(&$form, &$form_state) {

    // Get all parent profile changes and additions to the configuration form.
    $form = parent::get_config_form($form, $form_state);

    $form['stanford_sites_jumpstart_personal'] = array(
      '#type' => 'fieldset',
      '#title' => 'CAP API Configuration',
      '#description' => 'Cap configuration settings.',
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );

    $form['stanford_sites_jumpstart_personal']['capapi_username'] = array(
      '#type' => 'textfield',
      '#title' => 'CAP API Username',
      '#description' => 'Please enter the username to authenticate with the CAP API.',
      '#default_value' => isset($form_state['values']['capapi_username']) ? $form_state['values']['capapi_username'] : '',
    );

    $form['stanford_sites_jumpstart_personal']['capapi_password'] = array(
      '#type' => 'password',
      '#title' => 'CAP API Password',
      '#description' => 'Please enter the password to authenticate with the CAP API.',
      '#default_value' => isset($form_state['values']['capapi_password']) ? $form_state['values']['capapi_password'] : '',
    );

    return $form;

  }

  /**
   * Disable some modules.
   * @return [type] [description]
   */
  public function disable_modules(&$install_state) {
    $modules = array('dashboard', 'search');
    module_disable($modules, FALSE);
    drush_log('JSP - Finished disable modules task.', 'status');
  }

  /**
   * Installation task as defined in get_install_tasks()
   * Sets some configuration
   * @param  array $install_state the current installation state
   * @return null
   */
  public function install(&$install_state) {
    drush_log('JSP - Start install task.', 'status');
    // In here you have full access to everything Drupal. Beware of caches
    // registry and paths as they may not be available or correct. To ensure
    // some normallity it may be useful to flush all the caches first.

    // Set variables.
    $requester_name = variable_get('stanford_sites_requester_name', 'Welcome [edit me]');
    variable_set('site_name', $requester_name);

    // Variables.
    variable_set('theme_default', 'stanford_light');
    variable_set('admin_theme', 'stanford_seven');
    variable_set('node_admin_theme', 'stanford_seven');
    variable_set('webauth_link_text', "SUNetID Login");
    variable_set('webauth_allow_local', 0);
    // Disable user pictures on account page
    variable_set('user_pictures', 0);

    // Set temp directory.
    file_directory_temp();

    // Unset user menu as secondary links.
    variable_set('menu_secondary_links_source', "");

    // Temporary until we do something else.
    // variable_set('site_frontpage', 'node/1');

    // This variable is set in the stanford installation profile and causes
    // havoc when installing through drush. Re-enable later.
    variable_del('file_private_path');

    // Turn off client side caching in admin_menu as it doesnt work.
    variable_set('admin_menu_cache_client', 0);

    // Enable themes.
    $themes = array(
      'stanford_framework',
      'stanford_light',
      'stanford_seven',
      'open_framework'
    );

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

    drush_log('JSP - Finished install task.', 'status');
  }

  /**
   * Creates the user accounts for this install.
   * @return [type] [description]
   */
  public function create_users(&$install_state) {
    drush_log('JSP - Start create_users task.', 'status');

    $owner_role = user_role_load_by_name('site owner');
    $sunet_role = user_role_load_by_name('SUNet User');

    $sunetid = isset($install_state['forms']['install_configure_form']['stanford_sites_requester_sunetid']) ? $install_state['forms']['install_configure_form']['stanford_sites_requester_sunetid'] : 'webservices';
    $full_name = isset($install_state['forms']['install_configure_form']['stanford_sites_requester_name']) ? $install_state['forms']['install_configure_form']['stanford_sites_requester_name'] : 'Stanford Webservies';
    $email = isset($install_state['forms']['install_configure_form']['stanford_sites_requester_email']) ? $install_state['forms']['install_configure_form']['stanford_sites_requester_email'] : $sunetid . "@stanford.edu";

    // CREATE SITE OWNER USER!
    // ----------------------------------------------------------
    $sunet = strtolower(trim($sunetid));
    $authname = $sunet . '@stanford.edu';

    // Try to load up the user.
    $account = user_load_by_name($full_name);

    // If no user create one.
    if (!$account) {
      $account = new stdClass;
      $account->is_new = TRUE;
      $account->name = $full_name;
      $account->pass = user_hash_password(user_password());
      $account->mail = $email;
      $account->init = $authname;
      $account->status = TRUE;
    }
    // Change the roles for the user.
    $roles = array(DRUPAL_AUTHENTICATED_RID => TRUE);

    // Add site owner role by default.
    if ($owner_role) {
      $roles[$owner_role->rid] = TRUE;
    }

    // Add sunet role if available.
    if ($sunet_role) {
      $roles[$sunet_role->rid] = TRUE;
    }

    $account->roles = $roles;
    $account->timezone = variable_get('date_default_timezone', '');
    $account = user_save($account);

    // ----------------------------------------------------------

    drush_log('JSP - Finished create_users task.', 'status');
  }

  /**
   * Import content from the content server.
   * @param  [type] $install_state [description]
   * @return [type]                [description]
   */
  public function import_content(&$install_state) {
    drush_log('JSP - Start import_content task.', 'status');

    // Load up the jumpstart profile and use its import function.
    $this->load_sites_content_importer_files($install_state);

    // @TODO: no hard code of this.
    $endpoint = "https://sites.stanford.edu/jsa-content/jsainstall";

    // Now that the library exists lets add our own custom processors.
    require_once "ImporterFieldProcessorCustomFieldSDestinationPublish.php";
    require_once "ImporterFieldProcessorCustomBody.php";
    require_once "ImporterPropertyProcessorTrimAlias.php";

    // Do not import these!
    $restrict = array(
      'tags',              // tags vocabulary
      'sites_products',    // products vocabulary
      'news_categories',
      'publication_type',
      'stanford_event_audience',
      'stanford_event_categories',
      'stanford_slide_category',
    );

    // Vocab Pull.
    $importer = new SitesContentImporter();
    $importer->set_endpoint($endpoint);
    $importer->add_restricted_vocabularies($restrict);
    $importer->import_vocabulary_trees();

    // Bean Pull.
    $uuids = array(
      'e51a076f-dc84-4d28-8eb8-828bc67ec0e2', // Jumpstart Footer Social Media Connect Block
      'd6312ea0-d128-4805-ad0e-fa712aa1ac40', // Stanford Personal Node Edit Help Block
      'a0188c23-cd48-4886-a1a1-15d198e5329d', // Stanford Personal Footer Block
      'd08151ab-2808-4569-9e9a-e977c2ba57c4', // Stanford Personal Sidebar Block
    );

    $importer->set_bean_uuids($uuids);
    $importer->import_content_beans();

    // Content Pull.
    $filters = array('sites_products' => array('37'));  // 37 is term id for jsp
    $view_importer = new SitesContentImporterViews();
    $view_importer->set_endpoint($endpoint);
    $view_importer->set_resource('content');
    $view_importer->set_filters($filters);
    $view_importer->add_property_processor(array('url_alias' => 'ImporterPropertyProcessorTrimAlias'));
    $view_importer->import_content_by_views_and_filters();

    drush_log('JSP - Finished install task.', 'status');
  }

  /**
   * Import the main menu.
   * @return [type] [description]
   */
  public function import_menu() {
    drush_log('JSP - Starting import_menu task.', 'status');
    // Install menus.
    module_load_include('inc', 'menu_import', 'includes/import');
    $content_path = drupal_get_path('profile', 'stanford_sites_jumpstart_personal') . "/includes/menus/";
    $options = array(
      'create_content'  => FALSE,
      'link_to_content' => TRUE,
      'remove_menu_items' => TRUE,
    );

    // Import the menu items.
    $result = menu_import_file($content_path . 'main-menu-export.txt', 'main-menu', $options);

    // If we are done importing menus then we can disable the module.
    module_disable(array('menu_import'));

    drush_log('JSP - Finished import_menu task.', 'status');
  }

  /**
   * Install a bunch of block classes.
   * @return [type] [description]
   */
  public function install_block_classes() {
    drush_log('JSP - Started install_block_classes task.', 'status');

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
      array("bean","jumpstart-footer-social-media--0","well"),
      array("bean","stanford-personal-sidebar-block","well"),
      array("menu","menu-admin-shortcuts-home","shortcuts-home"),
      array("menu","menu-admin-shortcuts-site-action","shortcuts-actions shortcuts-dropdown"),
      array("menu","menu-admin-shortcuts-add-feature","shortcuts-features"),
      array("menu","menu-admin-shortcuts-get-help","shortcuts-help"),
      array("menu","menu-admin-shortcuts-ready-to-la","shortcuts-launch"),
      array("stanford_jumpstart_layouts","jumpstart-launch","shortcuts-launch-block"),
      array("menu","menu-admin-shortcuts-logout-link","shortcuts-logout"),
      array("bean","social-media-connect-block","well"),
      array("views","profile_blocks-block","well"),
    );

    // Key all the values.
    $insert = db_insert('block_class')->fields($fields);
    foreach ($values as $k => $value) {
      $db_values = array_combine($fields, $value);
      $insert->values($db_values);
    }
    $insert->execute();

    drush_log('JSP - Finished install_block_classes task.', 'status');
  }

  // CAP TASKS
  // ---------------------------------------------------------------------------

  /**
   * Attempts to connect with the CAP API service.
   *  $username = "drupal-hackathon";
   *  $password = "WP3q9Cdytt2K@SD";
   */
  public function cap_configure(&$install_state) {
    drush_log('JSP - Started cap_configure task.', 'status');

    $auth_uri = "https://authz.stanford.edu/oauth/token";
    $config_form_data = $install_state['forms']['install_configure_form'];
    $username = isset($config_form_data['capapi_username']) ? $config_form_data['capapi_username'] : "";
    $password = isset($config_form_data['capapi_password']) ? $config_form_data['capapi_password'] : "";

    // Save to DB.
    variable_set('stanford_cap_api_username', $username);
    variable_set('stanford_cap_api_password', $password);

    // Test the connection with the CAP API service.
    $auth_token = stanford_cap_api_auth($username, $password, $auth_uri);

    // If we cannot authenticate for any reason we should stop trying to get
    // information from the CAP API and carry on with plan B.
    if (!$auth_token) {
      watchdog('JumpstartSitesPersonal', 'Could not authenticate with CAP API.', WATCHDOG_ERROR);
      // END HERE WITHOUT SETTING THE cap_ok variable and the install tasks will
      // skip installing through cap and install the stanford_person option.
      return;
    }

    // All things are good.
    variable_set('stanford_sites_personal_cap_ok', TRUE);
    drush_log('JSP - Finished cap_configure task.', 'status');
  }

  /**
   * Syncs with the CAP API schema so that we may have a content type with
   * fields to put data in.
   * @param array $install_state installation state.
   */
  public function sync_with_cap(&$install_state) {
    drush_log('JSP - Started sync_with_cap task.', 'status');
    // module_load_include('inc', 'stanford_cap_api', 'stanford_cap_api.admin');

    // if (!stanford_cap_api_auth_status()) {
    //   variable_set('stanford_sites_personal_cap_ok', FALSE);
    //   throw new Exception("Could not connect to CAP API");
    // }

    // Sync without batch.
    // $form_state['values'] = array();
    // drupal_form_submit('stanford_cap_api_settings_form', $form_state);

    // drupal_form_submit('stanford_cap_api_details_form', $form_state);
    // stanford_cap_api_profiles_settings_form_submit($form, $form_state);

    // Before we go about syncing things lets check to see if a
    // profile is available.

    // $full_name = isset($install_state['forms']['install_configure_form']['stanford_sites_requester_name']) ? $install_state['forms']['install_configure_form']['stanford_sites_requester_name'] : '';
    // if (empty($full_name)) {
    //   watchdog('JumpstartSitesPersonal', 'Full Name was empty. Did not import profile.', WATCHDOG_NOTICE);
    //   $this->uninstall_cap_stuff($install_state);
    //   $this->install_stanford_person_feature($install_state);
    //   return;
    // }

    // $form = array();
    // $form_state['values'] = array();
    // $form_state['values']['name'] = $full_name;

    // drupal_form_submit('stanford_cap_api_profiles_import_form', $form_state);
    // stanford_cap_api_profiles_import_result_form($form, $form_state);

    // if (!isset($_SESSION['search_response']['values'][0])) {
    //   watchdog('JumpstartSitesPersonal', 'Did not import profile as profile not available.', WATCHDOG_NOTICE);
    //   $this->uninstall_cap_stuff($install_state);
    //   $this->install_stanford_person_feature($install_state);
    //   return;
    // }

    // Request schema data
    // $schema = stanford_cap_api_profiles_get_profile_schema();
    // if (!$schema) {
    //   watchdog('JumpstartSitesPersonal', 'Could not acquire field schema data.', WATCHDOG_NOTICE);
    //   $this->uninstall_cap_stuff($install_state);
    //   $this->install_stanford_person_feature($install_state);
    //   return;
    // }

    // Synchronize schema data
    // stanford_cap_api_profiles_synchronize_schema();

    // stanford_cap_api_profiles_get_orgcode_fields();
    // stanford_cap_api_orgs_import();

    drush_log('JSP - Finished sync_with_cap task.', 'status');
  }

  /**
   * Fetch the profile and create it!
   * While fetching the profile the cap module will sync the correct fields to
   * the content type.
   * @return [type] [description]
   */
  public function cap_fetch(&$install_state) {
    drush_log('JSP - Started cap_fetch task.', 'status');

    module_load_include('inc', 'stanford_cap_api', 'stanford_cap_api.admin');

    $form_state = array();
    $form = array();
    $sunetid = isset($install_state['forms']['install_configure_form']['stanford_sites_requester_sunetid']) ? $install_state['forms']['install_configure_form']['stanford_sites_requester_sunetid'] : '';

    // Ensure we have a sunetid or we cannot get person.
    if (empty($sunetid)) {
      watchdog('JumpstartSitesPersonal', 'SunetID was empty. Did not import profile.', WATCHDOG_NOTICE);
      $this->uninstall_cap_stuff($install_state);
      $this->install_stanford_person_feature($install_state);
      return;
    }

    // Look on the CAP api for a public profile that matches the name.
    $form_state['values'] = array();
    $form_state['values']['sunet_id'] = $sunetid;
    drupal_form_submit('stanford_cap_api_profiles_import_form', $form_state);
    stanford_cap_api_profiles_import_result_form($form, $form_state);

    // Check to see if there are results.
    $profile = FALSE;
    if (isset($_SESSION['search_response']['values'][0])) {
      $profile = $_SESSION['search_response']['values'][0];
    }
    else {
      // No results? Then kick over to the stanford_person feature.
      watchdog('JumpstartSitesPersonal', 'Did not import profile as profile not available.', WATCHDOG_NOTICE);
      $this->uninstall_cap_stuff($install_state);
      $this->install_stanford_person_feature($install_state);
      return;
    }

    // Import the profile.
    stanford_cap_api_profiles_profile_import($profile['profileId']);

    // If all went well then we should add the block in place.
    module_enable(array('stanford_personal_cap_profile_block'));

    drush_log('JSP - Finished cap_fetch task.', 'status');
  }

  // ---------------------------------------------------------------------------

  /**
   * Disable and Uninstall cap modules.
   * @param  [type] $install_state [description]
   * @return [type]                [description]
   */
  public function uninstall_cap_stuff(&$install_state) {
    $disable = array(
      'stanford_cap_api',
      'stanford_cap_api_orgs',
      'stanford_cap_api_profiles',
      'stanford_cap_api_profiles_layout',
      'stanford_personal_cap_settings',
    );
    module_disable($disable, FALSE);
    drupal_uninstall_modules($disable, FALSE);
  }

  // ---------------------------------------------------------------------------

  /**
   * Installs and configures the person feature. Used as a backup plan for when
   * CAP Data is unavailable.
   * @return [type] [description]
   */
  public function install_stanford_person_feature(&$install_state) {
    drush_log('JSP - Starting install_stanford_person_feature', 'status');
    $modules = array('stanford_person');
    module_enable($modules);

    // Import Jane Doe from the content server

    // @TODO: no hard code of this.
    $endpoint = "https://sites.stanford.edu/jsa-content/jsainstall";
    $ids = array('985a66ed-f74f-4427-b56a-4a634bbc9e96' => array()); // Jane Doe
    $importer = new SitesContentImporter();
    $importer->set_endpoint($endpoint);
    $importer->importer_process_nodes_by_uuids($ids);

    drush_log('JSP - Finished install_stanford_person_feature', 'status');
  }

  // ---------------------------------------------------------------------------

  /**
   * Final Tasks before done.
   * @param  [type] $install_state [description]
   * @return [type]                [description]
   */
  public function install_finished(&$install_state) {
    // Set the home page to the imported home page.
    variable_set('site_frontpage', drupal_get_normal_path('home'));
  }

}
