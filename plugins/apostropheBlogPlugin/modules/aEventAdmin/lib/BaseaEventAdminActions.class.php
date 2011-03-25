<?php
require_once dirname(__FILE__).'/aEventAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aEventAdminGeneratorHelper.class.php';
/**
 * Base actions for the aEventPlugin aEventAdmin module.
 * 
 * @package     aEventPlugin
 * @subpackage  aEventAdmin
 * @author      Dan Ordille <dan@punkave.com>
 */
abstract class BaseaEventAdminActions extends autoAEventAdminActions
{ 
  public $minorSorts = array('start_date desc', 'start_time desc');
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
    $this->form = new aNewEventForm();
    $this->form->bind($request->getParameter('a_new_event'));
    if ($this->form->isValid())
    {
      $this->a_event = new aEvent();
      $this->a_event->Author = $this->getUser()->getGuardUser();
      $this->a_event->setTitle($this->form->getValue('title'));
			// Reasonable default: event starts at the top of the next half hour, and runs for one hour
			$now = time();
			$halfHours = floor($now / (30 * 60));
			$halfHours ++;
			$now = $halfHours * (30 * 60);
			$later = $now + 60 * 60;
			
			$this->a_event->start_date = date('Y-m-d', $now);
			$this->a_event->end_date = date('Y-m-d', $later);
			$this->a_event->start_time = date('H:i:s', $now);
			$this->a_event->end_time = date('H:i:s', $later);
      $this->a_event->save();
      $this->getUser()->setFlash('new_post', true);
      $this->eventUrl = $this->generateUrl('a_event_admin_edit', $this->a_event);
      return 'Success';
    }
    return 'Error';
  }
   
  // DEPRECATED, see executeSearch below
   
  public function executeAutocomplete(sfWebRequest $request)
  {
    // Search is in virtual pages, the TITLE field is dead (or going to be) and not
    // I18N, we have to cope with that correctly. I tried to use Zend Search but we
    // can't easily distinguish blog pages from the rest and that seems to be a deeper
    // architectural problem. I still had to fix a few things in PluginaBlogItem which
    // was locking the virtual pages down and making them unsearchable by normal mortals.
    // Now it locks them down only when they are not status = published. Republish things
    // to get the benefit of this on existing sites
    
    $this->aEvents = aBlogItemTable::titleSearch($request->getParameter('q'), '@a_event_search_redirect');
    $this->setLayout(false);
  }
  
  public function executeSearch(sfWebRequest $request)
  {
    return aBlogToolkit::searchBody($this, '@a_event_redirect', 'aEvent', null, $request);
  }   
  
  public function executeUpdate(sfWebRequest $request)
  {
    $this->setAEventForUser();
    $this->form = new aEventForm($this->a_event);
    if ($request->getMethod() === 'POST')
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->a_event = $this->form->save();

        // We do this here to avoid some nasty race conditions that crop up when
        // we try to push things to the page inside the Doctrine form transaction
        $this->a_event->updatePageTagsAndCategories();

        // Recreate the form to get rid of bound values for the publication field,
        // so we can see the new setting
        $this->form = new aEventForm($this->a_event);
      }
    }
    if (!$request->isXmlHttpRequest())
    {
      $this->setTemplate('edit');
    }
  }
  
  protected function setAEventForUser()
  {
    $request = $this->getRequest();
    if ($this->getUser()->hasCredential('admin'))
    {
      $this->a_event = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_event = Doctrine::getTable('aEvent')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
  }
  
  public function executeRedirect()
  {
    $aEvent = $this->getRoute()->getObject();
    aRouteTools::pushTargetEnginePage($aEvent->findBestEngine());
    $url = $this->generateUrl('a_event_post', $aEvent);
    $this->redirect($url);
  }

  public function executeCategories()
  {
    $this->redirect('@a_blog_category_admin');
  }

  public function executeIndex(sfWebRequest $request)
  {
    if(!aPageTable::getFirstEnginePage('aEvent'))
    {
      $this->setTemplate('engineWarning');
    }

    parent::executeIndex($request);
    aBlogItemTable::populatePages($this->pager->getResults());
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->setAEventForUser();
    $this->forward404Unless($this->a_event);
    $this->form = new aEventForm($this->a_event);
		// Retrieve the tags currently assigned to the event for the inlineTaggableWidget
		$this->existingTags = $this->form->getObject()->getTags();
		// Retrieve the 10 most popular tags for the inlineTaggableWidget
    $this->popularTags = TagTable::getAllTagNameWithCount(null, array('model' => 'aEvent', 'sort_by_popularity' => true), false, 10);

    aBlogItemTable::populatePages(array($this->a_event));
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
}