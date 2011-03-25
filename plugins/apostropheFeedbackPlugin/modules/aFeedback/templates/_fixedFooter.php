<?php if ($sf_params->get('module') != 'aFeedback'): ?>
<?php use_helper('a') ?>
<div class="a-feedback-footer" id="a-feedback-footer">
	<?php echo a_js_button(a_('Submit a Bug Report'), array('a-link', 'a-feedback'), 'a-feedback-link') ?>
	<div class="a-feedback-form-container" id="a-feedback-form-container">
		<?php include_partial('aFeedback/feedback', array('form' => new aFeedbackForm(), 'feedbackSubmittedBy' => false, 'failed' => false)) ?> 
	</div>
	<?php if ($reportSubmittedBy = $sf_user->getFlash('reportSubmittedBy')): ?>
 	<div class="a-feedback-submitted"><span class="submitted-by"><?php echo $reportSubmittedBy ?></span> &ndash; <?php echo a_('Thank you for submitting a bug report.') ?></div>
	<?php endif ?>
</div>
<?php a_js_call('aFeedback.feedbackForm(?)', array('url' => url_for('aFeedback/feedback')."?".http_build_query(array('section' => $_SERVER['REQUEST_URI'])))) ?>
<?php endif ?>