<?php $results = $sf_data->getRaw('results') ?>
<?php // For jQuery.autocomplete ?>
<?php echo json_encode($results) ?>