<?php
	$a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
?>

<ul class="a-blog-item-meta">
  <li class="post-date"><?php echo aDate::pretty($a_blog_post['published_at']); ?></li>
  <li class="post-author">
		<?php include_partial('aBlog/author', array('a_blog_post' => $a_blog_post)) ?>
	</li>
	<?php  if (sfConfig::get('app_aBlog_disqus_enabled')): ?>
	<li><a class="disqus-comment-count" href="<?php echo url_for('a_blog_post', $a_blog_post) ?>#disqus_thread">0</a></li>   
	<?php endif ?>
</ul>
