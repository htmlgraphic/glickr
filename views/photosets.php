<?php
if(isset($collection['title'])) {
	echo '<h1>'.$collection['title'].'</h1>';
}

if(empty($photosets) && empty($subcollections)) {
	echo 'This collection is empty.';
}
else {
	
	// show subcollections
	if(isset($subcollections)) {
		foreach($subcollections as $collection) {
	 	   echo '<div class="collection">';
	 	   echo '<a href="/.../collection/'.$collection['id'].'">';
	 	   echo '<img src="'.$collection['iconsmall'].'" alt="'.htmlspecialchars($collection['title']).'" />';
	 	   echo '</a><p>';
	 	   echo '<a href="/.../collection/'.$collection['id'].'">';
	 	   echo $collection['title'];
	 	   echo '</a> <br/>';
	 	   echo $collection['description'];
	 	   echo '</p>';
	 	   echo '<div class="clear"></div>';
	 	   echo '</div>';	
		}	
	}
	
	// show photosets
	foreach($photosets as $photoset) {
			echo '<div class="photoset">';
			echo '<a href="/.../photoset/'.$photoset['id'].'">';
			echo '<img src="http://farm'.$photoset['photoset_photos']['photoset']['photo'][0]['farm'].'.static.flickr.com/'.$photoset['photoset_photos']['photoset']['photo'][0]['server'].'/'.$photoset['photoset_photos']['photoset']['photo'][0]['id'].'_'.$photoset['photoset_photos']['photoset']['photo'][0]['secret'].'_s.jpg" alt="'.htmlspecialchars($photoset['title']['_content']).'" />';
			echo '</a><p>';
			echo '<a href="/.../photoset/'.$photoset['id'].'">'.htmlspecialchars($photoset['title']['_content']).'</a> ';
			echo '('.$photoset['photos'].')';
			echo '<br/>';
			echo htmlspecialchars($photoset['description']['_content']);
			echo '</p>';
			echo '<div class="clear"></div>';
			echo '</div>';
	}
}
?>