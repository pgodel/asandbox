<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
?>
<?php use_helper("a") ?>
<?php $catClass = ""; foreach ($a_blog_post->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>
<div class="a-blog-item post <?php echo $a_blog_post->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?>">

	<?php if ($a_blog_post->userHasPrivilege('edit')): ?>
	  <ul class="a-ui a-controls a-blog-post-controls">
			<li>
				<?php echo a_button(a_('Edit'), url_for('a_blog_admin_edit', $a_blog_post), array('icon','a-edit','lite','alt','no-label')) ?>
			</li>
		</ul>
	<?php endif ?>
	
	<?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post, 'edit' => false)) ?>

</div>


