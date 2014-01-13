<?php require_once('Connections/localhost.php'); ?>
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

mysql_select_db($database_localhost, $localhost);
$query_Recordset1 = "select * from tweets where date(mysqldate)> DATE_SUB(now(),INTERVAL 5 DAY) and panic=1 ORDER BY mysqldate DESC";
$Recordset1 = mysql_query($query_Recordset1, $localhost) or die(mysql_error());


if(mysql_num_rows($Recordset1)>0)
{
	while ($get_info = mysql_fetch_assoc($Recordset1)) {
		$rowset[]= $get_info;

	}
}
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//send the xml header to the browser
header('Content-type: application/json');
//die($_COOKIE['lastTweet']);
print json_encode($rowset);

?>
<?php
mysql_free_result($Recordset1);
?>
