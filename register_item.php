<?php
// register_item.php - process form before any output to allow header redirects
include_once 'connection.php';

// initialize variables for form fields
$item = [
    'id' => '',
    'name' => '',
    'description' => '',
    'quantity' => '',
    'item_type' => '',
    'department' => '',
    'date' => ''
];
$edit_mode = false;

// if editing an existing item, load the record
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $eid = intval($_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM items WHERE id=$eid");
    if ($res && $row = mysqli_fetch_assoc($res)) {
        $item = $row;
    }
}

// handle POST (insert or update)
$show_success = false;
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize incoming values
    $id = mysqli_real_escape_string($conn, $_POST['txtid']);
    $name = mysqli_real_escape_string($conn, $_POST['txtname']);
    $description = mysqli_real_escape_string($conn, $_POST['txtdescription']);
    $quantity = mysqli_real_escape_string($conn, $_POST['txtquantity']);
    $item_type = mysqli_real_escape_string($conn, $_POST['txtitem_type']);
    $department = mysqli_real_escape_string($conn, $_POST['txtdepartment']);
    $date = mysqli_real_escape_string($conn, $_POST['txtdate']);

    if (!empty($_POST['original_id'])) {
        // update existing row (do not change primary key)
        $orig = intval($_POST['original_id']);
        mysqli_query($conn, "UPDATE items SET name='$name', description='$description', quantity='$quantity', item_type='$item_type', department='$department', date='$date' WHERE id=$orig");
        $success_message = 'Item updated successfully!';
    } else {
        // insert new item (let DB assign id via AUTO_INCREMENT)
        mysqli_query($conn, "INSERT INTO items (name,description,quantity,item_type,department,date) VALUES ('$name','$description','$quantity','$item_type','$department','$date')");
        $success_message = 'Item registered successfully!';
    }
    $show_success = true;
}

// load departments for select box
$dept_result = mysqli_query($conn, "SELECT * FROM departments ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SCC Inventory System - <?= $edit_mode ? 'Edit Item' : 'Register Item' ?></title>

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
        <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4"><?= $edit_mode ? 'Edit Item' : 'Register Item' ?></h1>
                            </div>
                            <form class="user" method="post" action="register_item.php">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="Item ID" name="txtid" value="<?= htmlspecialchars($item['id']) ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName"
                                            placeholder="Item Name" name="txtname" value="<?= htmlspecialchars($item['name']) ?>"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Description" name="txtdescription" value="<?= htmlspecialchars($item['description']) ?>">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Quantity" name="txtquantity" value="<?= htmlspecialchars($item['quantity']) ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="Item Type" name="txtitem_type" value="<?= htmlspecialchars($item['item_type']) ?>">
                                    </div>

                                </div>
                                            <select name="txtdepartment" class="form-control form-control-user" >
                                                <option value="">Please Select</option>
                                                <?php if ($dept_result): ?>
                                                    <?php while ($d = mysqli_fetch_assoc($dept_result)): ?>
                                                        <option value="<?= htmlspecialchars($d['name']) ?>" <?= $d['name'] === $item['department'] ? 'selected' : '' ?>><?= htmlspecialchars($d['name']) ?></option>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>
                                            </select>
                                
                                <div class="form-group">
                                    <input type="date" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="date" name="txtdate" value="<?= htmlspecialchars($item['date']) ?>">
                                </div>
                                <?php if ($edit_mode): ?>
                                    <input type="hidden" name="original_id" value="<?= htmlspecialchars($item['id']) ?>">
                                <?php endif; ?>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    <?= $edit_mode ? 'Update Item' : 'Register Item' ?>
                                </button>
                            </form>

                            <!-- Success Popup Modal -->
                            <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header bg-success text-white border-0">
                                            <h5 class="modal-title" id="successLabel">Success!</h5>
                                        </div>
                                        <div class="modal-body text-center py-4">
                                            <i class="fas fa-check-circle text-success" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                            <p style="font-size: 1.1rem; margin-top: 1rem;">
                                                <?= $success_message ?>
                                            </p>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button class="btn btn-success btn-block" type="button" id="closeModalBtn">OK</button>
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

    <!-- Success Popup Script -->
    <script>
        <?php if ($show_success): ?>
            // Show success modal on page load
            document.addEventListener('DOMContentLoaded', function() {
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            });

            // Redirect to item_list.php when OK button is clicked
            document.getElementById('closeModalBtn').addEventListener('click', function() {
                window.location.href = 'item_list.php';
            });
        <?php endif; ?>
    </script>

</body>

</html>