<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
?>
<?php foreach($a_blog_post->Editors as $editor): ?>
<?php echo link_to($editor->username, '@a_blog_admin_addFilter?name=editors_list&value='.$editor->id, 'post=true') ?> 
<?php endforeach ?>