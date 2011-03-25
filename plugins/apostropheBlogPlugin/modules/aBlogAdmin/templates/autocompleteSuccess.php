<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogPosts = isset($aBlogPosts) ? $sf_data->getRaw('aBlogPosts') : null;
?>
<?php foreach($aBlogPosts as $aBlogPost): ?>
<?php echo $aBlogPost['title'] ?> <?php echo $aBlogPost['published_at']?>|<?php echo $aBlogPost['id'] ?>|<?php echo $aBlogPost['title'] ?> 
<?php endforeach ?>