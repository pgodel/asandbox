<?php

/**
 * Base actions for the apostropheFeedbackPlugin aFeedback module.
 * 
 * @package     apostropheFeedbackPlugin
 * @subpackage  aFeedback
 * @author      Your name here
 * @version     SVN: $Id: BaseActions.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
abstract class BaseaFeedbackActions extends sfActions
{
  /**
	 * Executes feedback action
	 *
	 */
	public function executeFeedback(sfRequest $request)
	{
	  $section = $request->getParameter('section', false);
	  $this->form = new aFeedbackForm($section);
    $this->feedbackSubmittedBy = false;
    $this->failed = false;
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Tag', 'Url'));
    
		if ($request->isMethod('post'))
		{
			$this->form->bind(array_merge($request->getParameter('feedback'), array('captcha' => $request->getParameter('captcha'))), $request->getFiles('feedback'));
			if ($this->form->isValid())
			{
				$feedback = $this->form->getValues();
				$feedback['browser'] = $_SERVER['HTTP_USER_AGENT'];
				
        try
        {
          aZendSearch::registerZend();
          $mail = new Zend_Mail();
          $mail->setBodyText($this->getPartial('feedbackEmailText', array('feedback' => $feedback)))
              ->setFrom($feedback['email'], $feedback['name'])
              ->addTo(sfConfig::get('app_aFeedback_email_auto'))
              ->setSubject($this->form->getValue('subject', 'New aBugReport submission'));

          if ($screenshot = $this->form->getValue('screenshot'))
          {
            $mail->createAttachment(file_get_contents($screenshot->getTempName()), $screenshot->getType());
          }
          
          $mail->send();

          // A new form for a new submission
          $this->form = new aFeedbackForm();      
        }
        catch (Exception $e)
        {
          $this->logMessage('Request email failed: '. $e->getMessage(), 'err');
          $this->failed = true;

          return 'Success';
        }
      	
      	$this->getUser()->setFlash('reportSubmittedBy', $feedback['name']);
        $this->redirect($feedback['section']);
			}
		}
	}
}
