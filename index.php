<?php
/**
 * Copyright 2009 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */

ini_set('display_errors', 'Off');	// do not display errors

require_once('libs/phatso.php');
require_once('libs/gallery.php');

$ROUTES = array(
	'/'  => 'index',  
	'/photoset/(.*)' => 'photoset',			// photoset photo thumbnails
	'/photosets/' => 'photosets',			// all photosets
	'/collections/' => 'collections',		// all collections
	'/collection/(.*)' => 'collection',		// photosets in collection
	'/photo/(.*)' => 'photo',				// photo in photoset
	'/single_photo/(.*)' => 'single_photo',	// photo loaded from flickr
	'/photos/(.*)' => 'photos',				// all photos
	'/rebuildCache/' => 'rebuildCache',		// rebuild photosets and collections cache
	'/install/' => 'install',				// install script
	'/ajax/(.*)' => 'ajax'					// load pages using ajax layout
);

$app = new Gallery();
$app->run($ROUTES);
?>