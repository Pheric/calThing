<?php
    class ErrPair {
        var $err;
        var $errDesc;
        var $res;

        public function __construct($err, $errDesc = "", $res = null) {
            $this->err = $err;
            $this->res = $res;
            $this->errDesc = $errDesc;
        }

        public function isOk() {
            return empty($this->err) && empty($this->errDesc);
        }

        public function __toString() {
            return "{" . $this->err . "; " . $this->errDesc . "; " . $this->res . "}";
        }
    }