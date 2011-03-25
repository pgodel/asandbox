<?php

/**
 * aMediaItem filter form base class.
 *
 * @package    asandbox
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseaMediaItemFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'lucene_dirty'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'type'            => new sfWidgetFormChoice(array('choices' => array('' => '', 'image' => 'image', 'video' => 'video', 'audio' => 'audio', 'pdf' => 'pdf'))),
      'service_url'     => new sfWidgetFormFilterInput(),
      'format'          => new sfWidgetFormFilterInput(),
      'width'           => new sfWidgetFormFilterInput(),
      'height'          => new sfWidgetFormFilterInput(),
      'embed'           => new sfWidgetFormFilterInput(),
      'title'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'     => new sfWidgetFormFilterInput(),
      'credit'          => new sfWidgetFormFilterInput(),
      'owner_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Owner'), 'add_empty' => true)),
      'view_is_secure'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'            => new sfWidgetFormFilterInput(),
      'slots_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aSlot')),
      'categories_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory')),
    ));

    $this->setValidators(array(
      'lucene_dirty'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'type'            => new sfValidatorChoice(array('required' => false, 'choices' => array('image' => 'image', 'video' => 'video', 'audio' => 'audio', 'pdf' => 'pdf'))),
      'service_url'     => new sfValidatorPass(array('required' => false)),
      'format'          => new sfValidatorPass(array('required' => false)),
      'width'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'height'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'embed'           => new sfValidatorPass(array('required' => false)),
      'title'           => new sfValidatorPass(array('required' => false)),
      'description'     => new sfValidatorPass(array('required' => false)),
      'credit'          => new sfValidatorPass(array('required' => false)),
      'owner_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Owner'), 'column' => 'id')),
      'view_is_secure'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'            => new sfValidatorPass(array('required' => false)),
      'slots_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aSlot', 'required' => false)),
      'categories_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_media_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addSlotsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.aSlotMediaItem aSlotMediaItem')
      ->andWhereIn('aSlotMediaItem.slot_id', $values)
    ;
  }

  public function addCategoriesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.aMediaItemToCategory aMediaItemToCategory')
      ->andWhereIn('aMediaItemToCategory.category_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'aMediaItem';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'lucene_dirty'    => 'Boolean',
      'type'            => 'Enum',
      'service_url'     => 'Text',
      'format'          => 'Text',
      'width'           => 'Number',
      'height'          => 'Number',
      'embed'           => 'Text',
      'title'           => 'Text',
      'description'     => 'Text',
      'credit'          => 'Text',
      'owner_id'        => 'ForeignKey',
      'view_is_secure'  => 'Boolean',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'slug'            => 'Text',
      'slots_list'      => 'ManyKey',
      'categories_list' => 'ManyKey',
    );
  }
}
