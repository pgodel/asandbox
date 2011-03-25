<?php // Google says they accept HTML in descriptions but they seem not to use it for anything. ?>
<?php // You never see descriptions anyway except when editing them. So send the plaintext. ?>
<?php // The byte limit was chosen to avoid creating a URL that the browser won't accept, ?>
<?php // even when Google double-encodes it in some situations ?>
<?php $aEvent = $sf_data->getRaw('aEvent') ?>
<?php echo a_button(a_('Add to Google Calendar'), url_for('http://www.google.com/calendar/event?' . http_build_query(array('action' => 'TEMPLATE', 'text' => $aEvent->getTitle(), 'dates' => $aEvent->getUTCDateRange(), 'location' => preg_replace('/\s+/', ' ', $aEvent['location']), 'sprop' => 'website:' . $sf_request->getHost(), 'details' => aHtml::toPlaintext($aEvent->getTextForArea('blog-body', 500, array('characters' => true, 'append_ellipsis' => a_('...'))))))), array('icon','no-bg','alt','a-events')) ?>