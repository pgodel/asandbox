<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : null;
  $existingTags = isset($existingTags) ? $sf_data->getRaw('existingTags') : null;
?>
<?php use_helper("a") ?>
<?php include_partial('aBlogAdmin/form', array('form' => $form, 'a_blog_post' => $a_blog_post, 'popularTags' => $popularTags, 'existingTags' => $existingTags)) ?>
<?php include_partial('a/globalJavascripts') ?>
