<?php
//1needs to check input subID and department against submitted data, might eventually want to check it against received data as well
//2if subID and dep match,needs to create ALECVID from received date
//3needs to post ALECVID to submitted, post ALECVID, SubID, rec date from form to received data
//4needs to copy vendorID and id from submitted to received
//5needs to change received column for ALECVID to true in submitted table
//1SELECT * FROM alecsitetest.submitted WHERE (submission_id = $subid AND department = $dep);
//2php, handle form
//3UPDATE alecsitetest.submitted SET alecvid = $vid, received_date = $recdate, received = 1 WHERE (submission_id = $subid);
//4see if you actually need to do this, i don't think so
//NOTE $recdate needs to be in format '20220602'
//INSERT INTO alecsitetest.received (alecvid,received_date,department) VALUES ('$alecvid','$date',$dep);

//"SELECT alecvid FROM alecsitetest.submitted WHERE (submitted.alecvid NOT IN (SELECT alecvid FROM alecsitetest.received));";

session_start();

if (isset($_SESSION["user_id"])) {

    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM users
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $db = require __DIR__ . "/database.php";
 $subid = $_POST['subID'];
 $date = $_POST['date'];
 $dep = $_POST['dep'];
 $existsSQL = "SELECT * FROM alecsitetest.submitted WHERE (submitted.submission_id NOT IN (SELECT submission_id FROM alecsitetest.received) AND (submitted.submission_id = '$subid'  AND department = '$dep'));";
 $exists = $db->query($existsSQL);
 $exists = $exists->fetch_assoc();

 //$exists = $exists->fetch_assoc();
 if(!is_null($exists)) {
     //create ALECVID, start by creating string for date
     $alecvid = "ALECVID";
     $date = explode("-",$date);
     $date = implode("",$date);
     $alecvid = $alecvid . $date;
     if ($dep == '7') {
         $depstring = '04';
     }
     elseif ($dep == '9') {
         $depstring = '05';
     }
     elseif ($dep == '72' || $dep == '87') {
         $depstring = '03';
     }
     else {
         $depstring = '02';
     }

     $subnum = substr($subid,6,5);

     $alecvid = $alecvid . "01" . $depstring . $subnum;
     $inputSQL = "UPDATE alecsitetest.submitted SET alecvid = '$alecvid', received_date = '$date', received = 1 WHERE (submission_id = '$subid');";
     echo $inputSQL;
     $db->query($inputSQL);
     $receiveSQL = "INSERT INTO alecsitetest.received (alecvid,received_date,department,submission_id) VALUES ('$alecvid','$date',$dep,'$subid');";
     $db->query($receiveSQL);
     $_SESSION['alecvidrec'] = $alecvid;
     header('location:received.php');
 }
 else {
     echo "Incorrect Submission ID or Department";
 }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Receiving</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/test.css">
</head>
<body style = "background-image: url(/images/ALECScreen.png)">
<h1  class = "head"style = "text-align: center">AUBURN UNIVERSITY ALEC PROGRAM</h1>

<?php if (isset($user)): ?>

    <h3 style = "position: fixed; top: 0; right: 20px;">Hello <?= htmlspecialchars($user["username"]) ?>.</h3>
    <!DOCTYPE html>

    <head>
        <title>Received</title>
        <meta charset="UTF-8">
        <link rel="stylesheet"
              href="/css/test.css">
    </head>





    </form>
    <style>

    </style>

    <form method="post">
    <div class="test">
        <h1 style = "text-align: center">Received Form</h1>
            <div class = "subID">
                <p>
                    <h4 style = "text-align: center">Submission ID</h4>
                    <input type="text" id="subID" name="subID">
                </p>
            </div>
            <div class = "dep">
                <h4 style = "text-align: center">Department</h4>
                <select id="dep" name="dep">
                    <option value="7">7</option>
                    <option value="9">9</option>
                    <option value="14">14</option>
                    <option value="17">17</option>
                    <option value="20">20</option>
                    <option value="22">22</option>
                    <option value="71">71</option>
                    <option value="72">72</option>
                    <option value="74">74</option>
                    <option value="87">87</option>
                </select>
            </div>

            <div class = "date">
                <h4 style = "text-align: center">Receive Date
                    <input type="date" id="date" name = "date">
                </h4>
            </div>
        <button>
            Submit
        </button>
    </div>

    </form>
    <img class = "home" src = "/images/house.png" style = "height: 50px; position: fixed; bottom: 5%; right: 10%" onclick="window.location='home.php'">

<?php else: ?>

    <p><a href="login.php">Log in</a></p>

<?php endif; ?>

</body>

</html>










