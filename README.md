EntitledOpinions contains the Entitled Opinions project, an archive of the popular podcast. 

The repository is a basic Drupal 7 install, but set up to be run with Docksal: https://docksal.io/

Docksal includes Drush, a command line tool for working with Drupal, which should be used to update the drupal core. 

The site does require the php upload limits, generally set in php.ini, to be roughly 100MB since uploaded MP3s are larger than the default 2MB. The user may need to set the limit with the Drupal admin as well. 

You also need to make sure that the 'sites/default/files' directory is writable by the serving user, in many cases 'apache'.
