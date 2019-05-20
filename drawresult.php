<?php
/**
 * Created by PhpStorm.
 * User: mahesh
 * Date: 8/3/19
 * Time: 9:49 PM
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('connection.php');
if(!$db){
    echo "Connection failed";
}
if(isset($_POST['dateSubmit'])){
    $date = $_POST['date'];
}
else {
    $date = date('Y-m-d');
}
$sql = "SELECT * FROM lotto WHERE datetime LIKE '%$date%'";
$result = mysqli_query($db,$sql);

?>
    <html>
    <head>
<?php require_once('header.php');?>
        <style>
            th {
                text-align: center;
            }
            table {
                text-align: center;
            }
            /*body {
                background-color: #f0e5b4;
            }
            .container{
                background-color: #f0e5b4;
                min-height: 600px;
            }*/
            .table{
                background-color: #ffffff;
            }
            
        </style>
    </head>
    <body ng-app="myApp1" ng-controller="myController1">
    <div class="container">
        <h2>History</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="date" name="date" ng-model="dateString">
        <button type="submit" name="dateSubmit">Submit</button>
    </form>
        <p><strong>Date : </strong> <?php  $datedata = new DateTime($date);
        echo $datedata->format('d-m-Y');
        ?></p>
<table class="table table-bordered">
    <thead>
        <th>Time</th>
        <th>Navratna</th>
        <th>Mahalaxmi</th>
        <th>Megalotto</th>
        <th>Starlotto</th>
    </thead>
    <?php while($row = mysqli_fetch_array($result)){ 
        $n = $row['navratan'];
        $ml = $row['mahalaxmi'];
        $s = $row['starlotto'];
        $m = $row['megalotto'];
        if($n<10)
        $n = "0".$n;
        if($ml<10)
        $ml = "0".$ml;
        if($s<10)
        $s = "0".$s;
        if($m<10)
        $m = "0".$m;
    ?>
    <tr>
        <td><?php
            echo date('d-m-Y , h:i A', strtotime($row['datetime'])); ?></td>
        <td><?php echo $n; ?></td>
        <td><?php echo $ml; ?></td>
        <td><?php echo $m; ?></td>
        <td><?php echo $s; ?></td>
    </tr>
    <?php } ?>
</table>
    </div>
    <div class="footer">
        Copyright @ 2015 All rights are reserved.
    </div>
    </body>
    <script>
        var app = angular.module("myApp1",[]);
        app.controller("myController1",function ($scope, dateFilter) {


        });
        </script>