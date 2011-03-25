<h2 class="a-blog-item-title"><?php echo link_to($aEvent['title'], 'a_event_post', $aEvent) ?></h2>

<ul class="a-blog-item-meta">
  <li class="start-day"><?php echo aDate::dayAndTime($aEvent->getStartDate()) ?></li>
  <li class="start-date"><?php echo aDate::dayMonthYear($aEvent->getStartDate()) ?><?php if ($aEvent->getStartDate() != $aEvent->getEndDate()): ?> &mdash;<?php endif ?></li>
	<?php if ($aEvent->getStartDate() != $aEvent->getEndDate()): ?>
		<li class="end-day"><?php echo aDate::dayAndTime($aEvent->getEndDate()) ?></li>
	  <li class="end-date"><?php echo aDate::dayMonthYear($aEvent->getEndDate()) ?></li>
	<?php endif ?>
</ul>