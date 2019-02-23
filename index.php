<?php
    include "db.php";
    $version = "0.0.1-ALPHA";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CalThing</title>
</head>
<body>
    <h1>Welcome to CalThing v<?php echo $version ?></h1>
    <h3><?php $err = testDb(); echo empty($err)?'Test successful':$err ?></h3>
    <p><?php echo fetchEvent(2)->res->name ?></p>
</body>
</html>
