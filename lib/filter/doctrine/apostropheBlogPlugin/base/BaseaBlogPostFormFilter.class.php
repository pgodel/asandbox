<?php

/**
 * aBlogPost filter form base class.
 *
 * @package    asandbox
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedInheritanceTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseaBlogPostFormFilter extends aBlogItemFormFilter
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('a_blog_post_filters[%s]');
  }

  public function getModelName()
  {
    return 'aBlogPost';
  }
}
