<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//send the xml header to the browser
header ("Content-Type:text/xml"); 
 
//output the XML data
echo "PONG!";
?>