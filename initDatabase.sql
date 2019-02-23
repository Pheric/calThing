-- create and select the database
DROP DATABASE IF EXISTS activitiesPost;
CREATE DATABASE activitiesPost;
USE activitiesPost;  -- MySQL command

-- create the tables
CREATE TABLE events (
    eventID INT NOT NULL PRIMARY KEY,
    eventName VARCHAR(255) NOT NULL,
    eventDescription VARCHAR(255) NOT NULL,
    eventLocation VARCHAR(255) NOT NULL,
    eventTime VARCHAR(255) NOT NULL
);

INSERT INTO events VALUES
(1, "testing", "BIT235", "midnight to sunrise");

-- create the users and grant priveleges to those users
GRANT SELECT, INSERT, DELETE, UPDATE
ON activitiesPost.*
TO dbUser@localhost
IDENTIFIED BY 'Password1!';

GRANT SELECT
ON events
TO dbTester@localhost
IDENTIFIED BY 'Password1!';
