<?php
/**
 * Created by PhpStorm.
 * User: mahesh
 * Date: 9/3/19
 * Time: 11:05 AM
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('connection.php');
if(!$db){
    echo "Connection failed";
}
date_default_timezone_set('Asia/Kolkata');
//increase visitors count
$query = "SELECT count FROM lotto_visitors;";
$preCount = mysqli_query($db,$query);
$temp = array();
$temp = mysqli_fetch_array($preCount);
$newCount = $temp['count']+1;
$query1 = "UPDATE lotto_visitors SET count=".$newCount;
mysqli_query($db,$query1);

//setting current slot time
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
$dateSec = ":00";
$mainDateTime = new DateTime();
$mainDateTime->setTimestamp(strtotime($dateHr.$slot.$dateSec));
$temp = $mainDateTime->format('Y-m-d H:i:s');


$resultof = date("h:i A",strtotime($temp));

//previous 15min slot time
$preTime = new DateTime(date("Y-m-d H:i:s",strtotime($temp)));
$preTime->sub(new DateInterval('PT15M'));
$preTime->format('Y-m-d H:i:s')."<br>";
$temp;

//next 15min slot time
$timestamp2 = new DateTime(date("Y-m-d H:i:s",strtotime($temp)));
$timestamp2->add(new DateInterval('PT15M'));
$sslot = $timestamp2->format('Y-m-d H:i:s');

try{
    $mainDateTimeNext = new DateTime();
    $mainDateTimeNext->setTimestamp(strtotime($sslot));
    $nextTemp = $mainDateTimeNext->format('Y-m-d H:i:s');
    $nextResult = date("h:i A",strtotime($sslot));
}
catch (Exception $exception){
    echo $exception->getMessage();
}
$strPre = $preTime->format('Y-m-d H:i:s');

$strCurr = $mainDateTime->format('Y-m-d H:i:s');
$strNext = $timestamp2->format('Y-m-d H:i:s');
//fetching db data
$navratna = "- -";
$mahalaxmi = "- -";
$megalotto = "- -";
$starlotto = "- -";
$sql = "SELECT * FROM lotto where id = (SELECT MAX(id) FROM lotto WHERE datetime = ANY (SELECT datetime FROM lotto  WHERE datetime >= STR_TO_DATE('".$strCurr."','%Y-%m-%d %H:%i:%s') AND datetime <=STR_TO_DATE('".$strNext."','%Y-%m-%d %H:%i:%s')));";
$result = mysqli_query($db,$sql);
if(mysqli_num_rows($result)>=1) {
    if ($row = mysqli_fetch_array($result)) {
        $navratna = $row['navratan'];
        $mahalaxmi = $row['mahalaxmi'];
        $megalotto = $row['megalotto'];
        $starlotto = $row['starlotto'];

        if($navratna<10)
            $navratna = "0".$navratna;
        if($mahalaxmi<10)
            $mahalaxmi = "0".$mahalaxmi;
        if($megalotto<10)
            $megalotto = "0".$megalotto;
        if($starlotto <10)
            $starlotto = "0".$starlotto ;
    }
}


?>
<html>
<head>
    <?php require_once('header.php');?>
    <style>
        body{
            background-color: #071326;
        }
        .container{
            background-color: #f7f8f9;
            /*border: 1px solid black;*/
            min-height: 600px;
            padding: 20px;
            box-shadow: 0px 0px 15px #000000;
        }
    </style>
</head>
<body ng-app="myApp" ng-controller="myController">
    <div class="container">
        <div class="brand">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4"><h2>Mahalaxmi Star Lotto</h2></div>
                <div class="col-lg-4 col-md-4 col-sm-4"></div>
                <div class="col-lg-4 col-md-4 col-sm-4" style="text-align:right;">
                    <p><strong>info@mahalaxmistarlotto.com</strong></p>
                    <p><strong>support@mahalaxmistarlotto.com</strong></p>
                </div>
            </div>
        </div>
        <div class="main-section">
            <div class="timer">
                Website Date & Time : <?php echo date('d M Y'); ?> {{theTime}}  Time To Coupon Draw   {{timeLimit}}
            </div>
            <div class="row row-custom">
                <div class="col-lg-8 col-md-6 col-sm-12">
                    <div class="schedule">
                        <span style="">
                            Result Of:- <?php echo $resultof;?>
                        </span>
                        <span style="float: right">
                            Next Draw Time:- <?php echo  $nextResult;   ?>
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 box-container navratna">
                            <h4>Navratna</h4>
                            <div class="box box-navratna">
                                <span style="font-size: 30px;"><?php echo $navratna; ?></span>
                                <div class="triangle"></div>
                            </div>
                        </div>
                        <div class="col-sm-3  box-container mahalaxmi">
                            <h4>Mahalaxmi</h4>
                            <div class="box box-mahalaxmi">
                                <span style="font-size: 30px;"><?php echo $mahalaxmi; ?></span>
                                <div class="triangle"></div>
                            </div>
                        </div>
                        <div class="col-sm-3 box-container megalotto">
                            <h4>Megalotto</h4>
                            <div class="box box-megalotto">
                                <span style="font-size: 30px;"><?php echo $megalotto; ?></span>
                                <div class="triangle"></div>
                            </div>
                        </div>
                        <div class="col-sm-3 box-container starlotto">
                            <h4>Starlotto</h4>
                            <div class="box box-starlotto">
                                <span style="font-size: 30px;"><?php echo $starlotto; ?></span>
                                <div class="triangle"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="login-form">
                        <p>If you don't have an account</p>
                        <h4><strong>Free Registration</strong></h4><br>
                        <form>
                            <table>
                                <tr>
                                    <td><label>UserId:</label></td>
                                    <td><input type="text"></td>
                                </tr>
                                <tr><td><br></td></tr>
                                <tr>
                                    <td><label>Password:</label></td>
                                    <td><input type="password"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="checkbox"> Remember me</td>
                                </tr>
                                <tr><td><br></td></tr>
                                <tr>
                                    <td></td>
                                    <td><button type="button" class="btn btn-primary">Login</button>    <button type="button" class="btn btn-custom">Clear</button> </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
            <div class="footer">
                <br>
                Visitors  : <?php echo $newCount; ?><br>
            </div>
