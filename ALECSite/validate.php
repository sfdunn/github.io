<?php
//needs to pull data using session ALECVID variable
//2needs to post form data to validated table, might need to post current date to validated
//2INSERT INTO alecsitetest.validated (alecvid,spf,reason,notes,vpf) VALUES ('$alecvidval','$spf','$reason','$notes','$vpf');
session_start();
$alecvidval = $_SESSION['alecvidval'];
$alecvid = json_encode($alecvidval);
$db = require __DIR__ . "/database.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $spf = $_POST["spf"];
    $reason = $_POST["reason"];
    $notes = $_POST["notes"];
    $vpf = $_POST["vpf"];
    $insertSQL = "INSERT INTO alecsitetest.validated (alecvid,spf,reason,notes,vpf) VALUES ('$alecvidval','$spf','$reason','$notes','$vpf');";
    $db->query($insertSQL);
    $updateSQL = "UPDATE alecsitetest.submitted SET validated = 1 WHERE (alecvid = '$alecvidval')";
    $db->query($updateSQL);
    header('location: validation.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>RFID Lab ALEC Project</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/test.css">
    <title>Validation</title>
</head>
<body style = "background-image: url(/images/ALECScreen.png)">
<h1 style = "text-align: center" class = "head">AUBURN UNIVERSITY ALEC PROGRAM</h1>
<form method = "post">
        <div id = "validate" class="validate">
            <h1 style = "text-align: center">Validation Form</h1>
            <script>
                let div = document.getElementById("validate");
                let h3 = document.createElement('h3');
                let alecvid = <?php echo $alecvid;?>;
                //alecvid = JSON.stringify(alecvid);
                h3.append(alecvid);
                div.append(h3);
            </script>
            <div class = "dep">
                <h4 style = "text-align: center">Scout Pass/Fail</h4>
                <select id="spf" name="spf">
                    <option value="Pass">Pass</option>
                    <option value="Fail" selected>Fail</option>
                </select>
            </div>
            <div class = "subID">
                <p>
                <h4 style = "text-align: center">Scout Reason</h4>
                <input type="text" id="reason" name="reason">
                </p>
            </div>
            <div class = "subID">
                <p>
                <h4 style = "text-align: center">Scout Notes</h4>
                <input type="text" id="notes" name="notes">
                </p>
            </div>

            <div class = "dep">
                <h4 style = "text-align: center">Validator Pass/Fail</h4>
                <select id="vpf" name="vpf">
                    <option value="Pass">Pass</option>
                    <option value="Fail" selected>Fail</option>
                </select>
            </div>
            <input class = "valid" type = "submit">
        </div>
</form>
    <img class = "home" src = "/images/house.png" style = "height: 50px; position: fixed; bottom: 5%; right: 10%" onclick="window.location='home.php'">
    <button style = "position: fixed; bottom: 5%; left: 10%" href="logout.html">Log out</button>



</body>

</html>
