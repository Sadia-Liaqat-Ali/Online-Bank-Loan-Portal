<?php
session_start();
include '../dbconnection.php';

// Handle Assign Officer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_officer'])) {
    $stmt = $conn->prepare("UPDATE loan_applications SET officer_id = ? WHERE id = ?");
    $success = $stmt->execute([$_POST['officer_id'], $_POST['application_id']]);

    $_SESSION[$success ? 'success' : 'error'] = $success 
        ? "✅ Loan officer assigned successfully." 
        : "❌ Error assigning officer.";

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Get applications with officer and loan info
$appStmt = $conn->prepare("
    SELECT la.id, lt.name AS loan_name, c.name AS customer_name, c.email, 
           u.name AS officer_name, la.officer_id
    FROM loan_applications la
    JOIN loan_types lt ON la.loan_type_id = lt.id
    JOIN customers c ON la.user_id = c.id
    LEFT JOIN users u ON la.officer_id = u.user_id
    ORDER BY la.created_at DESC
");
$appStmt->execute();
$applications = $appStmt->fetchAll(PDO::FETCH_ASSOC);

// Get all loan officers
$officerStmt = $conn->prepare("SELECT user_id, name FROM users WHERE role = 'Loan Officer'");
$officerStmt->execute();
$loanOfficers = $officerStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Loan Officers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(to right, #e0eafc, #cfdef3); }
        .main-content { margin-left: 260px; padding: 30px; }
        .card-custom {
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.08);
            background-color: #ffffff;
            margin-bottom: 20px;
        }
        .status-icon {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="mb-4">
        <h4 class="text-success">📋 Assign Loan Officers</h4>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
    </div>

    <?php if (count($applications) === 0): ?>
        <div class="alert alert-info">No loan applications found.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($applications as $app): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-custom p-3">
                        <h5 class="text-primary">💼 <?= htmlspecialchars($app['loan_name']) ?></h5>
                        <p><strong>👤 Applicant:</strong> <?= htmlspecialchars($app['customer_name']) ?></p>
                        <p><strong>📧 Email:</strong> <?= htmlspecialchars($app['email']) ?></p>
                        <p>
                            <strong>👮 Assigned Officer:</strong>
                            <?= $app['officer_name'] 
                                ? '<span class="text-success status-icon">✔ '.htmlspecialchars($app['officer_name']).'</span>' 
                                : '<span class="text-danger status-icon">❌ Not Assigned</span>' ?>
                        </p>
                        <form method="POST">
                            <input type="hidden" name="application_id" value="<?= $app['id'] ?>">
                            <div class="mb-2">
                                <select name="officer_id" class="form-select" required>
                                    <option value="">Select Officer</option>
                                    <?php foreach ($loanOfficers as $officer): ?>
                                        <option value="<?= $officer['user_id'] ?>"
                                            <?= $officer['user_id'] == $app['officer_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($officer['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" name="assign_officer" class="btn btn-outline-success w-100">✅ Assign Officer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
