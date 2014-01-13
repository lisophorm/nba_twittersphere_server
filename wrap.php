<?php

function splitwords($string) {
$max_length = 15;
$separate_words = explode(" ", $string);

for($i = 0; $i < count($separate_words); $i++)
{
     if( strlen($separate_words[$i]) > $max_length )
     {
		 echo "cazzo - ".$separate_words[$i];
         $separate_words[$i] = wordwrap($separate_words[$i], $max_length,"-",1);
     }
}

$string = implode(" ", $separate_words); 
return $string;
}

echo splitwords(" la mariagiovanna se lo va a prendere nella capagnolacarazaxxola e gino tronico va al mare");

?>