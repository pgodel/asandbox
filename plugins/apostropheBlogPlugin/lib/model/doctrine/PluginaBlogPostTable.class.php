<?php
/**
 */
class PluginaBlogPostTable extends aBlogItemTable
{
  private static $engineCategoryCache;
    
  public static function getInstance()
  {
    return Doctrine_Core::getTable('aBlogPost');
  }

  public function createQuery($alias = '')
  {
    $query = parent::createQuery($alias);
    $query->orderBy($query->getRootAlias().'.published_at desc');

    return $query;
  }

  public function getEngineCategories()
  {
    return aEngineTools::getEngineCategories('aBlog');
  }
  
  public function getCountByCategory()
  {
    $raw = Doctrine::getTable('aCategory')->createQuery('c')->innerJoin('c.aBlogItemToCategory etc')->innerJoin('etc.BlogItem b WITH b.type = ?', 'post')->select('c.name, c.slug, count(etc.blog_item_id) as num')->groupBy('etc.category_id')->orderBy('c.name ASC')->execute(array(), Doctrine::HYDRATE_ARRAY);
    $results = array();
    foreach ($raw as $info)
    {
      $results[$info['id']] = array('name' => $info['name'], 'slug' => $info['slug'], 'count' => $info['num']);
    }
    return $results;
  }
}