<?php

	$f = unserialize(file_get_contents('serialize'));
	
	unset($f[0]);
	unset($f[1]);
	unset($f[2]);
	unset($f[3]);
	unset($f[4]);
	
	print_r($f);
	
	$c = fopen('serialize', 'w');
	fwrite($c, serialize($f));
	fclose($c);


?>