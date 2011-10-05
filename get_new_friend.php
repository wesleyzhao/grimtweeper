<?php

require_once('lib2/EpiCurl.php');
require_once('lib2/EpiOAuth.php');
require_once('lib2/EpiTwitter.php');
require_once('lib/secret.php');
try{
$twitterObj = new EpiTwitter($consumer_key,$consumer_secret);

	session_start();
	$token = $_SESSION['ot'];
	$secret = $_SESSION['ots'];
	$twitterObj->setToken($token,$secret);
	
	$friend_id = $_GET['friend_id'];
	$user_id = $_GET['user_id'];
	
	
	$timeline_max = 50;
	
	$resp2 = $twitterObj->get('/users/lookup.json',array('user_id'=>$friend_id));
	$response = $resp2->response;
	$resp3 = $twitterObj->get('/statuses/user_timeline.json',array('user_id'=>$friend_id,'count'=>$timeline_max));
	$statusResponse = $resp3->response;
	$friendResp = $twitterObj->get('/friendships/exists.json',array('user_a'=>$friend_id,'user_b'=>$user_id));
	$friendResponse = $friendResp->responseText;
	if ($friendResponse=='true') $isFollowing = "<img src='/images/tick.png' class='following-icon' alt='Checkmark image | The Grim Tweeper - clean up your Twitter follower list' width = 30px />";
	else $isFollowing = "<img src='/images/cross.png' class='following-icon' alt='Checkmark image | The Grim Tweeper - clean up your Twitter follower list' width = 30px />";
	
	$statusText = "";
	/*
	foreach ($statusResponse as $status){
		$statusText = $statusText."<br>{$status['text']}<br><i>{$status['created_at']}</i><br>";
	}
	*/
	for ($i = 0 ; $i <5 ; $i++){
		$statusText = $statusText."<br><div class='friend-tweet'>{$statusResponse[$i]['text']}</div><br><div class='friend-tweet-time'><i>{$statusResponse[$i]['created_at']}</i></div><br>";
	}
	$headers = $resp3->headers;
	$cur_date = $headers['Date'];
	$cur_date_arr = explode(' ',$cur_date);
	$cur_unix_string = $cur_date_arr[2]." ".$cur_date_arr[1]." ".$cur_date_arr[4]." ".$cur_date_arr[3];
	
	$old_tweet = $statusResponse[(count($statusResponse)-1)];
	$old_tweet_time = $old_tweet['created_at'];
	$old_tweet_arr = explode(' ',$old_tweet_time);
	$old_unix_string = $old_tweet_arr[1]." ".$old_tweet_arr[2]." ".$old_tweet_arr[3]." ".$old_tweet_arr[5];
	
	$cur_unix = strtotime($cur_unix_string);
	$old_unix = strtotime($old_unix_string);
		
	$time_diff = $cur_unix-$old_unix;
	$time_diff_days = $time_diff/(60.0*60.0*24.0);
	
		if ($old_unix_string=='' || $tweet_day==172) $tweets_day = 0;
		else $tweets_day = ceil($timeline_max/$time_diff_days);
	//print_r($friendResp->responseText);
	$friend_img = $response[0]['profile_image_url'];
	$friend_username = $response[0]['screen_name'];
	$friend_status = $response[0]['status']['text'];
	$friend_bio = $response[0]['description'];
	$friend_name = $response[0]['name'];
	$friend_friend_count = $response[0]['friends_count'];
	$friend_follower_count = $response[0]['followers_count'];
	$friend_location = $response[0]['location'];
	
	
	
	$imageHtml = "<img class='current-image' src = '$friend_img' alt='$friend_username profile picture | The Grim Tweeper - clean up your Twitter follower list' style=\"float:left; margin: 8px 10px 4px 0px;\">";
	$nameHtml = "<h2>$friend_name</h2><br />";
	$usernameHtml = "<a href = \"http://twitter.com/$friend_username\" alt='$friend_name Twitter | The Grim Tweeper - clean up your follower list'>@<span id=\"friend-username\">$friend_username</span></a>";
	
	$locationHtml = "<div id='friend-location' >$friend_location</div><br />";
	$bioHtml = "<em>$friend_bio</em><br /><br />";
	$followerStatsHtml_reverse = "<div class = \"more_info\" style = \"margin-left:-15px\"><span class = \"num\">$tweets_day</span><br /><b>Tweets/day</b></div>
					<div class = \"more_info\" ><span class = \"num\">$isFollowing</span><br /><b>Follows You</b></div>";
	$tweet_dayHtml_reverse = "<div class = \"more_info\" ><span class =\"num\">$friend_follower_count</span><br />Followers</div>";
	$followingStatsHtml_reverse = "<div class = \"more_info\" style = \"border:none\"><span class = \"num\">$friend_friend_count</span><br />Following</div><br />";
	
	$tweetsHtml  = "<h4><a href = \"javascript:unhide('latest');\" class = \"inherit\">>See @$friend_username's Latest Tweets</a></h4>
					<div id = \"latest\" class = \"hidden\"> $statusText</div>";
	
	$destroyHtml = "<a href='#' onclick='javascript:destroyFriendship();'>DESTROY FRIENDSHIP</a>";
	//$friends = array();
	//foreach ($resp as $friend){
		
	//}
	/*
	echo "$imageHtml<br>$usernameHtml<br>$nameHtml<br>$locationHtml<br>$followerStatsHtml
		<br>$followingStatsHtml<br>$bioHtml<br>$tweetsHtml<br>$tweet_dayHtml<br>
		$destroyHtml<br>$replyHtml<br>";
	*/
	echo $imageHtml.$nameHtml."<div id='subinfo'>".$usernameHtml.$locationHtml."</div>".$bioHtml.$followerStatsHtml_reverse.$tweet_dayHtml_reverse.$followingStatsHtml_reverse.$tweetsHtml;
		}
	catch (EpiTwitterServiceUnavailableException $e){
		echo "You have eliminated a lot of friends! The Grim Tweeper is tired... come back in about an hour.<br><br><em>AKA: Twitter rate limited us... or the Twitter Whale is at it again :(</em>";
	}
	catch (EpiTwitterBadRequestException $e){
		echo "You have eliminated a lot of friends! The Grim Tweeper is tired... come back in about an hour.<br><br><em>AKA: Twitter rate limited us... or the Twitter Whale is at it again :(</em>";
	}
	catch (EpiTwitterBadGatewayException $e){
		echo "You have eliminated a lot of friends! The Grim Tweeper is tired... come back in about an hour.<br><br><em>AKA: Twitter rate limited us... or the Twitter Whale is at it again :(</em>";
	}
	//print_r($resp);
	
?>