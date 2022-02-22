<?php
require_once "../includes/connect.php";

try {
	//search for username
	$sql = "SELECT `username`, `permissions`, `name` FROM `users` WHERE `username`=:username";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(":username", $_GET['username'], PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetch();
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error finding user:" . $e->getMessage() . PHP_EOL;
}

if ($result) {
	// echo "Found user " . $row['username'] . ' with permissions ' . $row['permissions'] . PHP_EOL;
	echo json_encode(array('username' => $result['username'], 'permissions' => $result['permissions'], 'name' => $result['name']));
} else {
	echo json_encode(array('username' => null, 'permissions' => null, 'name' => null));
}
