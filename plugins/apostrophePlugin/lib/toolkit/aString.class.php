<?php

/**
 * Tools, utilities and snippets collected and composed...
 */

class aString
{
	/**
	* Limits the number of words in a string.
	*
	* @param string $string
	*
	* @param uint $word_limit
	*   number of words to return
	* 
	* @param optional array
	* 	if $options['append_ellipsis'] is set, append that string to the end 
  *   of strings that have been truncated
  *   if $options['characters'] is true, limit by characters rather than words
	*   (a single API call for both is convenient when this is wrapped by other calls)
	*
	* Whitespace will be collapsed to single spaces. UTF8-aware where supported
	*
	* @return string
	*   new string containing only words up to the word limit.
	*/
	public static function limitWords($string, $word_limit, $options = array())
	{
	  $regexp = '/\s+/';
	  if (function_exists('mb_strtolower'))
    {
      $regexp .= 'u';
    }
	  $words = preg_split($regexp, $string, $word_limit + 1);
    $num_words = count($words);
	  if (isset($options['characters']) && $options['characters'])
	  {
	    // Call limitCharacters, but only after ensuring the same space-folding behavior
	    return aString::limitCharacters(implode(' ', $words), $word_limit, $options);
	  }

		# TBB: if there are $word_limit words or less, this check is necessary
    # to prevent the last word from being lost.
		if ($num_words > $word_limit)
		{
      array_pop($words);
    }
	  
		$string = implode(' ', $words);
		
		$append_ellipsis = false;
		if (isset($options['append_ellipsis']))
		{
			$append_ellipsis = $options['append_ellipsis'];
		}
		if ($append_ellipsis == true && $num_words > $word_limit)
		{
			$string .= '&hellip;';
		}
		
		return $string;
	}

	/**
	* Limits the number of characters in a string.
	*
	* @param string $string
	*
	* @param uint $character_limit
	*   maximum number of characters to return, inclusive of any added ellipsis
	*   NOTE: this is characters, not bytes (think UTF8). Be generous with columns
	* 
	* @param optional array
	* 	if $options['append_ellipsis'] is set, append that string to the end 
  *   of strings that have been truncated
	*
	* @return string
	*   new string containing only characters up to the limit
  * 
  * Suitable when a word count limit is not enough (because words are
  * sometimes unreasonably long).
  *
  * Tries to preserve word boundaries, but not too hard, as very long words can
  * create problems of their own.
	*/
  public static function limitCharacters($s, $length, $options = array())
  {
    $ellipsis = "";
    if (isset($options['append_ellipsis']) && $options['append_ellipsis'])
    {
      $ellipsis = "...";
    }
    if ($length < 12)
    {
      // Not designed to be elegant below this length
      return aString::substr($s, 0, $length);
    }
    if (aString::strlen($s) > $length)
    {
      $s = aString::substr($s, 0, $length - aString::strlen($ellipsis));
      $slength = aString::strlen($s);
      for ($i = 1; ($i <= 10); $i++)
      {
        $c = aString::substr($s, $slength - $i, 1);
        if (($c === ' ') || ($c === '\t') || ($c === '\r') || ($c === '\n'))
        {
          return aString::substr($s, 0, $slength) . $ellipsis;
        }
      }
      return $s . $ellipsis;
    }
    return $s;
  }
	
 	/**
  *
	* Accepts an array of keywords and a text; returns the portion of the
  * text beginning a few words prior to the first keyword encountered,
  * and continuing to the end of the text. If none of the keywords are
  * seen, returns the entire text.
  *
	* @param array $terms keywords
  * @param string $text
	*
	* @return string
  *
	*/
  public static function beginNear($keywords, $text)
  {
    foreach ($keywords as $keyword) {
      # TODO: can we do this without so many calls? I don't want
      # to capture an arbitrary number of words preceding - no more
      # than three - and I don't want to reject cases with fewer
      # than three preceding either. 
      $keyword = preg_quote($keyword, '/');
      for ($wordsPreceding = 3; ($wordsPreceding >= 0); $wordsPreceding--) {
        $regexp = "(" . 
          str_repeat("\w+\W+", $wordsPreceding) . ")(" . $keyword . ")" . "(.*)/is";
        if (function_exists('mb_strtolower'))
        {
          $regexp .= 'u';
        }
        if (preg_match("/^" . $regexp, $text, $matches)) {
          return $matches[1] . "<b>" . $matches[2] . "</b>" . $matches[3]; 
        } 
        if (preg_match("/" . $regexp, $text, $matches)) {
          return "... " . $matches[1] . "<b>" . $matches[2] . "</b>" . $matches[3]; 
        } 
      }
    }
    return false;
  }
  
