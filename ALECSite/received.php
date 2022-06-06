<?php
session_start();
$alecvid = $_SESSION['alecvidrec'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Received</title>
    <link rel = "stylesheet" href = "css/test.css">
</head>
<body style = "background-image: url(/images/ALECScreen.png)">
<div>
<h1 class = "head">AUBURN UNIVERSITY ALEC PROGRAM</h1>
<h4>Successfully Received. Your ALECVID for this Submission is <?php echo $alecvid; ?> </h4>
<button onclick = "window.location = 'receiving.php'">Receive Another</button>
</div>
<img class = "home" src = "/images/house.png" style = "height: 50px; position: absolute; bottom: 5%; right: 10%" onclick="window.location='home.php'">
</body>
</html>