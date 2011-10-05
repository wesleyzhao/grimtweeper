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
	
	$screen_name = $_GET['screen_name'];
	
	$statusResp = $twitterObj->get('/statuses/user_timeline.json',array('screen_name'=>$screen_name,'count'=>50));
	//print_r($statusResp);
	$headers = $statusResp->headers;
	$statusResponse = $statusResp->response;
	$cur_date = $headers['Date'];
	$cur_date_arr = explode(' ',$cur_date);
	
	$cur_date_day = $cur_date_arr[1];
	$cur_date_mon = $cur_date_arr[2];
	$cur_date_yr = $cur_date_arr[3];
	$cur_date_time = explode(':',$cur_date_arr[4]);
	$cur_date_hr = $cur_date_time[0];
	$cur_unix_string = $cur_date_arr[2]." ".$cur_date_arr[1]." ".$cur_date_arr[4]." ".$cur_date_arr[3];
	
	echo "<br>last_modified: {$headers['Date']}<br>";
	echo "day: $cur_date_day , month: $cur_date_mon , year: $cur_date_yr , hour: $cur_date_hr <br>";
	$old_tweet = $statusResponse[(count($statusResponse)-1)];
	$old_tweet_time = $old_tweet['created_at'];
	$old_tweet_arr = explode(' ',$old_tweet_time);
	$old_tweet_day = $old_tweet_arr[2];
	$old_tweet_mon = $old_tweet_arr[1];
	$old_tweet_yr = $old_tweet_arr[5];
	$old_tweet_timing = explode(':',$old_tweet_arr[3]);
	$old_tweet_hr = $old_tweet_timing[0];
	$old_unix_string = $old_tweet_arr[1]." ".$old_tweet_arr[2]." ".$old_tweet_arr[3]." ".$old_tweet_arr[5];
	echo "last created at: $old_tweet_time <br>";
	echo "day: $old_tweet_day , month: $old_tweet_mon , year: $old_tweet_yr , hour: $old_tweet_hr <br>";
	$cur_unix = strtotime($cur_unix_string);
	$old_unix = strtotime($old_unix_string);
	echo "cur_unix: $cur_unix *** old_unix: $old_unix <br>";
	$time_diff = $cur_unix-$old_unix;
	$time_diff_min = $time_diff/60.0;
	$time_diff_hrs = $time_diff_min/60.0;
	$time_diff_days = $time_diff_hrs/24.0;
	$time_diff_days = $time_diff/(60.0*60.0*24.0);
	$tweets_day = ceil(50/$time_diff_days);

	echo $time_diff_days;
	echo "<br>".$tweets_day;
	
	
	
?>