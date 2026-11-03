<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

$id = (int)($_POST['id'] ?? 0);

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$dob = !empty($_POST['dob']) ? $_POST['dob'] : null;
$gender = $_POST['gender'] ?? '';
$course = trim($_POST['course'] ?? '');
$address = trim($_POST['address'] ?? '');

$sql = "UPDATE applications
        SET full_name = ?, email = ?, phone = ?,
            dob = ?, gender = ?, course = ?, address = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssssi",
    $full_name,
    $email,
    $phone,
    $dob,
    $gender,
    $course,
    $address,
    $id
);

if ($stmt->execute()) {
    header("Location: admin.php");
    exit;
}

echo "Error updating student: " . htmlspecialchars($stmt->error);
?>