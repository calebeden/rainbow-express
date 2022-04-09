<?php
require_once 'includes/session.php';
if (!is_logged_in()) {
	header('Location: login.php');
	exit;
}

require_once 'includes/connect.php';

try {
	$sql = "SELECT `password` FROM `$users_table` WHERE `username`=:username";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':username', $_POST['username']);
	$stmt->execute();
	$results = $stmt->fetch();
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error finding user:" . $e->getMessage() . PHP_EOL;
}

if (password_verify($_POST['current_password'], $results['password'])) {

	if ($_POST['new_password'] == $_POST['confirm_password']) {
		//the password_hash function will use the strongest algorithm at the time of this php version releasing as well as automatically salt the hash
		$hashed_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

		try {
			$sql = "UPDATE `$users_table` SET `password`=:password WHERE `username`=:username";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
			$stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
			$stmt->execute();

			header('Location: index.php');
			echo "Password changed successfully!" . PHP_EOL;
		} catch (Exception $e) {
			echo $sql . PHP_EOL;
			echo "Error changing password:" . $e->getMessage() . PHP_EOL;
		}
	} else {
		session_start();
		$_SESSION['password_error'] = 1;
		header('Location: change_password.php');
		echo "Error: passwords do not match. Please try again." . PHP_EOL;
		exit;
	}
} elseif ($_POST['current_password'] == $results['password']) {
	if ($_POST['new_password'] == $_POST['confirm_password']) {
		$hashed_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
		try {
			$sql = "UPDATE `$users_table` SET `password`=:password WHERE `username`=:username";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
			$stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
			$stmt->execute();
			unset($_SESSION['plaintext_password']);

			header('Location: index.php');
			echo "Password changed successfully!" . PHP_EOL;
		} catch (Exception $e) {
			echo $sql . PHP_EOL;
			echo "Error changing password:" . $e->getMessage() . PHP_EOL;
		}
	} else {
		session_start();
		$_SESSION['password_error'] = 1;
		header('Location: change_password.php');
		echo "Error: passwords do not match. Please try again." . PHP_EOL;
		exit;
	}
} else {
	session_start();
	$_SESSION['password_error'] = 2;
	header('Location: change_password.php');
	echo "Error: incorrect password. Please try again." . PHP_EOL;
	exit;
}
