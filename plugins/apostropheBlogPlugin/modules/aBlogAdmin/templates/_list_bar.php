<?php
  // Compatible with sf_escaping_strategy: true
  $configuration = isset($configuration) ? $sf_data->getRaw('configuration') : null;
  $filters = isset($filters) ? $sf_data->getRaw('filters') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
?>
<div id="a-admin-bar" <?php if (count($sf_user->getAttribute('aBlogAdmin.filters', null, 'admin_module'))): ?>class="has-filters"<?php endif ?>>
  <?php if (0): ?>
    <h2 class="a-admin-title you-are-here"><?php echo __('Blog Post Admin', array(), 'messages') ?></h2>
  <?php endif ?>
</div>

<div class="a-admin-title-sentence">

<h3> 	
	<?php $appliedFilters = $filters->getAppliedFilters(); ?>
  <?php $fields = $configuration->getFormFilterFields($filters) ?>

	<?php if ($appliedFilters): ?>
		<?php echo a_('You are viewing posts') ?> 
	<?php else: ?>
		<?php echo a_('You are viewing all posts') ?>
	<?php endif ?>	

	<?php $n=1; foreach($appliedFilters as $name => $values): ?>
    <?php $field = $fields[$name] ?>
    <?php echo $field->getConfig('label', $name) ?>
    <?php foreach($values as $value): ?>
      <?php echo link_to($value, "@a_blog_admin_removeFilter?name=$name&value=$value", array('class' => 'selected')) ?><?php if ($n < count($appliedFilters)): ?>,<?php endif ?>
    <?php endforeach ?>
	<?php endforeach ?>
</h3>

</div>

<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {
		$('a.selected').prepend('<span class="close"></span>');
	});
//]]>	
</script>