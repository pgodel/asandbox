<?php
  // Compatible with sf_escaping_strategy: true
  $active = isset($active) ? $sf_data->getRaw('active') : null;
  $class = isset($class) ? $sf_data->getRaw('class') : null;
  $dragIcon = isset($dragIcon) ? $sf_data->getRaw('dragIcon') : null;
  $draggable = isset($draggable) ? $sf_data->getRaw('draggable') : null;
  $maxDepth = isset($maxDepth) ? $sf_data->getRaw('maxDepth') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $nav = isset($nav) ? $sf_data->getRaw('nav') : null;
  $nest = isset($nest) ? $sf_data->getRaw('nest') : null;
  $tabs = isset($tabs) ? $sf_data->getRaw('tabs') : null;
?>
<ul class="a-nav a-nav-<?php echo $name ?><?php echo (isset($tabs))? ' tabs':' accordion' ?> nav-depth-<?php echo $nest?>" id="a-nav-<?php echo $name ?>-<?php echo $nest ?>">

  <?php foreach($nav as $pos => $item): ?>
    <li class="<?php echo $class;
        if($item['slug'] == $active) echo ' a-current-page';
        if(isset($item['ancestor'])) echo ' ancestor';
        //Most people probably don't want this class, lets not clutter things up too much
        //if(isset($item['ancestor-peer'])) echo ' ancestor-peer';
        if(isset($item['extra'])) echo ' a-extra-page';
        if($item['archived']) echo ' a-archived-page';
        if($item['view_is_secure']) echo ' a-secure-page';
        if($pos == 0) echo ' first';
        if($pos == 1) echo ' second';
        if($pos == count($nav) - 2) echo ' next-last';
        if($pos == count($nav)-1) echo ' last'
    ?>" id="a-nav-item-<?php echo $name ?>-<?php echo $item['id']?>">

      <?php if(isset($item['external']) && $item['external']): ?>
        <?php echo link_to($item['title'], $item['slug']) ?>
      <?php else: ?>
        <?php echo link_to($item['title'], aTools::urlForPage($item['slug'], array('absolute' => true))) ?>
      <?php endif ?>

      <?php if(isset($item['children']) && count($item['children']) && $nest < $maxDepth): ?>
        <?php include_partial('aNavigation/accordion', array('nav' => $item['children'], 'draggable' => $draggable, 'maxDepth' => $maxDepth-1, 'name' => $name, 'nest' => $nest+1, 'dragIcon' => $dragIcon, 'class' => $class, 'active' => $active)) ?>
      <?php endif ?>

      <?php if ($dragIcon && $draggable): ?>
				<?php echo a_js_button('Drag', array('icon', 'a-drag', 'no-label', 'alt', 'no-bg')) ?>
      <?php endif ?>

    </li>
  <?php endforeach ?>
  

</ul>

<?php if ($draggable): ?>
<script type="text/javascript">
	 //<![CDATA[
  $(document).ready(
    function() 
    {
			var nav = $("#a-nav-<?php echo $name ?>-<?php echo $nest ?>");
			
      nav.sortable(
      { 
        delay: 100,
        update: function(e, ui) 
        { 
          var serial = nav.sortable('serialize', {key:'a-tab-nav-item[]'});
          var options = {"url":<?php echo json_encode(url_for('a/sortNav').'?page=' . $item['id']); ?>,"type":"post"};
          options['data'] = serial;
          $.ajax(options);
					
					// Fixes Margin
					nav.children().removeClass('first second next-last last');
					nav.children(':first').addClass('first');
					nav.children(':last').addClass('last');
					nav.children(':first').next("li").addClass('second');
					nav.children(':last').prev("li").addClass('next-last');
        },
        items: 'li:not(.extra)'
      });

    });
  //]]>
  </script>
<?php endif ?>