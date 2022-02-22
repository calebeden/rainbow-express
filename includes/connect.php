<?php
date_default_timezone_set("America/Chicago");

$server = "localhost";
$dbname = "rainbow_express";
$username = "MrCraftable";
$password = "P4st4778";
try {
	//Create a PDO object with host, dbname, username, and password
	$conn = new PDO("mysql:host=$server;dbname=$dbname;", $username, $password);
	//Changing the presentation of errors to exceptions using the setAttribute() method of the PDO object.
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//Changing the default format of returning rows as an associative array
	$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	//echo "Connected to database succesfully";
} catch (PDOException $e) {
	echo "Connection to database failed: " . $e->getMessage();
}
