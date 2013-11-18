<?php
/**
 * Phatso - A PHP Micro Framework
 * Copyright (C) 2008, Judd Vinet <jvinet@zeroflux.org>
 * 
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 * 
 * (1) The above copyright notice and this permission notice shall be
 *     included in all copies or substantial portions of the Software.
 * (2) Except as contained in this notice, the name(s) of the above
 *     copyright holders shall not be used in advertising or otherwise
 *     to promote the sale, use or other dealings in this Software
 *     without prior written authorization.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 *
 * Version 0.1 :: 2008-10-03
 *   - initial release
 * Version 0.2 :: 2009-04-30
 *   - optimizations by Woody Gilk <woody.gilk@kohanaphp.com>
 *   - auto-detect base web root for relative URLs
 * Version 0.2.1 :: 2009-05-31
 *   - bug reported by Sebastien Duquette
 * Version 0.3
 * modified by Ivan Ribas
 * - display errors using default layout
 * - added beforeFilter and afterFilter
 * - pass arguments as variables instead of arrays
 * - allow to redirect to external urls
 * - added the debug property
 */

function debug($arg) {
	$args = func_get_args();
	echo '<pre>';
	foreach($args as $arg) {
		echo '(', gettype($arg), ') ', print_r($arg, TRUE)."<br/>\n";
	}
	echo '</pre>';
}

class Phatso {
	var $layout = 'layout';
	var $template_vars = array();
	var $params = array();
	var $views_dir = 'views';
	var $hasRendered = false;
	var $web_root = '';
	var $debug = false;
	var $action = null;

	/**
	 * Dispatch web request to correct function, as defined by
	 * URL route array.
	 */

	function run($urls) {
		if($this->debug) error_reporting(E_ALL & E_NOTICE);
		
		$break = explode('/', $_SERVER['SCRIPT_NAME']);
		unset($break[count($break)-1]);
		$this->web_root = implode('/', $break).'/';
		
		$ctrl = trim(substr($_SERVER['REQUEST_URI'], strlen($this->web_root)), '/').'/';
		if($ctrl{0} != '/') $ctrl = "/$ctrl";

		$action = '';
		foreach($urls as $request=>$route) {
			if(preg_match('#^'.$request.'$#', $ctrl, $matches)) {
				$this->action = $action = $route;
				if(!empty($matches[1])) {
					$this->params = explode('/', trim($matches[1], '/'));
				}
				break;
			}
		}
		$this->runAction($action);
	}


	function runAction($action) {
		$this->beforeFilter();		
		if(!method_exists($this, $action)) {
			$this->status('404', 'File not found');
		}
		switch (count($this->params)) {
			case 0:
				$this->{$action}();
				break;
			case 1:
				$this->{$action}($this->params[0]);
				break;
			case 2:
				$this->{$action}($this->params[0], $this->params[1]);
				break;
			case 3:
				$this->{$action}($this->params[0], $this->params[1], $this->params[2]);
				break;
			case 4:
				$this->{$action}($this->params[0], $this->params[1], $this->params[2], $this->params[3]);
				break;
			default:
				call_user_func_array(array(&$this, $action), $this->params);
				break;
		 }	
		$this->render($action);
		$this->afterFilter();		
	}

	/**
	 * Set HTTP status code and exit.
	 */

	function status($code, $msg) {
		header("{$_SERVER['SERVER_PROTOCOL']} $code");
		if(method_exists($this, 'status'.$code)) {
			call_user_func(array(&$this, 'status'.$code), $msg);
			$this->render('status'.$code);
		}
		else {
			die($msg);			
		}
		exit;
	}

	/**
	 * Redirect to a new URL
	 */
	function redirect($url) {
		header("Location: $url");
		exit;
	}

	/**
	 * Set a template variable.
	 */
	function set($name, $val) {
		$this->template_vars[$name] = $val;
	}

	/**
	 * Render a template and return the content.
	 */
	function fetch($template_filename, $vars=array()) {
		$vars = array_merge($this->template_vars, $vars);
		ob_start();
		if(file_exists($this->views_dir.'/'.$template_filename.".php")) {
			extract($vars, EXTR_SKIP);
			require $this->views_dir.'/'.$template_filename.".php";			
		}
		else {
			echo "View not found:".$template_filename.".php";
		}
		return str_replace('/.../', $this->web_root, ob_get_clean());
	}

	/**
	 * Render a template (with optional layout) and send the
	 * content to the browser.
	 */

	function render($filename, $vars=array()) {
		if($this->hasRendered) return true;
		$vars['content_for_layout'] = $this->fetch($filename, $vars);
		echo $this->fetch($this->layout, $vars);
		$this->hasRendered = true;
	}

	/** 
	 * abstract methods
	 */
	
	function beforeFilter() {}
	function afterFilter() {}
}