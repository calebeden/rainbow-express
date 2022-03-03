<?php
require_once '../includes/session.php';
if (!is_logged_in()) {
	header('Location: ../login.php');
	exit;
}

require_once '../includes/connect.php';

if (isset($_GET['time']) && isset($_GET['property']) && isset($_GET['week'])) {
	try {
		$attendance_table = attendance_table($_GET['property'], date("Y"));
		$sql = "UPDATE `$attendance_table` SET `time`=:time WHERE `id`=:id";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(":time", $_GET['time'], PDO::PARAM_STR);
		$stmt->bindParam(":id", $_GET['week'], PDO::PARAM_INT);
		$stmt->execute();
	} catch (Exception $e) {
		echo $sql . PHP_EOL;
		echo "Error changing time:" . $e->getMessage() . PHP_EOL;
	}
}
