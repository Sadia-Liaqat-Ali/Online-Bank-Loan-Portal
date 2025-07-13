<?php
session_start();
include '../dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_name'] = $user['name'];
            $_SESSION['ocasuid'] = $user['id']; // ✅ For session use

            echo '<script>alert("Login successful! Redirecting to dashboard...")</script>';
            echo "<script>window.location.href='dashboard.php';</script>";
            exit();
        } else {
            echo '<script>alert("Invalid password. Please try again.")</script>';
        }
    } else {
        echo '<script>alert("No user found with this email.")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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
      <i class="bi bi-person-lock-fill me-2"></i>Customer Login
    </div>
    <div class="card-body">
      <?php if (isset($alert)): ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($alert) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
          <input type="email" name="email" class="form-control" placeholder="Email Address" required />
        </div>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Password" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>

      <div class="link-text">
        Don’t have an account? <a href="register.php">Register</a><br />
        <a href="forgot-password.php">Forgot Password?</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
