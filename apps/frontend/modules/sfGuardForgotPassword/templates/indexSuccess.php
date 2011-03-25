<?php use_helper('a') ?>
<?php slot('body_class') ?>sfguard-signin<?php end_slot() ?>

<?php slot('a-tabs', '') ?>
<?php slot('a-login', '') ?>

<div class="a-ui a-signin page forgot clearfix" id="a-signin">
	<form action="<?php echo url_for('@sf_guard_forgot_password') ?>" method="post">
		
		<div class="a-form-row a-hidden">
  		<?php echo $form->renderHiddenFields() ?>
		</div>
		
		<div class="a-form-row clearfix">
			<h3><?php echo __('Forgot your password?', null, 'sf_guard') ?></h3>
		</div>
					
	  <?php if ($form->hasGlobalErrors()): ?>
		<div class="a-form-row a-errors clearfix">
	    <?php echo $form->renderGlobalErrors() ?>
		</div>
	  <?php endif; ?>
					
		<div class="a-form-row clearfix">
  		<?php echo $form['email_address']->renderLabel(null, array('class' => 'a-hidden', )) ?>
			<div class="a-form-field">
  			<?php echo $form['email_address']->render() ?>
			</div>
  		<?php echo $form['email_address']->renderError() ?>	
			<div class="help">
	  		<?php echo __('We can email you instructions to reset your password.', null, 'sf_guard') ?>
			</div>
		</div>
		
		<div class="a-form-row submit clearfix">
			<input type="submit" class="a-btn big a-submit" name="change" value="<?php echo __('Reset Password', null, 'apostrophe') ?>" />
		</div>
	
	</form>
</div>

<?php a_js_call('apostrophe.selfLabel(?)', array('selector' => '#forgot_password_email_address', 'title' => 'Email', 'select' => true, 'focus' => true)) ?>