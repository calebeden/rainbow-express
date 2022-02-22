<?php
require_once 'includes/session.php';
if (is_logged_in()) {
	header('Location: ./');
	exit;
}
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Login page for Rainbow Express" />
	<meta name="author" content="Caleb Eden" />
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · Sign-In</title>
</head>

<body> <!-- style="background-color: #f5f5f5"> -->
	<?php include 'common/navbar.php' ?>
	<main role="main">
		<div class="row mx-3">
			<div class="col"></div>
			<div class="col-xxl-4 col-xl-4 col-lg-6 col-md-8 col-sm-8 col-xs-4">
				<form class="form-signin" action="includes/authenticate.php" method="post" role="form">
					<img class="mb-4 rounded" src="images/missionarlington.jpg" alt="Mission Arlington | Mission Metroplex: Taking church to the people" width="100%" height="" />
					<h1 class="h3 mb-3 fw-normal">Login</h1>
					<?php
					if (isset($_SESSION['login_error']) && $_SESSION['login_error'] == TRUE) {
						echo "<p>Incorrect username or password.</p>";
						unset($_SESSION['login_error']);
					}
					?>
					<div class="mb-2"> <!-- class="form-floating"> BOOTSTRAP 5 FLOATING LABELS NOT WORKING PROPERLY-->
						<input type="text" class="form-control" id="username" placeholder="Username" required autofocus name="username" autocomplete aria-label="Username">
						<!-- <label for="username" class="sr-only aria-label">Username</label> -->
					</div>
					<div class="mb-2"> <!-- class="form-floating"> BOOTSTRAP 5 FLOATING LABELS NOT WORKING PROPERLY-->
						<input type="password" class="form-control" id="password" placeholder="Password" required name="password" autocomplete aria-label="Password">
						<!-- <label for="password" class="sr-only aria-label">Password</label> -->
					</div>

					<div class="checkbox mb-3">
						<label>
							<input type="checkbox" value="remember-me"> Remember me
						</label>
					</div>
					<button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
				</form>
			</div>
			<div class="col"></div>
		</div>
	</main>
	<?php include 'common/footer.php' ?>

</body>

</html>