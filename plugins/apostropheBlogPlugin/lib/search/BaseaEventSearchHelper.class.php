<?php

class BaseaEventSearchHelper
{
  public function filterUpdateLuceneIndex($args)
  {
    $slug = $args['stored']['slug_stored'];
    // We are only interested in the virtual pages associated with
    // this engine, leave the actual engine pages alone
    if (preg_match('/^@.*?(\d+)$/', $slug, $matches))
    {
      $id = $matches[1];
    }
    else
    {
      return $args;
    }
    $event = aEventTable::getInstance()->find($id);
    if (!$event)
    {
      return $args;
    }
    $args['stored']['start_date'] = $event->start_date;
    $args['stored']['start_time'] = $event->start_time;
    $args['stored']['end_date'] = $event->end_date;
    $args['stored']['end_time'] = $event->end_time;
    return $args;
  }
  public function getPartial()
  {
    return 'aEvent/searchResult';
  }
}
