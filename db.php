<?php
    // Welcome to the jungle.

    include "errPair.php";
    include "event.php";

    const CONNECT_FAILURE = -1;
    const PREPARE_FAILURE = 2;
    const EXEC_FAILURE = 3;
    const EMPTY_RESPONSE = 4;

    function getConn() {
        $dbHost = "35.188.71.139";
        $dbPort = "3306";
        $dbUser = "calThing";
        $dbPass = "jumpingTacos!!";
        $dbName = "activitiesPost";

        $dbConn = @new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
        if ($dbConn->connect_error) {
            return new ErrPair(-1, $dbConn->connect_error, null);
        }

        return new ErrPair(null, null, $dbConn);
    }

    function testDb() {
        $connPair = getConn();
        if (!$connPair->isOk())
            return $connPair;
        $conn = $connPair->res;

        $err = "";
        if (!$conn->multi_query("
          CREATE TABLE IF NOT EXISTS test (f VARCHAR(20));
          INSERT INTO test (f) VALUES ('testing...');
          DROP TABLE test;
        ")) {
            $conn->close();
            return new ErrPair(EXEC_FAILURE, $conn->errno . ': ' . $conn->error);
        }

        $conn->close();
        return new ErrPair(null, null, null);
    }

    // Deprecated
    /*function fetchEvent($id) {
        $connPair = getConn();
        if (!$connPair->isOk())
            return $connPair;

        $conn = $connPair->res;

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

        $ret = new Event($id, $res['categoryId'], $res['eventName'], $res['eventDescription'], $res['eventLocation'], $res['eventTime']);

        $stmt->close();
        $conn->close();

        return new ErrPair(0, null, $ret);
    }*/

    function fetchEvents($postId) {
        $connPair = getConn();
        if (!$connPair->isOk())
            return $connPair;

        $conn = $connPair->res;

        $stmt = $conn->prepare('SELECT eventId, categoryId, eventName, eventDescription, eventLocation, eventTime FROM events WHERE postId = ? ORDER BY eventTime ASC');
        if (!$stmt) {
            // Something happened with the prepared statement, maybe a column name is wrong?
            return new ErrPair(PREPARE_FAILURE, "fetchEvents($postId) returning false", null);
        }
        if (!$stmt->bind_param('d', $postId)) {
            return new ErrPair(PREPARE_FAILURE, "fetchEvents($postId) param bind failed: $stmt->error", null);
        }
        if (!$stmt->execute())
            return new ErrPair(EXEC_FAILURE, "fetchEvents($postId) execution failed: $stmt->error", null);

        $res = $stmt->get_result();
        if (empty($res))
            return new ErrPair(EXEC_FAILURE, "fetchEvents($postId) result is empty!", null);

        $ret = array();
        while (!empty($r = $res->fetch_assoc()) && $r != null) {
            array_push($ret, new Event($r['eventId'], $r['categoryId'], $r['eventName'], $r['eventDescription'], $r['eventLocation'], $r['eventTime']));
        }

        return new ErrPair(null, null, $ret);
    }

    function fetchCategories() {
        $connPair = getConn();
        if (!$connPair->isOk())
            return $connPair;

        $conn = $connPair->res;

        $stmt = $conn->prepare('SELECT categoryId, categoryName FROM categories');
        if (!$stmt) {
            // Something happened with the prepared statement, maybe a column name is wrong?
            return new ErrPair(PREPARE_FAILURE, "fetchCategories() returning false", null);
        }
        if (!$stmt->execute())
            return new ErrPair(EXEC_FAILURE, "fetchCategories() execution failed: $stmt->error", null);

        $res = $stmt->get_result();
        if (empty($res))
            return new ErrPair(EXEC_FAILURE, 'fetchCategories() result is empty!', null);

        $ret = array();
        while (!empty($r = $res->fetch_assoc()) && $r != null) {
            array_push($ret, new Category($r['categoryId'], $r['categoryName']));
        }

        return new ErrPair(null, null, $ret);
    }

    function fetchPost($id) {
        $connPair = getConn();
        if (!$connPair->isOk())
            return $connPair;

        $conn = $connPair->res;

        $stmt = $conn->prepare('SELECT postTs, poster FROM posts WHERE id = ?');
        if (!$stmt) {
            // Something happened with the prepared statement, maybe a column name is wrong?
            return new ErrPair(PREPARE_FAILURE, "fetchPost($id) returning false", null);
        }
        if (!$stmt->bind_param('d', $id))
            return new ErrPair(PREPARE_FAILURE, "fetchPost($id) param bind failed: $stmt->error", null);
        if (!$stmt->execute())
            return new ErrPair(EXEC_FAILURE, "fetchPost($id) execution failed: $stmt->error", null);
        $res = $stmt->get_result()->fetch_assoc();
        if ($res == null || count($res) < 1) {
            return new ErrPair(EMPTY_RESPONSE, "too few rows in result set", null);
        }

        $eventsErrPair = fetchEvents($id);
        if (!$eventsErrPair->isOk()) return new ErrPair($eventsErrPair->err, "Error in fetchPost($id): $eventsErrPair->errDesc", null);

        $post = new Post($id, $res['postTs'], $res['poster'], $eventsErrPair->res);

        $stmt->close();
        $conn->close();

        return new ErrPair(0, null, $post);
    }