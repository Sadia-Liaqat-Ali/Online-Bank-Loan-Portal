<?php
session_start();
include '../dbconnection.php';

// Role check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Loan Officer') {
    header("Location: ../login.php");
    exit();
}

// Fetch repayments with loan, application, and customer details
$stmt = $conn->prepare("
    SELECT r.*, c.name AS customer_name, lt.name AS loan_name
    FROM repayments r
    JOIN loan_applications la ON r.application_id = la.id
    JOIN customers c ON la.user_id = c.id
    JOIN loan_types lt ON la.loan_type_id = lt.id
    WHERE la.officer_id = :officer_id
    ORDER BY r.installment_no ASC
");
$stmt->execute(['officer_id' => $_SESSION['user_id']]);

$repayments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track Repayments</title>
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
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        .form-status {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .table th, .table td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="card p-4 bg-white">
        <h3 class="text-center text-primary mb-4"><i class="fas fa-money-check-alt"></i> Customer Repayment Records</h3>

        <?php if (count($repayments) === 0): ?>
            <div class="alert alert-info text-center">No repayments found.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>App ID</th>
                        <th>Customer</th>
                        <th>Loan</th>
                        <th>Installment</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Method</th>
                        <th>Proof</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($repayments as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $row['application_id'] ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= htmlspecialchars($row['loan_name']) ?></td>
                            <td><?= $row['installment_no'] ?></td>
                            <td>Rs <?= number_format($row['amount'], 2) ?></td>
                            <td><?= $row['due_date'] ?></td>
                            <td><span class="badge bg-info"><?= $row['method'] ?></span></td>
                            <td>
                                <?php if ($row['proof']): ?>
                                    <a href="<?= $row['proof'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                <?php else: ?>
                                    <span class="text-muted">No Proof</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge 
                                    <?= $row['status'] === 'Completed' ? 'bg-success' : ($row['status'] === 'Rejected' ? 'bg-danger' : 'bg-warning') ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="update-repayment-status.php" class="form-status">
                                    <input type="hidden" name="repayment_id" value="<?= $row['id'] ?>">
                                    <select name="status" class="form-select form-select-sm" required>
                                        <option value="In Review" <?= $row['status'] === 'In Review' ? 'selected' : '' ?>>In Review</option>
                                        <option value="Completed" <?= $row['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="Rejected" <?= $row['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-sync"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
