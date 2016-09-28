<?php
/**************************** TWITTER.PHP *******************************/ 
/** This file constructs the twitter request and returns the cached results */ 
/** Author: John Thompson **/ 

//Twitter Feed
function get_twitter_feed($user, $count) {

	$cache = social_load_cache('twitter');
	$posts = array();
	
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

	foreach ($string as $items) {	
		$datetime = new DateTime($items['created_at']);
		$datetime->setTimezone(new DateTimeZone('Europe/Zurich'));
		$datetime2 = new DateTime();
		$interval = $datetime->diff($datetime2);
		$ago = 'just now';
		if ($interval->days == 0){
			$ago = 	$interval->h . ' hours ago';
		} else {
			$ago = 	$interval->days . ' days ago';	
		}
		if (isset($items['entities']['media']['0']['media_url'])) {
			$items['mediaSrc'] = $items['entities']['media']['0']['media_url'];	
		} else {
			$items['mediaSrc']=null;	
		}
		$tweet = $items['text'];
		$tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);
		$tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_blank\" href=\"https://twitter.com/hashtag/$1/?src=hash\">#$1</a>", $tweet);
		$tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);
		
		$items['tweet'] = $tweet;
		
		array_push($posts, $items);
		
		}
		
		social_write_cache('twitter', $posts);
		return $posts;
	}
	
}
