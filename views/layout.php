<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
	<base  href="<?php echo $base; ?>">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php 
		echo $gallery_name;
		if(isset($pageTitle) && !empty($pageTitle)) echo ' - '.$pageTitle;
	 ?></title>
	<link rel="stylesheet" type="text/css" href="/.../css/style.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js"></script>	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/scriptaculous.js?load=effects"></script>	
	<script type="text/javascript" src="/.../js/gallery-ajax.js"></script>	
</head>
<body>
	<div id="gallery-name"><a href="/.../"><?php echo $gallery_name; ?></a></div>
	<div id="main">
		<div id="container">
			<?php echo $content_for_layout; ?>
		</div>
	</div>
	<div id="footer">
		<a href="/.../collections">Collections</a> 
		<a href="/.../photosets">Photosets</a> 
		<a href="/.../photos">Photos</a>
	</div>	
</body>
</html>