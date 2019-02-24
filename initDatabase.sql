DROP DATABASE IF EXISTS activitiesPost;
CREATE DATABASE activitiesPost;
USE activitiesPost;

CREATE TABLE categories (
    categoryId INT AUTO_INCREMENT,
    categoryName VARCHAR(30) NOT NULL UNIQUE,
    CONSTRAINT category_pk PRIMARY KEY (categoryId)
);

CREATE TABLE events (
    eventId INT AUTO_INCREMENT PRIMARY KEY,
    categoryId INT NOT NULL,
    eventName VARCHAR(128) NOT NULL,
    eventDescription VARCHAR(6198) NOT NULL,
    eventLocation VARCHAR(256) NOT NULL,
    eventTime VARCHAR(128) NOT NULL,
    postDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (categoryName) VALUES
("Athletics"),
("Careers"),
("Academic"),
("Clubs"),
("Announcements");

INSERT INTO events (categoryId, eventName, eventDescription, eventLocation, eventTime) VALUES
(1, "testing1", "making sure the database works1", "BIT235-1", "midnight to sunrise1"),
(1, "testing2", "making sure the database works2", "BIT235-2", "midnight to sunrise2"),
(2, "testing3", "making sure the database works3", "BIT235-3", "midnight to sunrise3"),
(3, "testing4", "making sure the database works4", "BIT235-4", "midnight to sunrise4"),
(4, "testing5", "making sure the database works5", "BIT235-5", "midnight to sunrise5"),
(5, "testing6", "making sure the database works6", "BIT235-6", "midnight to sunrise6");