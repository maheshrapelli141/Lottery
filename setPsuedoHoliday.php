<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('connection.php');
if(!$db){
    echo "Connection failed";
}

if(isset($_GET['id']) && isset($_GET['delete'])) {
$deleteQuery = "DELETE FROM lotto WHERE pseudoDataId='".$_GET['id']."'";
if(mysqli_query($db,$deleteQuery)){
    $success = "Deleted successfully";
}
else {
    array_push($errors,"Deletion failed");
}
}

if(isset($_POST['setPsuedoHoliday'])){
    //validation check
    $errors = array();
    if(empty($_POST['date'])){
        array_push($errors,"Date field is empty");
    }
    if(empty($_POST['fhours'])) array_push($errors,"From hours field is empty");
    if(empty($_POST['fminutes'])) array_push($errors,"From minutes field is empty");
    if(empty($_POST['fmedian'])) array_push($errors,"From median field is empty");
    if(empty($_POST['thours'])) array_push($errors,"To hours field is empty");
    if(empty($_POST['tminutes'])) array_push($errors,"To minutes field is empty");
    if(empty($_POST['tmedian'])) array_push($errors,"To median field is empty");

    if(count($errors)<1) {
        $date = $_POST['date'];
        $fhours = $_POST['fhours'];
        $fminutes = $_POST['fminutes'];
        $fmedian = $_POST['fmedian'];
        $thours = $_POST['thours'];
        $tminutes = $_POST['tminutes'];
        $tmedian = $_POST['tmedian'];


        if ($fmedian == "PM") {
            $fhours = $fhours + 12;
        }
        if ($tmedian == "PM") {
            $thours = $thours + 12;
        }

        $from = $date . " " . $fhours . ":" . $fminutes . ":00";
        $to = $date . " " . $thours . ":" . $tminutes . ":00";

        //check from should smaller than to time
        if ($fhours > $thours) {
            array_push($errors, "From hours should be smaller than To hours");
        } else if ($fhours == $thours && $fminutes > $tminutes) {
            array_push($errors, "From minutes should be smaller than To minutes");
        }
        else if($fhours == $thours && $fminutes == $tminutes){
            array_push($errors,"Same time is not allowed");
        }
        else {
            $fromDT = date('Y-m-d H:i:s', strtotime($from));
            $toDT = date('Y-m-d H:i:s', strtotime($to));

//             echo $checkRange = "SELECT datetime FROM lotto WHERE datetime>='$fromDT' AND datetime<='$toDT';";
//            $result = mysqli_query($db,$checkRange);
//            if(mysqli_num_rows($result)>0){
//                array_push($errors,"Slot is already set");
//            }
        }
    }

    //login execution
    if(count($errors)<1) {


        $chours = $fhours;
        $cminutes = $fminutes;
        $pseudoDataId = uniqid(date('YmdHis_'));

        //loop for generating random numbers between from and to slots of 15 minutes gap
        while ($chours <= $thours) {
            if($chours == $thours && $cminutes == $tminutes){
                break;
            }

            $curr = $date . " " . $chours . ":" . $cminutes . ":00";
            $insertDT = date('Y-m-d H:i:s', strtotime($curr));

            $navratna = rand(0, 99);
            $mahalaxmi = rand(0, 99);
            $megalotto = rand(0, 99);
            $starlotto = rand(0, 99);

            $insertData = "INSERT INTO lotto(datetime,navratan,mahalaxmi,megalotto,starlotto,pseudoDataId) VALUES('$insertDT',$navratna,$mahalaxmi,$megalotto,$starlotto,'$pseudoDataId')";
            if (mysqli_query($db, $insertData)) {
                $success = "Off day is set.";
            } else {
                array_push($errors, "Data insertion failed at database");
                break;
            }

            $cminutes = $cminutes + 15;
            if ($cminutes > 45) {
                $chours++;
                $cminutes = 0;
            }
        }
    }
}


//get all off days data
$offDaysQuery = 'SELECT pseudoDataId,MIN(datetime) AS "from",MAX(datetime) AS "to" FROM lotto WHERE pseudoDataId!=0 GROUP BY pseudoDataId ';
$offDays = mysqli_query($db,$offDaysQuery);

?>
<html>
<head>
    <?php require_once('header.php');?>
    <meta http-equiv="refresh" content="120">
    <style>
        .container{
            /*border: 1px solid black;*/
            min-height: 450px;
            padding: 20px;
            box-shadow: 0px 0px 15px #000000;
            position: relative;
        }
        .col-sm-6 {
            padding : 15px;
        }
        .btn-set {
            margin: 20px;
            float: right;
        }
        .form-box {
            margin : 10px;
        }
        .glyphicon-trash {
            text-decoration: none;
            color: red;
        }
    </style>
