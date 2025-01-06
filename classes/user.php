<?php 
    /**
     * Classe rappresentante l'utente attraverso username e password scelti da lui
     */
    class User {
        /**
         * Stringa che rappresenta lo username scelto dall'utente
         * @var string
         */
        private $username;

        /**
         * Stringa che rappresenta la password scelta dall'utente
         * @var string
         */
        private $password;

        /**
         * Costruttore della classe User
         * @param string $username Stringa che rappresenta lo username passato per parametro
         * @param string $password Stringa che rappresenta la password passata per parametro
         */
        public function __construct($username, $password) {
            $this->username = $username;
            $this->password = $password;
        }

        /**
         * Metodo per restituire lo username dell'utente
         * @return string Username dell'utente
         */
        public function getUsername() {
            return $this->username;
        }

        /**
         * Metodo per verificare se lo username e la password passati come parametro corrispondono o meno a quelli dell'utente
         * @param string $username Stringa che rappresenta lo username passato per parametro
         * @param string $password Stringa che rappresenta la password passata per parametro
         * @return bool Variabile booleana che indica se username e password dell'utente corrispondono a quelli passati
         */
        public function doLogin($username, $password) {
            if ($this->username == $username && $this->password == $password) {
                return true;
            }
            return false;
        }

        /**
         * Metodo per restituire le informazioni dell'utente in una stringa con formato csv
         * @return string Le informazioni dell'utente con formato csv
         */
        public function toCSV() {
            return $this->username . ";" . $this->password;
        }
    }
?>