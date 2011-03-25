<?php

// Loading of the a CSS, JavaScript and helpers is now triggered here 
// to ensure that there is a straightforward way to obtain all of the necessary
// components from any partial, even if it is invoked at the layout level (provided
// that the layout does use_helper('a'). 

function _a_required_assets()
{
  $response = sfContext::getInstance()->getResponse();
  $user = sfContext::getInstance()->getUser();

  sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'I18N'));

  // Do not load redundant CSS and JS in an AJAX context. 
  // These are already loaded on the page in which the AJAX action
  // is operating. Please don't change this as it breaks or at least
  // greatly slows updates
  if (sfContext::getInstance()->getRequest()->isXmlHttpRequest())
  {
    return;
  }

	aTools::addStylesheetsIfDesired();

  aTools::addJavascriptsIfDesired();
}

_a_required_assets();

function a_slot($name, $type, $options = false)
{
  $options = a_slot_get_options($options);
  $options['type'] = $type;
	$options['singleton'] = true;
  aTools::globalSetup($options);
  include_component("a", "area", 
    array("name" => $name, "options" => $options)); 
  aTools::globalShutdown();
}

function a_area($name, $options = false)
{
  $options = a_slot_get_options($options);
  $options['infinite'] = true; 
  aTools::globalSetup($options);
  include_component("a", "area", 
    array("name" => $name, "options" => $options)); 
  aTools::globalShutdown();
}

function a_slot_get_options($options)
{
  if (!is_array($options))
  {
    if ($options === false)
    {
      $options = array();
    }
    else
    {
      $options = aTools::getSlotOptionsGroup($options);
    }
  }
  return $options;
}

function a_slot_body($name, $type, $permid, $options, $validationData, $editorOpen, $updating = false)
{
  $page = aTools::getCurrentPage();
  $slot = $page->getSlot($name);
  $parameters = array("options" => $options);
  $parameters['name'] = $name;
  $parameters['type'] = $type;
  $parameters['permid'] = $permid;
  $parameters['validationData'] = $validationData;
  $parameters['showEditor'] = $editorOpen;
  $parameters['updating'] = $updating;
  $user = sfContext::getInstance()->getUser();
  $controller = sfContext::getInstance()->getController();
  $moduleName = $type . 'Slot';
  if ($controller->componentExists($moduleName, "executeSlot"))
  {
    include_component($moduleName, "slot", $parameters);
  }
  else
  {
    include_component("a", "slot", $parameters);
  }
}

// Frequently convenient when you want to check an option in a template.
// Doing the isset() ? foo : bar dance over and over is bug-prone and confusing

function a_get_option($array, $key, $default = false)
{
  if (isset($array[$key]))
  {
    return $array[$key];
  }
  else
  {
    return $default;
  }
}

// THESE ARE DEPRECATED, use the aNavigationComponent instead

function a_navtree($depth = null)
{
  $page = aTools::getCurrentPage();
  $children = $page->getTreeInfo(true, $depth);
  return a_navtree_body($children);
}

function a_navtree_body($children)
{
  $s = "<ul>\n";
  foreach ($children as $info)
  {
    $s .= '<li>' . link_to($info['title'], aTools::urlForPage($info['slug']));
    if (isset($info['children']))
    {
      $s .= a_navtree_body($info['children']);
    }
    $s .= "</li>\n";
  }
  $s .= "</ul>\n";
  return $s;
}

function a_navaccordion()
{
  $page = aTools::getCurrentPage();
  $children = $page->getAccordionInfo(true);
  return a_navtree_body($children);
}

function a_get_stylesheets()
{
  $newStylesheets = array();
  $response = sfContext::getInstance()->getResponse();
  foreach ($response->getStylesheets() as $file => $options)
  {
    if (preg_match('/\.less$/', $file))
    {
      $absolute = false;
      if (isset($options['absolute']) && $options['absolute'])
      {
        unset($options['absolute']);
        $absolute = true;
      }
      if (!isset($options['raw_name']))
      {
        $file = stylesheet_path($file, $absolute);
      }
      $path = sfConfig::get('sf_web_dir') . $file;
      
      $dir = aFiles::getUploadFolder(array('asset-cache'));
      $name = md5($file) . '.less.css';
      $compiled = "$dir/" . md5($file) . '.less.css';
      
      // When minify is turned on we already have a policy that you are responsible for
      // hitting it with a 'symfony cc' to clear the asset cache if you make changes; so the
      // only thing we check for is whether the compiled CSS file exists
      
      // When minify is not turned on (usually in dev) we should do everything we can to be as 
      // tolerant as hitting refresh on a page with plain .css files in it would be, so we need to
      // check the modification time of the .less file against the compiled file
      
      if ((!file_exists($compiled)) || ((!sfConfig::get('app_a_minify')) && (filemtime($compiled) < filemtime($path))))
      {
        if (!isset($lessc))
        {
          $lessc = new lessc();
        }
        $lessc->importDir = dirname($path).'/';
        file_put_contents($compiled, $lessc->parse(file_get_contents($path)));
      }
      $newStylesheets[sfConfig::get('app_a_assetCacheUrl', '/uploads/asset-cache') . '/' . $name] = $options;
    }
    else
    {
      $newStylesheets[$file] = $options;
    }
  }
  return _a_get_assets_body('stylesheets', $newStylesheets);
}

