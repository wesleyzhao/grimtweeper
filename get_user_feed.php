<?php

require_once('../lib2/EpiCurl.php');
require_once('../lib2/EpiOAuth.php');
require_once('../lib2/EpiTwitter.php');
require_once('../lib/secret.php');
try{
$twitterObj = new EpiTwitter($consumer_key,$consumer_secret);

	session_start();
	$token = $_SESSION['ot'];
	$secret = $_SESSION['ots'];
	$twitterObj->setToken($token,$secret);
	
	//$friend_id = $_GET['friend_id'];
	$user_id = $_GET['user_id'];
	
	
	$timeline_max = 200;
	
	$resp2 = $twitterObj->get('/statuses/friends_timeline.json',array('count'=>$timeline_max,'include_rts'=>'t'));
	$response = $resp2->response;
	echo count($response);
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