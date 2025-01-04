<?php 
    class Event {
        private $name;
        private $description;
        private $date;
        private $image;
        private $isImportant;

        public function __construct($name, $description, $date, $image, $isImportant) {
            $this->name = $name;
            $this->description = $description;
            $this->date = $date;
            $this->image = $image;            
            $this->isImportant = $isImportant;            
        }

        public function getName() {
            return $this->name;
        }

        public function getDescription() {
            return $this->description;
        }

        public function getDate() {
            return $this->date;
        }

        public function setDate($date) {
            $this->date = $date;
        }

        public function getImage() {
            return $this->image;
        }

        public function isImportant() {
            return $this->isImportant;
        }

        public function toCSV() {
            return $this->name . ";" . $this->description . ";" . $this->date . ";" . $this->image .";" . $this->isImportant;
        }
    }
?>