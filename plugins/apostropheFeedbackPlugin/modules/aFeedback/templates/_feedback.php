<?php use_helper('a') ?>

<?php if ($failed): ?>
  <h4 class="a-error">
		<span><?php echo a_('Email delivery failed.') ?></span><br />
		<?php echo mail_to(sfConfig::get('app_aFeedback_email_manual', sfConfig::get('app_aFeedback_email_auto')), a_('Please contact us directly'), array('encode' => true)) ?>
	</h4>
<?php endif ?>

<h4><?php echo a_('Submit a bug report') ?></h4>

<form action="<?php echo url_for('aFeedback/feedback') ?>" method="post" enctype="multipart/form-data" id="a-feedback-form" class="a-ui a-feedback-form">
	
	<?php echo $form ?>

	<div class="a-form-row submit">
		<ul class="a-ui a-controls">
			<li>
				<?php echo a_submit_button('Submit Feedback', array('alt')) ?>
			</li>
			<li> 
					<?php echo a_button('<span class="icon"></span>'.a_('Cancel'), (($form['section']->getValue()) ? $form['section']->getValue() : '#cancel'), array('icon a-cancel'), 'a-feedback-form-cancel-button') ?>
			</li>
		</ul>
	</div>

</form>

<?php if ($sf_request->isXmlHttpRequest()): ?>
	<?php a_include_js_calls(); ?>
<?php endif ?>