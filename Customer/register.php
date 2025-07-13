<?php
include '../dbconnection.php';

$alert = "";
$type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $cnic     = $_POST['cnic'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM customers WHERE email = :email");
    $check->execute([':email' => $email]);

    if ($check->rowCount() > 0) {
        // If email already exists, show alert and redirect to login page
        echo '<script>alert("Email already exists. Please login instead.")</script>';
        echo "<script>window.location.href ='customer_login.php'</script>";
    } else {
        // If email does not exist, insert the new record
        $sql = "INSERT INTO customers (name, email, password, cnic, phone, address)
                VALUES (:name, :email, :password, :cnic, :phone, :address)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([ 
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $password,
            ':cnic'     => $cnic,
            ':phone'    => $phone,
            ':address'  => $address
        ]);
        $alert = "Registration successful!";
        $type = "success";
        // Show success message and redirect to login page
        echo '<script>alert("Registration successful! Please login.")</script>';
        echo "<script>window.location.href ='customer_login.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer Registration</title>
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
      max-width: 500px;
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
      <i class="bi bi-person-plus-fill me-2"></i> Customer Registration
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
          <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
          <input type="text" name="name" class="form-control" placeholder="Full Name" required />
        </div>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
          <input type="email" name="email" class="form-control" placeholder="Email Address" required />
        </div>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Password" required />
        </div>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-card-text"></i></span>
          <input type="text" name="cnic" class="form-control" placeholder="CNIC" required />
        </div>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
          <input type="text" name="phone" class="form-control" placeholder="Phone Number" />
        </div>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-house-door-fill"></i></span>
          <textarea name="address" class="form-control" rows="2" placeholder="Address"></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
      </form>

      <div class="link-text">
        Already have an account? <a href="customer_login.php">Login</a><br />
        <a href="forgot-password.php">Forgot Password?</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
