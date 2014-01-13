<?php require_once('../Connections/localhost.php'); ?>
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
	
  $logoutGoTo = "index.php";
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

$MM_restrictGoTo = "index.php?error=1";
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
$query_export = "SELECT firstname, mysqldate, lastname, email, optInMarketing, aboutus, aboutus_extra, postcode,gender,age FROM users";
$export = mysql_query($query_export, $localhost) or die(mysql_error());
$row_export = mysql_fetch_assoc($export);
$totalRows_export = mysql_num_rows($export);

//Export to Excel Server Behavior
if (isset($_GET['export'])&&($_GET['export']=="1")){
	$delim="";
	$delim_replace="";
	if($delim==""){
		$lang=(strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'],",")===false)?$_SERVER['HTTP_ACCEPT_LANGUAGE']:substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'],","));
		$semi_array=array("af","zh-hk","zh-mo","zh-cn","zh-sg","zh-tw","fr-ch","de-li","de-ch","it-ch","ja","ko","es-do","es-sv","es-gt","es-hn","es-mx","es-ni","es-pa","es-pe","es-pr","sw");
		$delim=(in_array($lang,$semi_array) || substr_count($lang,"en")>0)?",":";";
	}
	$output="";
	$include_hdr="1";
	if($include_hdr=="1"){
		$totalColumns_export=mysql_num_fields($export);
		for ($x=0; $x<$totalColumns_export; $x++) {
			if($x==$totalColumns_export-1){$comma="";}else{$comma=$delim;}
			$output = $output.(str_replace("_", " ",mysql_field_name($export, $x))).$comma;
		}
		$output = $output."\r\n";
	}

	do{$fixcomma=array();
    		foreach($row_export as $r){array_push($fixcomma,str_replace($delim,$delim_replace,$r));}
		$line = join($delim,$fixcomma);
    		$line=str_replace("\r\n", " ",$line);
    		$line=str_replace("\r", " ",$line);
    		$line=str_replace("\n", " ",$line);
    		$line = "$line\n";
    		$output=$output.$line;}while($row_export = mysql_fetch_assoc($export));
	$export_encoding="";
	$export_encoding=($export_encoding=="")?"":("; charset=".$export_encoding);
	header("Content-Type: application/xls".$export_encoding);
	header("Pragma: public");
	header("Content-Disposition: attachment; filename=report.csv");
	header("Content-Type: application/force-download");
	header("Cache-Control: post-check=0, pre-check=0", false);
	echo $output;
	die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Export excel document</title>
</head>

<body>
<p>Please <a href="excelexport.php?export=1">click here</a> to export all the registrations
  
</p>
<p><a href="<?php echo $logoutAction ?>">Log out</a></p>
</body>
</html>
<?php
mysql_free_result($export);
?>
