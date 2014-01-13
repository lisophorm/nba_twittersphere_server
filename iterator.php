<?php

function dirsize($dir) {
    if(is_file($dir)) return array('size'=>filesize($dir),'howmany'=>0);
    if($dh=opendir($dir)) {
        $size=0;
        $n = 0;
        while(($file=readdir($dh))!==false) {
            if($file=='.' || $file=='..') continue;
            $n++;
            $data = dirsize($dir.'/'.$file);
            $size += $data['size'];
            $n += $data['howmany'];
        }
        closedir($dh);
        return array('size'=>$size,'howmany'=>$n);
    } 
    return array('size'=>0,'howmany'=>0);
}
 dirsize("./");
?>