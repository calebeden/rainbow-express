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
	<meta name="description" content="Administrative page to add a new Rainbow Express property" />
	<meta name="author" content="Caleb Eden" />
	<base href="../"></base>
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · Add Property</title>
</head>

<body>
	<?php include '../common/navbar.php' ?>
	<main role="main">
		<?php
		if (isset($_SESSION['property'])) {
			$name = $_SESSION['property']['name'];
			$street = $_SESSION['property']['street'];
			$city = $_SESSION['property']['city'];
			$state = $_SESSION['property']['state'];
			$zip = $_SESSION['property']['zip'];
			unset($_SESSION['property']);
		} else {
			$state = 'TX';
		}
		?>

		<div class="container card">
			<form class="form-signin" action="admin/new_property_sql.php" method="post" enctype="multipart/form-data" autocomplete="off">
				<h1 class="h3 mb-3 font-weight-normal">Add Property:</h1>

				<fieldset class="mb-3">
					<legend><label for="property_name" class="sr-only">Property Name</label></legend>
					<?php
					if (isset($name)) {
						echo '<p style="color:red">Sorry, a property with the name ' . $name . ' already exists. Please try again.</p>' . PHP_EOL;
					}
					?>
					<input type="text" name="property_name" id="property_name" class="form-control" placeholder="Property Name" required />
				</fieldset>

				<legend>Address</legend>
				<div class="form-group">
					<fieldset class="mb-3">
						<input name="street" class="form-control" placeholder="Street" <?php if (isset($street)) echo "value='" . $street . "'" ?> />
					</fieldset>
					<fieldset class="mb-3">
						<input name="city" class="form-control" placeholder="City" <?php if (isset($city)) echo "value='" . $city . "'" ?> />
					</fieldset>
					<fieldset class="mb-3">
						<?php
						$priority = $state;
						require '../includes/select_state.php';
						?>
					</fieldset>
					<fieldset class="mb-3">
						<input name="zip" class="form-control" placeholder="Zip" <?php if (isset($zip)) echo "value='" . $zip . "'" ?> />
						<!--<input type="country" class="form-control" placeholder="Country" />-->
					</fieldset>
				</div>
				<fieldset>
					<div class="mb-3">
						<button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
					</div>
				</fieldset>
			</form>
		</div>
	</main>
	<?php include_once '../common/footer.php' ?>
</body>

</html>