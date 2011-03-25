<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
?>

<?php use_helper("a") ?>

<?php $catClass = ""; foreach ($a_event->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>

<div class="a-blog-item event <?php echo $a_event->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?>">
  <?php if($a_event->userHasPrivilege('edit')): ?>
	  <ul class="a-ui a-controls a-blog-post-controls">
			<li>
				<?php echo a_button(a_('Edit'), url_for('a_event_admin_edit', $a_event), array('icon','a-edit','lite','alt','no-label')) ?>
			</li>
		</ul>
	<?php endif ?>
	<?php include_partial('aEvent/'.$a_event->getTemplate(), array('a_event' => $a_event, 'edit' => false)) ?>
</div>