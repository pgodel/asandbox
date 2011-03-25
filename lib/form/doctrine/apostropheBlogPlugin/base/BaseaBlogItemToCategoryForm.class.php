<?php

/**
 * aBlogItemToCategory form base class.
 *
 * @method aBlogItemToCategory getObject() Returns the current form's model object
 *
 * @package    asandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseaBlogItemToCategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'blog_item_id' => new sfWidgetFormInputHidden(),
      'category_id'  => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'blog_item_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('blog_item_id')), 'empty_value' => $this->getObject()->get('blog_item_id'), 'required' => false)),
      'category_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('category_id')), 'empty_value' => $this->getObject()->get('category_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_blog_item_to_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'aBlogItemToCategory';
  }

}
