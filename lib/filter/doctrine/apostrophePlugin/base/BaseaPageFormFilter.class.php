<?php

/**
 * aPage filter form base class.
 *
 * @package    asandbox
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseaPageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'slug'            => new sfWidgetFormFilterInput(),
      'template'        => new sfWidgetFormFilterInput(),
      'view_is_secure'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'view_guest'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'edit_admin_lock' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'view_admin_lock' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'published_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'archived'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'admin'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'author_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Author'), 'add_empty' => true)),
      'deleter_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Deleter'), 'add_empty' => true)),
      'engine'          => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'lft'             => new sfWidgetFormFilterInput(),
      'rgt'             => new sfWidgetFormFilterInput(),
      'level'           => new sfWidgetFormFilterInput(),
      'categories_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory')),
    ));

    $this->setValidators(array(
      'slug'            => new sfValidatorPass(array('required' => false)),
      'template'        => new sfValidatorPass(array('required' => false)),
      'view_is_secure'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'view_guest'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'edit_admin_lock' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'view_admin_lock' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'published_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'archived'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'admin'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'author_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Author'), 'column' => 'id')),
      'deleter_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Deleter'), 'column' => 'id')),
      'engine'          => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'lft'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'categories_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_page_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
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
      ->leftJoin($query->getRootAlias().'.aPageToCategory aPageToCategory')
      ->andWhereIn('aPageToCategory.category_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'aPage';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'slug'            => 'Text',
      'template'        => 'Text',
      'view_is_secure'  => 'Boolean',
      'view_guest'      => 'Boolean',
      'edit_admin_lock' => 'Boolean',
      'view_admin_lock' => 'Boolean',
      'published_at'    => 'Date',
      'archived'        => 'Boolean',
      'admin'           => 'Boolean',
      'author_id'       => 'ForeignKey',
      'deleter_id'      => 'ForeignKey',
      'engine'          => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'lft'             => 'Number',
      'rgt'             => 'Number',
      'level'           => 'Number',
      'categories_list' => 'ManyKey',
    );
  }
}
