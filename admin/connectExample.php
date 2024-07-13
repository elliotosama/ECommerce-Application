<?php
	$server = 'yourServer';
	$username = 'yourUserName';
	$password = 'yourPassword';
	$db = 'shop';


	$conn = new mysqli($server, $username, $password, $db);

	if($conn->connect_error) {
		die("something went wrong");
	}
