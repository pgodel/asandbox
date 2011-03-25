<?php
class BaseaInsetAreaSlotForm extends BaseForm
{
  protected $id;
  protected $options;
  // PARAMETERS ARE REQUIRED, no-parameters version is strictly to satisfy i18n-update
  public function __construct($id = 1, $options = array())
  {
    $this->id = $id;
    $this->soptions = $options;
		$options['class'] = 'aInsetAreaSlot';
    $this->allowedTags = $this->consumeSlotOption('allowed-tags');
    $this->allowedAttributes = $this->consumeSlotOption('allowed-attributes');
    $this->allowedStyles = $this->consumeSlotOption('allowed-styles');
    parent::__construct();
  }

  protected function consumeSlotOption($s)
  {
    if (isset($this->soptions[$s]))
    {
      $v = $this->soptions[$s];
      unset($this->soptions[$s]);
      return $v;
    }
    else
    {
      return null;
    }
  }

  public function configure()
  {
    $widgetOptions = array();
 		$widgetOptions['tool'] = 'Sidebar';

    $tool = $this->consumeSlotOption('tool');

    if (!is_null($tool))
    {
      $widgetOptions['tool'] = $tool;
    }

    $this->setWidgets(array('value' => new aWidgetFormRichTextarea($widgetOptions, $this->soptions)));
    $this->setValidators(array('value' => new sfValidatorHtml(array('required' => false, 'allowed_tags' => $this->allowedTags, 'allowed_attributes' => $this->allowedAttributes, 'allowed_styles' => $this->allowedStyles))));

    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '-%s');
    $this->widgetSchema->setFormFormatterName('aAdmin');
		$this->widgetSchema->getFormFormatter()->setTranslationCatalogue('apostrophe');
  }

}
