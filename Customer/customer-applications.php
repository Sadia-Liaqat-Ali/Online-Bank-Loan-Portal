<?php
session_start();
include '../dbconnection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login.php");
    exit();
}

// Delete functionality
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("SELECT * FROM loan_applications WHERE id = ? AND user_id = ? AND status = 'Pending'");
    $stmt->execute([$delete_id, $_SESSION['customer_id']]);

    if ($stmt->rowCount() > 0) {
        $del = $conn->prepare("DELETE FROM loan_applications WHERE id = ?");
        $del->execute([$delete_id]);
        $_SESSION['msg'] = "Loan application deleted successfully.";
        header("Location: check-application.php");
        exit();
    }
}

// Fetch applications
$user_id = $_SESSION['customer_id'];
$stmt = $conn->prepare("SELECT la.*, lt.name AS loan_name 
                        FROM loan_applications la
                        JOIN loan_types lt ON la.loan_type_id = lt.id
                        WHERE la.user_id = ?
                        ORDER BY la.created_at DESC");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Loan Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .icon-btn {
            border: none;
            background: none;
            font-size: 1.1rem;
            cursor: pointer;
            margin: 0 5px;
        }
        .icon-btn:hover {
            opacity: 0.7;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
        }

        /* Adjust layout to avoid body overlapping with sidebar */
        .main-content {
            margin-left: 250px; /* Adjust this based on your sidebar width */
            padding: 20px;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container mt-5">
        <h3 class="mb-4 text-center text-success">Your Loan Applications</h3>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-success text-center"><?= $_SESSION['msg'] ?></div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <?php if (count($applications) === 0): ?>
            <div class="alert alert-info text-center">No loan applications found.</div>
        <?php else: ?>
            <div class="table-container">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Loan Type</th>
                            <th>Amount (PKR)</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Proof</th>
                            <th>Applied On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $status_colors = [
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Verified' => 'primary'
                    ];
                    ?>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?= htmlspecialchars($app['loan_name']) ?></td>
                            <td><?= number_format($app['amount'], 2) ?></td>
                            <td><?= htmlspecialchars($app['purpose']) ?></td>
                            <td>
                                <span class="badge bg-<?= $status_colors[$app['status']] ?? 'secondary' ?>">
                                    <?= htmlspecialchars($app['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($app['proof_file'])): ?>
                                    <a href="../uploads/<?= htmlspecialchars($app['proof_file']) ?>" target="_blank" class="icon-btn text-info" title="View Proof">📄</a>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d-M-Y', strtotime($app['created_at'])) ?></td>
                            <td>
    <?php if ($app['status'] === 'Pending'): ?>
        <a href="edit-loan.php?id=<?= $app['id'] ?>" class="icon-btn text-warning" title="Edit">✏️</a>
        <a href="check-application.php?delete_id=<?= $app['id'] ?>" onclick="return confirm('Are you sure you want to delete this application?')" class="icon-btn text-danger" title="Delete">🗑️</a>
    <?php elseif ($app['status'] === 'Rejected'): ?>
        <span class="icon-btn text-secondary" title="View Only">👁️‍🗨️</span>
    <?php elseif ($app['status'] === 'Approved'): ?>
        <a href="loan-distribution.php?id=<?= $app['id'] ?>" class="btn btn-sm btn-primary rounded-pill px-3" title="View Loan Distribution">🎉 View Your Loan Distribution</a>
    <?php endif; ?>
</td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../footer.php'; ?>


</body>
</html>
