<?php
session_start();
require_once('lib2/EpiCurl.php');
require_once('lib2/EpiOAuth.php');
require_once('lib2/EpiTwitter.php');
require_once('lib/secret.php');
require_once('mysql_connect.php');

$twitterObj = new EpiTwitter($consumer_key,$consumer_secret);
$oauth_token = $_GET['oauth_token'];
if ($oauth_token == '' && $_SESSION['ot']==''){
	header("location: http://grimtweeper.com");
}
else{
	//$_SESSION['oauth_token'] = $_GET['oauth_token'];
	try{
	if ($_SESSION['ot']==''){
		$twitterObj->setToken($_GET['oauth_token']);
		$token = $twitterObj->getAccessToken();
		$twitterObj->setToken($token->oauth_token,$token->oauth_token_secret);
		$_SESSION['ot'] = $token->oauth_token;
		$_SESSION['ots'] = $token->oauth_token_secret;
		
	}
	else{
		$twitterObj->setToken($_SESSION['ot'],$_SESSION['ots']);
	}
	mysqlConnect();
	$twitterInfo = $twitterObj->get_accountVerify_credentials();
	$twitterInfo->response;
	$username = $twitterInfo->screen_name;
			mysql_query("INSERT INTO logins (username) VALUES ('$username')");
			
	$user_id =$twitterInfo->id;
	//$user_id = $twitterInfo->user_id;
	$profilepic = $twitterInfo->profile_image_url;
	$followers_count = $twitterInfo->followers_count;
	$friends_count = $twitterInfo->friends_count;
		$_SESSION['user_id'] = $user_id;
		mysql_query("INSERT INTO scores (username,user_id,image_url) VALUES ('$username','$user_id','$profilepic')");
		$res = mysql_query("SELECT score FROM scores WHERE user_id='$user_id'");
		$row = mysql_fetch_array($res);
		$score = intval($row['score']);
	$resp = $twitterObj->get('/friends/ids.json',array('username'=>$username));
	$friend_ids = $resp->response;
	
	$res = mysql_query("SELECT user_id FROM views WHERE viewed_by_id='$user_id'");
	if (mysql_num_rows($res)){
		while($row =mysql_fetch_array($res)){
			$index = array_search($row['user_id'],$friend_ids);
			if ($index!= false) unset($friend_ids[$index]);
			
		}
		$friend_ids = array_values($friend_ids);
	}
	
	$is_error = false;
	}
	catch (EpiTwitterServiceUnavailableException $e){
		//echo "You have eliminated a lot of friends! The Grim Tweeper is tired... come back in about an hour.<br><br><em>AKA: Twitter rate limited us...</em>";
		$is_error = true;
	}
	catch (EpiTwitterBadRequestException $e){
		$is_error = true;
	}

	
	}
?>
<html>
<head>
	<link rel="stylesheet" href="main.css" type="text/css"/>
	<link rel='SHORTCUT ICON' href='favicon.ico'/>
	<script type="text/javascript"
	 src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js">
	</script> 
		<script type="text/javascript">
		function unhide(div_id) {
		var item = document.getElementById(div_id);
		if (item) {
			item.className=(item.className=='hidden')?'unhidden':'hidden';
		}
	}
	</script>
	
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1108031-19']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>
	<title>The Grim Tweeper</title>
	</head>

	<body>
		<div id = "header">
			<div id = "headwrap">
			<h1>Th<span class = "ar">e</span> Grim Tw<span class = "ar">ee</span>p<span class = "ar">e</span>r</h1>
			<div id = "you">
				
				<img class='profile-image' src ='<?=$profilepic?>' /> <!--<a href = "http://twitter.com/<?=$username?>" class = inherit>@<?=$username?></a>-->
				<a href='logout.php'>log out</a>
			</div>
			</div>
		
		</div>
		
		<div class = "wrapper">
			<div id = "content">
				Who do you have on the chopping block now? <br /><br />
				
	
				<div id = "controls">
				<div id='message-div'></div>
					<a href = "#" id = "kill" onclick='javascript:destroyFriendship();'>KILL</a>
					 <a href = "#" id = "keep" onclick='javascript:replacePerson();'>KEEP</a>
					<div id="last-rejected"></div>		
				</div>
				<div id = "them">
				<?php
					if ($is_error){
						echo "<b>Me thinks you slayed a few too many followers!</b><br><br><br>
						The Grim Tweeper is tired <em>(aka Twitter rate limited us...)</em>!<br><br><br>
						Come back in about an hour :). Thanks, and sorry for the inconvenience we caused.";
					}
				?>
				</div><!--end of them-->
			</div>
		
	<div id = "promo">
			<div id="float-right">
					<div class='tweet-button'><a href="http://twitter.com/share" class="twitter-share-button" data-url="http://grimtweeper.com" data-text="The Grim Tweeper - a new way to clean up your following list. Start cutting down now! #grimtweeper" data-counturl="http://www.grimtweeper.com" data-count="horizontal" data-via="thetweeper" >Tweet</a></div><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			
					<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fgrimtweeper.com&amp;layout=standard&amp;show_faces=false&amp;width=300&amp;action=like&amp;font&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:35px;" allowTransparency="true"></iframe>
					</div>
			You've killed <span id = "kill_count">10</span> follows with The Grim Tweeper.
		 
			
		</div>
		<iframe src="leaderboard.php" width="100%" height="300px" frameborder = "0">
		<p>Your browser does not support iframes.</p>
		</iframe> 
		<center>
			<script type="text/javascript">var adNumber = 1;</script>
				<script language="JavaScript" src="http://admore.heroku.com/javascripts/embed.js"></script>
		</center>
		<!--<br><br> -->
		<div class="footer"> 
		<span id = "about">The Grim Tweeper is Copyright 2011 <a href="http://twitter.com/weslayzhao">Wesley Zhao</a>, <a href="http://twitter.com/temiri">Tess Rinearson</a>, <a href="http://twitter.com/danshipper">Dan Shipper</a> and <a href="http://twitter.com/ajaymehta">Ajay Mehta</a></span> 
		</div>
	</div>