function a_get_javascripts()
{
  if (sfConfig::get('app_a_minify', false))
  {
    $response = sfContext::getInstance()->getResponse();
    return _a_get_assets_body('javascripts', $response->getJavascripts());
  }
  else
  {
    return get_javascripts();
  }
}

function _a_get_assets_body($type, $assets)
{
  $gzip = sfConfig::get('app_a_minify_gzip', false);
  sfConfig::set('symfony.asset.' . $type . '_included', true);

  $html = '';

  // We need our own copy of the trivial case here because we rewrote the asset list
  // for stylesheets after LESS compilation, and there is no way to
  // reset the list in the response object
  if (!sfConfig::get('app_a_minify', false))
  {
		// This branch is seen only for CSS, because javascript calls the original Symfony
		// functionality when minify is off
    foreach ($assets as $file => $options)
    {
      $html .= stylesheet_tag($file, $options);
    }
    return $html;
  }
  
  $sets = array();
  foreach ($assets as $file => $options)
  {
		if (preg_match('/^http(s)?:/', $file))
		{
			// Nonlocal URL. Don't get cute with it, otherwise things
			// like Addthis don't work
			if ($type === 'stylesheets')
			{
      	$html .= stylesheet_tag($file, $options);
			}
			else
			{
      	$html .= javascript_include_tag($file, $options);
			}
			continue;
		}
    /*
     *
     * Guts borrowed from stylesheet_tag and javascript_tag. We still do a tag if it's
     * a conditional stylesheet
     *
     */

    $absolute = false;
    if (isset($options['absolute']) && $options['absolute'])
    {
      unset($options['absolute']);
      $absolute = true;
    }

    $condition = null;
    if (isset($options['condition']))
    {
      $condition = $options['condition'];
      unset($options['condition']);
    }

    if (!isset($options['raw_name']))
    {
      if ($type === 'stylesheets')
      {
        $file = stylesheet_path($file, $absolute);
      }
      else
      {
        $file = javascript_path($file, $absolute);
      }
    }
    else
    {
      unset($options['raw_name']);
    }

    if ($type === 'stylesheets')
    {
      $options = array_merge(array('rel' => 'stylesheet', 'type' => 'text/css', 'media' => 'screen', 'href' => $file), $options);
    }
    else
    {
      $options = array_merge(array('type' => 'text/javascript', 'src' => $file), $options);
    }
    
    if (null !== $condition)
    {
      $tag = tag('link', $options);
      $tag = comment_as_conditional($condition, $tag);
      $html .= $tag . "\n";
    }
    else
    {
      unset($options['href'], $options['src']);
      $optionGroupKey = json_encode($options);
      $set[$optionGroupKey][] = $file;
    }
    // echo($file);
    // $html .= "<style>\n";
    // $html .= file_get_contents(sfConfig::get('sf_web_dir') . '/' . $file);
    // $html .= "</style>\n";
  }
  
  // CSS files with the same options grouped together to be loaded together

  foreach ($set as $optionsJson => $files)
  {
    $groupFilename = '';
    foreach ($files as $file)
    {
      $groupFilename .= $file;
      // If your CSS files depend on clever aliases that won't work
      // through the filesystem, we can get them by http. We're caching
      // so that's not terrible, but it's usually simpler faster and less
      // buggy to grab the file content.
    }
    // I tried just using $groupFilename as is (after stripping dangerous stuff) 
    // but it's too long for the OS if you include enough to make it unique
    $groupFilename = md5($groupFilename);
    $groupFilename .= (($type === 'stylesheets') ? '.css' : '.js');
    if ($gzip)
    {
      $groupFilename .= 'gz';
    }
    $dir = aFiles::getUploadFolder(array('asset-cache'));
    if (!file_exists($dir . '/' . $groupFilename))
    {
      $content  = '';
      foreach ($files as $file)
      {
        $path = null;
        if (sfConfig::get('app_a_stylesheet_cache_http', false))
        {
          $url = sfContext::getRequest()->getUriPrefix() . $file;
          $fileContent = file_get_contents($url);
        }
        else
        {
          $path = sfConfig::get('sf_web_dir') . $file;
          $fileContent = file_get_contents($path);
        }
        if ($type === 'stylesheets')
        {
          $options = array();
          if (!is_null($path))
          {
            // Rewrite relative URLs in CSS files.
            // This trick is available only when we don't insist on
            // pulling our CSS files via http rather than the filesystem
            
            // dirname would resolve symbolic links, we don't want that
            $fdir = preg_replace('/\/[^\/]*$/', '', $path);
            $options['currentDir'] = $fdir;
            $options['docRoot'] = sfConfig::get('sf_web_dir');
          }
          if (sfConfig::get('app_a_minify', false))
          {
            $fileContent = Minify_CSS::minify($fileContent, $options);
          }
        }
        else
        {
          // Trailing carriage return makes behavior more consistent with
          // JavaScript's behavior when loading separate files. For instance,
          // a missing trailing semicolon should be tolerated to the same
          // degree it would be with separate files. The minifier is not
          // a lint tool and should not surprise you with breakage
          $fileContent = JSMin::minify($fileContent) . "\n";
        }
        $content .= $fileContent;
      }
      if ($gzip)
      {
        _gz_file_put_contents($dir . '/' . $groupFilename . '.tmp', $content);
      }
      else
      {
        file_put_contents($dir . '/' . $groupFilename . '.tmp', $content);
      }
      @rename($dir . '/' . $groupFilename . '.tmp', $dir . '/' . $groupFilename);
    }
    $options = json_decode($optionsJson, true);
    // Use stylesheet_path and javascript_path so we can respect relative_root_dir
    if ($type === 'stylesheets')
    {
      $options['href'] = stylesheet_path(sfConfig::get('app_a_assetCacheUrl', '/uploads/asset-cache') . '/' . $groupFilename);
      $html .= tag('link', $options);
    }
    else
    {
      $options['src'] = javascript_path(sfConfig::get('app_a_assetCacheUrl', '/uploads/asset-cache') . '/' . $groupFilename);
      $html .= content_tag('script', '', $options); 
    }
  }
  return $html;
}

