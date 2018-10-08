<?php
/**
 * Created by Visual Studio Code.
 * User: minhpv
 * Date: 25/06/18
 * Time: 16:48 PM
 */

namespace App\Services;

class PushNotificationService extends BaseService {

	public function sendFCMMessage($data, $notification, $target){
	   //FCM API end-point
	   $url = 'https://fcm.googleapis.com/fcm/send';
	   //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
	   $server_key = config('fcm.server_key');				
	   $fields = array();
	   $fields['data'] = $data;
	   $fields['notification'] = $notification;
	   if(is_array($target)){
		$fields['registration_ids'] = $target;
	   }else{
		$fields['to'] = $target;
	   }
	   //header with content_type api key
	   $headers = array(
		'Content-Type:application/json',
	        'Authorization:key='.$server_key
	   );
	   //CURL request to route notification to FCM connection server (provided by Google)			
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_POST, true);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	   $result = curl_exec($ch);
	   // echo $result;
	   if ($result === FALSE) {
		die('Oops! FCM Send Error: ' . curl_error($ch));
	   }
	   curl_close($ch);
	   return $result;

	}

	public static function sendMessage($data, $notification, $target){
	   //FCM API end-point
	   $url = 'https://fcm.googleapis.com/fcm/send';
	   //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
	   $server_key = config('fcm.server_key');				
	   $fields = array();
	   $fields['data'] = $data;
	   $fields['notification'] = $notification;
	   if(is_array($target)){
		$fields['registration_ids'] = $target;
	   }else{
		$fields['to'] = $target;
	   }
	   //header with content_type api key
	   $headers = array(
		'Content-Type:application/json',
	        'Authorization:key='.$server_key
	   );
	   //CURL request to route notification to FCM connection server (provided by Google)			
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_POST, true);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	   $result = curl_exec($ch);
	   // echo $result;
	   if ($result === FALSE) {
		die('Oops! FCM Send Error: ' . curl_error($ch));
	   }
	   curl_close($ch);
	   return $result;

	}

}