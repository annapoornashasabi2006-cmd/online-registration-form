<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=applications.csv');

$output = fopen('php://output', 'w');

fputcsv($output, [
    'ID',
    'Full Name',
    'Email',
    'Phone',
    'Date of Birth',
    'Gender',
    'Course',
    'Address'
]);

$sql = "SELECT id, full_name, email, phone, dob, gender, course, address
        FROM applications
        ORDER BY id DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>