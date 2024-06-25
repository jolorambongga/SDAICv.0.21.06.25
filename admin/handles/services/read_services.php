<?php

require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT s.service_id, s.service_name, s.description, s.duration, s.cost, s.doctor_id,
                CONCAT(d.first_name, ' ', COALESCE(d.middle_name, ''), ' ', d.last_name) AS full_name,
                GROUP_CONCAT(a.avail_date ORDER BY 
                    CASE a.avail_date
                        WHEN 'Sunday' THEN 0
                        WHEN 'Monday' THEN 1
                        WHEN 'Tuesday' THEN 2
                        WHEN 'Wednesday' THEN 3
                        WHEN 'Thursday' THEN 4
                        WHEN 'Friday' THEN 5
                        WHEN 'Saturday' THEN 6
                    END, a.avail_date ASC SEPARATOR ',') AS concat_date,
                GROUP_CONCAT(CONCAT(a.avail_start_time, '-', a.avail_end_time) ORDER BY 
                    CASE a.avail_date
                        WHEN 'Sunday' THEN 0
                        WHEN 'Monday' THEN 1
                        WHEN 'Tuesday' THEN 2
                        WHEN 'Wednesday' THEN 3
                        WHEN 'Thursday' THEN 4
                        WHEN 'Friday' THEN 5
                        WHEN 'Saturday' THEN 6
                    END, a.avail_date ASC SEPARATOR ',') AS concat_time
            FROM tbl_Services AS s
            LEFT JOIN tbl_ServiceAvailability AS a ON s.service_id = a.service_id
            LEFT JOIN tbl_Doctors AS d ON s.doctor_id = d.doctor_id
            GROUP BY s.service_id;
             -- s.service_name, s.description, s.duration, s.cost, s.doctor_id, d.first_name, d.middle_name, d.last_name
            ";

    $stmt = $pdo->query($sql);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "read services", "data" => $services));

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage(), "report" => "read catch reached"]);
}
