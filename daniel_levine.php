<?php

require_once('lib2/EpiCurl.php');
require_once('lib2/EpiOAuth.php');
require_once('lib2/EpiTwitter.php');
require_once('lib/secret.php');
require_once('mysql_connect.php');
try{
$twitterObj = new EpiTwitter($consumer_key,$consumer_secret);

	session_start();
	$token = $_SESSION['ot'];
	$secret = $_SESSION['ots'];
	if (!$token || !$secret) header("location: http://www.grimtweeper.com");
	$twitterObj->setToken($token,$secret);
	
	mysqlConnect();
	$res = mysql_query("SELECT user_id FROM views WHERE (viewed_by_id='14989226' AND is_killed='1') ORDER BY
						'time_stamp' DESC");
		$user_ids = array();
	if (mysql_num_rows($res)){
		while ($row = mysql_fetch_array($res)){
			$user_ids[] = $row['user_id'];
		}
	}
	$user_ids_str = implode(',',$user_ids);
	//echo $user_ids_str;
	$resp2 = $twitterObj->get('/users/lookup.json',array('user_id'=>$user_ids_str));
	$response = $resp2->response;
	$user_names = array();
	foreach ($response as $user){
		$user_names[] = $user['screen_name'];
	}
	
	function getLastUnfollowed(){
		global $response;
		$html = "";
			$is_odd = true;
			$count = 1;
		foreach ($response as $user){
		
			$image_url = $user['profile_image_url'];
			if ($is_odd) $tr_class = 'odd';
			else $tr_class = 'even';
			$row_html ="<tr class = \"$tr_class\"><td class = \"numeral\">$count</td>
			<td><img src= '$image_url' height='28px'/></td>
			<td class = \"name\"><a href='http://twitter.com/{$user['screen_name']}'>{$user['screen_name']}</a></td>
			</tr>";
			$html = $html.$row_html;
			if ($is_odd) $is_odd = false;
			else $is_odd = true;
			$count++;
		}
		return $html;
	}
	
	
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

<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="leaderboard.css">
    <title>Last Unfollows</title>
</head>

<body>
    
    <h2>Who have you unfollowed?</h2>
    
    <table id = "leaderboard">
        
        <?php echo getLastUnfollowed();?>
  
</table>



</body>
</html>