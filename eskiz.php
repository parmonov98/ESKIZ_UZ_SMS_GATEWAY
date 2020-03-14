<?php
/*
author: Murod Parmonov | @parmonov98
platform: ESKIZ.UZ SMS GATEWAY
licence: freeware
*/
class Eskiz{
    private $token = '';
    // automatically sets or gets token from ESKIZ or token json file(SMS_GATEWAY_TOKEN_FILE in config).
	function __construct($email = '', $secret = ''){
		
		if($email != '' ANd $secret != ''){
			$smsdata = [
				'email' => $email,
				'password' => $secret
			];

		}else{
			$smsdata = [
				'email' => SMS_GATEWAY_EMAIL,
				'password' => SMS_GATEWAY_SECRET
			];
		}
		if(SMS_GATEWAY_TOKEN == ''){
			$sms_gateway_data = json_decode($this->sendPing($smsdata, 'auth/login', 'oauth'), 1);
			if($sms_gateway_data['message'] == 'token_generated'){
				file_put_contents(SMS_GATEWAY_TOKEN_FILE, $sms_gateway_data['data']['token']);
			}
			$token = $sms_gateway_data['data']['token'];
		}else{
			
			$token = file_get_contents(SMS_GATEWAY_TOKEN_FILE, $sms_gateway_data['data']['token']);
			
			$data = $this->sendPing([], 'auth/user');
			// print_r($data);
			if($data['message'] != 'authenticated_user' and $data['data']['status'] !== 'active'){
				$data = $this->sendPing([], 'auth/refresh', 'PATCH');
				
			}
		}

		$this->token = $token;
	}
	// getToken - get token from Eskiz.uz GateWay
	function getToken(array $data) { 

		$output = $this->sendPing($data, 'auth/login', 'oauth');  // $type is oauth when token is needed
		// print_r($output);
		if($output['message'] === 'token_generated'){
			$output = $output['data']['token'];
		}
		return $output;
	}
	
	// sendSMS - send SMS to a number through Eskiz.uz GateWay
	function sendSMS(array $data) { 

		$output = $this->sendPing($data, 'message/sms/send');
		// print_r($output);
		$output['is_sent'] = 'no';
		if($output['status'] == 'waiting'){
			$output['is_sent'] = 'yes';			
		}
		
		return $output;
	}
	
	// getSMS - get requests from Eskiz.uz GateWay
	function getSMS(integer $sms_id ) { 

		$output = $this->sendPing([], 'message/sms/get/status/' . $sms_id);
		// print_r($output);
		if($output['status'] === 'success' AND $output['message']['status'] === 'Delivered'  ){
			$output = $output['message']['status'];
		}
		return $output;
	}
	
	// sendPing - sending/getting(SMS)/refresh_token/get_token using Eskiz.uz GateWay
	function sendPing($content, $method, $type = '') { // $type is oauth when token is needed

		
			$curl = curl_init(); 
			// set url 
			curl_setopt($curl, CURLOPT_URL, SMS_GATEWAY_URL.$method); 
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
            
            // when you need to update token
			if($type == 'PATCH'){
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
			}

			
			// by default oauth token is set up in every request's header
			if($type !== 'oauth'){
				$headers = array(
					sprintf('Authorization: Bearer %s', $this->token)
				);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			}
            
            // when you need to send a message
			if(!empty($content)){
				curl_setopt($curl, CURLOPT_POST, 1);
			
				curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			}
			
			// $output contains the output string 
			$output = curl_exec($curl); 

			curl_close($curl);      
		#file_put_contents("return_sent.txt", $output);
		return json_decode($output, true);
	}
}

?>