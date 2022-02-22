<?php
require_once '../includes/session.php';
if (!is_logged_in()) {
	header('Location: ../login.php');
	exit;
}
if (!is_admin()) {
	header('Location: ../');
	exit;
}

require_once '../includes/connect.php';

$sql = "UPDATE `users` SET `permissions`=:permissions WHERE `users`.`username`=:username";
$stmt = $conn->prepare($sql);
try {
	$stmt->bindParam(":permissions", $_POST['permissions'], PDO::PARAM_INT);
	$stmt->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
	$stmt->execute();
	header('Location: ../');
	echo "User edited successfully!" . PHP_EOL;
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error editing user:" . $e->getMessage() . PHP_EOL;
}
