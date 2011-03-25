<?php
require_once dirname(__FILE__).'/aBlogAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aBlogAdminGeneratorHelper.class.php';
/**
 * Base actions for the aBlogPlugin aBlogAdmin module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlogAdmin
 * @author      Dan Ordille <dan@punkave.com>
 */
abstract class BaseaBlogAdminActions extends autoABlogAdminActions
{
  public $minorSorts = array('published_at desc');
  public function preExecute()
  {
    parent::preExecute();
  }

  // You must create with at least a title
  public function executeNew(sfWebRequest $request)
  {
    $this->forward404();
  }
  
  // Doctrine collection routes make it a pain to change the settings
  // of the standard routes fundamentally, so we provide another route
  public function executeNewWithTitle(sfWebRequest $request)
  {
    $this->form = new aBlogNewPostForm();
    $this->form->bind($request->getParameter('a_blog_new_post'));
    if ($this->form->isValid())
    {
      $this->a_blog_post = new aBlogPost();
      $this->a_blog_post->Author = $this->getUser()->getGuardUser();
      $this->a_blog_post->setTitle($this->form->getValue('title'));
      $this->a_blog_post->save();
      $this->postUrl = $this->generateUrl('a_blog_admin_edit', $this->a_blog_post);
      return 'Success';
    }
    return 'Error';
  }
    
  // DEPRECATED. use the new search method which is powered by Lucene
  public function executeAutocomplete(sfWebRequest $request)
  {
    $this->aBlogPosts = aBlogItemTable::titleSearch($request->getParameter('q'), '@a_blog_search_redirect');
    $this->setLayout(false);
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    $this->setABlogPostForUser();
    $this->form = new aBlogPostForm($this->a_blog_post);
    if ($request->getMethod() === 'POST')
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->a_blog_post = $this->form->save();
        
        // We do this here to avoid some nasty race conditions that crop up when
        // we try to push things to the page inside the Doctrine form transaction
        $this->a_blog_post->updatePageTagsAndCategories();
        
        // Recreate the form to get rid of bound values for the publication field,
        // so we can see the new setting
        $this->form = new aBlogPostForm($this->a_blog_post);
      }
    }
    if (!$request->isXmlHttpRequest())
    {
      $this->setTemplate('edit');
    }
  }
  
  public function executeUpdateTitle(sfWebRequest $request)
  {
    // Actually it might be either a post or an event, and that's OK, we
    // reuse this action
    $this->setABlogPostForUser();
    $title = trim($request->getParameter('title'));
    if (strlen($title))
    {
      // The preUpdate method takes care of updating the slug from the title as needed
      $this->a_blog_post->setTitle($title);
      $this->a_blog_post->save();
    }
    $this->setTemplate('titleAndSlug');
  }

  public function executeUpdateSlug(sfWebRequest $request)
  {
    // Actually it might be either a post or an event, and that's OK, we
    // reuse this action
    $this->setABlogPostForUser();
    $slug = trim($request->getParameter('slug'));
    if (strlen($slug))
    {
      // "OMG, aren't you going to slugify this?" The preUpdate method of the
      // PluginaBlogItem class takes care of slugifying and uniqueifying the slug.
      $this->a_blog_post->setSlug($slug);
      $this->a_blog_post->save();
    }
    $this->setTemplate('titleAndSlug');
  }
  
  protected function setABlogPostForUser()
  {
    $request = $this->getRequest();
    if ($this->getUser()->hasCredential('admin'))
    {
      $this->a_blog_post = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_blog_post = Doctrine::getTable('aBlogPost')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
  }

  public function executeRedirect()
  {
    $aBlogPost = $this->getRoute()->getObject();
    aRouteTools::pushTargetEnginePage($aBlogPost->findBestEngine());
    $this->redirect($this->generateUrl('a_blog_post', $this->getRoute()->getObject()));
  }

  public function executeCategories()
  {
    $this->redirect('@a_category_admin');
  }

  public function executeIndex(sfWebRequest $request)
  {
    if(!aPageTable::getFirstEnginePage('aBlog'))
    {
      $this->setTemplate('engineWarning');
    }

    parent::executeIndex($request);
    aBlogItemTable::populatePages($this->pager->getResults());
  }

  public function executeEdit(sfWebRequest $request)
  {
		$this->getResponse()->addJavascript('/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js','last');
	
    if($this->getUser()->hasCredential('admin'))
    {
      $this->a_blog_post = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_blog_post = Doctrine::getTable('aBlogPost')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
    $this->forward404Unless($this->a_blog_post);
    // Separate forms for separately saved fields
    $this->form = new aBlogPostForm($this->a_blog_post);

		// Retrieve the tags currently assigned to the blog post for the inlineTaggableWidget
		$this->existingTags = $this->form->getObject()->getTags();
		// Retrieve the 10 most popular tags for the inlineTaggableWidget
    $this->popularTags = TagTable::getPopulars(null, array('model' => 'aBlogPost', 'sort_by_popularity' => true), false, 10);

    aBlogItemTable::populatePages(array($this->a_blog_post));
  }

  protected function buildQuery()
  {
    if (is_null($this->filters))
    {
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }
    $filters = $this->getFilters();
    $resetFilters = false;
    foreach($this->filters->getAppliedFilters() as $name => $field)
    {
      foreach($field as $key => $value)
      {
        if(is_null($value))
        {
          unset($filters[$name]);
          $resetFilters = true;
        }
      }
    }
    if($resetFilters)
    {
      $this->getUser()->setAttribute('aBlogAdmin.filters', $filters, 'admin_module');
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }

    $query = parent::buildQuery();
    $query->leftJoin($query->getRootAlias().'.Author')
      ->leftJoin($query->getRootAlias().'.Editors')
      ->leftJoin($query->getRootAlias().'.Categories')
      ->leftJoin($query->getRootAlias().'.Page');
    return $query;
  }
  
  public function executeRemoveFilter(sfWebRequest $request)
  {
    $name = $request->getParameter('name');
    $value = $request->getParameter('value');

    $filters = $this->getUser()->getAttribute('aBlogAdmin.filters', $this->configuration->getFilterDefaults(), 'admin_module');
    unset($filters[$name]);
    $this->getUser()->setAttribute('aBlogAdmin.filters', $filters, 'admin_module');

    $this->redirect('@a_blog_admin');
  }

  public function executeSearch(sfWebRequest $request)
  {
    return aBlogToolkit::searchBody($this, '@a_blog_redirect', 'aBlogPost', null, $request);
  }   
}
