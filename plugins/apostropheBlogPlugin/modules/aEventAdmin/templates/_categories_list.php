<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
?>
<?php foreach($a_event->Categories as $category): ?>
<?php echo link_to($category->name, '@a_event_admin_addFilter?name=categories_list&value='.$category->id, 'post=true') ?> 
<?php endforeach ?>