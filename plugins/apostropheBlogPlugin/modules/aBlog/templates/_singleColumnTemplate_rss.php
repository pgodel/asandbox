<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogPost = isset($aBlogPost) ? $sf_data->getRaw('aBlogPost') : null;
?>
<?php echo link_to($aBlogPost['title'], 'a_blog_post', $aBlogPost, array('absolute' => true)) ?>
<br/>
<?php echo $aBlogPost['published_at'] ?>
<br/><br/>
<?php foreach($aBlogPost->Page->getArea('blog-body') as $slot): ?>
<?php echo $slot->getBasicHtml() ?>
<?php endforeach ?>
