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

        public function getRelevantCategories($categories) {
            $ret = new ArrayObject();
            foreach ($this->events as $event) {
                $c = $event->getCategory($categories);
                if ($c === null) continue;

                // I know, I know. Can't use a set, objects can't be used as keys in PHP
                $contains = false;
                foreach ($ret as $r) {
                    if ($r === $c) {
                        $contains = true;
                        break;
                    }
                }
                if (!$contains)
                    $ret->append($c);
            }

            return $ret;
        }

        public function getEventsInCategory($categories, $category) {
            $ret = new ArrayObject();

            foreach ($this->events as $event) {
                if ($event->getCategory($categories) === $category)
                    $ret->append($event);
            }

            return $ret;
        }
    }