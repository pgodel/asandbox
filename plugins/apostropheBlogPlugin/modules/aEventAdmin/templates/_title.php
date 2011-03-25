<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
?>
<?php echo link_to($a_event->title, 'a_event_admin_edit', $a_event) ?>