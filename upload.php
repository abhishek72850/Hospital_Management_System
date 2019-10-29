<?php

	var_dump($_FILES['file']);

	$db=mysqli_connect('localhost','root','','test');

	move_uploaded_file($_FILES['file']['tmp_name'], 'images/'.$_FILES['file']['name']);

	$name=$_FILES['file']['name'];

	$query="INSERT INTO file_upload(name) VALUES('$name')";

	$result=mysqli_query($db,$query);
?>