<?php
require_once '../../includes/session.php';
if (!is_logged_in()) {
	header('Location: ../../login.php');
	exit;
}
if (!is_admin()) {
	header('Location: ../../');
	exit;
}

require_once '../../includes/connect.php';

try {
	//test if participant already exists
	$sql = "SELECT `name` FROM `" . $_POST['property'] . "_roster` WHERE `name`=:name AND `date_of_birth`=:dob";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
	$stmt->bindParam(":dob", $_POST['date_of_birth'], PDO::PARAM_STR);
	$stmt->execute();
	$results = $stmt->fetchAll();
	$count = count($results);
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error creating participant:" . $e->getMessage() . PHP_EOL;
}

//if participant already exists, ask user to submit form again
// MAYBE ALLOW MANUAL OVERRIDE?????????
if ($count > 0) {
	session_start();
	$_SESSION['participant'] = array('name' => $_POST['name'], 'nickname' => $_POST['nickname'], 'date_of_birth' => $_POST['date_of_birth'], 'address1' => $_POST['address1'], 'address2' => $_POST['address2'], 'city' => $_POST['city'], 'state' => $_POST['state'], 'zip' => $_POST['zip']);
	// $_SESSION['participant_name'] = $_POST['name'];
	// $_SESSION['nickname'] = $_POST['nickname'];
	// $_SESSION['date_of_birth'] = $_POST['date_of_birth'];
	// $_SESSION['address1'] = $_POST['address1'];
	// $_SESSION['address2'] = $_POST['address2'];
	// $_SESSION['city'] = $_POST['city'];
	// $_SESSION['state'] = $_POST['state'];
	// $_SESSION['zip'] = $_POST['zip'];
	header('Location: add_participant.php?property=' . $_POST['property'] . '&week=' . $_POST['week']);
	echo '<p style="color:red">Sorry, a participant with the name ' . $_POST['name'] . ' and date of birth ' . $_POST['date_of_birth'] . ' already exists. Please try again.</p>' . PHP_EOL;
	exit;
}


try {
	$sql = "INSERT INTO `" . $_POST['property'] . "_roster` (`name`, `nickname`, `date_of_birth`, `address1`, `address2`, `city`, `state`, `zip`) VALUES (:name, :nickname, :dob, :address1, :address2, :city, :state, :zip)";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
	$stmt->bindParam(":nickname", $_POST['nickname'], PDO::PARAM_STR);
	$stmt->bindParam(":dob", $_POST['date_of_birth'], PDO::PARAM_STR);
	$stmt->bindParam(":address1", $_POST['address1'], PDO::PARAM_STR);
	$stmt->bindParam(":address2", $_POST['address2'], PDO::PARAM_STR);
	$stmt->bindParam(":city", $_POST['city'], PDO::PARAM_STR);
	$stmt->bindParam(":state", $_POST['state'], PDO::PARAM_STR);
	$stmt->bindParam(":zip", $_POST['zip'], PDO::PARAM_INT);
	$stmt->execute();
	header('Location: ./?property=' . $_POST['property'] . '&week=' . $_POST['week']);
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error creating participant:" . $e->getMessage() . PHP_EOL;
}
