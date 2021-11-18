<?php

header("Content-Type:application/json");

try {
	$database_name     = 'joomallc_neonpanel';
	$database_user     = 'joomallc_neonpanel';
	$database_password = '^gYd4RfJi#gT';
	$database_host     = 'localhost';

	$pdo = new PDO('mysql:host=' . $database_host . '; dbname=' . $database_name, $database_user, $database_password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
	$sql = 'select * from pricing';

	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$data = [];

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$data[] = $row;
	}

	$response =  $data;

	echo json_encode($response, JSON_PRETTY_PRINT);

} catch (PDOException $e) {
	echo 'Database error. ' . $e->getMessage();
}