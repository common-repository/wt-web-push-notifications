jQuery(document).ready(function() {
	jQuery("#btn-upload").on("click",function(){
		var image= wp.media({
				 title: "Upload Icon for Notifications Template",
				 multiple: true
			 }).open().on("select",function(e){
				 var uploaded_Image=image.state().get("selection").first();
				 var getImage=uploaded_Image.toJSON().url;
				 jQuery("#show-image").html("<img src=' "+getImage+" ' style='height:50px;width:50px'>");
				 jQuery("#image_name").val(getImage);
			});
	});	
	
	jQuery("#wtwpn_notify").on("click",function(){
			var sid = [];
				
            jQuery("input:checkbox[name='subscriber[]']:checked").each(function(){
				sid.push(jQuery(this).val());
			});
			
				if(sid.length === 0) {
				alert("Please select atleast one subscriber");
				return false;
				}
				else
				{
					var old_href = jQuery("#wtwpn_notify").attr("href");
					var sidString = sid.toString();
					var result = old_href.split('sid=');
					var new_href = result[0] + 'sid=' + sidString + result[1];
					jQuery("#wtwpn_notify").attr("href", new_href);
				}
				
			});
		
	
	jQuery("#wtwpn_sendmsg").on("click",function(){
		jQuery('#wtwpn_sendmsg').attr("disabled", true);	
		var tid = jQuery("#wtwpn_notification_template_list").val();
		var sid = jQuery("#wtwpn_sid").val();
	
		jQuery.ajax({
			type: "post",
			url: ntajaxurl,
			data: {action : 'wtwpn_subscriber_send', sid: sid, tid : tid},
			success: function(result){
				if(result > 0){
					jQuery("#msg").append("<div id='wtwpn_msg' class='alert alert-success'>Your notification is been processed.. </div>");
				}
				else
				{
					 jQuery("#msg").append("<div id='wtwpn_msg' class='alert alert-danger'>Something went wrong please try again later</div>");
				}
				 setTimeout(function() {
						parent.tb_remove();
						parent.location.reload(1)
					}, 1500);
				
			}
		});
	});

	jQuery("#frmAddNT").validate({ 
		submitHandler: function(){
			jQuery('#show').attr("disabled", true);
			var postdata="action=myntlibrary&param=save_nt&" + jQuery("#frmAddNT").serialize();
			jQuery.post(ntajaxurl,postdata,function(response){
				var data=jQuery.parseJSON(response);
				if(data.status1==1 || data.status1==2){
					 jQuery("#message1").html(data.message);
					 jQuery("#message1").show();
					 setTimeout(function() {
						 window.location.replace(data.redirectURL);
					}, 2500);
				}
			});
		}
	});
});
	
	
   
    
