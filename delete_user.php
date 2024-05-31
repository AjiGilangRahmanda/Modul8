<?php
header("Content-Type: application/json");
include 'db_config.php';

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Get the user ID from the query parameters
    if (!isset($_GET['id'])) {
        echo json_encode(["error" => "Invalid input"]);
        exit();
    }

    $id = (int)$_GET['id'];

    // Prepare and execute the SQL statement
    $stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $koneksi->close();
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
