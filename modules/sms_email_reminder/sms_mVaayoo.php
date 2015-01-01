<?php
/** 
* modules/sms_email_reminder/sms_mVaayoo.php  
* 
* SMS Api fo rmVaayoo 
* 
* 
* Copyright (C) 2014 Terry Hill <terry@lillysystems.com> 
* 
* LICENSE: This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 3 
* of the License, or (at your option) any later version. 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details. 
* You should have received a copy of the GNU General Public License 
* along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;. 
* 
* @package OpenEMR 
* @author Terry Hill <terry@lillysystems.com>
* @link http://www.open-emr.org 
*/

$fake_register_globals=false;
$sanitize_all_escapes=true;


class sms
{
       // init vars
 	var $username = "";
 	var $password = "";
	
 	function sms( $strUser, $strPass )
 	{
 	  $this->username = $strUser;
          $this->password = $strPass;	
 	}
 	
 	/**
 	 * Send sms method
 	 * @access public
 	 * @return string response
 	 */
 	 
 	function send($phoneNo, $sender, $message)
 	{
 		/* Prepare the server request */

 		$request .= "user=".urlencode($this->username);
		$request .= ":".urlencode($this->password);
	   	if( !$sender ) {
         $request .= "&senderID=TEST SMS";
        }
	    else
 	    {
	     $request .= "&senderID=".urlencode($sender);
		}
 		$request .= "&receipientno=".urlencode($phoneNo); 
        $request .= "&msgtxt=".urlencode($message);
		 
 		$response = $this->_send($request);
 		//debug
 		//echo "DEBUG :SMS ENGINE: sms sent with code =".$response." for req= ".$request."\n"; 
 		
  		return $response;
 	}

 	/**
 	 * Send sms method
 	 * @access private
 	 * @return string response
 	 */
 	function _send($request)
 	{
 		/** 
 		* cURL extension is the only way with this vendor 
 		* call the method that sends the sms through cURL
 		*/	 
 			 
 		$response = $this->_send_curl($request);
 
 		 return $response;
 	}
 	
 	/**
 	 * Send SMS through cURL
 	 * @access private
 	 * @return string response
 	 */
 	
 	function _send_curl($request)
 	{
 		/* Initiate a cURL session */
 		$ch = curl_init();
 		
 		/* Set cURL variables */
 		curl_setopt($ch, CURLOPT_URL, "http://api.mVaayoo.com/mvaayooapi/MessageCompose"); 
 		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
 		curl_setopt($ch, CURLOPT_POST, 1); 
 		curl_setopt($ch, CURLOPT_POSTFIELDS, $request); 
 		
 		/* Send the request through cURL */ 
 		$response = curl_exec($ch); 

 		/* End the cURL session */
 		curl_close($ch);

 		/* Return the server response */
 		return $response;
 	}
 	
 	/**
 	 * Leaving this code in place in case 
	 * this needs to be an option in the future 
	 * Send SMS using the sockets extension
 	 * @access private
 	 * @return string response
 	 */
 	function _send_sock($request)
 	{
 		/* Prepare the HTTP headers */
 		$http_header = "POST /client/api/http.php HTTP/1.1\r\n";
 		$http_header .= "Host: \r\n";
 		$http_header .= "User-Agent: HTTP/1.1\r\n";
 		$http_header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
 		$http_header .= "Content-Length: ".strlen($request)."\r\n";
 		$http_header .= "Connection: close\r\n\r\n";
 		$http_header .= $request."\r\n";
 		
 		/* Set the host that we are connecting to and the port number */
 		//$host = ;
 		//$port = ;
 		
 		/* Connect to the TM4B server */
 		$out = @fsockopen($host, $port, $errno, $errstr);
 		
 		/* Make sure that the connection succeded */
 		if($out)
 		{
 			/* Send the request */
 			fputs($out, $http_header);
 			
 			/* Get the response */
 			while(!feof($out)) $result[] = fgets($out);
 			
 			/* Terminate the connection */
 			fclose($out);
 		}
 		/* Get the response from the returned string */
 		$response = $result[9];
 	}
 }

?>
