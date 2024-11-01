<?php
if (!class_exists('WTWPN_Subscriber_Table')) {
	require_once WTWPN_PLUGIN_DIR_PATH  . '/includes/class-wtwpn-subscriber-table.php';
}


class WTWPN_Subscriber_list{
	
	public function __construct(){
		add_action('admin_notices', array($this, 'wtwpn_db_change_admin_notice'));
		add_action('in_admin_header', array($this, 'wtwpn_Subscriber_List_Page'));
		
		$this->wtwpn_Subscriber_List_Page();
	}
	
	function wtwpn_db_change_admin_notice()
	{
		$message = '';
		if (!isset($_GET['remind']) || !isset($_GET['action']))
			return;
		if ($_GET['action'] === 'delete')
			$message = count($_GET['remind']) . " record(s) deleted from database";
		?>
		<div class="updated">
			<p><?php echo $message; ?></p>
		</div>
		<?php
	}

	function wtwpn_Subscriber_List_Page()
	{
		$reportsTable = new WTWPN_Subscriber_Table();
		$reportsTable->wtwpn_Prepare_Items();
		?>
		<br>
		<br>

		<div class="wrap">
			<h1 class="wp-heading-inline"><?php _e('Subscribers', WTWPN_TEXT_DOMAIN); ?></h1>
			<?php add_thickbox();  ?>
    	<a href="<?php echo admin_url('admin.php?sid=&page=wtwpn-web-push-notifications-sendTB_iframe=true&width=570&height=400'); ?>" id="wtwpn_notify" class="page-title-action thickbox">Notify</a>
		 
		<hr class="wp-header-end">
			<form id="reports-filter" method="get">
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
				<?php $reportsTable->wtwpn_display() ?>
			</form>
		</div>
		<?php
	}

}
$Wt_Web_Push_Subscriber_list = new WTWPN_Subscriber_list;







