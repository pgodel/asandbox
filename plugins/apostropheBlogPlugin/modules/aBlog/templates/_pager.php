<?php $pager = $sf_data->getRaw('pager') ?>
<?php $pagerUrl = $sf_data->getRaw('pagerUrl') ?>
<?php $max_per_page = $sf_data->getRaw('max_per_page') ?>

<div class="a-ui a-media-library-controls">
  <?php include_partial('aPager/pager', array('pager' => $pager, 'pagerUrl' => $pagerUrl)) ?>

<?php /* We replaced the need for this with the sentence interface ?> 
  <ul class="a-ui a-controls a-media-footer-controls">
  	<li class="a-media-footer-item-count"><?php echo $pager->count() ?> items</li>
  	<li class="a-media-footer-separator a">|</li>
  	<li class="a-media-footer-view-label">viewing</li>
  	<?php // In 1.6 perhaps we'll bring back multiple view options with different #s of posts, ?>
  	<?php // but right now the memory usage etc. is too high and it's not a good time to think about ?>
  	<?php // how best to implement low impact alternatives ?>
		<li class="a-media-footer-view-option"><?php echo $max_per_page ?></li>
  </ul>
<?php //*/ ?>

</div>