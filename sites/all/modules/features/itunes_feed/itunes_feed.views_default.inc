<?php
/**
 * @file
 * itunes_feed.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function itunes_feed_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'itunes_feed';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'iTunes Feed';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Entitled Opinions';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Content: Path */
  $handler->display->display_options['fields']['path']['id'] = 'path';
  $handler->display->display_options['fields']['path']['table'] = 'node';
  $handler->display->display_options['fields']['path']['field'] = 'path';
  $handler->display->display_options['fields']['path']['absolute'] = TRUE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Content: Date */
  $handler->display->display_options['fields']['field_date']['id'] = 'field_date';
  $handler->display->display_options['fields']['field_date']['table'] = 'field_data_field_date';
  $handler->display->display_options['fields']['field_date']['field'] = 'field_date';
  $handler->display->display_options['fields']['field_date']['label'] = '';
  $handler->display->display_options['fields']['field_date']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_date']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['field_date']['type'] = 'date_plain';
  $handler->display->display_options['fields']['field_date']['settings'] = array(
    'format_type' => 'itunes_formatted',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
    'show_remaining_days' => 0,
  );
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  /* Field: Content: About Guest */
  $handler->display->display_options['fields']['field_about_guest']['id'] = 'field_about_guest';
  $handler->display->display_options['fields']['field_about_guest']['table'] = 'field_data_field_about_guest';
  $handler->display->display_options['fields']['field_about_guest']['field'] = 'field_about_guest';
  $handler->display->display_options['fields']['field_about_guest']['type'] = 'text_plain';
  /* Field: Content: Episode MP3 */
  $handler->display->display_options['fields']['field_link_to_download']['id'] = 'field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download']['table'] = 'field_data_field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download']['field'] = 'field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download']['label'] = 'Audio';
  $handler->display->display_options['fields']['field_link_to_download']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_link_to_download']['type'] = 'enclosure';
  /* Field: Content: Episode MP3 */
  $handler->display->display_options['fields']['field_link_to_download_1']['id'] = 'field_link_to_download_1';
  $handler->display->display_options['fields']['field_link_to_download_1']['table'] = 'field_data_field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download_1']['field'] = 'field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download_1']['label'] = 'Audio';
  $handler->display->display_options['fields']['field_link_to_download_1']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_link_to_download_1']['type'] = 'itunes:duration';
  /* Field: Content: Episode MP3 */
  $handler->display->display_options['fields']['field_link_to_download_2']['id'] = 'field_link_to_download_2';
  $handler->display->display_options['fields']['field_link_to_download_2']['table'] = 'field_data_field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download_2']['field'] = 'field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download_2']['label'] = 'Audio';
  $handler->display->display_options['fields']['field_link_to_download_2']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_link_to_download_2']['type'] = 'file_url_plain';
  /* Sort criterion: Content: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'show' => 'show',
  );

  /* Display: Feed */
  $handler = $view->new_display('feed', 'Feed', 'feed_1');
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['style_plugin'] = 'rss_fields';
  $handler->display->display_options['style_options']['channel'] = array(
    'core' => array(
      'views_rss_core' => array(
        'description' => 'With its high-powered, long-form conversations, Entitled Opinions has fans around the globe, from Australia to China, Mexico to Russia. Its host, Robert Pogue Harrison, has interviewed some of our era\'s leading figures in literature, philosophy, science, and cultural history for over a decade. One blogger called these intellectually expansive exchanges “the most fascinating, engaging podcasts in any possible universe.”',
        'language' => 'english',
        'category' => '',
        'image' => 'https://entitledopinions.stanford.edu/sites/default/files/field/image/robert.jpg',
        'copyright' => '',
        'managingEditor' => 'harrison@stanford.edu',
        'webMaster' => '',
        'generator' => '',
        'docs' => '',
        'cloud' => '',
        'ttl' => '',
        'skipHours' => '',
        'skipDays' => '',
      ),
    ),
    'itunes' => array(
      'views_rss_itunes' => array(
        'subtitle' => 'Entitled Opinions',
        'summary' => 'With its high-powered, long-form conversations, Entitled Opinions has fans around the globe, from Australia to China, Mexico to Russia. Its host, Robert Pogue Harrison, has interviewed some of our era\'s leading figures in literature, philosophy, science, and cultural history for over a decade. One blogger called these intellectually expansive exchanges “the most fascinating, engaging podcasts in any possible universe.”',
        'category' => array(
          40 => '40',
          50 => '50',
          33 => '33',
        ),
        'keywords' => '',
        'image' => 'https://entitledopinions.stanford.edu/sites/default/files/field/image/robert.jpg',
        'author' => 'Robert Harrison',
        'explicit' => 'no',
        'block' => '',
        'complete' => '',
        'new-feed-url' => '',
        'owner' => '',
      ),
    ),
  );
  $handler->display->display_options['style_options']['item'] = array(
    'core' => array(
      'views_rss_core' => array(
        'title' => 'title',
        'link' => 'path',
        'description' => 'field_about_guest',
        'author' => '',
        'category' => '',
        'comments' => '',
        'enclosure' => 'field_link_to_download',
        'guid' => 'field_link_to_download_2',
        'pubDate' => 'field_date',
      ),
    ),
    'itunes' => array(
      'views_rss_itunes' => array(
        'subtitle' => 'title',
        'summary' => 'field_about_guest',
        'duration' => '',
        'keywords' => '',
        'image' => '',
        'author' => '',
        'isClosedCaptioned' => '',
        'explicit' => '',
        'block' => '',
        'order' => '',
      ),
    ),
  );
  $handler->display->display_options['style_options']['feed_settings'] = array(
    'absolute_paths' => 1,
    'feed_in_links' => 0,
  );
  $handler->display->display_options['path'] = 'podcast.xml';
  $handler->display->display_options['sitename_title'] = 0;
  $export['itunes_feed'] = $view;

  return $export;
}
