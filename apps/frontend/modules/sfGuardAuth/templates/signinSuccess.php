<?php use_helper('a') ?>
<?php slot('body_class') ?>sfguard-signin<?php end_slot() ?>

<?php slot('a-tabs', '') ?>
<?php slot('a-login', '') ?>

<div class="a-ui a-signin page clearfix" id="a-signin">
  <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post" id="a-signin-form" <?php echo ($form->hasErrors())? 'class="has-errors"':''; ?>>

		<div class="a-form-row a-hidden">
  		<?php echo $form->renderHiddenFields() ?>
		</div>
		
		<div class="a-form-row clearfix">
    	<?php echo $form['username']->renderLabel() ?>
			<div class="a-form-field">
    		<?php echo $form['username']->render() ?>
			</div>
    	<?php echo $form['username']->renderError() ?>
		</div>
		
		<div class="a-form-row clearfix">		
    	<?php echo $form['password']->renderLabel() ?>
			<div class="a-form-field">
    		<?php echo $form['password']->render() ?>
			</div>
    	<?php echo $form['password']->renderError() ?>
		</div>
		
		<?php /* When this thing starts working, it can get displayed. ?>
		<div class="a-form-row">
    	<?php echo $form['remember']->renderLabel() ?>
			<div class="a-form-field">
				<?php echo $form['remember']->render() ?>
			</div>				
			<?php echo $form['remember']->renderError() ?>
		</div>
		<?php //*/ ?>		
		
		<ul class="a-form-row submit clearfix">
    	<li>
				<input type="submit" class="a-btn big a-submit" value="<?php echo __('Sign In', null, 'apostrophe') ?>" />
			</li>
		</ul>
		
		<?php $routes = $sf_context->getRouting()->getRoutes() ?>
    <?php if (isset($routes['sf_guard_forgot_password'])): ?>
      <a href="<?php echo url_for('@sf_guard_forgot_password') ?>" class="a-forgot-password"><?php echo __('Forgot your password?', null, 'sf_guard') ?></a>
    <?php endif; ?>

    <?php if (isset($routes['sf_guard_register'])): ?>
      <a href="<?php echo url_for('@sf_guard_register') ?>" class="a-register-password"><?php echo __('Want to register?', null, 'sf_guard') ?></a>
    <?php endif; ?>
    
  </form>
</div>

<?php a_js_call('$("#signin_username").focus();') ?>