</head>
<body ng-app="myApp" ng-controller="myController">
    <div class="container">
        <h2>Gmml Off Days :</h2>
        <div class="panel panel-default">
            <?php
            if(isset($errors) && count($errors)>0) {
                echo '<div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>';
                      foreach ($errors as $error)
                          echo $error.'<br>';
                   echo '</div>';
            } else if(isset($success) && $success!=""){
                echo '<div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>'.$success.'
                </div>';
            }
            ?>
        <div class="form-box">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="date">Date :</label>
                <input type="date" name="date" class="form-control" value="<?php if(isset($_POST['date'])) echo $date; ?>">
                <div class="row">
                    <div class="col-sm-6">
                        <fieldset>
                            <legend>From </legend>
                            <label for="fhours">Hours :</label>
                            <select class="form-control" name="fhours">
                                <option tabindex="-1" disabled selected>Select hour</option>
                                <?php for($i=1;$i<=12;$i++) { ?>
                                    <option value="<?php echo $i;?>" <?php
                                    if(isset($_POST['fhours']) && $_POST['fhours'] == $i)
                                        echo 'selected';
                                    ?>><?php echo $i;?></option>
                                <?php } ?>
                            </select>
                            <label for="fminutes">Minutes :</label>
                            <select class="form-control" name="fminutes">
                                <option tabindex="-1" disabled selected >Select minutes</option>
                                <option value="00" <?php if(isset($_POST['fminutes']) && $_POST['fminutes']=='0') echo 'selected'; ?>>00</option>
                                <option value="15" <?php if(isset($_POST['fminutes']) && $_POST['fminutes']=='15') echo 'selected'; ?>>15</option>
                                <option value="30" <?php if(isset($_POST['fminutes']) && $_POST['fminutes']=='30') echo 'selected'; ?>>30</option>
                                <option value="45" <?php if(isset($_POST['fminutes']) && $_POST['fminutes']=='45') echo 'selected'; ?>>45</option>
                            </select>
                            <label for="fmedian">AM / PM :</label>
                            <select class="form-control" name="fmedian">
                                <option  tabindex="-1" disabled selected >Select median</option>
                                <option value="AM" <?php if(isset($_POST['fmedian']) && $_POST['fmedian']=='AM') echo 'selected'; ?>>AM</option>
                                <option value="PM" <?php if(isset($_POST['fmedian']) && $_POST['fmedian']=='PM') echo 'selected'; ?>>PM</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <fieldset>
                            <legend>To </legend>
                            <label for="thours">Hours :</label>
                            <select class="form-control" name="thours">
                                <option tabindex="-1" disabled selected>Select hour</option>
                                <?php for($i=1;$i<=12;$i++) { ?>
                                    <option value="<?php echo $i;?>" <?php
                                    if(isset($_POST['thours']) && $_POST['thours'] == $i)
                                        echo 'selected';
                                    ?>><?php echo $i;?></option>
                                <?php } ?>
                            </select>
                            <label for="tminutes">Minutes :</label>
                            <select class="form-control" name="tminutes">
                                <option tabindex="-1" disabled selected >Select minutes</option>
                                <option value="00" <?php if(isset($_POST['tminutes']) && $_POST['tminutes']=='0') echo 'selected'; ?>>00</option>
                                <option value="15" <?php if(isset($_POST['tminutes']) && $_POST['tminutes']=='15') echo 'selected'; ?>>15</option>
                                <option value="30" <?php if(isset($_POST['tminutes']) && $_POST['tminutes']=='30') echo 'selected'; ?>>30</option>
                                <option value="45" <?php if(isset($_POST['tminutes']) && $_POST['tminutes']=='45') echo 'selected'; ?>>45</option>
                            </select>
                            <label for="tmedian">AM / PM :</label>
                            <select class="form-control" name="tmedian">
                                <option  tabindex="-1" disabled selected >Select median</option>
                                <option value="AM" <?php if(isset($_POST['tmedian']) && $_POST['tmedian']=='AM') echo 'selected'; ?>>AM</option>
                                <option value="PM" <?php if(isset($_POST['tmedian']) && $_POST['tmedian']=='PM') echo 'selected'; ?>>PM</option>
                            </select>
                        </fieldset>
                    </div>
                    <button type="submit" class="btn btn-default btn-set btn-primary btn-lg" name="setPsuedoHoliday" >Set</button>
                </div>
            </form>
        </div>
        </div>

        <table class="table ">
            <thead>
                <th>Sr no.</th>
                <th>Date</th>
                <th>From</th>
                <th>To</th>
                <th>Delete</th>
            </thead>
            <?php
            $i=1;
            foreach ($offDays as $offDay){ ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo date("d-m-Y",strtotime($offDay['from'])); ?></td>
                    <td><?php echo date("h:i A",strtotime($offDay['from'])); ?></td>
                    <td><?php echo date("h:i A",strtotime($offDay['to'])); ?></td>
                    <td><a href="<?php echo $_SERVER['PHP_SELF']."?id=".$offDay['pseudoDataId']."&delete=1";?>" onclick="return confirm('Are you sure to delete?');"><span class="glyphicon glyphicon-trash"></span></a></td>
                </tr>
            <?php
            $i++;
            } ?>
        </table>
     </div>
</body>
</html>
<script>
    var app = angular.module("myApp",[]);
    app.controller("myController",function ($scope, dateFilter, $interval,$filter,$window,$http) {

        $scope.range = function(min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };
    }
</script>
