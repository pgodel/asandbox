<?php

/**
 * aMediaItem form base class.
 *
 * @method aMediaItem getObject() Returns the current form's model object
 *
 * @package    asandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseaMediaItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'lucene_dirty'    => new sfWidgetFormInputCheckbox(),
      'type'            => new sfWidgetFormChoice(array('choices' => array('image' => 'image', 'video' => 'video', 'audio' => 'audio', 'pdf' => 'pdf'))),
      'service_url'     => new sfWidgetFormInputText(),
      'format'          => new sfWidgetFormInputText(),
      'width'           => new sfWidgetFormInputText(),
      'height'          => new sfWidgetFormInputText(),
      'embed'           => new sfWidgetFormTextarea(),
      'title'           => new sfWidgetFormInputText(),
      'description'     => new sfWidgetFormTextarea(),
      'credit'          => new sfWidgetFormInputText(),
      'owner_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Owner'), 'add_empty' => true)),
      'view_is_secure'  => new sfWidgetFormInputCheckbox(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'slug'            => new sfWidgetFormInputText(),
      'slots_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aSlot')),
      'categories_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory')),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'lucene_dirty'    => new sfValidatorBoolean(array('required' => false)),
      'type'            => new sfValidatorChoice(array('choices' => array(0 => 'image', 1 => 'video', 2 => 'audio', 3 => 'pdf'))),
      'service_url'     => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'format'          => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'width'           => new sfValidatorInteger(array('required' => false)),
      'height'          => new sfValidatorInteger(array('required' => false)),
      'embed'           => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 200)),
      'description'     => new sfValidatorString(array('required' => false)),
      'credit'          => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'owner_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Owner'), 'required' => false)),
      'view_is_secure'  => new sfValidatorBoolean(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'slug'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'slots_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aSlot', 'required' => false)),
      'categories_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'aMediaItem', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('a_media_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'aMediaItem';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['slots_list']))
    {
      $this->setDefault('slots_list', $this->object->Slots->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['categories_list']))
    {
      $this->setDefault('categories_list', $this->object->Categories->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveSlotsList($con);
    $this->saveCategoriesList($con);

    parent::doSave($con);
  }

  public function saveSlotsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['slots_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Slots->getPrimaryKeys();
    $values = $this->getValue('slots_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Slots', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Slots', array_values($link));
    }
  }

  public function saveCategoriesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['categories_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Categories->getPrimaryKeys();
    $values = $this->getValue('categories_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Categories', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Categories', array_values($link));
    }
  }

}
