<?php
require_once '../../includes/session.php';
if (!is_logged_in()) {
	header('Location: ../login.php');
	exit;
}
if (!isset($_GET['property']) || !can_access($_GET['property'])) {
	header('Location: ../../index.php');
	exit;
}

require_once '../../includes/connect.php';
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Login page for Rainbow Express" />
	<meta name="author" content="Caleb Eden" />
	<base href="../../">
	</base>
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · Index</title>
	<style>
		table {
			display: block;
			overflow-x: auto;
		}
	</style>
</head>

<body>
	<?php include '../../common/navbar.php' ?>
	<main role="main">
		<div class="container bg-light">
			<div class="row py-3">
				<div class="col"></div>
				<div class="col-lg-11">
					<!-- py-2 px-3">-->
					<?php
					date_default_timezone_set("America/Chicago");
					try {
						$sql = "SELECT `name` FROM `properties` WHERE `id`=:id LIMIT 1";
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(":id", $_GET['property'], PDO::PARAM_INT);
						$stmt->execute();
						$result = $stmt->fetch();

						echo "<h1>Roster for <b>" . $result['name'] . "</b></h1>" . PHP_EOL;
					} catch (Exception $e) {
						echo $sql . PHP_EOL;
						echo "Error retreiving property information:" . $e->getMessage() . PHP_EOL;
					}
					try {
						$sql = "SELECT * FROM `" . $_GET['property'] . "_roster`";
						$stmt = $conn->prepare($sql);
						$stmt->execute();
						$rows = $stmt->fetchAll();
					} catch (Exception $e) {
						echo $sql . PHP_EOL;
						echo "Error retreiving roster:" . $e->getMessage() . PHP_EOL;
					}
					?>

					<p class="px-1">
						<a class="btn btn-primary" href="attendance/?property=<?php echo $_GET['property'] ?>" role="button">Take Attendance</a>
						<a class="btn btn-primary" href="attendance/roster/add_participant.php?property=<?php echo $_GET['property'] . "&week=" . $_GET['week'] ?>" role="button">Add New Participant</a>
					</p>
					<?php
					try {
						$today = date("Y-m-d");

						$sql = "SELECT `participants`, `id`, `week_of` FROM `" . $_GET['property'] . "_attendance_" . date("Y") . "` WHERE `id`=:id LIMIT 1";
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(":id", $_GET['week']);
						$stmt->execute();
						$results = $stmt->fetch();
					} catch (Exception $e) {
						echo $sql . PHP_EOL;
						echo "Error retreiving attendance information:" . $e->getMessage() . PHP_EOL;
					}
					?>

					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th scope="col">Here This Week<br />(<?php echo date_create_from_format("Y-m-d", $results['week_of'])->format("m/d") ?>)</th>
								<th scope="col">Name</th>
								<th scope="col">Nickname</th>
								<th scope="col">Address Line 1</th>
								<th scope="col">Address Line 2</th>
								<th scope="col">City</th>
								<th scope="col">State</th>
								<th scope="col">Zipcode</th>
								<th scope="col">Date of Birth</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($rows as $row) {
								echo "<tr>" . PHP_EOL;
								echo "<td><input class='form-check-input' type='checkbox' id='" . $row['id'] . "_" . $results['id'] . "_" . $_GET['property'] . "' onclick='attendanceCheck(this)'";
								if (in_array($row['id'], json_decode($results['participants']))) {
									echo " checked ";
								}
								echo " /></td>" . PHP_EOL;
								echo "<td>" . $row['name'] . "</td>" . PHP_EOL;
								echo "<td>" . $row['nickname'] . "</td>" . PHP_EOL;
								echo "<td style='min-width:100px;'>" . $row['address1'] . "</td>" . PHP_EOL;
								echo "<td style='min-width:100px;'>" . $row['address2'] . "</td>" . PHP_EOL;
								echo "<td>" . $row['city'] . "</td>" . PHP_EOL;
								echo "<td>" . $row['state'] . "</td>" . PHP_EOL;
								echo "<td>" . $row['zip'] . "</td>" . PHP_EOL;
								echo "<td>" . $row['date_of_birth'] . " (" . date_diff(date_create($row['date_of_birth']), date_create($today))->format("%Y") . ")" . "</td>" . PHP_EOL;
							}
							?>
						</tbody>
					</table>
					<p class="px-1">
						<a class="btn btn-primary" href="attendance/?property=<?php echo $_GET['property'] ?>" role="button">Take Attendance</a>
						<a class="btn btn-primary" href="attendance/roster/add_participant.php?property=<?php echo $_GET['property'] . "&week=" . $_GET['week'] ?>" role="button">Add New Participant</a>
					</p>
				</div>
				<div class="col"></div>
			</div>
		</div>
	</main>
	<?php include '../../common/footer.php' ?>
	<script>
		function attendanceCheck(checkbox) {
			const key = checkbox.id.split("_");
			row = key[0];
			week_id = key[1];
			property = key[2];

			// SHOULD PROBABLY CONVERT THIS FROM GET TO POST AT SOME POINT
			$.get("attendance/roster/checkbox_sql.php" + "?property=" + property + "&row=" + row + "&week=" + week_id);
		}
	</script>
</body>

</html>