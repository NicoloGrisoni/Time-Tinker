<?php 
    //inclusione delle classi necessarie
    require_once "event.php";
    require_once "FileManager.php";

    class EventList {
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
            $rows = FileManager::GetRowFromFile(self::FILE);
            if (is_array($rows)) {
                foreach ($rows as $row) {
                    $fields = FileManager::GetFieldsFromRow(";", $row);
                    if (is_array($fields) && count($fields) == 5) {
                        $event = new Event($fields[0], $fields[1], $fields[2], $fields[3], $fields[4]);
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

        //metodo per prendere l'evento dal path dell'immagine
        public static function GetEventByImagePath($path) {
            $events = self::GetEvents();
            foreach ($events as $event) {
                if ($event->getImage() == $path) {
                    return $event;
                }
            }
            return null;
        }

        //metodo per ordinare gli eventi per anno
        public static function OrderEventsByYear($array) {
            $date = array();
            foreach ($array as $e) {
                $data = $e->getDate();
                if(str_contains($e->getDate(), ":"))
                    $data = explode(":", $e->getDate());

                if(is_array($data)) {
                    $data = $data[0];
                }

                $date[] = $data;
            }

            $n = count($date);
            // Loop esterno per passare più volte nell'array
            for ($i = 0; $i < $n - 1; $i++) {
                // Loop interno per confrontare gli elementi adiacenti
                for ($j = 0; $j < $n - 1 - $i; $j++) {
                    // Se l'elemento corrente è maggiore di quello successivo, scambiali
                    if ($date[$j] > $date[$j + 1]) {
                        // Scambio
                        $temp = $array[$j];
                        $array[$j] = $array[$j + 1];
                        $array[$j + 1] = $temp;

                        $temp = $date[$j];
                        $date[$j] = $date[$j + 1];
                        $date[$j + 1] = $temp;
                    }
                }
            }
            
            return $array;
        }

        //metodo per ottenere l'indice di un evento dato il suo nome
        public static function GetEventIndexByName($name) {
            $events = self::GetEvents();
            for ($i = 0; $i < count($events); $i++) {
                if ($events[$i]->getName() == $name) {
                    return $i;
                }
            }
            return -1;
        }

        //metodo per ottenere un evento dato il suo indice
        public static function GetEventByIndex($index) {
            $events = self::GetEvents();
            if ($index >= 0 && $index < count($events)) {
                return $events[$index];
            }
            return null;
        }   

        //metodo per aggiungere un evento
            //controlla se non esistre l'evento e lo inserisce
            //altrimenti non lo aggiunge
        //metodo chiamata alle API

    }
?>