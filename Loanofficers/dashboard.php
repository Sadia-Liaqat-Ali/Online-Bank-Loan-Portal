<?php
session_start();
include '../dbconnection.php';

// Check if user is logged in and is a Loan Officer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Loan Officer') {
    header("Location: ../manager-login.php");
    exit();
}

$officer_id = $_SESSION['user_id'];

// Fetch stats
$totalAssigned = $conn->query("SELECT COUNT(*) FROM loan_applications WHERE officer_id = $officer_id")->fetchColumn();
$totalApproved = $conn->query("SELECT COUNT(*) FROM loan_applications WHERE officer_id = $officer_id AND status = 'Approved'")->fetchColumn();
$totalRejected = $conn->query("SELECT COUNT(*) FROM loan_applications WHERE officer_id = $officer_id AND status = 'Rejected'")->fetchColumn();
$totalPending = $conn->query("SELECT COUNT(*) FROM loan_applications WHERE officer_id = $officer_id AND status = ''")->fetchColumn();
$totalQueries = $conn->query("SELECT COUNT(*) FROM loan_queries")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loan Officer Dashboard</title>
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
    .bg-red { background: #dc3545; }
    .bg-teal { background: #20c997; }

    .stat-card i {
      font-size: 30px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
  <div class="welcome-card text-center">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>! 👋</h1>
    <p class="lead">Manage your assigned applications and customer queries effectively.</p>
  </div>

  <!-- Stat Cards Row -->
  <div class="row text-center">
    <div class="col-md-4">
      <div class="stat-card bg-blue">
        <i class="fas fa-tasks"></i>
        <h4><?= $totalAssigned ?></h4>
        <p>Total Assigned Applications</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card bg-green">
        <i class="fas fa-check-circle"></i>
        <h4><?= $totalApproved ?></h4>
        <p>Approved Applications</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card bg-red">
        <i class="fas fa-times-circle"></i>
        <h4><?= $totalRejected ?></h4>
        <p>Rejected Applications</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card bg-orange">
        <i class="fas fa-hourglass-half"></i>
        <h4><?= $totalPending ?></h4>
        <p>Pending Applications</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card bg-teal">
        <i class="fas fa-question-circle"></i>
        <h4><?= $totalQueries ?></h4>
        <p>Total Customer Queries</p>
      </div>
    </div>
  </div>
</div>

</body>
</html>
