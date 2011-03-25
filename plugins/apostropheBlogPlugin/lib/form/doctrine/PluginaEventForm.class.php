<?php

/**
 * PluginaEvent form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaEventForm extends BaseaEventForm
{

  protected $engine = 'aEvent';

  public function setup()
  {
    parent::setup();

    $this->setWidget('start_date', new aWidgetFormJQueryDate(
			array('image' => '/apostrophePlugin/images/a-icon-datepicker.png'))
		);

    $this->setValidator('start_date', new sfValidatorDate(
      array(
        'required' => true,
      )));

    $this->setWidget('start_time', new aWidgetFormJQueryTime(array(), array('twenty-four-hour' => false, 'minutes-increment' => 30)));
    $this->setValidator('start_time', new sfValidatorTime(array('required' => false)));

    $this->setWidget('end_date', new aWidgetFormJQueryDate(
			array('image' => '/apostrophePlugin/images/a-icon-datepicker.png'))
		);

    $this->setValidator('end_date', new sfValidatorDate(
      array(
        'required' => true,
      )));

    $this->setWidget('end_time', new aWidgetFormJQueryTime(array(), array('twenty-four-hour' => false, 'minutes-increment' => 30)));
    $this->setValidator('end_time', new sfValidatorTime(array('required' => false)));

    $this->setWidget('location', new sfWidgetFormTextarea());
    $this->setValidator('location', new sfValidatorString(array('required' => false)));

    $this->getWidgetSchema()->setDefault('start_date', date('Y/m/d'));
    $this->getWidgetSchema()->setDefault('end_date', date('Y/m/d'));

		$this->setWidget('all_day', new sfWidgetFormInputCheckbox(array('label' => 'All Day')));
		$this->setValidator('all_day', new sfValidatorBoolean());

		$start = strtotime(aDate::mysql($this->object->start_date));
		$end = strtotime(aDate::mysql($this->object->end_date));
    if (is_null($this->object->start_time) && is_null($this->object->end_time))
		{
			$this->getWidgetSchema()->setDefault('all_day', true);
		}

    $this->widgetSchema->setNameFormat('a_blog_item[%s]');
    
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'validateEndDate')))
    );
  }
  
  public function validateEndDate($validator, $values)
  {
    $start = $values['start_date'] . ' ' . $values['start_time'];
    $end = $values['end_date'] . ' ' . $values['end_time'];
    if ($end < $start)
    {
      // Technically the problem might be the date but we show them on one row
      // anyway so always attach the error to the time which is easier to style
      $error = new sfValidatorError($validator, 'Ends before it begins!');
      throw new sfValidatorErrorSchema($validator, array('end_date' => $error));
    }
    return $values;
  }
  
  
  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->getValues();
    }
    
    if ($values['all_day'])
    {
      $values['start_time'] = null;
      $values['end_time'] = null;
    }
    return parent::updateObject($values);
  }
}
