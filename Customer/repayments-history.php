<?php
session_start();
include '../dbconnection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['customer_id'];

// Fetch repayment transactions for this customer using PDO
$stmt = $conn->prepare("SELECT r.id, r.application_id, r.installment_no, r.due_date, r.amount, r.method, r.proof, r.status, r.submitted_at, lt.name AS loan_name 
                        FROM repayments r
                        JOIN loan_applications la ON r.application_id = la.id
                        JOIN loan_types lt ON la.loan_type_id = lt.id
                        WHERE la.user_id = ? 
                        ORDER BY r.submitted_at DESC");
$stmt->execute([$user_id]);
$repayments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Repayment History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .proof-img {
            width: 80px;
            height: auto;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h3 class="mb-4 text-success">My Repayment History</h3>

    <?php if (count($repayments) === 0): ?>
        <div class="alert alert-info">You have not made any repayments yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped bg-white">
                <thead class="table-secondary">
                    <tr>
                        <th>Application ID</th>
                        <th>Loan Name</th>
                        <th>Installment No</th>
                        <th>Amount (Rs.)</th>
                        <th>Method</th>
                        <th>Proof</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($repayments as $rep): ?>
                        <?php
                            // Determine badge class based on repayment status
                            if ($rep['status'] === 'Completed') {
                                $badgeClass = 'success';
                            } elseif ($rep['status'] === 'Rejected') {
                                $badgeClass = 'danger';
                            } elseif ($rep['status'] === 'Refunded') {
                                $badgeClass = 'secondary';
                            } else {
                                $badgeClass = 'warning';
                            }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($rep['id']) ?></td>
                            <td><?= htmlspecialchars($rep['loan_name']) ?></td>
                            <td><?= htmlspecialchars($rep['installment_no']) ?></td>
                            <td><?= number_format($rep['amount'], 2) ?></td>
                            <td><?= htmlspecialchars($rep['method']) ?></td>
                            <td><a href="<?= htmlspecialchars($rep['proof']) ?>" target="_blank">View Proof</a></td>
                            <td><span class="badge bg-<?= $badgeClass ?>"><?= $rep['status'] ?? 'Under Review' ?></span></td>
                            <td><?= date('d-M-Y h:i A', strtotime($rep['submitted_at'])) ?></td>
                           
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
