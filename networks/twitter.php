<?php
/**************************** TWITTER.PHP *******************************/ 
/** This file constructs the twitter request and returns the cached results */ 
/** Author: John Thompson **/ 

//Twitter Feed
function get_twitter_feed($user, $count) {

	$cache = load_cache('twitter');

	if ($cache!=null) {
		return $cache;
	} else {

		require_once(WP_PLUGIN_DIR.'/UFM-Social/APIs/Twitter-API.php');
		
		//Your Twitter API settings
		$settings = array(
		'oauth_access_token' => "219002257-YiZWJC5WOFJFvJLipJRF0oqGwIU5jCs2weLacg99",
		'oauth_access_token_secret' => "1yOfPyj5DkQsvm53HUmTjjDA1vJdPpcvnPbu6gVOaQEc2",
		'consumer_key' => "IDYgbLZF59ED5SowiPl3II0r9",
		'consumer_secret' => "6HfjJ8LwzS5SOtTfxiynaMHeGzQS6OaWz4OB4YMV5yN9iuHe4z"
		);
		$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
		$requestMethod = "GET";
		$getfield = "?screen_name=$user&count=$count";
		$twitter = new TwitterAPIExchange($settings);
		$string = json_decode($twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest(),$assoc = TRUE);

		write_cache('twitter', $string);
		return $string;
	}
	
}
