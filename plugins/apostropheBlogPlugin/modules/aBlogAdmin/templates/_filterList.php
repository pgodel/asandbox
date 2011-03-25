<?php
  // Compatible with sf_escaping_strategy: true
  $filters = isset($filters) ? $sf_data->getRaw('filters') : null;
?>
<?php foreach($filters->getAppliedFilters() as $name => $value): ?>
  <p><?php echo $filters[$name]->renderLabel() ?>
  <?php if(is_array($value)): ?>
    <?php foreach($value as $val): ?>
      <?php echo $val ?>
    <?php endforeach ?>
  <?php else: ?>
    <?php echo $value ?>
  <?php endif ?>
  </p>
<?php endforeach ?>

