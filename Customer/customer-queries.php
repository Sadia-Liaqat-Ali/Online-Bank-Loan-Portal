<?php
session_start();
include '../dbconnection.php';

$success = false;
$error = '';

// Redirect if not logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $topic = $_POST['topic'];
    $type = $_POST['type'];
    $message = $_POST['message'];

    if (empty($topic) || empty($type) || empty($message)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO loan_queries (customer_id, topic, type, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$customer_id, $topic, $type, $message]);
        $success = true;
    }
}

$stmt = $conn->prepare("SELECT * FROM loan_queries WHERE customer_id = ?");
$stmt->execute([$_SESSION['customer_id']]);
$queries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Loan Queries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        window.onload = function() {
            <?php if ($success): ?>
                alert("✅ Your query has been submitted successfully!");
            <?php elseif (!empty($error)): ?>
                alert("❌ <?= $error ?>");
            <?php endif; ?>
        }
    </script>
    <style>
     body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
        }
        .container {
            max-width: 1200px;
            margin-top: 30px;
        }
        .card {
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .query-card {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .query-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        }
        footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="container">
    <div class="row">
        <!-- Left Column - Query Form -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h4 class="mb-3 text-primary">Ask a Loan Query</h4>

                <form method="POST">
                    <div class="mb-3">
                        <label for="topic" class="form-label">Topic</label>
                        <input type="text" name="topic" id="topic" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="General">General</option>
                            <option value="Eligibility">Eligibility</option>
                            <option value="Repayment">Repayment</option>
                            <option value="Interest Rate">Interest Rate</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea name="message" id="message" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Submit Query</button>
                </form>
            </div>
        </div>

        <!-- Right Column - Previous Queries -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h5 class="mb-3">Your Previous Queries</h5>

                <?php if (empty($queries)): ?>
                    <p class="text-muted">No queries submitted yet.</p>
                <?php else: ?>
                    <?php foreach ($queries as $q): ?>
                        <div class="query-card mb-3">
                            <h6><strong><?= htmlspecialchars($q['topic']) ?></strong></h6>
                            <p><strong>Type:</strong> <?= $q['type'] ?></p>
                            <p><strong>Message:</strong> <?= htmlspecialchars($q['message']) ?></p>
                            <p><small class="text-muted">Date: <?= $q['created_at'] ?></small></p>
                            <?php if ($q['reply']): ?>
                                <div class="mt-2 text-success"><strong>Officer Reply:</strong> <?= htmlspecialchars($q['reply']) ?></div>
                            <?php else: ?>
                                <div class="mt-2 text-warning">⏳ Awaiting reply</div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2025 Loan Portal. All rights reserved.</p>
</footer>

</body>
</html>
