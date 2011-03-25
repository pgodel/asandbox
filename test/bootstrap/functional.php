<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// guess current application
if (!isset($app))
{
  $traces = debug_backtrace();
  $caller = $traces[0];

  $dirPieces = explode(DIRECTORY_SEPARATOR, dirname($caller['file']));
  $app = array_pop($dirPieces);
}

require_once dirname(__FILE__).'/../../config/ProjectConfiguration.class.php';

// This has a chicken and egg problem if the route to your home page is a CMS route and your test database doesn't exist yet. You can
// resolve that with ./symfony doctrine:build-sql --env=test; ./symfony doctrine:insert-sql --env=test; ./symfony doctrine:data-load --env=test

$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', isset($debug) ? $debug : true);
sfContext::createInstance($configuration);

// remove all cache
sfToolkit::clearDirectory(sfConfig::get('sf_app_cache_dir'));

// DON'T do this. It doesn't know about fixtures,
// and it's much slower than our own loadData() which we call from
// the test constructor. Ours is faster because it reuses a cached
// mysqldump when possible. TBB

// Doctrine::loadData(sfConfig::get('sf_data_dir').'/fixtures');