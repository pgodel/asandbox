<?php $a_blog_item = $sf_data->getRaw('a_blog_item') ?>
<?php // Saving either of these forms updates both (because title can affect slug) ?>
<?php use_helper('a') ?>
<form method="post" action="<?php echo url_for('a_blog_admin_updateTitle', $a_blog_item) ?>" id="a-blog-item-title-interface" class="a-blog-item-title-interface a-ui clearfix">
  <?php // Titles are already entity-escaped (like all text slots), so don't double-escape them ?>
	<input type="text" name="title" class="a-title" value="<?php echo ($a_blog_item->title == 'untitled')? '':$a_blog_item->title ?>" />
  <ul class="a-ui a-controls blog-title">
    <li><?php echo a_anchor_submit_button(a_('Save'), array('a-save', 'big')) ?></li>
    <li><a href="#" class="a-btn icon a-cancel no-label big"><span class="icon"></span><?php echo a_('Cancel') ?></a></li>
  </ul>
</form>

<form method="post" action="<?php echo url_for('a_blog_admin_updateSlug', $a_blog_item) ?>" id="a-blog-item-permalink-interface" class="a-blog-item-permalink-interface a-ui clearfix">
	<div class="a-blog-item-permalink-wrapper url">
    <span><?php echo aTools::urlForPage($a_blog_item->findBestEngine()->getSlug()).'/' ?><?php echo date('Y/m/d/', strtotime($a_blog_item->getPublishedAt())) ?></span>
	</div>
	<div class="a-blog-item-permalink-wrapper slug">
		<input type="text" name="slug" class="a-slug" value="<?php echo a_entities($a_blog_item->slug) ?>">
	  <ul class="a-ui a-controls blog-slug">
	    <li><?php echo a_anchor_submit_button(a_('Save'), array('a-save', 'mini')) ?></li>
	    <li><a href="#" class="a-btn icon a-cancel no-label mini"><span class="icon"></span><?php echo a_('Cancel') ?></a></li>
	  </ul>
	</div>
</form>

<?php a_js_call('aBlogEnableTitle()') ?>
<?php a_js_call('aBlogEnableSlug()') ?>