</body>
</html>
<script>
    var app = angular.module("myApp",[]);
    app.controller("myController",function ($scope, dateFilter, $interval,$filter,$window) {

        //previuos result time
        var d = new Date();
        // $scope.currHour = formatTimeDigits(d.getHours());
        // $scope.currMinute = formatTimeDigits(d.getMinutes());
        //
        // $scope.slot = 0;
        // if($scope.currMinute>=0 && $scope.currMinute<=15){
        //     $scope.slot = 0;
        // }
        // else if($scope.currMinute>15 && $scope.currMinute<=30){
        //     $scope.slot = 15;
        // }
        // else if($scope.currMinute>30 && $scope.currMinute<=45){
        //     $scope.slot = 30;
        // }
        // else{
        //     $scope.slot = 45;
        // }
        //next result time
        // $scope.nextHour = formatTimeDigits(d.getHours(d.setMinutes($scope.slot+15)));
        // $scope.nextMinute = formatTimeDigits(d.getMinutes(d.setMinutes($scope.slot+15)));
        // $scope.tempNext = new Date(d.getFullYear(),d.getMonth(),d.getDay(),$scope.nextHour,$scope.nextMinute);
        $scope.tempNext = new Date("<?php echo $nextTemp; ?>");
        // $scope.nextTime = $scope.tempNext.getFullYear()+"-"+$scope.tempNext.getMonth()+"-"+$scope.tempNext.getDate()+" "+$scope.tempNext.getHours()+":"+$scope.tempNext.getMinutes()+":"+$scope.tempNext.getSeconds();
        $scope.theTime = new Date().toLocaleTimeString();

        //setting resultof and nextdraw time
        // $scope.tempPrev = new Date(d.getFullYear(),d.getMonth(),d.getDay(),$scope.currHour,$scope.slot);
        // $scope.resultof = formatAMPM($scope.tempPrev);
        // $scope.nextResult = formatAMPM($scope.tempNext);
        // console.log($scope.resultof+" - "+$scope.nextResult);

        $interval(function () {
            $scope.theTime = new Date().toLocaleTimeString();
        }, 1000);

        $scope.minutes = 0;
        $scope.seconds = 0;

        // $interval(function () {
        //     $scope.minutes = getDataDiff(new Date(),new Date($scope.nextTime)).minute;
        //     $scope.seconds = getDataDiff(new Date(),new Date($scope.nextTime)).second;
        //     $scope.timeLimit = "00 "+$scope.minutes+" "+$scope.seconds;
        //     if($scope.minutes==0 && $scope.seconds==0){
        //         window.location.reload(true);
        //     }
        //     else {
        //         console.log("Page will refresh in "+$scope.timeLimit);
        //     }
        // }, 1000);

        $interval(function () {
            var td = new Date();

            console.log($scope.tempNext.getMinutes());
            if($scope.tempNext.getMinutes()==0){
                $scope.minDiff = 60-td.getMinutes();
                console.log("60-"+td.getMinutes()+"-1");
            }else {
                $scope.minDiff = $scope.tempNext.getMinutes()-td.getMinutes();
                console.log($scope.tempNext.getMinutes()+"-"+td.getMinutes()+"-1");
            }
            $scope.secDiff = (60+($scope.tempNext.getSeconds()-td.getSeconds()));
            $scope.timeLimit = "00 "+$scope.minDiff+" "+$scope.secDiff;

            console.log($scope.minDiff);
            if($scope.minDiff==0 && $scope.secDiff==1){
                $scope.$apply($scope.tempNext.setMinutes($scope.tempNext.getMinutes()+15));
                $scope.tempNext = new Date("<?php echo $nextTemp; ?>");
                window.location.reload(true);
            }
            else {
                console.log("Page will refresh in "+$scope.timeLimit);
            }
        },1000);

        $scope.currTime = new Date().toLocaleTimeString();

        $scope.HHmm = $filter('date')(new Date(), 'HH:mm');
    });

    function formatAMPM(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = formatTimeDigits(hours) + ':' + minutes + ' ' + ampm;
        return strTime;
    }
    function formatTimeDigits(n){
        return n > 9 ? "" + n: "0" + n;
    }
    function getDataDiff(startDate, endDate) {
        var diff = endDate.getTime() - startDate.getTime();
        var days = Math.floor(diff / (60 * 60 * 24 * 1000));
        var hours = Math.floor(diff / (60 * 60 * 1000)) - (days * 24);
        var minutes = Math.floor(diff / (60 * 1000)) - ((days * 24 * 60) + (hours * 60));
        var seconds = Math.floor(diff / 1000) - ((days * 24 * 60 * 60) + (hours * 60 * 60) + (minutes * 60));
        return { day: days, hour: hours, minute: minutes, second: seconds };
    }

</script>