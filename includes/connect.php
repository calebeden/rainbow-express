<?php
date_default_timezone_set("America/Chicago");

$server = "localhost";
$dbname = "f4a02523964785"; // Staging database
// $dbname = "c5802517713232"; // Production database
$username = "MrCraftable";
$password = "P4st4778";

$users_table = "rainbow_express_users";
$properties_table = "rainbow_express_properties";
$dates_table = "rainbow_express_dates";

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

function roster_table($property_id, $year)
{
	//format should look like 1_roster_2022
	// return $property_id . "_roster_" . $year;
	return $property_id . "_roster";
}

function attendance_table($property_id, $year)
{
	// format should look like 1_attendance_2022
	return $property_id . "_attendance_" . $year;
}