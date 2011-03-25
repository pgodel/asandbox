<?php
  // Compatible with sf_escaping_strategy: true
  $dimensions = isset($dimensions) ? $sf_data->getRaw('dimensions') : null;
  $constraints = isset($constraints) ? $sf_data->getRaw('constraints') : null;
  $editable = isset($editable) ? $sf_data->getRaw('editable') : null;
  $item = isset($item) ? $sf_data->getRaw('item') : null;
  $itemId = isset($itemId) ? $sf_data->getRaw('itemId') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $slug = isset($slug) ? $sf_data->getRaw('slug') : null;
  $embed = isset($embed) ? $sf_data->getRaw('embed') : null;
?>

<div class="a-inset-image" style="width:<?php echo $dimensions['width'] ?>px;">
<?php if ($item): ?>
	<?php $embed = str_replace(array("_WIDTH_", "_HEIGHT_", "_c-OR-s_", "_FORMAT_"), array($dimensions['width'], $dimensions['height'], $dimensions['resizeType'],  $dimensions['format']), $embed) ?>
	<?php echo $embed ?>
	<?php if ($options['title']): ?>
		<p class="a-inset-image-title"><?php echo $item->getTitle() ?></p>		
	<?php endif ?>
<?php else: ?>
	<?php include_partial('aImageSlot/placeholder', array('placeholderText' => a_("Choose an Image"), 'options' => $options)) ?>	
<?php endif ?>
</div>

<?php if ($options['description']): ?>
  <?php echo $options['description'] ?>
<?php endif ?>