 	/**
  *
	* Accepts two text strings; returns a human-friendly representation of
	* the difference between them. The strategy is to word-wrap the strings
	* at a reasonably short boundary, split at line breaks, and then use
	* array_diff (in both directions) to discover differences. This function
	* returns an array like this:
	*
	* array(
  *   "onlyin1" => 
	*     array("first line unique to 1", "second line unique to 1..."), 
	*   "onlyin2" => 
	*     array("first line unique to 2", "second line unique to 2...")
	* )
	* It is suggested that, at a minimum, the first line of
	* onlyin1 be displayed (with visual cues to indicate that it is gone in 2)
	* and the first line of onlyin2 also be displayed (with visual cues to indicate
	* that is new in 2). 
	*
	* TODO: detect situations in which content has been purely rearranged rather
	* than edited, deleted or added, add preceding and trailing context, etc.
	* These are all going to be a lot less efficient than this simple
	* implementation though.
  *
	* @param string $text1
  * @param string $text2
	*
	* @return array
  *
	*/
  
  public static function diff($text1, $text2)
  {
    $array1 = array_map('trim', explode("\n", wordwrap($text1, 70)));
    $array2 = array_map('trim', explode("\n", wordwrap($text2, 70)));
    $onlyin1 = array_values(array_diff($array1, $array2));
    $onlyin2 = array_values(array_diff($array2, $array1));
    if (count($onlyin1) && count($onlyin2))
    {
      // The first line is critical because history displays
      // so little of a diff. So remove any shared prefix from the
      // first deleted and first added lines unless that means we'd
      // take it all
      $s1 = $onlyin1[0];
      $s2 = $onlyin2[0];
      if (strlen($s1) !== strlen($s2))
      {
        $min = min(strlen($s1), strlen($s2));
        for ($i = 0; ($i < $min); $i++)
        {
          $c1 = substr($s1, $i, 1);
          $c2 = substr($s2, $i, 1);
          if ($c1 !== $c2)
          {
            break;
          }
        }
        $onlyin1[0] = substr($s1, $i);
        $onlyin2[0] = substr($s2, $i);
        if (!strlen($onlyin1[0]))
        {
          array_shift($onlyin1);
        }
        if (!strlen($onlyin2[0]))
        {
          array_shift($onlyin2);
        }
      }
    }
    return array("onlyin1" => array_values($onlyin1), "onlyin2" => array_values($onlyin2));
  }
  
  static public function strtolower($s)
  {
    if (function_exists('mb_strtolower'))
    {
      return mb_strtolower($s, 'UTF-8');
    }
    else
    {
      return strtolower($s);
    }
  }

  static public function strlen($s)
  {
    if (function_exists('mb_strlen'))
    {
      return mb_strlen($s, 'UTF-8');
    }
    else
    {
      return strlen($s);
    }
  }

  static public function substr($s, $start, $length = null)
  {
    // Frustratingly you can't pass 'null' as a safe way of skipping the length
    // parameter, even with mb_substr which takes a fourth 'encoding' argument, so you
    // have to make a superfluous mb_strlen call
    if (function_exists('mb_substr'))
    {
      return mb_substr($s, $start, is_null($length) ? mb_strlen($s) : $length, 'UTF-8');
    }
    else
    {
      return substr($s, $start, is_null($length) ? strlen($s) : $length);
    }
  }
  
  static public function firstLine($s)
  {
    $ln = strpos($s, "\n");
    if ($ln === false)
    {
      return $s;
    }
    return substr($s, 0, $ln);
  }
  
  static public function toVcal($s)
  {
    // vcal is fairly picky. Avoid a lot of problems by
    // simplifying whitespace
    $s = preg_replace('/\s+/', ' ', $s);
    $s = trim($s);
    $s = addslashes($s);
    return $s;
  }
}

