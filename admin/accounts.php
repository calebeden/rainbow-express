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
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="Admin page to add new user account" />
	<meta name="author" content="Caleb Eden" />
	<base href="../">
	</base>
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · View Accounts</title>
</head>

<body>
	<?php include '../common/navbar.php' ?>
	<main role="main">
		<div class="container bg-light">
			<div class="row py-3">
				<div class="col"></div>
				<div class="col-lg-11">
					<h1>Users</h1>
					<!-- py-2 px-3">-->

					<?php
					date_default_timezone_set("America/Chicago");

					try {
						$sql = "SELECT `name`, `username` FROM `$users_table` ORDER BY `name`, `username`";
						$stmt = $conn->prepare($sql);
						$stmt->execute();
						$rows = $stmt->fetchAll();
						$row_count = count($rows);
					} catch (Exception $e) {
						echo $sql . PHP_EOL;
						echo "Error retreiving list of users:" . $e->getMessage() . PHP_EOL;
					}
					?>

					<table class="table table-striped table-bordered mb-1">
						<?php
						if (!array_key_exists('page', $_GET)) {
							$_GET['page'] = 1;
						}
						$max_page = intdiv($row_count, 20) + 1;
						$page_first_user = ($_GET['page'] - 1) * 20 + 1;
						$page_last_user = min($_GET['page'] * 20, $row_count);
						echo "<p class='text-muted'>Showing users $page_first_user-$page_last_user out of $row_count (Page $_GET[page]/$max_page)" . PHP_EOL;
						?>
						<thead>
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Username</th>
								<th scope="col">Functions</th>
							</tr>
						</thead>
						<tbody>
							<?php
							
							foreach (array_slice($rows, 20 * ($_GET['page'] - 1), 20) as $row) {
								echo "<tr>" . PHP_EOL;
								echo "<td>$row[name]</td>" . PHP_EOL;
								echo "<td>$row[username]</td>" . PHP_EOL;
								echo "<td><div class='row'>" . PHP_EOL;
								echo "<span class='col d-flex justify-content-center'><a type='button' class='btn btn-primary btn-sm' href='edit_account.php'>Edit</a></span>" . PHP_EOL;
								echo "<span class='col d-flex justify-content-center'><a type='button' class='btn btn-warning btn-sm' href='edit_account.php'>Reset Password</a></span>" . PHP_EOL;
								echo "<span class='col d-flex justify-content-center'><a type='button' class='btn btn-danger btn-sm' href='edit_account.php'>Disable</a></span>" . PHP_EOL;
								echo "</div></td>" . PHP_EOL;
								echo "</tr>" . PHP_EOL;
							}


							?>
						</tbody>
					</table>
					<div class="container">
						<?php
						echo "<a href='admin/accounts.php?page=1'>First</a>" . PHP_EOL;
						if ($row_count > 8) {
							if ($max_page - $_GET['page'] < 11) {
								$min = max(1, $_GET['page'] - 5);
								// $min = max(1, $_GET['page'] - 5 - );
							} else {
								$min = max(1, $_GET['page'] - 5);
							}
							if ($min == 1) {
								$max = min($max_page + 1, $_GET['page'] + 5 + (6 - $_GET['page']));
							} else {
								$max = min($max_page + 1, $_GET['page'] + 5);
							}
							for ($i = $min; $i < $max; $i++) {
								if ($i != $_GET['page']) {
									echo "<a href='admin/accounts.php?page=$i'> $i</a>" . PHP_EOL;
								} else {
									echo "<a style='color:#37404e' href='admin/accounts.php?page=$i'> $i</a>" . PHP_EOL;
								}
							}
						} else {
							for ($i = 1; $i < min($max_page, 8); $i++) {
								if ($i != $_GET['page']) {
									echo "<a href='admin/accounts.php?page=$i'> $i</a>" . PHP_EOL;
								} else {
									echo "<a style='color:#37404e' href='admin/accounts.php?page=$i'> $i</a>" . PHP_EOL;
								}
							}
						}
						echo "<a href='admin/accounts.php?page=$max_page'>Last</a>" . PHP_EOL;
						?>
					</div>
				</div>
				<div class="col"></div>
			</div>
		</div>
	</main>
	<?php include '../common/footer.php' ?>
</body>

</html>