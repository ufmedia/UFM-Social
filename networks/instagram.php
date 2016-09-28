<?php
/**************************** INSTAGRAM.PHP *******************************/ 
/** This file constructs the instagram request and returns the cached results */ 
/** Author: John Thompson **/ 

//Facebook Feed
function get_instagram_feed($user, $count) { 

	$i=0;
	$posts = array();
	$cache = social_load_cache('instagram');
	if ($cache!=null) {
		return $cache;
	} else {
//	$access_token="263133953.1677ed0.31cc927d0e6d4dc3a8e0d02cdfe5acf8"; //not needed
	$json_link="https://www.instagram.com/{$user}/media/?count={$count}";
//	$json_link.="access_token={$access_token}&count={$count}";
	$result = file_get_contents($json_link);
	$obj = json_decode($result, true); //json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
	
	social_write_cache('instagram', $string);
	
	return $obj['items'];
	}
} 
   
	