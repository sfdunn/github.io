<?php
//needs to show different buttons depending on access levels
session_start();
$user = $_SESSION["user_id"];
$db = require __DIR__ . "/database.php";

$info = $db->query("SELECT access FROM users WHERE id = '$user'");
$rows = array();
while ($row = $info->fetch_assoc()) {
    $rows[] = $row;
}
$access = $rows[0]["access"];

$final = json_encode($access);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>ALEC HOME PAGE</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="/css/test.css">
        <title>Home</title>
    </head>
    <body style =
          "background-image:url(/images/ALECScreen.png);
        background-repeat:no-repeat;
        background-size:cover;">
        <script>
            function test() {
                let access = <?php echo $final;?>;
                access = JSON.stringify(access);
                let div = document.querySelector('div.stations');
                let p = document.createElement('p');
                p.append(access);
                div.append(p);
            }
        </script>
        <h1 class = "head">AUBURN UNIVERSITY ALEC PROGRAM</h1>
        <div class = "stations">
            <form action = "logout.php">
                <button style = "position: fixed; bottom: 5%; left: 10%" href="logout.php">Log out</button>
            </form>
            <?php if($access == 1): ?>
                <h3>Select Your Station</h3>
                <button onclick="window.location='receiving.php'">Receiving</button>
            <?php elseif($access == 2):?>
                <h3>Select Your Station</h3>
                <button onclick="window.location='receiving.php'">Receiving</button>
                <button  onclick="window.location='validation.php'">Validation</button>
            <?php elseif($access == 3):?>
                <h3>Select Your Station</h3>
                <button onclick="window.location='receiving.php'">Receiving</button>
                <button  onclick="window.location='validation.php'">Validation</button>
                <button  onclick="window.location='verification.php'">Verification</button>
            <?php elseif($access == 4):?>
                <h3>Select Your Station</h3>
                <button onclick="window.location='receiving.php'">Receiving</button>
                <button  onclick="window.location='validation.php'">Validation</button>
                <button  onclick="window.location='verification.php'">Verification</button>
                <button  onclick="window.location='approval.php'">Approval</button>
            <?php elseif($access == 5):?>
                <h3>Select Your Station</h3>
                <button onclick="window.location='receiving.php'">Receiving</button>
                <button  onclick="window.location='validation.php'">Validation</button>
                <button  onclick="window.location='verification.php'">Verification</button>
                <button  onclick="window.location='approval.php'">Approval</button>
                <button onclick = "window.location = 'master.php'">Stats</button>
            <?php else:?>
                <p>You're in the wrong place. Ask for help, man.</p>
            <?php endif; ?>
        </div>
    </body>
</html>
