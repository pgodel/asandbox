<?php

/*
 * This file is part of Apostrophe
 * (c) 2009 P'unk Avenue LLC, www.punkave.com
 */

/**
 * @package    apostropheBlogPlugin
 * @subpackage Tasks
 * @author     Tom Boutell <tom@punkave.com>
 */

class aBlogGenerateTestEventsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('dictionary', null, sfCommandOption::PARAMETER_REQUIRED, 'The dictionary file', '/usr/share/dict/words'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The database connection', 'doctrine'),
      new sfCommandOption('amount', null, sfCommandOption::PARAMETER_REQUIRED, 'The number of events to be generated', '50'),
    ));


    $this->namespace        = 'apostrophe';
    $this->name             = 'generate-test-events';
    $this->briefDescription = 'Adds 50 random test events to the blog';
    $this->detailedDescription = <<<EOF
This task adds 50 test events with more or less random content to the blog for test purposes.
Words for those posts come from /usr/share/dict/words. If you don't have that file this task
won't work for you. (It's mainly a testing tool for us, but I'd accept a patch to take the
dictionary name from a file.)
EOF;
  }

  protected function execute($args = array(), $options = array())
  {    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
    // So we can play with app.yml settings from the application
    $context = sfContext::createInstance($this->configuration);

    $admin = Doctrine::getTable('sfGuardUser')->findOneByUsername('admin');
    
    for ($i = 0; ($i < $options['amount']); $i++)
    {
      echo("Creating event " . ($i + 1) . " of ".$options['amount']."...\n");

      $post = new aEvent();
      $post->author_id = $admin->id;
      $post->status = 'published';
			$post->start_date = aDate::mysql(strtotime(($i + 1) . ' days'));
			$post->start_time	= date('H:i:s', rand(0, time()));
			$post->end_date = aDate::mysql(strtotime(($i + rand(1,14)) . ' days'));
			$post->end_time = date('H:i:s', rand(0, time()));
			$post->excerpt = '';
			$post->location = "1168 E. Passyunk Avenue\nPhiladelphia PA 19147";
      $post->published_at = aDate::mysql(strtotime('-' . ($i + 1) . ' days'));
      $title = implode(' ', $this->getWords(mt_rand(5, 10), $options));
      $body = implode(' ', $this->getWords(mt_rand(20, 100), $options));
      $post->setTitle($title);
      $post->save();
      $slot = $post->Page->createSlot('aRichText');
      $slot->value = $body;
      $slot->save();
      $post->Page->newAreaVersion('blog-body', 'update', 
        array(
          'permid' => 1, 
          'slot' => $slot));
    }
  }
  
  protected $words = null;
  protected function getWords($count, $options)
  {
    if (is_null($this->words))
    {
      $this->words = file($options['dictionary'], FILE_IGNORE_NEW_LINES);
    }
    $result = array();
    for ($i = 0; ($i < $count); $i++)
    {
      $result[] = $this->words[mt_rand(0, count($this->words) - 1)];
    }
    return $result;
  }
}
