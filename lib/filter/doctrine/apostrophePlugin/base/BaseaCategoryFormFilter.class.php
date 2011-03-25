<?php

/**
 * aCategory filter form base class.
 *
 * @package    asandbox
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseaCategoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'             => new sfWidgetFormFilterInput(),
      'description'      => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'             => new sfWidgetFormFilterInput(),
      'media_items_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aMediaItem')),
      'pages_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aPage')),
      'users_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
      'groups_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'blog_items_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem')),
    ));

    $this->setValidators(array(
      'name'             => new sfValidatorPass(array('required' => false)),
      'description'      => new sfValidatorPass(array('required' => false)),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'             => new sfValidatorPass(array('required' => false)),
      'media_items_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aMediaItem', 'required' => false)),
      'pages_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aPage', 'required' => false)),
      'users_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
      'groups_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'blog_items_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_category_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addMediaItemsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('aMediaItemToCategory.media_item_id', $values)
    ;
  }

  public function addPagesListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('aPageToCategory.page_id', $values)
    ;
  }

  public function addUsersListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.aCategoryUser aCategoryUser')
      ->andWhereIn('aCategoryUser.user_id', $values)
    ;
  }

  public function addGroupsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.aCategoryGroup aCategoryGroup')
      ->andWhereIn('aCategoryGroup.group_id', $values)
    ;
  }

  public function addBlogItemsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('aBlogItemToCategory.blog_item_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'aCategory';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'name'             => 'Text',
      'description'      => 'Text',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
      'slug'             => 'Text',
      'media_items_list' => 'ManyKey',
      'pages_list'       => 'ManyKey',
      'users_list'       => 'ManyKey',
      'groups_list'      => 'ManyKey',
      'blog_items_list'  => 'ManyKey',
    );
  }
}
