<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
?>
<?php echo link_to($a_blog_post->title, 'a_blog_admin_edit', $a_blog_post) ?>