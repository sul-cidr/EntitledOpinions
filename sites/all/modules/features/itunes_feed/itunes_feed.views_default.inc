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
  $view->name = 'podcast_xml';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'iTunes Podcast XML';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'iTunes Podcast XML';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['style_plugin'] = 'views_xml_style';
  $handler->display->display_options['style_options']['plaintext_output'] = 1;
  $handler->display->display_options['style_options']['header'] = '';
  $handler->display->display_options['style_options']['author'] = 'Anonymous';
  $handler->display->display_options['style_options']['using_views_api_mode'] = 0;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Date */
  $handler->display->display_options['fields']['field_date']['id'] = 'field_date';
  $handler->display->display_options['fields']['field_date']['table'] = 'field_data_field_date';
  $handler->display->display_options['fields']['field_date']['field'] = 'field_date';
  $handler->display->display_options['fields']['field_date']['type'] = 'date_plain';
  $handler->display->display_options['fields']['field_date']['settings'] = array(
    'format_type' => 'itunes_formatted',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
    'show_remaining_days' => 0,
  );
  /* Field: Content: iTunes Description */
  $handler->display->display_options['fields']['field_itunes_description']['id'] = 'field_itunes_description';
  $handler->display->display_options['fields']['field_itunes_description']['table'] = 'field_data_field_itunes_description';
  $handler->display->display_options['fields']['field_itunes_description']['field'] = 'field_itunes_description';
  $handler->display->display_options['fields']['field_itunes_description']['type'] = 'text_plain';
  /* Field: Content: Episode MP3 */
  $handler->display->display_options['fields']['field_link_to_download']['id'] = 'field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download']['table'] = 'field_data_field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download']['field'] = 'field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download']['label'] = 'MP3 URL';
  $handler->display->display_options['fields']['field_link_to_download']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_link_to_download']['type'] = 'file_url_plain';
  /* Field: Content: Episode MP3 */
  $handler->display->display_options['fields']['field_link_to_download_1']['id'] = 'field_link_to_download_1';
  $handler->display->display_options['fields']['field_link_to_download_1']['table'] = 'field_data_field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download_1']['field'] = 'field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download_1']['label'] = 'Episode MP3 Enclosure';
  $handler->display->display_options['fields']['field_link_to_download_1']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_link_to_download_1']['type'] = 'enclosure';
  /* Field: Content: Episode MP3 */
  $handler->display->display_options['fields']['field_link_to_download_2']['id'] = 'field_link_to_download_2';
  $handler->display->display_options['fields']['field_link_to_download_2']['table'] = 'field_data_field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download_2']['field'] = 'field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download_2']['label'] = 'Episode MP3 Duration';
  $handler->display->display_options['fields']['field_link_to_download_2']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_link_to_download_2']['type'] = 'itunes:duration';
  /* Field: Content: Path */
  $handler->display->display_options['fields']['path']['id'] = 'path';
  $handler->display->display_options['fields']['path']['table'] = 'node';
  $handler->display->display_options['fields']['path']['field'] = 'path';
  /* Field: Content: Episode MP3 */
  $handler->display->display_options['fields']['field_link_to_download_3']['id'] = 'field_link_to_download_3';
  $handler->display->display_options['fields']['field_link_to_download_3']['table'] = 'field_data_field_link_to_download';
  $handler->display->display_options['fields']['field_link_to_download_3']['field'] = 'field_link_to_download';
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
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'rss_fields';
  $handler->display->display_options['style_options']['channel'] = array(
    'core' => array(
      'views_rss_core' => array(
        'description' => 'Entitled Opinions is a literary talk show on Stanford University Radio, KZSU, in which Professor Robert Harrison interviews guests about issues that range from literature and philosophy to politics and sports.',
        'language' => 'en-us',
        'category' => 'Philosophy,Public Radio',
        'image' => '',
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
        'subtitle' => '',
        'summary' => '',
        'category' => array(),
        'keywords' => 'literature, philosophy, theory, politics, stanford',
        'image' => '',
        'author' => 'Robert Harrison',
        'explicit' => '',
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
        'description' => 'field_itunes_description',
        'author' => '',
        'category' => '',
        'comments' => '',
        'enclosure' => 'field_link_to_download_1',
        'guid' => 'field_link_to_download',
        'pubDate' => 'field_date',
      ),
    ),
    'itunes' => array(
      'views_rss_itunes' => array(
        'subtitle' => '',
        'summary' => 'field_itunes_description',
        'duration' => 'field_link_to_download_2',
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
  $handler->display->display_options['path'] = 'itunes.xml';
  $handler->display->display_options['sitename_title'] = 0;
  $export['podcast_xml'] = $view;

  return $export;
}
