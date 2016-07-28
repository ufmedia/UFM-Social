<?php
/*
Plugin Name: UFM Social
Description: A simple, fancy free twitter, instagram & Pinterest feed.
Version: 0.1
Author: John Thompson
Author URI: http://www.ufmedia.net
*/

/* ----------------------------------------------------------------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------------------------------------------- */


//Twitter Feed
function get_twitter_feed($user, $count) {

require_once(WP_PLUGIN_DIR.'/UFM-Social/APIs/Twitter-API.php');
/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
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
//if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}
$tweets = array();
$i=0;
$mediaSrc=null;
	
	
	return $string;
	
	
}


//Pinterest Feed
function get_pinterest_feed($user, $count) {
	require_once(WP_PLUGIN_DIR.'/UFM-Social/APIs/Pinterest-API.php');
    
    // Create new instance of the Pinterest API
    $pinterest = new Pinterest($user);
	$pinterest->itemsperpage = $count; // Default: 25
    
    // Check if a page has to be set
    if(isset($_GET['page'])){
        $pinterest->currentpage = (int) $_GET['page'];
    }
    
    
    // Return the data
   $pinsresult = $pinterest->getPins();
   $pinpics = array();
    foreach( $pinsresult["data"] as $pin ){
        $bigimage = str_replace("237x", "736x", $pin->images->{'237x'}->url);
		
		array_push($pinpics, $pin->images->{'237x'}->url);
		//echo $pin->images->{'237x'}->url;
    }
   
		//print_r($arrEvents); 

}


//Instagram Feed
function get_instagram_feed($user, $count) {

function fetchData($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $result = curl_exec($ch);
    curl_close($ch); 
    return $result;
  }
  $result = fetchData("https://api.instagram.com/v1/users/1097978890/media/recent/?access_token=1097978890.ab103e5.c53109302e0f4f939ea25b8793ca64f4&count=3");
  $result = json_decode($result);
  $instagrams = array();
  //return $result->data;	
  foreach ($result->data as $post) {
   ob_start();
   ?>
   <div class="grid-item col-xs-6 col-md-4 col-lg-3">
      <div class="social-img">
        <a href="<?php echo $post->link; ?>"><img src="<?php echo $post->images->standard_resolution->url; ?>" class="img-responsive" width="338" height="351" alt=""/></a>									
      
	  </div>
	  <div class="tweet-text">
	  <p><?php 
	  
	$text = $post->caption->text;  
	$text = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $text);
	$text = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_blank\" href=\"https://www.instagram.com/explore/tags/$1/\">#$1</a>", $text);
	$text = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.instagram.com/$1\">@$1</a>", $text);
	  
	echo  $text; 
	
	?><br/><a href="https://www.instagram.com/dukes_sidmouth/">Dukes Inn on Instagram</a></p>
    </div>
	</div>
	<?php
	array_push($instagrams, ob_get_clean());
	
  }

   return $instagrams;
  
}


//Facebook Feed
function get_facebook_feed($user, $count) { 

require_once(WP_PLUGIN_DIR.'/UFM-Social/APIs/Facebook-API.php');

    // connect to app
    $config = array();
    $config['appId'] = '557678334394680';
    $config['secret'] = '8c6a6fdd71c8d1f14ba04e84f3fd2a25';
    $config['fileUpload'] = true; // optional

    // instantiate
    $facebook = new Facebook($config);

    // set page id
    $pageid = $user;

    // now we can access various parts of the graph, starting with the feed
    $pagefeed = $facebook->api("/" . $pageid . "/feed");
 
            $i = 0;
			$posts=array();
			
            foreach($pagefeed['data'] as $post) {
                ob_start();
                
                ?>              
                <div class="grid-item col-xs-6 col-md-4 col-lg-3">
				  <div class="tweet facebook-post">
					<div class="tweet-text facebook-text">
					<p><?php 
					if (empty($post['story']) === false) {
                                echo $post['story'];
                            } elseif (empty($post['message']) === false) {
                                echo $post['message'];
                            }
					?><br/><a href="https://www.facebook.com/pages/Dukes-Sidmouth/352649408118116?fref=ts">Dukes Inn on Facebook</a></p>		
					</div>  
				  </div>
				</div>
				<?php
			                      
                    
                    
                    $i++; 
                
                
                
                //  break out of the loop if counter has reached 10
                if ($i == $count) {
                    break;
                }
			
				array_push($posts, ob_get_clean());
				
            } // end the foreach statement
            
			return $posts;
}           
   
	