<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $tags = isset($tags) ? $sf_data->getRaw('tags') : null;
?>
<div class="a-admin-form-field-tags">
  <h5>Popular Tags</h5>

  <div id="blog-tag-list">
    <?php $n=1; foreach ($tags as $tag => $count): ?>
    		<?php echo link_to_function($tag, '', array('class' => (in_array($tag, $a_blog_post->getTags())) ? 'selected recommended-tag' : 'recommended-tag', )) ?><?php echo ($n < count($tags)) ? ', ' : ''; ?>			  
    <?php $n++; endforeach ?>
  </div>
</div>