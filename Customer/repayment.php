<?php
session_start();
include '../dbconnection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerId     = $_SESSION['customer_id'];
    $applicationId  = intval($_POST['application_id']);
    $installmentNo  = intval($_POST['installment_no']);
    $dueDate        = $_POST['due_date'];
    $method         = $_POST['method'];

    // Validate required fields
    if (empty($method) || empty($dueDate) || empty($installmentNo) || empty($applicationId)) {
        die("All fields are required.");
    }

    // File upload
    if (!isset($_FILES['proof']) || $_FILES['proof']['error'] !== UPLOAD_ERR_OK) {
        die("File upload failed.");
    }

    $uploadDir = "../uploads/repayments/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = time() . "_" . basename($_FILES["proof"]["name"]);
    $targetPath = $uploadDir . $filename;

    if (!move_uploaded_file($_FILES["proof"]["tmp_name"], $targetPath)) {
        die("Failed to upload proof image.");
    }

    // Get approved application to calculate installment amount
    $stmt = $conn->prepare("
        SELECT la.amount, lt.interest_rate, lt.term_months
        FROM loan_applications la
        JOIN loan_types lt ON la.loan_type_id = lt.id
        WHERE la.id = ? AND la.user_id = ? AND la.status = 'Approved'
    ");
    $stmt->execute([$applicationId, $customerId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        die("Approved application not found.");
    }

    $amount = $row['amount'];
    $rate = $row['interest_rate'];
    $term = $row['term_months'];

    $totalRepay = $amount + ($amount * $rate / 100);
    $installmentCount = ceil($term / 2);
    $installmentAmount = round($totalRepay / $installmentCount, 2);

    // Insert repayment record
    $insert = $conn->prepare("
        INSERT INTO repayments 
        (application_id, installment_no, due_date, amount, method, proof, status, submitted_at)
        VALUES (?, ?, ?, ?, ?, ?, 'In Review', NOW())
    ");

    $insert->execute([
        $applicationId,
        $installmentNo,
        $dueDate,
        $installmentAmount,
        $method,
        $targetPath
    ]);

    // Set a success message in session
    $_SESSION['repayment_message'] = "Thank you! Your repayment for installment #$installmentNo has been successfully submitted and is currently under review. Please allow some time for processing.";

    // Redirect after success
    header("Location: loan-distribution.php?id=" . $applicationId);
    exit();
} else {
    echo "Invalid request method.";
}
?>
