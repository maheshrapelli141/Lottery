<?php
/**
 * Created by PhpStorm.
 * User: mahesh
 * Date: 8/3/19
 * Time: 6:22 PM
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('connection.php');
if(!$db){
    echo "Connection failed";
}

if(isset($_POST['dataUpload'])){
    $navratan = $_POST['navratna'];
    $mahalaxmi = $_POST['mahalaxmi'];
    $megalotto = $_POST['megalotto'];
    $starlotto = $_POST['starlotto'];
    $timestamp = new DateTime();
    $timestamp->format('Y-m-d H:i:s');
    $minute = $timestamp->format('i');
    $slot=0;
    if($minute>=0 && $minute<=15){
        $slot=0;
    }
    elseif ($minute>15 && $minute<=30){
        $slot=15;
    }
    elseif ($minute>30 && $minute<=45){
        $slot=30;
    }
    else{
        $slot=45;
    }
    $dateHr= $timestamp->format('Y-m-d H:');
    $dateSec = $timestamp->format(':s');
//    $dateSec = ":00";
    $mainDateTime = new DateTime($dateHr.$slot.$dateSec);
    $temp = $mainDateTime->format('Y-m-d H:i:s');

    $sql = "INSERT INTO lotto(datetime,navratan,mahalaxmi,megalotto,starlotto) VALUES('$temp',$navratan,$mahalaxmi,$megalotto,$starlotto);";

    //precheck of data exist
    $now = date_format(new DateTime($temp),'Y-m-d H:i');
    $query = 'SELECT MAX(datetime) AS "datetime" FROM lotto;';
    $r = mysqli_query($db,$query);
    $topDatetime = 0;
    $r = mysqli_query($db,$query);
    if ($row = mysqli_fetch_array($r)){
        $topDatetime = $row['datetime'];
    }
    $pre = date_format(new DateTime($topDatetime),'Y-m-d H:i');
    $flag = 0;
    if(!strcmp($pre,$now)){
        $flag =0;
    }
    else {
        $flag = 1;
    }

    if($flag==1){
        $result = mysqli_query($db,$sql);
        if($result){
            $status = "Added Successfully";
        }
        else {
            $status = "Failed to upload data";
        }
    } else {
        $status = "Current Slot is already updated";
    }
}
?>

<html>
<head>
    <?php require_once('header.php');?>

    <style>
        td{
            padding: 5px;
            background-color: #ffffff;
        }
        td:hover {
            /*background-color: #eeeeee;*/
        }
        .custom-container{
            /*text-align: center;*/
        }
        .custom-container .col-md-6 {
            padding: 4%;
        }
        .custom-container .col-md-6 h3 {
            color: #ffffff;
        }
        .custom-container input {
            /*min-width: 300px;*/
        }
        .highlight {
            /*background-color: #f4f442;*/
            background-color: red;
            color : #ffffff
        }
        .col-sm-12{
            color: #ffffff;
            padding: 10px 25px;
        }
        input::-webkit-inner-spin-button,
        input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
            border-radius: 0px;
        }
    </style>
</head>
<body ng-app="myApp" ng-controller="myController">
<div class="container-fluid custom-container">
    <h4>Admin : Result  <span style="float:right;font-size:16px">Website Date & Time : <?php echo date('d M Y'); ?> {{theTime}}</span> </h4>
    <div class="row">
        <?php if(isset($status)){
            echo "<div class='alert alert-success alert-dismissible'>
  <a href='#'class='close' data-dismiss='alert' aria-label='close'>&times;</a>
  <strong>Status</strong> $status.
</div>";
        } ?>
        <div class="timer">

        </div>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="col-lg-3 col-md-3 col-sm-12 " style="background-color: red">
                <h3>Navratna</h3>
                <table class="table-bordered">
                    <tr>
                        <td ng-repeat="n in range(0,9)">0{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(10,19)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(20,29)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(30,39)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(40,49)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(50,59)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(60,69)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(70,79)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(80,89)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(90,99)">{{n}}</td>
                    </tr>
                </table><br>
                <input type="text" class="form-control"  onkeypress='validate(event)' name="navratna" maxlength="2" required>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12 "  style="background-color: blue">
                <h3>Mahalaxmi</h3>
                <table class="table-bordered">
                    <tr>
                        <td ng-repeat="n in range(0,9)">0{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(10,19)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(20,29)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(30,39)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(40,49)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(50,59)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(60,69)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(70,79)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(80,89)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(90,99)">{{n}}</td>
                    </tr>
                </table><br>
                <input type="text" class="form-control"  onkeypress='validate(event)' name="mahalaxmi" maxlength="2"  required>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12"  style="background-color: brown">
                <h3>Megalotto</h3>
                <table class="table-bordered">
                    <tr>
                        <td ng-repeat="n in range(0,9)">0{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(10,19)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(20,29)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(30,39)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(40,49)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(50,59)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(60,69)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(70,79)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(80,89)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(90,99)">{{n}}</td>
                    </tr>
                </table><br>
                <input type="text" class="form-control"  onkeypress='validate(event)' name="megalotto"  maxlength="2" required>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12 "  style="background-color: #ffa700">
                <h3>Starlotto</h3>
                <table class="table-bordered">
                    <tr>
                        <td ng-repeat="n in range(0,9)">0{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(10,19)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(20,29)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(30,39)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(40,49)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(50,59)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(60,69)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(70,79)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(80,89)">{{n}}</td></tr>
                    <tr><td ng-repeat="n in range(90,99)">{{n}}</td>
                    </tr>
                </table><br>
                <input type="text" class="form-control"  onkeypress='validate(event)' name="starlotto" maxlength="2"  required>
            </div><br><br style="clear: both"><br>
            <button type="submit" class="btn btn-lg btn-primary" name="dataUpload">Submit</button>
        </form>
    </div>
    <div style="width:50%">
        <multiple-date-picker></multiple-date-picker>
    </div>
</div>
</body>
</html>
<script>
    var app = angular.module("myApp",['multipleDatePicker']);
    app.controller("myController",function ($scope , $interval) {

        $scope.range = function(min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };

        $interval(function () {
            $scope.theTime = new Date().toLocaleTimeString();
        }, 1000);

    });
</script>
<script>
    $('document').ready(function () {
        $('td').click(function() {
            $(this).toggleClass('highlight');
        });
    });
</script>
<script>
    function validate(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }
</script>