<?php

require_once('lib2/EpiCurl.php');
require_once('lib2/EpiOAuth.php');
require_once('lib2/EpiTwitter.php');
require_once('lib/secret.php');

$twitterObj = new EpiTwitter($consumer_key,$consumer_secret);

	session_start();
	$token = $_SESSION['ot'];
	$secret = $_SESSION['ots'];
	$twitterObj->setToken($token,$secret);
	
	$friend_id = $_GET['friend_id']; 
	$source_username =$_GET['source_username'];
	$friend_username= $_GET['friend_username'];
	
	$resp2 = $twitterObj->get('/friendships/show.json',array('source_screen_name'=>$source_username,'target_screen_name'=>$friend_username));
	$response = $resp2->response;        
	print_r($response);
	/*
	$resp3 = $twitterObj->get('/statuses/user_timeline.json',array('user_id'=>$friend_id,'count'=>5));
	$statusResponse = $resp3->response;
	
	$statusText = "";
	foreach ($statusResponse as $status){
		$statusText = $statusText."<br>{$status['text']}<br><i>{$status['created_at']}</i><br>";
	}
	//print_r($response);
	$friend_img = $response[0]['profile_image_url'];
	$friend_username = $response[0]['screen_name'];
	$friend_status = $response[0]['status']['text'];
	$friend_bio = $response[0]['description'];
	$friend_name = $response[0]['name'];
	$friend_friend_count = $response[0]['friends_count'];
	$friend_follower_count = $response[0]['followers_count'];
	$friend_location = $response[0]['location'];
	
	//$friends = array();
	//foreach ($resp as $friend){
		
	//}
	echo "<img src='$friend_img' alt='$friend_username'/><br><b>username:</b> $friend_username<br><b>Name: </b>$friend_name<br><b>Location: </b>$friend_location<br><b>Followers:</b> $friend_follower_count <b>Following:</b> $friend_friend_count<br><b>Bio:</b> $friend_bio<br><b>Latest Tweets:</b><br> $statusText<br><a href='#' onclick='javascript:destroyFriendship();'>DESTROY FRIENDSHIP</a><br>";
	//print_r($resp);
	           */
?>