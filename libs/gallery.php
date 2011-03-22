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

class Gallery extends Phatso {
	
	var $data_folder = 'data';
	var $photosets_cache_file = 'photosets.txt';
	var $collections_cache_file = 'collections.txt';
	var $settings_file = 'settings.txt';
	var $api_url = 'http://api.flickr.com/services/rest/';
	var $api_key = null;
	var $user_id = null;
	var $photosets_cache = null;
	var $collections_cache = null;
	var $settings = null;

	/**
	 * beforeFilter
	 * method overload to set up the script loading settings and default variables
	 */

	function beforeFilter() {
		session_start();

		$this->settings = $this->data_folder.DIRECTORY_SEPARATOR.$this->settings_file;
		$this->photosets_cache = $this->data_folder.DIRECTORY_SEPARATOR.$this->photosets_cache_file;
		$this->collections_cache =  $this->data_folder.DIRECTORY_SEPARATOR.$this->collections_cache_file;
	
		$base = 'http://'.$_SERVER['SERVER_NAME'];
		if($_SERVER['SERVER_PORT']!=80) $base.= ':'.$_SERVER['SERVER_PORT'];
		
		$this->set('base', $base.$this->web_root);
		$this->set('gallery_name', 'Glickr - flickr photo gallery');

		if(!file_exists($this->settings) and $this->action!='install') {
			$this->redirect($this->web_root.'install');	
		} 	
		elseif(file_exists($this->settings)) {
			$settings = unserialize(file_get_contents($this->settings));
			$this->__delCache($settings['cache']);
			$this->api_key = $settings['api_key'];
			$this->user_id = $settings['user_id'];
			$this->set('gallery_name', $settings['gallery_name']);
		}
	}
	
	/**
	 * gallery index
	 * if user does not have collections redirect to photosets, if there are not photosets
	 * try loading all user photos from flickr.
	 */

	function index() {
		$collections = $this->__getCollections();
		if(!empty($collections)) {
			$this->set('collections', $collections);
			$this->render('collections');			
		}
		else {
			$photosets = $this->__getPhotosets();
			if(!empty($photosets)) {
				$this->set('photosets', $photosets);
				$this->render('photosets');
			}
			else {
				$this->runAction('photos');
			}
		}
	}
	
	/**
	 * show the collections index
	 */

	function collections() {
		$this->set('collections', $this->__getCollections());
		$this->set('pageTitle', 'collections');
	}

	/**
	 * gallery photosets index
	 * display all user sets with one thumbnail per set
	 */

	function photosets() {
		$this->set('photosets', $this->__getPhotosets());
		$this->set('pageTitle', 'photosets');		
	}
	
	/**
	 * photoset page
	 * display thumbnails for all the fotos in the set 
	 *
	 * @param $photoset_id
	 */

	function photoset($photoset_id) {
		$photosets = $this->__getPhotosets();
		$photoset = null;
		foreach($photosets as $ps) {
			if($ps['id'] == $photoset_id) {
				$photoset = $ps;	
				break;
			}
		}
		if($photoset==null) $this->status('404', 'File not found');
		
		$this->set('pageTitle', htmlspecialchars($photoset['title']['_content']));
		$this->set('photoset', $photoset);
	}

	/**
	 * show sets in collection
	 *
	 * @param $collection_id int
	 */

	function collection($collection_id) {
		$collection = $this->__getCollection($collection_id, $this->__getCollections());
		if($collection == null) $this->status('404', 'File not found');

		// get photosets in this collection
		$sets_id = array();
		if(isset($collection['set'])) {
			foreach($collection['set'] as $set) {
				array_push($sets_id, $set['id']);
			}
		}
		
		$photosets = $this->__getPhotosets();
		$sets = array();
		foreach($photosets as $photoset) {
			if(in_array($photoset['id'], $sets_id)) {
				array_push($sets, $photoset);
			}
		}

		// get subcollections in this collection
		$subcollections = array();
		if(isset($collection['collection'])) {
			foreach($collection['collection'] as $subcollection) {
				array_push($subcollections, $subcollection);
			}
		}

		$this->set('collection', $collection);
		$this->set('subcollections', $subcollections);
		$this->set('photosets', $sets);
		$this->set('pageTitle', $collection['title']);
		$this->render('photosets');
	}
	
	/**
	 * show all user photos connecting to flickr and not using cache
	 *
	 * @param $page int
	 */

	function photos($page = 1) {
		$page = intval($page);
		if($page < 1) $this->status('404', 'File not found');
		$photos = $this->__getPhotos($page);
		$_SESSION['photos'] = $photos;
		$_SESSION['page'] = $page;
		$this->set('photos', $photos);
		$this->set('pageTitle', 'page '.$page);
	}

