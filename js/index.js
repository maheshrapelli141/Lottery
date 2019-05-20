var app = angular.module("myApp",[]);
app.controller("myController",function ($scope, dateFilter, $interval,$filter,$window) {

    //previuos result time
    var d = new Date();
    $scope.currHour = formatTimeDigits(d.getHours());
    $scope.currMinute = formatTimeDigits(d.getMinutes());

    $scope.slot = 0;
    if($scope.currMinute>=0 && $scope.currMinute<=15){
        $scope.slot = 0;
    }
    else if($scope.currMinute>15 && $scope.currMinute<=30){
        $scope.slot = 15;
    }
    else if($scope.currMinute>30 && $scope.currMinute<=45){
        $scope.slot = 30;
    }
    else{
        $scope.slot = 45;
    }
    //next result time
    $scope.nextHour = formatTimeDigits(d.getHours(d.setMinutes($scope.slot+15)));
    $scope.nextMinute = formatTimeDigits(d.getMinutes(d.setMinutes($scope.slot+15)));
    $scope.tempNext = new Date(d.getFullYear(),d.getMonth(),d.getDay(),$scope.nextHour,$scope.nextMinute);
    $scope.nextTime = $scope.tempNext.getFullYear()+"-"+$scope.tempNext.getMonth()+"-"+$scope.tempNext.getDate()+" "+$scope.tempNext.getHours()+":"+$scope.tempNext.getMinutes()+":"+$scope.tempNext.getSeconds();
    $scope.theTime = new Date().toLocaleTimeString();

    //setting resultof and nextdraw time
    $scope.tempPrev = new Date(d.getFullYear(),d.getMonth(),d.getDay(),$scope.currHour,$scope.slot);
    $scope.resultof = formatAMPM($scope.tempPrev);
    $scope.nextResult = formatAMPM($scope.tempNext);
    console.log($scope.resultof+" - "+$scope.nextResult);

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

        if($scope.tempNext.getMinutes()==0){
            $scope.minDiff = 60-td.getMinutes()-1;
        }else {
            $scope.minDiff = $scope.tempNext.getMinutes()-td.getMinutes()-1;
        }
        $scope.secDiff = (60+($scope.tempNext.getSeconds()-td.getSeconds()));
        $scope.timeLimit = "00 "+$scope.minDiff+" "+$scope.secDiff;
        console.log($scope.tempNext.getMinutes()+"-"+td.getMinutes());
        if($scope.minDiff==0 && $scope.secDiff==1){
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