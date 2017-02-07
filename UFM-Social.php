<?php
/*
Plugin Name: UFM Social
Description: A cached plugin which returns Facebook, Twitter and Instagram feeds as unformatted objects ready to use in your custom themes.
Version: 1.0.0
Author: John Thompson
Author URI: http://www.ufmedia.net
*/

include 'networks/twitter.php';
include 'networks/facebook.php';
include 'networks/instagram.php';

function social_load_cache($cacheName) {
	
			$cacheDirectory =  WP_PLUGIN_DIR. "/UFM-Social/cache/";
			$ageInSeconds = 7200;
			$time = time();
			$time = $time - $ageInSeconds;
			if(file_exists($cacheDirectory.$cacheName) && filemtime($cacheDirectory.$cacheName) > $time) {
				$myfile = fopen($cacheDirectory.$cacheName, "r");
				$contents =   json_decode(file_get_contents($cacheDirectory.$cacheName), true);
				return $contents;
			} else {
				return null;
			}
}

function social_write_cache($cacheName, $string) {
	
	$cacheDirectory =  WP_PLUGIN_DIR. "/UFM-Social/cache/";
	$myfile = fopen($cacheDirectory.$cacheName, "w");
	$contents = print_r($string, true);
	file_put_contents($cacheDirectory.$cacheName,  json_encode($string));
	
	return;
}