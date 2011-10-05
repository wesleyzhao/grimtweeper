<?php
//returns back new score of user if properly signed in, else returns -1
require_once('mysql_connect.php');

session_start();

$killer_id = $_SESSION['user_id'];
if ($killer_id){
	$killed_id = $_GET['friend_id'];
	mysqlConnect();
	mysql_query("UPDATE views SET is_killed='0' WHERE (user_id = '$killed_id' AND viewed_by_id='$killer_id')");
	$res = mysql_query("SELECT score FROM scores WHERE user_id='$killer_id'");
	if (mysql_num_rows($res)){
		$row = mysql_fetch_array($res);
		$score = intval($row['score']);
		$new_score = $score-1;
		if ($new_score<0) $new_score=0;
		mysql_query("UPDATE scores SET score='$new_score' WHERE user_id='$killer_id'");
		echo $new_score;
	}
	
}
else echo -1;
?>