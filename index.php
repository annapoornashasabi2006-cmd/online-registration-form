<?php
// Registration form page
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Registration</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container">
    <section class="form-card">
      <div class="eyebrow">ONLINE APPLICATION</div>
      <h1>Registration Form</h1>
      <a href="admin.php" class="dashboard-btn">Admin Dashboard</a>
      
      <p class="intro">Please complete the details below. Fields marked * are required.</p>

      <form id="registrationForm" action="submit.php" method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

        <div class="grid">
          <div class="field">
            <label for="full_name">Full name *</label>
            <input id="full_name" name="full_name" type="text" maxlength="100" required>
            <small class="error"></small>
          </div>
          <div class="field">
            <label for="email">Email address *</label>
            <input id="email" name="email" type="email" maxlength="150" required>
            <small class="error"></small>
          </div>
          <div class="field">
            <label for="phone">Phone number *</label>
            <input id="phone" name="phone" type="tel" maxlength="20" required>
            <small class="error"></small>
          </div>
          <div class="field">
            <label for="dob">Date of birth</label>
            <input id="dob" name="dob" type="date">
            <small class="error"></small>
          </div>
          <div class="field">
            <label for="gender">Gender</label>
            <select id="gender" name="gender">
              <option value="">Select</option>
              <option>Female</option>
              <option>Male</option>
              <option>Non-binary</option>
              <option>Prefer not to say</option>
            </select>
          </div>
          <div class="field">
            <label for="course">Course / application type *</label>
            <select id="course" name="course" required>
              <option value="">Choose an option</option>
              <option>General Registration</option>
              <option>Undergraduate Application</option>
              <option>Postgraduate Application</option>
              <option>Other</option>
            </select>
            <small class="error"></small>
          </div>
          <div class="field full">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3" maxlength="500"></textarea>
          </div>
        </div>

        <label class="consent">
          <input type="checkbox" id="agree" name="agree" value="yes" required>
          <span>I confirm that the information provided is correct. *</span>
        </label>
        <small class="error consent-error"></small>

        <button class="submit-btn" type="submit">Submit application</button>
        <p class="privacy-note">Demo project: this version displays submitted details but does not save them to a database.</p>
      </form>
    </section>
  </main>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="script.js"></script>
</body>
</html>
