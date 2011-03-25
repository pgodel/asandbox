<?php

class BaseaInsetAreaSlotActions extends aSlotActions
{
  
  // Use the edit view for the URL (and any other well-behaved fields that may arise) 
  public function executeEdit(sfRequest $request)
  {
    $this->logMessage("====== in aInsetAreaSlotActions::executeEdit", "info");
    $this->editSetup();
		// Work around FCK's incompatibility with AJAX and bracketed field names
		// (it insists on making the ID bracketed too which won't work for AJAX)

		// Don't forget, there's a CSRF field out there too. We need to grep through
		// the submitted fields and get all of the relevant ones, reinventing what
		// PHP's bracket syntax would do for us if FCK were compatible with it

		$values = $request->getParameterHolder()->getAll();
		$value = array();
		foreach ($values as $k => $v)
		{
			if (preg_match('/^slot-form-' . $this->id . '-(.*)$/', $k, $matches))
			{
				$value[$matches[1]] = $v;
			}
		}
		$this->form = new aInsetAreaSlotForm($this->id, $this->options);
		$this->form->bind($value);
		if ($this->form->isValid())
		{
      $value = $this->form->getValue('value');
      $this->slot->value = $value;      
      $result = $this->editSave();
      return $result;
		}
		else
		{
			// Makes $this->form available to the next iteration of the
			// edit view so that validation errors can be seen
			return $this->editRetry();
		}
  }
}
