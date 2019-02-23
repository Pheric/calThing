-- create and select the database
DROP DATABASE IF EXISTS activitiesPost;
CREATE DATABASE activitiesPost;
USE activitiesPost;  -- MySQL command

-- create the tables
CREATE TABLE events (
    eventID INT NOT NULL PRIMARY KEY,
    categoryID INT(11) NOT NULL,
    eventName VARCHAR(255) NOT NULL,
    eventDescription VARCHAR(255) NOT NULL,
    eventLocation VARCHAR(255) NOT NULL,
    eventTime VARCHAR(255) NOT NULL
);

CREATE TABLE categories (
  categoryID INT(11) NOT NULL,
  categoryName VARCHAR(255) NOT NULL,
  PRIMARY KEY (categoryID)
);

INSERT INTO categories VALUES
(1, "Athletics"),
(2, "Careers"),
(3, "Academic"),
(4, "Clubs"),
(5, "Announcements");

INSERT INTO events VALUES
(1, 1, "testing1", "making sure the database works1", "BIT235-1", "midnight to sunrise1"),
(2, 1, "testing2", "making sure the database works2", "BIT235-2", "midnight to sunrise2"),
(3, 2, "testing3", "making sure the database works3", "BIT235-3", "midnight to sunrise3"),
(4, 3, "testing4", "making sure the database works4", "BIT235-4", "midnight to sunrise4"),
(5, 4, "testing5", "making sure the database works5", "BIT235-5", "midnight to sunrise5"),
(6, 5, "testing6", "making sure the database works6", "BIT235-6", "midnight to sunrise6");

-- create the users and grant priveleges to those users
GRANT SELECT, INSERT, DELETE, UPDATE
ON activitiesPost.*
TO dbUser@localhost
IDENTIFIED BY 'Password1!';

GRANT SELECT
ON events
TO dbTester@localhost
IDENTIFIED BY 'Password1!';
