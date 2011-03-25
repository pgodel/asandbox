<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : null;
  $existingTags = isset($existingTags) ? $sf_data->getRaw('existingTags') : null;
?>
<?php use_helper("a") ?>
<?php include_partial('aEventAdmin/form', array('form' => $form, 'a_event' => $a_event, 'popularTags' => $popularTags, 'existingTags' => $existingTags)) ?>
<?php include_partial('a/globalJavascripts') ?>
