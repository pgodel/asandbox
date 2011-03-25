<?php

class BaseaInsetAreaSlotComponents extends aSlotComponents
{
	protected function setupOptions()
	{
		$this->options['tool'] = $this->getOption('tool','Main');
		$this->options['width'] = $this->getOption('width', 480);
		$this->options['areaTemplate'] = $this->getOption('areaTemplate', 'insetArea');
		$this->options['insetTemplate'] = $this->getOption('insetTemplate', 'topLeft');
		$this->options['value'] = $this->getOption('value', true);
	 	// Options array for convenience, easy defaults, change it once - applies to all slots
		$this->areaOptions = array_merge(
			$this->getOption('areaOptions', array()),
			array(
				'width' => $this->options['width'],
				'height' => false,
				'resizeType' => 's',
				'flexHeight' => true
			)
		);
	}

  public function executeEditView()
  {
    $this->setup();
		$this->setupOptions();
    if (!isset($this->form))
    {
      $this->form = new aInsetAreaSlotForm($this->id, $this->options);
      $data = $this->slot->getValue('value');
      if (isset($data))
      {
        $this->form->setDefault('value', $data);
      }
    }
  }

  public function executeNormalView()
  {
    $this->setup();
		$this->setupOptions();
    $data = $this->slot->getValue('value');
    if ($this->options['value'])
    {
			if (isset($data['value'])) {
      	$this->options['value'] = $data;
			}
			else
			{
      	$this->options['value'] = false;
			}
    }
  }
}