<?php
session_start();
include '../dbconnection.php';

if (!isset($_GET['id'])) {
    header("Location: customer-dashboard.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM loan_types WHERE id = ?");
$stmt->execute([$_GET['id']]);
$loan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loan) {
    echo "Loan not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loan Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      margin: 80px auto;
      max-width: 700px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      background-color: #ffffff;
    }
    h3 {
      font-weight: 600;
      color: #5D88BB;
    }
    .list-group-item {
      background-color: #f9f9f9;
      border: none;
      border-radius: 8px;
      margin-bottom: 8px;
      font-size: 15px;
    }
    .btn-success {
      background-color: #5cb85c;
      border: none;
      border-radius: 25px;
      padding: 10px 25px;
    }
    .btn-secondary {
      border-radius: 25px;
      padding: 10px 25px;
    }
    .btn:hover {
      opacity: 0.9;
    }
    .card p {
      font-size: 15px;
      color: #444;
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container">
  <div class="card shadow p-4">
    <h3 class="mb-3"><i class="bi bi-info-circle-fill me-2"></i><?= htmlspecialchars($loan['name']) ?> (<?= htmlspecialchars($loan['loan_category']) ?>)</h3>
    <p><?= htmlspecialchars($loan['description']) ?></p>

    <ul class="list-group list-group-flush mb-4 mt-3">
      <li class="list-group-item"><strong>Interest Rate:</strong> <?= $loan['interest_rate'] ?>%</li>
      <li class="list-group-item"><strong>Term Duration:</strong> <?= $loan['term_months'] ?> months</li>
      <li class="list-group-item"><strong>Amount Range:</strong> PKR <?= number_format($loan['min_amount']) ?> - <?= number_format($loan['max_amount']) ?></li>
      <li class="list-group-item"><strong>Eligibility Criteria:</strong> <?= htmlspecialchars($loan['eligibility']) ?></li>
    </ul>

    <a href="apply-loan.php?id=<?= $loan['id'] ?>" class="btn btn-success">Apply Now</a>
    <a href="dashboard.php" class="btn btn-secondary ms-2">Back</a>
  </div>
</div>

<?php include '../footer.php'; ?>

</body>
</html>
