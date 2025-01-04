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

//metodo chiamata alle API
public static function getHistoricalEventFromAPI($year) {
    // Configura l'URL dell'API di Wikipedia (versione italiana)
    $baseUrl = "https://it.wikipedia.org/w/api.php";
    
    // Lista di termini storici per migliorare la ricerca
    $searchTerms = "storia OR scoperta OR invenzione OR guerra OR rivoluzione OR trattato OR evento";
    
    // Parametri della richiesta
    $params = [
        "action" => "query",
        "format" => "json",
        "generator" => "search",
        "gsrsearch" => "$year $searchTerms",  // Ricerca più generale
        "gsrlimit" => 10,  // Aumentiamo il limite per avere più risultati
        "prop" => "extracts",
        "exintro" => true,
        "explaintext" => true
    ];
    
    $url = $baseUrl . "?" . http_build_query($params);
    
    $options = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: HistoricalEventsApp/1.0 (your@email.com)\r\n"
        ]
    ];
    
    $context = stream_context_create($options);
    
    try {
        $response = file_get_contents($url, false, $context);
        
        if ($response === false) {
            throw new Exception("Errore nel recupero dei dati");
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['query']['pages']) || empty($data['query']['pages'])) {
            return [
                "success" => false,
                "message" => "Nessun evento trovato per l'anno $year"
            ];
        }
        
        // Cerchiamo l'articolo più rilevante
        $bestMatch = null;
        $bestScore = 0;
        
        foreach ($data['query']['pages'] as $page) {
            $score = 0;
            
            // Verifica se il titolo contiene l'anno
            if (strpos($page['title'], $year) !== false) {
                $score += 2;
            }
            
            // Verifica se la descrizione contiene l'anno
            if (strpos($page['extract'], $year) !== false) {
                $score += 1;
            }
            
            // Evita risultati troppo generici
            if ($page['title'] === $year || strlen($page['title']) < 5) {
                $score -= 3;
            }
            
            // Preferisci titoli più corti (spesso più specifici)
            $score -= (strlen($page['title']) / 100);
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $page;
            }
        }
        
        if ($bestMatch === null) {
            $bestMatch = reset($data['query']['pages']);
        }
        
        // Estraiamo la frase più rilevante dalla descrizione
        $description = $bestMatch['extract'];
        $sentences = explode(". ", $description);
        $relevantSentence = "";
        
        foreach ($sentences as $sentence) {
            if (strpos($sentence, $year) !== false) {
                $relevantSentence = $sentence;
                break;
            }
        }
        
        if (empty($relevantSentence)) {
            $relevantSentence = $sentences[0];
        }
        
        // Pulizia del titolo (rimuove date tra parentesi se presenti)
        $title = preg_replace('/\s*\([^)]*\)/', '', $bestMatch['title']);
        
        // Crea un nuovo oggetto Event
        $newEvent = new Event(
            $title,                          // data
            $relevantSentence,                         // nome
            $year . ".",        // descrizione
            "",                             // percorso immagine
            "no"                // importanza
        );
        
        // return [
        //     "success" => true,
        //     "event" => $newEvent
        // ];
        FileManager::InsertContent("events.csv", $newEvent->toCSV(), true);
        
    } catch (Exception $e) {
        // return [
        //     "success" => false,
        //     "message" => "Errore: " . $e->getMessage()
        // ];
    }
}
    }
?>