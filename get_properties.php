<?php
require_once 'includes/connect.php';
require_once 'includes/session.php';

try {
	if (isset($_GET['search'])) {
		// $sql = "SELECT `id`,`name` FROM `properties` WHERE  `name` LIKE '%" . $_GET['search'] . "%' ORDER BY `name`";
		$sql = "SELECT `id`,`name` FROM `properties` WHERE  `name` LIKE :query ORDER BY `name`";
		$stmt = $conn->prepare($sql);
		$query = "%" . $_GET['search'] . "%";
		$stmt->bindParam(":query", $query, PDO::PARAM_STR);
		/* // I couldn't quite get fulltext search working with partial words or sorting by relevancy, and searching online didn't present anything terribly helpful, but here are my attempts
		$sql = "SELECT `id`,`name` FROM `properties` WHERE MATCH `name` AGAINST ('+*".$_GET['search']."*' IN BOOLEAN MODE)";
		$sql = "SELECT *, MATCH (`name`) AGAINST ('".$_GET['search']."'IN BOOLEAN MODE) AS Relevance FROM `properties` WHERE 1 AND MATCH (`name`) AGAINST ('".$_GET['search']."' IN BOOLEAN MODE) ORDER BY Relevance DESC"; */
	} else {
		$sql = "SELECT `id`,`name` FROM `properties` ORDER BY `name`";
		$stmt = $conn->prepare($sql);
	}

	$stmt->execute();
	$results = [];
	// foreach ($conn->query($sql) as $row) {
	// 	if (is_admin() || can_access($row['id'])) {
	// 		$results[] = ['id' => $row['id'], 'name' => $row['name']];
	// 	}
	// }
	while ($row = $stmt->fetch()) {
		if (can_access($row['id'])) {
			$results[] = ['id' => $row['id'], 'name' => $row['name']];
		}
	}
	echo json_encode($results);
} catch (Exception $e) {
	echo $sql . PHP_EOL;
	echo "Error selecting properties:" . $e->getMessage() . PHP_EOL;
}
