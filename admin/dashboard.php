<?php
session_start();
include '../dbconnection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Bank Administrator') {
    header("Location:../manager-login.php");
    exit();
}

$totalApplications = $conn->query("SELECT COUNT(*) FROM loan_applications")->fetchColumn();
$totalCustomers = $conn->query("SELECT COUNT(*) FROM customers")->fetchColumn(); // NEW
$totalLoanTypes = $conn->query("SELECT COUNT(*) FROM loan_types")->fetchColumn();
$totalOfficers = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'Loan Officer'")->fetchColumn();
$totalQueries = $conn->query("SELECT COUNT(*) FROM loan_queries")->fetchColumn();
$confirmedRepayments = $conn->query("SELECT COUNT(*) FROM repayments WHERE status = 'Completed'")->fetchColumn();

$loanTypes = $conn->query("SELECT * FROM loan_types ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #5D88BB;
    }
    .navbar-brand {
      color: white;
      font-weight: bold;
    }
    .sidebar {
      height: 100vh;
      width: 250px;
      background: linear-gradient(135deg, #4b74a3, #5D88BB);
      padding-top: 5rem;
      position: fixed;
      top: 0;
      left: 0;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      padding: 15px 25px;
      display: flex;
      align-items: center;
      font-size: 16px;
      font-weight: 500;
    }
    .sidebar a:hover {
      background-color: rgba(255, 255, 255, 0.1);
      padding-left: 35px;
    }
    .content {
      margin-left: 260px;
      padding: 40px;
    }
    .welcome-card {
      background: linear-gradient(to right, #4b74a3, #5D88BB);
      color: white;
      border-radius: 15px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    .stat-card {
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 25px;
      color: white;
      box-shadow: 0 6px 12px rgba(0,0,0,0.08);
      transition: 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-5px);
    }
    .bg-blue { background: #007bff; }
    .bg-green { background: #28a745; }
    .bg-orange { background: #fd7e14; }
    .bg-purple { background: #6f42c1; }
    .bg-teal { background: #20c997; }
    .bg-pink { background: #e83e8c; }
    .stat-card i {
      font-size: 30px;
      margin-bottom: 10px;
    }
    .loan-section {
      background: #ffffff;
      border-radius: 20px;
      padding: 40px;
      margin-top: 50px;
      text-align: center;
      box-shadow: 0 8px 16px rgba(0,0,0,0.05);
    }
    .loan-section h2 {
      font-weight: bold;
      color: #5D88BB;
    }
    .loan-section p {
      font-size: 18px;
      margin: 20px auto;
      max-width: 700px;
    }
    .btn-loans {
      background-color: #5D88BB;
      color: white;
      font-weight: 500;
    }
    .btn-loans:hover {
      background-color: #3f699c;
    }
  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>

  <div class="content">
    <div class="welcome-card text-center">
      <h1>Welcome, Admin! 🎉</h1>
      <p class="lead">Monitor all your banking operations efficiently — from managing customers and loan products to monitoring repayments and officer performance.</p>
    </div>

    <!-- Stat Cards Row -->
    <div class="row text-center">
      <div class="col-md-4">
        <div class="stat-card bg-blue">
          <i class="fas fa-file-alt"></i>
          <h4><?= $totalApplications ?></h4>
          <p>Total Applications</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card bg-green">
          <i class="fas fa-users"></i>
          <h4><?= $totalCustomers ?></h4>
          <p>Customers Registered</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card bg-orange">
          <i class="fas fa-briefcase"></i>
          <h4><?= $totalLoanTypes ?></h4>
          <p>Loan Products</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card bg-purple">
          <i class="fas fa-user-tie"></i>
          <h4><?= $totalOfficers ?></h4>
          <p>Loan Officers</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card bg-teal">
          <i class="fas fa-question-circle"></i>
          <h4><?= $totalQueries ?></h4>
          <p>Customer Queries</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card bg-pink">
          <i class="fas fa-credit-card"></i>
          <h4><?= $confirmedRepayments ?></h4>
          <p>Completed Repayments</p>
        </div>
      </div>
    </div>

    <!-- Loan Types Promo Section -->
    <div class="loan-section">
      <h2>All Loan Services at a Glance</h2>
      <p>As a Bank Administrator, you can oversee every loan category and customer interaction. Click below to manage loan products, update terms, or review performance trends.</p>
      <a href="view-loan-types.php" class="btn btn-loans btn-lg mt-3">Manage Loan Types</a>
    </div>
  </div>
</body>
</html>
