<h1>Install gallery</h1>
<?php if(!$writable) echo '<p class="error">ATENTION: data directory is not writable!</p>'; ?>
<form method="post" action="<?php echo $base; ?>/install">
<label for="gallery_name">Gallery name</label>
<input type="text" name="gallery_name" id="gallery_name" />
<label for="api_key">Api key</label>
<input type="text" name="api_key" id="api_key" />
<label for="user_id">User id</label>
<input type="text" name="user_id" id="user_id" />
<label for="cache">Update cache</label>
<select name="cache" id="cache">
	<option value="1">Every 7 days</option>
	<option value="2">Every 30 days</option>
	<option value="3">I will do it manually</option>	
</select>
<br/>
<input type="submit" />
</form>
<script type="text/javascript">
	$('gallery_name').focus();
</script>