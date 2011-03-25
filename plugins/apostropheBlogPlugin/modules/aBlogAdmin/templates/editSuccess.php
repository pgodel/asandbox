<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $configuration = isset($configuration) ? $sf_data->getRaw('configuration') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $helper = isset($helper) ? $sf_data->getRaw('helper') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : null;
  $existingTags = isset($existingTags) ? $sf_data->getRaw('existingTags') : null;
?>
<?php use_helper("a") ?>
<?php include_partial('assets') ?>
<?php slot('body_class') ?>a-admin a-blog-admin <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?> <?php echo $a_blog_post['template'] ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
  <div class="a-ui a-subnav-wrapper a-admin-sidebar">
		<div class="a-subnav-inner">
	    <div id="a-ui a-admin-blog-post-form">
	      <form method="post" action="<?php echo url_for('a_blog_admin_update', $a_blog_post) ?>" id="a-admin-form" class="a-ui blog">
          <?php include_partial('aBlogAdmin/form', array('form' => $form, 'a_blog_post' => $a_blog_post, 'popularTags' => $popularTags, 'existingTags' => $existingTags)) ?>
        </form>
	    </div>
		</div>
  </div>
<?php end_slot() ?>

<?php slot('a-page-header') ?>
<div class="a-ui a-admin-header">
	<ul class="a-ui a-controls a-admin-controls">
		<li><a href="<?php echo url_for('@a_blog_admin'); ?>" class="a-btn big"><?php echo __('View All Posts', array(), 'apostrophe') ?></a></li>
     <?php include_partial('list_actions', array('helper' => $helper)) ?>
	</ul>
  <?php include_partial('aBlogAdmin/form_bar') ?>				
</div>
<?php end_slot() ?>

<div class="a-ui a-admin-container <?php echo $sf_params->get('module') ?>">

  <?php include_partial('flashes') ?>

	<div class="a-admin-content main">

    <div id="a-blog-title-and-slug">
      <?php include_partial('aBlogAdmin/titleAndSlug', array('a_blog_item' => $a_blog_post)) ?>
    </div>

		<div class="a-blog-item post<?php echo ($a_blog_post->hasMedia())? ' has-media':''; ?> <?php echo $a_blog_post->getTemplate() ?>">
  		<?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post, 'edit' => true)) ?>
		</div>

  </div>

  <div class="a-admin-footer">
    <?php include_partial('form_footer', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

</div>
