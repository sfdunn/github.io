<?php
//1needs to check inputted ALECVID against received and validated data
//2needs to populate the side table with the sorted, top 5 ALECVID's needing validation
//3needs to set SESSION variable ALECVID to the current vid if it's good to go
//4needs to move to next page
//2SELECT alecvid, received_date FROM alecsitetest.received WHERE (received.alecvid NOT IN (SELECT validated.alecvid FROM alecsitetest.validated)) ORDER BY received_date ASC;
//1SELECT * FROM alecsitetest.received WHERE (received.alecvid NOT IN (SELECT validated.alecvid FROM alecsitetest.validated) AND (received.alecvid = '$alecvid'));
//3$_SESSION['vid'] = $alecvid;
//4header('location:validate.php');
session_start();
$db = require __DIR__ . "/database.php";
$tableSQL = "SELECT alecvid, received_date FROM alecsitetest.received WHERE (received.alecvid NOT IN (SELECT validated.alecvid FROM alecsitetest.validated)) ORDER BY received_date ASC;";
$table = $db->query($tableSQL);
$rows = array();

if (!is_null($table)) {
    while ($row = $table->fetch_assoc()) {
        $rows[] = $row;
    }
}
$final = json_encode($rows);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $alecvid = $_POST["alecvidval"];
    $existsSQL = "SELECT * FROM alecsitetest.received WHERE (received.alecvid NOT IN (SELECT validated.alecvid FROM alecsitetest.validated) AND (received.alecvid = '$alecvid'));";
    $exists = $db->query($existsSQL);
    $exists = $exists->fetch_assoc();
    if (!is_null($exists)) {
        $_SESSION['alecvidval'] = $alecvid;
        header('location:validate.php');

    }
    else {
        echo "Incorrect ALECVID. Try Again, please.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel = "stylesheet" href = "css/test.css">
    <title>Validation</title>
</head>
<body onload = "createTable()" style = "background-image: url(/images/ALECScreen.png)">
<h1 class = "head">AUBURN UNIVERSITY ALEC PROGRAM</h1>
<div class = valverapp>
<div class = "awaiting">

    <script>
        let body = <?php echo $final; ?>;
        const div = document.querySelector('div.awaiting');
        let tableHeaders = ['ALECVID','Receive Date'];
        const createTable = () => {
            while (div.firstChild) div.removeChild(div.firstChild);
            let table = document.createElement('table');
            table.className = 'valTable';
            let tableHead = document.createElement('thead');
            tableHead.className = 'valTableHead';
            let tableHeaderRow = document.createElement('tr');
            tableHeaderRow.className = 'valTableHeaderRow';
            tableHeaders.forEach(header => {
                let vendorHeader = document.createElement('th');
                vendorHeader.innerText = header;
                tableHeaderRow.append(vendorHeader);
            })
            tableHead.append(tableHeaderRow);
            table.append(tableHead);
            let tableBody = document.createElement('tbody');
            tableBody.className = 'valTable-Body';
            //insert body of table
            for (let i = 0; i < body.length; i++) {
                let row = JSON.stringify(body[i]);
                let json = JSON.parse(row);
                let id = json.alecvid;
                let name = json.received_date;
                let tableRow = document.createElement('tr');
                tableRow.className = 'vendorTableRow';
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
            h1.append("Awaiting Validation");
            h1.className = 'awaitingval';
            div.append(h1);
            div.append(table);
        }
    </script>
</div>
<div class = "v">
<div>
    <h4>ALECVID</h4>
    <form method="post">
    <input type="text" id="ALECVIDVAL" name="alecvidval">
    <input type = "submit">
    </form>
</div>
</div>
</div>

<img class = "home" src = "/images/house.png" style = "height: 50px; position: absolute; bottom: 5%; right: 10%" onclick="window.location='home.php'">
</body>
</html>


