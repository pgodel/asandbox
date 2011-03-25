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

<?php // Instead of passing in a bunch of variables to the area from the aInsetAreaSlot ?> 
<?php // Let's just allow for an area template file that can be manipulated like any typical area ?>
<div class="a-inset-area-container" style="width:<?php echo $options['width'] ?>px;">
<?php include_partial('aInsetAreaSlot/'.$options['areaTemplate'], array(
	'editable' => $editable,
	'name' => $name,
	'options' => $options,
	'areaOptions' => $areaOptions, 	
	'page' => $page,
	'pageid' => $pageid,
	'permid' => $permid,
	'slot' => $slot,
	'slug' => $slug,
)) ?>
</div>

<?php if ($options['value']): ?>
  <?php echo $options['value'] ?>
<?php endif ?>