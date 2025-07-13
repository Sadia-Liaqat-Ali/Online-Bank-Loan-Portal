<?php
session_start();
include '../dbconnection.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM loan_types WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-loan.php?status=deleted");
    exit();
}

// Fetch all loan types
$stmt = $conn->prepare("SELECT * FROM loan_types ORDER BY created_at DESC");
$stmt->execute();
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Loan Types</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .content { margin-left: 260px; padding: 30px; }
    body { background: linear-gradient(to right, #e0eafc, #cfdef3); }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="content">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Loan Types</h2>
    <a href="add-loan.php" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i> Add New Loan Type
    </a>
  </div>

  <div id="statusAlert"></div>

  <table class="table table-bordered table-striped align-middle bg-white shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Loan Type</th>
        <th>Rate</th>
        <th>Term</th>
        <th>Amount Range</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php if (count($loans) > 0): ?>
      <?php foreach ($loans as $loan): ?>
        <tr>
          <td><?= $loan['id'] ?></td>
          <td><?= htmlspecialchars($loan['name']) ?></td>
          <td><?= htmlspecialchars($loan['loan_category']) ?></td>
          <td><?= $loan['interest_rate'] ?>%</td>
          <td><?= $loan['term_months'] ?> mo</td>
          <td>PKR <?= number_format($loan['min_amount']) ?> - <?= number_format($loan['max_amount']) ?></td>
          <td>
            <a href="edit-loan.php?id=<?= $loan['id'] ?>" class="btn btn-warning btn-sm me-1">
              <i class="fas fa-edit"></i>
            </a>
            <a href="?delete=<?= $loan['id'] ?>" onclick="return confirm('Are you sure you want to delete this loan type?')" class="btn btn-danger btn-sm">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="8" class="text-center text-muted">No loan types found.</td>
      </tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
const urlParams = new URLSearchParams(window.location.search);
const status = urlParams.get('status');
if (status) {
  let msg = '', cls = 'success';

  if (status === 'added') msg = '✅ Loan type added successfully.';
  else if (status === 'edited') msg = '✏️ Loan type updated successfully.';
  else if (status === 'deleted') {
    msg = '🗑️ Loan type deleted successfully.';
    cls = 'danger';
  }

  document.getElementById('statusAlert').innerHTML = `
    <div class="alert alert-${cls} alert-dismissible fade show" role="alert">
      ${msg}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `;

  setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) alert.classList.remove('show');
  }, 4000);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
