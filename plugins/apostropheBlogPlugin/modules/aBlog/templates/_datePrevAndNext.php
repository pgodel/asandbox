<?php $url = $sf_data->getRaw('url') ?>
<?php $params = $sf_data->getRaw('params') ?>

<?php if ($sf_params->get('year')): ?>
  <?php if (sfConfig::get('app_a_pretty_english_dates')): ?>
	  <?php if ($sf_params->get('day')): ?>
	    <?php $prevLabel = aDate::pretty($params['prev']['year'] . '-' . $params['prev']['month'] . '-' . $params['prev']['day']) ?>
	    <?php $nextLabel = aDate::pretty($params['next']['year'] . '-' . $params['next']['month'] . '-' . $params['next']['day']) ?>
	  <?php elseif ($sf_params->get('month')): ?>
	    <?php $prevLabel = aDate::pretty($params['prev']['year'] . '-' . $params['prev']['month'] . '-01') ?>
	    <?php $prevLabel = preg_replace('/\s+\d+/', '', $prevLabel) ?>
	    <?php $nextLabel = aDate::pretty($params['next']['year'] . '-' . $params['next']['month'] . '-01') ?>
	    <?php $nextLabel = preg_replace('/\s+\d+/', '', $nextLabel) ?>
	  <?php else: ?>
	    <?php $prevLabel = $params['prev']['year'] ?>
	    <?php $nextLabel = $params['next']['year'] ?>
	  <?php endif ?>
  <?php else: ?>
	  <?php if ($sf_params->get('day')): ?>
	    <?php $prevLabel = $params['prev']['year'] . '-' . $params['prev']['month'] . '-' . $params['prev']['day'] ?>
	    <?php $nextLabel = $params['next']['year'] . '-' . $params['next']['month'] . '-' . $params['next']['day'] ?>
	  <?php elseif ($sf_params->get('month')): ?>
	    <?php $prevLabel = $params['prev']['year'] . '-' . $params['prev']['month'] ?>
	    <?php $nextLabel = $params['next']['year'] . '-' . $params['next']['month'] ?>
	  <?php else: ?>
	    <?php $prevLabel = $params['prev']['year'] ?>
	    <?php $nextLabel = $params['next']['year'] ?>
	  <?php endif ?>
	<?php endif ?>
	<ul class="a-ui a-controls a-blog-browser-controls">
  	<li><?php echo a_button($prevLabel, url_for($url.'?'.http_build_query($params['prev'])), array('icon','a-arrow-left', 'alt', 'no-bg')) ?></li>
  	<li><?php echo a_button($nextLabel, url_for($url.'?'.http_build_query($params['next'])), array('icon','a-arrow-right', 'alt', 'no-bg', 'icon-right')) ?></li>
	</ul>
<?php endif ?>
