<?php wp_enqueue_media(); ?>

<div class="container">
<div class="row">
	<br>
	<div style="display:none;" class="alert alert-success" id="message1"></div>
	<div class="alert alert-info">
		<h4> <?php
		if (isset($_GET['id']) && $_GET['action'] == 'edit')
		{
			echo __('Edit Notifications Template', WTWPN_TEXT_DOMAIN);
			global $wpdb;
			$table = $wpdb->prefix . "wtwpn_notificationtemplate";
			$query= "SELECT * FROM " . $table . " WHERE id = " . $_GET['id']; 
			$result=$wpdb->get_row($query);
			
		}
		else
		{
			echo __('Add New Notifications Template', WTWPN_TEXT_DOMAIN);
		}
		?></h4>
		</div>
		<div class="panel panel-primary">
  <div class="panel-heading">Add New Notifications Template</div>
  <div class="panel-body">
	 <form action="javascript:void(0)" id="frmAddNT" >
	  <div class="form-group">
    <label class="control-label col-sm-2" for="title">Title:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" required id="title" name="title" placeholder="Enter Title" value="<?php echo(isset($result->id) && $result->title) ? $result->title : ''; ?>"><br>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="message">Message:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" required id="message" name="message" placeholder="Enter Message" value="<?php echo(isset($result->id) && $result->message) ? $result->message : ''; ?>"><br>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="url">Url:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" required id="url" name="url" placeholder="Enter Url" value="<?php echo(isset($result->id) && $result->url) ? $result->url : ''; ?>"><br>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="icon">Icon:</label>
    <div class="col-sm-10">
		 <input type="button" id="btn-upload" class="btn btn-info" value="Upload Image"><br>
		 <span id="show-image"><?php echo (isset($result->id) && $result->icon) ? '<img style="height:100px;" src="' . $result->icon . '">' : ''; ?></span>
      <input type="hidden" id="image_name" name="image_name" value="<?php echo(isset($result->id) && $result->icon) ? $result->icon : ''; ?>"/><br>

    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="status">Status:</label>
    <div class="col-sm-10">
   <label class="radio-inline"> <input type="radio" class="form-control" required id="status"  <?php echo (isset($result->id) && $result->status == 1) ? 'checked="checked"' : '';?> value ="1" name="active" placeholder="Enter Status">Active </label>
     <label class="radio-inline"> <input type="radio" class="form-control" required id="status"
       <?php echo (isset($result->id) && $result->status == 0) ? 'checked="checked"' : '';?> value ="0" name="active" placeholder="Enter Status" >Deactive 
     </label><br><br>

    </div>
  </div>
   <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
<button id="show" type="submit" class="button-primary" name="submit"><?php echo(isset($result->id)) ? 'Update': 'Submit'; ?></button>
<a href="<?php echo admin_url('admin.php?sid=&page=wtwpn-webpush-notification'); ?>" class="btn btn-warning">Cancel</a>

    </div>
  </div>
   <input type="hidden" name="tid" class="forid" value="<?php echo (isset($result->id)) ? $result->id : ''; ?>">
  <input type="hidden" name="foraction" class="foraction" value="<?php echo (isset($result->id)) ? $_GET['action'] : ''; ?>">

</form>
  

