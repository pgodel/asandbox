<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : null;
  $existingTags = isset($existingTags) ? $sf_data->getRaw('existingTags') : null;
?>

<?php use_helper("a") ?>

<?php $v = $form['publication']->getValue() ?>
<?php $saveLabels = array('nochange' => a_('Update'), 'draft' => a_('Save'), 'publish' => a_('Publish'), 'schedule' => a_('Update')) ?>
<?php $saveLabel = $saveLabels[$form['publication']->getValue()] ?>
<?php // One tiny difference: if we move from something else *TO* schedule, label it 'Schedule' ?>
<?php $updateLabels = array('nochange' => a_('Update'), 'draft' => a_('Save'), 'publish' => a_('Publish'), 'schedule' => a_('Schedule')) ?>
<?php // Invoked by include_partial in the initial load of the form partial and also directly on AJAX updates of this section ?>
<div class="a-hidden">
	<?php echo $form->renderHiddenFields() ?>
</div>

<div class="published section a-form-row">
  <div class="post-save clearfix">
  	<?php echo a_anchor_submit_button($saveLabel, array('a-save', 'a-sidebar-button', 'a-save-blog-main','a-show-busy','big')) ?>
  </div>
</div>
<div class="status section a-form-row">
 	<h4><?php echo a_('Status') ?></h4>
	<div class="status-list option">
    <?php echo $form['publication']->render() ?>
  </div>
</div>
<div class="a-published-at-container">
	<div class="a-form-row">
  	<?php echo $form['published_at']->render() ?>
	</div>
 	<?php echo $form['published_at']->renderError() ?>
</div>  


<hr />

<?php // Event Date Range ?>

<div class="event-date section a-form-row">

	<div class="start_date">
		<h4>Start Date</h4>
		<div class="a-form-row all_day">
			<div class="a-form-field">
				<?php echo $form['all_day']->render() ?>
			</div>
			<?php echo $form['all_day']->renderLabel() ?>
			<?php echo $form['all_day']->renderError() ?>
		</div>		
		<?php echo $form['start_date']->render() ?>
		<?php echo $form['start_date']->renderError() ?>
		<div class="start_time">
    	<?php echo $form['start_time'] ?>
		</div>
		<?php // Errors at the end so they appear and appear correctly regardless ?>
		<?php // of whether it's an all day event or not. Experiment before changing this ?>
		<?php echo $form['start_date']->renderError() ?>
  	<?php echo $form['start_time']->renderError() ?>
	</div>
	
	<div class="end_date">
		<h4>End Date</h4>
		<?php echo $form['end_date']->render() ?>
		<div class="end_time">
    	<?php echo $form['end_time'] ?>
		</div>
		<?php // Errors at the end so they appear and appear correctly regardless ?>
		<?php // of whether it's an all day event or not. Experiment before changing this ?>
		<?php echo $form['end_date']->renderError() ?>
  	<?php echo $form['end_time']->renderError() ?>
	</div>
</div>

<?php // Location ?>
<hr />
<div class="location section a-form-row" id="location-section">
	<h4><?php echo a_('Location') ?></h4>
	<?php echo $form['location']->render() ?>
	<?php echo $form['location']->renderError() ?>
</div>

