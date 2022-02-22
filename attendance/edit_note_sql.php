<?php
require_once '../includes/session.php';
if (!is_logged_in()) {
	header('Location: ../login.php');
	exit;
}

require_once '../includes/connect.php';

if (isset($_GET['note']) && isset($_GET['property']) && isset($_GET['week']) && isset($_GET['participant'])) {
	try {
		// get previous value
		$sql = "SELECT * FROM `" . $_GET['property'] . "_attendance_" . date("Y") . "` WHERE `id`=:week LIMIT 1";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(":week", $_GET['week'], PDO::PARAM_INT);
		$stmt->execute();
		$results = $stmt->fetch();
		$notes = json_decode($results['notes'], true);
		echo $results['notes'];
	} catch (Exception $e) {
		echo $sql . PHP_EOL;
		echo "Error finding previous notes:" . $e->getMessage() . PHP_EOL;
	}

	if ($_GET['note'] == null) {
		unset($notes[$_GET['participant']]);
	} else {
		$notes[$_GET['participant']] = $_GET['note'];
	}

	try {
		$sql = "UPDATE `" . $_GET['property'] . "_attendance_" . date("Y") . "` SET `notes`=:notes WHERE `id`=:id";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(":notes", json_encode($notes), PDO::PARAM_STR);
		$stmt->bindParam(":id", $_GET['week'], PDO::PARAM_INT);
		$stmt->execute();
	} catch (Exception $e) {
		echo $sql . PHP_EOL;
		echo "Error updating notes:" . $e->getMessage() . PHP_EOL;
	}
}
