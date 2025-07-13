<?php
session_start();
include '../dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $stmt = $conn->prepare("INSERT INTO loan_types 
    (loan_category, name, description, min_amount, max_amount, interest_rate, term_months, eligibility) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  
  $stmt->execute([
    $_POST['loan_category'],
    $_POST['name'],
    $_POST['description'],
    $_POST['min_amount'],
    $_POST['max_amount'],
    $_POST['interest_rate'],
    $_POST['term_months'],
    $_POST['eligibility']
  ]);

  header("Location: manage-loan.php?added=1");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Loan Type</title>
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
  <div class="form-title">Add New Loan Type</div>
  <form method="POST" class="form-card">
    <div class="row mb-3">
      <div class="col-md-6">
        <label>Loan Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label>Loan Category</label>
        <select name="loan_category" class="form-control" required>
          <option value="">-- Select Category --</option>
          <option value="Personal Loan">Personal Loan</option>
          <option value="Home Loan">Home Loan</option>
          <option value="Business Loan">Business Loan</option>
          <option value="Education Loan">Education Loan</option>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label>Description</label>
      <textarea name="description" class="form-control" rows="2"></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label>Minimum Amount (PKR)</label>
        <input type="number" step="0.01" name="min_amount" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label>Maximum Amount (PKR)</label>
        <input type="number" step="0.01" name="max_amount" class="form-control" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label>Interest Rate (%)</label>
        <input type="number" step="0.01" name="interest_rate" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label>Repayment Term (Months)</label>
      <input type="number" name="term_months" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Eligibility Criteria</label>
      <textarea name="eligibility" class="form-control" rows="2" required></textarea>
    </div>

    <button type="submit" class="btn btn-success">Add Loan Type</button>
    <a href="manage-loan.php" class="btn btn-secondary ms-2">Back</a>
  </form>
</div>

</body>
</html>
