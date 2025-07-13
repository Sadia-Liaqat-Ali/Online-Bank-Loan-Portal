<?php
session_start();
include '../dbconnection.php';

if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit;
}

$application_id = $_GET['id'];

// ✅ Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $feedback = $_POST['feedback'];

    $stmt = $conn->prepare("UPDATE loan_applications SET status = ?, feedback = ? WHERE id = ?");
    $stmt->execute([$status, $feedback, $application_id]);

    echo "<script>alert('Application updated successfully.'); window.location.href='review-application.php';</script>";
    exit;
}

// ✅ Get application data
$stmt = $conn->prepare("SELECT la.*, lt.name AS loan_name, c.name AS customer_name, c.email AS customer_email 
                        FROM loan_applications la
                        JOIN loan_types lt ON la.loan_type_id = lt.id
                        JOIN customers c ON la.user_id = c.id
                        WHERE la.id = ?");
$stmt->execute([$application_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "Application not found.";
    exit;
}
?>

   
    <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Check Application</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
    }
    .main-content {
      margin-left: 250px;
      padding: 30px;
    }
    .card {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-radius: 16px;
      max-width: 600px;
    }
    .card-title {
      font-size: 1.5rem;
    }
    label {
      font-weight: 600;
    }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content d-flex justify-content-center">
  <div class="card p-4 bg-white w-100">
    <h3 class="card-title text-center text-success mb-4"><i class="fas fa-clipboard-check"></i> Check Application</h3>

    <p><strong>Applicant Name:</strong> <?= htmlspecialchars($row['customer_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($row['customer_email']) ?></p>
    <p><strong>Loan Type:</strong> <?= htmlspecialchars($row['loan_name']) ?></p>
    <p><strong>Amount:</strong> PKR <?= number_format($row['amount'], 2) ?></p>
    <p><strong>Purpose:</strong> <?= nl2br(htmlspecialchars($row['purpose'])) ?></p>

    <?php
    $proofFile = $row['proof_file'];
    $filePath = '../uploads/' . $proofFile;

    if (!empty($proofFile) && file_exists($filePath)) {
        echo '<p><strong>Submitted Proof:</strong> <a href="' . $filePath . '" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-file-alt"></i> View Proof Document</a></p>';
    } else {
        echo '<p class="text-danger">Proof document not found.</p>';
    }
    ?>

    <form method="POST" class="mt-4">
      <div class="mb-3">
        <label for="feedback" class="form-label">Feedback</label>
        <textarea name="feedback" id="feedback" rows="4" class="form-control" required><?= htmlspecialchars($row['feedback'] ?? '') ?></textarea>
      </div>

      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
          <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
          <option value="Approved" <?= $row['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
          <option value="Rejected" <?= $row['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success w-100"><i class="fas fa-save"></i> Update Application</button>
    </form>
  </div>
</div>

</body>
</html>
