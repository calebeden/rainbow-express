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
	//test if username is taken first
	$sql = "SELECT `username` FROM `$users_table` WHERE `username`=:username";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
	$stmt->execute();
	$results = $stmt->fetchAll();
	$count = count($results);
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error creating user:" . $e->getMessage() . PHP_EOL;
}

//if username already exists, ask user to submit form again
if ($count > 0) {
	session_start();
	$_SESSION['existing_account'] = array('username' => $_POST['username'], 'permissions' => $_POST['permissions'], 'name' => $_POST['name']);
	// $_SESSION['new_username'] = $_POST['username'];
	// $_SESSION['new_permissions'] = $_POST['permissions'];
	// $_SESSION['new_name'] = $_POST['name'];
	header('Location: new_account.php');
	echo "Error: username " . $_POST['username'] . " already exists. Please try again." . PHP_EOL;
	exit;
}

//the password_hash function will use the strongest algorithm at the time of this php version releasing as well as automatically salt the hash
$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

try {
	$sql = "INSERT INTO `$users_table` (`username`, `password`, `permissions`, `name`) VALUES (:username, :password, :permissions, :name)";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
	$stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
	$stmt->bindParam(":permissions", $_POST['permissions'], PDO::PARAM_INT);
	$stmt->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
	$stmt->execute();

	$_SESSION['created_user'] = array('username' => $_POST['username'], 'password' => $_POST['password']);

	header('Location: ../index.php');
	echo "User created successfully!" . PHP_EOL;
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error creating user:" . $e->getMessage() . PHP_EOL;
}
