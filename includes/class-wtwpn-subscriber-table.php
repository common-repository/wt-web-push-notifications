<?php
if (!class_exists('WTWPN_List_Table')) {
	require_once WTWPN_PLUGIN_DIR_PATH . 'includes/class-wtwpn-list-table.php';
}

class WTWPN_Subscriber_Table extends WTWPN_List_Table
{
	private $_args;
	
	function __construct()
	{
		global $page;

		global $wpdb;
		$this->table = $wpdb->prefix . "wtwpn_subscribers";

		//Set parent defaults
 		$args = array(
			'plural'   => 'subscribers',
			'singular' => 'subscriber',
			'ajax'     => false,
		);
		
		$this->_args = $args;
	}

	function wtwpn_column_default($item, $column_name)
	{
		return $item[ $column_name ];
	}

	function wtwpn_column_reminder_email($item)
	{
		return '<a href="mailto:' . $item['reminder_email'] . '">' . $item['reminder_email'] . '</a>';
	}
	
	function wtwpn_column_icon($item)
	{
		return '<img src="' . $item[ 'icon' ] . '" class="wtwpn_icon">';
	}
	function wtwpn_column_status($item)
	{
		if($item['status']==1)
		{
			return '<span class="btn btn-success">'. $item['status'] . '</span>';
		}
			else
			{
			return '<span class="btn btn-danger">'. $item['status'] .'</span>';
		}
	}

	function wtwpn_column_post_id($item)
	{
		$post = get_post($item['post_id']);

		if (is_a($post, 'WP_Post'))
			return '<a href="' . get_edit_post_link($post->ID) . '#Wt_Web_Push_Subscriber">' . $post->post_title . '</a>';

		return 'Post Not Found';
	}
	function wtwpn_column_btn($item)
	{
		
		$item['btn']= "Notify";
		if($item['btn'])
		{
			$url = admin_url('admin.php?page=wtwpn-web-push-notifications-send');
			
			add_thickbox();
			
			return '<a href="' . $url .'TB_iframe=true&width=570&height=420" title="Push Notification" class=" btn btn-primary thickbox">'.$item['btn'].'</a>';
		}
		
	}
	function wtwpn_column_user_id($item)
	{
		if($item['user_id'])
		{
	
	$user = get_user_by( 'id', $item['user_id'] );
echo '' . $user->first_name . ' ' . $user->last_name;
			
		}
	}
	function wtwpn_column_cb($item)
	{
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/
			$this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
			/*$2%s*/
			$item['id']                //The value of the checkbox should be the record's id
		);
	}

	function wtwpn_get_bulk_actions()
	{
		$permission_options = get_option('WTWPN_permissions_settings');
		$bulk_action_permission = (isset($permission_options['minimum_role_change'])) ? $permission_options['minimum_role_change'] : 'activate_plugins';
		if (!current_user_can($bulk_action_permission))
			return array();

		$actions = array(
			'delete'        => 'Delete'
		);
		return $actions;
	}

	function wtwpn_Prepare_Items()
	{
		global $wpdb; //This is used only if making any database queries
		$per_page = 50;

		$columns = $this->wtwpn_get_columns();
		$hidden = array();
		$sortable = $this->wtwpn_get_sortable_columns();

		$this->_column_headers = array($columns, $hidden, $sortable);

		$this->wtwpn_process_bulk_action();

		$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'id'; //If no sort, default to title
		$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
		$query = "SELECT * FROM $this->table ORDER BY $orderby $order";
		$data = $wpdb->get_results($query, ARRAY_A);

		$current_page = $this->wtwpn_get_pagenum();

		$total_items = count($data);

		$data = array_slice($data, (($current_page - 1) * $per_page), $per_page);

		$this->items = $data;

		$this->wtwpn_set_pagination_args(array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
			'total_pages' => ceil($total_items / $per_page)   //WE have to calculate the total number of pages
		));
	}

	function wtwpn_get_columns()
	{
		$columns = array(
			'cb'       => '<input type="checkbox" />', //Render a checkbox instead of text
			 'id'     => 'ID',
			'key'  => 'Key',
			'user_id'  => 'User',
			'userrole'  => 'User Role',
			'browser'     => 'Browser',
			//~ 'type'     => 'type',
			//~ 'created by'     => 'created by',
			'createdon'     => 'Created On'
			
			
		);
		return $columns;
	}

	function wtwpn_get_sortable_columns()
	{
		$sortable_columns = array(
			'id' => array('id', true),     //true means it's already sorted
		);
		return $sortable_columns;
	}

	function wtwpn_process_bulk_action()
	{
		global $wpdb;
		//Detect when a bulk action is being triggered...
		if ('delete' === $this->wtwpn_current_action()) {
			$id_string = join(',', $_GET['subscriber']);
			$query = "DELETE FROM $this->table WHERE id IN ($id_string)";
			$wpdb->query($query);
		}

	}

}
