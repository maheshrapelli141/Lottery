<html>
<head>
    <?php require_once('header.php');?>
    <style>
        .container{
            /*border: 1px solid black;*/
            min-height: 450px;
            padding: 20px;
            box-shadow: 0px 0px 15px #000000;
        }
    </style>
</head>
<body ng-app="myApp" ng-controller="myController">
<div class="container">
    <div class="brand">
        <div class="row">
            <div class="col-lg-5 col-md-4 col-sm-4"><h2>Golden Mahalaxmi Mega Lotto</h2></div>
            <div class="col-lg-3 col-md-4 col-sm-4"></div>
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
                            Result Of:- {{values.resultof}}
                        </span>
                    <span style="float: right">
                            Next Draw Time:- {{values.nextResult}}
                        </span>
                </div>
                <div class="row">
                    <div class="col-sm-3 box-container navratna">
                        <h4>Navratna</h4>
                        <div class="box box-navratna">
                            <span style="font-size: 30px;">{{values.navaratan}}</span>
                            <div class="triangle"></div>
                        </div>
                    </div>
                    <div class="col-sm-3  box-container mahalaxmi">
                        <h4>Mahalaxmi</h4>
                        <div class="box box-mahalaxmi">
                            <span style="font-size: 30px;">{{values.mahalaxmi}}</span>
                            <div class="triangle"></div>
                        </div>
                    </div>
                    <div class="col-sm-3 box-container megalotto">
                        <h4>Megalotto</h4>
                        <div class="box box-megalotto">
                            <span style="font-size: 30px;">{{values.megalotto}}</span>
                            <div class="triangle"></div>
                        </div>
                    </div>
                    <div class="col-sm-3 box-container starlotto">
                        <h4>Starlotto</h4>
                        <div class="box box-starlotto">
                            <span style="font-size: 30px;">{{values.starlotto}}</span>
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
</div><br/>
<p style="text-align: center;color:white">You must be 18 year old to play and claim the prize. Gaming not allowed in the states where lottery is prohibited. </p>
<div class="footer">
    Visitors  : {{values.visitors}}<br>
</div>
</body>
</html>
<script>
    var app = angular.module("myApp",[]);
    app.controller("myController",function ($scope, dateFilter, $interval,$filter,$window,$http) {

        $interval(function () {
            $scope.theTime = new Date().toLocaleTimeString();
        }, 1000);


        $http({
            method:"GET",
            url:"dataFetch.php"
        }).then(function mySuccess(response) {
            $scope.values = response.data;
        },function myError(response) {
            $scope.values = response.statusText;
        });

        $interval(function () {
            var td = new Date();
            $scope.tempNext = new Date($scope.values.nextTemp);
            console.log($scope.tempNext.getMinutes());
            if($scope.tempNext.getMinutes()==0){
                $scope.minDiff = 60-td.getMinutes()-1;
                console.log("60-"+td.getMinutes()+"-1");
            }else {
                $scope.minDiff = $scope.tempNext.getMinutes()-td.getMinutes()-1;
                console.log($scope.tempNext.getMinutes()+"-"+td.getMinutes()+"-1");
            }
            $scope.secDiff = (60+($scope.tempNext.getSeconds()-td.getSeconds()));
            $scope.timeLimit = "00 "+$scope.minDiff+" "+$scope.secDiff;

            console.log($scope.minDiff);
            if($scope.minDiff==0 && $scope.secDiff==1 || $scope.minDiff<0 || $scope.minDiff>15){
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