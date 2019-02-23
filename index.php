<?php
    include "db.php";
    $version = "0.0.3-ALPHA";

    $errPair = testDb();
    $errDesc = htmlspecialchars($errPair->err . ': ' . $errPair->errDesc);
    $dbOk = $errPair->isOk();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styling/index.css" />
    <meta charset="UTF-8">
    <title>CalThing</title>
</head>
<body>
    <nav>
        <h4>CalThing v<?php echo $version ?></h4>
        <h5>
            <?php echo $dbOk
                ?'<span style="color: green;">✓</span> DB Check Successful'
                :"<a href='#' style='color: #e52' onclick='alert(`$errDesc`)'>DB Check Failed</a>" ?>
        </h5>
    </nav>
    <div class="flex-row-container flex-center">
        <div class="container">
            <p><?php if ($dbOk) echo fetchEvent(2)->res->name ?></p>
        </div>
    </div>
</body>
</html>
