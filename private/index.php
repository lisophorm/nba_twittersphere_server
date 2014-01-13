<?php
function enforce_protocol($p,$d){
	if($p=="https"){
		if($_SERVER['HTTPS']==""){
			if($d==""){
				header("Location:https://".$_SERVER['HTTP_HOST'].":443".$_SERVER["PHP_SELF"].(($_SERVER["QUERY_STRING"]=="")?"":("?".$_SERVER["QUERY_STRING"])));
			}else{
				header("Location:https://".$d.":443".$_SERVER["PHP_SELF"].(($_SERVER["QUERY_STRING"]=="")?"":("?".$_SERVER["QUERY_STRING"])));
			}
		}
	}else if($p=="http"){
		if($_SERVER['HTTPS']=="on"){
			header("Location:http://".$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"].(($_SERVER["QUERY_STRING"]=="")?"":("?".$_SERVER["QUERY_STRING"])));
		}
	}
}
enforce_protocol("https","");
 
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "excelexport.php";
  $MM_redirectLoginFailed = "index.php?error=1";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_localhost, $localhost);
  
  $LoginRS__query=sprintf("SELECT username, password FROM `access` WHERE username=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $localhost) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HP Spectre Lounge</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if(isset($_GET['error'])) { ?><div class="error">Wrong password please try again</div><?php } ?>
<form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
  <table width="600" border="0" style="margin-left:auto;margin-right:auto;">
    <tr>
      <td colspan="2"><strong>Please type in your credentials</strong></td>
    </tr>
    <tr>
      <td>Username</td>
      <td><label for="username"></label>
      <input type="text" name="username" id="username" /></td>
    </tr>
    <tr>
      <td>Password</td>
      <td><label for="password"></label>
      <input type="password" name="password" id="password" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="Submit" /></td>
    </tr>
  </table>
</form>
</body>
</html>