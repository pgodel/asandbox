[?php use_helper("a") ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

<script type="text/javascript">
$(document).ready(function(){
	var sortLabel = $("a.a-sort-label").parent().parent();
	sortLabel.click(function(event) {
		event.preventDefault();				
		var thisSortLabel = $(this).find('a.a-sort-label');
		thisSortLabel.focus();
    $(this).addClass('show-filters').find(".filternav").show();
    $(this).parent().hover(function() {
    }, function() {
			thisSortLabel.blur();
			$(this).find('ul').removeClass('show-filters');
      $(this).find(".filternav").fadeOut();
    });
  });
});	
</script>

[?php slot('a-page-header')?]
<div class="a-ui a-admin-header">
	<h3 class="a-admin-title">[?php echo __('<?php echo $this->configuration->getValue('list.title') ?>', array(), 'apostrophe') ?]</h3>
	<ul class="a-ui a-controls a-admin-controls">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]   
  </ul>
  [?php include_partial('<?php echo $this->getModuleName() ?>/list_bar', array('filters' => $filters, 'configuration' => $configuration)) ?]
</div>
[?php end_slot() ?]

<div class="a-ui a-admin-container [?php echo $sf_params->get('module') ?]">
 
	<div class="a-admin-content main">

		[?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]

		<?php if ($this->configuration->getValue('list.batch_actions')): ?>
			<form action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'batch')) ?]" method="post" id="a-admin-batch-form">
		<?php endif; ?>

		[?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'form' => $filters)) ?]

			<ul class="a-ui a-controls a-admin-actions">
	      [?php include_partial('<?php echo $this->getModuleName() ?>/list_batch_actions', array('helper' => $helper)) ?]
	    </ul>

		<?php if ($this->configuration->getValue('list.batch_actions')): ?>
		  </form>
		<?php endif; ?>

	</div>

  <div class="a-admin-footer">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list_footer', array('pager' => $pager)) ?]
  </div>

</div>