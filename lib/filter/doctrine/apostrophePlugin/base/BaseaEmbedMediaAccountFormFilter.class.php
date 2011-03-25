<?php

/**
 * aEmbedMediaAccount filter form base class.
 *
 * @package    asandbox
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseaEmbedMediaAccountFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'service'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'username' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'service'  => new sfValidatorPass(array('required' => false)),
      'username' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_embed_media_account_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'aEmbedMediaAccount';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'service'  => 'Text',
      'username' => 'Text',
    );
  }
}
