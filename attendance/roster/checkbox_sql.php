<?php
require_once '../../includes/session.php';
if (!is_logged_in()) {
	header('Location: ../../login.php');
	exit;
}

require_once '../../includes/connect.php';

if (isset($_GET['property']) && isset($_GET['row']) && isset($_GET['week'])) {
	try {
		// get previous value
		$sql = "SELECT `participants` FROM `" . $_GET['property'] . "_attendance_" . date("Y") . "` WHERE `id`=:id LIMIT 1";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(":id", $_GET['week'], PDO::PARAM_INT);
		$stmt->execute();
		$results = $stmt->fetch();
		$previous_participants = json_decode($results['participants']);
	} catch (Exception $e) {
		echo $sql . PHP_EOL;
		echo "Error finding previous participants:" . $e->getMessage() . PHP_EOL;
	}

	if (in_array($_GET['row'], $previous_participants)) {
		$key = array_search($_GET['row'], $previous_participants);
		$new_participants = $previous_participants;
		array_splice($new_participants, $key, 1);

		try {
			$sql = "UPDATE `" . $_GET['property'] . "_attendance_" . date("Y") . "` SET `participants`=:participants WHERE `id`=:id";
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(":participants", json_encode($new_participants), PDO::PARAM_STR);
			$stmt->bindParam(":id", $_GET['week'], PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e) {
			echo $sql . PHP_EOL;
			echo "Error updating attendance:" . $e->getMessage() . PHP_EOL;
		}
	} else {
		$new_participants = $previous_participants;
		$new_participants[] = (int)$_GET['row'];

		try {
			$sql = "UPDATE `" . $_GET['property'] . "_attendance_" . date("Y") . "` SET `participants`=:participants WHERE `id`=:id";
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(":participants", json_encode($new_participants), PDO::PARAM_STR);
			$stmt->bindParam(":id", $_GET['week'], PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e) {
			echo $sql . PHP_EOL;
			echo "Error updating attendance:" . $e->getMessage() . PHP_EOL;
		}
	}
}