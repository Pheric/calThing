<?php
    class Post {
        public $id, $catId, $name, $description, $location, $time;

        public function __construct($id, $catId, $name, $description, $location, $time) {
            $this->id = $id;
            $this->catId = $catId;
            $this->name = $name;
            $this->description = $description;
            $this->location = $location;
            $this->time = $time;
        }
    }
?>