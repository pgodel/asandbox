<?php a_area('inset-'.$name.'-'.$permid, array(
	'slug' => (isset($areaOptions['slug'])) ? $areaOptions['slug'] : null, 
	'edit' => (isset($areaOptions['edit'])) ? $areaOptions['edit'] : null,
	'allowed_types' => array(
		'aRichText',
		'aVideo',
		'aSlideshow',
		'aSmartSlideshow',
		'aFile',
		'aAudio',
		'aFeed',
		'aButton',
		'aBlog',
		'aEvent',
		'aText',
		'aRawHTML',
	),
  'type_options' => array(
		'aRichText' => array(
		  'tool' => 'Sidebar',
			// 'allowed-tags' => array(),
			// 'allowed-attributes' => array('a' => array('href', 'name', 'target'),'img' => array('src')),
			// 'allowed-styles' => array('color','font-weight','font-style'),
		),
		'aVideo' => array(
			'width' => $areaOptions['width'],
			'height' => $areaOptions['height'],
			'resizeType' => $areaOptions['resizeType'],
			'flexHeight' => $areaOptions['flexHeight'],
			'title' => false,
			'description' => false,
		),
		'aSlideshow' => array(
			'width' => $areaOptions['width'],
			'height' => $areaOptions['height'],
			'resizeType' => $areaOptions['resizeType'],
			'flexHeight' => $areaOptions['flexHeight'],
			'constraints' => array('minimum-width' => $areaOptions['width']),
			'arrows' => true,
			'interval' => false,
			'random' => false,
			'title' => false,
			'description' => false,
			'credit' => false,
			'position' => false,
			'itemTemplate' => 'slideshowItem',
		),
		'aSmartSlideshow' => array(
			'width' => $areaOptions['width'],
			'height' => $areaOptions['height'],
			'resizeType' => $areaOptions['resizeType'],
			'flexHeight' => $areaOptions['flexHeight'],
			'constraints' => array('minimum-width' => $areaOptions['width']),
			'arrows' => true,
			'interval' => false,
			'random' => false,
			'title' => false,
			'description' => false,
			'credit' => false,
			'position' => false,
			'itemTemplate' => 'slideshowItem',
		),
		'aFile' => array(
		),
		'aAudio' => array(
			'width' => $areaOptions['width'],
			'title' => true,
			'description' => true,
			'download' => true,
			'playerTemplate' => 'default',
		),
		'aFeed' => array(
			'posts' => 5,
			'links' => true,
			'dateFormat' => false,
			'itemTemplate' => 'aFeedItem',
			// 'markup' => '<strong><em><p><br><ul><li><a>',
			// 'attributes' => false,
			// 'styles' => false,
		),
		'aButton' => array(
			'width' => $areaOptions['width'],
			'flexHeight' => $areaOptions['flexHeight'],
			'resizeType' => $areaOptions['resizeType'],
			'constraints' => array('minimum-width' => $areaOptions['width']),
			'rollover' => true,
			'title' => true,
			'description' => false
		),
		'aBlog' => array(
			'slideshowOptions' => array(
				'width' => $areaOptions['width'],
				'height' => $areaOptions['height']
			),
		),
		'aEvent' => array(
			'slideshowOptions' => array(
				'width' => $areaOptions['width'],
				'height' => $areaOptions['height']
			),
		),
    'aText' => array(
			'multiline' => true
		),
		'aRawHTML' => array(
		),
	))) ?>