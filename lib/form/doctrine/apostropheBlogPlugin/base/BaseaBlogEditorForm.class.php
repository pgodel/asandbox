<?php

/**
 * aBlogEditor form base class.
 *
 * @method aBlogEditor getObject() Returns the current form's model object
 *
 * @package    asandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseaBlogEditorForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'blog_item_id' => new sfWidgetFormInputHidden(),
      'user_id'      => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'blog_item_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('blog_item_id')), 'empty_value' => $this->getObject()->get('blog_item_id'), 'required' => false)),
      'user_id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_blog_editor[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'aBlogEditor';
  }

}
