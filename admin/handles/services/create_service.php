<?php

require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $doctor_id = $_POST['doctor_id'];
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $cost = $_POST['cost'];
    $duration = $_POST['duration'];
    

    $sql = "INSERT INTO tbl_Services (doctor_id, service_name, description, cost, duration)
            VALUES (:doctor_id, :service_name, :description, :cost, :duration);";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->bindParam(':service_name', $service_name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':cost', $cost, PDO::PARAM_INT);
    $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);

    $stmt->execute();


    $service_id = $pdo->lastInsertId();

    $avail_dates = json_decode($_POST['avail_dates'], true);

    foreach ($avail_dates as $availability) {
        
        $avail_day = $availability['avail_day'];
        $avail_start_time = $availability['avail_start_time'];
        $avail_end_time = $availability['avail_end_time'];

        $query = "INSERT INTO tbl_ServiceAvailability (service_id, avail_date, avail_start_time, avail_end_time) VALUES (?, ?, ?, ?)";

        $stmt = $pdo->prepare($query);

        $stmt->execute([$service_id, $avail_day, $avail_start_time, $avail_end_time]);
    }


    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "add service and availability", "avail_dates_data" => $avail_dates));

} catch (PDOException $e) {

    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "add service and availability", "data" => $avail_dates));

}