function a_include_stylesheets()
{
  echo(a_get_stylesheets());
}

function a_include_javascripts()
{
  echo(a_get_javascripts());
}

function _gz_file_put_contents($file, $contents)
{
  $fp = gzopen($file, 'wb');
  gzwrite($fp, $contents);
  gzclose($fp);
}

// Call like this:

// a_js_call('object.property[?].method(?, ?)', 5, 'name', 'bob')

// That is, use ?'s to insert correctly json-encoded arguments into your JS call.

// Another, less-contrived example:

// a_js_call('apostrophe.slideshowSlot(?)', array('id' => 'et-cetera', ...))

// Notice that arguments can be strings, numbers, or arrays - JSON can handle all of them.

// All calls made in this way are accumulated into a jQuery domready block which
// appears at the end of the body element in our standard layout.php via a_include_js_calls.
// We also insert these at the end when adding or updating a slot via AJAX. You can invoke it
// yourself in other layouts etc.

function a_js_call($callable /* , $arg1, $arg2, ... */ )
{
  $args = array_slice(func_get_args(), 1);
  a_js_call_array($callable, $args);
}

function a_js_call_array($callable, $args)
{
  aTools::$jsCalls[] = array('callable' => $callable, 'args' => $args);
}

function a_include_js_calls()
{
  echo(a_get_js_calls());
}

function a_get_js_calls()
{
  $html = '';
  if (count(aTools::$jsCalls))
  {
    $html .= '<script type="text/javascript">' . "\n";
    $html .= '$(function() {' . "\n";
    foreach (aTools::$jsCalls as $call)
    {
      $html .= _a_js_call($call['callable'], $call['args']);
    }
    $html .= '});' . "\n";
    $html .= '</script>' . "\n";
  }
  return $html;
}

function _a_js_call($callable, $args)
{
  $clauses = preg_split('/(\?)/', $callable, null, PREG_SPLIT_DELIM_CAPTURE);
  $code = '';
  $n = 0;
  $q = 0;
  foreach ($clauses as $clause)
  {
    if ($clause === '?')
    {
      $code .= json_encode($args[$n++]);
    }
    else
    {
      $code .= $clause;
    }
  }
  if ($n !== count($args))
  {
    throw new sfException('Number of arguments does not match number of ? placeholders in js call');
  }
  return $code . ";\n";
}

// i18n with less effort. Also more flexibility for the future in how we choose to do it  
function a_($s, $params = null)
{
  return __($s, $params, 'apostrophe');
}

// One consistent encoding is needed for non-HTML output in our templates, since we do not assume
// that Symfony is in escaping mode, and the correct statement is so verbose

function a_entities($s)
{
  return htmlentities($s, ENT_COMPAT, 'UTF-8');
}

