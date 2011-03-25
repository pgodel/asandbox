<?php

/**
 * Base Components for the aBlogPlugin aBlog module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlog
 * @author      P'unk Avenue
 * @version     SVN: $Id: BaseComponents.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BaseaBlogComponents extends sfComponents
{
  protected $modelClass = 'aBlogPost';

  public function preExecute()
  {
    parent::preExecute();
  }

  public function executeSidebar()
  {
    $this->categories = $this->info['categoriesInfo'];
    $this->tagsByPopularity = $this->info['tagsByPopularity'];
    $this->tagsByName = $this->info['tagsByName'];
    // What is this for?
    if($this->reset == true)
    {
      $this->params['cat'] = array();
      $this->params['tag'] = array();
    }
  }
  
}
