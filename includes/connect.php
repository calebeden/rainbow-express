<?php
date_default_timezone_set("America/Chicago");

$database_info = parse_ini_file("php.ini");

$users_table = "rainbow_express_users";
$properties_table = "rainbow_express_properties";
$dates_table = "rainbow_express_dates";

try {
	//Create a PDO object with host, dbname, username, and password
	$conn = new PDO("mysql:host=$database_info[server];dbname=$database_info[dbname];", $database_info['username'], $database_info['password']);
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
	return "rainbow_express_{$property_id}_roster";
}

function attendance_table($property_id, $year)
{
	// format should look like 1_attendance_2022
	return "rainbow_express_{$property_id}_attendance_{$year}";
}