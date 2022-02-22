<?php
require_once 'includes/session.php';
if (!is_logged_in()) {
	header('Location: login.php');
	exit;
}
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Home page for Rainbow Express" />
	<meta name="author" content="Caleb Eden" />
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · Index</title>

</head>

<body>
	<?php include 'common/navbar.php' ?>
	<main class="container" role="main">
		<div class="container bg-light">
			<?php if (isset($_SESSION['created_user'])) {
				echo "Created new user " . $_SESSION['created_user']['username'] . " with temporary password " . $_SESSION['created_user']['password'] . PHP_EOL;
			}
			?>
			<div class="row">
				<h3>Your Action Menu:</h3>
				<ul class="list-group">
					<?php
					if (is_admin()) {
						echo '<li class="list-group-item">' . PHP_EOL;
						include("admin_function_list.php");
						echo '</li>' . PHP_EOL;
					}
					?>
					<li class="list-group-item">
						<h4 class="mx-1">Properties:
							<?php
							if (is_admin()) {
								echo "<a href='admin/new_property.php' class='link-primary'>Add Property</a>";
							}
							?>
						</h4>
						<input type="text" name="search" id="search" class="form-control mb-1 mx-1" placeholder="Search" autocomplete="off" />
						<div id="properties"></div>
					</li>
				</ul>

			</div>
		</div>
	</main>
	<?php include 'common/footer.php' ?>

	<script>
		var typingTimer;
		const doneTypingInterval = 250; //time in ms that the user has to stop typing for
		$('#search').keyup(function() {
			clearTimeout(typingTimer);
			if ($('#search').val()) {
				typingTimer = setTimeout(findProperties, doneTypingInterval, false);
			} else {
				typingTimer = setTimeout(findProperties, doneTypingInterval, true);
			}
		});

		function findProperties(is_empty) {
			var query = document.getElementById("search").value;

			if (is_empty) {
				// If the search box is empty, pull up all properties the user has access to
				target = "get_properties.php";
			} else {
				target = "get_properties.php?search=" + query;
			}

			$.get(target, function(data) {
				var response = JSON.parse(data);
				site_list = '';
				for (let i = 0; i < response.length; i++) {
					site_list += "<a class='btn btn-primary my-1 mx-1' role='button' href='attendance/?property=" + response[i]['id'] + "'>" + response[i]['name'] + "</a>";
				}

				$('#properties').html(site_list);

			});
		}

		// Pull up all available sites on page load, rather than waiting for a search query
		findProperties(true);
	</script>

</body>

</html>