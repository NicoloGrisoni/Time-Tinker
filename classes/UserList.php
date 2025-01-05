<?php 
    //inclusione delle classi necessarie
    require_once "user.php";
    require_once "FileManager.php";

    class UserList {
        /**
         * Lista degli utenti presenti
         * @var array
         */
        private $utenti;

        public function __construct($file) {
            $this->utenti = $this->getUtentiFromFile($file);
        }

        /**
         * Metodo per ottenere gli utenti salvati con corrispondenti username, password e tipologia di utente per ciascuno
         * @param string $file Nome del file dal quale recuperare gli utenti presenti
         * @return array Vettore contenente gli utenti presenti
         */
        private function getUtentiFromFile($file) {
            //array di utenti
            $users = array();

            //chiamata del metodo getRowsFromFile per ottenere le righe del file letto
            $rows = FileManager::GetRowFromFile($file);

            //per ogni riga...
            foreach ($rows as $row) {
                //chiamata del metodo getFieldsOfRow per ottenere i campi di ciascuna riga
                $fields = FileManager::GetFieldsFromRow(";", $row);
                if(is_array($fields))
                {
                    //controllo la validità dei campi
                    //devono essercene 3 per essere valide, altrimenti non viene fatto nulla
                    if (count($fields) == 2) {
                        //valido --> definizione dell'utente e salvataggio di esso
                        $utente = new User($fields[0], $fields[1]);
                        array_push($users, $utente);
                    } else {
                        continue;
                    }
                }
            }

            return $users;
        }

        /**
         * Metodo per verificare se username e password passati sono validi in quanto corrispondenti a quelli di un utente presente
         * @param string $username Username da verificare
         * @param string $password Password da verificare
         * @return string Il tipo dell'utente trovato come corrispondente con username e password
         * @return null Nel caso in cui non venga trovato alcun utente con username e password corrispondenti
         */
        public function doLogin($username, $password) {
            foreach ($this->utenti as $utente) {
                //per ogni utente viene richiamato il metodo doLogin della classe Utente
                //esso verifica se username e password ad esso passate risultano corrispondenti a quelle dell'utente preso in considerazione
                if ($utente->doLogin($username, $password)) {
                    return $utente;
                }
            }
            return null;
        }

        public function checkUsername($username) {
            foreach ($this->utenti as $utente) {
                if ($utente->getUsername() == $username) {
                    return false;
                }
            }
            return true;
        }
    }
?>