<?php
// item_list.php - display all items and allow deletion
include_once 'connection.php';

// handle delete request before any output
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM items WHERE id = $delete_id");
    header('Location: item_list.php');
    exit;
}

// fetch items for display (support optional search via ?q=)
$q = '';
$where = '';
if (isset($_GET['q']) && trim($_GET['q']) !== '') {
    $q = mysqli_real_escape_string($conn, trim($_GET['q']));
    $where = "WHERE name LIKE '%$q%' OR description LIKE '%$q%' OR item_type LIKE '%$q%' OR department LIKE '%$q%'";
}
$items_result = mysqli_query($conn, "SELECT * FROM items $where ORDER BY id");
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SCC Inventory System - Item List</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        include 'menubar.php';
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                include 'header.php';  
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                        <div class="container-fluid">

            
                    

                    <!-- Content Row -->
                <div class="container-fluid">
                    <div class="container-fluid d-flex flex-column align-items-center">
                        <div class="row w-100 mb-2">
                            <div class="col-12 text-center">
                                <h2 class="mb-0">Item List</h2>
                            </div>
                        </div>
                        <div class="row w-100 mb-3 align-items-center">
                            <div class="col-12 col-md-4">
                                <form class="form-inline" method="get" action="item_list.php">
                                    <div class="input-group input-group-sm w-100">
                                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search..." value="<?= htmlspecialchars($q) ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary btn-sm" type="submit">Go</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-md-8 text-md-right mt-2 mt-md-0">
                                <a href="register_item.php" class="btn btn-primary">Add New Item</a>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Type</th>
                                                <th>Department</th>
                                                <th>Register Date</th>
                                                <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tbody>
                                            <?php if ($items_result && mysqli_num_rows($items_result) > 0): ?>
                                                <?php while ($row = mysqli_fetch_assoc($items_result)): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                                        <td><?= htmlspecialchars($row['description']) ?></td>
                                                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                                                        <td><?= htmlspecialchars($row['item_type']) ?></td>
                                                        <td><?= htmlspecialchars($row['department']) ?></td>
                                                        <td><?= htmlspecialchars($row['date']) ?></td>
                                                        <td>
                                                            <a href="register_item.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                            <a href="item_list.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?');">Delete</a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr><td colspan="8" class="text-center">No items found.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </tbody> 
                                </table>
                            </div>
                        </div>
                            </div>
                        </div>
                        </div>
                </div>
                    </div>
                </div>
            </div>
        </div>

    </div> 
                        <div>
                    </div>

                    
                    <div>

                </div>


                    <div>

                </div>  
                    <div>

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
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>