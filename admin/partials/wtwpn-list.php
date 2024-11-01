<?php

if (!class_exists('WTWPN_Table')) {
	require_once WTWPN_PLUGIN_DIR_PATH . '/includes/class-wtwpn-table.php';
}

class WTWPN_list{
	
	public function __construct(){
		add_action('admin_notices', array($this, 'wtwpn_db_change_admin_notice'));
		
		$this->wtwpn_List_Page();
	}
	
	function wtwpn_db_change_admin_notice()
	{
		$message = '';
		if (!isset($_GET['notification']) || !isset($_GET['action']))
			return;
		if ($_GET['action'] === 'delete')
			$message = count($_GET['notification']) . " record(s) deleted from database";
		?>
		<div class="updated">
			<p><?php echo $message; ?></p>
		</div>
		<?php
	}

	function wtwpn_List_Page()
	{
		$reportsTable = new WTWPN_Table();
		$reportsTable->wtwpn_Prepare_Items();
		?>
		<br>
		<br>
		<?php 
//$pageslug = 'wtwpn-webpush-add-new-notification';
?>
    <form method="post" action="?page=<?php echo $pageslug; ?>&tab=2">
<!--
        <input type="submit" class="button-primary" value="Add New" />       
-->
    </form>
		
		<div class="wrap">
			<div id="icon-users" class="icon32"><br/></div>
			<h2><?php _e('Notifications Templates', WTWPN_TEXT_DOMAIN); ?>	<a href="<?php echo admin_url('admin.php?page=wtwpn-webpush-add-new-notification'); ?>" id="wtwpn_addnew" class="page-title-action addnew">Add New</a></h2>

			<form id="reports-filter" method="get">
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
				<?php $reportsTable->wtwpn_display() ?>
			</form>
		</div>
		<?php
	}

}
$Wt_Web_Push_Notifications_list = new WTWPN_list;
