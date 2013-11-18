<h1><?php echo $photoset['title']['_content']; ?></h1>
<div id="thumbnails">
<?php
foreach($photoset['photoset_photos']['photoset']['photo'] as $photo) {
	echo '<a href="/.../photo/'.$photoset['id'].'/'.$photo['id'].'"> ';
	echo '<img src="http://farm'.$photo['farm'].'.static.flickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_s.jpg" alt="'.htmlspecialchars($photo['title']).'" title="'.htmlspecialchars($photo['title']).'" /> ';
	echo '</a>';
}
?>
</div>