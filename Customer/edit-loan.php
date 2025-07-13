<?php
session_start();
include '../dbconnection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: check-loan.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM loan_applications WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['customer_id']]);
$loan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loan || $loan['status'] !== 'Pending') {
    echo "<script>alert('Invalid or non-editable application.'); window.location.href='check-loan.php';</script>";
    exit();
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $purpose = $_POST['purpose'];

    if (!is_numeric($amount) || empty($purpose)) {
        $error = "Please enter valid data.";
    } else {
        $stmt = $conn->prepare("UPDATE loan_applications SET amount = ?, purpose = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$amount, $purpose, $id, $_SESSION['customer_id']]);
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Loan Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            border-radius: 15px;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="container">
    <div class="card shadow p-4">
        <h4 class="mb-3 text-success text-center">Edit Your Loan Application</h4>

        <?php if ($success): ?>
            <div class="alert alert-success text-center">Loan updated successfully!</div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="amount" class="form-label">Loan Amount</label>
                <input type="number" id="amount" name="amount" value="<?= $loan['amount'] ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="purpose" class="form-label">Purpose</label>
                <textarea id="purpose" name="purpose" class="form-control" required><?= htmlspecialchars($loan['purpose']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-success w-100">Update</button>
            <a href="customer-applications.php" class="btn btn-secondary w-100 mt-2">Back</a>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2025 Loan Portal. All rights reserved.</p>
</footer>

</body>
</html>
