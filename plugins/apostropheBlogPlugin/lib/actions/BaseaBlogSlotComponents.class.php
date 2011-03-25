<?php
abstract class BaseaBlogSlotComponents extends aSlotComponents
{
  protected $modelClass = 'aBlogPost';
  protected $formClass = 'aBlogSlotForm';
  protected $handSelected = false;
  
  public function setup()
  {
    parent::setup();
  }

  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
    
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new $this->formClass($this->id, $this->slot->getArrayValue());
    }
    
    $this->popularTags = PluginTagTable::getPopulars(null, array('sort_by_popularity' => true), false, 10);
  	if (sfConfig::get('app_a_all_tags', true))
  	{
  	  $this->allTags = PluginTagTable::getAllTagNameWithCount();
    }
    else
    {
      $this->allTags = array();
    }
  }
  
  public function executeNormalView()
  {
    $this->setup();
    $this->values = $this->slot->getArrayValue();
    $q = $this->getQuery();
    
		$this->options['slideshowOptions']['width']	= ((isset($this->options['slideshowOptions']['width']))? $this->options['slideshowOptions']['width']:100);
		$this->options['slideshowOptions']['height'] = ((isset($this->options['slideshowOptions']['height']))? $this->options['slideshowOptions']['height']:100);
		$this->options['slideshowOptions']['resizeType'] = ((isset($this->options['slideshowOptions']['resizeType']))? $this->options['slideshowOptions']['resizeType']:'c');
				
    $this->options['excerptLength'] = $this->getOption('excerptLength', 100);
    $this->options['maxImages'] = $this->getOption('maxImages', 1);
		
    $this->aBlogPosts = $q->execute();
    aBlogItemTable::populatePages($this->aBlogPosts);
  }
  
  public function getQuery()
  {
    // Explicit select() mandatory with orderByList
    $q = Doctrine::getTable($this->modelClass)->createQuery()
      ->leftJoin($this->modelClass.'.Author a')
      ->leftJoin($this->modelClass.'.Categories c')
      ->select($this->modelClass . '.*, a.*, c.*');
    Doctrine::getTable($this->modelClass)->addPublished($q);
    if (isset($this->values['title_or_tag']) && ($this->values['title_or_tag'] === 'title'))
    {
      $this->handSelected = true;
      if (isset($this->values['blog_posts']) && count($this->values['blog_posts']))
      {
        $q->andWhereIn('id', $this->values['blog_posts']);
        $q = aDoctrine::orderByList($q, $this->values['blog_posts']);
      }
      else
      {
        $q->andWhere('0 <> 0');
      }
      // Works way better when you actually return it!
      return $q;
    }
    else
    {
      if (isset($this->values['categories_list']) && count($this->values['categories_list']) > 0)
      {
        $q->andWhereIn('c.id', $this->values['categories_list']);
      }
      if (isset($this->values['tags_list']) && strlen($this->values['tags_list']) > 0)
      {
        PluginTagTable::getObjectTaggedWithQuery($q->getRootAlias(), $this->values['tags_list'], $q, array('nb_common_tags' => 1));
      }
      if (!isset($this->values['count']))
      {
        $this->values['count'] = 3;
      }
      $q->limit($this->values['count']);
      $q->orderBy('published_at desc');
      return $q;
    }
  }
}
