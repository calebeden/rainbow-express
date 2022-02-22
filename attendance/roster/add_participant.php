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
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="Your one-stop shop for all things Overhang" />
	<meta name="author" content="Caleb Eden" />
	<base href="../../">
	</base>
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · Add Account</title>
</head>

<body>
	<?php include '../../common/navbar.php' ?>
	<main role="main">
		<?php
		if (isset($_SESSION['participant'])) {
			$name = $_SESSION['participant']['name'];
			$nickname = $_SESSION['participant']['nickname'];
			$date_of_birth = $_SESSION['participant']['date_of_birth'];
			$address1 = $_SESSION['participant']['address1'];
			$address2 = $_SESSION['participant']['address2'];
			$city = $_SESSION['participant']['city'];
			$state = $_SESSION['participant']['state'];
			$zip = $_SESSION['participant']['zip'];
			unset($_SESSION['participant']);
		} else {
			$state = 'TX';
		}
		?>

		<div class="container card">
			<form class="form-signin" action="attendance/roster/new_participant_sql.php" method="post" enctype="multipart/form-data" autocomplete="off">
				<input name="property" value="<?php echo $_GET['property'] ?>" hidden />
				<input name="week" value="<?php echo $_GET['week'] ?>" hidden />
				<h1 class="h3 mb-3 font-weight-normal">Add Participant:</h1>
				<?php
				if (isset($name)) {
					echo '<p style="color:red">Sorry, a participant with the name ' . $name . ' and date of birth ' . $date_of_birth . ' already exists. Please try again.</p>' . PHP_EOL;
				}
				?>
				<fieldset class="mb-3">
					<legend><label for="name">Name</label></legend>
					<input type="text" name="name" id="name" class="form-control" placeholder="Full Name" required <?php if (isset($name)) echo "value='" . $name . "'" ?> />
					<legend><label for="nickname">Nickname</label></legend>
					<input type="text" name="nickname" id="nickname" class="form-control" placeholder="Nickname (goes by)" required <?php if (isset($nickname)) echo "value='" . $nickname . "'" ?> />
					<legend><label for="date_of_birth">Date of Birth</label></legend>
					<p>
						<input type="date" name="date_of_birth" id="date_of_birth" min="1900-01-01" max="<?php echo date("Y\-m\-d") ?>" required <?php if (isset($date_of_birth)) echo "value='" . $date_of_birth . "'" ?> />
						<?php echo "(" . date_diff(date_create("1900-01-01"), date_create(date("Y-m-d")))->format("%Y") . " years old)" ?>
					</p>
				</fieldset>

				<fieldset class="mb-3">
					<legend>Address</legend>
					<div class="form-group">
						<fieldset class="mb-3">
							<input name="address1" class="form-control" placeholder="Address Line 1" <?php if (isset($address1)) echo "value='" . $address1 . "'" ?> />
						</fieldset>
						<fieldset class="mb-3">
							<input name="address2" class="form-control" placeholder="Address Line 2" <?php if (isset($address2)) echo "value='" . $address2 . "'" ?> />
						</fieldset>
						<fieldset class="mb-3">
							<input name="city" class="form-control" placeholder="City" <?php if (isset($city)) echo "value='" . $city . "'" ?> />
						</fieldset>
						<fieldset class="mb-3">
							<?php
							$priority = $state;
							require '../../includes/select_state.php';
							?>
						</fieldset>
						<fieldset class="mb-3">
							<input name="zip" class="form-control" placeholder="Zip" <?php if (isset($zip)) echo "value='" . $zip . "'" ?> />
							<!--<input type="country" class="form-control" placeholder="Country" />-->
						</fieldset>
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
	<?php include '../../common/footer.php' ?>
</body>

</html>