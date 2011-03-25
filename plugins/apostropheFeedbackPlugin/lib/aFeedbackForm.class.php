<?php

class aFeedbackForm extends sfForm
{
  protected $section;
  public function __construct($section = false)
  {
    if ($section === false)
    {
      $section = $_SERVER['REQUEST_URI'];
    }
    $this->section = $section;
    parent::__construct();
  }
  public function configure()
  {
    $this->setWidgets(array(
      'name' => new sfWidgetFormInput(),
      'email' => new sfWidgetFormInput(),
      'subject' => new sfWidgetFormInput(), 
      'section' => new sfWidgetFormInput(array('default' => $this->section)),
      'description' => new sfWidgetFormTextarea(),
      'screenshot' => new sfWidgetFormInputFile(), 
      'captcha' => new sfWidgetCaptchaGD(),
    ));
    
    $this->widgetSchema->setLabels(array(
      'name' => 'Your name', 
      'email' => 'Your email address', 
      'subject' => 'Brief description of the problem', 
      'section' => 'URL', 
      'description' => 'Comments',
      'captcha' => 'Security check', 
    ));
    
    $this->widgetSchema->setHelp('description', 'Please describe the nature of the problem with as much detail as possible. Tell us what you were doing when you encountered the problem and what you were expecting to happen instead.');
    $this->widgetSchema->setHelp('screenshot', 'On a Mac: press command+shift+4, then press the spacebar and click on the window. On a PC: <a href="http://www.wikihow.com/Take-a-Screenshot-in-Microsoft-Windows" target="_blank">follow these instructions</a>.');
    $this->widgetSchema->setHelp('captcha', 'This test is used to prevent automated robots from posting feedback.');
 
    $this->widgetSchema->setNameFormat('feedback[%s]');
    
    $this->setValidators(array(
      'name' => new sfValidatorString(array('required' => true), array(
        'required' => 'Please include your full name', 
        )),
      'email'   	 	 => new sfValidatorEmail(array('required' => true), array(
				'required' => 'Please include your email address.',
				'invalid'  => 'Please include a valid email address.',
			)),
      'section'  => new sfValidatorString(array('required' => false, 'max_length' => 255), array(
  			'invalid'  => 'The subject must not exceed 255 characters.',
  		)),			
      'subject'  => new sfValidatorString(array('required' => false, 'max_length' => 255), array(
  			'invalid'  => 'The subject must not exceed 255 characters.',
  		)),			
      'description'  => new sfValidatorString(array('required' => true, 'min_length' => 4), array(
  			'required' => 'Please provide a message.', 
  			'invalid'  => 'Your message must exceed four characters.',
  		)),			
      'screenshot'    => new sfValidatorFile(array('required' => false)),
      'captcha' => new sfCaptchaGDValidator(array('length' => 4), array(
  			'required'  => 'Please enter the numbers displayed in the image above.',
  			'invalid'  => 'Please enter the numbers displayed in the image above.',
      )),
    ));

    $this->getWidgetSchema()->setFormFormatterName('aAdmin');
  }
}
