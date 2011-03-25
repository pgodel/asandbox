<?php

class BaseaGroupAdminFilter extends sfGuardGroupFormFilter
{
  public function configure()
  {
    // TODO: it would be nice to have blog_categories_list and other things without writing 
    // code specific to other plugins here. Use an event? My main goal in limiting this list
    // was to prevent memory usage from exploding when you add more relations
    $this->useFields(array('name', 'created_at'));
  }
}
