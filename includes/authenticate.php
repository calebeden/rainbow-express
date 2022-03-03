<?php
require_once 'session.php';
require_once 'connect.php';

if (!isset($_POST['username'], $_POST['password'])) {
	$_SESSION['login_error'] = TRUE;
	header("Location: ../login.php");
}

try {
	$sql = "SELECT `id`, `password`, `name`, `permissions`, `properties` FROM `$users_table` WHERE `username`=:username";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':username', $_POST['username']);
	$stmt->execute();
	$results = $stmt->fetch();
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error finding user info:" . $e->getMessage() . PHP_EOL;
}

if ($results) {
	// Account exists, now we verify the password.
	if (password_verify($_POST['password'], $results['password'])) {
		session_regenerate_id();
		$_SESSION['user'] = array('username'=> $_POST['username'], 'id'=>$results['id'], 'permissions'=>$results['permissions'], 'name'=>$results['name']);
		$_SESSION['login_error'] = FALSE;

		$property_array = json_decode($results['properties']);
		if ($property_array == null) {
			$property_array = [];
		}
		$_SESSION['properties'] = $property_array;

		//in case php is updated to use a new password hashing algorithm
		try {
			if (password_needs_rehash($results['password'], PASSWORD_DEFAULT)) {
				$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
				$sql = "UPDATE `$users_table` SET `password`=:password WHERE `users`.`id` = " . $id;
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
				$stmt->execute();
				header('Location: ../index.php');
				echo "User logged in successfully!" . PHP_EOL;
			}
		} catch (Exception $e) {
			echo $sql . PHP_EOL;
			echo "Error re-hashing password:" . $e->getMessage() . PHP_EOL;
		}
		header('Location: ../');
		exit;
	} elseif ($_POST['password'] == $results['password']) {
		// This should only really be true when someone goes in the backend to add an account directly in the database and therefore does not have access to password_hash()
		session_regenerate_id();
		$_SESSION['user'] = array('username'=> $_POST['username'], 'id'=>$results['id'], 'permissions'=>$results['permissions'], 'name'=>$results['name']);
		$_SESSION['login_error'] = FALSE;

		$property_array = json_decode($results['properties']);
		if ($property_array == null) {
			$property_array = array();
		}
		$_SESSION['properties'] = $property_array;

		// this password is currently stored as plaintext; CHANGE IMMEDIATELY
		$_SESSION['plaintext_password'] = true;
		header('Location: ../change_password.php');
		exit;
	} else {
		$_SESSION['login_error'] = TRUE;
		header("Location: ../login.php");
		exit;
	}
} else {
	$_SESSION['login_error'] = TRUE;
	header("Location: ../login.php");
	exit;
}
