<?php

/**
 * PluginaNewEvent form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaNewEventForm extends BaseForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('title', new sfWidgetFormInputText());
    $this->setValidator('title', new sfValidatorString(array('min_length' => 2, 'required' => true)));
    $this->widgetSchema->setNameFormat('a_new_event[%s]');
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
