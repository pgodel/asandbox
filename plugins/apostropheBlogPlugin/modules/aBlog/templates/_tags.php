<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null;
	$type = $aBlogItem->getType();
?>

<?php // What if we're currently on an engine page that doesn't display this category? ?>
<?php // Don't enable this until we have reasonable behavior in that situation. Right now ?>
<?php // you would see nothing. Something like pushing a better engine page is needed but only ?>
<?php // when the current one absolutely will not do. Remember that we might not be on an engine ?>
<?php // page right now at all ?>
<?php if (0): ?>
  <?php if ((count($aBlogItem->getCategories()) != 0)): ?>
  <div class="a-blog-item-tags tags">
  	<span class="a-blog-item-tags-label">Categories:</span>
  		<?php $i=1; foreach ($aBlogItem->getCategories() as $cat): ?>
  			<?php echo link_to($cat->name, aUrl::addParams((($type == 'post') ? 'aBlog' : 'aEvent' ).'/index', array('cat' => $cat->slug))) ?><?php echo (($i < count($aBlogItem->getCategories())) ? ', ':'')?>
  		<?php $i++; endforeach ?>
  </div>
  <?php endif ?>
<?php endif ?>

<?php if ((count($aBlogItem->getTags()) != 0)): ?>
<div class="a-blog-item-tags tags">
	<span class="a-blog-item-tags-label">Tags:</span>
		<?php $i=1; foreach ($aBlogItem->getTags() as $tag): ?>
			<?php echo link_to($tag, aUrl::addParams((($type == 'post') ? 'aBlog' : 'aEvent' ).'/index', array('tag' => $tag))) ?><?php echo (($i < count($aBlogItem->getTags())) ? ', ':'')?>
		<?php $i++; endforeach ?>
</div>
<?php endif ?>