<?php // Author & Editors Section ?>
<hr />
<div class="author section a-form-row">

	<?php // Blog Post Author ?>
	<div class="post-author">
	  	<h4><?php echo a_('Author') ?>
			<?php if ($sf_user->hasCredential('admin')): ?>
				</h4>
				<div class="author_id option">
				<?php echo $form['author_id']->render() ?>
				<?php echo $form['author_id']->renderError() ?>
				</div>
			<?php else: ?>: <span><?php echo $a_event->Author ?></span></h4><?php endif ?>

	</div>

	<?php // Blog Post Editors ?>
  <?php if(isset($form['editors_list'])): ?>
	<div class="post-editors">

		<?php if (!count($a_event->Editors)): ?>
		  <a href="#" onclick="return false;" class="post-editors-toggle a-sidebar-toggle"><?php echo a_('allow others to edit this post') ?></a>
	  	<div class="post-editors-options option" id="editors-section">
		<?php else: ?>
			<hr/>
	  	<div class="post-editors-options option show-editors" id="editors-section">
		<?php endif ?>

	    <h4><?php echo a_('Editors') ?></h4>
	    <?php echo $form['editors_list']->render()?>
	    <?php echo $form['editors_list']->renderError() ?>

      </div>
    </div>
  </div>
  <?php endif ?>

	<?php // Blog Post Templates ?>
	<?php if(isset($form['template'])): ?>
	<hr />
	<div class="template section">
		<h4><?php echo a_('Template') ?></h4>
		<?php echo $form['template']->render() ?>
		<?php echo $form['template']->renderError() ?>
	</div>
	<?php endif ?>


	<?php // Blog Post Comments ?>
	<?php if(isset($form['allow_comments'])): ?>
	<hr />
	<div class="comments section">
		<h4><a href="#" class="allow_comments_toggle <?php echo ($a_event['allow_comments'])? 'enabled' : 'disabled' ?>"><span class="enabled" title="<?php echo a_('Click to disable comments') ?>"><?php echo a_('Comments are enabled') ?></span><span class="disabled" title="<?php echo a_('Click to enable comments') ?>"><?php echo a_('Comments are disabled') ?></span></a></h4>
		<div class="allow_comments option">
			<?php echo $form['allow_comments']->render() ?>
			<?php echo $form['allow_comments']->renderError() ?>
		</div>
	</div>
	<?php endif ?>
	
	<?php // Blog Post Categories ?>
	<hr />
	<div class="categories section a-form-row" id="categories-section">
		<h4><?php echo a_('Categories') ?></h4>
		<?php echo $form['categories_list']->render() ?>
		<?php $adminCategories = $form->getAdminCategories() ?>
		<?php if (count($adminCategories)): ?>
      <div class="a-form-row">
		    <?php echo 'Set by admin: ' . implode(',', $form->getAdminCategories()) ?>
		  </div>
		<?php endif ?>
		<?php echo $form['categories_list']->renderError() ?>
	</div>

	<?php // Blog Post Tags ?>
	<hr />
	<div class="tags section a-form-row">
	  <h4><?php echo a_('Tags') ?></h4>
		<div>
		<?php echo $form['tags']->render() ?>
		<?php echo $form['tags']->renderError() ?>
		<?php a_js_call('pkInlineTaggableWidget(?, ?)', '#a-blog-post-tags-input', array('popular-tags' => $popularTags, 'existing-tags' => $existingTags, 'typeahead-url' => url_for('taggableComplete/complete'), 'tags-label' => ' ')) ?>
		</div>
	</div>

	<hr />
	<div class="delete preview section a-form-row">
		<?php $engine = $a_event->findBestEngine(); ?>
	  <?php aRouteTools::pushTargetEnginePage($engine) ?>
		<?php echo link_to('<span class="icon"></span>'.a_('Preview'), 'a_blog_post', array('preview' => 1) + $a_event->getRoutingParams(), array('class' => 'a-btn icon a-search lite a-align-left', 'rel' => 'external')) ?>
	  <?php aRouteTools::popTargetEnginePage($engine->engine) ?>
	  <?php if($a_event->userHasPrivilege('delete')): ?>
		  <?php echo link_to('<span class="icon"></span>'.a_('Delete'), 'a_event_admin_delete', $a_event, array('class' => 'a-btn icon a-delete lite a-align-right', 'method' => 'delete', 'confirm' => a_('Are you sure you want to delete this event?'), )) ?>
	  <?php endif ?>
	</div>

</form>

<?php a_js_call('aBlogEnableForm(?)', array('update-labels' => $updateLabels, 'reset-url' => url_for('@a_event_admin_update?' . http_build_query(array('id' => $a_event->id, 'slug' => $a_event->slug))), 'editors-choose-label' => a_('Choose Editors'), 'categories-choose-label' => a_('Choose Categories'), 'categories-add' => $sf_user->hasCredential('admin'), 'categories-add-label' => a_('+ New Category'), 'popularTags' => $popularTags, 'existingTags' => $existingTags, 'template-change-warning' => a_('You are changing templates. Be sure to save any changes to the content at right before saving this change.'))) ?>
