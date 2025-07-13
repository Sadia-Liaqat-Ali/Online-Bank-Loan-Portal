<?php
include '../dbconnection.php';

// Assuming officer name is stored in session
$officerName = isset($_SESSION['name']) ? $_SESSION['name'] : 'Officer';
?>

<!-- Font Awesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

<style>
  body {
    font-family: 'Poppins', sans-serif;
  }

  .sidebar {
    height: 100vh;
    width: 250px;
    background: linear-gradient(to right, #4c4caa, #8360c3);
    background-color: #d3bfff; /* fallback light purple */
    padding-top: 5rem;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    transition: all 0.3s ease-in-out;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
  }

  .sidebar a {
    color: #fff;
    text-decoration: none;
    padding: 15px 25px;
    display: flex;
    align-items: center;
    font-size: 16px;
    font-weight: 500;
    transition: background 0.3s ease, padding-left 0.3s ease, color 0.3s ease;
  }

  .sidebar a i {
    margin-right: 12px;
    font-size: 18px;
    transition: transform 0.3s ease;
  }

  .sidebar a:hover {
    background-color: #5D88BB !important;
    padding-left: 35px;
    color: #fff;
  }

  .sidebar a:hover i {
    transform: scale(1.2);
    color: #ffd700; /* Gold icon on hover */
  }

  .sidebar .active {
    background-color: rgba(255, 255, 255, 0.2);
  }

  @media (max-width: 768px) {
    .sidebar {
      position: absolute;
      left: -250px;
      z-index: 999;
    }

    .sidebar.open {
      left: 0;
    }
  }
  /* Navbar Custom Styling */
  .navbar {
    background: linear-gradient(to right, #4c4caa, #8360c3);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .navbar-brand {
    font-family: 'Poppins', sans-serif;
    font-size: 24px;
    font-weight: 600;
    letter-spacing: 1px;
    color: #fff !important;
  }

  .navbar small {
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    color: #fdfdfd;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
  }

  .navbar small i {
    color: #ffd700; /* Gold color for the icon */
  }
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid flex-column text-center">
    <span class="navbar-brand mb-0 h1">ONLINE BANK LOAN PORTAL</span>
    
  </div>
</nav>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <a href="Dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
  <a href="review-application.php"><i class="fas fa-check-circle"></i>Review Applications</a>
  <a href="track-repayments.php"><i class="fas fa-sync-alt"></i>Track Repayments</a>
  <a href="officer-queries.php"><i class="fas fa-comment-dots"></i>Customer Communication</a>
  <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
