<style>#wpadminbar
{ 
display:none; 
} 
html.wp-toolbar
{
  padding-top: 0px;
}</style>
<center>
<h2>Choose Notification Templates</h2><br>
<?php

	global $wpdb;
	 $table_name = $wpdb->prefix.'wtwpn_notificationtemplate';
	$results = $wpdb->get_results( "SELECT * FROM $table_name");

	echo "<select name='to_user' id='wtwpn_notification_template_list' style='width: 220px;'>";
	foreach($results as $row){
		echo "<option value='". $row->id ."'>" .$row->title ."</option>" ;
	}
	echo "</select>" ;
?><br><br><br>
	<input type="hidden" id="wtwpn_sid" name="wtwpn_sid" value="<?php echo $_GET['sid'] ? $_GET['sid'] : ''; ?>" >
	<button id="wtwpn_sendmsg" type="submit" class="btn btn-success wtwpn_sendmsg" name="submit">Send</button><br><br>
	<div id="msg"></div>
</center>
