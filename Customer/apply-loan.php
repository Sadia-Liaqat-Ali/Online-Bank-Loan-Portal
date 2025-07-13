<?php
session_start();
include '../dbconnection.php';

if (!isset($_GET['id'])) {
    echo "Loan type not specified."; exit;
}
$stmt = $conn->prepare("SELECT * FROM loan_types WHERE id = ?");
$stmt->execute([$_GET['id']]);
$loan = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$loan) {
    echo "Loan type not found."; exit;
}

$success = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $loan_type_id = $_POST['loan_type_id'];
    $amount = $_POST['amount'];
    $purpose = trim($_POST['purpose']);
    $application_type = $_POST['application_type'];

    if (!is_numeric($amount) || $amount <= 0) {
        $error = "Enter valid loan amount.";
    } elseif (empty($purpose) || empty($application_type)) {
        $error = "Fill all required fields.";
    } elseif (!isset($_FILES['proof_file']) || $_FILES['proof_file']['error'] !== UPLOAD_ERR_OK) {
        $error = "Upload an image file.";
    } else {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES['proof_file']['type'], $allowed_types)) {
            $error = "Only JPG, JPEG, and PNG images allowed.";
        } else {
            $target_dir = '../uploads/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $file_name = time() . '_' . basename($_FILES['proof_file']['name']);
            $file_tmp = $_FILES['proof_file']['tmp_name'];

            if (!move_uploaded_file($file_tmp, $target_dir . $file_name)) {
                $error = "File upload failed.";
            } else {
                // Ensure status is 'pending' by default
                $stmt = $conn->prepare("INSERT INTO loan_applications 
                    (user_id, loan_type_id, amount, purpose, application_type, proof_file, status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'pending')");
                $stmt->execute([$customer_id, $loan_type_id, $amount, $purpose, $application_type, $file_name]);
                $success = true;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apply for <?= htmlspecialchars($loan['name']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      max-width: 750px;
      margin: 60px auto;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      background-color: #fff;
    }
    h4 {
      color: #5D88BB;
      font-weight: 600;
    }
    .form-label {
      font-weight: 500;
      color: #333;
    }
    .form-control, .form-select {
      border-radius: 10px;
    }
    .btn-success {
      background-color: #28a745;
      border: none;
      border-radius: 30px;
      padding: 10px 25px;
    }
    .btn-secondary {
      border-radius: 30px;
      padding: 10px 25px;
    }
    .alert {
      border-radius: 12px;
      font-size: 15px;
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container">
  <div class="card shadow p-4">
    <?php if ($success): ?>
      <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i> Congratulations! Your Loan Application submitted successfully!</div>
    <?php elseif (!empty($error)): ?>
      <div class="alert alert-danger"><i class="bi bi-exclamation-circle-fill me-2"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <h4 class="mb-4">
      Apply for <?= htmlspecialchars($loan['name']) ?>
      <br> (<?= htmlspecialchars($loan['loan_category']) ?>)
    </h4>

    <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="loan_type_id" value="<?= $loan['id'] ?>">

      <div class="mb-3">
        <label for="amount" class="form-label">Requested Amount</label>
        <input type="number" name="amount" id="amount" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="purpose" class="form-label">Purpose of Loan</label>
        <textarea name="purpose" id="purpose" class="form-control" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label for="application_type" class="form-label">Application Type</label>
        <select name="application_type" id="application_type" class="form-select" required>
          <option value="">Select Type</option>
          <option value="domestic">Domestic</option>
          <option value="overseas">Overseas</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="proof_file" class="form-label">Upload CNIC for eligibility check(Image Only)</label>
        <input type="file" name="proof_file" id="proof_file" class="form-control" accept=".jpg,.jpeg,.png" required>
      </div>

      <button type="submit" class="btn btn-success">Submit Application</button>
      <a href="customer-dashboard.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
  </div>
</div>

</body>
</html>
