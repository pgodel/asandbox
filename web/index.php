<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

// P'UNK TEAM: DO NOT change this file, use frontend_dev.php if you want dev or debug output etc.
// Changing this file creates problems for everyone else who expects a production environment here.
// Don't change the environment, don't change the debug flag.

// EVERYONE ELSE: of course you should feel free to change this in your own download of the project.
// Our preferred approach is to rsync exclude this file and have it be a dev environment on our
// local boxes and a production environment on production. But that's not the Symfony default,
// which we're trying to stick with here to help folks see what they expect to see.

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
