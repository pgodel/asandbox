<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $id = isset($id) ? $sf_data->getRaw('id') : null;
?>
<div class="a-form-row a-hidden">
<?php echo $form->renderHiddenFields() ?>
</div>

<?php echo $form['value']->render() ?>

<script type="text/javascript">
window.apostrophe.registerOnSubmit("<?php echo $id ?>", 
  function(slotId)
  {
    <?php # FCK doesn't do this automatically on an AJAX "form" submit on every major browser ?>
    var value = FCKeditorAPI.GetInstance('slot-form-<?php echo $id ?>-value').GetXHTML();
    $('#slot-form-<?php echo $id ?>-value').val(value);
  }
);
</script>

<?php a_js_call('apostrophe.slotEnhancements(?)', array('slot' => '#a-slot-'.$pageid.'-'.$name.'-'.$permid, 'editClass' => 'a-options')) ?>