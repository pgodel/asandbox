<?php
  // Compatible with sf_escaping_strategy: true
  $aEvent = isset($aEvent) ? $sf_data->getRaw('aEvent') : null;
?>

<ul class="a-ui a-blog-item-meta">

  <?php // This has been simplified quite a lot. Please leave it that way so ?>
  <?php // it can be easily shared with the admin side and also stay I18N ?>
  <li class="post-date"><?php include_partial('aEvent/dateRange', array('aEvent' => $aEvent,)) ?></li>

	<?php if (strlen($aEvent['location'])): ?>
	  <?php // It is amazing how often this works well even for something as short as ?>
	  <?php // 'Blockley Hall' since the user's location is often known to Google Maps. However ?>
	  <?php // it is less useful if all of your locations are 'room 150', etc. with no further ?>
	  <?php // information. Naturally full addresses work best ?>
    <li class="post-location">
			<?php echo aString::firstLine($aEvent['location']) ?>
  		<?php if (sfConfig::get('app_events_google_maps', true)): ?>
				<?php echo a_button(a_('Google Maps'), url_for('http://maps.google.com/maps?' . http_build_query(array('q' => preg_replace('/\s+/', ' ', $aEvent['location'])))), array('no-bg', 'alt', 'icon', 'a-google-maps')) ?>
		  <?php endif ?>
		</li>
	<?php endif ?>

	<?php /* Events generally don't display the author, but you can if necessary.  ?>
 	<li class="post-author">
		<span class="a-blog-item-meta-label"><?php echo __('Posted By:', array(), 'apostrophe') ?></span>
		<?php echo ($aEvent->getAuthor()->getName()) ? $aEvent->getAuthor()->getName() : $aEvent->getAuthor()  ?>
	</li>   			
	<?php //*/ ?>
	
	<li class="post-extra">
		<?php include_partial('aEvent/addToGoogleCalendar', array('aEvent' => $aEvent)) ?> 
		<?php include_partial('aEvent/addToIcal', array('aEvent' => $aEvent)) ?>  
	</li>
	
</ul>