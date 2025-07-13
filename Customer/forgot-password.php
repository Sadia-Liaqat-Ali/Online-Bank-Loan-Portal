<?php
include '../dbconnection.php';

$alert = "";
$type  = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email          = $_POST['email'];
    $newPassword    = $_POST['new_password'];
    $confirmPassword= $_POST['confirm_password'];

    // Check passwords match
    if ($newPassword !== $confirmPassword) {
        $alert = "Passwords do not match.";
        $type  = "danger";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() === 0) {
            $alert = "Email not found. Please register first.";
            $type  = "danger";
        } else {
            // Update password
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $upd    = $conn->prepare("UPDATE customers SET password = ? WHERE email = ?");
            $upd->execute([$hashed, $email]);

            $alert = "Password updated successfully! You may now login.";
            $type  = "success";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    body {
      background: linear-gradient(to right, #4c4caa, #8360c3);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .card-header {
      background-color: #5D88BB;
      color: #fff;
      text-align: center;
      font-size: 1.25rem;
      font-weight: 600;
    }
    .card-body {
      background-color: #d3bfff;
      padding: 2rem;
    }
    .input-group-text {
      background-color: #5D88BB;
      color: #fff;
      border: none;
    }
    .form-control {
      border-radius: 0.25rem;
      border: 1px solid #ccc;
    }
    .btn-primary {
      background-color: #5D88BB;
      border: none;
    }
    .btn-primary:hover {
      background-color: #4b74a3;
    }
    .alert {
      border-radius: 0.5rem;
      margin-bottom: 1.5rem;
    }
    .toggle-pass {
      cursor: pointer;
    }
    .link-text {
      font-size: 0.9rem;
      text-align: center;
      margin-top: 1rem;
    }
    .link-text a {
      color: #341f74;
      text-decoration: none;
      font-weight: 500;
    }
    .link-text a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="card-header">
      <i class="bi bi-key-fill me-2"></i>Reset Password
    </div>
    <div class="card-body">
      <?php if ($alert): ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($alert) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
          <input type="email" name="email" class="form-control" placeholder="Email Address" required>
        </div>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
          <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password" required>
          <span class="input-group-text toggle-pass" onclick="togglePass('new_password','new_pass_icon')">
            <i class="bi bi-eye-slash-fill" id="new_pass_icon"></i>
          </span>
        </div>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
          <span class="input-group-text toggle-pass" onclick="togglePass('confirm_password','confirm_pass_icon')">
            <i class="bi bi-eye-slash-fill" id="confirm_pass_icon"></i>
          </span>
        </div>
        <button type="submit" class="btn btn-primary w-100">Update Password</button>
      </form>

      <div class="link-text">
        <a href="customer_login.php">Back to Login</a>
      </div>
    </div>
  </div>
  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePass(fieldId, iconId) {
      const field = document.getElementById(fieldId);
      const icon  = document.getElementById(iconId);
      if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('bi-eye-slash-fill','bi-eye-fill');
      } else {
        field.type = 'password';
        icon.classList.replace('bi-eye-fill','bi-eye-slash-fill');
      }
    }
  </script>

</body>

</html>
