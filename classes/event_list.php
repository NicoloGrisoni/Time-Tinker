<?php 
    //inclusione delle classi necessarie
    require_once "event.php";
    require_once "file_manager.php";

    class event_list {
        private const FILE = "events.csv";

        // Metodo per ottenere tutti gli eventi importanti
        public static function GetImportants() {
            $allEvents = self::GetEvents();
            $importants = array();
            foreach ($allEvents as $event) {
                if ($event->isImportant() == "importante") {
                    $importants[] = $event;
                }
            }

            return $importants;
        }

        // Metodo privato per ottenere tutti gli eventi dal file
        private static function GetEvents() {
            $events = array();
            $rows = file_manager::GetRowFromFile(self::FILE);
            if (is_array($rows)) {
                foreach ($rows as $row) {
                    $fields = file_manager::GetFieldsFromRow(";", $row);
                    if (is_array($fields) && count($fields) == 5) {
                        $event = new event($fields[0], $fields[1], $fields[2], $fields[3], $fields[4]);
                        $events[] = $event;
                    }
                }
            }

            return $events;
        }

        //metodo per ottenere tutti gli eventi dato un determinato anno
        public static function GetEventsByYear($year) {
            $events = self::GetEvents();
            $eventsByYear = array();
            foreach ($events as $event) {
                if ($event->getDate() == $year) {
                    $eventsByYear[] = $event;
                }
            }

            return $eventsByYear;
        }

        //metodo per aggiungere un evento
            //controlla se non esistre l'evento e lo inserisce
            //altrimenti non lo aggiunge
        //metodo chiamata alle API 
    }
?>