<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginaRawHTMLSlot extends BaseaRawHTMLSlot
{
	protected $editDefault = true;
	
  public function getSearchText()
  {
    // Convert from HTML to plaintext before indexing by Lucene
    
    // However first add line breaks after certain tags for better formatting
    // (this method is also used for generating informational diffs between versions).
    // This is a noncritical feature so it doesn't have to be as precise
    // as strip_tags and shouldn't try to substitute for it in the matter of 
    // actually removing the tags
    $value = preg_replace("/(<p>|<br.*?>|<blockquote>|<li>|<dt>|<dd>|<nl>|<ol>)/i", "$1\n", $this->value);
    
    return aHtml::toPlaintext($value);
  }

  /**
   * Returns the plaintext representation of this slot
   */
  public function getText()
  {
    return $this->getSearchText();
  }
  
  /**
   * This function returns a basic HTML representation of your slot's comments
   * (passing the default settings of aHtml::simplify, for instance). Used for Google Calendar
   * buttons, RSS feeds and similar
   * @return string
   */
  public function getBasicHtml()
  {
    return aHtml::simplify($this->value);
  }
}