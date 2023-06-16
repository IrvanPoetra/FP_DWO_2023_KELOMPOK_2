<?php include 'koneksi.php' ;

session_start();

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>WAREHOUSE ADVENTUREWORK - Dashboard</title>

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

        <?php include 'page/sidebar.php';?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <?php include 'page/topbar.php' ?>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Jumlah Customer
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                $sql = "SELECT COUNT(CustomerID) as jumlah_cust from fakta_pendapatan";
                                                $query = mysqli_query($conn, $sql);
                                                while($row2=mysqli_fetch_array($query)){
                                                    echo number_format($row2['jumlah_cust'],0,".",",");
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                           <i class="fa fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Total Pendapatan
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                $sql = "SELECT SUM(SubTotal) as jumlah_produk from fakta_pendapatan";
                                                $query = mysqli_query($conn, $sql);
                                                while($row2=mysqli_fetch_array($query)){
                                                    echo"$".number_format($row2['jumlah_produk'],0,".",",");
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                           <i class="fa fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Jumlah Pesanan
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                $sql = "SELECT SUM(OrderQty) as jumlah_pesanan from fakta_barang_tidak_diterima";
                                                $query = mysqli_query($conn, $sql);
                                                while($row2=mysqli_fetch_array($query)){
                                                    echo number_format($row2['jumlah_pesanan'],0,".",",");
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                           <i class="fa fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Jumlah Ditolak
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                $sql = "SELECT SUM(RejectedQty) as jumlah_ditolak from fakta_barang_tidak_diterima2";
                                                $query = mysqli_query($conn, $sql);
                                                while($row2=mysqli_fetch_array($query)){
                                                    echo number_format($row2['jumlah_ditolak'],0,".",",");
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                           <i class="fa fa-shopping-basket fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Sisa Stok Barang
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                $sql = "SELECT SUM(StockedQty) as jumlah_sisa from fakta_barang_tidak_diterima2";
                                                $query = mysqli_query($conn, $sql);
                                                if (mysqli_num_rows($query) > 0){
                                                    while($row2=mysqli_fetch_array($query)){
                                                        echo number_format($row2['jumlah_sisa'],0,".",",");
                                                     }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                           <i class="fa fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- <div class="row"> -->
                        <div class="col-xl-8 col-lg-7">
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" id="headingOne">
                                    <h5 class="mb-0 ">
                                        <button class="btn btn-link font-weight-bold text-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        OLAP TABEL FAKTA SALES
                                        </button>
                                    </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                    <iframe name="mondrian" src="http://localhost:8080/mondrian/index.html  " style="height:300px; width:100%; border:none; align-content:center"> </iframe>
                                    </div>
                                    </div>
                                </div>                             
                            </div>
                        </div>
                    <!-- </div> -->
                    

                    <!-- Content Row -->

                </div>

            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Data Warehouse - Adventure Works 2008</span>
                    </div>
                </div>
            </footer>

        </div>

    </div>

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
                    <a class="btn btn-primary" href="logout.php">Logout</a>
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