<?php
//needs to show all the approval data that ALEC wants to see
//needs to post notes and status to approved table, change approved in submitted data to true

////3UPDATE alecsitetest.submitted SET received = 1 WHERE (alecvid = $alecvidapp);
session_start();
$alecvidapp = $_SESSION['alecvidapp'];
$alecvid = json_encode($alecvidapp);
//echo $alecvid;
$db = require __DIR__ . "/database.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $notes = $_POST["appnotes"];
    $pf = $_POST["apppf"];
    $insertSQL = "INSERT INTO alecsitetest.approved (alecvid,notes,pf) VALUES ('$alecvidapp','$notes','$pf');";
    $db->query($insertSQL);
    $updateSQL = "UPDATE alecsitetest.submitted SET approved = 1, passed = '$pf' WHERE (alecvid = '$alecvidapp')";
    $db->query($updateSQL);
    header('location: approval.php');
}
//ALL INFO NEEDED: SELECT submitted.vendor_id, submitted.alecvid, submitted.received_date, validated.spf, validated.reason, validated.notes, validated.vpf, verified.notes, verified.pf FROM alecsitetest.submitted LEFT JOIN alecsitetest.validated ON submitted.alecvid = validated.alecvid LEFT JOIN alecsitetest.verified ON submitted.alecvid = verified.alecvid WHERE submitted.alecvid = '$alecvidapp';
$sql = "SELECT submitted.vendor_id, submitted.alecvid, submitted.received_date, validated.spf, validated.reason, validated.notes, validated.vpf, verified.notes, verified.pf FROM alecsitetest.submitted LEFT JOIN alecsitetest.validated ON submitted.alecvid = validated.alecvid LEFT JOIN alecsitetest.verified ON submitted.alecvid = verified.alecvid WHERE submitted.alecvid = '$alecvidapp';";

$info = $db->query($sql);
$rows = array();
while ($row = $info->fetch_assoc()) {
    $rows[] = $row;
}
$final = json_encode($rows);
//echo $final;
?>
<!DOCTYPE html>
<html>
<head>
    <link rel = "stylesheet" href = "css/test.css">
    <title>Approval</title>
</head>
<body style = "background-image: url(/images/ALECScreen.png)">
<h1 class = "head">AUBURN UNIVERSITY ALEC PROGRAM</h1>
<div class = valverapp>
    <div id = "profile" class = "profile">
        <h1>Approval Profile</h1>
        <script>
            let div = document.getElementById("profile");
            let h3 = document.createElement('h3');
            let vendor = document.createElement('h4');
            let date = document.createElement('h4');
            let spf = document.createElement('h4');
            let alecvid = <?php echo $alecvid;?>;
            let data = <?php echo $final;?>;
            //alecvid = JSON.stringify(alecvid);
            h3.append(alecvid);
            vendor.append("VendorID: " + data[0].vendor_id);
            date.append("Received Date: " + data[0].received_date);
            spf.append("Scout Pass/Fail: " + data[0].spf);


            div.append(h3);
            div.append(vendor, date, spf);
        </script>
        <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="sn36jirjkcfedy8"></script>
        <script>
            function choose() {

                options = {

                    // Required. Called when a user selects an item in the Chooser.
                    success: function(files) {
                        let width = 800;
                        let height = 600;
                        var left = (screen.width / 2) - (width / 2);
                        var top = (screen.height / 2) - (height / 2);
                        window.open(files[0].link, "", "menubar=no,toolbar=no,status=no,width=" + width + ",height=" + height + ",top=" + top + ",left=" + left);

                    },

                    // Optional. Called when the user closes the dialog without selecting a file
                    // and does not include any parameters.
                    cancel: function() {

                    },

                    // Optional. "preview" (default) is a preview link to the document for sharing,
                    // "direct" is an expiring link to download the contents of the file. For more
                    // information about link types, see Link types below.
                    linkType: "preview", // or "direct"

                    // Optional. A value of false (default) limits selection to a single file, while
                    // true enables multiple file selection.
                    multiselect: false, // or true

                    // Optional. This is a list of file extensions. If specified, the user will
                    // only be able to select files with these extensions. You may also specify
                    // file types, such as "video" or "images" in the list. For more information,
                    // see File types below. By default, all extensions are allowed.
                    //extensions: ['.pdf', '.doc', '.docx','.png','.jpg'],

                    // Optional. A value of false (default) limits selection to files,
                    // while true allows the user to select both folders and files.
                    // You cannot specify `linkType: "direct"` when using `folderselect: true`.
                    folderselect: true, // or true

                    // Optional. A limit on the size of each file that may be selected, in bytes.
                    // If specified, the user will only be able to select files with size
                    // less than or equal to this limit.
                    // For the purposes of this option, folders have size zero.
                    //sizeLimit: 1024, // or any positive number
                };
                Dropbox.choose(options);
            }
        </script>
        <button onclick = "choose()">Choose File</button>


    </div>
    <div class = "verapp">
        <form method = "post">
            <div>
                <h4>Notes</h4>
                <input type="text" id="ALECVIDVAL" name="appnotes">
                <h4>Status</h4>
                <select id="priority" name="apppf">
                    <option value="Pass">Pass</option>
                    <option value="Fail" selected>Fail</option>
                </select>
                <input type = "submit">
            </div>
        </form>
    </div>
</div>
<img class = "home" src = "/images/house.png" style = "height: 50px; position: absolute; bottom: 5%; right: 10%" onclick="window.location='home.php'">
</body>
</html>


