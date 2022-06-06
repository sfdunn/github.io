<?php
session_start();
//$vendorID = $_SESSION["vendorID"];
$db = require __DIR__ . "/database.php";
$vendor_id = '123456';
$info = $db->query("SELECT submission_id, alecvid, received_date, received, validated, verified, approved, department, passed FROM alecsitetest.submitted WHERE vendor_id = '$vendor_id'");

$rows = array();
while ($row = $info->fetch_assoc()) {
    $rows[] = $row;
}

$final = json_encode($rows);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Dashboard</title>
    <link rel = "stylesheet" href = "/css/test.css">


</head>
<body onload="createTable()" style =
      "background-image: url(/images/ALECScreen.png);">
    <header><h1 class = "head">AUBURN UNIVERSITY ALEC PROGRAM</h1></header>
    <div class = 'vendorDash'>

    </div>
    <script>
        let body = <?php echo $final; ?>;
        const div = document.querySelector('div.vendorDash');



        let tableHeaders = ['Submission ID','ALECVID','Receive Date','Received','Validated','Verified','Approved','Submission Department', 'Passed?'];
        const createTable = () => {
            while (div.firstChild) div.removeChild(div.firstChild);
            let table = document.createElement('table');
            table.className = 'vendorTable';
            let tableHead = document.createElement('thead');
            tableHead.className = 'vendorTableHead';
            let tableHeaderRow = document.createElement('tr');
            tableHeaderRow.className = 'vendorTableHeaderRow';
            tableHeaders.forEach(header => {
                let vendorHeader = document.createElement('th');
                vendorHeader.innerText = header;
                tableHeaderRow.append(vendorHeader);
            })
            tableHead.append(tableHeaderRow);
            table.append(tableHead);
            let tableBody = document.createElement('tbody');
            tableBody.className = 'vendorTable-Body';

            //insert body of table
            for (let i = 0; i < body.length; i++) {
                let row = JSON.stringify(body[i]);
                let json = JSON.parse(row);
                let alecvid = json.alecvid;
                let date = json.received_date;
                let received = json.received;
                let validated = json.validated;
                let verified = json.verified;
                let approved = json.approved;
                let dep = json.department;
                let passed = json.passed;
                let subid = json.submission_id;
                let tableRow = document.createElement('tr');
                tableRow.className = 'vendorTableRow';
                let avidData = document.createElement('td');
                avidData.innerText = alecvid;
                let rdData = document.createElement('td');
                rdData.innerText = date;
                let rData = document.createElement('td');
                rData.innerText = received;
                let vaData = document.createElement('td');
                vaData.innerText = validated;
                let veData = document.createElement('td');
                veData.innerText = verified;
                let appData = document.createElement('td');
                appData.innerText = approved;
                let depData = document.createElement('td');
                depData.innerText = dep;
                let passData = document.createElement('td');
                passData.innerText = passed;
                let subData = document.createElement('td');
                subData.innerText = subid;
                tableRow.append(subData,avidData,rdData,rData,vaData, veData,appData,depData, passData);
                tableBody.append(tableRow);
            }

            table.append(tableBody);
            //let p = document.createElement('p');
            //let st = JSON.stringify(body);
            //p.append(st);
            //div.append(p);
            div.append(table);

        }

    </script>
    <form action = "logout.php">
        <button style = "position: fixed; bottom: 5%; left: 10%" href="logout.php">Log out</button>
    </form>
</body>
</html>