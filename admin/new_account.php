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
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="Your one-stop shop for all things Overhang" />
	<meta name="author" content="Caleb Eden" />
	<base href="../"></base>
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · Add Account</title>
</head>

<body>
	<?php include '../common/navbar.php' ?>
	<main role="main">
		<?php
		$permissions = 0; // unless previous invalid form states otherwise
		if (isset($_SESSION['existing_account'])) {
			$username = $_SESSION['existing_account']['username'];
			$permissions = $_SESSION['existing_account']['permissions'];
			$name = $_SESSION['existing_account']['name'];
			unset($_SESSION['existing_account']);
		}
		?>

		<div class="container card">
			<form class="form-signin" action="admin/new_account_sql.php" method="post" enctype="multipart/form-data" autocomplete="off">
				<h1 class="h3 mb-3 font-weight-normal">Add Account:</h1>

				<fieldset class="mb-3">
					<legend><label for="name" class="sr-only">Name</label></legend>
					<input type="text" name="name" id="name" class="form-control" placeholder="Person's Full Name or Group Name" required <?php if (isset($name)) echo "value='" . $name . "'" ?> />
				</fieldset>

				<fieldset class="mb-3">
					<legend><label for="username" class="sr-only">Username</label></legend>
					<?php if (isset($username)) echo '<p style="color:red">Sorry, the username ' . $username . ' is taken. Please try again.</p>' . PHP_EOL ?>
					<input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus />
				</fieldset>
				<fieldset class="mb-3">
					<legend><label for="password" class="sr-only">Password</label></legend>
					<?php $random_password = random_int(111111, 999999); ?>
					<input type="test" name="password" id="password" class="form-control" placeholder="Password" required value="<?php echo $random_password ?>" disabled />
					<input type="hidden" name="password" id="password" class="form-control" placeholder="Password" required value="<?php echo $random_password ?>" />
					<p>One-time use password for first login</p>
				</fieldset>

				<!-- <fieldset class="mb-3">
					<legend>Permissions</legend>
					<div class="form-check">
						<input type="radio" name="permissions" class="form-check-input" id="permissions_1" value=1 <?php if ($permissions == 1) echo 'checked' ?> />
						<label class="form-check-label" for="permissions_1">Level 1 - Manage attendance at resticted sites</label>
					</div>
					<div class=" form-check">
						<input type="radio" name="permissions" class="form-check-input" id="permissions_2" value=2 <?php if ($permissions == 2) echo 'checked' ?> />
						<label class="form-check-label" for="permissions_2">Level 2 - Manage all sites</label>
					</div>
					<div class="form-check">
						<input type="radio" name="permissions" class="form-check-input" id="permissions_3" value=3 <?php if ($permissions == 3) echo 'checked' ?> />
						<label class="form-check-label" for="permissions_3">Level 3 - Admin account (manage all sites, add/remove/edit users)</label>
					</div>
				</fieldset> -->
				<fieldset class="mb-3">
					<legend>Permissions</legend>
					<div class="form-check">
						<input type="radio" name="permissions" class="form-check-input" id="permissions_0" value=0 <?php if ($permissions == 0) echo 'checked' ?> />
						<label class="form-check-label" for="permissions_0">Attendance Taker</label>
					</div>
					<div class=" form-check">
						<input type="radio" name="permissions" class="form-check-input" id="permissions_1" value=0 <?php if ($permissions == 1) echo 'checked' ?> />
						<label class="form-check-label" for="permissions_1">Admin</label>
					</div>
				</fieldset>

				<fieldset>
					<div class="mb-3">
						<button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
					</div>
				</fieldset>

			</form>
		</div>
	</main>
	<?php include '../common/footer.php' ?>
</body>

</html>