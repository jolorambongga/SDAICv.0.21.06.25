<?php

require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $service_id = $_POST['service_id'];
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $max = $_POST['max'];
    $cost = $_POST['cost'];
    $doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : NULL;

    $sql = "UPDATE tbl_Services SET service_name = :service_name,  description = :description, duration = :duration, max = :max, cost = :cost, doctor_id = :doctor_id WHERE service_id = :service_id;";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':service_name', $service_name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);
    $stmt->bindParam(':max', $max, PDO::PARAM_INT);
    $stmt->bindParam(':cost', $cost, PDO::PARAM_INT);
    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);

    $stmt->execute();

    $sql = "DELETE FROM tbl_ServiceSched WHERE service_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$service_id]);

    $service_sched = json_decode($_POST['service_sched'], true);

    if (!empty($service_sched)) {
        foreach ($service_sched as $schedule) {
            $day_of_week = $schedule['day_of_week'];
            $start_time = $schedule['start_time'];
            $end_time = $schedule['end_time'];

            $sql = "INSERT INTO tbl_ServiceSched (service_id, day_of_week, start_time, end_time)
                    VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$service_id, $day_of_week, $start_time, $end_time]);
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "update_service", "service_sched" => $service_sched));

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "update_service"));
}
?>
