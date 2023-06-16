<?php
    $dbHost = "localhost";
    $dbDatabase = "whsakila2022";
    $dbUser = "root";
    $dbPasswrod = "";

    $mysqli = mysqli_connect($dbHost, $dbUser, $dbPasswrod, $dbDatabase);

    //QUERY CHART PERTAMA

    //query untuk tahu SUM(Amount) semuanya
    $sql = "SELECT sum(amount) as tot from fakta_pendapatan";
    $tot = mysqli_query($mysqli,$sql);
    $tot_amount = mysqli_fetch_row($tot);

    //echo $tot_amount[0];

    //query untuk ambil penjualan berdasarkan kategori, query sudah dimodifikasi
    //ditambahkan label variabel DATA. (teknik gak jelas :D)

	$sql = "SELECT concat('name:',f.kategori) as name, concat('y:', sum(fp.amount)*100/" . $tot_amount[0] .") as y, concat('drilldown:', f.kategori) as drilldown
            FROM film f
            JOIN fakta_pendapatan fp ON (f.film_id = fp.film_id)
            GROUP BY name
            ORDER BY y DESC"   ;         
            //echo $sql;
    $all_kat = mysqli_query($mysqli,$sql);
    
    while($row = mysqli_fetch_all($all_kat)) {
        $data[] = $row;
    }
    

    $json_all_kat = json_encode($data);
    
    //CHART KEDUA (DRILL DOWN)

    //query untuk tahu SUM(Amount) semua kategori
    $sql = "SELECT f.kategori kategori, sum(fp.amount) as tot_kat
            FROM fakta_pendapatan fp
            JOIN film f ON (f.film_id = fp.film_id)
            GROUP BY kategori";
    $hasil_kat = mysqli_query($mysqli,$sql);

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
    $sql = "SELECT f.kategori kategori, 
            t.bulan as bulan, 
            sum(fp.amount) as pendapatan_kat
            FROM film f
            JOIN fakta_pendapatan fp ON (f.film_id = fp.film_id)
            JOIN time t ON (t.time_id = fp.time_id)
            GROUP BY kategori, bulan";
    $det_kat = mysqli_query($mysqli,$sql);
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
                $string_data .= '{name:"' . $a[0] . '", id:"' . $a[0]    . '", data: [';
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

<html>
<head>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<link rel="stylesheet" href="/drilldown.css"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js">
    
</script>

</head>
<body >
<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
       
    </p>
</figure>



<script type="text/javascript">
// Create the chart
Highcharts.chart('container', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Persentase Nilai Penjualan (WH Sakila) - Semua Kategori'
    },
    subtitle: {
        text: 'Klik di potongan kue untuk melihat detil nilai penjualan kategori berdasarkan bulan'
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



<br>
<iframe name="mondrian" src="http://localhost:8080/mondrian/index.html" style="height:100% ;width:100%; border:none; text-align:center;">
</iframe>
<br>
<!--
<object data="http://localhost:7474/browser" width="600" height="400">
    <embed src="http://localhost:7474/browser" width="600" height="400"> </embed>
    Error: Embedded data could not be displayed.
</object>

<iframe name="neo4j" src="http://localhost:7474" style="height:100% ;width:100%; border:none; align-content:center;">
</iframe>
-->

</body>
</html>