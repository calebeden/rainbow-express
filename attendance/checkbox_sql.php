<?php
require_once '../includes/session.php';
if (!is_logged_in()) {
	header('Location: ../login.php');
	exit;
}

require_once '../includes/connect.php';

if (isset($_GET['property']) && isset($_GET['row']) && isset($_GET['column']) && isset($_GET['week'])) {
	// for ($day = 1; $day <= 4; $day++) {
	// 	if ($_GET['column'] == 'day' . $day) {
	// 		toggle_attendance('day' . $day, $_GET['week'], $_GET['property'], $conn);
	// 	}
	// }
	// if ($_GET['column'] == 'salvation') {
	// 	toggle_attendance('salvations', $_GET['week'], $_GET['property'], $conn);
	// }
	foreach (array("day1", "day2", "day3", "day4", "salvation") as $column){
		if ($_GET['column'] == $column) {
			toggle_attendance($column, $_GET['week'], $_GET['property'], $conn);
		}
	}
	// }
	// if ($_GET['column'] == 'salvation') {
	// 	toggle_attendance('salvations', $_GET['week'], $_GET['property'], $conn);
	// }
}

function toggle_attendance($column, $week_id, $property, $conn)
{
	$attendance_table = attendance_table($property, date("Y"));
	try {
		// get previous value
		$sql = "SELECT * FROM `$attendance_table` WHERE `id`=:id LIMIT 1";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(":id", $week_id, PDO::PARAM_INT);
		$stmt->execute();
		$results = $stmt->fetch();
		$previous_attendance = json_decode($results[$column]);
	} catch (Exception $e) {
		echo $sql . PHP_EOL;
		echo "Error finding previous attendance:" . $e->getMessage() . PHP_EOL;
	}

	if (in_array($_GET['row'], $previous_attendance)) {
		$key = array_search($_GET['row'], $previous_attendance);
		$new_attendance = $previous_attendance;
		array_splice($new_attendance, $key, 1);

		try {
			$sql = "UPDATE `$attendance_table` SET `" . $column . "`=:attendance WHERE `id`=:id";
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(":attendance", json_encode($new_attendance), PDO::PARAM_STR);
			$stmt->bindParam(":id", $week_id, PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e) {
			echo $sql . PHP_EOL;
			echo "Error updating attendance:" . $e->getMessage() . PHP_EOL;
		}
	} else {
		$new_attendance = $previous_attendance;
		$new_attendance[] = (int)$_GET['row'];

		try {
			$sql = "UPDATE `$attendance_table` SET `" . $column . "`=:attendance WHERE `id`=:id";
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(":attendance", json_encode($new_attendance), PDO::PARAM_STR);
			$stmt->bindParam(":id", $week_id, PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e) {
			echo $sql . PHP_EOL;
			echo "Error updating attendance:" . $e->getMessage() . PHP_EOL;
		}
	}
}
