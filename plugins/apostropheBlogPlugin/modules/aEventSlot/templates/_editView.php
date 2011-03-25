<?php use_helper('a') ?>
<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : array();
  $allTags = isset($allTags) ? $sf_data->getRaw('allTags') : array();
?>
<?php echo $form->renderHiddenFields() ?>
<div class="a-blog-edit-wrapper clearfix">
	<div class="a-form-row by-type meta">
	  <?php $w = $form['title_or_tag'] ?>
	  <input type="radio" id="<?php echo $w->renderId() ?>-tag" name="<?php echo $w->renderName() ?>" value="tags" <?php echo ($w->getValue() === "tags") ? 'checked' : '' ?> /> 
		<h4><label for="<?php echo $w->renderId() ?>-tag"><?php echo a_('By Category and Tag') ?></label></h4>
	  <div class="a-form-row count">
	  	<div class="a-form-field">
					<label for="<?php echo $form['count']->renderId() ?>" class="a-form-field-label"><?php echo a_('Number of Events to Display') ?></label>
	      	<?php echo $form['count']->render() ?>
					<div class="a-form-help collapsed"><?php echo $form['count']->renderHelp() ?></div>
	  	</div>	
	  	<div class="a-form-error"><?php echo $form['count']->renderError() ?></div>
	  </div>

	  <div class="a-form-row categories">
	    <div class="a-form-field">
	      	<label class="a-multiple-select-label" for="<?php echo $form['categories_list']->renderId() ?>"><?php echo a_('Categorized')?></label><?php echo $form['categories_list']->render() ?>
	    		<div class="a-form-help collapsed"><?php echo $form['categories_list']->renderHelp() ?></div>
			</div>
	  	<div class="a-form-error"><?php echo $form['categories_list']->renderError() ?></div>
	  </div>

	  <div class="a-form-row tags">
 			<label for="<?php echo $form['tags_list']->renderId() ?>"><?php echo a_('Tagged') ?></label>
	  	<div class="a-form-field">
	  	 	<?php echo $form['tags_list']->render() ?>
	      <?php $options = array('popular-tags' => $popularTags, 'tags-label' => ' ', 'commit-selector' => '#a-slot-form-submit-' . $id, 'typeahead-url' => url_for('taggableComplete/complete')) ?>
	      <?php if (sfConfig::get('app_a_all_tags', true)): ?>
	        <?php $options['all-tags'] = $allTags ?>        
	      <?php endif ?>
	      <?php a_js_call('pkInlineTaggableWidget(?, ?)', '#' . $form['tags_list']->renderId(), $options) ?>
	  	</div>
			<div class="a-form-help collapsed"><?php echo $form['tags_list']->renderHelp() ?></div>
	  	<div class="a-form-error"><?php echo $form['tags_list']->renderError() ?></div>
	  </div>
	</div>
	<hr />
	<div class="a-form-row by-type title">
	  <?php $w = $form['title_or_tag'] ?>
	  <input type="radio" id="<?php echo $w->renderId() ?>-title" name="<?php echo $w->renderName() ?>" value="title" <?php echo ($w->getValue() === "title") ? 'checked' : '' ?> /> 
		<h4><label for="<?php echo $w->renderId() ?>-title"><?php echo a_('By Title') ?></label></h4>
	  <div class="a-form-row events">
	    <?php echo $form['blog_posts']->render() ?><?php // Widget has the same name as in blog post form so they can share more stuff ?>
	  </div>
	</div>
</div>

<?php a_js_call('aBlog.slotEditView(?)', array('formName' => $form->getName(), 'autocompleteUrl' => url_for("aEventAdmin/search"), 'class' => 'events', 'selfLabelSelector' => '#'.$w->renderId().'-title',  'debug' => false)) ?>