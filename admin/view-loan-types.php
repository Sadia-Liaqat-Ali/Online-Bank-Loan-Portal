<?php
session_start();
include '../dbconnection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Bank Administrator') {
    header("Location: ../manager-login.php");
    exit();
}

$loanTypes = $conn->query("SELECT * FROM loan_types ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Loan Types</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      max-width: 1400px;
      margin-left: 260px;
      padding: 40px 30px;
    }
    .title-section {
      margin-bottom: 40px;
    }
    .title-section h1 {
      font-weight: bold;
      color: #2c3e50;
    }
    .loan-card {
      background-color: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      padding: 25px;
      margin-bottom: 30px;
      border-left: 6px solid #0d6efd;
      transition: all 0.3s ease;
    }
    .loan-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .loan-title {
      font-size: 1.6rem;
      font-weight: bold;
      color: #0d6efd;
      margin-bottom: 10px;
    }
    .loan-category {
      font-size: 1rem;
      color: #6c757d;
      font-style: italic;
    }
    .loan-details {
      margin-top: 15px;
      font-size: 15px;
      color: #444;
    }
    .loan-info {
      margin-top: 15px;
      font-size: 14px;
      color: #333;
    }
  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>

  <div class="container">
    <div class="title-section">
      <h1>📚 All Available Loan Types</h1>
      <p class="lead">Detailed view of all loan products currently offered in the system.</p>
    </div>

    <?php foreach ($loanTypes as $loan): ?>
      <div class="loan-card">
        <div class="loan-title"><?= htmlspecialchars($loan['name']) ?></div>
        <div class="loan-category"><?= htmlspecialchars($loan['loan_category']) ?></div>
        <div class="loan-details"><?= nl2br(htmlspecialchars($loan['description'])) ?></div>
        <div class="loan-info mt-3">
          <strong>Interest Rate:</strong> <?= $loan['interest_rate'] ?>%<br>
          <strong>Maximum Amount:</strong> Rs <?= number_format($loan['max_amount'], 2) ?><br>
          <strong>Duration:</strong> <?= $loan['term_months'] ?> months<br>
          <strong>Created At:</strong> <?= date("d M, Y", strtotime($loan['created_at'])) ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
