<?php include 'koneksi.php';

  
    //QUERY CHART PERTAMA

    //query untuk tahu SUM(Amount) semuanya
    $sql = "SELECT SUM(fs.order_qty) AS tot from fakta_penjualan fs";
    $tot = mysqli_query($conn,$sql);
    $tot_amount = mysqli_fetch_row($tot);

    $sql = "SELECT 
    concat('name:',dl.country) AS name, 
    concat('y:',SUM(fs.order_qty)*100/" . $tot_amount[0] .") AS y, 
    concat('drilldown:',dl.country) as drilldown 
    from 
        fakta_penjualan fs
        join customerstore_address dl on fs.customeraddress_id = dl.customeraddress_id 
    group by country ";

    $all_kat = mysqli_query($conn,$sql);
    
    while($row = mysqli_fetch_all($all_kat)) {
        $data[] = $row;
    }

    $json_all_kat = json_encode($data);
    
    //CHART KEDUA (DRILL DOWN)

    //query untuk tahu SUM(Amount) semua kategori
    $sql = "select dl.country as Country, 
    SUM(fs.order_qty) AS OrderQuantity
    from 
        fakta_penjualan fs
        join customerstore_address dl on fs.customeraddress_id = dl.customeraddress_id 
    group by country";

    $hasil_kat = mysqli_query($conn,$sql);

    while($row = mysqli_fetch_all($hasil_kat)){
        $tot_all_kat[] = $row;
    }

    //print_r($tot_all_kat);
    //function untuk nyari total_per_kat 

    //echo count($tot_per_kat[0]);
    //echo $tot_per_kat[0][0][1];
    
    function cari_tot_kat($kat_dicari, $tot_all_kat){
       $counter = 0;
       // echo $tot_all_kat[0];
       while( $counter < count($tot_all_kat[0]) ){
            if($kat_dicari == $tot_all_kat[0][$counter][0]){
                $tot_kat = $tot_all_kat[0][$counter][1];
                return $tot_kat;
            }
            $counter++;        
       }
    }

    //query untuk ambil penjualan di kategori berdasarkan bulan (clean)
    $sql = "SELECT dl.country as Country, dl.province_name as Name, SUM(fs.order_qty) AS OrderQuantity 
    from fakta_penjualan fs 
    join customerstore_address dl on fs.customeraddress_id = dl.customeraddress_id 
    group by country, Name";
    $det_kat = mysqli_query($conn,$sql);
    $i = 0;
    while($row = mysqli_fetch_all($det_kat)) {
        //echo $row;
        $data_det[] = $row;
        
    }

    //print_r($data_det);

    //PERSIAPAN DATA DRILL DOWN - TEKNIK CLEAN
    $i = 0;

    //inisiasi string DATA
    $string_data = "";
    $string_data .= '{name:"' . $data_det[0][$i][0] . '", id:"' . $data_det[0][$i][0] . '", data: [';


    // echo cari_tot_kat("Action", $tot_all_kat);
    foreach($data_det[0] as $a){
        //echo cari_tot_kat($a[0], $tot_all_kat);

        if($i < count($data_det[0])-1){
            if($a[0] != $data_det[0][$i+1][0]){
                $string_data .= '["' . $a[1] . '", ' . 
                    $a[2]*100/cari_tot_kat($a[0], $tot_all_kat) . ']]},';
                $string_data .= '{name:"' . $data_det[0][$i+1][0] . '", id:"' . $data_det[0][$i+1][0]    . '", data: [';
            }
            else{
                $string_data .= '["' . $a[1] . '", ' . 
                    $a[2]*100/cari_tot_kat($a[0], $tot_all_kat) . '], ';
            }            
        }
        else{
            $string_data .= '["' . $a[1] . '", ' . 
                $a[2]*100/cari_tot_kat($a[0], $tot_all_kat). ']]}';
        }

        $i = $i+1;
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

    <title>WAREHOUSE ADVENTUREWORK - Territory</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'page/sidebar.php';?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <?php include 'page/topbar.php' ?>
                </nav>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Lokasi</h1>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Jumlah Persebaran Lokasi
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php                                           
                                                $sql = "SELECT COUNT(customeraddress_id) as location from customerstore_address";
                                                $query = mysqli_query($conn, $sql);
                                                if (mysqli_num_rows($query) > 0){
                                                    while($row2=mysqli_fetch_array($query)){
                                                        echo number_format($row2['location'],0,".",",");
                                                    }
                                                }      
                                                ?>  
                                            </div>
                                            </div>
                                        <div class="col-auto">
                                             <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                                <figure class="highcharts-figure">
                                    <div id="chart1"></div>
                                        <p class="highcharts-description">
                                        </p>
                                </figure>
                            </div>
                       <div class="container-fluid">
                                <figure class="highcharts-figure">
                                    <div id="chart2"></div>
                                        <p class="highcharts-description">
                                        </p>
                                </figure>
                            </div>     

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
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>




    <script type="text/javascript">


// Create the chart
Highcharts.chart('chart1', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Persentase Produk Terjual Berdasarkan Negara'
    },
    subtitle: {
        text: 'Klik di potongan kue untuk melihat detil nilai penjualan kategori berdasarkan kategori'
    },

    accessibility: {
        announceNewData: {
            enabled: true
        },
        point: {
            valueSuffix: '%'
        }
    },

    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    },

    series: [
        {
            name: "Pendapatan By Kategori",
            colorByPoint: true,
            data: 
                <?php 
                    //TEKNIK GAK JELAS :D

                    $datanya =  $json_all_kat; 
                    $data1 = str_replace('["','{"',$datanya) ;   
                    $data2 = str_replace('"]','"}',$data1) ;  
                    $data3 = str_replace('[[','[',$data2);
                    $data4 = str_replace(']]',']',$data3);
                    $data5 = str_replace(':','" : "',$data4);
                    $data6 = str_replace('"name"','name',$data5);
                    $data7 = str_replace('"drilldown"','drilldown',$data6);
                    $data8 = str_replace('"y"','y',$data7);
                    $data9 = str_replace('",',',',$data8);
                    $data10 = str_replace(',y','",y',$data9);
                    $data11 = str_replace(',y : "',',y : ',$data10);
                    echo $data11;
                ?>
            
        }
    ],
    drilldown: {
        series: [
            
                <?php 
                    //TEKNIK CLEAN
                    echo $string_data;

                ?>

                
            
        ]
    }
});

</script>


</body>
