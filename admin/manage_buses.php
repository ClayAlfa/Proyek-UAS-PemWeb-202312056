<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Manage Buses";
$is_admin = true;
require '../includes/header.php';

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM buses WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    logActivity($_SESSION['user_id'], 'Delete Bus', "Bus deleted with ID: $delete_id");

    $_SESSION['message'] = "Bus deleted successfully.";
    header('Location: manage_buses.php');
    exit();
}

// Fetch all buses
$conn = getConnection();
$result = $conn->query("SELECT * FROM buses");
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Buses</h1>
                <a href="add_bus.php" class="btn btn-primary">Add New Bus</a>
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
                            <th>ID</th>
                            <th>Plate Number</th>
                            <th>Brand</th>
                            <th>Seat Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($bus = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $bus['id'] ?></td>
                                <td><?= $bus['plate_number'] ?></td>
                                <td><?= $bus['brand'] ?></td>
                                <td><?= $bus['seat_count'] ?></td>
                                <td><?= $bus['status'] ?></td>
                                <td>
                                    <a href="edit_bus.php?id=<?= $bus['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="manage_buses.php?delete_id=<?= $bus['id'] ?>" 
                                       class="btn btn-danger btn-sm delete-confirm">Delete</a>
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
