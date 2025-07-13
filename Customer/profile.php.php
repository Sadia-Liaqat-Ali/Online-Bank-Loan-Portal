<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../dbconnection.php';

if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch current user data
$stmt = $conn->prepare("SELECT name, email, cnic, phone, address FROM customers WHERE id = :id");
$stmt->execute([':id' => $customer_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<script>alert('User not found.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $cnic     = $_POST['cnic'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE customers SET name = :name, email = :email, cnic = :cnic, phone = :phone, address = :address, password = :password WHERE id = :id");
        $updated = $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':cnic' => $cnic,
            ':phone' => $phone,
            ':address' => $address,
            ':password' => $hashed_password,
            ':id' => $customer_id
        ]);
    } else {
        $stmt = $conn->prepare("UPDATE customers SET name = :name, email = :email, cnic = :cnic, phone = :phone, address = :address WHERE id = :id");
        $updated = $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':cnic' => $cnic,
            ':phone' => $phone,
            ':address' => $address,
            ':id' => $customer_id
        ]);
    }

    if ($updated) {
        echo "<script>alert('Profile updated successfully.'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Update failed. Try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #e9f1fb; }
    .card { border: none; border-radius: 15px; }
    .btn-primary { background-color: #5D88BB; border-color: #5D88BB; }
    .btn-primary:hover { background-color: #4b74a3; }
    .form-container { width: 600px; margin-top: 60px; }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center">
  <div class="form-container">
    <div class="card shadow-lg">
      <div class="card-header text-white text-center" style="background-color: #5D88BB !important;">
        <h4>Edit Profile</h4>
      </div>
      <div class="card-body">
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">CNIC</label>
            <input type="text" name="cnic" class="form-control" value="<?= htmlspecialchars($user['cnic']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control"><?= htmlspecialchars($user['address']) ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">New Password <small>(leave blank to keep current)</small></label>
            <input type="password" name="password" class="form-control">
          </div>
          <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </form>

        <div class="text-center mt-3">
          <a href="dashboard.php" class="btn btn-link">Back to Dashboard</a>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
