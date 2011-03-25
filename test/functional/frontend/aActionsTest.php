<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$b = new aTestFunctional(new aBrowser());
$b->setOptions(array('default-prefix' => '/admin/'));

$b->info('1 - CMS Credentials')->
  info('  1.1 - Unauthenticated users do not see the CMS controls or the buttons in the toolbar')->
  getAndCheck('a', 'show', '/')->
  with('response')->begin()->
    checkElement('li.a-header-cms', false)->
  end()->
  
  info('  1.2 - An admin user (admin/demo) can see the CMS controls and the buttons in the toolbar')->
  restart()->login('admin', 'demo');
  
$user = sfContext::getInstance()->getUser();

$b->info('Username is ' . $user->getGuardUser()->getUsername())->
  info('Permission is ' . $user->hasCredential('cms_admin'))->
  getAndCheck('a', 'show', '/');

$page = aPageTable::retrieveBySlug('/');

$b->info('User has edit privilege')->
  test()->is($page->userHasPrivilege('edit'), true);

$b->with('response')->begin()->
    checkElement('ul.a-controls', true)->
  end();
;

$b->info('User has manage privilege')->
  test()->is($page->userHasPrivilege('edit'), true);

$b->info('Creating a subpage works properly')->
  createPage('/', 'Test')->with('request')->begin()->isParameter('slug', 'test')->end();