	/**
	 * photo page
	 * display photo in medium size with links to next and previous photos in current photoset
	 * 
	 * @param $photoset_id int
	 * @param $photo_id int
	 */

	function photo($photoset_id, $photo_id) {
		$photosets = $this->__getPhotosets();
		$photoset = null;
		$photo = null;
		$prev = null;
		$next =  null;
		
		foreach($photosets as $ps) {
			if($ps['id'] == $photoset_id) {
				$photoset = $ps;	
				break;
			}
		}
		if($photoset==null) $this->status('404', 'File not found');
		
		foreach($photoset['photoset_photos']['photoset']['photo'] as $p) {
			if($p['id'] == $photo_id) {
				$photo = $p;
			}
			elseif($photo == null){
				$prev = $p;
			}
			elseif($photo!=null) {
				$next = $p;
				break;
			}
		}	
		if($photo==null) $this->status('404', 'File not found');
		
		$this->set('user_id', $this->user_id);
		$this->set('pageTitle', htmlspecialchars($photo['title']));
		$this->set('photoset',$photoset);
		$this->set('prev', $prev);
		$this->set('next', $next);			
		$this->set('photo', $photo);
	}

	/**
	 * load photo from flickr, no cache is used here.
	 * if page number is stored in session set the variable for the 'back' link
	 *
	 * @param $photo_id int
	 */
	
	function single_photo($photo_id) {
		$current_photo = null;
		$next = null;
		$previous = null;
		
		if(!($page = $_SESSION['page'])) $page = 1;

		if(isset($_SESSION['next_photo']) and $_SESSION['next_photo']==$photo_id) {
			$_SESSION['page'] = $_SESSION['page'] + 1;
			$_SESSION['photos'] = $this->__getPhotos($_SESSION['page']);
		}
		elseif(isset($_SESSION['prev_photo']) and $_SESSION['prev_photo']==$photo_id) {
			$_SESSION['page'] = $_SESSION['page']-1;
			$_SESSION['photos'] = $this->__getPhotos($_SESSION['page']);			
		}
		unset($_SESSION['prev_photo']);			
		unset($_SESSION['next_photo']);

		foreach($_SESSION['photos']['photos']['photo'] as $key=>$photo) {
			if($photo['id']==$photo_id) {
				$current_photo = $photo;
			
				// is last photo in current page
				if($key==sizeof($_SESSION['photos']['photos']['photo'])-1 and 
					$_SESSION['page'] < $_SESSION['photos']['photos']['pages']) {
						$tmp_photos = $this->__getPhotos($_SESSION['page']+1);
						$next = $tmp_photos['photos']['photo'][0];
						$_SESSION['next_photo'] = $next['id'];
				}
				// is first photo in current page
				if($key==0 and $_SESSION['page']>1) {
					$tmp_photos = $this->__getPhotos($_SESSION['page']-1);
					$size = sizeof($tmp_photos['photos']['photo'])-1;
					$prev = $tmp_photos['photos']['photo'][$size];
					$_SESSION['prev_photo'] = $prev['id'];
				}
			}
			elseif($current_photo == null) {
				$prev = $photo;
			}
			elseif($current_photo!=null) {
				$next = $photo;
				break;
			}
		}
		
		$params = array(
			'method' => 'flickr.photos.getInfo',
			'photo_id' => $photo_id
		);
		$photo = $this->__getResult($params);
		if($photo['stat']!= 'ok') $this->status('404', 'File not found');
		$photo['photo']['title'] = $photo['photo']['title']['_content'];
		
		$this->set('user_id', $this->user_id);
		$this->set('next', $next);
		$this->set('prev', $prev);
		$this->set('page', $_SESSION['page']);
		$this->set('photo', $photo['photo']);
		$this->set('pageTitle', htmlspecialchars($photo['photo']['title']));
	}

	/**
	 * load views using ajax layout
	 */

	function ajax() {
		$this->layout = 'ajax';
		if(empty($this->params)) {
			$this->runAction('index');			
		}
		else {
			$this->runAction(array_shift($this->params));			
		}
	}

	/**
	 * call this method to manually rebuild the cache files
	 */
	
	function rebuildCache() {
		$this->__rebuildCache();
		$this->set('status', '200 - OK');
		$this->layout = 'ajax';
	}
	
	/**
	 * install
	 * if setting file dows not exist install the script and create settings file
	 */
	
	function install() {
		if(file_exists($this->settings)) $this->status('404', 'File not found');	
		if(!empty($_POST)) {
			file_put_contents($this->settings,serialize($_POST));
			$this->redirect($this->web_root);
		}
		$this->set('writable', is_writable($this->data_folder));
		$this->set('pageTitle', 'Install');
	}
	
