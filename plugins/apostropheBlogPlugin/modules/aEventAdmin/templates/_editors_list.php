<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
?>
<?php foreach($a_event->Editors as $editor): ?>
<?php echo link_to($editor->username, '@a_event_admin_addFilter?name=editors_list&value='.$editor->id, 'post=true') ?> 
<?php endforeach ?>