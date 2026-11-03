
<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

if (!isset($_GET['id'])) {
    die("Student ID is missing.");
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM applications WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>

<h2>Edit Student Details</h2>
<p><a href="admin.php">← Back to Admin Dashboard</a></p>

<form action="update.php" method="POST">

    <input type="hidden" name="id"
           value="<?php echo $student['id']; ?>">

    Full Name:<br>
    <input type="text" name="full_name"
           value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
    <br><br>

    Email:<br>
    <input type="email" name="email"
           value="<?php echo htmlspecialchars($student['email']); ?>" required>
    <br><br>

    Phone:<br>
    <input type="text" name="phone"
           value="<?php echo htmlspecialchars($student['phone']); ?>" required>
    <br><br>

    Date of Birth:<br>
    <input type="date" name="dob"
           value="<?php echo htmlspecialchars($student['dob'] ?? ''); ?>">
    <br><br>

    Gender:<br>
    <select name="gender">
        <option value="">Select</option>
        <option value="Female" <?php if (($student['gender'] ?? '') == 'Female') echo 'selected'; ?>>Female</option>
        <option value="Male" <?php if (($student['gender'] ?? '') == 'Male') echo 'selected'; ?>>Male</option>
        <option value="Non-binary" <?php if (($student['gender'] ?? '') == 'Non-binary') echo 'selected'; ?>>Non-binary</option>
    </select>
    <br><br>

    Course:<br>
    <input type="text" name="course"
           value="<?php echo htmlspecialchars($student['course']); ?>" required>
    <br><br>

    Address:<br>
    <textarea name="address"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
    <br><br>

    <button type="submit">Update Student</button>

</form>

</body>
</html>
