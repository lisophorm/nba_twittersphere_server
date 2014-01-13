<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once('../Connections/localhost.php'); ?>
<?php
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_tweets = 50;
$pageNum_tweets = 0;
if (isset($_GET['pageNum_tweets'])) {
  $pageNum_tweets = $_GET['pageNum_tweets'];
}
$startRow_tweets = $pageNum_tweets * $maxRows_tweets;

mysql_select_db($database_localhost, $localhost);
$query_tweets = "SELECT id, approved, mysqldate, panic,name, flagged,text, profilePicture,picture,sweardebug FROM tweets ORDER BY mysqldate DESC ";
$query_limit_tweets = sprintf("%s LIMIT %d, %d", $query_tweets, $startRow_tweets, $maxRows_tweets);
$tweets = mysql_query($query_limit_tweets, $localhost) or die("$query_limit_tweets".mysql_error());
$row_tweets = mysql_fetch_assoc($tweets);

if (isset($_GET['totalRows_tweets'])) {
  $totalRows_tweets = $_GET['totalRows_tweets'];
} else {
  $all_tweets = mysql_query($query_tweets);
  $totalRows_tweets = mysql_num_rows($all_tweets);
}
$totalPages_tweets = ceil($totalRows_tweets/$maxRows_tweets)-1;

$queryString_tweets = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_tweets") == false && 
        stristr($param, "totalRows_tweets") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_tweets = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_tweets = sprintf("&totalRows_tweets=%d%s", $totalRows_tweets, $queryString_tweets);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Refresh" CONTENT="30; URL=moderator.php"> 
<title>Untitled Document</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript"  src="../js/jquery-1.7.1.min.js"></script>
</head>

<body>
Records <?php echo ($startRow_tweets + 1) ?> to <?php echo min($startRow_tweets + $maxRows_tweets, $totalRows_tweets) ?> of <?php echo $totalRows_tweets ?>
<table>
  <tr>
    <td>id</td>
    <td>approved</td>
    <td>automatic<br />
      flag</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>mysqldate</td>
    <td>name</td>
    <td>text</td>
    <td>Picture</td>
    <td>profilePicture</td>
    <td>Regexp results</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_tweets['id']; ?></td>
      <td><div id="cell<?php echo $row_tweets['id']; ?>"><a href="censor.php?approved=1&amp;id=<?php echo $row_tweets['id']; ?>" class="allow">allow</a> / <a href="censor.php?approved=0&amp;id=<?php echo $row_tweets['id']; ?>" class="block">block</a></div></td>
      <td><?php echo $row_tweets['flagged']; ?></td>
      <td><div class="status"><?php echo $row_tweets['approved']==1?"APPROVED":"BLOCKED";  ?></div></td>
      <td><div class="panic"><a class="panicbutton" href="panic.php?id=<?php echo $row_tweets['id']; ?>"><img src="../images/<?php 
	  
	  if($row_tweets['panic']=="") {
		  echo "blank.png";
	  } else if ($row_tweets['panic']==1) {
		  echo "ok.png";
	  } else {
		  echo "panic.png";
	  }
	  
	  
	  ?>" width="50" height="50" border="0" /></a></div></td>
      <td><?php echo $row_tweets['mysqldate']; ?></td>
      <td><?php echo $row_tweets['name']; ?></td>
      <td><?php echo $row_tweets['text']; ?></td>
      <td><a href="<?php echo $row_tweets['picture']; ?>" target="_blank" ><img src="<?php echo $row_tweets['picture']; ?>" alt="" width="100"   /></a></td>
      <td><img name="" src="<?php echo $row_tweets['profilePicture']; ?>" alt="" /></td>
      <td><?php echo $row_tweets['sweardebug']; ?></td>
    </tr>
    <?php } while ($row_tweets = mysql_fetch_assoc($tweets)); ?>
</table>
<p>&nbsp;
  <a href="<?php echo $logoutAction ?>">Log out</a>
<table border="0">
  <tr>
    <td><?php if ($pageNum_tweets > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_tweets=%d%s", $currentPage, 0, $queryString_tweets); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_tweets > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_tweets=%d%s", $currentPage, max(0, $pageNum_tweets - 1), $queryString_tweets); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_tweets < $totalPages_tweets) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_tweets=%d%s", $currentPage, min($totalPages_tweets, $pageNum_tweets + 1), $queryString_tweets); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_tweets < $totalPages_tweets) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_tweets=%d%s", $currentPage, $totalPages_tweets, $queryString_tweets); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_tweets + 1) ?> to <?php echo min($startRow_tweets + $maxRows_tweets, $totalRows_tweets) ?> of <?php echo $totalRows_tweets ?>
</p>
<script type="text/javascript">
$(".allow").click(function(e) {
	var status=$(this).parent().parent().parent().find(".status");
	var dest=$(this).attr("href");
	var imago=$(this).parent().parent().parent().find(".panic");
	console.log("imago:"+imago.html());
	imago.find("img").attr("src","../images/panic.png");
	
    $.get(dest);
	status.html("APPROVED");
	return false;
});
$(".block").click(function(e) {
	var status=$(this).parent().parent().parent().find(".status");
	var dest=$(this).attr("href");
    $.get(dest);
	status.html("BLOCKED");
	return false;
});
$(".panicbutton").click(function(e) {
	var img=$(this).find("img");
	var status=$(this).parent().parent().parent().find(".status");
	var dest=$(this).attr("href");

			status.html("BLOCKED");
			$(this).find("img").attr("src","../images/ok.png");
			$.get(dest);
	


	return false;
});
$(document).ready(function(e) {
   $("tr:odd").addClass("odd");
});
</script>
</body>
</html>
<?php
mysql_free_result($tweets);
?>
