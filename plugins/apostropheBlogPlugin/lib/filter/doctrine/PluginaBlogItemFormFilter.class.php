<?php

/**
 * PluginaBlogItem form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaBlogItemFormFilter extends BaseaBlogItemFormFilter
{
  private function i18nDummy() {
    a_('No Author');
  }
  
  // Subclasses return post or event. Next time let's have the type field in the database contain the actual model class name
  protected function getType()
  {
    return $this->type;
  }
  
  public function configure()
  {
    //$this->widgetSchema->setLabel('editors_list', 'Edited By');
    //$this->widgetSchema->setLabel('user_id', 'By');
  }

  public function getAuthorChoices()
  {
    if (isset($this->authors))
    {
      return $this->authors;
    }
    $authors = Doctrine::getTable('sfGuardUser')->createQuery('u')->innerJoin('u.BlogAuthorItems i')->where('i.type = ?', $this->getType())-> select('u.*')->orderBy('u.last_name asc, u.first_name asc, u.username asc')->execute();
    $this->authors = array();
    foreach ($authors as $author)
    {
      $this->authors[$author['id']] = (string) $author;
    }
    $this->authors['-'] = 'No Author';
    return $this->authors;
  }
  
  public function getCategoryChoices()
  {
    if (isset($this->categories))
    {
      return $this->categories;
    }
    $categories = Doctrine::getTable('aCategory')->createQuery('c')->innerJoin('c.BlogItems i')->where('i.type = ?', $this->getType())-> select('c.*')->orderBy('c.name asc')->execute();
    $this->categories = array();
    foreach ($categories as $category)
    {
      $this->categories[$category['id']] = (string) $category;
    }
    $this->categories['-'] = 'Uncategorized';
    return $this->categories;
  }
  
	public function getTagChoices()
	{
		if (isset($this->tags))
		{
		  return $this->tags;
		}
		$this->tags = TagTable::getAllTagNameWithCount(null, array('model' => $this->getModelName()));
		foreach($this->tags as $key => &$tag)
		{
			$tag = $key;
		}
		return $this->tags;
	}
	
  public function setup()
  {
    $this->fields = $this->getFields();

		parent::setup();

		$this->setWidget('author_id', new sfWidgetFormChoice(
		  array(
			  'choices' => $this->getAuthorChoices(),
				'multiple' => true,
				'expanded' => false
			)
		));
		
		$this->setValidator('author_id', new sfValidatorChoice(
		  array(
			  'choices' => array_keys($this->getAuthorChoices()),
				'multiple' => true,
				'required' => false
			)
		));

	$this->setWidget('categories_list', new sfWidgetFormChoice(
	  array(
		  'choices' => $this->getCategoryChoices(),
			'multiple' => true,
			'expanded' => false
		)
	));
	
	$this->setValidator('categories_list', new sfValidatorChoice(
	  array(
		  'choices' => array_keys($this->getCategoryChoices()),
			'multiple' => true,
			'required' => false
		)
	));
		
		$this->setWidget('tags_list', new sfWidgetFormChoice(
		  array(
			  'choices' => $this->getTagChoices(),
				'multiple' => true,
				'expanded' => false
			)
		));
		
		$this->setValidator('tags_list', new sfValidatorChoice(
		  array(
			  'choices' => $this->getTagChoices(),
				'multiple' => true,
				'required' => false
			)
		));

     $this->setWidget('status', new sfWidgetFormChoice(array('choices' => array('' => '', 'draft' => 'draft', 'published' => 'published'))));
    $this->setValidator('status', new sfValidatorChoice(array('required' => false, 'choices' => array('draft' => 'draft', 'published' => 'published'))));
  }
  
  public function getAppliedFilters()
  {
    $values = $this->processValues($this->getDefaults());
    $fields = $this->getFields();
    
    $names = array_merge($fields, array_diff(array_keys($this->validatorSchema->getFields()), array_keys($fields)));
    $fields = array_merge($fields, array_combine($names, array_fill(0, count($names), null)));
    
    $appliedValues = array();
    
    foreach ($fields as $field => $type)
    {
      if (!isset($values[$field]) || null === $values[$field] || '' === $values[$field] || $field == $this->getCSRFFieldName())
      {
        continue;
      }
      
      $method = sprintf('get%sValue', self::camelize($this->getFieldName($field)));
      if (method_exists($this, $method))
      {
        $value = $this->$method($field, $values[$field]);
        if($value) $appliedValues[$field] = $value; 
      }
      else if (null != $type)
      {
        $method = sprintf('get%sValue', $type);
        if (method_exists($this, $method = sprintf('get%sValue', $type)))
        {
          $value = $this->$method($field, $values[$field]);
          if($value) $appliedValues[$field] = $value; 
        }
        
      }
    }
    return $appliedValues; 
  }
  
  protected function getManyKeyValue($field, $values)
  {
    return $this->getForeignKeyValue($field, $values);
  }
  
  protected function getForeignKeyValue($field, $values)
  {
    $appliedValues = array();
    $choices = $this[$field]->getWidget()->getChoices();
    if(is_array($values))
    {
      foreach($values as $value)
      {
        $appliedValues[] = $choices[$value]; 
      }
    }
    else
    {
      $appliedValues[] = $choices[$values];
    }
    return $appliedValues;
  }
  
  protected function getNumberValue($field, $values)
  {
    if(is_array($values) && isset($values['text']) && '' !== $values['text'])
    {
      return $values['text'];
    }
  }
	
	protected function getTextValue($field, $values)
  {
    if(is_array($values) && isset($values['text']) && '' !== $values['text'])
    {
      return $values['text'];
    }
  }
  
  protected function getEnumValue($field, $value)
  {
    return array($value);
  }
  
  protected function getBooleanValue($field, $value)
  {
    if(is_array($value))
    {
      $value = current($value);
    }
    $choices = $this->getWidget($field)->getChoices();
    return array($choices[$value]);
  }
	
	public function addAuthorIdColumnQuery(Doctrine_Query $query, $field, $value)
  {
    if (!strlen($value))
    {
      return;
    }
    
    if ($value === '-')
    {
      $query->andWhere($query->getRootAlias() . '.author_id IS NULL');
    }
    else
    {
      $query->andWhere($query->getRootAlias() . '.author_id = ?', $value);
    }
  }

	public function addCategoriesListColumnQuery(Doctrine_Query $query, $field, $value)
  {
    if (!strlen($value))
    {
      return;
    }
    
    if ($value === '-')
    {
      $query->leftJoin($query->getRootAlias() . '.Categories c')->andWhere('c.id IS NULL');
    }
    else
    {
      $query->innerJoin($query->getRootAlias() . '.Categories c WITH c.id = ?', $value);
    }
  }
	
	public function addTagsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }
    else
    {
      $values = array_keys($values);
    }

    if (!count($values))
    {
      return;
    }

    $ids = Doctrine::getTable('tagging')->createQuery()
		  ->select('taggable_id')
			->leftJoin('tagging.Tag tag')
		  ->where('taggable_model = ?', $this->getModelName())
			->andWhereIn('tag.name', $values)
			->groupBy('taggable_id')
			->execute(array(), Doctrine::HYDRATE_SCALAR);
    
		$ids = array_map(create_function('$i', 'return $i["tagging_taggable_id"];'), $ids);

		if (empty($ids))
    {
      $query->where('false');
    }
    else
    {
      $query->whereIn($query->getRootAlias() . '.id', $ids);
    }
  }
  
  public function getFields()
  {
    $fields = parent::getFields();
    $fields['tags_list'] = 'ManyKey';
    
    return $fields;
  }
}
