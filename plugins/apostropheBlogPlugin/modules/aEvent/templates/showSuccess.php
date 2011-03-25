<?php
  // Compatible with sf_escaping_strategy: true
  $aEvent = isset($aEvent) ? $sf_data->getRaw('aEvent') : null;
  $blogCategories = isset($blogCategories) ? $sf_data->getRaw('blogCategories') : null;
  $dateRange = isset($dateRange) ? $sf_data->getRaw('dateRange') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
?>

<?php slot('og-meta') ?>
<?php // og-meta is meta information for Facebook that gets read when something is shared with Add This (or anything else)  ?>
<meta property="og:title" content="<?php echo $aEvent->getTitle() ?>"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="<?php echo url_for('a_event', $aEvent, true) ?>"/>
<?php $items = $aEvent->getMediaForArea('blog-body', 'image', 1) ?>
<?php if (count($items)): ?>
	<?php foreach ($items as $item): ?>
<meta property="og:image" content="<?php echo $item->getImgSrcUrl(400, false, 's', 'jpg', true) ?>"/>	
	<?php endforeach ?>
<?php endif ?>
<meta property="og:site_name" content="<?php echo sfContext::getInstance()->getResponse()->getTitle(); ?>"/>
<meta property="og:description" content="<?php echo $aEvent->getTextForArea('blog-body', 25) ?>"/>
<?php end_slot() ?>

<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
	<div class="a-ui a-subnav-wrapper blog clearfix">
		<div class="a-subnav-inner">
	    <?php include_component('aBlog', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'info' => $info, 'url' => 'aEvent/index', 'searchLabel' => a_('Search Events'), 'newLabel' => a_('New Event'), 'newModule' => 'aEventAdmin', 'newComponent' => 'newEvent', 'url' => 'aEvent/index', 'calendar' => $calendar)) ?>
	  </div> 
	</div>
<?php end_slot() ?>

<div id="a-blog-main" class="a-blog-main clearfix">
	<?php echo include_partial('aEvent/post', array('a_event' => $aEvent, 'preview' => $preview)) ?>
</div>