<?php
    /**
     * Inclusione dei file contenenti classi necessarie per il funzionamento della classe UserList
     */
    require_once "user.php";
    require_once "FileManager.php";

    class UserList {
        /**
         * Stringa che rappresenta il nome del file csv contenente tutti gli utenti presenti
         * @var string
         */
        private const FILE = "users.csv";

        /**
         * Vettore nel quale vengono salvati tutti gli utenti presenti nel file
         * @var array
         */
        private $utenti;

        /**
         * Costruttore della classe UserList
         * Viene inizializzato il vettore di utenti chiamando il metodo getUtentiFromFile
         * @param string $file
         */
        public function __construct() {
            $this->utenti = $this->getUtentiFromFile();
        }

        /**
         * Metodo privato per la lettura di tutti gli utenti del file 'users.csv':
         *      1- viene istanziato un vettore il quale conterrà tutti gli utenti
         *      1- viene effettuata la lettura di tutte le righe dal file 'users.csv'
         *      2- ciascuna riga viene divisa in campi, separando con il carattere ';' l'intera stringa
         *      3- se i campi sono corretti, viene istanziato un oggetto User passando ciascun campo come parametro
         *      4- l'oggetto appena istanziato viene aggiunto nel vettore istanziato all'inizio
         *      5- viene restituito il vettore con tutti gli utenti
         * @return array Vettore contenente tutti gli utenti presenti nel file 'users.csv'
         */
        private function getUtentiFromFile() {
            //vettore per contenere tutti gli utenti
            $users = array();

            //chiamata del metodo GetRowFromFile per ottenere tutte le righe del file 'users.csv'
            $rows = FileManager::GetRowFromFile(self::FILE);

            //controllo sulle righe:
            //se la variabile rows è effettivamente un vettore procedo, altrimenti non faccio nulla e verrà restituito il vettore di eventi storici vuoto
            if (is_array($rows)) {

                //ciclo per scorrere ogni riga
                foreach ($rows as $row) {
                    //chiamata del metodo GetFieldsFromRow per ottenere tutte i campi di ciascuna riga
                    $fields = FileManager::GetFieldsFromRow(";", $row);

                    //controllo:
                    //se la variabile fields è un vettore e contiene 2 elementi si va avanti in quanto corretto, altrimenti si va alla riga successiva
                    if(is_array($fields)) {
                        if (count($fields) == 2) {
                            $utente = new User($fields[0], $fields[1]);
                            //inserimento dell'utente nel vettore
                            array_push($users, $utente);
                        } else {
                            continue;
                        }
                    }
                }
            }

            return $users;
        }

        /**
         * Metodo per controllare se username e password passati corrispondono a quelle di un utente presente
         * Utilizzato nella login per verificare se l'utente ha inserito credenziali valide o meno
         * @param string $username Stringa che rappresenta lo username passato per parametro
         * @param string $password Stringa che rappresenta la password passata per parametro
         * @return mixed
         */
        public function doLogin($username, $password) {
            foreach ($this->utenti as $utente) {
                if ($utente->doLogin($username, $password)) {
                    return $utente;
                }
            }
            return null;
        }

        /**
         * Metodo per verificare se lo username passato corrisponde già a quello di un utente presente
         * Utilizzato nella registrazione per evitare che due utenti abbiano lo stesso username
         * @param string $username Stringa che rappresenta lo username passato per parametro
         * @return bool Variabile booleana per indicare se lo username non è già presente:
         *              true --> username non presente
         *              false --> username già presente
         */
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