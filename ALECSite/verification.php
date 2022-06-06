<?php
//needs to check inputted ALECVID against validated and verified data
//needs to populate the side table with the sorted, top 5 ALECVID's needing verification
//needs to set SESSION variable ALECVID to the current vid if it's good to go
//needs to move to next page
session_start();
$db = require __DIR__ . "/database.php";
$vidSQL = "SELECT alecvid, received_date FROM alecsitetest.submitted WHERE alecvid IN (SELECT alecvid FROM alecsitetest.validated WHERE (validated.alecvid NOT IN (SELECT alecvid FROM alecsitetest.verified))) ORDER BY received_date ASC;";
$vidtable = $db->query($vidSQL);

$vidrows = array();

if (!is_null($vidtable)) {
    while ($vidrow = $vidtable->fetch_assoc()) {
        $vidrows[] = $vidrow;
    }
}
$vidfinal = json_encode($vidrows);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $alecvid = $_POST["alecvidver"];

    $existsSQL = "SELECT * FROM alecsitetest.validated WHERE (validated.alecvid NOT IN (SELECT alecvid FROM alecsitetest.verified) AND (validated.alecvid = '$alecvid'));";
    $exists = $db->query($existsSQL);
    $exists = $exists->fetch_assoc();
    if (!is_null($exists)) {
        $_SESSION['alecvidver'] = $alecvid;
        header('location:verify.php');

    }
    else {
        echo "<div class = 'echo'>" . "Incorrect ALECVID. Try Again, please." . "</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel = "stylesheet" href = "css/test.css">
    <title>Verification</title>
</head>
<body onload = "createTable()" style = "background-image: url(/images/ALECScreen.png)">
<h1 class = "head">AUBURN UNIVERSITY ALEC PROGRAM</h1>
<div class = valverapp>
    <div class = "awaiting">

        <script>
            let vidbody = <?php echo $vidfinal; ?>;

            const div = document.querySelector('div.awaiting');
            let tableHeaders = ['ALECVID','Receive Date'];
            const createTable = () => {
                while (div.firstChild) div.removeChild(div.firstChild);
                let table = document.createElement('table');
                table.className = 'verTable';
                let tableHead = document.createElement('thead');
                tableHead.className = 'verTableHead';
                let tableHeaderRow = document.createElement('tr');
                tableHeaderRow.className = 'verTableHeaderRow';
                tableHeaders.forEach(header => {
                    let vendorHeader = document.createElement('th');
                    vendorHeader.innerText = header;
                    tableHeaderRow.append(vendorHeader);
                })
                tableHead.append(tableHeaderRow);
                table.append(tableHead);
                let tableBody = document.createElement('tbody');
                tableBody.className = 'verTable-Body';
                //insert body of table
                for (let i = 0; i < vidbody.length; i++) {
                    let row = JSON.stringify(vidbody[i]);

                    let vidjson = JSON.parse(row);

                    let id = vidjson.alecvid;
                    let name = vidjson.received_date;
                    let tableRow = document.createElement('tr');
                    tableRow.className = 'verTableRow';
                    let avidData = document.createElement('td');
                    avidData.innerText = id;
                    let rdData = document.createElement('td');
                    rdData.innerText = name;
                    tableRow.append(avidData,rdData);
                    tableBody.append(tableRow);
                }
                table.append(tableBody);
                //let p = document.createElement('p');
                //let st = JSON.stringify(body);
                //p.append(st);
                //div.append(p);
                let h1 = document.createElement('h1');
                h1.append("Awaiting Verification");
                div.append(h1);
                div.append(table);
            }
        </script>
    </div>
    <div class = "v">
        <form method = "post">
        <div>
            <h4>ALECVID</h4>
            <input type="text" id="ALECVIDVAL" name="alecvidver">
            <input type = "submit">
        </div>
        </form>
    </div>
<img class = "home" src = "/images/house.png" style = "height: 50px; position: absolute; bottom: 5%; right: 10%" onclick="window.location='home.php'">
</body>
</html>

