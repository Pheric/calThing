<?php
    function getConn() {
        $dbHost = "35.188.71.139";
        $dbPort = "3306";
        $dbUser = "calThing";
        $dbPass = "jumpingTacos!!";
        $dbName = "activitiesPost";

        $dbConn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
        if ($dbConn->connect_error) {
            die("Connection failed: " . $dbConn->connect_error);
        }

        return $dbConn;
    }

    function testDb() {
        $err = "";

        $conn = getConn();
        if (!$conn->multi_query("
          CREATE TABLE IF NOT EXISTS test (f VARCHAR(20));
          INSERT INTO test (f) VALUES ('testing...');
          DROP TABLE test;
        ")) {
            $err = $conn->error;
        }

        return $err;
    }
?>