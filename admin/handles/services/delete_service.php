<?php

require_once('../../../includes/config.php');

try {
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $service_id = $_POST['service_id'];
  $user_input = $_POST['user_input'];

  if ($user_input == 'DELETE') {
    $pdo->beginTransaction();

    $sql = "DELETE FROM tbl_ServiceAvailability WHERE service_id = ?;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $service_id, PDO::PARAM_STR);
    $stmt->execute();

    $sql = "DELETE FROM tbl_Services WHERE service_id = ?;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $service_id, PDO::PARAM_STR);
    $stmt->execute();

    $pdo->commit();

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "delete service IF", "user input is" => $user_input));
  } else {
    echo json_encode(array("status" => "error", "process" => "delete service ELSE", "user input is" => $user_input));
  }
} catch (PDOException $e) {
  $pdo->rollBack();
  echo json_encode(["status" => "error", "message" => $e->getMessage(), "report" => "del catch reached"]);
}
