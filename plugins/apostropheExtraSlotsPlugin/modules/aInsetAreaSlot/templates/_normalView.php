<?php
  // Compatible with sf_escaping_strategy: true
  $editable = isset($editable) ? $sf_data->getRaw('editable') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $areaOptions = isset($areaOptions) ? $sf_data->getRaw('areaOptions') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $slug = isset($slug) ? $sf_data->getRaw('slug') : null;
?>
<?php use_helper('a') ?>

<?php if ($editable): ?>
  <?php slot("a-slot-controls-$pageid-$name-$permid") ?>
		<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page, 'controlsSlot' => false, 'label' => a_get_option($options, 'editLabel', a_('Edit')))) ?>
  <?php end_slot() ?>
<?php endif ?>

<div class="a-inset-area-slot <?php echo aTools::slugify($options['insetTemplate']) ?>">
<?php include_partial('aInsetAreaSlot/'.$options['insetTemplate'].'Template',
	array(
		'editable' => $editable,
		'name' => $name,
		'options' => $options,
		'areaOptions' => $areaOptions, 
		'page' => $page,
		'pageid' => $pageid,
		'permid' => $permid,
		'slot' => $slot,
		'slug' => $slug,
	)
) ?>
</div>