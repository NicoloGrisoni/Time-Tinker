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

        //metodo per aggiungere un evento
            //controlla se non esistre l'evento e lo inserisce
            //altrimenti non lo aggiunge
        //metodo chiamata alle API

        //metodo API per cercare eventi
        function searchEvents($year, $numEvents) {
            // Evento storico dall'API Ninjas
            $url_eventi = "https://api.api-ninjas.com/v1/historicalevents?year=" . $year . "&limit=" . $numEvents;

            $ch = curl_init($url_eventi);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'X-Api-Key: B9z0CO1/XmbeuILJulYrEw==PGBVRHsUU8uvkYwW'
            ));
            $risposta_eventi = curl_exec($ch);

            if(curl_errno($ch)) {
                die('Errore nella richiesta eventi: ' . curl_error($ch));
            }

            $eventi = json_decode($risposta_eventi, true);
            curl_close($ch);

            if (!empty($eventi)) {
                $evento = $eventi[0]['event']; // Primo evento
                echo "Evento selezionato: $evento\n";

                // Generare immagine con DALL·E
                $url_image = "https://api.openai.com/v1/images/generations";

                $data = array(
                    "prompt" => $evento, // Prompt basato sull'evento
                    "n" => 1,
                    "size" => "1920x1080"
                );

                $ch_img = curl_init($url_image);
                curl_setopt($ch_img, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_img, CURLOPT_POST, true);
                curl_setopt($ch_img, CURLOPT_HTTPHEADER, array(
                    'Authorization: Bearer LA_TUA_CHIAVE_API_OPENAI', // Sostituisci con la tua chiave API OpenAI
                    'Content-Type: application/json'
                ));
                curl_setopt($ch_img, CURLOPT_POSTFIELDS, json_encode($data));

                $risposta_img = curl_exec($ch_img);

                if(curl_errno($ch_img)) {
                    die('Errore nella richiesta immagine: ' . curl_error($ch_img));
                }

                $immagine = json_decode($risposta_img, true);
                curl_close($ch_img);

                if (!empty($immagine['data'])) {
                    $url_immagine = $immagine['data'][0]['url'];
                    echo "Immagine generata: $url_immagine\n";
                } else {
                    echo "Nessuna immagine generata.";
                }
            } else {
                echo "Nessun evento trovato per l'anno $year.";
            }
        }
    }
?>