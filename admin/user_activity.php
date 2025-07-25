<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "User Activity";
$is_admin = true;
require '../includes/header.php';

// Fetch activity logs
$conn = getConnection();
$query = "SELECT al.*, u.username 
          FROM activity_logs al
          LEFT JOIN users u ON al.user_id = u.id
          ORDER BY al.timestamp DESC 
          LIMIT 100";
$result = $conn->query($query);
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">User Activity Log</h1>
                <a href="reports.php" class="btn btn-primary">View Reports</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($activity = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= formatDate($activity['timestamp']) ?></td>
                                <td><?= $activity['username'] ?? 'Guest' ?></td>
                                <td>
                                    <span class="badge badge-<?= 
                                        $activity['action'] == 'Login' ? 'success' : 
                                        ($activity['action'] == 'Logout' ? 'info' : 
                                        ($activity['action'] == 'Booking' ? 'warning' : 'primary')) 
                                    ?>">
                                        <?= $activity['action'] ?>
                                    </span>
                                </td>
                                <td><?= $activity['description'] ?></td>
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
