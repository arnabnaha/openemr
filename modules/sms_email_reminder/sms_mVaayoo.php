<?php

if (true){
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}

// Copyright (C) 2011 Suryaprakash Kompalli <neosurya@gmail.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
require_once('../../logging/logController.php');

class sms{
	var $baseURL = "";
	var $api_username = "superarnab@gmail.com";
	var $api_password = "summerof69**";
	var $sender_id = "TEST%20SMS";
	var $use_ssl = false;
	var $use_unicode = false;
	var $curl_use_proxy = false; 
	var $balace_limit = 5;
	
	/**
	 * Gateway command sending method (curl,fopen)
	 * @var mixed
	 */
	var $sending_method = "curl";
	
	public static $log = null; 
	public static function init_log(){
		if (sms::$log == NULL)
			sms::$log = Logger::getLogger('sms');
	}

	/**
	 * Class constructor
	 * Create SMS object and authenticate SMS gateway
	 * @return object New SMS object.
	 * @access public
	 */
	function sms() {
		sms::init_log();
		
		if ($this->use_ssl) {
			$this->baseURL   = "https://api.mvaayoo.com/mvaayooapi/";
		} else {
			$this->baseURL   = "http://api.mvaayoo.com/mvaayooapi/";
		}
	}
	/**
	 * Query SMS credis balance
	 * @return integer  number of SMS credits
	 * @access public
	 */
	function getbalance() {
		//http://api.mvaayoo.com/mvaayooapi/APIUtil?user=neosurya@gmail.com:mvay))passw0&type=0
		$comm = sprintf ("%s/APIUtil?user=%s:%s&type=0", $this->baseURL, $this->api_username, $this->api_password);
		 sms::$log->info($comm);
		 $commResult = $this->_execgw($comm);
		 sms::$log->info("commResult =" . $commResult);
		 return $this->_parse_getbalance ($commResult);
	}
	
	
	/**
	 * Send SMS message
	 * @param to mixed  The destination address.
	 * @param from mixed  The source/sender address
	 * @param text mixed  The text content of the message
	 * @return mixed  "OK" or script die
	 * @access public
	 */
	function send($to=null, $from=null, $text=null) {
	
		/* Check SMS credits balance */
		if ($this->getbalance() < $this->balace_limit) {
			die ("You have reach the SMS credit limit!");
		};
	
		/* Check SMS $text length */
		if ($this->use_unicode == true) {
			$this->_chk_mbstring();
			if (mb_strlen ($text) > 210) {
				die ("Your unicode message is too long! (Current lenght=".mb_strlen ($text).")");
			}
			/* Does message need to be concatenate */
			if (mb_strlen ($text) > 70) {
				$concat = "&concat=3";
			} else {
				$concat = "";
			}
		} else {
			if (strlen ($text) > 459) {
				die ("Your message is too long! (Current lenght=".strlen ($text).")");
			}
			/* Does message need to be concatenate */
			if (strlen ($text) > 160) {
				$concat = "&concat=3";
			} else {
				$concat = "";
			}
		}
	
		/* Check $to and $from is not empty */
		if (empty ($to)) {
			die ("You not specify destination address (TO)!");
		}
		if (empty ($from)) {
			die ("You not specify source address (FROM)!");
		}
	
		/* Reformat $to number */
		$cleanup_chr = array ("+", " ", "(", ")", "\r", "\n", "\r\n");
		$to = str_replace($cleanup_chr, "", $to);
	
		//UNicode message: http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=neosurya@gmail.com:mvay))passw0&senderID=TEST SMS&receipientno=9989780170&msgtype=4&dcs=8&ishex=1&msgtxt=092e094d0935093e092f094b0942&state=4
		// English only message: http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=neosurya@gmail.com:mvay))passw0&senderID=TEST SMS&receipientno=9989780170&dcs=0&msgtxt=This is Test message&state=4 
		/* Send SMS now */
		// Unicode: $comm = sprintf ("%s/MessageCompose?user=%s:%s&senderID=%s&receipientno=%s&msgtype=4&dcs=8&ishex=1&msgtxt=%s&state=4",
		// English: 
		$comm = sprintf ("%s/MessageCompose?user=%s:%s&senderID=%s&receipientno=%s&dcs=0&msgtxt=%s&state=4",
				$this->baseURL,
				$this->api_username,
				$this->api_password,
				$this->sender_id,
				rawurlencode($to),
				$this->encode_message($text)
		);
		sms::$log->info("Send SMS command:" . $comm);
		$commResult = $this->_execgw($comm);
		sms::$log->info("commResult =" . $commResult);
		return $this->_parse_send ($commResult);
	}
	
	/**
	 * Encode message text according to required standard
	 * @param text mixed  Input text of message.
	 * @return mixed  Return encoded text of message.
	 * @access public
	 */
	function encode_message ($text) {
		if ($this->use_unicode != true) {
			//standard encoding
			return rawurlencode($text);
		} else {
			//unicode encoding
			$uni_text_len = mb_strlen ($text, "UTF-8");
			$out_text = "";
	
			//encode each character in text
			for ($i=0; $i<$uni_text_len; $i++) {
				$out_text .= $this->uniord(mb_substr ($text, $i, 1, "UTF-8"));
			}
	
			return $out_text;
		}
	}
	
