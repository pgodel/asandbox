<?php
  // Compatible with sf_escaping_strategy: true
  $aEvent = isset($aEvent) ? $sf_data->getRaw('aEvent') : null;
?>
<?php echo link_to($aEvent['title'], 'a_event_post', $aEvent) ?> by <?php echo $aEvent->Author ?>
<br/>
<?php include_partial('aEvent/meta', array('aEvent' => $aEvent)) ?>
<br/><br/>
<?php foreach($aEvent->Page->getArea('blog-body') as $slot): ?>
<?php // getBasicHtml has basic formatting, which RSS does allow ?>
<?php echo $slot->getBasicHtml() ?>
<?php endforeach ?>
