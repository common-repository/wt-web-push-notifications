<?php
/**
 * Class JoompushHelpersJpush
 *
 * @since  1.0
 */
class WTWPN_Helper
{
	
    /**
     * send push message to subscriber
     *
     * @return  string
     */
    public static function wtwpn_push($pushMsg)
    {
		if ($pushMsg)
		{
			$registrationIds = array($pushMsg->key); 
			
			$icon = ''; 
			
			if ($pushMsg->icon)
			{
				$icon = $pushMsg->icon;
			}
			else
			{
				$icon = get_option( 'WTWPN_Setting_icon' );
			}
			
			$url = '';
			
			if ($pushMsg->url)
			{
				$url = $pushMsg->url;
			}
			else
			{
				$url = get_option( 'WTWPN_Setting_url' );
			}
			
			
			$dry_run = false;
			
			if (isset($pushMsg->dry_run))
			{
				$dry_run = true;
			}
			
			// prep the bundle
			$msg = array
			(
				'body' 			=> $pushMsg->message,
				'title'			=> $pushMsg->title,
				'icon'			=> $icon,
				'click_action'	=> $url,
				'vibrate'		=> 1,
				'sound'			=> 1
			);
			
			$fields = array
			(
				'registration_ids' 	=> $registrationIds,
				'notification'		=> $msg,
				'dry_run' 			=> $dry_run
			);
			
			return self::wtwpn_sendToClient('https://fcm.googleapis.com/fcm/send', $fields);
		} 
	}
    	
	/**
     * Send notification
     *
     * @return  string
     */
	public static function wtwpn_sendToClient($url, $fields)
	{
		
		$api_key = get_option( 'WTWPN_Setting_server' );
		
		$headers = array
		(
			'Authorization: key=' . $api_key,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, $url );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		
		return $result;
	}
	
	/**
     * send push message to topic
     *
     * @return  string
     */
    public static function wtwpn_topicPush($pushMsg)
    {
		if ($pushMsg)
		{
			$icon = ''; 
			
			if ($pushMsg->icon)
			{
				$icon = $pushMsg->icon;
			}
			else
			{
				$icon = get_option( 'WTWPN_Setting_icon' );
			}
			
			$url = '';
			
			if ($pushMsg->url)
			{
				$url = $pushMsg->url;
			}
			else
			{
				$url = get_option( 'WTWPN_Setting_url' );
			}
			
			// prep the bundle
			$msg = array
			(
				'body' 			=> $pushMsg->message,
				'title'			=> $pushMsg->title,
				'icon'			=> $icon,
				'click_action'	=> $url,
				'vibrate'		=> 1,
				'sound'			=> 1
			);
			
			$fields = array
			(
				 'to' => '/topics/' . $pushMsg->gid,
				'notification'		=> $msg
			);
			
			return self::wtwpn_sendToClient('https://fcm.googleapis.com/fcm/send', $fields);
		}
	}
	
	 /**
     * add suscriber to topic
     *
     * @return  string
     */
    public static function wtwpn_addTopicSubscription($topic_id, $recipients_tokens)
    {
		if ($topic_id)
		{
			$fields =  array(
                    'to' => '/topics/' . $topic_id,
                    'registration_tokens' => $recipients_tokens
			);
			
			return self::wtwpn_sendToClient('https://iid.googleapis.com/iid/v1:batchAdd', $fields);
		}
	}
	
	 /**
     * Remove suscriber to topic
     *
     * @return  string
     */
    public static function wtwpn_removeTopicSubscription($topic_id, $recipients_tokens)
    {

		if ($topic_id)
		{
			$fields =  array(
                    'to' => '/topics/' . $topic_id,
                    'registration_tokens' => $recipients_tokens
			);
			
			return self::wtwpn_sendToClient('https://iid.googleapis.com/iid/v1:batchRemove', $fields);
		}
	}
}
