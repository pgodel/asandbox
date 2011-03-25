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
	    <?php include_component('aBlog', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'info' => $info, 'url' => 'aEvent/index', 'searchLabel' => a_('Search Events'), 'newLabel' => a_('New Event'), 'newModule' => 'aEventAdmin', 'newComponent' => 'newEvent', 'calendar' => $calendar)) ?>
	  </div> 
	</div>
<?php end_slot() ?>

<div id="a-blog-main" class="a-blog-main clearfix">
  
	<div class="a-ui a-blog-heading">
		<?php ($page) ? $slots = $page->getArea('blog-heading') : $slots = array() ?>
		<?php if (count($slots) || $page->userHasPrivilege('edit')): ?>
	  	<?php a_area('blog-heading', array('areaLabel' => a_('Add Events Heading'), 'allowed_types' => array('aRichText', 'aSlideshow', 'aSmartSlideshow'))) ?>
		<?php endif ?>
 		<?php include_partial('aBlog/filters', array('type' => a_('event'), 'typePlural' => a_('events'), 'url' => 'aEvent/index', 'count' => $pager->count(), 'params' => $params)) ?>
	</div>

  <?php if ($pager->haveToPaginate()): ?>
  	<?php include_partial('aBlog/pager', array('max_per_page' => $max_per_page, 'pager' => $pager, 'pagerUrl' => url_for('aEvent/index?' . http_build_query($params['pagination'])))) ?>
  <?php endif ?>

  <?php foreach ($pager->getResults() as $a_event): ?>
  	<?php echo include_partial('aEvent/post', array('a_event' => $a_event)) ?>
  	<hr />
  <?php endforeach ?>

  <?php if ($pager->haveToPaginate()): ?>
  	<?php include_partial('aBlog/pager', array('max_per_page' => $max_per_page, 'pager' => $pager, 'pagerUrl' => url_for('aEvent/index?' . http_build_query($params['pagination'])))) ?>
  <?php endif ?>

</div>