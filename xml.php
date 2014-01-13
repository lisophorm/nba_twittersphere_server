<?php require_once('settings.php'); ?><?php
//database configuration
$config['mysql_host'] = "localhost";
$config['mysql_user'] = "tesco";
$config['mysql_pass'] = "tesco";
$config['db_name']    = "hp_twitter";
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
$sql = "SELECT * FROM tweets where approved=1 and mysqldate < DATE_SUB(now(),INTERVAL $tweetdelay MINUTE) order by mysqldate desc limit ".$totaltweets;

if (!$result = mysql_query($sql))
   die("Query failed.".$sql);
   
if(mysql_num_rows($result)>0)
{
	while ($get_info = mysql_fetch_assoc($result)) {
		$rowset[]= $get_info;
	}
	$rowset=array_reverse($rowset);
	//print_r($rowset);
	//die();
   while($result_array = array_shift($rowset))
   {
      $xml .= "\t<".$config['table_name'].">\r\n";
 
      //loop through each key,value pair in row
	  $date=date("H:i d-m-Y",strtotime($result_array['mysqldate']));
      foreach($result_array as $key => $value)
      {
         //$key holds the table column name
         $xml .= "\t\t<$key>";
 
 			if($key=="text") {
				$value = preg_replace('/#[^\s]*/i', '\1<font face=\"HP Simplified\"color=\"#009BDB\"><b>$0</b></font>', $value);
				
				$value = preg_replace('/@[^\s]*/i', '\1<font color=\"#009BDB\">$0</font>', $value);
				
				$value = preg_replace('/http:\/\/[^\s]*/i', '\1<font color=\"#009BDB\">$0</font>', $value);
			} else if ($key=="from_user") {
				$value="@".$value;
			} else if ($key=="mysqldate") {
				$value=$date;
			} else {
			} {
			}
         //embed the SQL data in a CDATA element to avoid XML entity issues
         $xml .= "<![CDATA[".trim(stripslashes($value))."]]>"; 
 
         //and close the element
         $xml .= "</$key>\r\n";
      }
 
      $xml.="\t</".$config['table_name'].">\r\n";
   }
}

//close the root element
$xml .= "</$root_element>\r\n";
 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//send the xml header to the browser
header ("Content-Type:text/xml"); 
 
//output the XML data
echo trim($xml);
?>