<?php

class aBlogSearchForm extends BaseForm
{
  public function configure()
  {
    $this->setWidget('q', new sfWidgetFormInput());
    $this->setValidator('q', new sfValidatorPass());
  }
}