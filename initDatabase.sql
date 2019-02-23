-- create and select the database
DROP DATABASE IF EXISTS activitiesPost;
CREATE DATABASE activitiesPost;
USE activitiesPost;  -- MySQL command

-- create the tables
CREATE TABLE event (
    eventId INT AUTO_INCREMENT,
    categoryId INT NOT NULL,
    eventName TEXT NOT NULL,
    eventDescription TEXT NOT NULL,
    eventLocation TEXT NOT NULL,
    eventTime TEXT NOT NULL,
    CONSTRAINT event_pk PRIMARY KEY (eventId),
    CONSTRAINT cat_fk FOREIGN KEY (categoryId) REFERENCES categories(categoryId)
);

CREATE TABLE category (
  categoryId INT AUTO_INCREMENT,
  categoryName TEXT NOT NULL UNIQUE,
  CONSTRAINT category_pk PRIMARY KEY (categoryId)
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
