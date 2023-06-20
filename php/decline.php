<?php
	// must match  141.76.100.128 - 255
	$addr = $_SERVER['REMOTE_ADDR'];
	
	//löschen, löschen und löschen
	
	echo preg_match('/^(?:141\.76\.100\.)[1|2]{1}[0-9]{2}$/',$addr,$result).'<br />';
	foreach ($result as $item)
		echo $item.'<br />';
	
	if(preg_match('/^(?:141\.76\.100\.)[1|2]{1}[0-9]{2}$/',$addr,$result) === 1){
		for($i=1;$i <= 4; $i++){
			$file = 'file'.$i;
			if(array_key_exists($file, $_GET) == FALSE)
				break;
		
			unlink('/var/lib/kpp/unverified/'.$_GET[$file]);
		}
    } else {
    	echo 'wrong IP range';
    }
	
?>
