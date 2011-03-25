<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
  $configuration = isset($configuration) ? $sf_data->getRaw('configuration') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $helper = isset($helper) ? $sf_data->getRaw('helper') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : null;
  $existingTags = isset($existingTags) ? $sf_data->getRaw('existingTags') : null;
?>
<?php use_helper("a") ?>
<?php include_partial('assets') ?>
<?php slot('body_class') ?>a-admin a-blog-admin <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?> <?php echo $a_event['template'] ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
  <div class="a-ui a-subnav-wrapper a-admin-sidebar">
		<div class="a-subnav-inner">
	    <div id="a-ui a-admin-blog-post-form">
	      <form method="post" action="<?php echo url_for('a_event_admin_update', $a_event) ?>" id="a-admin-form" class="a-ui blog">
          <?php include_partial('aEventAdmin/form', array('form' => $form, 'a_event' => $a_event, 'popularTags' => $popularTags, 'existingTags' => $existingTags)) ?>
        </form>
	    </div>
		</div>
  </div>
<?php end_slot() ?>

<?php slot('a-page-header') ?>
<div class="a-ui a-admin-header">
	<ul class="a-ui a-controls a-admin-controls">
		<li><a href="<?php echo url_for('@a_event_admin'); ?>" class="a-btn big"><?php echo __('View All Events', array(), 'apostrophe') ?></a></li>
     <?php include_partial('list_actions', array('helper' => $helper)) ?>
	</ul>
  <?php include_partial('aEventAdmin/form_bar') ?>				
</div>
<?php end_slot() ?>

<div class="a-ui a-admin-container <?php echo $sf_params->get('module') ?>">

	<div class="a-admin-content main">

    <div id="a-blog-title-and-slug">
      <?php include_partial('aBlogAdmin/titleAndSlug', array('a_blog_item' => $a_event)) ?>
    </div>

		<div class="a-blog-item event<?php echo ($a_event->hasMedia())? ' has-media':''; ?> <?php echo $a_event->getTemplate() ?>">
  		<?php include_partial('aEvent/'.$a_event->getTemplate(), array('a_event' => $a_event, 'edit' => true)) ?>
		</div>

  </div>

  <div class="a-admin-footer">
    <?php include_partial('form_footer', array('a_event' => $a_event, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

</div>
