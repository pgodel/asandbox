<?php

// Where does Symfony live? 

$dir = dirname(__FILE__);
if (file_exists("$dir/require-core.php"))
{
  // Look for a custom Symfony require directive in require-core.php
  require_once 'require-core.php';
}
else
{
  // Use copy checked out via svn:externals
  require_once "$dir/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php";
}

sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    // We do this here because we chose to put Zend in lib/vendor/Zend.
    // If it is installed system-wide then this isn't necessary to
    // enable Zend Search
    set_include_path(
      sfConfig::get('sf_lib_dir') .
        '/vendor' . PATH_SEPARATOR . get_include_path());
    // ORDER IS SIGNIFICANT. sfDoctrinePlugin logically comes first followed by sfDoctrineGuardPlugin.
    // apostrophePlugin must precede apostropheBlogPlugin. 
    $this->enablePlugins(array(
      'sfDoctrinePlugin',
      'sfDoctrineGuardPlugin',
      'sfDoctrineActAsTaggablePlugin',
      'sfTaskExtraPlugin',
      'sfWebBrowserPlugin',
      'sfFeed2Plugin',
      'sfSyncContentPlugin',
      'apostrophePlugin',
      'apostropheBlogPlugin',
			'apostropheExtraSlotsPlugin',
			));
  }
}
