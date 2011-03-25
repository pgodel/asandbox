<?php
  // Compatible with sf_escaping_strategy: true
  $categories = isset($categories) ? $sf_data->getRaw('categories') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $noFeed = isset($noFeed) ? $sf_data->getRaw('noFeed') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
  $tagsByPopularity = isset($tagsByPopularity) ? $sf_data->getRaw('tagsByPopularity') : null;
  $tagsByName = isset($tagsByName) ? $sf_data->getRaw('tagsByName') : null;
	$url = isset($url) ? $sf_data->getRaw('url') : null;
	$searchLabel = isset($searchLabel) ? $sf_data->getRaw('searchLabel') : null;
	$newLabel = isset($newLabel) ? $sf_data->getRaw('newLabel') : null;
	$adminModule = isset($adminModule) ? $sf_data->getRaw('adminModule') : null;
  $calendar = isset($calendar) ? $sf_data->getRaw('calendar') : null;
  $tag = (!is_null($sf_params->get('tag'))) ? $sf_params->get('tag') : null;
	$selected = array('icon','a-selected'); // Class names for selected filters
?>

<?php // url_for is the LAST step after other addParams calls play with what we want to include. Don't do it now ?>
<?php $filterUrl = aUrl::addParams($url, array('tag' => $sf_params->get('tag'), 'cat' => $sf_params->get('cat'), 'year' => $sf_params->get('year'), 'month' => $sf_params->get('month'), 'day' => $sf_params->get('day'), 'q' => $sf_params->get('q'))) ?>

<?php if (aBlogItemTable::userCanPost()): ?>
	<div class="a-ui clearfix a-subnav-section a-sidebar-button-wrapper">
	  <?php echo a_js_button($newLabel, array('big', 'a-add', 'a-blog-new-post-button', 'a-sidebar-button'), 'a-blog-new-post-button') ?>
    <div class="a-options a-blog-admin-new-ajax dropshadow">
      <?php include_component($newModule, $newComponent) ?>
    </div>
	</div>
<?php endif ?>

<div class="a-subnav-section search">
  <div class="a-search a-search-sidebar blog">
    <form action="<?php echo url_for(aUrl::addParams($filterUrl, array('q' => ''))) ?>" method="get">
  		<div class="a-form-row"> <?php // div is for page validation ?>
  			<label for="a-search-blog-field" style="display:none;">Search</label><?php // label for accessibility ?>
  			<input type="text" name="q" value="<?php echo htmlspecialchars($sf_params->get('q', null, ESC_RAW)) ?>" class="a-search-field" id="a-search-blog-field"/>
  			<input type="image" src="<?php echo image_path('/apostrophePlugin/images/a-special-blank.gif') ?>" class="submit a-search-submit" value="Search Pages" alt="Search" title="Search"/>
  		</div>
    </form>
  </div>
</div>

<?php if (isset($calendar) && $calendar): ?>
<hr class="a-hr" />
<?php include_partial('aEvent/calendar', array('calendar' => $calendar)) ?>
<?php endif ?>

<?php if (!$calendar): ?>
<hr class="a-hr" />
<div class='a-subnav-section range'>
  <h4><?php echo a_('Browse by') ?></h4>
  <div class="a-filter-options blog clearfix">
    <div class="a-filter-option">
    	
			<?php $selected_day = ($dateRange == 'day') ? $selected : array() ?>
			<?php echo a_button('Day', url_for($url . '?'.http_build_query(($dateRange == 'day') ? $params['nodate'] : $params['day'])), array_merge(array('a-link'),$selected_day)) ?>
		</div>
    <div class="a-filter-option">
			<?php $selected_month = ($dateRange == 'month') ? $selected : array() ?>
			<?php echo a_button('Month', url_for($url . '?'.http_build_query(($dateRange == 'month') ? $params['nodate'] : $params['month'])), array_merge(array('a-link'),$selected_month)) ?>
		</div>
    <div class="a-filter-option">
			<?php $selected_year = ($dateRange == 'year') ? $selected : array() ?>
			<?php echo a_button('Year', url_for($url . '?'.http_build_query(($dateRange == 'year') ? $params['nodate'] : $params['year'])), array_merge(array('a-link'),$selected_year)) ?>
		</div>
  </div>