	/**
	 * Unicode function replacement for ord()
	 * @param c mixed  Unicode character.
	 * @return mixed  Return HEX value (with leading zero) of unicode character.
	 * @access public
	 */
	function uniord($c) {
		$ud = 0;
		if (ord($c{0})>=0 && ord($c{0})<=127)
			$ud = ord($c{0});
		if (ord($c{0})>=192 && ord($c{0})<=223)
			$ud = (ord($c{0})-192)*64 + (ord($c{1})-128);
		if (ord($c{0})>=224 && ord($c{0})<=239)
			$ud = (ord($c{0})-224)*4096 + (ord($c{1})-128)*64 + (ord($c{2})-128);
		if (ord($c{0})>=240 && ord($c{0})<=247)
			$ud = (ord($c{0})-240)*262144 + (ord($c{1})-128)*4096 + (ord($c{2})-128)*64 + (ord($c{3})-128);
		if (ord($c{0})>=248 && ord($c{0})<=251)
			$ud = (ord($c{0})-248)*16777216 + (ord($c{1})-128)*262144 + (ord($c{2})-128)*4096 + (ord($c{3})-128)*64 + (ord($c{4})-128);
		if (ord($c{0})>=252 && ord($c{0})<=253)
			$ud = (ord($c{0})-252)*1073741824 + (ord($c{1})-128)*16777216 + (ord($c{2})-128)*262144 + (ord($c{3})-128)*4096 + (ord($c{4})-128)*64 + (ord($c{5})-128);
		if (ord($c{0})>=254 && ord($c{0})<=255) //error
			$ud = false;
		return sprintf("%04x", $ud);
	}
	
	/**
	 * Spend voucher with sms credits
	 * @param token mixed  The 16 character voucher number.
	 * @return mixed  Status code
	 * @access public
	 */
	function token_pay ($token) {
		$comm = sprintf ("%s/http/token_pay?session_id=%s&token=%s",
				$this->base,
				$this->session,
				$token);
	
		return $this->_execgw($comm);
	}
	
	/**
	 * Execute gateway commands
	 * @access private
	 */
	function _execgw($command) {
		if ($this->sending_method == "curl")
			return $this->_curl($command);
		if ($this->sending_method == "fopen")
			return $this->_fopen($command);
		die ("Unsupported sending method!");
	}
	
	/**
	 * CURL sending method
	 * @access private
	 */
	function _curl($command) {
		$this->_chk_curl();
		$ch = curl_init ($command);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER,0);
		if ($this->curl_use_proxy) {
			curl_setopt ($ch, CURLOPT_PROXY, $this->curl_proxy);
			curl_setopt ($ch, CURLOPT_PROXYUSERPWD, $this->curl_proxyuserpwd);
		}
		$result=curl_exec ($ch);
		curl_close ($ch);
		return $result;
	}
	
	/**
	 * fopen sending method
	 * @access private
	 */
	function _fopen($command) {
		$result = '';
		$handler = @fopen ($command, 'r');
		if ($handler) {
			while ($line = @fgets($handler,1024)) {
				$result .= $line;
			}
			fclose ($handler);
			return $result;
		} else {
			die ("Error while executing fopen sending method!<br>Please check does PHP have OpenSSL support and check does PHP version is greater than 4.3.0.");
		}
	}
	
	/**
	 * Parse send command response text
	 * @access private
	 */
	function _parse_send ($result) {
		$code = strpos($result, "Status=0");
	
		if ($code === false) {
			die ("Error sending SMS! ($result)");
		} else {
			$code = "OK";
		}
		return $code;
	}
	
	/**
	 * Parse getbalance command response text
	 * @access private
	 */
	function _parse_getbalance ($result) {
		// Return message should be of the type: Status=0,Credit balance is
		$currPos = strpos($result, "Credit balance is");
		sms::$log->info("currPos = " . $currPos);
		if ($currPos === false){
			return 0;
		}
		$currPos = $currPos + 17;
		$result = substr($result, $currPos);
		return (int)$result;
	}
	
	/**
	 * Check for CURL PHP module
	 * @access private
	 */
	function _chk_curl() {
		if (!extension_loaded('curl')) {
			die ("This SMS API class can not work without CURL PHP module! Try using fopen sending method.");
		}
	}
	
	/**
	 * Check for Multibyte String Functions PHP module - mbstring
	 * @access private
	 */
	function _chk_mbstring() {
		if (!extension_loaded('mbstring')) {
			die ("Error. This SMS API class is setup to use Multibyte String Functions module - mbstring, but module not found. Please try to set unicode=false in class or install mbstring module into PHP.");
		}
	}	
	
	public static function unit_test(){
		$smsSender = new sms();
		sms::unit_test_balance_check($smsSender);
		sms::unit_test_send_sms($smsSender);
	}
	
	static function unit_test_send_sms($smsSender){
		$smsSender->send("9903055401", "Myself", "This is a test SMS message from healthnet vaayoo");
	}
	static function unit_test_balance_check($smsSender){
		$balanceString = "Status=0";
		$balance = $smsSender->_parse_getbalance($balanceString);
		$message = "sms:: balance:" . $balance . ", message=" . $balanceString;
		echo $message . "<br>";
		sms::$log->info($message);	

		$balance = $smsSender->getbalance();
		$message = "sms:: Current balance:" . $balance;
		echo $message . "<br>";
		sms::$log->info($message);	
	}
}
?>
