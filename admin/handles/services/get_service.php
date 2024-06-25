<?php

require_once('../../../includes/config.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$service_id = $_GET['service_id'];
	$doctor_id = $_GET['doctor_id'];

	$sql = "SELECT * FROM tbl_Services as s 
			LEFT JOIN tbl_ServiceAvailability as a
			ON s.service_id = a.service_id
			WHERE s.service_id = $service_id";

	$stmt = $pdo->query($sql);

	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	header('Content-Type: application/json');

	echo json_encode(array("status" => "success", "process" => "get the service for editing", "data" => $result));


} catch (PDOException $e) {
	echo json_encode(["status" => "error", "message" => $e->getMessage(), "report" => "get catch reached"]);
}