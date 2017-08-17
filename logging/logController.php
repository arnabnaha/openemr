<?php
// Insert the path where you unpacked log4php
include('C:/xampp/htdocs/openemr/log4php/Logger.php');
//require_once(dirname(__FILE__) . '/../interface/globals.php');

Logger::configure(dirname(__FILE__) . '/config.xml');

// Fetch a logger, it will inherit settings from the root logger
?>