<?php
  // Compatible with sf_escaping_strategy: true
  $blogCategories = isset($blogCategories) ? $sf_data->getRaw('blogCategories') : null;
  $dateRange = isset($dateRange) ? $sf_data->getRaw('dateRange') : null;
  $pager = isset($pager) ? $sf_data->getRaw('pager') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
?>
<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
	<div class="a-ui a-subnav-wrapper blog clearfix">
		<div class="a-subnav-inner">
	    <?php include_component('aBlog', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'info' => $info, 'url' => 'aBlog/index', 'searchLabel' => a_('Search Posts'), 'newLabel' => a_('New Post'), 'newModule' => 'aBlogAdmin', 'newComponent' => 'newPost')) ?>
	  </div> 
	</div>
<?php end_slot() ?>

<div id="a-blog-main" class="a-blog-main clearfix">

	<div class="a-ui a-blog-heading">
		<?php ($page) ? $slots = $page->getArea('blog-heading') : $slots = array() ?>
		<?php if (count($slots) || $page->userHasPrivilege('edit')): ?>
		  <?php a_area('blog-heading', array('areaLabel' => a_('Add Blog Heading'), 'allowed_types' => array('aRichText', 'aSlideshow', 'aSmartSlideshow'))) ?>
		<?php endif ?>
  	<?php include_partial('aBlog/filters', array('type' => a_('post'), 'typePlural' => a_('posts'),  'url' => 'aBlog/index', 'count' => $pager->count(), 'params' => $params)) ?>
	</div>

  <?php if ($pager->haveToPaginate()): ?>
  	<?php include_partial('aBlog/pager', array('max_per_page' => $max_per_page, 'pager' => $pager, 'pagerUrl' => url_for('aBlog/index?' . http_build_query($params['pagination'])))) ?>
  <?php endif ?>

  <?php foreach ($pager->getResults() as $a_blog_post): ?>
  	<?php echo include_partial('aBlog/post', array('a_blog_post' => $a_blog_post)) ?>
  	<hr />
  <?php endforeach ?>

  <?php if ($pager->haveToPaginate()): ?>
  	<?php include_partial('aBlog/pager', array('max_per_page' => $max_per_page, 'pager' => $pager, 'pagerUrl' => url_for('aBlog/index?' . http_build_query($params['pagination'])))) ?>
	<?php endif ?>
		  
</div>