<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
  use_helper('a');
?>
<?php // When events are imported it's not uncommon not to have an author. Also deleting a person might not delete ?>
<?php // their blog items - you could argue the merits either way. Be tolerant ?>
<?php if ($a_event->Author): ?>
	<?php echo link_to($a_event->Author, '@a_event_admin_addFilter?name=author_id&value='.$a_event->Author->id, 'post=true') ?>
<?php else: ?>
  <?php echo link_to("No Author", '@a_event_admin_addFilter?name=author_id&value=-', 'post=true') ?>
<?php endif ?>
