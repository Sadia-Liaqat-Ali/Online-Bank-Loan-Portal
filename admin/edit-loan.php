<?php
session_start();
include '../dbconnection.php';

// Role check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Bank Administrator') {
    header("Location: ../login.php");
    exit();
}

// Check for loan ID
if (!isset($_GET['id'])) {
    header("Location: manage-loan.php");
    exit();
}

$loan_id = $_GET['id'];

// Handle form submission for updating loan type
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("UPDATE loan_types SET 
        loan_category = ?, 
        name = ?, 
        description = ?, 
        min_amount = ?, 
        max_amount = ?, 
        interest_rate = ?, 
        term_months = ?, 
        eligibility = ? 
        WHERE id = ?");
    
    $stmt->execute([
        $_POST['loan_category'],
        $_POST['name'],
        $_POST['description'],
        $_POST['min_amount'],
        $_POST['max_amount'],
        $_POST['interest_rate'],
        $_POST['term_months'],
        $_POST['eligibility'],
        $loan_id
    ]);

    header("Location: manage-loan.php?edited=1");
    exit();
}

// Fetch loan type details for the form
$stmt = $conn->prepare("SELECT * FROM loan_types WHERE id = ?");
$stmt->execute([$loan_id]);
$loan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loan) {
    echo "Loan type not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Loan Type</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      font-family: 'Segoe UI', sans-serif;
    }
    .content {
      margin-left: 260px;
      padding: 40px;
    }
    .form-title {
      font-size: 28px;
      font-weight: bold;
      color: #5D88BB;
      margin-bottom: 25px;
    }
    .form-card {
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.08);
      padding: 30px;
    }
    label {
      font-weight: 500;
      margin-bottom: 5px;
      color: #333;
    }
    .btn-success {
      background-color: #5cb85c;
      border: none;
    }
    .btn-success:hover {
      background-color: #4cae4c;
    }
    .btn-secondary {
      background-color: #6c757d;
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
  <div class="form-title">Edit Loan Type</div>
  <form method="POST" class="form-card">
    <div class="row mb-3">
      <div class="col-md-6">
        <label>Loan Name</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($loan['name']) ?>" required>
      </div>
      <div class="col-md-6">
        <label>Loan Category</label>
        <select name="loan_category" class="form-control" required>
          <option value="Personal Loan" <?= $loan['loan_category'] === 'Personal Loan' ? 'selected' : '' ?>>Personal Loan</option>
          <option value="Home Loan" <?= $loan['loan_category'] === 'Home Loan' ? 'selected' : '' ?>>Home Loan</option>
          <option value="Business Loan" <?= $loan['loan_category'] === 'Business Loan' ? 'selected' : '' ?>>Business Loan</option>
          <option value="Education Loan" <?= $loan['loan_category'] === 'Education Loan' ? 'selected' : '' ?>>Education Loan</option>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label>Description</label>
      <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($loan['description']) ?></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label>Minimum Amount (PKR)</label>
        <input type="number" step="0.01" name="min_amount" class="form-control" value="<?= $loan['min_amount'] ?>" required>
      </div>
      <div class="col-md-6">
        <label>Maximum Amount (PKR)</label>
        <input type="number" step="0.01" name="max_amount" class="form-control" value="<?= $loan['max_amount'] ?>" required>
      </div>
    </div>

    <div class="mb-3">
      <label>Interest Rate (%)</label>
      <input type="number" step="0.01" name="interest_rate" class="form-control" value="<?= $loan['interest_rate'] ?>" required>
    </div>

    <div class="mb-3">
      <label>Repayment Term (Months)</label>
      <input type="number" name="term_months" class="form-control" value="<?= $loan['term_months'] ?>" required>
    </div>

    <div class="mb-3">
      <label>Eligibility Criteria</label>
      <textarea name="eligibility" class="form-control" rows="2" required><?= htmlspecialchars($loan['eligibility']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-success">Update Loan Type</button>
    <a href="manage-loan.php" class="btn btn-secondary ms-2">Back</a>
  </form>
</div>

</body>
</html>
