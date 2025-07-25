<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Manage Transactions";
$is_admin = true;
require '../includes/header.php';

// Handle payment status update
if (isset($_POST['update_payment'])) {
    $transaction_id = $_POST['transaction_id'];
    $payment_status = $_POST['payment_status'];
    
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE transactions SET payment_status = ? WHERE id = ?");
    $stmt->bind_param("si", $payment_status, $transaction_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    
    logActivity($_SESSION['user_id'], 'Update Payment', "Payment status updated to $payment_status for transaction $transaction_id");
    
    $_SESSION['message'] = "Payment status updated successfully.";
    header('Location: manage_transactions.php');
    exit();
}

// Fetch all transactions
$conn = getConnection();
$query = "SELECT t.*, tr.payment_method, tr.payment_status, tr.payment_date,
                 p.name, p.email, s.departure_time, r.origin, r.destination, s.price
          FROM transactions tr
          JOIN tickets t ON tr.ticket_id = t.id
          JOIN passengers p ON t.passenger_id = p.id
          JOIN schedules s ON t.schedule_id = s.id
          JOIN routes r ON s.route_id = r.id
          ORDER BY tr.payment_date DESC";
$result = $conn->query($query);
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Transactions</h1>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Passenger</th>
                            <th>Route</th>
                            <th>Departure</th>
                            <th>Seat</th>
                            <th>Price</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($transaction = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $transaction['id'] ?></td>
                                <td>
                                    <?= $transaction['name'] ?><br>
                                    <small class="text-muted"><?= $transaction['email'] ?></small>
                                </td>
                                <td><?= $transaction['origin'] ?> → <?= $transaction['destination'] ?></td>
                                <td><?= formatDate($transaction['departure_time']) ?></td>
                                <td><?= $transaction['seat_number'] ?></td>
                                <td><?= formatCurrency($transaction['price']) ?></td>
                                <td><?= $transaction['payment_method'] ?></td>
                                <td>
                                    <span class="badge badge-<?= $transaction['payment_status'] == 'paid' ? 'success' : ($transaction['payment_status'] == 'pending' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($transaction['payment_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="transaction_id" value="<?= $transaction['id'] ?>">
                                        <select name="payment_status" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="pending" <?= $transaction['payment_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="paid" <?= $transaction['payment_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                                            <option value="failed" <?= $transaction['payment_status'] == 'failed' ? 'selected' : '' ?>>Failed</option>
                                        </select>
                                        <input type="hidden" name="update_payment" value="1">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

<?php
require '../includes/footer.php';
?>
