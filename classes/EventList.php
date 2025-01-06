<?php
    /**
     * Inclusione dei file contenenti classi necessarie per il funzionamento della classe EventList
     */
    require_once "event.php";
    require_once "FileManager.php";

    /**
     * Classe statica utilizzata per la gestione degli eventi salvati all'interno del file csv 'events.csv'
     */
    class EventList {
        /**
         * Stringa che rappresenta il nome del file csv contenente tutti gli eventi presenti
         * @var string
         */
        private const FILE = "events.csv";

        /**
         * Vettore utile per salvare tutti gli eventi storici letti dal file 'events.csv' la prima volta che si utilizza la classe
         * Permette di evitare la continua lettura del file ogni volta che si accede ad un metodo, ma di farlo soltanto la prima volta che si accede ad un metodo
         * @var array
         */
        private static $events = null;

        /**
         * Metodo privato per la lettura di tutti gli eventi storici all'interno del file 'events.csv':
         *      1- viene istanziato un vettore il quale conterrà tutti gli eventi storici
         *      1- viene effettuata la lettura di tutte le righe dal file 'events.csv'
         *      2- ciascuna riga viene divisa in campi, separando con il carattere ';' l'intera stringa
         *      3- se i campi sono corretti, viene istanziato un oggetto Event passando ciascun campo come parametro
         *      4- l'oggetto appena istanziato viene aggiunto nel vettore istanziato all'inizio
         *      5- viene restituito il vettore con tutti gli eventi storici salvati
         * @return array Vettore contenente tutti gli eventi storici validi presenti nel file 'events.csv'
         */
        private static function GetEvents() {
            //vettore per contenere tutti gli eventi storici
            $events = array();

            //chiamata del metodo GetRowFromFile per ottenere tutte le righe del file 'events.csv'
            $rows = FileManager::GetRowFromFile(self::FILE);

            //controllo sulle righe:
            //se la variabile rows è effettivamente un vettore procedo, altrimenti non faccio nulla e verrà restituito il vettore di eventi storici vuoto
            if (is_array($rows)) {

                //ciclo per scorrere ogni riga
                foreach ($rows as $row) {
                    //chiamata del metodo GetFieldsFromRow per ottenere tutte i campi di ciascuna riga
                    $fields = FileManager::GetFieldsFromRow(";", $row);
                    //controllo:
                    //se la variabile fields è un vettore e contiene 5 elementi si va avanti in quanto corretto, altrimenti si va alla riga successiva
                    if (is_array($fields) && count($fields) == 5) {
                        $event = new Event($fields[0], $fields[1], $fields[2], $fields[3], $fields[4]);
                        //inserimento dell'evento nel vettore
                        $events[] = $event;
                    }
                }
            }

            return $events;
        }

        /**
         * Metodo per ottenere tutti gli eventi storici con l'indicazione di importanza a valore 'importante'
         * @return array Vettore contenente solo e soltanto gli eventi storici importanti
         */
        public static function GetImportants() {
            //controllo della variabile statica:
            //se risulta essere null (prima volta che viene richiamato un metodo della classe) viene chiamato il metodo GetEvents per ottenere tutti gli eventi storici dal file
            if (is_null(self::$events))
                self::$events = self::GetEvents();

            //definzione e istanza del vettore il quale deve contenere gli eventi importanti
            $importants = array();
            //ciclo per scorrere ogni evento presente
            foreach (self::$events as $event) {
                //controllo:
                //se l'indicazione di importanza risulta corrispondere a 'importante', l'evento storico viene aggiunto nel vettore
                if ($event->isImportant() == "importante") {
                    $importants[] = $event;
                }
            }

            return $importants;
        }

        /**
         * Metodo per ottenere tutti gli eventi storici accaduti nell'anno passato come parametro
         * @param string $year Stringa che rappresenta l'anno del quale si cercano gli eventi storici
         * @return array Vettore contenente tutti gli eventi storici avvenuti nell'anno passato come parametro
         */
        public static function GetEventsByYear($year) {
            //controllo della variabile statica:
            //se risulta essere null (prima volta che viene richiamato un metodo della classe) viene chiamato il metodo GetEvents per ottenere tutti gli eventi storici dal file
            if (is_null(self::$events))
                self::$events = self::GetEvents();

            //definzione e istanza del vettore il quale deve contenere gli eventi accaduti nell'anno specificato come parametro
            $eventsByYear = array();
            //ciclo per scorrere ogni evento presente
            foreach (self::$events as $event) {
                $yearEvent = $event->getYears();
                //controllo:
                //se l'anno contiene il carattere ':' significa che è un'intervallo,
                //quindi viene divisa la stringa dell'intervallo e viene preso solo l'anno di inizio
                if(str_contains($yearEvent, ":")) {
                    $years = explode(":", $yearEvent);

                    if(is_array($years))
                        $yearEvent = $years[0];
                }

                //controllo:
                //se i due anni corrispondono, l'evento storico viene aggiunto nel vettore
                if ($yearEvent == $year) {
                    $eventsByYear[] = $event;
                }
            }

            return $eventsByYear;
        }

        /**
         * Metodo per ottenere l'evento storico il cui path della sua immagine corrisponde al path passato come parametro
         * @param string $path Stringa che rappresenta il path dell'immagine dell'evento che si sta cercando
         * @return Event|null:
         *      1) Event se viene trovata una corrispondenza con il path passato
         *      2) Null nel caso in cui non venga trovata alcuna corrispondenza
         */
        public static function GetEventByImagePath($path) {
            //controllo della variabile statica:
            //se risulta essere null (prima volta che viene richiamato un metodo della classe) viene chiamato il metodo GetEvents per ottenere tutti gli eventi storici dal file
            if (is_null(self::$events))
                self::$events = self::GetEvents();

            //ciclo per scorrere ogni evento presente
            foreach (self::$events as $event) {
                //controllo:
                //se i due path corrispondo, viene restituito l'evento storico
                if ($event->getImage() == $path) {
                    return $event;
                }
            }

            //viene restituito null nel caso in cui non venga trovato alcun evento il quale path corrisponda con quello passato
            return null;
        }

        /**
         * Metodo per ordinare gli eventi storici in ordine cronologico
         * @param array $array Vettore che rappresenta il vettore di eventi storici da ordinare
         * @return array Vettore contenente gli eventi storici in ordine cronologico
         */
        public static function OrderEventsByYear($array) {
            //vettore temporaneo per salvare gli eventi
            $tmp = array();
            foreach ($array as $e) {
                $tmpEvent = $e;
                if (str_contains($e->getYears(), ":")) {
                    $years = explode(":", $e->getYears());
                    $year = $years[0];
                    $tmpEvent = new Event($e->getName(), $e->getDescription(), $year, $e->getImage(), $e->isImportant());
                }

                //inserimento dell'evento nel vettore sul quale verrà effettuato l'ordinamento
                $tmp[] = $tmpEvent;
            }

            $n = count($tmp);
            //ciclo esterno per iterare più volte il vettore
            for ($i = 0; $i < $n - 1; $i++) {
                //ciclo interno per confrontare gli elementi adiacenti
                for ($j = 0; $j < $n - 1 - $i; $j++) {
                    //controllo:
                    //se l'elemento corrente è maggiore di quello successivo, vengono scambiati
                    if ($tmp[$j]->getYears() > $tmp[$j + 1]->getYears()) {
                        $temp = $tmp[$j];
                        $tmp[$j] = $tmp[$j + 1];
                        $tmp[$j + 1] = $temp;
                    }
                }
            }
            
            return $tmp;
        }

        /**
         * Metodo per ottenere l'indice nel vettore di un evento in base al suo nome
         * @param string $name Stringa che rappresenta il nome dell'evento
         * @return int Indice del vettore corrispondente all'evento col nome passato per parametro
         */
        public static function GetEventIndexByName($name) {
            //controllo della variabile statica:
            //se risulta essere null (prima volta che viene richiamato un metodo della classe) viene chiamato il metodo GetEvents per ottenere tutti gli eventi storici dal file
            if (is_null(self::$events))
                self::$events = self::GetEvents();

            //ciclo per scorrere tutti gli eventi
            for ($i = 0; $i < count(self::$events); $i++) {
                //controllo:
                //se il nome passato per parametro corrisponde al nome dell'evento, viene restituito l'indice nel vettore dell'evento
                if (self::$events[$i]->getName() == $name) {
                    return $i;
                }
            }
            
            //viene restituito -1 nel caso in cui non venga trovato alcun evento il quale nome corrisponda con quello passato
            return -1;
        }

        /**
         * Metodo per ottenere l'evento che si trova nell'indice passato all'interno del vettore
         * @param int $index Variabile numerica int che rappresenta l'indice nel vettore
         * @return Event|null: 
         *      1) Event se ne è presente nella posizione cercata nel vettore
         *      2) Null se l'indice non è valido 
         */
        public static function GetEventByIndex($index) {
            //controllo della variabile statica:
            //se risulta essere null (prima volta che viene richiamato un metodo della classe) viene chiamato il metodo GetEvents per ottenere tutti gli eventi storici dal file
            if (is_null(self::$events))
                self::$events = self::GetEvents();

            if ($index >= 0 && $index < count(self::$events)) {
                return self::$events[$index];
            } else {
                //viene restituito null nel caso in cui non venga l'indice passato non sia valido
                //non valido se l'indice è minore di 0 oppure se l'indice corrisponde o è maggiore rispetto al numero di eventi
                return null;
            }
        }
    }
?>