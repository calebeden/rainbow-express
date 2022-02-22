<?php
// Generate the database for the first time by going to /generate.php?dbname=ABCD where ABCD is the desired database name (should be rainbow_express)

date_default_timezone_set("America/Chicago");

$server = "localhost";
$dbname=$_GET['dbname'];
$username = "MrCraftable";
$password = "P4st4778";
try {
	//Create a PDO object with host, dbname, username, and password
	$conn = new PDO("mysql:host=$server;", $username, $password);
	//Changing the presentation of errors to exceptions using the setAttribute() method of the PDO object.
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//Changing the default format of returning rows as an associative array
	$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	//echo "Connected to database succesfully";
} catch (PDOException $e) {
	echo "Connection to server failed: " . $e->getMessage();
	exit;
}

try {
	$sql = "CREATE DATABASE `$dbname` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error creating database:" . $e->getMessage() . PHP_EOL;
	exit;
}

try {
	//Create a PDO object with host, dbname, username, and password
	$conn = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);
	//Changing the presentation of errors to exceptions using the setAttribute() method of the PDO object.
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//Changing the default format of returning rows as an associative array
	$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	//echo "Connected to database succesfully";
} catch (PDOException $e) {
	echo "Connection to server failed: " . $e->getMessage();
	exit;
}

try {
	$sql = "CREATE TABLE `users` (
		`id` int NOT NULL AUTO_INCREMENT,
		`name` varchar(255) DEFAULT NULL,
		`username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
		`password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
		`permissions` int NOT NULL DEFAULT '1',
		`properties` json DEFAULT NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `username` (`username`)
		) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error creating users table:" . $e->getMessage() . PHP_EOL;
	exit;
}

try {
	$sql = "CREATE TABLE `properties` (
		`id` int NOT NULL AUTO_INCREMENT,
		`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
		`street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
		`city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
		`state` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
		`zip` int NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error creating properties table:" . $e->getMessage() . PHP_EOL;
	exit;
}

echo "Success! <a href='./'>Go home</a>" . PHP_EOL;