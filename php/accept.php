<?php
	// must match  141.76.100.128 - 255
	$addr = $_SERVER['REMOTE_ADDR'];
	
	// Files (up to 4), course per file, directory
	// Kopieren aus Ablage in Lehrveranstaltung
	// löschen aus Ablage
	
	echo preg_match('/^(?:141\.76\.100\.)[1|2]{1}[0-9]{2}$/',$addr,$result).'<br />';
	foreach ($result as $item)
		echo $item.'<br />';
	
	if(preg_match('/^(?:141\.76\.100\.)[1|2]{1}[0-9]{2}$/',$addr,$result) === 1){
		for($i=1;$i <= 4; $i++){
			$file = 'file'.$i;
			$dir = 'dir'.$i;
			if(array_key_exists($file, $_GET) == FALSE || array_key_exists($dir, $_GET) == FALSE)
				break;
		
			if(!is_dir('/srv/ftp/komplexpruef/'.$_GET[$dir])){
				mkdir('/srv/ftp/komplexpruef/'.$_GET[$dir]);
			}
		
			copy('./unverified/'.$_GET[$file], '/srv/ftp/komplexpruef/'.$_GET[$dir].'/'.$_GET[$file]);
			unlink('./unverified/'.$_GET[$file]);
		}
	} else {
    	echo 'wrong IP range';
    }
	
?>
