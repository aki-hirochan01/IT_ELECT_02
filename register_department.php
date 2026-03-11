<?php
// register_department.php - process POST before any output so header() works
include_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['txtname'] ?? '');
    $description = trim($_POST['txtdescription'] ?? '');

    if ($name === '') {
        header('Location: register_department.php?status=empty');
        exit;
    }

    // Check if department name already exists
    $stmt = mysqli_prepare($conn, "SELECT id FROM departments WHERE name = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $exists = mysqli_stmt_num_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
    } else {
        // Fallback: assume not exists if prepare failed
        $exists = false;
    }

    if ($exists) {
        header('Location: register_department.php?status=exists');
        exit;
    }

    // Insert new department using prepared statement
    $ins = mysqli_prepare($conn, "INSERT INTO departments (name, description) VALUES (?, ?)");
    if ($ins) {
        mysqli_stmt_bind_param($ins, 'ss', $name, $description);
        mysqli_stmt_execute($ins);
        mysqli_stmt_close($ins);
        header('Location: register_department.php?status=created');
        exit;
    } else {
        // If prepare fails, redirect with an error status
        header('Location: register_department.php?status=error');
        exit;
    }
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

    <title>SCC Inventory System - Register Department </title>

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

                            <?php
                            // Handle status messages from redirects
                            $show_modal = false;
                            $modal_message = '';
                            $modal_title = '';
                            $modal_icon_class = '';
                            $modal_header_class = '';

                            if (isset($_GET['status'])) {
                                $status = $_GET['status'];
                                $show_modal = true;
                                
                                if ($status === 'created') {
                                    $modal_title = 'Success!';
                                    $modal_message = 'Department created successfully.';
                                    $modal_icon_class = 'text-success';
                                    $modal_header_class = 'bg-success';
                                } elseif ($status === 'exists') {
                                    $modal_title = 'Warning';
                                    $modal_message = 'Department name already exists.';
                                    $modal_icon_class = 'text-warning';
                                    $modal_header_class = 'bg-warning';
                                } elseif ($status === 'empty') {
                                    $modal_title = 'Error';
                                    $modal_message = 'Department name cannot be empty.';
                                    $modal_icon_class = 'text-danger';
                                    $modal_header_class = 'bg-danger';
                                } elseif ($status === 'error') {
                                    $modal_title = 'Error';
                                    $modal_message = 'An error occurred. Please try again.';
                                    $modal_icon_class = 'text-danger';
                                    $modal_header_class = 'bg-danger';
                                }
                            }
                            ?>

                            <!-- Strip Notification -->
                            <?php if ($show_modal): ?>
                            <div class="alert <?= strpos($modal_header_class,'success')!==false ? 'alert-success' : (strpos($modal_header_class,'warning')!==false ? 'alert-warning' : 'alert-danger') ?> alert-dismissible fade show" role="alert" id="statusContainer">
                                <strong><?= $modal_title ?></strong> <?= $modal_message ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closeModalBtn">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php endif; ?>
            
                    

                    <!-- Content Row -->
        <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Register Department</h1>
                            </div>
                            <form class="user" method="post" action="register_department.php">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="Department Name" name="txtname">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName"
                                            placeholder="Description" name="txtdescription"> 
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Department
                                </button>
        
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

    <!-- Strip Notification Script -->
    <script>
        <?php if ($show_modal): ?>
            document.getElementById('closeModalBtn').addEventListener('click', function() {
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        <?php endif; ?>
    </script>

</body>

</html>