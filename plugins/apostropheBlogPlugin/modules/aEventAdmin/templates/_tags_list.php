<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
  $i = isset($i) ? $sf_data->getRaw('i') : null;
?>
<?php foreach($a_event->getTags() as $tag): ?>
<?php if(isset($i)) echo $i ?>
<?php echo link_to($tag, "@a_event_admin_addFilter?name=tags_list&value=$tag") ?>
<?php $i = ', ' ?>
<?php endforeach ?>
