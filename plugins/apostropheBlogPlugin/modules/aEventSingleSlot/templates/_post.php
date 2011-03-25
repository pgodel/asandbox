<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
?>
<?php $full = a_get_option($options, 'full', false) ?>
<?php $template = a_get_option($options, 'template', $aBlogItem['template']) ?>
<?php $subtemplate = a_get_option($options, 'subtemplate', 'slot') ?>
<?php $templateOptionsAll = a_get_option($options, 'template_options', array()) ?>
<?php $templateOptions = a_get_option($templateOptionsAll, $template, array()) ?>
<?php $subtemplate = a_get_option($templateOptions, 'subtemplate', $subtemplate) ?>
<?php if ($full): ?>
	<?php $suffix = ''; ?>
<?php else: ?>
	<?php $suffix = '_'.$subtemplate; ?>
<?php endif ?>
<?php // Allows styling based on whether there is media in the post, ?>
<?php // the blog template, and the subtemplate ?>

<?php // Yes, catching exceptions in templates is unusual, but if there is no blog page on ?>
<?php // the site it is not possible to generate some of the links. That can kill the home page, ?>
<?php // so we must address it. Someday it might be better to do less in the template and ?>
<?php // generate the various URLs in component code rather than partial code ?>
<?php try { ?>

<div class="a-blog-item event<?php echo ($aBlogItem->hasMedia())? ' has-media':''; ?> <?php echo $template ?> <?php echo $subtemplate ?>">
<?php // TODO: passing a variable as both underscore and intercap is silly clean this up make the partials consistent but look out for overrides ?>
<?php include_partial('aEvent/'.$template.$suffix, array('aEvent' => $aBlogItem, 'a_event' => $aBlogItem, 'edit' => false, 'options' => $options)) ?>
</div>

<?php } catch (Exception $e) { ?>
  <h3>You must have an events page somewhere on your site to use event slots.</h3>
<?php } ?>