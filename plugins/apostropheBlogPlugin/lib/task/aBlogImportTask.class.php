<?php

/*
 *
 * This file is part of Apostrophe
 * (c) 2009 P'unk Avenue LLC, www.punkave.com
 */

/**
 * @package    apostrophePlugin
 * @subpackage Tasks
 * @author     Dan Ordille <dan@punkave.com>
 */
class aImportBlogTask extends sfBaseTask
{

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('events', null, sfCommandOption::PARAMETER_REQUIRED, 'XML of events', null),
      new sfCommandOption('posts', null, sfCommandOption::PARAMETER_REQUIRED, 'XML of posts', null)
      // add your own options here
    ));

    $this->namespace = 'apostrophe';
    $this->name = 'import-blog';
    $this->briefDescription = 'Imports a blog from an XML file';
    $this->detailedDescription = <<<EOF
Usage:

php symfony apostrophe:import-blog

See the Wiki for documentation of the XML format required.
EOF;
  }

  protected function execute($args = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getDoctrineConnection();

    if (is_null($options['posts']) && is_null($options['events']))
    {
      die("You must specify at least one of the posts and events options with a path to the xml file.\n");
    }
    if (!$this->askConfirmation("Importing the same content twice will result in duplicate content, are you sure? [y/N]", 'QUESTION_LARGE', false))
    {
      die("Import CANCELLED.  No changes made.\n");
    }
    $rootDir = $this->configuration->getRootDir();

    $importer = new aBlogImporter($connection, $options);
    if (!is_null($options['events']))
    {
      $importer->import('events');
    }
    if (!is_null($options['posts']))
    {
      $importer->import('posts');
    }
  }

}
