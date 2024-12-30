<?php 
    class event {
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

        public function getImage() {
            return $this->image;
        }

        public function getIsImportant() {
            return $this->isImportant;
        }

        public function toCSV()
        {
            return $this->name . ";" . $this->description . ";" . $this->date . ";" . $this->image;
        }
    }
?>