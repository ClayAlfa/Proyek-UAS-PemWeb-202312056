<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Manage Drivers";
$is_admin = true;
require '../includes/header.php';

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM drivers WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    logActivity($_SESSION['user_id'], 'Delete Driver', "Driver deleted with ID: $delete_id");

    $_SESSION['message'] = "Driver deleted successfully.";
    header('Location: manage_drivers.php');
    exit();
}

// Fetch all drivers
$conn = getConnection();
$result = $conn->query("SELECT * FROM drivers ORDER BY name");
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Drivers</h1>
                <a href="add_driver.php" class="btn btn-primary">Add New Driver</a>
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
                            <th>Name</th>
                            <th>Phone</th>
                            <th>License Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($driver = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $driver['id'] ?></td>
                                <td><?= $driver['name'] ?></td>
                                <td><?= $driver['phone'] ?></td>
                                <td><?= $driver['license_number'] ?></td>
                                <td>
                                    <a href="edit_driver.php?id=<?= $driver['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="manage_drivers.php?delete_id=<?= $driver['id'] ?>" 
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
