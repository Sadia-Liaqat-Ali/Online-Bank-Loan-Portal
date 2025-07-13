<?php
session_start();
include '../dbconnection.php'; // make sure this has PDO $conn

$query = "
SELECT 
    lt.id AS loan_type_id,
    lt.name AS loan_type_name,
    lt.interest_rate,
    COUNT(la.id) AS total_applications,
    COALESCE(SUM(la.amount), 0) AS total_disbursed,
    COALESCE(SUM(r.amount), 0) AS total_repaid,
    COUNT(r.id) AS total_repayments
FROM loan_types lt
LEFT JOIN loan_applications la ON lt.id = la.loan_type_id
LEFT JOIN repayments r ON la.id = r.application_id
GROUP BY lt.id, lt.name, lt.interest_rate
ORDER BY total_disbursed DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
$loanReports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Distribution Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(to right, #e0eafc, #cfdef3); }
        .main-content { margin-left: 260px; padding: 30px; }
        .table-container {
            background: #ffffff;
            border-radius: 1rem;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2.title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="table-container">
        <h2 class="title">📊 Loan Distribution Report (Admin View)</h2>
        <table class="table table-bordered table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Loan Type</th>
                    <th>Interest Rate (%)</th>
                    <th>Total Applications</th>
                    <th>Total Disbursed (Rs)</th>
                    <th>Total Repaid (Rs)</th>
                    <th>Installments Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loanReports as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($row['loan_type_name']) ?></td>
                    <td><?= $row['interest_rate'] ?>%</td>
                    <td><?= $row['total_applications'] ?></td>
                    <td>Rs <?= number_format($row['total_disbursed'], 2) ?></td>
                    <td>Rs <?= number_format($row['total_repaid'], 2) ?></td>
                    <td><?= $row['total_repayments'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
