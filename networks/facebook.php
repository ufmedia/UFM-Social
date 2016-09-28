<?php
/**************************** FACEBOOK.PHP *******************************/ 
/** This file constructs the facebook request and returns the cached results */ 
/** Author: John Thompson **/ 

//Facebook Feed
function get_facebook_feed($user, $count) { 

	$i=0;
	$posts = array();
	$cache = social_load_cache('facebook');
	if ($cache!=null) {
		return $cache;
	} else {
	$appID = '557678334394680';
	$appSecret = '8c6a6fdd71c8d1f14ba04e84f3fd2a25';
	$accessToken = $appID . '|' . $appSecret; 
	$url = "https://graph.facebook.com/$user/feed?access_token=$accessToken";
	$result = file_get_contents($url);
	$decodedFeed = json_decode($result, true);

	//Get the user Profile
	$pofile = "https://graph.facebook.com/$user?access_token=$accessToken";
	$profileResult = file_get_contents($pofile);
	$profileDecoded = json_decode($profileResult, true);

	
	//Get the profile picture
	$pofilePicture = "https://graph.facebook.com/$user/picture?height=100&redirect=false";
	$profilePictureResult = file_get_contents($pofilePicture);
	$profilePictureDecoded = json_decode($profilePictureResult, true);

		
	foreach ($decodedFeed['data'] as $post) {
		
		$id = $post['id'];
		
		//Simplify the content
		if (empty($post['story']) === false) {
            $post['content'] = $post['story'];
			$post['content'] .= ' ';
        } 
		if (empty($description) === false) {
			$post['content'] .= $description;
			$post['content'] .= ' ';
		}
		if (empty($link) === false) {
            $post['content'] .= $link;
			$post['content'] .= ' ';
		}
		if (empty($post['message']) === false) {
            $post['content'] .= $post['message'];
		}
		
		//Attachments
		$attachments = "https://graph.facebook.com/$id/attachments?access_token=$accessToken";
		$attachmentResult = file_get_contents($attachments);
		$attachmentDecoded = json_decode($attachmentResult, true);
		$post['attachment'] = $attachmentDecoded['data'][0]['media']['image']['src'];		
		
		//Likes
		$likes = "https://graph.facebook.com/$id/likes?access_token=$accessToken";
		$likesResult = file_get_contents($likes);
		$post['likes'] = count(json_decode($likesResult, true))-1;
		
		//Profile
		$post['profile'] = $profileDecoded;
		
		//Profile link
		$post['profile_link'] = $profileLink;
		
		//Profile Picture (Attached to each item - makes it easier)
		$post['profile_picture'] = $profilePictureDecoded['data']['url'];
		
		//Created time
		$date_source = strtotime($post['created_time']);
		$timestamp = date('F j', $date_source);
		$timestamp .= ' at ';
		$timestamp .= date('g:ia', $date_source);
		$post['timestamp'] = $timestamp;
		
		array_push($posts, $post);
		$i++;
		if ($i==$count) {
			break;
		}
		
	}
		social_write_cache('facebook', $posts);
		return $posts;
	}
}
   
	