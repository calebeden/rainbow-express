<?php
require_once '../includes/session.php';
if (!is_logged_in()) {
	header('Location: ../login.php');
	exit;
}

require_once '../includes/connect.php';

if (isset($_GET['group']) && isset($_GET['property']) && isset($_GET['week'])) {
	try {
		$attendance_table = attendance_table($_GET['property'], date("Y"));
		$sql = "UPDATE `$attendance_table` SET `church_group`=:church_group WHERE `id`=:id";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(":church_group", $_GET['group'], PDO::PARAM_STR);
		$stmt->bindParam(":id", $_GET['week'], PDO::PARAM_INT);
		$stmt->execute();
	} catch (Exception $e) {
		echo $sql . PHP_EOL;
		echo "Error changing church group:" . $e->getMessage() . PHP_EOL;
	}
}
