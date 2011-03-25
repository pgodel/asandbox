<?php

/**
 * Base Components for the apostropheBlogPlugin aEvent module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aEvent
 * @author      Dan Ordille
 */
abstract class BaseaEventComponents extends sfComponents
{
  protected $modelClass = 'aEvent';

  public function setup()
  {
    parent::setup();
  }

}
