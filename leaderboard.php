<?php
session_start();
require_once('lib2/EpiCurl.php');
require_once('lib2/EpiOAuth.php');
require_once('lib2/EpiTwitter.php');
require_once('lib/secret.php');
require_once('mysql_connect.php');
require('get_leaderboard.php');
?>

<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="leaderboard.css">
    <title>Top Grim Tweeps</title>
</head>

<body>
    
    <h2>So who are the Top De-tweepers?</h2>
    
    <table id = "leaderboard">
        
        <?php echo(getLeaderboard(5));?>
  
</table>



</body>
</html>
