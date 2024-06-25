<?php

require_once('../../../includes/config.php');

try {

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $doctor_id = $_POST['doctor_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $contact = $_POST['contact'];
    $avail_dates = json_decode($_POST['avail_dates'], true);


    $sql = "UPDATE tbl_Doctors 
            SET first_name = ?, middle_name = ?, last_name = ?, contact = ?
            WHERE doctor_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$first_name, $middle_name, $last_name, $contact, $doctor_id]);


    $sql = "DELETE FROM tbl_DoctorAvailability WHERE doctor_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$doctor_id]);


    $sql = "INSERT INTO tbl_DoctorAvailability (doctor_id, avail_date, avail_start_time, avail_end_time) 
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    foreach ($avail_dates as $schedule) {
        $stmt->execute([$doctor_id, $schedule['avail_day'], $schedule['avail_start_time'], $schedule['avail_end_time']]);
    }

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "update doctor", "data" => $avail_dates));

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage(), "report" => "update catch reached"]);
}