function a_link_button($label, $symfonyUrl, $options = array(), $classes = array(), $id = null)
{
  return a_button($label, url_for($symfonyUrl, $options), $classes, $id);
}

function a_button($label, $url, $classes = array(), $id = null, $name = null, $title = null)
{
  $hasIcon = in_array('icon', $classes);
	$aLink = in_array('a-link', $classes);
	$arrowBtn = in_array('a-arrow-btn', $classes);
	
	// if it's an a-events button, grab the date and append it as a class
	$aEvents = in_array('a-events', $classes);
	if ($aEvents) {
		$classes[] = 'day-'.date('j');
	}
	
  $s = '<a ';
  if (!is_null($name))
  {
    $s .= 'name="' . a_entities($name) . '" ';
  }
  if (!is_null($title))
  {
    $s .= 'title="' . a_entities($title) . '" ';
  }
  $s .= 'href="' . a_entities($url) . '" ';
  if (!is_null($id))
  {
    $s .= 'id="' . a_entities($id) . '" ';
  }

	if (!$aLink && !$arrowBtn) {
	  $s .= 'class="a-btn ' . implode(' ', $classes) . '">';
	}
	else
	{
		// a-link shares similar physical characteristic to a-btn
		// but they avoid the aeshetic styling of a-btn entirely
  	$s .= 'class="' . implode(' ', $classes) . '">';
	}

  if ($hasIcon)
  {
    $s .= '<span class="icon"></span>';
  }
  $s .= a_($label) . '</a>';
  return $s;
}

// For a button that will have an icon, specify the icon class.

// Common cases to be aware of: 

// For a cancel button use the a-cancel class (if you also specify the icon class you get an x)

// Do not use for submit buttons. Due to longstanding problems with JS submit() 
// calls not being able to invoke both JavaScript handlers and the native submit 
// behavior in the correct way it is usually eventually necessary to use a real 
// submit button. Use a_submit_button to get one of those styled in the standard 
// Apostrophe way.

function a_js_button($label, $classes = array(), $id = null)
{
  return a_button($label, '#', $classes, $id);
}

// Even more convenient way to do a cancel button based on the above
function a_js_cancel_button($label = null, $classes = array(), $id = null)
{
  if (is_null($label))
  {
    $label = a_('Cancel');
  }
  $classes[] = 'a-cancel';
  return a_js_button($label, $classes, $id);
}

// A real submit button, styled for Apostrophe.
// Should not need an id - we style these things by
// class so there can be more than one on a page, right?

function a_submit_button($label, $classes = array(), $name = null)
{
  $s = '<input type="submit" value="' . a_entities($label) . '" class="a-btn a-submit ' . implode(' ', $classes) . '" ';
  if (!is_null($name))
  {
    $s .= 'name="' . a_entities($name) . '" ';
  }
  $s .= '/>';
  return $s;
}

// TODO: having the options here be the reverse of the options to
// a_button is absurd and we need an options array for both of them.
// For now this is more backward compatible

// An anchor tag 'submit button', styled for Apostrophe
// and configured behind the scenes to autosubmit the form when clicked 
// like a real submit button would. However, this should
// NOT be used in AJAX forms, because there is no consistent
// way to avoid triggering the native submit behavior of
// the form. For AJAX forms use real submit buttons
// or attach the desired submit behavior directly to the button

// A submit button should never need an id because you style them
// by class - on the other hand it often needs a name so it can
// be distinguished from other submit buttons when the form submission
// is received, just like a normal submit button

// You will often want to add the a-submit class, but not always as it's
// not always the visual impact you want

function a_anchor_submit_button($label, $classes = array(), $name = null, $id = null)
{
  $classes[] = 'a-btn';
  $classes[] = 'a-act-as-submit';
  return a_button($label, '#', $classes, $id, $name);
}

// A button that removes a filter (parameter) from the given URL.
// Uses the "label followed by an x" style. $parameter can be an array of
// several parameter names. Calls link_to on the URL. This means you can pass an easily manipulated 
// Symfony URL with &-separated params but get a user friendly routed URL as final output.
// This ought to call a_button but I'm wrestling with the incompatibility of inline
// content and a_button's CSS. Notice that it's playing out rather well in the blog engine. -Tom

function a_remove_filter_button($label, $url, $parameter)
{
  if (!is_array($parameter))
  {
    $parameter = array($parameter);
  }
  $remove = array();
  foreach ($parameter as $p)
  {
    // aUrl::addParams removes when the value is blank
    $remove[$p] = '';
  }
  $url = aUrl::addParams($url, $remove);
  return link_to($label . image_tag('/apostrophePlugin/images/a-icon-close-small-simple.png'), url_for($url), array('class' => 'a-filter-link', 'title' => 'Remove Filter'));
}

