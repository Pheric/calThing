<?php
    class Event {
        var $id, $catId, $name, $description, $location, $time;

        public function __construct($id, $catId, $name, $description, $location, $time) {
            $this->id = $id;
            $this->catId = $catId;
            $this->name = $name;
            $this->description = $description;
            $this->location = $location;
            $this->time = $time;
        }

        public function getCategory($categories) {
            foreach ($categories as $c)
                if ($c->id === $this->catId) return $c;

            return null;
        }
    }

    class Category {
        var $id, $name;

        public function __construct($id, $name) {
            $this->id = $id;
            $this->name = $name;
        }
    }

    class Post {
        var $id, $timestamp, $poster;
        var $events;

        public function __construct($id, $timestamp, $poster, $events) {
            $this->id = $id;
            $this->timestamp = $timestamp;
            $this->poster = $poster;
            $this->events = $events;
        }
    }