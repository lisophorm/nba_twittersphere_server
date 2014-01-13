<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_rich = "localhost";
$database_rich = "rich_wilson";
$username_rich = "tesco";
$password_rich = "tesco";
$rich = mysql_pconnect($hostname_rich, $username_rich, $password_rich) or trigger_error(mysql_error(),E_USER_ERROR); 
?>