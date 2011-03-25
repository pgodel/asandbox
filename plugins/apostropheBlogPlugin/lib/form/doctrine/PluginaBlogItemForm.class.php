<?php

/**
 * PluginaBlogItem form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaBlogItemForm extends BaseaBlogItemForm
{
  protected $engine = 'aBlog';

  public function setup()
  {
    parent::setup();
    $user = sfContext::getInstance()->getUser();

    // We implement our own sluggability so we have to take responsibility for
    // booting that field out of the form classes
    unset(
      $this['type'], $this['page_id'], $this['created_at'], $this['updated_at'], $this['slug'], $this['slug_saved'], $this['tags'], $this['title'], $this['status']
    );

    $q = Doctrine::getTable($this->getModelName())->addCategoriesForUser($user->getGuardUser(), $user->hasCredential('admin'));
    $this->setWidget('categories_list',
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => $this->getModelName(), 'query' => $q)));
    $this->setValidator('categories_list',
      new sfValidatorDoctrineChoice(array('multiple' => true, 'model' =>  $this->getModelName(), 'query' => $q, 'required' => false)));

    if($user->hasCredential('admin'))
    {
      // Don't use a hidden field, an actual field will be output in that case and conflict
      // with the DHTML-generated checkboxes when PHP goes to parse the result
      $this->setWidget('categories_list_add',
        new sfWidgetFormInputText());
      //TODO: Make this validator better, should check for duplicate categories, etc.
      $this->setValidator('categories_list_add',
        new sfValidatorPass(array('required' => false)));
    }

    $templates = sfConfig::get('app_'.$this->engine.'_templates', $this->getObject()->getTemplateDefaults());
    $templateChoices = array();
	  foreach ($templates as $key => $template)
	  {
	    $templateChoices[$key] = $template['name'];
	  }

    $this->setWidget('template',
      new sfWidgetFormChoice(array('multiple' => false, 'choices' => $templateChoices)));
    $this->setValidator('template',
      new sfValidatorChoice(array('required' => true, 'multiple' => false, 'choices' => array_flip($templateChoices))));

    if(count($templateChoices) <= 1)
    {
      unset($this['template']);
    }

    if(!sfConfig::get('app_aBlog_comments', false))
    {
      unset($this['allow_comments']);
    }

    // The candidates to edit pages are candidates to author blogs
    $candidateGroup = sfConfig::get('app_a_edit_candidate_group', false);
    $sufficientGroup = sfConfig::get('app_a_edit_sufficient_group', false);

    if( $user->hasCredential('admin') || $user->getGuardUser()->getId() == $this->getObject()->getAuthorId() )
    {
      $q = Doctrine::getTable('sfGuardUser')->createQuery();

      $q->addWhere('sfGuardUser.id != ?', $user->getGuardUser()->getId());

      if ($candidateGroup)
      {
        $q->innerJoin('sfGuardUser.Groups g')->addWhere('g.name = ?', array($candidateGroup));
      }
      $this->setWidget('editors_list',
        new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'query' => $q)));
      $this->setValidator('editors_list',
        new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'query' => $q, 'required' => false)));
    }
    else
    {
      unset($this['editors_list']);
    }

    if($user->hasCredential('admin'))
    {
      // Blog author candidates include blog editor candidates, plus admins - if they aren't listed here they
      // wind up losing authorship to the first person in the dropdown due to the validator!
      if ($candidateGroup && $sufficientGroup)
      {
        $q = Doctrine::getTable('sfGuardUser')->createQuery('u')->leftJoin('u.Permissions p WITH p.name = "cms_admin"')->leftJoin('u.Groups g')->leftJoin('g.Permissions gp WITH gp.name = "cms_admin"')->addWhere('(g.name IN (?, ?)) OR (u.is_super_admin IS TRUE) OR gp.name = "cms_admin" OR p.name = "cms_admin"', array($candidateGroup, $sufficientGroup));
      }
      else
      {
        $q = Doctrine::getTable('sfGuardUser')->createQuery('u')->leftJoin('u.Permissions p WITH p.name = "cms_admin"')->leftJoin('u.Groups g')->leftJoin('g.Permissions gp WITH gp.name = "cms_admin"')->addWhere('(u.is_super_admin IS TRUE) OR gp.name = "cms_admin" OR p.name = "cms_admin"', array($candidateGroup, $sufficientGroup));
      }
      $this->setWidget('author_id',
        new sfWidgetFormDoctrineChoice(array('model' => 'sfGuardUser', 'query' => $q)));
      $this->setValidator('author_id',
        new sfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'query' => $q, 'required' => false)));
    }
    else
    {
      unset($this['author_id']);
    }

    $this->setWidget('published_at', new aWidgetFormJQueryDateTime(
			array('date' => array('image' => '/apostrophePlugin/images/a-icon-datepicker.png')),
			array('time' => array('twenty-four-hour' => false, 'minutes-increment' => 30))
		));
		
    // DON'T set a default for the date/time widget. If you do, for some reason it wins even though
    // the object already exists. A reasonable default is already in the object anyway by now
    
		$tagstring = implode(', ', $this->getObject()->getTags());  // added a space after the comma for readability
		// class tag-input enabled for typeahead support
    $this->widgetSchema['tags'] = new sfWidgetFormInput(array('default' => $tagstring), array('class' => 'tags-input', 'autocomplete' => 'off', 'id' => 'a-blog-post-tags-input'));
    $this->validatorSchema['tags'] = new sfValidatorString(array('required' => false));

    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'postValidator')))
    );
    
    $this->configurePublication();
  }

  public function postValidator($validator, $values)
  {
    if(isset($values['categories_list_add']) && is_array($values['categories_list_add']))
    {
      $stringValidator = new sfValidatorString();
      foreach($values['categories_list_add'] as $key => $value)
      {
        $values['categories_list_add'][$key] = $stringValidator->clean($value);
      }
    }
    return $values;
  }

  // Returns categories set on this item that this user is not eligible to remove.
  // Used for static display
  public function getAdminCategories()
  {
    $reserved = array();
    $existing = Doctrine::getTable('aCategory')->createQuery('c')->select('c.*')->innerJoin('c.BlogItems bi WITH bi.id = ?', $this->object->id)->execute();
    $categoriesForUser = aCategoryTable::getInstance()->addCategoriesForUser(sfContext::getInstance()->getUser()->getGuardUser(), sfContext::getInstance()->getUser()->hasCredential('admin'))->execute();
    $ours = array_flip(aArray::getIds($categoriesForUser));
    foreach ($existing as $category)
    {
      if (!isset($ours[$category->id]))
      {
        $reserved[] = $category;
      }
    }
    return $reserved;
  }
  
  public function updateCategoriesList($addValues)
  {
    // Add any new categories (categories_list_add), and restore any
    // categories we didn't have the privileges to remove
    
    $link = array();
    if(!is_array($addValues))
    {
      $addValues = array();
    }
    foreach ($addValues as $value)
    {
      $existing = Doctrine::getTable('aCategory')->findOneBy('name', $value);
      if ($existing)
      {
        $aCategory = $existing;
      }
      else
      {
        $aCategory = new aCategory();
        $aCategory['name'] = $value;
      }
      $aCategory->save();
      $link[] = $aCategory['id'];
    }
    if(!is_array($this->values['categories_list']))
    {
      $this->values['categories_list'] = array();
    }
    $reserved = $this->getAdminCategories();
    foreach ($reserved as $category)
    {
      if (!in_array($category->id, $this->values['categories_list']))
      {
        $this->values['categories_list'][] = $category->id;
      }
    }
    foreach ($link as $id)
    {
      if (!in_array($id, $this->values['categories_list']))
      {
        $this->values['categories_list'][] = $id;
      }
    }
  }

  protected function doSave($con = null)
  {
    $this->updateCategoriesList(isset($this->values['categories_list_add']) ? $this->values['categories_list_add'] : array());
    parent::doSave($con);
  }
  
  public function updateObject($values = null)
  {
    $object = $this->getObject();
    if (is_null($values))
    {
      $values = $this->getValues();
    }

    // Slashes break routes in most server configs. Do NOT force case of tags.
    
    $values['tags'] = str_replace('/', '-', isset($values['tags']) ? $values['tags'] : '');
    
    $object = parent::updateObject($values);
    $this->updatePublication($values);
    return $object;
  }
  
  // Implement's John's combined dropdown for publication status (along with
  // a little help from javascript as per always)
  
  public function updatePublication($values)
  {
    $object = $this->getObject();
    if ($values['publication'] === 'schedule')
    {
      $object->status = 'published';
      // published_at comes from corresponding field naturally
    }
    elseif ($values['publication'] === 'publish')
    {
      $object->status = 'published';
      // Override field, publish now
      $object->published_at = aDate::mysql();
    }
    elseif ($values['publication'] === 'draft')
    {
      $object->status = 'draft';
    }
  }
  
  // Implement's John's combined dropdown for publication status (along with
  // a little help from javascript as per always)
  
  public function configurePublication()
  {
    $choices = array();
    $o = $this->getObject();
    $now = aDate::mysql();
    if ($o->status === 'draft')
    {
      $choices = array('draft' => 'Draft',
        'publish' => 'Publish',
        'schedule' => 'Schedule'
      );
      $default = 'draft';
    } elseif (($o->status === 'published') && ($o->published_at <= $now))
    {
      $choices = array('nochange' => 'Published',
        'draft' => 'Draft',
        'schedule' => 'Schedule'
      );
      $default = 'nochange';
    } elseif (($o->status === 'published') && ($o->published_at > $now))
    {
      $choices = array('schedule' => 'Scheduled', 'publish' => 'Publish', 'draft' => 'Draft');
      $default = 'schedule';
    }
    $this->setWidget('publication', new sfWidgetFormChoice(array('choices' => $choices, 'default' => $default)));
    $this->setValidator('publication', new sfValidatorChoice(array('choices' => array_keys($choices))));
  }
}