	/**
	 * status404
	 * method for the 404 page not found error
	 *
	 * @param $msg string
	 */

	function status404($msg) {
		$this->set('pageTitle', $msg);
		$this->set('msg', $msg);
	}
	
	/**
	 * status 503
	 * service unavailable. Connection to flickr failed.
	 *
	 * @param $msg string
	 */
	
	function status503($msg) {
		$this->set('pageTitle', $msg);
		$this->set('msg', $msg);
	}
	
	/**
	 * Connect to flickr api to send a request and return the result
	 * It can redirect to error page if connection failed
	 *
	 * @param $params array
	 */
	
	function __getResult($params) {
		$params['format'] = 'php_serial';
		$params['api_key'] = $this->api_key;
		
		$encoded_params = array();
		foreach ($params as $k => $v){
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		
		$rsp = @file_get_contents($this->api_url.'?'.implode('&', $encoded_params));
		$rsp_obj = unserialize($rsp);

		if(!$rsp_obj['stat']=='ok') {
			switch($rsp_obj['code']) {
				case '100':
					$msg = 'Invalid API key.';
					break;
				case '1':
					$msg = 'User not found.';
					break;
				default:
					$msg = 'Connection to Flickr failed.';
					break;
			}
			$this->status('503', 'Service unavailable. '.$msg);
		}

		return $rsp_obj;		
	}	

	/**
	 * load photosets cache file
	 * if cache file does not exist retrive data from flickr and create a cache file
	 */

	function __getPhotosets() {
		if(!file_exists($this->photosets_cache)) {
			$this->__rebuildCache();
		}
		return unserialize(file_get_contents($this->photosets_cache));
	}
	
	/**
	 * load cache file for collections
	 * if it does not exist rebuild cache files
	 */

	function __getCollections() {
		if(!file_exists($this->collections_cache)) {
			$this->__rebuildCache();
		}
		$collections = unserialize(file_get_contents($this->collections_cache));
		return $collections['collections']['collection']; 
	}

	/**
	 * get user photos by page number
	 *
	 * @param $page int
	 */

	function __getPhotos($page) {
		$params = array(
			'method' => 'flickr.photos.search',
			'user_id' => $this->user_id,
			'per_page' => '104',
			'page' => $page
		);
		$photos = $this->__getResult($params);
		if(empty($photos['photos']['photo'])) $this->status('404', 'File not found');
		return $photos;		
	}
	
	/**
	 * recursive function to return collection or subcollection by id 
	 *
	 * @param $collection_id int
	 * @param $collections array
	 **/
	 
	 function __getCollection($collection_id, $collections) {
		$collection = null;
		foreach($collections as $cl) {
			if($cl['id'] == $collection_id) {
				$collection = $cl;
			}
			else if(isset($cl['collection'])){
				$collection = $this->__getCollection($collection_id, $cl['collection']);
			}
			if($collection != null) break;
		}
		return $collection;	 
	 }	

	/**
	 * delete cache files if they expired
	 *
	 * @param $cache int
	 */

	function __delCache($cache) {
		switch(true) {
			case ($cache==1 && filemtime($this->photosets_cache) < time() - (7 * 24 * 60 * 60)):
			case ($cache==1 && filemtime($this->collections_cache) < time() - (7 * 24 * 60 * 60)):
			case ($cache==2 && filemtime($this->photosets_cache) < time() - (30 * 24 * 60 * 60)):
			case ($cache==1 && filemtime($this->collections_cache) < time() - (30 * 24 * 60 * 60)):
				unlink($this->photosets_cache);
		}		
	}

	/**
	 * rebuild cache
	 *
	 * delete the cache file and rebuild it again retriving data from flickr
	 * run this if you made changes in your flickr sets
	 */

	function __rebuildCache() {
		$params = array(
			'method' => 'flickr.photosets.getList',
			'user_id' => $this->user_id
			);
		$photosets = $this->__getResult($params);	
		if($photosets['stat']=='ok') {
			$ps = array();
 	   		foreach($photosets['photosets']['photoset'] as $photoset) {
				$params = array(
					'method' => 'flickr.photosets.getPhotos',
					'photoset_id' => $photoset['id']
					);
				$rsp = $this->__getResult($params);
 	   			$photoset['photoset_photos'] = $rsp;
 	   			array_push($ps, $photoset);
 	   		}
		}
		file_put_contents($this->photosets_cache,serialize($ps));

		$params = array(
			'method' => 'flickr.collections.getTree',
			'user_id' => $this->user_id
		);
		$collections = $this->__getResult($params);
		file_put_contents($this->collections_cache,serialize($collections));		
	}
}
?>