</div>
<?php endif ?>

<?php if(count($categories) > 1): ?>
<hr class="a-hr" />
<div class="a-subnav-section categories">
  <h4><?php echo a_('Categories') ?></h4>
  <div class="a-filter-options blog clearfix">
	  <?php foreach ($categories as $category): ?>
	    <div class="a-filter-option">
      	
				<?php $selected_category = ($category['slug'] === $sf_params->get('cat')) ? $selected : array() ?>
				<?php echo a_button($category['name'], url_for(aUrl::addParams($filterUrl, array('cat' => ($sf_params->get('cat') === $category['slug']) ? '' : $category['slug']))), array_merge(array('a-link'),$selected_category)) ?>
			</div>
	  <?php endforeach ?>
  </div>	
</div>
<?php endif ?>

<?php if(count($tagsByName)): ?>
<hr class="a-hr" />
<div class="a-subnav-section tags">  

	<?php if (isset($tag)): ?>
	<div class="a-tag-sidebar-selected-tag clearfix">
		<h4 class="a-tag-sidebar-title selected-tag"><?php echo a_('Selected Tag') ?></h4>  
		<?php echo a_button($tag, url_for(aUrl::addParams($filterUrl, array('tag' => ''))), array('a-link','icon','a-selected')) ?>
	</div>
	<?php endif ?>  
  
	<h4 class="a-tag-sidebar-title popular"><?php echo a_('Popular Tags') ?></h4>  			
	<ul class="a-ui a-tag-sidebar-list popular">
		<?php $n=1; foreach ($tagsByPopularity as $tagInfo): ?>
		  <li <?php echo ($n == count($tagsByPopularity) ? 'class="last"':'') ?>>
				<?php echo a_button('<span class="a-tag-count">'.$tagInfo['t_count'].'</span>'.$tagInfo['name'], url_for(aUrl::addParams($filterUrl, array('tag' => $tagInfo['name']))), array('a-link','a-tag')) ?>
			</li>
		<?php $n++; endforeach ?>
	</ul>

	<h4 class="a-tag-sidebar-title all-tags"><?php echo a_('All Tags') ?> <span class="a-tag-sidebar-tag-count"><?php echo count($tagsByName) ?></span></h4>
	<ul class="a-ui a-tag-sidebar-list all-tags">
		<?php $n=1; foreach ($tagsByName as $tagInfo): ?>
		  <li <?php echo ($n == count($tagsByName) ? 'class="last"':'') ?>>
				<?php echo a_button('<span class="a-tag-count">'.$tagInfo['t_count'].'</span>'.$tagInfo['name'], url_for(aUrl::addParams($filterUrl, array('tag' => $tagInfo['name']))), array('a-link','a-tag')) ?>
			</li>
		<?php $n++; endforeach ?>
	</ul>
	
</div>
<?php endif ?>

<?php if(!isset($noFeed)): ?>
	<hr class="a-hr" />
	<ul class="a-ui a-controls stacked">
  <?php $full = $url . '?feed=rss' ?>
  <?php // Everything not date-related. A date-restricted RSS feed is a bit of a contradiction ?>
  <?php $filtered = aUrl::addParams($filterUrl, array('feed' => 'rss', 'year' => '', 'month' => '', 'day' => '')) ?>
  <?php if ($full === $filtered): ?>
    <li><?php echo a_button(a_('RSS Feed'), url_for($full), array('icon','a-rss-feed', 'no-bg', 'alt')) ?></li>
  <?php else: ?>
    <li><?php echo a_button(a_('Full Feed'), url_for($full), array('icon','a-rss-feed','no-bg', 'alt')) ?></li>
    <li><?php echo a_button(a_('Filtered Feed'), url_for($filtered), array('icon','a-rss-feed','no-bg', 'alt')) ?></li>
  <?php endif ?>
	</ul>
<?php endif ?>

<?php a_js_call('aBlog.sidebarEnhancements(?)', array()) ?>
<?php a_js_call('apostrophe.selfLabel(?)', array('selector' => '#a-search-blog-field', 'title' => $searchLabel, 'focus' => false )) ?>