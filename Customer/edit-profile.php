<?php
session_start();
include '../dbconnection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch current profile info
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $cnic     = $_POST['cnic'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $password = $_POST['password'];

    // Check for duplicate email
    $emailCheck = $conn->prepare("SELECT * FROM customers WHERE email = ? AND id != ?");
    $emailCheck->execute([$email, $customer_id]);

    if ($emailCheck->rowCount() > 0) {
        echo '<script>alert("❌ Email already exists. Please use another email.");</script>';
    } else {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE customers SET name = :name, email = :email, password = :password, cnic = :cnic, phone = :phone, address = :address WHERE id = :id");
            $update->bindParam(':name', $name, PDO::PARAM_STR);
            $update->bindParam(':email', $email, PDO::PARAM_STR);
            $update->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $update->bindParam(':cnic', $cnic, PDO::PARAM_STR);
            $update->bindParam(':phone', $phone, PDO::PARAM_STR);
            $update->bindParam(':address', $address, PDO::PARAM_STR);
            $update->bindParam(':id', $customer_id, PDO::PARAM_INT);
            $update->execute();
        } else {
            $update = $conn->prepare("UPDATE customers SET name = :name, email = :email, cnic = :cnic, phone = :phone, address = :address WHERE id = :id");
            $update->bindParam(':name', $name, PDO::PARAM_STR);
            $update->bindParam(':email', $email, PDO::PARAM_STR);
            $update->bindParam(':cnic', $cnic, PDO::PARAM_STR);
            $update->bindParam(':phone', $phone, PDO::PARAM_STR);
            $update->bindParam(':address', $address, PDO::PARAM_STR);
            $update->bindParam(':id', $customer_id, PDO::PARAM_INT);
            $update->execute();
        }

        echo '<script>alert("✅ Profile updated successfully!"); window.location.href = "dashboard.php";</script>';
    }

    // Refresh customer data
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3); /* Gradient background */
        }
        .card {
            border: none;
            border-radius: 15px;
        }
        .form-container {
            width: 600px;
            margin: 50px auto;
        }
        .btn-primary {
            background-color: #5D88BB;
        }
        .card-header {
            background-color: #5D88BB;
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="form-container">
        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h4>Edit Profile</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($customer['name']) ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">CNIC</label>
                        <input type="text" name="cnic" value="<?= htmlspecialchars($customer['cnic']) ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($customer['address']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Loan Portal. All rights reserved.</p>
    </footer>
</body>
</html>
