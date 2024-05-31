<?php
header("Content-Type: application/json");
include 'db_config.php';

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Get the posted data
    $data = json_decode(file_get_contents("php://input"));

    // Validate the data for update operation
    if (!isset($data->id) || !isset($data->name) || !isset($data->email) || !isset($data->tempat) || !isset($data->tanggal) || !isset($data->alamat)) {
        die(json_encode(["error" => "Invalid input"]));
    }

    // Extract and escape the data
    $id = (int)$data->id;
    $name = $koneksi->real_escape_string($data->name);
    $email = $koneksi->real_escape_string($data->email);
    $tempat = $koneksi->real_escape_string($data->tempat);
    $tanggal = $koneksi->real_escape_string($data->tanggal);
    $alamat = $koneksi->real_escape_string($data->alamat);

    // Validate and reformat the date
    $date_parts = explode('-', $tanggal);
    if (count($date_parts) == 3) {
        $day = $date_parts[0];
        $month = $date_parts[1];
        $year = $date_parts[2];
        if (checkdate($month, $day, $year)) {
            $tanggal_db = "$year-$month-$day";
        } else {
            die(json_encode(["error" => "Invalid date format"]));
        }
    } else {
        die(json_encode(["error" => "Invalid date format"]));
    }

    // Update the data in the database
    $sql = "UPDATE users SET name='$name', email='$email', tempat='$tempat', tanggal='$tanggal_db', alamat='$alamat' WHERE id=$id";

    if ($koneksi->query($sql) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => $koneksi->error]);
    }

    // Close the connection
    $koneksi->close();
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
