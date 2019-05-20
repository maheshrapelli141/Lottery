<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('connection.php');
if(!$db){
    echo "Connection failed";
}

if(isset($_GET['id']) && isset($_GET['delete'])){
    if($_GET['delete']=="true") {
        $id = $_GET['id'];
        $sqlDelete = "DELETE FROM lotto_calender WHERE id = $id";
        $deleteResult = mysqli_query($db,$sqlDelete);
        if($deleteResult){
            header('Location: ManageCalender.php');
        } else {
            $failure = "Failed to delete";
        }
    }
}

if(isset($_POST['add_calender'])){
    $date = $_POST['date'];
    $subject = "";
    $failure = "";
    $success = "";

    if(!empty($_POST['subject'])){
        $subject = $_POST['subject'];
    }
    if(empty($date)){
        $failure = "Empty date Field";
    }

    $sqlFetchData = "SELECT * FROM lotto_calender WHERE date LIKE '$date'";
    $fetchResult = mysqli_query($db,$sqlFetchData);
    if(mysqli_num_rows($fetchResult)>0){
        $failure = "Date is already set";
    } else {
        $sqlInsertData = "INSERT INTO lotto_calender(date,subject) VALUES('$date','$subject')";
        $insertResult = mysqli_query($db,$sqlInsertData);
        if($insertResult){
            $success = "Date is set";
        } else {
            $failure = "Date is not set";
        }
    }
}

//$sqlFetchAllData = "SELECT * FROM lotto_calender WHERE date >= CURDATE()";
$sqlFetchAllData = "SELECT * FROM lotto_calender";
$resultFetchALlResult = mysqli_query($db,$sqlFetchAllData);
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
        <h2>GMML Holidays  :</h2>
        <div class="panel panel-default">
            <div class="panel-body"><h4>Add Close Day :</h4><hr>
                <?php
                if(isset($_POST['add_calender']) && !empty($success)){
                    echo '<div class="alert alert-success alert-dismissible">
                              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong>Status : </strong> '.$success.'
                            </div>';
                }
                if(isset($_POST['add_calender']) && !empty($failure)){
                    echo '<div class="alert alert-danger alert-dismissible">
                              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong>Status : </strong> '.$failure.'
                            </div>';
                }
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label>Date :</label>
                    <input type="date" name="date" class="form-control">
                    <label>Subject :</label>
                    <input type="text" name="subject" class="form-control"><br>
                    <button type="submit" class="btn btn-primary" name="add_calender">Add</button>
                </form>
            </div>
        </div>
        <table class="table">
            <thead>
            <th>Date</th>
            <th>Subject</th>
            <th>Delete</th>
            </thead>
            <?php
            if(mysqli_num_rows($resultFetchALlResult)>0) {
                while ($row = mysqli_fetch_array($resultFetchALlResult)) {
                    echo "<tr>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['subject'] . "</td>";
                    echo "<td><a href='ManageCalender.php?id=" . $row['id'] . "&delete=true' style='color: red' onclick=\"return confirm('Are you sure?')\"><span class='glyphicon glyphicon-trash'></span></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td>No data found</td>";
                echo "<td>No data found</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
