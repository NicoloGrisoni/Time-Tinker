<?php 
    class User {
        private $username;
        private $password;

        public function __construct($username, $password) {
            $this->username = $username;
            $this->password = $password;
        }

        public function getUsername() {
            return $this->username;
        }

        public function doLogin($username, $password) {
            if ($this->username == $username && $this->password == $password) {
                return true;
            }
            return false;
        }

        public function toCSV() {
            return $this->username . ";" . $this->password;
        }
    }
?>