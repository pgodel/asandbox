<?php
abstract class BaseaEventSlotComponents extends BaseaBlogSlotComponents
{
  protected $modelClass = 'aEvent';
  protected $formClass = 'aEventSlotForm';
  
  public function getQuery()
  {
    $q = parent::getQuery();
    if (!$this->handSelected)
    {
      // Events automatically age off the slot. Convert NOW to DATE or
      // >= won't work
      $q->andWhere('end_date >= DATE(NOW())');
    }
    $q->orderBy('start_date asc, start_time asc');
    return $q;
  }
}
