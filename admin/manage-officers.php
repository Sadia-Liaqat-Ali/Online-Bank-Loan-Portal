<?php
session_start();
include '../dbconnection.php';

// ✅ DELETE officer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND role = 'Loan Officer'");
    $stmt->execute([$id]);
    $_SESSION['success'] = "✅ Loan officer deleted successfully.";
    header("Location: manage-loanofficers.php");
    exit();
}

// ✅ UPDATE officer
$update_msg = '';
if (isset($_POST['update'])) {
    $id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE user_id = ? AND role = 'Loan Officer'");
    if ($stmt->execute([$name, $email, $password, $id])) {
        $_SESSION['success'] = "✅ Officer updated successfully.";
    } else {
        $_SESSION['error'] = "❌ Failed to update officer.";
    }
}

// Fetch all loan officers
$stmt = $conn->prepare("SELECT * FROM users WHERE role = 'Loan Officer' ORDER BY created_at DESC");
$stmt->execute();
$officers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Loan Officers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        .main-content { margin-left: 250px; padding: 30px; }
        body { background: linear-gradient(to right, #e0eafc, #cfdef3); }
        table th, table td { vertical-align: middle !important; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h3 class="mb-4 text-primary">👥 Manage Loan Officers</h3>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (count($officers) === 0): ?>
        <div class="alert alert-info">No loan officers found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($officers as $o): ?>
                        <tr>
                            <form method="POST">
                                <td><?= $o['user_id'] ?>
                                    <input type="hidden" name="user_id" value="<?= $o['user_id'] ?>">
                                </td>
                                <td><input type="text" name="name" value="<?= htmlspecialchars($o['name']) ?>" class="form-control" required></td>
                                <td><input type="email" name="email" value="<?= htmlspecialchars($o['email']) ?>" class="form-control" required></td>
                                <td><input type="text" name="password" value="<?= htmlspecialchars($o['password']) ?>" class="form-control" required></td>
                                <td>
                                    <button name="update" class="btn btn-sm btn-primary">              <i class="fas fa-edit"></i>
</button>
                                    <a href="?delete=<?= $o['user_id'] ?>" onclick="return confirm('Are you sure you want to delete this loan officer?')" class="btn btn-sm btn-danger">
                                         <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
