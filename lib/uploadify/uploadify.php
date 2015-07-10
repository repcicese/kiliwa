<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '/kiliwa/tmp/files'; // Relative to the root

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_POST['timestamp'] .'_'. $_FILES['Filedata']['name'];
	
	// Validate the file type
	//$fileTypes = array('jpg','jpeg','gif','png','pdf'); // File extensions
	//$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if ($_POST['size'] == $_FILES['Filedata']['size']) {
		move_uploaded_file($tempFile,$targetFile);
	}
}
?>