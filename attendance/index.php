<?php
require_once '../includes/session.php';
if (!is_logged_in()) {
	header('Location: ../login.php');
	exit;
}
if (!isset($_GET['property']) || !can_access($_GET['property'])) {
	header('Location: ../index.php');
	exit;
}

require_once '../includes/connect.php';

?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Homepage for weekly attendance" />
	<meta name="author" content="Caleb Eden" />
	<base href="../">
	</base>
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · Weekly Attendance</title>
	<style>
		table {
			display: block;
			overflow-x: auto;
		}
	</style>
</head>

<body>
	<?php include '../common/navbar.php' ?>
	<main role="main">
		<div class="container bg-light">
			<div class="row py-3">
				<div class="col"></div>
				<div class="col-lg-11">
					<?php
					try {
						$sql = "SELECT * FROM information_schema.tables WHERE table_schema='mission-arlington_attendance' AND table_name = '" . $_GET['property'] . "_roster' LIMIT 1";
						$stmt = $conn->prepare($sql);
						$stmt->execute();
						$table = $stmt->fetch();
					} catch (Exception $e) {
						echo $sql . PHP_EOL;
						echo "Error retreiving property information:" . $e->getMessage() . PHP_EOL;
					}
					try {
						if ($table == null) {
							// first time this property has ever been loaded; create new table accordingly
							$sql = "CREATE TABLE `" . $_GET['property'] . "_roster` (
								`id` int NOT NULL AUTO_INCREMENT,
								`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
								`nickname` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
								`address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
								`address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
								`city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
								`state` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
								`zip` int DEFAULT NULL,
								`date_of_birth` date NOT NULL,
								PRIMARY KEY (`id`)
								) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
						}
					} catch (Exception $e) {
						echo $sql . PHP_EOL;
						echo "Error creating roster for current year:" . $e->getMessage() . PHP_EOL;
					}

					try {
						$sql = "SELECT * FROM information_schema.tables WHERE table_schema='mission-arlington_attendance' AND table_name = '" . $_GET['property'] . "_attendance_" . date("Y") . "' LIMIT 1";
						$stmt = $conn->prepare($sql);
						$stmt->execute();
						$table = $stmt->fetch();
					} catch (Exception $e) {
						echo $sql . PHP_EOL;
						echo "Error retreiving property information:" . $e->getMessage() . PHP_EOL;
					}
					try {
						if ($table == null) {
							// first time this property has been loaded for the year; create new table accordingly
							$sql = "CREATE TABLE `" . $_GET['property'] . "_attendance_" . date("Y") . "` (
								`id` int NOT NULL AUTO_INCREMENT,
								`week_of` date NOT NULL,
								`church_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
								`time` tinyint NOT NULL COMMENT '0=AM, 1=PM, 2=Evening',
								`participants` json NOT NULL,
								`day1` json NOT NULL,
								`day2` json NOT NULL,
								`day3` json NOT NULL,
								`day4` json NOT NULL,
								`salvation` json NOT NULL,
								`notes` json NOT NULL,
								PRIMARY KEY (`id`)
								) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
						}
					} catch (Exception $e) {
						echo $sql . PHP_EOL;
						echo "Error creating roster for current year:" . $e->getMessage() . PHP_EOL;
					}

					try {
						$sql = "SELECT `name` FROM `properties` WHERE `id`=:id LIMIT 1";
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(":id", $_GET['property'], PDO::PARAM_INT);
						$stmt->execute();
						$result = $stmt->fetch();

						$week_of = strtotime("Monday this week");
						$monday_database = date('Y-m-d', $week_of);
						$monday_visual = date('M d\, Y', $week_of);
						$sql = "SELECT * FROM `" . $_GET['property'] . "_attendance_" . date("Y") . "` WHERE `week_of`=:week_of";
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(":week_of", $monday_database, PDO::PARAM_STR);
						$stmt->execute();
						$previous_attendance = $stmt->fetch();
						if ($previous_attendance == null) {
							$sql = "INSERT INTO `" . $_GET['property'] . "_attendance_" . date("Y") . "` (`week_of`, `time`, `participants`, `day1`, `day2`, `day3`, `day4`, `salvation`, `notes`) VALUES (:week_of, 0, '[]', '[]', '[]', '[]', '[]', '[]', '[]')";
							$stmt2 = $conn->prepare($sql);
							$stmt2->bindParam(":week_of", $monday_database, PDO::PARAM_STR);
							$stmt2->execute();
						}
						// now fetch the new values
						$stmt->execute();
						$previous_attendance = $stmt->fetch();
						$week_id = $previous_attendance['id'];
						echo "<h1>Taking attendance for <b>" . $result['name'] . "</b> on the week of <b>" . $monday_visual . "</b></h1>" . PHP_EOL;
					} catch (Exception $e) {
						echo $sql . PHP_EOL;
						echo "Error retreiving property information:" . $e->getMessage() . PHP_EOL;
					}
					?>

					<div class="mb-3 row">
						<?php
						try {
							$sql = "SELECT `participants`, `notes`, `church_group` FROM `" . $_GET['property'] . "_attendance_" . date("Y") . "` WHERE `week_of`=:week_of LIMIT 1";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(":week_of", $monday_database);
							$stmt->execute();
							$results = $stmt->fetch();
						} catch (Exception $e) {
							echo $sql . PHP_EOL;
							echo "Error retreiving property information:" . $e->getMessage() . PHP_EOL;
						}
						?>
						<fieldset class="col">
							<legend>
								<label for="church_group" class="sr-only" style="font-size:0px;">Church Group</label>
							</legend>
							<input type="text" name='<?php echo $_GET['property'] . "_" . $week_id ?>' id="church_group" class="form-control" placeholder="Church Group, City, State" value="<?php echo $results['church_group'] ?>" />
							<p style='color:red; <?php if ($results['church_group'] != null) echo "visibility:hidden;" ?>' id="group_error">Please enter a church group name and location</p>
						</fieldset>

						<fieldset class="col-sm col-md col-lg col-xl">
							<fieldset class="col-xs">
								<label class="form-check-label" for="mon-thu">
									<input class='form-check-input' type='radio' id='mon-thu' style='margin-left:auto; margin-right:auto;' disabled>
									Monday-Thursday
								</label>
								<label class="form-check-label" for="thu-sat">
									<input class='form-check-input' type='radio' id='thu-sat' style='margin-left:auto; margin-right:auto;' disabled>
									Thursday-Saturday
								</label>
							</fieldset>
							<hr class="d-xs-block d-sm-none" />
							<fieldset class="col-xs">
								<label class="form-check-label" for="am">
									<input class='form-check-input' type='radio' id='am' value=0 name='<?php echo $_GET['property'] . "_" . $week_id ?>' style='margin-left:auto; margin-right:auto;' onclick="timeOption(this)" <?php if ($previous_attendance['time'] == 0) echo " checked" ?> />
									AM
								</label>
								<label class="form-check-label" for="pm">
									<input class='form-check-input' type='radio' id='pm' value=1 name='<?php echo $_GET['property'] . "_" . $week_id ?>' style='margin-left:auto; margin-right:auto;' onclick="timeOption(this)" <?php if ($previous_attendance['time'] == 1) echo " checked" ?> />
									PM
								</label>
								<label class="form-check-label" for="evening">
									<input class='form-check-input' type='radio' id='evening' value=2 name='<?php echo $_GET['property'] . "_" . $week_id ?>' style='margin-left:auto; margin-right:auto;' onclick="timeOption(this)" <?php if ($previous_attendance['time'] == 2) echo " checked" ?> />
									Evening
								</label>
							</fieldset>
						</fieldset>
					</div>

					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Address</th>
								<th scope="col">Age</th>
								<th scope="col">Day 1</th>
								<th scope="col">Day 2</th>
								<th scope="col">Day 3</th>
								<th scope="col">Day 4</th>
								<th scope="col">Salvation</th>
								<th scope="col">Notes</th>
							</tr>
						</thead>
						<tbody>
							<?php
							try {
								$today = date("Y-m-d");

								$sql = "SELECT * FROM `" . $_GET['property'] . "_roster` WHERE `id`=:id LIMIT 1";
								$stmt = $conn->prepare($sql);
								$stmt->bindParam(":id", $user_id);
								foreach (json_decode($results['participants']) as $user_id) {
									$stmt->execute();
									$row = $stmt->fetch();
									echo "<tr>" . PHP_EOL;
									echo "<td>" . $row['name'];
									if (isset($row['nickname'])) {
										echo " (" . $row['nickname'] . ")";
									}
									echo "</td>" . PHP_EOL;
									echo "<td style='min-width:100px;'>" . $row['address1'] . "<br />" . $row['address2'] . "</td>" . PHP_EOL;
									echo "<td>" . date_diff(date_create($row['date_of_birth']), date_create($today))->format("%Y") . "</td>" . PHP_EOL;

									foreach (array("day1", "day2", "day3", "day4", "salvation") as $column) {
										echo "<td>
											<input class='form-check-input' type='checkbox' id='" . $row['id'] . "_" . $column . "_" . $week_id . "_" . $_GET['property'] . "' onclick='attendanceCheck(this)' ";
										if (in_array($row['id'], json_decode($previous_attendance[$column]))) {
											echo " checked ";
										}
										echo "/>
									</td>" . PHP_EOL;
									}
									$notes_array = json_decode($results['notes'], true);
									echo "<td><textarea style='min-width:20em;' class='notes' name='" . $_GET['property'] . "_" . $week_id . "_" . $row['id'] . "'>";
									if (isset($notes_array[$row['id']])) {
										echo $notes_array[$row['id']];
									}
									echo "</textarea></td></tr>" . PHP_EOL;
								}
							} catch (Exception $e) {
								echo $sql . PHP_EOL;
								echo "Error retreiving attendance information:" . $e->getMessage() . PHP_EOL;
							}
							?>
						</tbody>
					</table>
					<p class="px-1"><a class="btn btn-primary" role="button" href="attendance/roster/?property=<?php echo $_GET['property'] ?>&week=<?php echo $week_id ?>" role="">Edit Participants</a></p>
				</div>
				<div class="col"></div>
			</div>
		</div>
	</main>
	<?php include '../common/footer.php' ?>

	<script>
		function attendanceCheck(checkbox) {
			const key = checkbox.id.split("_");
			row = key[0];
			column = key[1];
			week_id = key[2];
			property = key[3];

			// SHOULD PROBABLY CONVERT THIS FROM GET TO POST AT SOME POINT
			$.get("attendance/checkbox_sql.php" + "?property=" + property + "&row=" + row + "&column=" + column + "&week=" + week_id);
		}

		function timeOption(radio) {
			const key = radio.name.split("_");
			const property = key[0];
			const week = key[1];

			// SHOULD PROBABLY CONVERT THIS FROM GET TO POST AT SOME POINT
			$.get("attendance/change_time_sql.php" + "?property=" + property + "&week=" + week + "&time=" + $('input[name=' + radio.name + ']:checked').val());
		}

		var typingTimer;
		const doneTypingInterval = 1500; //time in ms that the user has to stop typing for
		$('#church_group').keyup(function() {
			clearTimeout(typingTimer);
			typingTimer = setTimeout(changeGroup, doneTypingInterval);
		});

		$('.notes').keyup(function() {
			target = $(this);
			clearTimeout(typingTimer);
			typingTimer = setTimeout(function() {
				editNotes(target);
			}, doneTypingInterval);
		});

		function changeGroup() {
			var church_group = $("#church_group").val();
			const key = $("#church_group").attr('name').split("_");
			const property = key[0];
			const week = key[1];

			if (church_group == '') {
				$("#group_error").css('visibility', 'visible')
			} else {
				$("#group_error").css('visibility', 'hidden')
			}
			// SHOULD PROBABLY CONVERT THIS FROM GET TO POST AT SOME POINT
			$.get("attendance/church_group_sql.php?property=" + property + "&week=" + week + "&group=" + church_group);
		}

		function editNotes(textarea) {
			var note = $(textarea).val();
			const key = $(textarea).attr('name').split("_");
			const property = key[0];
			const week = key[1];
			const participant = key[2];

			if (church_group == '') {
				$("#group_error").css('visibility', 'visible')
			} else {
				$("#group_error").css('visibility', 'hidden')
			}

			// SHOULD PROBABLY CONVERT THIS FROM GET TO POST AT SOME POINT
			$.get("attendance/edit_note_sql.php?property=" + property + "&week=" + week + "&participant=" + participant + "&note=" + note);
		}
	</script>

</body>

</html>