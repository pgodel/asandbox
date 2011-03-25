<?php

class BaseaInsetImageSlotComponents extends aSlotComponents
{
	protected function getInsetMedia()
	{
		// We are going to return the media in both Normal and Edit View
    if (!count($this->slot->MediaItems))
    {
      $this->item = false;
      $this->itemId = false;
    }
    else
    {
      $this->item = $this->slot->MediaItems[0];
      $this->itemId = $this->item->id;
      $this->dimensions = aDimensions::constrain(
        $this->item->width,
        $this->item->height,
        $this->item->format,
        array("width" => $this->options['width'],
          "height" => $this->options['flexHeight'] ? false : $this->options['height'],
          "resizeType" => $this->options['resizeType']));
      $this->embed = $this->item->getEmbedCode('_WIDTH_', '_HEIGHT_', '_c-OR-s_', '_FORMAT_', false);
    }
	}

	protected function setupOptions()
	{
		$this->options['tool'] = $this->getOption('tool','Main');
    $this->options['constraints'] = $this->getOption('constraints', array());
    $this->options['width'] = $this->getOption('width', 440);
    $this->options['height'] = $this->getOption('height', false);
    $this->options['resizeType'] = $this->getOption('resizeType', 's');
    $this->options['flexHeight'] = $this->getOption('flexHeight', true);
    $this->options['title'] = $this->getOption('title', true);
    $this->options['description'] = $this->getOption('description', true);
		$this->options['insetTemplate'] = $this->getOption('insetTemplate', 'topLeft');
	}

  public function executeEditView()
  {
    $this->setup();
		$this->setupOptions();
    $this->options['width'] = 160;
    $this->options['height'] = 160;
		$this->getInsetMedia();
    if (!isset($this->form))
    {
      $this->form = new aInsetImageSlotForm($this->id, $this->options);
      $value = $this->slot->getArrayValue();
      if (isset($value['description']))
      {
        $this->form->setDefault('description', $value['description']);
      }
    }
  }

  public function executeNormalView()
  {
    $this->setup();
		$this->setupOptions();
		$this->getInsetMedia();
    $data = $this->slot->getArrayValue();
    if ($this->options['description'])
    {
			if (isset($data['description'])) {
      	$this->options['description'] = $data['description'];
			}
			else
			{
      	$this->options['description'] = false;
			}
    }
  }
}