<?php
require_once 'includes/session.php';
if (!is_logged_in()) {
	header('Location: login.php');
	exit;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="Change Rainbow Express Password" />
	<meta name="author" content="Caleb Eden" />
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · Change Password</title>
</head>

<body>
	<?php include 'common/navbar.php' ?>
	<main role="main">
		<?php
		$password_error = 0; // unless previous invalid form states otherwise
		if (isset($_SESSION['password_error'])) {
			$password_error = $_SESSION['password_error'];
			unset($_SESSION['password_error']);
		}
		?>

		<div class="container card">
			<form class="form-signin" action="change_password_sql.php" method="post" enctype="multipart/form-data" autocomplete="off">
				<h1 class="h3 mb-3 font-weight-normal">Change Password:</h1>
				<?php
				if (isset($_SESSION['plaintext_password']) && $_SESSION['plaintext_password'] == true) {
					echo "It appears as though your password is currently stored as plaintext. Please change it immediately." . PHP_EOL;
				}
				?>
				<input type="hidden" name="username" id="username" class="form-control" required value="<?php echo $_SESSION['user']['username'] ?>" />
				<fieldset class="mb-3">
					<legend><label for="current_password" class="sr-only">Current Password</label></legend>
					<input type="password" name="current_password" id="current_password" class="form-control" placeholder="Current Password" required />
				</fieldset>
				<?php if ($password_error == 2) echo '<p style="color:red">Incorrect password. Please try again.</p>' . PHP_EOL ?>
				<fieldset class="mb-3">
					<legend><label for="new_password" class="sr-only">New Password</label></legend>
					<input type="password" name="new_password" id="password" class="form-control" placeholder="New Password" autocomplete="new-password" required />
				</fieldset>
				<fieldset class="mb-3">
					<legend><label for="confirm_password" class="sr-only">Confirm New Password</label></legend>
					<input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm New Password" autocomplete="new-password" required />
				</fieldset>
				<?php if ($password_error == 1) echo '<p style="color:red">Passwords do not match. Please try again.</p>' . PHP_EOL ?>

				<fieldset>
					<div class="mb-3">
						<button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
					</div>
				</fieldset>

			</form>
		</div>
	</main>
	<?php include 'common/footer.php' ?>
</body>

</html>