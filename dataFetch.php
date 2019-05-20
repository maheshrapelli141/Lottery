<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

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
if($minute>=0 && $minute<15){
    $slot=0;
}
elseif ($minute>=15 && $minute<30){
    $slot=15;
}
elseif ($minute>=30 && $minute<45){
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


//check holiday
$sqlHoliday = "SELECT * FROM lotto_calender";
$holidayResult = mysqli_query($db,$sqlHoliday);
$todayHoliday = false;
while ($row = mysqli_fetch_array($holidayResult)){
    if($row['date']==date("Y-m-d")){
        $todayHoliday = true;
    }
}

$myObj->resultof = $resultof;
$myObj->nextResult = $nextResult;
$myObj->nextTemp = $nextTemp;
$myObj->visitors = $newCount;
$myObj->todayHoliday = $todayHoliday;
$myObj->navaratan =  $navratna;
$myObj->mahalaxmi = $mahalaxmi;
$myObj->megalotto = $megalotto;
$myObj->starlotto = $starlotto;

echo json_encode($myObj);
?>