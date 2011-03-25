  // The primary sort needs to use orderBy so it overrides the default sort of 
  // the createQuery() method. The secondary sort can use addOrderBy. The last
  // sort should be the designated "minor sort" for this type. Right now we're
  // using published_at desc for both blog and events - a natural order for event
  // admin is actually quite difficult to arrive at in another way, we'd have to
  // somehow skip them to today's page as well as ascending order by start date -
  // while reverse order of publication date does give a good sense of "what I have
  // worked on lately" and will do for 1.5. -Tom

  protected function addSortQuery($query)
  {
    $sorts = array();
    if (array(null, null) != ($sort = $this->getSort()))
    {
      // When sorting by start_date we must have a secondary sort of start time
      $sorts[] = $sort[0] . ' ' . $sort[1];
      if ($sort[0] === 'start_date')
      {
        $sorts[] = 'start_time ' . $sort[1];
      }
    }
    foreach ($this->minorSorts as $sort)
    {
      $sorts[] = $sort;
    }
    $first = true;
    foreach ($sorts as $sort)
    {
      if ($first)
      {
        $query->orderBy($sort);
        $first = false;
      }
      else
      {
        $query->addOrderBy($sort);
      }
    }
  }

  protected function getSort()
  {
    if (!is_null($sort = $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.sort', null, 'admin_module')))
    {
      return $sort;
    }

    $this->setSort($this->configuration->getDefaultSort());

    return $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.sort', null, 'admin_module');
  }

  protected function setSort(array $sort)
  {
    if (!is_null($sort[0]) && is_null($sort[1]))
    {
      $sort[1] = 'asc';
    }

    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.sort', $sort, 'admin_module');
  }
