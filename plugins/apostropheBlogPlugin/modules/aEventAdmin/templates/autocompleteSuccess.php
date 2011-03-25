<?php
  // Compatible with sf_escaping_strategy: true
  $aEvents = isset($aEvents) ? $sf_data->getRaw('aEvents') : null;
?>
<?php foreach($aEvents as $aEvent): ?>
<?php echo $aEvent['title'] ?> <?php echo $aEvent['start_date']?>|<?php echo $aEvent['id'] ?>|<?php echo $aEvent['title'] ?>
<?php endforeach ?>