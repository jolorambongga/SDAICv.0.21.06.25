<?php

require_once('../../../includes/config.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$doctor_id = $_POST['doctor_id'];
	$user_input = $_POST['user_input'];

	if ($user_input == 'DELETE') {
		// DOCTOR AVAILABILITY
		$sql = "DELETE FROM tbl_DoctorAvailability WHERE doctor_id = ?;";

		$stmt = $pdo->prepare($sql);

		$stmt->bindParam(1, $doctor_id, PDO::PARAM_STR);

		$stmt->execute();

		// DELETE DOCTOR
		$sql = "DELETE FROM tbl_Doctors WHERE doctor_id = ?;";

		$stmt = $pdo->prepare($sql);

		$stmt->bindParam(1, $doctor_id, PDO::PARAM_STR);

		$stmt->execute();

		header('Content-Type: application/json');

		echo json_encode(array("status" => "success", "process" => "delete doctor IF", "user input is: " => $user_input));

	} else {
		echo json_encode(array("status" => "sucess", "process" => "delete doctor ELSE", "user input is: " => $user_input));
	}

} catch (PDOException $e) {
	echo json_encode(["status" => "error", "message" => $e->getMessage(), "report" => "del catch reached"]);
}