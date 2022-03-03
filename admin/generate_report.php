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

$date_components = explode(".", $_POST['date']);

require('../fpdf184/fpdf.php');

class PDF extends FPDF
{
	function Header()
	{
		global $date_components;
		// Arial bold 15
		$this->SetFont('Arial', 'B', 15);
		// Title
		$this->Cell(60, 10, "Week of " . $_POST['month'] . "/" . $date_components[0] . "/" . $_POST['year'], 1, 0, 'C');
		// Line break
		$this->Ln(12);
	}

	function blankRow($w, $fill)
	{
		$this->Cell($w[0], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[1], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[2], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[3], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[4], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[5], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[6], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[7], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[8], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[9], 6, "", 'LR', 0, 'L', $fill);
		$this->Ln();
	}

	function sectionHeader($header, $w, $fill)
	{
		$this->SetFont('', 'B');
		$this->Cell($w[0], 6, $header, 'LR', 0, 'L', $fill);
		$this->Cell($w[1], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[2], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[3], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[4], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[5], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[6], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[7], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[8], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[9], 6, "", 'LR', 0, 'L', $fill);
		$this->Ln();
		$this->SetFont('');
	}

	function dataRow($row, &$total, $w, $fill)
	{
		$day1 = count(json_decode($row['day1']));
		$day2 = count(json_decode($row['day2']));
		$day3 = count(json_decode($row['day3']));
		$day4 = count(json_decode($row['day4']));
		$salvations = count(json_decode($row['salvation']));
		$total['day1'] += $day1;
		$total['day2'] += $day2;
		$total['day3'] += $day3;
		$total['day4'] += $day4;
		$total['salvations'] += $salvations;
		$this->Cell($w[0], 6, $row['church_group'], 'LR', 0, 'L', $fill);
		$this->Cell($w[1], 6, $row['property'], 'LR', 0, 'L', $fill);
		$this->Cell($w[2], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[3], 6, $day1, 'LR', 0, 'L', $fill);
		$this->Cell($w[4], 6, $day2, 'LR', 0, 'L', $fill);
		$this->Cell($w[5], 6, $day3, 'LR', 0, 'L', $fill);
		$this->Cell($w[6], 6, $day4, 'LR', 0, 'L', $fill);
		$this->Cell($w[7], 6, $day1 + $day2 + $day3 + $day4, 'LR', 0, 'L', $fill);
		$this->Cell($w[8], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[9], 6, $salvations, 'LR', 0, 'L', $fill);
		$this->Ln();
	}

	function drawTable($header, $am_sites, $pm_sites, $evening_sites)
	{
		$total = array('day1' => 0, 'day2' => 0, 'day3' => 0, 'day4' => 0, 'salvations' => 0);

		$this->SetFillColor(255, 0, 0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128, 0, 0);
		$this->SetLineWidth(.3);
		$this->SetFont('', 'B');

		$w = array(75, 55, 1, 20, 20, 20, 20, 30, 1, 30);
		for ($i = 0; $i < count($header); $i++)
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
		$this->Ln();

		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');

		$fill = false;
		$this->sectionHeader("AM Sites", $w, $fill);
		$fill = !$fill;

		foreach ($am_sites as $row) {
			$this->dataRow($row, $total, $w, $fill);
			$fill = !$fill;
		}

		$this->blankRow($w, $fill);
		$fill = !$fill;
		$this->sectionHeader("PM Sites", $w, $fill);
		$fill = !$fill;

		foreach ($pm_sites as $row) {
			$this->dataRow($row, $total, $w, $fill);
			$fill = !$fill;
		}

		$this->blankRow($w, $fill);
		$fill = !$fill;
		$this->sectionHeader("Evening Sites", $w, $fill);
		$fill = !$fill;

		foreach ($evening_sites as $row) {
			$this->dataRow($row, $total, $w, $fill);
			$fill = !$fill;
		}

		$this->blankRow($w, $fill);
		$fill = !$fill;
		$this->setFont('', 'B');
		$this->Cell($w[0], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[1], 6, "TOTAL", 'LR', 0, 'L', $fill);
		$this->Cell($w[2], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[3], 6, $total['day1'], 'LR', 0, 'L', $fill);
		$this->Cell($w[4], 6, $total['day2'], 'LR', 0, 'L', $fill);
		$this->Cell($w[5], 6, $total['day3'], 'LR', 0, 'L', $fill);
		$this->Cell($w[6], 6, $total['day4'], 'LR', 0, 'L', $fill);
		$this->Cell($w[7], 6, $total['day1'] + $total['day2'] + $total['day3'] + $total['day4'], 'LR', 0, 'L', $fill);
		$this->Cell($w[8], 6, "", 'LR', 0, 'L', $fill);
		$this->Cell($w[9], 6, $total['salvations'], 'LR', 0, 'L', $fill);
		$this->Ln();

		// Closing line
		$this->Cell(array_sum($w), 0, '', 'T');
	}
}



try {
	$sql = "SELECT properties FROM `$dates_table` WHERE `id`=:id LIMIT 1";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(":id", $date_components[1], PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetch();
	$properties = json_decode($result['properties']);
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error retrieving properties from selected date:" . $e->getMessage() . PHP_EOL;
}

$am_sites = array();
$pm_sites = array();
$evening_sites = array();
$tabledata = array();
foreach ($properties as $key => $property) {
	try {
		$attendance_table = attendance_table($property, $_POST['year']);
		$sql = "SELECT * FROM `$attendance_table` WHERE `week_of`=:week_of LIMIT 1";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(":week_of", $_POST['year'] . "-" . $_POST['month'] . "-" . $date_components[0]);
		$stmt->execute();
		$results = $stmt->fetch();
	} catch (Exception $e) {
		echo $sql . PHP_EOL;
		echo "Error retrieving attendance for selected date:" . $e->getMessage() . PHP_EOL;
	}
	try {
		$sql = "SELECT `name` FROM `$properties_table` WHERE `id`=:id LIMIT 1";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(":id", $property, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch();
		$results['property'] = $result['name'];
	} catch (Exception $e) {
		echo $sql . PHP_EOL;
		echo "Error getting property name:" . $e->getMessage() . PHP_EOL;
	}
	switch ($results['time']) {
		case 0:
			array_push($am_sites, $results);
			break;
		case 1:
			array_push($pm_sites, $results);
			break;
		case 2:
			array_push($evening_sites, $results);
			break;
	}
}
//Alphabetize each time block by property name
usort($am_sites, function ($a, $b) {
	return strcmp($a['property'], $b['property']);
});
usort($pm_sites, function ($a, $b) {
	return strcmp($a['property'], $b['property']);
});
usort($evening_sites, function ($a, $b) {
	return strcmp($a['property'], $b['property']);
});

$pdf = new PDF();
$header = array("Church Group", "Property", "", "Day 1", "Day 2", "Day 3", "Day 4", "Total", "", "Salvations");
$pdf->SetFont('Arial', '', 14);
$pdf->AddPage("L");
$pdf->drawTable($header, $am_sites, $pm_sites, $evening_sites);
$pdf->Output();
