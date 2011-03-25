<?php // http_build_query wants a real array, not a decorated array ?>
<?php $calendar = $sf_data->getRaw('calendar') ?>
<?php $filtersWithoutDate = aUrl::addParams(url_for('aEvent/index'), array('year' => '', 'month' => '', 'day' => '', 'tag' => $sf_params->get('tag'), 'cat' => $sf_params->get('cat'), 'q' => $sf_params->get('q'))) ?>
<table class="a-calendar">
	<thead>
	<tr class="month">
		<th colspan="7">
			<?php echo a_button(a_('Previous'), url_for(aUrl::addParams($filtersWithoutDate,$calendar['params']['prev'])), array('a-arrow-btn', 'icon', 'a-arrow-left', 'previous-month', 'alt')) ?>
			<h4 class="title">
				<a href="<?php echo url_for(aUrl::addParams($filtersWithoutDate, array('year' => $calendar['year'], 'month' => date('m', strtotime($calendar['month']))))) ?>"><?php echo $calendar['month'] ?></a>
				<a href="<?php echo url_for(aUrl::addParams($filtersWithoutDate, array('year' => $calendar['year'], 'month' => ''))) ?>"><?php echo $calendar['year'] ?></a>
			</h4>
			<?php echo a_button(a_('Next'), url_for(aUrl::addParams($filtersWithoutDate, $calendar['params']['next'])), array('a-arrow-btn', 'icon', 'a-arrow-right', 'next-month', 'alt')) ?>
		</th>
	</tr>
	<tr class="days">
		<th class="day sunday">Su</th>
		<th class="day monday">M</th>
		<th class="day tuesday">T</th>
		<th class="day wedsnesday">W</th>
		<th class="day thursday">Th</th>
		<th class="day friday">F</th>
		<th class="day saturday">S</th>																								
	</tr>	
	</thead>
	<tbody>
	<?php $w=0; $d=0; foreach ($calendar['events']->getEventCalendar() as $week): ?>
		<tr class="week-<?php echo $w; ?>">
			<?php foreach ($week as $eventDate => $event): ?>
				<?php $day_class = (date('m', strtotime($eventDate)) == date('m', strtotime($calendar['month']))) ? ' current-month':' not-current-month'; ?>
				<?php $day_class .= (date('mdy', strtotime($eventDate)) == date('mdy')) ? ' today':''; ?>
				<?php $day_class .= (date('d', strtotime($eventDate)) == $sf_request->getParameter('day'))? ' selected':'' ?>

				<?php $day_title = (date('mdy', strtotime($eventDate)) == date('mdy')) ? a_('Today'):''; ?>

				<td class="day day-<?php echo $d; ?><?php echo $day_class ?>" title="<?php echo $day_title ?>">
					<?php if (count($event)): ?>
						<a href="<?php echo url_for(aUrl::addParams($filtersWithoutDate, array('year' => date('Y', strtotime($eventDate)), 'month' => date('m', strtotime($eventDate)), 'day' => date('d', strtotime($eventDate))))) ?>" title="<?php echo (count($event) > 1)? count($event).' Events':count($event).' Event' ?>"><?php echo date('d', strtotime($eventDate)) ?></a>
					<?php else: ?>
						<span><?php echo date('d', strtotime($eventDate)) ?></span>
					<?php endif ?>					
				</td>
			<?php $d++; endforeach ?>
		</tr>
	<?php $w++; endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="7">
				<a href="<?php echo url_for($filtersWithoutDate, array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'))) ?>" class="icon a-events day-<?php echo date('d') ?> alt a-calendar-today"><span class="icon"></span>Today</a>
			</td>
		</tr>
	</tfoot>
</table>
