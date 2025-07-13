<?php
session_start();
include '../dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query_id'], $_POST['reply'])) {
    $stmt = $conn->prepare("UPDATE loan_queries SET reply = ? WHERE id = ?");
    $stmt->execute([$_POST['reply'], $_POST['query_id']]);
}

// Fetch all queries with customer info
$stmt = $conn->prepare("SELECT q.*, c.name AS customer_name FROM loan_queries q 
                        JOIN customers c ON q.customer_id = c.id 
                        ORDER BY q.created_at DESC");
$stmt->execute();
$queries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    
                <!DOCTYPE html>
<html>
<head>
    <title>Customer Loan Queries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(to right, #eef2f3, #8e9eab);
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        .reply-text {
            font-weight: bold;
        }
        .form-inline-reply {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="card p-4 bg-white">
        <h3 class="text-center text-primary mb-4"><i class="fas fa-comments"></i> Customer Loan Queries</h3>

        <?php if (count($queries) === 0): ?>
            <div class="alert alert-info">No queries found.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Customer</th>
                            <th>Topic</th>
                            <th>Type</th>
                            <th>Message</th>
                            <th>Reply</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($queries as $query): ?>
                        <tr>
                            <td><?= htmlspecialchars($query['customer_name']) ?></td>
                            <td><?= htmlspecialchars($query['topic']) ?></td>
                            <td><span class="badge bg-info"><?= htmlspecialchars($query['type']) ?></span></td>
                            <td><?= htmlspecialchars($query['message']) ?></td>
                            <td>
                                <?php if ($query['reply']): ?>
                                    <span class="badge bg-success reply-text"><?= htmlspecialchars($query['reply']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">No reply yet</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$query['reply']): ?>
                                    <form method="POST" class="form-inline-reply">
                                        <input type="hidden" name="query_id" value="<?= $query['id'] ?>">
                                        <input type="text" name="reply" class="form-control form-control-sm" placeholder="Type reply..." required>
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-paper-plane"></i> Send</button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Replied</span>
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
</body>
</html>
