<?php 
require_once('Connections/localhost.php'); ?>
<?php require_once('settings.php'); ?>
<?php
	
	function moderate($phrase,$dictionaryfile) {
	$file = file_get_contents($dictionaryfile, true);
	$dictionary = preg_split( '/\r\n|\r|\n/', trim($file) );
	for ($a=0;$a<count($dictionary);$a++) {
		$dictionary[$a]=trim($dictionary[$a]);
	}
	
	$dinstance=2;
	//$firstpass = preg_replace('/[^a-z0-9]+/i', ' ', $phrase);
	$wordloop=preg_split( '/\r\n|\r|\n|\s/', trim($phrase) );
	$clean=true;
	for($i=0;$i<count($wordloop);$i++) {
		$current=strtolower($wordloop[$i]);
		for ($a=0;$a<count($dictionary);$a++) {
			
			$resulto=preg_match($dictionary[$a],$current);
			$dis=levenshtein($dictionary[$a],$current);
			
			if($resulto==1 || $dis<=2) {
				echo "BAD: ".$current." against ".$dictionary[$a]." regexp:".$resulto." dinst".$dis."<br/>";
				echo "phrase was:".$phrase."<br/>";
				return $current." against ".$dictionary[$a];
			}
			
		}

	}
	return $clean;
}
	
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

    if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
    {
      if (PHP_VERSION < 6) {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
      }
    
      $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
    
      switch ($theType) {
        case "text":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;    
        case "long":
        case "int":
          $theValue = ($theValue != "") ? intval($theValue) : "NULL";
          break;
        case "double":
          $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
          break;
        case "date":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "defined":
          $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
          break;
      }
      return $theValue;
    }
    }
    
    
echo "last pulled tweet:".$_COOKIE['lastTweet']."<br/>";
    
    
require_once("twitter.php");
$t = new Twitter('1kAXtd644bgJ4qyDNl51w','3lBlY9gqVrmpaij3cvfSQV2CrB2N6x7u9KiQ268GHQ');
$content=$t->searchTweets("#nba");

//$jsonobj = json_decode($content);

print_r($content);

echo "number of results: ".$content['search_metadata']['count'];

echo "<br/>";
    
      	mysql_select_db($database_localhost, $localhost);
        foreach($content['statuses'] as $item){
			
			echo "item:<br/>";
			print_r($item);
			echo "<br/>";
    
    
            $id = $item['id_str'];
            $created_at = $item['created_at'];
            $created_at = strtotime($created_at);
            $mysqldate = date('Y-m-d H:i:s',$created_at);
            $from_user = mysql_real_escape_string($item['user']['screen_name']);
            $from_user_id = $item['user']['id_str'];
            $text = mysql_real_escape_string($item['text']);
			if(strlen(trim($text))>0) {
				$hastext=1;
			} else {
				$hastext=0;
			}
            $source = mysql_real_escape_string($item['source']);
            $geo = $item['geo'];
            $iso_language_code = $item['iso_language_code'];
            
            // $test = $item->entities->user_mentions[0]->screen_name;
            $profile_image_url = mysql_real_escape_string($item['user']['profile_image_url']);
            $to_user_id = $item['to_user_id'];
            if($to_user_id==""){ $to_user_id = 0; }

			$reso=moderate($text,"admin/badwords.txt");

			if($autoapprove) {
				$pass=1;
			} else {
				$pass=0;
			}
			
			if($reso===true) {
				$flagged="OK";
			} else {
				$flagged="BAD";
				
				$pass=0;
			}
			echo "media count:".count($item['entities']['media']);
			echo "pass is:".$pass." while approved $autoapprove<br/>";
			$hasphoto=count($item['entities']['media']);
			if($hasphoto>0) {
				$hasphoto=1;
				$picture=$item['entities']['media'][0]['media_url'];
			} else {
				$hasphoto=0;
				$picture="";
				
			}
				
					echo "real name:$id".$userobj->name."<br/>";
										
				      $insertSQL = sprintf("INSERT IGNORE INTO tweets (sourceId,mysqldate,name,sourceUserId,text,profilePicture,approved,flagged,hasPicture,picture,hasText,sweardebug) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                           GetSQLValueString($id, "text"),
						   GetSQLValueString($mysqldate, "text"),
						   GetSQLValueString($from_user, "text"),
						   GetSQLValueString($from_user_id, "int"),
						   GetSQLValueString($text, "text"),
						   GetSQLValueString($profile_image_url, "text"),
						   $pass,
						   GetSQLValueString($flagged, "text"),
						   GetSQLValueString($hasphoto, "text"),
						   GetSQLValueString($picture, "text"),
						   GetSQLValueString($hastext, "text"),
						   GetSQLValueString($reso, "text")
						   						   );
						   						   
// print('<br><br>'.$insertSQL.'<br><br>');	   
						   						   
      					$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error()." ".$insertSQL);
      					
			}
			

    ?>
