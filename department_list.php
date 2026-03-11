<?php
// department_list.php - list departments and allow deletion
include_once 'connection.php';

// handle delete request before any output
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM departments WHERE id = $delete_id");
    header('Location: department_list.php');
    exit;
}

// search handling (GET `q`) and fetch departments
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    // use prepared statement with LIKE for name and description
    $like = "%$q%";
    $stmt = mysqli_prepare($conn, "SELECT * FROM departments WHERE name LIKE ? OR description LIKE ? ORDER BY name");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $like, $like);
        mysqli_stmt_execute($stmt);
        $dept_result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    } else {
        // fallback
        $dept_result = mysqli_query($conn, "SELECT * FROM departments ORDER BY name");
    }
} else {
    $dept_result = mysqli_query($conn, "SELECT * FROM departments ORDER BY name");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SCC Inventory System - Department List</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'menubar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'header.php'; ?>
                <div class="container-fluid">

                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <h2 class="mb-0">Department List</h2>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form class="form-inline" method="get" action="department_list.php">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="q" class="form-control" placeholder="Search departments..." value="<?= htmlspecialchars($q) ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                                    </div>
                                </div>
                                <?php if ($q !== ''): ?>
                                    <a href="department_list.php" class="btn btn-link btn-sm ml-2">Clear</a>
                                <?php endif; ?>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="register_department.php" class="btn btn-primary">Add Department</a>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($dept_result && mysqli_num_rows($dept_result) > 0): ?>
                                            <?php while ($d = mysqli_fetch_assoc($dept_result)): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($d['id']) ?></td>
                                                    <td><?= htmlspecialchars($d['name']) ?></td>
                                                    <td><?= htmlspecialchars($d['description']) ?></td>
                                                    <td>
                                                        <a href="register_department.php?edit=<?= $d['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <a href="department_list.php?delete=<?= $d['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this department?');">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center">No departments found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
