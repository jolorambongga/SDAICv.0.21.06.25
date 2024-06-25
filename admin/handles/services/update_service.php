<?php

require_once('../../../includes/config.php');

try {

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $service_id = $_POST['service_id'];
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $cost = $_POST['cost'];
    $doctor_id = $_POST['doctor_id'];
    $avail_dates = json_decode($_POST['avail_dates'], true);


    $sql = "UPDATE tbl_Services 
            SET service_name = ?, description = ?, duration = ?, cost = ?, doctor_id = ?
            WHERE service_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$service_name, $description, $duration, $cost, $doctor_id, $service_id]);




    $sql = "DELETE FROM tbl_ServiceAvailability WHERE service_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$service_id]);



    $sql = "INSERT INTO tbl_ServiceAvailability (service_id, avail_date, avail_start_time, avail_end_time) 
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    foreach ($avail_dates as $schedule) {
        $stmt->execute([$service_id, $schedule['avail_day'], $schedule['avail_start_time'], $schedule['avail_end_time']]);
    }

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "update service", "data" => $avail_dates));

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage(), "report" => "update service catch reached"]);
}
