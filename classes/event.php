<?php
    /**
     * Classe rappresentante un singolo evento storico con le seguenti informazioni:
     * 1) Nome dell'evento
     * 2) Descrizione dell'evento
     * 3) Data o intervallo di date nella quale si è svolto l'evento
     * 4) Immagine relativa all'evento storico
     * 5) Indicazione della molta importanza o meno dell'evento
     */
    class Event {
        /**
         * Stringa che rappresenta il nome dell'evento
         * @var string
         */
        private $name;

        /**
         * Stringa che rappresenta la descrizione dell'evento
         * @var string
         */
        private $description;

        /**
         * Stringa che rappresenta la data o l'intervallo di date nella quale si è svolto l'evento
         * @var string
         */
        private $years;

        /**
         * Stringa che rappresenta il percorso locale dell'immagine relativa all'evento storico
         * @var string
         */
        private $image;

        /**
         * Stringa che rappresenta l'indicazione della molta importanza o meno dell'evento
         * Questa indicazione è stata stabilita precedentemente dagli sviluppatori scrivendo nel file 'importante' o 'non importante'
         * @var string
         */
        private $isImportant;

        /**
         * Costruttore della classe Event
         * @param string $name Stringa che rappresenta il nome dell'evento passato come parametro
         * @param string $description Stringa che rappresenta la descrizione dell'evento passata come parametro
         * @param string $years Stringa che rappresenta la data o l'intervallo di date nella quale si è svolto l'evento passata come parametro
         * @param string $image Stringa che rappresenta il percorso locale dell'immagine relativa all'evento storico passato come parametro
         * @param string $isImportant Stringa che rappresenta l'indicazione della molta importanza o meno dell'evento passata come parametro
         */
        public function __construct($name, $description, $years, $image, $isImportant) {
            $this->name = $name;
            $this->description = $description;
            $this->years = $years;
            $this->image = $image;            
            $this->isImportant = $isImportant;            
        }

        /**
         * Metodo per restituire il nome dell'evento
         * @return string Nome dell'evento
         */
        public function getName() {
            return $this->name;
        }

        /**
         * Metodo per restituire la descrizione dell'evento
         * @return string Descrizione dell'evento
         */
        public function getDescription() {
            return $this->description;
        }

        /**
         * Metodo per restituire la data o l'intervallo di date di svolgimento dell'evento
         * @return string Data o l'intervallo di date di svolgimento dell'evento
         */
        public function getYears() {
            return $this->years;
        }

        /**
         * Metodo per restituire il percorso dell'immagine dell'evento
         * @return string Percorso dell'immagine dell'evento
         */
        public function getImage() {
            return $this->image;
        }

        /**
         * Metodo per restituire l'indicazione di importanza dell'evento
         * @return string Importanza stabilita dell'evento
         */
        public function isImportant() {
            return $this->isImportant;
        }

        /**
         * Metodo per restituire le informazioni dell'evento storico in una stringa con formato csv
         * @return string Le informazioni dell'evento storico con formato csv
         */
        public function toCSV() {
            return $this->name . ";" . $this->description . ";" . $this->years . ";" . $this->image .";" . $this->isImportant;
        }
    }
?>