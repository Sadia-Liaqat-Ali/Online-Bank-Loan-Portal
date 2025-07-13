<?php
session_start();
include '../dbconnection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login.php");
    exit();
}
if (isset($_SESSION['repayment_message'])) {
    echo '
    <div class="alert alert-success text-center d-flex align-items-center justify-content-center" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> ' . $_SESSION['repayment_message'] . '
    </div>';
    unset($_SESSION['repayment_message']);
}

if (!isset($_GET['id'])) {
    echo "Invalid application ID.";
    exit();
}

$applicationId = intval($_GET['id']);
$userId = $_SESSION['customer_id'];

$stmt = $conn->prepare(
    "SELECT la.amount, lt.interest_rate, lt.term_months, lt.name AS loan_type
     FROM loan_applications la
     JOIN loan_types lt ON la.loan_type_id = lt.id
     WHERE la.id = ? AND la.user_id = ? AND la.status = 'Approved'"
);
$stmt->execute([$applicationId, $userId]);
$app = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$app) {
    echo "Application not found or not approved.";
    exit();
}

$amount        = $app['amount'];
$rate          = $app['interest_rate'];
$termMonths    = $app['term_months'];
$loanTypeName  = htmlspecialchars($app['loan_type']);

$totalAmount      = $amount + ($amount * $rate / 100);
$installmentCount = ceil($termMonths / 2);
$installmentAmt   = round($totalAmount / $installmentCount, 2);

$installments = [];
$startDate    = new DateTime();
for ($i = 1; $i <= $installmentCount; $i++) {
    $due = (clone $startDate)->modify("+" . ($i * 2) . " months")->format('Y-m-d');
    $installments[] = [
        'no'       => $i,
        'amount'   => number_format($installmentAmt, 2),
        'due_date' => $due
    ];
}
usort($installments, fn($a, $b) => strcmp($b['due_date'], $a['due_date']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loan Distribution Report</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: linear-gradient(to right, #e0eafc, #cfdef3); font-family: 'Segoe UI', sans-serif; }
    .main-content { margin-left: 250px; padding: 40px; }
    .card { border-radius: 12px; box-shadow: 0 6px 12px rgba(0,0,0,0.1); }
    .card-header { background-color: #5D88BB; color: white; }
    .table th, .table td { vertical-align: middle; }
    .form-control-sm, .form-select-sm { border-radius: 8px; }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
  <!-- New Info Banner -->
<div class="alert alert-danger d-flex align-items-center mb-4" role="alert">    <i class="bi bi-info-circle-fill fs-3 me-2"></i>
    <div>
      Your loan has been approved! You can choose to pay in installments or settle the full amount at once. 
      Once submitted, our officers will review your payment proof promptly.
    </div>
  </div>

  <div class="container">
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="mb-0">Your Loan Distribution Report</h4>
      </div>
      <div class="card-body">
        <p><strong>Loan Type:</strong> <?= $loanTypeName ?></p>
        <p><strong>Requested Amount:</strong> Rs. <?= number_format($amount, 2) ?></p>
        <p><strong>Interest Rate:</strong> <?= $rate ?>%</p>
        <p><strong>Total Amount to Repay:</strong> Rs. <?= number_format($totalAmount, 2) ?></p>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Installment Schedule (Every 2 Months)</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered table-hover text-center mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Installment Amount (PKR)</th>
                <th>Due Date</th>
                <th>Action</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($installments as $ins): ?>
                <tr>
                  <td><?= $ins['no'] ?></td>
                  <td>Rs. <?= $ins['amount'] ?></td>
                  <td><?= $ins['due_date'] ?></td>
                  <td>
                    <button type="button" class="btn btn-sm btn-primary repay-btn"
                            data-bs-toggle="modal" data-bs-target="#repayModal"
                            data-installment="<?= $ins['no'] ?>"
                            data-amount="<?= $ins['amount'] ?>"
                            data-duedate="<?= $ins['due_date'] ?>">
                      Repay
                    </button>
                  </td>
                  <?php
                  $status = 'Active';
                  $color  = 'text-danger';
                  $check = $conn->prepare("SELECT status FROM repayments WHERE application_id = ? AND installment_no = ?");
                  $check->execute([$applicationId, $ins['no']]);
                  $repayment = $check->fetch(PDO::FETCH_ASSOC);
                  if ($repayment) {
                      $status = $repayment['status'];
                      switch (strtolower($status)) {
                          case 'completed': $color = 'text-success'; break;
                          case 'in review': $color = 'text-warning'; break;
                          case 'rejected':  $color = 'text-danger'; break;
                          default:          $color = 'text-primary';
                      }
                  }
                  ?>
                  <td id="status-<?= $ins['no'] ?>" class="<?= $color ?>">
                    <strong><?= htmlspecialchars($status) ?></strong>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="repayModal" tabindex="-1" aria-labelledby="repayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="repayForm" method="POST" enctype="multipart/form-data" action="repayment.php">
        <div class="modal-header">
          <h5 class="modal-title" id="repayModalLabel">Submit Repayment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="application_id" value="<?= $applicationId ?>">
          <input type="hidden" name="installment_no" id="installment_no">
          <input type="hidden" name="due_date" id="due_date">
          <input type="hidden" name="amount" id="repay_amount">

          <div class="mb-3">
            <label for="method" class="form-label">Repayment Method</label>
            <select name="method" id="method" class="form-select" required>
              <option value="">-- Select Method --</option>
              <option value="Bank Transfer">Bank Transfer</option>
              <option value="Debit/Credit Card">Debit/Credit Card</option>
              <option value="Easypaisa">Easypaisa</option>
              <option value="JazzCash">JazzCash</option>
              <option value="UPaisa">UPaisa</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="proof" class="form-label">Upload Proof of Payment</label>
            <input type="file" name="proof" id="proof" class="form-control" accept="image/*" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Submit Repayment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.querySelectorAll('.repay-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('installment_no').value = btn.dataset.installment;
      document.getElementById('due_date').value = btn.dataset.duedate;
      document.getElementById('repay_amount').value = btn.dataset.amount.replace(/Rs.\s*/, '');
      document.getElementById('repayModalLabel').textContent =
        `Submit Repayment - Installment #${btn.dataset.installment}`;
    });
  });

  document.getElementById('repayForm').addEventListener('submit', function() {
    const no = this.installment_no.value;
    document.getElementById(`status-${no}`).textContent = 'In Review';
  });
</script>
</body>
</html>
