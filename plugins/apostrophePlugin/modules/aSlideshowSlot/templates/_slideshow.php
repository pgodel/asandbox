<?php
  // Compatible with sf_escaping_strategy: true
  $id = isset($id) ? $sf_data->getRaw('id') : null;
  $items = isset($items) ? $sf_data->getRaw('items') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;

  $title = count($items) > 1 ? __('Click For Next Image', null, 'apostrophe') : false;
	$id = ($options['idSuffix']) ? $id.'-'.$options['idSuffix']:$id;
?>
<?php use_helper('a') ?>

<?php if (count($items)): ?>
	<ul id="a-slideshow-<?php echo $id ?>" class="a-slideshow clearfix transition-<?php echo $options['transition'] ?>"<?php echo ($options['transition'] == 'crossfade')? 'style="height:'.$options['height'].'px; width:'.$options['width'].'px;"':'' ?>>
	<?php $first = true; $n=0; foreach ($items as $item): ?>
	  <?php $dimensions = aDimensions::constrain(
	    $item->width, 
	    $item->height,
	    $item->format, 
	    array("width" => $options['width'],
	      "height" => $options['flexHeight'] ? false : $options['height'],
	      "resizeType" => $options['resizeType'])) ?>
		<?php // Implement maximum height ?>
		<?php if ($options['maxHeight']): ?>
			<?php if ($dimensions['height'] > $options['maxHeight']): ?>
			  <?php $dimensions = aDimensions::constrain(
			    $item->width,
			    $item->height,
			    $item->format, 
			    array("width" => false,
			      "height" => $options['maxHeight'],
			      "resizeType" => $options['resizeType'])) ?>
			<?php endif ?>
		<?php endif ?>
	  <?php $embed = str_replace(
	    array("_WIDTH_", "_HEIGHT_", "_c-OR-s_", "_FORMAT_"),
	    array($dimensions['width'], 
	      $dimensions['height'], 
	      $dimensions['resizeType'],
	      $dimensions['format']),
	    $item->getEmbedCode('_WIDTH_', '_HEIGHT_', '_c-OR-s_', '_FORMAT_', false)) ?>
	  <li class="a-slideshow-item" id="a-slideshow-item-<?php echo $id ?>-<?php echo $n ?>">
			<?php include_partial('aSlideshowSlot/'.$options['itemTemplate'], array('items' => $items, 'item' => $item, 'id' => $id, 'embed' => $embed, 'n' => $n,  'options' => $options)) ?>
		</li>
	<?php $first = false; $n++; endforeach ?>
	</ul>
<?php endif ?>

<?php if ($options['arrows'] && (count($items) > 1)): ?>
<ul id="a-slideshow-controls-<?php echo $id ?>" class="a-slideshow-controls">
	<li class="a-arrow-btn icon a-arrow-left"><span class="icon"></span><?php echo __('Previous', null, 'apostrophe') ?></li>
	<?php if ($options['position']): ?>
		<li class="a-slideshow-position">
			<span class="a-slideshow-position-head">1</span> of <span class="a-slideshow-position-total"><?php echo count($items); ?></span>
		</li>
	<?php endif ?>
	<li class="a-arrow-btn icon a-arrow-right"><span class="icon"></span><?php echo __('Next', null, 'apostrophe') ?></li>
</ul>
<?php endif ?>

<?php a_js_call('apostrophe.slideshowSlot(?)', array('debug' => false, 'id' => $id, 'position' => $options['position'], 'interval' => $options['interval'],  'transition' => $options['transition'], 'duration' => $options['duration'], 'title' => $title)) ?>