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
	<meta name="description" content="Admin page to edit user account" />
	<meta name="author" content="Caleb Eden" />
	<base href="../">
	</base>
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · Edit Account</title>
</head>

<body>
	<?php include '../common/navbar.php' ?>
	<main role="main">
		<div class="container card">
			<form class="form-signin" action="admin/edit_account_sql.php" method="post" enctype="multipart/form-data" autocomplete="off">
				<?php echo "<h1 class='h3 mb-3 font-weight-normal'>Edit $_GET[username]:</h1>" . PHP_EOL; ?>

				<div id="search_result">
				</div>

				<!-- <fieldset class="mb-3">
					<legend>Permissions</legend>
					<div class="form-check">
						<input type="radio" name="permissions" class="form-check-input" id="permissions_1" value=1 required />
						<label class="form-check-label" for="permissions_1">Level 1 - Manage attendance at specified sites</label>
					</div>
					<div class=" form-check">
						<input type="radio" name="permissions" class="form-check-input" id="permissions_2" value=2 required />
						<label class="form-check-label" for="permissions_2">Level 2 - Manage all sites</label>
					</div>
					<div class="form-check">
						<input type="radio" name="permissions" class="form-check-input" id="permissions_3" value=3 required />
						<label class="form-check-label" for="permissions_3">Level 3 - Admin account (manage all sites, add/remove/edit users)</label>
					</div>
					<small>Slight disclaimer: there currently isn't much of a difference between Levels 1 and 2, may get added in the next update. For assigning users, treat it as though work as described above</small>
				</fieldset> -->
				<fieldset class="mb-3">
					<legend>Account Type</legend>
					<div class="form-check">
						<input type="radio" name="permissions" class="form-check-input" id="permissions_0" value=0 required />
						<label class="form-check-label" for="permissions_0">Attendance Taker</label>
					</div>
					<div class=" form-check">
						<input type="radio" name="permissions" class="form-check-input" id="permissions_1" value=1 required />
						<label class="form-check-label" for="permissions_1">Admin</label>
					</div>
				</fieldset>

				<fieldset>
					<div class="mb-3">
						<button class="btn btn-lg btn-primary btn-block" type="submit" id="form_submit" disabled>Submit</button>
					</div>
				</fieldset>

			</form>
		</div>
	</main>
	<?php include '../common/footer.php' ?>

	<script>
		var typingTimer;
		const doneTypingInterval = 250; //time in ms that the user has to stop typing for
		$('#username').keyup(function() {
			clearTimeout(typingTimer);
			// if ($('#username').val()) {
			typingTimer = setTimeout(findUsername, doneTypingInterval);
			// }
		});

		function findUsername() {
			var username = $("#username").val();
			$.get("admin/username_search.php?username=" + username, function(data) {
				var response = JSON.parse(data);
				var session_username = '<?php echo $_SESSION['user']['username']; ?>';

				found_user_text = "";
				if (response['username'] == null) {
					// Reset the form when username_search.php cannot find a matching username in the database
					$('#permissions_0').prop('disabled', false);
					$('#permissions_0').prop('checked', false);
					$('#permissions_1').prop('disabled', false);
					$('#permissions_1').prop('checked', false);
					$('#permissions_2').prop('disabled', false);
					$('#permissions_2').prop('checked', false);
					$('#permissions_3').prop('disabled', false);
					$('#permissions_3').prop('checked', false);
					$('#form_submit').prop('disabled', true);
				} else {
					// Populate the form with existing database info
					found_user_text += "<p>Editing permissions for " + response['name'] + ":</p>";
					found_user_text += "<p>(Currently Level " + response['permissions'] + ")</p>";
					$('#form_submit').prop('disabled', false);

					switch (response['permissions']) {
						case '0':
							$('#permissions_0').prop('checked', true);
							break;
						case '1':
							$('#permissions_1').prop('checked', true);
							break;
						case '2':
							// don't want to remove "super-admins"
							$('#permissions_0').prop('disabled', true);
							$('#permissions_1').prop('checked', true);
							$('#permissions_1').prop('disabled', true);
							break;
					}
					if (response['username'] == session_username) {
						//Lock user out of form to prevent them from breaking own account
						found_user_text = "<p>You cannot edit your own permissions</p>";
						$('#permissions_0').prop('disabled', true);
						$('#permissions_1').prop('disabled', true);
						$('#permissions_2').prop('disabled', true);
						$('#permissions_3').prop('disabled', true);
						$('#form_submit').prop('disabled', true);
					}
				}
				$("#search_result").html(found_user_text);
			});
		}
	</script>

</body>

</html>