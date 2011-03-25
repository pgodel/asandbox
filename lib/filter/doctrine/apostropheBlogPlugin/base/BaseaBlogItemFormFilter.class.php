<?php

/**
 * aBlogItem filter form base class.
 *
 * @package    asandbox
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseaBlogItemFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'author_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Author'), 'add_empty' => true)),
      'page_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'add_empty' => true)),
      'title'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug_saved'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'excerpt'         => new sfWidgetFormFilterInput(),
      'status'          => new sfWidgetFormChoice(array('choices' => array('' => '', 'draft' => 'draft', 'pending review' => 'pending review', 'published' => 'published'))),
      'allow_comments'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'template'        => new sfWidgetFormFilterInput(),
      'published_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'type'            => new sfWidgetFormFilterInput(),
      'start_date'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'start_time'      => new sfWidgetFormFilterInput(),
      'end_date'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'end_time'        => new sfWidgetFormFilterInput(),
      'location'        => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'editors_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
      'categories_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory')),
    ));

    $this->setValidators(array(
      'author_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Author'), 'column' => 'id')),
      'page_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Page'), 'column' => 'id')),
      'title'           => new sfValidatorPass(array('required' => false)),
      'slug'            => new sfValidatorPass(array('required' => false)),
      'slug_saved'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'excerpt'         => new sfValidatorPass(array('required' => false)),
      'status'          => new sfValidatorChoice(array('required' => false, 'choices' => array('draft' => 'draft', 'pending review' => 'pending review', 'published' => 'published'))),
      'allow_comments'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'template'        => new sfValidatorPass(array('required' => false)),
      'published_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'type'            => new sfValidatorPass(array('required' => false)),
      'start_date'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'start_time'      => new sfValidatorPass(array('required' => false)),
      'end_date'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'end_time'        => new sfValidatorPass(array('required' => false)),
      'location'        => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'editors_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
      'categories_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_blog_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addEditorsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.aBlogEditor aBlogEditor')
      ->andWhereIn('aBlogEditor.user_id', $values)
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
      ->leftJoin($query->getRootAlias().'.aBlogItemToCategory aBlogItemToCategory')
      ->andWhereIn('aBlogItemToCategory.category_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'aBlogItem';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'author_id'       => 'ForeignKey',
      'page_id'         => 'ForeignKey',
      'title'           => 'Text',
      'slug'            => 'Text',
      'slug_saved'      => 'Boolean',
      'excerpt'         => 'Text',
      'status'          => 'Enum',
      'allow_comments'  => 'Boolean',
      'template'        => 'Text',
      'published_at'    => 'Date',
      'type'            => 'Text',
      'start_date'      => 'Date',
      'start_time'      => 'Text',
      'end_date'        => 'Date',
      'end_time'        => 'Text',
      'location'        => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'editors_list'    => 'ManyKey',
      'categories_list' => 'ManyKey',
    );
  }
}
