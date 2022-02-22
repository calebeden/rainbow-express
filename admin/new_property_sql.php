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

try {
	//test if property already exists
	$sql = "SELECT `name` FROM `properties` WHERE `name`=:name";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(":name", $_POST['property_name'], PDO::PARAM_STR);
	$stmt->execute();
	$results = $stmt->fetchAll();
	$count = count($results);
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error creating property:" . $e->getMessage() . PHP_EOL;
}

//if property name already exists, ask user to submit form again
if ($count > 0) {
	session_start();
	$_SESSION['property'] = array('name' => $_POST['property_name'], 'street' => $_POST['street'], 'city' => $_POST['city'], 'state' => $_POST['state'], 'zip' => $_POST['zip']);
	// $_SESSION['property_name'] = $_POST['property_name'];
	// $_SESSION['property_street'] = $_POST['street'];
	// $_SESSION['property_city'] = $_POST['city'];
	// $_SESSION['property_state'] = $_POST['state'];
	// $_SESSION['property_zip'] = $_POST['zip'];
	header('Location: new_property.php');
	echo '<p style="color:red">Sorry, a property with the name ' . $_POST['property_name'] . ' already exists (' . $_POST['city'] . ', ' . $_POST['state'] . '). Please try again.</p>' . PHP_EOL;
	exit;
}


try {
	$sql = "INSERT INTO `properties` (`name`, `street`, `city`, `state`, `zip`) VALUES (:name, :street, :city, :state, :zip)";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(":name", $_POST['property_name'], PDO::PARAM_STR);
	$stmt->bindParam(":street", $_POST['street'], PDO::PARAM_STR);
	$stmt->bindParam(":city", $_POST['city'], PDO::PARAM_STR);
	$stmt->bindParam(":state", $_POST['state'], PDO::PARAM_STR);
	$stmt->bindParam(":zip", $_POST['zip'], PDO::PARAM_INT);
	$stmt->execute();
	header('Location: ../');
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error creating property:" . $e->getMessage() . PHP_EOL;
}
