<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogPost = isset($aBlogPost) ? $sf_data->getRaw('aBlogPost') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
?>
<?php // Yes, catching exceptions in templates is unusual, but if there is no blog page on ?>
<?php // the site it is not possible to generate some of the links. That can kill the home page, ?>
<?php // so we must address it. Someday it might be better to do less in the template and ?>
<?php // generate the various URLs in component code rather than partial code ?>
<?php try { ?>
  
  <h3 class="a-blog-item-title"><?php echo link_to($aBlogPost['title'], 'a_blog_post', $aBlogPost) ?></h3>

  <ul class="a-blog-item-meta">
  	<li class="date"><?php echo aDate::long($aBlogPost['published_at']) ?></li>
  	<li class="author"><?php echo __('Posted By:', array(), 'apostrophe') ?> <?php echo $aBlogPost->getAuthor() ?></li>   			
  </ul>

  <?php if($options['maxImages'] && $aBlogPost->hasMedia()): ?>
  	<div class="a-blog-item-media">
  	<?php include_component('aSlideshowSlot', 'slideshow', array(
  	  'items' => $aBlogPost->getMediaForArea('blog-body', 'image', $options['maxImages']),
  	  'id' => 'a-slideshow-blogitem-'.$aBlogPost['id'],
  	  'options' => $options['slideshowOptions']
  	  )) ?>
  	</div>
  <?php endif ?>

  <div class="a-blog-item-excerpt-container">
  	<div class="a-blog-item-excerpt">
  		<?php echo $aBlogPost->getTextForArea('blog-body', $options['excerptLength']) ?>
  	</div>
    <div class="a-blog-read-more">
      <?php echo link_to('Read More', 'a_blog_post', $aBlogPost, array('class' => 'a-blog-more')) ?>
    </div>
  </div>
  
<?php } catch (Exception $e) { ?>
  <h3>You must have a blog page somewhere on your site to use blog slots.</h3>
<?php } ?>