<?php

/**
 * Base actions for the aBlogPlugin aBlog module.
 *
 * @package     aBlogPlugin
 * @subpackage  aBlog
 * @author      P'unk Avenue
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BaseaBlogActions extends aEngineActions
{
  protected $modelClass = 'aBlogPost';
  protected $slugStem = '@a_blog_search_redirect';
  // The application of the various filters (date, category, tag, search) is done
  // by aBlogToolkit::filterForEngine. That's where the important bits are

  public function getFilterForEngineParams()
  {
    $request = $this->getRequest();
    return array(
      'q' => $request->getParameter('q'),      
      'categoryIds' => aArray::getIds($this->page->Categories),
      'categorySlug' => $request->getParameter('cat'),
      'tag' => $request->getParameter('tag'),
      'slugStem' => $this->slugStem,
      'year' => $request->getParameter('year'),
      'month' => $request->getParameter('month'),
      'day' => $request->getParameter('day'),
      'byPublishedAt' => true);
  }
  
  public function preExecute()
  {
    parent::preExecute();
    $request = $this->getRequest();
    $this->info = aBlogToolkit::filterForEngine($this->getFilterForEngineParams());
  }

  protected function buildQuery($request)
  {
    // We already know what page ids are relevant, now we're fetching author
    // information. There's another method implicitly called later to populate
    // all of the blog content for the posts
    $q = Doctrine::getTable($this->modelClass)->createQuery()
      ->leftJoin($this->modelClass.'.Author a');
    if (count($this->info['pageIds']))
    {
      // We have page ids, so we need a join to figure out which blog items we want.
      // Doctrine doesn't have a withIn mechanism that takes a nice clean array, but we
      // know these are clean IDs 
      $q->innerJoin($this->modelClass.'.Page p WITH p.id IN (' . implode(',', $this->info['pageIds']) . ')');
      // Oops: there is NO ordering with an IN clause alone, you must make that explicit
      aDoctrine::orderByList($q, $this->info['pageIds'], 'p');
      // When you call aDoctrine::orderByList you must have an explicit select clause of your own as the
      // default 'select everything' behavior of Doctrine goes away as soon as that method calls addSelect
      $q->addSelect($q->getRootAlias() . '.*, a.*, p.*');
    }
    else
    {
      $q->where('0 <> 0');
    }
    return $q;
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->buildParams();
    $this->max_per_page = $this->getUser()->getAttribute('max_per_page', sfConfig::get('app_aBlog_max_per_page', 20), 'apostropheBlog_prefs');
    $pager = new sfDoctrinePager($this->modelClass);
    $pager->setMaxPerPage($this->max_per_page);
    $pager->setQuery($this->buildQuery($request));
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();

    $this->pager = $pager;

    aBlogItemTable::populatePages($pager->getResults());

    if($request->hasParameter('year') || $request->hasParameter('month') || $request->hasParameter('day') || $request->hasParameter('cat') || $request->hasParameter('tag'))
    {
      // Forbid combinations of filters for bots like Google. This prevents aggressive overspidering
      // of the same data
      $this->getResponse()->addMeta('robots', 'noarchive, nofollow');
    }

    if($this->getRequestParameter('feed', false))
    {
      $this->getFeed();
      return sfView::NONE;
    }
		
    return $this->pageTemplate;
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->buildParams();
    $this->dateRange = '';
    $this->aBlogPost = $this->getRoute()->getObject();
    $this->forward404Unless($this->aBlogPost);
    $this->forward404Unless($this->aBlogPost['status'] == 'published' || $this->getUser()->isAuthenticated());
		$this->preview = $this->getRequestParameter('preview');
    aBlogItemTable::populatePages(array($this->aBlogPost));
    
    return $this->pageTemplate;
  }

  public function buildParams()
  {
    $request = $this->getRequest();
    $this->params = array();

    // set our parameters for building pagination links
    $this->params['pagination']['year']  = $this->getRequestParameter('year');
    $this->params['pagination']['month'] = $this->getRequestParameter('month');
    $this->params['pagination']['day']   = $this->getRequestParameter('day');

    $date = strtotime($this->getRequestParameter('year', date('Y')).'-'.$this->getRequestParameter('month', date('m')).'-'.$this->getRequestParameter('day', date('d')));

    $this->dateRange = '';
    // set our parameters for building links that browse date ranges
    if ($this->getRequestParameter('day'))
    {
      $next = strtotime('tomorrow', $date);
      $this->params['next'] = array('year' => date('Y', $next), 'month' => date('m', $next), 'day' => date('d', $next), 'cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'q' => $request->getParameter('q'));

      $prev = strtotime('yesterday', $date);
      $this->params['prev'] = array('year' => date('Y', $prev), 'month' => date('m', $prev), 'day' => date('d', $prev), 'cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'q' => $request->getParameter('q'));

      $this->dateRange = 'day';
    }
    else if ($this->getRequestParameter('month'))
    {
      $next = strtotime('next month', $date);
      $this->params['next'] = array('year' => date('Y', $next), 'month' => date('m', $next), 'cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'q' => $request->getParameter('q'));

      $prev = strtotime('last month', $date);
      $this->params['prev'] = array('year' => date('Y', $prev), 'month' => date('m', $prev), 'cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'q' => $request->getParameter('q'));

      $this->dateRange = 'month';
    }
    else
    {
      $next = strtotime('next year', $date);
      $this->params['next'] = array('year' => date('Y', $next), 'cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'q' => $request->getParameter('q'));

      $prev = strtotime('last year', $date);
      $this->params['prev'] = array('year' => date('Y', $prev), 'cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'q' => $request->getParameter('q'));

      if ($this->getRequestParameter('year'))
      {
        $this->dateRange = 'year';
      }
    }

    // set our parameters for building links that set the date ranges and
    // keep other filters alive as well
    $this->params['day'] = array('year' => date('Y', $date), 'month' => date('m', $date), 'day' => date('d', $date), 'cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'q' => $request->getParameter('q'));
    $this->params['month'] = array('year' => date('Y', $date), 'month' => date('m', $date), 'cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'q' => $request->getParameter('q'));
    $this->params['year'] = array('year' => date('Y', $date), 'cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'q' => $request->getParameter('q'));
    $this->params['nodate'] = array('cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'q' => $request->getParameter('q'));

    $this->addFilterParams('cat');
    $this->addFilterParams('tag');
    $this->addFilterParams('q');
  }

  public function addFilterParams($name)
  {
    // if there is a filter request, we need to add it to our date params
    if ($this->getRequestParameter($name))
    {
      foreach ($this->params as &$params)
      {
        $params[$name] = $this->getRequestParameter($name);
      }
    }

    // set an array for building a link to this filter (we don't want it to already have the filter in there)
    $this->params[$name] = $this->params['pagination'];
    unset($this->params[$name][$name]);
  }

  public function getFeed()
  {
    $this->articles = $this->pager->getResults();
    aBlogItemTable::populatePages($this->articles);

    $title = sfConfig::get('app_aBlog_feed_title', $this->page->getTitle());

    $this->feed = sfFeedPeer::createFromObjects(
      $this->articles,
      array(
        'format'      => 'rss',
        'title'       => $title,
        'link'        => '@a_blog',
        'authorEmail' => sfConfig::get('app_aBlog_feed_author_email'),
        'authorName'  => sfConfig::get('app_aBlog_feed_author_name'),
        'routeName'   => '@a_blog_post',
        'methods'     => array('description' => 'getFeedText')
      )
    );

    $this->getResponse()->setContent($this->feed->asXml());
  }
}
