<?php
global $wpdb;
    $table_name = $wpdb->prefix . 'wtwpn_subscribers';
    $count_query = "select count(user_id) from $table_name";
    $count_querytoday = "SELECT COUNT(user_id) FROM $table_name WHERE DATE(createdon) = DATE(NOW())";
    $TotalSubscriber = $wpdb->get_var($count_query);
    $TodaySubscriber = $wpdb->get_var($count_querytoday);
    $table_notification = $wpdb->prefix . 'wtwpn_notifications';
    $count_query = "select count(id) from $table_notification";
    $count_querytoday = "SELECT COUNT(id) FROM $table_notification WHERE DATE(created_by) = DATE(NOW())";
    $TotalNotification = $wpdb->get_var($count_query);
    $TodayNotification = $wpdb->get_var($count_querytoday);
?>
	<div class="row">
	<h2 class="wtwpn_page_title"> Dashboard</h2>
	</div>
	<div class="row">
	<div class="col-md-9">
	<div class="row">
	<h3 class="subs"> Subscribers</h3>
	<br>
	</div>
	<div class="row">
		<div class="col-md-2">
				&nbsp;
		</div>
		<div class="col-md-3 subscribed">
			<i class="fa fa-user  fa-lg" aria-hidden="true"></i>
			<div class="tile-count"><?php echo $TodaySubscriber; ?></div>
			<div class="title">Subscribed Today</div>	
		</div>
		<div class="col-md-2">
				&nbsp;
		</div>
		<div class="col-md-3 subscribedtotal">
			<i class="fa fa-user" aria-hidden="true"></i>
			<div class="tile-count"><?php echo $TotalSubscriber;?></div>
			<div class="title">Total Subscribed</div>
		</div>
		<div class="col-md-2">
				&nbsp;
		</div>
			</div>
		<div class="row">&nbsp;</div>
		<div class="row">
		<hr>
		<h3 class="noti"> Notifications</h3>
		</div>
		<br>
	<div class="row">
		<div class="col-md-2">
				&nbsp;
		</div>
		<div class="col-md-3 Notification">
				<i class="fa fa-paper-plane" aria-hidden="true"></i>
				<div class="tile-count"><?php echo $TodayNotification;?></div>
				<div class="title">Sent Today</div>	
		</div>
			<div class="col-md-2">
				&nbsp;
			</div>
			<div class="col-md-3 Notificationtotal">
					<i class="fa fa-calendar" aria-hidden="true"></i>
					<div class="tile-count"><?php echo $TotalNotification;?></div>
					<div class="title">Total Sent</div>
			</div>
			<div class="col-md-2">
				&nbsp;
			</div>
			</div>
		</div>
		<div class="col-md-3 Stay">
			<h3> Stay Updated</h3><br>
			<a href="https://www.facebook.com/wtplugins" target="_blank" title="Like us on Facebook">Like us on Facebook   &nbsp;&nbsp;&nbsp;<i class="fa fa-facebook-square social2"></i></a><br>
			<a href="https://twitter.com/wtplugins" target="_blank" title="Follow us on Twitter">Follow us on Twitter    &nbsp;&nbsp;&nbsp;<i class="fa fa-twitter-square social3"></i></a><br>
			<a href="https://wtplugins.com/documentation/wt-web-push-notifications" target="_blank" title="Documentation">Documentation      &nbsp;&nbsp;&nbsp;<i class="fa fa-file-text social4"></i></a><br>
			<a href="https://wtplugins.com/support" target="_blank" title="Support">Support Request<i class="fa fa-life-ring social5"></i></a><br>
		</div>
	</div>
