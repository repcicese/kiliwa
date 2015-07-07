<?php
session_start();

	require "inc/ciceserep.class.php";
	$edg = new CICESEREP();
	$edg -> ejecutar();

?>