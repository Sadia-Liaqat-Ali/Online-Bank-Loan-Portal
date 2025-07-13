<?php
session_start();
include '../dbconnection.php';

// Role check: Only loan officers can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Loan Officer') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repayment_id'], $_POST['status'])) {
    $repayment_id = $_POST['repayment_id'];
    $status = $_POST['status'];

    // Update repayment status in the database
    $stmt = $conn->prepare("UPDATE repayments SET status = ? WHERE id = ?");
    $stmt->execute([$status, $repayment_id]);

    // Redirect back to the track repayments page with a success message
    $_SESSION['message'] = "Repayment status updated successfully!";
    header("Location: track-repayments.php");
    exit();
} else {
    // If the POST request isn't set, redirect to the track repayments page
    header("Location: track-repayments.php");
    exit();
}
?>
