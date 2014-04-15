<?php
/**
 * @file
 * Default Installation Class for JumpstartAcademic.
 * Child profile of JumpstartSites. Allows for additional configuration.
 *
 * @author Shea McKinney <sheamck@stanford.edu>
 * @author John Bickar <jbickar@stanford.edu>
 */

/**
 * JumpStart Installation Profile Class
 */
class JumpstartSitesAcademic extends JumpstartSites {

  /**
   * Returns all of the tree's install tasks in order from (first) top most
   * parent to (last) bottom most child.
   * @return [array] [an array of installation tasks]
   */
  public function get_install_tasks(&$install_state) {

    // Get parent tasks.
    $parent_tasks = parent::get_install_tasks($install_state);

    // Remove some parent tasks.
    // Jumpstart adds content to the site that is different from JSA. Lets
    // disable those modules and add in only the ones we want again.
    unset($parent_tasks['JumpstartSites_stanford_sites_jumpstart_enable_modules']);
    unset($parent_tasks['JumpstartSites_stanford_sites_jumpstart_install_jumpstart_specific']);

    // Add My Tasks.
    $tasks['stanford_sites_jumpstart_academic_disable_modules'] = array(
      'display_name' => st('Disable Modules'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'disable_modules',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_academic_enable_modules'] = array(
      'display_name' => st('Enable Modules'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'enable_modules',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_revert_all_features'] = array(
      'display_name' => st('Revert All Features.'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'revert_all_features',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_academic_deploy_content'] = array(
      'display_name' => st('Deploy Content'),
      'display' => TRUE,
      'type' => 'normal',
      'function' => 'deploy_content',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_academic_install_academic'] = array(
      'display_name' => st('Configure Jumpstart Academic'),
      'display' => TRUE,
      'type' => 'normal',
      'function' => 'install',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_academic_position_menus'] = array(
      'display_name' => st('Position Imported Menus'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'position_menus',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $tasks['stanford_sites_jumpstart_academic_roles_perms'] = array(
      'display_name' => st('Configure Roles and Permissions'),
      'display' => FALSE,
      'type' => 'normal',
      'function' => 'configure_roles_perms',
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    );

    $this->prepare_tasks($tasks, get_class());
    return array_merge($parent_tasks, $tasks);
  }

  /**
   * Implements hook_form_alter()
   * Modifies and alters the configuration form.
   * @return array the form array
   */
  public function get_config_form(&$form, &$form_state) {

    // Get parent altered configuration first.
    $my_form = parent::get_config_form($form, $form_state);

    // $my_form['jumpstart_academic'] = array(
    //   '#type' => 'fieldset',
    //   '#title' => 'Jumpstart Academic Configuration',
    //   '#description' => 'Jumpstart Sites Academic Configuration Options',
    //   '#collapsible' => TRUE,
    //   '#collapsed' => FALSE,
    // );

    // $my_form['jumpstart_academic']['deploy_password'] = array(
    //   '#type' => 'textfield',
    //   '#title' => 'Content Deployment Password',
    //   '#description' => 'Stanford Sites Environment Only: Please enter the password for the content deployment.',
    //   '#default_value' => isset($form_state['values']['deploy_password']) ? $form_state['values']['deploy_password'] : '',
    // );

    // $my_form['jumpstart_academic']['fetch_endpoint'] = array(
    //   '#type' => 'textfield',
    //   '#title' => 'Content Endpoint',
    //   '#description' => 'Expert Only: If you are installing outside of the stanford sites environment the installation will attempt to fetch content from a remote server. To change the location of that remote server edit this field. Otherwise, leave this alone.',
    //   '#default_value' => isset($form_state['values']['fetch_endpoint']) ? $form_state['values']['fetch_endpoint'] : 'https://sites.stanford.edu/jsa-content/jsainstall',
    // );

    return $my_form;
  }

  /**
   * [get_config_form_submit description]
   * @param  [type] $form       [description]
   * @param  [type] $form_state [description]
   * @return [type]             [description]
   */
  public function get_config_form_submit($form, &$form_state) {
    parent::get_config_form_submit($form, $form_state);
    $vars = variable_get('stanford_jumpstart_install', array());
    $vars['fetch_endpoint'] = check_plain($form['jumpstart_academic']['fetch_endpoint']['#value']);
    variable_set('stanford_jumpstart_install', $vars);
  }


  //  //////////////////////////////////////////////////////////////////////////

  /**
   * Disable modules if they are present.
   * @param  [array] $install_state [the current installation state]
   */
  public function disable_modules(&$install_state) {

    $disable_these_modules = array(
      'stanford_jumpstart_roles',
    );

    // Disable and uninstall only the modules we want. Not the dependants.
    module_disable($disable_these_modules, FALSE);
    drupal_uninstall_modules($disable_these_modules, FALSE);
  }

  /**
   * Enable some additional modules that didnt make it into the dependencies.
   * @param  [array] $install_state [the current installation state]
   */
  public function enable_modules(&$install_state) {
    drupal_flush_all_caches();

    // Turn jsa content on and off to get it's content into db.
    module_enable(
      array(
        'stanford_jsa_content',
        )
      );

    module_disable(array('stanford_jsa_content'), FALSE);
  }

  /**
   * A wack of configuration code. Variables, blocks, views and other settings.
   * @param  [array] $install_state [the current installation state]
   */
  public function install(&$install_state) {
    drush_log('Starting Academic Install.', 'status');

    static $drupal_static_fast;
    $drupal_static_fast = array();
    drupal_static_reset();
    drupal_flush_all_caches();

    // @todo: get this to configure page.
    $shortname = 'sites.stanford.edu';
    $home = drupal_lookup_path('source', 'home');
    $page_not_found = drupal_lookup_path('source', '404');

    // Variables.
    variable_set('stanford_jumpstart_academic', TRUE);
    variable_set('stanford_jsa_endpoint', $shortname);
    variable_set('site_frontpage', $home);
    variable_set('site_404', $page_not_found);
    # Set menu position default setting to 'mark the rule's parent menu item as being "active".'
    variable_set('menu_position_active_link_display', 'parent');

    // Turn views into more developer like.
    module_load_include('inc', 'views', 'drush/views.drush');
    views_development_settings();

    // Block clases.
    $fields = array('module', 'delta', 'css_class');
    $values = array(
      array("bean","jumpstart-home-page-about","well"),
      array("bean","homepage-banner-image","block-no-bottom-margin"),
      array("bean","jumpstart-affiliated-programs","well"),
      array("bean","jumpstart-contact-us-postcard","well"),
      array("bean","jumpstart-degree-programs-info-f","well"),
      array("bean","jumpstart-featured-course","well"),
      array("bean","jumpstart-featured-event","well"),
      array("bean","jumpstart-featured-event-block","well"),
      array("bean","jumpstart-footer-contact-block","span2"),
      array("bean","jumpstart-footer-social-media--0","span2"),
      array("bean","jumpstart-footer-social-media-co","span4"),
      array("bean","jumpstart-graduate-student-sideb","well"),
      array("bean","jumpstart-home-page-academics","well"),
      array("bean","jumpstart-info-for-current-gra-0","span4 well"),
      array("bean","jumpstart-info-for-current-gra-1","span4 well"),
      array("bean","jumpstart-info-for-current-gradu","span4 well"),
      array("bean","jumpstart-info-for-current-und-0","span4 well"),
      array("bean","jumpstart-info-for-current-und-1","span4 well"),
      array("bean","jumpstart-info-for-current-under","span4 well"),
      array("bean","jumpstart-info-for-prospective-0","span4 well"),
      array("bean","jumpstart-info-for-prospective-1","span4 well"),
      array("bean","jumpstart-info-for-prospective-g","span4 well"),
      array("bean","jumpstart-twitter-block","well"),
      array("bean","jumpstart-why-i-teach","well"),
      array("bean","jumpstart-why-i-teach-block","well"),
      array("bean","optional-footer-block","span4"),
      array("bean","social-media","span4"),
      array("ds_extras","contact","well"),
      array("ds_extras","office_hours","well"),
      array("menu","menu-admin-shortcuts-add-feature","shortcuts-features"),
      array("menu","menu-admin-shortcuts-get-help","shortcuts-help"),
      array("menu","menu-admin-shortcuts-home","shortcuts-home"),
      array("menu","menu-admin-shortcuts-logout-link","shortcuts-logout"),
      array("menu","menu-admin-shortcuts-ready-to-la","shortcuts-launch"),
      array("menu","menu-admin-shortcuts-site-action","shortcuts-actions shortcuts-dropdown"),
      array("menu","menu-footer-news-events-menu","span2"),
      array("menu","menu-footer-people-menu","span2"),
      array("menu","menu-footer-academics-menu","span2"),
      array("menu","menu-footer-about-menu","span2"),
      array("stanford_jumpstart_layouts","jumpstart-launch","shortcuts-launch-block"),
      array("views","-exp-publications-page","well"),
      array("views","f73ff55b085ea49217d347de6630cd5a","well"),
      array("views","jumpstart_current_user-block","shortcuts-user"),
      array("views","publications_common-block_4","well"),
      array("views","stanford_news-block","well"),
      array("views","stanford_events_views-block","well"),
      array("views","-exp-stanford_person_staff-page","well"),
      array("views","-exp-stanford_news-page_1","well"),
      array("views","-exp-courses-search_page","well"),
      array("views","442e92af913370af5bffd333a036ceaa","well"),
      array("views","b38da907588eed2d09c10bdb381e5aaf","well"),
      array("views","4066d038591af2b511f66557e5ac41e8","well"),
      array("views","2d9147be40cd77d32915a554bf315858","well"),
      array("views","85c57f65aa0dee37d8aa5a5031e564bc","well"),
      array("views","5c84bdc5ea8289bceed723799d38940f","well"),
    );

    // Key all the values.
    $insert = db_insert('block_class')->fields($fields);
    foreach ($values as $k => $value) {
      $db_values = array_combine($fields, $value);
      $insert->values($db_values);
    }
    $insert->execute();

    drush_log('Finished installing block classes.', 'ok');

    // Create redirects.
    $redirects = array(
      'academics' => 'academics/academics-overview',
      'people' => 'people/faculty',
      'news' => 'news/recent-news',
      'events' => 'events/upcoming-events',
      'about' => 'about/about-us',
    );

    foreach ($redirects as $source => $dest) {
      $redirect = new stdClass();
      $source_path = drupal_lookup_path('source', $source);

      if ($source_path == FALSE || $source_path == "<front>" || $source_path == "home") {
        $source_path = $source;
      }

      if (drupal_lookup_path('source', $dest)) {
        $dest = drupal_lookup_path('source', $dest);
      }

      // Check to see if redirect exists first.
      $found = redirect_load_by_source($source_path);
      if (!empty($found)) {
        // Redirect exists.
        continue;
      }

      module_invoke(
        'redirect',
        'object_prepare',
        $redirect,
        array(
          'source' => $source_path,
          'source_options' => array(),
          'redirect' => $dest,
          'redirect_options' => array(),
          'language' => LANGUAGE_NONE,
        )
      );

      if ($source_path !== $dest) {
        module_invoke('redirect', 'save', $redirect);
      }

    }

    drush_log('Finished installing redirects.', 'ok');

    // Install menus.
    module_load_include('inc', 'menu_import', 'includes/import');
    $content_path = drupal_get_path('profile', 'stanford_sites_jumpstart_academic') . "/includes/menus/";
    $options = array(
      'create_content'  => FALSE,
      'link_to_content' => TRUE,
      'remove_menu_items' => TRUE,
    );

    // Menu imports process does not find any paths from views defined in
    // features at this point. We will need to make it aware of them before
    // trying to import the menus links. Menu import uses drupal_valid_path() in
    // order to determine if a path is valid. drupal_valid_path() looks into the
    // menu router table for paths. At this point the menu_router table does not
    // have any views urls.

    // menu_rebuild(); Doesnt work. Nice try!

    // Load up and save all views to the db.
    $implements = module_implements('views_default_views');
    $views = array();
    foreach ($implements as $module_name) {
      $views += call_user_func($module_name . "_views_default_views");
    }

    // Initialize! Alive!
    foreach ($views as $view) {
      $view->save();
    }

    drush_log('Saved Views For Menu Import.', 'ok');

    // Import the menu items.
    $result = menu_import_file($content_path . 'main-menu-export.txt', 'main-menu', $options);
    drush_log('Imported Main Menu', 'ok');
    $result = menu_import_file($content_path . 'menu-footer-about-menu.txt', 'menu-footer-about-menu', $options);
    drush_log('Imported Footer About Menu', 'ok');
    $result = menu_import_file($content_path . 'menu-footer-academics-menu.txt', 'menu-footer-academics-menu', $options);
    drush_log('Imported Footer Academics Menu', 'ok');
    $result = menu_import_file($content_path . 'menu-footer-news-events-menu.txt', 'menu-footer-news-events-menu', $options);
    drush_log('Imported Footer News Menu', 'ok');
    $result = menu_import_file($content_path . 'menu-footer-people-menu.txt', 'menu-footer-people-menu', $options);
    drush_log('Imported Footer People Menu', 'ok');

    // If we are done importing menus then we can disable the module.
    module_disable(array('menu_import'));

    drush_log('Finished installing menus.', 'ok');

    drush_log('Finished academic install task.', 'ok');
  }

  /**
   * Puts the menus in place after import.
   * @param  [array] $install_state [the current installation state]
   */
  public function position_menus(&$install_state) {

    // Define the rules.
    $rules = array();
    $rules[] = array(
      'link_title' => 'About',
      'admin_title' => 'About by path',
      'conditions' => array(
        'pages' => array(
          'pages' => 'about/*',
        ),
      ),
    );
    $rules[] = array(
      'link_title' => 'Academics',
      'admin_title' => 'Academics by path',
      'conditions' => array(
        'pages' => array(
          'pages' => 'academics/*',
        ),
      ),
    );
    $rules[] = array(
      'link_title' => 'News',
      'admin_title' => 'News by content type',
      'conditions' => array(
        'content_type' => array(
          'content_type' => array(
            'stanford_news_item' => 'stanford_news_item',
          ),
        ),
      ),
    );
    $rules[] = array(
      'link_title' => 'News',
      'admin_title' => 'News by path',
      'conditions' => array(
        'pages' => array(
          'pages' => 'news/*',
        ),
      ),
    );
    $rules[] = array(
      'link_title' => 'Events',
      'admin_title' => 'Events by content type',
      'conditions' => array(
        'content_type' => array(
          'content_type' => array(
            'stanford_event' => 'stanford_event',
          ),
        ),
      ),
    );
    $rules[] = array(
      'link_title' => 'Events',
      'admin_title' => 'Events by path',
      'conditions' => array(
        'pages' => array(
          'pages' => 'events/*',
        ),
      ),
    );
    $rules[] = array(
      'link_title' => 'People',
      'admin_title' => 'People by content type',
      'conditions' => array(
        'content_type' => array(
          'content_type' => array(
            'stanford_person' => 'stanford_person',
          ),
        ),
      ),
    );
    $rules[] = array(
      'link_title' => 'People',
      'admin_title' => 'People by path',
      'conditions' => array(
        'pages' => array(
          'pages' => 'people/*',
        ),
      ),
    );
    $rules[] = array(
      'link_title' => 'Publications',
      'admin_title' => 'Publications by content type',
      'conditions' => array(
        'content_type' => array(
          'content_type' => array(
            'stanford_publication' => 'stanford_publication',
          ),
        ),
      ),
    );
    $vocabulary = taxonomy_vocabulary_machine_name_load('stanford_faculty_type');
    $vid = $vocabulary->vid;
    $rules[] = array(
      'link_title' => 'People',
      'admin_title' => 'Faculty by taxonomy',
      'conditions' => array(
        'taxonomy' => array(
          'vid' => $vid,
          'tid' => array(),
        ),
      ),
    );
    $vocabulary = taxonomy_vocabulary_machine_name_load('stanford_staff_type');
    $vid = $vocabulary->vid;
    $rules[] = array(
      'link_title' => 'People',
      'admin_title' => 'Staff by taxonomy',
      'conditions' => array(
        'taxonomy' => array(
          'vid' => $vid,
          'tid' => array(),
        ),
      ),
    );
    $vocabulary = taxonomy_vocabulary_machine_name_load('stanford_student_type');
    $vid = $vocabulary->vid;
    $rules[] = array(
      'link_title' => 'People',
      'admin_title' => 'Students by taxonomy',
      'conditions' => array(
        'taxonomy' => array(
          'vid' => $vid,
          'tid' => array(),
        ),
      ),
    );
    $vocabulary = taxonomy_vocabulary_machine_name_load('news_categories');
    $vid = $vocabulary->vid;
    $rules[] = array(
      'link_title' => 'News',
      'admin_title' => 'News by taxonomy',
      'conditions' => array(
        'taxonomy' => array(
          'vid' => $vid,
          'tid' => array(),
        ),
      ),
    );

    foreach ($rules as $mp_rule) {
      $this->insert_menu_rule($mp_rule);
    }

    drush_log('Finished menu position task.', 'ok');

  }

  /**
   * Create new Menu Position Rule.
   * @param array $mp_rules A multidimensional array with the following keys:
   * 'link_title' : Link title in the Main Menu. Assuming depth of 1
   * 'admin_title' : Administrative title of the Menu Position rule. Human-readable.
   * 'conditions' : multidimensional array of Menu Position conditions
   */
  private function insert_menu_rule($mp_rule) {
    module_load_include('inc', 'menu_position', 'menu_position.admin');

    // Get the mlid of the parent link.
    $result = db_select('menu_links', 'm')
    ->fields('m', array('mlid'))
    ->condition('menu_name', 'main-menu')
    ->condition('depth', 1)
    ->condition('link_title', $mp_rule['link_title'])
    ->execute()
    ->fetchAssoc();

    $plid = $result['mlid'];


    // Create the array to populate the rule.
    $rule = array(
      'admin_title' => $mp_rule['admin_title'],
  /*    'conditions' => array(
        'content_type' => array(
          'content_type' => array(
            'stanford_news_item' => 'stanford_news_item',
          ),
        ),
      ),*/
      'conditions' => $mp_rule['conditions'],
      'menu_name' => 'main-menu',
      'plid' => $plid,  // "News" item in main menu. Need to look this up programatically
    );

    // Calling menu_position_add_rule here because we can assume that no rules have been added.
    menu_position_add_rule($rule);
  }


  /**
   * [deploy_content description]
   * @param  [array] $install_state [the current installation state]
   * @return [type]                [description]
   */
  public function deploy_content(&$install_state) {
    $time = time();
    drush_log('Starting Jumpstart Academic Content Import. Time: ' . $time, 'ok');

    // Try to use libraries module if available to find the path.
    if (function_exists('libraries_get_path')) {
      $library_path = DRUPAL_ROOT . '/' . libraries_get_path('stanford_sites_content_importer');
    }
    if (!file_exists($library_path . '/SitesContentImporter.php')) {
      $library_path = DRUPAL_ROOT . '/sites/all/libraries/stanford_sites_content_importer';
    }
    if (!file_exists($library_path . '/SitesContentImporter.php')) {
      $library_path = DRUPAL_ROOT . '/sites/default/libraries/stanford_sites_content_importer';
    }
    $library_path .= "/SitesContentImporter.php";
    include_once $library_path;

    // Now that the library exists lets add our own custom processors.
    require_once "ImporterFieldProcessorCustomFieldSDestinationPublish.php";
    require_once "ImporterFieldProcessorCustomBody.php";

    $this->fetch_jsa_content();

    $time_diff = time() - $time;

    drush_log('Finished importing content. Import took: ' . $time_diff . ' seconds' , 'ok');
  }

  /**
   * Gets content from jsa-content site through rest json
   * @return [type] [description]
   */
  private function fetch_jsa_content() {
    $vars = variable_get('stanford_jumpstart_install', array());
    $endpoint = (!empty($vars['fetch_endpoint'])) ? $vars['fetch_endpoint'] : 'https://sites.stanford.edu/jsa-content/jsainstall';

    // Allow drush to ping servers.
    ini_set('allow_url_fopen', 1);

    // Get Vocabs and terms.
    $this->fetch_jsa_content_vocabularies($endpoint);
    drush_log('Finished importing vocabularies.', 'ok');

    // Fetch JSA Beans.
    $this->fetch_jsa_content_beans($endpoint);
    drush_log('Finished importing beans.', 'ok');

    // Get Nodes.
    $this->fetch_jsa_content_nodes($endpoint);
    drush_log('Finsiehd importing nodes.', 'ok');

  }

  /**
   * [fetch_jsa_content_vocabularies description]
   * @param  [type] $endpoint [description]
   * @return [type]           [description]
   */
  private function fetch_jsa_content_vocabularies($endpoint) {

    $importer = new SitesContentImporter();
    $importer->set_endpoint($endpoint);
    $importer->import_vocabulary_trees();

  }

  /**
   * [fetch_jsa_content_beans description]
   * @param  [type] $endpoint [description]
   * @return [type]           [description]
   */
  private function fetch_jsa_content_beans($endpoint) {

    $uuids = array(
      //  'e813c236-7400-4f43-ad18-736617ceb28e',  // Jumpstart Home Page Banner Image.
      '806b3a6e-4225-4ff7-8b8f-d96fd054280a',
      '178acfee-6423-4817-af05-5533d2e95e3f',
      //  'b66a5774-d0d1-44eb-abda-7aa8ea4eea0e',  // Home page about block.
      //  '27620187-f60a-4f85-b1d1-8c03e1eab40c',  // Home page academic block.
      '8dc5934a-ee22-4c48-a125-d78ce3293ffa',
      '60ecdc50-e649-4433-b936-0228aa32fc55',
      '080e08c1-0ef7-4102-8d2e-ebb1a6444513',
      'b7334d9f-b4d4-48ba-b1b5-e3ec7b9bb100',
      'ba352284-7aec-4044-a6dc-7e60441c2ccf',
      '67045bcc-06fc-4db8-9ef4-dd0ebb4e6d72',
      '61b6f7f7-5b94-4112-b69c-07240da330f8',
      '05f729cf-a05c-446a-96ce-324237e2a5db',
      '5ee82af2-bfac-4584-a006-a0fb0661af34',
      //  '44f527d1-08ab-4ade-b3f6-a57a97987b40',  // Why I teach block.
    );

    $importer = new SitesContentImporter();
    $importer->set_endpoint($endpoint);
    $importer->set_bean_uuids($uuids);
    $importer->import_content_beans();

  }


  /**
   * [fetch_jsa_content_nodes description]
   * @param  [type] $endpoint [description]
   * @return [type]           [description]
   */
  private function fetch_jsa_content_nodes($endpoint) {

    // Import types
    $content_types = array(
      // 'stanford_page', // only tagged content from now on.
      'stanford_event',
      'stanford_event_importer',
      'article',
      'stanford_person',
      'stanford_publication',
      'stanford_news_item',
      'stanford_course',
//      'stanford_course_importer',
    );

    // Restrictions
    // These entities we do not want even if they appear in the feed.
    $restrict = array(
      '2efac412-06d7-42b4-bf75-74067879836c',   // Recent News Page
      'fcbec50-0449-4e2d-8a79-3f957bf101e9',    // News item
      'ea1a02a9-0564-4448-82f3-09fb1d0ae8c1',   // news item
      '86765dd9-e282-4fb2-b4a1-da3a268b5e17',   // News item
      '985a66ed-f74f-4427-b56a-4a634bbc9e96',   // jane doe
      '5041c58e-efea-4dee-9dc4-628323328264',   // Jacob smith
      'fc7574ff-fce8-4e3a-bef6-0e20e85f1cc7',   // Emily Jordan
      'b8e7f735-93e3-4717-8208-e9b0baff5dc4',   // Haley Jackson
      '2918cbb6-a8a7-4cd8-9ae0-b773c1341dd1',   // Publication 1
      '873208ee-507c-412f-bbc1-533ba259c923',   // Publication 2
      '485c2104-6590-4e55-9821-c39632231fed',   // Publication 3
      '6d48181f-7387-40e8-81ba-199de7ede938',   // Courses Page.
    );

    $importer = new SitesContentImporter();
    $importer->set_endpoint($endpoint);
    $importer->add_import_content_type($content_types);
    $importer->add_uuid_restrictions($restrict);
    $importer->importer_content_nodes_recent_by_type();

    // JSA ONLY CONTENT
    $filters = array('sites_products' => array('33'));  // 33 is term id for jsa
    $view_importer = new SitesContentImporterViews();
    $view_importer->set_endpoint($endpoint);
    $view_importer->set_resource('content');
    $view_importer->set_filters($filters);
    $view_importer->import_content_by_views_and_filters();

  }

  /**
   * Reverts all features because they need to.
   * @param  [type] $install_state [description]
   * @return [type]                [description]
   */
  public function revert_all_features(&$install_state) {
    drush_log('Reverting all of the features.', 'status');
    features_revert();
  }

  /**
   * [configure_roles_perms description]
   * @param  [type] $install_state [description]
   * @return [type]                [description]
   */
  public function configure_roles_perms(&$install_state) {
    // Expire modules cache and rebuild registry.
    drupal_static_reset('registry_rebuild');

    static $drupal_static_fast;
    $drupal_static_fast['implementations'] = &drupal_static('module_implements');
    $drupal_static_fast['implementations'] = FALSE;

    registry_rebuild();

    module_enable(array('stanford_jsa_roles'));

  }


}
