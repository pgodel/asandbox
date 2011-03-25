<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null;
	$type = $aBlogItem->getType();
?>

<?php if ($addthis_username = sfConfig::get('app_aBlog_add_this')): ?>
	<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style"
	addthis:url="<?php echo url_for( (($type == 'post') ? 'a_blog_post':'a_event_post' ), $aBlogItem, true) ?>"
	addthis:title="<?php echo $aBlogItem['title'] ?>">
		<a href="http://addthis.com/bookmark.php?v=250&amp;username=<?php echo $addthis_username ?>" class="addthis_button_compact">Share</a>
		<span class="addthis_separator">|</span>
		<a class="addthis_button_facebook"></a>
		<a class="addthis_button_myspace"></a>
		<a class="addthis_button_google"></a>
		<a class="addthis_button_twitter"></a>
	</div>
	<!-- AddThis Button END -->	
	<?php use_javascript('http://s7.addthis.com/js/250/addthis_widget.js#username='.$addthis_username) ?>
<?php endif ?>
