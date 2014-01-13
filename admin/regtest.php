<form id="form1" name="form1" method="post" action="">
  <p>
    <label for="shit"></label>
  Please type in the words to test</p>
  <p>
    <textarea name="shit" cols="70" rows="5" id="shit"></textarea>
    <input type="submit" name="button" id="button" value="Submit" />
  </p>
</form>
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
			$lowerword=strtolower($dictionary[$a]);
			$resulto=preg_match($lowerword,$current);
			$dis=levenshtein($dictionary[$a],$current);
			echo "checking ".$current." against ".$dictionary[$a]." regexp:".$resulto." dinst".$dis."<br/>";
			if($resulto==1 || $dis<=2) {
				return false;
			}
			
		}

	}
	return $clean;
}
if(isset($_POST['button'])) {
$reso=moderate($_POST['shit'],"badwords.txt");
	if($reso) {
		echo "GOOD";
	} else {
		echo "BAD";
}
}

?>