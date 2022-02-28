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
	<meta name="description" content="Admin page to generate printable weekly report" />
	<meta name="author" content="Caleb Eden" />
	<base href="../">
	</base>
	<link rel="shortcut icon" href="favicon.ico?" />
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<title>Rainbow Express · Generate Report</title>
</head>

<body>
	<?php include '../common/navbar.php' ?>
	<main role="main">
		<div class="container card pb-2">
			<?php
			try {
				$sql = "SELECT * FROM `dates`";
				$stmt = $conn->prepare($sql);
				$stmt->execute();
				$results = $stmt->fetchAll();
				// echo json_encode($results[0]);

				$dates = array();
				foreach ($results as $row) {
					if (!array_key_exists($row['year'], $dates)) {
						$dates[(int)$row['year']] = array();
					}
					if (!array_key_exists($row['month'], $dates[$row['year']])) {
						$dates[(int)$row['year']][$row['month']] = array();
					}
					array_push($dates[$row['year']][$row['month']], array((int)$row['date'], $row['id']));
				}
				ksort($dates);
			} catch (Exception $e) {
				echo $sql . PHP_EOL;
				echo "Error retrieving list of dates:" . $e->getMessage() . PHP_EOL;
			}
			?>
			<form class="form-signin" action="admin/generate_report.php" target="blank_" method="post" enctype="multipart/form-data" autocomplete="off">
				<h3>Generate report for week of...</h3>
				<legend><label for="year">Year</label></legend>
				<div>
					<select id="year" name="year" onChange="loadMonths(this)" required>
						<option></option>
						<?php
						foreach (array_keys($dates) as $year) {
							echo "<option value='" . $year . "'>" . $year . "</option>";
						}
						?>
					</select>
				</div>
				<div id="monthsContainer" hidden=true>
					<legend><label for="year">Month</label></legend>
					<select id="month" name="month" onChange="loadDates(this)" required>
						<option></option>
					</select>
				</div>
				<div id="datesContainer" hidden=true>
					<legend><label for="year">Date (Monday)</label></legend>
					<select id="date" name="date">
						<option></option>
					</select>
				</div>
				<div id="submitContainer">
					<button class="btn btn-primary mt-2" type="submit" id="submit" disabled=true requied>Generate Report</button>
				</div>
			</form>
		</div>
	</main>
	<?php include '../common/footer.php' ?>
	<script>
		var dates = <?php echo json_encode($dates) ?>;

		function loadMonths(select) {
			$('#monthsContainer').prop('hidden', false);
			$('#submit').prop('disabled', true);
			$('#datesContainer').html("");
			$('#datesContainer').prop('hidden', true);
			html = "<legend><label for='month'>Month</label></legend>\
					<select id='month' name='month' onChange='loadDates(this)' required>\
						<option></option>";
			for (var month in dates[select.value]) {
				html += "<option>" + month + "</option>";
			}
			html += "</select>";
			$('#monthsContainer').html(html);
		}

		function loadDates(select) {
			$('#datesContainer').prop('hidden', false);
			$('#submit').prop('disabled', true);
			html = "<legend><label for='date'>Date (Monday)</label></legend>\
					<select id='date' name='date' onChange='loadSubmit(this)' required>\
						<option></option>";
			year = $('#year').val();
			for (var index in dates[year][select.value]) {
				date = dates[year][select.value][index];
				html += "<option value='" + date[0] + "." + date[1] + "'>" + date[0] + "</option>";
			}
			html += "</select>";
			$('#datesContainer').html(html);
		}

		function loadSubmit(select) {
			if (select.value) {
				$('#submit').prop('disabled', false);
			} else {
				$('#submit').prop('disabled', true);
			}
		}
	</script>
</body>

</html>