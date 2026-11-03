<?php
session_start();

function clean_input(string $key): string {
    return trim((string)($_POST[$key] ?? ''));
}
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$postedToken = (string)($_POST['csrf_token'] ?? '');
$sessionToken = (string)($_SESSION['csrf_token'] ?? '');
if ($sessionToken === '' || !hash_equals($sessionToken, $postedToken)) {
    http_response_code(403);
    exit('Invalid form session. Please go back and try again.');
}

$data = [
    'Full name' => clean_input('full_name'),
    'Email address' => clean_input('email'),
    'Phone number' => clean_input('phone'),
    'Date of birth' => clean_input('dob'),
    'Gender' => clean_input('gender'),
    'Course / application type' => clean_input('course'),
    'Address' => clean_input('address'),
];

$errors = [];
if ($data['Full name'] === '' || mb_strlen($data['Full name']) > 100) {
    $errors[] = 'Enter your name (up to 100 characters).';
}
if (!filter_var($data['Email address'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Enter a valid email address.';
}
if ($data['Phone number'] === '' || !preg_match('/^[0-9+().\-\s]{7,20}$/', $data['Phone number'])) {
    $errors[] = 'Enter a valid phone number.';
}
if ($data['Course / application type'] === '') {
    $errors[] = 'Choose an application type.';
}
if (!isset($_POST['agree'])) {
    $errors[] = 'You must confirm that the information is correct.';
}
if ($data['Date of birth'] !== '') {
    $date = DateTime::createFromFormat('Y-m-d', $data['Date of birth']);
    if (!$date || $date->format('Y-m-d') !== $data['Date of birth']) {
        $errors[] = 'Enter a valid date of birth.';
    }
}

if ($errors) {
    http_response_code(422);
}


if (empty($errors)) {
    require_once 'db.php';

    $dob = $data['Date of birth'] !== ''
        ? $data['Date of birth']
        : null;

    $stmt = $conn->prepare(
        "INSERT INTO applications
        (full_name, email, phone, dob, gender, course, address)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssssss",
        $data['Full name'],
        $data['Email address'],
        $data['Phone number'],
        $dob,
        $data['Gender'],
        $data['Course / application type'],
        $data['Address']
    );

    $stmt->execute();
    $stmt->close();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $errors ? 'Check your application' : 'Application submitted' ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container result-container">
    <?php if ($errors): ?>
      <section class="result-card">
        <div class="status error-status">Please check your details</div>
        <h1>Application not submitted</h1>
        <ul class="error-list">
          <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
        </ul>
        <a class="back-link" href="index.php">← Return to the form</a>
      </section>
    <?php else: ?>
      <section class="result-card" id="printArea">
        <div class="status success-status">✓ Submission successful</div>
        <h1>Registration Details</h1>
        <p class="intro">Thank you. Your information has been received by this demo application.</p>
        <div class="reference">APPLICATION SUMMARY</div>
        <dl class="details">
          <?php foreach ($data as $label => $value): ?>
            <div class="detail-row">
              <dt><?= e($label) ?></dt>
              <dd><?= $value !== '' ? nl2br(e($value)) : '—' ?></dd>
            </div>
          <?php endforeach; ?>
        </dl>
        <p class="submitted-at">Submitted on <?= date('d M Y, h:i A') ?></p>
      </section>
      <div class="actions no-print">
        <button class="submit-btn" type="button" onclick="window.print()">Print application</button>
        <a class="back-link" href="index.php">Submit another application</a>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
