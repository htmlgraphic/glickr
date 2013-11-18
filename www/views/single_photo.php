<div id="photo">
<h1><?php echo htmlspecialchars($photo['title']); ?></h1>
<?php
	echo '<img src="http://farm'.$photo['farm'].'.static.flickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'.jpg" alt="'.htmlspecialchars($photo['title']).'" id="flickr-photo" />';
?>
<p id="flickr-link">Open in <a href="http://www.flickr.com/photos/<?php echo $user_id.'/'.$photo['id'] ?>">flickr.com</a></p>
<p id="navigation">
<?php
	if($prev) echo '<a href="/.../single_photo/'.$prev['id'].'">«</a>';
	else echo '<span class="arrow">«</span>';

	echo '<a href="/.../photos/'.$page.'"> Photos </a>';

	if($next) echo '<a href="/.../single_photo/'.$next['id'].'">»</a>';
	else echo '<span class="arrow">»</span>';
?>
</p>
</div>