<script type="text/javascript">
var _sf_async_config={uid:19911,domain:"grimtweeper.com"};
(function(){
  function loadChartbeat() {
    window._sf_endpt=(new Date()).getTime();
    var e = document.createElement('script');
    e.setAttribute('language', 'javascript');
    e.setAttribute('type', 'text/javascript');
    e.setAttribute('src',
       (("https:" == document.location.protocol) ? "https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" : "http://static.chartbeat.com/") +
       "js/chartbeat.js");
    document.body.appendChild((e));
  }
  var oldonload = window.onload;
  window.onload = (typeof window.onload != 'function') ?
     loadChartbeat : function() { oldonload(); loadChartbeat(); };
})();

</script>
</body>


<script type='text/javascript'>
var friend_ids = <?php echo json_encode($friend_ids);?>;
var friend_ids_copy = <?php echo json_encode($resp->response);?>;
var friends_count = <?php echo $friends_count;?>;
var followers_count = <?php echo $followers_count;?>;
var user_id = <?php echo $user_id;?>;
var friends_count_changed = <?=$score ?>;
var curFriendId = 0;
var last_user_reject = '';

var last_user_reject_id = 0;
replacePerson();
$("#user-followers").html(followers_count);
$("#user-following").html(friends_count);
$("#kill_count").html(friends_count_changed);
function tweet_click(){
	var v='thetweeper';
	var r='thetweeper';
	var u = 'http://www.grimtweeper.com';
	var t = 'I feel liberated! I just slayed '+friends_count_changed+ ' tweeps with @thetweeper! #grimtweeper';
	var ref = 'http://twitter.com/share?related='+encodeURIComponent(r)+'&url='+encodeURIComponent(u)+'&text='+encodeURIComponent(t)+'&via='+encodeURIComponent(v);
	window.open(ref,'sharer','toolbar=0,status=0,width=535,height=355');
	return false;
}
function updateFriendsCount(){
//subtracts one from friends_count and then updates the span id tag
	if (friends_count>0){
		friends_count = friends_count-1;
		friends_count_changed+=1;
		if (friends_count_changed>0 && (friends_count_changed%10)==0){
			tweet_click();
		}
	}
	$("#user-following").html(friends_count);
	$("#kill_count").html(friends_count_changed);
}

function undoRejection(){
	if (last_user_reject!=''){
		//last_user_reject = $("#friend-username").html();
		var url = encodeURI('create_friendship.php?friend_screen_name='+last_user_reject);
		var url2 = encodeURI("undo_kill.php?friend_id="+last_user_reject_id);
		$.get(url2,function (data){});
		$.get(url,function(data){
				$("#last-rejected").html("You are friends with "+last_user_reject+" again!");
				friends_count_changed-=1;
				$("#kill_count").html(friends_count_changed);
			});
		
		
	}
}

function updateLastUserReject(){
	last_user_reject = $("#friend-username").html();
}

function updateLastUserRejectMessage(){
	$("#last-rejected").html("Last kill: @"+last_user_reject+". <b><a href='#' onclick='javascript:undoRejection();'>Click to re-friend.</a></b>");
}
function getNewId(){
	if (friend_ids.length<=0){
		friend_ids = friend_ids_copy.slice(0);
		var url2 = encodeURI("reset_user.php");
		$.get(url2,function(data){});
	}
	
	var newIdIndex = Math.floor(Math.random()*friend_ids.length);
	var newId = friend_ids[newIdIndex];
	friend_ids.splice(newIdIndex,1);
	return newId;
	
}
/*
function replacePerson(){
	var beenSeen = '1';     
	//while (beenSeen == '1'){
		curFriendId = getNewId();
		var url = encodeURI("has_been_viewed.php?user_id="+user_id+"&friend_id="+curFriendId); 
		alert(url);
		$.get(url,function(data){ 
			alert("data:" + data);
			beenSeen = data; 
			alert(beenSeen);
		});
		if (beenSeen =='1' && friend_ids.length<=0){
			var url2 = encodeURI("reset_user.php");
			$.get(url2,function(data){});
		}
	//}
	
	var url = encodeURI('get_new_friend.php?friend_id='+curFriendId+"&user_id="+user_id);
	setMessage('');
	$("#them").load(url);
	
}
*/
function replacePerson(){
	
	curFriendId = getNewId();
	var url = encodeURI('get_new_friend.php?friend_id='+curFriendId+"&user_id="+user_id);
	var url2 = encodeURI("has_been_viewed.php?user_id="+user_id+"&friend_id="+curFriendId); 
	setMessage('');
	$("#them").load(url);
	
	$.get(url2,function(data){});
	
}

function destroyFriendship(){
	var url = encodeURI('destroy_friendship.php?friend_id='+curFriendId);
	var url2 = encodeURI("make_kill.php?friend_id="+curFriendId);
	updateLastUserReject();
	last_user_reject_id = curFriendId;
	replacePerson();
	$('#message-div').load(url);
	$.get(url2,function (data){});
	setMessage('Friendship destroyed!');
	
	updateLastUserRejectMessage();
	
	updateFriendsCount();
}

function setMessage(text){
	$("#message-div").html(text);
}
</script>