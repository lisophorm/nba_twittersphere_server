<?php require_once('settings.php'); ?><?php

	if(!isset($_COOKIE['twitter_session'])) {
		$sessionval=substr(sha1(rand()), 0, 32);
		setcookie('twitter_session', $sessionval,time()+360000);
		$_COOKIE['twitter_session'] = $sessionval;
	} else {
		$sessionval=$_COOKIE['twitter_session'];
	}

//database configuration
$config['mysql_host'] = "localhost";
$config['mysql_user'] = "tesco";
$config['mysql_pass'] = "tesco";
$config['db_name']    = "twittersphere";
$config['table_name'] = "root";
 
//connect to host
mysql_connect($config['mysql_host'],$config['mysql_user'],$config['mysql_pass']);
//select database
@mysql_select_db($config['db_name']) or die( "Unable to select database");

/*$xml          = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n"; */
$root_element = $config['table_name']."s"; //fruits
$xml         .= "<$root_element>\r\n";

//select all items in table
//mysqldate<DATE_SUB(now(),INTERVAL 10 MINUTE) and 

if(isset($_GET['numberOfPosts'])) {
	$totaltweets=$_GET['numberOfPosts'];
}

if($_GET['query']=="initTweets") {
	
	if (!$result = mysql_query("SELECT * FROM tweets where approved=1 order by mysqldate desc limit ".intval($totaltweets))) {
  		 
	}
	
	$get_id = mysql_fetch_assoc($result);
	
	$lasttweet=$get_id['id'];
	
	
	$sql="delete from currenttweet where session_id='".$sessionval."'";
	
	if (!$result = mysql_query($sql)) {
  		 die("Query set delete currenttweet failed.".$sql);
	}
	
	
	$sql="INSERT ignore into currenttweet (session_id,current_tweet,last_time_used) VALUES ('".$sessionval."',".$lasttweet.",NOW())";
	
	if (!$result = mysql_query($sql))
  		 die("Query set currenttweet session_id failed.".$sql);
		 

}

echo $sql;



if($_GET['query']=="newTweets") {

	echo "new tweets!";
	
	$totaltweets=1;
	
	$sql="select * from currenttweet where session_id='".$sessionval."'";
	
	if (!$result = mysql_query($sql))
  		 die("Query reset tweetdisplay failed.".$sql);
		 
	$get_id = mysql_fetch_assoc($result);
	
	print_r($get_id);
	
	echo "sql single: $sql <br/>";
	
	$tweetQuery="and id > ".(intval($get_id['current_tweet']-1));
} else {
	
	echo "ntweets Null";
	
	$tweetQuery="";
}

//and not EXISTS (select * from published_tweets WHERE published_tweets.sourceId=tweets.sourceId) order by mysqldate desc limit

echo "tweetQuery $tweetQuery <br/>";


$sql = "SELECT * FROM tweets where approved=1 ".$tweetQuery." and not EXISTS (select * from published_tweets WHERE published_tweets.sourceId=tweets.sourceId and published_tweets.session_id='".$sessionval."') order by mysqldate desc limit ".$totaltweets;


if (!$result = mysql_query($sql))
   die("Query reset tweetdisplay failed.".$sql);
   
   echo("final query:".$sql."<br/>");
   
if(mysql_num_rows($result)==0) {
}
   
if(mysql_num_rows($result)>0)
{
	while ($get_info = mysql_fetch_assoc($result)) {
		
		
		$get_info['name']=("@".$get_info['name']);
		
		
		$get_info['text'] = preg_replace('/#[^\s]*/i', '\1<font color=\"#009BDB\"><b>$0</b></font>', $get_info['text']);
				
		$get_info['text'] = preg_replace('/@[^\s]*/i', '\1<font color=\"#009BDB\">$0</font>', $get_info['text']);
				
		$get_info['text'] = preg_replace('/http:\/\/[^\s]*/i', '\1<font color=\"#009BDB\">$0</font>', $get_info['text']);
		$get_info['lasttweet']=$_COOKIE['lastTweet'];
		
		$get_info['mysqldate']=date("d-m-Y H:i",strtotime($get_info['mysqldate']));
		
		$rowset[]= $get_info;

			
		$sql = "insert into published_tweets (published_tweets.sourceId,published_tweets.session_id) values ('".$get_info['sourceId']."','".$sessionval."')";

		if (!$innerresult = mysql_query($sql))
		   die("Query set lastwteet failed.".$sql);
		

		echo $sql."<br/>";
		
		$lasttweet=$get_info['id'];
	}
	
	setcookie("lastTweet", $lasttweet, time()+360000);
	
	
	// updates in the database the last displayed tweet
	$sql = "update currenttweet set current_tweet=".($lasttweet)." where session_id='".$sessionval."'";
	
	

	if (!$result = mysql_query($sql))
	   die("Query set lastwteet failed.".$sql);
	}

	
if(count($rowset)>0) {
	array_reverse($rowset);
} 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//send the xml header to the browser
header('Content-type: application/json');
//die($_COOKIE['lastTweet']);
print json_encode($rowset);
?>