<?php use_helper('a') ?>
<?php $options = $sf_data->getRaw('options') ?>
<?php $embed = $sf_data->getRaw('embed') ?>
<?php $item = $sf_data->getRaw('item') ?>
<?php $imgsrc = $item->getImgSrcUrl(
	a_get_option($options, 'width', false), 
 	a_get_option($options, 'height', false), 
 	a_get_option($options, 'resizeType', 's'), 
 	a_get_option($options, 'format', 'jpg'), 
 	false
); ?>			

<ul>
  <li class="a-slideshow-image" style="background-image:url(<?php echo $imgsrc ?>);<?php echo ($n==0)? 'display:block':'' ?>"><?php echo $embed ?></li>
  <?php if ($options['title']): ?>
    <li class="a-slideshow-meta a-slideshow-title"><?php echo $item->title ?></li>
  <?php endif ?>
  <?php if ($options['description']): ?>
    <li class="a-slideshow-meta a-slideshow-description"><div class="a-slideshow-description-wrapper"><?php echo $item->description ?></div></li>
  <?php endif ?>
</ul>