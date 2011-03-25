<?php /* ?> 
This slot is deprecated as of Apostrophe 1.5
<?php //*/ ?>

<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
?>
<?php include_partial('a/simpleEditWithVariants', array('pageid' => $page->id, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page)) ?>

<?php if ($aBlogItem): ?>
	<?php $options['slideshowOptions']['idSuffix'] = 'aEventSingleSlot-'.$permid.'-'.$slot.'-'.$aBlogItem->getId(); ?>			
  <?php include_partial('aEventSingleSlot/post', array('aBlogItem' => $aBlogItem, 'options' => $options)) ?>
<?php endif ?>
