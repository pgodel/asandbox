<h3 class="a-blog-item-title">
  <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
</h3>

<?php if($options['maxImages'] && $a_blog_post->hasMedia()): ?>		
<div class="a-blog-item-media">
		<?php include_component('aSlideshowSlot', 'slideshow', array(
	  'items' => $a_blog_post->getMediaForArea('blog-body', 'image', $options['maxImages']),
	  'id' => 'a-slideshow-blogitem-'.$a_blog_post['id'],
	  'options' => $options['slideshowOptions']
	  )) ?>
</div>
<?php endif ?>
