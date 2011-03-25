<?php

/**
 * aBlogItem form base class.
 *
 * @method aBlogItem getObject() Returns the current form's model object
 *
 * @package    asandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseaBlogItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'author_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Author'), 'add_empty' => true)),
      'page_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'add_empty' => true)),
      'title'           => new sfWidgetFormInputText(),
      'slug'            => new sfWidgetFormInputText(),
      'slug_saved'      => new sfWidgetFormInputCheckbox(),
      'excerpt'         => new sfWidgetFormTextarea(),
      'status'          => new sfWidgetFormChoice(array('choices' => array('draft' => 'draft', 'pending review' => 'pending review', 'published' => 'published'))),
      'allow_comments'  => new sfWidgetFormInputCheckbox(),
      'template'        => new sfWidgetFormInputText(),
      'published_at'    => new sfWidgetFormDateTime(),
      'type'            => new sfWidgetFormInputText(),
      'start_date'      => new sfWidgetFormDate(),
      'start_time'      => new sfWidgetFormTime(),
      'end_date'        => new sfWidgetFormDate(),
      'end_time'        => new sfWidgetFormTime(),
      'location'        => new sfWidgetFormTextarea(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'editors_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
      'categories_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory')),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'author_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Author'), 'required' => false)),
      'page_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'slug'            => new sfValidatorPass(),
      'slug_saved'      => new sfValidatorBoolean(array('required' => false)),
      'excerpt'         => new sfValidatorString(array('required' => false)),
      'status'          => new sfValidatorChoice(array('choices' => array(0 => 'draft', 1 => 'pending review', 2 => 'published'), 'required' => false)),
      'allow_comments'  => new sfValidatorBoolean(array('required' => false)),
      'template'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'published_at'    => new sfValidatorDateTime(array('required' => false)),
      'type'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'start_date'      => new sfValidatorDate(array('required' => false)),
      'start_time'      => new sfValidatorTime(array('required' => false)),
      'end_date'        => new sfValidatorDate(array('required' => false)),
      'end_time'        => new sfValidatorTime(array('required' => false)),
      'location'        => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'editors_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
      'categories_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_blog_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'aBlogItem';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['editors_list']))
    {
      $this->setDefault('editors_list', $this->object->Editors->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['categories_list']))
    {
      $this->setDefault('categories_list', $this->object->Categories->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveEditorsList($con);
    $this->saveCategoriesList($con);

    parent::doSave($con);
  }

  public function saveEditorsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['editors_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Editors->getPrimaryKeys();
    $values = $this->getValue('editors_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Editors', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Editors', array_values($link));
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
