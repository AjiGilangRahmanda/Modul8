<?php
include 'db_config.php';

$sql = "SELECT id, name, email, tempat, tanggal, alamat FROM users";
$result = $koneksi->query($sql);

$users = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tanggal_db = $row['tanggal'];
        $tanggal_parts = explode('-', $tanggal_db);
        if (count($tanggal_parts) == 3) {
            $year = $tanggal_parts[0];
            $month = $tanggal_parts[1];
            $day = $tanggal_parts[2];
            $tanggal_indo = "$day-$month-$year";
            $row['tanggal'] = $tanggal_indo;
        }
        $users[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($users);

$koneksi->close();
