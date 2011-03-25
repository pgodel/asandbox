<?php
	$a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
?>

<span class="a-blog-item-meta-label"><?php echo __('Posted By:', array(), 'apostrophe') ?></span>
<?php if ($a_blog_post->getAuthor()): ?>
  <?php echo ($a_blog_post->getAuthor()->getName()) ? $a_blog_post->getAuthor()->getName() : $a_blog_post->getAuthor()  ?>
<?php endif ?>
