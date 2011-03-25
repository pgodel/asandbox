<?php $result = isset($result) ? $sf_data->getRaw('result') : null; ?>

<?php $url = $result->url ?>
<dt class="result-title <?php echo $result->class ?>">
	<?php echo link_to($result->title, $url) ?> 
</dt>
<dd class="result-daterange"><?php include_partial('aEvent/dateRange', array('aEvent' => $result)) ?></dd>
<dd class="result-summary"><?php echo $result->summary ?></dd>
<dd class="result-url"><?php echo link_to($url, $url) ?></dd>
