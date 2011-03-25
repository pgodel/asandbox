<?php use_helper('a', 'Date') ?>
<li class="a-admin-action-new"><?php echo a_js_button(a_('New Post'), array('big', 'icon', 'a-add', 'a-blog-new-post-button'), 'a-blog-new-post-button') ?>
  <div class="a-options a-blog-admin-new-ajax dropshadow">
    <?php include_component('aBlogAdmin', 'newPost') ?>
  </div>
</li>
<?php if (sfConfig::get('app_aBlog_disqus_enabled', true) && sfConfig::get('app_aBlog_disqus_shortname')): ?>
<li><?php echo link_to('Comments', 'http://'. sfConfig::get('app_aBlog_disqus_shortname') .'.disqus.com', array('class' => 'a-btn big', )) ?></li>
<?php endif ?>