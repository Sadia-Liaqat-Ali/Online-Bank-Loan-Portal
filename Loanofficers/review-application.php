<?php
session_start();
include '../dbconnection.php';

// Officer currently logged in
$officer_id = $_SESSION['user_id'];

// Fetch assigned loan applications
$stmt = $conn->prepare("SELECT la.id, la.status, lt.name AS loan_name, c.name AS customer_name, c.email
                        FROM loan_applications la
                        JOIN loan_types lt ON la.loan_type_id = lt.id
                        JOIN customers c ON la.user_id = c.id
                        WHERE la.officer_id = :officer_id
                        ORDER BY la.created_at DESC");
$stmt->execute(['officer_id' => $officer_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📄 Review Loan Applications</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        table th, table td {
            vertical-align: middle !important;
        }
        .btn-info {
            background-color: #0dcaf0;
            border: none;
        }
        .btn-info:hover {
            background-color: #0bbde4;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h3 class="mb-4 text-success">📋 Review Assigned Applications</h3>

    <?php if (count($applications) === 0): ?>
        <div class="alert alert-info">No applications found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Loan Name</th>
                        <th>Applicant Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>View Application</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?= htmlspecialchars($app['loan_name']) ?></td>
                            <td><?= htmlspecialchars($app['customer_name']) ?></td>
                            <td><?= htmlspecialchars($app['email']) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $app['status'] === 'Pending' ? 'warning' : 
                                    ($app['status'] === 'Approved' ? 'success' : 'danger') ?>">
                                    <?= $app['status'] ?>
                                </span>
                            </td>
                            <td>
                               <a href="update-application.php?id=<?= $app['id'] ?>" class="btn btn-sm btn-info">
    <i class="fas fa-clipboard-check"></i> Check Application
</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
