<?php
session_start();
include '../dbconnection.php';

if (!isset($_SESSION['customer_id'])) {
    echo '<script>alert("Please login to continue."); window.location.href="login.php";</script>';
    exit();
}

$stmt = $conn->prepare("SELECT * FROM loan_types ORDER BY created_at DESC");
$stmt->execute();
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e6e9f0, #eef1f5);
      font-family: 'Segoe UI', sans-serif;
    }
    .content {
      margin-left: 260px;
      padding: 50px 30px;
    }
    .loan-card {
      background-color: #fff;
      border-left: 6px solid #6a5acd;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
      padding: 25px;
      margin-bottom: 30px;
      transition: all 0.3s ease-in-out;
    }
    .loan-card:hover {
      transform: scale(1.02);
      box-shadow: 0 10px 25px rgba(106, 90, 205, 0.2);
    }
    .btn-outline-primary:hover {
    background-color:#6a5acd ;
    }
    .loan-title {
      font-weight: bold;
      font-size: 1.4rem;
      color: #5D88BB;
    }
    .loan-category {
      font-size: 0.95rem;
      font-style: italic;
      color: #7a7a7a;
    }
    .btn-outline-primary {
      border-radius: 25px;
    }
    h2.page-title {
      color: #333;
      font-weight: 600;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
  <h2 class="page-title"><i class="bi bi-bank2 me-2"></i>Our Available Loan Types For You</h2>
  <div class="row">
    <?php foreach ($loans as $loan): ?>
      <div class="col-md-6 col-lg-4">
        <div class="loan-card">
          <div class="loan-title"><i class="bi bi-cash-stack me-2"></i><?= htmlspecialchars($loan['name']) ?></div>
          <div class="loan-category">(<?= htmlspecialchars($loan['loan_category']) ?>)</div>
          <p class="text-muted mt-2"><?= htmlspecialchars($loan['description']) ?></p>
          <a href="loan-details.php?id=<?= $loan['id'] ?>" class="btn btn-outline-primary mt-3 w-100">Check Details</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include '../footer.php'; ?>

</body>
</html>
