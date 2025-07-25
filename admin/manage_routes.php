<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../config/database.php';

$page_title = "Manage Routes";
$is_admin = true;
require '../includes/header.php';

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM routes WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    logActivity($_SESSION['user_id'], 'Delete Route', "Route deleted with ID: $delete_id");

    $_SESSION['message'] = "Route deleted successfully.";
    header('Location: manage_routes.php');
    exit();
}

// Fetch all routes
$conn = getConnection();
$result = $conn->query("SELECT * FROM routes ORDER BY origin, destination");
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Routes</h1>
                <a href="add_route.php" class="btn btn-primary">Add New Route</a>
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
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Distance (km)</th>
                            <th>Estimated Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($route = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $route['id'] ?></td>
                                <td><?= $route['origin'] ?></td>
                                <td><?= $route['destination'] ?></td>
                                <td><?= $route['distance_km'] ?></td>
                                <td><?= $route['estimated_time'] ?></td>
                                <td>
                                    <a href="edit_route.php?id=<?= $route['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="manage_routes.php?delete_id=<?= $route['id'] ?>" 
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
