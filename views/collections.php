<?php
if(empty($collections)) {
	echo 'There are not collections.';
}
else {
	foreach($collections as $collection) {
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
?>