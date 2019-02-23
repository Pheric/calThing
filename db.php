<?php
    include "errPair.php";
    include "post.php";

    const PREPARE_FAILURE = 2;
    const EXEC_FAILURE = 3;
    const EMPTY_RESPONSE = 4;

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

        $conn->close();
        return $err;
    }

    function fetchEvent($id) {
        $conn = getConn();

        $stmt = $conn->prepare('SELECT categoryId, eventName, eventDescription, eventLocation, eventTime FROM events WHERE eventId = ? LIMIT 1');
        if (!$stmt) {
            // Something happened with the prepared statement, maybe a column name is wrong?
            return new ErrPair(PREPARE_FAILURE, "fetchEvent($id) returning false", null);
        }
        if (!$stmt->bind_param('d', $id))
            return new ErrPair(PREPARE_FAILURE, "fetchEvent($id) param bind failed: $stmt->error", null);
        if (!$stmt->execute())
            return new ErrPair(EXEC_FAILURE, "fetchEvent($id) execution failed: $stmt->error", null);
        $res = $stmt->get_result()->fetch_assoc();
        if ($res == null || count($res) < 1) {
            return new ErrPair(EMPTY_RESPONSE, "too few rows in result set", null);
        }

        $ret = new Post($id, $res['categoryId'], $res['eventName'], $res['eventDescription'], $res['eventLocation'], $res['eventTime']);

        $stmt->close();
        $conn->close();

        return new ErrPair(0, null, $ret);
    }