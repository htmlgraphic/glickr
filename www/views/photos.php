<?php
if(empty($photos['photos']['photo'])) {
	echo 'There are no photos.';
}
else {
?>
	<div id="thumbnails">
<?php
	foreach($photos['photos']['photo'] as $photo) {
		echo '<a href="/.../single_photo/'.$photo['id'].'">';
		echo '<img src="http://farm'.$photo['farm'].'.static.flickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_s.jpg" alt="'.htmlspecialchars($photo['title']).'" title="'.htmlspecialchars($photo['title']).'" />';
		echo '</a>';
	}
?>
</div>
<p id="navigation">
<?php
if($photos['photos']['page']>1) echo '<a href="/.../photos/'.($photos['photos']['page']-1).'">«</a> ';
else echo '<span class="arrow">«</span>';

echo $photos['photos']['page'].'/'.$photos['photos']['pages'];

if($photos['photos']['page']<$photos['photos']['pages'])  echo '<a href="/.../photos/'.($photos['photos']['page']+1).'">»</a>';
else echo '<span class="arrow">»</span>';
?>
</p>
<?php } ?>