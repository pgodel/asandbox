<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $edit = isset($edit) ? $sf_data->getRaw('edit') : null;
	$admin = ($sf_params->get('module') == 'aBlogAdmin') ? true : false;
?>

<?php if (!$admin): ?>
	<h3 class="a-blog-item-title">
	  <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
		<?php if ($a_blog_post['status'] == 'draft'): ?>
			<span class="a-blog-item-status">&ndash; <?php echo a_('Draft') ?></span>
		<?php endif ?>
	</h3>
	<?php include_partial('aBlog/meta', array('a_blog_post' => $a_blog_post)) ?>
<?php endif ?>

<div class="a-blog-item-content">
	
	<?php a_area('blog-body', array(
	  'edit' => $edit, 'toolbar' => 'basic', 'slug' => $a_blog_post->Page->slug,
	  'allowed_types' => array('aRichText', 'aSlideshow', 'aVideo', 'aPDF'),
	  'type_options' => array(
	    'aRichText' => array('tool' => 'Main'),   
	    'aSlideshow' => array("width" => 480, "flexHeight" => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 480)),
			'aVideo' => array('width' => 480, 'flexHeight' => true, 'resizeType' => 's'),
			'aPDF' => array('width' => 480, 'flexHeight' => true, 'resizeType' => 's'),		
	))) ?>

	<?php a_area('blog-sidebar', array(
	  'edit' => $edit, 'toolbar' => 'basic', 'slug' => $a_blog_post->Page->slug,
	  'allowed_types' => array('aRichText', 'aSlideshow', 'aVideo', 'aPDF'),
	  'type_options' => array(
	    'aRichText' => array('tool' => 'Sidebar'),   
	    'aSlideshow' => array("width" => 220, "flexHeight" => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 180)),
			'aVideo' => array('width' => 220, 'flexHeight' => true, 'resizeType' => 's'), 
			'aPDF' => array('width' => 220, 'flexHeight' => true, 'resizeType' => 's'),				
	))) ?>

	<?php if (!$admin): ?>
		<?php include_partial('aBlog/tags', array('aBlogItem' => $a_blog_post)) ?>
		<?php include_partial('aBlog/addThis', array('aBlogItem' => $a_blog_post)) ?>
	<?php endif ?>

</div>
<?php slot('disqus_needed', 1) ?>